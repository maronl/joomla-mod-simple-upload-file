<?php

class modSimpleFileUpload
{

    private $max_file_size;

    private $allowed_file_types;

    private $destination_dir;

    public function __construct( $params ){

        $default = array(
            'max_file_size' => 1024,
            'destination_dir' => 'uploads',
            'allowed_file_types' => array()
        );

        $params = array_merge($default, $params);

        $this->max_file_size = $params['max_file_size'];
        $this->destination_dir = $params['destination_dir'];
        $this->allowed_file_types = $params['allowed_file_types'];
    }

    public function fileUpload($file){
        if (isset($file) && $file['error'] == 0) {
            //Clean up filename to get rid of strange characters like spaces etc
            $filename = JFile::makeSafe($file['name']);

            //Set up the source and destination of the file
            $src = $file['tmp_name'];
            $dest = $this->destination_dir . DS . $filename;

            // make unique file name to not overwite existing file
            $dest = $this->makeUniqueFileName($dest);

            //check size
            $check_size = $this->checkFileSize($file);
            if($check_size){
                $res['status'] = 0;
                $res['msg'] = sprintf(JText::_('MOD_SIMPLE_FILE_UPLOAD_ONLY_FILES_UNDER'),$this->max_file_size);
                return $res;
            }

            //check type
            $check_type = $this->checkFileTypes($file);
            if ( ! $check_type) {
                //error fyle type
                $res['status'] = 0;
                $res['msg'] = sprintf(JText::_('MOD_SIMPLE_FILE_UPLOAD_FILE_TYPE_INVALID'), $dest);

                return $res;

            }

            // upload file
            if (JFile::upload($src, $dest)) {
                // success uploading
                $res['status'] = 1;
                $res['msg'] = sprintf(JText::_('MOD_SIMPLE_FILE_UPLOAD_FILE_SAVE_AS'), $dest);
            } else {
                // error uploading
                $res['status'] = 0;
                $res['msg'] = sprintf(JText::_('MOD_SIMPLE_FILE_UPLOAD_ERROR_IN_UPLOAD'), $dest);
            }

            return $res;
        }

        $res['status'] = 0;
        $res['msg'] = JText::_('MOD_SIMPLE_FILE_UPLOAD_ERROR_NO_FILE');
        return $res;
    }

    // if file exist change name addin a suffix link -2 to prevent overwrite
    private function makeUniqueFileName( $dest ){
        $file_ext = JFile::getExt($dest);
        $file_path_no_ext = JFile::stripExt($dest);
        $unique_name = 1;
        while(JFile::exists( $dest )){
            $unique_name++;
            $dest = $file_path_no_ext . '-' . $unique_name . '.' . $file_ext;
        }
        return $dest;
    }

    private function checkFileSize($file){
        if ($file['size'] > $this->max_file_size) {
            return false;
        }
        return true;
    }

    private function checkFileTypes($file)
    {
        if( empty( $this->allowed_file_types ) ){
            return true;
        }

        if (in_array($file['type'], $this->allowed_file_types)) {
            return true;
        }
        return false;
    }

}

<?php
/**
 * Simple File Upload
 */

// No direct access
defined('_JEXEC') or die;

// Include the classes used by the plugin
require_once dirname(__FILE__) . '/helper.php';

//Import filesystem libraries. Perhaps not necessary, but does not hurt
jimport('joomla.filesystem.file');

// upload file configuration
// upload max file usually is somthing like 128M
$max_file_size = ini_get('upload_max_filesize');
if(!is_numeric($max_file_size) && strtoupper(substr($max_file_size, -1)) == 'M'){
    $max_file_size = substr($max_file_size,0,-1) * 1024;
}elseif(!is_numeric($max_file_size) && strtoupper(substr($max_file_size, -1)) == 'K'){
    $max_file_size = substr($max_file_size,0,-1);
}

$upload_params = array(
    'max_file_size' => ini_get('upload_max_filesize'),
    'destination_dir' => $params->get('dir'),
    'allowed_file_types' => in_array('*',$params->get('type'))?array():$params->get('type')
);

$uploader = new modSimpleFileUpload($upload_params);

// feedback user
$msg_classes = array('error', 'success');
$msg = '';
$msg_class = '';

//upload file if file exist.
$file = JRequest::getVar('file_upload', null, 'files', 'array');

if(isset($file)){
    $upload_task = $uploader->fileUpload($file);
    $msg = $upload_task['msg'];
    $msg_class = $msg_classes[$upload_task['status']];
}

require(JModuleHelper::getLayoutPath('mod_simple_file_upload', 'upload_form'));


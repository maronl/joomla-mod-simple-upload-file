<?php
// No direct access
defined('_JEXEC') or die; ?>
<form name="upload" method="post" enctype="multipart/form-data">
    <div class="upload-feedback <?php echo $msg_class; ?>"><?php echo $msg; ?></div>
    <input type="file" name="file_upload" />
    <input type="submit" />
</form>
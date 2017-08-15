<?php

error_reporting(E_ALL | E_STRICT);

require_once '../res/php/upload-handler.php';

$options = array(
	'image_versions' => array()
);

$upload_handler = new UploadHandler($options);

?>
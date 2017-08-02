<?php

session_start();
require_once 'ms.php';
require_once 'aws.phar';
define('PHOTOS_BUCKET', 'photos.fotlan.com');
define('PHOTOS_ROOT', 'http://photos.fotlan.com/');

//+++++ Retrieve AWS configuration +++++
function awsConfig() {
	if(file_exists(CONFIG_FILE)) {
		$_conf = parse_ini_file(CONFIG_FILE);
		if(is_array($_conf) && array_key_exists('aws.access.key_id', $_conf) && array_key_exists('aws.secret.access.key', $_conf)) {
			return $_conf;
		} else {
			return null;
		}
	} else {
		return null;
	}
}

?>
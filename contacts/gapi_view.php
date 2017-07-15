<?php

require_once '../res/php/gapi/autoload.php';
require_once '../res/php/ms.php';
require_once '../res/php/gapi.php';
require_once '../res/php/profile-class.php';

$google_id = (isset($_GET['id'])) ? trim($_GET['id']) : '0';

if($google_id != '0') {
	
	$url = $google_id . "&alt=json&oauth_token=" . $_SESSION['GAPI_TOKEN'];
	$json_response = gapiCurl($url);
	$data = json_decode($json_response, true);
	var_dump($data);
	
} else {
	
	echo 'Invalid ID';
	
}
?>
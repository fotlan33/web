<?php

require_once '../res/php/gapi/autoload.php';
require_once '../res/php/gapi.php';

//Get Google Client IDs
$google_conf = gapiConfig();
if(is_null($google_conf)) {
	$googleImportUrl = 'http://www.fotlan.com/hp/';
} else {
	//setup new google client
	$client = new Google_Client();
	$client -> setApplicationName('Fotlan Contacts');
	$client -> setClientid($google_conf['gapi.client.id']);
	$client -> setClientSecret($google_conf['gapi.client.secret']);
	$client -> setRedirectUri($google_conf['gapi.redirect.uri']);
	$client -> setAccessType('online');
	$client -> setScopes('https://www.google.com/m8/feeds');
	$googleImportUrl = $client -> createAuthUrl();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Synchronisation Google</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Refresh" content="0; url='<?php echo $googleImportUrl; ?>'">
	</head>
<body>
</body>
</html>

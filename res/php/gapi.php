<?php

session_start();
require_once 'ms.php';

//+++++ Retrieve GAPI configuration +++++
function gapiConfig() {
	if(file_exists(CONFIG_FILE)) {
		$_conf = parse_ini_file(CONFIG_FILE);
		if(is_array($_conf) && array_key_exists('gapi.client.id', $_conf)
		&& array_key_exists('gapi.client.secret', $_conf) && array_key_exists('gapi.redirect.uri', $_conf)) {
			return $_conf;
		} else {
			return null;
		}
	} else {
		return null;
	}
}

//+++++ Retrieve GAPI authentication token +++++
function gapiToken($auth_code) {

	// Retrieve configuration
	$google_conf = gapiConfig();
	if(!is_null($google_conf)) {

		// Connection values
		$auth_fields = array(
				'code' =>			urlencode($auth_code),
				'client_id' =>		urlencode($google_conf['gapi.client.id']),
				'client_secret' =>	urlencode($google_conf['gapi.client.secret']),
				'redirect_uri'=>	urlencode($google_conf['gapi.redirect.uri']),
				'grant_type'=>		urlencode('authorization_code')
		);
		
		// Connection string
		$auth_post = '';
		foreach($auth_fields as $key=>$value) {
			$auth_post .= $key . '=' . $value . '&';
		}
		$auth_post = rtrim($auth_post, '&');
	
		// Connection request
		$auth_result = json_decode(gapiCurl('https://accounts.google.com/o/oauth2/token', $auth_post));
		return $auth_result->access_token;

	} else {
		return null;
	}
}

//+++++ Make a curl request to Google +++++
function gapiCurl($url, $postdata = null, $putdata = null, $headers = null) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	if(!is_null($headers))
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	if(!is_null($postdata)) {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	}
	if(!is_null($putdata)) {
		$fp = fopen('php://temp/maxmemory:256000', 'w');
		fwrite($fp, $putdata);
		fseek($fp, 0);	
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_PUT, true);
		curl_setopt($ch, CURLOPT_INFILE, $fp);
		curl_setopt($ch, CURLOPT_INFILESIZE, strlen($putdata));
	}
	return curl_exec($ch);
}

?>
<?php

define('CONFIG_FILE', getenv('DOCUMENT_ROOT') . '/../.config.ini');
define('CERT_FILE', getenv('DOCUMENT_ROOT') . '/../.cert.pem');

//+++++ Database connection +++++
function msConnectDB($_dbname = null) {
	if(file_exists(CONFIG_FILE)) {
		$_conf = parse_ini_file(CONFIG_FILE);
		if(is_array($_conf) && array_key_exists('fotlan.mysql.host', $_conf)
			&& array_key_exists('fotlan.mysql.user', $_conf) && array_key_exists('fotlan.mysql.pswd', $_conf)) {
			$_host = $_conf['fotlan.mysql.host'];
			$_user = $_conf['fotlan.mysql.user'];
			$_pswd = $_conf['fotlan.mysql.pswd'];
			if(is_null($_dbname))
				$_dbname = $_conf['fotlan.mysql.dbname'];
			$_con = new PDO('mysql:host=' . $_host . ';dbname=' . $_dbname . ';charset=utf8', $_user, $_pswd);
			if($_con)
				return $_con;
			else 
				die('Echec de connexion a la base de donnees.');
		} else {
			die('Parametres de connexion introuvables.');
		}
	} else {
		die('Fichier de configuration introuvable.');
	}
	return $_con;
}

//+++++ Retrieve the first value of a request +++++
function msRequestValue($_request) {
	if($_request) {
		$_results = $_request->fetch(PDO::FETCH_NUM);
		return $_results[0];
	}
	else
		return null;
}

//+++++ Escape quotes from strings +++++
function msEscapeQuotes($_s, $_GPC) {
	if(get_magic_quotes_gpc() && $_GPC)
		return $_s;
	else
		return addslashes($_s);
}

//+++++ Convert MySQL Date to French Standard Date +++++
function msFormatStandardDate($_d) {
	if(is_null($_d) || strlen($_d) < 10)
		return null;
	else
		return substr($_d, 8, 2) . '/' . substr($_d, 5, 2) . '/' . substr($_d, 0, 4);
}

//+++++ Buil MySQL Date from French Standard Date +++++
function msFormatMysqlDate($_d, $_default = null) {
	if(strlen($_d) < 10)
		return $_default;
	else {
		$_yyyy = substr($_d, 6, 4);
		$_mm = substr($_d, 3, 2);
		$_dd = substr($_d, 0, 2);
		if(checkdate(intval($_mm), intval($_dd), intval($_yyyy)))
			return "$_yyyy-$_mm-$_dd";
		else
			return $_default;
	}
}

//+++++ Check value : isset, length, ... +++++
function msFormatString($_string, $_default = null, $_len = 0) {
	if(isset($_string) && trim($_string) != '') {
		$_buf = trim($_string);
		if(strlen($_buf) > $_len)
			return substr($_buf, 0, $_len);
		else 
			return $_buf;
	} else {
		return $_default;
	}
}

//+++++ Secure String for HTML Display +++++
function msSecureString($_string) {
	return htmlspecialchars($_string, ENT_COMPAT | ENT_HTML5);
}

//+++++ Display debug string +++++
function msDebug($buf, $html = false) {
	if($html)
		echo $buf . "<br />\n";
	else 
		echo $buf . "\n";
} 

//+++++ Normalize newlines +++++
function cr2nl($s)
{
	$s = mb_eregi_replace("\r\n" , "\n", $s);
	$s = mb_eregi_replace("\r" , "\n", $s);
	$s = mb_eregi_replace("\n\n" , "\n", $s);
	return($s);
}

?>
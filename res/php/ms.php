<?php

//+++++ Database connection +++++
function msConnectDB($_dbname) {
	$_host = get_cfg_var('fotlan.mysql.host');
	$_user = get_cfg_var('fotlan.mysql.user');
	$_pswd = get_cfg_var('fotlan.mysql.pswd');
	$_con = new PDO('mysql:host=' . $_host . ';dbname=' . $_dbname . ';charset=utf8', $_user, $_pswd);
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
	return substr($_d, 8, 2) . '/' . substr($_d, 5, 2) . '/' . substr($_d, 0, 4);
}

?>
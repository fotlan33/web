<?php

//+++++ Include +++++
require_once 'ms.php';

//+++++ HTTP Header +++++
header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

//+++++ Parameters +++++
$user = msEscapeQuotes($_POST['user'], false);
$pswd = msEscapeQuotes($_POST['pswd'], false);

if($user == '') {

	//+++++ Delete Cookie +++++
	setcookie('username', 'x', mktime(0, 0, 0, 1, 1, 1975), '/');
	setcookie('admin', '0', mktime(0, 0, 0, 1, 1, 1975), '/');
	$response = 'Yes';

} else {

	//+++++ Database Connection +++++
	$con = msConnectDB('dbu_fotlan');

	//+++++ SQL Request +++++
	$rs = $con->query("SELECT login,admin FROM t_profiles WHERE login='$user' AND pswd='$pswd'");

	//+++++ Set Cookie +++++
	if($rs->num_rows > 0) {
		$row = $rs->fetch_row();
		setcookie('username', $row[0], mktime(0, 0, 0, 1, 1, 2075), '/');
		setcookie('admin', $row[1], mktime(0, 0, 0, 1, 1, 2075), '/');
		$response = 'Yes';
	}
	else
		$response = 'No';

	//+++++ Close Connection +++++
	$rs->free();
	$con->close();
}
//+++++ HTTP Response +++++
echo "<Profile><Change>$response</Change></Profile>";
?>

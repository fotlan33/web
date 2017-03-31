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
	$db = msConnectDB('dbu_fotlan');

	//+++++ SQL Request +++++
	$sql = "SELECT login, admin FROM t_profiles WHERE login = :user AND pswd = :pswd";
	$rs = $db->prepare($sql);
	$rs->execute(array(':user' => $user, ':pswd' => $pswd));

	//+++++ Set Cookie +++++
	if($row = $rs->fetch(PDO::FETCH_ASSOC)) {
		setcookie('username', $row['login'], mktime(0, 0, 0, 1, 1, 2075), '/');
		setcookie('admin', $row['admin'], mktime(0, 0, 0, 1, 1, 2075), '/');
		$response = 'Yes';
	}
	else
		$response = 'No';

}
//+++++ HTTP Response +++++
echo "<Profile><Change>$response</Change></Profile>";
?>

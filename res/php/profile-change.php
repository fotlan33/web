<?php

//+++++ Include +++++
require_once 'ms.php';

//+++++ HTTP Header +++++
header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

//+++++ Parameters +++++
$user = isset($_POST['user']) ? trim($_POST['user']) : '';
$pswd = isset($_POST['pswd']) ? trim($_POST['pswd']) : '';

if($user == '') {

	//+++++ Delete Cookie +++++
	$expire_time = time() - 60 * 60;		// Cookie a expire il y a une heure
	setcookie('username', 'x', 100, '/');
	setcookie('admin', '0', 100, '/');
	$response = 'Yes';

} else {

	//+++++ Database Connection +++++
	$db = msConnectDB();

	//+++++ SQL Request +++++
	$sql = "SELECT login, admin FROM t_profiles WHERE login = :user AND pswd = :pswd";
	$rs = $db->prepare($sql);
	$rs->execute(array(':user' => $user, ':pswd' => $pswd));

	//+++++ Set Cookie +++++
	if($row = $rs->fetch(PDO::FETCH_ASSOC)) {
		$expire_time = time() + 60 * 60 * 24 * 365 * 5;		// Cookie expire dans 5 ans
		setcookie('username', $row['login'], $expire_time, '/');
		setcookie('admin', $row['admin'], $expire_time, '/');
		$response = 'Yes';
	}
	else
		$response = 'No';

}
//+++++ HTTP Response +++++
echo "<Profile><Change>$response</Change></Profile>";

?>

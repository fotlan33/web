<?php

//+++++ Include +++++
require_once '../res/php/ms.php';

//+++++ SQL Request +++++
$db = msConnectDB();
$data = array();
$rs = $db->query("SELECT id_contact, nom, prenom, priv_email, priv_ville, priv_tel, priv_gsm
				FROM t_contacts ORDER BY nom ASC, prenom ASC, priv_ville ASC, id_contact ASC");
while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
	$line = array(	'<a class="ctc-link" href="edit.php?id=' . $row['id_contact'] . '">' . msSecureString($row['nom']) . ' ' . msSecureString($row['prenom']) . '</a>',
					msSecureString($row['priv_ville']),
					msSecureString($row['priv_tel']),
					msSecureString($row['priv_gsm']));
	$data['data'][] = $line;
}

//+++++ Response ++++++
header('Content-Type: application/json');
echo json_encode($data);

?>
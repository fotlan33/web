<?php

//+++++ Include +++++
require_once '../res/php/ms.php';

//+++++ SQL Request +++++
$db = msConnectDB('dbu_fotlan');
$data = array();
$rs = $db->query("SELECT id_contact, nom, prenom, priv_email, priv_ville, priv_tel, priv_gsm
				FROM t_contacts ORDER BY nom ASC, prenom ASC, priv_ville ASC, id_contact ASC");
while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
	$line = array(	'<a class="ctc-link" href="edit.php?id=' . $row['id_contact'] . '">' . $row['nom'] . ' ' . $row['prenom'] . '</a>',
					$row['priv_ville'],
					$row['priv_tel'],
					$row['priv_gsm']);
	$data['data'][] = $line;
}

//+++++ Response ++++++
header('Content-Type: application/json');
echo json_encode($data);

?>
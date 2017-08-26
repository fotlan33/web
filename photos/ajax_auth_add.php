<?php

//+++++ Include +++++
require_once '../res/php/ms.php';
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();
$db = msConnectDB();

//+++++ Check autorization +++++
if($u->IsAdministrator) {
	
	$login = isset($_POST['login']) ? trim($_POST['login']) : 'unknown';
	$sql = "INSERT INTO pic_rights SET login = :login, id_folder = :folder";
	$rs = $db->prepare($sql);
	$rs->execute(array(':login' => $login, ':folder' => $folder->ID));
	$log = $rs->errorInfo();
	if(is_null($log[1])) {
		$id = $db->lastInsertId();
		$response = array(	'id'	=> $folder->ID,
							'type'	=> 'success',
							'title'	=> 'Succès !',
							'text'	=> 'Le gestionnaire a été ajouté.' );
	} else {
		$response = array(	'id'	=> $folder->ID,
							'type'	=> 'error',
							'title'	=> 'Erreur !',
							'text'	=> $log[2]);
	}
} else {
	$response = array(	'id'	=> $folder->ID,
						'type'	=> 'error',
						'title'	=> 'Echec !',
						'text'	=> 'Accès refusé.' );
}

// Send response
echo json_encode($response);

?>	
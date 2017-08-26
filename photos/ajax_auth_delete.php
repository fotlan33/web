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

	$id = isset($_POST['id']) ? trim($_POST['id']) : 0;
	$sql = "DELETE FROM pic_rights WHERE id_right = :id";
	$rs = $db->prepare($sql);
	$rs->execute(array(':id' => $id));
	$log = $rs->errorInfo();
	if(is_null($log[1]))
		$response = array(	'type'	=> 'success',
							'title'	=> 'Succès !',
							'text'	=> 'Le gestionnaire a été supprimé.' );
	else
		$response = array(	'type'	=> 'error',
							'title'	=> 'Erreur !',
							'text'	=> $log[2]);
} else {
	$response = array(	'type'	=> 'error',
						'title'	=> 'Echec !',
						'text'	=> 'Accès refusé.' );
}

// Send response
echo json_encode($response);

?>	
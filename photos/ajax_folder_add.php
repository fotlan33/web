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
if($folder->IsManager($u)) {
	
	// Create folder
	$name = isset($_POST['name']) ? $_POST['name'] : '';
	$name = (trim($name) == '') ? 'Nouveau dossier' : trim($name);
	$path = $folder->Path . '|' . $folder->Name;
	$sql = "INSERT INTO pic_folders SET folder = :name, path = :path";
	$rs = $db->prepare($sql);
	$rs->execute(array(':name' => $name, ':path' => $path));
	$log = $rs->errorInfo();
	if(is_null($log[1])) {
		$id = $db->lastInsertId();
		// Propagate authorizations
		$sql = "INSERT INTO pic_rights (login, id_folder)
				SELECT login, :id FROM pic_rights WHERE id_folder = :folder";
		$rs2 = $db->prepare($sql);
		$rs2->execute(array(':id' => $id, ':folder' => $folder->ID));
		// Response
		$response = array(	'id'	=> $id,
							'type'	=> 'success',
							'title'	=> 'Succès !',
							'text'	=> 'Le dossier a été créé.' );
	} else {
		$response = array(	'id'	=> 0,
							'type'	=> 'error',
							'title'	=> 'Erreur !',
							'text'	=> $log[2]);
	}
} else {
	$response = array(	'id'	=> 0,
						'type'	=> 'error',
						'title'	=> 'Echec !',
						'text'	=> 'Accès refusé.' );
}

// Send response
echo json_encode($response);

?>	
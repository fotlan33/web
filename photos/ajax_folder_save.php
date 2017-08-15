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
	
	// Check not root
	if($folder->ID != ROOT_ID) {
		
		// Change path
		$parentid = isset($_POST['parent']) ? $_POST['parent'] : ROOT_ID;
		$parent = new Folder($parentid);
		if($parent->IsRoot())
			$path = ROOT_NAME;
		else
			$path = $parent->Path . '|' . $parent->Name;

		// Update Folder
		$name = isset($_POST['nom']) ? $_POST['nom'] : '';
		$name = (trim($name) == '') ? 'Nouveau dossier' : trim($name);
		$sql = "UPDATE pic_folders SET folder = :name, path = :path WHERE id_folder = :id";
		$rs = $db->prepare($sql);
		$rs->execute(array(':name' => $name, ':path' => $path, ':id' => $folder->ID));
		$log = $rs->errorInfo();
		if(is_null($log[1]))
			$response = array(	'type'	=> 'success',
								'title'	=> 'Succès !',
								'text'	=> 'Le dossier a été mis à jour.' );
		else
			$response = array(	'type'	=> 'error',
								'title'	=> 'Erreur !',
								'text'	=> $log[2]);

		// Update Subfolders
		$new_path = $path . "|" . $name;
		$old_path = $folder->Path . '|' . $folder->Name;
		$sql = "UPDATE pic_folders SET path = REPLACE(path, :old_path, :new_path) WHERE path LIKE :filter";
		$rs = $db->prepare($sql);
		$rs->execute(array(':new_path' => $new_path, ':old_path' => $old_path, ':filter' => $old_path . '%'));
		$log = $rs->errorInfo();
		if(!is_null($log[1]))
			$response = array(	'type'	=> 'error',
								'title'	=> 'Erreur !',
								'text'	=> $log[2]);
	} else {
		$response = array(	'type'	=> 'error',
							'title'	=> 'Echec !',
							'text'	=> 'Impossible de modifier le répertoire racine.' );
	}
} else {
	$response = array(	'type'	=> 'error',
						'title'	=> 'Echec !',
						'text'	=> 'Accès refusé.' );
}

// Send response
echo json_encode($response);

?>	
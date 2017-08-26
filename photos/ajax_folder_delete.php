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
	
	// Check folder has no pictures
	$sql = "SELECT COUNT(*) AS nbi FROM pic_data WHERE id_folder = :id";
	$rs = $db->prepare($sql);
	$rs->execute(array(':id' => $folder->ID));
	$row = $rs->fetch(PDO::FETCH_ASSOC);
	if($row['nbi'] == 0) {
		// Check folder has no subfolders
		$sql = "SELECT COUNT(*) AS nbf FROM pic_folders WHERE path LIKE :path";
		$rs2 = $db->prepare($sql);
		$rs2->execute(array(':path' => $folder->Path . '|' . $folder->Name));
		$row2 = $rs2->fetch(PDO::FETCH_ASSOC);
		if($row2['nbf'] == 0) {
			// OK first we delete authorizations
			$sql = "DELETE FROM pic_rights WHERE id_folder = :id";
			$rs3 = $db->prepare($sql);
			$rs3->execute(array(':id' => $folder->ID));
			// Now we can try to delete folder
			$sql = "DELETE FROM pic_folders WHERE id_folder = :id";
			$rs4 = $db->prepare($sql);
			$rs4->execute(array(':id' => $folder->ID));
			$log = $rs4->errorInfo();
			if(is_null($log[1]))
				$response = array(	'type'	=> 'success',
									'title'	=> 'Succès !',
									'text'	=> 'Le dossier a été supprimé.' );
			else
				$response = array(	'type'	=> 'error',
									'title'	=> 'Erreur !',
									'text'	=> $log[2]);
		} else {
			$response = array(	'type'	=> 'error',
								'title'	=> 'Echec !',
								'text'	=> 'Le dossier n\'est pas vide.' );
		}
	} else {
		$response = array(	'type'	=> 'error',
							'title'	=> 'Echec !',
							'text'	=> 'Le dossier n\'est pas vide.' );
	}
} else {
	$response = array(	'type'	=> 'error',
						'title'	=> 'Echec !',
						'text'	=> 'Accès refusé.' );
}

// Send response
echo json_encode($response);

?>	
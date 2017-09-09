<?php

//+++++ Include +++++
require_once '../res/php/ms.php';
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$pic = new Picture();
$pic->Open($id);

//+++++ Check autorization +++++
if($folder->IsManager($u)) {
	
	// Update data
	$pic->Label = msFormatString($_POST['label'], null, 100);
	$pic->Keywords = msFormatString($_POST['keywords'], null, 255);
	$pic->Folder = intval('0' . $_POST['folder'], 10);
	
	// Save
	$pic->Save();
	if($pic->GetErrors() == '') {
		$response = array(	'type'	=> 'success',
							'title'	=> 'Succès !',
							'text'	=> 'Les informations ont été mises à jour.' );
	} else {
		$response = array(	'type'	=> 'error',
							'title'	=> 'Erreur !',
							'text'	=> $pic->GetErrors() );
	}
} else {
	$response = array(	'type'	=> 'error',
						'title'	=> 'Echec !',
						'text'	=> 'Accès refusé.' );
}

// Send response
echo json_encode($response);

?>
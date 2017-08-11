<?php

//+++++ Include +++++
require_once '../res/php/ms.php';
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();
$pic = new Picture();

//+++++ Check autorization +++++
if($folder->IsManager($u)) {
	$id = isset($_POST['id']) ? $_POST['id'] : 0;
	$pic->Open($id);
	if($pic->GetErrors() == '')
		$pic->Delete();
	if($pic->GetErrors() == '')
		$response = array(	'type'	=> 'success',
							'title'	=> 'Succès !',
							'text'	=> 'La photo a été supprimée.' );
	else
		$response = array(	'type'	=> 'error',
							'title'	=> 'Erreur !',
							'text'	=> $pic->GetErrors() );
} else {
	$response = array(	'type'	=> 'error',
						'title'	=> 'Echec !',
						'text'	=> 'Accès refusé.' );
}

// Send response
echo json_encode($response);

?>	
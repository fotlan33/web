<?php

//+++++ Include +++++
error_reporting(E_ERROR);
require_once '../res/php/ms.php';
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();

//+++++ Check autorization +++++
if($folder->IsManager($u)) {

	if(isset($_POST['file'])) {
		
		//+++++ Check File +++++
		$fichier = 'files/' . $_POST['file'];
		if(file_exists($fichier)) {
			list($imgLargeur, $imgHauteur, $imgTypeMime, $buf) = getimagesize($fichier);
			$imgTypeMime = image_type_to_mime_type($imgTypeMime);
			if(strpos($imgTypeMime, 'image') !== false) {
				
				//+++++ Image Properties +++++
				$info = pathinfo($fichier);
				$exif = exif_read_data($fichier, 0, true, false);
				$oImage = new Picture();
				$oImage->Label = $info['filename'];
				$oImage->Width = $imgLargeur;
				$oImage->Height = $imgHauteur;
				$oImage->Size = Ceil(filesize($fichier) / 1024);
				$oImage->Date = $oImage->NewDate(substr($exif['EXIF']['DateTimeOriginal'], 0, 10));
				$oImage->Folder = $folder->ID;
				$oImage->Extension = '.' . $info['extension'];
				
				//+++++ Insert into database +++++
				$oImage->Save();
				
				//+++++ Save Images +++++
				$oImage->StoreInS3('i', $fichier);
				$oImage->StoreThumbnail($fichier);
				$oImage->StorePreview($fichier);
				unlink($fichier);
				
				//+++++ Reporting +++++
				$report = $oImage->GetErrors();
				if($report == '') {
					$response = array(	'type'	=> 'success',
										'text'	=> 'Fichier téléchargé.' );
				} else {
					$response = array(	'type'	=> 'error',
										'text'	=> 'Erreur de traitement.' );
				}
				
			} else {
				$response = array(	'type'	=> 'error',
									'text'	=> 'Format de fichier non supporté.' );
			}
		} else {
			$response = array(	'type'	=> 'error',
								'text'	=> 'Fichier introuvable.' );
		}
	} else {
		$response = array(	'type'	=> 'error',
							'text'	=> 'Fichier introuvable.' );
	}
} else {
	$response = array(	'type'	=> 'error',
						'text'	=> 'Accès refusé.' );
}

// Send response
if(isset($_POST['file']))
	$response['file'] = $_POST['file'];
else 
	$response['file'] = 'NO_FILE';
echo json_encode($response);

?>
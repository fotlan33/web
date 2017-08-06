<?php

//+++++ Include +++++
require_once '../res/php/ms.php';
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();

//+++++ Check autorization +++++
if($folder->IsManager($u)) {

	if (isset($_FILES['pic-uploadfile'])) {
		
		//+++++ Check File +++++
		$fichier = $_FILES['pic-uploadfile']['tmp_name'];
		list($imgLargeur, $imgHauteur, $imgTypeMime, $buf) = getimagesize($fichier);
		$imgTypeMime = image_type_to_mime_type($imgTypeMime);
		if(strpos($imgTypeMime, 'image') !== false) {
			
			//+++++ Image Properties +++++
			$info = pathinfo($_FILES['pic-uploadfile']['name']);
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
			
			//+++++ Reporting +++++
			$report = $oImage->GetErrors();
			if($report == '') {
				echo 'Fichier &laquo; ' . $info['basename'] . ' &raquo; téléchargé.<br />'; 
			} else {
				echo 'Erreur lors du téléchargement de ' . $info['basename'] . ' :<br />';
				echo nl2br($report, true);
			}
			
		} else {
			echo "Format de fichier non supporté !<br />";
		}
	} else {
		echo "Impossible de trouver le fichier téléchargé !<br />";
	}
	
} else {
	"Accès refusé !<br />";
}
?>
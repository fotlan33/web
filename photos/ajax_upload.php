<?php

//+++++ Include +++++
require_once('../res/php/ms.php');
require_once('../res/php/profile-class.php');
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();

//+++++ Check autorization +++++
if($folder->IsManager($u)) {

	if (isset($_FILES['upload_file'])) {
		if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "D:/Users/Christophe/git/web/photos/data/" . $_FILES['upload_file']['name'])){
			echo "OK<br />";
		} else {
			echo "Transfert failed !<br />";
		}
	} else {
		echo "No files uploaded !<br />";
	}
	
} else {
	"Access denied !<br />";
}
?>
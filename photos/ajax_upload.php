<?php

//+++++ Include +++++
require_once('../res/php/ms.php');
require_once('../res/php/profile-class.php');

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();

//+++++ Check autorization +++++
if(($u->Username == 'Fot') || ($u->Username == 'Mumu')) {

if (isset($_FILES['upload_file'])) {
	if(move_uploaded_file($_FILES['upload_file']['tmp_name'], "datas/" . $_FILES['upload_file']['name'])){
		echo $_FILES['upload_file']['name']. " OK";
	} else {
		echo $_FILES['upload_file']['name']. " KO";
	}
	exit;
} else {
	echo "No files uploaded ...";
}
?>
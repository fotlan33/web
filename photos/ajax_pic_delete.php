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
	$pic->Delete();
}

echo 'OK';
?>	
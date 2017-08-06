<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Photos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
	<link rel="stylesheet" type="text/css" href="css/photos.css" />
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6"><a href="/hp/"><img src="/res/img/fotlan.png" alt="FotLan" width="140" height="50" /></a></div>
			<div class="col-xs-6 profile"><?php $u->Display(''); ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 pic-center"><div class="pic-title">Importer des photos</div></div>
		</div>
		<div class="row pic-center">
			<div class="pic-upload-btn-wrapper">
				<button class="pic-upload-btn">Fichiers...</button>
				<input type="file" id="pic-uploadfiles" multiple="multiple" accept="image/*" />
			</div>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
				0%
 			</div>
		</div>
		<div class="row pic-transfer">En attente de fichiers...</div>
		<div class="row">
			<div class="col-sm-12 pic-center"><button type="button" class="btn btn-warning" id="pic-back">Retour dossier</button></div>
		</div>
	</div>
	<input type="hidden" id="pic-folder-id" value="<?= $folder->ID; ?>" />
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/import.js"></script>
</body>
</html>
	
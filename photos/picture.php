<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$pic = new Picture();
$pic->Open($id);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Photos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap.datepicker3-1.6.4.min.css" />
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
			<div class="col-xs-12 pic-center"><div class="pic-title"><?= $pic->Label ?></div></div>
		</div>
		<div class="row">
			<div class="col-sm-3 pic-center"><img src="<?= $pic->VirtualPath() . $pic->FileName('v') ?>" alt="Photo" /></div>
			<div class="col-sm-9">
				<form id="frm" class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-description">Description :</label>
						<div class="col-sm-8"><input type="text" class="form-control" id="frm-description" placeholder="Description" maxlength="100" value="<?= msSecureString($pic->Label) ?>" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-keywords">Mots-clés :</label>
						<div class="col-sm-8"><input type="text" class="form-control" id="frm-keywords" placeholder="neige ski montagne..." maxlength="255" value="<?= msSecureString($pic->Keywords) ?>" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-date">Date :</label>
						<div class="col-sm-8">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								<input type="text" class="form-control" id="frm-date" placeholder="jj/mm/aaaa" value="<?= msFormatStandardDate($pic->Date) ?>" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-width">Largeur :</label>
						<div class="col-sm-8"><input type="text" class="form-control" id="frm-width" placeholder="en pixels" maxlength="50" value="<?= msSecureString($pic->Width) ?>" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-height">Hauteur :</label>
						<div class="col-sm-8"><input type="text" class="form-control" id="frm-height" placeholder="en pixels" maxlength="50" value="<?= msSecureString($pic->Height) ?>" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-size">Taille (Ko) :</label>
						<div class="col-sm-8"><input type="text" class="form-control" id="frm-size" placeholder="en Ko" maxlength="50" value="<?= msSecureString($pic->Size) ?>" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-size">Extension :</label>
						<div class="col-sm-8"><input type="text" class="form-control" id="frm-size" placeholder=".jpg .png .gif" maxlength="10" value="<?= msSecureString($pic->Extension) ?>" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-folder">Dossier :</label>
						<div class="col-sm-8"><select class="form-control" id="frm-folder">
						</select></div>
					</div>
					<div class="col-sm-9 pic-center">
						<button type="button" class="btn btn-warning">Enregistrer</button>&nbsp;
						<button type="button" class="btn btn-warning" id="pic-delete">Supprimer</button>&nbsp;
						<button type="button" class="btn btn-warning" id="pic-back">Retour</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<input type="hidden" id="pic-id" value="<?= $pic->ID ?>" />
	<input type="hidden" id="pic-folder-id" value="<?= $folder->ID ?>" />
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker.fr-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/photos.js"></script>
</body>
</html>

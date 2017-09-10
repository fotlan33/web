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
$db = msConnectDB();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Photos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap.datepicker3-1.6.4.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/sweetalert2-6.6.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
	<link rel="stylesheet" type="text/css" href="css/blueimp.gallery-2.25.2.min.css">
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
			<div class="col-sm-3 pic-center pic-slides">
				<a href="<?= $pic->VirtualPath() . $pic->FileName('p') ?>">
					<img src="<?= $pic->VirtualPath() . $pic->FileName('v') ?>" alt="Photo" />
				</a>
			</div>
			<div class="col-sm-9">
				<form id="frm" class="form-horizontal">
					<div class="form-group" id="pic-description">
						<label class="control-label col-sm-4" for="frm-description">Description :</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="frm-description" placeholder="Description" maxlength="100" value="<?= msSecureString($pic->Label) ?>" />
							<span class="glyphicon form-control-feedback"></span>
						</div>
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
						<label class="control-label col-sm-4" for="frm-extension">Extension :</label>
						<div class="col-sm-8"><input type="text" class="form-control" id="frm-extension" placeholder=".jpg .png .gif" maxlength="10" value="<?= msSecureString($pic->Extension) ?>" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="frm-folder-name">Dossier :</label>
						<div class="col-sm-8">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-folder-open"></i></span>
								<input type="text" class="form-control pic-folder-name" id="frm-folder-name" placeholder="sélectionne un dossier" value="<?= msSecureString($folder->Name) ?>" />
							</div>
							<input type="hidden" id="frm-folder-value" data-pic="folder" value="<?= $folder->ID ?>" />
						</div>
					</div>
					<div class="col-sm-12 pic-center">
						<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i><span class="hidden-xs"> Enregistrer</span></button>&nbsp;
						<button type="button" class="btn btn-danger" id="pic-delete"><i class="glyphicon glyphicon-trash"></i><span class="hidden-xs"> Supprimer</span></button>&nbsp;
						<button type="button" class="btn btn-warning" id="pic-back"><i class="glyphicon glyphicon-arrow-left"></i><span class="hidden-xs"> Retour</span></button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- ========== Variables ========== -->
	<input type="hidden" id="pic-id" value="<?= $pic->ID ?>" />
	<input type="hidden" id="pic-folder-id" value="<?= $folder->ID ?>" />

	<!-- ========== Galarie Photos ========== -->
	<div id="blueimp-gallery" class="blueimp-gallery"><div class="slides"></div></div>
	
	<!-- ========== Selection d'un dossier ========== -->
	<div class="modal fade" id="pic-folder-selector">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div id="pic-folder-list">Contenu</div>
					<div class="pic-center">
						<button class="btn btn-primary" id="pic-folder-select"><i class="glyphicon glyphicon-ok"></i> Sélectionner</button>
						<button class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-ban-circle"></i> Annuler</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker.fr-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/sweetalert2-6.6.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/blueimp.gallery-2.25.2.min.js"></script>
	<script type="text/javascript" src="js/picture.js"></script>
	<script type="text/javascript" src="js/folder_selector.js"></script>
	<script type="text/javascript" src="js/gallery.js"></script>
</body>
</html>

<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();
$parent = $folder->GetParent();
$db = msConnectDB();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Photos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/sweetalert2-6.6.7.min.css" />
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
		<div class="panel-group">
			<div class="panel panel-warning">
				<div class="panel-heading pic-panel">Nom</div>
				<div class="panel-body">
					<form id="frm-nommage" class="form-horizontal">
						<div class="form-group" id="pic-nom">
							<label class="control-label col-sm-4" for="frm-nom">Nom du dossier :</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="frm-nom" placeholder="Renseigne un nom de dossier" maxlength="50" value="<?= msSecureString($folder->Name) ?>" />
								<span class="glyphicon form-control-feedback"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="frm-parent-name">Dossier parent :</label>
							<div class="col-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-folder-open"></i></span>
									<input type="text" class="form-control pic-folder-name" id="frm-parent-name" placeholder="sélectionne un dossier" value="<?= $parent->Name ?>" />
								</div>
								<input type="hidden" id="frm-parent" data-pic="folder" value="<?= $parent->ID?>" />
							</div>
						</div>
						<div class="col-sm-12 pic-center">
							<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i><span class="hidden-xs"> Renommer</span></button>&nbsp;
							<button type="button" class="btn btn-info" id="pic-add"><i class="glyphicon glyphicon-plus"></i><span class="hidden-xs"> Sous-dossier</span></button>&nbsp;
							<button type="button" class="btn btn-danger" id="pic-delete"><i class="glyphicon glyphicon-trash"></i><span class="hidden-xs"> Supprimer</span></button>&nbsp;
							<button type="button" class="btn btn-warning" id="pic-back"><i class="glyphicon glyphicon-arrow-left"></i><span class="hidden-xs"> Retour</span></button>
						</div>
					</form>
				</div>
			</div>
<?php
if($u->IsAdministrator) {
	echo "
			<div class=\"panel panel-warning\">
				<div class=\"panel-heading pic-panel\">Gestionnaires</div>
				<div class=\"panel-body\">";
	$sql = "SELECT id_right, login FROM pic_rights
			WHERE id_folder = :folder
			ORDER BY login ASC, id_right ASC";
	$rs = $db->prepare($sql);
	$rs->execute(array(':folder' => $folder->ID));
	while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
		echo "
					<div class=\"row pic-pad5\">
						<div class=\"col-xs-6 pic-auth pic-vcenter\">" . $row['login'] . "</div><div class=\"col-xs-6 pic-vcenter\">
							<button class=\"btn btn-danger pic-auth-button\" data-auth=\"" . $row['id_right'] . "\">
								<i class=\"glyphicon glyphicon-trash\"></i>
								<span class=\"hidden-xs\">Supprimer</span>
							</button>
						</div>
					</div>";
	}
	echo "
					<div class=\"col-xs-12 pic-center\">
						<button type=\"button\" class=\"btn btn-info\" id=\"pic-add-auth\"><i class=\"glyphicon glyphicon-plus\"></i><span class=\"hidden-xs\"> Ajouter</span></button>
					</div>
				</div>
			</div>
";
} ?>
			<div class="panel panel-warning">
				<div class="panel-heading pic-panel">Photos</div>
				<div class="panel-body">
					<form id="frm-photos" class="form-horizontal">
<?php 
$sql = "SELECT id_picture, label, date, extension, keywords
		FROM pic_data
		WHERE id_folder = :folder
		ORDER BY date DESC, id_picture DESC";
$rs = $db->prepare($sql);
$rs->execute(array(':folder' => $folder->ID));
while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
	$pic = new Picture();
	$pic->ID = $row['id_picture'];
	$pic->Date = $row['date'];
	$pic->Extension = $row['extension'];
?>
						<div class="row pic-separator">
							<div class="col-sm-3 pic-center"><img src="<?= $pic->VirtualPath() . $pic->FileName('v') ?>" alt="Photo" /></div>
							<div class="col-sm-9">
								<div class="form-group">
									<label class="col-sm-4 control-label">Description :</label>
									<div class="col-sm-8">
										<div class="input-group">
											<input type="text" class="form-control" name="frm-description" placeholder="Description" maxlength="100" value="<?= msSecureString($row['label']) ?>" />
											<span class="input-group-addon pic-dup-description"><i class="glyphicon glyphicon-duplicate"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Mots-clés :</label>
									<div class="col-sm-8">
										<div class="input-group">
											<input type="text" class="form-control" name="frm-keywords" placeholder="neige ski montagne..." maxlength="255" value="<?= msSecureString($row['keywords']) ?>" />
											<span class="input-group-addon pic-dup-keywords"><i class="glyphicon glyphicon-duplicate"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Dossier :</label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-folder-open"></i></span>
											<input type="text" class="form-control pic-folder-name" name="frm-folder-name" placeholder="sélectionne un dossier" value="<?= $folder->Name ?>" />
											<span class="input-group-addon pic-dup-folder"><i class="glyphicon glyphicon-duplicate"></i></span>
										</div>
										<input type="hidden" name="frm-folder-value" data-pic="folder" data-pic="folder" value="<?= $folder->ID ?>" />
									</div>
								</div>
								<div class="col-sm-12 pic-center">
									<button type="button" class="btn btn-primary frm-pic-save-button"><i class="glyphicon glyphicon-ok"></i> Enregistrer</button>
									<input type="hidden" name="frm-pic-id" value="<?= $pic->ID ?>" />
								</div>
							</div>
						</div>
<?php } ?>
					</form>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="pic-folder-id" value="<?= $folder->ID ?>" />
	<input type="hidden" id="pic-parent-id" value="<?= $parent->ID ?>" />
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
	<!-- =================================== -->
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/sweetalert2-6.6.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/folder.js"></script>
	<script type="text/javascript" src="js/folder_selector.js"></script>
</body>
</html>

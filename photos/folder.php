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
							<label class="control-label col-sm-4" for="frm-parent">Dossier parent :</label>
							<div class="col-sm-8"><select class="form-control" id="frm-parent">
<?php	
	echo "\t\t\t\t\t\t\t<option value=\"" . ROOT_ID . "\">" . ROOT_NAME . "</option>\n";
	$sql = "SELECT * FROM pic_folders
			WHERE id_folder > 1
			ORDER BY CONCAT(path, '|', folder) ASC";
	$rs = $db->query($sql);
	while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
		$n = 1 + substr_count($row['path'], '|');
		$ident = '';
		for($i = 0; $i < $n; $i++) {
			$ident .= '&hellip;&hellip;';
		}
		echo "\t\t\t\t\t\t\t<option value=\"" . $row['id_folder'] . "\"";
		if($row['id_folder'] == $parent->ID)
			echo " selected=\"selected\"";
		echo ">" . $ident . "&nbsp;" . msSecureString($row['folder']) . "</option>\n";
 	}
?>
							</select></div>
						</div>
						
						<div class="col-sm-12 pic-center">
							<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-repeat"></i> Renommer</button>&nbsp;
							<button type="button" class="btn btn-info" id="pic-add"><i class="glyphicon glyphicon-plus"></i> Sous-dossier</button>&nbsp;
							<button type="button" class="btn btn-danger" id="pic-delete"><i class="glyphicon glyphicon-trash"></i> Supprimer</button>&nbsp;
							<button type="button" class="btn btn-warning" id="pic-back"><i class="glyphicon glyphicon-arrow-left"></i> Retour</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="pic-folder-id" value="<?= $folder->ID ?>" />
	<input type="hidden" id="pic-parent-id" value="<?= $parent->ID ?>" />
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/sweetalert2-6.6.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/folder.js"></script>
</body>
</html>

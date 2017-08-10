<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();
$subfolders = $folder->GetChildren();
$db = msConnectDB();
$bEdit = $folder->IsManager($u);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Photos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
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
			<div class="col-sm-12 pic-center"><div class="pic-title"><?= $folder->Name ?></div></div>
		</div>
		<div class="row">
			<div class="col-xs-2 pic-menu<?php if($folder->IsRoot()) echo '-disabled'; ?>" title="Dossier parent" id="pic-menu-parent"><span class="glyphicon glyphicon-chevron-up"></span></div>
			<div class="col-xs-2 pic-menu<?php if(count($subfolders) == 0) echo '-disabled'; ?>" title="Sous-dossiers" id="pic-menu-subfolders"><span class="glyphicon glyphicon-chevron-down"></span></div>
			<div class="col-xs-2 pic-menu" title="Rechercher" id="pic-menu-search"><span class="glyphicon glyphicon-search"></span></div>
			<div class="col-xs-2 pic-menu<?php if(!$bEdit) echo '-disabled'; ?>" title="Importer" id="pic-menu-import"><span class="glyphicon glyphicon-import"></span></div>
			<div class="col-xs-2 pic-menu<?php if(!$bEdit) echo '-disabled'; ?> col-xs-offset-2" title="Propriétés du dossier" id="pic-menu-edit"><span class="glyphicon glyphicon-pencil"></span></div>
		</div>
		<div class="row" id="pic-subfolders" style="display:none;">
<?php
			foreach($subfolders as $id => $name) {
				echo "\t\t\t<div class=\"col-xs-6 col-sm-4 col-md-2\" style=\"padding: 2px;\"><div class=\"pic-subfolder\"><a class=\"pic-lnk-subfolder\" href=\"./?f=$id\">$name</a></div></div>\n";
			}
?>
		</div>
		<div id="pic-links">
<?php
			$sql = "SELECT id_picture, label, width, height, size, date, extension, keywords
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
				echo "\t\t\t<a href=\"" . $pic->VirtualPath() . $pic->FileName('p')
							. "\" title=\"" . htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8')
							. "\" data-pic-source=\"" . $pic->VirtualPath() . $pic->FileName('i')
							. "\" data-pic-info=\"" . $row['width'] . ' x ' . $row['height'] . ' - ' . $row['size'] . ' Ko'
							. "\" data-pic-edit=\"picture.php?f=" . $folder->ID . "&id=" . $row['id_picture']
							. "\" data-pic-date=\"" . msFormatShortDate($row['date']) . "\">";
				echo "<img src=\"" . $pic->VirtualPath() . $pic->FileName('v') . "\" alt=\"" . htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8') . "\" /></a>\n";
			}				
?>
		</div>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
	<div class="slides"></div>
	<h3 class="title"></h3>
	<h4 class="date"></h4>
	<a class="prev">‹</a>
	<a class="next">›</a>
	<a class="close">×</a>
	<a class="download"><span class="glyphicon glyphicon-cloud-download"></span></a>
<?php if($bEdit) echo "<a class=\"edit\"><span class=\"glyphicon glyphicon-pencil\"></span></a>"; ?>
	<ol class="indicator"></ol>
</div>
<?php
		if(count($subfolders) != 0)
			echo "\t\t<div class=\"row\">
			<div class=\"cols-xs-12 pic-subfolders-tip\">Astuce : clique sur le chevron bas <span class=\"glyphicon glyphicon-chevron-down\"></span> pour explorer les sous-dossiers.</div>
		</div>\n";
?>
	</div>
	<input type="hidden" id="pic-parent-id" value="<?php if(!is_null($p = $folder->GetParent())) echo $p->ID; ?>" />
	<input type="hidden" id="pic-folder-id" value="<?= $folder->ID; ?>" />
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/blueimp.gallery-2.25.2.min.js"></script>
	<script type="text/javascript" src="js/menu.js"></script>
	<script type="text/javascript" src="js/gallery.js"></script>
</body>
</html>

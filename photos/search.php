<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';
require_once '../res/php/photos-folder-class.php';
require_once '../res/php/photos-picture-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$folder = new Folder();
$search = isset($_GET['r']) ? trim($_GET['r']) : '';
$start = isset($_GET['s']) ? $_GET['s'] : '01/01/1995';
$end = isset($_GET['e']) ? $_GET['e'] : date('d/m/Y');
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
			<div class="col-sm-12 pic-center"><div class="pic-title">Recherche</div></div>
		</div>
		<div class="row pic-pad5">
			<form id="frm" class="form-horizontal" method="get">
				<div class="form-group">
					<label class="control-label col-sm-6" for="frm-keywords">Mots-clés :</label>
					<div class="col-sm-6"><input type="text" class="form-control" name="r" id="frm-keywords" placeholder="neige ski montagne..." maxlength="255" value="<?= msSecureString($search) ?>" /></div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-6" for="frm-start">Photos prises entre le :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" class="form-control pic-date" name="s" id="frm-start" placeholder="jj/mm/aaaa" value="<?= msSecureString($start)?>" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-6" for="frm-end">et le :</label>
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" class="form-control pic-date" name="e" id="frm-end" placeholder="jj/mm/aaaa" value="<?= msSecureString($end)?>" />
						</div>
					</div>
				</div>
				<div class="col-sm-12 pic-center">
					<button type="submit" class="btn btn-warning">Rechercher</button>&nbsp;
					<button type="button" class="btn btn-warning" id="pic-back">Retour</button>
				</div>
			</form>
		</div>
		<div id="pic-links">
<?php
			$sql = "SELECT p.*, MATCH(label, keywords) AGAINST(:search IN BOOLEAN MODE) AS score 
					FROM pic_data p WHERE";
			if($search!= '')
				$sql .= " MATCH(label, keywords) AGAINST(:search IN BOOLEAN MODE) AND";
			$sql .= " DATE(p.date) >= :deb AND DATE(p.date) <= :fin ORDER BY score DESC, p.date DESC, p.id_picture DESC LIMIT 200";
			$rs = $db->prepare($sql);
			$rs->execute(array(	':search'	=> $search,
								':deb'		=> msFormatMysqlDate($start, date('Y-m-d', mktime(0, 0, 0, 1, 1, 1995))),
								':fin'		=> msFormatMysqlDate($end, date('Y-m-d'))
			));
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
		</div>
	</div>
	<input type="hidden" id="pic-folder-id" value="<?= $folder->ID; ?>" />
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker.fr-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/blueimp.gallery-2.25.2.min.js"></script>
	<script type="text/javascript" src="js/search.js"></script>
	<script type="text/javascript" src="js/gallery.js"></script>
</body>
</html>

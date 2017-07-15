<?php

//+++++ Include +++++
require_once 'profile-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Profil Fotlan</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
	<script type="text/javascript">
	function ScreenSize() {
		document.getElementById('screen_width').innerHTML = screen.width;
		document.getElementById('screen_height').innerHTML = screen.height;
	}
	</script>
</head>
<body onload="ScreenSize();">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6"><a href="/hp/"><img src="/res/img/fotlan.png" alt="FotLan" width="140" height="50" /></a></div>
			<div class="col-xs-6 profile"><?php $u->Display(''); ?>
			</div>
		</div>
		<div class="row">
			<blockquote>
				<b>Utilisateur</b> : <?= $u->Username ?><br />
				<b>Admin</b> : <?= $u->IsAdministrator ?><br />
				<b>MySQL Host</b> : <?= get_cfg_var('fotlan.mysql.host') ?><br />
				<b>Largeur &eacute;cran</b> : <span id="screen_width"></span><br />
				<b>Hauteur &eacute;cran</b> : <span id="screen_height"></span>
			</blockquote>
		</div>
	</div>
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
</body>
</html>

<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Le Compte est Bon</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
	<link rel="stylesheet" type="text/css" href="css/ceb.css" />
</head>
<body onload="javascript:Tirage();">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6"><a href="/hp/"><img src="/res/img/fotlan.png" alt="FotLan" width="140" height="50" /></a></div>
			<div class="col-xs-6 profile"><?php $u->Display(''); ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-12 ceb-center"><div class="ceb-title">Le Compte est Bon</div></div>
		</div>
		<div class="row">
			<div class="col-lg-12 ceb-container"><div id="ceb-target"></div></div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-md-2 ceb-center"><div id="c1" class="ceb-card"></div></div>
			<div class="col-xs-4 col-sm-4 col-md-2 ceb-center"><div id="c2" class="ceb-card"></div></div>
			<div class="col-xs-4 col-sm-4 col-md-2 ceb-center"><div id="c3" class="ceb-card"></div></div>
			<div class="col-xs-4 col-sm-4 col-md-2 ceb-center"><div id="c4" class="ceb-card"></div></div>
			<div class="col-xs-4 col-sm-4 col-md-2 ceb-center"><div id="c5" class="ceb-card"></div></div>
			<div class="col-xs-4 col-sm-4 col-md-2 ceb-center"><div id="c6" class="ceb-card"></div></div>
		</div>
		<div class="row">
			<div class="col-lg-12 ceb-center"><button type="button" class="btn btn-primary" onclick="javascript:Tirage();"><i class="glyphicon glyphicon-refresh"></i> Nouveau tirage</button></div>
		</div>
	</div>
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/ceb.js"></script>
</body>
</html>

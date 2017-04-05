<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
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
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="https://www.google.com/"><img src="img/google.png" alt="Google" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="https://mail.google.com/"><img src="img/gmail.png" alt="Fotlan GMail" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="https://www.qwant.com/"><img src="img/qwant.png" alt="Qwant" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="#"><img src="img/music.png" alt="Fotlan Player" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="#"><img src="img/camera.png" alt="Phototh&egrave;que" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="#"><img src="img/vtt.png" alt="Traces VTT" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="#"><img src="img/banque.png" alt="Comptes bancaires" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="#"><img src="img/radio.png" alt="Radios" title="Radios" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="#"><img src="img/library.png" alt="Médiathèque" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="/contacts/"><img src="img/contacts.png" alt="Carnet d'adresses" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="/corsica2010/"><img src="img/corsica.png" alt="Corsica 2010" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="http://www.lachainemeteo.com/meteo/courcouronnes/france/prevision_meteo_courcouronnes_france_ville_1394_0.php"><img src="img/meteo.png" alt="Meteo" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="https://mabanque.bnpparibas/"><img src="img/bnp.png" alt="BNP Paribas" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="http://www.allocine.fr/seance/salle_gen_csalle=B0059.html"><img src="img/allocine.png" alt="Cin&eacute;ma Evry" /></a></div>
			<div class="col-xs-4 col-sm-3 col-md-2 hp-bloc"><a class="hp-link" href="/ceb/"><img src="img/cv.png" alt="CV" /></a></div>
		</div>
	</div>
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
</body>
</html>

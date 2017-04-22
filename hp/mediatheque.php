<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Médiathèque : Liste lecteurs</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3"><a href="/hp/"><img src="/res/img/fotlan.png" alt="FotLan" width="140" height="50" /></a></div>
			<div class="col-md-3" style="vertical-align: middle;">
				<div>1. Cliquer sur <b>Se connecter</b></div>
				<div>2. Copier/Coller <b>Numéro de carte/Date de naissance</b></div>
				<div>3. Cliquer sur <b>OK</b></div>
			</div>
			<div class="col-md-3">
				<div class="text-right">Christophe : <input type="text" value="2300976189" onfocus="this.select();" /><input type="text" value="31071967" onfocus="this.select();" /></div>
				<div class="text-right">Murielle : <input type="text" value="2301023334" onfocus="this.select();" /><input type="text" value="30031967" onfocus="this.select();" /></div>
				<div class="text-right">Elise : <input type="text" value="2300976147" onfocus="this.select();" /><input type="text" value="11051999" onfocus="this.select();" /></div>
				<div class="text-right">Paul : <input type="text" value="2300985423" onfocus="this.select();" /><input type="text" value="27072001" onfocus="this.select();" /></div>
				<div class="text-right">Victor : <input type="text" value="2300989197" onfocus="this.select();" /><input type="text" value="18072003" onfocus="this.select();" /></div>
			</div>
			<div class="col-md-3 profile"><?php $u->Display(''); ?></div>
		</div>
		<div class="row col-md-12">
			<iframe class="hp-frame" src="http://mediatheques.agglo-evry.fr/EXPLOITATION"></iframe>
		</div>
	</div>
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
</body>
</html>

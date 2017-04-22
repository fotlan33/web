<?php

//+++++ Include +++++
require_once '../res/php/profile-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Carnet d'adresses</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap.dataTables-1.10.13.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
	<link rel="stylesheet" type="text/css" href="css/contacts.css" />
</head>
<body onload="LoadContacts();">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6"><a href="/hp/"><img src="/res/img/fotlan.png" alt="FotLan" width="140" height="50" /></a></div>
			<div class="col-xs-6 profile"><?php $u->Display(''); ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 ctc-center"><div class="ctc-title">Carnet d'adresses</div></div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table id="ctc-table" class="table table-striped table-hover dt-responsive nowrap">
					<thead>
						<tr>
							<th>NOM Prénom</th>
							<th class="hidden-xs">Ville</th>
							<th class="hidden-xs">Téléphone</th>
							<th class="hidden-xs">Mobile</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/jquery.dataTables-1.10.13.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.dataTables-1.10.13.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/contacts.js"></script>
</body>
</html>

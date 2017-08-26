<?php

//+++++ Include +++++
require_once '../res/php/ms.php';
require_once '../res/php/profile-class.php';

//+++++ Parameters +++++
$u = new FotlanProfile();
$id = (isset($_GET['id']) && trim($_GET['id']) != '') ? trim($_GET['id']) : 0;

//+++++ SQL request +++++
$db = msConnectDB();
$rs = $db->prepare("SELECT * FROM t_contacts WHERE id_contact = :id");
$rs->execute(array(':id' => $id));

//+++++ Data +++++
$row = $rs->fetch(PDO::FETCH_ASSOC);
if(!$row) {
	$row = array(	'id_contact' => 0,
					'titre' => '',
					'prenom' => '',
					'nom' => '',
					'pseudo' => '',
					'naissance' => null,
					'fonction' => '',
					'societe' => '',
					'priv_email' => '',
					'priv_web' => '',
					'priv_tel' => '',
					'priv_fax' => '',
					'priv_gsm' => '',
					'priv_adresse' => '',
					'priv_adresse_ext' => '',
					'priv_cp' => '',
					'priv_ville' => '',
					'priv_pays' => '',
					'pro_email' => '',
					'pro_web' => '',
					'pro_tel' => '',
					'pro_fax' => '',
					'pro_gsm' => '',
					'pro_adresse' => '',
					'pro_adresse_ext' => '',
					'pro_cp' => '',
					'pro_ville' => '',
					'pro_pays' => '',
					'remarques' => '',
					'mise_a_jour' => date('Y-m-d') );
}
$titre = trim($row['titre'] . ' ' . $row['prenom'] . ' ' . $row['nom']);
if($titre == '')
	$titre = 'Nouveau contact';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>FotLan - Carnet d'adresses</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap.datepicker3-1.6.4.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
	<link rel="stylesheet" type="text/css" href="css/contacts.css" />
</head>
<body onload="InitEdition();">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6"><a href="/hp/"><img src="/res/img/fotlan.png" alt="FotLan" width="140" height="50" /></a></div>
			<div class="col-xs-6 profile"><?php $u->Display(''); ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 ctc-center"><div class="ctc-title"><?= $titre ?></div></div>
		</div>
		<div class="row">
			<div class="col-sm-12 ctc-center"><a href="." class="ctc-link">Retour à liste de contacts</a></div>
		</div>
		<div class="row ctc-row">
			<form id="frm" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-2" for="frm-titre">Civilité :</label>
					<div class="col-sm-10"><select class="form-control" id="frm-titre">
						<option value="" label="aucun"></option>
						<option value="Dr."<?php if($row['titre'] == 'Dr.') echo ' selected="selected"' ?>>Dr.</option>
						<option value="M."<?php if($row['titre'] == 'M.') echo ' selected="selected"' ?>>M.</option>
						<option value="Me"<?php if($row['titre'] == 'Me') echo ' selected="selected"' ?>>Me</option>
						<option value="Mlle"<?php if($row['titre'] == 'Mlle') echo ' selected="selected"' ?>>Mlle</option>
						<option value="Mme"<?php if($row['titre'] == 'Mme') echo ' selected="selected"' ?>>Mme</option>
					</select></div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="frm-prenom">Prénom :</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="frm-prenom" placeholder="Prénom" maxlength="50" value="<?= msSecureString($row['prenom']) ?>" /></div>
				</div>
				<div class="form-group" id="ctc-nom-group">
					<label class="control-label col-sm-2" for="frm-nom">NOM :</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="frm-nom" placeholder="NOM" maxlength="50" value="<?= msSecureString($row['nom']) ?>" />
						<span class="glyphicon form-control-feedback"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="frm-pseudo">Pseudonyme :</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="frm-pseudo" placeholder="Pseudonyme" maxlength="50" value="<?= msSecureString($row['pseudo']) ?>" /></div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="frm-naissance">Naissance :</label>
					<div class="col-sm-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" class="form-control" id="frm-naissance" placeholder="jj/mm/aaaa" value="<?= msFormatStandardDate($row['naissance']) ?>" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="frm-fonction">Fonction :</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="frm-fonction" placeholder="Commercial, Agent, Médecin, ..." maxlength="100" value="<?= msSecureString($row['fonction']) ?>" /></div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="frm-societe">Société :</label>
					<div class="col-sm-10"><input type="text" class="form-control" id="frm-societe" placeholder="Société / Dir. / Dpt." maxlength="100" value="<?= msSecureString($row['societe']) ?>" /></div>
				</div>
				<div class="panel-group">
					<div class="panel panel-warning">
						<div class="panel-heading">Coordonnées personnelles</div>
						<div class="panel-body">
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-email">E-mail :</label>
								<div class="col-sm-10"><input type="email" class="form-control" id="frm-priv-email" placeholder="E-mail" maxlength="100" value="<?= msSecureString($row['priv_email']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-web">Web :</label>
								<div class="col-sm-10"><input type="url" class="form-control" id="frm-priv-web" placeholder="Site Web" maxlength="100" value="<?= msSecureString($row['priv_web']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-tel">Tél. :</label>
								<div class="col-sm-10"><input type="tel" class="form-control" id="frm-priv-tel" placeholder="Téléphone fixe" maxlength="50" value="<?= msSecureString($row['priv_tel']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-gsm">Mobile :</label>
								<div class="col-sm-10"><input type="tel" class="form-control" id="frm-priv-gsm" placeholder="Téléphone mobile" maxlength="50" value="<?= msSecureString($row['priv_gsm']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-adresse">Adresse :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-priv-adresse" placeholder="Adresse" maxlength="200" value="<?= msSecureString($row['priv_adresse']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-adresse-ext">&nbsp;</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-priv-adresse-ext" placeholder="Complément d'adresse" maxlength="100" value="<?= msSecureString($row['priv_adresse_ext']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-cp">Code postal :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-priv-cp" placeholder="Code postal" maxlength="10" value="<?= msSecureString($row['priv_cp']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-ville">Ville :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-priv-ville" placeholder="Ville" maxlength="50" value="<?= msSecureString($row['priv_ville']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-priv-pays">Pays :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-priv-pays" placeholder="Pays" maxlength="50" value="<?= msSecureString($row['priv_pays']) ?>" /></div>
							</div>
						</div>
					</div>
					<div class="panel panel-warning">
						<div class="panel-heading">Coordonnées professionnelles</div>
						<div class="panel-body">
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-email">E-mail :</label>
								<div class="col-sm-10"><input type="email" class="form-control" id="frm-pro-email" placeholder="E-mail" maxlength="100" value="<?= msSecureString($row['pro_email']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-web">Web :</label>
								<div class="col-sm-10"><input type="url" class="form-control" id="frm-pro-web" placeholder="Site Web" maxlength="100" value="<?= msSecureString($row['pro_web']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-tel">Tél. :</label>
								<div class="col-sm-10"><input type="tel" class="form-control" id="frm-pro-tel" placeholder="Téléphone fixe" maxlength="50" value="<?= msSecureString($row['pro_tel']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-gsm">Mobile :</label>
								<div class="col-sm-10"><input type="tel" class="form-control" id="frm-pro-gsm" placeholder="Téléphone mobile" maxlength="50" value="<?= msSecureString($row['pro_gsm']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-adresse">Adresse :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-pro-adresse" placeholder="Adresse" maxlength="200" value="<?= msSecureString($row['pro_adresse']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-adresse-ext">&nbsp;</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-pro-adresse-ext" placeholder="Complément d'adresse" maxlength="100" value="<?= msSecureString($row['pro_adresse_ext']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-cp">Code postal :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-pro-cp" placeholder="Code postal" maxlength="10" value="<?= msSecureString($row['pro_cp']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-ville">Ville :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-pro-ville" placeholder="Ville" maxlength="50" value="<?= msSecureString($row['pro_ville']) ?>" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="frm-pro-pays">Pays :</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="frm-pro-pays" placeholder="Pays" maxlength="50" value="<?= msSecureString($row['pro_pays']) ?>" /></div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="frm-remarques">Notes :</label>
					<div class="col-sm-10"><textarea class="form-control" id="frm-remarques"><?= msSecureString($row['remarques']) ?></textarea></div>
				</div>
				<div id="frm-alert" class="alert alert-block alert-success ctc-alert col-sm-12"></div>
<?php if($u->CheckAuthorization('CONTACTS', 'RW')) echo '				<div class="col-sm-offset-2 col-sm-10"><button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-save"></span> Enregistrer</button></div>';?>
				<input type="hidden" id="frm-id" value="<?= msSecureString($row['id_contact']) ?>" />
			</form>
		</div>
		<div class="row">
			<div class="col-sm-12 ctc-update">modifié le <?= msFormatStandardDate($row['mise_a_jour']) ?></div>
		</div>
		<div class="row">
			<div class="col-sm-12 ctc-center"><a id="ctc-bottom" href="." class="ctc-link">Retour à liste de contacts</a></div>
		</div>
	</div>
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap.datepicker.fr-1.6.4.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/contacts.js"></script>
</body>
</html>

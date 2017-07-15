<?php

// Includes
error_reporting(E_ALL);
set_time_limit(300);
require_once '../res/php/gapi/autoload.php';
require_once '../res/php/ms.php';
require_once '../res/php/gapi.php';
require_once '../res/php/profile-class.php';
$u = new FotlanProfile();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Synchronisation Google</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="/res/css/bootstrap-3.3.7.min.css" />
	<link rel="stylesheet" type="text/css" href="/res/css/fotlan.css" />
	<link rel="stylesheet" type="text/css" href="css/contacts.css" />
</head>
<body onload="InitSynchro();">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6"><a href="/hp/"><img src="/res/img/fotlan.png" alt="FotLan" width="140" height="50" /></a></div>
			<div class="col-xs-6 profile"><?php $u->Display(''); ?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 ctc-center"><div class="ctc-title">Synchronisation Google</div></div>
		</div>
		<div class="row">
			<div class="col-sm-12 ctc-center"><a href="." class="ctc-link">Retour à liste de contacts</a></div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Contact</th>
							<th>Propri&eacute;t&eacute;</th>
							<th>Valeur Google</th>
							<th>Op&eacute;ration</th>
							<th>Valeur Fotlan</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
<?php 
if(isset($_GET['code'])) {

	// Google Token
	$auth_code = $_GET['code'];
	$accesstoken = gapiToken($auth_code);
	$_SESSION['GAPI_TOKEN'] = $accesstoken;

	// Build updates
	$contacts = array();

	// Retrieve Google Contacts Group ID [Commun]
	$url = 'https://www.google.com/m8/feeds/groups/default/full?max-results=200&alt=json&v=3.0&oauth_token=' . $accesstoken;
	$json_response = gapiCurl($url);
	$groups = json_decode($json_response, true);
	$group_id = '';
	if(!empty($groups['feed']['entry'])) {
		foreach($groups['feed']['entry'] as $group) {
			if($group['title']['$t'] == 'Commun') {
				$group_id = $group['id']['$t'];
				break;
			}
		}
	}

	// Retrieve Google Contacts Properties
	if($group_id != '') {
		$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=200&alt=json&v=3.0&oauth_token=' . $accesstoken
			. '&group=' . urlencode($group_id);
		$json_response = gapiCurl($url);
		$g_contacts = json_decode($json_response, true);
		if(!empty($g_contacts['feed']['entry'])) {
			foreach($g_contacts['feed']['entry'] as $g_contact) {
				$fullname = $g_contact['title']['$t'];
				$contacts[$fullname]['id']['google'] = null;
				foreach($g_contact['link'] as $link) {
					if($link['rel'] == 'edit') {
						$contacts[$fullname]['id']['google'] = $link['href'];
						break;
					}
				}
				$contacts[$fullname]['name']['google'] = $g_contact['gd$name']['gd$familyName']['$t'];
				$contacts[$fullname]['firstname']['google'] = $g_contact['gd$name']['gd$givenName']['$t'];
				$contacts[$fullname]['nickname']['google'] = $g_contact['gContact$nickname']['$t'];
				$contacts[$fullname]['birthday']['google'] = $g_contact['gContact$birthday']['when'];
				$contacts[$fullname]['company']['google'] = $g_contact['gd$organization'][0]['gd$orgName']['$t'];
				$contacts[$fullname]['title']['google'] = $g_contact['gd$organization'][0]['gd$orgTitle']['$t'];
				$contacts[$fullname]['comments']['google'] = $g_contact['content']['$t'];
				$contacts[$fullname]['updated']['google'] = new DateTime($g_contact['updated']['$t']);
				// Emails
				$contacts[$fullname]['priv_email']['google'] = null;
				$contacts[$fullname]['pro_email']['google'] = null;
				$emails = $g_contact['gd$email'];
				foreach($emails as $email) {
					if(strpos($email['rel'], 'home') !== false) {
						$contacts[$fullname]['priv_email']['google'] = $email['address'];
					} elseif(strpos($email['rel'], 'work') !== false) {
						$contacts[$fullname]['pro_email']['google'] = $email['address'];
					}
				}
				// Websites
				$contacts[$fullname]['priv_web']['google'] = null;
				$contacts[$fullname]['pro_web']['google'] = null;
				$webs = $g_contact['gContact$website'];
				foreach($webs as $web) {
					if($web['rel'] == 'profile') {
						$contacts[$fullname]['priv_web']['google'] = $web['href'];
					} elseif($web['rel'] == 'work') {
						$contacts[$fullname]['pro_web']['google'] = $web['href'];
					}
				}
				// Phones
				$contacts[$fullname]['priv_tel']['google'] = null;
				$contacts[$fullname]['priv_gsm']['google'] = null;
				$contacts[$fullname]['pro_tel']['google'] = null;
				$contacts[$fullname]['pro_gsm']['google'] = null;
				$tels = $g_contact['gd$phoneNumber'];
				foreach($tels as $tel) {
					if(strpos($tel['rel'], 'home') !== false) {
						$contacts[$fullname]['priv_tel']['google'] = $tel['$t'];
					} elseif(strpos($tel['rel'], 'work') !== false) {
						$contacts[$fullname]['pro_tel']['google'] = $tel['$t'];
					} elseif(strpos($tel['rel'], 'mobile') !== false) {
						$contacts[$fullname]['priv_gsm']['google'] = $tel['$t'];
					} elseif(strpos($tel['label'], 'Mobile') !== false) {
						$contacts[$fullname]['pro_gsm']['google'] = $tel['$t'];
					}
				}
				// Addresses
				$contacts[$fullname]['priv_adresse']['google'] = null;
				$contacts[$fullname]['priv_adresse_ext']['google'] = null;
				$contacts[$fullname]['priv_cp']['google'] = null;
				$contacts[$fullname]['priv_ville']['google'] = null;
				$contacts[$fullname]['priv_pays']['google'] = null;
				$contacts[$fullname]['pro_adresse']['google'] = null;
				$contacts[$fullname]['pro_adresse_ext']['google'] = null;
				$contacts[$fullname]['pro_cp']['google'] = null;
				$contacts[$fullname]['pro_ville']['google'] = null;
				$contacts[$fullname]['pro_pays']['google'] = null;
				$adds = $g_contact['gd$structuredPostalAddress'];
				foreach($adds as $add) {
					if(strpos($add['rel'], 'home') !== false) {
						if(array_key_exists('gd$street', $add)) $contacts[$fullname]['priv_adresse']['google'] = $add['gd$street']['$t'];
						if(array_key_exists('gd$neighborhood', $add)) $contacts[$fullname]['priv_adresse_ext']['google'] = $add['gd$neighborhood']['$t'];
						if(array_key_exists('gd$postcode', $add)) $contacts[$fullname]['priv_cp']['google'] = $add['gd$postcode']['$t'];
						if(array_key_exists('gd$city', $add)) $contacts[$fullname]['priv_ville']['google'] = $add['gd$city']['$t'];
						if(array_key_exists('gd$country', $add)) $contacts[$fullname]['priv_pays']['google'] = $add['gd$country']['$t'];
					} elseif(strpos($add['rel'], 'work') !== false) {
						if(array_key_exists('gd$street', $add)) $contacts[$fullname]['pro_adresse']['google'] = $add['gd$street']['$t'];
						if(array_key_exists('gd$neighborhood', $add)) $contacts[$fullname]['pro_adresse_ext']['google'] = $add['gd$neighborhood']['$t'];
						if(array_key_exists('gd$postcode', $add)) $contacts[$fullname]['pro_cp']['google'] = $add['gd$postcode']['$t'];
						if(array_key_exists('gd$city', $add)) $contacts[$fullname]['pro_ville']['google'] = $add['gd$city']['$t'];
						if(array_key_exists('gd$country', $add)) $contacts[$fullname]['pro_pays']['google'] = $add['gd$country']['$t'];					}
				}
			}
		}
	}

	// Retrieve Properties from MySQL
	if(count($contacts) > 1) {
		$db = msConnectDB();
		$sql = "SELECT * FROM t_contacts ORDER BY nom ASC, prenom ASC, priv_ville ASC, id_contact ASC";
		$rs = $db->query($sql);
		while($row = $rs->fetch(PDO::FETCH_ASSOC))
		{
			$fullname = trim($row['prenom'] . ' ' . $row['nom']);
			$contacts[$fullname]['id']['mysql'] = (string) $row['id_contact'];
			$contacts[$fullname]['name']['mysql'] = $row['nom'];
			$contacts[$fullname]['firstname']['mysql'] = $row['prenom'];
			$contacts[$fullname]['nickname']['mysql'] = $row['pseudo'];
			$contacts[$fullname]['birthday']['mysql'] = $row['naissance'];
			$contacts[$fullname]['company']['mysql'] = $row['societe'];
			$contacts[$fullname]['title']['mysql'] = $row['fonction'];
			$contacts[$fullname]['comments']['mysql'] = $row['remarques'];
			$contacts[$fullname]['priv_email']['mysql'] = $row['priv_email'];
			$contacts[$fullname]['pro_email']['mysql'] = $row['pro_email'];
			$contacts[$fullname]['priv_web']['mysql'] = $row['priv_web'];
			$contacts[$fullname]['pro_web']['mysql'] = $row['pro_web'];
			$contacts[$fullname]['priv_tel']['mysql'] = $row['priv_tel'];
			$contacts[$fullname]['pro_tel']['mysql'] = $row['pro_tel'];
			$contacts[$fullname]['priv_gsm']['mysql'] = $row['priv_gsm'];
			$contacts[$fullname]['pro_gsm']['mysql'] = $row['pro_gsm'];
			$contacts[$fullname]['priv_adresse']['mysql'] = $row['priv_adresse'];
			$contacts[$fullname]['priv_adresse_ext']['mysql'] = $row['priv_adresse_ext'];
			$contacts[$fullname]['priv_cp']['mysql'] = $row['priv_cp'];
			$contacts[$fullname]['priv_ville']['mysql'] = $row['priv_ville'];
			$contacts[$fullname]['priv_pays']['mysql'] = $row['priv_pays'];
			$contacts[$fullname]['pro_adresse']['mysql'] = $row['pro_adresse'];
			$contacts[$fullname]['pro_adresse_ext']['mysql'] = $row['pro_adresse_ext'];
			$contacts[$fullname]['pro_cp']['mysql'] = $row['pro_cp'];
			$contacts[$fullname]['pro_ville']['mysql'] = $row['pro_ville'];
			$contacts[$fullname]['pro_pays']['mysql'] = $row['pro_pays'];
			$contacts[$fullname]['updated']['mysql'] = new DateTime($row['mise_a_jour']);
		}
	}
	
	// Find and list updates
	if(count($contacts) > 1) {
		
		// Display updates
		$n = 0;
		foreach($contacts as $name => $contact) {
			if(array_key_exists('mysql', $contact['id']) && array_key_exists('google', $contact['id'])) {
				$way = ($contact['updated']['google'] > $contact['updated']['mysql']) ? 'glyphicon-circle-arrow-right' : 'glyphicon-circle-arrow-left';
				foreach($contact as $propname => $propvalue) {
					if($propname != 'updated' && $propname != 'id' && cr2nl($propvalue['mysql']) != cr2nl($propvalue['google'])) {
						$n++;
						echo "\t\t\t\t\t\t<tr id=\"prop_" . $n . "\">\n";
						echo "\t\t\t\t\t\t\t<td>" . $name . "</td>\n";
						echo "\t\t\t\t\t\t\t<td>" . $propname . "</td>\n";
						echo "\t\t\t\t\t\t\t<td>" . nl2br($propvalue['google']) . "</td>\n";
						echo "\t\t\t\t\t\t\t<td class=\"ctc-way\"><i class=\"glyphicon " . $way . "\"></i></td>\n";
						echo "\t\t\t\t\t\t\t<td>" . nl2br($propvalue['mysql']) . "</td>\n";
						echo "\t\t\t\t\t\t\t<td class=\"ctc-action\"><span class=\"glyphicon glyphicon-flash\"></span>";
						echo "<input type=\"hidden\" name=\"google-id\" value=\"" . htmlspecialchars($contact['id']['google'], ENT_HTML5) . "\" />";
						echo "<input type=\"hidden\" name=\"fotlan-id\" value=\"" . htmlspecialchars($contact['id']['mysql'], ENT_HTML5) . "\" />";
						echo "<input type=\"hidden\" name=\"google-value\" value=\"" . htmlspecialchars($propvalue['google'], ENT_HTML5) . "\" />";
						echo "<input type=\"hidden\" name=\"fotlan-value\" value=\"" . htmlspecialchars($propvalue['mysql'], ENT_HTML5) . "\" />";
						echo "<input type=\"hidden\" name=\"property-name\" value=\"" . htmlspecialchars($propname, ENT_HTML5) . "\" />";
						echo "</td>\n";
						echo "\t\t\t\t\t\t</tr>\n";
					}
				}
			} elseif(array_key_exists('mysql', $contact['id']) && !array_key_exists('google', $contact['id'])) {
				$n++;
				echo "\t\t\t\t\t\t<tr id=\"prop_" . $n . "\">\n";
				echo "\t\t\t\t\t\t\t<td>" . $name . "</td>\n";
				echo "\t\t\t\t\t\t\t<td>fullname</td>\n";
				echo "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
				echo "\t\t\t\t\t\t\t<td class=\"ctc-way\"><i class=\"glyphicon glyphicon-circle-arrow-left\"></i></td>\n";
				echo "\t\t\t\t\t\t\t<td>" . $name . "</td>\n";
				echo "\t\t\t\t\t\t\t<td class=\"ctc-action\"><span class=\"glyphicon glyphicon-flash\"></span>";
				echo "<input type=\"hidden\" name=\"google-id\" value=\"0\" />";
				echo "<input type=\"hidden\" name=\"fotlan-id\" value=\"" . htmlspecialchars($contact['id']['mysql'], ENT_HTML5) . "\" />";
				echo "<input type=\"hidden\" name=\"google-value\" />";
				echo "<input type=\"hidden\" name=\"fotlan-value\" value=\"" . htmlspecialchars($contact['firstname']['mysql'], ENT_HTML5) . "\" />";
				echo "<input type=\"hidden\" name=\"property-name\" value=\"" . htmlspecialchars($contact['name']['mysql'], ENT_HTML5) . "\" />";
				echo "</td>\n";
				echo "\t\t\t\t\t\t</tr>\n";
			} elseif(!array_key_exists('mysql', $contact['id']) && array_key_exists('google', $contact['id'])) {
				$n++;
				echo "\t\t\t\t\t\t<tr id=\"prop_" . $n . "\">\n";
				echo "\t\t\t\t\t\t\t<td>" . $name . "</td>\n";
				echo "\t\t\t\t\t\t\t<td>fullname</td>\n";
				echo "\t\t\t\t\t\t\t<td>" . $name . "</td>\n";
				echo "\t\t\t\t\t\t\t<td class=\"ctc-way\"><i class=\"glyphicon glyphicon-circle-arrow-right\"></i></td>\n";
				echo "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
				echo "\t\t\t\t\t\t\t<td class=\"ctc-action\"><span class=\"glyphicon glyphicon-flash\"></span>";
				echo "<input type=\"hidden\" name=\"google-id\" value=\"" . htmlspecialchars($contact['id']['google'], ENT_HTML5) . "\" />";
				echo "<input type=\"hidden\" name=\"fotlan-id\" value=\"0\" />";
				echo "<input type=\"hidden\" name=\"google-value\" value=\"" . htmlspecialchars($contact['firstname']['google'], ENT_HTML5) . "\" />";
				echo "<input type=\"hidden\" name=\"fotlan-value\" />";
				echo "<input type=\"hidden\" name=\"property-name\" value=\"" . htmlspecialchars($contact['name']['google'], ENT_HTML5) . "\" />";
				echo "</td>\n";
				echo "\t\t\t\t\t\t</tr>\n";
			}
		}

	} else {
		echo '<tr><td colspan="6">Nothing to sync.</td></tr>';
	}
		
} else {
	echo '<tr><td colspan="6">No autorisation</td></tr>';
}
?>
					</tbody>
				</table>
				<input type="hidden" id="group-id" value="<?= $group_id ?>" />
			</div>
		</div>
	</div>
	<script type="text/javascript" src="/res/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="/res/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="/res/js/fotlan.js"></script>
	<script type="text/javascript" src="js/contacts.js"></script>
</body>
</html>

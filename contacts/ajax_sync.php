<?php

require_once '../res/php/gapi/autoload.php';
require_once '../res/php/ms.php';
require_once '../res/php/gapi.php';
require_once '../res/php/profile-class.php';

$group_id = (isset($_POST['group_id'])) ? trim($_POST['group_id']) : '';
$parent_id = (isset($_POST['parent_id'])) ? trim($_POST['parent_id']) : '';
$sync_way = (isset($_POST['sync_way'])) ? trim($_POST['sync_way']) : '';
$google_id = (isset($_POST['google_id'])) ? trim($_POST['google_id']) : '0';
$fotlan_id = (isset($_POST['fotlan_id'])) ? trim($_POST['fotlan_id']) : '0';
$property_name = (isset($_POST['property_name'])) ? trim($_POST['property_name']) : '';
$u = new FotlanProfile();

$response = array(	'parent_id'	=> $parent_id,
					'mode'		=> 'NO_WAY',
					'err_no'	=> 100,
					'err_text'	=> 'No way !'
);

//+++++ Check autorization +++++
if($u->CheckAuthorization('CONTACTS', 'RW')) {
	
	if($sync_way == 'G2F' && $google_id != '0') {
	
		$db = msConnectDB();
		
		if($fotlan_id == '0') {
			
			// Insert contact
			$sql = "INSERT INTO t_contacts (nom, prenom)
					VALUES (:nom, :prenom)";
			$rs = $db->prepare($sql);
			$firstname = (isset($_POST['google_value'])) ? trim($_POST['google_value']) : '';
			$rs->execute(array(':nom' => $property_name, ':prenom' => $firstname));
			$response['mode'] = 'FOTLAN_CREATED';
			$response['err_no'] = 0;
			$response['err_text'] = '';
	
		} else {
			
			//Update contact
			$value = (isset($_POST['google_value'])) ? trim($_POST['google_value']) : '';
			switch($property_name) {
				case 'name':
					$sql = "nom = :nom";
					$data = array(':nom' => msFormatString($value, '[INCONNU]', 50));
					break;
				case 'firstname':
					$sql = "prenom = :prenom";
					$data = array(':prenom' => msFormatString($value, null, 50));
					break;
				case 'nickname':
					$sql = "pseudo = :pseudo";
					$data = array(':pseudo' => msFormatString($value, null, 50));
					break;
				case 'birthday':
					$birthday = new DateTime($value);
					$sql = "naissance = :naissance";
					$data = array(':naissance' => $birthday->format('Y-m-d'));
					break;
				case 'company':
					$sql = "societe = :societe";
					$data = array(':societe' => msFormatString($value, null, 100));
					break;
				case 'title':
					$sql = "fonction = :fonction";
					$data = array(':fonction' => msFormatString($value, null, 100));
					break;
				case 'comments':
					$sql = "remarques = :remarques";
					$data = array(':remarques' => msFormatString($value, null, 1000));
					break;
				case 'priv_email':
					$sql = "priv_email = :priv_email";
					$data = array(':priv_email' => msFormatString($value, null, 100));
					break;
				case 'pro_email':
					$sql = "pro_email = :pro_email";
					$data = array(':pro_email' => msFormatString($value, null, 100));
					break;
				case 'priv_web':
					$sql = "priv_web = :priv_web";
					$data = array(':priv_web' => msFormatString($value, null, 100));
					break;
				case 'pro_web':
					$sql = "pro_web = :pro_web";
					$data = array(':pro_web' => msFormatString($value, null, 100));
					break;
				case 'priv_tel':
					$sql = "priv_tel = :priv_tel";
					$data = array(':priv_tel' => msFormatString($value, null, 50));
					break;
				case 'pro_tel':
					$sql = "pro_tel = :pro_tel";
					$data = array(':pro_tel' => msFormatString($value, null, 50));
					break;
				case 'priv_gsm':
					$sql = "priv_gsm = :priv_gsm";
					$data = array(':priv_gsm' => msFormatString($value, null, 50));
					break;
				case 'pro_gsm':
					$sql = "pro_gsm = :pro_gsm";
					$data = array(':pro_gsm' => msFormatString($value, null, 50));
					break;
				case 'priv_adresse':
					$sql = "priv_adresse = :priv_adresse";
					$data = array(':priv_adresse' => msFormatString($value, null, 200));
					break;
				case 'priv_adresse_ext':
					$sql = "priv_adresse_ext = :priv_adresse_ext";
					$data = array(':priv_adresse_ext' => msFormatString($value, null, 100));
					break;
				case 'priv_cp':
					$sql = "priv_cp = :priv_cp";
					$data = array(':priv_cp' => msFormatString($value, null, 10));
					break;
				case 'priv_ville':
					$sql = "priv_ville = :priv_ville";
					$data = array(':priv_ville' => msFormatString($value, null, 50));
					break;
				case 'priv_pays':
					$sql = "priv_pays = :priv_pays";
					$data = array(':priv_pays' => msFormatString($value, null, 50));
					break;
				case 'pro_adresse':
					$sql = "pro_adresse = :pro_adresse";
					$data = array(':pro_adresse' => msFormatString($value, null, 200));
					break;
				case 'pro_adresse_ext':
					$sql = "pro_adresse_ext = :pro_adresse_ext";
					$data = array(':pro_adresse_ext' => msFormatString($value, null, 100));
					break;
				case 'pro_cp':
					$sql = "pro_cp = :pro_cp";
					$data = array(':pro_cp' => msFormatString($value, null, 10));
					break;
				case 'pro_ville':
					$sql = "priv_ville = :pro_ville";
					$data = array(':pro_ville' => msFormatString($value, null, 50));
					break;
				case 'pro_pays':
					$sql = "pro_pays = :pro_pays";
					$data = array(':pro_pays' => msFormatString($value, null, 50));
					break;
			}
			$sql = "UPDATE t_contacts SET " . $sql . " WHERE id_contact = :id";
			$rs = $db->prepare($sql);
			$data[':id'] = $fotlan_id;
			$rs->execute($data);
			$response['mode'] = 'FOTLAN_UPDATE';
			$response['err_no'] = 0;
			$response['err_text'] = '';
	
		}
	
	} elseif($sync_way == 'F2G' && $fotlan_id != '0') {
	
		if($google_id == '0') {
	
			// Build contact
			$contact = array();
			$firstname = (isset($_POST['fotlan_value'])) ? trim($_POST['fotlan_value']) : '';
			$contact['gd$name']['gd$familyName']['$t'] = $property_name;
			$contact['gd$name']['gd$givenName']['$t'] = $firstname;
			$contact['gd$name']['gd$fullName']['$t'] = trim($firstname . ' ' . $property_name);
			
			// Join contact to group
			if($group_id != '') {
				$group = array();
				$entity = array('deleted'	=> 'false',
								'href'		=> $group_id);
				$group[] = $entity;
				$contact['gContact$groupMembershipInfo'] = $group;
			}
			$data = array('version' => '1.0', 'encoding' => 'UTF-8', 'entry' => $contact);
			
			// Save data
			$url = "https://www.google.com/m8/feeds/contacts/default/full?v=3.0&alt=json&oauth_token=" . $_SESSION['GAPI_TOKEN'];
			$json_request = json_encode($data, JSON_UNESCAPED_SLASHES);
			gapiCurl($url, $json_request, null, array('Content-Type: application/json'));
			$response['mode'] = 'GOOGLE_CREATED';
			$response['err_no'] = 0;
			$response['err_text'] = '';
	
		} else {
			
			// Retrieve data
			$url = $google_id . "&alt=json&oauth_token=" . $_SESSION['GAPI_TOKEN'];
			$json_response = gapiCurl($url);
			$data = json_decode($json_response, true);
			
			// Update data
			$value = (isset($_POST['fotlan_value'])) ? trim($_POST['fotlan_value']) : '';
			switch($property_name) {
				case 'name':
					$data['entry']['gd$name']['gd$familyName']['$t'] = $value;
					break;
				case 'firstname':
					$data['entry']['gd$name']['gd$givenName']['$t'] = $value;
					break;
				case 'nickname':
					$data['entry']['gContact$nickname']['$t'] = $value;
					break;
				case 'birthday':
					if(trim($value) == '')
						unset($data['entry']['gContact$birthday']);
					else
						$data['entry']['gContact$birthday']['when'] = $value;
					break;
				case 'company':
					if(array_key_exists('gd$organization', $data['entry'])) {
						$data['entry']['gd$organization'][0]['gd$orgName']['$t'] = $value;
					} else {
						$orga = array();
						$entity = array('rel' => 'http://schemas.google.com/g/2005#other');
						$entity['gd$orgName']['$t'] = $value;
						$entity['gd$orgTitle']['$t'] = '';
						$orga[] = $entity;
						$data['entry']['gd$organization'] = $orga;
					}
					break;
				case 'title':
					if(array_key_exists('gd$organization', $data['entry'])) {
						$data['entry']['gd$organization'][0]['gd$orgTitle']['$t'] = $value;
					} else {
						$orga = array();
						$entity = array('rel' => 'http://schemas.google.com/g/2005#other');
						$entity['gd$orgName']['$t'] = '';
						$entity['gd$orgTitle']['$t'] = $value;
						$orga[] = $entity;
						$data['entry']['gd$organization'] = $orga;
					}
					break;
				case 'comments':
					$data['entry']['content']['$t'] = $value;
					break;
				case 'priv_email':
					$data = UpdateGoogleMail($value, 'home', $data);
					break;
				case 'pro_email':
					$data = UpdateGoogleMail($value, 'work', $data);
					break;
				case 'priv_web':
					$data = UpdateGoogleWeb($value, 'profile', $data);
					break;
				case 'pro_web':
					$data = UpdateGoogleWeb($value, 'work', $data);
					break;
				case 'priv_tel':
					$data = UpdateGoogleTel($value, 'rel', 'http://schemas.google.com/g/2005#home', $data);
					break;
				case 'pro_tel':
					$data = UpdateGoogleTel($value, 'rel', 'http://schemas.google.com/g/2005#work', $data);
					break;
				case 'priv_gsm':
					$data = UpdateGoogleTel($value, 'rel', 'http://schemas.google.com/g/2005#mobile', $data);
					break;
				case 'pro_gsm':
					$data = UpdateGoogleTel($value, 'label', 'Mobile Pro.', $data);
					break;
				case 'priv_adresse':
					$data = UpdateGoogleStreet($value, 'home', $data);
					break;
				case 'priv_adresse_ext':
					$data = UpdateGoogleNeighborhood($value, 'home', $data);
					break;
				case 'priv_cp':
					$data = UpdateGooglePostcode($value, 'home', $data);
					break;
				case 'priv_ville':
					$data = UpdateGoogleCity($value, 'home', $data);
					break;
				case 'priv_pays':
					$data = UpdateGoogleCountry($value, 'home', $data);
					break;
				case 'pro_adresse':
					$data = UpdateGoogleStreet($value, 'work', $data);
					break;
				case 'pro_adresse_ext':
					$data = UpdateGoogleNeighborhood($value, 'work', $data);
					break;
				case 'pro_cp':
					$data = UpdateGooglePostcode($value, 'work', $data);
					break;
				case 'pro_ville':
					$data = UpdateGoogleCity($value, 'work', $data);
					break;
				case 'pro_pays':
					$data = UpdateGoogleCountry($value, 'work', $data);
					break;
			}
	
			// Save data
			$json_request = json_encode($data, JSON_UNESCAPED_SLASHES);
			gapiCurl($url, null, $json_request, array('If-Match: *', 'Content-Type: application/json'));
			
			$response['mode'] = 'GOOGLE_UPDATED';
			$response['err_no'] = 0;
			$response['err_text'] = $json_request;
		}
	
	}
}
echo json_encode($response);

function UpdateGoogleMail($address, $type, $data) {
	if(array_key_exists('gd$email', $data['entry'])) {
		$changed = false;
		foreach ($data['entry']['gd$email'] as $key => &$old_data) {
			if($old_data['rel'] == 'http://schemas.google.com/g/2005#' . $type) {
				if(trim($address) == '') {
					unset($data['entry']['gd$email'][$key]);
				} else {
					$old_data['address'] = $address;
					$changed = true;
				}
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
			$new_data['address'] = $address;
			$data['entry']['gd$email'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
		$new_data['primary'] = 'true';
		$new_data['address'] = $address;
		$data['entry']['gd$email'][] = $new_data;
	}
	return $data;
}

function UpdateGoogleWeb($address, $type, $data) {
	if(array_key_exists('gContact$website', $data['entry'])) {
		$changed = false;
		foreach ($data['entry']['gContact$website'] as $key => &$old_data) {
			if($old_data['rel'] == $type) {
				if(trim($address) == '') {
					unset($data['entry']['gContact$website'][$key]);
				} else {
					$old_data['href'] = $address;
					$changed = true;
				}
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array('rel' => $type);
			$new_data['href'] = $address;
			$data['entry']['gContact$website'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array('rel' => $type);
		$new_data['href'] = $address;
		$data['entry']['gContact$website'][] = $new_data;
	}
	return $data;
}

function UpdateGoogleTel($address, $lab, $type, $data) {
	if(array_key_exists('gd$phoneNumber', $data['entry'])) {
		$changed = false;
		foreach ($data['entry']['gd$phoneNumber'] as $key => &$old_data) {
			if($old_data[$lab] == $type) {
				if(trim($address) == '') {
					unset($data['entry']['gd$phoneNumber'][$key]);
				} else {
					$old_data['uri'] = 'tel:' . str_replace(' ', '-', $address);
					$old_data['$t'] = $address;
					$changed = true;
				}
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array($lab => $type);
			$new_data['uri'] = 'tel:' . str_replace(' ', '-', $address);
			$new_data['$t'] = $address;
			$data['entry']['gd$phoneNumber'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array($lab => $type);
		$new_data['uri'] = 'tel:' . str_replace(' ', '-', $address);
		$new_data['$t'] = $address;
		$data['entry']['gd$phoneNumber'][] = $new_data;
	}
	return $data;
}

function UpdateGoogleFormattedAddress(&$postal) {
	$postal['gd$formattedAddress'][$t] = '';
	if(array_key_exists('gd$street', $postal) && trim($postal['gd$street'][$t]) != '')
		$postal['gd$formattedAddress'][$t] .= trim($postal['gd$street'][$t]) . "\n";
	if(array_key_exists('gd$neighborhood', $postal) && trim($postal['gd$neighborhood'][$t]) != '')
		$postal['gd$formattedAddress'][$t] .= trim($postal['gd$neighborhood'][$t]) . "\n";
	if(array_key_exists('gd$postcode', $postal) && trim($postal['gd$postcode'][$t]) != '')
		$postal['gd$formattedAddress'][$t] .= trim($postal['gd$postcode'][$t]) . " ";
	if(array_key_exists('gd$city', $postal) && trim($postal['gd$city'][$t]) != '')
		$postal['gd$formattedAddress'][$t] .= trim($postal['gd$city'][$t]) . "\n";
	if(array_key_exists('gd$country', $postal) && trim($postal['gd$country'][$t]) != '')
		$postal['gd$formattedAddress'][$t] .= trim($postal['gd$country'][$t]);
}

function UpdateGoogleStreet($address, $type, $data) {
	if(array_key_exists('gd$structuredPostalAddress', $data['entry'])) {
		$changed = false;
		foreach($data['entry']['gd$structuredPostalAddress'] as $key => &$old_data) {
			if($old_data['rel'] == 'http://schemas.google.com/g/2005#' . $type) {
				if(trim($address) == '') {
					unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$street']);
				} else {
					$old_data['gd$street']['$t'] = $address;
					$changed = true;
				}
				unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$formattedAddress']);
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
			$new_data['gd$street']['$t'] = $address;
			$data['entry']['gd$structuredPostalAddress'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
		$new_data['gd$street']['$t'] = $address;
		$data['entry']['gd$structuredPostalAddress'][] = $new_data;
	}
	return $data;
}

function UpdateGoogleNeighborhood($address, $type, $data) {
	if(array_key_exists('gd$structuredPostalAddress', $data['entry'])) {
		$changed = false;
		foreach($data['entry']['gd$structuredPostalAddress'] as $key => &$old_data) {
			if($old_data['rel'] == 'http://schemas.google.com/g/2005#' . $type) {
				if(trim($address) == '') {
					unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$neighborhood']);
				} else {
					$old_data['gd$neighborhood']['$t'] = $address;
					$changed = true;
				}
				unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$formattedAddress']);
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
			$new_data['gd$neighborhood']['$t'] = $address;
			$data['entry']['gd$structuredPostalAddress'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
		$new_data['gd$neighborhood']['$t'] = $address;
		$data['entry']['gd$structuredPostalAddress'][] = $new_data;
	}
	return $data;
}

function UpdateGooglePostcode($address, $type, $data) {
	if(array_key_exists('gd$structuredPostalAddress', $data['entry'])) {
		$changed = false;
		foreach($data['entry']['gd$structuredPostalAddress'] as $key => &$old_data) {
			if($old_data['rel'] == 'http://schemas.google.com/g/2005#' . $type) {
				if(trim($address) == '') {
					unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$postcode']);
				} else {
					$old_data['gd$postcode']['$t'] = $address;
					$changed = true;
				}
				unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$formattedAddress']);
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
			$new_data['gd$postcode']['$t'] = $address;
			$data['entry']['gd$structuredPostalAddress'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
		$new_data['gd$postcode']['$t'] = $address;
		$data['entry']['gd$structuredPostalAddress'][] = $new_data;
	}
	return $data;
}

function UpdateGoogleCity($address, $type, $data) {
	if(array_key_exists('gd$structuredPostalAddress', $data['entry'])) {
		$changed = false;
		foreach($data['entry']['gd$structuredPostalAddress'] as $key => &$old_data) {
			if($old_data['rel'] == 'http://schemas.google.com/g/2005#' . $type) {
				if(trim($address) == '') {
					unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$city']);
				} else {
					$old_data['gd$city']['$t'] = $address;
					$changed = true;
				}
				unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$formattedAddress']);
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
			$new_data['gd$city']['$t'] = $address;
			$data['entry']['gd$structuredPostalAddress'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
		$new_data['gd$city']['$t'] = $address;
		$data['entry']['gd$structuredPostalAddress'][] = $new_data;
	}
	return $data;
}

function UpdateGoogleCountry($address, $type, $data) {
	if(array_key_exists('gd$structuredPostalAddress', $data['entry'])) {
		$changed = false;
		foreach($data['entry']['gd$structuredPostalAddress'] as $key => &$old_data) {
			if($old_data['rel'] == 'http://schemas.google.com/g/2005#' . $type) {
				if(trim($address) == '') {
					unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$country']);
				} else {
					$old_data['gd$country']['$t'] = $address;
					$changed = true;
				}
				unset($data['entry']['gd$structuredPostalAddress'][$key]['gd$formattedAddress']);
			}
		}
		if(!$changed && trim($address) != '') {
			$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
			$new_data['gd$country']['$t'] = $address;
			$data['entry']['gd$structuredPostalAddress'][] = $new_data;
		}
	} elseif(trim($address) != '') {
		$new_data = array('rel' => 'http://schemas.google.com/g/2005#' . $type);
		$new_data['gd$country']['$t'] = $address;
		$data['entry']['gd$structuredPostalAddress'][] = $new_data;
	}
	return $data;
}

?>
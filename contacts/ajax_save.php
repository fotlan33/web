<?php

//+++++ Include +++++
require_once('../res/php/ms.php');
require_once('../res/php/profile-class.php');

//+++++ Parameters +++++
$u = new FotlanProfile();
$response = array(	'id'		=> 0,
					'err_no'	=> 401,
					'err_text'	=> 'ERREUR : Non autorisé' );

//+++++ Check autorization +++++
if(($u->Username == 'Fot') || ($u->Username == 'Mumu')) {

	// +++++ Retrieve data +++++
	$id = (isset($_POST['id']) && trim($_POST['id']) != '') ? trim($_POST['id']) : 0;
	$data = array(	':titre'			=> msFormatString($_POST['titre'], null, 20),
					':prenom'			=> msFormatString($_POST['prenom'], null, 50),
					':nom'				=> msFormatString($_POST['nom'], '[INCONNU]', 50),
					':pseudo'			=> msFormatString($_POST['pseudo'], null, 50),
					':naissance'		=> msFormatMysqlDate($_POST['naissance'], null),
					':fonction'			=> msFormatString($_POST['fonction'], null, 100),
					':societe'			=> msFormatString($_POST['societe'], null, 100),
					':priv_email'		=> msFormatString($_POST['priv_email'], null, 100),
					':priv_web'			=> msFormatString($_POST['priv_web'], null, 100),
					':priv_tel'			=> msFormatString($_POST['priv_tel'], null, 50),
					':priv_gsm'			=> msFormatString($_POST['priv_gsm'], null, 50),
					':priv_adresse'		=> msFormatString($_POST['priv_adresse'], null, 200),
					':priv_adresse_ext'	=> msFormatString($_POST['priv_adresse_ext'], null, 100),
					':priv_cp'			=> msFormatString($_POST['priv_cp'], null, 10),
					':priv_ville'		=> msFormatString($_POST['priv_ville'], null, 50),
					':priv_pays'		=> msFormatString($_POST['priv_pays'], null, 50),
					':pro_email'		=> msFormatString($_POST['pro_email'], null, 100),
					':pro_web'			=> msFormatString($_POST['pro_web'], null, 100),
					':pro_tel'			=> msFormatString($_POST['pro_tel'], null, 50),
					':pro_gsm'			=> msFormatString($_POST['pro_gsm'], null, 50),
					':pro_adresse'		=> msFormatString($_POST['pro_adresse'], null, 50),
					':pro_adresse_ext'	=> msFormatString($_POST['pro_adresse_ext'], null, 50),
					':pro_cp'			=> msFormatString($_POST['pro_cp'], null, 10),
					':pro_ville'		=> msFormatString($_POST['pro_ville'], null, 50),
					':pro_pays'			=> msFormatString($_POST['pro_pays'], null, 50),
					':remarques'		=> msFormatString($_POST['remarques'], null, 1000) );

	// +++++ Save data +++++
	$db = msConnectDB();
	if($id == 0) {
		$sql = "INSERT INTO t_contacts (titre, prenom, nom, pseudo, naissance, fonction, societe, priv_email, priv_web, priv_tel, priv_gsm, priv_adresse, priv_adresse_ext, priv_cp, priv_ville, priv_pays,
				pro_email, pro_web, pro_tel, pro_gsm, pro_adresse, pro_adresse_ext, pro_cp, pro_ville, pro_pays, remarques, mise_a_jour)
                VALUE (:titre, :prenom, :nom, :pseudo, :naissance, :fonction, :societe, :priv_email, :priv_web, :priv_tel, :priv_gsm, :priv_adresse, :priv_adresse_ext, :priv_cp, :priv_ville, :priv_pays,
				:pro_email, :pro_web, :pro_tel, :pro_gsm, :pro_adresse, :pro_adresse_ext, :pro_cp, :pro_ville, :pro_pays, :remarques, NOW())";
	} else {
		$data[':id'] = $id;
		$sql = "UPDATE t_contacts SET titre = :titre, prenom = :prenom, nom = :nom, pseudo = :pseudo, naissance = :naissance, fonction = :fonction, societe = :societe, 
				priv_email = :priv_email, priv_web = :priv_web, priv_tel = :priv_tel, priv_gsm = :priv_gsm, priv_adresse = :priv_adresse, priv_adresse_ext = :priv_adresse_ext, priv_cp = :priv_cp, priv_ville = :priv_ville, priv_pays = :priv_pays,
				pro_email = :pro_email, pro_web = :pro_web, pro_tel = :pro_tel, pro_gsm = :pro_gsm, pro_adresse = :pro_adresse, pro_adresse_ext = :pro_adresse_ext, pro_cp = :pro_cp, pro_ville = :pro_ville, pro_pays = :pro_pays,
				remarques = :remarques, mise_a_jour = NOW() WHERE id_contact = :id";
	}
	$rs = $db->prepare($sql);
	$rs->execute($data);
	$errsql = $rs->errorInfo();
	if($id == 0)
		$response['id'] = $db->lastInsertId();
	else
		$response['id'] = $id;
	$response['err_no'] = intval($errsql[0]);
	$response['err_text'] = 'ERREUR : ' . $errsql[2];
}

// Return results
echo json_encode($response);

?>
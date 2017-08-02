<?php

class FotlanProfile {

	public $Username = 'Invité';
	public $IsAdministrator = false;
	public $IsAnonymous = true;
	public $IsLocal = false;

	function __construct() {

		// Set Username
		if(isset($_COOKIE['username']))
			$this->Username = $_COOKIE['username'];
		else
			$this->Username = 'Invité';

		// Set Admin Flag
		if(isset($_COOKIE['admin']))
			$this->IsAdministrator = $_COOKIE['admin'];
		else
			$this->IsAdministrator = false;

		// Set Anonymous Flag
		if($this->Username != 'Invité')
			$this->IsAnonymous = false;

		// Set Local Flag
		if(strripos($_SERVER['REMOTE_ADDR'], '192.168.1.', 0) === false)
			$this->IsLocal = false;
		else
			$this->IsLocal = true;

	}

	function Display($Message) {

		echo "
<!-- Affichage et modification du profil -->
$Message<a class=\"profile\" href=\"#profile-box\" data-toggle=\"modal\">" . $this->Username . "</a>
<div class=\"modal fade\" id=\"profile-box\">
	<div class=\"modal-dialog\">
		<div class=\"modal-content\">
			<div class=\"modal-body\">
				<div class=\"form-group\">
					<label for=\"profile-user\" class=\"profile-lbl\">Identifiant :</label>
					<input type=\"text\" class=\"form-control\" id=\"profile-user\" placeholder=\"Ton nom d'utilisateur\">
				</div>
				<div class=\"form-group\">
					<label for=\"profile-password\" class=\"profile-lbl\">Mot de passe :</label>
					<input type=\"password\" class=\"form-control\" id=\"profile-password\" placeholder=\"Ton mot de passe\">
				</div>
				<button class=\"btn btn-warning\" id=\"profile-connect\"><span class=\"glyphicon glyphicon-ok-sign\"></span> Connecter</button>
				<button class=\"btn btn-warning\" data-dismiss=\"modal\"><span class=\"glyphicon glyphicon-remove-sign\"></span> Annuler</button>
			    <div id=\"profile-error\" class=\"alert alert-block alert-warning profile-error\">Erreur !</div>
			</div>
		</div>
	</div>
</div>
<!-- =================================== -->\n";

	}

	function GetRight($MySqlConnection, $Application) {
		$sql = "SELECT aut_droit FROM t_applications
				WHERE aut_profile = :profile AND aut_appli = :appli
				ORDER BY aut_no ASC LIMIT 1";
		$rs = $MySqlConnection->prepare($sql);
		$rs->execute(array(':profile' => $this->Username, ':appli' => $Application));
		if($row = $rs->fetch(PDO::FETCH_ASSOC))
			return $row['aut_droit'];
		else 
			return null;
	}

	function AllUsers($MySqlConnection) {

		$_t = array();
		$_rs = $MySqlConnection->query("SELECT login FROM t_profiles ORDER BY login ASC");
		while($_row = $_rs->fetch(PDO::FETCH_NUM))
			$_t[] = $_row[0];
		return $_t;

	}
}

?>

<?php

require_once 'ms.php';

class FotlanProfile {

	public $Username = 'Invité';
	public $IsAdministrator = false;
	public $IsAnonymous = true;
	public $IsLocal = false;
	private $_db = null;

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
		if(stripos($_SERVER['REMOTE_ADDR'], '192.168.192.', 0) === false)
			$this->IsLocal = false;
		else
			$this->IsLocal = true;

	}

	public function Display($Message) {

		echo "
<!-- Affichage et modification du profil -->
$Message<a class=\"profile\" href=\"#profile-box\" data-toggle=\"modal\">" . $this->Username . "</a>
<div class=\"modal fade\" id=\"profile-box\">
	<div class=\"modal-dialog\">
		<div class=\"modal-content\">
			<div class=\"modal-body\">
				<form id=\"profile-form\">
					<div class=\"form-group\">
						<label for=\"profile-user\" class=\"profile-lbl\">Identifiant :</label>
						<input type=\"text\" class=\"form-control\" id=\"profile-user\" placeholder=\"Ton nom d'utilisateur\">
					</div>
					<div class=\"form-group\">
						<label for=\"profile-password\" class=\"profile-lbl\">Mot de passe :</label>
						<input type=\"password\" class=\"form-control\" id=\"profile-password\" placeholder=\"Ton mot de passe\">
					</div>
					<button class=\"btn btn-warning\" type=\"submit\"><span class=\"glyphicon glyphicon-ok-sign\"></span> Connecter</button>
					<button class=\"btn btn-warning\" data-dismiss=\"modal\"><span class=\"glyphicon glyphicon-remove-sign\"></span> Annuler</button>
				    <div id=\"profile-error\" class=\"alert alert-block alert-warning profile-error\">Erreur !</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- =================================== -->\n";

	}

	public function CheckAuthorization($Application, $Action) {
		
		// DB Connection
		if(is_null($this->_db))
			$this->_db = msConnectDB(null);
		
		// Build request
		$sql = "SELECT aut_droit FROM t_applications
				WHERE aut_profile = :profile
				AND aut_appli = :appli
				AND aut_droit = :droit
				ORDER BY aut_no ASC LIMIT 1";
		$rs = $this->_db->prepare($sql);
		
		// Execute request
		$rs->execute(array(	':profile'	=> $this->Username,
							':appli'	=> $Application,
							':droit'	=> $Action
		));
		
		// Response
		if($row = $rs->fetch(PDO::FETCH_ASSOC))
			return true;
		else 
			return false;
	}

}

?>

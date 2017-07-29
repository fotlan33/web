<?php

//+++++ Constants +++++
define('ROOT_ID', '0');
define('ROOT_NAME', 'Photos');
require_once 'ms.php';

//+++++ Class ++++++
class Folder {

	//----- Properties -----
	public	$ID = ROOT_ID;
	public	$Name = ROOT_NAME;
	public	$Path = null;
	private $db = null;

	//----- Constructor -----
	function __construct() {
		$this->db = msConnectDB('dbu_pictures');
		switch(func_num_args()) {
			case 0:
				$sql = "SELECT * FROM t_folders WHERE id_folder = :id";
				if(isset($_GET['f']))
					$data = array(':id' => $_GET['f']);
				elseif(isset($_POST['f']))
					$data = array(':id' => $_GET['f']);
				else
					$data = array(':id' => ROOT_ID);
				break;
			case 1:
				$sql = "SELECT * FROM t_folders WHERE id_folder = :id";
				$data = array(':id' => func_get_arg(0));
				break;
			case 2:
				$sql = "SELECT * FROM t_folders WHERE folder = :folder AND path = :path";
				$data = array(':folder' => func_get_arg(0), ':path' => func_get_arg(1));
				break;
		}
		$rs = $this->db->prepare($sql);
		$rs->execute($data);
		if($row = $rs->fetch(PDO::FETCH_ASSOC)) {
			$this->ID = $row['id_folder'];
			$this->Name = $row['folder'];
			$this->Path = $row['path'];
		} else {
			$this->ID = ROOT_ID;
			$this->Name = ROOT_NAME;
			$this->Path = null;
		}
	}

	//----- Destructor  -----
	function __destruct() {
		$this->db = null;
	}

	//----- Methods ----- 
	public function IsRoot() {
		return($this->ID == ROOT_ID);
	}

	public function IsManager($user) {
		return $user->IsAdministrator;
	}
	
	public function GetParent() {
		if($this->ID == ROOT_ID)
			return null;
		elseif(!mb_stripos($this->Path, '|', 0, 'UTF-8'))
			return new Folder(ROOT_ID);
		else {
			$k = mb_strripos($this->Path, '|', 0, 'UTF-8');
			$name = mb_substr($this->Path, $k + 1);
			$path = mb_substr($this->Path, 0, $k);
			return new Folder($name, $path);
		}
	}
	
	public function GetChildren() {
		$children = array();
		$sql = "SELECT * FROM t_folders WHERE path = :path";
		if($this->IsRoot())
			$path = ROOT_NAME;
		else 
			$path = $this->Path . '|' . $this->Name;
		$rs = $this->db->prepare($sql);
		$rs->execute(array(':path' => $path));
		while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
			$children[$row['id_folder']] = $row['folder'];
		}
		return $children;
	}
}
?>
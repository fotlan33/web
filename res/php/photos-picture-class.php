<?php

//+++++ Constants +++++
define('HEIGHT_THUMB', '150');
define('HEIGHT_PREVIEW', '600');
define('PATH_DATA', '/var/data/photos/');
//define('PATH_HOST', 'http://photos.fotlan.com/data/');
define('PATH_HOST', 'http://photos.fotlan.com.s3-website-eu-west-1.amazonaws.com/data/');

require_once 'ms.php';

//+++++ Class ++++++
class Picture {

	//----- Properties -----
	public	$ID = 0;
	public	$Label = '';
	public	$Keywords = '';
	public	$Width = 0;
	public	$Height = 0;
	public	$Size = 0;
	public	$Date = '';
	public	$Extension = '';
	public	$Folder = 0;
	private	$db = null;

	//----- Constructor -----
	function __construct() {
		$this->db = msConnectDB('dbu_pictures');
	}

	//----- Destructor  -----
	function __destruct() {
		$this->db = null;
	}

	//----- Methods ----- 
	public function Open($PictureID) {
		$sql = "SELECT * FROM t_pictures WHERE id_picture = $PictureID";
		if($rs = $this->db->query($sql)) {
			$row = $rs->fetch_array();
			$this->ID = $PictureID;
			$this->Label = $row['label'];
			$this->Keywords = $row['keywords'];
			$this->Width = $row['width'];
			$this->Height = $row['height'];
			$this->Size = $row['size'];
			$this->Date = mb_substr($row['date'], 0, 10, 'UTF-8');
			$this->Extension = $row['extension'];
			$this->Folder = $row['id_folder'];
		}
	}

	public function Save() {
		if($this->ID == 0) {
			$sql = "INSERT INTO t_pictures SET
				label = '" . msEscapeQuotes($this->Label, false) . "',
				keywords = '" . msEscapeQuotes($this->Keywords, false) . "',
				width = " . $this->Width . ",
				height = " . $this->Height . ",
				size = " . $this->Size . ",
				date = '" . $this->Date . "',
				extension = '" . $this->Extension . "',
				id_folder = " . $this->Folder;
			$this->db->query($sql);
			$rs = $this->db->query("SELECT LAST_INSERT_ID()");
			$this->ID = msRequestValue($rs);
			$rs->free();
		}
		else {
			$sql = "UPDATE t_pictures SET
				label = '" . msEscapeQuotes($this->Label, false) . "',
				keywords = '" . msEscapeQuotes($this->Keywords, false) . "',
				width = " . $this->Width . ",
				height = " . $this->Height . ",
				size = " . $this->Size . ",
				date = '" . $this->Date . "',
				extension = '" . $this->Extension . "',
				id_folder = " . $this->Folder . "
				WHERE id_picture = " . $this->ID;
			$this->db->query($sql);
		}
	}

	public function Delete() {
		$this->DeleteFile($this->Path() . $this->FileName('v'));
		$this->DeleteFile($this->Path() . $this->FileName('p'));
		$this->DeleteFile($this->Path() . $this->FileName('i'));
		$sql = "DELETE FROM t_pictures WHERE id_picture = " . $this->ID;
		$this->db->query($sql);
	}

	public function BuildThumbnail() {
		$width_thumb = round(HEIGHT_THUMB * $this->Width / $this->Height);
		$this->Resize($width_thumb, HEIGHT_THUMB, 'v');
	}

	public function BuildPreview() {
		if($this->Height > HEIGHT_PREVIEW) {
			$width_preview = round(HEIGHT_PREVIEW * $this->Width / $this->Height);
			$this->Resize($width_preview, HEIGHT_PREVIEW, 'p');
		}
		else
			copy($this->Path() . $this->FileName('i'), $this->Path() . $this->FileName('p'));
	}

	private function Resize($NewWidth, $NewHeight, $sType) {
		$imgFile = $this->Path() . $this->FileName('i');
		list($imgWidth, $imgHeight, $imgTypeMime, $buf) = getimagesize($imgFile);
		$imgTypeMime = image_type_to_mime_type($imgTypeMime);
		if(strpos($imgTypeMime, 'image') !== false) {
			$rsMemory = imagecreatetruecolor($NewWidth, $NewHeight);
			switch($imgTypeMime)
			{ 
				case 'image/gif': 
					$imgMemory = imagecreatefromgif($imgFile); 
					break; 
				case 'image/png': 
					$imgMemory = imagecreatefrompng($imgFile); 
					break; 
				default: 
					$imgMemory = imagecreatefromjpeg($imgFile); 
			}
			imagecopyresampled($rsMemory, $imgMemory, 0, 0, 0, 0, $NewWidth, $NewHeight, $imgWidth, $imgHeight); 
			$rsFile = $this->Path() . $this->FileName($sType);
			imagejpeg($rsMemory, $rsFile, 80);
		}
	}

	public function Path() {
		$path = PATH_DATA . mb_substr($this->Date, 0, 4, 'UTF-8');
		if(!is_dir($path))
			mkdir($path);
		$path .= '/' . mb_substr($this->Date, 5, 2, 'UTF-8');
		if(!is_dir($path))
			mkdir($path);
		return($path . '/');
	}

	public function VirtualPath() {
		return(PATH_HOST . mb_substr($this->Date, 0, 4, 'UTF-8') . '/' . mb_substr($this->Date, 5, 2, 'UTF-8') . '/');
	}

	public function FileName($sType) {
		return($sType . substr((10000000 + $this->ID), 1, 7) . $this->Extension);
	}

	public function DeleteFile($sFileName) {
		if(file_exists($sFileName))
			unlink($sFileName);
	}

	public function NewDate($sDate) {
		if(mb_strlen($sDate, 'UTF-8') < 10)
			return($this->Today());
		else {
			$imgYear = mb_substr($sDate, 0, 4, 'UTF-8');
			$imgMonth = mb_substr($sDate, 5, 2, 'UTF-8');
			$imgDay = mb_substr($sDate, 8, 2, 'UTF-8');
			if(checkdate($imgMonth, $imgDay, $imgYear))
				return("$imgYear-$imgMonth-$imgDay");
			else
				return($this->Today());
		}
	}

	private function Today() {
		$today = getdate();
		$imgYear = $today['year'];
		$imgMonth = substr((100 + $today['mon']), 1, 2);
		$imgDay = substr((100 + $today['mday']), 1, 2);
		return("$imgYear-$imgMonth-$imgDay");
	}

	public function DateConversion($sType, $sDate) {
		if($sType == 'fr')
			return(mb_substr($sDate, 8, 2, 'UTF-8') . '/' . mb_substr($sDate, 5, 2, 'UTF-8') . '/' . mb_substr($sDate, 0, 4, 'UTF-8'));
		else
			return(mb_substr($sDate, 6, 4, 'UTF-8') . '-' . mb_substr($sDate, 3, 2, 'UTF-8') . '-' . mb_substr($sDate, 0, 2, 'UTF-8'));
	}

	public function TypeContent() {
		switch($this->Extension) {
			case '.gif':
				return('image/gif');
				break;
			case '.png':
				return('image/png');
				break;
			default:
				return('image/jpeg');
				break;
		}
	}

	public function Display($sPosition)
	{
		echo ("<div class=\"$sPosition\">
	<a href=\"" . $this->VirtualPath() . $this->FileName('p') . "\" class=\"highslide\" onclick=\"return(hs.expand(this));\">
		<img src=\"" . $this->VirtualPath() . $this->FileName('v') . "\" alt=\"Photo\" title=\"Clic pour agrandir\" />
	</a>
	<div class=\"highslide-caption\">
		<table class=\"wide_table\">
			<tr>
				<td class=\"preview_label\">" . htmlspecialchars($this->Label, ENT_QUOTES, 'UTF-8') . "</td>
				<td class=\"preview_hq\"><a href=\"/photos/download.php?id=" . $this->ID . "\">HQ - " . $this->Size . " Ko</a></td>
			</tr>
			<tr>
				<td class=\"preview_date\">" . msFormatShortDate($this->Date) . "</td>
				<td class=\"preview_hq\">" . $this->Width . " x " . $this->Height . "</td>
			</tr>
		</table>
	</div>
</div>");
	}
}
?>

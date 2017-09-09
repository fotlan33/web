<?php

//+++++ Include +++++
require_once '../res/php/ms.php';
require_once '../res/php/photos-folder-class.php';

//+++++ Parameters +++++
define('STEP', 20);
$levels = 1;
$folder = new Folder();
$db = msConnectDB();

//+++++ Root Folder +++++
if($folder->ID == ROOT_ID)
	echo "<div class=\"folder-item folder-selected\" data-id=\"" . ROOT_ID . "\">&raquo; <span id=\"folder-selection\">" . ROOT_NAME . "</span></div>\n";
else
	echo "<div class=\"folder-item\" data-id=\"" . ROOT_ID . "\">&raquo; " . ROOT_NAME . "</div>\n";

//+++++ Path +++++
if($folder->ID != ROOT_ID) {
	if($folder->Path != ROOT_NAME) {
		$path = ROOT_NAME;
		$folders = mb_split('\|', $folder->Path);
		for($i = 1; $i < count($folders); $i++) {
			$fo = new Folder($folders[$i], $path);
			echo "<div class=\"folder-item\" style=\"padding-left:" . $levels * STEP. "px\" data-id=\"" . $fo->ID . "\">&raquo; " . $fo->Name . "</div>\n";
			$path .= '|' . $fo->Name;
			$levels++;
		}
	}
	echo "<div class=\"folder-item folder-selected\" style=\"padding-left:" . $levels * STEP. "px\" data-id=\"" . $folder->ID . "\">&raquo; <span id=\"folder-selection\">" . $folder->Name . "</span></div>\n";
	$levels++;
}

//+++++ Children +++++
$sql = "SELECT id_folder, folder FROM pic_folders WHERE path = :path ORDER BY folder ASC";
$rs = $db->prepare($sql);
if($folder->ID == ROOT_ID)
	$data = array(':path' => ROOT_NAME);
else
	$data = array(':path' => $folder->Path . '|' . $folder->Name);
$rs->execute($data);
while($row = $rs->fetch(PDO::FETCH_ASSOC)) {
	echo "<div class=\"folder-item\" style=\"padding-left:" . $levels * STEP. "px\" data-id=\"" . $row['id_folder'] . "\">&raquo; " . $row['folder']. "</div>\n";
}

?>	
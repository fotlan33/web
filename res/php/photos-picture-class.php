<?php

//+++++ Constants +++++
define('HEIGHT_THUMB', '150');
define('HEIGHT_PREVIEW', '600');

require_once 'ms.php';
require_once 'aws.php';
use Aws\S3\S3Client;

//+++++ Class ++++++
class Picture {

	//----- Properties -----
	public	$ID = 0;
	public	$Label = '';
	public	$Keywords = '';
	public	$Date = '';
	public	$Width = 0;
	public	$Height = 0;
	public	$Size = 0;
	public	$Extension = '';
	public	$Folder = 0;
	private	$db = null;
	private $s3 = null;
	private $errors = '';

	//----- Constructor -----
	function __construct() {
		$this->db = msConnectDB();
	}

	//----- Destructor  -----
	function __destruct() {
		$this->db = null;
	}

	//----- Methods ----- 
	public function Open($PictureID) {
		$sql = "SELECT * FROM pic_data WHERE id_picture = :id";
		$rs = $this->db->prepare($sql);
		$rs->execute(array(':id' => $PictureID));
		if($row = $rs->fetch(PDO::FETCH_ASSOC)) {
			$this->ID = $PictureID;
			$this->Label = $row['label'];
			$this->Keywords = $row['keywords'];
			$this->Width = $row['width'];
			$this->Height = $row['height'];
			$this->Size = $row['size'];
			$this->Date = $row['date'];
			$this->Extension = $row['extension'];
			$this->Folder = $row['id_folder'];
		} else {
			$this->SetError("Impossible de charger l'image " . $PictureID);
		}
	}

	public function Save() {
		$data = array(	':label'		=> $this->Label,
						':keywords'		=> $this->Keywords,
						':width'		=> $this->Width,
						':height'		=> $this->Height,
						':size'			=> $this->Size,
						':date'			=> $this->Date,
						':extension'	=> $this->Extension,
						':folder'		=> $this->Folder
		);
		if($this->ID == 0) {
			$sql = "INSERT INTO pic_data SET label = :label, keywords = :keywords, width = :width, height = :height,
						size = :size, date = :date, extension = :extension, id_folder = :folder";
			$rs = $this->db->prepare($sql);
			$rs->execute($data);
			$this->ID = $this->db->lastInsertId();
		}
		else {
			$sql = "UPDATE pic_data SET label = :label, keywords = :keywords, width = :width, height = :height,
						size = :size, date = :date, extension = :extension, id_folder = :folder
					WHERE id_picture = :id";
			$data['id'] = $this->ID;
			$rs = $this->db->prepare($sql);
			$rs->execute($data);
		}
		$log = $rs->errorInfo();
		if(!is_null($log[1]))
				$this->SetError($log[2]);
	}

	public function Delete() {
		$this->DeleteFile($this->Path() . $this->FileName('v'));
		$this->DeleteFile($this->Path() . $this->FileName('p'));
		$this->DeleteFile($this->Path() . $this->FileName('i'));
		$sql = "DELETE FROM pic_data WHERE id_picture = :id";
		$rs = $this->db->prepare($sql);
		$rs->execute(array(':id' => $this->ID));
		$log = $rs->errorInfo();
		if(!is_null($log[1]))
			$this->SetError($log[2]);
	}

	public function StoreInS3($Format, $SourceFilePath) {
		if(is_null($this->s3)) {
			if(!$this->GetS3Client())
				return false;
		}
		$TargetFilePath = $this->Path() . $this->FileName($Format);
		try {
			$result = $this->s3->putObject([
					'Bucket'     => PHOTOS_BUCKET,
					'Key'        => $TargetFilePath,
					'SourceFile' => $SourceFilePath,
					'ACL'        => 'public-read'
			]);
			return true;
		} catch (S3Exception $e) {
			$this->SetError('Erreur AWS : ' . $e->getMessage());
			return false;
		}
	}

	public function StoreThumbnail($SourceFilePath) {
		$width_thumb = round(HEIGHT_THUMB * $this->Width / $this->Height);
		$ThumbnailFilePath = $this->Resize($SourceFilePath, $width_thumb, HEIGHT_THUMB);
		if(!is_null($ThumbnailFilePath)) {
			$this->StoreInS3('v', $ThumbnailFilePath);
			unlink($ThumbnailFilePath);
		}
	}

	public function StorePreview($SourceFilePath) {
		if($this->Height > HEIGHT_PREVIEW) {
			$width_preview = round(HEIGHT_PREVIEW * $this->Width / $this->Height);
			$PreviewFilePath = $this->Resize($SourceFilePath, $width_preview, HEIGHT_PREVIEW);
			if(!is_null($PreviewFilePath)) {
				$this->StoreInS3('p', $PreviewFilePath);
				unlink($PreviewFilePath);
			}
		}
		else
			$this->StoreInS3('p', $SourceFilePath);
	}

	private function GetS3Client() {
		$aws_conf = awsConfig();
		if(is_null($aws_conf)) {
			$this->SetError('Configuration AWS non disponible !');
			return false;
		} else {			
			try {
				$this->s3 = new S3Client([
						'version'     	=> 'latest',
						'region'      	=> 'eu-west-1',
						'http'			=> ['verify' => CERT_FILE],
						'credentials'	=> [
								'key'		=> $aws_conf['aws.access.key.id'],
								'secret'	=> $aws_conf['aws.secret.access.key'],
						],
				]);
				return true;
			} catch (S3Exception $e) {
				$this->SetError('Erreur AWS : ' . $e->getMessage());
				return false;
			}
		}
	}
	
	private function MoveS3File($SourceFilePath, $TargetFilePath) {
		if($SourceFilePath != $TargetFilePath) {
			if(is_null($this->s3)) {
				if(!$this->GetS3Client())
					return false;
			}
			try {
				$result = $this->s3->copyObject([
						'Bucket'     => PHOTOS_BUCKET,
						'Key'        => $TargetFilePath,
						'CopySource' => PHOTOS_BUCKET . '/' . $SourceFilePath,
						'ACL'        => 'public-read'
				]);
				$this->DeleteFile($SourceFilePath);
				return true;
			} catch (S3Exception $e) {
				$this->SetError('Erreur AWS : ' . $e->getMessage());
				return false;
			}
		}
	}
	
	private function Resize($SourceFilePath, $NewWidth, $NewHeight) {
		list($imgWidth, $imgHeight, $imgTypeMime, $buf) = getimagesize($SourceFilePath);
		$imgTypeMime = image_type_to_mime_type($imgTypeMime);
		if(strpos($imgTypeMime, 'image') !== false) {
			$rsMemory = imagecreatetruecolor($NewWidth, $NewHeight);
			switch($imgTypeMime)
			{ 
				case 'image/gif': 
					$imgMemory = imagecreatefromgif($SourceFilePath); 
					break; 
				case 'image/png': 
					$imgMemory = imagecreatefrompng($SourceFilePath); 
					break; 
				default: 
					$imgMemory = imagecreatefromjpeg($SourceFilePath); 
			}
			imagecopyresampled($rsMemory, $imgMemory, 0, 0, 0, 0, $NewWidth, $NewHeight, $imgWidth, $imgHeight); 
			$rsFile = tempnam(sys_get_temp_dir(), 'pic');
			imagejpeg($rsMemory, $rsFile, 80);
			return $rsFile;
		} else {
			$this->SetError('Format image non supporté');
			return null;
		}
	}

	public function Path() {
		return PHOTOS_ROOT. mb_substr($this->Date, 0, 4, 'UTF-8') . '/' . mb_substr($this->Date, 5, 2, 'UTF-8') . '/';
	}

	public function VirtualPath() {
		return(PHOTOS_HOST . $this->Path());
	}

	public function FileName($sType) {
		return($sType . substr((10000000 + $this->ID), 1, 7) . $this->Extension);
	}

	public function DeleteFile($sFilePath) {
		if(is_null($this->s3)) {
			if(!$this->GetS3Client())
				return false;
		}
		try {
			$result = $this->s3->deleteObject([
					'Bucket'     => PHOTOS_BUCKET,
					'Key'        => $sFilePath
			]);
			return true;
		} catch (S3Exception $e) {
			$this->SetError('Erreur AWS : ' . $e->getMessage());
			return false;
		}
	}

	public function NewDate($sDate) {
		if(mb_strlen($sDate, 'UTF-8') < 10)
			return($this->Today());
		else {
			$imgYear = mb_substr($sDate, 0, 4, 'UTF-8');
			$imgMonth = mb_substr($sDate, 5, 2, 'UTF-8');
			$imgDay = mb_substr($sDate, 8, 2, 'UTF-8');
			if(checkdate(intval($imgMonth, 10), intval($imgDay, 10), intval($imgYear, 10)))
				return("$imgYear-$imgMonth-$imgDay");
			else
				return($this->Today());
		}
	}

	public function ChangeDate($sDate) {
		$SourceFileThumbnail = $this->Path() . $this->FileName('v');
		$SourceFilePreview = $this->Path() . $this->FileName('p');
		$SourceFileImage = $this->Path() . $this->FileName('i');
		$this->Date = $this->NewDate($sDate);
		$this->MoveS3File($SourceFileThumbnail, $this->Path() . $this->FileName('v'));
		$this->MoveS3File($SourceFilePreview, $this->Path() . $this->FileName('p'));
		$this->MoveS3File($SourceFileImage, $this->Path() . $this->FileName('i'));
	}
	
	private function Today() {
		return(date('Y-m-d'));
	}

	public function GetErrors() {
		return $this->errors;
	}
	
	private function SetError($NewError) {
		$this->errors .= $NewError . "\n";
	}
}
?>

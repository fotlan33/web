<?php

require_once '../res/php/aws.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Check file uploaded
if (isset($_FILES['photo_upload'])) {
	
	// Get AWS configuration
	$aws_conf = awsConfig();
	if(is_null($aws_conf)) {
		echo "ERROR : AWS configuration not available !\n";
	} else {
	
		try {		
			// Get S3 Client
			$s3 = new S3Client([
					'version'     	=> 'latest',
					'region'      	=> 'eu-west-1',
					'http'			=> ['verify' => CERT_FILE],
					'credentials'	=> [	
								'key'		=> $aws_conf['aws.access.key_id'],
								'secret'	=> $aws_conf['aws.secret.access.key'],
					],
			]);
			$result = $s3->putObject([
					'Bucket'     => PHOTOS_BUCKET,
					'Key'        => 'data/2015/' . $_FILES['photo_upload']['name'],
					'SourceFile' => $_FILES['photo_upload']['tmp_name'],
			]);
		} catch (S3Exception $e) {
			echo "ERROR : " . $e->getMessage() . "\n";
		}
		
	}

} else {
	echo "ERROR : No files uploaded !\n";
}
?>
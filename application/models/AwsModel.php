<?php

require 'vendor/autoload.php';

use Aws\Sns\SnsClient;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class AwsModel extends CI_Model
{
	private $key = ACCESS_KEY;
	private $secret = ACCESS_SECRET_KEY;


	public function sendSMS1()
	{
		try {
//			$SnSclient = new SnsClient(array(
//				'region' => 'ap-south-1',
//				'version' => 'latest',
//				'credentials' => array(
//					'key' => "AKIA3ORLNQH55M536KWH",
//					'secret' => "YRoCS1gz9rPUoLdWVuzW+c25oMtgqvglJC5l222M"
//				)
//			));
//
//			$message = 'This message is sent from a Amazon SNS code sample.';
//			$phone = '+919320206598';
//
//
//			$result = $SnSclient->publish(array(
//				'MessageAttributes'=>array(
//					'AWS.SNS.SMS.SenderID'=>array(
//						'DataType'=>'String',
//						'StringValue'=>'GLDBRZ'
//					),
//					'AWS.SNS.SMS.SMSType'=>array(
//						'DataType'=>'String',
//						'StringValue'=>'Transactional'
//					)
//				),
//				'Message' => $message,
//				'PhoneNumber' => $phone,
//			));
//			var_dump($result);
		} catch (AwsException $e) {
			// output error message if fails
//			var_dump($e->getMessage());
		}


	}


	public function checkConnection()
	{
		$s3Client = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));
		$bucket = 'ecovisrkca-template-engine';
		try {
			$result = $s3Client->putObject(array(
				'Bucket' => $bucket,
				'Key' => 'my-key.txt',
				'Body' => 'this is the body!',
//                "SourceFile" => $filePath,
				'ACL' => 'public-read', // make file 'public'
			));
			if ($result["@metadata"]["statusCode"] == '200') {
				$response["status"] = 200;
				$response["body"] = 'my-key.txt';
				var_dump($response);
			} else {
				$response["status"] = 201;
				$response["body"] = "Failed To Store on s3";
				var_dump($response);
			}

		} catch (S3Exception $e) {
			$response["status"] = 201;
			$response["body"] = $e->getMessage();
			var_dump($response);
		}


	}

	function uploadDrive($fileName, $filePath, $type = 0)
	{

// Instantiate an Amazon S3 client.
		$s3Client = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));


		$bucket = 'aws-drive-project';
		// $file_Path = __DIR__ . '/sample.txt';
		$key = $fileName;


// Upload a publicly accessible file. The file size and type are determined by the SDK.
		try {

			if ($key != "" || $fileName != "") {
				if (!$s3Client->doesObjectExist($bucket, trim($key))) {
					if ($type == 0) {
						$result = $s3Client->putObject(array(
							'Bucket' => $bucket,
							'Key' => $key,
							"SourceFile" => $filePath,
							'ACL' => 'public-read', // make file 'public'
						));
						log_message("ERROR", $result);
					} else {
						$result = $s3Client->putObject(array(
							'Bucket' => $bucket,
							'Key' => $key,
							"body" => '',
							'ACL' => 'public-read', // make file 'public'
						));
						log_message("ERROR", $result);
					}

					if ($result["@metadata"]["statusCode"] == '200') {
						$response["status"] = 200;
						$response["body"] = $key;
						$response["fileName"] = basename($key);
						$response["data"] = $result["@metadata"]["headers"];
						return $response;
					} else {
						$response["status"] = 201;
						$response["body"] = "Failed To Store on s3";
						return $response;
					}
				} else {
					$response["status"] = 203;
					$response["body"] = "Same Name File exist";
					return $response;
				}
			} else {
				$response["status"] = 202;
				$response["body"] = "Failed To Store on s3";
				return $response;
			}

		} catch (S3Exception $e) {
			$response["status"] = 204;
			$response["body"] = $e->getMessage();
			return $response;
		}
	}

	function upload($fileName, $filePath, $type = 0)
	{

// Instantiate an Amazon S3 client.
		$s3Client = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));


		$bucket = 'ecovisrkca-template-engine';
		// $file_Path = __DIR__ . '/sample.txt';
		$key = $fileName;


// Upload a publicly accessible file. The file size and type are determined by the SDK.
		try {

			if ($key != "" || $fileName != "") {
				if (!$s3Client->doesObjectExist($bucket, trim($key))) {
					if ($type == 0) {
						$result = $s3Client->putObject(array(
							'Bucket' => $bucket,
							'Key' => $key,
							"SourceFile" => $filePath,
							'ACL' => 'public-read', // make file 'public'
						));
						log_message("ERROR", $result);
					} else {
						$result = $s3Client->putObject(array(
							'Bucket' => $bucket,
							'Key' => $key,
							"body" => '',
							'ACL' => 'public-read', // make file 'public'
						));
						log_message("ERROR", $result);
					}

					if ($result["@metadata"]["statusCode"] == '200') {
						$response["status"] = 200;
						$response["body"] = $key;
						$response["fileName"] = basename($key);
						$response["data"] = $result["@metadata"]["headers"];
						return $response;
					} else {
						$response["status"] = 201;
						$response["body"] = "Failed To Store on s3";
						return $response;
					}
				} else {
					$response["status"] = 201;
					$response["body"] = "Same Name File exist";
					return $response;
				}
			} else {
				$response["status"] = 201;
				$response["body"] = "Failed To Store on s3";
				return $response;
			}

		} catch (S3Exception $e) {
			$response["status"] = 201;
			$response["body"] = $e->getMessage();
			return $response;
		}
	}

	function awsUpload_file($upload_path)
	{

		if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] != '4') {
			$files = $_FILES;
			$_FILES['userfile']['name'] = $files['userfile']['name'];
			$_FILES['userfile']['type'] = $files['userfile']['type'];
			$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'];
			$_FILES['userfile']['error'] = $files['userfile']['error'];
			$_FILES['userfile']['size'] = $files['userfile']['size'];
			$fileName = preg_replace('/\s+/', '_', str_replace(' ', '_', $_FILES['userfile']['name']));
			//$data = array('upload_data' => $this->upload->data());
			if (empty($fileName)) {
				$response['status'] = 203;
				$response['body'] = "file is empty";
				return false;
			} else {
				$file = $this->upload($upload_path . $fileName, $files['userfile']['tmp_name']);
				if ($file["status"] != 200) {
					//$error = array('upload_error' => $this->upload->display_errors());
					$response['status'] = 204;
					$response['body'] = $files['userfile']['name'] . ' ' . $file["body"];
					return $response;
				} else {
					$response['status'] = 200;
					$response['body'] = $file["body"];
					return $response;
				}
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "";
			return $response;
		}
	}

	function upload_multiple_file_new($upload_path, $inputname, $com = 2)
	{


		if (isset($_FILES[$inputname]) && $_FILES[$inputname]['error'] != '4') {

			if (is_array($_FILES[$inputname]['name'])) {
				$count = count($_FILES[$inputname]['name']); // count element
				$files = $_FILES[$inputname];
				$images = array();
				if ($count > 0) {
					$inputname = $inputname . "[]";
					$file_with_path = array();
					$error_with_path = array();
					for ($i = 0; $i < $count; $i++) {
						$_FILES[$inputname]['name'] = $files['name'][$i];
						$_FILES[$inputname]['type'] = $files['type'][$i];
						$_FILES[$inputname]['tmp_name'] = $files['tmp_name'][$i];
						$_FILES[$inputname]['error'] = $files['error'][$i];
						$_FILES[$inputname]['size'] = $files['size'][$i];
						$fileName = $files['name'][$i];
						//get system generated File name CONCATE datetime string to Filename
						$destination = "";
						if ($fileName == "") {
							$result['status'] = 201;
							$result['body'] = "File Is Empty";
						} else {
							$date = date('Y-m-d H:i:s');
							$randomdata = strtotime($date);
							$fileName = $randomdata . '_' . $fileName;

							$images[] = $fileName;
							$destination = $upload_path . "/" . $fileName;
							$result = $this->upload($destination, $files['tmp_name'][$i]);
						}

						if ($result["status"] == 200) {
							if ($destination != "") {
								$file_with_path[] = $destination;
							} else {
								$result["status"] = 201;
								$error_with_path[] = $result;
							}
						} else {
							$error_with_path[] = $result;
						}
					}
					if (count($file_with_path) > 0) {
						$response['status'] = 200;
						$response['body'] = $file_with_path;
					} else {
						$response['status'] = 205;
						$response['body'] = $error_with_path;
					}
					return $response;

				} else {
					$response['status'] = 204;
					$response['body'] = array();
					return $response;
				}
			} else {
				$response['status'] = 203;
				$response['body'] = array();
				return $response;
			}
		} else {
			$response['status'] = 202;
			$response['body'] = array();
			return $response;
		}
	}

	function upload_multiple_file_on_drive($upload_path, $inputname, $com = 2)
	{

		if (isset($_FILES[$inputname]) && $_FILES[$inputname]['error'] != '4') {

			if (is_array($_FILES[$inputname]['name'])) {
				$count = count($_FILES[$inputname]['name']); // count element
				$files = $_FILES[$inputname];
				$images = array();
				if ($count > 0) {
					$inputname = $inputname . "[]";
					$file_with_path = array();
					$error_with_path = array();
					for ($i = 0; $i < $count; $i++) {
						$_FILES[$inputname]['name'] = $files['name'][$i];
						$_FILES[$inputname]['type'] = $files['type'][$i];
						$_FILES[$inputname]['tmp_name'] = $files['tmp_name'][$i];
						$_FILES[$inputname]['error'] = $files['error'][$i];
						$_FILES[$inputname]['size'] = $files['size'][$i];
						$fileName = $files['name'][$i];
						//get system generated File name CONCATE datetime string to Filename
						$destination = "";
						if ($fileName == "") {
							$result['status'] = 201;
							$result['body'] = "File Is Empty";
						} else {
							$date = date('Y-m-d H:i:s');
							$randomdata = strtotime($date);
							$fileName = $randomdata . '_' . $fileName;

							$images[] = $fileName;
							$destination = $upload_path . "/" . $fileName;
							$result = $this->uploadDrive($destination, $files['tmp_name'][$i]);
						}

						if ($result["status"] == 200) {
							if ($destination != "") {
								$file_with_path[] = $result;
							} else {
								$result["status"] = 201;
								$error_with_path[] = $result;
							}
						} else {
							$error_with_path[] = $result;
						}
					}
					if (count($file_with_path) > 0) {
						$response['status'] = 200;
						$response['body'] = $file_with_path;
					} else {
						$response['status'] = 205;
						$response['body'] = $error_with_path;
					}
					return $response;

				} else {
					$response['status'] = 204;
					$response['body'] = array();
					return $response;
				}
			} else {
				$response['status'] = 203;
				$response['body'] = array();
				return $response;
			}
		} else {
			$response['status'] = 202;
			$response['body'] = array();
			return $response;
		}
	}

	function download($fileName, $type = 1)
	{

		$s3 = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));
		if ($type == 1) {
			$bucket = 'ecovisrkca-template-engine';
		} else {
			$bucket = 'aws-drive-project';
		}

		try {

			$result = $s3->getObject(array(
				'Bucket' => $bucket,
				'Key' => trim($fileName)
			));
			header("Content-Type: {$result['ContentType']}");

			header("Content-Disposition: attachment; filename=" . basename($fileName) . "");
			echo $result['Body'];
		} catch (S3Exception $e) {

			echo $e->getMessage() . PHP_EOL;
		}
	}

	function viewFile($fileName, $type = 1)
	{

		$s3 = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));
		if ($type == 1) {
			$bucket = 'ecovisrkca-template-engine';
		} else {
			$bucket = 'aws-drive-project';
		}
		try {
			$result = $s3->getObject(array(
				'Bucket' => $bucket,
				'Key' => trim($fileName)
			));
			header("Content-Type: {$this->getTypes($fileName)}");
			header("Content-Disposition: filename=" . basename($fileName) . "");
			echo $result['Body'];
		} catch (S3Exception $e) {
			echo $e->getMessage() . PHP_EOL;
		}
	}

	function getObjectList()
	{
		$s3 = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
		));
		$bucket = 'ecovisrkca-template-engine';

		$objects = $s3->getIterator('ListObjects', array(
			'Bucket' => $bucket,
		));

		foreach ($objects as $object) {
			echo "<li><a href='https://rmt.ecovisrkca.com/downloadFile?f=" . $object['Key'] . "' >
                 " . $object['Key'] . "
                </a></li>";
		}
	}

	function downloadFileOnServer($fileName)
	{

		$s3 = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));
		$bucket = 'ecovisrkca-template-engine';

		try {

			$result = $s3->getObject(array(
				'Bucket' => $bucket,
				'Key' => trim($fileName)
			));
			$file = basename($fileName);
			$response["file"] = $file;
			$response["body"] = $result['Body'];
			$response["status"] = 200;
			return $response;

		} catch (S3Exception $e) {
			$response["status"] = 201;
			$response["body"] =$e->getMessage();
			log_message('ERROR', $e->getMessage());
			return $response;
		}
	}

	function fileInfo()
	{

		$s3 = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));
		$bucket = 'drive-bucket-ecovisrkca';

		$fileName = "SK/SET.txt";

		try {

			$result = $s3->headObject(array(
				'Bucket' => $bucket,
				'Key' => trim($fileName)
			));
			var_dump($result);
			$response["status"] = 200;
			$response["body"] = $result;


		} catch (S3Exception $e) {
			log_message("ERROR", $e);
			$response["status"] = 201;
			$response["body"] = $e->getMessage();
		}
		echo json_encode($response);
	}

	function createFolder($folderName)
	{
		$response = $this->uploadDrive($folderName, "", 1);
		if ($response['status'] == 200) {
			$response['status'] = 200;
			$response['body'] = array($response);
		} else {
			$response['status'] = 201;
			$response['body'] = $response;
		}
		return $response;
	}

	function getPreAssignURL($fileName, $type = 1)
	{
		$s3 = new S3Client(array(
			'version' => 'latest',
			'region' => 'ap-south-1',
			'credentials' => array(
				'key' => $this->key,
				'secret' => $this->secret
			)
		));
		if ($type == 1) {
			$bucket = 'ecovisrkca-template-engine';
		} else {
			$bucket = 'aws-drive-project';
		}
		try {
			$cmd = $s3->getCommand("GetObject", array(
				'Bucket' => $bucket,
				'Key' => trim($fileName)
			));
//			return $s3->getObjectUrl($bucket, trim($fileName));
			$request = $s3->createPresignedRequest($cmd, '+10 minutes');
			return (string)$request->getUri();

		} catch (S3Exception $e) {
			log_message("ERROR", $e->getMessage() . PHP_EOL);
			return null;
		}
	}

	public function getTypes($fileName)
	{
		$array = explode(".", basename($fileName));
		$ext = end($array);
		$mimes = array('hqx' => 'application/mac-binhex40',
			'cpt' => 'application/mac-compactpro',
			'csv' => array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
			'bin' => 'application/macbinary',
			'dms' => 'application/octet-stream',
			'lha' => 'application/octet-stream',
			'lzh' => 'application/octet-stream',
			'exe' => array('application/octet-stream', 'application/x-msdownload'),
			'class' => 'application/octet-stream',
			'psd' => 'application/x-photoshop',
			'so' => 'application/octet-stream',
			'sea' => 'application/octet-stream',
			'dll' => 'application/octet-stream',
			'oda' => 'application/oda',
			'pdf' => array('application/pdf', 'application/x-download'),
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',
			'smi' => 'application/smil',
			'smil' => 'application/smil',
			'mif' => 'application/vnd.mif',
			'xls' => array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
			'ppt' => array('application/powerpoint', 'application/vnd.ms-powerpoint'),
			'wbxml' => 'application/wbxml',
			'wmlc' => 'application/wmlc',
			'dcr' => 'application/x-director',
			'dir' => 'application/x-director',
			'dxr' => 'application/x-director',
			'dvi' => 'application/x-dvi',
			'gtar' => 'application/x-gtar',
			'gz' => 'application/x-gzip',
			'php' => 'application/x-httpd-php',
			'php4' => 'application/x-httpd-php',
			'php3' => 'application/x-httpd-php',
			'phtml' => 'application/x-httpd-php',
			'phps' => 'application/x-httpd-php-source',
			'js' => 'application/x-javascript',
			'swf' => 'application/x-shockwave-flash',
			'sit' => 'application/x-stuffit',
			'tar' => 'application/x-tar',
			'tgz' => array('application/x-tar', 'application/x-gzip-compressed'),
			'xhtml' => 'application/xhtml+xml',
			'xht' => 'application/xhtml+xml',
			'zip' => array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
			'mid' => 'audio/midi',
			'midi' => 'audio/midi',
			'mpga' => 'audio/mpeg',
			'mp2' => 'audio/mpeg',
			'mp3' => array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
			'aif' => 'audio/x-aiff',
			'aiff' => 'audio/x-aiff',
			'aifc' => 'audio/x-aiff',
			'ram' => 'audio/x-pn-realaudio',
			'rm' => 'audio/x-pn-realaudio',
			'rpm' => 'audio/x-pn-realaudio-plugin',
			'ra' => 'audio/x-realaudio',
			'rv' => 'video/vnd.rn-realvideo',
			'wav' => array('audio/x-wav', 'audio/wave', 'audio/wav'),
			'bmp' => array('image/bmp', 'image/x-windows-bmp'),
			'gif' => 'image/gif',
			'jpeg' => array('image/jpeg', 'image/pjpeg'),
			'jpg' => array('image/jpeg', 'image/pjpeg'),
			'jpe' => array('image/jpeg', 'image/pjpeg'),
			'png' => array('image/png', 'image/x-png'),
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'css' => 'text/css',
			'html' => 'text/html',
			'htm' => 'text/html',
			'shtml' => 'text/html',
			'txt' => 'text/plain',
			'text' => 'text/plain',
			'log' => array('text/plain', 'text/x-log'),
			'rtx' => 'text/richtext',
			'rtf' => 'text/rtf',
			'xml' => 'text/xml',
			'xsl' => 'text/xml',
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg',
			'mpe' => 'video/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',
			'avi' => 'video/x-msvideo',
			'movie' => 'video/x-sgi-movie',
			'doc' => 'application/msword',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'word' => array('application/msword', 'application/octet-stream'),
			'xl' => 'application/excel',
			'eml' => 'message/rfc822',
			'json' => array('application/json', 'text/json')
		);

		if (!isset($mimes[$ext])) {
			$mime = 'application/octet-stream';
		} else {
			//get the MIME type from the mimes array
			$mime = (is_array($mimes[$ext])) ? $mimes[$ext][0] : $mimes[$ext];
		}
		return $mime;
	}
}

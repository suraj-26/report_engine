<?php


class HexaController extends CI_Controller
{


	/**
	 * HexaController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * @param $properties array of properties that you want to validate
	 * @return stdClass of status and jsonObject
	 */
	public function request_parameter($properties){
		$propertyObject =new stdClass();
		$jsonObject = json_decode($this->input->raw_input_stream);
		if (!is_null($jsonObject)) {
			$propertyCounter=0;
			foreach ($properties as $property){
				if(property_exists($jsonObject, $property)){
					$propertyCounter++;
				}
			}
			if (count($properties) == $propertyCounter){
				$propertyObject->jsonObject = $jsonObject;
				$propertyObject->status =TRUE;
			}else{
				$propertyObject->status= FALSE;
				$propertyObject->response =array("status"=>204,"body"=>'Required Parameter Missing');
			}
		}else{
			$propertyObject->status= 204;
			$propertyObject->response =array("status"=>204,"body"=>'Invalid Request');
		}
		return $propertyObject;
	}

	/**
	 * @param $response object|string pass as response
	 * @return string convert response to base64_encode string
	 */
	public function base64_response($response){
		return base64_encode(json_encode($response));
	}

	/**
	 * @param $value string|date that you want to convert in human readable string
	 * @return false|string format date date Month year
	 */
	public function getDate($value){
		return date('jS M Y', strtotime($value));
	}


	/**
	 * @param $parameters array array of required parameter
	 * @return stdClass object of result status and object of parameter value if get all value other wise send false
	 */
	function is_parameter($parameters){
		$result = false;
		$parameterValues =array();
		foreach ($parameters as $param){
			if(!is_null($this->input->post($param))){
				$result = true;
				$parameterValues[$param]=$this->input->post($param);
			}else{
				$result = false;
				break;
			}
		}
		$parameterObject = new stdClass();
		$parameterObject->status =$result;
		$parameterObject->param = (object)$parameterValues;
		return $parameterObject;
	}


	/**
	 * @param $upload_path string path to search file exist or not
	 * @return array send list of file path
	 */
	function check_file_exist($upload_path) {
		$filenames = array();
		foreach (glob('./' . $upload_path . '/*.*') as $file_NAMEEXISTS) {
			$file_NAMEEXISTS;
			$filenames[] = str_replace("./" . $upload_path . "/", "", $file_NAMEEXISTS);
		}
		return $filenames;
	}

	/**
	 * @param $upload_path string where to save file
	 * @param $input_name string input element name
	 * @param string $combination type of combination 1 = save file with existing name if file exist throw error 2 = save file with datetime prefix with file name
	 * @return object of response with status and body key, the body contain array of file path
	 */
	function upload_file($upload_path, $input_name, $combination = "") {

		$response = new stdClass();
		$combination = (explode(",", $combination));

		$check_file_exist = $this->check_file_exist($upload_path);
		if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] != '4') {

			$files = $_FILES;
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = '*';
			$config['max_size'] = '20000000';    //limit 10000=1 mb
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;

			$this->load->library('upload', $config);
			if (is_array($_FILES[$input_name]['name'])) {
				$count = count($_FILES[$input_name]['name']);
				$files = $_FILES[$input_name];
				$images = array();
				$dataInfo = array();

				if (in_array("1", $combination)) {
					for ($j = 0; $j < $count; $j++) {
						$fileName = $files['name'][$j];
						if (in_array($fileName, $check_file_exist)) {

							$response->status = false;
							$response->body = $fileName . " Already exist";
							return $response;
						}
					}
				}
				$input_name = $input_name . "[]";
				for ($i = 0; $i < $count; $i++) {
					$_FILES[$input_name]['name'] = $files['name'][$i];
					$_FILES[$input_name]['type'] = $files['type'][$i];
					$_FILES[$input_name]['tmp_name'] = $files['tmp_name'][$i];
					$_FILES[$input_name]['error'] = $files['error'][$i];
					$_FILES[$input_name]['size'] = $files['size'][$i];
					$fileName = $files['name'][$i];
					//get system generated File name CONCATE datetime string to Filename
					if (in_array("2", $combination)) {
						$date = date('Y-m-d H:i:s');
						$random_data = strtotime($date);
						$fileName = $random_data . $fileName;
					}
					$images[] = $fileName;

					$config['file_name'] = $fileName;

					$this->upload->initialize($config);
					$this->upload->do_upload($input_name);
					$dataInfo[] = $this->upload->data();
				}
				$file_with_path = array();
				foreach ($dataInfo as $row) {
					$raw_name = $row['raw_name'];
					$file_ext = $row['file_ext'];
					$file_name = $raw_name . $file_ext;
					$file_with_path[] = $upload_path . "/" . $file_name;
				}
				$response->status = true;
				$response->body = $file_with_path;
				return $response;
			}
		} else {
			$response->status = false;
			$response->body  = array();
			return $response;
		}
	}

}

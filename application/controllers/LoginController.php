<?php

require_once 'HexaController.php';

/**
 * @property  User User
 * @property  loginModel loginModel
 * @property  MasterModel MasterModel
 */
class LoginController extends HexaController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('MasterModel');
		date_default_timezone_set('Asia/Kolkata');
	}

	public function login(){

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		if($email != null && $email != '' && $password != null && $password != ''){
			$checkLogin = $this->MasterModel->_select('users_master',array('user_name'=>$email,'password'=>$password),
				array("id","user_name","name", "email","mobile_number", "user_type","roles",
					"branch_id", "access_menus", "all_access", "customer_id","company_id","password"),true);

			if($checkLogin->totalCount > 0){

				$userdata=$checkLogin->data;
				$this->session->user_session = $userdata;
				$response['status'] = 200;
				$response['data'] = $userdata;
				$response['body'] = "Login Successful";
			}else{
				$response['status'] = 201;
				$response['body'] = "Email or Password Invalid";
			}
		}else{
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		} echo json_encode($response);
	}

	public function logout(){
		session_destroy();
		redirect('/');
	}
	/*
	 * login api
	 */

	function auth_redirect(){
		if(isset($this->session->user_session)){

			$roles = (int)$this->session->user_session->roles;

			if($roles==1 ||  $roles ==2){
				redirect("/admin/view_departments");
			}
			if($roles==3){
				redirect("/materialSupply");
			}
			if($roles==4){
				redirect("/viewForm/186");
			}
			if($roles==5){
				redirect("/viewForm/187");
			}
			if($roles==6){
				redirect("/viewForm/188");
			}
		}else{
			redirect("/");
		}
	}
	public function loginUser()
	{

		$validationObject = parent::is_parameter(array("email", "password"));
		$param = $validationObject->param;
		$Did = "";
		if ($validationObject->status) {
			$isToken = $param->isToken;
			if ($param->device != null) {
				$device = $param->device;
				$deviceData1 = base64_decode($device);
				if ($deviceData1 != null) {
					$deviceData = json_decode($deviceData1);
					if ($deviceData != null) {
						$Did = $deviceData->device_id;
					}
				}
			}
			$logged_users = array();
			if ($param->log_users != null) {
				$users = $param->log_users;
				if ($users != null) {
					$logged = base64_decode($users);
					if ($logged != null) {
						$logged_users = json_decode($logged);
					}
				}
			}


			$loginData = $this->loginValidation($param->email, $param->password);
			if ($loginData['status'] == 200) {
//                $response = $loginData;
				$branch_id = $this->session->user_session->branch_id;
				$user_id = $this->session->user_session->id;
				$check2Step = $this->MasterModel->_select('branch_master', array('id' => $branch_id), 'two_step_auth', true)->data;
				if ($check2Step != null) {
					if ($check2Step->two_step_auth == 1) {
						if ($isToken == 1) {
							if (is_array($logged_users) == true) {
								if (in_array($user_id, $logged_users)) {
									$response = $loginData;
									$updateAuthUser = $this->MasterModel->_update('auth_user_master', array('last_login_on' => date('Y-m-d H:i:s')), array('user_id' => $this->session->user_session->id, 'device_master_id' => $Did));
								} else {
									$response['mobile'] = $this->session->user_session->mobile_number;
									$response['status'] = 301;
									$response['body'] = 'Device is Not Registered';
								}
							} else {
								$response = $loginData;
								$updateAuthUser = $this->MasterModel->_update('auth_user_master', array('last_login_on' => date('Y-m-d H:i:s')), array('user_id' => $this->session->user_session->id, 'device_master_id' => $Did));
							}
						} else {
							$response['mobile'] = $this->session->user_session->mobile_number;
							$response['status'] = 301;
							$response['body'] = 'Device is Not Registered';
						}
					} else {
						$response = $loginData;
					}
				} else {
					$response = $loginData;
				}
			} else {
				$response['status'] = 201;
				$response['body'] = 'login failed';
			}
		} else {
			$response['status'] = 202;
			$response['body'] = 'login failed';
		}
		echo json_encode($response);
	}

	public function loginValidation($email, $password)
	{

		$resultData = $this->loginModel->login(trim($email),trim($password));
		if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
			if ($resultData->totalCount > 0) {
				$userdata = (object)$resultData;
				$patient_data = (object)$resultData;
				$branch_id = $userdata->branch_id;
				$patient_id = $userdata->id;
				$exp = (explode("#", $password));
				$company_id = $exp[0];
				$patient_data->name = $userdata->patient_name;
				$patient_data->mobile_number = $userdata->mobile_number;
				$patient_data->branch_id = $userdata->branch_id;
				$patient_data->patient_id = $userdata->id;
				$patient_data->company_id = $company_id;
				$patient_data->roles = 0;
				$patient_data->departments = "";
				$patient_data->hospital_room_table = "";
				$patient_data->hospital_bed_table = "";
				$patient_data->patient_bed_history_table = "";
				$patient_data->patient_mediine_table = "";
				$patient_data->patient_medicine_history_table = "";
				$this->session->user_session = $patient_data;
				$response['status'] = 200;
				$response['data'] = array("roles" => 0);
				$response['body'] = 'login successfully';
			} else {
				$response['status'] = 201;
				$response['body'] = 'login failed';
			}
		} else {
			if ($resultData->totalCount > 0) {
				// If user did not validate, then show them login page again
				// session_start();
				if ((int)$resultData->data->roles == 1) {
					$userData = $resultData->data;
					$userData->patient_mediine_table = "";
					$userData->hospital_room_table = "";
					$userData->hospital_bed_table = "";
					$userData->patient_bed_history_table = "";
					$userData->patient_medicine_history_table = "";
					$userData->departments = "";
					$get_group_id = $this->MessengerModel->get_group_id($userData->user_name);
					if ($get_group_id != false) {
						$local_group_id = $get_group_id;
					} else {
						$local_group_id = "";
					}
					$userData->group_id = $local_group_id;
					$this->session->user_session = $userData;


					$response['status'] = 200;
					$response['data'] = $userData;
					$response['body'] = 'login successfully';
				} else {
					$userData = $resultData->data;
					$branch_id = $userData->branch_id;
					$user_id = $userData->id;
					$default_access = $userData->default_access;
					$resultpermission = $this->loginModel->getPermissionData($user_id);
					if ($resultpermission != false) {
						$per_arr = array();
						foreach ($resultpermission as $row) {
							$per_arr[] = $row->permission_name;
						}
						if(is_null($per_arr)){
							$per_arr=array();
						}
						$this->session->user_permission = $per_arr;
					}
					$branchLogoResult = $this->loginModel->_select("branch_master", array("id" => $branch_id), array("branch_logo","style"));

					if ($branchLogoResult->totalCount > 0) {
						$this->session->branch_logo = $branchLogoResult->data;
					} else {
						$this->session->branch_logo = "dist/img/credit/healthstart.jpeg";
					}

					$resultBranchpermission = $this->loginModel->getBranchPermissionData($branch_id);
					if ($resultBranchpermission != false) {
						$branch_per_arr = array();
						foreach ($resultBranchpermission as $row) {
							$branch_per_arr[] = $row->permission_name;
						}
						if(is_null($branch_per_arr)){
							$branch_per_arr=array();
						}
						$this->session->Branch_permission = $branch_per_arr;
					}
					$resultBranch = $this->loginModel->getBranchCompanyData($branch_id);


					if ($this->db->affected_rows() > 0) {
						$branchData = $resultBranch->row();

						$userData->branch_name = $branchData->branch_name;
//					$userData->company_id = $branchData->id;
						$userData->company_name = $branchData->name;
						$userData->patient_table = $branchData->patient_table;
						$userData->lab_patient_table = $branchData->lab_patient_table;
						$userData->prescription_master_table = $branchData->prescription_master_table;
						$userData->patient_mediine_table = $branchData->patient_medicine_table;//dose details table
						$userData->hospital_room_table = $branchData->hospital_room_table;
						$userData->hospital_bed_table = $branchData->hospital_bed_table;
						$userData->patient_bed_history_table = $branchData->patient_bed_history_table;
						$userData->patient_medicine_history_table = $branchData->patient_medicine_history_table;//dose history table
						$userData->billing_transaction = $branchData->billing_transaction;
						$get_group_id = $this->MessengerModel->get_group_id($userData->user_name);
						if ($get_group_id != false) {
							$local_group_id = $get_group_id;
						} else {
							$local_group_id = "";
						}
						$userData->group_id = $local_group_id;
						$resultDepartment = $this->loginModel->fetchAllUserDepartments($userData->id,$branch_id);
						$response['dep'] = $resultDepartment;
						if ($resultDepartment->totalCount > 0) {
							$userData->departments = $resultDepartment->data->departments;

							$this->session->user_session = $userData;
							$response['status'] = 200;
							$response['data'] = $userData;
							$response['body'] = 'login successfully';
						} else {
							$response['status'] = 201;
							$response['body'] = 'No Role Assign';
						}
					} else {
						$response['status'] = 201;
						$response['body'] = 'No Role Assign';
					}
				}
			} else {

				$response['status'] = 201;
				$response['body'] = 'login failed';
			}
		}

		return $response;
	}

	public function updateMobile()
	{
		$mobile = $this->input->post('mobile_number');
		$user_id = $this->session->user_session->id;
		$branch_id = $this->session->user_session->branch_id;
		if ($mobile != null && $mobile != "" && $mobile) {
			$updateMobile = $this->MasterModel->_update('users_master', array('mobile_number' => $mobile), array('id' => $user_id, 'branch_id' => $branch_id));
			if ($updateMobile == true) {
				$this->session->user_session->mobile_number = $mobile;
				$response['status'] = 200;
				$response['body'] = 'Mobile Number Updated';
			} else {
				$response['status'] = 201;
				$response['body'] = 'Updation Failed';
			}
		} else {
			$response['status'] = 201;
			$response['body'] = 'Please Enter Your Number';
		}
		echo json_encode($response);
	}
	public function getDepartmentTableData()
	{
		$type = $this->input->post('type');

		$where = '';
		if (!is_null($this->input->post('companyId'))) {
			$companyId = $this->input->post('companyId');
			$where = 'where company_id="' . $companyId . '"';
		}
		$tableName = 'departments_master';
		$resultObject = $this->loginModel->getTableData($tableName, $where);
		//print_r($resultObject);exit();
		if ($resultObject->totalCount > 0) {
			$tableRows = array();
			foreach ($resultObject->data as $row) {
				$tableRows[] = array(
					$row->id,
					$row->name,
					$row->company_id,
					$row->status,
					$row->create_on,
					$row->create_by,
					$row->company_name
				);
			}
			$results = array(
				"data" => $tableRows
			);
		} else {
			$results = array(
				"draw" => (int)$_POST['draw'],
				"recordsTotal" => $resultObject->totalCount,
				"recordsFiltered" => $resultObject->totalRecored,
				"data" => $resultObject->data
			);
		}
		echo json_encode($results);

	}

	public function otp()
	{
		$mobile = $this->session->user_session->mobile_number;
		$user_id = $this->session->user_session->id;
		$branch_id = $this->session->user_session->branch_id;
		$user_type = $this->session->user_session->roles;
		$current_date = date('Y-m-d H:i:s');
		$message = "";
		$checkTodaysOtp = $this->MasterModel->_rawQuery('SELECT otp FROM otp_master where expiry_on >= "' . $current_date . '" and user_id = "' . $user_id . '" and branch_id = "' . $branch_id . '" and user_type = "' . $user_type . '" order by id desc limit 1')->data;
		if ($checkTodaysOtp != null && !empty($checkTodaysOtp)) {
			$LastOtp = $checkTodaysOtp[0]->otp;
			$message = "Please use previously sent OTP <br> on ".$mobile." to login ";
//            $this->MasterModel->sendSMS($mobile,array('otp'=>$LastOtp),'1107162869107284120','3');
		} else {

			$rnd_no = rand(1111, 9999);
			$date = date("Y-m-d H:i:s", strtotime(date("Y-m-d 23:59:59")));
			$checkOtpExists = $this->MasterModel->_select('otp_master', array('user_id' => $user_id, 'branch_id' => $branch_id, 'user_type' => $user_type), '*', true)->totalCount;
			if ($checkOtpExists > 0) {
				$updateOtp = $this->MasterModel->_update('otp_master', array('otp' => $rnd_no, 'expiry_on' => $date,'create_on'=>date('Y-m-d H:i:s')), array('user_id' => $user_id, 'branch_id' => $branch_id, 'user_type' => $user_type));
			} else {
				$insertOtp = $this->MasterModel->_insert('otp_master', array('otp' => $rnd_no, 'user_id' => $user_id, 'branch_id' => $branch_id, 'user_type' => $user_type, 'expiry_on' => $date,'create_on'=>date('Y-m-d H:i:s')));
			}
			$company = "Covidcare";
			$this->load->model("SmsModel");
			$this->SmsModel->sendSMS($mobile,
				array('company' => $company, 'otp' => $rnd_no, 'time' => $date), '1107164205399035078', '3');
			$message = "Please enter the One Time Password <br> sent on ".$mobile." <br> to verify your Device";
		}
		$response['data'] = $message;
		$response['user_id'] = $user_id;
		$this->load->view('otp_page', $response);
	}

	function generate_password( $length = 8 ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr( str_shuffle( $chars ), 0, $length );
		return $password;
	}

	public function validateOtp()
	{
		$user_id = $this->session->user_session->id;
		$branch_id = $this->session->user_session->branch_id;
		$roles = $this->session->user_session->roles;
		$user_type = $this->session->user_session->user_type;
		$default_access = $this->session->user_session->default_access;

		$userData = array(
			'roles' => $roles,
			'user_type' => $user_type,
			'default_access' => $default_access
		);

		$otp = $this->input->post('otp');
		$IP = $_SERVER['REMOTE_ADDR'];
//        $MAC = exec('getmac');
//        $mac_address = strtok($MAC, ' ');

		$mac_address =$this->generate_password(10);
		$current_date = date('Y-m-d H:i:s');
		if ($otp != null && $otp != "") {
			$ValidateOtp =
				$this->MasterModel->_rawQuery('SELECT otp FROM otp_master where expiry_on >= "' . $current_date . '" and otp = "' . $otp . '" and user_id = "' . $user_id . '" and branch_id = "' . $branch_id . '" and user_type = "' . $roles . '"')->totalCount;
//                $this->MasterModel->_select('otp_master', array('otp' => $otp,'user_id' => $user_id, 'branch_id' => $branch_id, 'user_type' => $user_type, 'expiry_on >=' => $current_date), '*', false)->data;

			if ($ValidateOtp > 0) {
				$device_id = "";
				$checkDeviceExists = $this->MasterModel->_select('device_master', array('user_id' => $user_id, 'branch_id' => $branch_id, 'mac_address' => $mac_address, 'ip_address' => $IP), '*', true);
				if ($checkDeviceExists->totalCount > 0) {
					$DeviceData = $checkDeviceExists->data;
					$device_id = $DeviceData->id;
					$updateDeviceMaster = $this->MasterModel->_update('device_master', array('mac_address' => $mac_address, 'ip_address' => $IP,), array('id' => $device_id));
					$updateUserAuth = $this->MasterModel->_update('auth_user_master', array('last_login_on' => date('Y-m-d H:i:s')), array('device_master_id' => $device_id, 'user_id' => $user_id));
				} else {
					$insertDeviceMaster = $this->MasterModel->_insert('device_master', array('mac_address' => $mac_address, 'ip_address' => $IP, 'user_id' => $user_id, 'branch_id' => $branch_id));
					$device_id = $insertDeviceMaster->inserted_id;
					$insertUserAuth = $this->MasterModel->_insert('auth_user_master', array('device_master_id' => $device_id, 'user_id' => $user_id, 'last_login_on' => date('Y-m-d H:i:s')));
				}
				$response['status'] = 200;
				$response['data'] = $userData;
				$response['device_id'] = array('device_id' => $device_id, 'mac_address' => $mac_address, 'ip_address' => $IP);
			} else {
				$response['status'] = 201;
				$response['data'] = "Enter Correct Otp";
			}
		} else {
			$response['status'] = 201;
			$response['data'] = 'Required Parameter Missing';
		}
		echo json_encode($response);
	}

	public function ResendOtp()
	{
		$user_id = $this->session->user_session->id;
		$branch_id = $this->session->user_session->branch_id;
		$mobile = $this->session->user_session->mobile_number;
		$rnd_no = rand(1111, 9999);
		$date = date("Y-m-d H:i:s", strtotime(date("Y-m-d 23:59:59")));
		$updateOtp = $this->MasterModel->_update('otp_master', array('otp' => $rnd_no, 'expiry_on' => $date,'create_on'=>date('Y-m-d H:i:s')), array('user_id' => $user_id, 'branch_id' => $branch_id));
		$company = "Covidcare";

		$this->load->model("SmsModel");
		$this->SmsModel->sendSMS($mobile, array('company' => $company, 'otp' => $rnd_no, 'time' => $date), '1107164205399035078', '3');
		if($updateOtp)
		{
			$response['status'] = 200;
			$response['data'] = 'Otp sent Successfully';
		}
		else{
			$response['status'] = 201;
			$response['data'] = 'Otp Not Updated';
		}
		echo json_encode($response);
	}
}

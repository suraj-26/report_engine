<?php

require_once 'HexaController.php';

/**
 * @property  UserModel UserModel
 */
class UserController extends HexaController
{
	/**
	 * UserController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel');
	}

	public function patientInfo()
	{
		$this->load->view('admin/patients/view_tab_patients', array("title" => "Patient Details"));
	}

	public function save_basic_details()
	{

		$requestParameter = parent::request_parameter(array('name', 'email', 'gender', 'contact', 'role', 'user_type'));
		if ($requestParameter->status) {
			$jsonObject = $requestParameter->jsonObject;
			if (property_exists($jsonObject, 'userId')) {
				$user_id = $jsonObject->userId;
			} else {
				$user_id = 0;
			}
			$name = $jsonObject->name;
			$role = $jsonObject->role;
			$email = $jsonObject->email;
			$gender = $jsonObject->gender;
			$contact = $jsonObject->contact;
			$type = $jsonObject->user_type;
			$data = array('name' => $name, 'email' => $email, 'gender' => $gender, 'contact' => $contact, 'role' => $role, 'login_status' => 1, 'login_time' => date(DATETIME));
			if (property_exists($jsonObject, 'lang')) {
				$lan = $jsonObject->lang;
				$data['lang'] = $lan;
			}
			if ($user_id === 0) {
				$id = $this->User->generate_id();
				if ($id == -1) {
					$response['status'] = 201;
					$response['body'] = "Failed To Registered";
					echo json_encode($response);
					exit();
				}
				$data['user_id'] = $id;
				$token = crypt(substr(md5(rand()), 0, 7), $id);
				$expired_at = date(DATETIME, strtotime('+8 hours'));
				$data['token'] = $token;
				$data['expiry_token'] = $expired_at;
			}
			$resultObject = $this->User->save_user_change($data, $user_id);
			if ($resultObject->status) {
				$response['status'] = 200;
				$response['body'] = parent::base64_response($resultObject);
			} else {
				$response['status'] = 201;
				$response['body'] = "Failed To Registered";
			}
		} else {
			$response = $requestParameter->response;
		}
		echo json_encode($response);
	}

	public function getUsersTableData()
	{
		$type = $this->input->post('type');

		$where = array("u.status" => 1);
		if (!is_null($this->input->post('companyId')) && $this->input->post('companyId') != "") {
			$companyId = $this->input->post('companyId');
			$where["company_id"] = $companyId;
		}


		$resultObject = $this->UserModel->getUsers($where);
		$where = array('u.status' => 1);
		if (!is_null($this->input->post('branch_id')) && $this->input->post('branch_id') != "") {
			$where['u.branch_id'] = $this->input->post('branch_id');
		}
		$tableName = "users_master u";

		$order = array('id' => 'desc');
		$column_order = array('name', 'name');
		$column_search = array("name", "id");
		$select = array("u.*", 
		"(select c.name from branch_master c where c.id=u.branch_id and c.status=1) as branch_name", 
		"(select c.name from company_master c where c.id=u.company_id and c.status=1) as company_name");

		$memData = $this->UserModel->getRows($_POST, $where, $select, $tableName, $column_search, $column_order, $order);
		$results_last_query = $this->db->last_query();
		$filterCount = $this->UserModel->countFiltered($_POST, $tableName, $where, $column_search, $column_order, $order);
		$totalCount = $this->UserModel->countAll($tableName, $where);

		if (count($memData) > 0) {
			$tableRows = array(  );
			foreach ($memData as $row) {
				$tableRows[] = array(
				$row->id,
					$row->name,
					$row->user_name,
					$row->company_name,
					$row->branch_name,
					
					$row->create_on,
					$row->create_by,
					$row->company_id,

				);
			}
			$results = array(
				"draw" => (int)$_POST['draw'],
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $tableRows
			);
		} else {
			$results = array(
				"draw" => (int)$_POST['draw'],
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $memData

			);
		}
		$results["query"]=$results_last_query;
		echo json_encode($results);


	}
	public function uploadOtherUsers(){
		
			$c_name = $this->input->post('uAllCompanies1');
			$b_name = $this->input->post('uAllBranches1');
			$u_name = $this->input->post('user_name1');
			$u_email = $this->input->post('user_email1');
			$u_pass = $this->input->post('password1');
			$user_type = $this->input->post('user_type1');
			$u_id = $this->input->post('forward_user1');
			if($user_type ==1){ //radiology
				$role=3;
				$user_type =7;
			}else if($user_type ==2){ //pathology
				$role=3;
				$user_type =6;
			}else{ //supplier
				$role=3;
				$user_type =4;
			}
			$session_data = $this->session->user_session;
			$user_id = $session_data->id;
		
			if (!empty($u_id)) {
					$userData = array('name' => $u_name,
					'user_name' => $u_email,
					'password' => $u_pass,
					'company_id' => $c_name,
					'branch_id' => $b_name,
					'user_type' => $user_type,
					'status' => 1,
					'roles' => $role,
					'create_by' => $user_id);
				$where=array("id"=>$u_id);
				$set=$userData;
				
				 $this->db->where($where);
				$result=$this->db->update("users_master",$set);
					if ($result == TRUE) {
						$response["status"] = 200;
						$response["data"] = "Updated successfully";
					} else {
						$response["status"] = 201;
						$response["data"] = "Not Found";
					}
			}else{
				
					$userData = array('name' => $u_name,
					'user_name' => $u_email,
					'password' => $u_pass,
					'company_id' => $c_name,
					'branch_id' => $b_name,
					'user_type' => $user_type,
					'status' => 1,
					'roles' => $role,
					'create_on' => date('Y-m-d'),
					'create_by' => $user_id);
				$where = 'where user_name="' . $u_email . '" and company_id="' . $c_name . '"';
				$queySelect = $this->UserModel->selectDataById("users_master", $where);
				//print_r($queySelect);exit();
				if ($queySelect->totalCount <= 0) {
					$result=$this->db->insert('users_master',$userData);
					if ($result == TRUE) {
						$response["status"] = 200;
						$response["data"] = "uploaded successfully";
					} else {
						$response["status"] = 201;
						$response["data"] = "Not Found";
					}
				}else{
					$response["status"] = 201;
					$response["data"] = "User already added in same company";
				}
			}
			echo json_encode($response);
			
	}
	public function uploadUsers()
	{
		//print_r($this->input->post('depCheck'));exit();
		if (!is_null($this->input->post('uAllCompanies')) && !is_null($this->input->post('user_name')) && !is_null($this->input->post('user_email')) && !is_null($this->input->post('password'))) {

			$c_name = $this->input->post('uAllCompanies');
			$b_name = $this->input->post('uAllBranches');
			$u_name = $this->input->post('user_name');
			$u_email = $this->input->post('user_email');
			$u_pass = $this->input->post('password');
			$u_id = $this->input->post('forward_user');
			$user_type = $this->input->post('user_type');
			$u_contact = $this->input->post('user_contact');
			$role = 2;
			
			

//			$chkdepartment = array();
//			if (!is_null($this->input->post('depCheck')) && !empty($this->input->post('depCheck')) && $this->input->post('depCheck') != '') {
//				$chkdepartment = $this->input->post('depCheck');
//			}


			$session_data = $this->session->user_session;
			$user_id = $session_data->id;
			
			$table_name1 = 'users_master';
			$table_name2 = 'role_master';
			if (!empty($u_id)) {
				$userData = array('name' => $u_name,
					'user_name' => $u_email,
					'password' => $u_pass,
					'mobile_number' => $u_contact,
					'company_id' => $c_name,
					'branch_id' => $b_name,
					'user_type' => $user_type,
					'status' => 1,
					'roles' => $role,
					'create_on' => date('Y-m-d'),
					'create_by' => $user_id);
				$chkdepartmentData = array();
//				if (!empty($chkdepartment) && $chkdepartment != "") {
//					foreach ($chkdepartment as $key => $value) {
//						$chkdepartmentData[] = array('department_id' => $value,
//							'activity_status' => 1,
//							'user_id' => $u_id,
//							'create_on' => date('Y-m-d'),
//							'create_by' => $user_id);
//					}
//				}
				$get_column_name = $this->db->query("select column_name from profile_management_table where id='$user_type'");
				$roles=array();
				$permission=array();
				if ($this->db->affected_rows() > 0) {
					$res = $get_column_name->row();
					$user = $res->column_name;

					$roles = $this->db->query("select ".$res->column_name.",permission_name,type from group_permission_table where ".$res->column_name." = 1")->result();
					if(count($roles)>0){
						foreach ($roles as $role){
							if($role->type == 2){
								$chkdepartmentData[] =array('department_id' => $role->permission_name,
									'activity_status' => 1,
									'user_id' => $u_id,
									'create_on' => date('Y-m-d'),
									'create_by' => $user_id);
							}else{
								array_push($permission,array("permission_name"=>$role->permission_name,"user_id"=>$u_id,"status"=>1));
							}
						}
					}
				}


				$where = array('id' => $u_id);
				$where1 = array('user_id' => $u_id);
				$result = $this->UserModel->updateUser($userData, $chkdepartmentData,$permission, $table_name1, $table_name2, $where, $where1);
				$this->ResendOtp($u_id,$b_name);
				//$result=$this->DepartmentModel->updateForm($table_name,$userData,$where);
				if ($result == TRUE) {
					$response["status"] = 200;
					$response["roles"]=$roles;
					$response["data"] = "updated successfully";
				} else {
					$response["status"] = 201;
					$response["data"] = "Not updated";
				}
			} else {

				$userData = array('name' => $u_name,
					'user_name' => $u_email,
					'password' => $u_pass,
					'mobile_number' => $u_contact,
					'company_id' => $c_name,
					'branch_id' => $b_name,
					'user_type' => $user_type,
					'status' => 1,
					'roles' => $role,
					'create_on' => date('Y-m-d'),
					'create_by' => $user_id);

				$chkdepartmentData = array();
//				if (!empty($chkdepartment) && $chkdepartment != "") {
//					foreach ($chkdepartment as $key => $value) {
//						$chkdepartmentData[] = array('department_id' => $value,
//							'activity_status' => 1,
//							'create_on' => date('Y-m-d'),
//							'create_by' => $user_id);
//					}
//				}
				$user = "";
				$get_column_name = $this->db->query("select column_name from profile_management_table where id='$user_type'");
				if ($this->db->affected_rows() > 0) {
					$res = $get_column_name->row();
					$user = $res->column_name;

					$roles = $this->db->query("select ".$res->column_name.",permission_name from group_permission_table where type =2 and ".$res->column_name." = 1")->result();
					if(count($roles)>0){
						foreach ($roles as $role){
							$chkdepartmentData[] =array('department_id' => $role->permission_name,
								'activity_status' => 1,
								'create_on' => date('Y-m-d'),
								'create_by' => $user_id);
						}
					}
				}

				$where = 'where user_name="' . $u_email . '" and company_id="' . $c_name . '"';
				$queySelect = $this->UserModel->selectDataById($table_name1, $where);
				//print_r($queySelect);exit();
				if ($queySelect->totalCount <= 0) {
					$result = $this->UserModel->addUser($userData, $chkdepartmentData, $table_name1, $table_name2, $user);


					if ($result == TRUE) {
						$response["status"] = 200;
						$response["data"] = "uploaded successfully";
					} else {
						$response["status"] = 201;
						$response["data"] = "Not Found";
					}
				} else {
					$response["status"] = 201;
					$response["data"] = "User already added in same company";
				}

			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Something went wrong";
		}
		echo json_encode($response);
	}

	public function ResendOtp($user_id,$branch_id)
	{
		$mobile = $this->input->post('mobile');
		$rnd_no = rand(1111, 9999);
		$date = date("Y-m-d H:i:s", strtotime(date("Y-m-d 23:59:59")));
		$updateOtp = $this->UserModel->_update('otp_master', array('otp' => $rnd_no, 'expiry_on' =>
			$date, 'create_on' => date('Y-m-d H:i:s')), array('user_id' => $user_id, 'branch_id' => $branch_id));
		$this->load->model("SmsModel");
		$this->SmsModel->sendSMS($mobile, array('company' => base_url(), 'otp' => $rnd_no, 'time' => $date), '1107164205399035078', '3');
		if ($updateOtp) {
			$response['status'] = 200;
			$response['data'] = 'Otp sent Successfully';
		} else {
			$response['status'] = 201;
			$response['data'] = 'Otp Not Updated';
		}
	}

	public function getUserDataById()
	{
		if (!is_null($this->input->post('userId'))) {
			$userId = $this->input->post('userId');
			$tableName = 'users_master';
			//$where = array('id' => $depId);
			$where = "where dm.id='" . $userId . "'";
			$resultObject = $this->UserModel->getUser($userId);
			$obj=$resultObject->data;
			$user_type=$obj[0]->user_type;
			$role=$obj[0]->roles;
			$oth=0;
			
			if($role == 3 && $user_type==7){
				$user_type1 =1;//radiology
				$oth=1;
			}else if($role == 3 && $user_type==6){
				$user_type1 =2;//pathology
				$oth=1;
			}else if($role == 3 && $user_type==4){
				$user_type1 =3;//supplier
				$oth=1;
			}else{
				$oth=0;
				$user_type1=$user_type;
			}
		
			$data=array(
			"oth"=>$oth,
			"user_type"=>$user_type1,
			);
			
			if ($resultObject->totalCount > 0) {
				$response["status"] = 200;
				$response["body"] = $resultObject->data;
				$response["data"] = $data;
			} else {
				$response["status"] = 201;
				$response["body"] = "Data Not Found";
				$response["data"] = "";
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Something went wrong";
			$response["data"] = "";
		}
		echo json_encode($response);
	}

	public function deleteUser()
	{
		if (!is_null($this->input->post('userId'))) {
			$userId = $this->input->post('userId');

			$table_name = 'users_master';
			$departmentData = array('status' => 0);
			$where = array('id' => $userId);
			$result = $this->UserModel->updateForm($table_name, $departmentData, $where);
			if ($result == TRUE) {
				$response["status"] = 200;
				$response["body"] = "Deleted successfully";
			} else {
				$response["status"] = 201;
				$response["body"] = "Not Deleted";
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Something went wrong";
		}
		echo json_encode($response);
	}

	public function getUserByType()
	{

		$validObject = $this->is_parameter(array("type"));
		$response = array();
		if ($validObject->status) {

			$type = $validObject->param->type;

			$where = array("user_type" => $type);
			$userData = $this->db->select(array("id", "name"))->where($where)->get("users_master")->result();
			$response["last_query"] = $this->db->last_query();
			$data = array();
			if (count($userData) > 0) {
				foreach ($userData as $user) {
					array_push($data, array("id" => $user->id, "text" => $user->name));
				}
			}
			$response["body"] = $data;
		} else {
			$response["body"] = array();
		}
		echo json_encode($response);
	}

	public function get_access_data()
	{
		$user_id = $this->input->post('id');
		$get_user_type = $this->db->query("select user_type from users_master where id='$user_id'");
		$user_type = "";
		if ($this->db->affected_rows() > 0) {
			$r = $get_user_type->row();
			$user_type = $r->user_type;
		}
		$data = "";
		if ($user_type != "") {

			$user = "";
			$get_column_name = $this->db->query("select column_name from profile_management_table where id='$user_type'");
			if ($this->db->affected_rows() > 0) {
				$res = $get_column_name->row();
				$user = $res->column_name;
			}
			$this->load->model('loginModel');
			$resultpermission = $this->loginModel->getPermissionData($user_id);
			$per_arr = array();
			if ($resultpermission != false) {

				foreach ($resultpermission as $row) {
					$per_arr[] = $row->permission_name;
				}

			}
			$query = $this->db->query("select * from group_permission_table");
			if ($this->db->affected_rows() > 0) {
				$result = $query->result();
				$data .= "<input type='hidden' id='user_id' name='user_id' value='" . $user_id . "'>";
				foreach ($result as $row) {
					if((int)$row->type == 1) {
						$permission_name = $row->permission_name;
						$status = $row->$user;
						$readonly = '';
						if ($status == 1) {
							$readonly = 'readonly';
						}
						if (in_array($permission_name, $per_arr)) {
							$checked = 'checked';
						} else {
							$checked = '';
						}
						$data .= '
					<input type="checkbox" ' . $checked . ' ' . $readonly . 'id="check" name="check[]" value="' . $permission_name . '" > ' . $permission_name . '<br>
					';
					}
				}
				$data .= "<hr><button type='button' onclick='save_permission()' class='btn btn-primary'>Save</button>";
				$response["body"] = $data;
				$response["status"] = 200;
			} else {
				$response["body"] = $data;
				$response["status"] = 201;
			}
		} else {
			$response["body"] = $data;
			$response["status"] = 201;
		}
		echo json_encode($response);

	}

	function save_permission()
	{
		$user_id = $this->input->post('user_id');
		$permission_array = $this->input->post('check');
		$cnt = 1;
		$delete = $this->db->delete("user_permission_table", array("user_id" => $user_id));
		for ($i = 0; $i < count($permission_array); $i++) {
			$array = array(
				"user_id" => $user_id,
				"status" => 1,
				"permission_name" => $permission_array[$i],
			);
			$data_insert = $this->db->insert("user_permission_table", $array);
			if ($data_insert == true) {
				$cnt++;
			}
		}
		if ($cnt > 1) {
			$response["body"] = "Permission Given Suceessfully";
			$response["status"] = 200;
		} else {
			$response["body"] = "Something Went Wrong";
			$response["status"] = 201;
		}
		echo json_encode($response);

	}

	function get_permission_div()
	{
		$query = $this->db->query("select * from group_permission_table");
		$data = "";
		$data .= "<label><b>Note: Select the permission wich you want to give to this profile</label></b>";
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			$data .= '';
			foreach ($result as $row) {
				if((int)$row->type == 1){
					$permission_name = $row->permission_name;
					$data .= '<div class="form-check">
					<input type="checkbox"  name="check_profile[]" id="check_profile' . $row->id . '" value="' . $permission_name . '" > 
					<label class="form-check-label" for="check_profile' .$row->id . '">
										' .  $permission_name  . '
									</label>
					</div>
					';
				}

			}
			$data .= '';
			$response["data"] = $data;
			$response["status"] = 200;
		} else {
			$response["data"] = $data;
			$response["status"] = 201;
		}
		echo json_encode($response);
	}

	function save_profile()
	{
		$profile_name = $this->input->post('profile_name');

		$check_profile = $this->input->post('check_profile');
		$roles = $this->input->post("depCheck");
		$pf_id = $this->input->post('pf_id');
		if (isset($pf_id)) {
			//update profile
			$insert = $this->UserModel->add_profile($profile_name, $check_profile,$roles, $pf_id);
			if ($insert == TRUE) {
				$response["status"] = 200;
				$response["body"] = "Update successfully";
			} else {
				$response["status"] = 201;
				$response["body"] = "Not Updated";
			}
		} else {
			//ad new profile
			if ($profile_name != "") {
				if (count($check_profile) == 0) {
					$response["status"] = 201;
					$response["body"] = "Select Atleast One Permission";
					echo json_encode($response);
					exit;
				}

				$pf_id = 0;
				$insert = $this->UserModel->add_profile($profile_name, $check_profile,$roles, $pf_id);

				if ($insert == TRUE) {
					$response["status"] = 200;
					$response["body"] = "Added successfully";
				} else {
					$response["status"] = 201;
					$response["body"] = "Not Added";
				}
			} else {
				$response["status"] = 201;
				$response["body"] = "Please Enter Profile Name";
			}
		}
		echo json_encode($response);

	}

	function get_user_types()
	{
		$query = $this->db->query("select * from profile_management_table");
		$option = "<option selected disabled>Select Type</option>";
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();

			foreach ($result as $row) {
				$option .= "<option value='" . $row->id . "'>" . $row->profile_name . "</option>";
			}
			$response["data"] = $option;
			$response["status"] = 200;
		} else {
			$response["data"] = $option;
			$response["status"] = 201;
		}
		echo json_encode($response);
	}

	function get_all_profile_data()
	{
		$query = $this->db->query("select * from profile_management_table");
		$data = "";

		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			$data .= '
		<table class="table">
		<thead>
		<tr>
		<th>Profile Name</th>
		<th>Action</th>
		</tr>
		</thead><tbody>';
			foreach ($result as $row) {
				$edit_btn = '<button class="btn btn-link" type="button" onclick="get_profile_edit(' . $row->id . ')"><i class="fa fa-pen"></i></button>';
				$data .= "<tr>
			<td>" . $row->profile_name . "</td>
			<td>" . $edit_btn . "</td>
			</tr>";

			}
			$data .= '</tbody></table>
		';
			$response["data"] = $data;
			$response["status"] = 200;
		} else {
			$response["data"] = "";
			$response["status"] = 200;
		}
		echo json_encode($response);
	}

	public function get_profile_edit()
	{
		$id = $this->input->post('id');
		$query = $this->db->query("select * from profile_management_table where id='$id'");
		$data = "";
		if ($this->db->affected_rows() > 0) {
			$result = $query->row();
			$profile_name = $result->profile_name;
			$column_name = $result->column_name;
			$get_permission_data = $this->UserModel->getPermissionDataProfile($column_name);
			$roles =array();
			if ($get_permission_data != false) {
				$data .= "<label><b>Note: Select the permission wich you want to give to this profile</label></b><br>";
				$data .= "<input type='hidden' id='pf_id' name='pf_id' value='" . $id . "'>";
				foreach ($get_permission_data as $row) {
					if((int) $row->type == 1){
						$permission_name = $row->permission_name;
						$status = $row->$column_name;
						if ($status == 1) {
							$checked = "checked";
						} else {
							$checked = "";
						}
						$data .= '
					<input type="checkbox" ' . $checked . ' id="check_profile" name="check_profile[]" value="' . $permission_name . '" > ' . $permission_name . '<br>
					';
					}else{
						array_push($roles,array($row->permission_name,(int)$row->$column_name));
					}

				}
			}
			$response["name"] = $profile_name;
			$response["data"] = $data;
			$response["roles"] = $roles;
			$response["status"] = 200;
		} else {
			$response["data"] = "";
			$response["status"] = 200;
		}
		echo json_encode($response);
	}
}

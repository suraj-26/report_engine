<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property  DepartmentModel DepartmentModel
 */
class DepartmentController extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		// load model
		$this->load->model('DepartmentModel');

		// load base_url
		$this->load->helper('url');

	}

	public function index()
	{

		$this->load->view('create_company');
	}

	public function getDepartmentTableData()
	{
		$type = $this->input->post('type');
		$search = $this->input->post('search');
		$value = '';
		if(count($search) >0){
			$value = $search['value'];
		}
		$where = array();
		if (!is_null($this->input->post('companyId')) && $this->input->post('companyId') != "") {
			$companyId = $this->input->post('companyId');
			$where['company_id'] = $companyId;
		}

		$like = 'name';

		$resultObject = $this->DepartmentModel->getTableData($where,$like,$value);

		if ($resultObject->totalCount > 0) {
			$tableRows = array();
			foreach ($resultObject->data as $row) {
				if((int)$row->is_admin==1)
				{
					$type="Admin";
				}
				else if((int)$row->is_admin==2)
				{
					$type="Free Form";
				}
				else
				{
					$type="Normal";
				}

				$tableRows[] = array(
					$row->name,
					$row->company_name,
					$type,
					$row->status,
					$row->create_on,
					$row->create_by,
					$row->id,
					$row->is_admin,
					urlencode(base64_encode($row->id))
				);
			}
			$results = array(
				"draw" => (int)$_POST['draw'],
				"recordsTotal" => $resultObject->totalCount,
				"recordsFiltered" => $resultObject->totalCount,
				"data" => $tableRows
			);
		} else {
			$results = array(
				"draw" => (int)$_POST['draw'],
				"recordsTotal" => $resultObject->totalCount,
				"recordsFiltered" => $resultObject->totalCount,
				"data" => $resultObject->data
			);
		}
		$results['last_query'] = $resultObject->last_query;
		echo json_encode($results);

	}

	public function selectAllCompanies()
	{
		$tableName = 'company_master';
		$resultObject = $this->DepartmentModel->getSelectCompaniesData($tableName);
		if ($resultObject->totalCount > 0) {
			$option = '<option selected value="0">All</option>';

			foreach ($resultObject->data as $key => $value) {
				$option .= '<option value="' . $value->id . '">' . $value->name . '</option>';
			}
			$results['status'] = 200;
			$results['option'] = $option;
		} else {
			$results['status'] = 201;
			$results['option'] = '';
		}
		echo json_encode($results);
	}

	public function selectCompanyDepartment()
	{
		if (!is_null($this->input->post('companyId')) && $this->input->post('companyId') != "") {
			$companyId = $this->input->post('companyId');
			$tableName = 'departments_master';
			//$where = array('company_id' =>$companyId );
			$where = 'where company_id="' . $companyId . '"';
			$resultObject = $this->DepartmentModel->selectDataById($tableName, $where);
			if ($resultObject->totalCount > 0) {
				$checkbox = '
				<div class="form-check">
									<input class="form-check-input" type="checkbox"  onClick="checkDepartment(this)" id="defaultCheck">
									<label class="form-check-label" for="defaultCheck">
										All
									</label>
							</div>';
				foreach ($resultObject->data as $key => $value) {
					$checkbox .= '<div class="form-check">
									<input class="form-check-input" name="depCheck[]" multiple type="checkbox" id="defaultCheck' . $value->id . '" value="' . $value->id . '">
									<label class="form-check-label" for="defaultCheck' .$value->id . '">
										' . $value->name . '
									</label>
								</div>';
				}
				$results['status'] = 200;
				$results['data'] = $checkbox;
			} else {
				$results['status'] = 201;
				$results['data'] = '';
			}
		} else {
			$results['status'] = 201;
			$results['data'] = '';
		}
		echo json_encode($results);
	}

	public function uploadDepartment()
	{
		//print_r($this->input->post());exit();
		if (!is_null($this->input->post('dcompany_id')) && !is_null($this->input->post('department_name'))) {

			$c_name = $this->input->post('dcompany_id');
			$de_name = $this->input->post('department_name');

			$department_id = $this->input->post('forward_department');
			$de_description = $this->input->post('department_description');
			$ch_admin_template = $this->input->post('ch_admin_template');
			$ch_free_template = $this->input->post('ch_free_template');
			$ck_admin_template = 0;
			if (!is_null($ch_admin_template)) {
				$ck_admin_template = $ch_admin_template == "on" ? 1 : 0;
			}
			if (!is_null($ch_free_template)) {
				if($ch_free_template == "on")
				{
					$ck_admin_template = 2;
				}

			}
			if (is_null($this->input->post('department_description')) && $this->input->post('department_description') != "") {
				$de_description = "";
			}

			//$session_data = $this->session->user_session;

			$user_id =1; //$session_data->id;
			$table_name = 'departments_master';
			if (!empty($department_id)) {
				$departmentData = array('name' => $de_name,
					'company_id' => $c_name,
					'status' => 1,
					'is_admin'=>$ck_admin_template,
					'description' => $de_description,
					'create_on' => date('Y-m-d'),
					'create_by' => $user_id);
				$where = array('id' => $department_id);
				$result = $this->DepartmentModel->updateForm($table_name, $departmentData, $where);
				if ($result == TRUE) {
					$response["status"] = 200;
					$response["data"] = "updated successfully";
				} else {
					$response["status"] = 201;
					$response["data"] = "Not updated";
				}
			} else {

				$departmentData = array('name' => $de_name,
					'company_id' => $c_name,
					'status' => 1,
					'is_admin'=>$ck_admin_template,
					'description' => $de_description,
					'create_on' => date('Y-m-d'),
					'create_by' => $user_id);

				$result = $this->DepartmentModel->addForm($table_name, $departmentData);

				if ($result == TRUE) {
					$response["status"] = 200;
					$response["data"] = "uploaded successfully";
				} else {
					$response["status"] = 201;
					$response["data"] = "Not Found";
				}
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Something went wrong";
		}
		echo json_encode($response);
	}

	public function getDepartmentDataById()
	{

		if (!is_null($this->input->post('depId'))) {
			$depId = $this->input->post('depId');
			$where = array("dm.id" => $depId);
			$resultObject = $this->DepartmentModel->getTableDatas($where);
			if ($resultObject->totalCount > 0) {
				$response["status"] = 200;
				$response["body"] = $resultObject->data;
			} else {
				$response["status"] = 201;
				$response["body"] = "Data Not Found";
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Something went wrong";
		}
		echo json_encode($response);
	}

	public function deleteDeaprtment()
	{
		if (!is_null($this->input->post('depId'))) {
			$depId = $this->input->post('depId');

			$table_name = 'departments_master';
			$departmentData = array('status' => 0);
			$where = array('id' => $depId);
			$result = $this->DepartmentModel->updateForm($table_name, $departmentData, $where);
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


	public function ChangeDepartmentStatus()
	{
		if (!is_null($this->input->post('depId')) && !is_null($this->input->post('status'))) {
			$depId = $this->input->post('depId');
			$status = $this->input->post('status');
			$table_name = 'departments_master';
			$departmentData = array('status' => $status);
			$where = array('id' => $depId);
			$result = $this->DepartmentModel->updateForm($table_name, $departmentData, $where);
			if ($result == TRUE) {
				$response["status"] = 200;
				$response["body"] = "Process status change successfully";
			} else {
				$response["status"] = 201;
				$response["body"] = "Status Not Changed";
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Something went wrong";
		}
		echo json_encode($response);
	}

}

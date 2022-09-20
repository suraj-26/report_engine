<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExcelUploadController extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 *        http://example.com/index.php/welcome
	 *    - or -
	 *        http://example.com/index.php/welcome/index
	 *    - or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	 function __construct()
	{
		parent::__construct();
		$this->load->model('Global_model');
	}
	public function index()
	{
		$this->load->view('ExcelUpload/excel_upload', array("title" => "Login"));
	}


	public function add_Excel_file()
	{
		
		$mimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel','text/xls');
		/* var_dump($_FILES['userfile']['type']);
		exit; */
		if(!in_array($_FILES['userfile']['type'],$mimes)){
		  $response['status'] = 202;
			$response['body'] = "Upload Excel file.";
			echo json_encode($response);
			exit;
		}
		$path = $_FILES["userfile"]["tmp_name"];
		// print_r($path);exit();
		// $function_monthly_file = $this->monthly_file_function($path);
		$this->load->library('excel');
		$object = PHPExcel_IOFactory::load($path);
		//$objReader  = PHPExcel_IOFactory::createReader('CSV');
	//	$object = $objReader->load($path);
		/* $objReader = new PHPExcel_Reader_CSV();
		$objReader->setInputEncoding('CP1252');
		$objReader->setDelimiter(';');
		$objReader->setEnclosure('');
		$objReader->setLineEnding("\r\n");
		$objReader->setSheetIndex(0); */
		//$object =$objReader->load($path);
		$worksheet = $object->getActiveSheet();
	 	$highestRow = $worksheet->getHighestRow();
		$highestColumn = $worksheet->getHighestColumn();
		$branch_id = $this->session->user_session->branch_id;
		//cells check if wrong uploaded file
		$cnt = 1;
		$dupliacte_array = array();
		$dupliacte_array1 = array();
		$duplicate_count=0;
		$insert_count=0;
		$duplicate_table="";
		$duplicate_table .="
		<table class='table table-responsive' id='dup_table'><thead>
		<tr>
		<th>VisitDate</th>
		<th>Location</th>
		<th>visit_number</th>
		<th>Patient_number</th>
		<th>Patient_Age</th>
		<th>OrderTest</th>
		<th>ParameterId</th>
		<th>ParameterName</th>
		<th>result</th>
		<th>unit</th>
		<th>ref_range</th>
		<th>sample_name</th>
		<th>method_name</th>
		<th>approveDateTime</th>
		<th>approveBy</th>
		<th>orderId</th>
		</tr>
		</thead>
		<tbody>
		";
		
		
		for ($i = 2; $i <= $highestRow; $i++) {
			$VisitDate = $object->getActiveSheet()->getCell('A' . $i)->getFormattedValue();
			$Orgname = $object->getActiveSheet()->getCell('B' . $i)->getValue();
			$Location = $object->getActiveSheet()->getCell('C' . $i)->getValue();
			$visit_number = $object->getActiveSheet()->getCell('D' . $i)->getValue();
			$Patient_number = $object->getActiveSheet()->getCell('E' . $i)->getValue();
			$Patient_Name = $object->getActiveSheet()->getCell('F' . $i)->getValue();
			$Patient_Age = $object->getActiveSheet()->getCell('G' . $i)->getValue();
			$Gender = $object->getActiveSheet()->getCell('H' . $i)->getValue();
			$OrderTest = $object->getActiveSheet()->getCell('I' . $i)->getValue();
			$ParameterId = $object->getActiveSheet()->getCell('J' . $i)->getValue();
			$ParameterName = $object->getActiveSheet()->getCell('K' . $i)->getValue();
			$result = $object->getActiveSheet()->getCell('L' . $i)->getValue();
			$unit = $object->getActiveSheet()->getCell('M' . $i)->getValue();
			$ref_range = $object->getActiveSheet()->getCell('N' . $i)->getValue();
			$sample_name = $object->getActiveSheet()->getCell('O' . $i)->getValue();
			$method_name = $object->getActiveSheet()->getCell('P' . $i)->getValue();
			$approveDateTime = $object->getActiveSheet()->getCell('Q' . $i)->getFormattedValue();
			$approveBy = $object->getActiveSheet()->getCell('R' . $i)->getValue();
			$orderId = $object->getActiveSheet()->getCell('S' . $i)->getValue();
			if($Patient_number[0]=='N'){
			$id = ltrim($Patient_number,'N');
			$p_id=ltrim($id,0);
			}
			if($orderId[0]=='A'){
			$order_number=ltrim($orderId,'AA00');
			}
			$data = array("VisitDate" => $VisitDate,
				"Orgname" => $Orgname,
				"Location" => $Location,
				"visit_number" => $visit_number,
				"Patient_number" => $Patient_number,
				"Patient_Name" => $Patient_Name,
				"Patient_Age" => $Patient_Age,
				"OrderTest" => $OrderTest,
				"ParameterId" => $ParameterId,
				"ParameterName" => $ParameterName,
				"result" => $result,
				"unit" => $unit,
				"ref_range" => $ref_range,
				"sample_name" => $sample_name,
				"method_name" => $method_name,
				"approveDateTime" => $approveDateTime,
				"approveBy" => $approveBy,
				"orderId" => $orderId,
				"branch_id" => $branch_id,
				"patient_id" => $p_id,
				"order_number" => $order_number,
			);
			
			$tablename = "excel_structure_data";
			if($orderId != ""){
			$query1=$this->db->query("select * from excel_structure_data where orderId='$orderId' AND ParameterId='$ParameterId'");
			if($this->db->affected_rows() > 0){
				$duplicate_count++;
				$duplicate_table .='<tr>
				<td>'.$VisitDate.'</td>
				<td>'.$Orgname.'</td>
				<td>'.$Location.'</td>
				<td>'.$visit_number.'</td>
				<td>'.$Patient_number.'</td>
				<td>'.$Patient_Name.'</td>
				<td>'.$Patient_Age.'</td>
				<td>'.$OrderTest.'</td>
				<td>'.$ParameterId.'</td>
				<td>'.$ParameterName.'</td>
				<td>'.$result.'</td>
				<td>'.$unit.'</td>
				<td>'.$ref_range.'</td>
				<td>'.$sample_name.'</td>
				<td>'.$method_name.'</td>
				<td>'.$approveDateTime.'</td>
				<td>'.$approveBy.'</td>
				<td>'.$orderId.'</td>
				</tr>';
			}else{
						 try {
					$this->db->trans_start();
					$insert=$this->db->insert($tablename,$data);
					if($insert == true){
						$this->db->where(array("id"=>$order_number));
						$update=$this->db->update("service_order",array("sample_status"=>1));
						$insert_count++;
					}
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
					  
					} else {
						$this->db->trans_commit();
					 
					}
					$this->db->trans_complete();
				 } catch (Exception $exc) {
					$this->db->trans_rollback();
					$this->db->trans_complete();
				   
				}
			}
			}
			/* $excelUploadData[$orderId] = $data;
			$excelUploadData1[$ParameterId] = $data; */
		}
		
		$duplicate_table .="</tbody></table>";
		/* $orders = array_keys($excelUploadData);
		$orderIds = implode(',',$orders);
		$Parameters = array_keys($excelUploadData1);
		$ParameterIds = implode(',',$Parameters);

		$tablename = "excel_structure_data";
		$existingOrders =array();
		$resultQuery = $this->db->query('select orderId from ' . $tablename . ' where  find_in_set(orderId,"' . $orderIds . '") AND find_in_set(ParameterId,"' . $ParameterIds . '")')->result();
		
		if(is_array($resultQuery)){
			foreach ($resultQuery as $orders){
				array_push($existingOrders,$orders);
				unset($excelUploadData[$orders]);
			}
		}
		try{
			$this->db->trans_start();
			if(count($excelUploadData)>0){
				$this->db->insert_batch($tablename,$excelUploadData);
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('info', "update form Transaction Rollback");
				$result = FALSE;
			} else {
				$this->db->trans_commit();
				log_message('info', "update form Transaction Commited");
				$result = TRUE;
			}
			$this->db->trans_complete();
		}catch (Exception $exception){
			$result = FALSE;
			$this->db->trans_rollback();
		}
		 */
		
		

		$response['insertCount'] = ($insert_count);
		$response['existingCount'] = ($duplicate_count);
		$response['duplicate_table'] = ($duplicate_table);
//$response['existingOrders'] = $existingOrders;
		if ($insert_count > 0) {
		//file_ex1
		$upload_path = "uploads";
		$combination = 2;
		$name_input = "file_ex1";
		$result = $this->Global_model->upload_file($upload_path);
		if ($result['status'] == 200) {
			$input_data = $result['body'];
			} else {
				$input_data = "";
			}
			$date=date('Y-m-d');
			$user_id = $this->session->user_session->id;
			$excel_data=array("created_on"=>$date,
			"created_by"=>$user_id,
			"file_name"=>"uploads/".$input_data,
			);
			$this->db->insert("excel_files_table",$excel_data);
			$response['status'] = 200;
			$response['body'] = "Added Successfully";
		} else {
			$response['status'] = 201;
			$response['body'] = "something went wrong Or You are uploading same file again.";
		}
		echo json_encode($response);


	}
	
	function getuploadedFiles(){
		$user_id = $this->session->user_session->id;
		$query=$this->db->query("select * from excel_files_table where created_by='$user_id' order by created_on desc");
		$data="";
		$data = '<table class="table"id="fileTable">
		<thead>
		<tr>
		<th>File Name</th>
		<th>Uploaded Date</th>
		</tr>
		</thead>
		<tbody>
		';
		if($this->db->affected_rows() > 0){
			
			$result=$query->result();
			foreach($result as $row){
				
				$btn='<a href="'.base_url().$row->file_name.'" download>
 <button type="button" class="btn btn-link"><i class="fa fa-download"></i></button>
</a>';
				$data .="<tr>
				<td>".$btn."</td>
				<td>".$row->created_on."</td>
				</tr>";
			}
			
		$data .="</tbody></table>";
		$response['status'] = 200;
			$response['data'] = $data;
		} else {
			$response['status'] = 201;
			$response['data'] = $data;	
		}echo json_encode($response);
	}

}

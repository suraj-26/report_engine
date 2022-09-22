<?php
require_once 'HexaController.php';

use Dompdf\Dompdf;

/**
 * @property  LabReport LabReport
 */
class Report extends HexaController
{


	/**
	 * Report constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model("MasterModel");
	}

	public function Reports_query()
	{
		$this->load->view('Report/view_reports', array("title" => "Reports"));
	}

	public function report_view()
	{
		$this->load->view('ReportView/dashboard', array("title" => "Reports"));
	}
	public function get_user_report()
	{
		$this->load->model('LabReport');
		$requestParameter = parent::request_parameter(array('userId'));
		if ($requestParameter->status) {
			$resultObject = $this->LabReport->getReportByPatientId($requestParameter->jsonObject->userId);
			if ($resultObject->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = parent::base64_response($resultObject->data);
			} else {
				$response['status'] = 201;
				$response['body'] = $resultObject->data;
			}
		} else {
			$response = $requestParameter->response;
		}
		echo json_encode($response);
	}


	public function mark_as()
	{
		$this->load->model('LabReport');
		$requestParameter = parent::request_parameter(array('reportId', 'type'));
		if ($requestParameter->status) {
			$resultObject = $this->LabReport->setReportMarkAs($requestParameter->jsonObject->reportId, $requestParameter->jsonObject->type);
			if ($resultObject->status) {
				$response['status'] = 200;
				$response['body'] = "Save Changes";
			} else {
				$response['status'] = 201;
				$response['body'] = "Failed To Save Changes";
			}
		} else {
			$response = $requestParameter->response;
		}
		echo json_encode($response);
	}

	public function mark_as_notify()
	{
		$requestParameter = parent::request_parameter(array('reportIds'));
		if ($requestParameter->status) {
			$reportArray = $requestParameter->jsonObject->reportIds;
			$notifyReport = array();
			foreach ($reportArray as $item) {
				array_push($notifyReport, array("id" => $item, "mark_as" => 1));
			}
			$resultObject = $this->LabReport->setReportMarkAsNotify($notifyReport);
			if ($resultObject->status) {
				$response['status'] = 200;
				$response['body'] = "Save Changes";
			} else {
				$response['status'] = 201;
				$response['body'] = "Failed To Save Changes";
			}
		} else {
			$response = $requestParameter->response;
		}
		echo json_encode($response);
	}

	public function getFormData()
	{
		$id = $this->input->post('id');

		$param = '';
		$query_val = "";
		$reoprt_name = "";
		$sub_report_name = "";
		$display_type = "";
		$query_id = 0;
		$option = '<option value="1">Data Table</option>
			<option value="0">Normal Table</option>';
		$p_arr = array();
		$d_arr = array();
		$l_arr = array();
		$v_arr = array();
		if (!is_null($id) && !empty($id)) {

			$query = $this->db->query("select * from query_reports_table where id=" . $id);
			if ($this->db->affected_rows() > 0) {
				$result = $query->row();
				$query_val = $result->query;
				$reoprt_name = $result->reoprt_name;
				$sub_report_name = $result->sub_report_name;
				$display_type = $result->display_type;
				$query_id = $result->id;

				for ($i = 1; $i <= 5; $i++) {
					$p = "param" . $i;
					$d = "datatype" . $i;
					$l = "lable" . $i;
					$v = "value" . $i;
					$p_arr[] = $result->$p;
					$d_arr[] = $result->$d;
					$l_arr[] = $result->$l;
					$v_arr[] = $result->$v;
				}
			}
		}
		$m = 0;
		for ($i = 1; $i <= 5; $i++) {
			if (count($p_arr) > 0) {
				$paramData = $p_arr[$m];
			} else {
				$paramData = "";
			}
			if (count($d_arr) > 0) {
				$dtypeData = $d_arr[$m];
			} else {
				$dtypeData = "";
			}
			if (count($l_arr) > 0) {
				$lableData = $l_arr[$m];
			} else {
				$lableData = "";
			}
			if (count($v_arr) > 0) {
				$valueData = $v_arr[$m];
			} else {
				$valueData = "";
			}
			$param .= '<div class="form-group row">
		
		<div class="col-md-3">
			<lable>parameter' . $i . '</lable>
			<input type="text" class="form-control" value="' . $paramData . '" id="param' . $i . '" name="param[]">
		</div>
		<div class="col-md-3">
			<lable>Datatype' . $i . '</lable>
			<select class="form-control" id="datatype' . $i . '" name="datatype[]">
			<option value="1">Text</option>
			<option value="2">Date</option>
			<option value="3">Numeric</option>
			<option value="4">Alpha Numeric</option>
			</select>
			<script>
			$("#datatype' . $i . '").val(' . $dtypeData . ');
			</script>
		</div>
		<div class="col-md-3">
			<lable>Lable' . $i . '</lable>
			<input type="text" class="form-control" value="' . $lableData . '" id="lable' . $i . '" name="label[]">
		</div>
		<div class="col-md-3">
			<lable>Option' . $i . '</lable>
			<input type="text" class="form-control" value="' . $valueData . '" id="option' . $i . '" name="option[]">
		</div>
		</div>';
			$m++;
		}
		$data = '';
		$data .= '
		<form name="query_form" id="query_form" method="post">
		<input type="hidden" id="query_id_edit" name="query_id_edit" value="' . $query_id . '">
		<div class="form-group row">
		<div class="col-md-4">
			<lable>Report Name</lable>
			<input type="text" class="form-control" id="report_name" value="' . $reoprt_name . '"name="report_name">
		</div>
		<div class="col-md-4">
			<lable>Sub Report Name</lable>
			<input type="text" class="form-control" id="sub_report_name" value="' . $sub_report_name . '" name="sub_report_name">
		</div>
		<div class="col-md-4">
			<lable>Display Type</lable>
			<select id="display_type" class="form-control" name="display_type">
			' . $option . '
			</select>
			<script>
			$("#display_type").val(' . $display_type . ');
			</script>
		</div>
		</div>
		<div class="form-group row">
		
		<div class="col-md-12">
			<lable>Query</lable>
			<textarea class="form-control" id="query_data"  name="query_data">
			' . $query_val . '
			</textarea>
		</div>
		</div>
		' . $param . '
		<button type="button" onclick="saveFormData()" class="btn btn-primary">Save</button>
		</form>
		';
		$response['data'] = $data;
		echo json_encode($response);
	}

	function saveFormData()
	{
		$query_id_edit = $this->input->post('query_id_edit');
		$report_name = $this->input->post('report_name');
		$sub_report_name = $this->input->post('sub_report_name');
		$query_data = $this->input->post('query_data');
		$param = $this->input->post('param');
		$datatype = $this->input->post('datatype');
		$label = $this->input->post('label');
		$option = $this->input->post('option');
		$display_type = $this->input->post('display_type');
		$data = array();
		$data['reoprt_name'] = $report_name;
		$data['sub_report_name'] = $sub_report_name;
		$data['query'] = $query_data;
		$data['display_type'] = $display_type;
		$k = 1;
		$cnt = 1;
		for ($n = 0; $n < count($param); $n++) {
			if ($param[$n] != "") {
				$p = "param" . $k;
				$d = "datatype" . $k;
				$l = "lable" . $k;
				$v = "value" . $k;
				$data[$p] = $param[$n];
				$data[$d] = $datatype[$n];
				$data[$l] = $label[$n];
				$data[$v] = $option[$n];
			}
			$k++;

		}
		if ($query_id_edit == 0) {
			$insert = $this->db->insert("query_reports_table", $data);
		} else {
			$this->db->where(array("id" => $query_id_edit));
			$insert = $this->db->update("query_reports_table", $data);
		}


		if ($insert == true) {
			$response['status'] = 200;
			$response['body'] = "Report Added SuccessFully";
		} else {
			$response['status'] = 201;
			$response['body'] = "Something Went Wrong";
		}
		echo json_encode($response);

	}

	function getReportData()
	{
		$query = $this->db->query("select * from query_reports_table");
		$data = "";
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();

			foreach ($result as $row) {
				$data .= "<button class='btn btn-primary' id='report_btn" . $row->id . "' onclick='get_report(" . $row->id . ")' >" . $row->reoprt_name . "</button>";
			}
			$response['data'] = $data;
		} else {
			$response['data'] = $data;
		}
		echo json_encode($response);
	}

	public function getReportFormData()
	{
		$id = $this->input->post('id');
		$query = $this->db->query("select * from query_reports_table where id=" . $id);
		$data = "";
		if ($this->db->affected_rows() > 0) {
			$result = $query->row();
			//var_dump($result);
			$reoprt_name = $result->reoprt_name;
			$data .= '<form id="download_form">
			<input type="hidden" id="query_id" name="query_id" value="' . $id . '"><br>';
			for ($i = 1; $i <= 5; $i++) {
				$DatatypeV = "datatype" . $i;
				$lableV = "lable" . $i;
				$valueV = "value" . $i;
				$datatype = $result->$DatatypeV;
				$lable = $result->$lableV;
				$value = $result->$valueV;

				if (!is_null($datatype) && !is_null($lable)) {
					if ($datatype == 1) {
						$type = "text";
					} else if ($datatype == 2) {
						$type = "date";
					} else if ($datatype == 3) {
						$type = "number";
					} else {
						$type = "text";
					}

					if (is_null($value) || empty($value)) {
						$data .= '<div class="col-md-12">
							<lable>' . $lable . '</lable>
							<input class="form-control" type="' . $type . '" id="input_val' . $i . '" name="input_val' . $i . '">
							</div>';
					} else {
						$exp = explode(",", $value);
						$option = "<option value='' selected disabled>Select Option</option>";
						for ($j = 0; $j < count($exp); $j++) {
							$option .= "<option value='" . $exp[$j] . "'>" . $exp[$j] . "</option>";
						}
						$data .= '<div class="col-md-12">
							<lable>' . $lable . '</lable>
							<select class="form-control" id="input_val' . $i . '" name="input_val' . $i . '">
							' . $option . '
							</select>
							</div>';
					}

				}
			}
			$data .= ' <br><div style="float:right"><button type="button" class="btn btn-primary" onclick="ShowData()">Show Report</button>
          <button type="button" class="btn btn-primary"onclick="DownloadData()">Download Report</button>
		  </div>
		  </form><br>';

			$response['status'] = 200;
			$response['data'] = $data;
		} else {
			$response['status'] = 201;
			$response['data'] = $data;
		}
		echo json_encode($response);
	}

	function ShowData()
	{
		$id = $this->input->post('query_id');
		$branch_id = $this->session->user_session->branch_id;
//		$patient_table = $this->session->user_session->patient_table;
		$query = $this->db->query("select * from query_reports_table where id=" . $id);
		if ($this->db->affected_rows() > 0) {
			$result = $query->row();
			$query_val = $result->query;
			$is_dataTable = $result->display_type;
			$branchiDV = "#branch_id";
//			$patient_tableV = '"#patient_table"';
			for ($i = 1; $i <= 5; $i++) {
				$ParamV = "param" . $i;
				$param = $result->$ParamV;
				if (!is_null($param)) {
					$value = $this->input->post('input_val' . $i);
					$char = "#" . $param;
					$query_val = str_replace($char, $value, $query_val);

				}
			}

			$query_val = str_replace($branchiDV, $branch_id, $query_val);
//			$query_val = str_replace($patient_tableV, $patient_table, $query_val);

			$array_head = array();
			$array_data = array();
			$table_td = "";
			$table_th = "";
			$table = "";
			$query_new = $this->db->query($query_val);
			$response['query'] = $this->db->last_query();
			if ($this->db->affected_rows() > 0) {
				$q_result = $query_new->result();

				$m = 0;
				foreach ($q_result as $row) {
					$table_td .= "<tr>";

					$array_data[] = array_values((array)$row);


					foreach ($row as $key => $r) {
						if ($m == 0) {
							$array_head[] = $key;
						}

						$table_td .= "<td>" . $r . "</td>";
					}
					$table_td .= "</tr>";
					$m++;

				}
				$table_th .= "<tr>";
				/* var_dump($array_data);
			   exit;  */
				for ($i = 0; $i < count($array_head); $i++) {
					$table_th .= "<th>
				" . $array_head[$i] . "
				</th>";
				}

				$table_th .= "</tr>";

				if ($is_dataTable == 1) {
					$table .= '
			<table class="table table-bordered" id="table_data1">
			<thead>
			' . $table_th . '
			</thead>
			<tbody>
			</tbody>
			</table>
			';
				} else {
					$table .= '
			<table class="table table-bordered" id="table_data">
			<thead>
			' . $table_th . '
			</thead>
			<tbody>
			' . $table_td . '
			</tbody>
			</table>
			
			';
				}


			}

			$response['array_data'] = $array_data;
			$response['is_dataTable'] = $is_dataTable;
			$response['table'] = $table;
			$response['status'] = 200;

		} else {
			$response['table'] = $table;
			$response['status'] = 200;
		}
		echo json_encode($response);
	}

	function DownloadData()
	{
		$formdata = $this->input->post_get('formdata');
		$object = json_decode($formdata);

		$id = $object->query_id;
		$branch_id = $this->session->user_session->branch_id;
//		$patient_table = $this->session->user_session->patient_table;
		$this->load->library('excel');
		//$listInfo = $this->export->exportList();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$query = $this->db->query("select * from query_reports_table where id=" . $id);
		if ($this->db->affected_rows() > 0) {
			$result = $query->row();
			$query_val = $result->query;
			for ($i = 1; $i <= 5; $i++) {
				$ParamV = "param" . $i;
				$param = $result->$ParamV;

				if (!is_null($param)) {
					//$value=$this->input->post('input_val'.$i);
					$v = 'input_val' . $i;
					$value = $object->$v;
					$char = "#" . $param;
					$query_val = str_replace($char, $value, $query_val);

				}
			}
			$branchiDV = "#branch_id";
			$patient_tableV = '"#patient_table"';
			$query_val = str_replace($branchiDV, $branch_id, $query_val);
//			$query_val = str_replace($patient_tableV, $patient_table, $query_val);
			$array_head = array();

			$query_new = $this->db->query($query_val);
			if ($this->db->affected_rows() > 0) {
				$q_result = $query_new->result();

				$m = 0;
				$rowCount = 2;

				foreach ($q_result as $row) {
					$col = 'A';
					foreach ($row as $key => $r) {
						if ($m == 0) {
							$array_head[] = $key;
						}

						//other row code
						$objPHPExcel->getActiveSheet()->SetCellValue($col . $rowCount, $r);
						$col++;
					}
					$m++;
					$rowCount++;

				}

				$column = 'A';
				for ($i = 0; $i < count($array_head); $i++) {
					//excel head code
					$objPHPExcel->getActiveSheet()->SetCellValue($column . '1', $array_head[$i]);
					$column++;
				}


			}

			ob_end_clean();
			ini_set('memory_limit', '-1');
			$filename = "Dashboard_Report" . date("Y-m-d") . "" . time() . ".xls";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');


		}
	}

	public function getQueryData()
	{
		$tableName = "query_reports_table m";
		$d = "*";
		$select = array($d);
		$where = array();
		$order = array();
		$group_by = array();
		$column_order = array('reoprt_name');
		$column_search = array('reoprt_name', "sub_report_name");

		$memData = $this->MasterModel->getRows($_POST, $where, $select, $tableName, $column_search, $column_order, $order, $group_by);

		$filterCount = $this->MasterModel->countFiltered($_POST, $tableName, $where, $column_search, $column_order, $order);
		$totalCount = $this->MasterModel->countAll($tableName, $where);
		//	echo $this->db->last_query();
		if (count($memData) > 0) {
			$tableRows = array();

			// print_r($memData);exit();
			foreach ($memData as $row) {
				if ($row->display_type == 1) {
					$display_type = "Data Table";
				} else {
					$display_type = "Normal Table";
				}
				$paramData = "";
				$Datatype = "";
				$Lableata = "";
				$ValueData = "";
				for ($j = 1; $j <= 5; $j++) {
					$p = "param" . $j;
					$d = "datatype" . $j;
					$l = "lable" . $j;
					$v = "value" . $j;
					if (!is_null($row->$p)) {
						$paramData .= $row->$p . ",";
					}
					if (!is_null($row->$d)) {
						$datatype1 = $row->$d;
						if ($datatype1 == 1) {
							$type = "text";
						} else if ($datatype1 == 2) {
							$type = "date";
						} else if ($datatype1 == 3) {
							$type = "number";
						} else {
							$type = "text";
						}
						$Datatype .= $type . ",";
					}
					if (!is_null($row->$l)) {
						$Lableata .= $row->$l . ",";
					}
					if (!is_null($row->$v)) {
						$ValueData .= $row->$v . "|";
					}
				}
				$paramData = rtrim($paramData, ",");
				$Datatype = rtrim($Datatype, ",");
				$Lableata = rtrim($Lableata, ",");
				$ValueData = rtrim($ValueData, "|");
				$tableRows[] = array(
					$row->reoprt_name,
					$row->sub_report_name,
					$row->query,
					$display_type,
					$paramData,
					$Datatype,
					$Lableata,
					$ValueData,
					$row->id

				);
			}
			$results = array(
				"draw" => (int)$_POST['draw'],
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $tableRows,
			);
		} else {
			$results = array(
				"draw" => (int)$_POST['draw'],
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $memData,
			);
		}
		echo json_encode($results);
	}

	public function getDropDown()
	{
		$branch_id = $this->session->user_session->branch_id;
		$query = $this->db->query("SELECT group_concat(id) as id,reoprt_name,group_concat(sub_report_name) as sub_report_name 
FROM query_reports_table where find_in_set(".$branch_id.",branch_level_permission) group by reoprt_name");
		$data = '';

		if ($this->db->affected_rows() > 0) {
			$data .= '<div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Reports
            </button>
			 <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">';
			$result = $query->result();

			foreach ($result as $row) {
				$row->reoprt_name;
				$sub_report_name = $row->sub_report_name;
				$exp = explode(",", $sub_report_name);
				$exp2 = explode(",", $row->id);
				$liNew = "";
				for ($k = 0; $k < count($exp); $k++) {
					//$liNew .=' <li class="dropdown-submenu dropdown-item  submenuli" style="background-color:#807379;color:white;margin:2px;border-bottom: 1px solid grey;"><a  ">'.$exp[$k].'</a></li>';
					$liNew .= '  <li class="dropdown-item" onclick="getDataHTML(' . $exp2[$k] . ')"><a tabindex="-1" href="#">' . $exp[$k] . '</a></li>';
				}

				$data .= '
           
                <li class="dropdown-divider"></li>
                <li class="dropdown-submenu">
                  <a  class="dropdown-item" tabindex="-1" href="#">' . $row->reoprt_name . '</a>
                  <ul class="dropdown-menu">
                    ' . $liNew . '
                  </ul>
                </li>';

			}
			$data .= "</ul></div>";


			$response['status'] = 200;
			$response['data'] = $data;

		} else {
			$response['status'] = 201;
			$response['data'] = $data;
		}
		echo json_encode($response);
	}

	public function DownloadNewpdf()
	{
		$formdata = $this->input->post_get('formdata');
		$object = json_decode($formdata);

		$id = $object->query_id;
		$branch_id = $this->session->user_session->branch_id;
		$patient_table = $this->session->user_session->patient_table;

		$query = $this->db->query("select * from query_reports_table where id=" . $id);
		if ($this->db->affected_rows() > 0) {
			$result = $query->row();
			$query_val = $result->query;
			for ($i = 1; $i <= 5; $i++) {
				$ParamV = "param" . $i;
				$param = $result->$ParamV;

				if (!is_null($param)) {
					//$value=$this->input->post('input_val'.$i);
					$v = 'input_val' . $i;
					$value = $object->$v;
					$char = "#" . $param;
					$query_val = str_replace($char, $value, $query_val);

				}
			}
			$branchiDV = "#branch_id";
			$patient_tableV = '"#patient_table"';
			$query_val = str_replace($branchiDV, $branch_id, $query_val);
			$query_val = str_replace($patient_tableV, $patient_table, $query_val);
			$array_head = array();
			$table_td = "";
			$table_th = "";
			$table = "";
			$query_new = $this->db->query($query_val);
			if ($this->db->affected_rows() > 0) {
				$q_result = $query_new->result();

				$m = 0;
				foreach ($q_result as $row) {
					$table_td .= "<tr>";

					$array_data[] = array_values((array)$row);


					foreach ($row as $key => $r) {
						if ($m == 0) {
							$array_head[] = $key;
						}

						$table_td .= "<td style='border:1px solid #ccc;text-align:center'>" . $r . "</td>";
					}
					$table_td .= "</tr>";
					$m++;

				}
				$table_th .= "<tr>";
				/* var_dump($array_data);
			   exit;  */
				for ($i = 0; $i < count($array_head); $i++) {
					$table_th .= "<th style='border:1px solid #ccc;'>
				" . $array_head[$i] . "
				</th>";
				}

				$table_th .= "</tr>";


				$table .= '
			<table class="table table-bordered" style="width:100%"  id="table_data">
			<thead>
			' . $table_th . '
			</thead>
			<tbody >
			' . $table_td . '
			</tbody>
			</table>
			
			';


				// include autoloader
				require_once FCPATH . 'vendor\autoload.php';

// reference the Dompdf namespace


// instantiate and use the dompdf class
				$dompdf = new Dompdf();

				$dompdf->loadHtml($table);

// (Optional) Setup the paper size and orientation
				$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
				$dompdf->render();
				return $dompdf->stream('ReportPDF.pdf', array('Attachment' => 0));
				/* $output = $dompdf->output();
				file_put_contents("file.pdf", $output); */
			}

		}
	}

	function getAllTablenames()
	{
		$tables = $this->db->list_tables();
		$option = "<option value=''>Select Table</option>";
		foreach ($tables as $table) {
			$option .= "<option value='" . $table . "'>" . $table . "</option>";
		}
		$response['option'] = $option;
		echo json_encode($response);

	}

	function GetAllColumns()
	{
		$TableName = $this->input->post('TableName');
		$count = $this->input->post('count');
		$type = $this->input->post('type');
		$dataArray = $this->input->post('dataArray');
		$insertIDGenerate = $this->input->post('insertIDGenerate');
		$fields = $this->db->field_data($TableName);
		$data = "<br>";
		$option = "";
		$optionFields = "";


		$exp = explode(",", $dataArray);

		foreach ($exp as $r) {
			$optionFields .= "<option value='" . $r . "'>" . $r . "</option>";
		}
		if ($insertIDGenerate == 1) {
			//$optionFields .="<option value='#insertID".$count."'>#insertID".$count."</option>";
		}
		foreach ($fields as $field) {
			$column_name = $field->name;
			$option .= "<option value='" . $column_name . "'>" . $column_name . "</option>";
		}
		$cnt = 1;
		$data1 = "";
		foreach ($fields as $field) {

			$data .= "<div class='row d-flex'>
		   <div class='col-md-1'><br>
		   <input class='q_check" . $count . "'  type='checkbox' id='checkColumn" . $count . "_" . $cnt . "' value='" . $cnt . "' onclick='create_array(" . $count . "," . $cnt . ")' name='checkColumn" . $count . "[]'>
		   </div>
		   <div class='col-md-4'>
		   <select class='form-control' id='columnName_" . $count . "_" . $cnt . "' onchange='create_array(" . $count . "," . $cnt . ")' name='columnName_" . $count . "_" . $cnt . "'>
		   " . $option . "
		   </select>
		   </div>
		   <div class='col-md-4'>
		   <select class='form-control hashoptionclass' id='fieldName_" . $count . "_" . $cnt . "' onchange='create_array(" . $count . "," . $cnt . ")' name='fieldName_" . $count . "_" . $cnt . "'>
		  " . $optionFields . "
		   </select>
		   </div>
		   
		   </div>
		  
		   </div>
		   <br>";
			$cnt++;
		}
		$cnt1 = 1;
		$data1 .= "<h6>Where Condition</h6>
		<button class='btn btn-link' type='button'
		onclick='appendWhereHtml(" . $count . ",\"" . base64_encode($option) . "\",\"" . base64_encode($optionFields) . "\")'> Add More..</button>";
		if ($type == 2 || $type == 3) {
			$data1 .= " <input type='hidden' id='Uwherecount_" . $count . "' name='Uwherecount_" . $count . "' value='" . $cnt1 . "'>
				<div class='row'>
		   <div class='col-md-4'>
		   <select class='form-control' id='WherecolumnName_" . $count . "_" . $cnt1 . "' name='WherecolumnName_" . $count . "_" . $cnt1 . "'>
		   " . $option . "
		   </select>
		   </div>
		   <div class='col-md-4'>
		   <select class='form-control' id='WherefieldName_" . $count . "_" . $cnt1 . "' name='WherefieldName_" . $count . "_" . $cnt1 . "'>
		  " . $optionFields . "
		   </select>
		   </div>
		    <div class='col-md-4'>
		  <input type='text' class='form-control' id='WheretextfieldName_" . $count . "_" . $cnt1 . "'  name='WheretextfieldName_" . $count . "_" . $cnt1 . "'>
		   </div>
		   </div>
		   <div id='divwhereAppend_" . $count . "_" . $cnt1 . "'>
		   ";
		}

		if ($type == 3) {
			$data = $data1;
		} else {
			$data = $data . $data1;
		}

		$response['data'] = $data;
		echo json_encode($response);
	}

	function forinsertdataviaquery()
	{


		$user_id = 1;
		$id_123 = 12;
		$str = "user_id:#user_id|id:#id_123";
		$exp = explode('|', $str);
		$array = array();
		for ($i = 0; $i < count($exp); $i++) {
			$str2 = $exp[$i];
			$exp2 = explode(':', $str2);
			$index = $exp2[0];
			$value = str_replace("#", "", $exp2[1]);

			$value = ${$value};
			$array[$index] = $value;

		}
		var_dump(($array));
	}


	function getAllPatientBill()
	{
		$branch_id = $this->session->user_session->branch_id;
		$tableName = $this->session->user_session->patient_table;

		$query = $this->db->query("select id,admission_date,discharge_date from " . $tableName . " 

		where branch_id=" . $branch_id . " and (admission_date is not null and admission_date != '0000-00-00 00:00:00' and date(admission_date) < '2021-07-07')");

		if ($this->db->affected_rows() > 0) {
			$res = array();
			$result = $query->result();

			foreach ($result as $row) {
				$patientId = $row->id;
				$admission_date = $row->admission_date;
				$discharge_date = $row->discharge_date;

				if (is_null($discharge_date) || $discharge_date == '0000-00-00 00:00:00') {

					$discharge_date = '2021-07-07';
				} else {
					if(strtotime('Y-m-d',strtotime($discharge_date)) < strtotime('2021-07-07')){
						$discharge_date = $row->discharge_date;
					}else{
						$discharge_date = '2021-07-07';
					}

				}

//				$object = new stdClass();
//				$object->pid=$patientId;
//				$object->aDate = $admission_date;
//				$object->dDate = $discharge_date;


				$date_array = $this->getDatesRange1($admission_date, $discharge_date, $format = 'Y-m-d');
				//var_dump( $date_array);
				$res1 = $this->add_dctr_consult($date_array, $patientId);
				$res[] = $res1;
			}

//			$where = "patient_id=" . $patient_id . " AND type=3 AND service_id='CON006'";
//			$this->db->delete($billing_transaction, $where);

			var_dump($res);
		}
	}

	public function generate_order($p_id)
	{
		$order_id = 'O_' . $p_id . '_' . rand(100, 10000000);
		$result = $this->db->query("select order_id from oder_room_table where order_id='$order_id'")->row();
		if (is_object($result)) {
			return $this->generate_order($p_id);
		} else {
			return $order_id;
		}

	}

	function getDatesRange1($date1, $date2, $format = 'Y-m-d')
	{
		$dates = array();
		$datelast = $date2;
		$current = strtotime(date('Y-m-d', strtotime($date1)));
		$date2 = strtotime(date('Y-m-d', strtotime($date2)));
		//  $date2 = strtotime($date2);

		while ($current <= $date2) {
			$dates[] = date($format, $current);
			$current = strtotime('+24hours', $current);
		}
		return $dates;
	}

	function add_dctr_consult($dateArray, $patient_id)
	{
		$oid = $this->generate_order($patient_id);
		$branch_id = $this->session->user_session->branch_id;
		$billing_transaction = $this->session->user_session->billing_transaction;

		$where = "patient_id=" . $patient_id . " AND type=3 AND service_id='CON005'";
		$where2 = "patient_id=" . $patient_id . " AND date(trans_date)<='2021-07-07' AND branch_id=".$branch_id;
		$delete=$this->db->delete("his_com_1_dep_9", $where2);
		//$delete=$this->db->delete($billing_transaction, $where);
		$c = 1;
		$insertBatch = array();
		$insertBatch1 = array();
		foreach ($dateArray as $key => $date) {
			// if($key == 0){
			// $rate=1400;
			// $service_name='IP Doctor Visit - Regular';
			// $service_id='CON005';
			// }else{
			// $rate=1400;
			// $service_name='IP Doctor Visit - Subsequent/Referral';
			// $service_id='CON006';
			// }
			$rate = 1400;
			$service_name = 'Normal Visit';
			$service_id = 'CON005';
			$array2 = array(
				"service_id" => $service_id,
				"patient_id" => $patient_id,
				"rate" => $rate,
				"total" => $rate,
				"final_amount" => $rate,

				"order_id" => $oid,
				"service_desc" => $service_name,
				"date_service" => $date,
				"unit" => 1,
				"type" => 3,
				"branch_id" => $branch_id,
				"confirm" => 1

			);
			$array_dr=array(
			"patient_id"=>$patient_id,
			"branch_id"=>$branch_id,
			"trans_date"=>$date,
			"sec_26_f_215"=>1274,
			"sec_26_f_216"=>"Consulting Charges BackDated",
			"sec_26_f_143"=>1448,
			"sec_26_f_200"=>"DR VIVEK KUMAR",
			);

			array_push($insertBatch, $array2);
			array_push($insertBatch1, $array_dr);

		}

		$object = new stdClass();
		$object->patient_id = $patient_id;
		$object->count = count($insertBatch);
		$object->insertBatch = $insertBatch;

//		return $object;
		if (count($insertBatch) > 0) {
			//if($delete == true){
				//$insert = $this->db->insert_batch($billing_transaction, $insertBatch);
				$insert2 = $this->db->insert_batch('his_com_1_dep_9', $insertBatch1);
			if ($insert2 == true) {
				return "Added" . $patient_id;
			} else {
				return "Not Added" . $patient_id;
			}
			/* }else{
				return "Not Added" . $patient_id;
			} */

		} else {
			return "Not Added" . $patient_id;
		}
	}


}

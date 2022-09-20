<?php
defined('BASEPATH') or exit('No direct script access allowed');


require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * Class WordReportController
 * @property MasterModel MasterModel
 */
class WordReportController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MasterModel');
		$this->load->model('Global_model');
	}

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
	public function index($type, $id)
	{
		$this->load->view('WordReport/bmr_report', array('title' => 'Report', 'id' => $id, 'type' => $type));
	}

	public function report_list()
	{
		$this->load->view('WordReport/view_report_list', array('title' => 'Report List'));
	}

	public function bmr_report_view($type, $id)
	{
		$this->load->view('WordReport/view_bmr_report', array('title' => 'Report List', 'report_id' => $id, 'type' => $type));
	}

	public function all_bmr_report_view($type, $id)
	{
		$this->load->view('WordReport/view_all_bmr_report', array('title' => 'Report List', 'report_id' => $id, 'type' => $type));
	}

	public function saveHtmlTemplate()
	{
		$html_obj = $this->input->post('html_obj');
		$page_name = $this->input->post('page_name');
		$page_id = $this->input->post('page_id');
		$bmr_no = $this->input->post('bmr_no');
		$update_id = $this->input->post('update_id');
		$type = $this->input->post('type');
		$template_id = 1;

		if ($type == 1) {
			$tableName = 'word_reportMaker_table';
		} else {
			$tableName = 'child_group_master';
		}
		$id = '';
		if ($html_obj != null && $html_obj != '') {

			if ($update_id != null && $update_id != '') {
				$id = $update_id;
				$insert = $this->MasterModel->_update($tableName, array('code' => $html_obj), array('id' => $update_id));
			} else {
				$insert = $this->MasterModel->_insert($tableName, array('code' => $html_obj));
				$id = $insert->inserted_id;
			}


			if ($insert->status) {
				if ($type == 1) {
					$getTableName = $this->MasterModel->_select('word_reportMaker_table', array('id' => $update_id), 'table_name', true);
					if ($getTableName->totalCount > 0) {
						$table_name = $getTableName->data->table_name;
						$getPageData = $this->MasterModel->_select($table_name, array('bmr_id' => $update_id, 'page_id' => $page_id), '*', true);

						if ($getPageData->totalCount > 0) {
							$updateData = $this->MasterModel->_update($table_name, array('page_html' => $html_obj), array('bmr_id' => $update_id, 'page_id' => $page_id));
						} else {
							$insertData = $this->MasterModel->_insert($table_name, array('bmr_id' => $update_id, 'page_id' => $page_id, 'page_html' => $html_obj));
						}
					}
				}
				$response['status'] = 200;
				$response['body'] = "Save Successfully";
				$response['data'] = $html_obj;
				$response['insert_id'] = $id;
			} else {
				$response['status'] = 201;
				$response['body'] = "Something Went Wrong";
			}

		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function userProfiles()
	{

		$data = $this->MasterModel->_rawQuery(
			"select distinct (case when roles =1 then concat(roles,'-','Admin') when roles=2 then concat(roles,'-','Branch Admin') 
				when roles=3 then concat(roles,'-','Supplier') when roles=4 
				then concat(roles,'-','Inventory Admin') when roles=5 then concat(roles,'-','QC Tester')
				when roles=6 then concat(roles,'-','QA Tester') end) as roles from users_master");

		$users = $this->MasterModel->_rawQuery("select concat(id,'-',name) as users from users_master");


		$profiles = array();
		$user_array = array();
		if ($data->totalCount > 0) {
			foreach ($data->data as $p) {
				$profiles[] = $p->roles;
			}

			if ($users->totalCount > 0) {

				foreach ($users->data as $u) {
					$user_array[] = $u->users;
				}

				$response['users'] = $user_array;
			}

			$response['status'] = 200;
			$response['body'] = "Data Found";
			$response['data'] = $profiles;
		} else {
			$response['status'] = 201;
			$response['body'] = "No Data Found";
		}
		echo json_encode($response);
	}

	public function getWordReportMakerData()
	{
		if (!is_null($this->input->post('id')) && $this->input->post('id') != "") {
			$id = $this->input->post('id');
			$type = $this->input->post('type');
			if ($type == 1) {
				$resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), '*', false);
			} else {
				$resultObject = $this->MasterModel->_select('child_group_master', array('id' => $id), '*', false);
			}

			if ($resultObject->totalCount > 0) {
				$data = '';
				$pageList = array('none');
				foreach ($resultObject->data as $row) {
					$html_code = $row->code;
					$html_data = json_decode($html_code);

					if ($html_data != null) {
						foreach ($html_data[0]->pages as $p) {
							if (property_exists($p, 'page_name')) {
								$data .= '<tr><td>' . $p->page_name . '</td><td><button type="button" class="btn btn-primary btn-sm" onclick="getPageDataToEditor(' . $row->id . ',' . $p->page_id . ')"><i class="fa fa-edit"></i></button></td></tr>';
							}
							$pageList[] = $p->page_id . '-' . $p->page_name;
						}

					} else {
						$data = '';
					}
				}
				$pagesData = '';

				if ($html_data != null) {
					$pagesData = $html_data[0]->pages;
				}

				$response['status'] = 200;
				$response['body'] = $data;
				$response['pages'] = $pagesData;
				$response['pageList'] = $pageList;

			} else {
				$response['status'] = 201;
				$response['body'] = "No data found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getPageDataToEditor()
	{
		if (!is_null($this->input->post('id')) && $this->input->post('id') != "") {
			$id = $this->input->post('id');
			$type = $this->input->post('type');
			$page_id = $this->input->post('page_id');
			$data = array();
			if ($type == 1) {
				$resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), '*');
			} else {
				$resultObject = $this->MasterModel->_select('child_group_master', array('id' => $id), '*');
			}
			if ($resultObject->totalCount > 0) {

				$row = $resultObject->data;
				$html_code = json_decode($row->code);
				foreach ($html_code[0]->pages as $p) {
					if ($p->page_id == $page_id) {
						$data = $p;
					}
				}

				$response['status'] = 200;
				$response['body'] = $data;
				$response['id'] = $id;
				$response['bmr_object'] = $row->code;
			} else {
				$response['status'] = 201;
				$response['body'] = "No data found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getReportData()
	{
		if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
			$id = $this->input->post('report_id');
			$type = $this->input->post('type');
			if ($type == 1) {
				$tablename = 'word_reportMaker_table';
			} else {
				$tablename = 'child_group_master';
			}
			$resultObject = $this->MasterModel->_select($tablename, array('id' => $id), '*');
			if ($resultObject->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = $resultObject->data;
			} else {
				$response['status'] = 201;
				$response['body'] = "No data found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function saveReportPageData()
	{
		if (!is_null($this->input->post('page_id')) && $this->input->post('page_id') != "") {
			$page_id = $this->input->post('page_id');
			$type = $this->input->post('type');
			$report_id = $this->input->post('report_id');
			$page_input = $this->input->post('page_input');

			$page_type = 0;
			$product_id = 0;
			$insert_array = array();
			$systemArr = array();
			$pageDataArr = array();
			$systemTable = '';
			$user_id = $this->session->user_session->id;
			if ($page_input != "") {
				if ($type == 1) {
					$tablename = 'word_reportMaker_table';
				} else {
					$tablename = 'child_group_master';
				}
//				$page_input=explode(',',$page_input);
				$resultObject = $this->MasterModel->_select($tablename, array('id' => $report_id), '*');
				if ($resultObject->totalCount > 0) {
					$data = $resultObject->data->code;
					$data = json_decode($data);
					$pagedata = $data[0]->pages;

					$onePageInd = array_search($page_id, array_column($pagedata, 'page_id'));
					if ($onePageInd !== "") {
						$onePage = $pagedata[$onePageInd];

						$pageType = $onePage->page_type;
						$pagename = $onePage->page_name;

						$page_input = $onePage->keys;
//						$systemTable = $onePage->tablename;
						$keyPairs = $onePage->keyPairs;

						$isconfig = $onePage->is_config;
						$pageDataSetarray = array();
						$pageDataSet = array();
						if (count($page_input) > 0) {
							foreach ($page_input as $row) {
								if (!is_null($this->input->post($row)) || !empty($_FILES[$row]['name'])) {
									$inputValue = $this->input->post($row);
									if (is_array($inputValue)) {
										$inputValue = implode(',', $inputValue);
									}

									if (!empty($_FILES[$row]['name'])) {
										if (!empty($_FILES[$row]['name'])) {
											$des_path = "upload";
											$file_name = $this->Global_model->upload_file($des_path, $row);
											$answerFile = array();
											if ($file_name['status'] == 200) {
												$answerFile = $file_name['body'];
											}
											if ($answerFile != '' && !empty($answerFile)) {
												$inputValue = implode(',', $answerFile);
											} else {
												$inputValue = '';
											}
										}
									}

									$inputAccess = array();
									$inputAccess[$row] = array('value' => $inputValue, 'users' => $user_id, 'date' => date('Y-m-d'));
									array_push($pageDataSet, $inputAccess);
								}
							}
							foreach ($keyPairs as $kp) {
								$systemArr[$kp[4]] = $this->input->post($kp[0]);
							}
						}
						if (!property_exists($onePage, 'dataset')) {
							$onePage->dataset = array();
						}
						array_push($pageDataSetarray, $pageDataSet);
						if ($isconfig == 'true') {
							foreach ($pageDataSetarray as $pageDataSet) {
								array_push($pageDataSetarray, $pageDataSet);
							}

							$onePage->dataset = $pageDataSetarray;


						} else {
							$onePage->dataset = $pageDataSetarray;

						}
						$pageDataArr = $pageDataSet;
						if ($pageType == 1) {
							$page_type = $pageType;

							$insert_array = array(
								'reference_mfr_no' => $this->input->post('reference_mfr_no'),
								'generic_name' => $this->input->post('generic_name'),
								'composition' => $this->input->post('composition'),
								'description' => $this->input->post('description'),
								'mfg_lic_no' => $this->input->post('mfg_lic_no'),
								'shelf_life' => $this->input->post('shelf_life'),
								'product_code' => $this->input->post('product_code'),
								'branch_id' => $this->session->user_session->branch_id
							);

							if ($type == 1) {
								$productDetails = $this->MasterModel->_select('product_master_table', array('bmr_id' => $report_id), '*');
								if ($productDetails->totalCount > 0) {
									$product_id = $productDetails->data->id;
								}
							}
						}
					}
					if ($type == 1) {
						$getTableName = $this->MasterModel->_select('word_reportMaker_table', array('id' => $report_id), 'table_name', true);

						if ($getTableName->totalCount > 0) {
							$table_name = $getTableName->data->table_name;

							$getPageData = $this->MasterModel->_select($table_name, array('bmr_id' => $report_id, 'page_id' => $page_id), '*', true);

							if ($getPageData->totalCount > 0) {
								$updateData = $this->MasterModel->_update($table_name, array('dataset' => json_encode($pageDataArr)), array('bmr_id' => $report_id, 'page_id' => $page_id));

							}
						}
					}


					$pagedatanew = json_encode($data);


					$update = new stdClass();
					$update->status = false;

					if ($page_type == 1) {
						if ($type == 1) {
							if ($product_id != 0) {
								$update = $this->MasterModel->_update('product_master_table', $insert_array, array('id' => $product_id));
							} else {
								$insert_array['bmr_id'] = $report_id;
								$update = $this->MasterModel->_insert('product_master_table', $insert_array);
							}
						}
					}
					$update = $this->MasterModel->_update($tablename, array('code' => $pagedatanew), array('id' => $report_id));

					if (count($systemArr) > 0) {
						if ($systemTable != '') {
							$this->MasterModel->_insert($systemTable, $systemArr);
						}

					}

					if ($update->status) {
						$response['status'] = 200;
						$response['body'] = "Data Submitted";
					} else {
						$response['status'] = 201;
						$response['body'] = "Something Went Wrong";
					}
				} else {
					$response['status'] = 201;
					$response['body'] = "No changes to added";
				}

			} else {
				$response['status'] = 201;
				$response['body'] = "No changes to added";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}


	public function bmrReport($type, $id)
	{
		$historyTable = '';
		$materialTable = '';
		$processChart = '';
		$table = 'word_reportMaker_table';
		if ($type == 2) {
			$table = 'child_group_master';
		} else {
			$table = 'word_reportMaker_table';

		}
		$resultObject = $this->MasterModel->_select($table, array('id' => $id), '*');
		$bmr_name = $resultObject->data->name;

		$finalhtml = '';

		$finalhtml = $this->allBMRReports($id, $type);

		header("Content-Type: application/vnd.ms-word");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//no-cache
		header("content-disposition: attachment;filename=" . $bmr_name . ".doc");

		$fhtml = $this->allBMRReports($id, $type);
//		header("Content-Type: application/vnd.ms-word");
//		header("Expires: 0");
//		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//no-cache
//		header("content-disposition: attachment;filename=" . $bmr_name . ".doc");
//
		$finalhtml .= "<html>";
		$finalhtml .= "<style>
						table{margin:0px;width:100%;max-width: 750px;}
						@page { margin: 10px; }
						body { margin:  10px; }
						</style>";
		$finalhtml .= "<meta charset=UTF-8>";
		$finalhtml .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";


		$finalhtml .= "<body>";
		$finalhtml .= "<div class='test'>";
		$finalhtml .= $fhtml;
		$finalhtml .= "</div>";
		$finalhtml .= "</body>";
		$finalhtml .= "</html>";

		$options = new Options();
		$options->set('isRemoteEnabled', TRUE);
		$options->set('isHtml5ParserEnabled', TRUE);
		$options->set('enable_php', true);
		$options->set('enable_remote', true);
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($finalhtml);
		$dompdf->setPaper('A4', 'potrait');
		$dompdf->render();
		$dompdf->stream($bmr_name);
	}


	public function getBMRMasterReportList()
	{
		$resultObject = $this->MasterModel->_select('word_reportMaker_table', array('status' => 1), '*', false);
		if ($resultObject->totalCount > 0) {
			$option = '<option value="">Select BMR Report</option>';
			foreach ($resultObject->data as $row) {
				$option .= '<option value="' . $row->id . '">' . $row->name . '</option>';
			}
			$response['status'] = 200;
			$response['body'] = $option;
		} else {
			$response['status'] = 201;
			$response['body'] = "";
		}
		echo json_encode($response);
	}

	public function setBMRReportToScheduler()
	{
		if (!is_null($this->input->post('id')) && $this->input->post('id') != "") {
			$id = $this->input->post('id');
			$schedule_id = $this->input->post('schedule_id');
			$user_id = $this->session->user_session->id;
			$resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), '*');
			if ($resultObject->totalCount > 0) {
				$data = $resultObject->data;
				$code = json_decode($data->code);
				$pagedata = $this->changeProductPageData($code, $id);
				$pagedata = json_encode($pagedata);
//				print_r($pagedata);exit();
//				$onePageInd = array_search('1', array_column($pagedata, 'page_type'));
//				print_r($pagedata);exit();
				$insertData = array('name' => $data->name,
					'code' => $pagedata,
					'report_id' => $id,
					'scheduler_id' => $schedule_id,
					'status' => 1,
					'created_by' => $user_id,
					'created_on' => date('Y-m-d H:i:s'));
				$updateData = array('name' => $data->name,
					'code' => $pagedata,
					'report_id' => $id,
					'updated_by' => $user_id,
					'updated_at' => date('Y-m-d H:i:s'));
				$reportObject = $this->MasterModel->_select('production_scheduler_bmr_report', array('scheduler_id' => $schedule_id), '*');
				if ($reportObject->totalCount > 0) {
					$insert = $this->MasterModel->_update('production_scheduler_bmr_report', $updateData, array('id' => $reportObject->data->id));
				} else {
					$insert = $this->MasterModel->_insert('production_scheduler_bmr_report', $insertData);
				}
				if ($insert->status == true) {
					$response['status'] = 200;
					$response['body'] = "Data Inserted Successfully";
				} else {
					$response['status'] = 201;
					$response['body'] = "Data Not Inserted";
				}
			} else {
				$response['status'] = 201;
				$response['body'] = "No Such Template Exists";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	function changeProductPageData($code, $id)
	{
		$pages = $code[0]->pages;
		$type = 1;
		if (count($pages) > 0) {
			foreach ($pages as $row) {
				if ($row->page_type == 1) {
					$datasetarr = $row->dataset;
					$htmlcode = $row->html_code;
					$staticFields = $row->staticFields;
					$keysArr = $row->keys;
					$jsonarray = array();
					if (count($datasetarr) > 0) {
						foreach ($datasetarr as $datajson) {
							foreach ($datajson as $key => $val) {
								$jsonarray[$key] = $val->value;
							}
						}
					}
					$keyPairs = $row->keyPairs;

					foreach ($keyPairs as $key => $keyRow) {

						if ($keyRow[1] == 'table') {
							if (strpos($keyRow[0], 'histable') !== false) {
								$historyTable = $this->historyDesign($id, $type);
								$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $keyRow[0] . '</span>', $historyTable, $htmlcode);
							} else if (strpos($keyRow[0], 'materialTable') !== false) {
								$materialTable = $this->materialDesign($id, $type);
								$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $keyRow[0] . '</span>', $materialTable, $htmlcode);
							}
						} else {
							if (array_key_exists($keyRow[0], $jsonarray)) {
								if (count($staticFields) > 0) {
									if (in_array($keyRow[0], $staticFields)) {
										$inputValue = $jsonarray[$keyRow[0]];
										$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $keyRow[0] . '</span>', $inputValue, $htmlcode);
										unset($keyPairs[$key]);
										$row->keyPairs = array_values($keyPairs);
//										if (($keyind = array_search($keyRow[0], $keysArr)) !== false) {
//
//											unset($keysArr[$keyind]);
//										}
//										$row->keys= array_values($keysArr);
									}
								}
							}
						}
					}
//					$row->dataset=array();
//					$row->keyPairs=array();
//					$row->keys=array();
					$row->staticFields = array();
					$row->html_code = $htmlcode;
				}
			}
		}
		return $code;
	}

	public function checkBMRReportAttach()
	{
		if (!is_null($this->input->post('schedule_id')) && $this->input->post('schedule_id') != "") {
			$id = $this->input->post('schedule_id');
			$resultObject = $this->MasterModel->_select('production_scheduler_bmr_report', array('scheduler_id' => $id), '*');
			if ($resultObject->totalCount > 0) {
				$response['status'] = 200;
				$response['report_id'] = $resultObject->data->report_id;
				$response['id'] = $resultObject->data->id;
			} else {
				$response['status'] = 201;
				$response['body'] = "Required Parameter Missing";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function addNewBMR()
	{
		$id = $this->input->post('bmr_update_id');
		$name = $this->input->post('bmr_name');
		$query_param = $this->input->post('queryparameter_list');
		$query_param_type = $this->input->post('parameter_type');
		$query_param_value = $this->input->post('param_value');
		$bmr_list = $this->input->post('bmr_list');
		$group_type = $this->input->post('group_type');
		$insert_array = array();
		$param_data = array();
		$bmr_id = '';

		if($group_type == 2){
			if($bmr_list == null && $bmr_list == ''){
				$response['status'] = 201;
				$response['body'] = "Select Group";
				echo json_encode($response);
				exit();
			}
		}

		if ($name != null && $name != '') {
			if ($id != null && $id != '') {

				if($group_type == 1){
					$insert = $this->MasterModel->_update('word_reportMaker_table',
						array('name' => $name, 'created_by' => $this->session->user_session->id), array('id' => $id));
					$bmr_id = $id;
				}else{
					$insert = $this->MasterModel->_update('child_group_master',
						array('name' => $name, 'created_by' => $this->session->user_session->id), array('id' => $id));
					$bmr_id = $id;
				}
			} else {
				$bmrdata = array(
					'name' => $name,
					'created_by' => $this->session->user_session->id
				);
				if ($bmr_list != -1) {
					$bmrcode = $this->MasterModel->_select('word_reportMaker_table', array('id' => $bmr_list), 'code');
					if ($bmrcode->totalCount > 0) {
						$bmrdata['code'] = $bmrcode->data->code;
					}
				}

				if($group_type == 1){
					$insert = $this->MasterModel->_insert('word_reportMaker_table', $bmrdata);
					$bmr_id = $insert->inserted_id;
					$result = $this->createTableDynamic($name, $query_param, $bmr_id);
					$updateName = $this->MasterModel->_update('word_reportMaker_table', array('table_name' => $result), array('id' => $bmr_id));
				}else{
					$bmrdata['g_id'] = $bmr_list;
					$insert = $this->MasterModel->_insert('child_group_master', $bmrdata);
					$bmr_id = $insert->inserted_id;
					$result = $this->createTableDynamic($name, $query_param, $bmr_id);
					$updateName = $this->MasterModel->_update('word_reportMaker_table', array('table_name' => $result), array('id' => $bmr_id));
				}
			}
			foreach ($query_param as $i => $val) {
				$data = array(
					'group_id' => $bmr_id,
					'param_name' => $val,
					'param_type' => $query_param_type[$i],
					'param_value' => $query_param_value[$i]
				);
				array_push($insert_array, $data);
			}

			if (count($insert_array)) {
				if($group_type == 1){
					$this->MasterModel->_delete('group_parameter_mapping', array('group_id' => $bmr_id));
					$insert = $this->MasterModel->_insertBatch('group_parameter_mapping', $insert_array);
				}else{
					$this->MasterModel->_delete('child_group_parameter_mapping', array('group_id' => $bmr_id));
					$insert = $this->MasterModel->_insertBatch('child_group_parameter_mapping', $insert_array);
				}

			}

			if ($insert->status) {
				$response['status'] = 200;
				$response['body'] = "Saved Successfully";
			} else {
				$response['status'] = 201;
				$response['body'] = "Something Went Wrong";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);

	}

	public function getBMRList()
	{
		$option = "<option value='-1'>Select BMR</option>";
		$getOption = $this->MasterModel->_select('word_reportMaker_table', array('status' => 1), '*', false);

		if ($getOption->totalCount > 0) {

			foreach ($getOption->data as $row) {
				$option .= '<option value="' . $row->id . '">' . $row->name . '</option>';
			}

			$response['status'] = 200;
			$response['body'] = "Data Found";
			$response['data'] = $option;

		} else {
			$response['status'] = 201;
			$response['body'] = "No Data Found";
			$response['data'] = "<option>No Data Found</option>";
		}
		echo json_encode($response);
	}

	public function getBMRNameList()
	{
		$id = $this->input->post('id');
		$resultObject = $this->MasterModel->_select('word_reportMaker_table', array('id' => $id), 'name');
		if ($resultObject->totalCount > 0) {

			$response['status'] = 200;
			$response['body'] = "Data Found";
			$response['data'] = $resultObject->data->name;

		} else {
			$response['status'] = 201;
			$response['body'] = "No Data Found";
			$response['data'] = "";
		}
		echo json_encode($response);
	}

	function historyDesign($id, $type)
	{

		$html = '';

		$resultObject = $this->MasterModel->_rawQuery('select * from product_reason where product_id=(select id from product_master_table where bmr_id=' . $id . ' order by id limit 1)');
		if ($resultObject->totalCount > 0) {

			$html .= '<p>

		<div align="centerc">
			<table border="1" cellspacing="0" cellpadding="0" width="703
			" style="width:527.2pt;border-collapse:collapse;border:none">
		<tbody>
		<tr style="height:9.0pt">
			<td width="114" style="width:85.15pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
			<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Sl. No.</span></p>
		</td>
		<td width="144" style="width:1.5in;border:solid windowtext 1.0pt;border-left:
			none;
			padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
		<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Revision No./Date</span></p>
		</td>
		<td width="132" style="width:99.0pt;border:solid windowtext 1.0pt;border-left:
			none;
			padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
		<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Supersedes BMR No./Date</span></p>
		</td>
		<td width="313" style="width:235.05pt;border:solid windowtext 1.0pt;border-left:
			none;
			padding:0in 5.4pt 0in 5.4pt;height:9.0pt">
		<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
			line">Reasons for change</span></p>
		</td>
		</tr>';
			$i = 0;
			foreach ($resultObject->data as $row) {
				$i++;
				$date = date('Y-m-d', strtotime($row->created_on));
				$html .= '<tr style="height:28.7pt">
			<td width="114" style="width:85.15pt;border:solid windowtext 1.0pt;border-top:
						none;
						padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
					<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
						line">' . $i . '</span></p>
				</td>
				<td width="144" style="width:1.5in;border-top:none;border-left:none;border-bottom:
				solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
				<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
					line">' . $date . '</span></p>
				</td>
				<td width="132" style="width:99.0pt;border-top:none;border-left:none;
				border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
				<p align="center" style="text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
					line">RIPL/CETB/BMR/' . $date . '</span></p>
				</td>
				<td width="313" style="width:235.05pt;border-top:none;border-left:none;
				border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:28.7pt">
			<p align="center" style="margin-left:.5in;text-align:center"><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
				line">' . $row->reason . '</span></p>
			</td>

			</tr>';
			}
			$html .= '</tbody>
			</table>
			</div>
			<br />
			</p>';

		}

		return $html;
	}

	public function checkBMRId()
	{
		$bmr_id = $this->input->post('bmr_id');
		$resultObject = $this->MasterModel->_select('product_master_table', array('bmr_id' => $bmr_id), 'id');
		if ($resultObject->totalCount > 0) {

			$response['status'] = 200;
			$response['body'] = "Data Found";
			$response['data'] = $resultObject->data->id;
		} else {
			$response['status'] = 201;
			$response['body'] = "No Data Found";

		}
		echo json_encode($response);
	}


	public function getHistoryTable()
	{
		if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
			$id = $this->input->post('report_id');
			$type = $this->input->post('type');
			$html = $this->historyDesign($id, $type);
			$response['status'] = 200;
			$response['body'] = $html;
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getMaterialTable()
	{
		if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
			$id = $this->input->post('report_id');
			$type = $this->input->post('type');
			$html = $this->materialDesign($id, $type);
			$response['status'] = 200;
			$response['body'] = $html;
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	function materialDesign($id, $type)
	{
		$html = '';
		if ($type == 1) {
			$where = "(select id from product_master_table where bmr_id='$id' order by id limit 1)";
		} else {
			$where = "(select product_id from production_scheduler where id = (select scheduler_id from production_scheduler_bmr_report where id = '$id'))";
		}

		$resultObject = $this->MasterModel->_rawQuery('select *,
		(select name from material_master where id=mt.material_id) as material_name  
		from material_transaction_table mt where product_id=' . $where . '
		');
		// print_r($resultObject);exit();

		if ($resultObject->totalCount > 0) {

			$html .= '<p>
			<p style="margin-top:0in"><b><u><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">BILL OF MATERIAL</span></u></b><u></u></p>
			<p style="margin-top:0in;margin-right:0in;margin-bottom:0in;
			margin-left:.25in;margin-bottom:.0001pt;line-height:50%"><b><u><span style="font-size:10.0pt;line-height:50%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;"><span style="text-decoration:none">&nbsp;</span></span></u></b></p>
			<div align="centerc">
			<table border="1" cellspacing="0" cellpadding="0" width="737
			" style="border-collapse:collapse;border:none">
			<tbody>
			<tr style="height:41.75pt">
			<td width="41" valign="top" style="width:30.65pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">S. No.</span></b></p>
			</td>
			<td width="78" valign="top" style="width:58.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Material</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Code No.</span></b></p>
			</td>
			<td width="156" valign="top" style="width:116.7pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Name of Material</span></b></p>
			</td>
			<td width="116" valign="top" style="width:86.65pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Grade/</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">Specific Requirement</span></b></p>
			</td>
			<td width="102" valign="top" style="width:76.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Label
			claim (mg/</span></b></p>
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">&nbsp;</span></b></p>
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">tablet)</span></b><b></b></p>
			</td>
			<td width="94" valign="top" style="width:70.85pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Overages</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:10.0pt;font-family:
			&quot;Verdana&quot;,&quot;sans-serif&quot;">(%)</span></b></p>
			</td>
			<td width="150" valign="top" style="width:112.8pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:41.75pt">
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">Qty. for 6,00,000
			Tablets</span></b></p>
			<p class="MsoNormalM"><b><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">75.00Kg)</span></b></p>
			</td>
			</tr>';
			$i = 0;
			foreach ($resultObject->data as $row) {
				$i++;
				$date = date('Y-m-d', strtotime($row->created_on));
				$html .= '<tr style="height:19.25pt">
					<td width="41" valign="top" style="width:30.65pt;border:solid windowtext 1.0pt;
					border-top:none;
					padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p style="line-height:150%"><span style="font-size:10.0pt;
					line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $i . '</span></p>
					</td>
					<td width="78" valign="top" style="width:58.5pt;border-top:none;border-left:none;
					border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p style="line-height:150%"><span style="font-size:10.0pt;
					line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:black">' . $row->material_code . '</span></p>
					</td>
					<td width="156" valign="top" style="width:116.7pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p style="line-height:150%"><span style="font-size:10.0pt;
					line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->material_name . '</span></p>
					</td>
					<td width="116" valign="top" style="width:86.65pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="margin-right:-5.4pt;text-align:center;
					line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:
					&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->grade . '</span></p>
					</td>
					<td width="102" valign="top" style="width:76.5pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="text-align:center;line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->label . '</span></p>
					</td>
					<td width="94" valign="top" style="width:70.85pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="text-align:center;line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->overages . '</span></p>
					</td>
					<td width="150" valign="top" style="width:112.8pt;border-top:none;border-left:
					none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:19.25pt">
					<p align="center" style="text-align:center;line-height:150%"><span style="font-size:10.0pt;line-height:150%;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;">' . $row->qty . '</span></p>
					</td>
				</tr>';
			}
			$html .= '</tbody>
				</table>
				</div>
				<p><span style="font-size:10.0pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;layout-grid-mode:
				line">*30% overage for compensate process loss.</span></p>
				<br />
				</p>';

		}

		return $html;
	}

	function IndentHTML($id, $type)
	{
		$html = '';

		$productionData = $this->MasterModel->_rawQuery('SELECT scheduler_id,(select product_id from production_scheduler where id = p.scheduler_id) as product_id,
					(select date_format(end_date,"%d %M %Y") from production_scheduler where id = p.scheduler_id) as end_date
					FROM production_scheduler_bmr_report p where id = ' . $id);

		if ($productionData->totalCount > 0) {
			$scheduleId = $productionData->data[0]->scheduler_id;
			$product_id = $productionData->data[0]->product_id;

			$end_date = $productionData->data[0]->end_date;

			$resultObject = $this->MasterModel->_select('schedule_material_mapping mt', array('production_schedule_id' => $scheduleId),
				'*,(select mm.name from material_master mm where mm.id=mt.material_id) as material_name,
			(select actual_qty from production_scheduler where id=mt.production_schedule_id) as plant_qty,
			(select group_concat(arn_no) from indent_material_transaction where schedule_id = mt.production_schedule_id and material_id = mt
			.material_id) as ARN_no,
			(select group_concat((select name from users_master where id in (select qc_updated_by 
			from com_1_material_order where find_in_set(ARN_no,concat(i.arn_no)))))
 			from indent_material_transaction i where schedule_id = mt.production_schedule_id and material_id = mt .material_id) as qc_updated, 
			(SELECT (select name from users_master where id = supplied_by) FROM com_1_material_order 
			where production_schedule_id = mt.production_schedule_id and material_id = mt.material_id and order_status != 1 limit 1) as supplier,
			
			(select name from unit_master where id = mt.label) as main_unit', false);

			if ($resultObject->totalCount > 0) {
				$i = 1;


				$html .= '
				<table border="1" cellspacing="0" cellpadding="0" width="737" style="border-collapse:collapse;border:none">
	<tbody>
		<tr style="page-break-inside:avoid;
			height:.05in">
			<td width="63" rowspan="2" valign="top" style="width:47.55pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Item Code</span></b></p>
			</td>
			<td width="121" rowspan="2" valign="top" style="width:91.05pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Material</span></b></p>
			</td>
			<td width="66" rowspan="2" valign="top" style="width:49.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Std. Qty. in Kgs</span></b></p>
			</td>
			<td width="54" rowspan="2" valign="top" style="width:40.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Qty. Issued</span></b></p>
			</td>
			<td width="66" rowspan="2" valign="top" style="width:49.5pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">A.R. No. with date</span></b></p>
			</td>
			<td width="170" colspan="3" valign="top" style="width:127.8pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Weighing (Kgs)</span></b></p>
			</td>
			<td width="76" rowspan="2" valign="top" style="width:56.95pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Exp. Date/ Retest Date</span></b></p>
			</td>
			<td width="60" rowspan="2" valign="top" style="width:44.75pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Dispensed</span></b></p>
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">by</span></b></p>
			</td>
			<td width="70" rowspan="2" valign="top" style="width:52.45pt;border:solid windowtext 1.0pt;
			border-left:none;padding:0in 5.4pt 0in 5.4pt;height:.05in">
			<p align="center" style="text-align:center"><b><span style="font-size:8.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Checked by Q/A</span></b></p>
			</td>
		</tr>
		<tr style="page-break-inside:avoid;height:24.25pt">
			<td width="60" valign="top" style="width:45.25pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:24.25pt">
			<p align="center" style="text-align:center"><b><span style="font-size:9.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Gross Wt.</span></b></p>
			</td>
			<td width="56" valign="top" style="width:42.05pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:24.25pt">
			<p align="center" style="text-align:center"><b><span style="font-size:9.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Tare Wt.</span></b></p>
			</td>
			<td width="54" valign="top" style="width:40.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:24.25pt">
			<p align="center" style="text-align:center"><b><span style="font-size:9.0pt;font-family:
			&quot;Verdana&quot;,sans-serif">Net Wt.</span></b></p>
			</td>
		</tr>';


				foreach ($resultObject->data as $row) {
					$unit_weight = $this->MasterModel->UnitRecon($row->unit, $row->label);

					$reqQrt = ($unit_weight) * ($row->plant_qty);
					$reqQrt = round($reqQrt, 3);

					$html .= '<tr style="page-break-inside:avoid;height:14.75pt">
				
					<td width="63" valign="top" style="width:47.55pt;border:solid windowtext 1.0pt;
			border-top:none;
			padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p align="center" style="text-align:center;line-height:150%"><span style="font-size:9.0pt;line-height:150%;font-family:&quot;Verdana&quot;,sans-serif;
			color:black">' . $row->material_code . '</span></p>
			</td>
			<td width="121" valign="top" style="width:91.05pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:150%"><span style="font-size:9.0pt;
			line-height:150%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->material_name . '</span></p>
			</td>
			<td width="66" valign="top" style="width:49.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="margin-right:-5.25pt;line-height:200%"><span style="font-size:9.0pt;line-height:200%;font-family:&quot;Verdana&quot;,sans-serif;
			color:black">' . $unit_weight . ' ' . $row->main_unit . '</span></p>
			</td>
			<td width="54" valign="top" style="width:40.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif;color:black">' . $reqQrt . '</span></p>
			</td>
			<td width="66" valign="top" style="width:49.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->ARN_no . '</span></p>
			</td>
			<td width="60" valign="top" style="width:45.25pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $reqQrt . '</span></p>
			</td>
			<td width="56" valign="top" style="width:42.05pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $reqQrt . '</span></p>
			</td>
			<td width="54" valign="top" style="width:40.5pt;border-top:none;border-left:none;
			border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $reqQrt . '</span></p>
			</td>
			<td width="76" valign="top" style="width:56.95pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $end_date . '</span></p>
			</td>
			<td width="60" valign="top" style="width:44.75pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->supplier . '</span></p>
			</td>
			<td width="70" valign="top" style="width:52.45pt;border-top:none;border-left:
			none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt;height:14.75pt">
			<p style="line-height:200%"><span style="font-size:9.0pt;
			line-height:200%;font-family:&quot;Verdana&quot;,sans-serif">' . $row->qc_updated . '</span></p>
			</td>
		</tr>
									
					</tr>';
					$i++;
				}
			}
			return $html;

		} else {
			return $html = false;
		}

	}

	public function getAllBMRReport()
	{
		$type = $this->input->post('type');
		$id = $this->input->post('report_id');
		$finalhtml = '';
		if ($id != null && $id != '') {
			$finalhtml = $this->allBMRReports($id, $type);

			$response['status'] = 200;
			$response['body'] = $finalhtml;
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);

	}

	function allBMRReports($id, $type)
	{
		$historyTable = '';
		$materialTable = '';
		$table = 'word_reportMaker_table';
		if ($type == 2) {
			$table = 'child_group_master';
			$result = $this->historyDesign($id, $type);
		} else {
			$table = 'word_reportMaker_table';
		}

		if ($id != null && $id != '') {
			$resultObject = $this->MasterModel->_select($table, array('id' => $id), '*');
			$code = $resultObject->data->code;
			$bmr_name = $resultObject->data->name;
			$jsonDecoded = json_decode($code, true);
			$entries = $jsonDecoded[0]['pages'];
			$finalhtml = '';

			foreach ($entries as $jsondata) {
				$datasetarr = $jsondata['dataset'];
				$htmlcode = $jsondata['html_code'];
				$keypair = $jsondata['keyPairs'];
				$jsonarray = array();
				$historydata = array();


				foreach ($datasetarr as $dataj) {
					foreach ($dataj as $datajson) {
						foreach ($datajson as $key => $val) {
							if ($val != '') {
								$jsonarray[$key] = $val['value'];
							}
						}
					}
				}
				foreach ($keypair as $key => $val) {
					$replacedata = '';
					$historydata[$val[0]] = $val[1];

					if ($val[1] == 'table') {
						if (strpos($val[0], 'histable') !== false) {
							$historyTable = $this->historyDesign($id, $type);
							$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $historyTable, $htmlcode);
						} else if (strpos($val[0], 'materialTable') !== false) {
							$materialTable = $this->materialDesign($id, $type);
							$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $materialTable, $htmlcode);
						} else if (strpos($val[0], 'indentTable') !== false) {
							if ($type == 2) {
								$materialTable = $this->IndentHTML($id, $type);
								$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $materialTable, $htmlcode);
							}
						} else if (strpos($val[0], 'processChart') !== false) {

							if ($type == 2) {
								$materialTable = $this->processFlowChart($id, $type);

								$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $materialTable, $htmlcode);
							}
						}
					} else {

						if (array_key_exists($val[0], $jsonarray)) {
							$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $jsonarray[$val[0]], $htmlcode);
						} else {
							$htmlcode = str_replace('<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' . $val[0] . '</span>', $replacedata, $htmlcode);
						}

					}


				}
				$finalhtml .= $htmlcode;

			}

			return $finalhtml;
		}

	}

	public function getIndentTable()
	{
		if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
			$id = $this->input->post('report_id');
			$type = $this->input->post('type');
			$html = $this->IndentHTML($id, $type);
			$response['status'] = 200;
			$response['body'] = $html;
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getProcessFlowChart()
	{
		if (!is_null($this->input->post('report_id')) && $this->input->post('report_id') != "") {
			$id = $this->input->post('report_id');
			$type = $this->input->post('type');
			$html = $this->processFlowChart($id, $type);
			$response['status'] = 200;
			$response['body'] = $html;
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	function processFlowChart($id, $type)
	{
		$processChart = '';
		$resultObject = $this->MasterModel->_rawQuery('select name from process_master where id in 
(select process_id from schedule_procedure_mapping where 
production_schedule_id=(select scheduler_id from production_scheduler_bmr_report where id="' . $id . '"))');
		if ($resultObject->totalCount > 0) {
			$processChart .= '<div class="tree horizontal text-center" style="margin: 1% 40% 1% 40%;" id="processTree">';
			$i = 1;
			foreach ($resultObject->data as $row) {
				$path = base_url() . 'assets/images/down-arrow.png';
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$imagedata = file_get_contents($path);
				$base64 = "data:image/svg+xml;base64," . base64_encode($imagedata);

				if ($i != 1) {
//				src="'.base_url().'assets/images/down_arrow.svg"
//					src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDQ3Ni40OTIgNDc2LjQ5MiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDc2LjQ5MiA0NzYuNDkyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8cG9seWdvbiBwb2ludHM9IjI1My4yNDYsMzM5Ljk1MiAyNTMuMjQ2LDAgMjIzLjI0NiwwIDIyMy4yNDYsMzM5Ljk1MiAxMDEuNzA3LDMzOS45NTIgMjM4LjI0Niw0NzYuNDkyIDM3NC43ODUsMzM5Ljk1MiAiLz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjwvc3ZnPg0K"
					$processChart .= '<div class="mainLi" style="list-style: none;"><span class="d-block">
                                    <img width="30" style="margin-left: 20px;" height="30" src="' . $path . '" />
									</span></div>';

				}
				$processChart .= '<div class="mainLi" style="list-style: none;">
									<div class="processNode" style="padding: 2px 20px; display: inline-block;border: solid 1px black;border-radius:10px;font-size: 12px;"><div class=""></div>' . $row->name . '</div>

								</div>';
				$i++;
			}
			$processChart .= '</div>';

		}
		return $processChart;

	}

	function getAllTablesNames()
	{
		$tables = $this->db->list_tables();
		$option = "<option value='' selected disabled>Select Table</option>";
		foreach ($tables as $table) {
			$option .= "<option value='" . $table . "'>" . $table . "</option>";
		}
		$response['option'] = $option;
		echo json_encode($response);
	}

	public function getColumnNames()
	{
		$query = $this->input->post('query');
		$value = $this->input->post('inputValue');
		$type = $this->input->post('type');
		$params = $this->input->post('params');
		$bmr_id = $this->input->post('id');
		$page_id = $this->input->post('page_id');
		$page_type = $this->input->post('page_type');
		if ($query != null && $query != '') {
			$paramData = json_decode(base64_decode($params));
			if ($paramData != null && $paramData != 'NULL') {
				foreach ($paramData as $key => $value) {
					$query = str_replace('#' . $key, $value, $query);
				}
			}


			if ($type == 1) {

				if ($page_type == 1) {
					$getTableName = $this->MasterModel->_select('word_reportMaker_table', array('id' => $bmr_id), 'table_name', true);
					if ($getTableName->totalCount > 0) {
						$table_name = $getTableName->data->table_name;
						$getPageData = $this->MasterModel->_select($table_name, array('bmr_id' => $bmr_id, 'page_id' => $page_id), '*', true);

						if ($getPageData->totalCount > 0) {
							$pageData = $getPageData->data;
							if ($pageData->dataset != null && $pageData->dataset != 'null') {

								$dataset = json_decode($pageData->dataset);
								foreach ($dataset as $d) {
									foreach ($d as $k => $val) {
										if (strpos($query, $k)) {
											$Pvalue = $val->value;
											if ($val->value == '' && $val->value == null) {
												$Pvalue = 0;
											}
											$query = str_replace('#' . $k, $Pvalue, $query);
										}
									}
								}
							} else {
								$pages = json_decode($pageData->page_html);
								foreach ($pages[0]->pages as $row) {
									if ($row->page_id == $page_id) {
										$keys = $row->keys;
										foreach ($keys as $k) {
											$query = str_replace('#' . $k, 0, $query);
										}
									}
								}
							}
						}

					}
				} else {
					$getData = $this->MasterModel->_select('child_group_master', array('id' => $bmr_id), '*');
					if ($getData->totalCount > 0) {
						$pageData = $getData->data;
						$pages = json_decode($pageData->code);
						foreach ($pages[0]->pages as $row) {
							if ($row->page_id == $page_id) {
								$dataset = $row->dataset;
								if ($dataset != null && !empty($dataset)) {
									foreach ($dataset as $ds) {
										foreach ($ds as $d) {
											foreach ($d as $k => $val) {
												if (strpos($query, $k)) {
													$Pvalue = $val->value;
													if ($val->value == '' && $val->value == null) {
														$Pvalue = 0;
													}
													$query = str_replace('#' . $k, $Pvalue, $query);
												}
											}
										}
									}
								} else {
									$keys = $row->keys;
									foreach ($keys as $k) {
										$query = str_replace('#' . $k, 0, $query);
									}
								}
							}
						}
					}
				}
			}

			$getHandson = $this->MasterModel->_rawQuery($query);
			if ($getHandson->totalCount > 0) {
				$option = '';
				if ($type == 1) {
					$option = $getHandson->data[0]->value;
				} else {
					$option = "<option value='-1' selected>Select One</option>";
					foreach ($getHandson->data as $row) {
						if ($value == $row->id) {


							$option .= "<option value='" . $row->id . "' selected>" . $row->text . "</option>";
						} else {

							$option .= "<option value='" . $row->id . "'>" . $row->text . "</option>";
						}

					}
				}
				$response['status'] = 200;
				$response['data'] = $option;
				$response['body'] = "Data Found";
			} else {
				if ($type == 1) {
					$option = '';
				} else {
					$option = "<option value='-1' selected>No Data Found</option>";
				}
				$response['data'] = $option;
				$response['status'] = 201;
				$response['body'] = "No Data Found";
			}
		} else {
			if ($type == 1) {
				$option = '';
			} else {
				$option = "<option value='-1' selected>No Data Found</option>";
			}
			$response['data'] = $option;
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getTableColumns()
	{
		$table_name = $this->input->post('id');
		if ($table_name != null && $table_name != '') {
			$Arr = array();
			$getData = $this->MasterModel->_rawQuery('SHOW columns FROM ' . $table_name);
			if ($getData->totalCount > 0) {
				foreach ($getData->data as $row) {
					$Arr[] = $row->Field;
				}

				$response['status'] = 200;
				$response['body'] = 'Columns Found';
				$response['data'] = $Arr;
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function createTemplateTable($table_name, $fields)
	{

		$this->load->dbforge();
		$resultObject = new stdClass();
		// create new table if not exists then create
		if (count($fields) > 0) {
			if ($this->db->table_exists($table_name)) {
				$this->dbforge->add_column($table_name, $fields);
				$resultObject->status = true;
				$resultObject->body = "table exists new column added";
			} else {
				$this->dbforge->add_field($fields);
				$this->dbforge->add_field('id');
				if ($this->dbforge->create_table($table_name, TRUE)) {
					$resultObject->body = "new table new column added";
					$resultObject->status = true;
				} else {
					$resultObject->status = false;
				}
			}
		} else {
			$resultObject->status = true;
		}


		$resultObject->fields = $fields;
		return $resultObject;


	}

	public function createTableDynamic($name, $query_param, $bmr_id)
	{
		$fieldsArray = array();
		$pagename = strtolower($name);
		$table_name = str_replace(' ', '_', $pagename);
		$table_name = $table_name . '_' . $bmr_id;
		// $table_name='bmr_'.$table_name;
		if (!$this->tableExist($table_name)) {
			$patientFieldDetails = array("type" => "INT", 'constraint' => '11');
			$fieldsArray["id"] = $patientFieldDetails;
			$bmrFieldDetails = array("type" => "INT", 'constraint' => '11');
			$fieldsArray["bmr_id"] = $bmrFieldDetails;
			$pageFieldDetails = array("type" => "INT", 'constraint' => '11');
			$fieldsArray["page_id"] = $pageFieldDetails;
			$pageHtmlFieldDetails = array("type" => "json");
			$fieldsArray["page_html"] = $pageHtmlFieldDetails;
			$companyFieldDetails = array("type" => "json");
			$fieldsArray["dataset"] = $companyFieldDetails;
//			if (count($query_param) > 0) {
//				foreach ($query_param as $row) {
//                    $query_product = array("type" => "INT",'constraint' => '11');
//					$fieldsArray[$row] = $query_product;
//					$query_branch = array("type" => "INT",'constraint' => '11');
//					$fieldsArray[$row] = $query_branch;
//				}
//			}
			$result = $this->createTemplateTable($table_name, $fieldsArray);
		}

		return $table_name;
	}

	public function tableExist($table_name)
	{
		return $this->db->table_exists($table_name);
	}

	public function getQueryParameterList()
	{
		$option = "<option value='-1'>Select QueryParameter</option>";
		$getOption = $this->MasterModel->_rawQuery('select * from query_parameter_table');

		if ($getOption->totalCount > 0) {

			foreach ($getOption->data as $row) {
				$option .= '<option value="' . $row->name . '">' . $row->name . '</option>';
			}

			$response['status'] = 200;
			$response['body'] = "Data Found";
			$response['data'] = $option;

		} else {
			$response['status'] = 201;
			$response['body'] = "No Data Found";
			$response['data'] = "<option>No Data Found</option>";
		}
		echo json_encode($response);
	}

	public function getReportPageData()
	{

		if (!is_null($this->input->post('bmr_id')) && $this->input->post('bmr_id') != "") {
			$id = $this->input->post('bmr_id');
			$type = $this->input->post('type');
			if ($type == 1) {
				$tablename = 'word_reportMaker_table';
			} else {
				$tablename = 'child_group_master';
			}
			$resultObject = $this->MasterModel->_select($tablename, array('id' => $id), '*');
			if ($resultObject->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = $resultObject->data;
			} else {
				$response['status'] = 201;
				$response['body'] = "No data found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getPageLabelData()
	{
		$bmr_id = $this->input->post('bmr_id');
		$page_id = $this->input->post('id');
		$type = $this->input->post('type');
		$group_id = $this->input->post('group_id');
		$queryType = $this->input->post('queryType');

		if ($bmr_id != null && $bmr_id != '') {
			$getData = array();
			if ($queryType == 1) {
				$getData = $this->getPageData($bmr_id, $page_id, $type);
			} else {
				$getData = $this->getLabelData($bmr_id, $page_id, $type, $group_id);
			}

			if (count($getData) > 0) {
				$response['status'] = 200;
				$response['body'] = "Data Found";
				$response['data'] = $getData;
			} else {
				$response['status'] = 201;
				$response['body'] = "No Data Found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getBMRParamData()
	{
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		if ($id != null && $id != '') {

			$table = 'group_parameter_mapping';
			if($type == 2){
				$table = 'child_group_parameter_mapping';
			}
			$getData = $this->MasterModel->_select($table, array('group_id' => $id), '*', false);
			if ($getData->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = "Data Found";
				$response['data'] = $getData->data;
			} else {
				$response['status'] = 201;
				$response['body'] = "No Data Found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getQueryParamData()
	{
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		if ($id != null && $id != '') {

			$params = array();
			$table = 'group_parameter_mapping';
			if ($type == 2) {
				$table = 'child_group_parameter_mapping';
			}
			$getParams = $this->MasterModel->_select($table, array('group_id' => $id), '*', false);
			if ($getParams->totalCount > 0) {

				$data = $getParams->data;
				foreach ($data as $row) {
					if ($row->param_type == 1) {
						$params[$row->param_name] = $row->param_value;
					} else if ($row->param_type == 2) {
						$session_name = $row->param_value;
						$params[$row->param_name] = $this->session->user_session->$session_name;
					} else {
						$query = $row->param_value;
						if (strpos($query, '#id')) {
							$query = str_replace('#id', $id, $query);
						}
						$data = $this->MasterModel->_rawQuery($query);
						if ($data->totalCount > 0) {
							$params[$row->param_name] = $data->data[0]->value;
						} else {
							$params[$row->param_name] = '';
						}

					}
				}
				$response['status'] = 200;
				$response['body'] = "Data Found";
				$response['params'] = $params;
			} else {
				$response['status'] = 201;
				$response['body'] = "No Data Found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = 'Required Parameter Missing';
		}
		echo json_encode($response);
	}

	public function getAwsLinkToDownload()
	{
		if (!is_null($this->input->post('file')) && $this->input->post('file') != "") {
			$files = $this->input->post('file');
			$fileArr = explode(',', $files);
			$fileArray = array();
			foreach ($fileArr as $r) {
				$splitName = explode('/', $r);
				$ext = explode('.', $splitName[count($splitName) - 1]);
				$file_name_r = $splitName[count($splitName) - 1];
				if (is_numeric(substr($splitName[count($splitName) - 1], 0, 10))) {
					$file_name_r = substr($file_name_r, 11);
				}
				$urlFile = new stdClass();
				$urlFile->urlPath = $this->AwsModel->getPreAssignURL($r);
				$urlFile->filename = $file_name_r;
				array_push($fileArray, $urlFile);
			}
			if (count($fileArray) > 0) {
				$response['status'] = 200;
				$response['body'] = $fileArray;
			} else {
				$response['status'] = 201;
				$response['body'] = $fileArray;
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getLabelData($bmr_id, $page_id, $type, $group_id)
	{
		$pagelabellist = array();

		if ($type == 1) {
			$getTableName = $this->MasterModel->_select('word_reportMaker_table', array('id' => $group_id), 'table_name', true);
			if ($getTableName->totalCount > 0) {
				$table_name = $getTableName->data->table_name;
				$getPageData = $this->MasterModel->_select($table_name, array('bmr_id' => $group_id, 'page_id' => $page_id), '*', true);

				if ($getPageData->totalCount > 0) {
					$row = $getPageData->data;

					$html_code = $row->page_html;
					$html_data = json_decode($html_code);
					if ($html_data != null) {

						foreach ($html_data[0]->pages as $p) {
							if ($p->page_id == $page_id) {
								$keyPairs = $p->keyPairs;
								foreach ($keyPairs as $key => $val) {
									$pagelabellist[] = $val[6];
								}
							}

						}
					}
				}
			}
		} else {
			$getPageData = $this->MasterModel->_select('child_group_master', array('id' => $group_id), '*', true);
			if ($getPageData->totalCount > 0) {
				$row = $getPageData->data;

				$html_code = $row->code;
				$html_data = json_decode($html_code);
				if ($html_data != null) {
					foreach ($html_data[0]->pages as $p) {
						if ($p->page_id == $page_id) {
							$keyPairs = $p->keyPairs;
							foreach ($keyPairs as $key => $val) {
								$pagelabellist[] = $val[6];
							}
						}
					}
				}
			}
		}
		return $pagelabellist;
	}

	public function getPageData($bmr_id, $page_id, $type)
	{
		$pagelabellist = array();

		if ($type == 1) {
			$getPageData = $this->MasterModel->_select('word_reportMaker_table', array('id' => $page_id), '*', true);
			if ($getPageData->totalCount > 0) {
				$row = $getPageData->data;
				$html_code = $row->code;
				$html_data = json_decode($html_code);
				if ($html_data != null) {
					foreach ($html_data[0]->pages as $p) {
						$pagelabellist[] = $p->page_id . '-' . $p->page_name;
					}
				}
			}
		} else {
			$getPageData = $this->MasterModel->_select('child_group_master', array('id' => $page_id), '*', true);
			if ($getPageData->totalCount > 0) {
				$row = $getPageData->data;

				$html_code = $row->code;
				$html_data = json_decode($html_code);
				if ($html_data != null) {
					foreach ($html_data[0]->pages as $p) {
						$pagelabellist[] = $p->page_id . '-' . $p->page_name;
					}
				}
			}
		}
		return $pagelabellist;
	}

	public function getGroupsList()
	{
		$type = $this->input->post('type');
		if ($type != null && $type != '') {

			$table = 'word_reportMaker_table';
			if ($type == 2) {
				$table = "child_group_master";
			}
			$getGroupData = $this->MasterModel->_select($table, array('status' => 1), '*', false);
			if ($getGroupData->totalCount > 0) {
				$data = $getGroupData->data;
				$groupArr = array('none');
				foreach ($data as $row) {
					$groupArr[] = $row->id . '-' . $row->name;
				}

				$response['status'] = 200;
				$response['body'] = "Data Found";
				$response['data'] = $groupArr;
			} else {
				$response['status'] = 201;
				$response['body'] = "No Data Found";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function ChildGroup(){
		$this->load->view('WordReport/ChildGroup',array('title' => 'Child Group'));
	}
}

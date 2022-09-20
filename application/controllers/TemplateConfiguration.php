<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class TemplateConfiguration
 * @property MasterModel MasterModel
 */
class TemplateConfiguration extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('MasterModel');
		$this->load->model('Global_model');
	}

	public function index($template_id)
	{
		$this->load->view('pageConfiguration/page-configuration', array('template_id' => $template_id));
	}

	public function template_list()
	{
		$this->load->view('pageConfiguration/template-list', array('title' => 'Template List'));
	}

	public function viewForm($template_id, $dynamic_id = 0)
	{
//		print_r($this->session->user_session);exit();
//		$this->session->user_session = array('user_id' => 'U_656', 'user_type' => 1, 'user_name' => "supriya", 'firm_id' => 'Firm_1101');
		$qqueryParameter = array();
		//index start with always 1
		$indexArray = array('index_1' => $template_id, 'index_2' => $dynamic_id);
		$resultObject = $this->MasterModel->_select('TE_template_form_structure', array('id' => $template_id), "query_param");

//		if($resultObject->totalCount>0)
//		{
//			$queryParam=$resultObject->data->query_param;
//
//			if($queryParam!="" && $queryParam!=null)
//			{
//				$query_param=(array)json_decode($queryParam);
//
//				if(count($query_param)>0)
//				{
//					foreach ($query_param as $qkey => $qrow)
//					{
//						if(is_array($qrow))
//						{
//							foreach ($qrow as $qprow) {
//								if (is_numeric($qkey)) {
//									$qqueryParameter['queryParam_' . $qkey . '_' . $qprow->value] = '';
//								} else {
//									$qqueryParameter['queryParam_' . $template_id . '_' . $qprow->value] = '';
//								}
//							}
//						}
//						else{
//							$qrowArr=$qrow->value;
//							if(count($qrowArr)>0)
//							{
//								foreach ($qrowArr as $qprow) {
//									if (is_numeric($qkey)) {
//										$qqueryParameter['queryParam_' . $qkey . '_' . $qprow] = '';
//									} else {
//										$qqueryParameter['queryParam_' . $template_id . '_' . $qprow] = '';
//									}
//								}
//							}
//						}
//					}
//				}
//			}
//		}
		$this->load->view('pageConfiguration/view-form', array('template_id' => $template_id, 'title' => "Form", 'session' => $this->session->user_session, 'queryParam' => $qqueryParameter, 'indexArray' => $indexArray));
	}

	public function saveConfiguration()
	{
		$method = $this->input->post('method');
		$id = $this->input->post('id');
		$data = $this->input->post('data');
		$template_name = $this->input->post('template_name');
		$configuration = $this->input->post('configuration');
		$allQueryParamFields = $this->input->post('allQueryParamFields');

		$data_array = json_decode($data);

		if (!empty($data_array->rows) && $data_array->rows != null) {
			if ($template_name != null && $template_name != '') {

				if ($method == 1) {
					$template_structure = array(
						'template_name' => $template_name,
						'template_structure' => $data,
						'created_by' => "U_591",
						'configuration' => $configuration,
						'query_param' => $allQueryParamFields,
					);
					$insert = $this->MasterModel->_insert('TE_template_form_structure', $template_structure);
					if ($insert->status) {
						$response['status'] = 200;
						$response['body'] = "Template Saved Successfully";
						$response['data'] = $data;
						$response['template_name'] = $template_name;
						$response['template_id'] = $insert->inserted_id;
					} else {
						$response['status'] = 201;
						$response['body'] = "Something Went Wrong";
					}

				} else {

					$template_structure = array(
						'template_name' => $template_name,
						'template_structure' => $data,
						'created_by' => "U_591",
						'configuration' => $configuration,
						'query_param' => $allQueryParamFields,
					);

					$update = $this->MasterModel->_update('TE_template_form_structure', $template_structure, array('id' => $id));
					if ($update->status) {
						$response['status'] = 200;
						$response['body'] = "Template Updated Successfully";
						$response['data'] = $data;
						$response['template_name'] = $template_name;

					} else {
						$response['status'] = 201;
						$response['body'] = "Something Went Wrong";
					}
				}
			} else {
				$response['status'] = 201;
				$response['body'] = "Template Name Should not be Empty";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "No Data Found";
		}
		echo json_encode($response);
	}


	public function ShowForm()
	{
		$id = $this->input->post("id");
		if ($id != null && $id != '') {
			$data = $this->MasterModel->_rawQuery('select * from TE_template_form_structure where id = ' . $id . ' ');
			if ($data->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = "Data Found";
				$response['data'] = $data->data[0];
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


	public function getTableFields()
	{

		$table = $this->input->post('table');
		if ($table != null && $table != '') {
			$tableData = $this->db->list_fields($table);
			if (!empty($tableData)) {
				$response['status'] = 200;
				$response['body'] = "Tables Field Found";
				$response['data'] = $tableData;
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

	// supriya functions
	public function getAllTemplateList()
	{

		$select = array("*");
		$where = array();

		$tableName = 'TE_template_form_structure';
		$order = 'id';
		$key = 'desc';
		$column_order = array('template_name');
		$column_search = array("template_name");
		// $edData = $this->MasterModel->getRows($_POST, $where, $select, $tableName, $column_search, $column_order, $order);
		// $filterCount = $this->MasterModel->countFiltered($_POST, $tableName, $where, $column_search, $column_order, $order);
		// $totalCount = $this->MasterModel->countAll($tableName, $where);
		$edData = $this->MasterModel->_select($tableName, $where, array('*'), false, null, null, $order, $key);

		$filterCount = $edData->totalCount;
		$totalCount = $edData->totalCount;
		if ($edData->totalCount > 0) {
			$tableRows = array();
			$count = 1;
			foreach ($edData->data as $row) {
				$date = "";
				if ($row->created_on != null && $row->created_on != "" && $row->created_on != "0000:00:00 00:00:00") {
					$date = date('Y-m-d', strtotime($row->created_on));
				}
				$tableRows[] = array(
					$row->id,
					$row->template_name,
					$date,
					$row->status,
					$row->id
				);
				$count++;
			}
			$results = array(
				"draw" => 1,
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $tableRows,
			);
		} else {
			$results = array(
				"draw" => 1,
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $edData,
			);
		}
		echo json_encode($results);
	}

	public function saveFormData()
	{
		$template_id = $this->input->post('template_id');
		$update_id = $this->input->post('update_id');
		$id_array = array();
		$dataArray = array();
		$is_html = '';
		$response = array();
		if ($template_id != null && $template_id != '') {

			try {
				$this->db->trans_start();
				$templateData = $this->MasterModel->_select('TE_template_form_structure', array('id' => $template_id), '*', true);
				if ($templateData->totalCount > 0) {

					$td = $templateData->data;
					$data = $td->template_structure;
					$data = json_decode($data);
					foreach ($data->operation->transaction as $op) {
						$depend_on = $op->dependent_on;
						$t_id = $op->id;
						$table = $op->table;
						$method = $op->method;
						$column = $op->column;
						$bulk = $op->is_bulk;
						$update_data = $op->whereColumn;
						$is_html = "";
						if (property_exists($op, 'is_html')) {
							$is_html = $op->is_html;
						}
						$is_dependent = false;
						$where_array = array();
						if ($update_id != 0 && $update_id != '' && $update_id != null && $update_id != '0') {
							$insert_array = array();
							foreach ($column as $k => $row) {
								$key = key((array)($column[$k]));
								$row_val = $row->{$key};
								$val = '';
								if (substr($row_val, 0, 1) == '#') {
									$depend = explode("#", $row_val);
									if (count($depend) > 1) {
										$val = $update_id;
										$is_dependent = true;
										$where_array[$key] = $val;
									}
								} else {
									$val = $this->input->post($row_val);
								}
								if (isset($_FILES[$row_val])) {
									$des_path = "uploads";
									$combination = "2";
									$result = $this->Global_model->upload_multiple_file_new($des_path, $row_val, $combination);
									$des_path = "";
									if ($result["status"] == 200) {
										if (count($result["body"]) > 0) {
											if ($result["body"][0] === "uploads/") {
												$des_path = "";
											} else {
												if (count($result['body']) > 1) {
													for ($i = 0; $i < count($result['body']); $i++) {

														$des_path .= $result["body"][$i] . ",";
													}
												} else {
													$des_path = $result["body"][0];
												}
											}
										} else {
											$des_path = "";
										}
										$des_path = rtrim($des_path, ",");
										if ($des_path != "") {
											$des_path = ltrim($des_path, ",");
											$insert_array[$key] = $des_path;
										}
									}
								} else {
									if (is_array($val) && count($val) > 0) {
										$val = implode(',', $val);
									}
									$insert_value = $val;
									$insert_array[$key] = $insert_value;
								}
							}
							$insert = false;
							$insert_array2 = array();
							if ($bulk == 1) {
								foreach ($insert_array as $k1 => $i) {
									$i = explode(",", $i);
									if (is_array($i) && count($i) > 0) {
										foreach ($i as $bulk_val) {
											$insert_array[$k1] = $bulk_val;
											array_push($insert_array2, $insert_array);
										}
										break;
									}
								}
								$insert = $this->db->insert_batch($table, $insert_array2);
							} else {
								$insert_array2 = $insert_array;
								if ($is_dependent == true) {
									$insert = $this->db->where($where_array)->update($table, $insert_array2);
								} else {
									$insert = $this->db->where('id', $update_id)->update($table, $insert_array2);
								}
							}


							if (property_exists($data->operation, 'action')) {
								$trans_id = $data->operation->action->extra[0];
								$trans_id = explode(".", $trans_id);
								$trans_id = $trans_id[0];
								$id_array[$trans_id] = $update_id;
							}

							if ($this->db->trans_status() === FALSE) {
								$response['status'] = 201;
								$response['body'] = 'Something Went Wrong';
								$this->db->trans_rollback();
							} else {
								if ($insert) {
									$response['status'] = 200;
									$response['body'] = "Data Updated Successfully";
									$response['data'] = $insert_array;
									$response['insert_id'] = $id_array;
									$this->db->trans_commit();
								} else {
									$response['status'] = 201;
									$response['body'] = 'Something Went Wrong';
									$this->db->trans_rollback();
								}
							}
						} else {
							if ($method == 1) {
								$insert_array = array();
								foreach ($column as $k => $row) {
									$key = key((array)($column[$k]));
									$row_val = $row->{$key};
									$val = '';
									if (substr($row_val, 0, 1) == '#') {
										$depend = explode("#", $row_val);
										if (count($depend) > 1) {
											$val = $id_array[$depend[1]];
										}
									} else {
										$val = $this->input->post($row_val);
									}
									if (isset($_FILES[$row_val])) {
										$des_path = "uploads";
										$combination = "2";
										$result = $this->Global_model->upload_multiple_file_new($des_path, $row_val, $combination);
										$des_path = "";
										if ($result["status"] == 200) {
											if (count($result["body"]) > 0) {
												if ($result["body"][0] === "uploads/") {
													$des_path = "";
												} else {
													if (count($result['body']) > 1) {
														for ($i = 0; $i < count($result['body']); $i++) {

															$des_path .= $result["body"][$i] . ",";
														}
													} else {
														$des_path = $result["body"][0];
													}
												}
											} else {
												$des_path = "";
											}
											$des_path = rtrim($des_path, ",");
											if ($des_path != "") {
												$des_path = ltrim($des_path, ",");
												$insert_array[$key] = $des_path;
											}
										}
									} else {
										if (is_array($val) && count($val) > 0) {
											$val = implode(',', $val);
										}
										$insert_value = $val;
										$insert_array[$key] = $insert_value;
									}
								}
								$insert = false;
								$insert_array2 = array();
								if ($bulk == 1) {
									foreach ($insert_array as $k1 => $i) {
										$i = explode(',', $i);
										if (is_array($i) && count($i) > 0) {
											foreach ($i as $bulk_val) {
												$insert_array[$k1] = $bulk_val;
												array_push($insert_array2, $insert_array);
											}
											break;
										}
									}
									if (!empty($insert_array2)) {
										$insert = $this->db->insert_batch($table, $insert_array2);
									}
								} else {
									$insert_array2 = $insert_array;
									$insert = $this->db->insert($table, $insert_array2);
									$id_array[$t_id] = $this->db->insert_id();
								}
								if ($is_html == 1) {
									array_push($dataArray, $insert_array2);
								}
								if ($this->db->trans_status() === FALSE) {
									$response['status'] = 201;
									$response['body'] = 'Something Went Wrong';
									$this->db->trans_rollback();
								} else {
									if ($insert) {
										$response['status'] = 200;
										$response['body'] = "Data Inserted";
										$response['data'] = $insert_array2;
										$response['insert_id'] = $id_array;
										$this->db->trans_commit();
									} else {
										$response['status'] = 201;
										$response['body'] = 'Something Went Wrong';
									}
								}
							} else {
								$update_array = array();
								$insert_array = array();
								foreach ($column as $k => $row) {
									$key = key((array)($column[$k]));
									$row_val = $row->{$key};
									$val = '';
									if (substr($row_val, 0, 1) == '#') {
										$depend = explode("#", $row_val);
										if (count($depend) > 1) {
											$val = $id_array[$depend[1]];
										}
									} else {
										$val = $this->input->post($row_val);
									}
									if (is_array($val) && count($val) > 0) {
										$val = implode(",", $val);
									}
									$insert_array[$key] = $val;
								}

								foreach ($update_data as $k => $row) {
									$key = key((array)($update_data[$k]));
									$row_val = $row->{$key};
									$val = $this->input->post($row_val);
									$update_value = '';
									if (is_array($val) && count($val) > 0) {
										$update_value = implode(",", $val);
									} else {
										$update_value = $val;
									}
									$update_array[$key] = $update_value;
								}
								$update = $this->MasterModel->_update($table, $insert_array, $update_array);

								if ($this->db->trans_status() === FALSE) {
									$response['status'] = 201;
									$response['body'] = 'Something Went Wrong';
									$this->db->trans_rollback();
								} else {
									if ($update->status) {
										$response['status'] = 200;
										$response['body'] = "Data Updated Successfully";
										$response['data'] = $insert_array;
										$response['update'] = $update;
										$this->db->trans_commit();
									} else {
										$response['status'] = 201;
										$response['body'] = 'Something Went Wrong';
									}
								}
							}
						}
					}
					$response['action'] = null;
					$response['dataArray'] = $dataArray;
					$response['is_html'] = $is_html;
					if (property_exists($data->operation, 'action')) {
						$response['action'] = $data->operation->action;
					}
				} else {
					$response['status'] = 201;
					$response['body'] = "No Data Found";
				}
				$this->db->trans_complete();
			} catch (Exception $ex) {
				$this->db->trans_rollback();
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}


	public function getHandson()
	{
		$getHandson = $this->MasterModel->_select('TE_handson_template_master', array('status' => 1), '*', false);
		if ($getHandson->totalCount > 0) {
			$option = "<option value='-1' selected>Select Handsontable</option>";
			foreach ($getHandson->data as $row) {
				$option .= "<option value='" . $row->id . "'>" . $row->template_name . "</option>";
			}
			$response['status'] = 200;
			$response['data'] = $option;
			$response['body'] = "Data Found";
		} else {
			$option = "<option value='-1' selected>No Data Found</option>";
			$response['data'] = $option;
			$response['status'] = 201;
			$response['body'] = "No Data Found";
		}
		echo json_encode($response);
	}

	public function getDataOnChange()
	{
		if (!is_null($this->input->post('value')) && !is_null($this->input->post('query')) && !is_null($this->input->post('columnType')) && !is_null($this->input->post('columns'))) {
			$value = $this->input->post('value');
			$query = $this->input->post('query');
			$columnType = $this->input->post('columnType');
			$columns = $this->input->post('columns');
			if ($columnType == 'dropdown') {
				$value = explode('#', $value);
				$value = $value[0];
			}
			$resultObject = $this->MasterModel->_rawQuery($query, array($value));
			$data = array();

			if ($resultObject->totalCount > 0) {
				$columns = explode(',', $columns);
				$arrayData = (array)$resultObject->data[0];
				$i = 0;
				foreach ($arrayData as $key => $row) {
					$col = $columns[$i];
					array_push($data, array('col' => $col, 'value' => $row));
//					$data[$col]=$row;
					$i++;
				}
				$response['status'] = 200;
				$response['body'] = $data;
			} else {
				$response['status'] = 201;
				$response['body'] = array();
			}
		} else {
			$response['status'] = 201;
			$response['body'] = array();
		}
		echo json_encode($response);
	}

	public function getTableColumn()
	{

		$table = $this->input->post('table_name');
		if ($table != null && $table != '') {
			$tableData = $this->db->list_fields($table);
			if (!empty($tableData)) {
				$response['status'] = 200;
				$response['body'] = "Tables Field Found";
				$response['data'] = $tableData;
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

	public function getOptions()
	{
		$query = $this->input->post('query');
		if ($query != null && $query != '') {
			$getHandson = $this->MasterModel->_rawQuery($query);

			if ($getHandson->totalCount > 0) {
				$option = "<option value='-1' selected>Select One</option>";
				foreach ($getHandson->data as $row) {
					$option .= "<option value='" . $row->id . "'>" . $row->text . "</option>";
				}
				$response['status'] = 200;
				$response['data'] = $option;
				$response['body'] = "Data Found";
			} else {
				$option = "<option value='-1' selected>No Data Found</option>";
				$response['data'] = $option;
				$response['status'] = 201;
				$response['body'] = "No Data Found";
			}
		} else {
			$option = "<option value='-1' selected>No Data Found</option>";
			$response['data'] = $option;
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function saveAddMore()
	{
		// print_r($this->input->post());exit();
		$template_id = $this->input->post('template_id');

		$templateData = $this->MasterModel->_select('TE_template_form_structure', array('id' => $template_id), '*', true);
		if ($templateData->totalCount > 0) {
			$td = $templateData->data;
			$data = $td->template_structure;
			$data = json_decode($data);
			$rows = array();
			$table_name = '';
			$select_type='';
			$input_array = array();
			foreach ($data as $op) {
				if (count($op) > 0) {
					$rows = $op[0]->cols[0]->element->addmore_option_array;
					$table_name = $op[0]->cols[0]->element->query_table_name;


					if (count($rows) > 0) {
						foreach ($rows as $cols) {
							$input_array[] = ($cols->input);
						}
					}
				}
			}

			$data_arr = array();
			$update_data_arr = array();
			foreach ($this->input->post($input_array[0]) as $i => $v) {
				$update_id=$this->input->post('update_addmore['.$i.']');
				$selectdate=$this->input->post('date['.$i.']');


				if (count($rows) > 0) {
					$arr = array();

					if((int)$update_id!=0){
					$arr['id'] = $update_id;
					foreach ($rows as $cols) {
						if(!empty($selectdate))
						{
							$arr['created_on']=date($selectdate);
						}
						$arr[$cols->column_name] = $this->input->post($cols->input . "[" . $i . "]");

					}
                     array_push($update_data_arr, $arr);
				   }else{
					foreach ($rows as $cols) {
						if(!empty($selectdate))
						{
							$arr['created_on']=date($selectdate);
						}
						$arr[$cols->column_name] = $this->input->post($cols->input . "[" . $i . "]");

					}
						array_push($data_arr, $arr);
					   }

				}
			}

			$update = new stdClass();
			$update->status = false;

            if(count($update_data_arr) > 0){

				$update = $this->MasterModel->_updateBatch($table_name, $update_data_arr,'id');

			}if (count($data_arr) > 0) {

				$update = $this->MasterModel->_insertBatch($table_name, $data_arr);
			}


			if ($update->status) {
				$response['status'] = 200;
				$response['table'] = $table_name;
				$response['body'] = "Saved Successfully";
			}else {
				$response['status'] = 201;
				$response['body'] = "Something Went Wrong";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "No Data Found";
		}
		echo json_encode($response);
	}

	public function UpdateAddMore()
	{
		$id = $this->input->post('id');
		if ($id != null && $id != '') {
			$columnObject=null;
			$templateData = $this->MasterModel->_select('TE_template_form_structure', array('id' => $id), '*', true);

			if ($templateData->totalCount > 0) {
				$td = $templateData->data;
				$data = $td->template_structure;
				$data = json_decode($data);
				$rows = array();
				$table_name = '';
				$input_array = array();
				foreach ($data as $op) {
					if (count($op) > 0) {
						$rows = $op[0]->cols[0]->element->addmore_option_array;
						$table_name = $op[0]->cols[0]->element->query_table_name;
						$columnObject=$op[0]->cols[0];
					}
				}
				$where = array('status' => 1);
				$getData = $this->MasterModel->_select($table_name, $where, '*', false);
				if ($getData->totalCount > 0) {

					$cnt = 0;
					$GData = $getData->data;
					foreach ($GData as $row) {
						$arr = array();
						$cnt = 0;

						$arr['pk'] = $row->id;
						foreach ($rows as $cols) {
							$arr[$cols->input] = $row->{$cols->column_name};

							$cnt++;
						}

						array_push($input_array, $arr);

					}

					$response['data'] = $input_array;
					$response['col'] = $cnt;
					$response["templateFormat"]=$columnObject;

					$response['status'] = 200;
					$response['body'] = "Data Found";
				} else {
					$response['status'] = 201;
					$response['body'] = "No Data Found";
				}
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

	function removeAddmoreRows()
	{
		$table_name = $this->input->post('query_table');
		$id = (int)$this->input->post('query_id');

		if (!is_null($id)) {
			$where = array(
				'id' => $id

			);

			$delete = $this->db->delete($table_name, $where);
			if ($delete == TRUE) {
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


}

?>

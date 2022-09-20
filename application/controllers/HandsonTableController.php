<?php

require_once 'HexaController.php';

class HandsonTableController extends HexaController
{


	/**
	 * CompaniesController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('MasterModel');
	}

	public function index()
	{
		$data['title'] = 'Configuration';
		$this->load->view('HandsonTable/HandsonConfiguaration', $data);
	}

	public function addHandsonTemplate()
	{
//		 print_r($this->input->post());exit();
		if (!is_null($this->input->post('template_name')) && $this->input->post('template_name') != "" && !is_null($this->input->post('attribute_name')) && $this->input->post('attribute_name') != "") {
//			$user_id = $this->session->user_session->user_id;
			$user_id = '1';
			$template_name = $this->input->post('template_name');
			$template_id = $this->input->post('template_id');
			$prefill = $this->input->post('prefill');
			$company_id = $this->input->post('company_id');
			$hashKey = $this->input->post('hashKey');
			$props = $this->input->post('props');
			$fill_table = $this->input->post('fill_table');
			$route_pos = $this->input->post('route_pos');
			$route_pos_query = $this->input->post('route_pos_query');
			$required_fields = $this->input->post('required_fields');
			$show_total = $this->input->post('show_total');
			$hashKey = '';
			if ($hashKey != "") {
				$hashKey = implode(",", $hashKey);
			}

			$insert_id = '';
			try {
				$this->db->trans_start();
				$status = 201;
				$body = 'Data Not Saved';
				if (!empty($template_id)) {
					$updatetemplateMaster = array('template_name' => $template_name, 'prefill' => $prefill, 'hash_key' => $hashKey, 'show_total' => $show_total, 'props' => $props, 'modify_by' => $user_id, 'modify_on' => date('Y-m-d H:i:s'), 'fill_table' => $fill_table, 'route_pos' => $route_pos, 'route_pos_query' => $route_pos_query, 'required_fields' => $required_fields);
					$where = array('id' => $template_id);
					if ($this->db->set($updatetemplateMaster)->where($where)->update('TE_handson_template_master')) {
						/*if ($this->db->delete('TE_handson_template_column_master', array('template_id' => $template_id))) {
							$insert_id = $template_id;
						}*/
					}
					$insert_id = $template_id;
				} else {
					$templateMaster = array('template_name' => $template_name, 'user_id' => $user_id, 'created_by' => $user_id,
						'hash_key' => $hashKey, 'show_total' => $show_total, 'props' => $props, 'created_on' => date('Y-m-d H:i:s'), 'status' => 1, 'prefill' => $prefill, 'fill_table' => $fill_table, 'route_pos' => $route_pos, 'route_pos_query' => $route_pos_query, 'required_fields' => $required_fields);
					if ($this->db->insert('TE_handson_template_master', $templateMaster)) {
						$insert_id = $this->db->insert_id();
					}
				}
				if (!empty($insert_id)) {
					$attribute_name = $this->input->post('attribute_name');
					$attribute_type = $this->input->post('attribute_type');
					$attribute_query = $this->input->post('attribute_query');
					$hdn_update_id = $this->input->post('hdn_update_id');

					$valueType = $this->input->post('valueType');
					$is_query = $this->input->post('is_query');
					$default_value = $this->input->post('default_value');

					//anyone dependant
					$is_anyoneDep = $this->input->post('is_anyoneDep');
					$custom_query = $this->input->post('custom_query');
					$dependant_col = $this->input->post('dependant_col');

					$is_readonly = $this->input->post('is_readonly');

					// $sequence = $this->input->post('sequence');
					$template_name_length = count($this->input->post('attribute_name'));
					$columnMaster = array();
					$columnMasterUpdate = array();
					$setUpdate = array();
					$c = 1;
					for ($i = 0; $i < $template_name_length; $i++) {
						$formulaColumns = $this->input->post('formulaColumns' . $c);
						if ($attribute_type[$i] == "") {
							$attribute_type[$i] = 'text';
						}

						if (is_null($valueType) || empty($valueType)) {
							$valueType1 = '';
						} else {
							$valueType1 = $valueType[$i];
						}
						if (is_null($formulaColumns) || empty($formulaColumns)) {
							$formulaColumns1 = '';
						} else {
							$formulaColumns1 = implode(",", $formulaColumns);
						}
						if ($hdn_update_id[$i] == 0) {
							$templateDetails = array('template_id' => $insert_id,
								'column_name' => $attribute_name[$i],
								'column_type' => $attribute_type[$i],
								'option_data' => $attribute_query[$i],
								'is_query' => $is_query[$i],
								'sequence' => $i,
								'user_id' => $user_id,
								'created_by' => $user_id,
								'value_type' => $valueType1,
								'formula' => $formulaColumns1,
								'created_on' => date('Y-m-d H:i:s'),
								'column_value' => 'column_' . $c,
								'default_value' => $default_value[$i],
								'is_anyoneDep' => $is_anyoneDep[$i],
								'custom_query' => $custom_query[$i],
								'dependant_col' => $dependant_col[$i],
								'is_readonly' => $is_readonly[$i]
							);
							array_push($columnMaster, $templateDetails);
						} else {
							$templateDetailsUpdate = array('template_id' => $insert_id,
								'column_name' => $attribute_name[$i],
								'column_type' => $attribute_type[$i],
								'option_data' => $attribute_query[$i],
								'is_query' => $is_query[$i],
								'sequence' => $i,
								'user_id' => $user_id,
								'created_by' => $user_id,
								'value_type' => $valueType1,
								'formula' => $formulaColumns1,
								'id' => $hdn_update_id[$i],
								'created_on' => date('Y-m-d H:i:s'),
								'column_value' => 'column_' . $c,
								'default_value' => $default_value[$i],
								'is_anyoneDep' => $is_anyoneDep[$i],
								'custom_query' => $custom_query[$i],
								'dependant_col' => $dependant_col[$i],
								'is_readonly' => $is_readonly[$i]
							);
							array_push($columnMasterUpdate, $templateDetailsUpdate);

						}

						$c++;
					}

					if (count($columnMaster) > 0) {
						$this->db->insert_batch('TE_handson_template_column_master', $columnMaster);

					}
					if (count($columnMasterUpdate) > 0) {
						$this->db->update_batch('TE_handson_template_column_master', $columnMasterUpdate, 'id');
					}

				}

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$status = 201;
					$body = 'something went wrong';
				} else {
					$this->db->trans_commit();
					$status = 200;
					$body = 'Data Uploaded';
				}
				$this->db->trans_complete();
			} catch (Exception $exc) {
				$status = 201;
				$body = 'something went wrong';
				$this->db->trans_rollback();
				$this->db->trans_complete();
			}

			$response['status'] = $status;
			$response['body'] = $body;
			$response['prefill'] = $prefill;
			$response['temp_id'] = $insert_id;
			$response['temp_name'] = $template_name;
		} else {
			$response['status'] = 201;
			$response['body'] = 'Parameter missing';
		}
		echo json_encode($response);
	}

	public function getTablesList()
	{
		$user_id = 1;
		if ($this->input->post('company_id') == 'all') {
			$where = array();
			$where_in = null;
		} else {

			$where = array('status' => 1);
		}
		$tables = $this->MasterModel->_select('TE_handson_template_master', $where, array('id', 'template_name', 'status', 'prefill'), false);

		// print_r($tables);exit();
		$tableRows = array();
		if ($tables->totalCount > 0) {
			$i = 1;
			foreach ($tables->data as $row) {
				// print_r($row);exit();
				array_push($tableRows, array($i, $row->template_name, $row->id, $row->status, $row->prefill));
				$i++;
			}
			rsort($tableRows);
		}
		$results = array(
			"draw" => 1,
			"recordsTotal" => count($tableRows),
			"recordsFiltered" => count($tableRows),
			"data" => $tableRows
		);
		echo json_encode($results);
	}

	public function edithandsontemplate()
	{
		// print_r($this->input->post());exit();
		if (!is_null($this->input->post('id')) && !is_null($this->input->post('template_name'))) {
			$company_id = '';
			$template_id = $this->input->post('id');
			$template_name = $this->input->post('template_name');
			//	$resultObject = $this->MasterModel->_select('TE_handson_template_column_master ht', array('template_id' => $template_id), "*,(select hash_key from TE_handson_template_master hm where hm.id=ht.template_id) as hash_key", false);
			$resultObject = $this->db->query("select *,(select hash_key from TE_handson_template_master hm where hm.id=ht.template_id) as hash_key,(select props from TE_handson_template_master hm where hm.id=ht.template_id) as props,(select show_total from TE_handson_template_master hm where hm.id=ht.template_id) as show_total,(select required_fields from TE_handson_template_master hm where hm.id=ht.template_id) as required_fields from TE_handson_template_column_master ht where template_id=" . $template_id . " order by sequence asc");
			// print_r($resultObject);exit();
			$attributeRows = array();
			if ($this->db->affected_rows() > 0) {

				foreach ($resultObject->result() as $row) {
					array_push($attributeRows, $row);
				}
				$response['status'] = 200;
				$response['template_name'] = $template_name;
				$response['body'] = $attributeRows;
			} else {
				$response['status'] = 200;
				$response['template_name'] = $template_name;
				$response['body'] = $attributeRows;
			}

		} else {
			$response['status'] = 201;
			$response['body'] = 'Parameter missing';
		}
		echo json_encode($response);
	}

	public function getAllTablesFromDatabase()
	{
		$tables = array();
		$tableRows = array();
		$tables = $this->db->list_tables();
		if (count($tables) > 0) {
			$tableRows = array();
			foreach ($tables as $row) {
				$tableRows[] = array(
					$row
				);
			}
			$results = array(
				"data" => $tableRows,
			);
		} else {
			$results = array(
				"data" => $tableRows,
			);
		}
		echo json_encode($results);

	}

	function TE_getTemplateData()
	{
		$template_id = $this->input->post('temp_id');
		if (!is_null($template_id) || !empty($template_id)) {
			$query = $this->MasterModel->_rawQuery('select * from TE_handson_template_column_master where template_id=' . $template_id . ' order by sequence asc');
			if ($query->totalCount > 0) {
				$result = $query->data;
			} else {
				$result = array();
			}
			$tableName = '';
			$querygetTemplateMapping = $this->MasterModel->_rawQuery('select * from TE_Template_DB_mapping where template_id=' . $template_id);
			$resultMapping = array();
			if ($querygetTemplateMapping->totalCount > 0) {
				$resultTemplateMapping = $querygetTemplateMapping->data;
				foreach ($resultTemplateMapping as $row) {
					$resultMapping[$row->column_id] = $row->DB_column;
					$tableName = $row->DB_table;
				}
			} else {
				$resultTemplateMapping = array();
			}

			$where_condition = '';
			$where_array = $this->MasterModel->_select('TE_handson_template_master', array('id' => $template_id), 'where_condition');
			if ($where_array->totalCount > 0) {
				$where_condition = $where_array->data->where_condition;
			}
			$response['status'] = 200;
			$response['data'] = $result;
			$response['tableName'] = $tableName;
			$response['where_condition'] = $where_condition;
			$response['dataTemplateMapping'] = $resultMapping;
		} else {
			$response['status'] = 201;
			$response['body'] = 'Parameter Missing!';
		}
		echo json_encode($response);
	}

	function TE_getDBColumnNames()
	{
		//get HashKeys
		/*$map_temp_id=$this->input->post('map_temp_id');
		$resulArray=$this->MasterModel->_rawQuery('select hash_key from TE_handson_template_master where id='.$map_temp_id);

		if($resulArray->totalCount > 0){
			$resultData=$resulArray->data;
			foreach ($resultData as $row){
				 $hash_key=$row->hash_key;
			}
			$exp=explode(",",$hash_key);
			$tableColumns[] = $exp;
		}*/
		$tableColumns = array();
		$TableName = $this->input->post('TableName');

		if ($TableName == "") {
			$results = array(
				"data" => $tableColumns,
			);
		} else {
			$columns = $this->db->list_fields($TableName);
			if (count($columns) > 0) {

				foreach ($columns as $row) {
					$tableColumns[] = array(
						$row
					);
				}
				$results = array(
					"data" => $tableColumns,
				);
			} else {
				$results = array(
					"data" => $tableColumns,
				);
			}
		}

		echo json_encode($results);

	}

	function checkDuplicates($array)
	{
		$cnt = 0;
		foreach (array_count_values($array) as $key => $val) {
			if ($val > 1) {
				$cnt++;
			}  //Push the key to the array sice the value is more than 1
		}
		if ($cnt > 0) {
			return true;
		} else {
			return false;
		}
	}

	function TE_SaveConfiguration()
	{
		$colName = $this->input->post('colName');
		$DBColname = $this->input->post('DBColname');
		$map_col_idArray = $this->input->post('map_col_id');
		$map_temp_id = $this->input->post('map_temp_id');
		$table_name = $this->input->post('table_name');
		$where_condition = $this->input->post('where_condition');
		$duplicates = $this->checkDuplicates($DBColname);
		if ($duplicates == true) {
			$response['status'] = 201;
			$response['body'] = 'Duplicate Column Assignment Found.Please Check!';
			echo json_encode($response);
			exit;
		}
		$FinalArray = array();
		if (count($colName) > 0) {
			if ($table_name == "") {
				$response['status'] = 201;
				$response['body'] = 'Select Table Name';
				echo json_encode($response);
				exit;
			} else if (!is_array($DBColname)) {
				$response['status'] = 201;
				$response['body'] = 'Select DB Column Name at all Places!';
				echo json_encode($response);
				exit;
			} else if (count($map_col_idArray) != count($DBColname)) {
				$response['status'] = 201;
				$response['body'] = 'Select DB Column Name at all Places!';
				echo json_encode($response);
				exit;
			} else {
				foreach ($colName as $key => $item) {

					$DBcolumn = $DBColname[$key];
					$map_col_id = $map_col_idArray[$key];
					$data = array(
						'template_id' => $map_temp_id,
						'column_id' => $map_col_id,
						'DB_table' => $table_name,
						'DB_column' => $DBcolumn,
					);
					array_push($FinalArray, $data);
				}
				if (count($FinalArray) > 0) {
					try {
						$status = 201;
						$body = 'something went wrong';
						$this->db->trans_start();
						$where = array('template_id' => $map_temp_id);
						$delete = $this->db->delete('TE_Template_DB_mapping', $where);
						if ($delete == true) {
							$insert_batch = $this->db->insert_batch('TE_Template_DB_mapping', $FinalArray);

							$this->db->set(array('where_condition' => $where_condition))->where(array('id' => $map_temp_id))->update('TE_handson_template_master');
						}

						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							$status = 201;
							$body = 'something went wrong';
						} else {
							$this->db->trans_commit();
							$status = 200;
							$body = 'Data Uploaded';
						}
						$this->db->trans_complete();
					} catch (Exception $exc) {
						$status = 201;
						$body = 'something went wrong';
						$this->db->trans_rollback();
						$this->db->trans_complete();
					}
					$response['status'] = $status;
					$response['body'] = $body;
				} else {
					$response['status'] = 201;
					$response['body'] = 'Something Went Wrong!';
				}
			}

		} else {
			$response['status'] = 201;
			$response['body'] = 'Parameter Missing!';
		}
		echo json_encode($response);
	}


	function TransactionDataFetch($temp_id, $table_name, $where = '', $columnSequnceArray = array(), $positionArray = null, $positionKey = null, $is_edit = false, $where_condition = '')
	{
		$where_array = null;
		if (count($columnSequnceArray) > 0) {
			$finalArray = array();
			$column = implode(',', $columnSequnceArray);
			//$queryFetch=$this->MasterModel->_rawQuery("select ".$column." from ".$table_name.$where);
			if ($where_condition != '') {
				$where_array = $where_condition;
			}
			$queryFetch = $this->MasterModel->_select($table_name, $where, $select = $columnSequnceArray, false, null, null, null, null, $where_array);
			if ($queryFetch->totalCount > 0) {
				$data = $queryFetch->data;
				$i = 0;
				foreach ($data as $key => $row) {
					foreach ($row as $k => $v) {
						if ($row->$k != null && $row->$k != '') {
							if (array_key_exists($i, $positionArray)) {
								if ($is_edit == true) {
									$update_query = $positionArray[$i];
									$update_query = explode('where', $update_query);
									$update_query = $update_query[0];
									$query = $update_query . 'where ' . $positionKey[$i][0] . '=' . $row->$k;
								} else {
									$query = $positionArray[$i] . ' AND ' . $positionKey[$i][0] . ' =' . $row->$k;
								}
								$qdata = $this->db->query($query)->row();
								if (!is_null($qdata)) {
									$qdata = $qdata->{$positionKey[$i][0]} . "#" . $qdata->{$positionKey[$i][1]};
									$row->$k = $qdata;
								}
							}
						}
						$i++;
					}
					$i = 0;
					$rowArr = (array)$row;
					$finalIndArray = array_values($rowArr);
					array_push($finalArray, $finalIndArray);
				}
			} else {
				return false;
			}
			if (count($finalArray) > 0) {
				return $finalArray;
			} else {
				return false;
			}
		} else {
			return false;
		}


	}

	function getHandsonTableData()
	{
		$temp_id = $this->input->post('temp_id');
		$code = $this->input->post('code');
		$DefaultValuesArray = $this->input->post('insert_id');
		$DefaultValuesArray = json_decode($DefaultValuesArray);

		$is_edit = false;
		if ($DefaultValuesArray != null) {
			$is_edit = true;
		}

		$where = array();
		$dataSchema = new stdClass();
		$formulaArray = array();
		$queryResult = $this->MasterModel->_rawQuery('select option_data,column_type,id,default_value,value_type,formula,(select DB_column from TE_Template_DB_mapping M where M.column_id=cm.id ) as DB_column from TE_handson_template_column_master cm where template_id=' . $temp_id . ' order by sequence asc');
		if ($queryResult->totalCount > 0) {
			$where = array();
			$data = $queryResult->data;
			$cnt = 0;
			foreach ($data as $row) {
				if ($row->column_type == 'hidden') {
					if ($row->default_value != '' && $row->default_value != null) {

					} else {
						$optionData = $row->option_data;
						$where[$row->DB_column] = $DefaultValuesArray->$optionData;
					}
				}
				$dataSchema->$cnt = $row->default_value;
				if ($row->value_type == 2) {
					$formulaArray[$cnt] = $row->formula;
				}
				$cnt++;
			}
		}
		$props = '';
		$fill_table = 1;
		$route_pos = '';
		$route_pos_query = '';
		$where_condition = '';
		$show_total = '';
		$propsData = $this->MasterModel->_select('TE_handson_template_master', array('id' => $temp_id), array('props', 'show_total', 'fill_table', 'route_pos', 'route_pos_query', 'where_condition'), true);
		if ($propsData->totalCount > 0) {
			$props = $propsData->data->props;
			$fill_table = $propsData->data->fill_table;
			$route_pos = $propsData->data->route_pos;
			$route_pos_query = $propsData->data->route_pos_query;
			$where_condition = $propsData->data->where_condition;
			$show_total = $propsData->data->show_total;
		}

		if ($code == 1) {
			$query = $this->MasterModel->_rawQuery('select column_name as columnName,column_type,is_query,option_data,
(select jsonCode from TE_handson_prefill_table hp where hm.template_id=hp.template_id) as jsonCode,is_anyoneDep,custom_query,dependant_col,is_readonly
 from TE_handson_template_column_master hm  where template_id=' . $temp_id . ' order by sequence asc');
		} else {
			$query = $this->MasterModel->_rawQuery('select *,
(select column_name from TE_handson_template_column_master ht where ht.id=tm.column_id) as columnName,
 (select column_type from TE_handson_template_column_master ht where ht.id=tm.column_id) as column_type,
 (select is_query from TE_handson_template_column_master ht where ht.id=tm.column_id) as is_query,
 (select option_data from TE_handson_template_column_master ht where ht.id=tm.column_id) as option_data,
 (select jsonCode from TE_handson_prefill_table hp where tm.template_id=hp.template_id) as jsonCode,
  (select is_anyoneDep from TE_handson_template_column_master ht where ht.id=tm.column_id) as is_anyoneDep,
  (select custom_query from TE_handson_template_column_master ht where ht.id=tm.column_id) as custom_query,
  (select dependant_col from TE_handson_template_column_master ht where ht.id=tm.column_id) as dependant_col,
  (select is_readonly from TE_handson_template_column_master ht where ht.id=tm.column_id) as is_readonly
 from TE_Template_DB_mapping tm where template_id=' . $temp_id . ' order by (select sequence from TE_handson_template_column_master ht where ht.id=tm.column_id) asc');
		}

		if ($query->totalCount > 0) {
			//check Transaction Data Available
			$DB_table = '';
			$columnHeaders = array();
			$columnTypes = array();
			/*$object1 = new stdClass();
			$object1->type = 'text';*/
			$prefillData = '';
			$allColumns = array();
			$hideColumns = array();
			array_push($columnTypes);
			$j = 1;
			$i = 0;
			$defaultVal = array();

			$positionArray = array();
			$positionKey = array();
			$AnyoneDependant = array();

			foreach ($query->data as $row) {
				array_push($columnHeaders, $row->columnName);
				if ($code != 1) {
					$allColumns[] = $row->DB_column;
				}
				$object = new stdClass();
				$object->type = $row->column_type;
				if ($row->column_type == "hidden") {


					$hideColumns[] = $i;
					$object->type = "text";

				}
				if ($row->is_readonly == 1) {
					$object->readOnly = true;
				}
				if ($row->column_type == 'dropdown') {

					if ($row->is_query == 1) {
						if ($row->option_data != '') {
							$positionArray[$i] = $row->option_data;
							$queryOption = $this->MasterModel->_rawQuery($row->option_data);
							$final_optionData = array();
							if ($queryOption->totalCount > 0) {
								$dataKey = array();
								$cnt = 1;
								foreach ($queryOption->data as $row1) {
									if ($cnt == 1) {
										foreach ($row1 as $key => $r1) {
											$dataKey[] = $key;
											$positionKey[$i][] = $key;
										}
									} else {
										break;
									}
									$cnt++;
								}
								foreach ($queryOption->data as $queryRow) {
									$id = $dataKey[0];
									$val = $dataKey[1];
									$final_optionData[] = $queryRow->$id . '#' . $queryRow->$val;
								}
							}
							$object->source = $final_optionData;
						}
					} else {
						if ($row->option_data != '') {
							$object->source = explode(',', $row->option_data);
						}
					}

				}
				if ($row->is_anyoneDep == 1) {
					if ($row->custom_query != '' && $row->custom_query != null && $row->dependant_col != '' && $row->dependant_col != null) {

						array_push($AnyoneDependant, array('columnNo' => $i, 'query' => $row->custom_query, 'columns' => $row->dependant_col, 'columnType' => $row->column_type));
					}
				}
				array_push($columnTypes, $object);
				if ($j == 1) {
					$prefillData = $row->jsonCode;
					if ($code != 1) {
						$DB_table = $row->DB_table;
					}

				}
				$j++;
				$i++;
			}

			$prefillDataArray = array();
			//$where='';
			$TransactionDataFetch = $this->TransactionDataFetch($temp_id, $DB_table, $where, $allColumns, $positionArray, $positionKey, $is_edit, $where_condition);
			if ($TransactionDataFetch != false) {
				$prefillDataArray = $TransactionDataFetch;
			}

			if ($code == 1 || $TransactionDataFetch == false) {
				$prefillData = $prefillData;
				$prefillDataArray = json_decode($prefillData);
			}

			if ($is_edit) {
				if (is_array($prefillDataArray)) {
					foreach ($prefillDataArray as $pda) {
						if(property_exists($columnTypes[0],'source')){
							if (!in_array($pda[0], $columnTypes[0]->source)) {
								array_push($columnTypes[0]->source, $pda[0]);
							}
						}
					}
				}
			}

			$response['columnHeaders'] = $columnHeaders;
			$response['columnTypes'] = $columnTypes;
			$response['hideArra'] = $hideColumns;
			$response['rows'] = $prefillDataArray;
			$response['posistion_array'] = $positionArray;
			$response['keys'] = $positionKey;
			$response['dataSchema'] = $dataSchema;
			$response['formula'] = $formulaArray;
			$response['anyoneDepend'] = $AnyoneDependant;
			$response['show_total'] = $show_total;
			$response['props'] = $props;
			$response['fill_table'] = $fill_table;
			$response['route_pos_query'] = $route_pos_query;
			$response['route_pos'] = $route_pos;
			$response['status'] = 200;
		} else {
			$response['status'] = 201;
			$response['body'] = 'Required Parameter Missing';
		}
		echo json_encode($response);
	}

	function containsOnlyNull($input)
	{
		return empty(array_filter($input, function ($a) {
			return $a !== null;
		}));
	}

	function TE_SaveTransaction()
	{
		extract($_POST);
		//	$firm_id='Firm_1001'; //session
		//get HashKeys of Template
		$DefaultValuesArray = json_decode($DefaultValuesArray);
		$default = array();
		$whereArray = array();
		$queryResult = $this->MasterModel->_rawQuery('select option_data,column_type,id,default_value from TE_handson_template_column_master where template_id=' . $TemplateId . ' order by sequence asc');
		if ($queryResult->totalCount > 0) {
			$data = $queryResult->data;
			foreach ($data as $row) {
				if ($row->column_type == 'hidden') {
					if ($row->default_value != null && $row->default_value != '') {
						$default[$row->id] = $row->default_value;
					} else {
						$optionData = $row->option_data;
						$default[$row->id] = $DefaultValuesArray->$optionData;
					}
				}
			}
		}


		$required = '';
		$required_fields = $this->MasterModel->_select('TE_handson_template_master', array('id' => $TemplateId), 'required_fields');
		if ($required_fields->totalCount > 0) {
			$required = $required_fields->data->required_fields;
			$required = explode(',', $required);
		}

		$data1 = json_decode($reportData);
		$TemplateDBMapping = $this->MasterModel->_rawQuery('select *,
 (select formula from TE_handson_template_column_master htm where htm.id=hm.column_id) as formula,
 (select column_type from TE_handson_template_column_master htm where htm.id=hm.column_id) as column_type  
 from TE_Template_DB_mapping hm where template_id=' . $TemplateId);
		if ($TemplateDBMapping->totalCount > 0) {
			$resultArray = $TemplateDBMapping->data;
			$InsertBatcharray = array();
			$tableName = '';
			$req_cnt = 0;
			foreach ($data1 as $Handsondata) {

				if($required != '' && $required != null && is_array($required)){
					$is_required = $this->CheckArray($Handsondata,$required);
					if($is_required == false){
						continue;
					}
				}
				$isEmptyArray = $this->containsOnlyNull($Handsondata);
				if ($isEmptyArray != true) {
					$i = 0;
					$insertArray = array();
					foreach ($resultArray as $row) {

						$tableName = $row->DB_table;
//						if ($row->formula != "" && !is_null($row->formula)) {
//							$formula = $row->formula;
//							$exp = explode(",", $formula);
//							$Sum = 0;
//							foreach ($exp as $colNum) {
//								$Sum += $Handsondata[$colNum];
//							}
//							$insertArray[$row->DB_column] = $Sum;
//						} else {
						if ((int)$Handsondata[end($required)] < 0) {
							$req_cnt++;
						}
						if (array_key_exists($row->column_id, $default)) {
							$insertArray[$row->DB_column] = $default[$row->column_id];
							$whereArray[$row->DB_column] = $default[$row->column_id];
						} else {
							$value = $Handsondata[$i];
							if ($row->column_type == "dropdown") {
								if ($Handsondata[$i] != "") {
									$explodeData = explode('#', $Handsondata[$i]);
									$value = $explodeData[0];
								}
							}
							$insertArray[$row->DB_column] = $value;
						}

//						}

						$i++;
					}

					if (count($insertArray) > 0) {
						$insertArray = array_merge($insertArray);
						array_push($InsertBatcharray, $insertArray);
					}

				}
			}
			if (count($InsertBatcharray) > 0 && $tableName != '') {
				$insertQuery = false;
//				$ignore_cnt = 0;
				//delete before add
//				try {
//					$this->db->trans_start();
//					$delete = $this->db->delete($tableName, $whereArray);
//					foreach($InsertBatcharray as $row){
//						$this->db->ignore();
//						$insertQuery = $this->db->insert($tableName,$row);

//						$insert_query = $this->db->insert_string($tableName, $row);
//						$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
//						$insertQuery = $this->db->query($insert_query);
//
//						if($this->db->affected_rows()==0){
//							$ignore_cnt++;
//						}
//					}

//					$insertQuery = $this->db->insert_batch($tableName, $InsertBatcharray);

//					if ($this->db->trans_status() === FALSE) {
//						$this->db->trans_rollback();
//					} else {
//						$this->db->trans_commit();
//					}
//					$this->db->trans_complete();
//				} catch (Exception $ex) {
//					$this->db->trans_rollback();
//				}

				if ($req_cnt > 0) {
					$response['status'] = 201;
					$response['body'] = "Rate Should be greater Than 0";
					echo json_encode($response);
					exit();
				}
				$delete = $this->db->delete($tableName, $whereArray);
				$insertQuery = $this->db->insert_batch($tableName, $InsertBatcharray);

				if ($insertQuery == true) {
					$msg_body = 'Saved Successfully';
//					if($ignore_cnt > 0 ){
//						$msg_body = $ignore_cnt." items Were Duplicate so they were Ignored";
//					}

					$response['status'] = 200;
//					$response['count'] = $ignore_cnt;
					$response['body'] = $msg_body;
				} else {
					$response['status'] = 201;
					$response['body'] = "Something Went Wrong.";
				}
			} else {
				$response['status'] = 201;
				$response['body'] = "Something Went Wrong.";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Something Went Wrong.";
		}
		echo json_encode($response);
	}

	public function CheckArray($data,$required){
		$is_correct = true;
		foreach($required as $r){
			if($data[$r] == null && $data[$r] == ''){
				$is_correct = false;
			}
		}
		return $is_correct;
	}

	function TE_SavePrefillData()
	{
		$user_id = 1;
		$temp_id = $this->input->post('TemplateId');
		$reportData = $this->input->post('reportData');
		if (!is_null($temp_id) || !empty($temp_id)) {
			$data = array(
				"template_id" => $temp_id,
				"jsonCode" => $reportData,
				"user_id" => $user_id,
			);
			//check Data is alredy available
			$CheckAvalabilty = $this->MasterModel->_rawQuery('select id from TE_handson_prefill_table where template_id=' . $temp_id);
			$operation = false;
			if ($CheckAvalabilty->totalCount > 0) {
				$operation = $this->db->update('TE_handson_prefill_table', $data, array('template_id' => $temp_id));
			} else {
				$operation = $this->db->insert('TE_handson_prefill_table', $data);
			}
			if ($operation == true) {
				$response['status'] = 200;
				$response['body'] = "Saved Successfully.";
			} else {
				$response['status'] = 201;
				$response['body'] = "Failed to Save.";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Parameter Missing!";
		}
		echo json_encode($response);
	}

	function TE_getAllHashKeys()
	{
		$query = $this->db->query('select hash_key from TE_hashKeys');
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			$response['status'] = 200;
			$response['data'] = $result;
		} else {
			$response['status'] = 201;
		}
		echo json_encode($response);
	}

	function RemoveRowFromDBFunc()
	{
		$id = $this->input->post('id');
		$template_id = $this->input->post('template_id');
		if (!is_null($id) || !is_null($template_id)) {
			$where = array(
				'id' => $id,
				'template_id' => $template_id,
			);
			$where2 = array(
				'column_id' => $id,
				'template_id' => $template_id,
			);

			try {
				$this->db->trans_start();
				$status = 201;
				$body = 'Failed to Delete';
				$delete = $this->db->delete('TE_handson_template_column_master', $where);
				if ($delete == true) {
					$delete2 = $this->db->delete('TE_Template_DB_mapping', $where2);
				}

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$status = 201;
					$body = 'something went wrong';
				} else {
					$this->db->trans_commit();
					$status = 200;
					$body = 'Deleted Successfully';
				}
				$this->db->trans_complete();
			} catch (Exception $exc) {
				$status = 201;
				$body = 'something went wrong';
				$this->db->trans_rollback();
				$this->db->trans_complete();
			}
		} else {
			$status = 201;
			$body = 'Parameter Missing!';
		}
		$response['status'] = $status;
		$response['body'] = $body;
		echo json_encode($response);
	}

	function TE_deactiveTemplate()
	{
		$template_id = $this->input->post('template_id');
		$status = $this->input->post('status');
		if ($status == 1) {
			$status = 0;
		} else {
			$status = 1;
		}
		if (!is_null($template_id)) {
			$update = $this->db->update('TE_handson_template_master', array('status' => $status), array('id' => $template_id));
			if ($update == true) {
				$status = 200;
				$body = 'Updated Successfully';
			} else {
				$status = 201;
				$body = 'Fail to Update';
			}
		} else {
			$status = 201;
			$body = 'Parameter Missing!';
		}
		$response['status'] = $status;
		$response['body'] = $body;
		echo json_encode($response);
	}
}

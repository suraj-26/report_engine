<?php

require_once 'HexaController.php';

use Dompdf\Dompdf;

/**
 * @property  MasterModel MasterModel
 */
class DatatableEditorController extends HexaController
{


	/**
	 * DatatableEditorController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model("MasterModel");
	}

	public function datatableTabSection()
	{
		$this->load->view('admin/patients/view_tab_patients', array("title" => "Datatable"));
	}

	function getAllTableNames()
	{
		$tables = $this->db->list_tables();
		$option = "<option value='' selected disabled>Select Table</option>";
		foreach ($tables as $table) {
			$option .= "<option value='" . $table . "'>" . $table . "</option>";
		}
		$response['option'] = $option;
		echo json_encode($response);
	}

	function getAllColumns()
	{
		$TableName = $this->input->post('TableName');
		$fields = $this->db->field_data($TableName);
		$option = "<option value='' disabled>Select Column</option>";
		foreach ($fields as $field) {
			$column_name = $field->name;
			$option .= "<option value='" . $column_name . "'>" . $column_name . "</option>";
		}
		$response['status'] = 200;
		$response['option'] = $option;
		echo json_encode($response);


	}


	function saveDataTableEditor()
	{

		$header = $this->is_parameter(array("data"));

		if ($header->status) {

			$data = $header->param->data;
			$data = json_decode($data);
			// print_r($data);exit();
			$query_master_id = $data->query_master_id;
			$filterArray = null;
			$actionArray = null;
			$whereArray = null;
			if (property_exists($data, 'filterData')) {
				$filterArray = json_decode($data->filterData);
			}
			if (property_exists($data, 'actionData')) {
				$actionArray = json_decode($data->actionData);
			}
			if (property_exists($data, 'whereData')) {
				$whereArray = json_decode($data->whereData);
			}
			$customWhereCondition = $data->customWhereCondition;
			$table = $data->queryTable;
			$select = implode(',', $data->queryTableSelectColumn);
			$search = implode(',', $data->queryTableSearchColumn);
			$order = implode(',', $data->queryTableOrderColumn);
			$direction = $data->queryTableOrderColumnDirection;
			$type = $data->dataTableSyncType;
			$whereCondition = "";
			if (!is_null($data->queryTableWhereCondition) && $data->queryTableWhereCondition !== "") {
				$whereCondition = $data->queryTableWhereCondition;
			}

			$filterStringArray = array();
			if (!is_null($filterArray)) {
				foreach ($filterArray as $filterObject) {
					$filterString = "";
					if (property_exists($filterObject, "FilterTableColumn")) {
						$filterString .= "FilterTableColumn:" . $filterObject->FilterTableColumn . "|";
					}
					if (property_exists($filterObject, "filterValueType")) {
						$filterString .= "filterValueType:" . $filterObject->filterValueType . "|";
					}
					if (property_exists($filterObject, "filterQueryTable")) {
						$filterString .= "filterQueryTable:" . $filterObject->filterQueryTable . "|";
					}
					if (property_exists($filterObject, "filterKey")) {
						$filterString .= "filterKey:" . $filterObject->filterKey . "|";
					}
					if (property_exists($filterObject, "filterKeyValue")) {
						$filterString .= "filterValue:" . $filterObject->filterKeyValue . "|";
					}
					if (property_exists($filterObject, "filterQueryCondition")) {
						$filterString .= "filterQueryCondition:" . $filterObject->filterQueryCondition . "|";
					}
					if (property_exists($filterObject, "filterStaticValue")) {
						$filterString .= "filterStaticValue:" . $filterObject->filterStaticValue . "|";
					}
					if (property_exists($filterObject, "customfilterValue")) {
						$filterString .= "customfilterValue:" . $filterObject->customfilterValue;
					}
					array_push($filterStringArray, $filterString);
				}
			}

			$actionStringArray = array();
			if (!is_null($actionArray)) {
				foreach ($actionArray as $actionObject) {
					$actionString = "";
					if (property_exists($actionObject, "actionButtonPrimary")) {
						$actionString .= "actionButtonPrimary:" . $actionObject->actionButtonPrimary . "|";
					}
					if (property_exists($actionObject, "actionButtonIcon")) {
						$actionString .= "actionButtonIcon:" . $actionObject->actionButtonIcon . "|";
					}
					if (property_exists($actionObject, "actionButtonType")) {
						$actionString .= "actionButtonType:" . $actionObject->actionButtonType . "|";
					}
					if (property_exists($actionObject, "actionButtonRedirectTemplate")) {
						$actionString .= "actionButtonRedirectTemplate:" . $actionObject->actionButtonRedirectTemplate . "|";
					}
					if (property_exists($actionObject, "actionButtonRedirectQueryParam")) {
						$actionString .= "actionButtonRedirectQueryParam:" . $actionObject->actionButtonRedirectQueryParam . "|";
					}
					if (property_exists($actionObject, "actionButtonExecutionQuery")) {
						$actionString .= "actionButtonExecutionQuery:" . $actionObject->actionButtonExecutionQuery . "|";
					}
					if (property_exists($actionObject, "actionButtonRedirectPage")) {
						$actionString .= "actionButtonRedirectPage:" . $actionObject->actionButtonRedirectPage . "|";
					}
					if (property_exists($actionObject, "actionButtonModalQueryParam")) {
						$actionString .= "actionButtonModalQueryParam:" . $actionObject->actionButtonModalQueryParam . "|";
					}
					if (property_exists($actionObject, "actionButtonModalTemplate")) {
						$actionString .= "actionButtonModalTemplate:" . $actionObject->actionButtonModalTemplate . "|";
					}


					array_push($actionStringArray, $actionString);
				}
			}
			$whereStringArray = array();
			if (!is_null($whereArray)) {
				foreach ($whereArray as $itemObject) {
					$whereString = "";
					if (property_exists($itemObject, "WhereTableColumn")) {
						$whereString .= "whereTableColumn:" . $itemObject->WhereTableColumn . "|";
					}
					if (property_exists($itemObject, "WhereColumnValue")) {
						$whereString .= "WhereColumnValue:" . $itemObject->WhereColumnValue . "|";
					}
					array_push($whereStringArray, $whereString);
				}
			}


			$filterColumns = implode(",", $filterStringArray);
			$actionColumns = implode(",", $actionStringArray);
			$whereColumns = implode(",", $whereStringArray);
			if (!is_null($customWhereCondition) && $customWhereCondition != "") {
				$cusWhereArray = explode(",", $customWhereCondition);
				foreach ($cusWhereArray as $whereOption) {
					// $values = explode("=", $whereOption);
					$whereColumns .= ",customWhereCondition:" . $whereOption;
					// if (count($values) == 2) {
					// 	$whereColumns .= ",whereTableColumn:" . $values[0] . "|WhereColumnValue:" . $values[1];
					// }
				}
			}
			$dataTableDetails = array(
				"elementID" => $data->element_id,
				"sectionID" => $data->section_id,
				"table_name" => $table,
				"select_column" => $select,
				"search_column" => $search,
				"order_column" => $order,
				"order_direction" => $direction,
				"table_type" => $type,
				"where_condition" => $whereColumns,
				"filter_columns" => $filterColumns,
				"action_columns" => $actionColumns,
				"status" => 1,
			);

			// print_r($dataTableDetails);exit();
			if ($query_master_id != null) {
				$where = array('id' => $query_master_id);
				$resultObject = $this->MasterModel->_update("TE_datatable_query_master", $dataTableDetails, $where);
			} else {
				$resultObject = $this->MasterModel->_insert("TE_datatable_query_master", $dataTableDetails);
			}

			if ($resultObject->status) {
				$response["status"] = 200;
				$response["body"] = "Add Successfully";
			} else {
				$response["status"] = 201;
				$response["body"] = "failed";
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Missing Parameter";
		}
		echo json_encode($response);
	}


	public function getDataTableTemplate()
	{
		$templateID = $this->input->post("templateID");
		$rowID = $this->input->post("rowID");
		$columnID = $this->input->post("columnID");
		$columnObjectSize = $this->input->post("columnObjectSize");
		$resultObject = $this->MasterModel->_select("TE_template_form_structure", array("status" => 1, "id" => $templateID));
		$action = 'Action';
		if ($resultObject->totalCount > 0) {
			$template_structure = $resultObject->data->template_structure;
			$template_structure = json_decode($template_structure);
			$headerColumns = array();
			$data = array();
			$filters = array();
			if (property_exists($template_structure, "rows")) {
				foreach ($template_structure->rows as $row) {
					if (property_exists($row, "cols")) {
						if ($row->id == $rowID) {
							foreach ($row->cols as $col) {
								if ($col->id == $columnID) {
									if (property_exists($col, "element")) {
										if (!is_null($col->element)) {
											$element = $col->element;
											// print_r($element);exit();
											$table = $element->queryTable;
											if ($element->queryTableActionCondition != "") {
												if (count($element->queryTableActionCondition) > 0) {
													if (property_exists($element, 'actionButtonName')) {
														$action = $element->actionButtonName;
													}
												}
											}
											// DataTable Headers
											$headerColumns = $this->getDataTableHeader($element);

											//DataTable Fileters Options
											$filters = $this->getDataTableFiltersOptions($element);
										}
									}
								}
							}
						}

					}
				}
			}

			$response["status"] = 200;
			$response["header"] = $headerColumns;
			$response["filterOptions"] = $filters;
			$response["actionColumn"] = $action;

		} else {
			$response["status"] = 201;
			$response["body"] = "Not Found";
		}
		echo json_encode($response);

	}

	function getDataTableHeader($element)
	{
//		print_r($element);exit();
		$headerColumns = array();
		$selectColumn = $element->rawQueryTableSelectColumn;
		foreach ($selectColumn as $column) {
			$position = strpos($column, " as ");
			if ($position !== false) {
				$column = str_replace("'", "", substr($column, ($position + 4)));
			}
			if (substr($column, 0, 1) !== '_') {
				array_push($headerColumns, $column);
			}
		}
		if ($element->rawQueryTableFileColumn != "") {
			if (count($element->rawQueryTableFileColumn) > 0) {
				array_push($headerColumns, 'Doc');
			}
		}
		if ($element->queryTableActionCondition != "") {
			if (count($element->queryTableActionCondition) > 0) {
				$action = 'Action';
				if (property_exists($element, 'actionButtonName')) {
					$action = $element->actionButtonName;
				}
				array_push($headerColumns, $action);
			}
		}

		return $headerColumns;
	}

	function getDataTableFiltersOptions($element)
	{
		$filters = array();
		if ($element->queryTableFilterCondition != "") {
			foreach ($element->queryTableFilterCondition as $index => $filter) {
				$filterOptionArray = array();
				if ((int)$filter->type == 3) {
					if ($filter->query != "") {
						$resultObject = $this->MasterModel->_rawQuery($filter->query);

						if ($resultObject->totalCount > 0) {
							$filterOptionArray = $resultObject->data;
						}

					}
					if ($filter->staticOption != "") {
						foreach ($filter->staticOption as $staticOp) {
							array_push($filterOptionArray, array('id' => $staticOp->id, 'text' => $staticOp->text));
						}
					}
				}
				array_unshift($filterOptionArray, array("id" => -1, "text" => ""));
				$label = '';
				if (property_exists($filter, 'label')) {
					$label = $filter->label;
				}
				array_push($filters, array("type" => $filter->type, "label" => $label, "column" => $filter->column, "options" => $filterOptionArray
				));
				// $filters[$index]=$filterOptionArray;
			}
		}
		return $filters;
	}

	function getDynamicTableData()
	{
		$templateID = $this->input->post("templateID");
		$rowID = $this->input->post("rowID");
		$columnID = $this->input->post("columnID");
		$queryParameter_hidden = $this->input->post("queryParameter");

		$sessionData = $this->session->user_session;
		$resultObject = $this->MasterModel->_select("TE_template_form_structure", array("status" => 1, "id" => $templateID));

		if ($resultObject->totalCount > 0) {
			$template_structure = $resultObject->data->template_structure;
			$template_structure = json_decode($template_structure);
			$headerColumns = array();
			$data = array();
			$filterCount = 0;
			$totalCount = 0;
			$last_query = array();
			if (property_exists($template_structure, "rows")) {
				foreach ($template_structure->rows as $row) {
					if ($row->id == $rowID) {
						foreach ($row->cols as $col) {
							if ($col->id == $columnID) {

								if (!is_null($col->element)) {
									$element = $col->element;
									// print_r($element);exit();
									$table = $element->queryTable . " q";
									$selectColumn = $element->rawQueryTableSelectColumn;
									$searchColumn = $element->rawQueryTableSearchColumn;
									$orderColumn = $element->rawQueryTableOrderColumn;
									$updateSearchColumn = array();
									foreach ($selectColumn as $index => $column) {
										$position = strpos($column, " as ");
										if ($position !== false) {
											array_push($updateSearchColumn, trim(str_replace("'", "", substr($column, ($position + 4)))));
										} else {
											$selectColumn[$index] = " q." . $column;
											array_push($updateSearchColumn, trim($column));
										}
									}
									//where condition
									$where = array();
									$where = $this->getDataTableWhereCondition($element->queryTableWhereCondition, $queryParameter_hidden);
									// filter
									$filterWhere = array();
									$filterOptions = $this->input->post("filterOptions");

									if ($filterOptions != null && $filterOptions != "") {
										if (count($filterOptions) > 0) {
											for ($i = 0; $i < count($filterOptions); $i++) {
												if ($filterOptions[$i]['key'] != "") {
													if ($filterOptions[$i]['val'] != "" && $filterOptions[$i]['val'] != "-1" && $filterOptions[$i]['val'] != -1 && $filterOptions[$i]['val'] != "0000-00-00 00:00:00") {
//														print_r($filterOptions);exit();
//														$where[$filterOptions[$i]['key']]=$filterOptions[$i]['val'];

														if (strpos($filterOptions[$i]['key'], '?') !== false) {
															$whereCon = str_replace('?', '"' . $filterOptions[$i]['val'] . '"', $filterOptions[$i]['key']);

														} else {
															$whereCon = '' . $filterOptions[$i]['key'] . '="' . $filterOptions[$i]['val'] . '"';

															array_push($filterWhere, array($filterOptions[$i]['key'] => $filterOptions[$i]['val']));
														}

//														array_push($filterWhere,$whereCon);
//														array_push($where,$whereCon);
													}
												}
											}
										}
									}


//									 print_r($filterWhere);exit();
									//order column
									$orderDirectionArray = array();
									$columnOrder = array();
									if ($element->rawQueryTableOrderColumn != "") {
										foreach ($element->rawQueryTableOrderColumn as $orderRow) {
											$orderDirectionArray[$orderRow[0]] = $orderRow[1];
											array_push($columnOrder, $orderRow[0]);
										}
									}
									$groupArray = array();
									if (property_exists($element, 'rawQueryTableGroupColumn')) {
										if ($element->rawQueryTableGroupColumn != "") {
											$groupArray = $element->rawQueryTableGroupColumn;
										}
									}


									//action column
									// $actionButtons=$this->getDataTableActionData($element->queryTableActionCondition);
									// print_r($actionButtons);exit();
//									print_r($selectColumn);exit();
									//data query
									$metaData = $this->MasterModel->getRows($_POST, $where, array_unique($selectColumn), $table, $searchColumn, array(),
										$orderDirectionArray, $groupArray, array(), $filterWhere, array(), null);
									$last_query["dataQuery"] = $this->db->last_query();
									//filter count
									$filterCount = $this->MasterModel->countFiltered($_POST, $table, $where, $searchColumn, array(), $orderDirectionArray, array(1), $filterWhere, array_unique($selectColumn));
									$last_query["FilterCount"] = $this->db->last_query();
									//total count
									$totalCount = $this->MasterModel->countAll($table, $where);
									$last_query["totalCount"] = $this->db->last_query();
//									print_r($metaData);exit();
									foreach ($metaData as $rowData) {
										$tempData = array();
										// foreach ($updateSearchColumn as $columns) {
										// if(property_exists($rowData,$columns))
										// {

										// $tempData[$columns]=$rowData->{$columns};
										// array_push($tempData,array($columns=> $rowData->{$columns}));
										// }
										// }
										if ($element->rawQueryTableFileColumn != "") {
											if (count($element->rawQueryTableFileColumn) > 0) {
												foreach ($element->rawQueryTableFileColumn as $fileRow) {
													$filecol = '_' . $fileRow;
													if($rowData->$filecol!=null && $rowData->$filecol!="") {
														$fileaction = '<a href="' . base_url() . $rowData->$filecol . '" download title="' . $rowData->$filecol . '"><i class="fa fa-download"></i></a>';
														$rowData->Doc = $fileaction;
													}
													else{
														$rowData->Doc = '';
													}
												}
											}
										}
										if ($element->queryTableActionCondition != "") {
											if (count($element->queryTableActionCondition) > 0) {
												$action = 'Action';
												if (property_exists($element, 'actionButtonName')) {
													$action = $element->actionButtonName;
												}
												$rowData->$action = json_encode($element->queryTableActionCondition);
											}
										}
										// array_push($tempData, $rowData);
										$data[] = $rowData;
									}

								}

							}
						}
					}
				}
			}

			$response = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $data,
				"last_query" => $last_query
			);
		} else {
			$response = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => array()
			);
		}
		echo json_encode($response);
	}

	function getDataTableWhereCondition($queryTableWhereCondition, $queryParameter_hidden)
	{
		$sessionData = $this->session->user_session;
		$where = array();
		if ($queryTableWhereCondition != "") {
			foreach ($queryTableWhereCondition as $whereRow) {
				$type = $whereRow->columnValue->type;
				$typeValue = $whereRow->columnValue->typeValue;
				if ($type == 1) // get column value from session
				{
					if (isset($sessionData)) {
						if (!is_array($typeValue)) {
							if (!empty($sessionData->$typeValue)) {
								$resultArray=$this->getTypeOneWhereCondition($whereRow->columnValue,$whereRow->columnName,$sessionData,$typeValue);
								$where[$resultArray["key"]]=$resultArray["value"];
							}
						}else{
							$resultArray=$this->getTypeOneWhereCondition($whereRow->columnValue,$whereRow->columnName,$sessionData,$typeValue);
							$where[$resultArray["key"]]=$resultArray["value"];
						}
					}
				} else if ($type == 3) //get value from query parameter
				{
//					print_r(str_replace("#","",$typeValue));exit();
					if (!empty($queryParameter_hidden)) {
						foreach ($queryParameter_hidden as $qRow) {
							if (str_replace("#", "", $typeValue) == str_replace("#", "", $qRow['key'])) {
//								 $value=str_replace("#","",$whereRow->columnName);
								$where[$whereRow->columnName] = $qRow['value'];
							}
						}
					}
				} else if ($type == 4) {
					$value = $this->replaceStringWithValue($typeValue, $queryParameter_hidden);
					$key = $this->replaceStringWithValue($whereRow->columnName, $queryParameter_hidden);
					$where[$key] = $value;
				} else if ($type == 5) {
					foreach ($queryParameter_hidden as $qRow) {
						if (strpos($whereRow->columnName, $qRow['key']) !== false) {
							$whereRow->columnName = str_replace($qRow['key'], $qRow['value'], $whereRow->columnName);
						}
					}
					$where['(' . $whereRow->columnName . ')<>'] = 0;
				} else {
					$where[$whereRow->columnName] = $typeValue;
				}
			}
		}
		return $where;
	}

	function getTypeOneWhereCondition($columnValue,$columnName,$sessionData,$typeValue){
		$where=array();
		if (property_exists($columnValue, 'is_query')) {
			$is_query = $columnValue->is_query;
			if ($is_query == 0) {
				$where["key"]= $columnName ;
				$where["value"]=$sessionData->$typeValue;
			} else {
				if (strpos($columnName, '@') !== false) {
					if (is_array($typeValue)) {
						foreach ($typeValue as $item) {
							$value=$item->value;
							$sessionValue=-1;
							if(!is_null($sessionData->$value)){
								$sessionValue=$sessionData->$value;
							}
							$columnName = str_replace($item->key, $sessionValue, $columnName );
						}
						$where["key"]='(' . $columnName . ')<>';
						$where["value"]=0;
					}
				} else {
					$columnName = str_replace("?", $sessionData->$typeValue, $columnName);
					$where["key"]='(' . $columnName . ')<>';
					$where["value"]=0;
				}
			}
		} else {
			$where["key"]= $columnName ;
			$where["value"]=$sessionData->$typeValue;
		}
		return $where;
	}

	function replaceStringWithValue($customWhereCondition, $queryParameter_hidden)
	{
		if ($customWhereCondition != "") {
//			$queryParameterString = base64_decode($queryParameter_hidden);
//			$queryStringArray = (array)json_decode($queryParameterString);
			foreach ($queryParameter_hidden as $key => $value) {
				$customWhereCondition = str_replace($value['key'], $value['value'], $customWhereCondition);
			}
		}
		return $customWhereCondition;
	}

	public function customQueryExecutor()
	{
		if (!is_null($this->input->post('query')) && !is_null($this->input->post('param'))) {
			$query = $this->input->post('query');
			$param = $this->input->post('param');
			$resultObject = $this->MasterModel->_rawQuery($query, array($param), 2);
			if ($resultObject->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = "Changes Saved";
			} else {
				$response['status'] = 201;
				$response['body'] = "Changes Not Saved";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Something went wrong";
		}
		echo json_encode($response);
	}

	public function getTemplateFormIdByName()
	{
		if (!is_null($this->input->post('templateName'))) {
			$templateName = $this->input->post('templateName');
			$resultObject = $this->MasterModel->_select('TE_template_form_structure', array("template_name"), array('id'));
			if ($resultObject->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = $resultObject->data->id;
			} else {
				$response['status'] = 201;
				$response['body'] = "Changes Not Saved";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Something went wrong";
		}
		echo json_encode($response);
	}

	public function getAllTemplateListOptions()
	{
//		$resultObject=$this->MasterModel->_select('TE_template_form_structure',array('configuration'=>1),array('id','template_name'),false);
		$resultObject = $this->MasterModel->_rawQuery('select id,template_name from TE_template_form_structure where configuration=1 or configuration=3');
		if ($resultObject->totalCount > 0) {
			$options = '<option value="-1">Select Template </option>';
			foreach ($resultObject->data as $row) {
				$options .= '<option value="' . $row->id . '">' . $row->template_name . '</option>';
			}
			$response['status'] = 200;
			$response['body'] = $options;
		} else {
			$response['status'] = 201;
			$response['body'] = "";
		}
		echo json_encode($response);
	}

	public function getTemplateQueryParam()
	{
		if (!is_null($this->input->post('templateId'))) {
			$id = $this->input->post('templateId');
			$resultObject = $this->MasterModel->_select('TE_template_form_structure', array('id' => $id), array('template_name', 'query_param'), true);
			if ($resultObject->totalCount > 0) {
				$response['status'] = 200;
				$response['body'] = $resultObject->data;
			} else {
				$response['status'] = 201;
				$response['body'] = "";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	public function getEditFormData()
	{
		if (!is_null($this->input->post('id')) && !is_null($this->input->post('templateId'))) {
			$id = $this->input->post('id');
			$template_id = $this->input->post('templateId');
			$queryEncrypted = $this->input->post('query');
			$fileTable = $this->input->post('fileTable');
			$query = base64_decode($queryEncrypted);
			$resultObject = $this->MasterModel->_rawQuery($query, array($id));

			if ($resultObject->totalCount > 0) {
				$resultData = $resultObject->data[0];
				$templateData = $this->MasterModel->_select('TE_template_form_structure', array('id' => $template_id), '*', true);
				if ($templateData->totalCount > 0) {
					$td = $templateData->data;
					$data = $td->template_structure;
					$data = json_decode($data);
					$dataArray = array();
					if (property_exists($data->operation, 'transaction')) {
						foreach ($data->operation->transaction as $op) {
							if ($op->method == 1) {
								foreach ($op->column as $oprow) {
									array_push($dataArray, $oprow);
								}
							}
						}
					}

					$formArray = array();
					$fileArray = array();
					if (count($dataArray) > 0) {
						foreach ($resultData as $rKey => $rRow) {
							foreach ($dataArray as $dkey => $dRow) {
								if (property_exists($dRow, $rKey)) {
									$formArray[$dRow->$rKey] = $rRow;
									if (preg_replace('/[0-9]+/', '', $dRow->$rKey) == 'file') {
										if ($rRow != "" && $rRow != null) {
											$fileName = explode('/', $rRow);
											if (count($fileName) > 1) {
												$formArray[$dRow->$rKey] = $fileName[1];
											}
										}
										array_push($fileArray, array('ele' => $dRow->$rKey, 'column' => $rKey, 'table' => $fileTable));
									}
//									else if(preg_replace('/[0-9]+/', '', $dRow->$rKey)=='queryDropdown')
//									{
//										print_r($dRow->$rKey);exit();
//									}

								}
							}
						}
					}
					$QueryArray = array();
					if (count($data->rows) > 0 && !empty($data->rows)) {
						foreach ($data->rows as $row) {
							foreach ($row->cols as $col) {
								if ($col->element != null) {
									if (property_exists($col->element, 'type')) {
										if ($col->element->type == 'queryDropdown') {
											$optionsHtml = '<option selected>Select One</option>';
											if ($col->element->queryDependentTable != null && $col->element->queryDependentTable != '') {
												$Querywhere = array();
												$Qwhere = $col->element->queryDependentWhere;
												for ($i = 0; $i < count($Qwhere); $i++) {
													if ($Qwhere[$i][1] == 2) {
														$Querywhere[$Qwhere[$i][0]] = 1;//$this->session->user_session->$Qwhere[$i][2];
													} else {
														$Querywhere[$Qwhere[$i][0]] = $Qwhere[$i][2];
													}
												}
												$options = $this->MasterModel->_select($col->element->queryDependentTable, $Querywhere, '*', false);
												if ($options->totalCount > 0) {
													foreach ($options->data as $o) {
														$optionsHtml .= '<option value="' . $o->id . '">' . $o->name . '</option>';
													}
												}
											}
											$query_id = $col->element->type . "" . $col->id;
											array_push($QueryArray, array('id' => $query_id, 'option' => $optionsHtml));
										}
									}
								}
							}
						}
					}
					$response['status'] = 200;
					$response['body'] = $formArray;
					$response['option'] = $QueryArray;
					$response['fileArray'] = $fileArray;
				} else {
					$response['status'] = 201;
					$response['body'] = "No Such Configuration Found";
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

	function getTemplateElementData($template_id, $rowID, $columnID)
	{
		$element = "";
		$resultObject = $this->MasterModel->_select('TE_template_form_structure', array('id' => $template_id), '*', true);
		if ($resultObject->totalCount > 0) {
			$template_structure = $resultObject->data->template_structure;
			$template_structure = json_decode($template_structure);
			if (property_exists($template_structure, "rows")) {
				foreach ($template_structure->rows as $row) {
					if ($row->id == $rowID) {
						foreach ($row->cols as $col) {
							if ($col->id == $columnID) {
								if (!is_null($col->element)) {
									$element = $col->element;
								}
							}
						}
					}
				}
			}
		}
		return $element;
	}

	public function get_data()
	{
		$search = $this->input->post('searchTerm');
		$data = (object)$this->input->post_get('data');
//		$data=base64_decode($data);
//		$data=json_decode($data);

		$options = array();
//		if ($search != "") {
			$element = $this->getTemplateElementData($data->templateID, $data->rowID, $data->columnID);
			if ($element != "") {
				if ($element->method == 1) {
					$result = $this->getResultOfQueryDropdown($element, $data, $search);
					if ($result->totalCount > 0) {
						foreach ($result->data as $arr) {
							$val = $arr->id;
							$options[] = array("id" => $val, "text" => $arr->text);
						}
					}
				} else {
					$query = $element->normal_query;
					$query = str_replace("#like", "%" . $search . "%", $query);
					$result = $this->MasterModel->_rawQuery($query);
					if ($result->totalCount > 0) {
						foreach ($result->data as $arr) {
							$val = $arr->id;
							$options[] = array("id" => $val, "text" => $arr->text);
						}
					}
				}
			}
//		}
		echo json_encode($options);
	}

	function getResultOfQueryDropdown($element, $data, $search = "", $selectArr = array(), $value = null)
	{
//		print_r($element);exit();
		$tableName = $element->queryDependentTable;
		$optionValue = $element->queryDependentoptionValue . ' as id';
		$optionName = $element->queryDependentoptionName . ' as text';
//		$whereCondition=$element->queryDependentWhere;
		$selectName = $element->queryDependentoptionValue;
		if($search != ''){
			$like = array($element->queryDependentoptionName => $search);
		}else{
			$like = null;
		}
		$select = array();
		if (count($selectArr) > 0) {
			$select = $selectArr;
		}
		array_push($select, $optionValue);
		array_push($select, $optionName);
		$where = array();
		if (property_exists($data, 'whereArray')) {
			$whereCondition = $data->whereArray;
			if (is_array($whereCondition) && count($whereCondition) > 0) {
				foreach ($whereCondition as $whereValues) {
					if (is_array($whereValues)) {
						if ($whereValues['value'] != "") {
							$where[$whereValues['name']] = $whereValues['value'];
						}
					} else {
						if ($whereValues->value != "") {
							$where[$whereValues->name] = $whereValues->value;
						}
					}
				}
			}
		}

		if (property_exists($data, 'dependOnValue')) {
			if ($data->dependOnValue != "") {
				$where[$data->valueCheck] = $data->dependOnValue;
			}
		}
		if (!is_null($value)) {
			if ($value != "") {
				$where[$selectName] = $value;
			}
		}

		if (property_exists($data, 'imDependOnAny')) {
			if (is_array($data->imDependOnAny)) {
				foreach ($data->imDependOnAny as $imDepKey => $imDepRow) {
					if ($imDepRow['Value'] != null && $imDepRow['Value'] != "" && $imDepRow['Value'] != "-1" && $imDepRow['Value'] != "Select One") {
						$where[$imDepRow['Column']] = $imDepRow['Value'];
					}
				}
			}
		}
		$result = $this->MasterModel->_select($tableName, $where, $select, false, null, $like);
		return $result;
	}

	function getOnchangeDependantData()
	{
		$data = $this->input->post_get('data');
		$value = $this->input->post_get('value');
		$data = json_decode($data);
		$DependValueArray = array();
		$element = $this->getTemplateElementData($data->templateID, $data->rowID, $data->columnID);

		if ($element != "") {
			if ($element->method == 1) {
				$dependData = $data->anyOneDepend;
				$select = array();
				foreach ($dependData as $dRow) {
					array_push($select, $dRow->Value);
				}

				$result = $this->getResultOfQueryDropdown($element, $data, "", $select, $value);

				if ($result->totalCount > 0) {
					$rData = $result->data;
					foreach ($rData as $rRow) {

						foreach ($dependData as $dRow) {
							if (property_exists($rRow, $dRow->Value)) {
								$dValue = $dRow->Value;
								array_push($DependValueArray, array("id" => $dRow->Id, "value" => $rRow->$dValue));
							}
						}
					}
				}
			}
			$response['status'] = 200;
			$response['data'] = $DependValueArray;
		} else {
			$response['status'] = 201;
		}
		echo json_encode($response);
	}

	public function getSearchInputData()
	{
		$templateID = $this->input->post("templateID");
		$rowID = $this->input->post("rowID");
		$columnID = $this->input->post("columnID");
		$searchValue = $this->input->post("searchValue");
		$element = $this->getTemplateElementData($templateID, $rowID, $columnID);
		if ($element != "") {
			$inputQueryEle = $element->inputSearch_query;
			$inputQueryEle = json_decode($inputQueryEle);
			if ($inputQueryEle[0] != "") {
				$headerColumns = array();
				$selectColumn = $inputQueryEle[0]->select;
				$headerColumns = $this->inputSearchDataTableSelect($selectColumn);
				$response['status'] = 200;
				$response['data'] = $headerColumns;
			} else {
				$response['status'] = 201;
				$response['data'] = "No Data Found";
			}
		} else {
			$response['status'] = 201;
			$response['data'] = "No Data Found";
		}
		echo json_encode($response);
	}

	function inputSearchDataTableSelect($selectColumn)
	{
		$headerColumns = array();
		foreach ($selectColumn as $column) {
			$position = strpos($column, " as ");
			if ($position !== false) {
				$column = str_replace("'", "", substr($column, ($position + 4)));
			}
			if (substr($column, 0, 1) !== '_') {
				array_push($headerColumns, $column);
			}
		}
		array_push($headerColumns, 'Action');
		return $headerColumns;
	}

	function inputSearchDataTableWhere($queryTableWhereCondition, $searchValue)
	{
		$where = array();
		if ($queryTableWhereCondition != "") {
			foreach ($queryTableWhereCondition as $whereRow) {
				$type = $whereRow->columnValue->type;
				$typeValue = $whereRow->columnValue->typeValue;
				if ($type == 1) // get column value from session
				{
					if (isset($sessionData)) {
						$where[$whereRow->columnName] = $sessionData->$typeValue;
					}
				} else if ($type == 3) //get value from query parameter
				{
					// $value=str_replace("#","$",$typeValue);
					// $where[$whereRow->columnName] = $value;
				} else if ($type == 4) {
//					$value=$this->replaceStringWithValue($typeValue,$queryParameter_hidden);
//					$where[$whereRow->columnName] = $value;
				} else if ($type == 5) {
					$where[$whereRow->columnName] = $searchValue;
				} else {
					$where[$whereRow->columnName] = $typeValue;
				}
			}
		}
		return $where;
	}

	public function getInputSearchDataTableData()
	{
		$templateID = $this->input->post("templateID");
		$rowID = $this->input->post("rowID");
		$columnID = $this->input->post("columnID");
		$searchValue = $this->input->post("searchValue");

		$sessionData = $this->session->user_session;
		$resultObject = $this->MasterModel->_select("TE_template_form_structure", array("status" => 1, "id" => $templateID));

		$element = $this->getTemplateElementData($templateID, $rowID, $columnID);
		if ($element != "") {

			$headerColumns = array();
			$data = array();
			$filterCount = 0;
			$totalCount = 0;
			$inputQueryEle = $element->inputSearch_query;
			$inputQueryEle = json_decode($inputQueryEle);

			if ($inputQueryEle[0] != "") {
				$table = $inputQueryEle[0]->table . " q";
				$selectColumn = $inputQueryEle[0]->select;
				$searchColumn = $inputQueryEle[0]->searchColumn;
				$orderColumn = $inputQueryEle[0]->orderColumn;

				//search column
				$updateSearchColumn = array();
				foreach ($selectColumn as $index => $column) {

					$position = strpos($column, " as ");
					if ($position !== false) {
						$col = trim(str_replace("'", "", substr($column, ($position + 4))));
						$key = trim(str_replace(substr($column, (0 + $position)), "", $column));
						$updateSearchColumn[$key] = $col;

					} else {
						$selectColumn[$index] = " q." . $column;
						$updateSearchColumn[trim($column)] = trim($column);
					}
				}

				//order column
				$orderDirectionArray = array();
				if ($orderColumn != "") {
					foreach ($orderColumn as $orderRow) {
						$orderDirectionArray[$orderRow[0]] = $orderRow[1];
					}
				}
				//IdAnyoneDepend
				$dependArray = array();
				if (property_exists($inputQueryEle[0], 'IsAnyoneDepend')) {
					$isAnyoneDepend = $inputQueryEle[0]->IsAnyoneDepend;
					foreach ($isAnyoneDepend as $dependRow) {
						if ($dependRow->type == 1) {
							$dependArray[$dependRow->elementId] = $dependRow->columnName;
						}
					}
				}

				//where condition
				$where = array();
				$where = $this->inputSearchDataTableWhere($inputQueryEle[0]->where, $searchValue);
				//data query
				$metaData = $this->MasterModel->getRows($_POST, $where, array_unique($selectColumn), $table, $searchColumn, array(),
					$orderDirectionArray, array(), array(), null, array());
				$last_query = $this->db->last_query();
				//filter count
				$filterCount = $this->MasterModel->countFiltered($_POST, $table, $where, $searchColumn, array(), $orderDirectionArray, array());
				//total count
				$totalCount = $this->MasterModel->countAll($table, $where);

//						print_r($updateSearchColumn);print_r($dependArray);print_r($metaData);exit();
				foreach ($metaData as $rowData) {
					$actionArray = array();
					if (count($dependArray) > 0) {
						foreach ($dependArray as $dKey => $dRow) {
//									if(isset($updateSearchColumn[$dRow]))
//									{
//										$columnName=$updateSearchColumn[$dRow];
//										$actionArray[$dKey]=$rowData->$columnName;
							array_push($actionArray, array('id' => $dKey, 'value' => $rowData->$dRow));
//									}
						}

					}
					$rowData->Action = $actionArray;
					$data[] = $rowData;
				}
			}

			$response = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $filterCount,
				"recordsFiltered" => $totalCount,
				"data" => $data
			);
		} else {
			$response = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => 0,
				"recordsFiltered" => 0,
				"data" => array()
			);
		}
		echo json_encode($response);
	}

	public function getReportDatatableStructure()
	{
		$template_id = $this->input->post('templateID');
		$row_id = $this->input->post('rowID');
		$column_id = $this->input->post('colID');
		$element = $this->getTemplateElementData($template_id, $row_id, $column_id);
		if ($element != "") {
			$array_head = array();
			$array_data = array();
			if (property_exists($element, 'query')) {
				$query_val = $element->query;
				$i = 1;
				foreach ($element->data as $eleRow) {
					$param = $eleRow->param;
					if (!is_null($param) && $param != "") {
						$value = $this->input->post('input_val' . $i);
						$char = "#" . $param;
						$query_val = str_replace($char, $value, $query_val);
					}
					$i++;
				}
				$result = $this->MasterModel->_rawQuery($query_val);
				if ($result->totalCount > 0) {
					foreach ($result->data[0] as $rKey => $rRow) {
						array_push($array_head, array('data' => $rKey));
					}
					foreach ($result->data as $dKey => $dRow) {
						$array_data[] = $dRow;
					}
				}
			}
			$response['status'] = 200;
			$response['head'] = $array_head;
			$response['body'] = $array_data;
		} else {
			$response['status'] = 200;
			$response['body'] = '';
		}
		echo json_encode($response);
	}

	function DownloadData()
	{
		$formdata = $this->input->post_get('formdata');
		$object = $object = json_decode($formdata);
//		print_r($object);exit();
		$template_id = $object->templateID;
		$row_id = $object->rowID;
		$column_id = $object->colID;
		$report_type = $object->type;
		$element = $this->getTemplateElementData($template_id, $row_id, $column_id);
		if ($element != "") {

			if (property_exists($element, 'query')) {
				$query_val = $element->query;
				$i = 1;
				foreach ($element->data as $eleRow) {
					$param = $eleRow->param;
					if (!is_null($param) && $param != "") {
						$input = 'input_val' . $i;
						$value = $object->$input;
						$char = "#" . $param;
						$query_val = str_replace($char, $value, $query_val);
					}
					$i++;
				}
				$result = $this->MasterModel->_rawQuery($query_val);
				if ($result->totalCount > 0) {
					if (property_exists($element, 'report_type')) {
						if ($report_type == 1) {
							$this->downloadExcelFile($element->report_name, $result);
						} else if ($report_type == 2) {
							$this->downloadPDFFile($element->report_name, $result);
						} else {
							$this->downloadCSVFile($element->report_name, $result);
						}
					}

				}
			}

		}
	}

	function downloadExcelFile($report_name, $result)
	{
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$array_head = array();
		$array_data = array();
		$column = 'A';
		//excel head code
		foreach ($result->data[0] as $rKey => $rRow) {
			array_push($array_head, $rKey);
			$objPHPExcel->getActiveSheet()->SetCellValue($column . '1', $rKey);
			$column++;
		}

		$rowCount = 2;
		foreach ($result->data as $dKey => $dRow) {
			$col = 'A';
			foreach ($array_head as $aRow) {
				$objPHPExcel->getActiveSheet()->SetCellValue($col . $rowCount, $dRow->$aRow);
				$col++;
			}
			$rowCount++;
		}
		ob_end_clean();
		$filename = $report_name . "_Excel_Report" . date("Y-m-d") . "" . time() . ".xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	function downloadPDFFile($report_name, $result)
	{
		$table_td = "";
		$table_th = "";
		$table = "";
		$array_head = array();
		$array_data = array();
		//excel head code
		$table_th .= "<tr>";
		foreach ($result->data[0] as $rKey => $rRow) {
			array_push($array_head, $rKey);
			$table_th .= "<th style='border:1px solid #ccc;'>
				" . $rKey . "
				</th>";
		}
		$table_th .= "</tr>";
		foreach ($result->data as $dKey => $dRow) {
			$table_td .= "<tr>";
			foreach ($array_head as $aRow) {
				$table_td .= "<td style='border:1px solid #ccc;text-align:center'>" . $dRow->$aRow . "</td>";
			}
			$table_td .= "</tr>";
		}
		$table .= '<table class="table table-bordered" style="width:100%"  id="table_data">
						<thead>
						' . $table_th . '
						</thead>
						<tbody >
							' . $table_td . '
						</tbody>
					</table>';

		// include autoloader
		require_once FCPATH . 'vendor\autoload.php';

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		$dompdf->loadHtml($table);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'landscape');

		// Render the HTML as PDF
		$dompdf->render();
		$name = $report_name . "_PDF_Report";
		return $dompdf->stream($name, array('Attachment' => 0));
		/* $output = $dompdf->output();
		file_put_contents("file.pdf", $output); */
	}

	function downloadCSVFile($report_name, $result)
	{
		$array_head = array();
		$array_data = array();
		$reoprt_name = "dashboard";
		$filename = $reoprt_name . '_CSV_Report' . date('Ymd') . '.csv';
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$filename");
		header("Content-Type: application/csv; ");

		// file creation
		$file = fopen('php://output', 'w');
		foreach ($result->data[0] as $rKey => $rRow) {
			array_push($array_head, $rKey);
		}
		fputcsv($file, $array_head);
		foreach ($result->data as $dKey => $dRow) {
			$otherdata = array();
			foreach ($array_head as $aRow) {
				$otherdata[] = $dRow->$aRow;
			}
			fputcsv($file, $otherdata);
		}
		fclose($file);
		exit;
	}

	public function deleteColumnDataFromtable()
	{
		$columnName = $this->input->post('columnName');
		$tableName = $this->input->post('tableName');
		$id = $this->input->post('id');
		$resultObject = $this->MasterModel->_update($tableName, array($columnName => ""), array('id' => $id));
		if ($resultObject->status) {
			$response['status'] = 200;
			$response['body'] = "Changes Saved";
		} else {
			$response['status'] = 201;
			$response['body'] = "Changes Not Saved";
		}
		echo json_encode($response);
	}

	public function saveModalDataTableButtonInsertion()
	{
		if (!is_null($this->input->post('insertdata')) && $this->input->post('insertdata') != "") {
			$insertData = $this->input->post('insertdata');
			$insertData = json_decode($insertData);

			if (count($insertData) > 0) {
				$resultObject = false;
				try {
					$this->db->trans_start();
					$InsertCnt = count($insertData);
					$cnt = 0;
					foreach ($insertData as $iRow) {
						if ($iRow->table != "") {
							$tableName = $iRow->table;
							$data = array();
							if ($iRow->column != "") {
								if (count($iRow->column)) {
									foreach ($iRow->column as $cRow) {
										$data[$cRow->column] = $cRow->value;
									}
								}
							}
							$this->MasterModel->_insert($tableName, $data);
						}
					}
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$resultObject = FALSE;
					} else {
						$this->db->trans_commit();
						$resultObject = TRUE;
					}
					$this->db->trans_complete();
				} catch (Exception $ex) {
					$resultObject = FALSE;
					$this->db->trans_rollback();
				}
				if ($resultObject == true) {
					$response['status'] = 200;
					$response['body'] = "Changes Saved";
				} else {
					$response['status'] = 201;
					$response['body'] = "Data Not Saved";
				}
			} else {
				$response['status'] = 201;
				$response['body'] = "No Data For Insertion";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	// old code

	function getDataTableActionData($queryTableActionCondition)
	{
		$buttonTemp = array();
		if ($queryTableActionCondition != "") {
			foreach ($queryTableActionCondition as $actionRow) {
				$actionArr = array('action' => $actionRow->action, 'label' => $actionRow->label, 'column' => $actionRow->column);
				if ($actionRow->action == 1) //update operaation
				{
					$actionArr['confirmation'] = $actionRow->confirmation;
					$actionArr['value'] = '';
					if ($actionRow->type == 2) {
						$actionArr['query'] = json_encode($actionArr->query->query);
					} else {
						$actionArr['value'] = $actionRow->value;
					}
				} else if ($actionRow->action == 3) //redirection to new page
				{
					if ($actionRow->redirection != "") {
						$actionArr['path'] = $actionRow->redirection->path;
						if ($actionRow->redirection->type == 2) {
							$actionArr['value'] = $actionRow->redirection->value;
						} else if ($actionRow->redirection->type == 1) {
							if (isset($sessionData)) {
								$actionArr['value'] = $sessionData->$actionRow->redirection->value;
							}
						} else if ($actionRow->redirection->type == 3) {
							$actionArr['value'] = $this->replaceStringWithValue($actionRow->redirection->value, $queryParameter_hidden);
						}
					}
				} else if ($actionRow->action == 5) {
					$actionArr['templateName'] = $actionRow->templateName;
				} else if ($actionRow->action == 4) {
					$actionArr['modalID'] = $actionRow->modalID;
				} else if ($actionRow->action == 2) {
					$actionArr['confirmation'] = $actionRow->confirmation;
					$actionArr['query'] = $actionRow->query;
				}
				array_push($buttonTemp, $actionArr);
			}
		}
		return $buttonTemp;
	}

	function getAllTemplateSection($button)
	{

		$actionButtonTemplate = "";
		if ($button->type == 3) {
			$actionButtonTemplate = "<button class='btn btn-sm' type='button'
						 onclick='dataTableExecution(`" . $button->elementID . "`,`" . $button->section_id . "`," . $button->index . "," . $button->type . ",`" . $button->value . "`)'>" . $button->icon . "</button>";

		} else if ($button->type == 4) {
			$actionButtonTemplate = "<button class='btn btn-link' type='button' id='redirectButton" . str_replace('#', '', $button->elementID) . "'>" . $button->icon . "</button>
				<script> document.getElementById(redirectButton" . str_replace('#', '', $button->elementID) . ").onclick = function () {
			        location.href = '<?php echo base_url();?>" . $button->url . "';
			    };
			    </script>";

		} else {
			$actionButtonTemplate = "<button class='btn btn-sm' type='button'
						 onclick='dataTableUpdate(`" . $button->elementID . "`,`" . $button->section_id . "`," . $button->index . "," . $button->type . ",`" . $button->value . "`," . $button->actionButtonRedirectTemplate . ")'>" . $button->icon . "</button>";

		}
		return $actionButtonTemplate;
	}

	function executionButton()
	{
		$header = $this->is_parameter(array("transID", "elementID", "sectionID", "index"));
		if ($header->status) {

			$transValue = $header->param->transID;
			$elementID = str_replace("#", "", $header->param->elementID);
			$sectionId = $header->param->sectionID;
			$index = $header->param->index;

			$dataTable = $this->MasterModel->_select("datatable_query_master",
				array("elementID" => "#" . $elementID, "sectionID" => $sectionId),
				array("action_columns")
			);


			if ($dataTable->totalCount > 0) {

				$action_columns = $dataTable->data->action_columns;

				$actionArray = array();
				if ($action_columns != "" && $action_columns != null) {
					$actionStrings = explode(",", $action_columns);
					foreach ($actionStrings as $action) {
						$actionOptions = explode("|", $action);
						$actionObject = new stdClass();
						foreach ($actionOptions as $option) {
							$actionProperties = explode(":", $option);
							if (count($actionProperties) == 2) {
								$actionObject->{$actionProperties[0]} = $actionProperties[1];
							}
						}
						array_push($actionArray, $actionObject);
					}
				}

				if (count($actionArray) > 0) {
					foreach ($actionArray as $actionObject) {

						if ($actionObject->actionButtonType == 3) {
							$executionQuery = $actionObject->actionButtonExecutionQuery;
							if (strpos($executionQuery, $actionObject->actionButtonPrimary)) {
								$sqlQuery = str_replace("#" . $actionObject->actionButtonPrimary, $transValue, $executionQuery);
								if (!is_array($sqlQuery)) {
									$this->db->query($sqlQuery);
									if ($this->db->affected_rows() > 0) {
										$response["status"] = 200;
										$response["body"] = "Save Changes";
									} else {
										$response["status"] = 201;
										$response["body"] = "Failed To Save Changes";
									}
								}
							} else {
								$response["status"] = 201;
								$response["body"] = "Failed To Save Changes";
								$response["error"] = "Query mapping failed";
							}
						}
					}
				} else {
					$response["status"] = 201;
					$response["body"] = "Failed To Save Changes";
					$response["error"] = "No Action Details Found";
				}
			} else {
				$response["status"] = 201;
				$response["body"] = "Failed To Save Changes";
				$response["error"] = "Action Template Not Found";
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Failed To Save Changes";
			$response["error"] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	function getAllSection()
	{


		$resultObject = $this->MasterModel->_select("html_section_master", array("status" => 1), array("id", "name"), false);

		$options = "";
		if ($resultObject->totalCount > 0) {
			foreach ($resultObject->data as $section) {
				$options .= "<option value='" . $section->id . "'>" . $section->name . "</option>";
			}
		}
		$response["status"] = 200;
		$response["body"] = $options;
		echo json_encode($response);

	}

	public function fetchTemplateDatatableData()
	{
		// print_r($this->input->post());exit();
		$validationObject = $this->is_parameter(array("section_id", "element_id"));
		if ($validationObject->status) {
			$params = $validationObject->param;
			$section_id = $params->section_id;
			$element_id = $params->element_id;
			$resultObject = $this->MasterModel->_select("datatable_query_master",
				array("status" => 1, "elementID" => $element_id, "sectionID" => $section_id), array("*"));
			$data = '';
			if ($resultObject->totalCount > 0) {
				$data = $resultObject->data;
			}
			$response['status'] = 200;
			$response['data'] = $data;
			$response['body'] = '';
		} else {
			$response['status'] = 201;
			$response['body'] = 'Something Went wrong';
		}
		echo json_encode($response);
	}
	function getAllTablesList()
	{
		$tables = $this->db->list_tables();
		$option = "<option value='' selected disabled>Select Table</option>";
		foreach ($tables as $table) {
			$option .= "<option value='" . $table . "'>" . $table . "</option>";
		}
		$response['option'] = $option;
		echo json_encode($response);
	}
}

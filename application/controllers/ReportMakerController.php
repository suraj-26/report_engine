<?php
require_once 'HexaController.php';
require_once './vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @property  LabReport LabReport
 */
class ReportMakerController extends HexaController
{
    /**
     * ReportMakerController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ReportMakerModel');
        $this->load->model('Global_model');
        date_default_timezone_set('Asia/Kolkata');
        $this->db2 = $this->load->database('live', TRUE);
    }

    public function index()
    {
        $this->load->view('ReportMaker/report_maker', array("title" => "Report Maker"));
    }

    public function ViewReportMaker($dept_id = "", $report_id = "", $string_param = "")
    {

        $this->load->view('ReportMaker/View_Report', array("title" => "View Report", "report_id" => $report_id, "string_param" => $string_param, "dep_id" => $dept_id));
    }
	public function payerDetails($dept_id = "", $report_id = "", $string_param = "")
	{
		$this->load->view('ReportMaker/view_payment_details', array("title" => "Payment Details", "section_id" => $report_id, "queryParam" => $string_param, "department_id" => $dept_id));
	}

    public function getAllTablenames()
    {
        $tables = $this->db->list_tables();
        $option = "<option value=''>Select Table</option>";
        foreach ($tables as $table) {
            $option .= "<option value='" . $table . "'>" . $table . "</option>";
        }
        // return $option;
        $response['option'] = $option;
        $response['data'] = $tables;
        echo json_encode($response);

    }

    public function GetAllTableColumns()
    {
        $TableName = $this->input->post('TableName');
        $fields = $this->db->field_data($TableName);

        $data = "";
        $option = "";
        foreach ($fields as $field) {
            $column_name = $field->name;
            $option .= "<option value='" . $column_name . "'>" . $column_name . "</option>";
        }
        $response['data'] = $option;
        echo json_encode($response);
    }

    public function save_template_reportHtml()
    {
        // print_r($this->input->post());exit();
        $validationObject = $this->is_parameter(array("report_name", "elementSequenceType", "elementSequenceId", "elementSequenceText", "editor", "QueryStringParameter"));
        if ($validationObject->status) {
            $params = $validationObject->param;
            $report_name = $params->report_name;
            $elementSequenceType = $params->elementSequenceType;
            $elementSequenceId = $params->elementSequenceId;
            $elementSequenceText = $params->elementSequenceText;
            $QueryStringParameter = $params->QueryStringParameter;
            $editor = $params->editor;
            $report_id = $this->input->post('report_id');
            $isRequired = $this->input->post('isRequired');
            $isRequired = json_decode($isRequired);
            $QueryStringParameter1 = implode(',', $QueryStringParameter);
            $queryParamenterArray = array();
            // echo '<pre>';
            // print_r($isRequired);exit();
            if (!empty($isRequired)) {
                for ($i = 0; $i < count($isRequired); $i++) {
                    $where_v = implode('|', $isRequired[$i]->where);
                    $queryData = array('table_name' => $isRequired[$i]->table,
                        'column_field' => $isRequired[$i]->field,
                        'where_condition' => $where_v,
                        'hash_key' => $isRequired[$i]->id,
                        'ans_type' => $isRequired[$i]->ans_type,
                        'created_on' => date('Y-m-d H:i:s'),
                        'query' => $isRequired[$i]->rawQuery
                    );
                    array_push($queryParamenterArray, $queryData);
                }
            }
            // print_r($queryParamenterArray);exit();
            $insert_data = array('Report_name' => $report_name,
                'status' => 1,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->user_session->id,
                'ans_type' => $elementSequenceType,
                'raw_html' => $editor,
                'hash_keys' => $elementSequenceText,
                'element_sequenceId' => $elementSequenceId,
                'query_param' => $QueryStringParameter1
            );
            $updateHtml = $this->ReportMakerModel->addReportTemplateHtml($report_id, $insert_data, $isRequired, $queryParamenterArray);


            if ($updateHtml->status == true) {
                $response['status'] = 200;
                $response['report_id'] = $updateHtml->report_id;
                $response['body'] = "Changes Saved";
            } else {
                $response['status'] = 201;
                $response['body'] = 'Data Not Saved';
            }
        } else {
            $response["status"] = 201;
            $response["body"] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public function getReportMakers()
    {

        $resultObject = $this->ReportMakerModel->fetchReportMakers();
        if ($resultObject->data) {
            $response["status"] = 200;
            $response["body"] = $resultObject->data;
        } else {
            $response["status"] = 201;
            $response["body"] = "Missing Parameter";
        }
        echo json_encode($response);
    }

    public function fetch_ReportMakerHtml()
    {
        $report_id = $this->input->post('report_id');

        $query = $this->ReportMakerModel->get_HtmlReportMaker($report_id);
        $data = "";

        if ($query != false) {
            $data = $query;

            $response['status'] = 200;
            $response['data'] = $data;
            $response['body'] = "data fetch";

        } else {
            $response['status'] = 201;
            $response['data'] = '';
            $response['form_data'] = '';
            $response['field_name'] = '';
            $response['body'] = 'Something went wrong';
        }


        echo json_encode($response);
    }

    public function getTableQueryData()
    {
        // print_r($this->input->post());exit();

        $validationObject = $this->is_parameter(array("report_id", "field_id", "field_type"));
        if ($validationObject->status) {

            $params = $validationObject->param;
            $report_id = $params->report_id;

            $ans_type = $params->field_type;
            $query_data = $this->ReportMakerModel->get_reportquerydata($params->field_id, $report_id, $ans_type);
            $where = array();
            $table_options = $this->getAllTablenames1();
            $query_paracolumns = $this->getQueryStringPara1();
            $field_id = explode('#', $params->field_id);
            $value1 = $field_id[1];
            if ($query_data != false && $query_data != "") {
                $table_name = $query_data->table_name;
                $raw_query = $query_data->query;
                $select_value = $query_data->column_field;
                if ($query_data->where_condition != "" && $query_data->where_condition != null) {
                    $where_str = explode('|', $query_data->where_condition);
                    if (!empty($where_str)) {
                        foreach ($where_str as $key => $value) {
                            $resultObject = new stdClass();
                            $where_key = explode('=', $value);
                            // print_r($where_key);
                            if (count($where_key) > 1) {
                                $resultObject->column = $where_key[0];
                                $resultObject->para_value = "";
                                $resultObject->text_value = "";
                                if (substr($where_key[1], 0, strlen('#')) == '#') {
                                    $resultObject->para_value = str_replace("#", "", $where_key[1]);
                                } else {
                                    $resultObject->text_value = $where_key[1];
                                }
                                // print_r($resultObject->para_value);
                                // $where[$where_key[0]]=$where_key[1];
                                array_push($where, $resultObject);
                            }
                        }
                    }
                }
            } else {
                $table_name = '';
                $raw_query = '';
                $select_value = '';
            }
            $table_columns = '';
            if ($table_name != "") {
                $table_columns = $this->GetAllTableColumns1($table_name);
            }
            $design_data = '';
            $design_data = '<div class="col-md-12">
				<div class="mt-2"> <strong>' . $params->field_id . ' options</strong> 
				<button type="button" class="btn btn-link ml-2" onclick="getHideDiv(\'form_through_' . $value1 . '\',\'mormal_query_' . $value1 . '\')">form through</button>
				<button type="button" class="btn btn-link ml-2" onclick="getHideDiv(\'mormal_query_' . $value1 . '\',\'form_through_' . $value1 . '\')">normal query</button>
				</div>
				<div class="mt-2" id="mormal_query_' . $value1 . '" style="display:none">
					<label>Query : </label><textarea class="form-control" name="textquery_' . $value1 . '" id="textquery_' . $value1 . '" placeholder="Enter query here">' . $raw_query . '</textarea>
				</div>
				<div class="" id="form_through_' . $value1 . '">
					<div class="mt-2 row">
					<div class="col-md-6">
						Select Table : <select name="select_' . $value1 . '" id="select_' . $value1 . '" class="selectTable" onchange="getListOfColumns2(this.value,\'key_' . $value1 . '\')">
						' . $table_options . '</select>
						<script>
						';
            if ($table_name != "" && $table_name != null) {
                $design_data .= '$("#select_' . $value1 . ' option[value=' . $table_name . ']").prop("selected", true);
							';
            }

            $design_data .= '$("#select_' . $value1 . '").select2();</script>
					</div>
					<div class="col-md-6">
						Select Option Value: <select name="key_' . $value1 . '[]" id="key_' . $value1 . '" class="tableColumns" multiple>
						' . $table_columns . '
						</select>
					</div>
					<script>';
            if ($select_value != "" && $select_value != null) {
                // $select_v=explode(',',$select_value);
                $design_data .= '
							$.each("' . $select_value . '".split(","), function(i,e){
							    $("#key_' . $value1 . ' option[value=" + e + "]").prop("selected", true);
							});';
            }
            $design_data .= '$("#key_' . $value1 . '").select2();</script>
					</div>
					<div class="mt-2 row">
					<div class="col-md-12">
					<button type="button" name="add_where" id="add_where" onclick="getWhereColumns(\'' . $value1 . '\',\'' . base64_encode($query_paracolumns) . '\',\'' . $table_name . '\')" class="btn btn-outline-primary">
					<i class="fa fa-plus"></i> Add Where Condition</button>
					</div></div>';
            $count = 0;
            if (count($where) > 0) {
                $k = 1;
                foreach ($where as $key => $w_value) {
                    // print_r($w_value->para_value);
                    $design_data .= '
							<div class="mt-2 row">
							<div class="col-md-4">
								<label>where column: </label><select name="column' . $k . '_' . $value1 . '" id="column' . $k . '_' . $value1 . '" class="tableColumns">
								' . $table_columns . '
								</select>
							</div>
							<div class="col-md-3">
								<label>Where value: </label>
								<select name="para_value' . $k . '_' . $value1 . '" id="para_value' . $k . '_' . $value1 . '" class="form-control-field">
								' . $query_paracolumns . '
								</select>
							</div>
							<div class="col-md-3">
								<label>Where value: </label>
								<input type="text" name="text' . $k . '_' . $value1 . '" id="text' . $k . '_' . $value1 . '" placeholder="Enter value here" class="form-control-field" value="' . $w_value->text_value . '" />
							</div>
							</div>
							<script>
							
							$("#column' . $k . '_' . $value1 . ' option[value=' . $w_value->column . ']").prop("selected", true);
							$("#column' . $k . '_' . $value1 . '").select2();
							$("#para_value' . $k . '_' . $value1 . ' option[value=\'' . $w_value->para_value . '\']").prop("selected", true);
							$("#para_value' . $k . '_' . $value1 . '").select2();
							</script>
							';
                    $k++;
                    $count++;
                }
            }

            $design_data .= '<input type="hidden" value="' . $count . '" name="wherecount_' . $value1 . '" id="wherecount_' . $value1 . '" class="form-control-field">';
            $design_data .= '
					<div id="whereIds_' . $value1 . '">

					</div>
				
				</div>
				</div>
				<div class="mt-4 row">
				<div class="col-md-12">
				<button type="button" class="btn btn-primary mt-4" onclick="queryTableSave(' . $report_id . ',\'' . $params->field_id . '\',' . $params->field_type . ',\'' . $value1 . '\')" style="float:right;">Save</button>
				</div>
			</div>';

            $response['status'] = 200;
            $response['data'] = $design_data;

        } else {
            $response['status'] = 201;
            $response['data'] = '';
            $response['body'] = 'Required parameter Missing';

        }

        echo json_encode($response);
    }

    public function saveQueryTableData()
    {

        $validationObject = $this->is_parameter(array("report_id", "hash_id", "ans_type", "without_hash", "query_data"));
        if ($validationObject->status) {

            $params = $validationObject->param;
            $report_id = $params->report_id;
            $hash_id = $params->hash_id;
            $ans_type = $params->ans_type;
            $without_hash = $params->without_hash;
            $query_data = json_decode($params->query_data);
            $raw_query = '';
            $select = '';
            $table_name = '';
            $where_value = '';

            $rawq = $this->input->post('rawq');
            if ($rawq == "") {
                $where = array();
                if (count($query_data) > 0) {
                    foreach ($query_data as $key => $value) {
                        $raw_query = $value->raw_query;
                        if (!empty($value->select_key)) {
                            $select = implode(',', $value->select_key);
                        }
                        $table_name = $value->table_name;
                        if (!empty($value->where_op)) {
                            foreach ($value->where_op as $where_key => $where_value) {
                                array_push($where, $where_value->where_col . '=' . $where_value->where_text);
                            }
                        }

                    }
                }
                if (!empty($where)) {
                    $where_value = implode('|', $where);
                }
            }


            $insert_data = array('report_id' => $report_id,
                'hash_key' => $hash_id,
                'ans_type' => $ans_type,
                'query' => $rawq,
                'table_name' => $table_name,
                'column_field' => $select,
                'where_condition' => $where_value,
                'created_on' => date('Y-m-d H:i:s'));
            // print_r($insert_data);exit();
            $where = array('report_id' => $report_id, 'ans_type' => $ans_type, 'hash_key' => $hash_id);
            $getData = $this->db->select('id')->where($where)->limit(1)->get('reportMakerQuery');
            if ($getData->num_rows() > 0) {
                $this->db->update('reportMakerQuery', $insert_data, $where);
            } else {
                $this->db->insert('reportMakerQuery', $insert_data);
            }

            $response['status'] = 200;
            $response['data'] = '';
            $response['body'] = 'Changes Saved';


        } else {
            $response['status'] = 201;
            $response['data'] = '';
            $response['body'] = 'Required Parameter Missing';

        }

        echo json_encode($response);
    }

    public function getAllTablenames1()
    {
        $tables = $this->db->list_tables();
        $option = "<option value=''>Select Table</option>";
        foreach ($tables as $table) {
            $option .= "<option value='" . $table . "'>" . $table . "</option>";
        }
        return $option;

    }

    public function GetAllTableColumns1($tablename)
    {
        $fields = $this->db->field_data($tablename);

        $data = "";
        $option = "";
        foreach ($fields as $field) {
            $column_name = $field->name;
            $option .= "<option value='" . $column_name . "'>" . $column_name . "</option>";
        }
        return $option;
    }

    public function getParaghraphConfigurationData()
    {
        $paraGraphArray = $this->input->post('paraGraphArray');
        $paraGraphArray = json_decode($paraGraphArray);
        // print_r($paraGraphArray);exit();
        $table_names = $this->getAllTablenames1();
        $stringPara = $this->getQueryStringPara1();

        $template = '';
        $template .= '<div class="mt-4">
						<div class="row">
						<div class="col-md-2"><h6>Field Name</h6></div>
						<div class="col-md-2"><h6>Table</h6></div>
						<div class="col-md-2"><h6>Field</h6></div>
						<div class="col-md-1"></div>
						<div class="col-md-2"><h6>Where Column</h6></div>
						<div class="col-md-3"><h6>Where Value</h6></div>
						
					   </div>';
        $cnt = 0;
        foreach ($paraGraphArray as $key => $para_value) {

            $where_value = "";
            $table_value = "";
            $field_value = "";
            $para_text = $para_value->text;
            $tableColumns = "";
            $para_text = str_replace("#", "", $para_text);
            $where = array();
            $rawQuery = $para_value->rawQuery;
            // print_r($para_value->where);exit();
            if ($para_value->where != '' && $para_value->where != null) {
                $where_str = explode('|', $para_value->where);

                if (!empty($where_str)) {
                    foreach ($where_str as $key => $value) {
                        $resultObject = new stdClass();
                        $where_key = explode('=', $value);
                        // print_r($where_key);
                        if (count($where_key) > 1) {
                            $resultObject->column = $where_key[0];
                            $resultObject->para_value = "";
                            $resultObject->text_value = "";
                            if (substr($where_key[1], 0, strlen('#')) == '#') {
                                $resultObject->para_value = str_replace("#", "", $where_key[1]);
                            } else {
                                $resultObject->text_value = $where_key[1];
                            }
                            // print_r($resultObject->para_value);
                            // $where[$where_key[0]]=$where_key[1];
                            array_push($where, $resultObject);
                        }
                    }
                }
                // $where_value=$value->where;
            }

            if ($para_value->table != '') {
                $table_value = $para_value->table;
                $tableColumns = $this->GetAllTableColumns1($table_value);
                // getListOfColumns1($table_value,'tableColumns'.$para_text);
            }
            if ($para_value->field != '') {
                $field_value = $para_value->field;
            }


            $template .= '<div class="row">
						<div class="title col-md-2 mt-3"> ' . $para_value->text . '
						
						</div>
						
						<div class="col-md-2 form-group mt-3">
						<select class="form-control table_select_' . $para_text . '" name="table_select_' . $para_text . '" id="table_select_' . $para_text . '"
						  onchange="getListOfColumns1(this.value,\'tableColumns' . $para_text . '\')">
							' . $table_names . '
						</select>
						</div>
					   <div class="col-md-2 form-group mt-3">
						<select name="key_' . $para_text . '" id="key_' . $para_text . '" class=" form-control tableColumns' . $para_text . '">
						' . $tableColumns . '
						</select>
					   </div>
					      <div class="col-md-1 form-group mt-3">
					   <button class="btn btn-primary" onclick="getConfiWhereColumns(\'' . $para_text . '\',\'configurationWhereColumn' . $para_text . '\',\'' . base64_encode($tableColumns) . '\',\'' . base64_encode($stringPara) . '\',' . $cnt . ')"><i class="fa fa-plus"></i></button>
					   </div>';
            $template .= '<div class="col-md-5 form-group mt-3">
					    <div class="row">';
            $count = 0;
            if (count($where) > 0) {
                // print_r($where);exit();
                $k = 1;
                foreach ($where as $key => $w_value) {
                    $template .= '<div class="col-md-5 form-group my-0 py-0 mt-1">
							<select name="wherecolumn_' . $k . '_' . $para_text . '" id="wherecolumn_' . $k . '_' . $para_text . '" class=" form-control tableColumns' . $para_text . '">
							' . $tableColumns . '
							</select>
						   </div>
						     <div class="col-md-5 form-group my-0 py-0 mt-1">
						     <select name="wherequerystring_' . $k . '_' . $para_text . '" id="wherequerystring_' . $k . '_' . $para_text . '" class=" form-control ">
							' . $stringPara . '
							</select>
							
						   </div>
						   <div class="col-md-2 form-group my-0 py-0 mt-1">
						   		<input type="text" name="wherevalue_' . $k . '_' . $para_text . '" id="wherevalue_' . $k . '_' . $para_text . '" class=" form-control " placeholder="Enter value here" value="' . $w_value->text_value . '">
						   </div> <script>';
                    if ($w_value->column != "") {
                        $template .= '$("#wherecolumn_' . $k . '_' . $para_text . ' option[value=' . $w_value->column . ']").prop("selected", true);';
                    }
                    $template .= '$("#wherecolumn_' . $k . '_' . $para_text . '").select2();';
                    if ($w_value->para_value != "") {
                        $template .= '$("#wherequerystring_' . $k . '_' . $para_text . ' option[value=' . $w_value->para_value . ']").prop("selected", true);';
                        $template .= '$("#wherequerystring_' . $k . '_' . $para_text . '").select2();';
                    }
                    $k++;
                    $count++;
                    $template .= '</script>';

                }
            }
            $template .= ' </div></div>';
            $template .= ' </div>
					   <input type="hidden" name="whereConfigCount_' . $cnt . '_' . $para_text . '" id="whereConfigCount_' . $cnt . '_' . $para_text . '" value="' . $count . '">
					   <div class="row" id="configurationWhereColumn' . $para_text . '"></div>
					   <script>';
            if ($table_value != "") {
                $template .= '$("#table_select_' . $para_text . ' option[value=' . $table_value . ']").prop("selected", true);';
            }
            $template .= '$("#table_select_' . $para_text . '").select2();';
            if ($field_value != "") {
                $template .= '$("#key_' . $para_text . ' option[value=' . $field_value . ']").prop("selected", true);';
            }
            $template .= '$("#key_' . $para_text . '").select2();';
            // $template.='$("#wherecolumn_'.$para_text.'").select2();';
            // $template.='$("#wherequerystring_'.$para_text.'").select2();';
            $template .= '</script>
					   ';
            $template .= '<div class="row">
						<div class="title col-md-2 mb-2">
									
									</div>
							<div class="col-md-10 mb-2">

								<textarea name="rawQuery_' . $para_text . '" id="rawQuery_' . $para_text . '" class="form-control" placeholder="Enter Raw Query Here">' . $rawQuery . '</textarea>
							</div>
						 </div>';

            $cnt++;
        }
        $template .= '</div>';
        $response['status'] = 200;
        $response['data'] = $template;
        echo json_encode($response);

    }

    public function deleteReportmaker()
    {
        $validationObject = $this->is_parameter(array("report_id"));
        if ($validationObject->status) {
            $param = $validationObject->param;
            $resultObject = $this->ReportMakerModel->deleteReportmaker($param->report_id);
            if ($resultObject->status) {
                $response["data"] = $resultObject;
                $response["status"] = 200;
                $response["body"] = "Delete Report";
            } else {
                $response["status"] = 200;
                $response["body"] = "Fail To Delete";
            }

        } else {
            $response["status"] = 201;
            $response["body"] = "Missing Parameter";
        }
        echo json_encode($response);
    }

    function getQueryStringPara()
    {
        $query = $this->db->query("select * from html_querystring_parameter_table");

        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            $data = "<option value=''>Select Parameters</option>";
            foreach ($result as $row) {
                $data .= "<option value='" . $row->parameter_name . "'>" . $row->parameter_name . "</option>";
            }
            $response['status'] = 200;
            $response['data'] = $data;
        } else {
            $response['status'] = 201;
            $response['data'] = "";
        }
        echo json_encode($response);
    }

    function getQueryStringPara1()
    {
        $query = $this->db->query("select * from html_querystring_parameter_table");
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            $data = "<option value=''>Select Parameters</option>";
            foreach ($result as $row) {
                $data .= "<option value='" . $row->parameter_name . "'>" . $row->parameter_name . "</option>";
            }

        }
        return $data;
    }


    public function GetReportView($report_id, $string_param, $post_array)
    {
//        $report_id = $this->input->post('report_id');
//        $string_param = $this->input->post('string_param');
        $s = (base64_decode($string_param));
        //var_dump($s);
        $ex1 = explode("&", $s);
        $array_param = array();
        foreach ($ex1 as $item) {
            $exppp = explode("=", $item);
            $array_param[$exppp[0]] = $exppp[1];
        }

        $query = $this->db->query("select * from reportMakerTable where id=" . $report_id);

        if ($this->db->affected_rows() > 0) {
            $data_to_show = "";
            $result = $query->row();
            $raw_html = $result->raw_html;
            $hash_key = $result->hash_keys;
            $ans_type = $result->ans_type;
            $data_query = $this->getHashKeyValue($hash_key, $ans_type, $report_id, $array_param, $post_array);

            if ($data_query != false) {
                foreach ($data_query as $key => $value) {
                    //	echo $key;
                    $raw_html = str_replace($key, $value, $raw_html);
                }
                $data_to_show .= $raw_html;
            }
//            $response["status"] = 200;
//            $response["data"] = $data_to_show;
//            $response["report_id"] = $report_id;
            return $data_to_show;
        } else {
            return false;
        }
    }

    public function getHashKeyValue($hash_key, $ans_type, $report_id, $array_param, $post_array)
    {
        extract($array_param);
        extract($post_array);
        $hash_key_array = explode(",", $hash_key);
        $ans_type_array = explode(",", $ans_type);
        $i = 0;
        $array_to_replace = array();
        foreach ($hash_key_array as $key) {
            $query = $this->db->query("select * from reportMakerQuery where report_id=" . $report_id . " AND hash_key='" . $key . "'");

            if ($this->db->affected_rows() > 0) {
                $result = $query->row();
                $ans_t = $result->ans_type;
                $raw_query = $result->query;

                if ($ans_t == 4 || $ans_t == 6) {
                    if (trim($raw_query) == "" || is_null($raw_query)) {
                        $table_name = $result->table_name;
                        $column_field = $result->column_field;
                        $where = $result->where_condition;
                        if ($where != "") {
                            $where = $this->replace_param($array_param, $where);
                            $where = str_replace("#Prescription_date", $trans_date, $where);
                            $exp_whre = str_replace("|", " AND ", $where);
                            $this->db->where($exp_whre);
                        }
                        $this->db->select($column_field);
                        $this->db->from($table_name);
                        $query1 = $this->db->get();
                        if ($query1->num_rows() > 0) {
                            $value = $query1->row();

                            $value = $value->$column_field;
                            $array_to_replace[$key] = $value;
                        } else {
                            $array_to_replace[$key] = "";
                        }
                    } else {
                        //  echo $key;
                        $raw_query = $this->replace_param($array_param, $raw_query);
                        $raw_query = str_replace("#Prescription_date", $trans_date, $raw_query);
                        $query1 = $this->db->query($raw_query);
                        // echo $this->db->last_query();
                        if ($this->db->affected_rows() > 0) {
                            $value = $query1->result();

                            $val = "";
                            foreach ($value as $k1 => $v1) {
                                foreach ($v1 as $ky => $k2) {
                                    $val = $k2;
                                }

                            }

                            $array_to_replace[$key] = $val;
                        } else {

                            $array_to_replace[$key] = "";
                        }
                    }

                    // var_dump($array_to_replace);


                } else if ($ans_t == 3) {
                    $array_to_replace[$key] = "";
                } else if ($ans_t == 7) {

                    //vertical Table

                    if ($result->query != "") {
                        $multiple_table = true;
                    } else {
                        $multiple_table = false;
                    }

                    $getVerticalTable = $this->getVerticalTable($multiple_table, $result, $array_param, $key);
                    $array_to_replace[$key] = $getVerticalTable;
                } else if ($ans_t == 8) {
                    //Horizontal Table
                    if ($result->query != "") {
                        $multiple_table = true;
                    } else {
                        $multiple_table = false;
                    }
                    $getHorizontalTable = $this->getHorizontalTable($multiple_table, $result, $array_param, $key);
                    $array_to_replace[$key] = $getHorizontalTable;
                } else {
                    $array_to_replace[$key] = "";
                }

                $i++;
            }

        }
        return $array_to_replace;

    }

    function replace_param($array_param, $string)
    {
        foreach ($array_param as $key1 => $item) {
            $string = str_replace("#" . $key1, $item, $string);
        }
        return $string;
    }

    function getVerticalTable($multiple_table, $result, $array_param, $key)
    {

        extract($array_param);
        if ($multiple_table == true) {

            $query_val = $result->query;
            $query_val = $this->replace_param($array_param, $query_val);
            $query_new = $this->db->query($query_val);
            // echo $this->db->last_query();
        } else {

            $table_name = $result->table_name;
            $column_field = $result->column_field;
            $where = $result->where_condition;
            if ($where != "") {
                $where = $this->replace_param($array_param, $where);
                $exp_whre = str_replace("|", " AND ", $where);
                $this->db->where($exp_whre);
            }

            $this->db->select($column_field);
            $this->db->from($table_name);

            $query_new = $this->db->get();
        }


//			echo $this->db->last_query();
        $table_td = "";
        $table_th = "";
        $table = "";
        if ($this->db->affected_rows() > 0) {
            $q_result = $query_new->result();

            $m = 0;
            $array_head = array();
            $array_data = array();
            //var_dump($q_result);
            $table_tr = "";
            $table_td1 = array();
            $table_td2 = array();


            foreach ($q_result as $row) {


                $array_data[] = array_values((array)$row);


                foreach ($row as $key => $r) {

                    if ($m == 0) {

                        $array_head[] = $key;
                        $table_tr .= "<th style='border:1px solid #ccc;text-align:center'>" . $key . "</th>";
                    }
                    $array_data[] = $r;
                    $table_td .= "<td style='border:1px solid #ccc;text-align:center'>" . $r . "</td>";
                    $table_td1[$key] = "<td style='border:1px solid #ccc;text-align:center'>" . $r . "</td>";

                }
                array_push($table_td2, $table_td1);

                $m++;

            }
            $t_new = "";
            foreach ($array_head as $data) {
                $t_new .= "<tr>";
                $t_new .= "<th style='border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);'>" . $data . "</th>";
                foreach ($table_td2 as $i) {
                    foreach ($i as $k => $pp) {

                        if ($k == $data) {
                            $t_new .= $pp;
                        }
                    }
                }
                $t_new .= "</tr>";
            }

            $table .= '
									<table class="table table-responsive" style="width:100%"  id="">
									' . $t_new . '
									</table>
									';
            return $table;
        } else {
            return $table;
        }

    }

    function getHorizontalTable($multiple_table, $result, $array_param, $key)
    {
        extract($array_param);
        if ($multiple_table == true) {
            $query_val = $result->query;
            $query_val = $this->replace_param($array_param, $query_val);
            $query_new = $this->db->query($query_val);
        } else {
            $table_name = $result->table_name;
            $column_field = $result->column_field;
            $where = $result->where_condition;
            if ($where != "") {
                $where = $this->replace_param($array_param, $where);
                $exp_whre = str_replace("|", " AND ", $where);
                $this->db->where($exp_whre);
            }
            $this->db->select($column_field);
            $this->db->from($table_name);
            $query_new = $this->db->get();
        }
        $table_td = "";
        $table_th = "";
        $table = "";
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
									<table class="table table-bordered " style="width:100%"  id="">
									<thead>
									' . $table_th . '
									</thead>
									<tbody >
									' . $table_td . '
									</tbody>
									</table>
									';
            return $table;
        } else {
            return $table;
        }

    }

    function GetQueryParamDataReport($report_id, $string_param = "")
    {
        if ($string_param == "") {
            $response['status'] = 201;
            $response['data'] = "Parameter Missing";
            echo json_decode($response);
            exit;
        }
        $post_array = array();
        $post_array = $_POST;
        //  var_dump(base64_decode($string_param));
        //  $string_param="branch_id:int:2,patient_id:int:135";

        $aa = json_decode(base64_decode($string_param));
        $data_array = array();
        foreach ($aa as $k => $s) {
            $exp = explode(":", ($k));
            $data_array[$exp[0]] = $s;
        }
        extract($data_array);
        //  $report_id = $this->input->post('report_id');
        $query = $this->db->query("select query_param,dep_id from reportMakerTable where id=" . $report_id);

        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $query_param = $result->query_param;
            $dep_id = $result->dep_id;
            $expp = explode(",", $query_param);
            $string = "";
            foreach ($expp as $e) {
                $string .= $e . "=#" . $e . "&";
            }

            $string1 = rtrim($string, "&");
            if (array_key_exists("branch_id", $data_array)) {
                $string1 = str_replace("#branch_id", $branch_id, $string1);
            }
            if (array_key_exists("patient_id", $data_array)) {
                $string1 = str_replace("#patient_id", $patient_id, $string1);
            }
            if (array_key_exists("company_id", $data_array)) {
                $string1 = str_replace("#company_id", $company_id, $string1);
            }
            if (array_key_exists("transaction_id", $data_array)) {
                $string1 = str_replace("#transaction_id", $transaction_id, $string1);
            }
            if (array_key_exists("user_id", $data_array)) {
                $string1 = str_replace("#user_id", $user_id, $string1);
            }
            $fun_report = $this->GetReportView($report_id, base64_encode($string1), $post_array);
            $response['status'] = 200;
            $response['department_id'] = $dep_id;
            $response['report_id'] = $report_id;
            $response['string'] = base64_encode($string1);
            $response['final_data'] = $fun_report;

//            $url=base_url()."ViewReportMaker/".$dep_id."/".$report_id."/".(base64_encode($string1));
//            header("Location:".$url);
//            exit;

        } else {
            $response['status'] = 201;
            $response['data'] = "something went worng";
        }
        echo json_encode($response);
    }

    public function ChangeBillingOpen()
    {
        $p_id = $this->input->post('p_id');
        $patient_table = $this->session->user_session->patient_table;
        $query = $this->db->query("select billing_open from " . $patient_table . " where id=" . $p_id);

        //1=close 0=open
        if ($this->db->affected_rows() > 0) {
            $billing_open = $query->row()->billing_open;
            if ($billing_open == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            $where = array("id" => $p_id);
            if ($status == 0) {
                $close_date = null;
                $close_user = null;
                $discharge_date = null;
            } else {
                $close_date = date('Y-m-d H:i:s');
                $discharge_date = date('Y-m-d H:i:s');
                $close_user = $this->session->user_session->id;
            }
            $set = array("billing_open" => $status, "close_bill_date" => $close_date, "discharge_date" => $discharge_date, "bill_close_user" => $close_user);
            $this->db->where($where);
            $update = $this->db->update($patient_table, $set);
            if ($update == true) {
                $response['status'] = 200;
                if ($status == 0) {
                    $response['body'] = "Successfully Open";
                } else {
                    $response['body'] = "Successfully Close";
                }

            } else {
                $response['status'] = 201;
                $response['body'] = "Something went wrong";
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Something went wrong";
        }
        echo json_encode($response);
    }

    function GetPrescriptionReport()
    {
        $object = $this->input->post('object');
        $trans_date = $this->input->post('trans_date');
        $data = json_decode(base64_decode($object));
        extract((array)$data);
        $patientProfile = $this->getPatientProfile($data);
        $doctor_Consult_table1 = $this->getDatadoctorSpeciality($data, $trans_date);
        $getRestoreHealth = $this->getRestoreHealth($data, $trans_date);
        if($doctor_Consult_table1 != ""){
			$doctor_Consult_table=$doctor_Consult_table1[1];
			$DoctorName="<div class='col-md-12 offset-10' style='font-size: 18px;'><b>Doctor Name :".$doctor_Consult_table1[0]."</b></div>";
		}else{
			$doctor_Consult_table="";
			$DoctorName="";

		}
        $Medication_table = $this->getDataMedication($data, $trans_date);
        $Investigation_table = $this->getDataInvestigation($data, $trans_date);
        $CaseHistory_table = $this->getDataCaseHistory($data, $trans_date);
        $PaymentInfo = $this->GetBillingData($data, $trans_date);

		$data ='<div class="col-md-12 offset-10 firstimgs	"><br><br><img alt="image" id="" style="width: 200px;height: 92px;" src="'.base_url().'/assets/img/rel_logo.jpg"  class="" ></div>';
        $data .= "<br>" . $patientProfile . "<br>" . $doctor_Consult_table . "<br>" . $Medication_table .
            "<br>" . $Investigation_table . "<br>" . $CaseHistory_table . "<br>". $getRestoreHealth . "<br>".$DoctorName. "<br>";
        $response['data'] = $data;
        $response['status'] = 200;
        echo json_encode($response);

    }

	function GetBillingData($data, $trans_date)
	{
		//get Total Bill
		extract((array)$data);

		/*$queryInvestigation = $this->db->query("select sum(rate) as Investigation from
service_master where id in(select service_id from opd_service_order where patient_id=" . $trans_date . "
AND branch_id=" . $branch_id . " AND status=1 AND add_to_bill='Yes') ");*/
		$queryInvestigation=$this->db->query("select sum(amount) as Investigation from opd_service_order where patient_id=" . $trans_date . " AND branch_id=" . $branch_id . " AND status=1 AND add_to_bill='Yes'");
		$investion = 0;
		//  echo $this->db->last_query();
		if ($this->db->affected_rows() > 0) {
			$investion = $queryInvestigation->row()->Investigation;
		}
		$querydoctorconsult = $this->db->query("select sum(amount) as dr_cons  from doctor_consult 
where patient_id=" . $trans_date . " AND branch_id=" . $branch_id . " ");
		$doctor_consult = 0;
		if ($this->db->affected_rows() > 0) {
			$doctor_consult = $querydoctorconsult->row()->dr_cons;
		}
		$patient_table = $this->session->user_session->patient_table;
		$queryPaidAmount = $this->db->query("select sum(amount) as paid_amount,
(select billing_discount from " . $patient_table . " where id=" . $trans_date . ") as discount_percent,
(select billing_dis_type from " . $patient_table . " where id=" . $trans_date . ") as discount_type from Opd_billing_table where patient_id=" . $trans_date . " AND branch_id=" . $branch_id);
		$paidAmount = 0;
		$discount_percent = 0;
		$discount_type = 1;
		$p_checked='checked';
		$d_checked='';
		// echo $this->db->last_query();
		if ($this->db->affected_rows() > 0) {
			$paidAmount = $queryPaidAmount->row()->paid_amount;
			$discount_percent = $queryPaidAmount->row()->discount_percent;
			$discount_type = $queryPaidAmount->row()->discount_type;
		}

		if (is_numeric($investion) && is_numeric($doctor_consult)) {
			$total = $investion + $doctor_consult;
		} else if (!is_numeric($investion)) {
			$total = $doctor_consult;
		} else if (!is_numeric($doctor_consult)) {
			$total = $investion;
		}
		$final_amount="0";

		if ($discount_percent == 0) {
			$discounted_value = 0;
		} else {
			if($discount_type==1)
			{
				$discounted_value = (($total / 100) * $discount_percent);
				$p_checked='checked';
				$final_amount = $total-$discounted_value;
			}
			else
			{
				$discounted_value = $total - $discount_percent;
				$d_checked='checked';
				$final_amount = $discounted_value;
			}

		}

		$html = '<div class="col-md-12"><table class="table table-bordered" style="width:50%">
                      <tr class="hide_class">
                        <th  style="border:1px solid #ccc;text-align:center;width:30% !important;background-color: rgba(0, 0, 0, 0.04);">Total Amount:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . $total . '</td>
                      </tr>
                      <tr class="hide_class">
                        <th  style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Discount Percent:</th>
                        <td  style="border:1px solid #ccc;text-align:center">100%</td>
                      </tr>
                      <tr>
                        <th style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Final Amount:</th>
                        <td  style="border:1px solid #ccc;text-align:center">0</td>
                      </tr>
                      <tr>
                        <th style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Paid Amount:</th>
                        <td  style="border:1px solid #ccc;text-align:center">0</td>
                      </tr>
                      <tr>
                        <th style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Net Amount Due:</th>
                        <td  style="border:1px solid #ccc;text-align:center">0</td>
                      </tr>
                    </table></div>';

/*		$html = '<div class="col-md-12"><table class="table table-bordered" style="width:50%">
                      <tr class="hide_class">
                        <th  style="border:1px solid #ccc;text-align:center;width:30% !important;background-color: rgba(0, 0, 0, 0.04);">Total Amount:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . $total . '</td>
                      </tr>
                      <tr class="hide_class">
                        <th  style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Discount Percent:</th>
                        <td  style="border:1px solid #ccc;text-align:center"><br>
                        <form id="percentage_form">
                        <input type="radio" name="discount" value="1" '.$p_checked.' id="per_btn"> Percentage <input type="radio" name="discount" value="2" id="amt_btn" '.$d_checked.'> Amount <input type="number" name="discount_amt" id="discount_amt" min="0" value="'.$discount_percent.'"> <button type="button" onclick="save_Percentage()" class="btn btn-link"><i class="fa fa-save"></i></button></form> </td>
                      </tr>
                      <tr>
                        <th style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Final Amount:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . ($final_amount) . '</td>
                      </tr>
                      <tr>
                        <th style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Paid Amount:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . $paidAmount . '</td>
                      </tr>
                      <tr>
                        <th style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Net Amount Due:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . ($final_amount - $paidAmount) . '</td>
                      </tr>
                    </table></div>';*/
		return $html;
	}

    function getPatientProfile($data)
    {
        extract((array)$data);
        $patient_table = $this->session->user_session->patient_table;
        $query = $this->db->query("select * from " . $patient_table . " where id=" . $patient_id . " AND branch_id=" . $branch_id);
        $html = "";
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $html .= '<table class="table table-bordered" style="width:100%">
                      <tr>
                        <th  style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Patient Name:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . $result->patient_name . '</td>
                      </tr>
                      <tr>
                        <th  style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Aadhar Number:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . $result->adhar_no . '</td>
                      </tr>
                      <tr>
                        <th style="border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);">Address:</th>
                        <td  style="border:1px solid #ccc;text-align:center">' . $result->address . '</td>
                      </tr>
                    </table>';
        } else {
            $html = "";
        }
        if ($html != "") {
            return "<div class='col-md-12'><h5 style='color: #891635'>Patient Profile:</h5>" . $html . "</div>";
        } else {
            return "";
        }
    }

    function getDataInvestigation($data, $trans_date)
    {
        extract((array)$data);
        $query = $this->db->query("select (select s.service_description from service_master s where s.id=q.service_id) as Service_name,
                date(q.order_date) as Order_date,q.notes as Notes
                from opd_service_order q where patient_id=" . $trans_date . " 
                AND branch_id=" . $branch_id . " AND status=1  order by id desc");
        $table = "";
        $table_td = "";
        $table_th = "";
        if ($this->db->affected_rows() > 0) {
            $q_result = $query->result();

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
									<table class="table table-bordered " style="width:100%"  id="">
									<thead>
									' . $table_th . '
									</thead>
									<tbody >
									' . $table_td . '
									</tbody>
									</table>
									';
        } else {
        }
        if ($table != "") {
            return "<div class='col-md-12'><h5 style='color: #891635'>Investigation:</h5>" . $table . "</div>";
        } else {
            return "";
        }

    }

    function getDataCaseHistory($data, $trans_date)
    {
        extract((array)$data);
        $query = $this->db->query("select symptoms as Symptoms,other_symptoms as Other_symptoms,
comorbidities as Comorbidities,(case when spo2 =0 then '' else spo2 end) as SPO2,(case when temperature =0 then '' else temperature end) as Temperature,
(case when weight=0 then '' else weight end) as Weight,(case when height=0 then '' else height end) as Height,vaccination as Vaccination,
allergy as Allergy,addiction as Addiction,transaction_date as Consultation_date
 from opd_patient_case_history  where patient_id=" . $trans_date . " AND branch_id=" . $branch_id . " order by id desc");
        $table = "";
        $table_td = "";

        if ($this->db->affected_rows() > 0) {
            $q_result = $query->result();
            $m = 0;
            $array_head = array();
            $array_data = array();
            //var_dump($q_result);
            $table_tr = "";
            $table_td1 = array();

            $table_td2 = array();


            foreach ($q_result as $row) {


                $array_data[] = array_values((array)$row);


                foreach ($row as $key => $r) {

                    if ($m == 0) {

                        $array_head[] = $key;
                        $table_tr .= "<th style='border:1px solid #ccc;text-align:center'>" . $key . "</th>";
                    }
                    $array_data[] = $r;
                    $table_td .= "<td style='border:1px solid #ccc;text-align:center'>" . $r . "</td>";
                    $table_td1[$key] = "<td style='border:1px solid #ccc;text-align:center'>" . $r . "</td>";

                }
                array_push($table_td2, $table_td1);

                $m++;

            }
            $t_new = "";
            foreach ($array_head as $data) {
                $t_new .= "<tr>";
                $t_new .= "<th style='border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);'>" . $data . "</th>";
                foreach ($table_td2 as $i) {
                    foreach ($i as $k => $pp) {

                        if ($k == $data) {
                            $t_new .= $pp;
                        }
                    }
                }
                $t_new .= "</tr>";
            }

            $table .= '
									<table class="table " style="width:100%"  id="">
									' . $t_new . '
									</table>
									';
        } else {
        }

        if ($table != "") {
			return "<div class='col-md-12 case_history'><h5 style='color: #891635'>Case History:   &nbsp;&nbsp;&nbsp;<input type='checkbox' class='case_history_checkbox' checked onchange='hideclassremover(this)'></h5>
            " . $table . "</div>";
        } else {
            return "";
        }
    }

    function getDataMedication($data, $trans_date)
    {
        extract((array)$data);
        $query = $this->db->query("select (select name from medicine_master where id=medicine_id) as Medicine_name,
                start_date as Start_ate,end_date as End_date,remark as Remark,transaction_date as Consultation_date from opd_medication where patient_id=" . $trans_date . "
                 AND branch_id=" . $branch_id . " AND status=1 order by id desc");
        $table = "";
        $table_td = "";
        $table_th = "";

        if ($this->db->affected_rows() > 0) {
            $q_result = $query->result();

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
									<table class="table table-bordered " style="width:100%"  id="">
									<thead>
									' . $table_th . '
									</thead>
									<tbody >
									' . $table_td . '
									</tbody>
									</table>
									';
        } else {
        }

        if ($table != "") {
            return "<div class='col-md-12'><h5 style='color: #891635'>Medicine Details:</h5>" . $table . "</div>";
        } else {
            return "";
        }
    }
	function getRestoreHealth($data, $trans_date)
	{
		extract((array)$data);
		$query = $this->db->query("select sec_38_f_213 as Meal_Type,sec_38_f_212 as Diet_type,sec_38_f_214 as Nutrition_Notes
		,sec_38_f_261 as Spirometry,sec_38_f_203 as Range_of_motion,sec_38_f_204 as Muscle_Strength,sec_38_f_206 as Breathing
		 from com_1_dep_16 where patient_id=" . $trans_date . "
                 AND branch_id=" . $branch_id . "  order by id desc");
		$table = "";
		$table_td = "";
		$table_th = "";

		if ($this->db->affected_rows() > 0) {
			$q_result = $query->result();

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
									<table class="table table-bordered " style="width:100%"  id="">
									<thead>
									' . $table_th . '
									</thead>
									<tbody >
									' . $table_td . '
									</tbody>
									</table>
									';
		} else {
		}

		if ($table != "") {
			return "<div class='col-md-12'><h5 style='color: #891635'>Restore Health:</h5>" . $table . "</div>";
		} else {
			return "";
		}
	}

    function getDatadoctorSpeciality($data, $trans_date)
    {
        extract((array)$data);
        $query = $this->db->query("select doctor_speciality as Doctor_speciality,doctor_name as Doctor_name,
patient_complaint as Patient_complaint,diagnosis as Diagnosis,treatment_plan as Treatment_plan,amount as Amount,
transaction_date as Consultation_date from doctor_consult where patient_id=" . $trans_date . " AND branch_id=" . $branch_id . " order by id desc");
        $table = "";
        $table_td = "";

        if ($this->db->affected_rows() > 0) {
            $q_result = $query->result();
            $m = 0;
            $array_head = array();
            $array_data = array();
            //var_dump($q_result);
            $table_tr = "";
            $table_td1 = array();

            $table_td2 = array();

			$Doctor_name="";
            foreach ($q_result as $row) {

				$Doctor_name=$row->Doctor_name;
                $array_data[] = array_values((array)$row);


                foreach ($row as $key => $r) {

                    if ($m == 0) {

                        $array_head[] = $key;
                        $table_tr .= "<th style='border:1px solid #ccc;text-align:center'>" . $key . "</th>";
                    }
                    $array_data[] = $r;
                    $table_td .= "<td style='border:1px solid #ccc;text-align:center'>" . $r . "</td>";
                    $table_td1[$key] = "<td style='border:1px solid #ccc;text-align:center'>" . $r . "</td>";

                }
                array_push($table_td2, $table_td1);

                $m++;

            }
            $t_new = "";
            foreach ($array_head as $data) {
                $t_new .= "<tr>";
                $t_new .= "<th style='border:1px solid #ccc;text-align:center;background-color: rgba(0, 0, 0, 0.04);'>" . $data . "</th>";
                foreach ($table_td2 as $i) {
                    foreach ($i as $k => $pp) {

                        if ($k == $data) {
                            $t_new .= $pp;
                        }
                    }
                }
                $t_new .= "</tr>";
            }

            $table .= '
									<table class="table " style="width:100%"  id="">
									' . $t_new . '
									</table>
									';
        } else {
        }
        if ($table != "") {

             $d="<div class='col-md-12'><h5 style='color: #891635'>Doctor Consult:</h5>" . $table . "</div>";
             return array($Doctor_name,$d);
        } else {
            return "";
        }

    }

    function GetallDatesofPatient()
    {
        $p_id = $this->input->post('p_id');
        $patient_adharnumber = $this->input->post('patient_adharnumber');
        $patient_table = $this->session->user_session->patient_table;
        $branch_id = $this->session->user_session->branch_id;
        $query = $this->db->query("select admission_date,admission_mode,id from " . $patient_table . "  where  adhar_no=" . $patient_adharnumber . " and branch_id=" . $branch_id . " order by id desc");

        $option = "";
        $optionIPD = "";
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {
                if ($row->admission_mode == 1) {
                    $option .= "<option value='" . $row->id . "'>" . date('Y-m-d', strtotime($row->admission_date)) . "</option>";
                }
                if ($row->admission_mode == 2) {
                    $optionIPD .= "<option value='" . $row->id . "'>" . date('Y-m-d', strtotime($row->admission_date)) . "</option>";
                }

            }
            $response['status'] = 200;
            $response['data'] = $option;
            $response['dataIPD'] = $optionIPD;
        } else {
            $response['status'] = 201;
            $response['data'] = "";
            $response['dataIPD'] = "";
        }
        echo json_encode($response);
    }


    function array_flatten($array)
    {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    function savePercentage()
    {
        $p_id = $this->input->post('p_id');
        $discountPercent = $this->input->post('discountPercent');
        $patient_table = $this->session->user_session->patient_table;
        $where = array("id" => $p_id);
        $set = array("discount_percent" => $discountPercent);
        $this->db->where($where);
        $update = $this->db->update($patient_table, $set);
        if ($update == true) {
            $response['status'] = 200;
        } else {
            $response['status'] = 201;
        }
        echo json_encode($response);

    }

    function GetSelectData()
    {
        $hashkey = $this->input->post('hashkey');
        $section_id = $this->input->post('section_id');
        $value = $this->input->post('value');
        $hashkey = ltrim($hashkey, "#");
        $query = $this->db->query("select * from htmlquerytable where section_id=" . $section_id . " AND field_id='" . $hashkey . "'");
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $array_string = $result->array_string;
            $exp = explode(",", $array_string);
            $f_c = $exp[0];
            $s_c = $exp[1];
            $table_name = $result->table_name;
            $query = $this->db->query("select " . $array_string . " from " . $table_name . " where " . $f_c . "=" . $value);

            if ($this->db->affected_rows() > 0) {
                $result = $query->row();
                $val = $result->$f_c;
                $opt = $result->$s_c;
                $response['id'] = $val;
                $response['status'] = 200;
                $response['option'] = $opt;
            } else {
                $response['id'] = "";
                $response['option'] = "";
            }
        } else {
            $response['id'] = "";
            $response['option'] = "";
        }
        echo json_encode($response);
    }
    function getAlladmitted_patients(){
        $patient_table = $this->session->user_session->patient_table;
        $branch_id = $this->session->user_session->branch_id;
        $query=$this->db->query("select id,(select category from com_1_bed c where c.id=bed_id) as category from ".$patient_table." where (discharge_date is null OR discharge_date = '0000-00-00 00:00:00')");

        if($this->db->affected_rows() > 0) {
            $result = $query->result();
            foreach ($result as $row) {

            $patient_id = $row->id;
            $category = $row->category;
            $data = $this->getCriticalParameterData($patient_id, $branch_id,$category);
            if ($data == true) {
                echo "added2";
            } else {
                echo "not added";
            }
        }
        }else{
            echo "not added1";
        }
    }
    function getCriticalParameterData($patient_id,$branch_id,$category)
    {
        $patient_table = $this->session->user_session->patient_table;
        $query = $this->db->query("select result,ParameterId,(select group_concat(patient_name,'|',adhar_no,'|',bed_id) from " . $patient_table . " where id=" . $patient_id . " AND branch_id=" . $branch_id . ") as patient_details,
(select group_concat(sec_1_f_8,'|',sec_1_f_16) from com_1_dep_1 where patient_id=" . $patient_id . " AND branch_id=" . $branch_id . " ) as case_history
 from excel_structure_data where patient_id=" . $patient_id . " AND branch_id=" . $branch_id . " 
 AND ParameterId COLLATE utf8mb4_general_ci in (select para_id from labparameter_table)");
//echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            $excelData = array();
            foreach ($result as $row) {
                $case_history = $row->case_history;
                $patient_details = $row->patient_details;
                $excelData[$row->ParameterId] = $row->result;
            }
            $cormobodies = "";
            $vaccination = "";
            $vaccination_sts=0;
            $cormobodies_sts=0;
            if ($case_history != null) {
                $explode1 = explode("|", $case_history);
                $cormobodies = $explode1[0];
                if (strpos($cormobodies, '953') !== false) { //diabetic
                    $cormobodies_sts=3;
                }else{
                    $cormobodies_sts=2;
                }
                if (strpos($cormobodies, '957') !== false) { //cancer
                    $cormobodies_sts=4;
                }else{
                    $cormobodies_sts=2;
                }
                $vaccination = $explode1[1];
                if($vaccination == 971){
                    $vaccination_sts=3;
                }else if($vaccination == 972){
                    $vaccination_sts=2;
                }else{
                    $vaccination_sts=4;
                }
            }
            $patient_name = "";
            $adhar_no = "";
            $bed_id = "";
            if ($patient_details != null) {
                $explode2 = explode("|", $patient_details);
                $patient_name = $explode2[0];
                $adhar_no = $explode2[1];
                $bed_id = $explode2[2];
            }
            $dataToinsert = array(
                "patient_id" => $patient_id,
                "branch_id" => $branch_id,
                "patient_name" => $patient_name,
                "adhar_no" => $adhar_no,
                "cormobodies_status" => $cormobodies_sts,
                "cormobodies" => $cormobodies,
                "vaccination_status" => $vaccination_sts,
                "vaccination" => $vaccination,
            );
            $queryGetData=$this->db->query("select * from labparameter_table");
            $array_data_min=array();
            $array_data_max=array();
            if($this->db->affected_rows() > 0){
                $resultQ=$queryGetData->result();
                foreach ($resultQ as $row){
                    $array_data_min[$row->para_id]=$row->min_range;
                    $array_data_max[$row->para_id]=$row->max_range;
                }
            }
            $count=0;

            foreach ($excelData as $key => $data) {

                if ($key == 6864) {
                    $dataToinsert['CRP'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_CRP_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_CRP_status'] = 'Yes';
                    }

                }
                if ($key == 9344) {
                    $dataToinsert['Haemoglobin'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_Haemoglobin_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_Haemoglobin_status'] = 'Yes';
                    }
                }
                if ($key == 9362) {
                    $dataToinsert['platelate_count'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_platelate_count_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_platelate_count_status'] = 'Yes';
                    }
                }
                if ($key == 8344) {
                    $dataToinsert['Interleukin'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_Interleukin_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_Interleukin_status'] = 'Yes';
                    }
                }
                if ($key == 6897) {
                    $dataToinsert['d_dimer'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_d_dimer_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_d_dimer_status'] = 'Yes';
                    }
                }
                if ($key == 9350) {
                    $dataToinsert['total_Leukocyte_count'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_total_Leukocyte_count_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_total_Leukocyte_count_status'] = 'Yes';
                    }
                }
                if ($key == 6654) {
                    $dataToinsert['ALTPT'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_ALTPT_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_ALTPT_status'] = 'Yes';
                    }
                }
                if ($key == 6655) {
                    $dataToinsert['ASTOT'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_ASTOT_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_ASTOT_status'] = 'Yes';
                    }
                }
                if ($key == 10016) {
                    $dataToinsert['HBS_ag'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_HBS_ag_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_HBS_ag_status'] = 'Yes';
                    }
                }
                if ($key == 6657) {
                    $dataToinsert['ALP'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_ALP_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_ALP_status'] = 'Yes';
                    }
                }
                if ($key == 6968) {
                    $dataToinsert['Ferritin'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_Ferritin_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_Ferritin_status'] = 'Yes';
                    }
                }
                if ($key == 9349) {
                    $dataToinsert['RDW'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_RDW_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_RDW_status'] = 'Yes';
                    }
                }
                if ($key == 6867) {
                    $dataToinsert['Creatinine'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_Creatinine_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_Creatinine_status'] = 'Yes';
                    }
                }
                if ($key == 7495) {
                    $dataToinsert['LDH'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_LDH_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_LDH_status'] = 'Yes';
                    }
                }
                if ($key == 7148) {
                    $dataToinsert['Neutrophils'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_Neutrophils_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_Neutrophils_status'] = 'Yes';
                    }
                }
                if ($key == 7145) {
                    $dataToinsert['Neutrophils_hash'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_Neutrophils_hash_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_Neutrophils_hash_status'] = 'Yes';
                    }
                }
                if ($key == 7053) {
                    $dataToinsert['HbA1c'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_HbA1c_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_HbA1c_status'] = 'Yes';
                    }
                }
                if ($key == 7373) {
                    $dataToinsert['troponin_I_hrs'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_troponin_I_hrs_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_troponin_I_hrs_status'] = 'Yes';
                    }
                }
                if ($key == 7374) {
                    $dataToinsert['troponin_T_hrs'] = $data;
                    if($data >= $array_data_min[$key] && $data <= $array_data_max[$key]){
                        $dataToinsert['Blood_troponin_T_hrs_status'] = 'No';
                        $count += 1;
                    }else{
                        $dataToinsert['Blood_troponin_T_hrs_status'] = 'Yes';
                    }
                }
            }

            $daviation_percent=($count/21)*100;
            if($daviation_percent > 50){

                $dataToinsert['blood_test_result']=4;//Very high
            }elseif($daviation_percent <= 50 && $daviation_percent > 30){
                $dataToinsert['blood_test_result']=3;//high

            }else if($daviation_percent <= 30 && $daviation_percent > 20){
                $dataToinsert['blood_test_result']=2;//medium
            }else{
                $dataToinsert['blood_test_result']=1;//Low i.e. No daviation
            }
            //get data from second database (Vital Data)
            if($category == 2){
                if($bed_id != ""){
                    $query2=$this->db2->query("select SPO2,RR_RPM,HR_BPM,NIBP from patient_monitor_live where patient_id=".$bed_id." order by id desc");
                    if($this->db2->affected_rows() > 0){
                        $res=$query2->row();
                        $dataToinsert['SPO2'] = $res->SPO2;
                        if($res->SPO2 >= 95){
                            $dataToinsert['SPO2_status'] = 1; //low
                        }else if($res->SPO2 >= 90 && $res->SPO2 < 95){
                            $dataToinsert['SPO2_status'] = 2; //medium
                        }else if($res->SPO2 < 90 && $res->SPO2 >= 85){
                            $dataToinsert['SPO2_status'] = 3; //high
                        }else if($res->SPO2 < 85){
                            $dataToinsert['SPO2_status'] = 4; //Very high
                        }

                        $dataToinsert['RR'] = $res->RR_RPM;//PR
                        if($res->RR_RPM >= 12 && $res->RR_RPM <= 16){
                            $dataToinsert['RR_status'] = 1;//low
                        }else {
                            $dataToinsert['RR_status'] = 3; //Very High
                        }

                        $dataToinsert['HR'] = $res->HR_BPM;
                        if($res->HR_BPM >= 60 && $res->HR_BPM <= 100){
                            $dataToinsert['HR_status'] = 1;//low
                        }else if($res->HR_BPM < 60){
                            $dataToinsert['HR_status'] = 4; //Very High
                        }elseif($res->HR_BPM > 100 ){
                            $dataToinsert['HR_status'] = 3; //High
                        }
                    }
                }
            }else{
                $query2=$this->db->query("select sec_2_f_4,sec_2_f_21,sec_2_f_20,sec_2_f_347 from com_1_dep_2 where patient_id=".$patient_id." order by id desc");
                if($this->db->affected_rows() > 0) {
                    $res = $query2->row();
                    $dataToinsert['SPO2'] = $res->sec_2_f_4;
                    if ($res->sec_2_f_4 >= 95) {
                        $dataToinsert['SPO2_status'] = 1; //low
                    } else if ($res->sec_2_f_4 >= 90 && $res->sec_2_f_4 < 95) {
                        $dataToinsert['SPO2_status'] = 2; //medium
                    } else if ($res->sec_2_f_4 < 90 && $res->sec_2_f_4 >= 85) {
                        $dataToinsert['SPO2_status'] = 3; //high
                    } else if ($res->sec_2_f_4 < 85) {
                        $dataToinsert['SPO2_status'] = 4; //Very high
                    }

                    $dataToinsert['HR'] = $res->sec_2_f_20;//HR
                    if($res->sec_2_f_20 >= 60 && $res->sec_2_f_20 <= 100){
                        $dataToinsert['HR_status'] = 1;//low
                    }else if($res->sec_2_f_20 < 60){
                        $dataToinsert['HR_status'] = 4; //Very High
                    }elseif($res->sec_2_f_20 > 100 ){
                        $dataToinsert['HR_status'] = 3; //High
                    }
                    $dataToinsert['RR'] = $res->sec_2_f_20;//HR
                    if($res->sec_2_f_20 >= 12 && $res->sec_2_f_20 <= 16){
                        $dataToinsert['RR_status'] = 1;//low
                    }else {
                        $dataToinsert['RR_status'] = 3; //Very High
                    }
                    $dataToinsert['OS']=$res->sec_2_f_347;
					if($res->sec_2_f_347 == 1565){
						$dataToinsert['OS_status'] = 1;//low
					}else if($res->sec_2_f_347 >= 1566 && $res->sec_2_f_347 <= 1569) {
						$dataToinsert['OS_status'] = 2; // Mediuam
					}else if($res->sec_2_f_347 >= 1570 && $res->sec_2_f_347 <= 1580){
						$dataToinsert['OS_status'] = 3; // High
					}else{
						$dataToinsert['OS_status'] = 0; // High
					}
                }
            }

            $where=array("patient_id"=>$patient_id,"branch_id"=>$branch_id);
            $this->db->delete("patient_critical_data",$where);
            $insert=$this->db->insert("patient_critical_data",$dataToinsert);
            if($insert == true){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

	function labReportGeneration1($patient_id,$order_id)
	{
		// $patient_id = $this->input->post('patient_id');
		$html='';
		$branch_id = $this->session->user_session->branch_id;
		$patient_data=$this->db->query('select * from lab_patient where id="'.$patient_id.'" and status=1');
		if($this->db->affected_rows()>0)
		{
			$patient_data=$patient_data->row();
			$age='';
			if($patient_data->birth_date!=null && $patient_data->birth_date!='0000-00-00 00:00:00')
			{
				$age= date_diff(date_create($patient_data->birth_date), date_create('today'))->y;
			}
			if($patient_data->gender==1)
			{
				$gender='Male';
			}else if($patient_data->gender==2)
			{
				$gender='Female';
			}
			else
			{
				$gender='Other';
			}
			$admission_date='';
			if($patient_data->admission_date!=null && $patient_data->admission_date!='0000-00-00 00:00:00')
			{
				$admission_date= date("d/M/Y", strtotime($patient_data->admission_date));
			}
			// $today= date("d/M/Y H:i A", strtotime(date('Y-m-d H:i:s A')));
			$today= date('d/M/Y H:i A', time());
			// exit;
			$sample_type='';
			$sample_collect_on='';
			$order_data=$this->db->query('select t1.*,(case when service_type=1 then (select sm.service_name from setup_lab_service_master sm where sm.id=t1.service_id) else (select mp.package_name from master_package mp where mp.id=t1.service_id) end) as service_name from lab_patient_serviceorder t1 where t1.patient_id="'.$patient_id.'" and t1.id="'.$order_id.'"');
			if($this->db->affected_rows()>0)
			{
				$order_data=$order_data->row();
				$sample_collect_on= date("d/M/Y H:i A", strtotime($order_data->created_on));
				$sample_type=$order_data->service_name;
			}
			// print_r($patient_data->result());exit();
			$html .= '<!DOCTYPE html>
                <html>
    <head>
        <style>
            @page{
               // margin-top: 100px; /* create space for header */
                margin-bottom: 70px; /* create space for footer */
                font-family: "Times New Roman", Times, serif;
            }
            header, footer{
                position: fixed;
                left: 0px;
                right: 0px;
            }
            header{
                height: 60px;
                margin-top: -44px;
                margin-left: -43px;
                margin-right: -43px;
            }
            header{
                height: 20px;
                margin-top: -44px;
                margin-left: -43px;
                margin-right: -43px;
            }
            hr {
            position: relative;
            top: 0px;
            border: none;
            height: 0.7px;
            background: black;
            margin-bottom: 5px;
        }
        .righttd{
        padding-right: 30px;
        font-size: 12px;
       
        }
        .lefttd{
        padding-right: 50px;
        font-size: 12px;
        }
        .testResult th{
        padding-right: 50px;
        text-align: center;
        font-size: 15px;
        }
        .testResult td{
        font-size: 12px;
        }
      
        </style>
        </head>
        <body>
            <header style="background-color:#019fa8;height: 8%">
                <div class="row" style="height: 50px;width: 50px" >
                <img src="'.base_url().'assets/healthlogo.png" id="site-logo">
                </div>
            </header>
            
            <main>
                
                <div style="page-break-after: always;margin-top: 5%;">
                <hr>
                <table class="table">
                <tr>
                <td class="righttd" >Patient Name</td><td class="lefttd" >:'.$patient_data->patient_name.'</td>
                <td class="righttd" >Sample Collected On</td><td class="lefttd">:'.$sample_collect_on.'</td>
                </tr>
                <tr>
                <td class="righttd" >Age/Gender</td><td class="lefttd" >:'.$age.'/'.$gender.'</td>
                <td class="righttd" >Sample Received On</td><td class="lefttd">:19/Aug/2021 12:47PM</td>
                </tr>
                <tr>
                <td class="righttd" >Order Id</td><td class="lefttd" >:'.$order_id.'</td>
                <td class="righttd" >Report Generated On</td><td class="lefttd">:'.$today.'</td>
                </tr>
                <tr>
                <td class="righttd" >Referred By</td><td class="lefttd" >:Self</td>
                <td class="righttd" >Sample Temperature</td><td class="lefttd">:Maintained</td>
                </tr>
                <tr>
                <td class="righttd" >Customer Since </td><td class="lefttd" >:'.$admission_date.'</td>
                <td class="righttd" >ReportStatus</td><td class="lefttd">:Final Report</td>
                </tr>
                <tr>
                <td class="righttd" >Sample Type</td><td class="lefttd" >:'.$sample_type.'</td>
                </tr>
                </table>
                ';
			$master_data=$this->db->select('`t1`.*,(case when order_type=1 then (select sm.name 
                        from lab_child_test sm where sm.id=t1.child_test_id) else
                         (select mp.child_test_name from set_master_package mp where mp.id=t1.child_test_id) end) as child_name, 
                         (case when order_type=1 then (select sm.service_name from setup_lab_service_master sm where sm.id=t1.order_id) else
                         (select mp.package_name from master_package mp where mp.id=t1.order_id) end) as master_name,(case when order_type=1 then
                         (select ld.name from lab_department_master ld where ld.id in (select lmt.dep_id from lab_master_test lmt where lmt.id in
                         (select sm.master_id from lab_child_test sm where sm.id=t1.id))) else (select ld.name from lab_department_master ld where ld.id in 
                         (select lmt.dep_id from lab_master_test lmt where lmt.id in (select sm.master_id from lab_child_test sm where sm.id in 
                         (select smp.child_test_id from set_master_package smp where smp.id=t1.id)))) end) 
                         as department_name')->where(array('patient_id'=>$patient_id,'order_id'=>$order_id))->get('lab_test_data_entry t1');
			$department_array=array();
			if($this->db->affected_rows()>0)
			{
				$master_data=$master_data->result();
				foreach ($master_data as $key => $item) {
					$department_array[$item->department_name][$item->master_name][]=$item;
				}

			}

			foreach ($department_array as $key => $item) {
				$html.='
                    <hr>    
                    <center><h4>'.$key.'</h4></center>
                    <table class="testResult" align="left">
                        <thead>
                            <tr>
                                <th style="">Test Name</th>
                                <th>Value</th>
                                <th>Unit</th>
                                <th>Bio. Ref Interval </th>
                            </tr>                
                        </thead>                   
                        <tbody>';

				foreach ($item as $m_key => $m_value) {


					$html.='<tr>
                                <td style="font-weight: bold;font-size: 14px">'.$m_key.'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';

					foreach ($m_value as $key => $c_value) {
						$html.='<tr>
                                <td style="word-break: break-word;font-size:12px;">'.$c_value->child_name.' <br/>Method: HPLC</td>
                                <td style="text-align:center;">'.$c_value->value.'</td>
                                <td >'.$c_value->unit.'</td>
                                <td >'.$c_value->refe_value.'</td>
                            </tr>
                            <tr><td></td><td></td><td></td><td></td></tr>
                            <tr>
                                <td colspan="4"> Average Estimated Glucose - plasma Average Estimated Glucose - plasma Average Estimated Glucose - plasma Average Estimated Glucose - plasma Average Estimated Glucose - plasma Average Estimated Glucose - plasma</td>
                                
                            </tr>
                             <tr><td></td><td></td><td></td><td></td></tr>';
					}
				}
				$html.='
                        </tbody>
                    </table>
                    ';
			}
			$html.='
                </div>
            </main>
            
        </body>
    </html>
        ';
		}

		return $html;
	}
	function labReportGeneration2($patient_id,$order_id)
	{
		$html='';
		$branch_id = $this->session->user_session->branch_id;
		$patient_data=$this->db->query('select * from lab_patient where id="'.$patient_id.'" and status=1');
		if($this->db->affected_rows()>0)
		{
			$patient_data=$patient_data->row();
			$age='';
			if($patient_data->birth_date!=null && $patient_data->birth_date!='0000-00-00 00:00:00')
			{
				$age= date_diff(date_create($patient_data->birth_date), date_create('today'))->y;
			}
			if($patient_data->gender==1)
			{
				$gender='Male';
			}else if($patient_data->gender==2)
			{
				$gender='Female';
			}
			else
			{
				$gender='Other';
			}
			$admission_date='';
			if($patient_data->admission_date!=null && $patient_data->admission_date!='0000-00-00 00:00:00')
			{
				$admission_date= date("d/M/Y", strtotime($patient_data->admission_date));
			}
			$today= date('d/M/Y H:i A', time());
			// exit;
			$sample_type='';
			$sample_collect_on='';
			$order_data=$this->db->query('select t1.*,(case when service_type=1 then (select sm.service_name from setup_lab_service_master sm where sm.service_id=t1.service_id and sm.branch_id="'.$branch_id.'") else (select mp.package_name from master_package mp where mp.id=t1.service_id) end) as service_name from lab_patient_serviceorder t1 where t1.patient_id="'.$patient_id.'" and t1.id="'.$order_id.'"');
			if($this->db->affected_rows()>0)
			{
				$order_data=$order_data->row();
				$sample_collect_on= date("d/M/Y H:i A", strtotime($order_data->created_on));
				$sample_type=$order_data->service_name;
			}
			$path=base_url()."assets/healthlogo.png";
			$html .= '<!DOCTYPE html>
                <html>
            <head>
                <style>
                    @page{
                       // margin-top: 100px; /* create space for header */
                        margin-bottom: 63px; /* create space for footer */
                        font-family: "Times New Roman", Times, serif;
                        padding:0px;
                        // margin-left:0px;
                        // margin-right:0px;
                    }
                    header, footer{
                        position: fixed;
                        left: 0px;
                        right: 0px;
                    }
                    header{
                        height: 60px;
                        margin-top: -45px;
                        margin-left: -45px;
                        margin-right: -45px;
                    }
                    footer{
                        height: 20px;
                        bottom: -60px; 
                        margin-left: -45px;
                        margin-right: -45px;
                    }
                    hr {
                    position: relative;
                    top: 0px;
                    border: none;
                    height: 0.7px;
                    background: black;
                    margin-bottom: 5px;
                }
                .righttd{
                padding-right: 30px;
                font-size: 12px;
               
                }
                .lefttd{
                padding-right: 50px;
                font-size: 12px;
                }
                .testResult th{
                padding-right: 50px;
                text-align: center;
                font-size: 15px;
                }
                .testResult td{
                font-size: 12px;
                }
              
                </style>
                </head>
                <body>
                    <header style="background-color:#019fa8;height: 8%">
                        <div class="row" style="height: 50px;width: 50px;padding-left:20px;" >
                        <img src="'.$path.'" id="site-logo">
                        </div>
                     </header>
                    <footer style="background-color:#019fa8;"></footer>
                   
                        
                        ';
			$master_data=$this->db->select('`t1`.*, (case when order_type=1 then (select sm.name from lab_child_test sm where sm.id=t1.child_test_id) 
                            else (select mp.child_test_name from set_master_package mp where mp.id=t1.child_test_id) end) as child_name, 
                            (case when order_type=1 then (select sm.service_name from setup_lab_service_master sm where sm.id=t1.order_id) else
                             (select mp.package_name from master_package mp where mp.id=t1.order_id) end) as master_name, 
                             (case when order_type=1 then (select ld.name from lab_department_master ld where ld.id in (select lmt.dep_id from lab_master_test lmt where lmt.id in 
                             (select sm.master_id from lab_child_test sm where sm.id=t1.id))) else
                             (select ld.name from lab_department_master ld where ld.id in
                             (select lmt.dep_id from lab_master_test lmt where lmt.id in
                             (select sm.master_id from lab_child_test sm where sm.id in 
                             (select smp.child_test_id from set_master_package smp where smp.id=t1.id)))) end) as department_name,
                             (select method from lab_child_test lct where id = (select smp.child_test_id from set_master_package smp where smp.id=t1.id)) as method,
                             (SELECT lmt.description FROM lab_master_test lmt where lmt.id = (select master_id from lab_child_test lct where lct.id = (select smp.child_test_id from set_master_package smp where smp.id=t1.id))) as description')->where(array('patient_id'=>$patient_id,'order_id'=>$order_id))->get('lab_test_data_entry t1');
			// print_r($this->db->last_query());exit();
			$department_array=array();
			if($this->db->affected_rows()>0)
			{
				$master_data=$master_data->result();
				foreach ($master_data as $key => $item) {
					$department_array[$item->department_name][$item->master_name][]=$item;
				}

			}
			$html.='<main>
                                <div>';
			$count=count($department_array);
			$cnt=0;
			foreach ($department_array as $key => $item) {
				$cnt++;
				$html.='
                        
                       <hr/>
                        <table class="table" style="margin-top: 5%;">
                        <tr>
                        <td class="righttd" >Patient Name</td><td class="lefttd" >:'.$patient_data->patient_name.'</td>
                        <td class="righttd" >Sample Collected On</td><td class="lefttd">:'.$sample_collect_on.'</td>
                        </tr>
                        <tr>
                        <td class="righttd" >Age/Gender</td><td class="lefttd" >:'.$age.'/'.$gender.'</td>
                        <td class="righttd" >Sample Received On</td><td class="lefttd">:'.$today.'</td>
                        </tr>
                        <tr>
                        <td class="righttd" >Order Id</td><td class="lefttd" >:'.$order_id.'</td>
                        <td class="righttd" >Report Generated On</td><td class="lefttd">:'.$today.'</td>
                        </tr>
                        <tr>
                        <td class="righttd" >Referred By</td><td class="lefttd" >:Self</td>
                        <td class="righttd" >Sample Temperature</td><td class="lefttd">:Maintained</td>
                        </tr>
                        <tr>
                        <td class="righttd" >Customer Since </td><td class="lefttd" >:'.$admission_date.'</td>
                        <td class="righttd" >ReportStatus</td><td class="lefttd">:Final Report</td>
                        </tr>
                        <tr>
                        <td class="righttd" >Sample Type</td><td class="lefttd" >:'.$sample_type.'</td>
                        </tr>
                        </table>
                       ';
				$html.='
                            <hr>    
                            <center><h4>'.$key.'</h4></center>
                            <table class="testResult" align="left">
                                <thead>
                                    <tr>
                                        <th style="width:40%">Test Name</th>
                                        <th style="width:10%">Value</th>
                                        <th style="width:20%">Unit</th>
                                        <th style="width:30%">Bio. Ref Interval </th>
                                    </tr>                
                                </thead>                   
                                <tbody>';

				foreach ($item as $m_key => $m_value) {


					$html.='<tr>
                                        <td style="font-weight: bold;font-size: 14px">'.$m_key.'</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>';

					foreach ($m_value as $key => $c_value) {
						$html.='<tr>
                                        <td style="word-break: break-word;font-size:12px;">'.$c_value->child_name.' <br/>Method: '.$c_value->method.'</td>
                                        <td>'.$c_value->value.'</td>
                                        <td >'.$c_value->unit.'</td>
                                        <td >'.$c_value->refe_value.'</td>
                                    </tr>
                                    <tr><td></td><td></td><td></td><td></td></tr>
                                    <tr>
                                        <td colspan="4"> '.$c_value->description.'</td>
                                        
                                    </tr>
                                     <tr><td></td><td></td><td></td><td></td></tr>';
					}
				}
				$html.='
                                </tbody>
                            </table>
                            ';
				if($cnt<$count){
					$html.='<div style="page-break-after:always;"> </div>';
				}
			}
			$html.='
                        </div>
                    </main>
                    
                </body>
            </html>
        ';
		}

		return $html;
	}

	public function load_view($data)
	{
		$patient_data=base64_decode($data);
		$patient_data=json_decode($patient_data);
		// print_r($patient_data);exit();
		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new Dompdf($options);
		$html = $this->labReportGeneration($patient_data->patient_id,$patient_data->service_id);

		$dompdf->loadHtml($html);
		$dompdf->set_base_path("https://c19.docango.com/");
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', '');

		// Render the HTML as PDF
		$dompdf->render();
		$time = time();

		// Output the generated PDF to Browser
		$dompdf->stream("welcome-" . $time, array("Attachment" => false));
		exit(0);
	}

	function deleteMasterTest()
	{
		$master_id = $_POST['master_id'];
		$checkservice = $this->db->query("SELECT * FROM lab_patient_serviceorder WHERE master_id = ".$master_id." ");
		if($this->db->affected_rows()>0)
		{
			echo 0;
		}
		else
		{
			$deletequery = $this->db->query("DELETE FROM lab_master_test WHERE id =".$master_id." ");
			echo 1;
		}
	}

	function labReportGeneration($patient_id,$order_id)
	{
		$html='';
		$branch_id = $this->session->user_session->branch_id;
		$patient_data=$this->db->query('select * from lab_patient where id="'.$patient_id.'" and status=1');
		if($this->db->affected_rows()>0)
		{
			$patient_data=$patient_data->row();
			$age='';
			if($patient_data->birth_date!=null && $patient_data->birth_date!='0000-00-00 00:00:00')
			{
				$age= date_diff(date_create($patient_data->birth_date), date_create('today'))->y;
			}
			if($patient_data->gender==1)
			{
				$gender='Male';
			}else if($patient_data->gender==2)
			{
				$gender='Female';
			}
			else
			{
				$gender='Other';
			}
			$admission_date='';
			if($patient_data->admission_date!=null && $patient_data->admission_date!='0000-00-00 00:00:00')
			{
				$admission_date= date("d/M/Y", strtotime($patient_data->admission_date));
			}
			// $today= date("d/M/Y H:i A", strtotime(date('Y-m-d H:i:s A')));
			$today= date('d/M/Y H:i A', time());
			// exit;
			$sample_type='';
			$sample_collect_on='';
			$order_data=$this->db->query('select t1.*,(case when service_type=1 then (select sm.service_name from setup_lab_service_master sm where sm.service_id=t1.service_id and sm.branch_id="'.$branch_id.'") else (select mp.package_name from master_package mp where mp.id=t1.service_id) end) as service_name from lab_patient_serviceorder t1 where t1.patient_id="'.$patient_id.'" and t1.id="'.$order_id.'"');
			//echo $this->db->last_query();
			if($this->db->affected_rows()>0)
			{
				$order_data=$order_data->row();
				$sample_collect_on= date("d/M/Y H:i A", strtotime($order_data->created_on));
				$sample_type=$order_data->service_name;
			}
			else
			{
				echo "HI";
				exit();
			}
			//print_r($patient_data->result());exit();

			$path = base_url(). "assets/healthlogo.png";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$imagedata = file_get_contents($path);
			$base64 = "data:image/" . $type . ";base64," . base64_encode($imagedata);
			//$base64image = " <img src =' ".$path." '  id='site-logo'>";

			$html .= '<!DOCTYPE html>
                <html>
    <head>
        <style>
            @page{
               // margin-top: 100px; /* create space for header */
                margin-bottom: 70px; /* create space for footer */
                font-family: "Times New Roman", Times, serif;
            }
            header{
                position: fixed;
                left: 0px   ;
                right: 0px;
            }
            header{
                height: 60px;
                margin-top: -44px;
                margin-left: -43px;
                margin-right: -43px;
            }
            footer{
                height: 20px;
                position: fixed;
                bottom:0;
                left: 0px   ;
                right: 0px;
                margin-left: -43px;
                margin-right: -43px;
            }
            .footer_address{
            margin-left: 22px;
            }

           
            hr {
            position: relative;
            top: 0px;
            border: none;
            height: 0.7px;
            background: black;
            margin-bottom: 5px;
        }
        .righttd{
        padding-right: 30px;
        font-size: 12px;
       
        }
        .lefttd{
        padding-right: 50px;
        font-size: 12px;
        text-transform : capitalize;
        }
        .testResult th{
        padding-right: 50px;
        text-align: center;
        font-size: 15px;
        }
        .testResult td{
        font-size: 12px;
        }

        .testResult{
        position:relative;
        top: 250px;
            text-align: center;
            margin : 0 auto 0 auto;
        }
        .table{
            margin-left : 120px;
            margin-top : 30px;
        }

        .hr_style1{
            margin-top: 15px;
        }
        .departmentname{
            text-transform : uppercase;
            margin-top : 0px;
        }
        .testResult_header{
            width: 20%;
            text-align: left;
        }
        .testResult_header1{
            width: 40%;
            text-align: center;
        }
        .child_test_key{
            font-weight: bold;
            text-align:left;
            padding-bottom: 20px;
        }

        .child_test{
            width:20%;
            padding-bottom: 20px;
        }
        .child_test1{
            width:40%;
            padding-bottom: 20px;
        }
        .child_test2{
            width:20%;
            word-break: break-word;
            text-align:left;
            font-size:12px;
            padding-bottom: 20px;
        }
      

        </style>
        </head>
        <body>
            <header style="background-color:#019fa8;height: 8%">
                <div class="row" style="height: 50px;width: 50px" >
                <img src ="'.$path.'"  id="site-logo">
                </div>



                <hr style="margin-top:40px;">
                    <table class="table">
                    <tr>
                    <td class="righttd" >Patient Name</td><td class="lefttd" >:'.$patient_data->patient_name.'</td>
                    <td class="righttd" >Sample Collected On</td><td class="lefttd">:'.$sample_collect_on.'</td>
                    </tr>
                    <tr>
                    <td class="righttd" >Age/Gender</td><td class="lefttd" >:'.$age.'/'.$gender.'</td>
                    <td class="righttd" >Sample Received On</td><td class="lefttd">:19/Aug/2021 12:47PM</td>
                    </tr>
                    <tr>
                    <td class="righttd" >Order Id</td><td class="lefttd" >:'.$order_id.'</td>
                    <td class="righttd" >Report Generated On</td><td class="lefttd">:'.$today.'</td>
                    </tr>
                    <tr>
                    <td class="righttd" >Referred By</td><td class="lefttd" >:Self</td>
                    <td class="righttd" >Sample Temperature</td><td class="lefttd">:Maintained</td>
                    </tr>
                    <tr>
                    <td class="righttd" >Customer Since </td><td class="lefttd" >:'.$admission_date.'</td>
                    <td class="righttd" >ReportStatus</td><td class="lefttd">:Final Report</td>
                    </tr>
                    <tr>
                    <td class="righttd" >Sample Type</td><td class="lefttd" >:'.$sample_type.'</td>
                    </tr>
                    </table>
                    <hr style="margin-top:20px;">

                     <center><h4>'.$this->session->userdata('name').'</h4></center>

            </header>

            
            <footer>
            <div class="footer_address">
            <p>The Test was Performed by Healthians Lab - Ground Floor Unit No. 16/C, Dattani Plaza, Unit No. 716 C wing, Safedpool,
            opposite Saki Naka, Telephone exchange, Andheri (East), Mumbai 400059, signed by Lab Pathologist.</p>
            </div>
            <div style="background-color:#019fa8;width:100%;height: 40px;">
            </div>
            </footer>
            
            <main>
                
                <div style="page-break-after: always;margin-top: 5%;">
                
                ';
			$master_data=$this->db->select('`t1`.*, (select sm.unit from lab_child_test sm where sm.id=t1.child_test_id) as unit,(case when order_type=1 then (select sm.name from lab_child_test sm where sm.id=t1.child_test_id) 
                    else (select mp.child_test_name from set_master_package mp where mp.id=t1.child_test_id) end) as child_name, 
                    (case when order_type=1 then (select sm.service_name from setup_lab_service_master sm where sm.id=t1.order_id) else
                     (select mp.package_name from master_package mp where mp.id=t1.order_id) end) as master_name, 
                     (case when order_type=1 then (select ld.name from lab_department_master ld where ld.id in (select lmt.dep_id from lab_master_test lmt where lmt.id in 
                     (select sm.master_id from lab_child_test sm where sm.id=t1.id))) else
                     (select ld.name from lab_department_master ld where ld.id in
                     (select lmt.dep_id from lab_master_test lmt where lmt.id in
                     (select sm.master_id from lab_child_test sm where sm.id in 
                     (select smp.child_test_id from set_master_package smp where smp.id=t1.id)))) end) as department_name,
                     (select method from lab_child_test lct where id = (select smp.child_test_id from set_master_package smp where smp.id=t1.id)) as method,
                     (SELECT lmt.description FROM lab_master_test lmt where lmt.id = (select master_id from lab_child_test lct where lct.id = (select smp.child_test_id from set_master_package smp where smp.id=t1.id))) as description')->where(array('patient_id'=>$patient_id,'order_id'=>$order_id))->get('lab_test_data_entry t1');
			//print_r($this->db->last_query());exit();


			$department_array=array();
			if($this->db->affected_rows()>0)
			{
				$master_data=$master_data->result();
				foreach ($master_data as $key => $item) {

					$department_array[$item->department_name][$item->master_name][]=$item;
				}

			}
			foreach ($department_array as $key => $item) {
				$main_name = array('name' => $key);
				$this->session->set_userdata($main_name);

				$html.='
                    <table class="testResult" style="page-break-after:always;width:100%;">
                          <thead>
                            <tr>
                                <th class="testResult_header2" style="text-align:left;">Test Name</th>
                                <th class="testResult_header">Value</th>
                                <th class="testResult_header">Unit</th>
                                <th class="testResult_header1">Bio. Ref Interval </th>
                            </tr>                
                        </thead>              
                        <tbody>';

				foreach ($item as $m_key => $m_value) {


					$html.='<tr>
                                <td class="child_test_key" style="font-size:14px;">'.$m_key.'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';

					foreach ($m_value as $key => $c_value) {
						// $descriptionvalue = array('description' => $c_value->description);
						// $this->session->set_userdata($descriptionvalue);

						$html.='<tr>
                                <td class="child_test2">'.$c_value->child_name.' <br/>Method: HPLC</td>
                                <td class="child_test">'.$c_value->value.'</td>
                                <td class="child_test">'.$c_value->unit.'</td>
                                <td class="child_test1">'.$c_value->refe_value.'</td>
                            </tr>
                             <tr><td></td><td></td><td></td><td></td></tr>';
					}
				}
				$html.='
                        </tbody>
                    </table>
                    ';
			}
			$html.='
                </div>
            </main>
        </body>
    </html>
        ';
		}

		return $html;
	}


	function saveOpdTableData()
	{
		$header = $this->is_parameter(array("data"));
		if ($header->status) {
			$data = json_decode($header->param->data);
			$insertBatch = array();
			$updateid = "";
			foreach ($data as $object)
			{
				if(!empty($object->id)){
					$opd_update_data = array(
						"branch_id" => $this->session->user_session->branch_id,
						"user_id" => $this->session->user_session->id,
						"name" => $object->name,
						"doctor_name" => $object->doctor_name,
						"date" => $object->date,
						"status" => 1
					);
					$updateid  = $object->id;
					$this->db->where('id',$updateid);
					$update_data  = $this->db->update('opd_standard_prescription',$opd_update_data);
				}else{
					$opd_Data = array(
						"branch_id" => $this->session->user_session->branch_id,
						"user_id" => $this->session->user_session->id,
						"name" => $object->name,
						"doctor_name" => $object->doctor_name,
						"date" => $object->date,
						"status" => 1
					);
					array_push($insertBatch, $opd_Data);
				}
			}
			if(!empty($insertBatch))
			{
				$insert_data = $this->db->insert_batch('opd_standard_prescription',$insertBatch);
				$response["status"] = 200;
				$response["body"] = "Data Inserted";
			}
			else if($update_data)
			{
				$response["status"] = 200;
				$response["body"] = "Data was Updated";
			}
			else
			{
				$response["status"] = 201;
				$response["body"] = "Data was Not Saved";
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Something went wrong";
		}
		echo json_encode($response);
	}

	function getOpdTableData()
	{
		$data = $this->getOPD_Data();
		if(count($data)>0)
		{
			$opd_data = array();
			foreach($data as $row)
			{
				$date = date('m/d/Y',strtotime($row->date));
				$opd_data1 = array(
					"name" => $row->name,
					"doctor_name" => $row->doctor_name,
					"date" => $date,
					"id" => $row->id
				);
				array_push($opd_data, $opd_data1);
			}
			$response["status"] = 200;
			$response["body"] = "Data";
			$response['data'] = $opd_data;
		}else{
			$response["status"] = 201;
			$response["body"] = "No Data Found";
			$response["data"] = "";
		}
		echo json_encode($response);
	}

	function getOPD_Data()
	{
		$branch_id = $this->session->user_session->branch_id;
		$this->db->select('*')
			->where('branch_id',$branch_id)
			->from('opd_standard_prescription');
		$query = $this->db->get();
		return $query->result();
	}

}


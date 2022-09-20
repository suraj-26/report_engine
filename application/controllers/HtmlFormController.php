<?php
require_once 'HexaController.php';

/**
 * @property  User User
 * @property  HtmlFormModel HtmlFormModel
 */
class HtmlFormController extends HexaController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('HtmlFormModel');
		$this->load->model('HtmlFormTemplateModel');
		$this->load->model('Global_model');
		date_default_timezone_set('Asia/Kolkata');
	}

	function index($id = 0)
	{
		$dep_id = base64_decode(urldecode($id));

//		$dep_id=1;
		//$data['id']=$dep_id;
		$this->load->view("admin/templates/section_form", array("title" => "Forms", "department_id" => $dep_id));
	}

	function excel_table_test()
	{
		$this->load->view("admin/HtmlFormTemplate/excel_table_test", array("title" => "Excel Table"));
	}

	function index_personal($id = 0)
	{
		$dep_id = base64_decode(urldecode($id));
		$dep_id = $id;
//		$dep_id=1;
		//$data['id']=$dep_id;
		$this->load->view("admin/templates/section_form_personal", array("title" => "Forms", "department_id" => $dep_id));
	}

	public function fill()
	{
		$code = $_POST['code'];
		// print_r($code);exit();
		// $xml = new SimpleXMLElement($code1);

		$xml = simplexml_load_string($code);
		echo json_encode($xml);


	}

	function get_form_fields()
	{
		$department_id = $this->input->post('department_id');
		$patient_id = $this->input->post('patient_id');

		$query = $this->HtmlFormModel->get_form($department_id);
		$data = "";
		$template_name = "-";
		if ($query != false) {
			$patientResultObject = null;
			$patientObject = null;
			$active = 0;


			usort($query, function ($a, $b) {
				return (int)$a->seq_num > $b->seq_num;
			});

			foreach ($query as $value) {

				$activePanel = "show";
				$exapanded = "aria-expanded='true'";
				if ($active == 0) {
					$activePanel = "show";
					$exapanded = "aria-expanded='true'";
					$active = 1;
				}
				$section_id = $value->section_id;
				//		$section_status = $value->section_status;
				$is_traction = $value->is_traction;
				$is_history = $value->is_history;
				$tb_history = $value->tb_history;
				$template_name = $value->template_name;
				if ($patientResultObject == null) {
					$patientResultObject = $this->HtmlFormModel->getPatientDetails($value->tb_name, array('patient_id' => $patient_id));
					if ($patientResultObject->totalCount > 0) {
						$patientObject = (array)$patientResultObject->data;

					}

				}


				//is_traction
				$data .= '<div id="accordion_' . $section_id . '">
	<div class="accordion">
                        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body' . $section_id . '" ' . $exapanded . '>
                          <h4> ' . $value->section_name . '</h4>
                        </div>
                        <br>
                        ';
				$querysec = $this->HtmlFormModel->get_sectionform($section_id);
				if ($is_history == 1) {
					$btn1 = '<button type="button" id="history_btn_' . $section_id . '" class="btn btn-sm btn-outline-dark mx-1" onclick="view_history(\'' . $tb_history . '\',' . $section_id . ')"> <i class="fas fa-notes-medical"></i> view history</button>';
					$btn2 = '<button type="button" id="graph_btn_' . $section_id . '" class="btn btn-sm btn-outline-dark mx-1" style="display:none" onclick="view_graph(\'' . $value->section_name . '\',' . $section_id . ')"> <i class="fas fa-chart-line"></i> view graph</button>';
					$btn3 = '<button type="button" id="main_btn_' . $section_id . '" style="display:none" class="btn btn-sm btn-outline-dark mx-1" onclick="view_main_data(' . $section_id . ')"><i class="fab fa-wpforms"></i>View form</button>';
				} else {
					$btn1 = '';
					$btn3 = '';
					$btn2 = "";
				}

				$data .= '<div class="accordion-body collapse ' . $activePanel . '" id="panel-body' . $section_id . '" data-parent="#accordion_' . $section_id . '">';
				$data .= '<div class="row justify-content-end row">' . $btn3 . $btn1 . $btn2 . '</div>';
				$data .= '<div id="main_div_' . $section_id . '"> 
							<form id="data_form_' . $section_id . '" method="post" data-form-valid="save_form_data">
							<div class="">
									<input type="hidden"  name="department_id"
										   value="' . $department_id . '">
									<input type="hidden"  name="section_id"
										   value="' . $section_id . '">	   
									<input type="hidden" id="patient_id" name="patient_id"
										   value="' . $patient_id . '">
							
							';
				$weightfield = "";
				$heightfield = "";
				foreach ($querysec as $value1) {
					$patientValue = "";
					if ($patientObject != null) {
						if ($is_history != 1) {
							if (array_key_exists($value1->field_name, $patientObject)) {
								$patientValue = $patientObject[$value1->field_name];
							}
						}


						if ($value1->name == 'Weight (Kg)') {

							$weightfield = $value1->field_name;

						}
						if ($value1->name == 'Height (cm)') {
							$heightfield = $value1->field_name;
						}

						$w_val = "";
						if (array_key_exists($weightfield, $patientObject)) {
							$w_val = $patientObject[$weightfield];
						}
						$h_val = "";
						if (array_key_exists($heightfield, $patientObject)) {
							$h_val = ($patientObject[$heightfield]) / 100;
						}

						//echo $patientValue = round(($w_val / ($h_val * $h_val)), 2);

						if ($value1->name == 'BMI' && $h_val != '' && $w_val != '' && is_numeric($h_val) && is_numeric($w_val)) {

							$patientValue = round(($w_val / ($h_val * $h_val)), 2);
						}

					}
					$validation = "";
					if ((int)$value1->is_required == 1) {
						$validation = 'data-valid="required" data-msg="Please Fill this Field"';
					}


					if ($value1->ans_type == 1) {
						if ($value1->name == 'user id') {
							$disabled = "readonly";
							$patientValue = $this->session->user_session->id;

							$type = "hidden";
							$lable = "d-none";
						} else {
							$disabled = "";
							$type = "text";
							$lable = "";

						}
						if ((int)$value1->id == 179) {
							$patientValue = 'Dr. Vivek Kumar';
						}
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold ' . $lable . '"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">

					<input type="' . $type . '" class="form-control" ' . $disabled . ' id="form_field' . $value1->id . '" value="' . $patientValue . '" name="form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' /></div>';


					} else if ($value1->ans_type == 10) {
						$order_id = $this->Global_model->generate_order($value1->custom_query);

						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="form_field' . $value1->id . '" value="' . $order_id . '"   name="form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' /></div>';
					} else if ($value1->ans_type == 6) {
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="number" class="form-control"id="form_field' . $value1->id . '" value="' . $patientValue . '" name="form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '" ' . $validation . ' /></div>';
					} else if ($value1->ans_type == 5) {
						$isDate = '<script>
							var dt = new Date();   
   							var month = dt.getMonth()+1;
							var day = dt.getDate();
							var output = dt.getFullYear() + "-" +
    (month<10 ? "0" : "") + month + "-" +
    (day<10 ? "0" : "") + day+" 00:00:00";
//   var date=output+\"T\"+time;
   document.getElementById("form_field' . $value1->id . '").min = app.getDate(output);
					</script>';
						if ((int)$value1->id == 83 || (int)$value1->id == 17) {
							$isDate = "";
						}
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="datetime-local" class="form-control" id="form_field' . $value1->id . '" value="' . $patientValue . '" name="form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' />
					' . $isDate . '
					
					</div>';
					} else if ($value1->ans_type == 2) {
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<textarea id="form_field' . $value1->id . '" class="form-control"  name="form_field' . $value1->id . '" ' . $validation . '>' . $patientValue . '</textarea></div>';
					} else if ($value1->ans_type == 3) {
						$get_option = $this->HtmlFormModel->get_all_options($value1->id);
						$option = "<option value=''  selected disabled></option>";
						$selectedOptions = explode(",", $patientValue);

						foreach ($get_option as $option_value) {
							$selected = "";
							foreach ($selectedOptions as $o_value) {
								if ($o_value == $option_value->id) {
									$selected = "selected";
									break;
								}
							}

							$option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . ucfirst($option_value->name) . '</option>';
						}
						$check_dependancy = $this->HtmlFormModel->check_dependancy($value1->name, $section_id);

						$onchange = "";
						if ($check_dependancy != false) {
							$id = $check_dependancy;
							$id2 = "form_field" . $value1->id;
							$id3 = "append_div" . $value1->id;
							$onchange = "get_other_dropdown('$id','$id2','$id3')";
							$multiple = "";
						} else {
							$multiple = '';
						}
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9" >
					<select class="custom-select" id="form_field' . $value1->id . '"  onchange="' . $onchange . '" name="form_field' . $value1->id . '" ' . $validation . '>
							' . $option . '
									</select><script>$("#form_field' . $value1->id . '").select2()</script></div>';
					} else if ($value1->ans_type == 4) {
						$get_option = $this->HtmlFormModel->get_all_options($value1->id);
						$option = "<option value='' selected disabled></option>";
						$selectedOptions = explode(",", $patientValue);
						if (is_array($get_option)) {
							foreach ($get_option as $option_value) {
								$selected = "";
								foreach ($selectedOptions as $o_value) {
									if ($o_value == $option_value->id) {
										$selected = "selected";
										break;
									}
								}
								$option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . ucfirst($option_value->name) . '</option>';
							}
						}

						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<select class="form-control" multiple id="form_field' . $value1->id . '" name="form_field' . $value1->id . '[]" ' . $validation . '>
							' . $option . '
									</select> <script>$("#form_field' . $value1->id . '").select2()</script></div>';
					} else if ($value1->ans_type == 7) {
						if ($patientValue != "") {
							$patientValue = '<a href="' . base_url($patientValue) . '" class="btn btn-link" download><i class="fa fa-download"></i> Download</a>';
						}
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="file" name="form_field' . $value1->id . '[]" id="form_field' . $value1->id . '" class="form-control"  ' . $validation . '>
					' . $patientValue . '
					</div>';
					} else if ($value1->ans_type == 8) {
						$dependancy = $value1->dependancy;
						$option = "<option value='-1'>select option</option>";
						if ($dependancy == "") {
							$query = $value1->custom_query;

							/*$qr=$this->db->query($query);

							if($this->db->affected_rows() > 0){ */
							/* $res=$qr->result();
							foreach($res as  $val){

								$arr=array();
								foreach($val as $k => $v){

								$arr[]=$v;
								}

								$option .= "<option value='".$arr[0]."'>".$arr[1]."</option>";
							} */
							$check_dependancy = $this->HtmlFormModel->check_dependancy($value1->name, $section_id);

							$onchange = "";
							if ($check_dependancy != false) {
								$id = $check_dependancy;
								$id2 = "form_field" . $value1->id;
								$id3 = "append_div" . $value1->id;
								$onchange = "get_other_dropdown('$id','$id2','$id3')";
								$multiple = "";
							} else {
								$multiple = '';
							}
							if ($value1->is_extended_template == 1) {
								$ext_template_id = $value1->ext_template_id;
								$dep_encode = urlencode(base64_encode($ext_template_id));
								$qren = base64_encode($query);
								$url = base_url() . "FormController/get_data?query=$qren";
								$field = '
								<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
								<div class="col-sm-9">
								<div class="align-items-center row">
								<div class="col-sm-11 mx-0 px-0">
										<select class="custom-select"  id="form_field' . $value1->id . '" name="form_field' . $value1->id . '" ' . $validation . '  onchange="' . $onchange . '">
										  ' . $option . '
										</select>
										</div>
									<div class="col-sm-1 mx-0 px-0">
									  <a class="btn btn-primary" href="' . base_url() . 'company/form_view/' . $dep_encode . '"><i class="fa fa-plus"></i></a>
									</div>
									</div>
						
                      			</div><script>$("#form_field' . $value1->id . '").select2(
									{
								  ajax: { 
								   url: "' . $url . '",
								   type: "post",
								   dataType: "json",
								   delay: 250,
								   data: function (params) {
									return {
									  searchTerm: params.term // search term
									};
								   },
								   processResults: function (response) {
									 return {
										results: response
									 };
								   },
								   cache: true
								  },
								  minimumInputLength: 3
								 }
									)</script>
								';
							} else {
								$qren = base64_encode($query);
								$url = base_url() . "FormController/get_data?query=$qren";
								$field = '

					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9" >
					<select data-check="1" class="form-control"  ' . $multiple . ' onchange="' . $onchange . '"  id="form_field' . $value1->id . '" name="form_field' . $value1->id . '" ' . $validation . '>
							
									</select> <script>$("#form_field' . $value1->id . '").select2(
									{
								  ajax: { 
								   url: "' . $url . '",
								   type: "post",
								   dataType: "json",
								   delay: 250,
								   data: function (params) {
									return {
									  searchTerm: params.term // search term
									};
								   },
								   processResults: function (response) {
									 return {
										results: response
									 };
								   },
								   cache: true
								  },
								  minimumInputLength: 3
								 }
									)</script></div>';

							}

							//}


						} else {
							$field = '';
						}
					}


					$data .= ' 
                      <div class="form-group row mb-3" >
						' . $field . '			
					  </div>
					  <div id="append_div' . $value1->id . '"></div>
                    ';
				}
				if ($is_traction == 1) {

					if ($is_history == 1) {
						$now = date('Y-m-d H:i:s');
						//$now = date('d-M-Y H:i A');
						// print_r($now);exit;
						$data .= ' 
                      <div class="form-group row mb-3">
                      		<label  class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">Date</label>
                      		<div class="col-sm-9">
							<input type="datetime-local" id="transaction_date' . $section_id . '" name="transaction_date' . $section_id . '" value="' . $now . '"  class="form-control" data-valid="required" data-msg="Please Fill this Field"></div>	
					  </div>
					  <script>
//						var dt = new Date();
//   var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
//   var month = dt.getMonth()+1;
//var day = dt.getDate();
//
//var output = dt.getFullYear() + "-" +
//    (month<10 ? "0" : "") + month + "-" +
//    (day<10 ? "0" : "") + day;
//   var date=output+"T"+time;
//   document.getElementById("transaction_date' . $section_id . '").min = date;
					  	let date = app.getDate("' . $now . '")
					  	$("#transaction_date' . $section_id . '").val(date);
					  </script>
                    ';
					}
				} else {

					if ($is_history == 1) {
						$now = date('Y-m-d\TH:i:sP');
						$now = date('Y-m-d H:i:s');
						$data .= ' 
                      <div class="form-group row mb-3">
                      		<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">Date</label>
                      		<div class="col-sm-9">
								<input type="datetime-local" id="transaction_date' . $section_id . '" name="transaction_date' . $section_id . '" value="' . $now . '" class="form-control" data-valid="required" data-msg="Please Fill this Field">		
							</div>
							<script>
//						var dt = new Date();
//   var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
//   var month = dt.getMonth()+1;
//var day = dt.getDate();
//
//var output = dt.getFullYear() + "-" +
//    (month<10 ? "0" : "") + month + "-" +
//    (day<10 ? "0" : "") + day;
//   var date=output+"T"+time;
//   document.getElementById("transaction_date' . $section_id . '").min = date;
	date = app.getDate("' . $now . '")
					  	$("#transaction_date' . $section_id . '").val(date);
					</script>
					  </div>
                    ';
					}
				}
				$data .= "</div><div class='card-footer text-right'>
									<button class='btn btn-primary mr-1' type='submit'>Submit
									</button>
									<button class='btn btn-secondary' type='reset'>Reset</button>
								</div></form></div>";

				$data .= "</div>
				<div id='history_div_" . $section_id . "' class='d-none'></div>
				<div id='graph_div_" . $section_id . "' class='d-none'>
						<div class='row' id='graph_section_" . $section_id . "'>
						
						</div>
				</div></div></div>";
			}
			$response["template_name"] = $template_name;
			$response['code'] = 200;
			$response['data'] = $data;
		} else {
			$response['code'] = 201;
			$response['data'] = '';
		}


		echo json_encode($response);
	}

	function add_form_data()
	{
		$department_id = $this->input->post('department_id');
		$patient_id = $this->input->post('patient_id');
		$form_section_id = $this->input->post('section_id');
//		$company_id = $this->session->user_session->company_id;
		$get_data = $this->HtmlFormModel->get_all_data($department_id, $form_section_id);
		if ($get_data != false) {
			$data_to_insert = array();
			foreach ($get_data as $value) {
				$id = $value->id;
				$ans_type = $value->ans_type;
				$tb_name = $value->tb_name;
				$field_name = $value->field_name;
				$name_input = "form_field" . $id;
				if ($ans_type == 7) {
					$upload_path = "uploads";
					$combination = 2;
					$result = $this->upload_file($upload_path, $name_input, $combination);
					if ($result->status) {
						if ($result->body[0] == "uploads/") {
							$input_data = "";
						} else {
							$input_data = $result->body[0];
						}

					} else {
						$input_data = "";
					}
				} else if ($ans_type == 4) {
					if (is_array($this->input->post($name_input))) {
						$input_data = implode(',', $this->input->post($name_input));
					} else {
						$input_data = $this->input->post($name_input);
					}

				} else {
					$input_data = $this->input->post($name_input);
				}

				if ($input_data != "") {
					$data_to_insert[$field_name] = $input_data;
				}
			}
			$data_to_insert["patient_id"] = $patient_id;
			$data_to_insert["trans_date"] = date('Y-m-d H:i:s');
			$data_to_insert["branch_id"] = $this->session->user_session->branch_id;

			try {
				$this->db->trans_start();
				$check_patient_availabel = $this->HtmlFormModel->check_patient_availabel($patient_id, $tb_name);
				if ($check_patient_availabel == true) {
					$this->db->where('patient_id', $patient_id);
					$insert = $this->db->update($tb_name, $data_to_insert);
				} else {
					$insert = $this->db->insert($tb_name, $data_to_insert);
				}
				if ($insert == true) {
					//insert data into history table if history unable
					$query = $this->HtmlFormModel->get_form($department_id);
					if ($query != false) {
						foreach ($query as $value) {
							$section_id = $value->section_id;
							if ($form_section_id == $section_id) {
								$tb_history = $value->tb_history;
								$is_history = $value->is_history;
								$is_trans = $value->is_traction;
								$transDate = 'transaction_date' . $section_id;
								if ($is_history == 1) {
									$get_data_section = $this->HtmlFormModel->get_section_data($department_id, $section_id);
									if ($get_data_section != false) {
										$section_array = array();
										foreach ($get_data_section as $row) {
											$f_name = $row->field_name;
											$get_data_from_table = $this->HtmlFormModel->get_data_from_table($f_name, $tb_name, $patient_id);
											if ($get_data_from_table != false) {
												$section_array[$f_name] = $get_data_from_table;
											}
										}
										$section_array["branch_id"] = $this->session->user_session->branch_id;
										$section_array["patient_id"] = $patient_id;
										//echo $this->input->post($transDate);
										if ((int)$is_trans == 1) {
											if (!is_null($this->input->post($transDate))) {
												//	echo 1;
												$section_array["trans_date"] = $this->input->post($transDate);
												$data_to_insert["trans_date"] = $this->input->post($transDate);
												$response['trans1'][] = $this->input->post($transDate);
											} else {
												//	echo 2;
												$section_array["trans_date"] = date('Y-m-d H:i:s');
												$data_to_insert["trans_date"] = date('Y-m-d H:i:s');
												$response['trans1'][] = date('Y-m-d H:i:s');;
											}

										} else {
											if (is_null($this->input->post($transDate))) {
												//	echo 3;
												$section_array["trans_date"] = date('Y-m-d H:i:s');
												$data_to_insert["trans_date"] = date('Y-m-d H:i:s');
												$response['trans0'][] = date('Y-m-d H:i:s');
											} else {
												//echo 4;
												$section_array["trans_date"] = $this->input->post($transDate);
												$data_to_insert["trans_date"] = $this->input->post($transDate);
												$response['trans0'][] = $this->input->post($transDate);;
											}

										}

										$this->db->insert($tb_history, $data_to_insert);
									}
								}
							}
						}
					}
				}
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$response['code'] = 201;
				} else {
					$this->db->trans_commit();
					$response['code'] = 200;
				}
				$this->db->trans_complete();
			} catch (Exception $exception) {
				$this->db->trans_rollback();
				$response['code'] = 201;
			}
		} else {
			$response['code'] = 201;
		}
		echo json_encode($response);
	}

	public function get_history_data()
	{
		$table_name = $this->input->post('table_name');
		$patient_id = $this->input->post('patient_id');
		$section_id = $this->input->post('section_id');
		$branch_id = $this->session->user_session->branch_id;
		$resultTemplateObject = $this->HtmlFormModel->getHistoryTableColumn($section_id);
		$labelArray = array();
		$dataArray = array();
		$transArray = array();
		$data = "";
		$optionsValue = array();
		if ($resultTemplateObject->totalCount > 0) {
			$data = "<table class='table table-responsive' style='width:100%!important' id='history_table_" . $section_id . "'><thead style='width:100%!important'>";
			foreach ($resultTemplateObject->data as $column) {
				$data .= "<th>" . $column->name . "</th>";
				if ((int)$column->ans_type == 3) {
					$options = $this->HtmlFormModel->get_all_options($column->id);
					if (is_array($options)) {
						$optionsValue[$column->field_name] = $options;
					}
				}
				if ((int)$column->ans_type == 4) {
					$options = $this->HtmlFormModel->get_all_options($column->id);
					if (is_array($options)) {
						$optionsValue[$column->field_name] = $options;
					}
				}

				if ((int)$column->ans_type == 6) {
					array_push($labelArray, $column->name);
				}
			}
			$totalColumn = count($resultTemplateObject->data);
			$response["transColumnIndex"] = $totalColumn;
			$response["dd"] = $resultTemplateObject->data;
			$data .= "<th>Date</th><th>Action</th>";
			$data .= "</thead><tbody>";
			$resultObject = $this->HtmlFormModel->history_data($table_name, array("patient_id" => $patient_id, "branch_id" => $branch_id));
			$response["query"] = $this->db->last_query();
			// print_r($resultObject);exit();
			if (count($resultObject) > 0) {
				foreach ($resultObject as $recordIndex => $record) {
					$row = (array)$record;
					$td = "";
					$count = 0;
					foreach ($resultTemplateObject->data as $column) {
						$value = $row[$column->field_name];
						if ((int)$column->ans_type == 7) {
							if ($row[$column->field_name] != "" && $row[$column->field_name] != null)
								$value = '<a href="' . base_url($row[$column->field_name]) . '" class="btn btn-link" download><i class="fa fa-download"></i> Download</a>';
						}

						if ((int)$column->ans_type == 4) {
							$option = $optionsValue[$column->field_name];
							if (is_array($option)) {
								foreach ($option as $optionValues) {
									if ($optionValues->id == $value) {
										$value = $optionValues->name;
										break;
									}
								}
							}
						}
						if ((int)$column->ans_type == 3) {
							$option = $optionsValue[$column->field_name];
							if (is_array($option)) {
								foreach ($option as $optionValues) {
									if ($optionValues->id == $value) {
										$value = $optionValues->name;
										break;
									}
								}
							}
						}
						if ($value != "" && $value != null) {
							$td .= "<td>" . $value . "</td>";
							$dataArray[$column->name][date('jS M H:i:a', strtotime($row['trans_date']))] = $row[$column->field_name];
							$count = 1;
						} else {
							$td .= "<td></td>";
						}
					}
					$edit_btn = '';
					$permission_array = $this->session->user_permission;
					if ($permission_array != null) {
						if (in_array("history_update", $permission_array)) {
							$edit_btn = "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editHistoryModal'  data-patient_id='" . $patient_id . "' data-section_id='" . $section_id . "' data-history_id='" . $record->id . "' id='editSectionButton_" . $section_id . "'><i class='fa fa-edit'></i></button>";
						} else {
							//$edit_btn="<button class='btn btn-primary btn-sm'id='editSectionButton_" . $section_id . "' disabled><i class='fa fa-edit'></i></button>";
						}
					}

					if ($count == 1) {
						$data .= "<tr>";
						$data .= $td;
						$data .= "<td>" . date('d/m H:i:a', strtotime($row['trans_date'])) . "</td>";
						$data .= "<td>" . $edit_btn . "</td>";
						$data .= "</tr>";
						array_push($transArray, date('jS M H:i:a', strtotime($row['trans_date'])));
					}

				}
			}
			$data .= "</tbody></table>";
		}

		$response["table"] = $data;
		$response["label"] = $labelArray;
		$response["trans"] = $transArray;
		$response["data"] = $dataArray;

		echo json_encode($response);
	}

	function getdependantdropdown()
	{

		$id = $this->input->post('id');
		$value = $this->input->post('value');
		$querysec = $this->HtmlFormModel->get_sectionformid_wise($id);
		$option = "";
		if ($value == -1) {
			$response['code'] = 200;
			$response['data'] = "";
		} else {
			foreach ($querysec as $value1) {
				$query = $value1->custom_query;

				$query = str_replace("#id", $value, $query);
//
//				$qr = $this->db->query($query);
//				$last_query=$this->db->last_query();
//				if ($this->db->affected_rows() > 0) {
//					$res = $qr->result();
//					foreach ($res as $val) {
//
//						$arr = array();
//						foreach ($val as $k => $v) {
//
//							$arr[] = $v;
//						}
//
//						$option .= "<option value='" . $arr[0] . "'>" . $arr[1] . "</option>";
//					}
				$validation = "";
				if ((int)$value1->is_required == 1) {
					$validation = 'data-valid="required" data-msg="Please Fill this Field"';
				}
				$onchange = "";
				$check_dependancy = $this->HtmlFormModel->check_dependancy($value1->name, $value1->section_id);
				$onchange = "";
				if ($check_dependancy != false) {
					$id = $check_dependancy;
					$id2 = "form_field" . $value1->id;
					$id3 = "append_div" . $value1->id;
					$onchange = "get_other_dropdown('$id','$id2','$id3')";
					$multiple = "";
				} else {
					$multiple = '';
				}
				$qren = base64_encode($query);
				$url = base_url() . "FormController/get_data?query=$qren";
				$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9" >
					<select  class="form-control" onchange="' . $onchange . '" ' . $multiple . ' id="form_field' . $value1->id . '" name="form_field' . $value1->id . '" ' . $validation . '>
							
									</select> <script>$("#form_field' . $value1->id . '").select2(
									{
								  ajax: { 
								   url: "' . $url . '",
								   type: "post",
								   dataType: "json",
								   delay: 250,
								   data: function (params) {
									return {
									  searchTerm: params.term // search term
									};
								   },
								   processResults: function (response) {
									 return {
										results: response
									 };
								   },
								   cache: true
								  },
								  minimumInputLength: 3
								 }
									)</script></div>';
				$data = ' 
                      <div class="form-group row mb-3">
						' . $field . '			
					  </div>
					   <div id="append_div' . $value1->id . '"></div>
                    ';
				$response['code'] = 200;
				$response['data'] = $data;


//				} else {
//					$field = '
//					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
//					<div class="col-sm-9" >
//					<select class="form-control"  id="form_field' . $value1->id . '" name="form_field' . $value1->id . '[]" >
//
//									</select> <script>$("#form_field' . $value1->id . '").select2()</script></div>';
//					$data = '
//                      <div class="form-group row mb-3">
//						' . $field . '
//					  </div>
//					   <div id="append_div' . $value1->id . '"></div>
//                    ';
//					$response['code'] = 200;
//					$response['data'] = $data;
//				}


			}
		}
		echo json_encode($response);

	}

	public function get_data()
	{
		$search = $this->input->post('searchTerm');
		$query1 = $this->input->post_get('query');
		$q = base64_decode($query1);
		$data = array();
		if ($search == "") {

		} else {
			$q = str_replace("#like", $search, $q);

			$query = $this->db->query($q . " limit 20");
			//$data["last_query"]=$this->db->last_query();

			if ($this->db->affected_rows() > 0) {
				$res = $query->result();
				foreach ($res as $val) {

					$arr = array();
					foreach ($val as $k => $v) {

						$arr[] = $v;
					}
					$val = $arr[0] . " " . $arr[1];
					$data[] = array("id" => $val, "text" => $arr[1]);
//					$data[] = array("id" => $arr[0], "text" => $arr[1]);

				}
			}

		}
		echo json_encode($data);
	}

	public function getClassification()
	{

		$validObject = $this->is_parameter(array("type"));
		$response = array();
		if ($validObject->status) {

			$type = $validObject->param->type;
			$where = array("type" => $type);
			$userData = $this->db->select(array("key_name", "value"))->where($where)->get("admin_options")->result();
			$response["last_query"] = $this->db->last_query();
			$data = array();
			if (count($userData) > 0) {
				foreach ($userData as $user) {
					array_push($data, array("id" => $user->key_name, "text" => $user->value));
				}
			}
			$response["body"] = $data;
		} else {
			$response["body"] = array();
		}
		echo json_encode($response);
	}

	public function getSubClassification()
	{

		$validObject = $this->is_parameter(array("type"));
		$response = array();
		if ($validObject->status) {

			$type = $validObject->param->type;
			if ($type == "401") {
				$where = array("head_group" => "401 medicine");
			} else {
				$where = array("head_group" => $type);
			}

			$userData = $this->db->select(array("id", "name"))->where($where)->get("medicine_group")->result();
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

	public function getUnitOfMeasure()
	{

		$validObject = $this->is_parameter(array("type"));
		$response = array();
		if ($validObject->status) {

			$type = $validObject->param->type;
			$where = array("temp_id" => $type);
			$userData = $this->db->select(array("id", "name"))->where($where)->get("option_master")->result();
			$response["last_query"] = $this->db->last_query();
			$data = array();
			if (count($userData) > 0) {
				foreach ($userData as $user) {
					if ($user->name == "EA") {
						array_push($data, array("id" => $user->name, "text" => $user->name, "selected" => true));
						continue;
					}
					array_push($data, array("id" => $user->name, "text" => $user->name));
				}
			}
			$response["body"] = $data;
		} else {
			$response["body"] = array();
		}
		echo json_encode($response);
	}

	public function getHistoryTemplate()
	{
		// print_r('hiiii');exit();
		$section_id = $this->input->post('section_id');
		$history_id = $this->input->post('history_id');
		$patient_id = $this->input->post('patient_id');

		// $department_main_id = $this->input->post('department_id');
		$table_name = "section_master";
		$where = "id=" . $section_id . " ";
		$resultObject = $this->HtmlFormModel->get_all_selection_data($table_name, $where);// get all section data
		// print_r($resultObject);exit();
		if ($resultObject->totalCount > 0) {
			$result = $resultObject->data;
			$department_id = $result->department_id;
			$table_name_his = $result->tb_history;

			// $data=$this->get_form_fields($department_id,$patientValue);


			$query = $this->HtmlFormModel->get_form1($department_id, $section_id);
			// print_r($query);exit();
			$data = "";
			$template_name = "-";
			if ($query != false) {
				$patientResultObject = null;
				$patientObject = null;
				$active = 0;
				foreach ($query as $value) {
					// print_r($value);exit();
					$activePanel = "show";
					$exapanded = "aria-expanded='true'";
					if ($active == 0) {

						$activePanel = "show";
						$exapanded = "aria-expanded='true'";
						if ($active == 0) {
							$activePanel = "show";
							$exapanded = "aria-expanded='true'";
							$active = 1;
						}
						$section_id = $value->section_id;
						//		$section_status = $value->section_status;
						$is_traction = $value->is_traction;
						$is_history = $value->is_history;
						$tb_history = $value->tb_history;
						$template_name = $value->template_name;
						if ($patientResultObject == null) {
							$patientResultObject = $this->HtmlFormModel->getPatientDetails($table_name_his, array('id' => $history_id));

							if ($patientResultObject->totalCount > 0) {
								$patientObject = (array)$patientResultObject->data;

							}


						}

					}
					// print_r($patientObject);exit();
					//is_traction
					$data .= '<div id="update_accordion_' . $section_id . '">

	<div class="accordion">
                       
                        <br>
                        ';

					$querysec = $this->HtmlFormModel->get_sectionform($section_id);


					$data .= '<div class="accordion-body collapse ' . $activePanel . '" id="panel-body' . $section_id . '" data-parent="#update_accordion_' . $section_id . '">';

					$data .= '<div id="main_update_div_' . $section_id . '"> 
							<form id="update_data_form_' . $section_id . '" method="post" data-form-valid="update_form_data" onsubmit="return false">
							<div class="">
									<input type="hidden"  name="u_department_id"

										   value="' . $department_id . '">
									<input type="hidden"  name="u_section_id"
										   value="' . $section_id . '">	   
									<input type="hidden" id="u_patient_id" name="patient_id"
										   value="' . $patient_id . '">
									<input type="hidden" id="u_history_id" name="u_history_id"
										   value="' . $history_id . '">
							
							';
					foreach ($querysec as $value1) {
						$patientValue = "";
						if ($patientObject != null) {
							if ($is_history != 1) {
								if (array_key_exists($value1->field_name, $patientObject)) {
									$patientValue = $patientObject[$value1->field_name];
								}
							} else {
								if (array_key_exists($value1->field_name, $patientObject)) {
									$patientValue = $patientObject[$value1->field_name];
								}
							}

						}


						$validation = "";
						if ((int)$value1->is_required == 1) {
							$validation = 'data-valid="required" data-msg="Please Fill this Field"';
						}


						if ($value1->ans_type == 1) {
							$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="u_form_field' . $value1->id . '" value="' . $patientValue . '" name="u_form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' /></div>';

						} else if ($value1->ans_type == 10) {
							$order_id = $this->Global_model->generate_order($value1->custom_query);

							$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" id="u_form_field' . $value1->id . '" value="' . $order_id . '"   name="u_form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' /></div>';
						} else if ($value1->ans_type == 6) {
							$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="number" class="form-control"id="u_form_field' . $value1->id . '" value="' . $patientValue . '" name="u_form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '" ' . $validation . ' /></div>';
						} else if ($value1->ans_type == 5) {
							$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="datetime-local" class="form-control" id="u_form_field' . $value1->id . '" value="' . $patientValue . '" name="u_form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' />
					<script>
//						var dt = new Date();
//   var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
//   var month = dt.getMonth()+1;
//var day = dt.getDate();
//
//var output = dt.getFullYear() + "-" +
//    (month<10 ? "0" : "") + month + "-" +
//    (day<10 ? "0" : "") + day;
//   var date=output+"T"+time;
//   document.getElementById("u_form_field' . $value1->id . '").min = date;
					</script>
					</div>';
						} else if ($value1->ans_type == 2) {
							$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<textarea id="u_form_field' . $value1->id . '" class="form-control"  name="u_form_field' . $value1->id . '" ' . $validation . '>' . $patientValue . '</textarea></div>';
						} else if ($value1->ans_type == 3) {
							$get_option = $this->HtmlFormModel->get_all_options($value1->id);
							$option = "<option value=''  disabled></option>";
							$selectedOptions = explode(",", $patientValue);


							foreach ($get_option as $option_value) {
								$selected = "";
								foreach ($selectedOptions as $o_value) {
									if ($o_value == $option_value->id) {
										$selected = "selected";
										break;
									}
								}

								$option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . $option_value->name . '</option>';
							}
							$check_dependancy = $this->HtmlFormModel->check_dependancy($value1->name, $section_id);

							$onchange = "";
							if ($check_dependancy != false) {
								$id = $check_dependancy;
								$id2 = "u_form_field" . $value1->id;
								$id3 = "append_div" . $value1->id;
								$onchange = "get_other_dropdown('$id','$id2','$id3')";
								$multiple = "";
							} else {
								$multiple = '';
							}
							$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9" >
					<select class="custom-select" id="u_form_field' . $value1->id . '"  onchange="' . $onchange . '" name="u_form_field' . $value1->id . '" ' . $validation . '>
							' . $option . '
									</select><script>$("#u_form_field' . $value1->id . '").select2()</script></div>';
						} else if ($value1->ans_type == 4) {
							$get_option = $this->HtmlFormModel->get_all_options($value1->id);
							$option = "<option value=''  disabled></option>";
							$selectedOptions = explode(",", $patientValue);
							foreach ($get_option as $option_value) {
								$selected = "";
								foreach ($selectedOptions as $o_value) {
									if ($o_value == $option_value->id) {
										$selected = "selected";
										break;
									}
								}
								$option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . $option_value->name . '</option>';
							}
							$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<select class="form-control" multiple id="u_form_field' . $value1->id . '" name="u_form_field' . $value1->id . '[]" ' . $validation . '>
							' . $option . '
									</select> <script>$("#u_form_field' . $value1->id . '").select2()</script></div>';
						} else if ($value1->ans_type == 7) {
							$field = '<input type="hidden" name="u_hiden_form_field' . $value1->id . '" id="u_hiden_form_field' . $value1->id . '" class="form-control"  ' . $validation . ' value="' . $patientValue . '">';
							if ($patientValue != "") {
								$patientValue = '<a href="' . base_url($patientValue) . '" class="btn btn-link" download><i class="fa fa-download"></i> Download</a>';
							}
							$field .= '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="file" name="u_form_field' . $value1->id . '[]" id="u_form_field' . $value1->id . '" class="form-control"  ' . $validation . '>
					
					' . $patientValue . '
					</div>';
						} else if ($value1->ans_type == 8) {
							$dependancy = $value1->dependancy;
							$option = "<option value='-1'>select option</option>";
							if ($dependancy == "") {
								$query = $value1->custom_query;

								/*$qr=$this->db->query($query);

								if($this->db->affected_rows() > 0){ */
								/* $res=$qr->result();
								foreach($res as  $val){

									$arr=array();
									foreach($val as $k => $v){

									$arr[]=$v;
									}

									$option .= "<option value='".$arr[0]."'>".$arr[1]."</option>";
								} */
								$check_dependancy = $this->HtmlFormModel->check_dependancy($value1->name, $section_id);

								$onchange = "";
								if ($check_dependancy != false) {
									$id = $check_dependancy;
									$id2 = "u_form_field" . $value1->id;
									$id3 = "append_div" . $value1->id;
									$onchange = "get_other_dropdown('$id','$id2','$id3')";
									$multiple = "";
								} else {
									$multiple = '';
								}
								if ($value1->is_extended_template == 1) {
									$ext_template_id = $value1->ext_template_id;
									$dep_encode = urlencode(base64_encode($ext_template_id));
									$qren = base64_encode($query);
									$url = base_url() . "FormController/get_data?query=$qren";
									$field = '
								<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
								<div class="col-sm-9">
								<div class="align-items-center row">
								<div class="col-sm-11 mx-0 px-0">
										<select class="custom-select"  id="u_form_field' . $value1->id . '" name="u_form_field' . $value1->id . '" ' . $validation . '  onchange="' . $onchange . '">
										  ' . $option . '
										</select>
										</div>
									<div class="col-sm-1 mx-0 px-0">
									  <a class="btn btn-primary" href="' . base_url() . 'company/form_view/' . $dep_encode . '"><i class="fa fa-plus"></i></a>
									</div>
									</div>
						
                      			</div><script>$("#u_form_field' . $value1->id . '").select2(
									{
								  ajax: { 
								   url: "' . $url . '",
								   type: "post",
								   dataType: "json",
								   delay: 250,
								   data: function (params) {
									return {
									  searchTerm: params.term // search term
									};
								   },
								   processResults: function (response) {
									 return {
										results: response
									 };
								   },
								   cache: true
								  },
								  minimumInputLength: 3
								 }
									)</script>
								';
								} else {
									$qren = base64_encode($query);
									$url = base_url() . "FormController/get_data?query=$qren";
									$field = '

					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9" >
					<select data-check="1" class="form-control"  ' . $multiple . ' onchange="' . $onchange . '"  id="u_form_field' . $value1->id . '" name="u_form_field' . $value1->id . '" ' . $validation . '>
							
									</select> <script>$("#u_form_field' . $value1->id . '").select2(
									{
								  ajax: { 
								   url: "' . $url . '",
								   type: "post",
								   dataType: "json",
								   delay: 250,
								   data: function (params) {
									return {
									  searchTerm: params.term // search term
									};
								   },
								   processResults: function (response) {
									 return {
										results: response
									 };
								   },
								   cache: true
								  },
								  minimumInputLength: 3
								 }
									)</script></div>';

								}

								//}


							} else {
								$field = '';
							}
						}


						$data .= ' 
                      <div class="form-group row mb-3" >
						' . $field . '			
					  </div>
					  <div id="append_div' . $value1->id . '"></div>
                    ';
					}

					// if ($is_traction == 1) {
					// 	if ($is_history == 1) {
					// 		$now = date('Y-m-d\TH:i:sP');
					// 		$data .= ' 

					//                  <div class="form-group row mb-3">
					//                  		<label  class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">Date</label>
					//                  		<div class="col-sm-9">
					// 		<input type="datetime-local" id="u_transaction_date' . $section_id . '" name="transaction_date' . $section_id . '" value="' . $patientObject['trans_date'] . '"  class="form-control" data-valid="required" data-msg="Please Fill this Field"></div>		
					//   </div>
					//   <script>

					//   	$("#u_transaction_date' . $section_id . '").val(app.getDate("' . $patientObject['trans_date'] . '"));
					//   </script>
					//                ';

					// 	}
					// } else {
					// 	if ($is_history == 1) {
					// 		$now = date('Y-m-d\TH:i:sP');
					// 		$data .= ' 

					//                  <div class="form-group row mb-3">
					//                  		<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">Date</label>
					//                  		<div class="col-sm-9">
					// 			<input type="datetime-local" id="u_transaction_date' . $section_id . '" name="transaction_date' . $section_id . '" value="' . $patientObject['trans_date'] . '" class="form-control" data-valid="required" data-msg="Please Fill this Field">		
					// 		</div>
					//   </div>
					//   <script>

					//   	$("#u_transaction_date' . $section_id . '").val(app.getDate("' . $patientObject['trans_date'] . '"));
					//   </script>
					//                ';
					// 	}
					// }


				}
				$data .= "</div><div class='text-right'>
									<button class='btn btn-primary mr-1' type='button' onclick='update_form_data(\"update_data_form_" . $section_id . "\")'>Submit

									</button>
									<button class='btn btn-secondary' type='reset'>Reset</button>
								</div></form></div>";

				$data .= "</div>
				
				</div></div>";
			}
			$response['status'] = 200;
			$response['data'] = $data;

		} else {
			$response['status'] = 201;
			$response['data'] = "";
		}
		echo json_encode($response);
	}


	public function update_form_data()
	{

		$department_id = $this->input->post('u_department_id');
		$patient_id = $this->input->post('patient_id');
		$form_section_id = $this->input->post('u_section_id');
		$history_id = $this->input->post('u_history_id');

		$branch_id = $this->session->user_session->branch_id;
		// print_r($branch_id);exit();
		$get_data = $this->HtmlFormModel->get_all_data($department_id, $form_section_id);

		if ($get_data != false) {

			$data_to_insert = array();
			foreach ($get_data as $value) {

				$id = $value->id;
				$ans_type = $value->ans_type;
				$tb_name = $value->tb_name;

				$field_name = $value->field_name;
				$name_input = "u_form_field" . $id;
				if ($ans_type == 7) {
					$upload_path = "uploads";
					$combination = 2;
					$name_file_input = "u_hiden_form_field" . $id;

					$result = $this->upload_file($upload_path, $name_input, $combination);
					if ($result->status) {
						if ($result->body[0] == "uploads/") {
							$input_data = $this->input->post($name_file_input);
						} else {
							$input_data = $result->body[0];
						}

					} else {
						$input_data = $this->input->post($name_file_input);
					}
				} else if ($ans_type == 4) {
					if (is_array($this->input->post($name_input))) {
						$input_data = implode(',', $this->input->post($name_input));
					} else {
						$input_data = $this->input->post($name_input);
					}

				} else {
					$input_data = $this->input->post($name_input);
				}

				if ($input_data != "" && $input_data != NULL) {
					$data_to_insert[$field_name] = $input_data;
				} else {
					$input_data = NULL;
					$data_to_insert[$field_name] = $input_data;
				}
			}
			$data_to_insert["patient_id"] = $patient_id;
			// $data_to_insert["trans_date"] = date('Y-m-d H:m:i');

			$data_to_insert["branch_id"] = $this->session->user_session->branch_id;
			// print_r($data_to_insert);exit();
			try {
				$this->db->trans_start();
				$query = $this->HtmlFormModel->get_form1($department_id, $form_section_id);

				if ($query != false) {
					foreach ($query as $value) {
						$tb_history = $value->tb_history;
						$is_trans = $value->is_traction;
						$transDate = 'transaction_date' . $form_section_id;
						// if ((int)$is_trans == 1) {
						// 	// echo 1;
						// 	$data_to_insert["trans_date"] = date('Y-m-d H:m:i');
						// } else {
						// echo 0;
						// if (is_null($this->input->post($transDate))) {

						// 	$data_to_insert["trans_date"] = date('Y-m-d H:m:i');
						// } else {

						// 	$data_to_insert["trans_date"] = $this->input->post($transDate);
						// }

						// }
						// print_r($this->input->post($transDate));exit();
						$history_where = array('patient_id' => $patient_id,
							'id' => $history_id,
							'branch_id' => $branch_id);

						$this->db->where($history_where);
						$insert = $this->db->update($tb_history, $data_to_insert);

						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							$response['code'] = 201;
							$response['tableName'] = $tb_history;
							$response['section_id'] = $form_section_id;
						} else {
							$this->db->trans_commit();
							$response['code'] = 200;
							$response['tableName'] = $tb_history;
							$response['section_id'] = $form_section_id;
						}
					}
				}

				$this->db->trans_complete();
			} catch (Exception $exception) {
				$this->db->trans_rollback();
				$response['code'] = 201;
				// $response['tableName'] = $tb_name;
				$response['section_id'] = $form_section_id;
			}


		} else {
			$response['code'] = 201;
		}
		echo json_encode($response);
	}

	public function getPatientHistoryData()
	{
		$p_id = $this->input->post('p_id');
		$query = $this->db->query("select * from section_master where status=1 AND is_history=1 AND id=2");
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			$data = "";

			foreach ($result as $row) {
				$data .= '<div class="row">
				<div class="col-8 col-sm-8 col-md-2">
				 <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
				  <li class="nav-item">
                            <a class="nav-link" id="home-tab' . $row->id . '" onclick="get_TableData(\'' . $row->id . '\',\'' . $p_id . '\',\'' . $row->tb_history . '\')" data-toggle="tab" href="#panel-body' . $row->id . '" role="tab" aria-controls="home" aria-selected="true">' . $row->name . '</a>
                          </li>
				 </ul>
				</div>
				<div class="col-12 col-sm-12 col-md-8" id="content_data" >
				<div class="tab-content no-padding" id="myTab2Content">
                          <div class="tab-pane fade" id="panel-body' . $row->id . '" role="tabpanel" aria-labelledby="home-tab1">
                          
                          </div>
						  </div>
				</div>
				</div>
			
				';
			}
			$response['data'] = $data;
			$response['code'] = 200;
		} else {
			$response['code'] = 200;
		}
		echo json_encode($response);
	}

	function getCriticalParaData()
	{
		$p_id = $this->input->post('p_id');
		$data_li = ' <li class="nav-item"><a class="nav-link" id="home-tab_critical" onclick="get_data_critical(' . $p_id . ')" data-toggle="tab" href="#panel-bodyCP" role="tab"  aria-selected="false">Critical Parameter</a>
                          </li>';
		$data_li .= ' <li class="nav-item"><a class="nav-link" id="home-tab_critical" onclick="load_medicine_history(' . $p_id . ')" data-toggle="tab" href="#panel-bodyLB" role="tab"  aria-selected="false">Medication History</a>
                          </li>';
		$data_body = ' <div class="tab-pane " id="panel-bodyCP" role="tabpanel" aria-labelledby="home-tab2"></div>';
		$data_body .= ' <div class="tab-pane " id="panel-bodyLB" role="tabpanel" aria-labelledby="home-tab2">
		<section id="history_table_section">
								</section></div>';

		$response['data_li'] = $data_li;
		$response['data_body'] = $data_body;
		$response['code'] = 200;


		echo json_encode($response);
	}

	public function GetQueryDataInsert($ButtonQueryString, $section_id, $department_id)
	{

		$ButtonQueryString = json_decode($ButtonQueryString);

		$finalCount = ($this->input->post('finalCount')) - 1;
		$ButtonInsertID = ($this->input->post('ButtonInsertID'));
		// var_dump();
		$ButtonQueryString = (array)($ButtonQueryString);
		$tableArray = array();
		foreach ($ButtonQueryString as $key => $d) {
			$keyArray[] = $key;
			if (strpos($key, 'TableName_') !== false) {
				$splitValue = explode("TableName_", $key);
				$splitValue = $splitValue[1];
				$tableArray[$splitValue] = $d;
			}

		}

		$columnArray = array();
		$arrayWhereString = array();
		$query_typeArray = array();
		foreach ($tableArray as $index => $column) {
			$queryType = $this->input->post('queryType' . $index);
			//query_type
			$query_typeArray[$index] = $queryType;
			$whereString = "";
			if ($queryType == 2 || $queryType == 3) {
				$wherecount = $this->input->post('Uwherecount_' . $index);
				for ($i = 1; $i <= $wherecount; $i++) {
					$WherecolumnName = $this->input->post('WherecolumnName_' . $index . '_' . $i);
					$WherefieldName = $this->input->post('WherefieldName_' . $index . '_' . $i);
					$WheretextfieldName = $this->input->post('WheretextfieldName_' . $index . '_' . $i);
					if (!empty($WherecolumnName) && !empty($WherefieldName)) {
						if (empty($WheretextfieldName)) {
							$whereString .= $WherecolumnName . ":" . $WherefieldName . "|";
						} else {
							$whereString .= $WherecolumnName . ":" . $WheretextfieldName . "|";
						}

					}

				}
			}
			$arrayWhereString[$index] = rtrim($whereString, "|");
			foreach ($ButtonQueryString as $key => $d) {
				if (strpos($key, 'otherData' . $index . '_') !== false || $queryType == 3) {

					$columnArray[$index][] = $d;
				}
			}
		}

		$QuerydataToInsert = array();
		$QuerydataToInsert2 = array();
		$resultObject = new stdClass();
		$resultObject->status = 200;
		foreach ($columnArray as $ind => $m) {

			$ColumnDataInsert = implode("|", $m);
			$DataWhereString = $arrayWhereString[$ind];
			$query_type = $query_typeArray[$ind];
			$TableName = $tableArray[$ind];
			//$missMatch = $this->getMissMatchColumn($TableName, $m, $section_id);

//			if ($missMatch->status == false) {
//				// print_r($missMatch);
//				$resultObject->status = 201;
//				$resultObject->column = $missMatch->column;
//				$resultObject->field = $missMatch->field;
//				break;
//			}

			if ($query_type == 3) {
				$QuerydataToInsert['array_string'] = "";
			} else {
				$QuerydataToInsert['array_string'] = $ColumnDataInsert;
			}

			$QuerydataToInsert['wherearray_string'] = $DataWhereString;
			$QuerydataToInsert['table_name'] = $TableName;
			$QuerydataToInsert['query_type'] = $query_type;
			$QuerydataToInsert['field_id'] = $ButtonInsertID;
			$QuerydataToInsert['field_type'] = 14;
			$QuerydataToInsert['department_id'] = $department_id;
			$QuerydataToInsert['section_id'] = $section_id;
			array_push($QuerydataToInsert2, $QuerydataToInsert);

		}
		$resultObject->QuerydataToInsert2 = $QuerydataToInsert2;
		return $resultObject;
	}

	function getMissMatchColumn($tableName, $field_data1, $section_id)
	{
		$resultObject = new stdClass();
		$result_data = $this->db->query('select field_id,field_type,type from html_field_required_table where section_id=' . $section_id)->result();
		$query_data = $query = $this->db->query('SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE table_name = "' . $tableName . '"')->result();
		$value_array = array();
		$column_array = array();

		foreach ($result_data as $key => $value) {
			if ($value->type == 1) {
				$field_id = "#" . $value->field_id;
			} else {
				$field_id = $value->field_id;
			}
			$value_array[$field_id] = $value->field_type;
		}
		foreach ($query_data as $key => $value) {
			$column_array[$value->column_name] = $value->DATA_TYPE;
		}


		$inetger_array = array('integer', 'int', 'smallint', 'tinyint', 'mediumint', 'bigint', 'decimal', 'numeric', 'float', 'double');
		$date_array = array('date', 'datetime', 'timestamp', 'time', 'year');
		$combine_array = array('integer', 'int', 'smallint', 'tinyint', 'mediumint', 'bigint', 'decimal', 'numeric', 'float', 'double', 'date', 'datetime', 'timestamp', 'time', 'year');
		$resultObject->status = true;
		foreach ($field_data1 as $field_value) {
			// print_r($field_value);
			$field_data = explode(':', $field_value);
			if (count($field_data) > 1) {
				$column = $column_array[$field_data[0]];
				$value_type = $value_array[$field_data[1]];
				if ((int)$value_type) {

					if (in_array($column, $inetger_array)) {
						if ($value_type != 6) {
							$resultObject->status = false;
							$resultObject->column = $field_data[0];
							$resultObject->field = $field_data[1];
							$resultObject->column_type = $column;
							break;
						}
					}
					if (in_array($column, $date_array)) {
						if ($value_type != 5) {
							$resultObject->status = false;
							$resultObject->column = $field_data[0];
							$resultObject->field = $field_data[1];
							$resultObject->column_type = $column;
							break;
						}
					}
				} else {
					if ($column != $value_type) {
						$resultObject->status = false;
						$resultObject->column = $field_data[0];
						$resultObject->field = $field_data[1];
						$resultObject->column_type = $column;
						// return $resultObject;
						break;
					}
				}

			}
		}

		return $resultObject;


	}

	function SaveQueryDataHTML()
	{
		$ButtonQueryString = $this->input->post('queryString');
		$section_id = $this->input->post('querysection_id');
		$department_id = $this->input->post('queryDepartmentID');
		$formActionType = $this->input->post('formActionType');
		if ($formActionType == 1) {
			$route1 = $this->input->post('route1');
			$qeryparam1 = $this->input->post('qeryparam1');
			//$qeryparam1=implode(",",$queparam1);
			$redirection = 'Action_type:1|route:' . $route1 . '|ExtraParam:' . $qeryparam1;
		} else if ($formActionType == 2) {
			$sectionselect2 = $this->input->post('sectionselect2');
			$qeryparam2 = $this->input->post('qeryparam2');
			//$qeryparam2=implode(",",$qeryparam2);
			$redirection = 'Action_type:2|route:' . $sectionselect2 . '|ExtraParam:' . $qeryparam2;
		} else {
			$redirection = 'Action_type:2';
		}
		if (($ButtonQueryString) == 'undefined') {
			$GetQueryDataInsert = false;
		} else {
			$GetQueryDataInsert1 = $this->GetQueryDataInsert($ButtonQueryString, $section_id, $department_id);

			if ($GetQueryDataInsert1->status == 201) {

				$response['status'] = 201;
				$response['body'] = "Column " . $GetQueryDataInsert1->column . " Field " . $GetQueryDataInsert1->field . " datatype miss match";
				echo json_encode($response);
				exit();
			} else {
				$GetQueryDataInsert = $GetQueryDataInsert1->QuerydataToInsert2;
			}
		}

		$c = 1;
		if ($GetQueryDataInsert != false) {
			foreach ($GetQueryDataInsert as $data) {
				$data['redirection'] = $redirection;
				$insert = $this->db->insert("htmlquerytable", $data);
				if ($insert == true) {
					$c++;
				}
			}

			if ($c > 1) {
				$response['status'] = 200;
				$response['body'] = "Added Successfully";
			} else {
				$response['status'] = 201;
				$response['body'] = "Failed to add";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Something Went Wrong";
		}
		echo json_encode($response);
	}

	function InsertFordataUsingButton()
	{

		extract($_POST);

		if (!is_null($queryparameter_hidden)) {
			$queryparameterstring = base64_decode($queryparameter_hidden);
			$queryStringArray = json_decode($queryparameterstring);
           // var_dump($queryStringArray);
			extract((array)$queryStringArray);
		}
	//var_dump((array)$queryStringArray);
//		exit;
		$query = $this->db->query("select * from htmlquerytable where field_id='#" . $btn_id . "' AND section_id=".$section_id." order by id desc");
        
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			//var_dump($result);

			$cnt = 1;
			$last_insert_id = array();
			foreach ($result as $row) {

				$array_string = $row->array_string;
				$wherearray_string = $row->wherearray_string;
				$table_name = $row->table_name;
				$query_type = $row->query_type;
				$section_id = $row->section_id;
				$redirection = $row->redirection;
				if (!empty($array_string)) {
					$exp = explode('|', $array_string);
					$array = array();
					//add or set array
					for ($i = 0; $i < count($exp); $i++) {
						$str2 = $exp[$i];
						$exp2 = explode(':', $str2);
						$index = $exp2[0];

						if (strpos($exp2[1], '#insertID') !== false) {
							$insert_number = explode('#insertID', $exp2[1]);
							$insert_number = $insert_number[1] - 1;
							$value = $last_insert_id[$insert_number];
						} else {
							$value = str_replace("#", "", $exp2[1]);
							$value1 = explode('_', $value);

							if ($value1[0] == 'file') {
								$value = $this->getFileUploadData($value);
							} else {
								if (isset(${$value})) {
									$value = ${$value};

								} else {
									$value = "";
								}
							}

						}
						// print_r($value);
						if (is_array($value)) {
							$value = implode(",", $value);
						}
						if (isset($value)) {
							$array[$index] = $value;
						} else {
							$array[$index] = "";
						}


					}

				}
                if(isset($operation_id)){
                    $array['operation_id'] = $operation_id;
                }
//var_dump($array);
//				exit;
				//where array

				$wherearray = array();
				if ($wherearray_string != "") {

					$expWhere = explode('|', $wherearray_string);
					for ($j = 0; $j < count($expWhere); $j++) {
						$strwhere2 = $expWhere[$j];
						$expwhere2 = explode(':', $strwhere2);
						$indexwhere = $expwhere2[0];
						if (strpos($expwhere2[1], '#') !== false) {
							$valuewhere = str_replace("#", "", $expwhere2[1]);
							$valuewhere = ${$valuewhere};
						} else {
							$valuewhere = $expwhere2[1];
						}


						$wherearray[$indexwhere] = $valuewhere;
					}
				}
				$dependant_section_id = 0;
				$is_dependant = 0;
				
				if ($query_type == 1) {
					if (isset($update_id)) {
						$where = array('id' => $update_id);
						$this->db->where($where);
						$insert = $this->db->update($table_name, $array);
					} else {
						$insert = $this->db->insert($table_name, $array);
						$insert_id = $this->db->insert_id();

						$last_insert_id[] = $insert_id;
						$findSectionDependancy = $this->HtmlFormTemplateModel->getDependantsection($section_id);
						if ($findSectionDependancy->totalCount > 0) {
							$dependant_section_id = $findSectionDependancy->data->id;
							$response['insert_id'] = $insert_id;
						} else {
							$dependant_section_id = 0;
						}
						$find_is_dependant = $this->HtmlFormTemplateModel->get_Is_Dependantsection($section_id);
						if ($find_is_dependant->totalCount > 0) {
							if (!is_null($find_is_dependant->data->dependant_section_id) && ($find_is_dependant->data->dependant_section_id) != 0) {
								$is_dependant = 1;
							} else {
								$is_dependant = 0;
							}
						}
					}

				} else if ($query_type == 2) {

					if (count($wherearray) > 0 && is_array($wherearray)) {
						$this->db->where($wherearray);
					}
					$insert = $this->db->update($table_name, $array);


				} else {
					if (count($wherearray) > 0 && is_array($wherearray)) {
						$this->db->where($wherearray);
					}
					$insert = $this->db->delete($table_name);
				}


				if ($insert == true) {
					$cnt++;
				}
			}

			if ($cnt > 1) {
				$response['status'] = 200;
				$response['redirection'] = $redirection;
				$response['dependant_section_id'] = $dependant_section_id;
				$response['is_dependant'] = $is_dependant;
				$response['this_section_id'] = $section_id;
				$response['body'] = "Inserted Successfully";
			} else {
				$response['status'] = 201;
				$response['body'] = "Failed to insert Data";
			}
		} else {
			$response['status'] = 201;
			$response['body'] = "Something Went Wrong";
		}
		echo json_encode($response);


	}

	public function getFileUploadData($value)
	{
		$name_input = $value;

		$upload_path = "uploads";
		$combination = 2;
		$result = $this->upload_file($upload_path, $name_input, $combination);
		// print_r($result);exit();
		if ($result->status) {
			if ($result->body[0] == "uploads/") {
				$input_data = "";
			} else {
				$input_data = $result->body;
			}

		} else {
			$input_data = "";
		}

		if ($input_data != "") {
			if (count($input_data) > 1) {
				$input_data = implode(',', $input_data);
			} else {
				$input_data = $input_data[0];
			}
		} else {
			$input_data = "";
		}

		$value = $input_data;
		return $value;
	}

	public function SaveQueryDataHTMLTable()
	{
		extract($_POST);
		if (!is_null($section_id)) {
			$query = $this->db->query("select * from htmlquerytable where section_id=" . $section_id . " AND department_id=" . $department_id . " AND field_id='" . $btn_id . "'");
			if ($this->db->affected_rows() > 0) {
				$result = $query->result();
				$data = '<table class="table-bordered table table-responsive">
			<thead>
			<tr>
			<th>Query Type</th>
			<th>Table Name</th>
			<th>Main column String</th>
			<th>Where Column String</th>
			<th>Action</th>
			</tr>
			</thead>
			';
				foreach ($result as $row) {
					$redirection = $row->redirection;
					if ($row->query_type == 1) {
						$query_type = 'Insert Query';
					} else if ($row->query_type == 2) {
						$query_type = 'Update Query';
					} else {
						$query_type = 'Delete Query';
					}

					$edit_btn = '<button class="btn btn-link" onclick="functionEditQuery(' . $row->id . ')"><i class="fa fa-pen"></i></button>';
					$data .= '
				<tr>
				<td>' . $query_type . '</td>
				<td>' . $row->table_name . '</td>
				<td>' . $row->array_string . '</td>
				<td>' . $row->wherearray_string . '</td>
				<td>' . $edit_btn . '</td>
				</tr>
				';
				}
				$data .= '
			</tbody>
			</table>';
				$response['status'] = 200;
				$response['data'] = $data;
				$response['redirection'] = $redirection;
			} else {
				$response['status'] = 201;
				$response['data'] = $data;
				$response['redirection'] = $redirection;
			}
		} else {
			$response['status'] = 201;
			$response['data'] = "";
			$response['redirection'] = "";
		}
		echo json_encode($response);
	}

	function GetQueryDatatoEdit()
	{

		extract($_POST);
		$query = $this->db->query("select q.*,
	 (select count(id) from htmlquerytable where field_id=q.field_id) as countQuery,
	(select html_section_text from html_section_master where id=q.section_id) as hashvalues ,
	(select query_string_parameter from html_section_master where id=q.section_id) as query_string_parameter 
	from htmlquerytable q where q.id=" . $id);

		if ($this->db->affected_rows() > 0) {
			$result = $query->row();

			$TableName = $result->table_name;
			$column_option = $this->getColumnByTablename($TableName);

			$array_string = $result->array_string;

			$fields = $this->db->field_data($TableName);
			$total_columns = count($fields);
			$data = "<hr>
		<form id='UpdateQueryForm' method='post' name='UpdateQueryForm'>
		<input type='hidden' id='updateId' name='updateId' value='" . $result->id . "'>
		<input type='hidden' id='totalColCount' name='totalColCount' value='" . $total_columns . "'>
		";
			$count = 1;
			$cnt = 1;
			$selected_table_column = array();
			$selected_hash_value = array();
			if (!empty($array_string)) {
				$mainArrayString = explode("|", $array_string);
				foreach ($mainArrayString as $m) {
					$fieldArray = explode(":", $m);
					$selected_table_column[] = $fieldArray[0];
					$selected_hash_value[] = $fieldArray[1];

				}
			}


			$hashvalues = $result->hashvalues;
			$exp = explode(",", $hashvalues);
			$option_hash = "";
			$option_hash .= '<option value="">No selection</option>';
			foreach ($exp as $ex) {
				$option_hash .= '<option value="' . $ex . '">' . $ex . '</option>';
			}
			$query_string_parameter = $result->query_string_parameter;

			$exp2 = explode(",", $query_string_parameter);
			foreach ($exp2 as $e) {
				$xp = explode(":", $e);
				$a = $xp[0];
				$option_hash .= '<option value="#' . $a . '">#' . $a . '</option>';
			}
			for ($m = 1; $m <= ($result->countQuery); $m++) {
				$option_hash .= '<option value="#insertID' . $m . '">#insertID' . $m . '</option>';
			}
			$count_TCol = count($selected_table_column);
			$count_HCol = count($selected_hash_value);
			$k = 0;
			foreach ($fields as $field) {
				$selected_table_columnV = "";
				$selected_hash_valueV = "";
				if ($k < ($count_TCol)) {
					if (count($selected_table_column) > 0) {
						$selected_table_columnV = $selected_table_column[$k];
						$selected_hash_valueV = $selected_hash_value[$k];
					} else {
						$selected_table_columnV = "";
						$selected_hash_valueV = "";
					}
				}
				$data .= "<div class='row d-flex'>
		  
		   <div class='col-md-4'>
		   <select class='form-control' id='UcolumnName_" . $count . "_" . $cnt . "'
		  name='UcolumnName_" . $count . "_" . $cnt . "'>
		   " . $column_option . "
		   </select>
		   <script>
		   $('#UcolumnName_" . $count . "_" . $cnt . "').val('" . $selected_table_columnV . "');
		   </script>
		   </div>
		   <div class='col-md-4'>
		   <select class='form-control hashoptionclass'
		   id='UfieldName_" . $count . "_" . $cnt . "' 
		    name='UfieldName_" . $count . "_" . $cnt . "'>
		  " . $option_hash . "
		   </select>
		   <script>
		   $('#UfieldName_" . $count . "_" . $cnt . "').val('" . $selected_hash_valueV . "');
		   </script>
		   </div>
		   
		   </div>
		  
		   </div>
		   <br>";
				$k++;
				$cnt++;
			}
			$type = $result->query_type;
			$wherearray_string = $result->wherearray_string;

			$cnt1 = 1;
			$data1 = "";
			$data1 .= "<h6>Where Condition</h6>
		<button class='btn btn-link' type='button' id='addmoreVarButton'
		onclick='appendWhereHtml(" . $count . ",\"" . base64_encode($column_option) . "\",\"" . base64_encode($option_hash) . "\")'> Add More..</button>";
			$W_Col_name = array();
			$W_hash_name = array();
			if ($type == 2 || $type == 3) {


				$data1 .= " <input type='hidden' id='Uwherecount_" . $count . "' name='Uwherecount_" . $count . "' value='" . $cnt1 . "'>
				<div class='row'>
		   <div class='col-md-4'>
		   <select class='form-control' id='WherecolumnName_" . $count . "_" . $cnt1 . "' name='WherecolumnName_" . $count . "_" . $cnt1 . "'>
		   " . $column_option . "
		   </select>
		   </div>
		   <div class='col-md-4'>
		   <select class='form-control' id='WherefieldName_" . $count . "_" . $cnt1 . "' name='WherefieldName_" . $count . "_" . $cnt1 . "'>
		  " . $option_hash . "
		   </select>
		   </div>
		    <div class='col-md-4'>
		  <input type='text' class='form-control' id='WheretextfieldName_" . $count . "_" . $cnt1 . "'  name='WheretextfieldName_" . $count . "_" . $cnt1 . "'>
		   </div>
		   </div>
		   <div id='divwhereAppend_" . $count . "_" . $cnt1 . "'></div>
		   ";
				$data = $data . $data1;

				$W_count = 0;
				if (!empty($wherearray_string)) {
					$wexp1 = explode("|", $wherearray_string);
					foreach ($wexp1 as $wex) {

						$wexp2 = explode(":", $wex);
						$W_Col_name[] = $wexp2[0];
						$W_hash_name[] = $wexp2[1];
					}
					$W_count = count($W_Col_name);

				}


				//$W_count=3;
				$data2 = "";
				if ($W_count >= 1) {
					$r = 0;
					if ($W_count == 1) {
						if (strpos($W_hash_name[0], '#') !== false) {
							$HashID = "#WherefieldName_" . $count . "_" . $cnt1;
						} else {
							$HashID = "#WheretextfieldName_" . $count . "_" . $cnt1;
						}
						$data2 = "
				<script>
				$('#WherecolumnName_" . $count . "_" . $cnt1 . "').val('" . $W_Col_name[0] . "');
				
				$('" . $HashID . "').val('" . $W_hash_name[0] . "');
				</script>
				";
					} else {
						for ($d = 1; $d < $W_count; $d++) {
							//echo $W_Col_name[$r];
							if (strpos($W_hash_name[$r], '#') !== false) {
								$HashID = "#WherefieldName_" . $count . "_" . $cnt1;
							} else {
								$HashID = "#WheretextfieldName_" . $count . "_" . $cnt1;
							}
							$data2 = "
				<script>
				$('#addmoreVarButton').click();
				$('#WherecolumnName_" . $count . "_" . $cnt1 . "').val('" . $W_Col_name[$r] . "');
				$('" . $HashID . "').val('" . $W_hash_name[$r] . "');
				</script>
				";
							$r++;
						}
					}

				}
				$data .= $data2;
			}

			$data .= '
		<div><br>
		<button class="btn btn-primary"  type="button" onclick="FunUpdateQueryForm()">Update</button>
		</div>
		</form><hr>';
			$response['status'] = 200;
			$response['data'] = $data;
		} else {
			$response['status'] = 201;
			$response['data'] = $data;
		}
		echo json_encode($response);
	}

	function UpdatequeryTable()
	{
		$totalColCount = $this->input->post('totalColCount');
		$updateId = $this->input->post('updateId');
		$Uwherecount = $this->input->post('Uwherecount_1');
		$formActionType = $this->input->post('formActionType');
		if ($formActionType == 1) {
			$route1 = $this->input->post('route1');
			$qeryparam1 = $this->input->post('qeryparam1');
			//$qeryparam1=implode(",",$queparam1);
			$redirection = 'Action_type:1|route:' . $route1 . '|ExtraParam:' . $qeryparam1;
		} else if ($formActionType == 2) {
			$sectionselect2 = $this->input->post('sectionselect2');
			$qeryparam2 = $this->input->post('qeryparam2');
			//$qeryparam2=implode(",",$qeryparam2);
			$redirection = 'Action_type:2|route:' . $sectionselect2 . '|ExtraParam:' . $qeryparam2;
		} else {
			$redirection = 'Action_type:2';
		}
		$W_stringData = "";
		if (isset($Uwherecount)) {
			for ($j = 1; $j <= $Uwherecount; $j++) {
				$wcol_name = $this->input->post('WherecolumnName_1_' . $j);
				$whash_name = $this->input->post('WherefieldName_1_' . $j);
				$whash_text = $this->input->post('WheretextfieldName_1_' . $j);
				if (!empty($whash_text)) {
					$hash_name1 = $whash_text;
				} else {
					$hash_name1 = $whash_name;
				}
				if (!empty($wcol_name) && !empty($hash_name1)) {
					$W_stringData .= $wcol_name . ":" . $hash_name1 . "|";
				}
			}
		}
		$stringData = "";
		for ($i = 1; $i <= $totalColCount; $i++) {
			$col_name = $this->input->post('UcolumnName_1_' . $i);
			$hash_name = $this->input->post('UfieldName_1_' . $i);
			if (!empty($col_name) && !empty($hash_name)) {
				$stringData .= $col_name . ":" . $hash_name . "|";
			}
		}
		$stringDataFinal = rtrim($stringData, "|");
		$W_stringDataFinal = rtrim($W_stringData, "|");
		$set = array("array_string" => $stringDataFinal, "wherearray_string" => $W_stringDataFinal);
		$this->db->where(array("id" => $updateId));
		$update = $this->db->update('htmlquerytable', $set);
		//get section id
		$q_sec_id = $this->db->query("select section_id,field_id from htmlquerytable where id=" . $updateId);
		if ($this->db->affected_rows() > 0) {
			$section_id = $q_sec_id->row()->section_id;
			$field_id = $q_sec_id->row()->field_id;
			$this->db->where(array("section_id" => $section_id, "field_id" => $field_id));
			$update1 = $this->db->update('htmlquerytable', array("redirection" => $redirection));
		}

		if ($update == true) {
			$response['status'] = 200;
			$response['data'] = "Updated Successfully";
		} else {
			$response['status'] = 201;
			$response['data'] = "Something Went Wrong";
		}
		echo json_encode($response);
	}

	function getColumnByTablename($TableName)
	{
		$fields = $this->db->field_data($TableName);
		$option = "";
		$option .= '<option value="">No selection</option>';
		foreach ($fields as $field) {
			$column_name = $field->name;
			$option .= "<option value='" . $column_name . "'>" . $column_name . "</option>";
		}
		return $option;

	}

	function GetQueryParamData()
	{
		extract($_POST);
		$section_id;
		$dept_id;

		$query = $this->db->query("select * from html_field_required_table where section_id=" . $section_id . " AND type=1");
		$data = '';
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			$p = 1;
			$data_array = array();
			foreach ($result as $row) {

				$data .= '<div class="row">
			<lable>' . $row->field_id . '</lable>
			<!--<input class="form-control" type="hidden" value="" id="param_str' . $p . '" name="param_str' . $p . '">-->
			<input class="form-control" type="text" id="param' . $p . '" name="' . $row->field_id . '">
			</div>
			';
				$data_array[] = $row->field_id;
				$p++;
			}
			$response['status'] = 200;
			$response['data_array'] = $data_array;
			$response['data'] = $data;
		} else {
			$response['status'] = 201;
			$response['data'] = $data;
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
				$data .= "<option value='" . $row->parameter_name . ":" . $row->paramete_type . "'>" . $row->parameter_name . "</option>";
			}
			$response['status'] = 200;
			$response['data'] = $data;
		} else {
			$response['status'] = 201;
			$response['data'] = "";
		}
		echo json_encode($response);
	}

	function getQueryStringPara2()
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

	function GetallSections()
	{
		extract($_POST);

		$query = $this->db->query("select id,name from html_section_master where status=1 AND department_id=" . $department_id);

		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			$data = "<option value=''>Select Section</option>";
			foreach ($result as $row) {
				$data .= "<option value='" . $row->id . "'>" . $row->name . "</option>";
			}
			$response['status'] = 200;
			$response['data'] = $data;
		} else {
			$response['status'] = 201;
			$response['data'] = "";
		}
		echo json_encode($response);
	}

	function CheckHistoryUnabled()
	{
		extract($_POST);
		$query = $this->db->query("select history_unabled from html_section_master where id=" . $section_id);
		if ($this->db->affected_rows() > 0) {
			$history_unabled = $query->row()->history_unabled;
			if ($history_unabled == 1) {
				$response['status'] = 200;
			} else {
				$response['status'] = 201;
			}
		} else {
			$response['status'] = 201;
		}
		echo json_encode($response);
	}

	function getExcelTableConfiguaration()
	{
		extract($_POST);
		$data = "";
		if (!is_null($section_id)) {
			$query = $this->db->query("select id,hash_key, group_concat(input_name,':',input_type) as input_fields from ExcelInputsTable where section_id=" . $section_id . " AND department_id=" . $department_id . " AND hash_key='" . $id . "' group by hash_key");
			if ($this->db->affected_rows() > 0) {
				$result = $query->result();
				$data = '<table class="table-bordered table table-responsive">
			<thead>
			<tr>
			<th>button</th>
			<th>Input</th>
			<th>Action</th>
			</tr>
			</thead>
			';
				foreach ($result as $row) {

					$edit_btn = '<button class="btn btn-link" onclick="functionEditExcelHeader(' . $section_id . ',' . $department_id . ',\'' . $id . '\')"><i class="fa fa-eye"></i></button>
						<button class="btn btn-link" onclick="functionDeleteExcelHeader(' . $section_id . ',' . $department_id . ',\'' . $id . '\')"><i class="fa fa-trash"></i></button>';
					$data .= '
				<tr>
				<td>' . $row->hash_key . '</td>
				<td>' . $row->input_fields . '</td>
				<td>' . $edit_btn . '</td>
				</tr>
				';
				}
				$data .= '
			</tbody>
			</table>';
				$response['status'] = 200;
				$response['data'] = $data;
			} else {
				$response['status'] = 201;
				$response['data'] = $data;
			}
		} else {
			$response['status'] = 201;
			$response['data'] = "";
		}
		echo json_encode($response);

	}

	function getExcelTableConfiguarationUpdate()
	{
		extract($_POST);
		$result = array();
		if (!is_null($section_id)) {
			$query = $this->db->query("select * from ExcelInputsTable where section_id=" . $section_id . " AND department_id=" . $department_id . " AND hash_key='" . $id . "'");
			if ($this->db->affected_rows() > 0) {
				$result = $query->result();
				$response['status'] = 200;
				$response['data'] = $result;
			} else {
				$response['status'] = 201;
				$response['data'] = $result;
			}
		} else {
			$response['status'] = 201;
			$response['data'] = "";
		}
		echo json_encode($response);
	}

	function getExcelTableConfiguarationDelete()
	{
		extract($_POST);
		if (!is_null($section_id)) {
			// $query = $this->db->query("delete from excelinputstable where section_id=" . $section_id . " AND department_id=" . $department_id . " AND hash_key='" . $id . "'");
			$where = array('section_id' => $section_id,
				'department_id' => $department_id,
				'hash_key' => $id);

			if ($this->db->delete('ExcelInputsTable', $where)) {
				$response['status'] = 200;
				$response['data'] = "Data Deleted Successfully";
			} else {
				$response['status'] = 201;
				$response['data'] = "Data Not Deleted";
			}
		} else {
			$response['status'] = 201;
			$response['data'] = "";
		}
		echo json_encode($response);
	}

	function ExcelHeadersave()
	{
		$input_name = $this->input->post('input_name');
		$input_type = $this->input->post('input_type');
		$input_options = $this->input->post('input_options');
		$querydropdown = $this->input->post('querydropdown');
		$ExcelBtnID = $this->input->post('ExcelBtnID');
		$ExcelDeptId = $this->input->post('ExcelDeptId');
		$excelSectionID = $this->input->post('excelSectionID');
		$excelColumnNames = $this->input->post("tableColumn");
		$tableName = $this->input->post("table_name");
		//ExcelInputsTable
		if (is_array($input_name) && is_array($input_type) && is_array($input_options)
			&& is_array($excelColumnNames) && ($tableName != "" || $tableName != null)) {
			$i = 0;
			$final_array = array();
			foreach ($input_name as $row) {

				if ($row != "" && !empty($input_type[$i])) {
					$data = array(
						"input_name" => $row,
						"input_type" => $input_type[$i],
						"options" => $input_options[$i],
						"section_id" => $excelSectionID,
						"query" => $querydropdown[$i],
						"department_id" => $ExcelDeptId,
						"hash_key" => $ExcelBtnID,
						"column" => $excelColumnNames[$i],
						"table" => $tableName,
						"date" => date('Y-m-d h:i:s'),
					);
					array_push($final_array, $data);
				}
				$i++;
			}

			$insert = $this->db->insert_batch('ExcelInputsTable', $final_array);
			if ($insert == true) {
				$response['status'] = 200;
				$response['body'] = 'Added Successfully';
			} else {
				$response['status'] = 201;
				$response['body'] = 'Something Went Wrong';
			}
		} else {
			$response['status'] = 201;
			$response['body'] = 'Parameter Missing';
		}
		echo json_encode($response);
	}

	function getExcelTabledata()
	{
		$section_id = $this->input->post('section_id');
		$hash_key = $this->input->post('hash_key');
		$query = $this->db->query("select * from ExcelInputsTable where section_id=" . $section_id . " AND hash_key='#" . $hash_key . "'");
		if ($this->db->affected_rows() > 0) {
			$result = $query->result();
			$response['status'] = 200;
			$response['data'] = $result;
		} else {
			$response['status'] = 201;
			$response['data'] = "";
		}
		echo json_encode($response);
	}

	function ExcelTabledataInsert()
	{
		//	extract($_POST);
		$table_name = 'profile_management_table';
		$coulmn_array_sequence = array("profile_name", "column_name");
		$jsonString = $this->input->post('jsonString');
		$aa = json_decode($jsonString, true);
		$final_array = array();
		foreach ($aa as $row) {
			$data = array();
			$k = 0;
			foreach ($row as $r1) {
				$col = $coulmn_array_sequence[$k];
				$data[$col] = $r1;
				$k++;
			}
			$productString = implode(" ", $data);
			if (trim($productString) == "") {
				//no values in array
			} else {
				array_push($final_array, $data);
			}

		}
		$insert = $this->db->insert_batch($table_name, $final_array);
		if ($insert == true) {
			$response['status'] = 200;
			$response['body'] = 'Added Successfully';
		} else {
			$response['status'] = 201;
			$response['body'] = 'Something Went Wrong';
		}
		echo json_encode($response);
	}

	function getAllColumnsListExcel()
	{
		$TableName = $this->input->post('TableName');
		$section_id = $this->input->post('section_id');
		$hash_key = $this->input->post('hash_key');
		$fields = $this->db->field_data($TableName);
		$optionArr=array();
		foreach ($fields as $field) {
			$column_name = $field->name;
			$optionArr[] = $column_name;
		}
		//get other options
		$oth_option="<option value=''>Select Input Type</option>";
		$query=$this->db->query("select id,input_name from ExcelInputsTable where section_id=".$section_id." AND hash_key='".$hash_key."'");
		if($this->db->affected_rows()>0){
			$result=$query->result();
			foreach ($result as $row){
				$oth_option .=	"<option value='".$row->id."'>".$row->input_name."</option>";
			}

		}
		$data ="";
		$cnt=1;
		$p=0;
		foreach ($fields as $field) {
			$data .="<div class='row'>
					<div class='col-md-6'><input type='text' class='form-control' readonly value='".$optionArr[$p]."' id='Ex_columnName_" . $cnt . "' name='Ex_columnName_" . $cnt . "'></div>
					<div class='col-md-6'><select class='form-control' id='input_type_".$cnt."' name='input_type_".$cnt."'>
					".$oth_option."
</select></div>
</div>";
			$cnt++;
			$p++;
		}
		$response['status'] = 200;
		$response['data'] = $data;
		echo json_encode($response);
	}
}

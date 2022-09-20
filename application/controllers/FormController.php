<?php
require_once 'HexaController.php';

/**
 * @property  User User
 * @property  Formmodel Formmodel
 */
class FormController extends HexaController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Formmodel');
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
		$patient_id = $this->input->post('product_id');
		$process_id = $this->input->post('process_id');
		$schedule_id = $this->input->post('schedule_id');
		$branch_id = 1;//$this->session->user_session->branch_id;
		$emergency_contact_doctor = 1;
//		$get_DataDoctor = $this->db->query("Select emergency_contact_doctor from branch_master where id=" . $branch_id);
//		if ($this->db->affected_rows() > 0) {
//			$emergency_contact_doctor = $get_DataDoctor->row()->emergency_contact_doctor;
//		}

		$getStatus = new stdClass();
		$getStatus->totalCount = 0;
		if ($schedule_id != null && $department_id != null && $process_id != null && $schedule_id != '' && $department_id != '' && $process_id != '') {
			$getStatus = $this->Formmodel->_rawQuery('
		(select working_status from schedule_process_template_mapping where template_id = ' . $department_id . ' and procedure_mapping_id = (select id from schedule_procedure_mapping where production_schedule_id = ' . $schedule_id . ' and product_id = ' . $patient_id . ' 
		and process_id = ' . $process_id . '))
		');
		}

		$status = 0;
		if ($getStatus->totalCount > 0) {
			$status = $getStatus->data[0]->working_status;
		}


		$query = $this->Formmodel->get_form($department_id);
		$data = "";
		$template_name = "-";
		if ($query != false) {
			$patientResultObject = null;
			$patientObject = null;
			$active = 0;


//			usort($query, function ($a, $b) {
//				return (int)$a->seq_num > $b->seq_num;
//			});

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
					$patientResultObject = $this->Formmodel->getPatientDetails($value->tb_name, array('patient_id' => $patient_id, 'branch_id' => $branch_id));
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
				$querysec = $this->Formmodel->get_sectionform($section_id);
				if ($is_history == 1) {
					$btn1 = '<button type="button" id="history_btn_' . $section_id . '" class="btn btn-sm btn-outline-dark mx-1" onclick="view_history(\'' . $tb_history . '\',' . $section_id . ','.$process_id.')"> <i class="fas fa-notes-medical"></i> view history</button>';
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
									<input type="hidden" id="process_id" name="process_id"
										   value="' . $process_id . '">
									<input type="hidden" id="schedule_id" name="schedule_id"
										   value="' . $schedule_id . '">
							
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
							if (is_numeric($patientObject[$heightfield])) {
								$h_val = ($patientObject[$heightfield]) / 100;
							}
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
							$patientValue = $emergency_contact_doctor;
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
						if ((int)$value1->id == 83 || (int)$value1->id == 17 || (int)$value1->id == 258 || (int)$value1->id == 364 || (int)$value1->id == 432) {
							$isDate = "";
						}
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9 d-flex">
					<input type="datetime-local" class="form-control" id="form_field' . $value1->id . '" value="' . $patientValue . '" name="form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' />
					' . $isDate . '
					<button type="button" class="btn btn-link btn-sm" style="color: #891635d9 !important" onclick="clearElementValue(\'form_field' . $value1->id . '\',' . $value1->ans_type . ')">X</button>
					</div>';
					} else if ($value1->ans_type == 2) {
						$field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<textarea id="form_field' . $value1->id . '" class="form-control"  name="form_field' . $value1->id . '" ' . $validation . '>' . $patientValue . '</textarea></div>';
					} else if ($value1->ans_type == 3) {
						$get_option = $this->Formmodel->get_all_options($value1->id);
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
							if ($value1->id == 143 && $option_value->id == 1448) {
								$selected = "selected";
							}
							if ($value1->id == 215 && $option_value->id == 1274) {
								$selected = "selected";
							}
							if ($value1->id == 347 && $option_value->id == 1565) {
								$selected = "selected";
							}
							if ($value1->id == 350 && $option_value->id == 1581) {
								$selected = "selected";
							}

							$option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . ucfirst($option_value->name) . '</option>';
						}
						$check_dependancy = $this->Formmodel->check_dependancy($value1->name, $section_id);

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
					<div class="col-sm-9 d-flex" >
					<select class="custom-select" id="form_field' . $value1->id . '"  onchange="' . $onchange . '" name="form_field' . $value1->id . '" ' . $validation . '>
							' . $option . '
									</select><button type="button" class="btn btn-link btn-sm" style="color: #891635d9 !important" onclick="clearElementValue(\'form_field' . $value1->id . '\',' . $value1->ans_type . ')">X</button>
									<script>$("#form_field' . $value1->id . '").select2()</script></div>';
					} else if ($value1->ans_type == 4) {
						$get_option = $this->Formmodel->get_all_options($value1->id);
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
							$check_dependancy = $this->Formmodel->check_dependancy($value1->name, $section_id);

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
//                        $now = date('Y-m-d\TH:i:sP');
						$now = date('Y-m-d H:iP');
						//$now = date('d-M-Y H:i A');
						// print_r($now);exit;
						$data .= ' 
                      <div class="form-group row mb-3">
                      		<label  class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">Date</label>
                      		<div class="col-sm-9">
							<input type="datetime-local" id="transaction_date' . $section_id . '" name="transaction_date' . $section_id . '" value="' . $now . '"  class="form-control" data-valid="required" data-msg="Please Fill this Field"></div>	
					  </div>
					  <script>
					  	$("#transaction_date' . $section_id . '").val( app.getDate("' . $now . '"));
					  </script>
                    ';
					}
				} else {

					if ($is_history == 1) {
//						$now = date('Y-m-d\TH:i:sP');
						$now = date('Y-m-d H:iP');
						$data .= ' 
                      <div class="form-group row mb-3">
                      		<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">Date</label>
                      		<div class="col-sm-9">
								<input type="datetime-local" id="transaction_date' . $section_id . '" name="transaction_date' . $section_id . '" value="' . $now . '" class="form-control" data-valid="required" data-msg="Please Fill this Field">		
							</div>
							<script>
	
					  	$("#transaction_date' . $section_id . '").val(app.getDate("' . $now . '"));
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
			$response['status'] = $status;
		} else {
			$response['code'] = 201;
			$response['data'] = '';
			$response['status'] = $status;
		}


		echo json_encode($response);
	}

	function add_form_data()
	{
		$department_id = $this->input->post('department_id');
		$patient_id = $this->input->post('patient_id');
		$form_section_id = $this->input->post('section_id');
		$process_id = $this->input->post('process_id');
		$schedule_id = $this->input->post('schedule_id');
		$is_history_on = 0;
//		$company_id = $this->session->user_session->company_id;
		$getSection = $this->Formmodel->_select('section_master', array('id' => $form_section_id), array('is_history'));
		if ($getSection->totalCount > 0) {
			$is_history_on = $getSection->data->is_history;
		}
		$get_data = $this->Formmodel->get_all_data($department_id, $form_section_id);
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
				} else {
					$data_to_insert[$field_name] = "";
				}
			}
			$data_to_insert["patient_id"] = $patient_id;
			$data_to_insert['process_id'] = $process_id;
			$data_to_insert['schedule_id'] = $schedule_id;
			$data_to_insert["trans_date"] = date('Y-m-d H:i:s');
			$data_to_insert["branch_id"] = $this->session->user_session->branch_id;

			try {
				$this->db->trans_start();
				$check_patient_availabel = $this->Formmodel->check_patient_availabel($patient_id, $tb_name);
				if ($check_patient_availabel == true) {
					$this->db->where('patient_id', $patient_id);
					$insert = $this->db->update($tb_name, $data_to_insert);
				} else {
					$insert = $this->db->insert($tb_name, $data_to_insert);
				}
				if ($insert == true) {
					//insert data into history table if history unable
					$query = $this->Formmodel->get_form($department_id);
					if ($query != false) {
						foreach ($query as $value) {
							$section_id = $value->section_id;
							if ($form_section_id == $section_id) {
								$tb_history = $value->tb_history;
								$is_history = $value->is_history;
								$is_trans = $value->is_traction;
								$transDate = 'transaction_date' . $section_id;
								if ($is_history == 1) {
									$get_data_section = $this->Formmodel->get_section_data($department_id, $section_id);
									if ($get_data_section != false) {
										$section_array = array();
										foreach ($get_data_section as $row) {
											$f_name = $row->field_name;
											$get_data_from_table = $this->Formmodel->get_data_from_table($f_name, $tb_name, $patient_id);
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
					$response['section_id'] = $form_section_id;
					$response['is_history'] = $is_history_on;
				} else {
					$this->db->trans_commit();
					$response['code'] = 200;
					$response['section_id'] = $form_section_id;
					$response['is_history'] = $is_history_on;
				}
				$this->db->trans_complete();
			} catch (Exception $exception) {
				$this->db->trans_rollback();
				$response['code'] = 201;
				$response['section_id'] = $form_section_id;
				$response['is_history'] = $is_history_on;
			}
		} else {
			$response['code'] = 201;
			$response['section_id'] = $form_section_id;
			$response['is_history'] = $is_history_on;
		}
		echo json_encode($response);
	}

	public function get_history_data()
	{
		$table_name = $this->input->post('table_name');
		$patient_id = $this->input->post('patient_id');
		$section_id = $this->input->post('section_id');
		$schedule_id = $this->input->post('schedule_id');
		$process_id = $this->input->post('process_id');
		$BloodInvestigationId = $this->input->post('BloodInvestigationId');
		$branch_id = 1;//$this->session->user_session->branch_id;
		$resultTemplateObject = $this->Formmodel->getHistoryTableColumn($section_id);
		$labelArray = array();
		$dataArray = array();
		$transArray = array();
		$data = "";
		$optionsValue = array();
		if ($resultTemplateObject->totalCount > 0) {
			$data = "<table class='table table-responsive' style='width:100%;display:table' id='history_table_" . $section_id . "'><thead>";
			foreach ($resultTemplateObject->data as $column) {
				$data .= "<th>" . $column->name . "</th>";
				if ((int)$column->ans_type == 3) {
					$options = $this->Formmodel->get_all_options($column->id);
					if (is_array($options)) {
						$optionsValue[$column->field_name] = $options;
					}
				}
				if ((int)$column->ans_type == 4) {
					$options = $this->Formmodel->get_all_options($column->id);
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
			if ($section_id == 43 || $section_id == 45) {
				$data .= "<th>Total</th>";
			}
			$data .= "<th>Date</th><th>Action</th>";
			$data .= "</thead><tbody>";
			$w_arr = array("patient_id" => $patient_id,'schedule_id'=>$schedule_id,'process_id'=>$process_id, "branch_id" => $branch_id);
			if ($section_id == 48) {
				$w_arr['blood_transfusion_id'] = $BloodInvestigationId;
			}
			$resultObject = $this->Formmodel->history_data($table_name, $w_arr);
			$response["query"] = $this->db->last_query();
			// print_r($resultObject);exit();
			$total_section43 = 0;
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
							$multi_op = array();
							$option = $optionsValue[$column->field_name];
							if (is_array($option)) {
								foreach ($option as $optionValues) {
									$value1 = explode(',', $value);
									if (!empty($value1)) {
										foreach ($value1 as $key_value) {

											if ($optionValues->id == $key_value) {
												array_push($multi_op, $optionValues->name);

											}
										}

									}


								}
								if (!empty($multi_op)) {
									$value = implode(',', $multi_op);
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
							if ($section_id == 43) {
								$total_section43 = $total_section43 + $value;
							}
							if ($section_id == 45) {

								$expp = explode(" ", $value);
								if (count($expp) >= 2) {
									$total_section43 = $total_section43 + $expp[0];
								}

							}
							$dataArray[$column->name][date('jS M H:i:a', strtotime($row['trans_date']))] = $row[$column->field_name];
							$count = 1;
						} else {
							$td .= "<td></td>";
						}
					}
					$edit_btn = '';
					$permission_array = array(); //$this->session->user_permission;
					if (in_array("history_update", $permission_array)) {
						$edit_btn = "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editHistoryModal'  data-patient_id='" . $patient_id . "' data-section_id='" . $section_id . "' data-history_id='" . $record->id . "' id='editSectionButton_" . $section_id . "'><i class='fa fa-edit'></i></button>";
					} else {
						//$edit_btn="<button class='btn btn-primary btn-sm'id='editSectionButton_" . $section_id . "' disabled><i class='fa fa-edit'></i></button>";
					}
					if ($count == 1) {
						$data .= "<tr>";
						$data .= $td;
						if ($section_id == 43 || $section_id == 45) {
							$data .= "<td>" . $total_section43 . "</td>";
						}

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
		$querysec = $this->Formmodel->get_sectionformid_wise($id);
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
				$check_dependancy = $this->Formmodel->check_dependancy($value1->name, $value1->section_id);
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
		$resultObject = $this->Formmodel->get_all_selection_data($table_name, $where);// get all section data
		// print_r($resultObject);exit();
		if ($resultObject->totalCount > 0) {
			$result = $resultObject->data;
			$department_id = $result->department_id;
			$table_name_his = $result->tb_history;

			// $data=$this->get_form_fields($department_id,$patientValue);


			$query = $this->Formmodel->get_form1($department_id, $section_id);
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
							$patientResultObject = $this->Formmodel->getPatientDetails($table_name_his, array('id' => $history_id));

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

					$querysec = $this->Formmodel->get_sectionform($section_id);


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
					<div class="col-sm-9 d-flex">
					<input type="datetime-local" class="form-control" id="u_form_field' . $value1->id . '" value="' . $patientValue . '" name="u_form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '"  ' . $validation . ' />
					<button type="button" class="btn btn-link btn-sm" style="color: #891635d9 !important" onclick="clearElementValue(\'u_form_field' . $value1->id . '\',' . $value1->ans_type . ')">X</button>
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
							$get_option = $this->Formmodel->get_all_options($value1->id);
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
							$check_dependancy = $this->Formmodel->check_dependancy($value1->name, $section_id);

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
					<div class="col-sm-9 d-flex" >
					<select class="custom-select" id="u_form_field' . $value1->id . '"  onchange="' . $onchange . '" name="u_form_field' . $value1->id . '" ' . $validation . '>
							' . $option . '
									</select>
									<button type="button" class="btn btn-link btn-sm" style="color: #891635d9 !important" onclick="clearElementValue(\'u_form_field' . $value1->id . '\',' . $value1->ans_type . ')">X</button>
									<script>$("#u_form_field' . $value1->id . '").select2()</script></div>';
						} else if ($value1->ans_type == 4) {
							$get_option = $this->Formmodel->get_all_options($value1->id);
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
								$check_dependancy = $this->Formmodel->check_dependancy($value1->name, $section_id);

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
		$get_data = $this->Formmodel->get_all_data($department_id, $form_section_id);

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
				$query = $this->Formmodel->get_form1($department_id, $form_section_id);

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


}



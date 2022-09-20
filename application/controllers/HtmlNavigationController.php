<?php
require_once 'HexaController.php';

use Dompdf\Dompdf;

/**
 * @property  LabReport LabReport
 */
class HtmlNavigationController extends HexaController
{
    public function index($department_id = 0, $section_id = 0, $QueryParamter = null)
    {
        $this->load->view('Form_Show/form_view_navigation', array("title" => "Opd Patients", "department_id" => $department_id, "section_id" => $section_id, "queryParam" => $QueryParamter));
    }

    public function patient_navigation($department_id = 0, $section_id = 0, $QueryParamter = null)
    {
        $this->load->view('Form_Show/patient_navigation_view', array("title" => "OT Patients", "department_id" => $department_id, "section_id" => $section_id, "queryParam" => $QueryParamter));
    }
    public function patient_report(){
        $this->load->view('admin/patients/patient_report_view',array("title"=>"Patients Report"));
    }

    public function getDepartmentMenus()
    {
        $department_id = $this->input->post('department_id');
        $section_id = $this->input->post('section_id');
        $queryparameter_hidden = $this->input->post('queryparameter_hidden');

        if ($department_id != null) {
            $this->db->select();
            $this->db->from('html_section_master');
            $where = array('department_id' => $department_id, 'status' => 1);
            $this->db->where($where);
            $query = $this->db->get();
            $report = $this->getReport($department_id);
            if ($query->num_rows() > 0) {
                $result = $query->result();
                $response['status'] = 200;
                $response['data'] = $result;
                if ($report["status"] == 200) {
                    $response['report'] = $report["data"];
                }
            } else if ($report["status"] == 200) {
                $response['status'] = 200;
                $response['report'] = $report["data"];
            } else {
                $response['status'] = 201;
                $response['data'] = '';
                $response['body'] = 'No Section Found';
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Sometheing Went Wrong";
        }
        echo json_encode($response);
    }

    function getReport($department_id)
    {
        $this->db->select();
        $this->db->from('reportMakerTable');
        $where = array('dep_id' => $department_id, 'status' => 1);
        $this->db->where($where);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $response['status'] = 200;
            $response['data'] = $result;
        } else {
            $response['status'] = 201;
            $response['data'] = '';
            $response['body'] = 'No Section Found';
        }
        return $response;
    }

    public function getFormInputValues()
    {

        $section_id = $this->input->post('section_id');
        $queryParamString = $this->input->post('queryPara');

        if ($section_id != null) {
            $data = $this->getDetailsToedit($queryParamString, $section_id);
            // print_r($data);exit();
            if ($data != false) {
                $response['status'] = 200;
                $response['data'] = $data;
            } else {
                $response['status'] = 201;
                $response['data'] = '';
            }
        } else {
            $response['status'] = 201;
            $response['body'] = "Sometheing Went Wrong";
        }
        echo json_encode($response);
    }

    function getDataFromCol($table_name, $whereValues, $queryParamString, $field_type)
    {
        extract((array)$queryParamString);
        $this->db->select('*');
        $this->db->from($table_name);
        if ((int)$field_type == 8) {
            $wheres = explode("|", $whereValues);
            foreach ($wheres as $cond) {
                $condValue = explode(":", $cond);
                if (count($condValue) > 2) {
                    if (strpos("#", $condValue[1])) {
                        $queryParam = str_replace("#", "", $condValue[1]);
                        $this->db->where($condValue[0], ${$queryParam});
                    } else {
                        $this->db->where($condValue[0], $condValue[1]);
                    }
                }
            }
        } else {
            $this->db->where('patient_id', $patient_id);
            $this->db->where('branch_id', $branch_id);
            if (isset($operation_id)) {
                $this->db->where('operation_id', $operation_id);
            }
        }
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            return false;
        }
    }

    function getDetailsToedit($queryParamString, $section_id)
    {
        $queryParamString = json_decode(base64_decode($queryParamString));
        extract((array)$queryParamString);
        $query = $this->db->query("select * from htmlquerytable where section_id=" . $section_id . " and field_type=14 AND query_type=1 order by id desc");
        if ($this->db->affected_rows() > 0) {

            $query_type = $query->row()->query_type;
            $table_name = $query->row()->table_name;
            $array_string = $query->row()->array_string;
            $wherearray_string = $query->row()->wherearray_string;
            $fieldType = $query->row()->field_type;
            $fieldId = $query->row()->field_id;
            if ((int)$fieldType == 8) {
                $array_string_exp = explode(",", $array_string);
            } else {
                $array_string_exp = explode("|", $array_string);
            }


            $final_array = array();
            $getData = $this->getDataFromCol($table_name, $wherearray_string, $queryParamString, $fieldType);

            if ($getData != false) {

                foreach ($array_string_exp as $a) {


                        $exp = explode(":", $a);
                        $column_name = $exp[0];
                        $hash_key = $exp[1];
                        $final_array[$hash_key] = $getData->$column_name;

                    $final_array['id'] = $getData->id;
                }

                return $final_array;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function load_service_amount()
    {
        $service_id=$this->input->post('service_id');
        $branch_id = $this->session->user_session->branch_id;
        if($service_id!=null)
        {
            $rate= $this->db->select('rate')->where('id', $service_id)->get('service_master')->row();
            // print_r($data);exit();
            if($this->db->affected_rows()>0)
            {
                $response['status']=200;
                $response['amount']=$rate->rate;
            }
            else
            {
                $response['status']=201;
                $response['amount']='';
            }
        }
        else
        {
            $response['status']=201;
            $response['body']="Sometheing Went Wrong";
        }
        echo json_encode($response);
    }
	function AddServiceorderBillFile(){
		$id=$this->input->post('serviceDescId');
		$service_given=$this->input->post('confirm_service_given');
		if($service_given == 0){
			$data=array(
				"bill_status"=>0,
			);
		}else{
			$name_input = "service_file";

			$upload_path = "uploads";
			$combination = 2;
			$result = $this->upload_file($upload_path, $name_input, $combination);
			// print_r($result);exit();
			if ($result->status) {
				if ($result->body[0] == "uploads/") {
					$input_data = "";
				} else {
					$input_data = implode(",",$result->body);
				}

			} else {
				$input_data = "";
			}
			$data=array(
				"add_to_bill_file"=>$input_data,
				"bill_status"=>1,
			);
		}

		$where=array(
			"id"=>$id,
		);
		$this->db->where($where);
		$update=$this->db->update("opd_service_order",$data);
		if($update == true){
			$response['status']=200;
			$response['body']="Updated Successfully";
		}else{
			$response['status']=201;
			$response['body']="Fail To Add";
		}echo json_encode($response);

	}
	function GetBillingInfoData(){
		$object=$this->input->post('object');
		$data = json_decode(base64_decode($object));
		extract((array)$data);
		$Query=$this->db->query("select *,(select service_description from service_master sm where sm.id=o.service_id ) as service_name from opd_service_order o where
 add_to_bill='Yes' AND status=1 AND patient_id=".$patient_id." AND branch_id=".$branch_id);
		//echo $this->db->last_query();
		$dataHtml="";
		$dataHtml .='<table class="table">
				<thead>
				<tr>
				<th>Service Description</th>
				<th>Service Date</th>
				<th>File</th>
				<th>Action</th>
				</tr>
</thead>
<tbody>
';
		if($this->db->affected_rows()>0){
			$result=$Query->result();

			foreach ($result as $row){
				$date=date("d-m-Y",strtotime($row->order_date));
				if($row->bill_status == 1){
					$check="checked";
					$btn="<button class='btn btn-link'><a href='".base_url().$row->add_to_bill_file."' download=''><i class='fa fa-download'></i></a></button>";
				}else{
					$check="";
					$btn="";
				}

				$dataHtml .="<tr>
			<td>".$row->service_name."</td>
			<td>".$date."</td>
			<td>".$btn."</td>
			<td><input type='checkbox' id='addtobill".$row->id."' ".$check." name='addtobill".$row->id."' onclick='BillinInfoChangestatus(".$row->id.")' value='".$row->id."'> </td>
			</tr>
			";
			}
			$dataHtml .="</tbody>";
			$dataHtml .="</table>";
			$response['status']=200;
			$response['data']=$dataHtml;
		}else{
			$dataHtml .="</tbody>";
			$dataHtml .="</table>";
			$response['status']=200;
			$response['data']=$dataHtml;
		}echo json_encode($response);

	}
}

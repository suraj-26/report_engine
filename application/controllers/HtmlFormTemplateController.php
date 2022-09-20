<?php
require_once 'HexaController.php';

use Dompdf\Dompdf;

/**
 * @property  LabReport LabReport
 */
class HtmlFormTemplateController extends HexaController
{


    /**
     * HtmlFormTemplateController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HtmlFormTemplateModel');
        $this->load->model('HtmlFormModel');

        $this->load->model('Global_model');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index()
    {
        $this->load->view('admin/HtmlFormTemplate/view_html_departments', array("title" => "HTML Template"));
    }

    public function Htmlform_template()
    {
        $this->load->view('admin/HtmlFormTemplate/htmlform_template', array("title" => "HTML Template"));
    }

    public function Html_template()
    {
        $this->load->view('admin/HtmlFormTemplate/Html_template', array("title" => "HTML Template"));
    }

    public function Html_template_drag()
    {
        $this->load->view('admin/HtmlFormTemplate/Html_template_drag', array("title" => "HTML Template"));
    }

    public function sample_page()
    {
        $this->load->view('admin/HtmlFormTemplate/sample_page', array("title" => "Sample Page"));
    }

    public function html_form_view($id = 0, $QueryParamter = null)
    {
        $this->load->view("admin/HtmlFormTemplate/html_section_form",
            array("title" => "Forms", "section_id" => $id, "queryParam" => $QueryParamter));
    }

    public function HtmlPageTemplate()
    {
        $this->load->view('HtmlPageTemplate/HtmlPageTemplate', array("title" => "HTML Page Template"));
    }

    function saveTemplate()
    {

        $validationObject = $this->is_parameter(array("department_id", "section_name", "elementSequenceType", "elementSequenceId"));
        if ($validationObject->status) {

            $params = $validationObject->param;
            $section_name = $params->section_name;

            $department_id = $params->department_id;
            $create_by = 1;
            $resultObject = $this->HtmlFormTemplateModel->getCompany($department_id);
            $elementObjectArray = array();
            $tableName = "";
            if (is_null($this->input->post("ck_template_transaction"))) {
                $sectionTransactionMode = 0;
            } else {
                $sectionTransactionMode = $this->input->post("ck_template_transaction") == "on" ? 1 : 0;
            }
            if (is_null($this->input->post("section_id"))) {
                $sectionId = null;
            } else {
                if ($this->input->post("section_id") == "") {
                    $sectionId = null;
                } else {
                    $sectionId = $this->input->post("section_id");
                }

            }
            if (is_null($this->input->post("ck_template_history"))) {
                $sectionHistoryMode = 0;
            } else {
                $sectionHistoryMode = $this->input->post("ck_template_history") == "on" ? 1 : 0;
            }

            if ($resultObject->totalCount == 1) {
                $company_id = $resultObject->data->company_id;
                $tableName = "formcom_" . $company_id . "_dep_" . $department_id;

                $elementsType = explode(",", $params->elementSequenceType);
                $elementsId = explode(",", $params->elementSequenceId);

                if (count($elementsType) == count($elementsId)) {
                    foreach ($elementsType as $typeIndex => $type) {
                        $index = $elementsId[$typeIndex];
                        switch ((int)$type) {
                            case 1:
                                array_push($elementObjectArray, $this->getShortText($index, $typeIndex, $type));
                                break;
                            case 2:
                                array_push($elementObjectArray, $this->getLongText($index, $typeIndex, $type));
                                break;
                            case 3:
                                array_push($elementObjectArray, $this->getDropDown($index, $typeIndex, $type));
                                break;
                            case 4:
                                array_push($elementObjectArray, $this->getMultipleDropDown($index, $typeIndex, $type));
                                break;
                            case 5:
                                array_push($elementObjectArray, $this->getDateText($index, $typeIndex, $type));
                                break;
                            case 6:
                                array_push($elementObjectArray, $this->getNumberText($index, $typeIndex, $type));
                                break;
                            case 7:
                                array_push($elementObjectArray, $this->getAttachmentText($index, $typeIndex, $type));
                                break;
                            case 8:
                                array_push($elementObjectArray, $this->getqueryDropDown($index, $typeIndex, $type));
                                break;
                            case 10:
                                array_push($elementObjectArray, $this->getfixnumber($index, $typeIndex, $type));
                                break;
                            case 11:
                                array_push($elementObjectArray, $this->getLabel($index, $typeIndex, $type));
                                break;
                            case 12:
                                array_push($elementObjectArray, $this->getCheckBoxGroup($index, $typeIndex, $type));
                                break;
                            case 13:
                                array_push($elementObjectArray, $this->getRadioGroup($index, $typeIndex, $type));
                                break;
                        }
                    }
                }
            }
            $response["sectionId"] = $sectionId;
            $resultObject = $this->HtmlFormTemplateModel->saveTemplate($section_name, $department_id, $elementObjectArray, $tableName, $create_by, $sectionId, $sectionTransactionMode, $sectionHistoryMode);
            $dataObject = new stdClass();
            $dataObject->sectionName = $params->section_name;
            $dataObject->data = $elementObjectArray;
            if ($resultObject->status) {
                // $resultHtml=$this->getSectionTemplateFormPersonal($department_id,$resultObject->section_id);
                // if($resultHtml!="")
                // {
                // 	$updateHtml=$this->HtmlFormTemplateModel->updateTemplateHtml($resultHtml,$resultObject->section_id);
                // }

                $response["status"] = 200;
                $response["body"] = "Save Changes";
                $response["data"] = $resultObject;
                $response["section_id"] = $resultObject->section_id;

            } else {
                $response["status"] = 201;
                $response["body"] = "Failed To Save Changes";
                $response["body"] = $dataObject;
                $response["data"] = $resultObject;
            }
        } else {
            $response["status"] = 201;
            $response["body"] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    private function getShortText($position, $sequence, $type)
    {
        $shortTextObject = new stdClass();
        $shortTextObject->sequeance_num = $sequence;
        $shortTextObject->ans_type = $type;

        $shortText = $this->input->post("short_text_" . $position);
        $shortTextId = $this->input->post("short_text_id_" . $position);
        $shortTextPlaceholder = $this->input->post("short_text_placeholder_" . $position);
        $shortTextRequired = $this->input->post("ck_short_text_required_" . $position);
        $shortTextHistory = $this->input->post("ck_short_text_history_" . $position);

        $shortTextObject->name = $shortText;
        if ($shortTextId != null)
            $shortTextObject->id = $shortTextId;
        if ($shortTextPlaceholder != null)
            $shortTextObject->placeholder = $shortTextPlaceholder;
        if ($shortTextRequired != null)
            $shortTextObject->is_required = $shortTextRequired == "on" ? 1 : 0;
        if ($shortTextHistory != null)
            $shortTextObject->is_history = $shortTextHistory == "on" ? 1 : 0;
        return $shortTextObject;
    }

    private function getLongText($position, $sequence, $type)
    {
        $longtextObject = new stdClass();
        $longtextObject->sequeance_num = $sequence;
        $longtextObject->ans_type = $type;

        $longText = $this->input->post("long_text_" . $position);
        $longTextId = $this->input->post("long_text_id_" . $position);
        $longTextPlaceholder = $this->input->post("long_text_placeholder_" . $position);
        $longTextRequired = $this->input->post("ck_long_text_required_" . $position);
        $longTextHistory = $this->input->post("ck_long_text_history_" . $position);

        $longtextObject->name = $longText;
        if ($longTextId != null)
            $longtextObject->id = $longTextId;
        if ($longTextPlaceholder != null)
            $longtextObject->placeholder = $longTextPlaceholder;
        if ($longTextRequired != null)
            $longtextObject->is_required = $longTextRequired == "on" ? 1 : 0;
        if ($longTextHistory != null)
            $longtextObject->is_history = $longTextHistory == "on" ? 1 : 0;
        return $longtextObject;
    }

    private function getDropDown($position, $sequence, $type)
    {
        $dropDownObject = new stdClass();
        $dropDownObject->sequeance_num = $sequence;
        $dropDownObject->ans_type = $type;
        $dropDown = $this->input->post("drop_down_" . $position);
        $dropDownId = $this->input->post("drop_down_id_" . $position);
        $dropDownPlaceholder = $this->input->post("drop_down_placeholder_" . $position);
        $dropDownRequired = $this->input->post("ck_drop_down_required_" . $position);
        $dropDownHistory = $this->input->post("ck_drop_down_history_" . $position);
        $dropDownOptions = $this->input->post("drop_down_option_value_" . $position);
        $dropDownObject->name = $dropDown;
        if ($dropDownId != null)
            $dropDownObject->id = $dropDownId;
        if ($dropDownPlaceholder != null)
            $dropDownObject->placeholder = $dropDownPlaceholder;
        if ($dropDownRequired != null)
            $dropDownObject->is_required = $dropDownRequired == "on" ? 1 : 0;
        if ($dropDownHistory != null)
            $dropDownObject->is_history = $dropDownHistory == "on" ? 1 : 0;
        if ($dropDownOptions != null) {
            $options = explode(",", $dropDownOptions);
            $optionsValues = array();
            foreach ($options as $value) {
                if (!empty($value)) {
                    array_push($optionsValues, $value);
                }
            }
            $dropDownObject->options = $optionsValues;
        } else {
            $dropDownObject->options = array();
        }

        return $dropDownObject;
    }

    private function getqueryDropDown($position, $sequence, $type)
    {
        $dropDownObject = new stdClass();
        $dropDownObject->sequeance_num = $sequence;
        $dropDownObject->ans_type = $type;
        $dropDown = $this->input->post("query_drop_down_" . $position);
        $dropDownId = $this->input->post("query_drop_down_id_" . $position);
        $dropDownPlaceholder = $this->input->post("query_drop_down_placeholder_" . $position);
        $dropDownRequired = $this->input->post("ck_query_drop_down_required_" . $position);
        $dropDownHistory = $this->input->post("ck_query_drop_down_history_" . $position);
        $dropDownquery = $this->input->post("query_drop_down_option_" . $position);
        $dropDowndependancy = $this->input->post("query_drop_down_dependant_" . $position);
        $dropDownisextended = $this->input->post("ck_query_drop_down_exttemp_" . $position);
        $dropDownexttempid = $this->input->post("slct_template_" . $position);

        $dropDownObject->name = $dropDown;
        if ($dropDownId != null)
            $dropDownObject->id = $dropDownId;
        if ($dropDownPlaceholder != null)
            $dropDownObject->placeholder = $dropDownPlaceholder;
        if ($dropDownRequired != null)
            $dropDownObject->is_required = $dropDownRequired == "on" ? 1 : 0;
        if ($dropDownHistory != null)
            $dropDownObject->is_history = $dropDownHistory == "on" ? 1 : 0;
        if ($dropDownquery != null)
            $dropDownObject->custom_query = $dropDownquery;
        if ($dropDowndependancy != null)
            $dropDownObject->dependancy = $dropDowndependancy;
        if ($dropDownisextended != null)
            $dropDownObject->is_extended_template = $dropDownisextended == "on" ? 1 : 0;
        if ($dropDownexttempid != null)
            $dropDownObject->ext_template_id = $dropDownexttempid;

        return $dropDownObject;
    }

    private function getMultipleDropDown($position, $sequence, $type)
    {
        $dropDownObject = new stdClass();
        $dropDownObject->sequeance_num = $sequence;
        $dropDownObject->ans_type = $type;
        $dropDown = $this->input->post("multi_drop_down_" . $position);
        $dropDownId = $this->input->post("multi_drop_down_id_" . $position);
        $dropDownPlaceholder = $this->input->post("multi_drop_down_placeholder_" . $position);
        $dropDownRequired = $this->input->post("ck_multi_drop_down_required_" . $position);
        $dropDownHistory = $this->input->post("ck_multi_drop_down_history_" . $position);
        $dropDownOptions = $this->input->post("multi_drop_down_option_value_" . $position);
        $dropDownObject->name = $dropDown;
        if ($dropDownId != null)
            $dropDownObject->id = $dropDownId;
        if ($dropDownPlaceholder != null)
            $dropDownObject->placeholder = $dropDownPlaceholder;
        if ($dropDownRequired != null)
            $dropDownObject->is_required = $dropDownRequired == "on" ? 1 : 0;
        if ($dropDownHistory != null)
            $dropDownObject->is_history = $dropDownHistory == "on" ? 1 : 0;
        if ($dropDownOptions != null) {
            $options = explode(",", $dropDownOptions);
            $optionsValues = array();
            foreach ($options as $value) {
                if (!empty($value)) {
                    array_push($optionsValues, $value);
                }
            }
            $dropDownObject->options = $optionsValues;
        } else {
            $dropDownObject->options = array();
        }
        return $dropDownObject;
    }

    private function getNumberText($position, $sequence, $type)
    {
        $shortTextObject = new stdClass();
        $shortTextObject->sequeance_num = $sequence;
        $shortTextObject->ans_type = $type;
        $shortText = $this->input->post("number_element_" . $position);
        $shortTextId = $this->input->post("number_text_id_" . $position);
        $shortTextPlaceholder = $this->input->post("number_placeholder_" . $position);
        $shortTextRequired = $this->input->post("ck_number_text_required_" . $position);
        $shortTextHistory = $this->input->post("ck_number_text_history_" . $position);

        $shortTextObject->name = $shortText;
        if ($shortTextId != null)
            $shortTextObject->id = $shortTextId;
        if ($shortTextPlaceholder != null)
            $shortTextObject->placeholder = $shortTextPlaceholder;
        if ($shortTextRequired != null)
            $shortTextObject->is_required = $shortTextRequired == "on" ? 1 : 0;
        if ($shortTextHistory != null)
            $shortTextObject->is_history = $shortTextHistory == "on" ? 1 : 0;
        return $shortTextObject;
    }

    private function getfixnumber($position, $sequence, $type)
    {
        $shortTextObject = new stdClass();
        $shortTextObject->sequeance_num = $sequence;
        $shortTextObject->ans_type = $type;
        $shortText = $this->input->post("fixnumber_element_" . $position);
        $shortTextId = $this->input->post("fixnumber_text_id_" . $position);
        $shortTextPlaceholder = $this->input->post("fixnumber_placeholder_" . $position);
        $shortTextRequired = $this->input->post("fixck_number_text_required_" . $position);
        $shortTextHistory = $this->input->post("fixck_number_text_history_" . $position);
        $shortTextquery = $this->input->post("fixnumber_drop_down_option_" . $position);

        $shortTextObject->name = $shortText;
        if ($shortTextId != null)
            $shortTextObject->id = $shortTextId;
        if ($shortTextPlaceholder != null)
            $shortTextObject->placeholder = $shortTextPlaceholder;
        if ($shortTextquery != null)
            $shortTextObject->custom_query = $shortTextquery;
        if ($shortTextRequired != null)
            $shortTextObject->is_required = $shortTextRequired == "on" ? 1 : 0;
        if ($shortTextHistory != null)
            $shortTextObject->is_history = $shortTextHistory == "on" ? 1 : 0;
        return $shortTextObject;
    }

    private function getDateText($position, $sequence, $type)
    {
        $shortTextObject = new stdClass();
        $shortTextObject->sequeance_num = $sequence;
        $shortTextObject->ans_type = $type;
        $shortText = $this->input->post("date_element_" . $position);
        $shortTextId = $this->input->post("date_text_id_" . $position);
        $shortTextPlaceholder = $this->input->post("date_placeholder_" . $position);
        $shortTextRequired = $this->input->post("ck_date_text_required_" . $position);
        $shortTextHistory = $this->input->post("ck_date_text_history_" . $position);

        $shortTextObject->name = $shortText;
        if ($shortTextId != null)
            $shortTextObject->id = $shortTextId;
        if ($shortTextPlaceholder != null)
            $shortTextObject->placeholder = $shortTextPlaceholder;
        if ($shortTextRequired != null)
            $shortTextObject->is_required = $shortTextRequired == "on" ? 1 : 0;
        if ($shortTextHistory != null)
            $shortTextObject->is_history = $shortTextHistory == "on" ? 1 : 0;
        return $shortTextObject;
    }

    private function getAttachmentText($position, $sequence, $type)
    {
        $shortTextObject = new stdClass();
        $shortTextObject->sequeance_num = $sequence;
        $shortTextObject->ans_type = $type;
        $shortText = $this->input->post("attachment_element_" . $position);
        $shortTextId = $this->input->post("attachment_id_" . $position);
        $shortTextPlaceholder = $this->input->post("attachment_placeholder_" . $position);
        $shortTextRequired = $this->input->post("ck_attachment_text_required_" . $position);
        $shortTextHistory = $this->input->post("ck_attachment_text_history_" . $position);

        $shortTextObject->name = $shortText;
        if ($shortTextId != null)
            $shortTextObject->id = $shortTextId;
        if ($shortTextPlaceholder != null)
            $shortTextObject->placeholder = $shortTextPlaceholder;
        if ($shortTextRequired != null)
            $shortTextObject->is_required = $shortTextRequired == "on" ? 1 : 0;
        if ($shortTextHistory != null)
            $shortTextObject->is_history = $shortTextHistory == "on" ? 1 : 0;
        return $shortTextObject;
    }

    private function getLabel($position, $sequence, $type)
    {
        $shortTextObject = new stdClass();
        $shortTextObject->sequeance_num = $sequence;
        $shortTextObject->ans_type = $type;
        $shortText = $this->input->post("label_" . $position);
        $shortTextId = $this->input->post("label_text_id_" . $position);
        if ($shortTextId != null)
            $shortTextObject->id = $shortTextId;

        $shortTextObject->name = $shortText;
        return $shortTextObject;


    }

    private function getRadioGroup($position, $sequence, $type)
    {
        $dropDownObject = new stdClass();
        $dropDownObject->sequeance_num = $sequence;
        $dropDownObject->ans_type = $type;
        $dropDown = $this->input->post("radio_box_" . $position);
        $dropDownId = $this->input->post("radio_box_id_" . $position);
        $dropDownRequired = $this->input->post("ck_radio_box_required_" . $position);
        $dropDownOptions = $this->input->post("radio_box_option_value_" . $position);
        $dropDownObject->name = $dropDown;
        if ($dropDownId != null)
            $dropDownObject->id = $dropDownId;
        if ($dropDownRequired != null)
            $dropDownObject->is_required = $dropDownRequired == "on" ? 1 : 0;
        if ($dropDownOptions != null) {
            $options = explode(",", $dropDownOptions);
            $optionsValues = array();
            foreach ($options as $value) {
                if (!empty($value)) {
                    array_push($optionsValues, $value);
                }
            }
            $dropDownObject->options = $optionsValues;
        } else {
            $dropDownObject->options = array();
        }

        return $dropDownObject;
    }

    private function getCheckBoxGroup($position, $sequence, $type)
    {

        $dropDownObject = new stdClass();
        $dropDownObject->sequeance_num = $sequence;
        $dropDownObject->ans_type = $type;
        $dropDown = $this->input->post("check_box_" . $position);
        $dropDownId = $this->input->post("check_box_id_" . $position);
        $dropDownRequired = $this->input->post("ck_check_box_required_" . $position);
        $dropDownOptions = $this->input->post("check_box_option_value_" . $position);
        $dropDownObject->name = $dropDown;
        if ($dropDownId != null)
            $dropDownObject->id = $dropDownId;
        if ($dropDownRequired != null)
            $dropDownObject->is_required = $dropDownRequired == "on" ? 1 : 0;
        if ($dropDownOptions != null) {
            $options = explode(",", $dropDownOptions);
            $optionsValues = array();
            foreach ($options as $value) {
                if (!empty($value)) {
                    array_push($optionsValues, $value);
                }
            }
            $dropDownObject->options = $optionsValues;
        } else {
            $dropDownObject->options = array();
        }

        return $dropDownObject;
    }

    public function getDepartmentSections()
    {
        $validationObject = $this->is_parameter(array("department_id"));
        if ($validationObject->status) {
            $param = $validationObject->param;
            $resultObject = $this->HtmlFormTemplateModel->fetchTemplateSections($param->department_id);
            $response["status"] = 200;
            $response["body"] = $resultObject->data;
        } else {
            $response["status"] = 201;
            $response["body"] = "Missing Parameter";
        }
        echo json_encode($response);
    }

    public function getSectionElements()
    {
        $validationObject = $this->is_parameter(array("department_id", "section_id"));
        if ($validationObject->status) {
            $param = $validationObject->param;
            $resultObject = $this->HtmlFormTemplateModel->fetchSectionElement($param->section_id, $param->department_id);
            // print_r($resultObject);exit();
            $response["status"] = 200;
            $response["body"] = $resultObject->data;
        } else {
            $response["status"] = 201;
            $response["body"] = "Missing Parameter";
        }
        echo json_encode($response);
    }

    public function deleteSection()
    {
        $validationObject = $this->is_parameter(array("section_id"));
        if ($validationObject->status) {
            $param = $validationObject->param;
            $resultObject = $this->HtmlFormTemplateModel->deleteSection($param->section_id);
            if ($resultObject->status) {
                $response["data"] = $resultObject;
                $response["status"] = 200;
                $response["body"] = "Delete Section";
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

    public function deleteTemplateElement()
    {
        $validationObject = $this->is_parameter(array("templateID"));
        if ($validationObject->status) {
            $param = $validationObject->param;
            $resultObject = $this->HtmlFormTemplateModel->deleteElement($param->templateID);
            if ($resultObject->status) {
                $response["data"] = $resultObject;
                $response["status"] = 200;
                $response["body"] = "Delete Element";
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

    function gettemplatelist()
    {
        $query = $this->HtmlFormTemplateModel->getTemplateList();
        $option = "<option value='-1'>Select Template</option>";
        if ($query != false) {

            foreach ($query as $data) {
                $option .= "<option value=" . $data->id . ">" . $data->name . "</option>";
            }
            $response["status"] = 200;
            $response["data"] = $option;
        } else {
            $response["status"] = 201;
            $response["data"] = $option;
        }
        echo json_encode($response);
    }

    function getSectionTemplateFormPersonal($section_id)
    {
        // $department_id = $this->input->post('department_id');
        // $patient_id = $this->input->post('patient_id');
        // $section_id = $this->input->post('section_id');
        $patient_id = null;
        $query = $this->HtmlFormModel->get_personalTemplate_form($department_id, $section_id);
        $data = "";
        $button_data = "";
        $template_name = "-";
        if ($query != false) {
            $patientResultObject = null;
            $patientObject = null;
            $active = 0;
            $field = "";

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

                $data .= '
				
				<div id="accordion_' . $section_id . '">
	<div class="accordion">
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
							<form id="data_form_' . $section_id . '" method="post" data-form-valid="save_form_data" onsubmit="return false">
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
                    } else if ($value1->ans_type == 11) {
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					';
                    } else if ($value1->ans_type == 12) {
                        $get_option = $this->HtmlFormModel->get_all_options($value1->id);
                        $selected = "";
                        $option = "";
                        $selectedOptions = explode(",", $patientValue);
                        foreach ($get_option as $option_value) {

                            foreach ($selectedOptions as $o_value) {
                                if ($o_value == $option_value->name) {
                                    $selected = "checked";
                                    break;
                                } else {
                                    $selected = "";
                                }
                            }

                            $option .= ' <input type="checkbox" ' . $selected . ' value="' . $option_value->name . '" name="form_field' . $value1->id . '[]" id="form_field' . $value1->id . '"> ' . $option_value->name;
                        }
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;"> ' . $value1->name . '</label>
					<div class="col-sm-9">' . $option . '</div>
					';


                    } else if ($value1->ans_type == 13) {
                        $get_option = $this->HtmlFormModel->get_all_options($value1->id);
                        $selected = "";
                        $option = "";
                        // print_r($patientValue);
                        // $selectedOptions = explode(",", $patientValue);

                        foreach ($get_option as $option_value) {

                            // foreach ($selectedOptions as $o_value) {

                            if ($patientValue == $option_value->name) {
                                // echo $option_value->name;
                                $selected = "checked";
                                // break;
                            } else {
                                $selected = "";
                            }
                            // }

                            $option .= ' <input type="radio" ' . $selected . ' value="' . $option_value->name . '" name="form_field' . $value1->id . '" id="form_field' . $value1->id . '"> ' . $option_value->name;
                        }
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;"> ' . $value1->name . '</label>
					<div class="col-sm-9">' . $option . '</div>
					';


                    } else if ($value1->ans_type == 6) {
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="number" class="form-control" step="any" id="form_field' . $value1->id . '" value="' . $patientValue . '" name="form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '" ' . $validation . ' /></div>';
                    } else if ($value1->ans_type == 5) {
                        $isDate = '';
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

                            $option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . $option_value->name . '</option>';
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
                                $option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . $option_value->name . '</option>';
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
                                $url = base_url() . "HtmlFormController/get_data?query=$qren";
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
								  minimumInputLength: 1
								 }
									)</script>
								';
                            } else {
                                $qren = base64_encode($query);
                                $url = base_url() . "HtmlFormController/get_data?query=$qren";
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
								  minimumInputLength: 1
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
							
					  </div>
                    ';
                    }
                }
                $data .= "</div><div class='text-right'>
									<button class='btn btn-primary mr-1' type='button' onclick='save_form_data(" . $section_id . ")'>Submit
									</button>
									<button class='btn btn-secondary' type='reset'>Reset</button>
								</div></form></div>";

                $data .= "</div>
				<div id='history_div_" . $section_id . "' class='d-none'></div>
				<div id='graph_div_" . $section_id . "' class='d-none'>
						<div class='row' id='graph_section_" . $section_id . "'>
						
						</div>
				</div></div></div>
				 
       ";
            }
            // $response["template_name"] = $template_name;
            // $response['code'] = 200;
            // $response['data'] = $data;
            // $response['button_data'] = $button_data;
            return $data;
        } else {
            // $response['code'] = 201;
            // $response['data'] = '';
            // $response['button_data'] = '';
            return "";
        }


        echo json_encode($response);
    }

    function add_form_data()
    {
        // print_r($this->input->post());exit();
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

                } else if ($ans_type == 12) {

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
                    // $insert=true;
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

    function get_form_fields()
    {
        $department_id = $this->input->post('department_id');
        $patient_id = $this->input->post('patient_id');

        $query = $this->HtmlFormModel->get_form($department_id);
        $data = "";
        $button_data = "";
        $template_name = "-";
        if ($query != false) {
            $patientResultObject = null;
            $patientObject = null;
            $active = 0;
            $field = "";

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
                $button_data .= '
				<button id="persTemModalBtn_' . $section_id . '" class="btn btn-primary myBtn mt-2" data-toggle="modal" data-target="#persTemModal_' . $section_id . '">' . $value->section_name . '</button>';
                $data .= '
				  <div id="persTemModal_' . $section_id . '" class="modal" >
				  <div class="modal-dialog modal-xl">
				      <div class="modal-content">
				        <div class="modal-header modal_header">
				         
				          <h4 class="modal-title">' . $value->section_name . '</h4>
				           <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
				        </div>
				        <div class="modal-body">
				<div id="accordion_' . $section_id . '">
	<div class="accordion">
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
                    } else if ($value1->ans_type == 11) {
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					';
                    } else if ($value1->ans_type == 12) {
                        $get_option = $this->HtmlFormModel->get_all_options($value1->id);
                        $selected = "";
                        $option = "";
                        $selectedOptions = explode(",", $patientValue);
                        foreach ($get_option as $option_value) {

                            foreach ($selectedOptions as $o_value) {
                                if ($o_value == $option_value->name) {
                                    $selected = "checked";
                                    break;
                                } else {
                                    $selected = "";
                                }
                            }

                            $option .= ' <input type="checkbox" ' . $selected . ' value="' . $option_value->name . '" name="form_field' . $value1->id . '[]" id="form_field' . $value1->id . '"> ' . $option_value->name;
                        }
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;"> ' . $value1->name . '</label>
					<div class="col-sm-9">' . $option . '</div>
					';


                    } else if ($value1->ans_type == 13) {
                        $get_option = $this->HtmlFormModel->get_all_options($value1->id);
                        $selected = "";
                        $option = "";
                        // print_r($patientValue);
                        // $selectedOptions = explode(",", $patientValue);

                        foreach ($get_option as $option_value) {

                            // foreach ($selectedOptions as $o_value) {

                            if ($patientValue == $option_value->name) {
                                // echo $option_value->name;
                                $selected = "checked";
                                // break;
                            } else {
                                $selected = "";
                            }
                            // }

                            $option .= ' <input type="radio" ' . $selected . ' value="' . $option_value->name . '" name="form_field' . $value1->id . '" id="form_field' . $value1->id . '"> ' . $option_value->name;
                        }
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;"> ' . $value1->name . '</label>
					<div class="col-sm-9">' . $option . '</div>
					';


                    } else if ($value1->ans_type == 6) {
                        $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1->name . '</label>
					<div class="col-sm-9">
					<input type="number" class="form-control" step="any" id="form_field' . $value1->id . '" value="' . $patientValue . '" name="form_field' . $value1->id . '" placeholder="' . $value1->placeholder . '" ' . $validation . ' /></div>';
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

                            $option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . $option_value->name . '</option>';
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
                                $option .= '<option value="' . $option_value->id . '" ' . $selected . '>' . $option_value->name . '</option>';
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
                                $url = base_url() . "HtmlFormController/get_data?query=$qren";
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
								  minimumInputLength: 1
								 }
									)</script>
								';
                            } else {
                                $qren = base64_encode($query);
                                $url = base_url() . "HtmlFormController/get_data?query=$qren";
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
								  minimumInputLength: 1
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
                $data .= "</div><div class='text-right'>
									<button class='btn btn-primary mr-1' type='submit'>Submit
									</button>
									<button class='btn btn-secondary' type='reset'>Reset</button>
								</div></form></div>";

                $data .= "</div>
				<div id='history_div_" . $section_id . "' class='d-none'></div>
				<div id='graph_div_" . $section_id . "' class='d-none'>
						<div class='row' id='graph_section_" . $section_id . "'>
						
						</div>
				</div></div></div>
				 
          
        </div>
      </div>
      </div>
    </div>";
            }
            $response["template_name"] = $template_name;
            $response['code'] = 200;
            $response['data'] = $data;
            $response['button_data'] = $button_data;

        } else {
            $response['code'] = 201;
            $response['data'] = '';
            $response['button_data'] = '';
        }


        echo json_encode($response);
    }

    public function fetch_template_sectionsHtml()
    {
        $section_id = $this->input->post('section_id');

        $query = $this->HtmlFormTemplateModel->get_HtmlFormSection($section_id);
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

    public function GetQueryDataInsert($ButtonQueryString, $section_id, $department_id)
    {

        $ButtonQueryString = json_decode($ButtonQueryString);
        $finalCount = ($this->input->post('finalCount')) - 1;
        $ButtonInsertID = ($this->input->post('ButtonInsertID'));
        // var_dump();
        $ButtonQueryString = (array)json_decode($ButtonQueryString);
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
        foreach ($tableArray as $index => $column) {
            foreach ($ButtonQueryString as $key => $d) {
                if (strpos($key, 'otherData' . $index . '_') !== false) {

                    $columnArray[$index][] = $d;
                }
            }
        }


        $QuerydataToInsert = array();
        $QuerydataToInsert2 = array();
        foreach ($columnArray as $ind => $m) {
            $ColumnDataInsert = implode("|", $m);
            $TableName = $tableArray[$ind];
            $QuerydataToInsert['array_string'] = $ColumnDataInsert;
            $QuerydataToInsert['table_name'] = $TableName;
            $QuerydataToInsert['field_id'] = $ButtonInsertID;
            $QuerydataToInsert['field_type'] = 14;
            $QuerydataToInsert['department_id'] = $department_id;
            array_push($QuerydataToInsert2, $QuerydataToInsert);

        }
        return $QuerydataToInsert2;
    }

    public function save_template_sectionsHtml()
    {
        // print_r($this->input->post());exit();
        $validationObject = $this->is_parameter(array("department_id", "section_name", "elementSequenceType", "elementSequenceId", "elementSequenceText", "editor", "isRequired", 'QueryStringParameter', 'textMainInputArray'));
        if ($validationObject->status) {


            $params = $validationObject->param;
            $section_name = $params->section_name;
            $department_id = $params->department_id;
            $elementSequenceType = $params->elementSequenceType;
            $elementSequenceId = $params->elementSequenceId;
            $elementSequenceText = $params->elementSequenceText;
            $elementSequenceText = $params->elementSequenceText;
            $QueryStringParameter = $params->QueryStringParameter;
            $history_unabled = $this->input->post('history_unabled');
            $dependant_section_id = $this->input->post('depend_section_id');
            $history_unabled1 = ($history_unabled == 'on') ? 1 : 0;
            $section_id = $this->input->post('section_id');
            $section_form_type = $this->input->post('form_type');
            $primary_table = $this->input->post('primary_table');
            $primaryTableInsert = $this->input->post('primaryTableInsert');
            $primaryTableButton = $this->input->post('primaryTableButton');
            $colorPicker = $this->input->post('colorPicker');
            $colorCardBodyPicker = $this->input->post('colorCardBodyPicker');
            $colorCardBodyTextPicker = $this->input->post('colorCardBodyTextPicker');
            $theme_view = $this->input->post('theme_view');
            $colorHeaderTextPicker = $this->input->post('colorHeaderTextPicker');


            $primaryTableColumnArray = $this->input->post('primaryTableColumnArray');
            $historyTableButton = $this->input->post('historyTableButton');
            $textMainInputArray = $params->textMainInputArray;
            // echo $textMainInputArray;exit();

            $primary_data = array();
            if ($primary_table != null && $primary_table != "" && $primary_table != 1) {
                $primary_data = array('department_id' => $department_id,
                    'array_string' => $primaryTableInsert,
                    'table_name' => $primary_table,
                    'field_id' => $primaryTableButton,
                    'field_type' => 14);
            }
            // print_r($primaryTableInsert);exit();
            $datatable_insert = array();
            if ($history_unabled1 == 1) {
                if (!empty($primaryTableColumnArray)) {
                    $where_condition = 'whereTableColumn:status|WhereColumnValue:1|';
                    // $select_column=implode(',', $primaryTableColumnArray);
                    $datatable_insert = array('elementID' => $historyTableButton,
                        'table_name' => $primary_table,
                        'select_column' => $primaryTableColumnArray,
                        'where_condition' => $where_condition,
                        'table_type' => 1,
                        'status' => 1);
                }
            }


            //$explode_t=explode(",",$QueryStringParameter);
            $querystringParaName = array();
            $querystringParaType = array();
            $QueryStringParameterDataFinal = array();
            foreach ($QueryStringParameter as $exp) {
                $exp2 = explode(":", $exp);
                $querystringParaName = $exp2[0];
                $querystringParaType = $exp2[1];
                $QueryStringParameterData = array(
                    "department_id" => $department_id,
                    //"section_id"=>$section_id,
                    "field_id" => $querystringParaName,
                    "field_type" => $querystringParaType,
                    "is_required" => 0,
                    "type" => 1
                );
                array_push($QueryStringParameterDataFinal, $QueryStringParameterData);


            }
            $QueryStringParameter = implode(",", $QueryStringParameter);

            $isRequired = json_decode($params->isRequired);

            $editor = $params->editor;


            //
            // print_r($final_data);exit();
            $GetQueryDataInsert = false;

            $insert_data = array('name' => $section_name,
                'status' => 1,
                'create_on' => date('Y-m-d H:i:s'),
                'create_by' => $this->session->user_session->id,
                'department_id' => $department_id,
                'dependant_section_id' => $dependant_section_id,
                'html_section_types' => $elementSequenceType,
                'html_section_ids' => $elementSequenceId,
                'query_string_parameter' => $QueryStringParameter,
                'history_unabled' => $history_unabled1,
                'html_section_text' => $elementSequenceText,
                'form_type' => $section_form_type,
                'primary_table' => $primary_table,
                'card_color' => $colorPicker,
                'card_body_color' => $colorCardBodyPicker,
                'card_body_text_color' => $colorCardBodyTextPicker,
                'html_section_mainInput' => $textMainInputArray,
                'card_head_text_color' => $colorHeaderTextPicker,
                'theme_view' => $theme_view);

            $updateHtml = $this->HtmlFormTemplateModel->addSectionTemplateHtml($section_id, $insert_data, $GetQueryDataInsert, $isRequired, $department_id, $QueryStringParameterDataFinal, $primary_data, $primary_table, $section_form_type, $datatable_insert);


            if ($updateHtml->status == true) {
                $color = "#891635";
                if ($colorPicker != null && $colorPicker != "") {
                    $color = $colorPicker;
                }
                $final_data = "<style>.card_border" . $section_id . "
							{
                            //box-shadow: 2px 2px 8px 1px #2c2c2c5c;
							}
							.card_head_back" . $section_id . "{
								 border-bottom: 2px solid " . $color . " !important;
							    background: " . $color . " !important;
							    color: " . $colorHeaderTextPicker . " !important;
                                min-height: 50px!important;
                                padding: 10px 20px!important;
                                line-height: 20px!important;
                                text-align: center!important;

							}
							
							.btn_color_class" . $section_id . "
							{
								border-bottom: 2px solid " . $color . " !important;
							    background: " . $color . " !important;
							    color: " . $colorHeaderTextPicker . " !important;
							}
							.select2-container{
								width: 100%!important;
							}.card_body" . $section_id . "{
								background-color:" . $colorCardBodyPicker . " !important;
								color:" . $colorCardBodyTextPicker . " !important;
							}
							</style>";

                $final_data .= $this->getSectionFormData($editor, $elementSequenceType, $elementSequenceId, $elementSequenceText, $department_id, $updateHtml->section_id, $isRequired, $history_unabled1, $textMainInputArray, $section_name);


                if ($final_data != "") {
                    // print_r($editor);exit();
                    $updateHtml1 = $this->HtmlFormTemplateModel->updateTemplateHtml($editor, $updateHtml->section_id, $final_data, $QueryStringParameter, $history_unabled1);
                    //$QueryStringParameterDataFinal
                    $updateData = $this->HtmlFormTemplateModel->UpdateQueryStringData($QueryStringParameterDataFinal, $updateHtml->section_id);
                }

                //insert Query Data by pooja

                //$data=$this->InsertQueryData();
                $response['status'] = 200;
                $response['section_id'] = $updateHtml->section_id;
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

    public function getSectionFormData($editor, $elementSequenceType, $elementSequenceId, $elementSequenceText, $department_id, $section_id, $isRequired, $history_unabled1, $textMainInputArray, $section_name)
    {

        $resultArray = $this->getSectionForm($editor, $elementSequenceType, $elementSequenceId, $elementSequenceText, $department_id, $section_id, $isRequired, $history_unabled1, $textMainInputArray);
        // print_r($resultArray);exit();
        $dataTable_editor = "";
        if (!empty($resultArray)) {
            foreach ($resultArray as $key => $value) {
                // print_r($value);
                $editor = str_replace($value->mainInput, $value->field, $editor);
                if ($value->ans_type == 17) {
                    $dataTable_editor .= '<div id="dynamic_datatable_' . $value->value1 . '" style="padding:20px;overflow:auto;">
							<script>loadDataTable("#' . $value->value1 . '",' . $section_id . ')</script></div>';
                } else if ($value->ans_type == 23) {
                    $dataTable_editor .= '<div id="dynamic_exceltable_' . $value->value1 . '" style="padding:20px;overflow:auto;">
							<script>exceltabledata("' . $value->value1 . '",' . $section_id . ')</script></div>';
                }

            }
        }

        return '<div class="card card_border' . $section_id . ' shadow-none">
					                <div class="card-header card_head_back' . $section_id . '">
					                    	<h4 class="m-0" id="section_name">' . $section_name . '</h4>
					                    <div class="card-header-action">
					                    	<button class="btn btn-default" type="button" style="float:right;display:none" id="formButton" onclick="switch_form_history(2)"> View Form</button>
					                     	<button class="btn btn-default" type="button"id="HistoryButton" style="float:right;margin-right:10px;display:none;"onclick="switch_form_history(1)"> View History</button>
					                    </div>
					                </div>
				                  	<div class="card-body card_body' . $section_id . '">
				                  	<form method="post" id="form_' . $section_id . '">' . $editor . '</form>
				                  	' . $dataTable_editor . '
				                  	<script>var posRevert=Array.from(document.getElementById("form_' . $section_id . '").children);
				                  	posRevert.forEach(child => child.style.position="relative");
				                  	$(".removeDeleteButton").remove();</script>
				                  	</div>
				                </div>';

    }

    function getSectionForm($editor, $elementSequenceType, $elementSequenceId, $elementSequenceText, $department_id, $section_id, $isRequired, $history_unabled1, $textMainInputArray)
    {
        // print_r($elementSequenceId);exit();
        $sectionFieldArray = array();
        $data = "";
        $template_name = "-";
        $class_name = "form-control";
        $elementSequenceText = explode(",", $elementSequenceText);
        $elementSequenceType = explode(",", $elementSequenceType);
        $elementSequenceId = explode(",", $elementSequenceId);
        $textMainInputArray = explode(",", $textMainInputArray);
        if (!empty($elementSequenceText) && !empty($elementSequenceText[0])) {
            //is_traction
            foreach ($elementSequenceText as $index => $value) {

                $textexplode = explode('#', $value);
                $validation = "";
                $default_val = "";
                $min_val = "";
                $max_val = "";
                $placeholder = "";
                $field = "";
                foreach ($isRequired as $required) {
                    if ($required->id == $value) {
                        if ((int)$required->is_req == 1) {
                            // $validation = 'data-valid="required" data-msg="Please Fill this Field"';
                            $validation = 'required';
                        }
                        $default_val = $required->default_val;
                        $min_val = ' min="' . $required->min_val . '"';
                        $max_val = ' max="' . $required->max_val . '"';
                        $placeholder = ' placeholder="' . $required->placeholder . '"';
                        break;
                    }
                }
                $value1 = $textexplode[1];
                $ans_type = $elementSequenceType[$index];
                $seq_id = $elementSequenceId[$index];
                $mainInput = $textMainInputArray[$index];
                if ($ans_type == 1) {
                    $disabled = "";
                    $type = "text";
                    $lable = "";

                    $field = '<input type="' . $type . '" class="' . $class_name . '"  id="' . $value1 . '" name="' . $value1 . '" ' . $validation . ' ' . $placeholder . ' value="' . $default_val . '" />';


                } else if ($ans_type == 10) {
                    $order_id = $this->Global_model->generate_order($value1->custom_query);

                    $field = '
					<input type="text" class="' . $class_name . '" id="form_field' . $value1->id . '" value="' . $order_id . '"   name="form_field' . $value1->id . '" ' . $placeholder . ' ' . $validation . ' />';
                } else if ($ans_type == 11) {
                    $field = '
					<label class="col-sm-3 col-form-label font-weight-bold"  style="font-size: medium; color: brown;">' . $value1 . '</label>
					';
                } else if ($ans_type == 12) {
                    $get_option = $this->input->post('dropDownOptions');
                    $option = $this->get_options($get_option, $value1, $ans_type, $department_id, $section_id, $default_val);
                    // print_r($option);exit();
                    $selected = "";
                    $field = '' . $option . '';


                } else if ($ans_type == 13) {
                    $get_option = $this->input->post('dropDownOptions');
                    $option = $this->get_options($get_option, $value1, $ans_type, $department_id, $section_id, $default_val);
                    // print_r($option);exit();
                    $field = '' . $option . '';
                } else if ($ans_type == 14) {

                    $field = '<button class="btn btn_color_class' . $section_id . ' mt-2 ' . $class_name . '" type="button" id="' . $value1 . '" onclick="insertDataForm(\'' . $value1 . '\',' . $section_id . ')">Save</button>';

                } else if ($ans_type == 15) {

                    $field = '<button class="btn btn_color_class' . $section_id . ' mt-2 ' . $class_name . '" type="button" id="' . $value1 . '" onclick="getDataHTML(\'' . $value1 . '\',' . $section_id . ',' . $ans_type . ',\'' . $value . '\')"><i class="fa fa-download"></i> Excel Report</button>
							';

                } else if ($ans_type == 16) {

                    $field = '<button class="btn btn_color_class' . $section_id . ' mt-2 ' . $class_name . '" type="button" id="' . $value1 . '" onclick="getDataHTML(\'' . $value1 . '\',' . $section_id . ',' . $ans_type . ',\'' . $value . '\')"><i class="fa fa-download"></i> PDF Report</button>
							';
                } else if ($ans_type == 19) {

                    $field = '<button class="btn btn_color_class' . $section_id . ' mt-2 ' . $class_name . '" type="button" id="' . $value1 . '" onclick="getDataHTML(\'' . $value1 . '\',' . $section_id . ',' . $ans_type . ',\'' . $value . '\')"><i class="fa fa-download"></i> CSV Report</button>
							';
                } else if ($ans_type == 20) {

                    $field = '<div id="' . $value1 . '"></div>
							';
                } else if ($ans_type == 17 || $ans_type == 18) {

                    $field = '<div></div>
							';
                } else if ($ans_type == 23) {
                    $field = '<div></div>';

                } else if ($ans_type == 24) {
                    $field = ' <div id="saveForm_' . $value1 . '">
							<div class="row pt-3">
								<div class="col-12 col-md-12">
									<input type="hidden" name="transTablename" id="transTablename_' . $value1 . '"/>
									<table id="example_' . $value1 . '" class="table table-borderless" style="width:100%">
										<thead id="tableHead_' . $value1 . '" ></thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 ">
									<div class="row justify-content-end pr-4">
										<button class="btn btn-outline-primary" type="button" id="saveButton_' . $value1 . '">
											Save
										</button>
									</div>
								</div>
							</div>
						</div>
						<script> getHeaders(\'' . $value1 . '\',' . $section_id . ',' . $department_id . ');</script>';

                } else if ($ans_type == 6) {
                    $field = '<input type="number" step="any" class="' . $class_name . '" id="' . $value1 . '" name="' . $value1 . '" ' . $validation . ' value="' . $default_val . '" ' . $min_val . ' ' . $max_val . ' />';
                } else if ($ans_type == 5) {

                    $date_exp = explode(',', $default_val);
                    $default_val = "";
                    $date_script = "";
                    if (!empty($date_exp) && count($date_exp) > 1) {
                        if ($date_exp[0] == 1) {
                            $default_val = "";
                        } else if ($date_exp[0] == 2) {
                            $date_script = '
							<script>
							var dt = new Date();   
   							var month = dt.getMonth()+1;
							var day = dt.getDate();
							var output = dt.getFullYear() + "-" +
						    (month<10 ? "0" : "") + month + "-" +
						    (day<10 ? "0" : "") + day;
							 $("#' . $value1 . '").val(output);
							 </script>';
                        } else if ($date_exp[0] == 3) {
                            $default_val = $date_exp[1];
                        }
                    }
                    $field = '<input type="date" class="' . $class_name . '" id="' . $value1 . '"  name="' . $value1 . '" ' . $validation . ' ' . $placeholder . ' value="' . $default_val . '" ' . $min_val . ' ' . $max_val . ' />' . $date_script;
                } else if ($ans_type == 2) {
                    $field = '<textarea id="' . $value1 . '" class="' . $class_name . '"  name="' . $value1 . '" ' . $validation . ' ' . $placeholder . '>' . $default_val . '</textarea>';
                } else if ($ans_type == 3) {
                    $get_option = $this->input->post('dropDownOptions');

                    // print_r($get_option);exit();
                    $option = $this->get_options($get_option, $value1, $ans_type, $department_id, $section_id, $default_val);
                    // print_r($option);exit();
                    $onchange = "";

                    $field = '<select class="custom-select ' . $class_name . '" id="' . $value1 . '" name="' . $value1 . '" ' . $validation . '>
							' . $option . '
								</select>
								<script>$("#' . $value1 . '").select2()</script>';
                } else if ($ans_type == 4) {
                    $get_option = $this->input->post('dropDownOptions');

                    $option = $this->get_options($get_option, $value1, $ans_type, $department_id, $section_id, $default_val);


                    $field = '<select class="' . $class_name . '" multiple id="' . $value1 . '" name="' . $value1 . '[]" ' . $validation . ' >
							' . $option . '
									</select> <script>$("#' . $value1 . '").select2()</script>
									';
                } else if ($ans_type == 7) {

                    $field = '<input type="file" name="' . $value1 . '[]" id="form_field' . $value1 . '" class="' . $class_name . '" ' . $validation . ' ' . $placeholder . ' style="width:100%!important;">';
                } else if ($ans_type == 8) {
                    $op_data = $this->getQueryDropdownAjax2($section_id, $value1, $ans_type);
                    $option = "<option value='-1'>select option</option>";
                    if ($op_data == "") {
                        $field = '<select class="custom-select ' . $class_name . '"  id="' . $value1 . '" name="' . $value1 . '" ' . $validation . '>
										  ' . $option . '
										</select>
										<script>$("#' . $value1 . '").select2()</script>
										';
                    } else {
                        $field = '<select class="custom-select ' . $class_name . '"  id="' . $value1 . '" name="' . $value1 . '" ' . $validation . '>
										  ' . $option . '
										</select>
										' . $op_data . '';
                    }
                } else if ($ans_type == 25) {
                    $op_data = $this->getQueryDropdownAjax2($section_id, $value1, $ans_type);
                    $option = "<option value='-1'>select option</option>";
                    if ($op_data == "") {
                        $field = '<select class="custom-select ' . $class_name . '"  id="' . $value1 . '" name="' . $value1 . '[]" multiple ' . $validation . '>
										  ' . $option . '
										</select>
										<script>$("#' . $value1 . '").select2()</script>
										';
                    } else {
                        $field = '<select class="custom-select ' . $class_name . '"  id="' . $value1 . '" name="' . $value1 . '[]" multiple ' . $validation . '>
										  ' . $option . '
										</select>
										' . $op_data . '';
                    }
                }


                $sectionFieldObject = new stdClass();
                $sectionFieldObject->mainInput = $mainInput;
                $sectionFieldObject->field = $field;
                $sectionFieldObject->ans_type = $ans_type;
                $sectionFieldObject->value1 = $value1;
                array_push($sectionFieldArray, $sectionFieldObject);
                // $sectionFieldArray[$mainInput]=$field;


            }

        }


        return $sectionFieldArray;

    }


    public function get_options($get_option, $value1, $ans_type, $department_id, $section_id, $default_val)
    {
        $option = '';
        $option = "<option value=''  selected disabled></option>";
        if ($get_option != null) {
            $get_option = json_decode($get_option);
            // print_r($get_option);exit();

            if (!empty($get_option)) {
                foreach ($get_option as $option_value) {
                    $op_valueExplode = explode('#', $option_value->id);
                    if (count($op_valueExplode) > 1) {
                        $opvalue1 = $op_valueExplode[1];

                        if ($opvalue1 == $value1) {
                            $updateData = $this->HtmlFormTemplateModel->addDropOptions($department_id, $section_id, $ans_type, $option_value->id, $option_value->options);
                            if ($updateData->status == true) {
                                $data = explode(',', $option_value->options);
                                if (!empty($data)) {
                                    foreach ($data as $opvalue) {

                                        $selected = "";
                                        if ($ans_type == 3) {
                                            if ($default_val == $opvalue) {
                                                $selected = "selected";
                                            }
                                            $option .= '<option value="' . $opvalue . '" ' . $selected . '>' . $opvalue . '</option>';
                                        } else if ($ans_type == 4) {
                                            $multiselected = explode(',', $default_val);
                                            if (!empty($multiselected)) {
                                                foreach ($multiselected as $multivalue) {
                                                    if ($multivalue == $opvalue) {
                                                        $selected = "selected";
                                                        break;
                                                    }
                                                }
                                            }
                                            $option .= '<option value="' . $opvalue . '" ' . $selected . '>' . $opvalue . '</option>';
                                        } else if ($ans_type == 12) {
                                            $multiselected = explode(',', $default_val);
                                            if (!empty($multiselected)) {
                                                foreach ($multiselected as $multivalue) {
                                                    if ($multivalue == $opvalue) {
                                                        $selected = "checked";
                                                        break;
                                                    }
                                                }
                                            }
                                            $option .= ' <input type="checkbox" ' . $selected . ' value="' . $opvalue . '" name="' . $value1 . '[]" id="' . $value1 . '"> ' . $opvalue;
                                        } else if ($ans_type == 13) {
                                            if ($default_val == $opvalue) {
                                                $selected = "checked";
                                            }
                                            $option .= ' <input type="radio" ' . $selected . ' value="' . $opvalue . '" name="' . $value1 . '" id="' . $value1 . '"> ' . $opvalue;
                                        }

                                    }
                                }
                            }

                        }
                    }

                }
            }
        }
        return $option;
    }

    public function getAllTablenames1()
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

    public function getAllTablenames()
    {
        $tables = $this->db->list_tables();
        $option = "<option value=''>Select Table</option>";
        foreach ($tables as $table) {
            $option .= "<option value='" . $table . "'>" . $table . "</option>";
        }
        return $option;
        // $response['option']=$option;
        // $response['data']=$tables;
        // echo json_encode($response);

    }

    public function getAllHashOptions($hasharray)
    {
        // $tables = $this->db->list_tables();
        $hasharray = explode(',', $hasharray);
        $option = "<option value=''>Select Hash Id</option>";
        if (count($hasharray) > 0) {
            foreach ($hasharray as $table) {
                $option .= "<option value='" . $table . "'>" . $table . "</option>";
            }
        }
        return $option;
        // $response['option']=$option;
        // $response['data']=$tables;
        // echo json_encode($response);

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

    public function GetAllTableColumns1($TableName)
    {

        $fields = $this->db->field_data($TableName);

        $data = "";
        $option = "";
        foreach ($fields as $field) {
            $column_name = $field->name;
            $option .= "<option value='" . $column_name . "'>" . $column_name . "</option>";
        }
        return $option;
    }

    public function addQueryDropdownOptions($querydropDownOptions, $section_id, $department_id)
    {

        $insert_batch = array();
        $querydropDownOptions = json_decode($querydropDownOptions);
        foreach ($querydropDownOptions as $key => $value) {
            $resultArray = array();
            $array_string = '';
            $table_name = '';
            $wherearray_string = '';
            $wherearray_dep = '';
            $anyonedep_string = '';

            if (isset($value->table_name)) {
                $array_string = $value->select_key . ',' . $value->select_value;
                $table_name = $value->table_name;

                if (!empty($value->where_op)) {
                    $wherearr = array();
                    if (!empty($value->where_op)) {
                        foreach ($value->where_op as $key1 => $where_value) {
                            // print_r($where_value);exit();
                            $where_col = $where_value->where_col;
                            $where_value1 = '';

                            $where_value1 = $where_value->where_text;
                            $whereString = $where_col . ":" . $where_value1;
                            array_push($wherearr, $whereString);

                        }
                    }
                }
                $anyonedeparr = array();
                if (!empty($value->anydep_op)) {
                    foreach ($value->anydep_op as $key1 => $anyone_value) {
                        $anydep_col = $anyone_value->anydep_col;
                        $anydep_text = $anyone_value->anydep_text;
                        $anyoneString = $anydep_col . "@" . $anydep_text;
                        array_push($anyonedeparr, $anyoneString);
                    }
                }
                if ($value->dep_value != '' or $value->dep_value != null) {

                    $wherearray_dep = $value->dep_column . ":" . $value->dep_value;

                }
                if (!empty($wherearr)) {
                    $wherearray_string = implode('|', $wherearr);
                }
                if (!empty($anyonedeparr)) {
                    $anyonedep_string = implode('|', $anyonedeparr);
                }


            }
            // print_r($wherearray_string);exit();
            $resultArray = array(
                'section_id' => $section_id,
                'department_id' => $department_id,
                'table_name' => $table_name,
                'array_string' => $array_string,
                'wherearray_string' => $wherearray_string,
                'is_dropdownDependancy' => $wherearray_dep,
                'raw_query' => $value->raw_query,
                'field_id' => $value->id,
                'field_type' => $value->type,
                'is_anyone_depend' => $anyonedep_string);
            $insert_batch[$key] = $resultArray;

        }
        return $insert_batch;

    }

    public
    function get_dropdownquery($value1, $section_id, $ans_type)
    {
        $resultObject = new stdClass();
        $query_data = $this->HtmlFormTemplateModel->get_dropdownquery($value1, $section_id, $ans_type);
        if ($query_data != false) {
            if ($query_data->table_name != "" && $query_data->table_name != null) {
                $where = array();
                if ($query_data->wherearray_string != "" && $query_data->wherearray_string != null) {
                    $where_str = explode('|', $query_data->wherearray_string);
                    if (!empty($where_str)) {
                        foreach ($where_str as $key => $value) {
                            $where_key = explode(':', $value);
                            if (count($where_key) > 1) {
                                // $where_key[1]// remove hash c
                                // if(isset(${$where_key[1]}))
                                $where[$where_key[0]] = $where_key[1];
                            }
                        }
                    }
                }
                $dep_where = array();
                $dep_count = 0;
                $dep_ids = '';
                if ($query_data->is_dropdownDependancy != "" && $query_data->is_dropdownDependancy != null) {

                    $where_str = explode(':', $query_data->is_dropdownDependancy);
                    if (count($where_str) > 0) {
                        // array_push($dep_ids, $where_key[1]);
                        $dep_ids = $where_str[1];
                        $where[$where_str[0]] = '$type';
                        $dep_count++;

                    }
                }

                $select = array();
                if ($query_data->array_string != "" && $query_data->array_string != null) {
                    $select_key = explode(',', $query_data->array_string);
                    foreach ($select_key as $key => $value) {
                        array_push($select, $value);
                    }
                }
                $resultObject->is_dependancy = 0;

                if ($dep_count > 0) {
                    $resultObject->is_dependancy = 1;
                    $resultObject->is_dependancy_ids = $dep_ids;
                    $query_string = $this->HtmlFormTemplateModel->_select($query_data->table_name, $where, $select);
                    $query_string = $query_string->last_query;
                } else {
                    $query_string = $this->HtmlFormTemplateModel->_select($query_data->table_name, $where, $select);
                    $query_string = $query_string->last_query;
                }


                $resultObject->status = true;
                $resultObject->query = $query_string;
                // print_r($query_string->last_query);exit();
            } else if ($query_data->raw_query != "" && $query_data->raw_query != null) {
                $query_string = $query_data->raw_query;
                $resultObject->status = true;
                $resultObject->query = $query_string;
                $resultObject->is_dependancy = 0;
            } else {
                $resultObject->status = false;
                $resultObject->query = '';
                $resultObject->is_dependancy = 0;
            }
        } else {
            $resultObject->status = false;
            $resultObject->query = '';
            $resultObject->is_dependancy = 0;
        }
        return $resultObject;
    }

    public
    function get_direct_dropdownquery($value1, $section_id, $ans_type, $queryparameter_hidden, $type, $search)
    {
        $resultObject = new stdClass();
        $query_data = $this->HtmlFormTemplateModel->get_dropdownquery($value1, $section_id, $ans_type);
        if ($query_data != false) {
            if ($query_data->table_name != "" && $query_data->table_name != null) {
                $where = array();
                if ($query_data->wherearray_string != "" && $query_data->wherearray_string != null) {
                    $where_str = explode('|', $query_data->wherearray_string);
                    if (!empty($where_str)) {
                        if (!is_null($queryparameter_hidden)) {
                            $queryparameterstring = base64_decode($queryparameter_hidden);
                            $queryStringArray = json_decode($queryparameterstring);

                            extract((array)$queryStringArray);
                        }
                        foreach ($where_str as $key => $value) {
                            $where_key = explode(':', $value);
                            if (count($where_key) > 1) {
                                $where_v = str_replace("#", "", $where_key[1]);
                                // $where_key[1]// remove hash c
                                if (isset(${$where_v})) {
                                    $where[$where_key[0]] = ${$where_v};
                                } else {
                                    $where[$where_key[0]] = $where_key[1];
                                }

                            }
                        }
                    }
                }
                $dep_where = array();
                $dep_count = 0;
                $dep_ids = '';
                if ($query_data->is_dropdownDependancy != "" && $query_data->is_dropdownDependancy != null) {

                    $where_str = explode(':', $query_data->is_dropdownDependancy);
                    if (count($where_str) > 0) {
                        // array_push($dep_ids, $where_key[1]);
                        $dep_ids = $where_str[1];
                        if ($type != null) {
                            $where[$where_str[0]] = $type;
                        } else {
                            $where[$where_str[0]] = '$type';
                        }


                        $dep_count++;

                    }
                }

                $select = array();
                $search_a = array();
                if ($query_data->array_string != "" && $query_data->array_string != null) {
                    $select_key = explode(',', $query_data->array_string);
                    $k = 1;
                    foreach ($select_key as $key => $value) {
                        array_push($select, $value);
                        if ($k == 2) {
                            $search_a[$value] = $search;
                        }
                        $k++;
                    }
                }

                $resultObject->is_dependancy = 0;

                if ($dep_count > 0) {
                    $resultObject->is_dependancy = 1;
                    $resultObject->is_dependancy_ids = $dep_ids;
                    $query_string = $this->HtmlFormTemplateModel->_select($query_data->table_name, $where, $select, null, null, $search_a);
                    $query_string = $query_string->last_query;
                } else {
                    $query_string = $this->HtmlFormTemplateModel->_select($query_data->table_name, $where, $select, null, null, $search_a);
                    $query_string = $query_string->last_query;
                }


                $resultObject->status = true;
                $resultObject->query = $query_string;
                $resultObject->select = $select;
                // print_r($query_string->last_query);exit();
            } else if ($query_data->raw_query != "" && $query_data->raw_query != null) {
                $query_string = $query_data->raw_query;
                $resultObject->status = true;
                $resultObject->query = $query_string;
                $resultObject->is_dependancy = 0;
            } else {
                $resultObject->status = false;
                $resultObject->query = '';
                $resultObject->is_dependancy = 0;
            }
        } else {
            $resultObject->status = false;
            $resultObject->query = '';
            $resultObject->is_dependancy = 0;
        }
        return $resultObject;
    }

    function InsertFordataUsingButton()
    {

        extract($_POST);
        $query = $this->db->query("select * from htmlquerytable where field_id='," . $btn_id . "'");

        if ($this->db->affected_rows() > 0) {
            $result = $query->result();

            $cnt = 1;
            foreach ($result as $row) {

                $array_string = $row->array_string;
                $table_name = $row->table_name;
                $exp = explode('|', $array_string);
                $array = array();
                for ($i = 0; $i < count($exp); $i++) {
                    $str2 = $exp[$i];
                    $exp2 = explode(':', $str2);
                    $index = $exp2[0];
                    $value = str_replace("#", "", $exp2[1]);

                    $value = ${$value};
                    $array[$index] = $value;

                }
                $insert = $this->db->insert($table_name, $array);
                if ($insert == true) {
                    $cnt++;
                }
            }

            if ($cnt > 1) {
                $response['status'] = 200;
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

    public
    function get_data()
    {
        $search = $this->input->post('searchTerm');
        $query1 = $this->input->post_get('query');
        $queryparameter_hidden = $this->input->post_get('data');
        if (!is_null($queryparameter_hidden)) {
            $queryparameterstring = base64_decode($queryparameter_hidden);
            $queryStringArray = json_decode($queryparameterstring);

            extract((array)$queryStringArray);

        }
        $q = base64_decode($query1);
        // print_r($q);exit();
        $q = str_replace("#company_id", ${$b}, $q);
        $data = array();
        if ($search == "") {

        } else {
            $q = str_replace("#like", $search, $q);

            $query = $this->db->query($q . " limit 20");
            // $this->db->where(array($b[0]=>${$b[1]}))
            // $query=$this->db->query("SELECT `id`, `name` FROM `branch_master` WHERE `company_id` = '$company_id' limit 20");
            // print_r($q);exit();
            $data["last_query"] = $this->db->last_query();
            if ($this->db->affected_rows() > 0) {
                $res = $query->result();

                foreach ($res as $val) {

                    $arr = array();
                    foreach ($val as $k => $v) {

                        $arr[] = $v;
                    }
                    $val = $arr[0];
                    $data[] = array("id" => $val, "text" => $arr[1]);
//					$data[] = array("id" => $arr[0], "text" => $arr[1]);

                }
            }

        }
        echo json_encode($data);
    }

    public
    function get_dep_data()
    {
        $search = $this->input->post('searchTerm');
        $type = $this->input->post('type');
        $query1 = $this->input->post_get('query');
        $queryparameter_hidden = $this->input->post_get('data1');

        if (!is_null($queryparameter_hidden)) {
            $queryparameterstring = base64_decode($queryparameter_hidden);
            $queryStringArray = json_decode($queryparameterstring);

            extract((array)$queryStringArray);
        }
        // print_r($queryStringArray);exit();
        $data = array();
        if ($search == "") {
            $q = base64_decode($query1);

            $para_query = explode('|', $q);

            if (count($para_query) > 2) {
                $section_id = $para_query[0];
                $field_id = $para_query[1];
                $field_type = $para_query[2];

                $result_data = $this->get_direct_dropdownquery($field_id, $section_id, $field_type, $queryparameter_hidden, $type, $search);
                $qq = $result_data->query;

                $q = str_replace("#like", $search, $qq);

                $query = $this->db->query($qq . " limit 20");
                // $data["last_query"]=$this->db->last_query();
                // print_r($this->db->last_query());exit();
                if ($this->db->affected_rows() > 0) {
                    $res = $query->result();
                    foreach ($res as $val) {

                        $arr = array();
                        foreach ($val as $k => $v) {

                            $arr[] = $v;
                        }
                        $val = $arr[0];
                        if ($val != null && $arr[1] != null) {
                            $data[] = array("id" => $val, "text" => $arr[1]);
                        }

//					$data[] = array("id" => $arr[0], "text" => $arr[1]);

                    }
                }
            }
        } else {
            $q = base64_decode($query1);

            $para_query = explode('|', $q);

            if (count($para_query) > 2) {
                $section_id = $para_query[0];
                $field_id = $para_query[1];
                $field_type = $para_query[2];

                $result_data = $this->get_direct_dropdownquery($field_id, $section_id, $field_type, $queryparameter_hidden, $type, $search);
                $col = $result_data->select;
                if ($col[1] != "") {
                    $qq = $result_data->query . " AND " . $col[1] . " like '%#like%'";
                } else {
                    $qq = $result_data->query;
                }

                $q = str_replace("#like", $search, $qq);

                $query = $this->db->query($q . " limit 20");
                // $data["last_query"]=$this->db->last_query();
                // print_r($this->db->last_query());exit();
                if ($this->db->affected_rows() > 0) {
                    $res = $query->result();
                    foreach ($res as $val) {

                        $arr = array();
                        foreach ($val as $k => $v) {

                            $arr[] = $v;
                        }
                        if (count($arr) >= 2) {
                            $val = $arr[0];
                            if ($val != null && $arr[1] != null) {
                                $data[] = array("id" => $val, "text" => $arr[1]);
                            }
                        } else {
                            $val = $arr[0];
                            if ($val != null) {
                                $data[] = array("id" => $val, "text" => $val);
                            }
                        }


//					$data[] = array("id" => $arr[0], "text" => $arr[1]);

                    }
                }
            }

        }
        echo json_encode($data);
    }

    function getQueryStringPara()
    {
        $query = $this->db->query("select * from html_querystring_parameter_table");
        $data = '';
        if ($this->db->affected_rows() > 0) {
            $result = $query->result();
            $data = "<option value=''>Select Parameters</option>";
            foreach ($result as $row) {
                $data .= "<option value='" . $row->parameter_name . ":" . $row->paramete_type . "'>" . $row->parameter_name . "</option>";
            }

        }
        return $data;
    }

    public
    function getQueryDropdownData()
    {
        // print_r($this->input->post());exit();

        $validationObject = $this->is_parameter(array("department_id", "section_id", "field_id", "field_type"));
        if ($validationObject->status) {

            $params = $validationObject->param;
            $section_id = $params->section_id;
            $department_id = $params->department_id;
            $field_id = explode('#', $params->field_id);
            $value1 = $field_id[1];
            $ans_type = $params->field_type;
            $hasharray = $this->input->post('textarray');
            $table_options = $this->getAllTablenames();
            $hash_options = $this->getAllHashOptions($hasharray);


            $query_data = $this->HtmlFormTemplateModel->get_dropdownquery($value1, $section_id, $ans_type);

            $where = array();
            $anyonedependant = array();
            if ($query_data != false && $query_data != "") {

                $table_name = $query_data->table_name;
                $raw_query = $query_data->raw_query;
                $select_value = '';
                $select_name = '';
                $dep_column = '';
                $dep_value = '';
                if ($query_data->array_string != "" or $query_data->array_string != null) {
                    $select_key = explode(',', $query_data->array_string);
                    if (count($select_key) > 0) {
                        $select_value = $select_key[0];
                        $select_name = $select_key[1];
                    }
                }
                if ($query_data->wherearray_string != "" && $query_data->wherearray_string != null) {
                    $where_str = explode('|', $query_data->wherearray_string);
                    if (!empty($where_str)) {
                        foreach ($where_str as $key => $value) {
                            $resultObject = new stdClass();
                            $where_key = explode(':', $value);
                            // print_r($where_key);
                            if (count($where_key) > 1) {
                                $resultObject->column = $where_key[0];
                                $resultObject->para_value = "";
                                $resultObject->text_value = "";
                                if (substr($where_key[1], 0, strlen('#')) == '#') {
                                    $resultObject->para_value = str_replace("#", "", $where_key[1]) . ':' . $where_key[2];
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
                if ($query_data->is_anyone_depend != "" && $query_data->is_anyone_depend != null) {
                    $anydep_str = explode('|', $query_data->is_anyone_depend);
                    if (!empty($anydep_str)) {
                        foreach ($anydep_str as $key => $value) {
                            $resultObject = new stdClass();
                            $anydep_key = explode('@', $value);
                            if (count($anydep_key) > 1) {
                                $resultObject->column = $anydep_key[0];
                                $resultObject->text_value = $anydep_key[1];
                            }
                            array_push($anyonedependant, $resultObject);
                        }
                    }
                }
                if ($query_data->is_dropdownDependancy != "" && $query_data->is_dropdownDependancy != null) {
                    $where_dep_str = explode(':', $query_data->is_dropdownDependancy);
                    if (count($where_dep_str) > 0) {
                        $dep_column = $where_dep_str[0];
                        $dep_value = $where_dep_str[1];
                    }
                }

            } else {
                $table_name = "";
                $raw_query = "";
                $select_value = "";
                $select_name = "";
                $dep_column = "";
                $dep_value = "";
            }
            // print_r($dep_value);exit();
            $table_columns = '';
            $query_paracolumns = '';
            if ($table_name != "") {
                $table_columns = $this->GetAllTableColumns1($table_name);
                $query_paracolumns = $this->getQueryStringPara();
            }


            $design_data = '';
            $design_data .= '<div class="col-md-12">
			<div class="mt-2"> <strong>' . $params->field_id . ' options</strong> 
			<button type="button" class="btn btn-link ml-2" onclick="getHideDiv(\'form_through_' . $value1 . '\',\'mormal_query_' . $value1 . '\')">form through</button>
			<button type="button" class="btn btn-link ml-2" onclick="getHideDiv(\'mormal_query_' . $value1 . '\',\'form_through_' . $value1 . '\')">normal query</button>
			</div>
			<div class="mt-2" id="mormal_query_' . $value1 . '" style="display:none">
				<label>Query : </label><textarea class="form-control" name="textquery_' . $value1 . '" id="textquery_' . $value1 . '">' . $raw_query . '</textarea>
			</div>
			<div class="" id="form_through_' . $value1 . '">
			<div class="mt-2 row">
			<div class="col-md-6">
				Select Table : <select name="select_' . $value1 . '" id="select_' . $value1 . '" class="selectTable" onchange="getListOfColumns1(this.value)">
				' . $table_options . '</select>
				<script>
				';
            if ($table_name != "" && $table_name != null) {
                $design_data .= '$("#select_' . $value1 . ' option[value=' . $table_name . ']").prop("selected", true);';
            }

            $design_data .= '</script>
			</div>
			</div>
			<div id="dropdownQueryyData_' . $value1 . '">
			</div>
			<div class="mt-2 row">
				<div class="col-md-6">
				Select Option Value: <select name="key_' . $value1 . '" id="key_' . $value1 . '" class="tableColumns">
				' . $table_columns . '
				</select>
				</div>
				<div class="col-md-6">
				Select Option Name: <select name="name_' . $value1 . '" id="name_' . $value1 . '" class="tableColumns">
				' . $table_columns . '
				</select>
				</div>
				<script>';
            if ($select_value != "" && $select_value != null) {
                $design_data .= '$("#key_' . $value1 . ' option[value=' . $select_value . ']").prop("selected", true);$("#name_' . $value1 . ' option[value=' . $select_name . ']").prop("selected", true);
						';
            }
            $st = 'd-none';
            if ($dep_value != "" && $dep_value != null) {
                $st = "";
            }
            $design_data .= '</script>
			</div>
			<hr/>
			<button class="btn btn-link btn-sm" type="button" onclick="get_dependant_value(\'' . $value1 . '\')">is dependant?</button>
			<div class="mt-2 row ' . $st . '" id="txt_dependant_' . $value1 . '">
				<div class="col-md-6 mt-2">
					
						Where Dependant Value Check : <select name="dep_column_' . $value1 . '" id="dep_column_' . $value1 . '" class="tableColumns">
						' . $table_columns . '
						</select>
				</div>
				<div class="col-md-6 mt-2">
						Dependant On: <select name="dep_value_' . $value1 . '" id="dep_value_' . $value1 . '" class="">
						' . $hash_options . '</select> 
						
					</div>
					<script>
					
					';
            if ($dep_column != "" && $dep_column != null) {

                $design_data .= '
						$("#dep_column_' . $value1 . ' option[value=' . $dep_column . ']").prop("selected", true);';
            }
            if ($dep_value != "" && $dep_value != null) {
                $design_data .= '
						$("#dep_value_' . $value1 . ' option[value=\'' . $dep_value . '\']").prop("selected", true);';
            }

            $design_data .= '
					
					</script>
				
			</div>
			<hr/>
			<div class="mt-2 row">
			<div class="col-md-12">
			<button type="button" name="add_where" id="add_where" onclick="getAnyoneDependantColumns(\'' . $value1 . '\',\'' . $hasharray . '\',\'' . $table_name . '\')" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Is anyone dependant?</button>
			</div></div>';
            $ad_count = 0;
            if (count($anyonedependant) > 0) {
                $ad_k = 1;
                foreach ($anyonedependant as $key => $a_value) {
                    $design_data .= '
					<div class="mt-2 row">
						<div class="col-md-4 mt-2">
							Dependant: <select name="anyone_dep_column' . $ad_k . '_' . $value1 . '" id="anyone_dep_column' . $ad_k . '_' . $value1 . '" class="">
							' . $hash_options . '</select> 
							
						</div>
						<div class="col-md-8 mt-2">
							Dependant Query: <textarea name="anyone_dep_query' . $ad_k . '_' . $value1 . '" id="anyone_dep_query' . $ad_k . '_' . $value1 . '" class="form-control" row="4" placeholder="Enter dependant query here">' . $a_value->text_value . '</textarea>
						</div>
						<script>
						$("#anyone_dep_column' . $ad_k . '_' . $value1 . ' option[value=\'' . $a_value->column . '\']").prop("selected", true);
							$("#anyone_dep_column' . $ad_k . '_' . $value1 . '").select2();
						</script>
					</div>';
                    $ad_k++;
                    $ad_count++;
                }
            }
            $design_data .= '<input type="hidden" value="' . $ad_count . '" name="anydepcount_' . $value1 . '" id="anydepcount_' . $value1 . '" class="form-control-field">';
            $design_data .= '
			<div id="txt_anyone_dependant_' . $value1 . '">
			</div>
			<hr/>
			<div class="mt-2 row">
			<div class="col-md-12">
			<button type="button" name="add_where" id="add_where" onclick="getWhereColumns(\'' . $value1 . '\',\'' . $hasharray . '\',\'' . $table_name . '\')" class="btn btn-outline-primary">
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
			
			<script>$("#select_' . $value1 . '").select2();
			$("#key_' . $value1 . '").select2();
			$("#name_' . $value1 . '").select2();
			$("#dep_column_' . $value1 . '").select2();
			$("#dep_value_' . $value1 . '").select2();
			</script>
			<div class="mt-4 row">
			<div class="col-md-12">
				<button type="button" class="btn btn-primary" onclick="queryDropdownSave(' . $section_id . ',' . $department_id . ',\'' . $params->field_id . '\',' . $params->field_type . ')" style="float:right;">Save</button>
			</div>
			</div>';
            // print_r($design_data);exit();
            $response['status'] = 200;
            $response['data'] = $design_data;

        } else {
            $response['status'] = 201;
            $response['data'] = '';
            $response['body'] = 'Required parameter Missing';

        }

        echo json_encode($response);
    }

    public
    function save_dropdown_sectionsHtml()
    {
        // print_r($this->input->post());exit();
        $validationObject = $this->is_parameter(array("department_id", "section_id", "field_id", "field_type", "querydropDownOptions"));
        if ($validationObject->status) {

            $params = $validationObject->param;
            $section_id = $params->section_id;
            $department_id = $params->department_id;
            $field_id = $params->field_id;
            $field_type = $params->field_type;
            $querydropDownOptions = $params->querydropDownOptions;
            $query_drop = $this->addQueryDropdownOptions($querydropDownOptions, $section_id, $department_id);
            $resultObject = $this->HtmlFormTemplateModel->addQueryDropdown($query_drop, $section_id, $department_id, $field_id, $field_type);
            if ($resultObject->status == true) {
                $data = $this->getQueryDropdownDefaultData($field_id, $section_id, $field_type);
                $response['status'] = 200;
                $response['body'] = "Added Successfully";
                $response['data'] = $data;
            } else {
                $response['status'] = 201;
                $response['data'] = "not added";
            }
        } else {
            $response['status'] = 201;
            $response['bosy'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public
    function save_html_querydrop_default()
    {
        $validationObject = $this->is_parameter(array("department_id", "section_id", "field_id", "field_type", "default_val"));
        if ($validationObject->status) {
            $params = $validationObject->param;
            $section_id = $params->section_id;
            $department_id = $params->department_id;
            $field_id = "#" . $params->field_id;
            $field_type = $params->field_type;
            $default_val = $params->default_val;
            $update_data = array("department_id" => $department_id,
                "section_id" => $section_id,
                "field_id" => $field_id,
                "field_type" => $field_type,
                "default_val" => $default_val);
            $where = array("department_id" => $department_id,
                "section_id" => $section_id,
                "field_id" => $field_id,
                "field_type" => $field_type);
            $resultObject = $this->HtmlFormTemplateModel->addQueryDropdownDefault($update_data, $where);
            if ($resultObject->status == true) {
                $response['status'] = 200;
                $response['body'] = "Added Successfully";
            } else {
                $response['status'] = 201;
                $response['data'] = "not added";
            }
        } else {
            $response['status'] = 201;
            $response['bosy'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public
    function getQueryDropdownDefaultData($field_id, $section_id, $field_type)
    {
        $option = "";
        $query = $this->get_dropdownquery($field_id, $section_id, $field_type);
        $quer_default = $this->HtmlFormTemplateModel->getHtmlRequiredFieldData($field_id, $field_type, $section_id);
        $selected_value = "";
        if ($quer_default != false) {
            $selected_value = $quer_default->default_val;
        }
        // print_r($quer_default);exit();
        if ($query->status == true) {
            $result = $this->db->query($query->query);
            if ($this->db->affected_rows() > 0) {
                $resultArray = $result->result();
                $option .= "<option value=''>select value</option>";
                foreach ($resultArray as $key => $value) {
                    $array_value = (array)$value;
                    $k = 1;
                    $key_val = "";
                    $name_val = "";
                    foreach ($array_value as $a_key => $a_value) {
                        if ($k == 1) {
                            $key_val = $a_key;
                        } else {
                            $name_val = $a_key;
                        }
                        $k++;
                    }

                    if ($key_val != "" && $name_val != "") {
                        $selected_o = "";
                        if ($selected_value == $value->$key_val) {
                            $selected_o = "selected";
                        }
                        $option .= "<option value='" . $value->$key_val . "' " . $selected_o . ">" . $value->$name_val . "</option>";
                    }
                }

            }
        }
        $data = '<div class="col-md-12 row"><div class="col-md-3">#' . $field_id . ' <label><b>Default value :</b> </label></div>
			<div class="col-md-4">
			<select class="form-control" name="default_query_' . $field_id . '" id="default_query_' . $field_id . '">
			' . $option . '</select></div>
			<div class="col-md-5">
			<button type="button" class="btn btn-primary" onclick="save_html_querydrop_default(\'' . $field_id . '\',' . $field_type . ',' . $section_id . ')">save default value</button></div></div>
			<script>$("#default_query_' . $field_id . '").select2();</script>';
        return $data;

    }

    public
    function getQueryDropdownAjax()
    {
        $validationObject = $this->is_parameter(array("department_id", "section_id", "field_id", "field_type"));
        if ($validationObject->status) {
            $params = $validationObject->param;
            $section_id = $params->section_id;
            $department_id = $params->department_id;
            $field_id = explode('#', $params->field_id);
            $field_id = $field_id[1];
            $field_type = $params->field_type;
            $query = $this->get_dropdownquery($field_id, $section_id, $field_type);
            // print_r($query);exit();
            $url = '';
            $field = '';
            if ($query->is_dependancy == 1) {
                if ($query->status == true) {
                    $qren = base64_encode($query->query);
                    $url = base_url() . "HtmlFormTemplateController/get_dep_data?query=$qren";

                    $field = '
				
			  			<script>
			  			
			  			$("#' . $field_id . '").select2(
							{

						  ajax: { 
						   url: "' . $url . '",
						   type: "post",
						   dataType: "json",
						   delay: 250,
						   data: function (params) {
							return {
								type:$("' . $query->is_dependancy_ids . '").val(),
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
						  minimumInputLength: 1
						 }
							);</script>
						';
                }
            } else {
                if ($query->status == true) {

                    $qren = base64_encode($query->query);
                    $url = base_url() . "HtmlFormTemplateController/get_data?query=$qren";


                    $field = '
			
		  			<script>$("#' . $field_id . '").select2(
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
					  minimumInputLength: 1
					 }
						);</script>
					';
                }
            }


            $response['status'] = 200;
            $response['data'] = $field;
            $response['body'] = "add Successfully";
        } else {
            $response['status'] = 201;
            $response['body'] = "Required Parameter Missing";
        }
        echo json_encode($response);
    }

    public
    function getQueryDropdownAjax1($section_id, $field_id, $field_type)
    {
        $query = $this->get_dropdownquery($field_id, $section_id, $field_type);
        // print_r($query);exit();
        $url = '';
        $field = '';
        if ($query->is_dependancy == 1) {
            if ($query->status == true) {
                $qren = base64_encode($query->query);
                $url = base_url() . "HtmlFormTemplateController/get_dep_data?query=$qren";

                $field = '
				
			  			<script>
			  			
			  			$("#' . $field_id . '").select2(
							{

						  ajax: { 
						   url: "' . $url . '",
						   type: "post",
						   dataType: "json",
						   delay: 250,
						   data: function (params) {
							return {
								type:$("' . $query->is_dependancy_ids . '").val(),
								data:$("#queryparameter_hidden").val(),
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
						  minimumInputLength: 1
						 }
							);</script>
						';
            }
        } else {
            if ($query->status == true) {

                $qren = base64_encode($query->query);
                $url = base_url() . "HtmlFormTemplateController/get_data?query=$qren";


                $field = '
			
		  			<script>$("#' . $field_id . '").select2(
						{
					  ajax: { 
					   url: "' . $url . '",
					   type: "post",
					   dataType: "json",
					   delay: 250,
					   data: function (params) {
						return {
						  data:$("#queryparameter_hidden").val(),
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
					  minimumInputLength: 1
					 }
						);</script>
					';
            }
        }


        return $field;

    }

    public
    function getQueryDropdownAjax2($section_id, $field_id, $field_type)
    {
        $query = $this->get_dropdownquery($field_id, $section_id, $field_type);
        // print_r($query);exit();
        $query1 = $section_id . '|' . $field_id . '|' . $field_type;
        $url = '';
        $field = '';
        if ($query->is_dependancy == 1) {
            if ($query->status == true) {
                $qren = base64_encode($query1);
                $url = base_url() . "HtmlFormTemplateController/get_dep_data?query=$qren";

                $field = '
				
			  			<script>
			  			
			  			$("#' . $field_id . '").select2(
							{

						  ajax: { 
						   url: "' . $url . '",
						   type: "post",
						   dataType: "json",
						   delay: 250,
						   data: function (params) {
							return {
								type:$("' . $query->is_dependancy_ids . '").val(),
								data1:$("#queryparameter_hidden").val(),
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
						  minimumInputLength: 1
						 }
							);</script>
						';
            }
        } else {
            if ($query->status == true) {

                $qren = base64_encode($query1);
                $url = base_url() . "HtmlFormTemplateController/get_dep_data?query=$qren";


                $field = '
			
		  			<script>$("#' . $field_id . '").select2(
						{
					  ajax: { 
					   url: "' . $url . '",
					   type: "post",
					   dataType: "json",
					   delay: 250,
					   data: function (params) {
						return {
						  data1:$("#queryparameter_hidden").val(),
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
					  minimumInputLength: 1
					 }
						);</script>
					';
            }
        }


        return $field;

    }

    public
    function getExcelPdfData()
    {
        $id = $this->input->post('id');
        $section_id = $this->input->post('section_id');
        $department_id = $this->input->post('department_id');
        $field_type = $this->input->post('field_type');

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

            $query = $this->db->query("select * from html_query_reports_table where field_id='" . $id . "' and field_type=" . $field_type . " and section_id=" . $section_id);
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
        $data_btn = '';
        if ($field_type == 20) {

            $data_btn .= '
			<div class="row">
			<div class="col-md-3"><lable>Select Type</lable>
			<select class="form-control" id="btn_type" name="btn_type">
			<option value="1">Redirection Button</option>
			</select>
			</div>
			<div class="col-md-3"><lable>Column Name</lable>
			<input type="text" class="form-control" id="btn_column_name" name="btn_column_name">
			</div>
			<div class="col-md-3"><lable>Button Class</lable>
			<input type="text" class="form-control" id="btn_class_name" name="btn_class_name">
			</div>
			<div class="col-md-3"><lable>Route</lable>
			<input type="text" class="form-control" id="btn_route_name" name="btn_route_name">
			</div>
			</div>
			';
        } else {
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
        }
        $data = '';
        $data .= '
		<form name="query_form" id="query_form" method="post">
		<input type="hidden" id="query_id_edit" name="query_id_edit" value="' . $query_id . '">
		<input type="hidden" id="query_field_id_edit" name="query_field_id_edit" value="' . $id . '">
		<input type="hidden" id="query_field_type_edit" name="query_field_type_edit" value="' . $field_type . '">
		<input type="hidden" id="query_section_id_edit" name="query_section_id_edit" value="' . $section_id . '">
		<div class="form-group row">
		<div class="col-md-4">
			<lable>Report Name</lable>
			<input type="text" class="form-control" id="report_name" value="' . $reoprt_name . '"name="report_name">
		</div>
		<div class="col-md-4">
			<lable>Button Display Name</lable>
			<input type="text" class="form-control" id="sub_report_name" value="' . $sub_report_name . '" name="sub_report_name">
		</div>
		</div>
		<div class="form-group row">
		
		<div class="col-md-12">
			<lable>Query</lable>
			<textarea class="form-control" id="query_data"  name="query_data">' . $query_val . '</textarea>
		</div>
		</div>
		' . $param . '
		' . $data_btn . '
		<br><button type="button" onclick="saveFormData()" class="btn btn-primary">Save</button>
		</form>
		';
        $response['data'] = $data;
        echo json_encode($response);
    }

    function saveFormData()
    {
        $query_id_edit = $this->input->post('query_id_edit');
        $query_field_id_edit = $this->input->post('query_field_id_edit');
        $query_field_type_edit = $this->input->post('query_field_type_edit');
        $query_section_id_edit = $this->input->post('query_section_id_edit');
        $report_name = $this->input->post('report_name');
        $sub_report_name = $this->input->post('sub_report_name');
        $query_data = $this->input->post('query_data');
        $param = $this->input->post('param');
        $datatype = $this->input->post('datatype');
        $label = $this->input->post('label');
        $option = $this->input->post('option');
        $btn_type = $this->input->post('btn_type');
        $btn_column_name = $this->input->post('btn_column_name');
        $btn_class_name = $this->input->post('btn_class_name');
        $btn_route_name = $this->input->post('btn_route_name');
        $data_button_array = array(
            "column_name" => $btn_column_name,
            "type" => $btn_type,
            "button_class" => $btn_class_name,
            "route" => $btn_route_name,
            "hash_key" => $query_field_id_edit,
            "section_id" => $query_section_id_edit,
        );
        $data = array();
        $data['reoprt_name'] = $report_name;
        $data['sub_report_name'] = $sub_report_name;
        $data['query'] = $query_data;
        $data['field_id'] = $query_field_id_edit;
        $data['field_type'] = $query_field_type_edit;
        $data['section_id'] = $query_section_id_edit;

        $k = 1;
        $cnt = 1;
        if (is_array($param) > 0) {
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
        }
        if ($query_id_edit == 0) {
            $insert = $this->db->insert("html_query_reports_table", $data);
            $insert1 = $this->db->insert("html_acttion_button_table", $data_button_array);
        } else {
            $this->db->where(array("id" => $query_id_edit));
            $insert = $this->db->update("html_query_reports_table", $data);
            $insert1 = $this->db->insert("html_acttion_button_table", $data_button_array);
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

    public
    function getReportFormData()
    {
        $id = $this->input->post('id');
        $section_id = $this->input->post('section_id');
        $field_type = $this->input->post('type');
        $hash_id = $this->input->post('hash_id');
        $query = $this->db->query("select * from html_query_reports_table where field_id='" . $hash_id . "' and field_type=" . $field_type . " and section_id=" . $section_id);
        $data = "";
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            //var_dump($result);
            $reoprt_name = $result->reoprt_name;
            $data .= '<form id="download_form_' . $id . '">
			<input type="hidden" id="query_id" name="query_id" value="' . $result->id . '">
			<input type="hidden" id="query_field_id" name="query_field_id" value="' . $id . '">
			<input type="hidden" id="query_field_type" name="query_field_type" value="' . $field_type . '">
			<input type="hidden" id="query_section_id" name="query_section_id" value="' . $section_id . '"><br>';
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
							<input class="form-control" type="' . $type . '" id="input_val' . $i . '" name="input_val' . $i . '" placeholder="Enter value">
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
            $data .= ' <br><div style="float:right">';

            if ($field_type == 15) {
                $data .= '<button type="button" class="btn btn-primary" onclick="DownloadData(\'' . $id . '\')"> <i class="fa fa-download"></i> Download ' . $result->sub_report_name . '</button>';
            } else if ($field_type == 16) {
                $data .= '<button type="button" class="btn btn-primary" onclick="DownloadPDFData(\'' . $id . '\')"> <i class="fa fa-download"></i> Download ' . $result->sub_report_name . '</button>';
            } else if ($field_type == 19) {
                $data .= '<button type="button" class="btn btn-primary" onclick="DownloadCSVData(\'' . $id . '\')"> <i class="fa fa-download"></i> Download ' . $result->sub_report_name . '</button>';
            }

            $data .= ' </div>
		  </form><br>';

            $response['status'] = 200;
            $response['data'] = $data;
        } else {
            $response['status'] = 201;
            $response['data'] = $data;
        }
        echo json_encode($response);
    }

    function DownloadData()
    {
        $data = $this->input->post_get('formdata');
        $object = json_decode($data);
        // print_r($data);exit();
        $id = $object->query_id;
        $field_id = $object->query_field_id;
        $field_type = $object->query_field_type;
        $section_id = $object->query_section_id;
        $branch_id = $this->session->user_session->branch_id;
        // $patient_table = $this->session->user_session->patient_table;
        $patient_table = "";
        $this->load->library('excel');
        //$listInfo = $this->export->exportList();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $query = $this->db->query("select * from html_query_reports_table where field_id='#" . $field_id . "' and field_type=" . $field_type . " and section_id=" . $section_id);
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
            $filename = "Dashboard_Report" . date("Y-m-d") . "" . time() . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');


        }
    }

    function getDataTableReport()
    {
        $hash_key = $this->input->post_get('hash_key');
        $section_id = $this->input->post_get('section_id');

        // $patient_table = $this->session->user_session->patient_table;
        $patient_table = "";
        $table = '';
        $table_td = '';
        $table_th = '';
        $query = $this->db->query("select * from html_query_reports_table where field_id='#" . $hash_key . "' and section_id=" . $section_id);
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $query_val = $result->query;


            $array_head = array();


            //get all action buttons
            $action_button = 0;
            $data_action_button = "";
            $a = "";
            $query_action_btn = $this->db->query("select * from html_acttion_button_table where section_id=" . $section_id . " AND hash_key='#" . $hash_key . "'");
            if ($this->db->affected_rows() > 0) {
                $action_button = 1;
                $result_a_button = $query_action_btn->result();

                foreach ($result_a_button as $row_Act) {
                    if ($row_Act->type == 1) {
                        $route = $row_Act->route;
                        $column_name = $row_Act->column_name;
                        $str = "#" . $column_name;
                        //$dataRplc=$row->$column_name;
                        //$route=str_replace($str,$dataRplc,$route);
                        $data_action_button .= "
							<a href='" . base_url() . "/" . $route . "'>
							<button class='btn btn-primary'><i class='" . $row_Act->button_class . "'></i></button>
							</a>
							";
                        $a .= "pooja";
                    }

                }
            } else {
                $action_button = 0;
            }
            $query_new = $this->db->query($query_val);

            if ($this->db->affected_rows() > 0) {
                $q_result = $query_new->result();

                $m = 0;
                //$array_data=array();
                foreach ($q_result as $row) {
                    $table_td .= "<tr>";
                    $row = (array)$row;
                    if ($action_button == 1) {

                        $row[] = $data_action_button;
                    }
                    $array_data[] = array_values($row);

                    foreach ($row as $key => $r) {


                        if ($m == 0) {
                            $array_head[] = $key;
                        }

                        $table_td .= "<td>" . $r . "</td>";
                    }


                    //get action buttons


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
                if ($action_button == 1) {
                    $table_th .= "<th>Action</th>";
                }

                $table_th .= "</tr>";


                $table .= '
			<table class="table table-bordered" id="table_data_' . $hash_key . '">
			<thead>
			' . $table_th . '
			</thead>
			<tbody>
			</tbody>
			</table>
			';


            }

            $response['array_data'] = $array_data;

            $response['table'] = $table;

            $response['status'] = 200;

        } else {
            $response['table'] = $table;
            $response['status'] = 200;
        }
        echo json_encode($response);


    }

    function DownloadNewcsv()
    {
        $data = $this->input->post_get('formdata');
        $object = json_decode($data);
        // print_r($data);exit();
        $id = $object->query_id;
        $field_id = $object->query_field_id;
        $field_type = $object->query_field_type;
        $section_id = $object->query_section_id;
        $branch_id = $this->session->user_session->branch_id;
        // $patient_table = $this->session->user_session->patient_table;
        $patient_table = "";
        $query = $this->db->query("select * from html_query_reports_table where field_id='#" . $field_id . "' and field_type=" . $field_type . " and section_id=" . $section_id);
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            $query_val = $result->query;
            $reoprt_name = $result->reoprt_name;
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

            $query_new = $this->db->query($query_val);
            if ($this->db->affected_rows() > 0) {
                $q_result = $query_new->result();

                $m = 0;
                $rowCount = 2;
                $filename = $reoprt_name . '_CSVReport' . date('Ymd') . '.csv';
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$filename");
                header("Content-Type: application/csv; ");

                // file creation
                $file = fopen('php://output', 'w');
                foreach ($q_result as $row) {

                    foreach ($row as $key => $r) {
                        if ($m == 0) {
                            $array_head[] = $key;
                        }
                    }
                }
                $header = $array_head;
                fputcsv($file, $header);

                foreach ($q_result as $row) {
                    $otherdata = array();
                    foreach ($row as $key => $r) {

                        $otherdata[] = $r;
                    }
                    fputcsv($file, $otherdata);

                }

                fclose($file);
                exit;

            }
        }
    }

    public
    function DownloadNewpdf()
    {
        $formdata = $this->input->post_get('formdata');
        $object = json_decode($formdata);

        $id = $object->query_id;
        $field_id = $object->query_field_id;
        $field_type = $object->query_field_type;
        $section_id = $object->query_section_id;
        $branch_id = $this->session->user_session->branch_id;
        // $patient_table = $this->session->user_session->patient_table;
        $patient_table = "";
        $query = $this->db->query("select * from html_query_reports_table where field_id='#" . $field_id . "' and field_type=" . $field_type . " and section_id=" . $section_id);
        if ($this->db->affected_rows() > 0) {
            $result = $query->row();
            // print_r($result);exit();
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

    public
    function getHtmlTemplateForm()
    {
        $section_id = $this->input->post('section_id');
        $queryparameter_hidden = $this->input->post('queryparameter_hidden');
        if (!is_null($queryparameter_hidden)) {
            $queryparameterstring = base64_decode($queryparameter_hidden);
            $queryStringArray = json_decode($queryparameterstring);
            extract((array)$queryStringArray);
        }
       // var_dump($queryStringArray);

        $query = $this->HtmlFormTemplateModel->get_HtmlFormSection($section_id);


        $data = "";

        if ($query != false) {
            $data = $query;

            if (isset($update_id) && $data->primary_table != null && !empty($data->primary_table)) {
                $QueryTogetDataUpdate = $this->HtmlFormTemplateModel->GetUpdateData($update_id, $data->primary_table);
                $getMappingData = $this->HtmlFormTemplateModel->GetMappingData($section_id, $data->primary_table);
            } else {
                $QueryTogetDataUpdate = false;
                $getMappingData = false;

            }
            if ($QueryTogetDataUpdate != false) {
                $response['Update_data'] = $QueryTogetDataUpdate;
                $response['Mapping_Data'] = $getMappingData;
            } else {
                $response['Update_data'] = "";
                $response['Mapping_Data'] = "";
            }
            $response['status'] = 200;
            $response['data'] = $data;
            $response['branch_id'] = $this->session->user_session->branch_id;
            $response['body'] = "data fetch";

        } else {
            $response['status'] = 201;
            $response['data'] = '';
            $response['form_data'] = '';
            $response['field_name'] = '';
            $response['branch_id'] = $this->session->user_session->branch_id;
            $response['body'] = 'Something went wrong';
        }


        echo json_encode($response);
    }

    public
    function getTableColumnsAndDatatypes()
    {
        $table_name = $this->input->post('table_name');
        $results = '';
        $results = $this->HtmlFormTemplateModel->getTablecolumns($table_name);

        $tableFieldarray = array();
        if (!empty($results)) {
            $inetger_array = array('integer', 'int', 'smallint', 'tinyint', 'mediumint', 'bigint', 'decimal', 'numeric', 'float', 'double');
            $date_array = array('date', 'datetime', 'timestamp', 'time', 'year');
            $k = 0;
            $og_array = array('status', 'id', 'user_id', 'transaction_date');
            $col_name = array();

            foreach ($results as $key => $value) {
                $col_name[] = $value->COLUMN_NAME;
//				if($k!=0){
                $resultObject = new stdClass();
                $resultObject->column = $value->COLUMN_NAME;
                $resultObject->datatype = $value->DATA_TYPE;
                if (in_array($value->DATA_TYPE, $inetger_array)) {
                    $resultObject->type = 6;
                } else if (in_array($value->DATA_TYPE, $date_array)) {
                    $resultObject->type = 5;
                } else {
                    $resultObject->type = 1;
                }

                array_push($tableFieldarray, $resultObject);
//				}
//				$k++;
            }
            $final_array = array_intersect($col_name, $og_array);

            if (count($final_array) == count($og_array)) {
                $response['status'] = 200;
                $response['data'] = $tableFieldarray;
                $response['body'] = 'Data Fetch';
            } else {
                $response['status'] = 201;
                $response['body'] = 'Column id,status,user_id,transaction_date are compulsory.In your table only found ' . implode(",", $final_array) . ' Please add remaining columns';
            }

        } else {
            $response['status'] = 201;
            $response['data'] = '';
            $response['body'] = 'Data Not Found';
        }

        echo json_encode($response);
    }

    public
    function uploadTrumbowygImage()
    {
        // print_r($this->input->post());exit();
        $name_input = "image";

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
        // print_r($input_data);exit();
        $data = new stdClass();
        if ($input_data != "") {
            $data->link = base_url() . $input_data[0];
            $response['data'] = $data;
            $response['success'] = true;
            $response['status'] = 200;
        } else {
            $data->link = '';
            $response['data'] = $data;
            $response['success'] = false;
            $response['status'] = 201;
        }

        echo json_encode($response);
    }

    public
    function getDataTableElementId()
    {
        extract($_POST);
        $query = $this->db->query("select elementID from datatable_query_master where sectionID=" . $section_id);
        if ($this->db->affected_rows() > 0) {
            $dataTableElementId = $query->row()->elementID;
            $response['dataTableElementId'] = $dataTableElementId;
            $response['status'] = 200;
        } else {
            $response['status'] = 201;
        }
        echo json_encode($response);
    }

    public
    function makePage()
    {
        $content = $_GET["content"];
        $file = "new_page" . uniqid() . ".php";
        file_put_contents($file, $content);
        echo $file;
    }

    public
    function getAnyoneDependantOnQueryDropdown()
    {
        $section_id = $this->input->post('section_id');
        $queryHashId = $this->input->post('queryHashId');
        $hash_key = str_replace('#', '', $queryHashId);
        $results = $this->HtmlFormTemplateModel->getAnyoneDependantOnQueryDropdown($section_id, $hash_key);
        $response['query'] = $this->db->last_query();
        if ($results != false) {
            $data = $results->is_anyone_depend;
            $response['data'] = $data;
            $response['status'] = 200;
        } else {
            $response['status'] = 201;
        }
        echo json_encode($response);
    }

    public
    function loadElementsValue()
    {
        $hash_id = $this->input->post('hash_id');
        $hash_value = $this->input->post('hash_value');
        $querydata = $this->input->post('querydata');
        $querydata = base64_decode($querydata);

        $queryparameter_hidden = $this->input->post('queryPara');
        if (!is_null($queryparameter_hidden)) {
            $queryparameterstring = base64_decode($queryparameter_hidden);
            $queryStringArray = json_decode($queryparameterstring);
            extract((array)$queryStringArray);
        }
        $queryArrayValue = array();
        $queryData = explode('|', $querydata);
        if (!empty($queryData)) {
            foreach ($queryData as $key => $value) {
                $value_data = explode('@', $value);
                if (count($value_data) >= 2) {
                    $depHashId = $value_data[0];
                    $depQuery = $value_data[1];
                    if (is_numeric($hash_value)) {
                        $hash_value = $hash_value;
                    } else {
                        $hash_value = "'" . $hash_value . "'";
                    }
                    $shortTextObject = new stdClass();
                    $shortTextObject->hash_key = $depHashId;

                    $query = str_replace($hash_id, $hash_value, $depQuery);
                    $query = str_replace('#', '$', $query);
                    $querydata = $this->db->query($query . ' order by id desc');
                    $queryValue = '';

                    if ($this->db->affected_rows() > 0) {

                        $queryvalue = $querydata->result();
                        $i = 0;
                        $j = 0;
                        foreach ($queryvalue[0] as $q_key1 => $q_value1) {
                            if ($j == 0) {
                                $k = $q_key1;
                                break;
                            }

                            $j++;
                        }
                        foreach ($queryvalue as $q_key => $q_value) {
                            if ($i == 0) {
                                // var_dump($q_key);
                                $queryValue = $q_value->$k;
                            }
                            $i++;
                        }
                    }
                    $shortTextObject->hash_key_value = $queryValue;
                    array_push($queryArrayValue, $shortTextObject);
                }
            }
            $response['query_data'] = $queryArrayValue;
            $response['status'] = 200;
        } else {
            $response['query_data'] = array();
            $response['status'] = 201;
        }
        echo json_encode($response);
    }

	public function getSectionConfigData()
	{
		$has_key = $this->input->post('id');
		$section_id = $this->input->post('section_id');
		$department_id = $this->input->post('department_id');

		$query = $this->HtmlFormTemplateModel->get_SectionConfigData($has_key,$section_id,$department_id);
		$data = "";
		if($query != false)
		{
			$data = $query;
			$response['status'] = 200;
			$response['data'] = $data;

		} else {
			$response['status'] = 201;
			$response['data'] = '';
		}
		echo json_encode($response);
	}


	public function getSectionTableData()
	{
		$has_key = $this->input->post('id');
		$section_id = $this->input->post('section_id');
		$department_id = $this->input->post('department_id');

		$query= $this->HtmlFormTemplateModel->get_SectionTableData($has_key,$section_id,$department_id);
		$data = "";
		if($query != false)
		{
			$data = $query;
			$response['status'] = 200;
			$response['body'] = "Data Fetch";
			$response['data'] = $data;

		} else {
			$response['status'] = 201;
			$response['body'] = 'No Data Found';
			$response['data'] = '';
		}
		echo json_encode($response);
	}
}


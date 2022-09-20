<?php

require_once 'HexaController.php';

/**
 * @property  TemplateModel TemplateModel
 */
class TemplateController extends HexaController
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('TemplateModel');
	}


	function saveTemplate()
	{

		$validationObject = $this->is_parameter(array("department_id","section_name", "elementSequenceType","elementSequenceId"));
		if ($validationObject->status) {

			$params = $validationObject->param;
			$section_name = $params->section_name;
			$department_id = $params->department_id;
			$create_by =1;
			$resultObject = $this->TemplateModel->getCompany($department_id);
			$elementObjectArray = array();
			$tableName="";
			if (is_null($this->input->post("ck_template_transaction"))) {
				$sectionTransactionMode = 0;
			} else {
				$sectionTransactionMode = $this->input->post("ck_template_transaction") == "on" ? 1 : 0;
			}
			if (is_null($this->input->post("section_id"))) {
				$sectionId = null;
			} else {
				if($this->input->post("section_id")==""){
					$sectionId = null;
				}else{
					$sectionId = $this->input->post("section_id");
				}

			}
			if (is_null($this->input->post("ck_template_history"))) {
				$sectionHistoryMode = 0;
			} else {
				$sectionHistoryMode = $this->input->post("ck_template_history") == "on" ? 1 : 0;
			}

			if($resultObject->totalCount == 1){
				$company_id=$resultObject->data->company_id;
				$tableName = "com_".$company_id."_dep_".$department_id;

				$elementsType=explode(",",$params->elementSequenceType);
				$elementsId=explode(",",$params->elementSequenceId);

				if(count($elementsType) == count($elementsId)){
					foreach ($elementsType as $typeIndex => $type) {
						$index = $elementsId[$typeIndex];
						switch ((int)$type) {
							case 1:
								array_push($elementObjectArray, $this->getShortText($index,$typeIndex,$type));
								break;
							case 2:
								array_push($elementObjectArray, $this->getLongText($index,$typeIndex,$type));
								break;
							case 3:
								array_push($elementObjectArray, $this->getDropDown($index,$typeIndex,$type));
								break;
							case 4:
								array_push($elementObjectArray, $this->getMultipleDropDown($index,$typeIndex,$type));
								break;
							case 5:
								array_push($elementObjectArray, $this->getDateText($index,$typeIndex,$type));
								break;
							case 6:
								array_push($elementObjectArray, $this->getNumberText($index,$typeIndex,$type));
								break;
							case 7:
								array_push($elementObjectArray, $this->getAttachmentText($index,$typeIndex,$type));
								break;
							case 8:
								array_push($elementObjectArray, $this->getqueryDropDown($index,$typeIndex,$type));
								break;
							case 10:
								array_push($elementObjectArray, $this->getfixnumber($index,$typeIndex,$type));
								break;
						}
					}
				}
			}
			$response["sectionId"] = $sectionId;
			$resultObject = $this->TemplateModel->saveTemplate($section_name, $department_id, $elementObjectArray, $tableName, $create_by,$sectionId,$sectionTransactionMode,$sectionHistoryMode);
			$dataObject = new stdClass();
			$dataObject->sectionName = $params->section_name;
			$dataObject->data = $elementObjectArray;
			if($resultObject->status){
				$response["status"] = 200;
				$response["body"]="Save Changes";
				$response["data"] = $resultObject;
			}else{
				$response["status"] = 201;
				$response["body"]="Failed To Save Changes";
				$response["body"] = $dataObject;
				$response["data"] = $resultObject;
			}
		} else {
			$response["status"] = 201;
			$response["body"] = "Required Parameter Missing";
		}
		echo json_encode($response);
	}

	private function getShortText($position,$sequence,$type)
	{
		$shortTextObject = new stdClass();
		$shortTextObject->sequeance_num=$sequence;
		$shortTextObject->ans_type=$type;

		$shortText = $this->input->post("short_text_".$position);
		$shortTextId = $this->input->post("short_text_id_" . $position);
		$shortTextPlaceholder = $this->input->post("short_text_placeholder_".$position);
		$shortTextRequired = $this->input->post("ck_short_text_required_".$position);
		$shortTextHistory = $this->input->post("ck_short_text_history_".$position);

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

	private function getLongText($position,$sequence,$type)
	{
		$longtextObject = new stdClass();
		$longtextObject->sequeance_num=$sequence;
		$longtextObject->ans_type=$type;

		$longText = $this->input->post("long_text_".$position);
		$longTextId = $this->input->post("long_text_id_" . $position);
		$longTextPlaceholder = $this->input->post("long_text_placeholder_".$position);
		$longTextRequired = $this->input->post("ck_long_text_required_".$position);
		$longTextHistory = $this->input->post("ck_long_text_history_".$position);

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

	private function getDropDown($position,$sequence,$type)
	{
		$dropDownObject = new stdClass();
		$dropDownObject->sequeance_num=$sequence;
		$dropDownObject->ans_type=$type;
		$dropDown = $this->input->post("drop_down_".$position);
		$dropDownId = $this->input->post("drop_down_id_" . $position);
		$dropDownPlaceholder = $this->input->post("drop_down_placeholder_".$position);
		$dropDownRequired = $this->input->post("ck_drop_down_required_".$position);
		$dropDownHistory = $this->input->post("ck_drop_down_history_".$position);
		$dropDownOptions = $this->input->post("drop_down_option_value_".$position);
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
		}else{
			$dropDownObject->options = array();
		}

		return $dropDownObject;
	}

	private function getqueryDropDown($position,$sequence,$type){
		$dropDownObject = new stdClass();
		$dropDownObject->sequeance_num=$sequence;
		$dropDownObject->ans_type=$type;
		$dropDown = $this->input->post("query_drop_down_".$position);
		$dropDownId = $this->input->post("query_drop_down_id_" . $position);
		$dropDownPlaceholder = $this->input->post("query_drop_down_placeholder_".$position);
		$dropDownRequired = $this->input->post("ck_query_drop_down_required_".$position);
		$dropDownHistory = $this->input->post("ck_query_drop_down_history_".$position);
	 	$dropDownquery = $this->input->post("query_drop_down_option_".$position);
	 	$dropDowndependancy = $this->input->post("query_drop_down_dependant_".$position);
	 	$dropDownisextended = $this->input->post("ck_query_drop_down_exttemp_".$position);
	 	$dropDownexttempid = $this->input->post("slct_template_".$position);
	
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
			$dropDownObject->is_extended_template = $dropDownisextended== "on" ? 1 : 0;
		if ($dropDownexttempid != null)
			$dropDownObject->ext_template_id = $dropDownexttempid;
		
		return $dropDownObject;
	}

	private function getMultipleDropDown($position,$sequence,$type)
	{
		$dropDownObject = new stdClass();
		$dropDownObject->sequeance_num=$sequence;
		$dropDownObject->ans_type=$type;
		$dropDown = $this->input->post("multi_drop_down_".$position);
		$dropDownId = $this->input->post("multi_drop_down_id_" . $position);
		$dropDownPlaceholder = $this->input->post("multi_drop_down_placeholder_".$position);
		$dropDownRequired = $this->input->post("ck_multi_drop_down_required_".$position);
		$dropDownHistory = $this->input->post("ck_multi_drop_down_history_".$position);
		$dropDownOptions = $this->input->post("multi_drop_down_option_value_".$position);
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
		}else{
			$dropDownObject->options = array();
		}
		return $dropDownObject;
	}

	private function getNumberText($position,$sequence,$type)
	{
		$shortTextObject = new stdClass();
		$shortTextObject->sequeance_num=$sequence;
		$shortTextObject->ans_type=$type;
		$shortText = $this->input->post("number_element_".$position);
		$shortTextId = $this->input->post("number_text_id_" . $position);
		$shortTextPlaceholder = $this->input->post("number_placeholder_".$position);
		$shortTextRequired = $this->input->post("ck_number_text_required_".$position);
		$shortTextHistory = $this->input->post("ck_number_text_history_".$position);

		$shortTextObject->name = $shortText;
		if ($shortTextId != null)
			$shortTextObject->id = $shortTextId;
		if ($shortTextPlaceholder != null)
			$shortTextObject->placeholder = $shortTextPlaceholder;
		if ($shortTextRequired != null)
			$shortTextObject->is_required = $shortTextRequired== "on" ? 1 : 0;
		if ($shortTextHistory != null)
			$shortTextObject->is_history = $shortTextHistory == "on" ? 1 : 0;
		return $shortTextObject;
	}

	private function getfixnumber($position,$sequence,$type)
	{
		$shortTextObject = new stdClass();
		$shortTextObject->sequeance_num=$sequence;
		$shortTextObject->ans_type=$type;
		$shortText = $this->input->post("fixnumber_element_".$position);
		$shortTextId = $this->input->post("fixnumber_text_id_" . $position);
		$shortTextPlaceholder = $this->input->post("fixnumber_placeholder_".$position);
		$shortTextRequired = $this->input->post("fixck_number_text_required_".$position);
		$shortTextHistory = $this->input->post("fixck_number_text_history_".$position);
		$shortTextquery = $this->input->post("fixnumber_drop_down_option_".$position);
		
		$shortTextObject->name = $shortText;
		if ($shortTextId != null)
			$shortTextObject->id = $shortTextId;
		if ($shortTextPlaceholder != null)
			$shortTextObject->placeholder = $shortTextPlaceholder;
		if ($shortTextquery != null)
			$shortTextObject->custom_query = $shortTextquery;
		if ($shortTextRequired != null)
			$shortTextObject->is_required = $shortTextRequired== "on" ? 1 : 0;
		if ($shortTextHistory != null)
			$shortTextObject->is_history = $shortTextHistory == "on" ? 1 : 0;
		return $shortTextObject;
	}

	private function getDateText($position,$sequence,$type)
	{
		$shortTextObject = new stdClass();
		$shortTextObject->sequeance_num=$sequence;
		$shortTextObject->ans_type=$type;
		$shortText = $this->input->post("date_element_".$position);
		$shortTextId = $this->input->post("date_text_id_" . $position);
		$shortTextPlaceholder = $this->input->post("date_placeholder_".$position);
		$shortTextRequired = $this->input->post("ck_date_text_required_".$position);
		$shortTextHistory = $this->input->post("ck_date_text_history_".$position);

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

	private function getAttachmentText($position,$sequence,$type)
	{
		$shortTextObject = new stdClass();
		$shortTextObject->sequeance_num=$sequence;
		$shortTextObject->ans_type=$type;
		$shortText = $this->input->post("attachment_element_".$position);
		$shortTextId = $this->input->post("attachment_id_" . $position);
		$shortTextPlaceholder = $this->input->post("attachment_placeholder_".$position);
		$shortTextRequired = $this->input->post("ck_attachment_text_required_".$position);
		$shortTextHistory = $this->input->post("ck_attachment_text_history_".$position);

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

	public function getDepartmentSections(){
		$validationObject = $this->is_parameter(array("department_id"));
		if($validationObject->status){
			$param = $validationObject->param;
			$resultObject=$this->TemplateModel->fetchTemplateSections($param->department_id);
			$response["status"]=200;
			$response["body"]=$resultObject->data;
		}else{
			$response["status"]=201;
			$response["body"]="Missing Parameter";
		}
		echo  json_encode($response);
	}

	public function getSectionElements(){
		$validationObject = $this->is_parameter(array("department_id","section_id"));
		if($validationObject->status){
			$param = $validationObject->param;
			$resultObject=$this->TemplateModel->fetchSectionElement($param->section_id,$param->department_id);
			$response["status"]=200;
			$response["body"]=$resultObject->data;
		}else{
			$response["status"]=201;
			$response["body"]="Missing Parameter";
		}
		echo  json_encode($response);
	}

	public function deleteSection(){
		$validationObject = $this->is_parameter(array("section_id"));
		if($validationObject->status){
			$param = $validationObject->param;
			$resultObject=$this->TemplateModel->deleteSection($param->section_id);
			if($resultObject->status){
				$response["data"]=$resultObject;
				$response["status"]=200;
				$response["body"]="Delete Section";
			}else{
				$response["status"]=200;
				$response["body"]="Fail To Delete";
			}

		}else{
			$response["status"]=201;
			$response["body"]="Missing Parameter";
		}
		echo  json_encode($response);
	}

	public function deleteTemplateElement(){
		$validationObject = $this->is_parameter(array("templateID"));
		if($validationObject->status){
			$param = $validationObject->param;
			$resultObject=$this->TemplateModel->deleteElement($param->templateID);
			if($resultObject->status){
				$response["data"]=$resultObject;
				$response["status"]=200;
				$response["body"]="Delete Element";
			}else{
				$response["status"]=200;
				$response["body"]="Fail To Delete";
			}

		}else{
			$response["status"]=201;
			$response["body"]="Missing Parameter";
		}
		echo  json_encode($response);
	}
	
	function gettemplatelist(){
		$query=$this->TemplateModel->getTemplateList();
		$option="<option value='-1'>Select Template</option>";
		if($query != false){
			
			foreach($query as $data){
				$option.="<option value=".$data->id.">".$data->name."</option>";
			}
			$response["status"]=200;
			$response["data"]=$option;
		}else{
			$response["status"]=201;
			$response["data"]=$option;
		}echo  json_encode($response);
	}
	
	
}

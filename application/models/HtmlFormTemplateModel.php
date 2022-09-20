<?php

require_once 'MasterModel.php';

class HtmlFormTemplateModel extends MasterModel
{

	private $table;

	/**
	 * TemplateModel constructor.
	 * @param $table
	 */
	public function __construct()
	{
		$this->table = "html_html_template_master";
	}


	public function getCompany($company_id)
	{
		return $this->_select("html_departments_master", array("status" => 1, "id" => $company_id));
	}
	public function GetUpdateData($update_id,$primary_table){
		return $this->_select($primary_table, array("id" => $update_id));
	}
	public function GetMappingData($section_id,$primary_table){
		return $this->_select("htmlquerytable", array("section_id" => $section_id,"table_name"=>$primary_table));
	}
	
	public function getDependantsection($section_id){
		return $this->_select("html_section_master", array("dependant_section_id" => $section_id));
	}
	public function get_Is_Dependantsection($section_id){
		return $this->_select("html_section_master", array("id" => $section_id));
	}
	public function getTemplateList(){
		$query=$this->db->query("select id,name from html_departments_master");
		if($this->db->affected_rows() > 0){
			$result=$query->result();
			return $result;
		}else{
			return false;
		}
	}

	public function saveTemplate($sectionName, $department_id, $templateFields, $table_name, $create_by, $section_id, $sectionTransactionMode, $sectionHistoryMode)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			if (!empty($table_name)) {
				$history_table = null;
				if ($section_id == null) {
					$history_table = "his_" . $table_name;
					$this->db->insert("html_section_master",
						array("name" => $sectionName, "department_id" => $department_id, "create_by" => $create_by, "is_history" => $sectionHistoryMode, "is_traction" => $sectionTransactionMode));
					$section_id = $this->db->insert_id();
					$resultObject->section_id=$section_id;
					$this->db->set("tb_history", $history_table)->where(array("id" => $section_id))->update("html_section_master");
				} else {
					$this->db->set(array("name" => $sectionName, "is_history" => $sectionHistoryMode, "is_traction" => $sectionTransactionMode))->where("id", $section_id)->update("html_section_master");
					$history_table = "his_" . $table_name ;
					$resultObject->section_id=$section_id;

				}

				$fieldsArray = array();
				$historyFieldsArray=array();
				if (!$this->tableExist($table_name)) {
					$patientFieldDetails = array("type" => "INT", 'constraint' => '11');
					$fieldsArray["patient_id"] = $patientFieldDetails;
//					$companyFieldDetails = array("type" => "INT", 'constraint' => '11');
//					$fieldsArray["company_id"] = $companyFieldDetails;
					$companyFieldDetails = array("type" => "INT", 'constraint' => '11');
					$fieldsArray["branch_id"] = $companyFieldDetails;
					$transactionFieldDetails = array("type" => "datetime" );
					$fieldsArray["trans_date"] = $transactionFieldDetails;
					$processFieldDetails = array("type" => "INT", 'constraint' => '11');
					$fieldsArray["process_id"] = $processFieldDetails;
					$scheduleFieldDetails = array("type" => "INT", 'constraint' => '11');
					$fieldsArray["schedule_id"] = $scheduleFieldDetails;
				}

				foreach ($templateFields as $field) {
					$field->tb_name = $table_name;
					$field->section_id = $section_id;
					$field->department_id = $department_id;
					$options = null;
					if ($field->ans_type == 3 || $field->ans_type == 4 || $field->ans_type == 12 || $field->ans_type == 13) {
						$options = $field->options;
						unset($field->options);
					}
					$fields = (array)$field;
//var_dump($fields);

					if (!property_exists($field, "id")) {
						$this->db->insert("html_template_master", $fields);
						$field_id = $this->db->insert_id();
						$field_name = "sec_" . $section_id . "_f_" . $field_id;
						$this->db->set("field_name", $field_name)->where(array("id" => $field_id))->update("html_template_master");
						$fieldDetails = array("type" => "varchar", 'constraint' => '255', 'null' => TRUE, 'AFTER' => 'id');
						if (!$this->columnExist($table_name, $field_name)) {
							$fieldsArray[$field_name] = $fieldDetails;
							$historyFieldsArray[$field_name]=$fieldDetails;
						}
						if ($options != null) {
							foreach ($options as $value) {
								$this->db->insert("html_option_master", array("name" => $value, "temp_id" => $field_id));
							}
						}
					} else {
						$custom_query="";
						$dependancy="";
						$is_extended_template="";
						$ext_template_id="";
						if(array_key_exists("custom_query",$fields)){
							$custom_query=$fields['custom_query'];
						}
						if(array_key_exists("dependancy",$fields)){
							$dependancy=$fields['dependancy'];
						}
						if(array_key_exists("is_extended_template",$fields)){
							$is_extended_template=$fields['is_extended_template'];
						}
						if(array_key_exists("ext_template_id",$fields)){
							$ext_template_id=$fields['ext_template_id'];
						}
						$this->db->set(
						array("name" => $field->name, "ans_type" => $field->ans_type,
						"is_history"=>$sectionHistoryMode,
						'sequeance_num'=>$field->sequeance_num,"custom_query"=>$custom_query,
						"dependancy"=>$dependancy,"is_extended_template"=>$is_extended_template,"ext_template_id"=>$ext_template_id))
							->where(array("id" => $field->id))
							->update("html_template_master");

						$field_name = "sec_" . $section_id . "_f_" . $field->id;
						$fieldDetails = array("type" => "varchar", 'constraint' => '255', 'null' => TRUE, 'AFTER' => 'id');
						if (!$this->columnExist($table_name, $field_name)) {
							$historyFieldsArray[$field_name] = $fieldDetails;
						}

						if (!$this->columnExist($history_table, $field_name)) {
							$historyFieldsArray[$field_name]=$fieldDetails;
						}
						$resultObject->update = "On";
						if ($options != null) {
							// $this->db->where("temp_id", $field->id)->delete("html_option_master");
							$data=$this->getUniqueData($options,$field->id);
							// foreach ($options as $value) {							
							// 		$this->db->insert("html_option_master", array("name" => $value, "temp_id" => $field->id));	
							// }
						}else{
							//$this->db->where("temp_id", $field->id)->delete("html_option_master");
						}

					}
				}
				
				$resultObject->last_query = $this->db->last_query();
				$resultObject->fields = $templateFields;
				$result = $this->createTemplateTable($table_name, $fieldsArray);
				if ($sectionHistoryMode == 1) {
					$commonColumn=array("patient_id","trans_date","branch_id","process_id","schedule_id");
					foreach ($commonColumn as $column){
						$fieldDetails = array("type" => "INT", 'constraint' => '11');
						if($column == "trans_date"){
							$fieldDetails = array("type" => "datetime" );
						}
						if (!$this->columnExist($history_table, $column)) {
							$historyFieldsArray[$column] = $fieldDetails;
						}
					}
					$resultTable = $this->createTemplateTable($history_table, $historyFieldsArray);
					$resultObject->HistoryTableResult = $resultTable;
				}
				$resultObject->TransactionTableResult = $result;
				if ($result->status) {
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						$resultObject->status = FALSE;
						$resultObject->section = 4;
					} else {
						$this->db->trans_commit();
						$resultObject->status = TRUE;
					}
				} else {
					$this->db->trans_rollback();
					$resultObject->status = FALSE;
					$resultObject->section = 3;
				}

			} else {
				$this->db->trans_rollback();
				$resultObject->status = FALSE;
				$resultObject->section = 2;
			}
			$this->db->trans_complete();


		} catch (Exception $ex) {
			$resultObject->status = FALSE;
			$resultObject->section = 1;
			$this->db->trans_rollback();
		}
		return $resultObject;
	}

	public function columnExist($table_name, $column_name)
	{
		if($this->tableExist($table_name))
		return $this->db->field_exists($column_name, $table_name);

		return false;
	}

	public function tableExist($table_name)
	{
		return $this->db->table_exists($table_name);
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

	public function fetchTemplateSections($department_id)
	{
		// return $this->_select("html_section_master",
		// 	array("department_id" => $department_id, "status" => 1),
		// 	"*", false);
		$sql="select * from html_section_master where department_id=".$department_id." and status=1 order by id desc";
		return $this->_rawQuery($sql);
	}

	public function fetchSectionElement($section_id, $department_id)
	{
		$sql='select t.id,t.name,t.ans_type,t.sequeance_num,t.is_required,t.is_history,t.placeholder,t.custom_query,t.dependancy,t.is_extended_template,t.ext_template_id,
		(select group_concat(o.id,"|||",o.name) from html_option_master o where o.temp_id=t.id) as options,
		(select group_concat(s.id,"|||",s.name,"|||",s.is_traction) from html_section_master s where s.id=t.section_id) as section
		from html_template_master t where department_id='.$department_id.' and t.status=1 and section_id='.$section_id.' and status=1 order by sequeance_num';
		return $this->_rawQuery($sql);
//		return $this->_select("html_template_master t",
//			array("department_id" => $department_id,"t.status"=>1, "section_id" => $section_id, "status" => 1),
//			array("t.id", "t.name", "t.ans_type", "t.sequeance_num", "t.is_required", "t.is_history", "t.placeholder",
//				"(select group_concat(o.id,'|||',o.name) from html_option_master o where o.temp_id=t.id) as options",
//				"(select group_concat(s.id,'|||',s.name) from html_section_master s where s.id=t.section_id) as section"
//			), false);
	}

	public function updateTemplateHtml($raw_html,$section_id,$final_html,$QueryStringParameter,$history_unabled)
	{
		return $this->_update("html_section_master", array("section_html" => $final_html,"raw_html"=>$raw_html,"query_string_parameter"=>$QueryStringParameter,"history_unabled"=>$history_unabled), array("id" => $section_id));
	}
	public function UpdateQueryStringData($QueryStringParameterDataFinal,$section_id){
		$delete=$this->db->delete("html_field_required_table",array("section_id"=>$section_id,"type"=>1));
		if($delete == true){
			if(count($QueryStringParameterDataFinal) > 0){
									//echo $section_id1;
									$QueryStringParameterDataFinal1=array();
									foreach($QueryStringParameterDataFinal as $data){
										$data['section_id']=$section_id;
										array_push($QueryStringParameterDataFinal1,$data);
										
									}
									
									$insert2 = $this->db->insert_batch('html_field_required_table', $QueryStringParameterDataFinal1);
				return true;					
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}

	public function deleteSection($section_id)
	{
		return $this->_update("html_section_master", array("status" => 0), array("id" => $section_id));
	}

	public function deleteElement($element_id){
		return $this->_update("html_template_master",array("status"=>0),array("id"=>$element_id));
	}

	public function getUniqueData($options,$temp_id)
	{
		$resultMyOp=$this->db->select("name")->where('temp_id', $temp_id) -> get('html_option_master')->result();
		$same_array=array();
		$deleted_array=array();
		// print_r($options);
		foreach ($resultMyOp as $key => $value) {
			if(in_array($value->name, $options))
			{
				array_push($same_array,$value->name);
			}
			else
			{
				array_push($deleted_array,$value->name);
			}
		}

		$insert_array=array_diff($options,$same_array);
		// print_r($result);
		foreach ($insert_array as $key => $value) {
			$this->db->insert("html_option_master", array("name" => $value, "temp_id" => $temp_id));
		}

		foreach ($deleted_array as $key => $value) {
			$where=array("temp_id"=>$temp_id,"name"=>$value);
			$this->db->where($where)->delete("html_option_master");
		}
		return true;
		// print_r($deleted_array);
	}

	public function getSectionHtmlForm($section_id)
	{
		
		$query=$this->db->query("select * from html_section_master where id=".$section_id);
		if($this->db->affected_rows() > 0){
			$result=$query->row();
			return $result;
		}else{
			return false;
		}
		
	}


	public function addSectionTemplateHtml($section_id,$insert_data,$GetQueryDataInsert,$isRequired,$department_id,$QueryStringParameterDataFinal,$primary_data,$primary_table,$section_form_type,$datatable_insert)
	{
		try {
			$this->db->trans_start();
			$resultObject = new stdClass();
			
					if ($section_id == null) {
							if($this->db->insert("html_section_master",$insert_data))
							{
								$section_id1 = $this->db->insert_id();
								$resultObject->section_id = $section_id1;
								$resultObject->status = true;
								
								if($GetQueryDataInsert != false){
									foreach($GetQueryDataInsert as $data){
										$data['section_id']=$section_id1;
										$insert=$this->db->insert("htmlquerytable",$data);
									}
								}
								if ($primary_table!=null && $primary_table !="" && $primary_table!=1) {
									$primary_data['section_id']=$resultObject->section_id;
									$prim_insert=$this->db->insert("htmlquerytable",$primary_data);
								}
								if($isRequired!=false)
								{
									// print_r($isRequired);exit();
									foreach($isRequired as $required) {

										$insert_re=array('department_id'=>$department_id,
														'section_id'=>$resultObject->section_id,
													    'field_id'=>$required->id,
														'field_type'=>$required->type,
														'is_required'=>$required->is_req,
														'default_val'=>$required->default_val,
														'min_val'=>$required->min_val,
														'max_val'=>$required->max_val,
														'placeholder'=>$required->placeholder
													);
											$insert1=$this->db->insert("html_field_required_table",$insert_re);
									}
								}
								if($section_form_type==2)
								{
									if(!empty($datatable_insert))
									{
										$datatable_insert['sectionID']=$resultObject->section_id;
										
										$action_column='actionButtonPrimary:id|actionButtonIcon:fa fa-pen|actionButtonType:2|actionButtonRedirectTemplate:'.$resultObject->section_id.'|,actionButtonPrimary:id|actionButtonIcon:fa fa-trash|actionButtonType:3|actionButtonExecutionQuery:update '.$primary_table.' set status=0 where id=#id|';
										$datatable_insert['action_columns']=$action_column;
										$dataT_insert=$this->db->insert("datatable_query_master",$datatable_insert);
									}
								}
								if(count($QueryStringParameterDataFinal) > 0){
									//echo $section_id1;
									$QueryStringParameterDataFinal1=array();
									foreach($QueryStringParameterDataFinal as $data){
										$data['section_id']=$section_id1;
										array_push($QueryStringParameterDataFinal1,$data);
										
									}
									
									$insert2 = $this->db->insert_batch('html_field_required_table', $QueryStringParameterDataFinal1);
									
								}
								
							}
							else
							{
								$resultObject->status = false;
							}
						}
						else
						{
							if($this->db->set($insert_data)->where("id", $section_id)->update("html_section_master"))
							{
								$resultObject->section_id = $section_id;
								$resultObject->status = true;

								if($isRequired!=false)
								{
									foreach ($isRequired as $required) {
										$insert_re=array('is_required'=>$required->is_req,
														'default_val'=>$required->default_val,
														'min_val'=>$required->min_val,
														'max_val'=>$required->max_val);
										$where_re=array('department_id'=>$department_id,
														'section_id'=>$section_id,
													    'field_id'=>$required->id,
														'field_type'=>$required->type,
														'placeholder'=>$required->placeholder);
										$select_data=$this->db->select("id")->where($where_re)->get('html_field_required_table')->num_rows();
										if($select_data>0)
										{
											$this->db->set($insert_re)->where($where_re)->update("html_field_required_table");
										}
										else
										{
											$insert_re1=array('department_id'=>$department_id,
														'section_id'=>$section_id,
													    'field_id'=>$required->id,
														'field_type'=>$required->type,
														'is_required'=>$required->is_req,
														'default_val'=>$required->default_val,
														'min_val'=>$required->min_val,
														'max_val'=>$required->max_val,
														'placeholder'=>$required->placeholder);
											$insert2=$this->db->insert("html_field_required_table",$insert_re1);
										}
									}
								}
							}
							else
							{
								$resultObject->status = false;
							}
						}
			
			
					
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('info', "insert form Transaction Rollback");
				$resultObject->status = false;
			} else {
				$this->db->trans_commit();
				log_message('info', "insert form Transaction Commited");
				$resultObject->status = true;
			}
			$this->db->trans_complete();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			$resultObject->status = false;
		}
		return $resultObject;
		
	}

	public function get_HtmlFormSection($section_id)
	{
		$query=$this->db->query('select sm.*,(select group_concat(om.name,"||",om.value_id,"||",om.ans_type SEPARATOR  "@")
			from html_option_master om where om.section_id=sm.id and om.status=1) as opt,(select group_concat(fr.field_id,"||",fr.field_type,"||",fr.is_required,"||",fr.default_val,"||",fr.min_val,"||",fr.max_val,"||",fr.placeholder SEPARATOR  "@")
			from html_field_required_table fr where fr.section_id=sm.id) as req from html_section_master 
			sm where sm.id='.$section_id.'');
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function addDropOptions($department_id,$section_id,$ans_type,$value1,$option_value)
	{
		$resultObject = new stdClass();
		$resultMyOp=$this->db->select("name")->where(array("department_id" => $department_id, "status" => 1,"section_id"=>$section_id,"value_id"=>$value1,"ans_type"=>$ans_type)) -> get('html_option_master');
		if($this->db->affected_rows()>0)
		{
			$update_data = array('name' => $option_value);
			$where = array('value_id' => $value1,'section_id'=>$section_id ,'department_id'=>$department_id,'ans_type'=>$ans_type);
			if($this->db->set($update_data)->where($where)->update("html_option_master"))
			{
				$resultObject->section_id = $section_id;
				$resultObject->status = true;
			}
			else
			{
				$resultObject->status = false;
			}
		}
		else
		{
			$insert_data = array('name' => $option_value ,
								 'value_id'=>$value1,
								 'create_on'=>date('Y-m-d H:i:s'),
								 'create_by'=>$this->session->user_session->id,
								 'department_id'=>$department_id,
								 'section_id'=>$section_id,
								 'status'=>1,
								 'ans_type'=>$ans_type );
			if($this->db->insert("html_option_master",$insert_data))
			{
				$option_id1 = $this->db->insert_id();
				
				$resultObject->status = true;
			}
			else
			{
				$resultObject->status = true;
			}
		}
		return $resultObject;
	}
	public function get_dropdownquery($value1,$section_id,$ans_type)
	{
		$query = $this->db->query("select *  from htmlquerytable where section_id=".$section_id." and field_id='".$value1."' and field_type=".$ans_type." order by id desc");
		if ($this->db->affected_rows() > 0) {
			$query_data=$query->row();
			
			return $query_data;
		} else {
			return false;
		}
	}
	public function addQueryDropdown($query_drop,$section_id,$department_id,$field_id,$field_type)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			$query = $this->db->query("select *  from htmlquerytable where section_id=".$section_id." and field_id='".$field_id."' and field_type=".$field_type." order by id desc");
		if ($this->db->affected_rows() > 0) {
			if(!empty($query_drop))
				{
					$where = array('section_id' =>$section_id ,
								   'department_id'=>$department_id,
								   'field_id'=>$field_id,
								   'field_type'=>$field_type);
				foreach ($query_drop as $key => $value) {

						$this->db->set($value)->where($where)->update("htmlquerytable");
					}
				}
		}
		else 
		{
			if(!empty($query_drop))
				{
				foreach ($query_drop as $key => $value) {
						$this->db->insert("htmlquerytable",$value);
					}
				}
			
		}
			
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('info', "insert form Transaction Rollback");
				$resultObject->status = FALSE;
			} else {
				$this->db->trans_commit();
				log_message('info', "insert form Transaction Commited");
				$resultObject->status = TRUE;
			}
			$this->db->trans_complete();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			$resultObject->status = FALSE;
		}
		return $resultObject;
	}
	public function addQueryDropdownDefault($update_data,$where)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			$resultMyOp=$this->db->select("*")->where($where) -> get('html_field_required_table');
			if ($this->db->affected_rows() > 0) {
				$this->db->set($update_data)->where($where)->update("html_field_required_table");
			}
			else 
			{
				$this->db->insert("htmlquerytable",$update_data);
			}
			
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('info', "insert form Transaction Rollback");
				$resultObject->status = FALSE;
			} else {
				$this->db->trans_commit();
				log_message('info', "insert form Transaction Commited");
				$resultObject->status = TRUE;
			}
			$this->db->trans_complete();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			$resultObject->status = FALSE;
		}
		return $resultObject;
	}
	public function getHtmlRequiredFieldData($field_id,$field_type,$section_id)
	{
		$query = $this->db->query("select *  from html_field_required_table where section_id=".$section_id." and field_id='#".$field_id."' and field_type=".$field_type." order by id desc");
		if($this->db->affected_rows()>0)
		{
			$result=$query->row();
			
			return $result;
		}
		else
		{
			return false;
		}
	}
	public function getTablecolumns($table_name)
	{
		
		$query = "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, 
		COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
		WHERE table_name = '$table_name' AND table_schema = '".$this->db->database."'";	
		$result = $this->db->query($query)->result(); 

		return $result;
	}
    public function getAnyoneDependantOnQueryDropdown($section_id,$hash_id)
    {
        $query = $this->db->query("select is_anyone_depend from htmlquerytable where section_id=".$section_id." and field_id='".$hash_id."' and field_type=8 order by id desc");
        if($this->db->affected_rows()>0)
        {
            $result=$query->row();

            return $result;
        }
        else
        {
            return false;
        }
    }
}

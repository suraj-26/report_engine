<?php

require_once 'HexaController.php';


class DatatableFormInputController extends HexaController
{

    function __construct()
    {
        parent::__construct();
    }


    public function viewFormDataTable()
    {
        $this->load->view("DatatableTemplate/DatatableFormInput", array("title" => "DataTable Input Form"));
    }

    public function saveDynamicFormTableEntry()
    {
        $header = $this->is_parameter(
            array("trans_table", "data", "operation","operationTable","haskey","section_id","depart_id"));
        if ($header->status) {
            $tableName = $header->param->trans_table;
            $data = json_decode($header->param->data);

            try {
                $this->db->trans_start();

                $masterData = array(
                    "table_name" => $tableName,
                    "operation" => $header->param->operation,
                    "has_key" => $header->param->haskey,
                    "section_id" => $header->param->section_id,
                    "dep_id" => $header->param->depart_id,
                    "where_condition" => $this->input->post("dynamicWhereCondition"),
                    "operation_table"=>$header->param->operationTable,
                    "update_where_columns"=>$this->input->post("update_where_columns"),
                    "fetch_query_columns"=>$this->input->post("fetch_query_columns")
                );
                if ($this->db->insert("dynamic_form_table_master", $masterData)) {
                    $master_id = $this->db->insert_id();
                    $insertBatch = array();
                    foreach ($data as $object) {
                        $formatValue = (array)$object;
                        $formatValue["master_id"] = $master_id;
                        array_push($insertBatch, $formatValue);
                    }
                    $this->db->insert_batch("dynamic_form_column_master", $insertBatch);
                } else {
                    $this->db->trans_rollback();
                    $response["status"] = 201;
                    $response["body"] = "Failed To Save Configuration";
                }

                if ($this->db->trans_status()) {
                    $this->db->trans_commit();
                    $response["status"] = 200;
                    $response["body"] = "Save Configuration";
                } else {
                    $this->db->trans_rollback();
                    $response["status"] = 201;
                    $response["body"] = "Failed To Save Configuration";
                }
                $this->db->trans_complete();
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $this->db->trans_complete();
                $response["status"] = 201;
                $response["body"] = "Failed To Save Configuration";
            }
        } else {
            $response["status"] = 201;
            $response["body"] = "Something went wrong";
        }
        echo json_encode($response);
    }

    public function getHeaderConfiguration()
    {
        $header = $this->is_parameter(array("section_id","dep_id","haskey"));

        if ($header->status) {
            $sql = "select master_id,table_name,operation,header,type,configuration,column_name from dynamic_form_table_master m join dynamic_form_column_master c
 on m.id=c.master_id where m.has_key ='#" . $header->param->haskey . "' and m.section_id = ".$header->param->section_id." and m.dep_id = ".$header->param->dep_id." and m.status=1 and c.status=1";
            $resultObject = $this->db->query($sql)->result();
            $response["query"] = $this->db->last_query();
            if (count($resultObject) > 0) {
                $response["status"] = 200;
                $response["body"] = $resultObject;
            } else {
                $response["status"] = 201;
                $response["body"] = "Not Found";
            }
        } else {
            $response["status"] = 201;
            $response["body"] = "Something went wrong";
        }
        echo json_encode($response);
    }

    public function updateDynamicFormTransaction()
    {
        $header = $this->is_parameter(array("tableName", "data","section_id","dep_id","haskey"));

        if ($header->status) {

            $tableName = $header->param->tableName;
            $data = json_decode($header->param->data);
            $section_id = $header->param->section_id;
            $hash_key = "#".$header->param->haskey;
            $dept_id = $header->param->dep_id;
            $getConfiguartiondataResult=$this->getConfiguartiondata($section_id,$hash_key,$dept_id);
            if($getConfiguartiondataResult == false){
                $response['status']=201;
                $response['body']="Something Went Wrong";
                echo json_encode($response);
                exit;
            }
            $getConfiguartiondata=$getConfiguartiondataResult[0];

            $operation=$getConfiguartiondata->operation;
            $operation_table=$getConfiguartiondata->operation_table;
            if($operation == 1){
                $InsertBatchData = array();
                foreach ($data as $object) {
                    if (!is_null($object)) {
                        $isAll = false;
                        foreach ($getConfiguartiondataResult as $row) {
                            if ($row->operation_column != null && $row->operation_column !== "") {
                                if (property_exists($object, $row->operation_column)) {
                                    $isAll = true;
                                } else {
                                    $isAll = false;
                                    break;
                                }
                            }
                        }
                        if ($isAll) {
                            array_push($InsertBatchData, (array)$object);
                        }
                    }
                }
                $result=$this->db->insert_batch($operation_table, $InsertBatchData);
            }else{
                $key = $getConfiguartiondata->update_where_columns;
                $updateBatchData = array();
                foreach ($data as $object) {
                    if (!is_null($object))
                        array_push($updateBatchData, (array)$object);
                }
                $result=$this->db->update_batch($operation_table, $updateBatchData, $key);
            }
            if ($result==true) {
                $response["status"] = 200;
                $response["body"] = "Save Changes";
            } else {
                $response["status"] = 201;
                $response["body"] = "Failed to save change";
            }
        } else {
            $response["status"] = 201;
            $response["body"] = "something went wrong";
        }
        echo json_encode($response);

    }

    public function getConfiguartiondata($section_id,$hash_key,$dept_id){
        $query=$this->db->query("select master_id,table_name,operation,header,type,configuration,column_name,operation_column,update_where_columns,operation_table from dynamic_form_table_master m join dynamic_form_column_master c
 on m.id=c.master_id where section_id=".$section_id." AND has_key='".$hash_key."' AND dep_id=".$dept_id);
        if($this->db->affected_rows() > 0){
            $result=$query->result();
            return $result;

        }else{
            return false;
        }
    }

    public function getDynamicFormData()
    {
        $header = $this->is_parameter(array("section_id","dep_id","haskey","queryParam"));

        if($header->status){
            $sql = "select master_id,table_name,operation,header,type,configuration,column_name,operation_column,where_condition,fetch_query_columns,where_condition from dynamic_form_table_master m join dynamic_form_column_master c
 on m.id=c.master_id where m.has_key ='#" . $header->param->haskey . "' and m.section_id = ".$header->param->section_id." and m.dep_id = ".$header->param->dep_id." and m.status=1 and c.status=1";
            $resultObject = $this->db->query($sql)->result();
            $qParam =(array)json_decode(base64_decode($header->param->queryParam));

            if(count($resultObject)>0){
                $select=array();
                $tableName="";
                $where_condition="";
                foreach ($resultObject as $item){
                    if($item->fetch_query_columns !=="" && $item->fetch_query_columns !==null){
                        $select=explode(",",$item->fetch_query_columns);

                    }else{
                        array_push($select,$item->column_name);
                    }

                    $tableName=$item->table_name;
                    $where_condition=$item->where_condition;
                }
                if($tableName!==""){
                    $this->db->select($select);
                    $where=array();
                    if($where_condition!=="" && $where_condition !=null){
                        $conditions=explode(",",$where_condition);
                        if(count($conditions)>0){
                            foreach ($conditions as $cont){
                                $cond = explode("=",$cont);
                                if(count($cond)>0){
                                    if(substr(trim($cond[1]), 0, strlen('#'))!=='#'){
                                        $where[$cond[0]]=$cond[1];
                                    }else{
                                        $propName=str_replace("#","",$cond[1]);
                                        if(array_key_exists(trim($propName),$qParam)){
                                            $where[$cond[0]]=$qParam[$propName];
                                        }
                                    }

                                }
                            }
                        }
                    }

                    $this->db->where($where);
                    $data=$this->db->get($tableName)->result();
                    $response["query"]=$this->db->last_query();
                    $response["status"]=200;
                    $response["body"]=$data;
                }else{
                    $response["status"]=201;
                    $response["body"]=array();
                }
            }else{
                $response["status"]=201;
                $response["body"]=array();
            }
        }else{
            $response["status"]=201;
            $response["body"]="something went wrong";
        }
        echo json_encode($response);

    }

    public function getColumnOptions(){
        $header = $this->is_parameter(array("conf"));

        if($header->status){

            $conf = $header->param->conf;

            $segment=explode("||",$conf);

            if(count($segment)>0){
                $object =new stdClass();
                var_dump($segment);
                foreach ($segment as $seg){

                    if(strpos($seg,"primaryTable")){
                        echo "primary";
                        $sec=explode("=",$seg);
                        $object->$seg[0]=$sec[1];
                    }
                    if(strpos($seg,"keyColumn")){
                        $sec=explode("=",$seg);
                        $object->$seg[0]=$sec[1];
                    }
                    if(strpos($seg,"valueColumn")){
                        $sec=explode("=",$seg);
                        $object->$seg[0]=$sec[1];
                    }
                    if(strpos($seg,"WhereColumn")){
                        $sec=explode(",",$seg);
                        foreach ($sec as $section){
                            $condition=explode("@",$section);
                            if(count($condition)==2){
                                $whereColumn=explode("=",$condition[0]);
                                $whereValue=explode("=",$condition[1]);
                                $object->$whereColumn[1]=$whereValue[1];
                            }
                        }
                    }
                }
                var_dump($object);
            }


        }
    }

    public function GetDataColumnsForConfig(){
        extract($_POST);
        $option_fetch="<option value=''>Select Column</option>";
        $option_fetch .="<option value=''>None</option>";
        $option_fetch1="<option value=''>Select Column</option>";
        $option_fetch1 .="<option value=''>None</option>";
        $fetch_query="select ".$dynamicselectColumns." from ".$fecth_table_name;
        $option_fetch .=$this->GetColumnsfromQuery($fetch_query);

        $fields2 = $this->db->field_data($Insert_table_name);
        foreach ($fields2 as $f1){
            $option_fetch1 .="<option value='".$f1->name."'>".$f1->name."</option>";
        }

        $response['fecth_columns']=$option_fetch;
        $response['insert_columns']=$option_fetch1;
        $response['status']=200;
        echo json_encode($response);
    }

    public function GetColumnsfromQuery($fetch_query){
        //$fetch_query = substr($fetch_query, 0, strpos($fetch_query, "where"));
        $query=$this->db->query($fetch_query);
        $opt="";
        if($this->db->affected_rows() > 0){
            $result=$query->result();
            //var_dump($result);
            foreach ($result[0] as $key =>$r){
                $opt .="<option value='".$key."'>".$key."</option>";
            }
            return $opt;
        }else{
            return $opt;
        }

    }
}

<?php

require_once 'MasterModel.php';

class ReportMakerModel extends MasterModel
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

	public function addReportTemplateHtml($report_id,$insert_data,$isRequired,$queryParamenterArray)
	{
		try {
			$this->db->trans_start();
			$resultObject = new stdClass();
			
					if ($report_id == null) {
							if($this->db->insert("reportMakerTable",$insert_data))
							{
								$report_id1 = $this->db->insert_id();
								$resultObject->report_id = $report_id1;
								$resultObject->status = true;
								
								if($queryParamenterArray != false){
									foreach($queryParamenterArray as $data){
										$data['report_id']=$report_id1;
										$insert=$this->db->insert("reportMakerQuery",$data);
									}
								}
							}
							else
							{
								$resultObject->status = false;
							}
						}
						else
						{
							if($this->db->set($insert_data)->where("id", $report_id)->update("reportMakerTable"))
							{
								$resultObject->report_id = $report_id;
								$resultObject->status = true;
									if($queryParamenterArray!=false)
								{
									foreach ($queryParamenterArray as $data) {

										// print_r($data);exit();
										$insert_re=array('table_name'=>$data['table_name'],
														'column_field'=>$data['column_field'],
														'where_condition'=>$data['where_condition'],
                                            'query'=>$data['query']);
										$where_re=array('hash_key'=>$data['hash_key'],
														'ans_type'=>$data['ans_type'],
													    'report_id'=>$report_id);
										$select_data=$this->db->select("id")->where($where_re)->get('reportMakerQuery')->num_rows();
										if($select_data>0)
										{
											$this->db->set($insert_re)->where($where_re)->update("reportMakerQuery");
										}
										else
										{
											$insert_re1=array('table_name'=>$data['table_name'],
														'column_field'=>$data['column_field'],
														'where_condition'=>$data['where_condition'],
														'hash_key'=>$data['hash_key'],
														'ans_type'=>$data['ans_type'],
                                                        'query'=>$data['query'],
													    'report_id'=>$report_id
													);
											$insert2=$this->db->insert("reportMakerQuery",$insert_re1);
										}
									}
								}
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

	public function fetchReportMakers()
	{
		$sql="select * from reportMakerTable where status=1 order by id desc";
		return $this->_rawQuery($sql);
	}
	public function get_HtmlReportMaker($report_id)
	{
        $query=$this->db->query('select sm.*,(select group_concat(om.hash_key,"||",om.ans_type,"||",om.table_name,"||",om.column_field,"||",om.where_condition,"||",(case when om.query is null then " " else om.query end) SEPARATOR  "@")
			from reportMakerQuery om where om.report_id=sm.id and om.status=1) as opt from reportMakerTable 
			sm where sm.id='.$report_id.'');
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function get_reportquerydata($value1,$report_id,$ans_type)
	{
		$query = $this->db->query("select * from reportMakerQuery where report_id=".$report_id." and hash_key='".$value1."' and ans_type=".$ans_type." order by id desc");
		if ($this->db->affected_rows() > 0) {
			$query_data=$query->row();
			
			return $query_data;
		} else {
			return false;
		}
	}
	public function deleteReportmaker($report_id)
	{
		return $this->_update("reportMakerTable", array("status" => 0), array("id" => $report_id));
	}
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'MasterModel.php';

class HtmlDepartmentModel extends MasterModel
{

// fetch table data
	public function getTableData($where)
	{
		return $this->_select("html_departments_master dm", $where, array("dm.*", "(SELECT cm.name from company_master cm where cm.id=dm.company_id) as company_name "), false);
	}

	public function getSelectCompaniesData($tableName)
	{
		return $this->_select($tableName, array("status" => 1), "*", false);
	}

	public function selectDataById($tableName, $where)
	{
		$query = "select * from " . $tableName . " " . $where;
		return parent::_rawQuery($query);
	}

	public function selectCompanyWithDepartment($where)
	{
		return $this->_select("branch_master cm", $where, array("cm.*", "(select count(dm.id) from html_departments_master dm where dm.company_id=cm.id) as departments"), false);
	}

	public function addForm($tableName, $formData, $type = null)
	{
		try {
			$this->db->trans_start();
			if ($type != null) {
				$this->db->insert_batch($tableName, $formData);
			} else {
				$this->db->insert($tableName, $formData);
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('info', "insert form Transaction Rollback");
				$result = FALSE;
			} else {
				$this->db->trans_commit();
				log_message('info', "insert form Transaction Commited");
				$result = TRUE;
			}
			$this->db->trans_complete();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			$result = FALSE;
		}
		return $result;
	}

	public function updateForm($tableName, $formData, $where)
	{
		try {
			$this->db->trans_start();
			$this->db->set($formData)->where($where)->update($tableName);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('info', "update form Transaction Rollback");
				$result = FALSE;
			} else {
				$this->db->trans_commit();
				log_message('info', "update form Transaction Commited");
				$result = TRUE;
			}
			$this->db->trans_complete();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			$result = FALSE;
		}
		return $result;
	}






}


<?php


class MasterModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}

	/**
	 * @param $sql query which you want to execute
	 * @param bool $bindParam default false or array
	 * @return stdClass witch properties of totalCount and data of query result
	 */
	function _rawQuery($sql, $bindParam = FALSE,$type=1)
	{
		$resultObject = new stdClass();
		try {
			$query = $this->db->query($sql, $bindParam);

			if($type!=1){

				 if($type==2)
				 {
				 	$result=$this->db->affected_rows();
				 }
				 else
				 {
				 	$result = $query->result_array();
				 }
			}else
			{
				$result = $query->result();
			}

			if($type==2)
			{
				$resultObject->totalCount = 1;
				$resultObject->data = $result;
			}
			else
			{
				if (count($result) > 0) {
					$resultObject->totalCount = count($result);
					$resultObject->data = $result;
				} else {
					$resultObject->totalCount = 0;
					$resultObject->data = array();
				}
			}

			$resultObject->last_query = $this->db->last_query();
		} catch (Exception $ex) {
			$resultObject->totalCount = 0;
			$resultObject->data = array();
		}
		return $resultObject;
	}

	/**
	 * @param $table String table name
	 * @param $data array values
	 * @return stdClass object of status and last insert id
	 */
	function _insert($table, $data)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			$this->db->insert($table, $data);
			$resultObject->inserted_id = $this->db->insert_id();
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$resultObject->status = FALSE;
			} else {
				$this->db->trans_commit();
				$resultObject->status = TRUE;
			}
			$this->db->trans_complete();
			$resultObject->last_query = $this->db->last_query();
		} catch (Exception $ex) {
			$resultObject->status = FALSE;
			$this->db->trans_rollback();
		}
		return $resultObject;
	}

	/**
	 * @param $table String the table name
	 * @param $data  array you want to update
	 * @param $where array where update record
	 * @return stdClass object with property of status
	 */
	function _update($table, $data, $where)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			$this->db->set($data)->where($where)->update($table);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$resultObject->status = FALSE;
			} else {
				$this->db->trans_commit();
				$resultObject->status = TRUE;
			}
			$this->db->trans_complete();
			$resultObject->last_query = $this->db->last_query();
		} catch (Exception $ex) {
			$resultObject->status = FALSE;
			$this->db->trans_rollback();
		}
		return $resultObject;
	}

	/**
	 * @param $table String the table name
	 * @param $data  array you want to update including where column name
	 * @param $key String where column name
	 * @return stdClass object with property of status
	 */
	function _updateBatch($table, $data, $key)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			$this->db->update_batch($table, $data, $key);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$resultObject->status = FALSE;
			} else {
				$this->db->trans_commit();
				$resultObject->status = TRUE;
			}
			$this->db->trans_complete();
			$resultObject->last_query = $this->db->last_query();
		} catch (Exception $ex) {
			$resultObject->status = FALSE;
			$this->db->trans_rollback();
		}
		return $resultObject;
	}

	/**
	 * @param $table String the table name
	 * @param $data  array you want to update including where column name
	 * @param $key String where column name
	 * @return stdClass object with property of status
	 */
	function _insertBatch($table, $data)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			$this->db->insert_batch($table, $data);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$resultObject->status = FALSE;
			} else {
				$this->db->trans_commit();
				$resultObject->status = TRUE;
			}
			$this->db->trans_complete();
			$resultObject->last_query = $this->db->last_query();
		} catch (Exception $ex) {
			$resultObject->status = FALSE;
			$this->db->trans_rollback();
		}
		return $resultObject;
	}


	/**
	 * @param $table String the table name
	 * @param $where array where delete record
	 * @return stdClass object with property of status
	 */
	function _delete($table, $where)
	{
		$resultObject = new stdClass();
		try {
			$this->db->trans_start();
			$this->db->where($where)->delete($table);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$resultObject->status = FALSE;
			} else {
				$this->db->trans_commit();
				$resultObject->status = TRUE;
			}
			$this->db->trans_complete();
			$resultObject->last_query = $this->db->last_query();
		} catch (Exception $ex) {
			$resultObject->status = FALSE;
			$this->db->trans_rollback();
		}
		return $resultObject;
	}


	/**
	 * @param $tableName String table name
	 * @param $where array of condition
	 * @param $select array|String of columns
	 * @param $type true for single row and false for multiple rows
	 * @param null $group_by group by value
	 * @return stdClass object with property of totalCount and data
	 */
	function _select($tableName, $where, $select = "*", $type = true, $group_by = null, $like = null,$order_by=null,$key=null,$custom_where = null)
	{

		$resultObject = new stdClass();
		try {
			if ($type) {
				$this->db->select($select)->where($where);
				if($custom_where != null){
					$this->db->where($custom_where);
				}
				if ($group_by != null) {
					$this->db->group_by($group_by);
				}
				if ($order_by != null) {
					$this->db->order_by($order_by,$key);
				}
				$result = $this->db->get($tableName)->row();

				if ($result != null) {
					$resultObject->totalCount = 1;
					$resultObject->data = $result;
				} else {
					$resultObject->totalCount = 0;
					$resultObject->data = $result;
				}
			} else {
				if ($like != null) {
					if (!empty($like)) {
						$this->db->select($select)->where($where)->like($like);
					}
				} else {
					$this->db->select($select)->where($where);
				}
				if($custom_where != null){
					$this->db->where($custom_where);
				}

				if ($group_by != null) {
					$this->db->group_by($group_by);
				}
				if ($order_by != null) {
					$this->db->order_by($order_by,$key);
				}
				$result = $this->db->get($tableName)->result();

				$resultObject->totalCount = count($result);
				if (count($result) > 0) {
					$resultObject->data = $result;
				} else {
					$resultObject->data = array();
				}
			}
			$resultObject->last_query = $this->db->last_query();
		} catch (Exception $e) {
			$resultObject->totalCount = 0;
			$resultObject->data = null;
		}
		return $resultObject;
	}

	public function getRows($postData, $where, $select, $table, $column_search, $column_order, $order, $group_by = null, $where_or = null, $having = null, $customWhereCondition = null,$filterWhere=null)
	{

		$this->_get_datatables_query($postData, $table, $column_search, $column_order, $order, $group_by);
		if ($having != null) {
			if (!empty($having)) {

					foreach ($having as $condition){
						$this->db->having($condition);
					}
			}

		}
		if ($postData['length'] != -1) {
			$this->db->limit($postData['length'], $postData['start']);
		}
//		$this->where = $where;


		if ($where_or != null) {
			$this->db->where($where);
			$this->db->or_where($where_or);
		} else {
			$this->db->where($where);
			if ($customWhereCondition != null)
				$this->db->where($customWhereCondition);
		}
		if($filterWhere!=null)
		{
			if(!empty($filterWhere))
			{
				foreach ($filterWhere as $frow)
				{
					$this->db->where($frow);
				}
			}
		}

		$query = $this->db->select($select)->get();

		//echo $this->db->last_query();
		return $query->result();
	}

	/*
	 * Count all records
	 */

	public function countAll($table, $where)
	{
		$this->db->where($where)->from($table);
		return $this->db->count_all_results();
	}

	public function countFiltered($postData, $table, $where, $column_search, $column_order, $order,$customWhereCondition=null,$filterWhere=null,$select=null)
	{
		$this->_get_datatables_query($postData, $table, $column_search, $column_order, $order);

		if($customWhereCondition !=null){
			$this->db->where($where);
		}
		if($filterWhere!=null)
		{
			if(!empty($filterWhere))
			{
				foreach ($filterWhere as $frow)
				{
					$this->db->having($frow);
				}
			}
		}
		if($select !=null){
			$this->db->select($select);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}

	/*
	 * Perform the SQL queries needed for an server-side processing requested
	 * @param $_POST filter data based on the posted parameters
	 */

	public function _get_datatables_query($postData, $table, $column_search, $column_order, $order, $group_by = null)
	{

		$this->db->from($table);
		$i = 0;
		// loop searchable columns
		foreach ($column_search as $item) {
			// if datatable send POST for search
			if ($postData['search']['value']) {
				// first loop
				if ($i === 0) {
					// open bracket
					$this->db->group_start();
					$this->db->like($item, $postData['search']['value']);
				} else {
					$this->db->or_like($item, $postData['search']['value']);
				}
				// last loop
				if (count($column_search) - 1 == $i) {
					// close bracket
					$this->db->group_end();
				}
			}
			$i++;
		}
		if ($group_by != null) {
			$this->db->group_by($group_by);
		}
		if (isset($postData['order'])) {
			$column = (int)$postData['order']['0']['column'];
			if (count($column_order) > $column)
				$this->db->order_by($column_order[$column], $postData['order']['0']['dir']);
		} else if (count($order) > 0) {
			foreach($order as $ordekey => $orderValue)
			{
				$this->db->order_by($ordekey, $orderValue);
			}

		}
	}

	public function unitConverter($weight,$type){
		if($type == 1){
			return round($weight * 1000000,2);
		}else if ($type == 2){
			return round($weight * 1000,2);
		}else if ($type == 3){
			return $weight;
		}else if ($type == 4){
			return round($weight * 1000,2);
		}else if ($type == 5){
			return $weight;
		}
	}

	public function UnitRecon($weight,$type){
		if($type == 1){
			return round($weight / 1000000,2);
		}else if ($type == 2){
			return round($weight / 1000,2);
		}else if ($type == 3){
			return $weight;
		}else if ($type == 4){
			return round($weight / 1000,2);
		}else if ($type == 5){
			return round($weight,2);
		}
	}

}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class WordReportController
 * @property MasterModel MasterModel
 */
class Pages extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('MasterModel');
	}


	public function index()
	{
		$this->load->view('pages/product', array('title' => 'Product'));
	}

	public function getProductDetails()
	{

		$group_id = $this->input->post('group_id');
		$page_id = $this->input->post('page_id');
		$select = array("*");
		$where = array('id' => $group_id);

		$tableName = 'child_group_master';
		$order = 'id';
		$key = 'desc';
		$column_order = array('');
		$column_search = array("");
		$edData = $this->MasterModel->_select($tableName, $where, array('*'), true, null, null, $order, $key);

		$filterCount = $edData->totalCount;
		$totalCount = $edData->totalCount;
		if ($edData->totalCount > 0) {
			$tableRows = array();

			$pData = $edData->data;

			$data_arr = array();
			$pages = json_decode($pData->code);
			foreach ($pages as $p) {
				foreach ($p as $d) {
					if (property_exists($d[0], 'dataset')) {
						foreach ($d[0]->dataset as $datasset) {
							$obj = new stdClass();
							foreach ($datasset as $val) {
								foreach ($val as $k => $v) {
									$obj->$k = $v;
								}
							}
							array_push($data_arr,$obj);
						}
					}
				}
			}
			foreach ($data_arr as $val) {
				$tableRows[] = array(
					$val->product_id->value,
					$val->Input7->value,
					$val->Input2->value,
					$val->Input4->value,
					$val->product_id->value
				);
			}
			$results = array(
				"draw" => 1,
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $tableRows,
			);
		} else {
			$results = array(
				"draw" => 1,
				"recordsTotal" => $totalCount,
				"recordsFiltered" => $filterCount,
				"data" => $edData,
			);
		}
		echo json_encode($results);
	}

}


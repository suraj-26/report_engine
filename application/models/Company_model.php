<?php
require_once 'MasterModel.php';


/**
 * @property  TemplateModel TemplateModel
 */
class Company_model extends MasterModel
{


	function saveCompany($company, $isBed, $isMedicine, $company_id = null)
	{
		$this->load->model("TemplateModel");
		$roomTableName = null;
		$bedTableName = null;
		$bedHistoryTableName = null;
		try {
			$this->db->trans_start();

			if ($company_id == null) {
				$this->db->insert("company_master", $company);
				$company_id = $this->db->insert_id();
				if ($isBed == 1) {
					$bedManagement = $this->getBedManagementTables($company_id);
					$this->db->set(
						array("hospital_room_table" => $bedManagement->roomTable,
							"hospital_bed_table" =>  $bedManagement->bedTable,
							"patient_bed_history_table" =>  $bedManagement->bedHistoryTable))
						->where(array("id" => $company_id))
						->update("company_master");
				}
				if($isMedicine ==1){
					$bedManagement = $this->getMedicineManagementTables($company_id);
					$this->db->set(
						array("patient_medicine_table" => $bedManagement->medicineTable,
							"patient_medicine_history_table" =>  $bedManagement->medicineHistoryTable,
							"prescription_master_table"=>$bedManagement->prescriptionTable))
						->where(array("id" => $company_id))
						->update("company_master");
				}

				$roomTableResult=$this->createBilllingTransactionAutoTable();
				$billingTable = "com_" . $company_id . "_billing_transaction";
				if(!$this->TemplateModel->tableExist($billingTable)) {
					$this->TemplateModel->createTemplateTable($billingTable, $roomTableResult->fields);
				}
				$this->db->set(
					array("billing_transaction" => $billingTable))
					->where(array("id" => $company_id))
					->update("company_master");
			} else {
				$this->db->set($company)->where('id',$company_id)->update("company_master");
				if ($isBed == 1) {
					$bedManagement = $this->getBedManagementTables($company_id);
					$this->db->set(
						array("hospital_room_table" => $bedManagement->roomTable,
							"hospital_bed_table" =>  $bedManagement->bedTable,
							"patient_bed_history_table" =>  $bedManagement->bedHistoryTable))
						->where(array("id" => $company_id))
						->update("company_master");
				}
				if($isMedicine ==1){
					$bedManagement = $this->getMedicineManagementTables($company_id);
					$this->db->set(
						array("patient_medicine_table" => $bedManagement->medicineTable,
							"patient_medicine_history_table" =>  $bedManagement->medicineHistoryTable,
							"prescription_master_table"=>$bedManagement->prescriptionTable))
						->where(array("id" => $company_id))
						->update("company_master");
				}

				$roomTableResult=$this->createBilllingTransactionAutoTable();
				$billingTable = "com_" . $company_id . "_billing_transaction";
				if(!$this->TemplateModel->tableExist($billingTable)) {
					$this->TemplateModel->createTemplateTable($billingTable, $roomTableResult->fields);

				}
				$this->db->set(
					array("billing_transaction" => $billingTable))
					->where(array("id" => $company_id))
					->update("company_master");
			}

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

		} catch (Exception $exception) {
			$result = FALSE;
		}
		return $result;
	}

	public function  getBedManagementTables($company_id){
		$roomTableResult = $this->createCompanyHospitalRoomAutoTable();
		$bedTableResult = $this->createCompanyHospitalBedAutoTable();
		$bedHistoryTableResult = $this->createCompanyHospitalBedHistoryAutoTable();

		$resultObject = new stdClass();
		$resultObject->roomTable = "com_" . $company_id . "_room";
		if(!$this->TemplateModel->tableExist($resultObject->roomTable)) {
			$this->TemplateModel->createTemplateTable($resultObject->roomTable, $roomTableResult->fields);
		}
		$resultObject->bedTable = "com_" . $company_id . "_bed";
		if(!$this->TemplateModel->tableExist($resultObject->bedTable)) {
			$this->TemplateModel->createTemplateTable($resultObject->bedTable, $bedTableResult->fields);
		}
		$resultObject->bedHistoryTable = "com_" . $company_id . "_bed_history";
		if(!$this->TemplateModel->tableExist($resultObject->bedHistoryTable)) {
			$this->TemplateModel->createTemplateTable($resultObject->bedHistoryTable, $bedHistoryTableResult->fields);
		}
		return $resultObject;
	}

	public function getMedicineManagementTables($company_id){
		$doseDetailsTableResult = $this->createCompanyDoseDetailsAutoTable();
		$doseHistoryTableResult = $this->createCompanyDoseHistoryAutoTable();
		$prescriptionTableResult = $this->createCompanyPrescriptionTable();
		$resultObject = new stdClass();
		$resultObject->medicineTable = "com_" . $company_id . "_medicine";
		if(!$this->TemplateModel->tableExist($resultObject->medicineTable)){
			$this->TemplateModel->createTemplateTable($resultObject->medicineTable , $doseDetailsTableResult->fields);
		}

		$resultObject->medicineHistoryTable = "com_" . $company_id . "_medicine_history";
		if(!$this->TemplateModel->tableExist($resultObject->medicineHistoryTable)){
			$this->TemplateModel->createTemplateTable($resultObject->medicineHistoryTable , $doseHistoryTableResult->fields);
		}

		$resultObject->prescriptionTable = "com_" . $company_id . "_prescriptionTable";
		if(!$this->TemplateModel->tableExist($resultObject->prescriptionTable)){
			$this->TemplateModel->createTemplateTable($resultObject->prescriptionTable , $prescriptionTableResult->fields);
		}

		return $resultObject;
	}

	public function createCompanyHospitalRoomAutoTable()
	{
		$fields = array(
			'room_no' => array(
				'type' => 'VARCHAR',
				'constraint' => '45'
				// 'unique' => TRUE,
			),
			'ward_no' => array(
				'type' => 'VARCHAR',
				'constraint' => '45'
				// 'unique' => TRUE,
			),
			'v_facility' => array(
				'type' => 'VARCHAR',
				'constraint' => '100'
				// 'unique' => TRUE,
			),
			'branch_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
			'create_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'modify_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'modify_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'status' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '1',
			),
			'bed_id' => array(
				'type' => 'INT',
				'constraint' => '11',

			),
			'roomid' => array(
				'type' => 'INT',
				'constraint' => '11',

			),

		);
		$roomTable = new stdClass();
		$roomTable->fields = $fields;
		return $roomTable;
	}

	public function createCompanyHospitalBedAutoTable()
	{
		$fields = array(
			'Id_room' => array(
				'type' => 'VARCHAR',
				'constraint' => '45'
				// 'unique' => TRUE,
			),
			'bed_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '45'
				// 'unique' => TRUE,
			),
			'status' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'branch_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
			'create_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'modify_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'modify_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'service_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'objective' => array(
				'type' => 'TEXT',
				// 'unique' => TRUE,
			),


		);
		$bedTable = new stdClass();
		$bedTable->fields = $fields;
		return $bedTable;
	}

	public function createCompanyHospitalBedHistoryAutoTable()
	{
		$fields = array(
			'patient_id' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'branch_id' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'inTime' => array(
				'type' => 'DATETIME'
			),
			'bed_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '45'
				// 'unique' => TRUE,
			),
			'room_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '45'
				// 'unique' => TRUE,
			),
			'create_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'modify_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'modify_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'service_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),


		);
		$bedHistoryTable = new stdClass();
		$bedHistoryTable->fields = $fields;
		return $bedHistoryTable;
	}

	public function createCompanyDoseDetailsAutoTable()
	{
		$fields = array(
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255'
				// 'unique' => TRUE,
			),
			'start_date' => array(
				'type' => 'DATETIME',
				'null' => TRUE

			),
			'end_date' => array(
				'type' => 'DATETIME',
				'null' => TRUE
			),
			'status' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '1'
				// 'unique' => TRUE,
			),
			'p_id' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'total_iteration' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'remark' => array(
				'type' => 'TEXT'
			),
			'create_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'modify_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'modify_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),


		);
		$doseDetailTable = new stdClass();
		$doseDetailTable->fields = $fields;
		return $doseDetailTable;
	}

	public function createCompanyDoseHistoryAutoTable()
	{
		$fields = array(
			'p_id' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'iteration_count' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'iteration_on' => array(
				'type' => 'DATE'

			),
			'status' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'does_details_id' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'iteration_time' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'modify_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'modify_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),


		);
		$doseHistoryTable = new stdClass();
		$doseHistoryTable->fields = $fields;
		return $doseHistoryTable;
	}

	public function createBilllingTransactionAutoTable()
	{
		$fields = array(
			'service_id' => array(
				'type' => 'INT',
				'constraint' => '11'
				// 'unique' => TRUE,
			),
			'unit' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '1',
			),
			'rate' => array(
				'type' => 'DOUBLE'
			),
			'total' => array(
				'type' => 'DOUBLE',
			),
			'create_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'service_desc' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
			'service_file' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'status' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '1',
			),
			'patient_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
			'date_service' => array(
				'type' => 'INT',
				'constraint' => '11',

			),

		);
		$billingTable = new stdClass();
		$billingTable->fields = $fields;
		return $billingTable;
	}

	public function createCompanyPrescriptionTable()
	{
		$fields = array(
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '255'
			),
			'medicine_id' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'per_date_schedule' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'status' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'remark' => array(
				'type' => 'VARCHAR',
				'constraint' => '255'
			),
			'create_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'create_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),
			'modify_on' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
			),
			'modify_by' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => TRUE,
			),


		);
		$doseHistoryTable = new stdClass();
		$doseHistoryTable->fields = $fields;
		return $doseHistoryTable;
	}
}

?>

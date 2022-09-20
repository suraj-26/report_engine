<?php
require_once 'HexaController.php';

use Dompdf\Dompdf;

/**
 * @property  LabReport LabReport
 */
class HtmlPageController extends HexaController
{


	/**
	 * HtmlFormTemplateController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('HtmlPageModel');
		$this->load->model('HtmlFormModel');

		$this->load->model('Global_model');
		date_default_timezone_set('Asia/Kolkata');
	}
	public function HtmlPageTemplate()
	{
		$this->load->view('HtmlPageTemplate/HtmlPageTemplate',array("title"=>"HTML Page Template"));
	}
	public function fetchAllSection()
	{
		$resultObject=$this->HtmlPageModel->fetchAllSections();
		if($resultObject->totalCount>0){
			$response["status"]=200;
			$response["body"]=$resultObject->data;
		}else{
			$response["status"]=201;
			$response["body"]="No Data Found";
		}
		echo  json_encode($response);
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('login', array('title' => 'Login'));
	}

	public function from_template()
	{
		$this->load->view("admin/templates/template_form",array("title"=>"Template"));
	}
	public function view_department()
	{
		$this->load->view("admin/department/view_departments",array("title"=>"Department"));
	}
	public function view_process($productID)
	{
		$this->load->view("Form_Show/process_configuration",array("title"=>"Department","productID"=>$productID));
	}
}

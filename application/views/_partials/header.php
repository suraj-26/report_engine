<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!isset($this->session->user_session)) {
	redirect('/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

	<title><?php echo $title; ?></title>
	<!-- add icon link -->
	<link rel="icon" href="<?= base_url()?>assets/images/cprompt_black.png"
		  type="image/x-icon" style="font-size:60px;">

	<!-- General CSS Files -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/izitoast/css/iziToast.min.css">
	<!-- CSS Libraries -->
	<?php  if ($this->uri->segment(1) == "viewForm") { ?>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css"
			  crossorigin="anonymous" referrerpolicy="no-referrer" />
			  
	<?php } ?>

	<?php  if ($this->uri->segment(1) == "product" || $this->uri->segment(1) == "view_product" || $this->uri->segment(1) == "ProductionSchedule"  ) { ?>

		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/8.3.2/handsontable.full.min.css" integrity="sha512-eUeyGbgtvJnVVAw4PdhvHPXux0s6vc5tn8jewf0dRNCWDlstQxCwoVMjv5IWJkCpBY1Zo+N+Gz+Fr84ODFWSog=="
			  crossorigin="anonymous" referrerpolicy="no-referrer" />
	<?php } ?>

	<?php  if ($this->uri->segment(1) == "materialSupply"  || $this->uri->segment(1) == "materialReceive"
			|| $this->uri->segment(1) == "materialReturned" ||  $this->uri->segment(1) == "vendormaterialSupply"
			||  $this->uri->segment(1) == "materialOrder" ||  $this->uri->segment(1) == "material_process") { ?>

		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">

	<?php } ?>
	<?php  if ($this->uri->segment(1) == "report_list") { ?>


<!--		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css">-->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/richText/rte_theme_default.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/richText/runtime/richtexteditor_content.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/richText/res/style.css">

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css"
			  crossorigin="anonymous" referrerpolicy="no-referrer" />
			  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">


	<?php } ?>
	<!-- Template CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
</head>

<style>
	.main-sidebar .sidebar-menu li a.has-dropdown:after {
		right:15px;
	}
	.main-sidebar{
		width: 275px!important;
	}

	</style>

<div>
	<?php if ($this->session->user_session) {
		$userSeession=(array)$this->session->user_session;
		if (count($userSeession) > 0) {
			foreach ($userSeession as $session_key => $session_value) {
				?>
				<input type="hidden" name="sess_<?php echo $session_key ?>"
					   id="sess_<?php echo $session_key ?>"
					   value="<?php echo $session_value ?>">
			<?php }
		}
	} ?>
</div>
<?php
$this->load->view('_partials/layout');
if($this->uri->segment(1) == "report_list" || $this->uri->segment(1)=="bmr_report_view")
{

}
else{
	$this->load->view('_partials/sidebar');
}
?>

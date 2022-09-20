<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- General JS Scripts -->
<script src="<?php echo base_url(); ?>assets/modules/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/popper.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/tooltip.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/stisla.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/izitoast/js/iziToast.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/jquery-validation/js/jquery.validate.min.js"
		type="text/javascript"></script>
		


<script src="<?php echo base_url(); ?>assets/js/custom.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/customValidation.js" type="text/javascript"></script>

<?php if ($this->uri->segment(2) == "view_process") { ?>
	<script src="<?= base_url() ?>assets/modules/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="<?= base_url() ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>
	<script src="<?= base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
<?php } ?>

<?php if ($this->uri->segment(2) == "form_template") { ?>
	<script src="<?= base_url() ?>assets/modules/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="<?= base_url() ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>
	<script src="<?= base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
	<script src="<?= base_url(); ?>assets/js/templates/templates.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "pageConfiguration") { ?>

	<script src="<?= base_url() ?>assets/modules/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="<?= base_url() ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>

	<script src="<?php echo base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/pageConfiguration/page-configuration.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/pageConfiguration/page-creation.js"></script>


<?php }
if ($this->uri->segment(1) == "HandsonConfiguration") { ?>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/datatables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/page/bootstrap-modal.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
			integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
			crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="<?php echo base_url(); ?>assets/js/HandsonTable/handsonConfiguaration.js"></script>

<?php }
if ($this->uri->segment(1) == "template_list") { ?>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/datatables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.6.0/src/loadingoverlay.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/pageConfiguration/template-list.js?version=<?= time(); ?>"></script>

<?php }
if ($this->uri->segment(1) == "viewForm" || $this->uri->segment(1) == "form_view"
		|| $this->uri->segment(1) == 'product' || $this->uri->segment(1) == 'view_product'
		|| $this->uri->segment(1) == 'ProductionSchedule' || $this->uri->segment(1) == 'material_process') { ?>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/datatables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
	

    <script src="<?= base_url() ?>assets/modules/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="<?= base_url() ?>assets/modules/jquery-ui/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/pageConfiguration/page-creation.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/HandsonTable/handsonConfiguaration.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js" crossorigin="anonymous"
			referrerpolicy="no-referrer"></script>

<?php }
if ($this->uri->segment(1) == "viewForm" || $this->uri->segment(1) == "form_view" || $this->uri->segment(1) == "view_product") { ?>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/section_form.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
<?php }
if ($this->uri->segment(1) == "product" || $this->uri->segment(1) == "materialOrder") { ?>
	<script src="<?php echo base_url(); ?>assets/js/pages/product.js"></script>
<?php }
if ($this->uri->segment(1) == "view_product" || $this->uri->segment(1) == "ProductionSchedule") { ?>
	<script src="<?php echo base_url(); ?>assets/js/pages/product_view.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/Material/material.js"></script>
<?php } ?>

<?php if ($this->uri->segment(2) == "view_departments") { ?>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/datatables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.6.0/src/loadingoverlay.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/department/department_view.js"></script>

<?php } ?>
<?php if ($this->uri->segment(1) == "materialSupply" || $this->uri->segment(1) == "materialReceive" || $this->uri->segment(1) == "materialReturned" ||  $this->uri->segment(1) == "vendormaterialSupply") { ?>

	<script src="<?= base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>

	<script src="<?= base_url() ?>assets/modules/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/Material/material.js"></script>

<?php } ?>

<?php
if($this->uri->segment(1) == "materialOrder"){ ?>
<script src="<?= base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
 <script src="<?= base_url() ?>assets/modules/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/datatables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
<?php }?>
<?php  if ($this->uri->segment(1) == "report_list") { ?>

<!--	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>-->
    <script src="<?php echo base_url(); ?>assets/modules/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/modules/richText/rte.js"></script>
	<script>RTE_DefaultConfig.url_base='<?php echo base_url(); ?>assets/modules/richText/'</script>
	<script type="text/javascript" src='<?php echo base_url(); ?>assets/modules/richText/all_plugins.js'></script>
	<script type="text/javascript" src='<?php echo base_url(); ?>assets/modules/richText/res/patch.js'></script>

	<script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js" crossorigin="anonymous"
			referrerpolicy="no-referrer"></script>
			
	<script src="<?= base_url(); ?>assets/modules/richText/wordReport.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/pageConfiguration/page-creation.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/pages/product.js"></script>

	
	
	
<?php } ?>
<?php  if ($this->uri->segment(1) == "bmr_report_view") { ?>
	<script src="<?= base_url(); ?>assets/modules/richText/wordReportCreation.js"></script>
	
<?php } ?>
<?php  if ($this->uri->segment(1) == "view_product") { ?>
	<script src="<?= base_url(); ?>assets/modules/richText/wordReportCreation.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == 'admin') { ?>
	<script type="text/javascript">
		var base_url = "<?php echo base_url(); ?>";
		var baseURL = "";
	</script>
<?php } else { ?>
	<script type="text/javascript">
		var base_url = "<?= base_url(); ?>";
		var baseURL = "<?= base_url(); ?>";
	</script>
<?php } ?>

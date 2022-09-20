<?php
defined('BASEPATH') or exit('No direct script access allowed');
//

$this->load->view('_partials/header');

?>
<style>
	.select2-container {
		width: 100% !important;
	}
	.container
	{
		padding: 20px 20px!important;
	}
	#templateForm74,#templateForm72
	{
		padding: 20px 10px!important;
	}
</style>
<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1 id="templateName">Create New Table</h1>

			<div id="indexFieldDiv">
				<?php foreach ($indexArray as $indexKey => $indexRow){ ?>
					<input type="hidden" name="<?php echo $indexKey ?>" id="<?php echo $indexKey ?>" value="<?php echo $indexRow ?>">
				<?php } ?>
			</div>
			<div style="margin-left: auto;">


			</div>

		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-md-12">
					<div class="card">

						<?php
						if ($this->uri->segment(2) == '167') { ?>
							<div class="row">
								<div class="col-12 px-5 py-2">
									<a class="btn btn-sm btn-primary float-right" style="color: white" href="<?=base_url()?>product/0" id="prodctPurchase" type="button">Create Product</a>
									<a class="btn btn-sm btn-primary mr-2" style="color: white;display: none" href="<?=base_url()?>viewForm/167" id="purchaseHome" type="button"><i class="fa fa-arrow-left"></i>&nbsp;Home</a>
								</div>
							</div>

							<?php } if ($this->uri->segment(2) == '177') { ?>
							<div class="row">
								<div class="col-12 px-5 py-2">
									<a class="btn btn-sm btn-primary mr-2" style="color: white;" href="<?=base_url()?>ProductionSchedule" id="purchaseHome" type="button"><i class="fa fa-arrow-left"></i>&nbsp;Home</a>
								</div>
							</div>

						<?php } if ($this->uri->segment(2) == '204') { ?>
							<div class="row">
								<div class="col-12 px-5 py-2">
									<button class="btn btn-sm btn-primary mr-2" onclick="addBMR()" style="color: white;float: right" id="purchaseHome" type="button">Create BMR</button>
								</div>
							</div>

						<?php } else if ($this->uri->segment(2) == '162') {
							$value = '';
							if($this->uri->segment(2) == '162'){
								$value = '167';
							}
							?>
							<div class="row">
								<div class="col-12 px-5 py-2">
									<a class="btn btn-sm btn-primary mr-2" style="color: white;"
									   href="<?= base_url() ?>viewForm/<?=$value?>" id="purchaseHome" type="button"><i
												class="fa fa-arrow-left"></i>&nbsp;Home</a>
								</div>
							</div>
						<?php } ?>
						<input type="hidden" name="template_id" id="template_id" value="<?php echo $template_id ?>">
						<?php if (isset($session)) {
							$id = $session->id;
							$userType = $session->user_type;
							$branch_id=$session->branch_id;
							$access_menus=explode(",",$session->access_menus);?>
							<?php if(isset($id)) {
								?>
								<input type="hidden" name="session_userID"
									   id="session_userID" value="<?= $id ?>">
							<?php } ?>
							<?php if(isset($userType)) {
								?>
								<input type="hidden" name="session_userType"
									   id="session_userType" value="<?= $userType ?>">
							<?php } ?>
							<?php if(isset($branch_id)) {
								?>
								<input type="hidden" name="session_branchId"
									   id="session_branchId" value="<?= $branch_id ?>">
							<?php } ?>
							<?php if(isset($session->access_menus)) {
								?>
								<input type="hidden" name="session_userAccess"
									   id="session_userAccess" value="<?= $session->access_menus ?>">
							<?php } ?>
							<?php if(isset($session->customer_id)) {
								?>
								<input type="hidden" name="session_customerId"
									   id="session_customerId" value="<?= $session->customer_id ?>">
							<?php } ?>
						<?php } ?>

						<div id="hiddenFields">
							<?php if (isset($queryParam)) {
								if (count($queryParam) > 0) {
									foreach ($queryParam as $queryParam_key => $queryParam_value) {
										?>
										<input type="hidden" name="<?php echo $queryParam_key ?>"
											   id="<?php echo $queryParam_key ?>"
											   value="<?php echo $queryParam_value ?>">
									<?php }
								}
							} ?>

							<?php foreach ($indexArray as $indexKey => $indexRow){ ?>
								<input type="hidden" name="<?php echo $indexKey ?>" id="<?php echo $indexKey ?>" value="<?php echo $indexRow ?>">
							<?php } ?>
						</div>
						<div id="finalFormat" data-page_container="parent"></div>


						<?php if ($this->uri->segment(2) == '130') {?>
								<input type="text" class="form-control" id="totalVal">
						<?php } ?>
					</div>

				</div>
			</div>
		</div>
	</section>
</div>

<!-- modal data -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">My Modal</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary mx-auto" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- modal data -->
<div class="modal fade" id="searchModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Search Modal</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="searchDataTable"></div>
			</div>

		</div>
	</div>
</div>

<!-- modal data -->
<div class="modal fade" id="templateModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="hiddenArea"></div>
				<div id="templateForm"></div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('pageConfiguration/inventoryFormModal');?>
<script> var baseURL = '<?=base_url()?>';</script>
<?php

$this->load->view('_partials/footer');
$this->load->view('HandsonTable/HandsonModal');

?>
<!-- custom script -->

</body>
</html>
<script type="text/javascript">
	$(document).ready(function () {

		ShowForm($("#template_id").val());
	});
</script>



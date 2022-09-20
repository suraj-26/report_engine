<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Templates</h1>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Template Creation</h4>
							<div class="card-header-action">
								<div class="row">
									<a href="<?php echo base_url() ?>pageConfiguration/0" class="btn btn-icon btn-primary" id="addTemplate"><i
											class="fas fa-plus"></i></a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="">
								<div class="">
									<table class="table" id="templateTable" class="display" style="width: 100% !important;">
									<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Date</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	var baseURL='<?php echo base_url() ?>';
</script>
<?php $this->load->view('_partials/footer'); ?>


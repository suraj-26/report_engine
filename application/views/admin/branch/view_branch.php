<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Branches Details</h1>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Branches Details</h4>
							<div class="card-header-action">
								<button class="btn btn-icon btn-primary" data-toggle="modal"
										data-target="#fire-modal-company" data-id="0" id="companyFormButton"><i
										class="fas fa-plus"></i></button>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-striped" id="companyTable">
									<thead>
									<tr>
										<td>Name</td>
										<td>Status</td>
										<td>Action</td>
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
	</section>
</div>

<!-- company modal  -->
<?php $this->load->view('admin/branch/branch_form'); ?>


<?php $this->load->view('_partials/footer'); ?>

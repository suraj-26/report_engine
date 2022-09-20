<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Department Details</h1>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Department Details</h4>
							<div class="card-header-action">
								<div class="row">
									<div class="form-group mx-2 my-0">
										<select class="form-control" id='allCompanies' onchange="loadDepartment(2,this.value)">

										</select>
									</div>
								<button class="btn btn-icon btn-primary" data-toggle="modal" data-target="#fire-modal-department" id="addDepartment"><i
											class="fas fa-plus"></i></button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<div class="dataTables_wrapper no-footer">
									<table class="table table-striped dataTable no-footer" id="departmentTable"
										   role="grid">
										<thead>
										<tr>
											<td>Name</td>
											<td>company</td>
											<td>Type</td>
											<td>status</td>
											<td>Action</td>
										</tr>
										</thead>										
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

<!-- company modal  -->
<?php $this->load->view('admin/HtmlFormTemplate/html_department_form'); ?>


<?php $this->load->view('_partials/footer'); ?>


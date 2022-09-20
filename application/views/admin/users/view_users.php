<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');


?>

<style>


</style>
<!-- Main Content -->
<div class="main-content main-content1">
	<section class="section">
		<div class="section-header" style="border-top: 2px solid #891635">
			<h1>Users Details</h1>
		</div>
		<div class="section-body">
			<div class="">
				<div class="">
					<div class="card">
						<div class="card-header">
							<h4></h4>
							<div class="card-header-action">
								<div class="row">
									<div class="form-group mx-2 my-0">
										<select class="form-control" name="userCompany" id="userCompany"
												onchange="getUsersTableData(2,this.value)">
											<!-- <option>Company 1</option>
											<option>Company 2</option>
											<option>Company 3</option> -->
										</select>
									</div>
									<button class="btn btn-icon btn-primary" data-toggle="modal"
											data-target="#fire-modal-users" id="userAdd"><i
												class="fas fa-plus"></i></button>
<!--									<button class="btn btn-icon btn-primary" data-toggle="modal"-->
<!--											data-target="#profile_management" id="profileadd">-->
<!--										<i class="fas fa-users"></i> <i class="fas fa-plus"></i></button>-->
									<?php if((int)$this->session->user_session->roles == 1){?>
									<button class="btn btn-icon btn-primary" data-toggle="modal"
											data-target="#profile_management" id="profileadd">
										<i class="fas fa-users"></i> <i class="fas fa-plus"></i></button>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<div class="dataTables_wrapper no-footer">
									<table class="table table-striped dataTable no-footer" id="usersTable"
										   role="grid">
										<thead>
										<tr>
											<td>ID</td>
											<td>Name</td>
											<td>Username</td>
											<td>Company</td>
											<td>Branch</td>
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
		</div>
	</section>
</div>
<div class="modal fade " id="access_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4>User Permission</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="accessdataform" name="accessdataform">
					<div id="per_data_div"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade " id="profile_management" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Profile Management</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body row">
				<hr>
				<div id="table_profile" class="col-md-6"></div>
				<div class="col-md-6">
				<form id="profile_form" name="profile_form">
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<label class="col-md-4">Profile Name :</label>
								<input type="text" class="form-control col-md-8" id="profile_name" name="profile_name"  placeholder="select profile">
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item">
											<a href="#permission"
											   class="nav-link active"
											   id="permission-tab"
											   data-toggle="tab" role="tab"
											>Permission</a>
										</li>
										<li class="nav-item">
											<a href="#roles"
											   class="nav-link "
											   id="permission-tab"
											   data-toggle="tab" role="tab"
											>Role</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active"
											 id="permission"
											 role="tabpanel">
											<div class="col-md-12" id="all_permission_div">

											</div>
										</div>
										<div class="tab-pane fade"
											 id="roles"
											 role="tabpanel">
											<div class="col-md-12" id="roleDepartment">

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<button class="btn btn-primary" style="float:right" type="button" onclick="save_profile()">Save
					</button>


				</form>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- company modal  -->
<?php $this->load->view('admin/users/user_form'); ?>


<?php $this->load->view('_partials/footer'); ?>


<?php

$branch_id = $this->session->user_session->branch_id;
$company_id = $this->session->user_session->company_id;
$role = $this->session->user_session->roles;

?>
<div class="modal fade " tabindex="-1" role="dialog" id="fire-modal-users"
	 aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">User Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>

			<div class="modal-body py-0">
				<div class="card my-0 shadow-none">
					<div class="card-body">
						<ul class="nav nav-tabs" id="usertab" role="tablist">
							<li class="nav-item">
								<a href="#normalUser"
								   class="nav-link active"
								   id="permission-tab1"
								   data-toggle="tab" role="tab"
								>User</a>
							</li>
							<li class="nav-item">
								<a href="#otheruser"
								   class="nav-link "
								   id="permission-tab2"
								   data-toggle="tab" role="tab"
								>Other Users</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active"
								 id="normalUser"
								 role="tabpanel">
								<form id="uploadUsers" method="post" data-form-valid="saveUser">
									<?php
									if ($role == 2) { ?>
										<input type="hidden" id="uAllCompanies" name="uAllCompanies"
											   value="<?php echo $company_id ?>">
										<input type="hidden" id="uAllBranches" name="uAllBranches"
											   value="<?php echo $branch_id ?>">
										<input type="hidden" id="role" name="role" value="<?php echo $role ?>">
									<?php } else { ?>
										<div class="form-group  py-0">
											<label>Select Company</label>
											<select class="form-control select2" id="uAllCompanies" name="uAllCompanies"
													onchange="getCompanyBranches(this.value)"
													data-valid="required"
													data-msg="Select Company name"
											>

											</select>
										</div>
										<div class="form-group  py-0">
											<label>Select Branch</label>
											<select class="form-control select2" id="uAllBranches" name="uAllBranches"
													data-valid="required"
													data-msg="Select Company name"
											>
												<option>No data</option>
											</select>
										</div>
									<?php }
									?>

									<div class="form-group my-0 py-0">
										<label>Select User Type</label>
										<select class="form-control" name="user_type" id="user_type" tabindex="1"
												data-valid="required"
												data-msg="Select User Type">
											<option selected disabled>Select Type</option>

										</select>
									</div>
									<div class="form-group  py-0">
										<label>Name</label>
										<input type="hidden" name="forward_user" id="forward_user">
										<input type="text" class="form-control" name="user_name" id="user_name"
											   data-valid="required"
											   data-msg="Enter name">
									</div>
									<div class="form-group  py-0">
										<label>User Name</label>
										<input type="text" class="form-control" name="user_email" id="user_email"
											   data-valid="required"
											   data-msg="Enter username">
									</div>
									<div class="form-group  py-0">
										<label>Password</label>
										<input type="password" class="form-control" name="password" id="password"
											   data-valid="required"
											   data-msg="Enter Password">
									</div>
									<div class="form-group  py-0">
										<label>Contact</label>
										<input type="text" class="form-control" name="user_contact" id="user_contact"
											   data-valid="number|minlength=10|maxlength=12"
											   data-msg="Enter Only number|10 digit number|mobile number should be between 10 to 12">
									</div>
									<button class="btn btn-primary mr-1" type="submit">Submit</button>
								</form>
							</div>
							<div class="tab-pane fade"
								 id="otheruser"
								 role="tabpanel">
								<form id="uploadUsers1" method="post" data-form-valid="saveUser1">
									<?php
									if ($role == 2) { ?>
										<input type="hidden" id="uAllCompanies1" name="uAllCompanies1"
											   value="<?php echo $company_id ?>">
										<input type="hidden" id="uAllBranches1" name="uAllBranches1"
											   value="<?php echo $branch_id ?>">

									<?php } else { ?>
										<div class="form-group  py-0">
											<label>Select Company</label>
											<select class="form-control select2" id="uAllCompanies1"
													name="uAllCompanies1" onchange="getCompanyBranches(this.value)"
													data-valid="required"
													data-msg="Select Company name">

											</select>
										</div>
										<div class="form-group  py-0">
											<label>Select Branch</label>
											<select class="form-control select2" id="uAllBranches1" name="uAllBranches1"
													data-valid="required"
													data-msg="Select Company name"
											>
												<option>No data</option>
											</select>
										</div>
									<?php }
									?>
									<div class="form-group my-0 py-0">
										<label>Select User Type</label>
										<select class="form-control" name="user_type1" id="user_type1" tabindex="1"
												data-valid="required"
												data-msg="Select User Type">
											<option selected disabled>Select Type</option>
											<option value="1">Radiology Collection</option>
											<option value="2">Pathelogy Collection</option>
											<option value="3">Supplier</option>

										</select>
									</div>
									<div class="form-group  py-0">
										<label>Name</label>
										<input type="hidden" name="forward_user1" id="forward_user1">
										<input type="text" class="form-control" name="user_name1" id="user_name1"
											   data-valid="required"
											   data-msg="Enter name">
									</div>
									<div class="form-group  py-0">
										<label>User Name</label>
										<input type="text" class="form-control" name="user_email1" id="user_email1"
											   data-valid="required"
											   data-msg="Enter username">
									</div>
									<div class="form-group  py-0">
										<label>Password</label>
										<input type="password" class="form-control" name="password1" id="password1"
											   data-valid="required"
											   data-msg="Enter Password">
									</div>
									<div class="form-group  py-0">
										<label>Contact</label>
										<input type="text" class="form-control" name="user_contact" id="user_contact"
											   data-valid="number|minlength=10|maxlength=12"
											   data-msg="Enter Only number|10 digit number|mobile number should be between 10 to 12">
									</div>
									<button class="btn btn-primary mr-1" type="submit">Submit</button>

								</form>
							</div>
						</div>

						<!--							<div class="form-group">-->
						<!--								<label class="d-block">Roles</label>-->
						<!--								<div id="roleDepartment">-->
						<!--								</div>-->
						<!--							</div>-->
					</div>
				</div>
			</div>
			<div class="modal-footer">

				<button class="btn btn-secondary" type="reset">Reset</button>
			</div>

		</div>
	</div>
</div>
<div class="modal fade " tabindex="-1" role="dialog" id="fire-modal-privileges">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header"><h5 class="modal-title">Privileges</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div class="badges">
					<span class="badge badge-secondary">Vital Monitoring</span>
					<span class="badge badge-secondary">Doctor Notes</span>
					<span class="badge badge-secondary">Case History</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="fire-modal-department"
	 aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Department Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<form id="uploadDepartment" method="post" data-form-valid="uploadDepartment">
				<div class="modal-body py-0">
					<div class="card my-0 shadow-none">
						<div class="card-body">
							<div class="form-group my-0 py-0">
								<label>Select Customer</label>
										<select class="form-control" name="dcompany_id" id="dcompany_id" tabindex="1"
											data-valid="required" 
											data-msg="Select Company name"
										   required autofocus>
										</select>
									</div>
							<div class="form-group my-0 py-0">
								<label>Name</label>
								<input type="hidden" name="forward_department" id="forward_department">
								<input type="text" class="form-control" id="department_name" name="department_name" data-valid="required" 
									data-msg="Please fill department name"
										   tabindex="2">
							</div>
							
							<button class="btn btn-link btn-sm" type="button" onclick="$('#txt_description_box').toggleClass('d-none')">
								add description of department
							</button>
							<div class="form-group my-0 py-0 d-none" id="txt_description_box">
								<label>Description</label>
								<textarea class="form-control" id="department_description" name="department_description"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary mr-1" type="submit">Submit</button>
					<button class="btn btn-secondary" type="reset">Reset</button>
				</div>
			</form>
		</div>
	</div>
</div>

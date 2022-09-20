<div class="modal fade" tabindex="-1" role="dialog" id="fire-modal-company"
	 aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Branche Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<form id="uploadCompany" method="post" data-form-valid="saveCompany" >
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
								<input type="hidden" name="forward_company" id="forward_company">
								<input type="text" class="form-control" name="company_name" id="company_name" data-valid="required"   data-msg="Enter Company name">
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

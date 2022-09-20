<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Doctor Details</h1>
		</div>
		<div class="section-body">
			<div class="row align-items-center">
				<div class="col-12 col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4>Doctor Details</h4>
							<div class="card-header-action">
								
							</div>
						</div>
						<div class="card-body">
							<div class="col-md-12">
								<form id="excelFileupload" method="post" onsubmit="return false">
									<input type="file" name="file_ex1" id="file_ex1" >
									<button class="btn btn-icon btn-primary" type="submit">Upload</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!--
		check javascript inn doctor-view-1.js
-->

<?php $this->load->view('_partials/footer'); ?>
<script type="text/javascript">
	$('#excelFileupload').validate({
		rules: {
			file_ex1: {
				required: true
			}
		},
		messages: {
			file_ex1: {
				required: "Select Excel File"
			}
		},
		errorElement: 'span',
		submitHandler: function (form) {
			var form_data = document.getElementById('excelFileupload');
			var Form_data = new FormData(form_data);
			// var form_data = new FormData(document.getElementById("patientForm"));
			// $.LoadingOverlay("show");
			$.ajax({
				type: "POST",
				url: baseURL + "add_Excel_file",
				dataType: "json",
				data: Form_data,
				contentType: false,
				processData: false,
				success: function (result) {
					if (result.status === 200) {

						app.successToast(result.body);

					} else {
						app.errorToast(result.body);
					}

					// $.LoadingOverlay("hide");

				}, error: function (error) {
					console.log('Logged ---> ', error);
					// $.LoadingOverlay("hide");
					app.errorToast('something went wrong');
				}
			});
		}
	});
</script>

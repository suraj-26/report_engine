<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" type="text/css">
<style>
	.custom-header {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		padding: 8px 0;
	}

	.main-content {
		padding-left: 0px !important;
	}

	.inputText {
		background-color: yellow !important;
	}

	.editorDiv {
		height: 70vh;
		overflow-y: auto;
	}

	#report_page_list .table:not(.table-sm):not(.table-md):not(.dataTable) td, #report_page_list .table:not(.table-sm):not(.table-md):not(.dataTable) th {
		padding: 0 25px;
		height: 30px;
		vertical-align: middle;
	}
</style>
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>BMR Report</h1>
			<div style="margin-left: auto">
				<button class="btn btn-sm btn-primary" type="button" id="printBtn" onclick="bmrReport()">Print</button>
			</div>
			<input type="hidden" name="report_id" id="report_id" value="<?php echo $report_id; ?>">
			<input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
			<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->session->user_session->id; ?>">
		</div>
		<div class="section-body">

			<div class="queryParamDiv">
				<input type="hidden" name="queryParameters" id="queryParameters" value="">
			</div>
			<div class="row">
				<div class="col-12 col-md-8 col-lg-8">
					<div class="card">
						<form id="saveReportPage">
							<div>
								<input type="hidden" id="page_id" name="page_id">
								<input type="hidden" id="page_input" name="page_input">
							</div>
							<div class="text-center"><h5><span id="pageName"></span></h5></div>
							<div class="card-body" id="report_page">

							</div>
							<div class="text-right mr-4 m-3">
								<button type="button" class="btn btn-primary" onclick="saveReportPageData()">Save
								</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-12 col-md-4 col-lg-4">
					<div class="card">

						<div class="card-body" id="report_page_list">
							<table class="table table-bordered table-striped mb-0">
								<thead>
								<tr>
									<th>Pages</th>
								</tr>
								</thead>
								<tbody id="pagesList">

								</tbody>
							</table>
						</div>


					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<?php $this->load->view('_partials/footer'); ?>
<script src="https://www.jqueryscript.net/demo/Export-Html-To-Word-Document-With-Images-Using-jQuery-Word-Export-Plugin/FileSaver.js"></script>
<script src="https://www.jqueryscript.net/demo/Export-Html-To-Word-Document-With-Images-Using-jQuery-Word-Export-Plugin/jquery.wordexport.js"></script>
<script>
	// $("#printBtn").click(function(event) {

	// 	$("#report_page").wordExport();
	// });

	function bmrReport() {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		window.location.href = baseURL + "bmrReport/"+type+'/'+report_id;
	}
</script>

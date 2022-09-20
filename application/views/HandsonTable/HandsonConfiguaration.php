<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/8.3.2/handsontable.full.min.css" integrity="sha512-eUeyGbgtvJnVVAw4PdhvHPXux0s6vc5tn8jewf0dRNCWDlstQxCwoVMjv5IWJkCpBY1Zo+N+Gz+Fr84ODFWSog==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style type="text/css">
	.main-content {
		width: 100% !important;

	}

	.xs_btn {
		padding: 7px 12px 5px 12px !important;
	}

	.roundCornerBtn1 {
		border-radius: 1px;
	}

	.roundCornerBtn2 {
		border-radius: 6px;
	}

	.roundCornerBtn3 {
		border-radius: 3px;
	}

	.roundCornerBtn4 {
		border-radius: 4px;
	}

	.roundCornerBtn5 {
		border-radius: 5px;
	}

	.roundCornerBtn6 {
		border-radius: 6px;
	}

	.roundCornerBtn7 {
		border-radius: 7px;
	}

	.roundCornerBtn8 {
		border-radius: 8px;
	}

	#template_detail_div {
		list-style: none;
	}

	#template_detail_div li {
		background-color: #f2f4f6;
		margin-bottom: 2px;
		padding: 2px 12px;
	}

	#template_detail_div label {
		font-weight: bold;
	}

	.select2-container {
		width: 100% !important;
	}

</style>
<!-- Main Content -->
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Create New Table</h1>

		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-primary">
						<div class="card-body">
							<button type="button" onclick="createUpdateSpreadsheet1()"
									class="btn btn-primary btn-sm roundCornerBtn4"
									style="margin-left: auto;margin-right: 20px;"><i class="fa fa-plus"></i> New
								Spreadsheet
							</button>
							<div class="">
								<table id="table_lists" class="display" style="width: 100% !important;">
									<thead>
									<tr>
										<th>#</th>
										<th>Spreadsheet Name</th>
										<th>Action</th>
										<th>Edit</th>
										<th>Configuration</th>
										<th>Transaction</th>
										<th>Prefill data</th>
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
<?php
$this->load->view('_partials/footer');
$this->load->view('HandsonTable/HandsonModal');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
		integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handsontable/8.3.2/handsontable.full.min.js" integrity="sha512-QKxTQfGv2U/EFV8WPXb0i/TtwNzacYyd6JE0bP1LHhSYu0aivteQYaE7uNi3IvyF6h+Z2oo7GUWXbYhlGrXUng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
	var base_url = '<?php base_url(); ?>';
</script>

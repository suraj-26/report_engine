<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
$this->load->view('pageConfiguration/inventoryFormModal');
?>
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
	.inputText
	{
		background-color: yellow!important;
	}
	.editorDiv
	{
		height: 70vh;
		overflow-y: auto;
	}
	iframe
	{
		padding: 2px;
		width: 210mm!important;
		border: 1px solid!important;
		margin: 1rem auto;
	}

	@media (min-width: 992px)
	#addmaterialmodal {
		max-width: 80%!important;
	}
</style>
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>BMR Report</h1>
			<input type="hidden" name="bmr_no" id="bmr_no" value="<?=$id?>">

<!--			--><?php //if($type == 1) { ?>
<!--			<div style="margin-left: 684px">-->
<!--				<button class="btn btn-primary btn-sm" type="button" onclick="addMaterial()">Add Material</button>-->
<!--			</div>-->
<!--			--><?php // } ?>
<!--			--><?php //if($type == 1) { ?>
<!--			<div style="margin-left: 15px">-->
<!--				<button class="btn btn-primary btn-sm" type="button" onclick="addProcess()">Add Process</button>-->
<!--			</div>-->
<!--			--><?php // } ?>
			<div style="margin-left: auto">
				<button class="btn btn-primary btn-sm" type="button" onclick="addNewPage()">Add Page</button>
			</div>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-8 col-lg-8">
					<div class="card">
						<form id="page_form" method="post" data-form-valid="saveHtml">
							<div class="card-body">
								<div class="align-items-baseline justify-content-between px-4 row">
									<div class="section-title">Pages Details</div>
								</div>
								<div class="row">
									<div class="form-group col-6">
										<label>Page Name</label>
										<input type="text" class="form-control" name="section_name" id="section_name"
											   data-valid="required"
											   data-msg="Enter page name"/>
										<input type="hidden" name="update_id" id="update_id" value="<?=$id?>"/>
										<input type="hidden" name="type" id="type" value="<?=$type?>"/>
										<input type="hidden" name="page_id" id="page_id"/>
										<input type="hidden" name="template_id" id="template_id"/>
										<input type="hidden" name="elementCount" id="elementCount" value="0"/>

									</div>
									<div class="col-4">
										<div class="form-group">

<!--											--><?php //if($type==1){ ?>
<!--												<label>Page Type</label>-->
<!--											<select name="page_type" id="page_type" class="form-control">-->
<!--												<option value="-1">Select One</option>-->
<!--												<option value="1">Product</option>-->
<!--												<option value="2">Production</option>-->
<!--											</select>-->
<!--											--><?php //} else { ?>
<!--												<input type="hidden" id="page_type" name="page_type" value="2">-->
<!--											--><?php //} ?>
										</div>
									</div>
									<div class="col-2">
										<button class="btn btn-primary mr-1" type="button" onclick="saveHtml()" id="templateFornBtn" style="margin-top: 30px;">Submit</button>
										<!-- <button class="btn btn-secondary" type="reset">Reset</button> -->
									</div>
								</div>

								<div class="editorDiv">
									<textarea class="bg-secondary" contenteditable="true" id="summernote">
									</textarea>

								</div>

							</div>

						</form>
					</div>
				</div>
				<div class="col-12 col-md-4 col-lg-4">
					<div class="card">

						<div class="card-body">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active show" id="section-tab" data-toggle="tab"
									   href="#sectionTab" role="tab" aria-controls="section" aria-selected="false">Setting</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="question-tab" data-toggle="tab" href="#questionTab"
									   role="tab" aria-controls="question" aria-selected="true">Questions</a>
								</li>
<!--								--><?php //if($type==1){ ?>
<!--								<li class="nav-item">-->
<!--									<a class="nav-link" id="fields-tab" data-toggle="tab" href="#fieldsTab"-->
<!--									   role="tab" aria-controls="fields" aria-selected="true">Fields</a>-->
<!--								</li>-->
<!--								--><?php //} ?>

							</ul>
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade" id="questionTab" role="tabpanel"
									 aria-labelledby="question-tab">

										<button onclick="setTextBoxCode(1)">
											<i class="fas fa-font"></i> Textbox
										</button>


								</div>
								<div class="tab-pane fade active show" id="sectionTab" role="tabpanel"
									 aria-labelledby="section-tab">
									<div class="row">
										<div class="col-12">
											<table class="table table-bordered table-striped mb-0">
												<thead>
												<tr>
													<th>Pages</th>
													<th></th>
												</tr>
												</thead>
												<tbody id="sectionTableBody">

												</tbody>
											</table>
										</div>
									</div>

								</div>
								<div class="tab-pane fade" id="fieldsTab" role="tabpanel"
									 aria-labelledby="fields-tab">
									<div class="section-title">Static Fields</div>
									<button onclick="setTextBoxCode(3,'reference_mfr_no')">
										<i class="fas fa-font"></i> Reference M.F.R No.
									</button>
									<button onclick="setTextBoxCode(3,'generic_name')">
										<i class="fas fa-font"></i> Generic Name
									</button>
									<button onclick="setTextBoxCode(3,'composition')">
										<i class="fas fa-font"></i> Composition
									</button>
									<button onclick="setTextBoxCode(3,'description')">
										<i class="fas fa-font"></i> Description
									</button>
									<button onclick="setTextBoxCode(3,'mfg_lic_no')">
										<i class="fas fa-font"></i> MFG. LIC. No
									</button>
									<button onclick="setTextBoxCode(3,'shelf_life')">
										<i class="fas fa-font"></i> Shelf life
									</button>
									<button onclick="setTextBoxCode(3,'product_code')">
										<i class="fas fa-font"></i> Product code
									</button>

									<div class="section-title">Static Tables</div>
									<button onclick="setTextBoxCode(2)">
										<i class="fas fa-font"></i> History Changes
									</button>
									<button onclick="setTextBoxCode(4)">
										<i class="fas fa-font"></i> Materials
									</button>
									<button onclick="setTextBoxCode(5)">
										<i class="fas fa-font"></i> Indent
									</button>
									<button onclick="setTextBoxCode(6)">
										<i class="fas fa-font"></i> Process Flow Chart
									</button>
								</div>

							</div>


						</div>
					</div>


					<div class="card">
						<div id="isconfigure" style="margin: 20px;">
							<input type="checkbox" name="is_config" id="is_config" value="true">&nbsp;IsConfigurable

						</div>
<!--						<div class="col-md-9">-->
<!--							<label>Select Table</label>-->
<!--							<select name="query_table" onchange="getTableName(this.value)" id="alltablename" class="form-control select2"></select>-->
<!--							<input type="hidden" name="table_name" id="table_name" value="">-->
<!--						</div>-->


						<div class="card-header">
							<h4>Input Configuration</h4>
						</div>
						<div class="card-body">
							<div id="keyPairsDiv">

							</div>

							<div id="keybutton" style="margin: 20px;">

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<?php $this->load->view('_partials/footer'); ?>

<script type="text/javascript">
getAllTablesNames();
	$(document).ready(function () {
		app.formValidation();
	});
	function getAllTablesNames() {
	app.request("getAllTablesNames", null).then(res => {
		$("#alltablename").html('');
		$("#alltablename").html(res.option);
		$("#alltablename").select2();

	}).catch(error => console.log(error));
}
</script>

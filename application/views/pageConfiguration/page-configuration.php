<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Page Configuration</title>

	<link href="<?php echo base_url(); ?>assets/modules/bootstrap/css/bootstrap.min.css" rel="styelsheet"/>
	<link href="<?php echo base_url(); ?>assets/modules/bootstrap/css/bootstrap.css" rel="stylesheet"/>
	<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet"/>
	<link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/izitoast/css/iziToast.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">


	<style>
		.d-row {
			position: relative;
			left: -28px;
			text-align: center;
			top: 28px;
			padding: 0px 4px;
			background-color: #e9ebef;
			cursor: pointer;
		}

		.d-g-row {
			position: relative;
			top: -14px;
		}

		.d-col {
			min-height: 50px;
			border: solid 1px #e9ebef;
		}

		.drop-zone {
			min-height: 50px;
			background: rgb(235, 230, 230);
		}

		#pageContainer {
			/* border: solid 1px #e9ebef; */
		}

		span.select2.select2-container.select2-container--default.select2-container--below.select2-container--open{
			width: 100%!important;
		}
		span.select2.select2-container.select2-container--default{
			width: 100%!important;
		}
		.leftsidebar
		{
			height: 80vh;
			overflow: auto;
		}
	</style>

</head>

<body>

<div class="container-container-fluid">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<p>Page Configuration</p>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label>Template Name</label>
					<input type="text" id="template_name" required name="template_name" class="form-control">
					<input type="hidden" id="template_id" name="template_id" class="form-control" value="<?php echo $template_id; ?>">
				</div>
				<div class="form-group">
					<label>Configuration</label>
					<input type="radio" id="template_configuration" required name="configuration" checked="" class="" value="1" onclick="showComponents(1)"> <label for="template_configuration">Template Configuration</label>
					<input type="radio" id="page_configuration" required name="configuration" class="" value="2" onclick="showComponents(2)"> <label for="page_configuration">Page Configuration</label>
					<input type="radio" id="div_configuration" required name="configuration" class="" value="3" onclick="showComponents(3)"> <label for="div_configuration">Table Configuration</label>
				</div>
				<div class="row">
					<div class="col-md-2 leftsidebar">
						<div class="form-group">
							<label>Template Control</label>
						</div>
						<div class="template-controls">
							<div class="temp_conf">

								<div class="section copy-control" id="clear-1" data-lable="clear"
									 onclick="setControlToDropZone('clear-1',1)">
									<label class="form-control text-nowrap">Clear</label>
								</div>

								<div class="section copy-control" id="lable-1" data-lable="lable"
									 onclick="setControlToDropZone('lable-1',1)">
									<div class="section-title mt-0 mb-1" id="labelTag" contenteditable="true">Default</div>
								</div>
								<div class="drag-control form-group p-1 copy-control temp_conf"
									 onclick="setControlToDropZone('button-1',1)"
									 id="button-1" data-control_id="11" data-lable="button" data-type="button">
									<div class="row">
										<div class="col-md-11">
											<button class="btn btn-primary config_btn" contenteditable="true" type="submit">
												Save
											</button>
										</div>
										<div class="col-md-1">
											<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-11" type="button"><i class="fa fa-plus-square"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="drag-control form-group p-1 copy-control" data-lable="hidden" data-type="hidden"
								 id="hidden-1" data-control_id="10"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('hidden-1',1)">
								<div class="row">
									<div class="col-md-11">
										<lable> Hidden Field</lable>
										<input type="hidden" class="form-control" disabled/>
									</div>
									<div class="col-md-1" data-type="hidden">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-10" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							<div class="drag-control form-group p-1" data-control_id="17" data-lable="queryParam" data-type="queryParam"
								 id="control-17"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-17',1)">
								<div class="row">
									<div class="col-md-11">
										<label>Query Parameter</label>
										<input type="hidden" class="form-control" disabled placeholder="QueryParam" data-type="queryParam" />
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-17" type="button"><i class="fa fa-plus-square"></i></button>
									</div>

								</div>
							</div>
							<div class="page_conf">
								<div class="drag-control form-group p-1" data-control_id="18" data-type="templateForm"
									 id="control-18"
									 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-18')">
									<div class="row">
										<div class="col-md-11">
											<label>templateForm</label>
											<select class="form-control" disabled></select>
										</div>
										<div class="col-md-1">
											<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-18" type="button"><i class="fa fa-plus-square"></i></button>
										</div>

									</div>
								</div>
							</div>
							<div class="temp_conf">
							<div class="drag-control form-group p-1 " data-control_id="1"
								 id="control-1" data-type="text"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-1')">
								<div class="row">
									<div class="col-md-11">
										<lable>Short Text</lable>
										<input type="text" class="form-control" disabled/>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-1" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							<div class="drag-control form-group p-1 " data-control_id="2" data-type="textarea"
								 id="control-2"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-2')">
								<div class="row">
									<div class="col-md-11">
										<lable>Textarea</lable>
										<textarea type="number" class="form-control" disabled></textarea>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-2" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							<div class="drag-control form-group p-1 " data-control_id="3" data-type="number"
								 id="control-3"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-3')">

								<div class="row">
									<div class="col-md-11">
										<lable>Number</lable>
										<input type="text" class="form-control" disabled/>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-3" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							<div class="drag-control form-group p-1 " data-control_id="4" data-type="date" id="control-4"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-4')">
								<div class="row">
									<div class="col-md-11">
										<lable>Date</lable>
										<input type="date" class="form-control" disabled/>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-4" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
								<div class="drag-control form-group p-1 " data-control_id="23"
									 id="control-23" data-type="email"
									 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-23')">
									<div class="row">
										<div class="col-md-11">
											<lable>Email</lable>
											<input type="email" class="form-control" disabled/>
										</div>
										<div class="col-md-1">
											<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-23" type="button"><i class="fa fa-plus-square"></i></button>
										</div>
									</div>
								</div>
							<div class="drag-control form-group p-1 " data-control_id="5" data-type="file" id="control-5"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-5')">
								<div class="row">
									<div class="col-md-11">
										<lable>Attachment</lable>
										<input type="file" name="userfile[]" class="form-control" disabled/>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-5" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							<div class="drag-control form-group p-1 " data-control_id="6" data-type="singleSelection"
								 id="control-6"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-6')">
								<div class="row">
									<div class="col-md-11">
										<lable>Single Selection</lable>
										<select class="form-control" disabled></select>
									</div>
									<div class="col-md-1 config_btn">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-6" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							<div class="drag-control form-group p-1 " data-control_id="7" data-type="multipleSelection"
								 id="control-7"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-7')">
								<div class="row">
									<div class="col-md-11">
										<lable>Multiple Selection</lable>
										<select multiple class="form-control" disabled></select>
									</div>
									<div class="col-md-1 config_btn">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-7" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							<div class="form-group d-flex " style="border: solid 1px #dae0e5;" data-type="radio"
								 data-control_id="8"
								 id="control-8" onclick="setControlToDropZone('control-8')">

								<div class="col-md-10">

									<label class="d-block">Inline Radio</label>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" id="inlineradio1" value="option1"
											   name="t1">
										<label class="form-check-label" for="inlineradio1">1</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" id="inlineradio2" value="option2"
											   name="t1">
										<label class="form-check-label" for="inlineradio2">2</label>
									</div>

								</div>
								<div class="col-md-2 config_btn ">
									<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-8" type="button"><i class="fa fa-plus-square"></i></button>
								</div>

							</div>
							<div class="form-group d-flex " style="border: solid 1px #dae0e5;" data-type="checkbox"
								 data-control_id="9"
								 id="control-9" onclick="setControlToDropZone('control-9')">

								<div class="col-md-10">
									<label class="d-block">Inline Checkbox</label>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" id="inlinecheck1" value="option1"
											   name="t1">
										<label class="form-check-label" for="inlinecheck1">1</label>
									</div>

								</div>
								<div class="col-md-2 config_btn">
									<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-9" type="button"><i class="fa fa-plus-square"></i></button>
								</div>
							</div>


							<div class="drag-control form-group p-1 " data-control_id="12" data-type="queryDropdown"
								 id="control-12"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-12')">
								<div class="row">
									<div class="col-md-11">
										<lable>Query Dropdown</lable>
										<select class="form-control" disabled></select>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-12" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
							</div>
							<div class="div_conf">
							<div class="drag-control form-group p-1 " data-control_id="13" data-type="excel_report"
								 id="control-13"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-13')">
								<div class="row">
									<div class="col-md-11">
										<label class="form-control text-nowrap" readonly>Report Button</label>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-13" type="button"><i class="fa fa-plus-square"></i></button>
									</div>
								</div>
							</div>
<!--							<div class="drag-control form-group p-1 " data-control_id="14" data-type="pdf_report"-->
<!--								 id="control-14"-->
<!--								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-14')">-->
<!--								<div class="row">-->
<!--									<div class="col-md-11">-->
<!--										<label class="form-control text-nowrap" readonly>PDF Report Button</label>-->
<!--									</div>-->
<!--									<div class="col-md-1">-->
<!--										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-14" type="button"><i class="fa fa-plus-square"></i></button>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
<!--							<div class="drag-control form-group p-1 " data-control_id="15" data-type="csv_report"-->
<!--								 id="control-15"-->
<!--								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-15')">-->
<!--								<div class="row">-->
<!--									<div class="col-md-11">-->
<!--										<label class="form-control text-nowrap" readonly>CSV Report Button</label>-->
<!--									</div>-->
<!--									<div class="col-md-1">-->
<!--										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-15" type="button"><i class="fa fa-plus-square"></i></button>-->
<!--									</div>-->
<!---->
<!--								</div>-->
<!--							</div>-->
							<div class="drag-control form-group p-1 " data-control_id="16" data-type="datatable"
								 id="control-16"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-16')">
								<div class="row">
									<div class="col-md-11">
										<label class="form-control text-nowrap" readonly>Datatable</label>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-16" type="button"><i class="fa fa-plus-square"></i></button>
									</div>

								</div>
							</div>
								<div class="drag-control form-group p-1" data-control_id="19" data-type="Handsontable"
									 id="control-19"
									 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-19')">
									<div class="row">
										<div class="col-md-11">
											<label class="form-control text-nowrap" readonly>Handsontable</label>
										</div>
										<div class="col-md-1">
											<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-19" type="button"><i class="fa fa-plus-square"></i></button>
										</div>

									</div>
								</div>
							</div>

							<div class="drag-control form-group p-1" data-control_id="20" data-type="inputSearch"
								 id="control-20"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-20')">
								<div class="row">
									<div class="col-md-11">
										<label class="form-control text-nowrap" readonly>Input Search</label>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-20" type="button"><i class="fa fa-plus-square"></i></button>
									</div>

								</div>
							</div>
							<div class="drag-control form-group p-1" data-control_id="21" data-type="modalButton"
								 id="control-21"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-21')">
								<div class="row">
									<div class="col-md-11">
										<label class="form-control text-nowrap" readonly>Modal Button</label>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-21" type="button"><i class="fa fa-plus-square"></i></button>
									</div>

								</div>
							</div>
							<div class="drag-control form-group p-1" data-control_id="22" data-type="dropdownButton"
								 id="control-22"
								 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-22')">
								<div class="row">
									<div class="col-md-11">
										<label class="form-control text-nowrap" readonly>Dropdown with Button</label>
									</div>
									<div class="col-md-1">
										<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="setConfigurations()" id="config_btn-22" type="button"><i class="fa fa-plus-square"></i></button>
									</div>

								</div>
							</div>
							<div class="div_conf">
							<div class="drag-control form-group p-1 " data-control_id="24"
									 id="control-24" data-type="addmore"
									 style="border: solid 1px #dae0e5;" onclick="setControlToDropZone('control-24')">
									<div class="row">
										<div class="col-md-11">
											<lable>AddMore</lable>
											<input type="text" class="form-control" disabled/>
										</div>
										<div class="col-md-1">
											<button class="btn btn-sm btn-link config_btn" style="display: none" onclick="" id="config_btn-24" type="button"><i class="fa fa-plus-square"></i></button>
										</div>
									</div>
							</div>
	                        </div>
							

						</div>
					</div>
					<div class="col-md-10">
						<div class="row justify-content-between">
							<div class="btn-group">
								<button class="btn btn-dark mx-1" onclick="addRow(12)">One Column Row</button>
								<button class="btn btn-dark mx-1" onclick="addRow(6)">Two Column Row</button>
								<button class="btn btn-dark mx-1" onclick="addRow(4)">Three Column Row</button>
								<button class="btn btn-dark mx-1" onclick="addRow(3)">Four Column Row</button>
							</div>
							<div class="btn-group">
								<button class="btn btn-primary mx-1" onclick="SaveTemplate()">Save</button>

							</div>
						</div>
						<div class="row">
							<div class="col-md-12" id="pageContainer">

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer">

			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="col-md-12">
		<div class="card">
			<div id="finalFormat"></div>
		</div>

	</div>
</div>
<script> var baseURL = '<?=base_url()?>';</script>
<?php
$this->load->view('pageConfiguration/config-modal');
$this->load->view('pageConfiguration/datatable-modal');
$this->load->view('_partials/footer');
?>
<!-- custom script -->


</body>
</html>
<script type="text/javascript">
	$(document).ready(function(){
		if($("#template_id").val()!=0 && $("#template_id").val()!="")
		{
			getTemplateDataById($("#template_id").val());
		}
	});
</script>

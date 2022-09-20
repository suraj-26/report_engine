<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style type="text/css">
	@media (max-width: 800px) {

		body.layout-2 .main-content {
			padding-top: 5px !important;
			padding-left: 5px !important;
			padding-right: 5px !important;
		}

	}

	@media (min-width: 768px) {
		.modal-xl {
			width: 90%;
			max-width: 1200px;
		}
	}

	.card .card_border108 .shadow-none,
	.card .card_border87 .shadow-none,
	.card .card_border93 .shadow-none,
	.card .card_border96 .shadow-none,
	.card .card_border97 .shadow-none,
	{
		padding: 0 !important;
	}

	.card-body.card_body101, .card-body.card_body102, .card-body.card_body103, .card-body.card_body104, .card-body.card_body105 {
		padding: 0 !important;
	}

	.content-wrap section {
		text-align: unset !important;
	}
	#form_data_report th{
		text-align: center !important;
	}
</style>

<!-- Main Content -->
<div class="main-content">
	<section class="section section-body_new">
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card">
						<input type="hidden" id="department_id" name="department_id"
							   value="<?= $department_id ?>">
						<input type="hidden" id="section_id" name="section_id"
							   value="<?= $section_id ?>">
						<input type="hidden" id="queryparameter_hidden" name="queryparameter_hidden"
							   value="<?= $queryParam ?>">

						<style>
							#form_data_report .div_display {
								height: auto !important;
								width: auto !important;
								padding: 4px !important;
								top: 0px !important;
							}

							#form_data_report .spanLabel {
								background-color: transparent !important;
								padding: 4px;
							}

							#form_data_report p {
								height: auto !important;
							}

							#form_data_report #div_79, #div_68 {
								position: unset !important;
							}

							#form_data_report th {
								width: 15% !important;
							}

							#form_data_report .table1:not(.table1-sm):not(.table1-md):not(.dataTable1) td, .table1:not(.table1-sm):not(.table1-md):not(.dataTable) th {
								padding: 0 25px;
								height: 40px;
								vertical-align: middle;

							}
							.helptext{
								visibility: hidden;
							}

							@media print {
								.amount_table {
									display: none;
								}
								.hide_class{
									display: none;
								}
								.case_history_checkbox{
									display: none;
								}
								input[type="checkbox"]:checked .case_history {
									display: none;
								}
							}
							.firstimgs {
								display: none !important;
							}
						</style>


						<section id="profileSection">
							<div class="card card-primary">
								<div class="card-body">

									<div class="tabs tabs-style-underline">
										<nav>
											<ul id="opd_top_nav">
												<li class="tab-current">
													<a href="#caseHistory" class="icon" id="tabCaseHistory">
														<i class="fas fa-file-medical-alt  mr-1 fa_class"></i>

														<span>Case History</span>
													</a>
												</li>
												<li class="">
													<a href="#doctorConsult" class="icon" id="tabDoctorConsult">
														<i class="fas fa-notes-medical  mr-1 fa_class"></i>
														<span>DOCTOR CONSULT</span></a></li>
												<li class="">
													<a href="#medication" class="icon" id="tabMedication">
														<i class="fas fa-capsules mr-1 fa_class"></i>
														<span> MEDICATION</span></a></li>
												<li class="">
													<a href="#investigation" class="icon" id="tabInvestigation"
													><i class="fas fa-vial mr-1 fa_class"></i>
														<span> INVESTIGATION</span></a></li>
                                                <li class="">
                                                    <a href="#restoreHealth" class="icon" id="tabRestoreHealth"
                                                    >
                                                        <i class="fas fa-notes-medical mr-1 fa_class"></i>
                                                        <span> RESTORE HEALTH</span></a></li>
											</ul>
										</nav>
										<div class="content-wrap">
											<section id="caseHistory" class="content-current">
												<div id="tabCaseHistoryPanel"></div>
											</section>
											<section id="doctorConsult">
												<div id="tabDoctorConsultPanel"></div>
											</section>
											<section id="medication">
												<div id="tabMedicationPanel"></div>
											</section>
											<section id="investigation">
												<div id="tabInvestigationPanel"></div>
											</section>
                                            <section id="restoreHealth">
                                                <div id="tabRestoreHealthPanel"></div>
                                            </section>
										</div>
									</div>
								</div>
							</div>
						</section>
						<section id="reportPanel">
							<div>
								<div class="form-group row px-2" id="datefordoctorconsult" style="display: none">
									<label for="datefordoctorconsult" class="col-sm-3 col-form-label mx-2 hide_class">Dates:</label>
									<div class="col-md-12 row ">
										<div class="col-md-10 odpsction">
											<select class="form-control form-control-sm col-md-4 hide_class"
													onchange="getDataofReport(12,'<?php echo $queryParam ?>')"
													id="datefordoctorconsult1"
													name="datefordoctorconsult">

											</select>
										</div>
										<div class="col-md-10 idpsction" style="display: none">
											<select class="form-control form-control-sm col-md-4"
													onchange="GetIPDBill(this.id)"
													id="datefordoctorconsult2"
													name="datefordoctorconsult2">

											</select>
										</div>
										<div class="col-md-2">
											<label class="custom-switch custom-control-inline hide_class">
												<span class="custom-switch-description">OPD</span> &nbsp;&nbsp;&nbsp;
												<input type="checkbox" name="admission_mode" id="ckteleconsult" onchange="ToggleIPD()" class="custom-switch-input" value="1">
												<span class="custom-switch-indicator hide_class"></span>
												<span class="custom-switch-description">IPD</span>
											</label>
										</div>


									</div>
								</div>


								<button class="btn btn-primary odpsction hide_class" type="button"
										style="margin-right:10px;margin-left: auto;float:right;display: none"
										id="CloseBillButton" onclick="Close_billing()">Close Billing
								</button>
								<button class="btn btn-primary odpsction hide_class" type="button"
										style="margin-right:10px;margin-left: auto;float:right;display: none"
										id="printButton" onclick="print_div()">PRINT Prescription
								</button>
								<button class="btn btn-primary odpsction hide_class" type="button"
										style="margin-right:10px;margin-left: auto;float:right;display: none"
										id="printButton1" onclick="print_Newdiv()">PRINT Billing
								</button>
								<!--                                <button class="btn btn-primary" type="button"-->
								<!--                                        style="margin-right:10px;margin-left: auto;float:right;display: none"-->
								<!--                                        id="IPDBillButton" onclick="GetIPDBill()">IPD Billing-->
								<!--                                </button>-->
							</div>
							<div id="ShowForm">
								<br><div class="col-md-12 offset-10" id=""><br><br>
									<img alt="image" id="" style="width: 200px;height: 92px;display: none" src="<?= base_url()?>/assets/img/rel_logo.jpg"  class="" ></div>
								<div id="form_data"></div>
								<div id="form_data1"></div>
								<div id="form_data_report" class="odpsction" style="display: none"></div>
								<div id="form_data_report2" class="idpsction" style="display: none">

									<button type="button" class="btn btn-primary" style="float: right" id="ipdprintbtn" onclick="printDiv()">PRINT</button>
									<div class="section-body1">
										<div class="">
											<div class="">
												<div class="card">
													<input type="hidden" name="billingMaster_patient_id" id="billingMaster_patient_id">
													<div class="col-md-12 card-body" style="padding-top: 20px;">
														<div class="form-row">
															<div class="form-group col-md-6">
																Name : <span id="billingMasterPatientName"></span>
															</div>
															<div class="form-group col-md-6">
																Bill No : <span id="billingNo"></span>
															</div>
														</div>
														<div class="form-row">
															<div class="form-group col-md-6">
																Age : <span id="patientAge"></span>
															</div>
															<div class="form-group col-md-6">
																Bill Date : <span id="billingDate"></span>
															</div>
														</div>
														<div class="form-row">
															<div class="form-group col-md-6">
																Patient ID : <span id="billingPatient_id"></span>
															</div>
															<div class="form-group col-md-6">
																Case Type : <span id=""> Covid Treatment</span>
															</div>
														</div>
														<div class="form-row">
															<div class="form-group col-md-6">
																Case ID : <span id="case_id"></span>
															</div>
															<div class="form-group col-md-6">
																Location : <span id=""> NSCI, Worli, Mumbai</span>
															</div>
														</div>

													</div>
													<hr/>
													<div class="col-md-12 card-body">
														<div class="form-row" style="font-size: 16px;text-decoration: underline;">
															<b>Stay</b></div>
														<div class="form-row">
															<div class="form-group col-md-6">
																Admission Date : <span id="admission_date"></span>
															</div>
															<div class="form-group col-md-6">
																Admission Time : <span id="admission_time"></span>
															</div>
														</div>
														<div class="form-row">
															<div class="form-group col-md-6">
																Discharge Date : <span id="discharge_date"></span>
															</div>
															<div class="form-group col-md-6">
																Discharge Time : <span id="discharge_time"></span>
															</div>
														</div>
													</div>
													<hr/>

													<div class="row card-body">

														<div class="col-md-12">
															<div class="form-view" style="font-size: 16px;text-decoration: underline;">
																<strong>Accomodation</strong></div>
															<div class="table-responsive">
																<table class="table table-bordered table-striped" id="">
																	<thead>
																	<tr>
																		<td>Start Date/Time</td>
																		<td>Service Description</td>
																		<td>Qty</td>
																		<td>Rate ( Rs)</td>
																		<td>Amount ( Rs)</td>
																		<td>Discount (%)</td>
																		<td>Delete</td>
																		<td>Final Amount ( Rs)</td>
																	</tr>
																	</thead>
																	<tbody id="accomodationTableBilling">

																	</tbody>
																</table>
															</div>
														</div>
														<hr/>
														<div class="col-md-12">
															<div class="form-view" style="font-size: 16px;text-decoration: underline;">
																<strong>Doctor Consultation</strong></div>
															<div class="table-responsive">
																<table class="table table-bordered table-striped"
																	   id="doctorConsulationTableBilling">
																	<thead>
																	<tr>
																		<td>Date/Time</td>
																		<td>Service Description</td>
																		<td>Service Order No.</td>
																		<td>Qty</td>
																		<td>Rate ( Rs)</td>
																		<td>Amount ( Rs)</td>
																		<td>Discount (%)</td>
																		<td>Delete</td>
																		<td>Final Amount ( Rs)</td>


																	</tr>
																	</thead>
																	<tbody id="roombilltable">

																	</tbody>
																</table>
															</div>
														</div>
													</div>
													<hr/>
													<div class="row card-body">

														<div class="col-md-12">
															<div class="form-view" style="font-size: 16px;text-decoration: underline;">
																<strong>Service Orders</strong></div>
															<div class="table-responsive">
																<table class="table table-bordered table-striped" id="">
																	<thead>
																	<tr>
																		<td>Date/Time</td>
																		<td>Service Description</td>
																		<td>Service Order No.</td>
																		<td>Qty</td>
																		<td>Rate ( Rs)</td>
																		<td>Amount ( Rs)</td>
																		<td>Discount (%)</td>
																		<td>Delete</td>
																		<td>Final Amount ( Rs)</td>
																	</tr>
																	</thead>
																	<tbody id="serviceOrderCollectionTableBilling">

																	</tbody>
																</table>
															</div>
														</div>
													</div>
													<hr/>
													<div class="row card-body">

														<div class="col-md-12">
															<div class="form-view" style="font-size: 16px;text-decoration: underline;">
																<strong>Medication and Consumables</strong></div>
															<div class="table-responsive">
																<table class="table table-bordered table-striped" id="">
																	<thead>
																	<tr>
																		<td>S. No.</td>
																		<td>Voucher Reference Number</td>
																		<td>Date / Time</td>
																		<td>Amount ( Rs)</td>
																		<td>Discount (%)</td>
																		<td>Delete</td>
																		<td>Final Amount ( Rs)</td>
																	</tr>
																	</thead>
																	<tbody id="medicineAndConsumablesTableBilling">

																	</tbody>
																</table>
															</div>
														</div>
													</div>
													<div class="row card-body">

														<div class="col-md-12">
															<div class="form-view"><strong>Grand Total : <span
																			id="subTotal"></span></strong></div>
															<div class="form-view"><strong>Discount : <span
																			id="discount">100%</span></strong></div>
															<div class="form-view" style="font-size: 20px;"><strong>Payable Amount : <span
																			id="grandTotal"></span></strong></div>

														</div>
													</div>


												</div>
											</div>
										</div>
									</div>

								</div>
								<div class="col-md-12">
								<div id="BillingInfoData1" style="display: none"><h5 style="color: #891635">Billing Info:</h5>
									<div id="BillingInfoData"></div>
								</div>
								</div>

							</div>
							<div id="ShowHistory" class="" style="display:none">
								History Table show here
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<div class="modal" id="myModalIPDBill" role="dialog" data-backdrop="false">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4>IPD Billing</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-primary" id="ipdprintbtn" onclick="printDiv()">PRINT</button>
				<div class="section-body1">
					<div class="">
						<div class="">
							<div class="card">
								<!-- <input type="hidden" name="billingMaster_patient_id" id="billingMaster_patient_id"> -->
								<div class="col-md-12 card-body" style="padding-top: 20px;">
									<div class="form-row">
										<div class="form-group col-md-6">
											Name : <span id="billingMasterPatientName"></span>
										</div>
										<div class="form-group col-md-6">
											Bill No : <span id="billingNo"></span>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											Age : <span id="patientAge"></span>
										</div>
										<div class="form-group col-md-6">
											Bill Date : <span id="billingDate"></span>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											Patient ID : <span id="billingPatient_id"></span>
										</div>
										<div class="form-group col-md-6">
											Case Type : <span id=""> Covid Treatment</span>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											Case ID : <span id="case_id"></span>
										</div>
										<div class="form-group col-md-6">
											Location : <span id=""> NSCI, Worli, Mumbai</span>
										</div>
									</div>

								</div>
								<hr/>
								<div class="col-md-12 card-body">
									<div class="form-row" style="font-size: 16px;text-decoration: underline;">
										<b>Stay</b></div>
									<div class="form-row">
										<div class="form-group col-md-6">
											Admission Date : <span id="admission_date"></span>
										</div>
										<div class="form-group col-md-6">
											Admission Time : <span id="admission_time"></span>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											Discharge Date : <span id="discharge_date"></span>
										</div>
										<div class="form-group col-md-6">
											Discharge Time : <span id="discharge_time"></span>
										</div>
									</div>
								</div>
								<hr/>

								<div class="row card-body">

									<div class="col-md-12">
										<div class="form-view" style="font-size: 16px;text-decoration: underline;">
											<strong>Accomodation</strong></div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="">
												<thead>
												<tr>
													<td>Start Date/Time</td>
													<td>Service Description</td>
													<td>Qty</td>
													<td>Rate ( Rs)</td>
													<td>Amount ( Rs)</td>
													<td>Discount (%)</td>
													<td>Delete</td>
													<td>Final Amount ( Rs)</td>
												</tr>
												</thead>
												<tbody id="accomodationTableBilling">

												</tbody>
											</table>
										</div>
									</div>
									<hr/>
									<div class="col-md-12">
										<div class="form-view" style="font-size: 16px;text-decoration: underline;">
											<strong>Doctor Consultation</strong></div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped"
												   id="doctorConsulationTableBilling">
												<thead>
												<tr>
													<td>Date/Time</td>
													<td>Service Description</td>
													<td>Service Order No.</td>
													<td>Qty</td>
													<td>Rate ( Rs)</td>
													<td>Amount ( Rs)</td>
													<td>Discount (%)</td>
													<td>Delete</td>
													<td>Final Amount ( Rs)</td>


												</tr>
												</thead>
												<tbody id="roombilltable">

												</tbody>
											</table>
										</div>
									</div>
								</div>
								<hr/>
								<div class="row card-body">

									<div class="col-md-12">
										<div class="form-view" style="font-size: 16px;text-decoration: underline;">
											<strong>Service Orders</strong></div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="">
												<thead>
												<tr>
													<td>Date/Time</td>
													<td>Service Description</td>
													<td>Service Order No.</td>
													<td>Qty</td>
													<td>Rate ( Rs)</td>
													<td>Amount ( Rs)</td>
													<td>Discount (%)</td>
													<td>Delete</td>
													<td>Final Amount ( Rs)</td>
												</tr>
												</thead>
												<tbody id="serviceOrderCollectionTableBilling">

												</tbody>
											</table>
										</div>
									</div>
								</div>
								<hr/>
								<div class="row card-body">

									<div class="col-md-12">
										<div class="form-view" style="font-size: 16px;text-decoration: underline;">
											<strong>Medication and Consumables</strong></div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped" id="">
												<thead>
												<tr>
													<td>S. No.</td>
													<td>Voucher Reference Number</td>
													<td>Date / Time</td>
													<td>Amount ( Rs)</td>
													<td>Discount (%)</td>
													<td>Delete</td>
													<td>Final Amount ( Rs)</td>
												</tr>
												</thead>
												<tbody id="medicineAndConsumablesTableBilling">

												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="row card-body">

									<div class="col-md-12">
										<div class="form-view"><strong>Grand Total : <span
														id="subTotal"></span></strong></div>
										<div class="form-view"><strong>Discount : <span
														id="discount">0%</span></strong></div>
										<div class="form-view" style="font-size: 20px;"><strong>Payable Amount : <span
														id="grandTotal"></span></strong></div>

									</div>
								</div>


							</div>
						</div>
						<div id="billingPrint"></div>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="fire-modal-Billing"
	 aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add To Bill</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<form id="FileUploadationForm" method="post">
				<input type="hidden" name="serviceDescId" id="serviceDescId">
				<div class="">
					<div class="form-group col-md-12">
						<label for="service_file">File Upload</label>
						<input type="file" class="form-control" name="service_file[]"  data-valid="required" data-msg="Select file" id="service_file">
						<span id="radiology_diles_error" style="color: red"></span>
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
<?php $this->load->view('admin/HtmlFormTemplate/html_history_form'); ?>
<?php $this->load->view('_partials/footer'); ?>
<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";

	$(document).ready(function () {
		var section_type = localStorage.getItem("section_type");
		console.log(section_type);
		if (section_type == "" || section_type == null) {
			localStorage.setItem("section_type", 1);
		}

		// localStorage.clear();
		var department_id = $("#department_id").val();
		var section_id = $("#section_id").val();

		var queryparameter_hidden = $("#queryparameter_hidden").val();
		get_DepartMent_Menus(department_id, section_id, queryparameter_hidden);
        $("#tabRestoreHealth").on('click', function (e) {
            // restore health tab
            loadRestorePanel();
        });


		// get_section_forms(department_id,section_id,queryparameter_hidden);
		checkBillingStatus();
		getallDates();
		$('#editHistoryModal').on('show.bs.modal', function (e) {
			let section_id = parseInt($(e.relatedTarget).data('section_id'));
			let history_id = parseInt($(e.relatedTarget).data('history_id'));
			let department_id = parseInt($(e.relatedTarget).data('department_id'));
			let patient_id = localStorage.getItem("patient_id");
			// get_forms(localStorage.getItem("patient_id"));
			get_forms_history(section_id, history_id, patient_id, department_id);
		});
	});
	function get_forms_history(section_id, history_id, patient_id, department_id) {
		let formData = new FormData();
		formData.set("section_id", section_id);
		formData.set("history_id", history_id);
		formData.set("patient_id", patient_id);
		// formData.set("department_id",department_id);
		app.request("<?= base_url('getHistoryTemplate') ?>", formData).then(response => {
			if (response.status === 200) {
				$('#history_form_section').empty();
				$('#history_form_section').append(response.data);
			}
		}).catch(error => {
			console.log(error);
		})
	}
    function loadRestorePanel(){
        let queryparameter_hidden = $("#queryparameter_hidden").val();
        let object = JSON.parse(atob(queryparameter_hidden));

            $.ajax({
                url: "<?= base_url("getTemplateForm") ?>",
                type: "POST",
                dataType: "json",
                data: {department_id: 16, patient_id: object.patient_id},
                success: function (result) {
                    var data = result.data;
                    if (result['code'] === 200) {
                        $("#tabRestoreHealthPanel").html(data);
                        $(".accordion-header").addClass('d-none')
                        app.formValidation();
                        showPanel(38)
                    } else {

                    }
                }, error: function (error) {

                    alert('Something went wrong please try again');
                }
            });

    }

    let labelsCollection = null;
    let record = null;
    const trans = [];

    function view_history(table_name, section_id) {
        $("#main_div_" + section_id).addClass('d-none');
        $("#history_div_" + section_id).removeClass('d-none');
        $("#graph_div_" + section_id).addClass('d-none');
        $("#history_btn_" + section_id).hide();//main_btn
        $("#graph_btn_" + section_id).show();
        $("#main_btn_" + section_id).show();
        let patient_id = localStorage.getItem("patient_id")
        $.ajax({
            type: "POST",
            url: "<?= base_url("get_history_data") ?>",
            dataType: "json",
            data: {table_name: table_name, patient_id: patient_id, section_id: section_id},
            success: function (result) {
                $("#history_div_" + section_id).empty();
                $("#history_div_" + section_id).append(result.table);

                $("#example").on("mousedown", "td .fa.fa-minus-square", function (e) {
                    table.row($(this).closest("tr")).remove().draw();
                });
                $("#example").on('mousedown.edit', "i.fa.fa-pencil-square", function (e) {

                    $(this).removeClass().addClass("fa fa-envelope-o");
                    var $row = $(this).closest("tr").off("mousedown");
                    var $tds = $row.find("td").not(':first').not(':last');

                    $.each($tds, function (i, el) {
                        var txt = $(this).text();
                        $(this).html("").append("<input type='text' value=\"" + txt + "\">");
                    });
                });

                $('#history_table_' + section_id).on('mousedown', "#selectbasic", function (e) {
                    e.stopPropagation();
                });

                $('#history_table_' + section_id).DataTable(
                    {
                        order:[[result.transColumnIndex,"desc"]],
                        "columnDefs" : [{"targets":result.transColumnIndex, "type":"date-eu"}],
                    }
                );
                labelsCollection = result.label;
                record = result.data;
                trans.push(result.trans);
            }, error: function (error) {
                app.errorToast('Something went wrong please try again');
            }
        });
    }

    // receving section

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function loadGraph(sectionName, records, lable, transDate, section_id) {
        const labelsValues = transDate;
        let color = getRandomColor();
        const historyDataSets = [{
            label: lable,
            data: records[lable],
            borderColor: color,
            backgroundColor: color,
            tension: 0.1
        }];

        const data = {
            labels: labelsValues,
            datasets: historyDataSets
        };
        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: sectionName
                    }
                }
            },
        };


        $(`#graph_section_${section_id}`).append(`<canvas id="chat_section_${lable}"  class="col-md-4" style="width: 100%;height: 100%"></canvas>`);
        var ctx = document.getElementById(`chat_section_${lable}`)
        new Chart(ctx, config);
    }


    function view_main_data(section_id) {
        $("#history_btn_" + section_id).show();//main_btn
        $("#main_btn_" + section_id).hide();
        $("#history_div_" + section_id).addClass('d-none');
        $("#main_div_" + section_id).removeClass('d-none');
        $("#graph_div_" + section_id).addClass('d-none');
        $("#graph_btn_" + section_id).hide();
    }
    function view_graph(sectionName, section_id) {
        $("#history_div_" + section_id).addClass('d-none');
        $("#main_div_" + section_id).addClass('d-none');
        $("#graph_div_" + section_id).removeClass('d-none');
        $(`#graph_section_${section_id}`).empty();
        const transDate = trans[0];
        labelsCollection.map(l => {
            loadGraph(sectionName, record, l, transDate, section_id);
            return;
        })

    }

	function showPanel(id) {
		$("#datefordoctorconsult").css('display','d-none');
		let panel =parseInt(id);


		$("#li_menu_id").removeClass("menu-header_section");
		$("#li_menu_id").addClass("menu-header_section1");
		if (panel===1 || panel===87|| panel===85 || panel===108||panel===93||panel===96||panel===38) {
			$("#profileSection").removeClass('d-none');
			$("#reportPanel").addClass('d-none');
			$("#li_menu_97").removeClass("active");
			$("#li_menu_12").removeClass("active");
			$("#li_menu_id").removeClass("menu-header_section1");
			$("#li_menu_id").addClass("menu-header_section");
			$("#li_menu_id").addClass("active");

			$(".content-wrap section").removeClass("content-current");
			$("#opd_top_nav li").removeClass("tab-current")
			if(panel ===87){
				$("#opd_top_nav li:nth-child(1)").addClass("tab-current")
				$("#caseHistory").addClass("content-current");
				// $("#caseHistory").click();
			}
			if(panel ===108){
				$("#opd_top_nav li:nth-child(2)").addClass("tab-current")
				$("#doctorConsult").addClass("content-current");
				// $("#doctorConsult").click();
			}
			if(panel ===93){
				$("#opd_top_nav li:nth-child(3)").addClass("tab-current")
				$("#medication").addClass("content-current");
				// $("#medication").click();
			}
			if(panel ===96){
				$("#opd_top_nav li:nth-child(4)").addClass("tab-current")
				$("#investigation").addClass("content-current");
				// $("#tabInvestigation").click();
			}
			if(panel ===38){
				$("#opd_top_nav li:nth-child(5)").addClass("tab-current")
				$("#restoreHealth").addClass("content-current");
				// $("#restoreHealth").click();
			}
		}
		$("#printButton1").hide();
		$("#ShowForm img").hide()
		if (panel===97 ||panel===12 ||panel===10) {
			$("#IPDBillButton").hide();
			$("#datefordoctorconsult").hide();
			$("#profileSection").addClass('d-none');
			$("#reportPanel").removeClass('d-none');

			if(panel ==12){
				$("#ShowForm img").show()
				$("#datefordoctorconsult").css('display','block');
			}
			if(panel == 97){
				$("#BillingInfoData1").hide();
			}
		}
	}

	function get_DepartMent_Menus(department_id, section_id, queryparameter_hidden) {

		var formData = new FormData();
		formData.set("department_id", department_id);
		formData.set("section_id", section_id);
		formData.set("queryparameter_hidden", queryparameter_hidden);
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>getDepartmentMenus",
			dataType: "json",
			data: formData,
			contentType: false,
			processData: false,
			success: function (result) {

				if (result.status === 200) {
					$("#form_navigation_menu").empty();
					var userdata = result.data;
					var pathArray = window.location.pathname.split('/');
					if (userdata != null) {

						userdata.forEach((e, i) => {

							if (section_id == "null" || section_id == 0) {
								if (i == 0) {
									section_id = userdata[i].id;
								}
							}
							var newOBJ = null;
							if (userdata[i].query_string_parameter != null && userdata[i].query_string_parameter != "") {
								var stringParaData = userdata[i].query_string_parameter;
								var data_array = stringParaData.split(',');
								var Obj = {};
								var queryparameter_hidden = $("#queryparameter_hidden").val();
								//console.log(queryparameter_hidden);
								let qObject = JSON.parse(atob(queryparameter_hidden));
								//    console.log(qObject);
								$.each(data_array, function (i, field) {
									var field1 = field.split(":");
									if (qObject.hasOwnProperty(field1[0])) {
										Obj[field1[0]] = qObject[field1[0]];
									}

								});
								//console.log(Obj);
								newOBJ = JSON.stringify(Obj);
								newOBJ = btoa(newOBJ);

							}

							let isActive = false;
							if ("html_navigation" === pathArray[1]) {
								if (department_id === pathArray[2]) {
									if (userdata[i].id === pathArray[3]) {
										isActive = true;
									}
								}
							}
							let style = isActive ? 'color: white;text-decoration: none;' : 'color: black;text-decoration: none;'
							let active = isActive ? 'menu-header_section active' : 'menu-header_section1';
							var ChangeUrl = btoa(`<?php echo base_url(); ?>html_navigation/${department_id}/${userdata[i].id}/${newOBJ}`);
							if (parseInt(userdata[i].id) == 97 || parseInt(userdata[i].id) == 12 || parseInt(userdata[i].id) == 10) {
								var template = `
								<a class="a_menu a_menu22" id="a_menu_${userdata[i].id}"
                    onclick="get_forms(${userdata[i].id},0,'${newOBJ}',${department_id},'${ChangeUrl}'),showPanel(${userdata[i].id})" class=""
								  style="cursor:pointer">
									<li  id="li_menu_${userdata[i].id}" class="li_menu menu-header mt-2 ${active}">${userdata[i].name}</li>
								</a>`;
								var templateMobileView = `
								<a onclick="get_forms(${userdata[i].id},0,'${newOBJ}',${department_id},'${ChangeUrl}'),showPanel(${userdata[i].id})" class="btn btn-icon" data-toggle="tooltip" data-placement="top" title="${userdata[i].name}"
								style="<?php echo $this->uri->segment(1) == 'html_navigation/<?php echo $department_id?>/<?php echo $userdata[i].id?>' ? 'color: white;text-decoration: none;' : 'color: black;text-decoration: none;'; ?>">
									<i class="fas fa-file-medical-alt"></i>
								</a>
								`;
							}
							if(parseInt(userdata[i].id) == 87){
								var template = `
								<a class="a_menu a_menu22" id="a_menu_${userdata[i].id}"
                    onclick=" get_DepartMent_Menus(${department_id},${userdata[i].id},'${ChangeUrl}'),
                    get_forms(${userdata[i].id},0,'${newOBJ}',${department_id},'${ChangeUrl}'),showPanel(${userdata[i].id}),showMenu()" class=""
								  style="cursor:pointer">
									<li  id="li_menu_${userdata[i].id}" class="li_menu menu-header mt-2 ${active}">Profile</li>
								</a>`;
								var templateMobileView = `
								<a onclick="get_forms(${userdata[i].id},0,'${newOBJ}',${department_id},'${ChangeUrl}'),showPanel(${userdata[i].id})" class="btn btn-icon" data-toggle="tooltip" data-placement="top" title="${userdata[i].name}"
								style="<?php echo $this->uri->segment(1) == 'html_navigation/<?php echo $department_id?>/<?php echo $userdata[i].id?>' ? 'color: white;text-decoration: none;' : 'color: black;text-decoration: none;'; ?>">
									<i class="fas fa-file-medical-alt"></i>
								</a>
								`;
							}
							if (parseInt(userdata[i].id) == 87) {
								$("#tabCaseHistory").on('click', function (e) {
									showPanel(parseInt(userdata[i].id) );
									get_forms(userdata[i].id, 0, newOBJ, department_id, ChangeUrl, 'tabCaseHistoryPanel');

								})
							}

							if (parseInt(userdata[i].id) == 93) {
								$("#tabMedication").on('click', function (e) {
									showPanel(parseInt(userdata[i].id) );
									get_forms(userdata[i].id, 0, newOBJ, department_id, ChangeUrl, 'tabMedicationPanel');

								})
							}

							if (parseInt(userdata[i].id) == 96) {
								$("#tabInvestigation").on('click', function (e) {
									showPanel(parseInt(userdata[i].id) );
									get_forms(userdata[i].id, 0, newOBJ, department_id, ChangeUrl, 'tabInvestigationPanel');

								})
							}

							if (parseInt(userdata[i].id) == 108) {
								$("#tabDoctorConsult").on('click', function (e) {
									showPanel(parseInt(userdata[i].id) );
									get_forms(userdata[i].id, 0, newOBJ, department_id, ChangeUrl, 'tabDoctorConsultPanel');

								})
							}
							// if (parseInt(userdata[i].id) == 97) {
							// 		showPanel(parseInt(userdata[i].id) );
							// 		get_forms(userdata[i].id, 0, newOBJ, department_id, ChangeUrl, 'form_data');
							// }
							$("#tabRestoreHealth").on('click', function (e) {
								// restore health tab
								loadRestorePanel();
							});

							$("#form_navigation_menu").append(template);
							$("#from_navigation_menu_mobile").append(templateMobileView);

						});
						if (localStorage.getItem("section_type") == 1) {
							showPanel(section_id);
							if(section_id==87){
								get_forms(section_id, 0, null, null, null, 'tabCaseHistoryPanel');
							}
							if(section_id==93){
								get_forms(section_id, 0, null, null, null, 'tabMedicationPanel');
							}
							if(section_id==96){
								get_forms(section_id, 0, null, null, null, 'tabInvestigationPanel');
							}
							if(section_id==108){
								get_forms(section_id, 0, null, null, null, 'tabDoctorConsultPanel');
							}
							if(section_id==97)
							{
								get_forms(section_id, 0, null, null, null, 'form_data');
							}

						}

					}
					if (result.hasOwnProperty("report")) {
						let report = result.report;
						report.forEach(r => {

							let department_id = r.id;
							var newOBJ = null;
							var Obj = {};
							var stringParaData = r.query_param;
							var data_array = stringParaData.split(',');
							var queryparameter_hidden = $("#queryparameter_hidden").val();
							let qObject = JSON.parse(atob(queryparameter_hidden));

							$.each(data_array, function (i, field) {
								if (qObject.hasOwnProperty(field)) {
									Obj[field] = qObject[field];
								}
							});

							newOBJ = JSON.stringify(Obj);
							newOBJ = btoa(newOBJ);

							let isActive = false;

							if ("html_navigation" === pathArray[1]) {
								if (department_id === pathArray[2]) {
									if (userdata[i].id === pathArray[3]) {
										isActive = true;
									}
								}
							}

							let style = isActive ? 'color: white;text-decoration: none;' : 'color: black;text-decoration: none;'
							let active = isActive ? 'menu-header_section active' : 'menu-header_section1';
							var template = `
								<a class="a_menu" id="a_menu_r${department_id}"
                                onclick="getDataofReport(${department_id},'${newOBJ}'),showPanel(${department_id})"
								  style="cursor:pointer">
									<li  id="li_menu_r${department_id}" class="li_menu menu-header mt-2 ${active}">${r.Report_name}</li>
								</a>`;
							var templateMobileView = `
								<a href="<?php echo base_url(); ?>GetQueryParamDataReport/${department_id}/${newOBJ}" class="btn btn-icon" data-toggle="tooltip" data-placement="top" title="${r.Report_name}"
								style="<?php echo $this->uri->segment(1) == 'html_navigation/<?php echo $department_id?>/<?php echo $userdata[i].id?>' ? 'color: white;text-decoration: none;' : 'color: black;text-decoration: none;'; ?>">
									<i class="fas fa-file-medical-alt"></i>
								</a>
								`
							$("#form_navigation_menu").append(template);
							$("#from_navigation_menu_mobile").append(templateMobileView);
						});
						if (localStorage.getItem("section_type") == 2) {
							var pathArray = window.location.pathname.split('/');
							getDataofReport(pathArray[3], pathArray[4]);
							$("#profileSection").hide();
						}
					}
					checkBillingStatus();
					// [].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
					//     new CBPFWTabs(el);
					// });
				} else {
					app.errorToast(result.body);
				}
			}, error: function (error) {

				app.errorToast('something went wrong');
			}
		});

	}
	function showMenu()
	{
		var section_id = $("#section_id").val();
		$("#profileSection").show();
		get_forms(section_id, 0, null, null, null, 'tabCaseHistoryPanel');
	}
	function firstTab() {
		$("#tabCaseHistory").click();
	}

	function getallDates() {
		var p_id = localStorage.getItem("patient_id");
		var patient_adharnumber = localStorage.getItem("patient_adharnumber");
		$.ajax({
			type: "POST",
			url: '<?= base_url("ReportMakerController/GetallDatesofPatient")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id,patient_adharnumber},
			success: function (result) {

				if (result.status == 200) {
					$("#datefordoctorconsult1").html("");
					$("#datefordoctorconsult1").html(result.data);
					$("#datefordoctorconsult2").html("");
					$("#datefordoctorconsult2").html(result.dataIPD);

				} else {
					$("#datefordoctorconsult1").html("");
					$("#datefordoctorconsult1").html(result.data);
					$("#datefordoctorconsult2").html("");
					$("#datefordoctorconsult2").html(result.dataIPD);
				}
			}
		});
	}

	function getDataofReport(report_id, object) {
		if($("#ckteleconsult").prop("checked") == true){
			$('#ckteleconsult').click();
			$('.odpsction').show();
			$('.idpsction').hide();
		}
		var trans_date = $("#datefordoctorconsult1").val();
		var formData = new FormData();
		$("#IPDBillButton").show();
		formData.set("trans_date", trans_date);
		formData.set("object", object);
		localStorage.setItem("section_type", 2);

		$(".a_menu").css({
			"text-decoration": 'none',
			"color": 'black'
		});
		$('.li_menu').addClass('menu-header_section1').removeClass('menu-header_section active');
		$('#a_menu_r' + report_id).css({
			"text-decoration": 'none',
			"color": 'white'
		});
		$("#li_menu_r" + report_id).addClass('menu-header_section active').removeClass('menu-header_section1');
		if(report_id == 10){
			app.request(base_url + "GetBillingInfoData", formData).then(response => {
				if (response.status === 200) {
					$("#datefordoctorconsult").hide();
					$("#BillingInfoData").show();
					$("#BillingInfoData1").show();
					$("#IPDBillButton").hide();
					$("#form_data_report").hide();
					$("#form_data").html("");
					$("#form_data1").html("");
					var ChangeUrl = (`<?php echo base_url(); ?>html_navigation/18/13/${object}`);
					window.history.pushState('', '', ChangeUrl);
					$("#form_data_report").hide();
					$("#printButton").hide();
					$("#printButton1").hide();
					$("#CloseBillButton").hide();
					$("#BillingInfoData").html(response.data);

					// $("#form_data").html(data.section_html);
					$(".removeDeleteButton").remove();
					$(".ui-resizable-handle").remove();
					$("[contenteditable='true']").each((i, e) => {
						$(e).attr("contenteditable", false);
					});
				} else {

				}
			});

		}else{
			$("#BillingInfoData").hide();
			$("#BillingInfoData1").hide();
			app.request(base_url + "GetPrescriptionReport", formData).then(response => {
				if (response.status === 200) {
					$("#datefordoctorconsult").show();
					$("#IPDBillButton").show();
					$("#form_data_report").show();
					$("#form_data").html("");
					$("#form_data1").html("");
					var ChangeUrl = (`<?php echo base_url(); ?>html_navigation/18/12/${object}`);
					window.history.pushState('', '', ChangeUrl);
					$("#form_data_report").html(response.data);
					$("#printButton").show();
					$("#printButton1").hide();
					if (report_id == 12) {
						$("#CloseBillButton").show();
						$("#printButton1").show();
					} else {
						$("#CloseBillButton").hide();
						$("#printButton1").hide();
					}

					// $("#form_data").html(data.section_html);
					$(".removeDeleteButton").remove();
					$(".ui-resizable-handle").remove();
					$("[contenteditable='true']").each((i, e) => {
						$(e).attr("contenteditable", false);
					});
				} else {

				}
			});
		}

	}
	function BillinInfoChangestatus(id){
		if ($("#addtobill" + id).prop('checked') == true) {
			$("#fire-modal-Billing").modal('show');
			$("#serviceDescId").val(id);
		}else{
			var formData = new FormData();
			formData.append('confirm_service_given', '0');
			formData.append('serviceDescId', id);
			uploadServiceOrderBillingInfo(formData, 0);
		}

	}
	$("#FileUploadationForm").validate({
		rules: {
			service_file: {
				required: true,
			},
		},
		messages: {
			service_file: {
				required: "Please Select File"
			},

		},
		errorElement: 'span',
		submitHandler: function (form) {
			var file = document.getElementById("service_file");
			if (file.files.length == 0) {
				$("#radiology_diles_error").html("Please Select File");
				// app.errorToast("No files selected");
			} else {
				var formData = new FormData(form);
				formData.append('confirm_service_given', '1');
				formData.append('sample_pickup', '1');
				uploadServiceOrderBillingInfo(formData, 1);
			}

		}
	});
	function uploadServiceOrderBillingInfo(data, btnCheck) {
		app.request('<?php echo base_url()?>' + "AddServiceorderBillFile", data).then(res => {
			if (res.status === 200) {
				app.successToast(res.body);
				$("#fire-modal-Billing").modal('hide');
			} else {
				app.errorToast(res.body);
			}
		}).catch(error => {
			console.log(error);
		})
	}

	function print_div() {
		let divName = ".section-body_new";
		$('#printButton').toggleClass('d-none');
		var printContents = document.querySelector(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
		$('#printButton').toggleClass('d-none');
	}

	function printDiv() {
		let divName = ".section-body1";
		$('#ipdprintbtn').toggleClass('d-none');
		var printContents = document.querySelector(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
		$('#ipdprintbtn').toggleClass('d-none');
		$('#myModalIPDBill').hide();
	}
	function print_Newdiv(){
		var p_id = localStorage.getItem("patient_id");
		var trans_date = $("#datefordoctorconsult1").val();
		$.ajax({
			type: "POST",
			url: '<?= base_url("getPatientBIllingPrintDataNew")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id: p_id,trans_date:trans_date},
			success: function (result) {

				if (result.status == 200) {
					var userdata = result.data;

					$("#billingPrint").html(userdata);
				} else {
					// app.errorToast('No data found');
				}
			}
		});
		let divName = "#billingPrint";
		var printContents = document.querySelector(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	function Close_billing() {
		var p_id = localStorage.getItem("patient_id");
		$.ajax({
			type: "POST",
			url: '<?= base_url("ReportMakerController/ChangeBillingOpen")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id},
			success: function (result) {

				if (result.status == 200) {

					alert(result.body);
					checkBillingStatus();
				} else {
					alert(result.body);
				}
			}
		});
	}

	function checkBillingStatus() {
		var p_id = localStorage.getItem("patient_id");
		$.ajax({
			type: "POST",
			url: '<?= base_url("check_billing_status")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id},
			success: function (result) {

				if (result.status == 200) {
					var value = result.value;
					if (value == 1) {
						$("#CloseBillButton").html("Billing Already Close");
						$('.a_menu22').hide();
					} else {
						$("#CloseBillButton").html("Close Billing");
						$('.a_menu22').show();
					}
				} else {

				}
			}
		});
	}

	function GetIPDBill(patient_id) {
		// $("#myModalIPDBill").modal('show');
		document.getElementById("patient_nameSidebar").innerText = localStorage.getItem("patient_name");
		document.getElementById("patient_adharSidebar").innerText = localStorage.getItem("patient_adharnumber");
		// var patient_id = localStorage.getItem("patient_id");
		$("#billingMaster_patient_id").val(patient_id);
		$("#patient_list").val(patient_id);
		$("#p_id").val(patient_id);

		getPatientBIllingData();
		getAccomodationTableBIllingData();
		getmedicineAndConsumablesTableBilling();
		getserviceOrderCollectionTableBilling();
		getGrandTotal();
		check_billing_status();
	}

	function getAccomodationTableBIllingData() {
		var p_id = $("#billingMaster_patient_id").val();
		$.ajax({
			type: "POST",
			url: '<?= base_url("getAccomodationTableBIllingData")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id: p_id},
			success: function (result) {

				if (result.status == 200) {
					$("#accomodationTableBilling").html(result.data);
				} else {
					$("#accomodationTableBilling").html(result.data);
				}
			}
		});
	}

	function getserviceOrderCollectionTableBilling() {
		var p_id = $("#billingMaster_patient_id").val();
		$.ajax({
			type: "POST",
			url: '<?= base_url("getserviceOrderCollectionTableBilling")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id: p_id},
			success: function (result) {

				if (result.status == 200) {
					$("#serviceOrderCollectionTableBilling").html(result.data);
					$("#roombilltable").html(result.data_consult);
				} else {
					$("#serviceOrderCollectionTableBilling").html(result.data);
					$("#roombilltable").html(result.data_consult);
				}
			}
		});
	}

	function getmedicineAndConsumablesTableBilling() {
		var p_id = $("#billingMaster_patient_id").val();
		$.ajax({
			type: "POST",
			url: '<?= base_url("getmedicineAndConsumablesTableBilling")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id: p_id},
			success: function (result) {

				if (result.status == 200) {
					$("#medicineAndConsumablesTableBilling").html(result.data);
				} else {
					$("#medicineAndConsumablesTableBilling").html(result.data);
				}
			}
		});
	}

	function getPatientBIllingData() {
		var p_id = $("#billingMaster_patient_id").val();
		$.ajax({
			type: "POST",
			url: '<?= base_url("getPatientBIllingData")?>',
			dataType: "json",
			async: false,
			cache: false,
			data: {p_id: p_id},
			success: function (result) {

				if (result.status == 200) {
					var userdata = result.data;
					$("#billingMasterPatientName").html(result.patient_name);
					$("#billingNo").html('BL_' + result.patient_id);
					// console.log(result.patientAge);
					if (result.patient_age == 0) {
						$("#patientAge").html("DOB Not available");
					} else {
						$("#patientAge").html(result.patient_age);
					}

					$("#billingDate").html('<?php echo date('d M Y'); ?>');
					if (userdata[0]['adhar_no'] != null || userdata[0]['adhar_no'] !== "") {
						$("#billingPatient_id").html(userdata[0]['adhar_no']);
					}

					$("#case_id").html(result.patient_id);
					$("#admission_date").html(result.admission_date);
					$("#admission_time").html(result.admission_time);
					$("#discharge_date").html(result.discharge_date);
					$("#discharge_time").html(result.discharge_time);

				} else {
					app.errorToast('No data found');
				}
			}
		});
	}

	function getGrandTotal() {
		var serviceOCSubtotal = $("#serviceOCSubtotal").val();
		var accomodationSubtotal = $("#accomodationSubtotal").val();
		var medicineSubtotal = $("#medicineSubtotal").val();
		var doctorconsultsubtotal = $("#doctorconsultsubtotal").val();
		// var serviceTotal=$("#serviceOCSubtotal").val();

		var grand_total = parseInt(serviceOCSubtotal) + parseInt(accomodationSubtotal) + parseInt(medicineSubtotal) + parseInt(doctorconsultsubtotal);
		// var discount = grand_total;
		var dis_type=document.querySelector('input[name="discount"]:checked').value;
		if(dis_type==1)
		{
			var discount = grand_total*( (100-$("#discount_amt").val()) / 100 );
		}
		else
		{
			var discount = grand_total-$("#discount_amt").val();
		}

		$("#subTotal").html(grand_total);
		$("#grandTotal").html(discount);
	}

	function ToggleIPD(){

		if($("#ckteleconsult").prop("checked") == true){
			$('.idpsction').show();
			$('.odpsction').hide();
			var patient_id= $("#datefordoctorconsult2").val();
			GetIPDBill(patient_id);
		}else{
			$('.idpsction').hide();
			$('.odpsction').show();
		}

	}
	function hideclassremover(e){
		var div = $('div.case_history');
		if( $(e).is(':checked') )
		{
			div.removeClass('hide_class');
			$('.case_history_checkbox').attr( 'checked', true )
		}
		else
		{
			div.addClass('hide_class');
			$('.case_history_checkbox').removeAttr('checked');
		}
	};

</script>
<script type="text/javascript">
	// manually data fetch for investigation amount
	function save_Percentage() {
		// var p_id = $("#billingMaster_patient_id").val();
		var formdata=document.getElementById('percentage_form');
		var queryparameter_hidden = $("#queryparameter_hidden").val();
		let formData = new FormData(formdata);
		formData.set("p_id", localStorage.getItem("patient_id"));
		// console.log(localStorage.getItem("patient_id"));
		$.ajax({
			type: "POST",
			url: '<?= base_url("savePatientBIllingDiscountData")?>',
			dataType: "json",
			data:formData,
			processData: false,
			contentType: false,
			success: function (result) {

				if (result.status == 200) {
					// var userdata=result.data;
					app.successToast('discount changes saved');
					getDataofReport(12,queryparameter_hidden);
				} else {
					app.errorToast('No data Changes');
				}
			}
		});
	}

</script>

<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
	.select2.select2-container.select2-container--default {
		width: 100% !important;
	}

	.accordion .accordion-header[aria-expanded="true"] {
		box-shadow: 0 2px 6px #891635 !important;
		background-color: #891635 !important;
	}
	.modal_header
	{
		background: #891635;
		color: white;
	}
	.modal_close
	{
		color: white;
	}
	@media (min-width: 768px) {
	  .modal-xl {
	    width: 90%;
	   max-width:1200px;
	  }
	}
</style>

<div class="main-content main-content1">
	<section class="section">
		<div class="section-header card-primary" style="border-top: 2px solid #891635">
			<h1 id="department_name">-</h1>
		</div>
		<div class="section-body">
			<div class=" justify-content-center">
				<div class="">
					<div class="card">
						<div class="card-body">
							<input type="hidden" id="department_id" name="department_id"
								   value="<?= $department_id ?>">
							<!--							<form id="data_form" method="post" data-form-valid="save_form_data">-->
							<!--								<div class="card-body">-->
							<!--									<input type="hidden" id="department_id" name="department_id"-->
							<!--										   value="-->
							<?php //echo $department_id ?><!--">-->
							<!--									<input type="hidden" id="patient_id" name="patient_id"-->
							<!--										   value="-->
							<?php //echo $department_id ?><!--">-->


							<div id="button_data"></div>
							<!--								</div>-->
							<!--								<div class="card-footer text-right">-->
							<!--									<button class="btn btn-primary mr-1" type="submit">Submit-->
							<!--									</button>-->
							<!--									<button class="btn btn-secondary" type="reset">Reset</button>-->
							<!--								</div>-->
							<!--							</form>-->
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<div id="form_data"></div>
<div class="modal fade" tabindex="-1" role="dialog" id="fire-modal-1" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Are You Sure?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				This action can not be undone. Do you want to continue?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-shadow" id="">Yes</button>
				<button type="button" class="btn btn-secondary" id="">Cancel</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('admin/templates/history_form'); ?>

<?php $this->load->view('_partials/footer'); ?>
<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#editHistoryModal').on('show.bs.modal', function (e) {
			let section_id = parseInt($(e.relatedTarget).data('section_id'));
			let history_id = parseInt($(e.relatedTarget).data('history_id'));
			let department_id = parseInt($(e.relatedTarget).data('department_id'));
			let patient_id = localStorage.getItem("patient_id");
			// get_forms(localStorage.getItem("patient_id"));
			get_forms_history(section_id, history_id, patient_id, department_id);
		});

		$("#selUser").select2({
			ajax: {
				url: "<?= base_url("FormController/get_data") ?>",
				type: "post",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						searchTerm: params.term // search term
					};
				},
				processResults: function (response) {
					return {
						results: response
					};
				},
				cache: true
			},
			minimumInputLength: 3
		});
	});
	$(document).ready(function () {
		if (document.getElementById("patient_nameSidebar")) {
			document.getElementById("patient_nameSidebar").innerText = localStorage.getItem("patient_name");
		}
		if (document.getElementById("patient_adharSidebar")) {
			document.getElementById("patient_adharSidebar").innerText = localStorage.getItem("patient_adharnumber");
		}
		if (document.getElementById("patient_profile")) {
			if (localStorage.getItem("patient_profile") !== "" && localStorage.getItem("patient_profile") !== "null") {

				document.getElementById("patient_profile").setAttribute("src", localStorage.getItem("patient_profile"));
			}
		}

		// document.getElementById("patient_id").value = localStorage.getItem("patient_id");
		get_forms(localStorage.getItem("patient_id"));
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

	function get_forms(patient_id) {
		var department_id = $("#department_id").val();

		$.ajax({
			url: "<?= base_url("getTemplateFormPersonal") ?>",
			type: "POST",
			dataType: "json",
			data: {department_id: department_id, patient_id: patient_id},
			success: function (result) {
				var data = result.data;
				if (result['code'] === 200) {
					$('#department_name').html(result.template_name);
					$("#form_data").html(data);
					$("#button_data").html(result.button_data);
					app.formValidation();
				} else {
					$("#button_data").html('');
				}
			}, error: function (error) {

				alert('Something went wrong please try again');
			}
		});
	}

	function get_other_template(template_id) {
		//http://localhost/new_covid/form_view/MQ%3D%3D
		//window.location = "<?= base_url("getTemplateForm") ?>/form_view/MQ%3D%3D";
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

	function get_other_dropdown(id, id2, id3) {
		var value = $("#" + id2).val();
		$.ajax({
			type: "POST",
			url: "<?= base_url("getdependantdropdown") ?>",
			dataType: "json",
			data: {id, value},
			success: function (result) {
				$("#" + id3).html("");
				if (result.code == 200) {

					$("#" + id3).html(result.data);

				} else {

					$("#" + id3).html(result.data);
				}
			}, error: function (error) {
				console.log("Error in Server Request Function : ", error);


			}
		});
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

	function view_main_data(section_id) {
		$("#history_btn_" + section_id).show();//main_btn
		$("#main_btn_" + section_id).hide();
		$("#history_div_" + section_id).addClass('d-none');
		$("#main_div_" + section_id).removeClass('d-none');
		$("#graph_div_" + section_id).addClass('d-none');
		$("#graph_btn_" + section_id).hide();
	}

	// user update dynamic form submission
	function update_form_data(sectionFormId) {
		// console.log('hiiiii');
		form = document.getElementById(sectionFormId);
		app.request(baseURL + "sectionUpdate", new FormData(form)).then(result => {
			var firm_data = result.firm_data;

			if (result['code'] == '200') {
				var tableName = result['tableName'];
				var section_id = result['section_id'];
				app.successToast("Save Changes");
				view_history(tableName, section_id);
				$("#editSectionButton_" + section_id).click();

			} else {
				app.errorToast("Failed To Save Data");
			}
		}).catch(error => {
			console.log(error)
			app.errorToast('Something went wrong please try again');
		})
	}
</script>

<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
	<section class="section section-body_new">
		<div class="section-body">
			<div class="row px-4">
				<div class="col-md-12">
					<input type="hidden" name="productId" id="productId" value="<?= $productID ?>"/>
					<h5 class="font-weight-bold mb-3 text-dark">PROCESS PLAN</h5>
					<div class="row" id="PROCESS_PLAN">
						<div class="col-12" id="process_plan_box_1">
							<div class="align-items-center d-flex mb-1 process_plan_box">
								<input type="radio" name="process_plan_required_1" class="ml-1 mr-2"
									   id="process_plan_required_1">
								<select class="form-control" name="process_name" id="process_name_1"
										style="width: 100%"></select>
								<input type="hidden" name="isDependOn_1" id="is_depend_on_1" value="0"/>
								<button class="btn btn-link text-dark"
										data-toggle="dropdown" aria-haspopup="true">
									<i class="fa fa-ellipsis-v  px-2" id=""
									   style="font-size: x-large;cursor: pointer;"></i>
								</button>
								<div class="dropdown-menu dropright" x-placement="right-start">
									<a class="dropdown-item" href="#">CheckList</a>
									<a class="dropdown-item" href="#"  data-toggle="modal" data-process_id="1" data-target="#templateSelectionModal">Template</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#" onclick="removeProcessItem(1)">Delete</a>
								</div>
							</div>
						</div>
					</div>
					<h6 class="font-weight-bold mb-3 text-dark text-right" onclick="PROCESS_PLAN_add_rows()"
						id="PROCESS_PLAN_add_rows">(+ to open more rows)
					</h6>

				</div>
			</div>
			<div class="row px-4">
				<div class="col-md-12">
					<button type="button" onclick="save()" class="btn btn-primary">save</button>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="templateSelectionModal"
	 aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Attach Templates</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<form id="processTemplatesForm" method="post" data-form-valid="saveTemplateDetails">
				<div class="modal-body">
					<input type="hidden" name="modal_process_id" id="modal_process_id">
					<input type="hidden" name="modal_process_row" id="modal_process_row">
					<input type="hidden" name="modal_product_id" id="modal_product_id">
					<div class="form-group col-md-12">
						<label for="service_file">Select Templates</label>
						<select class="form-control" name="process_templates[]" data-valid="required"
								multiple
								data-msg="Select templates" id="process_template_list"></select>
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


<?php $this->load->view('_partials/footer'); ?>
<script>
	let processOptions = [];
	let processList = [1];
	let processTemplateList=[{id:1,templateList:[]}];

	$(document).ready(function () {
		getProcessOptions();
		$('#templateSelectionModal').on('shown.bs.modal', function (e) {
			let id = $(e.relatedTarget).data('process_id');
			let processId = $("#process_name_" + id).val();
			let productId = $("#productId").val();

			if (!processId) {
				alert("no process selected");
			} else {
				$("#modal_process_id").val(processId);
				$("#modal_product_id").val(productId);
				$("#modal_process_row").val(id);
				templateList();
				app.formValidation();
			}
		})
	});

	function templateList() {
		app.request(base_url + "admin/getProcessOptions", null)
				.then(response => {
					processOptions = response.data;
					app.selectOption("process_template_list", "Select template", null, processOptions);
				})
				.catch(e => console.log(e))
	}

	let templateDetailsForm = null;

	function saveTemplateDetails(formData) {
		let form = new FormData(formData);
		templateDetailsForm=formData;
		// let processID=form.get("modal_process_id");
		// let productID=form.get("modal_product_id");
		let templates=form.getAll("process_templates[]");
		let index=form.get("modal_process_row");

		let findProcess = processTemplateList.find(i=>i.id === parseInt(index));
		if(findProcess){
			findProcess.templateList=templates;
		}
	}

	function PROCESS_PLAN_add_rows() {
		let box = processList.length + 1;
		let processplan = `
 <div class="col-12" id="process_plan_box_${box}">
    <div class="align-items-center d-flex mb-1 process_plan_box" >
		<input type="radio" name="process_plan_required_${box}" class="ml-1 mr-2" id="process_plan_required_${box}">
		<select class="form-control" name="process_name_${box}" id="process_name_${box}" style="width: 100%"></select>
		<input type="hidden" name="isDependOn_${box}" id="is_depend_on_${box}" value="0" />
		<button class="btn btn-link text-dark"
				data-toggle="dropdown" aria-haspopup="true">
			<i class="fa fa-ellipsis-v  px-2" id="" style="font-size: x-large;cursor: pointer;"></i>
		</button>
		<div class="dropdown-menu dropright" x-placement="right-start">
			<a class="dropdown-item" href="#">CheckList</a>
			<a class="dropdown-item"  data-toggle="modal" data-process_id="${box}" data-target="#templateSelectionModal">Template</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="#" onclick="removeProcessItem(${box})">Delete</a>
		</div>
	</div>
</div>`;
		$("#PROCESS_PLAN").append(processplan);
		processList.push(box);
		processTemplateList.push({id:box,templateList:[]});
		app.selectOption("process_name_" + box, "Select Process", null, processOptions);
	}

	function removeProcessItem(itemIndex) {
		processList = processList.filter(i => i !== itemIndex);
		processTemplateList = processTemplateList.filter(i=>i.id!==itemIndex)
		$("#process_plan_box_" + itemIndex).remove();
	}

	function getProcessOptions() {
		app.request(base_url + "admin/getProcessOptions", null)
				.then(response => {
					processOptions = response.data;
					app.selectOption("process_name_1", "Select Process", null, processOptions);
				})
				.catch(e => console.log(e))

	}

	function save() {
		let processListData = [];
		for (let i = 0; i < processList.length; i++) {
			let isRequired = $("#process_plan_required_" + processList[i]).is(":checked");
			let processId = $("#process_name_" + processList[i]).val();
			let isDependOn = $("#is_depend_on_" + processList[i]).val();
			let templates= processTemplateList.find(k=>k.id === processList[i])
			let obj = {
				is_required: isRequired,
				process_id: processId,
				is_depend_on: isDependOn,
				template_id: [],
				sequence: i
			}
			if(templates){
				obj.template_id = templates.templateList;
			}
			processListData.push(obj);
		}
		let form = new FormData();
		form.set("product_id", $("#productId").val());
		form.set("process", JSON.stringify(processListData));
		app.request(base_url + "admin/saveProcessDetails", form)
				.then(response => {
					if (response.status === 200) {
						app.successToast(response.body);
					} else {
						app.errorToast(response.body);
					}

				}).catch(e => console.log(e))
	}


</script>

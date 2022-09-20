let queryparameterOptions = [];

function SaveTemplate() {

	let method = 1;
	let id = '';
	if ($("#template_id").val() != 0 && $("#template_id").val() != "") {
		method = 2;
		id = $("#template_id").val();
	}

	let data = pageConfiguration;
	let template_name = $("#template_name").val();
	let formdata = new FormData();
	formdata.set('data', JSON.stringify(data));
	formdata.set('template_name', template_name);
	formdata.set('configuration', $("input[type='radio'][name='configuration']:checked").val());
	formdata.set('allQueryParamFields', JSON.stringify(allQueryParamFields));
	formdata.set('method', method);
	formdata.set('id', id);
	app.request("saveConfiguration", formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			window.location.href = baseURL + 'template_list';

		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function isEmpty(obj) {
	return Object.keys(obj).length === 0;
}

function getForm() {
	let id = 14;
	ShowForm(id);
}

function ShowForm(id) {
	let formdata = new FormData();
	formdata.set('id', id);
	return app.request("ShowForm", formdata).then(res => {
		if (res.status === 200) {
			// app.successToast(res.body);
			let data = res.data;
			let template = data.template_structure;
			let configuration = data.configuration;
			$("#templateName").html(data.template_name);
			dataTableConfigurationObject = [];
			// multipleParameterModal=[];
			loadFinalOutput(template, id, configuration, 'finalFormat');
		} else {
			app.errorToast(res.body);
		}
		if (id == 118 || id == 146) {
			if (id == 118) {
				getLeaseTransferCount();
				getAcceptdataCount();
			} else if (id == 146) {
				getLeaseTransferCount();
				getAcceptanceItemCount();
			}
		}


	}).catch(error => console.log(error));
}

function templateForm(id, divId, onload = null) {
//	promise
	return new Promise((resolve, reject) => {
		let formdata = new FormData();
		formdata.set('id', id);
		app.request("ShowForm", formdata).then(res => {
			if (res.status === 200) {
				// app.successToast(res.body);
				let data = res.data;
				let template = data.template_structure;
				let configuration = data.configuration;
				let structure = JSON.parse(template);
				loadFinalOutput(template, id, configuration, divId, onload);
				// let content =loadInputStructure(structure,id,configuration);
				// loadSelectSectionSort(structure);
			}
		}).catch(error => console.log(error));
		resolve(true);
	});
}

// function getTemplateFormData(routeItem,itemObject)
// {
// 	return new Promise((resolve, reject) => {
// 		app.request(routeItem, itemObject).then(res => {
// 				// app.successToast(res.body);
// 				resolve(res);
//
// 		}).catch(error => console.log(error));
// 	});
// }
var hideShowArray = [];

function loadFinalOutput(template, form_id, conf, divId, onload = null) {

	let hide_class = '';
	if (onload != null && onload == 0) {
		hide_class = 'style="display:none"';
	}
	let structure = JSON.parse(template);
	let finalFormat = $("#" + divId);
	let finalTemplate = '';

	if (conf == 1) {
		finalTemplate = `<form class="p-3" id="templateForm${form_id}" ${hide_class} data-form-valid="saveFormData" method="post" enctype="multipart/form-data">
					<input type="hidden" name="update_id" id="${form_id}_update_id">`;
	} else {
		finalTemplate = `<div class="container" id="templateForm${form_id}">`;
	}

	finalTemplate += `<input type="hidden" name="template_id" id="template_id" value="${form_id}">`;
	var newFormArray = [];
	var queryDropFormArray = [];
	var excelReportFormArray = [];
	var inputSearchArray = [];
	var modalButtonArray = [];
	var dropdownButtonArray = [];
	var addmorearray = [];
	Promise.all(structure.rows.sort((a, b) => a.seqNo - b.seqNo).map((row) => {
		return Promise.all(row.cols.map(async columnObject => {
			let column_id = columnObject.id;
			let hiddenRow = structure.hiddenFields.find(h => (h.row === row.id && h.col === columnObject.id));
			let content = ``;
			let hidden_content = '';
			if (hiddenRow) {
				let HiddenOption = hiddenRow.element.default_value_option;
				let HiddenValue = hiddenRow.element.default_value;
				if (HiddenOption == 'session_value') {
					let seesioDataValue = $("#sess_" + HiddenValue).val();
					hidden_content = `<input type="hidden" name="hidden${column_id}" id="hidden${column_id}" value="${seesioDataValue}"/>`;
				} else {
					hidden_content = `<input type="hidden" name="hidden${column_id}" id="hidden${column_id}" value="${HiddenValue}"/>`;
				}
			}
			let templateDefault = '';
			let defaultHideDiv = '';

			if (columnObject.element != null) {

				let type = columnObject.element.type;
				let id = columnObject.element.id;
				let label = columnObject.element.label;
				let checkbox = columnObject.element.checkbox;
				let placeHolder = columnObject.element.placeholder;
				let min = columnObject.element.min_value;
				let max = columnObject.element.max_value;
				let default_value = columnObject.element.default_value;
				let date_default_option = columnObject.element.default_value_option;
				let option_array = columnObject.element.option_array;
				let handson_id = columnObject.element.handson_id;
				let modalButtonId = columnObject.element.modalButtonId;

				let eleData = columnObject.element.addmore_option_array;

				let minLength = '';
				if (columnObject.element.hasOwnProperty('min_length')) {
					minLength = columnObject.element.min_length;
				}
				let maxLength = '';
				if (columnObject.element.hasOwnProperty('max_length')) {
					maxLength = columnObject.element.max_length;
				}
				let defaultHide = '';
				if (columnObject.element.hasOwnProperty('defaultHide')) {
					defaultHide = columnObject.element.defaultHide;
				}
				let hideshowColumns = '';
				let showHideOnchange = '';
				if (columnObject.element.hasOwnProperty('hideshow_array')) {
					hideshowColumns = columnObject.element.hideshow_array;
					if (hideshowColumns != '' && hideshowColumns != null) {
						hideShowArray.push({
							templateID: form_id,
							columnID: column_id,
							rowID: row.id,
							columnObjectID: columnObject.id,
							hideshowColumns: hideshowColumns
						});
						showHideOnchange = `onchange="showHideFormColumns(this.value,'${columnObject.id}')"`;
					}
				}

				if (defaultHide != '') {
					defaultHideDiv = `style="display:none;"`;
				}


				let required = '';
				let rule = '';
				let ruleMsg = '';
				if (checkbox == 1) {
					rule = 'required';
					ruleMsg = 'Please Fill this Field';
				}

				if (columnObject.element.hasOwnProperty('custom_validation')) {
					if (columnObject.element.custom_validation !== "" && columnObject.element.custom_validation !== null) {
						let customValidationMethod = columnObject.element.custom_validation.split('||');
						let customValidation = '';
						if (rule !== "") {
							customValidation = '|' + customValidationMethod[0];
						} else {
							customValidation = customValidationMethod[0];
						}
						rule += customValidation;
						if (customValidationMethod.length > 1) {
							ruleMsg += '|' + customValidationMethod[1];
						} else {
							ruleMsg += 'Please Check the Field';
						}
					}
				}

				if (rule !== "") {
					required = 'data-valid="' + rule + '" data-msg="' + ruleMsg + '"';
				}
				let today_date = new Date();
				today_date = moment().format('YYYY-MM-DD');
				switch (type) {
					case "text":
						content = `<div class="form-group">
									<label>${label}</label>
									<input type="text" name="text${column_id}" id="text${column_id}" placeholder="${placeHolder}" ${required} class="form-control">
								</div>`;
						break;
					case "email":
						content = `<div class="form-group">
									<label>${label}</label>
									<input type="email" name="email${column_id}" id="email${column_id}" placeholder="${placeHolder}" ${required} class="form-control">
								</div>`;
						break;
					case "number":
						let min_val = '';
						let max_val = '';
						if (min != null && min != '') {
							min_val = `min="${min}"`;
						}
						if (max != null && max != '') {
							max_val = `max="${max}"`;
						}
						let min_length = '';
						let max_length = '';
						if (minLength != null && minLength != '') {
							min_length = `minlength="${minLength}"`;
						}
						if (maxLength != null && maxLength != '') {
							max_length = `maxlength="${maxLength}"`;
						}

						content = `<div class="form-group">
									<label>${label}</label>
 									<input type="number" name="number${column_id}" id="number${column_id}" ${min_val} ${max_val} ${min_length} ${max_length} ${required} class="form-control">
									</div>`;
						break;
					case "textarea":
						content = `
									<div class="form-group">
									<label>${label}</label>
									<textarea class="form-control" name="textarea${column_id}" id="textarea${column_id}" rows="5" cols="5" placeholder="${placeHolder}" ${required}></textarea>
									</div>
									`;
						break;
					case "date":
						if (date_default_option == 'today_date') {
							content = `<div class="form-group">
									<label>${label}</label>
									<input type="date" name="date${column_id}" id="date${column_id}" min="${today_date}" max="${today_date}" value="${today_date}" ${required} class="form-control">
									</div>`;
						} else if (date_default_option == 'specific_date') {
							content = `<div class="form-group">
									<label>${label}</label>
									<input type="date" name="date${column_id}"  id="date${column_id}" value="${default_value}" min="${default_value}" max="${default_value}" ${required} class="form-control">
									</div>`;
						} else {
							content = `<div class="form-group">
									<label>${label}</label>
									<input type="date" name="date${column_id}" id="date${column_id}" min="${min}" max="${max}" ${required} class="form-control">
									</div>`;
						}
						break;
					case "file":
						content = `<div class="form-group">
									<label>${label}</label>
									<input type="file" name="file${column_id}[]" id="file${column_id}" ${required} class="form-control">
									<div class="small" id="div_file${column_id}"></div></div>`;
						break;
					case "singleSelection":
						content = `<div class="form-group">
									<label>${label}</label>
									<select name="singleSelection${column_id}" id="singleSelection${column_id}" ${required} class="form-control select2" ${showHideOnchange}>
									</select>
									</div>
									`;
						break;
					case "multipleSelection":
						content = `<div class="form-group">
									<label>${label}</label>
									<select name="multipleSelection${column_id}[]" id="multipleSelection${column_id}" ${required} multiple class="form-control select2">
									</select>
									</div>
									`;
						break;
					case "radio":
						content = `
									<div class="form-group">
									<label>${label}</label>
									<div class="form-group m-2">`;
						if (option_array != null && option_array != '') {
							option_array.map(e => {
								content += `
							<input type="radio" name="radio${column_id}" class="m-2" value="${e.id}" ${required} id="radio${e.id}">${e.text}
							`;
							});
						}
						content += `
									</div></div>`;
						break;
					case "checkbox":
						content = `
									<div class="form-group">
									<label>${label}</label>
									<div class="form-group m-2">`;
						if (option_array != null && option_array != '') {
							option_array.map(e => {
								content += `
							<input type="checkbox" class="m-2" name="checkbox${column_id}[]" value="${e.id}" ${required} id="checkbox${e.id}">${e.text}
							`;
							});
						}
						content += `
									</div></div>`;
						break;
					case "button":
						content = `
									<div class="form-group">
									<button type="submit" class="btn btn-primary" data-form="${form_id}" id="button${column_id}" style="margin-top: 30px">Save</button>
									</div>`;
						break;
					case "hidden":
						content = hidden_content;
						break;
					case "datatable":
						if (onload != 0) {
							loadDataTable(form_id, row.id, columnObject.id, columnObject.size);
						}
						break;
					case "queryDropdown":
						queryDropFormArray.push({
							templateID: form_id,
							columnID: column_id,
							rowID: row.id,
							columnObjectID: columnObject.id,
							element: columnObject.element,
							multiple: columnObject.multiple
						});
						break;
					case "templateForm":

						newFormArray.push({
							templateID: default_value,
							onload: `${date_default_option}`,
							divID: `${form_id}-drop-zone-${columnObject.id}`
						});
						templateDefault = default_value;
						break;
					case "Handsontable":
						content = `<div class="row">
									<div class="col-12">
									<div class="form-group" data-value="${handson_id}" id="Handsontable${form_id}">
									</div>
									</div>
									<div class="col-12">
									<div class="form-group" id="totalDiv" ${hide_class}><label>Total</label><input type="text" name="totalVal" id="totalVal" class="form-control"></div>										
									</div>
									<div class="col-12">
									<button id="create_template1" class="btn btn-primary handsonbtn${form_id} float-right ml-3 roundCornerBtn4" ${hide_class} type="button" onclick="SaveTransaction()"><i class="fa fa-save"></i> Save
									<button class="btn btn-default float-right roundCornerBtn4 handsonbtn${form_id}" ${hide_class}  data-dismiss="modal" aria-hidden="true">Cancel</button>
									</button>	
									</div>
									</div>
									`;

						if (onload != 0) {
							getHandson(handson_id, `Handsontable${form_id}`);
						}
						break;
					case "excel_report":
						excelReportFormArray.push({
							templateID: form_id,
							columnID: column_id,
							rowID: row.id,
							columnObjectID: columnObject.id,
							element: columnObject.element
						});
						break;
					case "inputSearch":
						inputSearchArray.push({
							templateID: form_id,
							columnID: column_id,
							rowID: row.id,
							columnObjectID: columnObject.id,
							element: columnObject.element
						});
						break;
					case "modalButton":
						content = `<div class="form-group">
									<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#templateModal" id="button${column_id}" style="margin-top: 30px" onclick="templateForm('${modalButtonId}','templateForm')"><i class="fa fa-plus"></i></button>
								</div>`;

						break;
					case "dropdownButton":
						dropdownButtonArray.push({
							templateID: form_id,
							columnID: column_id,
							rowID: row.id,
							columnObjectID: columnObject.id,
							element: columnObject.element
						});

						break;
					case "addmore":
						content = await loadAddMoreRowsData(columnObject, form_id, column_id);
						break;
				}
			} else {
				if (columnObject.html != null && columnObject.html != '') {
					var doc = new DOMParser().parseFromString(columnObject.html, "text/html");
					if (doc !== null && doc !== '' && doc !== undefined) {
						let lable_parent_id = doc.getElementsByTagName('div')[1].innerText;
						content = `<div class="section copy-control mb-0" data-lable="lable" style="border: none;">
										<div class="section-title mt-0 mb-1">${lable_parent_id}</div>
									</div>`;
					}
				}

			}

			last_column_id = columnObject.id;

			// console.log(content);
			return `<div class="col-md-${columnObject.size} col-sm-12"     
                        id="${columnObject.id}" ${defaultHideDiv}>
                        <div id="${form_id}-drop-zone-${columnObject.id}" data-templateID="${templateDefault}">
                            ${content}
                        </div>
                    </div>`
		})).then(r => {
			return `<div class="row" id="f-${row.id}">
					 ${r.join("")}
				   </div>`
		})
	})).then(r => {
		r = r.join("")
		finalTemplate += r;

		if (conf == 1) {
			finalTemplate += `</form>`
		} else {
			finalTemplate += `</div>`
		}
		finalFormat.empty();
		finalFormat.append(finalTemplate);

		loadSelectSectionSort(structure);

		if (newFormArray.length > 0) {
			newFormArray.forEach(e => {
				templateForm(e.templateID, e.divID, e.onload);
				console.log(e.onload);
			});
		}
		// for query dropdown html
		if (queryDropFormArray.length > 0) {
			queryDropFormArray.forEach(ele => {
				queryDropdownForm(ele);
			});
		}
		//for excel report html
		if (excelReportFormArray.length > 0) {
			excelReportFormArray.forEach(exc => {
				excelReportForm(exc);
			});
		}
		// for input serach
		if (inputSearchArray.length > 0) {
			inputSearchArray.forEach(exc => {
				searchInputForm(exc);
			});
		}
		// for dropdown with button html
		if (dropdownButtonArray.length > 0) {
			dropdownButtonArray.forEach(ele => {
				dropdownButtonForm(ele);
			});
		}
		app.formValidation();
	})
}

function loadSelectSectionSort(structure) {
	structure.rows.sort((a, b) => a.seqNo - b.seqNo).map((row) => {
		row.cols.map(columnObject => {
			if (columnObject.element != null) {
				let option_array = columnObject.element.option_array;
				let type = columnObject.element.type;
				let id = columnObject.element.id;
				if (type == 'singleSelection') {
					app.selectOption('singleSelection' + columnObject.id, 'Select One Option', null, option_array);
				}
				if (type == 'multipleSelection') {
					app.selectOption('multipleSelection' + columnObject.id, 'Select One Option', null, option_array);
				}
				if (type == 'queryDropdown') {

				}
			}
		})
	});
}

function getLabels() {

	return new Promise(function (resolve, reject) {
		let options = '<option selected>Select One</option>';
		let rows = pageConfiguration.rows.sort((a, b) => a.seqNo - b.seqNo).map((row) => {
			let colTemplate = row.cols.map(columnObject => {
				let hiddenRow = pageConfiguration.hiddenFields.find(h => (h.row === row.id && h.col === columnObject.id))
				if (hiddenRow) {

				}
				if (columnObject.hasOwnProperty('element')) {
					if (columnObject.element != null) {
						if (columnObject.element.hasOwnProperty('type')) {
							let option_id = columnObject.element.type + "" + columnObject.id;
							if (columnObject.element.hasOwnProperty('label')) {
								options += `<option value="${option_id}">${columnObject.element.label}</option>`;
							}
						}
					}
				}
			})
		})
		resolve(options);
	});
}

function getAllTablesNames() {
	app.request("getAllTables", null).then(res => {
		$(".Maintable").html(res.option);
		$(".Maintable").select2({
			allowClear: true
		});
		$("#query_table").html(res.option);
		$("#query_table").select2({
			allowClear: true
		});
	}).catch(error => console.log(error));
}

let options = '';

function getButtonData(Maincnt) {
	let table = $("#toTable" + Maincnt).val();
	getTableFields(table).then(res => {
		let fields = res;
		let html = `<div class="row">
						<input type="hidden" name="querystring" id="queryString${Maincnt}">
						<div class="col-lg-12 col-md-12 col-sm-12 d-flex">
						<div class="col-md-2">
						<input type="checkbox" id="config_check_head${Maincnt}" onclick="checkall(${Maincnt})"> Select All
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-2">
						</div>
						</div>`;
		let labels = getLabels().then(result => {
			for (let i = 0; i < fields.length; i++) {
				html += `
					 <div class="col-lg-12 col-md-12 col-sm-12 d-flex">
					 <div class="col-md-2">
						<div class="form-group">
							<input type="checkbox" class="config_checkbox_${Maincnt}" id="config_checkbox_${Maincnt}_${i}" onclick="create_array(${Maincnt},${i})" name="config_checkbox[]">
						</div>
					</div>
					<div class="col-md-5">
					 <input type="text" class="form-control" name="column_${Maincnt}" id="column_${Maincnt}_${i}" readonly value="${fields[i]}">
					</div>
					<div class="col-md-5">
					 	<div class="form-group">
					 		<select name="config_option_${Maincnt}[]" id="config_option_${Maincnt}_${i}" class="form-control">
					 			${result}
							</select>
						</div>
					</div>
					</div>
					 `;

				options += `<option value="${fields[i]}">${fields[i]}</option>`;
			}
			html += `
					<div class="col-lg-12 col-md-12 col-sm-12 d-flex">
					<input type="hidden" name="update_div_count" id="update_div_count${Maincnt}" value="0">
					<div class="col-md-2">
					<div class="form-group">
					<label>Select Method</label>
					<select name="method${Maincnt}" onchange="showUpdateDiv(${Maincnt})" id="config_method${Maincnt}" class="form-control">
					<option value="1">Insert Query</option>
					<option value="2">Update Query</option>
					</select>
					</div>
					</div>
					<div class="col-md-5 update_div" style="display: none">
					<label>Where Column</label>
					 <div class="form-group">
						<select name="where_id${Maincnt}[]" id="where_id_${Maincnt}_0" class="form-control">
							${options}
						</select>
					 </div>
					</div>
					<div class="col-md-5 update_div" style="display: none">
					 	<div class="form-group">
					 	<label>Where Value</label>
					    	<select name="where_value${Maincnt}[]" id="where_value_${Maincnt}_0" class="form-control">
					 			${result}
							</select>
						</div>
					</div>
					</div>
					
					<div class="col-md-12" id="update_add_div${Maincnt}">
					</div>
					
					<div class="col-md-12 update_div" style="display: none">
					<button type="button" onclick="createUpdateDiv(${Maincnt})" class="btn btn-primary float-right">Add More</button>
					</div>
					</div>`;
			$("#config_details" + Maincnt).html('');
			$("#config_details" + Maincnt).html(html);
		});
	}).catch(res => {
		$("#config_details" + Maincnt).html('');
	});
}

function getButtonConfig(table, Maincnt) {
	return new Promise(function (resolve, reject) {
		getTableFields(table).then(res => {
			let fields = res;
			let html = `<div class="row">
						<input type="hidden" name="querystring" id="queryString${Maincnt}">
						<div class="col-lg-12 col-md-12 col-sm-12 d-flex">
						<div class="col-md-2">
						<input type="checkbox" id="config_check_head${Maincnt}" onclick="checkall(1)"> Select All
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-2">
						</div>
						</div>`;
			let labels = getLabels().then(result => {
				for (let i = 0; i < fields.length; i++) {
					html += `
					 <div class="col-lg-12 col-md-12 col-sm-12 d-flex">
					 <div class="col-md-2">
						<div class="form-group">
							<input type="checkbox" class="config_checkbox_${Maincnt}" id="config_checkbox_${Maincnt}_${i}" onclick="create_array(${Maincnt},${i})" name="config_checkbox[]">
						</div>
					</div>
					<div class="col-md-5">
					 <input type="text" class="form-control" name="column_${Maincnt}" id="column_${Maincnt}_${i}" readonly value="${fields[i]}">
					</div>
					<div class="col-md-5">
					 	<div class="form-group">
					 		<select name="config_option_${Maincnt}[]" id="config_option_${Maincnt}_${i}" class="form-control">
					 			${result}
							</select>
						</div>
					</div>
					</div>
					 `;

					options += `<option value="${fields[i]}">${fields[i]}</option>`;
				}
				html += `
					<div class="col-lg-12 col-md-12 col-sm-12 d-flex">
					<input type="hidden" name="update_div_count" id="update_div_count${Maincnt}" value="0">
					<div class="col-md-2">
					<div class="form-group">
					<label>Select Method</label>
					<select name="method${Maincnt}" onchange="showUpdateDiv(${Maincnt})" id="config_method${Maincnt}" class="form-control">
					<option value="1">Insert Query</option>
					<option value="2">Update Query</option>
					</select>
					</div>
					</div>
					<div class="col-md-5 update_div" style="display: none">
					<label>Where Column</label>
					 <div class="form-group">
						<select name="where_id${Maincnt}[]" id="where_id_${Maincnt}_0" class="form-control">
							${options}
						</select>
					 </div>
					</div>
					<div class="col-md-5 update_div" style="display: none">
					 	<div class="form-group">
					 	<label>Where Value</label>
					    	<select name="where_value${Maincnt}[]" id="where_value_${Maincnt}_0" class="form-control">
					 			${result}
							</select>
						</div>
					</div>
					</div>
					
					<div class="col-md-12" id="update_add_div${Maincnt}">
					</div>
					
					<div class="col-md-12 update_div" style="display: none">
					<button type="button" onclick="createUpdateDiv(${Maincnt})" class="btn btn-primary float-right">Add More</button>
					</div>
					</div>`;
				$("#config_details" + Maincnt).html('');
				$("#config_details" + Maincnt).html(html);
			});
			resolve(html);
		}).catch(res => {
			$("#config_details" + Maincnt).html('');
		});
	});
}


let newqueryData = {};

function checkall(id) {
	arr_length = $(".config_checkbox_" + id).length;
	let DataArray = {};
	if ($("#config_check_head" + id).prop('checked')) {
		for (let i = 0; i < arr_length; i++) {
			$(".config_checkbox_" + id).prop('checked', true);
			var data1 = $("#column_" + id + "_" + i).val();
			var data2 = $("#config_option_" + id + "_" + i).val();
			newqueryData['otherData' + id + "_" + i] = data1 + ":" + data2;
			$("#queryString" + id).val(JSON.stringify(newqueryData));
		}
	} else {
		$(".config_checkbox_" + id).prop('checked', false);
		for (let i = 0; i < arr_length; i++) {
			var k = "otherData" + id + "_" + i;
			delete newqueryData[k];
			$("#queryString" + id).val(JSON.stringify(newqueryData));
		}
	}
}

function create_array(id, i) {
	if ($("#config_checkbox_" + id + "_" + i).prop('checked')) {
		var data1 = $("#column_" + id + "_" + i).val();
		var data2 = $("#config_option_" + id + "_" + i).val();
		newqueryData['otherData' + id + "_" + i] = data1 + ":" + data2;
		$("#queryString").val(JSON.stringify(newqueryData));
	} else {
		$("#config_checkbox_" + id + "_" + i).prop('checked', false);
		var k = "otherData" + id + "_" + i;
		delete newqueryData[k];
		$("#queryString" + id).val(JSON.stringify(newqueryData));
	}
}

function getTableFields(table) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('table', table);
		app.request('getTableFields', formdata).then(res => {
			if (res.status === 200) {
				let data = res.data;
				resolve(data);
			} else {
				reject();
			}
		}).catch(error => console.log(error));
	});
}

let where_array = {};

function saveConfigData(Maincnt) {
	// pageConfiguration.table = [];
	// pageConfiguration.method = [];
	// pageConfiguration.data = [];
	// let table = $("#toTable"+Maincnt).val();
	// let method = $("#config_method"+Maincnt).val();
	// let where_id = null;
	// let where_value = null;
	// let cnt = $("#update_div_count"+Maincnt).val();
	// cnt = parseInt(cnt);
	// if (method == 2) {
	// 	for (let i = 0; i <= cnt; i++) {
	// 		where_id = $("#where_id_"+Maincnt+"_" + i).val();
	// 		where_value = $("#where_value_"+Maincnt+"_" + i).val();
	// 		where_array['where_data_' + i] = where_id + ":" + where_value;
	// 	}
	// }
	// let data = $("#queryString"+Maincnt).val();
	// pageConfiguration.table.push(table);
	// pageConfiguration.method.push(method, JSON.stringify(where_array));
	// pageConfiguration.data.push(data);
	pageConfiguration.operation = [];
	let data = $('#btnConfig').val();
	pageConfiguration.operation = JSON.parse(data);
	$("#ConfigurationModal").modal('hide');
}

function showUpdateDiv(cnt) {
	let val = $("#config_method" + cnt).val();
	if (val == '2') {
		$(".update_div").show();
	} else {
		$(".update_div").hide();
	}
}

function createUpdateDiv(maincnt) {
	return new Promise(function (resolve, reject) {
		getLabels().then(result => {
			let cnt = $("#update_div_count" + maincnt).val();
			cnt++;
			let html = `
			<div class="col-lg-12 col-md-12 col-sm-12 d-flex" id="update_child_div${cnt}">
			<div class="col-md-2">
			<div class="form-group">
				<button class="btn btn-danger mt-4" type="button" onclick="remove_div('update_child_div${cnt}',${cnt},'update_div_count${maincnt}',1)"><i class="fa fa-trash"></i></button>
			</div>
			</div>
			<div class="col-md-5">
			<label>Where Column</label>
			 <div class="form-group">
				<select name="where_id[]" id="where_id_1_${cnt}" class="form-control">
					${options}
				</select>
			 </div>
			</div>
			<div class="col-md-5">
			 <div class="form-group">
			 <label>Where Value</label>
			    <select name="where_value[]" id="where_value_1_${cnt}" class="form-control">
					${result}
			</select>
			</div>
			</div>
			</div>`;

			$("#update_div_count" + maincnt).val(cnt);
			$("#update_add_div" + maincnt).append(html);
			resolve(html);
		});
	});
}

function remove_div(id, cnt, count_id, type) {
	$("#" + id).remove();
	if (type == 1) {
		var k = "where_data_" + cnt;
		delete where_array[k];
	}
	cnt--;
	$("#" + count_id).val(cnt);
}

function changeTab(div_id1, div_id2, type = null) {

	$("#QueryDropdown_method").val(type);
	$("#" + div_id1).show();
	$("#" + div_id2).hide();
}

let queryOptions = '';

function getQueryDropdownData() {
	return new Promise(function (resolve, reject) {
		let table = $("#query_table").val();
		getTableFields(table).then(res => {
			queryOptions = '';
			getLabels().then(result => {
				let fields = res;
				for (let i = 0; i < fields.length; i++) {
					queryOptions += `<option value="${fields[i]}">${fields[i]}</option>`;
				}

				$(".queryDropdownSelect").html('');
				$(".queryDropdownSelect").html(queryOptions).select2();

				$(".queryDropdownLabel").html('');
				$(".queryDropdownLabel").html(result).select2();
				resolve(res);
			});
		});
	});
}

function getDependentDiv() {
	return new Promise(function (resolve, reject) {
		getLabels().then(result => {
			let cnt = $("#is_dependent_count").val();
			cnt++;

			let html = `
		<div class="col-md-12 d-flex" id="dependent_div${cnt}">
		<div class="col-md-3">
			<label>Dependent Value</label>
			<select name="dependent_value[]" id="dependent_value${cnt}" class="form-control queryDropdownLabel select2">${result}</select>
		</div>
		<div class="col-md-3">
			<label>Depend Column </label>
			<select name="dependent_column${cnt}" id="dependent_column${cnt}" class="form-control queryDropdownSelect select2">${queryOptions}</select>
		</div>	
		<div class="col-md-4">
			<label>Dependent Query</label>
			<textarea class="form-control" placeholder="Enter Dependent Query Here" cols="5" rows="5" name="dependent_query[]" id="dependent_query${cnt}"></textarea>
		</div>
		<div class="col-md-2">
			<button class="btn btn-danger mt-4" type="button" onclick="remove_div('dependent_div${cnt}',${cnt},'is_dependent_count',2)"><i class="fa fa-trash"></i></button>
		</div>	
		</div>
		`;

			$("#is_dependent_count").val(cnt);
			$("#is_dependent_row_div").append(html);
			$("#dependent_value" + cnt).select2();
			$("#dependent_column" + cnt).select2();
			resolve(result);
		});
	});
}

function getImDependentDiv() {
	return new Promise(function (resolve, reject) {
		getLabels().then(result => {
			let cnt = $("#is_im_dependent_count").val();
			cnt++;

			let html = `
		<div class="col-md-12 d-flex" id="im_dependent_div${cnt}">
		<div class="col-md-5">
			<label>Where Dependent Value Check  </label>
			<select name="dependent_value_check${cnt}" id="dependent_value_check${cnt}" class="form-control queryDropdownSelect select2">${queryOptions}</select>
		</div>

		<div class="col-md-5">
			<label>Dependent On </label>
			<select name="dependent_on${cnt}" id="dependent_on${cnt}" class="form-control queryDropdownLabel select2">${result}</select>
		</div>
		<div class="col-md-2">
			<button class="btn btn-danger mt-4" type="button" onclick="remove_div('im_dependent_div${cnt}',${cnt},'is_im_dependent_count',2)"><i class="fa fa-trash"></i></button>
		</div>	
		</div>
		`;

			$("#is_im_dependent_count").val(cnt);
			$("#is_im_dependent_div").append(html);
			$("#dependent_value_check" + cnt).select2();
			$("#dependent_on" + cnt).select2();
			resolve(result);
		});
	});
}

function getDependentWhereDiv() {
	return new Promise(function (resolve, reject) {
		getLabels().then(result => {
			let cnt = $("#where_row_count").val();
			cnt++;

			let html = `
		<div class="col-md-12 d-flex" id="dependent_where_div${cnt}">
		<div class="col-md-4">
			<label>Where Column</label>
			<select name="query_where_column[]" id="query_where_column${cnt}" class="form-control queryDropdownSelect select2">${queryOptions}</select>
		</div>	
		<div class="col-md-4">
			<label>Where Value</label>
			<select name="query_where_value[]" id="query_where_value${cnt}" class="form-control select2">
				<option value="1">Static</option>
				<option value="2">Session</option>
			</select>
		</div>
		<div class="col-md-3">
		<label>Where Value</label>
		<input type="text" name="query_where_value2[]" id="query_where_value2_${cnt}" class="form-control">
		</div>
		<div class="col-md-1">
			<button class="btn btn-danger mt-4" type="button" onclick="remove_div('dependent_where_div${cnt}',${cnt},'where_row_count',3)"><i class="fa fa-trash"></i></button>
		</div>	
		</div>
		`;

			$("#where_row_count").val(cnt);
			$("#query_where_div").append(html);
			$("#query_where_column" + cnt).select2();

			resolve(result);
		});
	});
}

function saveFormData(form_id) {
	var form = document.getElementById(form_id.id);
	var formData = new FormData(form);
	app.request("saveFormData", formData).then(result => {
		if (result.status === 200) {
			app.successToast("Save Changes");

			if (result.action) {
				let actionData = result.action;
				if (actionData.method !== 2 && actionData.method !== 5) {
					$("#" + form_id.id)[0].reset();
					$('#' + form_id.id + ' input[name=update_id]').val('');
					$(".select2").val([]).trigger('change');
				}
			} else {
				$("#" + form_id.id)[0].reset();
				$('#' + form_id.id + ' input[name=update_id]').val('');
				$(".select2").val([]).trigger('change');
			}

			let insert_id = result.insert_id;
			if (result.action) {
				let actionData = result.action;
				if (actionData.method == 1) {
					let tempId = actionData.templateID;
					let columnID = $("div[data-templateID=" + tempId + "]").attr('id');
					templateForm(tempId, columnID);
				}

				if (actionData.method == 2) {
					let tempId = actionData.templateID;
					let columnID = $("div[data-templateID=" + tempId + "]").attr('id');
					$("#" + columnID).show();
					$(".handsonbtn" + tempId).show();
					$("#totalDiv").hide();
					let handson_id = $("#Handsontable" + tempId).attr('data-value');
					getHandson(handson_id, `Handsontable${tempId}`, insert_id);
				}

				if (actionData.method === 3) {
					let tempId = actionData.templateID;
					let hiddenHTml = Object.keys(insert_id).map(r => {
						return `<input type="hidden" name="last_inserted_id" id="last_${tempId}_insert_id_${r}" value="${insert_id[r]}">`;
					});
					$("#hiddenFields").append(hiddenHTml.join(''));
				}

				if (actionData.method === 4) {
					let tempId = actionData.templateID;
					let columnID = $("div[data-templateID=" + tempId + "]").attr('id');

					let c_id = columnID.split('-').slice(-1);
					c_id = c_id[0];
					console.log(c_id);
					$("#dynamicDataTable_" + c_id + "_wrapper").show();
					let hiddenHTml = Object.keys(insert_id).map(r => {
						return `<input type="hidden" name="last_inserted_id" id="last_${tempId}_insert_id_${r}" value="${insert_id[r]}">`;
					});

					$("#hiddenFields").append(hiddenHTml.join(''));
					templateForm(tempId, columnID);
				}

				if (actionData.method === 5) {
					let tempId = actionData.templateID;
					let hiddenHTml = Object.keys(insert_id).map(r => {
						return `<input type="hidden" name="last_inserted_id" id="last_${tempId}_insert_id_${r}" value="${insert_id[r]}">`;
					});
					$("#hiddenFields").append(hiddenHTml.join(''));

					let template_id = $("#template_id").val();
					$("form button[data-form='" + template_id + "']").hide();
				}
			}


			if (result.is_html == 1) {
				let formid = form_id.id;
				formid = formid.slice(-2);
				let columnID = $("div[data-templateID=" + formid + "]").attr('id');
				$("#" + columnID).html('');

				let html = `<div class="row form-group">`;
				let formdata = result.dataArray;
				for (var key in formdata[0]) {

					html += `<div class="col-4">
								<label class="px-3 py-3 text-capitalize">${key} :</label>
								<label class="text-body text-capitalize">${formdata[0][key]}</label>
							</div>`;
				}
				html += `</div>`;

				$("#" + columnID).html(html);
			}


		} else {
			app.errorToast("Failed To Save Data");
		}
	}).catch(error => {
		console.log(error)
		app.errorToast('Something went wrong please try again');
	})
}

function copyToClipboard(type = null) {
	const elem = document.createElement('textarea');
	if (type === 1) {
		elem.value = `{"transaction":[{"id":"T1","table":"table","method":1,"column":[{"where_column":"value"}],"whereColumn":[{}],"dependent_on":[],"is_bulk":0},{"id":"T2","table":"TE_product_map","method":1,"column":[{"where_column":"value"}],"whereColumn":[{}],"dependent_on":["T1"],"is_bulk":1}],"action":{"method":1,"templateID":34,"extra":["T1.insert_id"]}}`;
	} else {
		elem.value = '[{"id":1, "text":"option1"},{"id":2, "text":"option2"}]';
	}
	document.body.appendChild(elem);
	elem.select();
	document.execCommand('copy');
	document.body.removeChild(elem);
}


function loadDataTable(form_id, rowID, columnID, columnObjectSize) {
	let formData = new FormData();
	formData.set("templateID", form_id);
	formData.set("rowID", rowID);
	formData.set("columnID", columnID);
	formData.set("columnObjectSize", columnObjectSize);
	app.request("getDataTableTemplate", formData).then(response => {

		if (response.status == 200) {
			//table creation
			let temp = ``;
			let filterOp = [];
			if (response.filterOptions != "" && response.filterOptions.length > 0) {
				let obj = getDataTableFiltersStructure(form_id, rowID, columnID, response.filterOptions, response.header, response.actionColumn);
				temp += obj.design;
				filterOp = obj.option;
			}
			temp += getDataTableStructure(columnID, response.header);
			$("#" + form_id + "-drop-zone-" + columnID).html(temp);
			filterOp.map(loadFilterOptions);
			//datatable data
			let filterColumns = getFilterOptionsColumns(response.filterOptions);
			loadDataTableData(form_id, rowID, columnID, filterColumns, response.header, response.actionColumn);
		}
	});

}

let dataTableConfigurationObject = [];
let multipleParameterModal = [];
let rowcnt = 0;

function loadDataTableData(form_id, rowID, columnID, filterColumns, header, actionColumn) {
	let tableId = 'dynamicDataTable_' + columnID;
	let a = {
		tableId: tableId,
		templateID: parseInt(form_id),
		rowID: rowID,
		columnID: columnID,
		filterOptions: filterColumns,
		header: header,
		actionColumn: actionColumn
	}
	if (dataTableConfigurationObject.findIndex(i => i.tableId === tableId) === -1) {
		dataTableConfigurationObject.push(a);
	}
	if (!Array.isArray(filterColumns)) {
		if (filterColumns != "") {
			filterColumns = filterColumns.split(',');
		}
	}
	if (Array.isArray(filterColumns)) {
		filterColumns = filterColumns.map((i, ind) => {
			console.log(i);
			return {key: i, val: $("#dynamicDataTableFilter_" + ind).val()};
		});
	} else {
		filterColumns = [];
	}
	if (!Array.isArray(header)) {
		if (header != "") {
			header = header.split(',');
		}
	}
	let TableCol = header.map((col, ind) => {
		if (col !== actionColumn) {
			return {
				data: `${col}`
			}
		} else {

			return {
				data: actionColumn,
				render: (d, t, r, m) => {

					rowcnt++;
					return `<div class="btn-group">${getActionButtionsStructure(d, r, form_id, columnID, m.row, rowcnt)}</div>`;

				}
			}
		}

	});
	let queryParameter = [];
	var div = document.getElementById('hiddenFields');
	$(div).find('input:text, input:password, input:file,input:hidden, select, textarea')
		.each(function () {
			let eleId = this.id;
			let eleVal = this.value;
			queryParameter.push({key: eleId, value: eleVal});
			// $(this).val('');
		});
	var indexdiv = document.getElementById('indexFieldDiv');
	$(indexdiv).find('input:text, input:password, input:file,input:hidden, select, textarea')
		.each(function () {
			let eleId = this.id;
			let eleVal = this.value;
			queryParameter.push({key: eleId, value: eleVal});
			// $(this).val('');
		});
	var dataObj = {
		templateID: form_id,
		rowID: rowID,
		columnID: columnID,
		queryParameter: queryParameter,
		filterOptions: filterColumns
	};
	console.log(queryParameter);
	console.log(tableId);
	app.dataTable(tableId, {
		url: baseURL + "getDataTableData",
		data: {
			templateID: form_id,
			rowID: rowID,
			columnID: columnID,
			queryParameter: queryParameter,
			filterOptions: filterColumns
		}
	}, TableCol, undefined, () => {
		app.confirmationBox();
	});
}

function getActionButtionsStructure(d, r, form_id, columnID, rowIndex, rowcnt) {
	let structureAction = ``;
	let actions = JSON.parse(d);
	var ctrl_cnt = 1;
	structureAction = actions.map((b, i) => {

		let onclick = ``;
		let confirmation = ``;
		let directPath = ``;
		let btnOnclick = ``;
		let modal = ``;
		let target = ``;
		let buttonTitle = ``;
		if (b.hasOwnProperty('title')) {
			buttonTitle = `title="${b.title}"`;
		}
		if (b.action == 1 || b.action == 2) {
			if (b.hasOwnProperty('query')) {
				if (b.query.hasOwnProperty('type')) {
					let queryValue = parameterValue(b, r);

					onclick = `executeQuery('${b.query.query}', '${queryValue}','${form_id}','${columnID}')`;
				}
			}
			if (b.hasOwnProperty('confirmation')) {
				confirmation = `data-toggle="tooltip" title
                            data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                            data-confirm-yes="${onclick}"
                            data-original-title="Action"`;
			}
		} else if (b.action == 3) {
			if (b.hasOwnProperty('redirection')) {
				if (b.redirection.hasOwnProperty('type')) {
					let redirectValue = parameterValue(b, r, form_id, 1);
					if (redirectValue != "") {
						redirectValue = "/" + redirectValue;
					}
					let redirectPath = b.redirection.path;
					directPath = baseURL + redirectPath + redirectValue;
				}

				if (b.hasOwnProperty('target')) {
					if (b.target === 1) {
						target = `target="_blank"`;
					}
				}
			}
		} else if (b.action == 4) {
			if (b.hasOwnProperty('modalID')) {
				confirmation = `data-toggle="modal" data-target="#${b.modalID}"`;
				btnOnclick = `onclick="getTemplateFormId('${b.templateName}')"`;
			}
		} else if (b.action == 5) {
			if (b.hasOwnProperty('templateID')) {
				if (b.hasOwnProperty('redirection')) {
					if (b.redirection.type == 4) {
						let muliPara = {
							action: b.action,
							form_id: form_id,
							r: r,
							b: b,
							id: rowIndex,
							rowcnt: rowcnt
						};
						if (multipleParameterModal.findIndex(p => (p.form_id === form_id && p.id == rowIndex && p.rowcnt == rowcnt)) === -1) {
							multipleParameterModal.push(muliPara);
						}
					}
					btnOnclick = `onclick="getRedirectionStructure('${b.templateID}','${b.redirection.type}','${b.redirection.queryPara}','${r['_' + b.column]}','${form_id}','${rowIndex}',${rowcnt})"`;
				}
			}
		} else if (b.action == 6) {
			if (b.hasOwnProperty('templateID')) {
				let fileTable = ``;
				if (b.hasOwnProperty('fileTable')) {
					fileTable = b.fileTable;
				}
				btnOnclick = `onclick="getEditFormData('${r['_' + b.column]}','${b.templateID}','${btoa(b.query)}','${fileTable}',${b.method},${form_id})"`;
			}
		} else if (b.action == 7) {
			if (b.hasOwnProperty('insertion')) {
				let InsertData = setInsertionColumnValue(b.insertion, r);
				let action = ``;
				if (b.hasOwnProperty('behaviour')) {
					action = b.behaviour;
				}
				btnOnclick = `onclick="saveModalDataTableButtonInsertion('${btoa(JSON.stringify(InsertData))}','${btoa(JSON.stringify(action))}')"`;

			}
		} else if (b.action == 8) {
			if (b.hasOwnProperty('function')) {
				let funName = b.function;
				let hasFunction = window[funName];
				if (typeof hasFunction === "function") {
					return hasFunction.call(null, d, r);
				}
			}
		}

		if (b.action == 3) {
			return `<a href="${directPath}" ${target} class="btn btn-sm btn-primary mx-1" ${buttonTitle}>${b.label}</a>`;
		} else {
			return `<button type="button" class="btn btn-sm btn-primary mx-1" ${confirmation} ${btnOnclick} ${buttonTitle}>${b.label}</button>`;
		}
		ctrl_cnt++;
	}).join('');
	return structureAction;
}

function invetoryShowHide(d, r) {
	let rowString = JSON.stringify(r);
	let rowEncode = btoa(rowString);
	let buttonString = ``;
	if (r['_sale_type'] == 2 && r['_qty'] > 0) {
		buttonString += `<button type="button" class="btn btn-primary" onclick="openModalForForm(135,1,'${rowEncode}','templateForm')">Return</button>`;
	} else if (r['_sale_type'] == 2 && r['_qty'] == 0) {

	} else if (r['_sale_type'] == 1) {
		if (r['_product_type'] == 1 && r['_IsConsumed'] == 1) {

		} else {
			if (r['_product_type'] == 2 && r['_qty'] == 0) {

			} else {
				if (r['_product_type'] == 2) {
					buttonString += `<button type="button" class="btn btn-primary mr-1" onclick="openModalForForm(135,4,'${rowEncode}','templateForm')" title="Consumption"><i class="fa fa-shopping-bag"></i></button>`;
				}
				// if(r['_product_type']==1){
				buttonString += `<button type="button" class="btn btn-primary" onclick="openModalForForm(135,3,'${rowEncode}','templateForm')" title="Lease"><i class="fa fa-shopping-cart"></i></button>`;
				// }
				buttonString += `<button type="button" class="btn btn-primary ml-1" onclick="openModalForForm(135,2,'${rowEncode}','templateForm')" title="Transfer"><i class="fa fa-truck"></i></button>`;
			}
		}
	}
	return buttonString;
}

function openModalForForm(tempId, type, rencode, divId) {
	// let formDesign=``;
	let rdecode = atob(rencode);
	let RDecoded = JSON.parse(rdecode);
	console.log(RDecoded);
	let product_type = RDecoded['_product_type'];
	if (type == 1) {
		if (product_type == 1) {
			$("#inventoryModal").modal('show');
			$("#i_invoice_no").val('LR-' + RDecoded['_sales_id']);
			$("#i_order_id").val(RDecoded['_sales_id']);
			$("#i_serial_id").val(RDecoded['_serial_id']);
			$("#i_taxable_value").val(RDecoded['_taxable_value']);
			$("#i_in_location").val(RDecoded['_location']);
			$("#i_item_no").val(RDecoded['Item No']);
			$("#i_customer_name").val(RDecoded['Customer Name']);
			$("#i_customer_location").val(RDecoded['Location']);
			$("#i_customer_id").val(RDecoded['_customer_id']);
		} else {
			$("#inventoryModalReturn").modal('show');
			$("#ir_invoice_no").val('LR-' + RDecoded['_sales_id']);
			$("#ir_order_id").val(RDecoded['_sales_id']);
			$("#ir_serial_id").val(RDecoded['_serial_id']);
			$("#ir_taxable_value").val(RDecoded['_taxable_value']);
			$("#ir_in_location").val(RDecoded['_location']);
			$("#ir_item_no").val(RDecoded['Item No']);
			$("#ir_customer_name").val(RDecoded['Customer Name']);
			$("#ir_customer_location").val(RDecoded['Location']);
			$("#ir_customer_id").val(RDecoded['_customer_id']);
			$("#ir_before_qty").val(RDecoded['_qty']);
		}
		getAllWareHouseLocation(1);
		getChalanNumber();
	}
	if (type == 2) {
		// $("#inventoryTransferModal").modal('show');
		// $("#it_order_id").val(RDecoded['_location_order_id']);
		// $("#it_serial_id").val(RDecoded['_serial_id']);
		// $("#it_item_id").val(RDecoded['_item_id']);
		// $("#it_product_type").val(RDecoded['_product_type']);
		// $("#it_in_location").val(RDecoded['_location']);
		let formdata = new FormData();
		formdata.set('c_order_id', RDecoded['_location_order_id']);
		formdata.set('c_item_id', RDecoded['_item_id']);
		formdata.set('c_product_type', RDecoded['_product_type']);
		formdata.set('c_in_location', RDecoded['_location']);
		formdata.set('c_serial_no', RDecoded['_serial_id']);

		if (RDecoded['_qty'] !== undefined) {
			formdata.set('c_qty', RDecoded['_qty']);

		}
		formdata.set('type', 2);
		saveInventoryCardItemData(formdata);
	}
	if (type == 3) {
		// $("#inventoryAddToCartModal").modal('show');
		let formdata = new FormData();
		formdata.set('c_order_id', RDecoded['_location_order_id']);
		formdata.set('c_item_id', RDecoded['_item_id']);
		formdata.set('c_product_type', RDecoded['_product_type']);
		formdata.set('c_in_location', RDecoded['_location']);
		formdata.set('c_serial_no', RDecoded['_serial_id']);

		if (RDecoded['_qty'] !== undefined) {
			formdata.set('c_qty', RDecoded['_qty']);

		}
		formdata.set('type', 1);
		saveInventoryCardItemData(formdata);
	}
	if (type === 4) {

		console.log(RDecoded);
		$("#consumptionModal").modal('show');
		$("#c_brand_name").val(RDecoded['Brand Name']);
		$("#c_product_name").val(RDecoded['Product Name']);
		$("#cir_order_id").val(RDecoded['_sales_id']);
		$("#cir_item_id").val(RDecoded['_item_id']);
		$("#cir_serial_no").val(RDecoded['_serial_id']);
		$("#cir_taxable_value").val(RDecoded['_taxable_value']);
		$("#cir_in_location").val(RDecoded['_location']);
		$("#cir_item_no").val(RDecoded['Item No']);
		$("#cir_customer_name").val(RDecoded['Customer Name']);
		$("#cir_customer_location").val(RDecoded['Location']);
		$("#cir_customer_id").val(RDecoded['_customer_id']);
		$("#cir_product_type").val(RDecoded['_product_type']);
		let product_type = RDecoded['_product_type'];
		if (product_type == 1) {
			$("#cir_qty").val(1);
			$("#cir_before_qty").val(1);
		} else {
			$("#cir_before_qty").val(RDecoded['_qty']);
		}
		getAllWareHouseLocation(1);
		getChalanNumber();
	}

}

function setInsertionColumnValue(insertCol, r) {
	// console.log(r);
	let insertArray = [];
	insertCol.forEach(e => {
		let columnArray = [];
		if (e.hasOwnProperty('column')) {
			e.column.forEach(c => {
				if (c.type == 1) //datatable select column value
				{
					columnArray.push({'column': c.tableCol, 'value': r[c.selectCol]});
				} else if (c.type == 2) //session value
				{
					columnArray.push({'column': c.tableCol, 'value': $("#session_" + c.selectCol).val()});
				} else if (c.type == 3) {
					columnArray.push({'column': c.tableCol, 'value': $("#" + c.selectCol).val()});
				} else if (c.type == 4) {
					columnArray.push({'column': c.tableCol, 'value': $("" + c.selectCol).val()});
				} else {
					columnArray.push({'column': c.tableCol, 'value': c.selectCol});
				}
			});
		}
		insertArray.push({'table': e.table, 'column': columnArray});
	});
	return insertArray;
}

function getRedirectionStructure(tempId, redirectType, queryPara, val, form_id = null, indexModal = '', rowcnt = '') {
	if (parseInt(redirectType) == 1) {
		//modal
		modalTemplateLoad(tempId, 'templateForm', queryPara, val);

	} else if (parseInt(redirectType) == 2) {
		//same page
		ShowForm(tempId);
	} else if (parseInt(redirectType) == 3) {
		modalOpenWithValue(tempId, 'templateForm', queryPara, val);
	} else if (parseInt(redirectType) == 4) {

		let templateIndex = multipleParameterModal.findIndex(m => (m.form_id === form_id && m.id == indexModal && m.rowcnt == rowcnt));
		let object = null;
		if (templateIndex !== -1) {
			object = multipleParameterModal[templateIndex];
		}
		if (object) {
			modalOpenWithMultipleValue(tempId, 'templateForm', object.b.redirection.queryParaArray, object.r);
		}

	}
}

function saveModalDataTableButtonInsertion(InsertData, actionData) {
	let actData = JSON.parse(atob(actionData));
	let formData = new FormData();
	formData.set('insertdata', atob(InsertData));
	app.request("saveModalDataTableButtonInsertion", formData).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			if (actData != "") {
				if (actData.type == 1) {
					let columnID = $("div[data-templateID=" + actData.templateId + "]").attr('id');
					templateForm(actData.templateId, columnID);
				}
			}
		} else {
			app.errorToast(res.body);
		}
	});

}

function parameterValue(b, r, form_id, type = null) {
	let queryValue = ``;
	let main = b.query;
	if (type != null) {
		main = b.redirection;
	}
	if (main.type == 4) {
		queryValue = r['_' + b.column];
	} else if (main.type == 1) {
		queryValue = $("#session_" + b.column).val();
	} else if (main.type == 1) {
		queryValue = b.value;
	} else if (main.type == 3) {
		//query parameter
		// queryValue=$("#queryParam_"+form_id+"_"+b.query.value).val();
		queryValue = $("#queryParam_" + form_id + "_" + main.value).val();
	}
	return queryValue;
}

function executeQuery(query, parameter, form_id, columnID) {
	let form = new FormData();
	form.set("query", query);
	form.set("param", parameter);
	app.request("executeQuery", form).then((r) => {
		if (r.status === 200) {
			let templateIndex = dataTableConfigurationObject.findIndex(i => i.tableId === "dynamicDataTable_" + columnID);
			let object = null;
			if (templateIndex !== -1) {
				object = dataTableConfigurationObject[templateIndex];
			}
			if (object) {
				loadDataTableData(object.templateID, object.rowID, object.columnID, object.filterOptions, object.header, object.actionColumn);
			}

		}
	}).catch(error => {
		console.log(error);
	});
}

function getTemplateFormId(templateName) {
	let form = new FormData();
	form.set("templateName", templateName);
	app.request("getTemplateFormIdByName", form).then((r) => {
		if (r.status === 200) {
			ShowForm(r.body);
		}
	}).catch(error => {
		console.log(error);
	});
}

function loadFilterOptions(items, index) {
	app.selectOption('dynamicDataTableFilter_' + items.id, "Select Value", null, items.values);
}

function getDataTableStructure(columnID, headers) {
	let table = ``;
	table += `<div class='row'>
					<div class='table'>
					<table class='dataTable table table-striped'  style='width: 100%' id='dynamicDataTable_${columnID}'>
						<thead>
							<tr>`;
	headers.map(e => {
		table += `<th>${e}</th>`;
	});
	table += `					</tr>
						</thead>
						<tbody></tbody>
					</table>
					</div>
				</div>`;
	return table;
}

function getDataTableFiltersStructure(form_id, rowID, columnID, filterOptions, header, actionColumn) {
	let filterSelectOptions = [];
	let filterDesign = `<div class="row">`;
	let filterColumns = getFilterOptionsColumns(filterOptions);
	filterOptions.map((e, i) => {

		if (e.type == 1 || e.type == 2) //date and datetime filter
		{
			let type = 'date';
			if (e.type == 2) {
				type = 'datetime-local';
			}
			filterDesign += `<div class="col-md-3">
								<label>${e.label}</label>
								<input type="${type}" class="form-control" name="dynamicDataTableFilter_${i}"
								 id="dynamicDataTableFilter_${i}"
								 onchange="loadDataTableData('${form_id}','${rowID}','${columnID}','${filterColumns}','${header}','${actionColumn}')" />
							</div>`;
		} else if (e.type == 3) //select filter
		{
			filterDesign += `<div class="form-group col-md-3">
								<label>${e.label}</label>
								<select  class="form-control" name="dynamicDataTableFilter_${i}"
								 id="dynamicDataTableFilter_${i}"
								 onchange="loadDataTableData('${form_id}','${rowID}','${columnID}','${filterColumns}','${header}','${actionColumn}')"></select>
							</div>`;
			filterSelectOptions.push({id: i, values: e.options});
		}
	})
	filterDesign += `</div>`;
	return {design: filterDesign, option: filterSelectOptions};
}

function getFilterOptionsColumns(filterOptions) {
	let filterColumn = [];
	filterOptions.map((i, ind) => {
		filterColumn.push(i.column);
	});
	return filterColumn;
}


function AddTransaction() {
	// let main_cnt = $("#MainCount").val();
	// let Maincnt = parseInt(main_cnt);
	//

	let Maincnt = findCount('.transactionBtn');
	Maincnt++;

	let html = `
			<div id="MainTransactionDiv${Maincnt}">
			<div class="row col-md-12">
			<div class="col-10">
			<div class="form-group">
			<select class="form-control transactionBtn Maintable select2" onchange="getButtonData(${Maincnt})" name="toTable"
			id="toTable${Maincnt}">			

			</select>
			</div>
			</div>
			<div class="col-2">
			<button class="btn btn-primary" id="MainBtn${Maincnt}" onclick="removeMain('MainTransactionDiv${Maincnt}',${Maincnt})" type="button"><i class="fa fa-trash"></i></button>
			</div>
			</div>			

			<div class="col-md-12">
			<div id="config_details${Maincnt}">
			</div>
			</div><hr></div>`;


	$("#MainDiv").append(html);
	$("#MainCount").val(Maincnt);
	getAllTablesNames();
}

function removeMain(id, cnt) {
	$("#" + id).remove();
	cnt--;
	$("#MainCount").val(cnt);
	// $("#MainBtn"+cnt).show();
}

function getEditFormData(id, templateId, query, fileTable, method = null, form_id = null) {
	let form = new FormData();
	form.set("id", id);
	form.set("templateId", templateId);
	form.set("query", query);
	form.set("fileTable", fileTable);
	app.request("getEditFormData", form).then((r) => {
		if (r.status === 200) {
			let body = r.body;
			$("#" + templateId + "_update_id").val(id);
			setFormInputValue(body, id, r.fileArray);
			let optionData = r.option;
			optionData.map(e => {
				let id = e.id;
				$("#" + id).html('');
				$("#" + id).append(e.option);
				for (const key in body) {
					if (key === id) {
						if (body[key] != null && body[key] != '') {
							if (key.includes('queryDropdown')) {
								if (body[key] != null) {
									if (body[key].includes(',')) {
										let values = body[key].split(',');
										$("#" + id).val(values).trigger('change');
									} else {
										$("#" + id).val([body[key]]).trigger('change');
									}
								}
							} else {
								$("#" + id).val(body[key]).trigger('change');
							}
						} else {
							$("#" + id).val('').trigger('change').select2();
						}
					}
				}
			});

			$("#templateForm" + templateId).show();
			if (method == 2) {
				$("form#templateForm" + templateId).submit();
				$("#templateForm" + form_id).hide();
				$("#productPurchase").hide();
				$("#itemPurchase").hide();
				$("#SalesProduct").hide();
				$("#purchaseHome").show();
				$("#SalesHome").show();
			}
		} else {
			app.errorToast(r.body);
		}
	}).catch(error => {
		console.log(error);
	});
}

function setFormInputValue(data, id, fileArray) {
	if (data != "") {
		Object.keys(data).filter((k) => {
			setInputValue(k, data[k], fileArray, id);
		});
	}
}

function getQueryDropdownScript(id, queryData) {

	// let queryData={"templateID":templateID,"rowID":rowID,"columnID":columnID};
	let url = baseURL + "DatatableEditorController/get_data";
	$("#queryDropdown" + id).select2(
		{
			ajax: {
				url: url,
				type: "post",
				dataType: "json",
				delay: 250,
				data: function (params) {
					if (queryData.hasOwnProperty('dependOn')) {
						if (queryData.dependOn != "") {
							let dependOnVal = $("#" + queryData.dependOn).val();
							if (dependOnVal == null) {
								queryData.dependOnValue = "";
							} else {
								queryData.dependOnValue = dependOnVal;
							}
						}
					}
					console.log(queryData);
					if (queryData.hasOwnProperty('imDependOnAnyOne')) {
						if (queryData.imDependOnAnyOne != "") {
							let imDependArr = [];
							queryData.imDependOnAnyOne.map((e, i) => {
								let imDepObj = {};
								imDepObj.Column = e.Column;
								let imdependOnVal = $("#" + e.Value).val();
								if (imdependOnVal == null) {
									imDepObj.Value = "";
								} else {
									imDepObj.Value = imdependOnVal;
								}
								imDependArr.push(imDepObj);
							});
							queryData.imDependOnAny = imDependArr;
						}
					}
					return {
						data: queryData,
						searchTerm: params.hasOwnProperty('term') ? params.term : ''  // search term
					};
				},
				processResults: function (response) {
					return {
						results: response
					};
				},
				cache: true
			},
			// minimumInputLength: 0
		}
	)
}

let anyOneDependOnMeObject = [];
let imDependOnAnyOneObject = [];

function queryDropdownForm(ele) {

	let required = '';
	let onChange = '';

	let multiple = '';
	let name = 'queryDropdown' + ele.columnID;
	if (ele.element.multiple === 1) {
		multiple = 'multiple';
		name = 'queryDropdown' + ele.columnID + '[]';
	}

	if (ele.element.checkbox == 1) {
		required = 'data-valid="required" data-msg="Please Fill this Field"';
	}
	let queryData = {"templateID": ele.templateID, "rowID": ele.rowID, "columnID": ele.columnObjectID};
	let dependArray = [];
	let imdependArray = [];
	if (ele.element.method == 1) {
		if (ele.element.hasOwnProperty('queryDependentOn')) {
			if (ele.element.queryDependentOn !== "Select One") {
				queryData.valueCheck = ele.element.queryDependentValueCheck;
				queryData.dependOn = ele.element.queryDependentOn;
			}
		}

		if (ele.element.hasOwnProperty('queryisDependentOnOther')) {
			ele.element.queryisDependentOnOther.forEach(e => {
				dependArray.push({Id: e.dValue, Value: e.dColumn});
			});
		}

		if (ele.element.hasOwnProperty('queryisimDependentOnOther')) {
			ele.element.queryisimDependentOnOther.forEach(e => {
				imdependArray.push({Column: e.dColumn, Value: e.dValue});
			});
		}


		if (ele.element.hasOwnProperty('queryDependentWhere')) {
			let whereArray = [];
			ele.element.queryDependentWhere.forEach(e => {
				if (e[1] == 2) {
					if (e[2] != null) {
						e[2] = e[2].replace('#', "");
						whereArray.push({name: e[0], value: $("#session_" + e[2]).val()});
					}
				} else {
					whereArray.push({name: e[0], value: e[2]});
				}
			});
			queryData.whereArray = whereArray;
		}
	}
	if (dependArray.length > 0) {
		queryData.anyOneDepend = dependArray;
		anyOneDependOnMeObject.push(queryData);
		onChange = `onchange="getOnchangeDependantData('${ele.templateID}','${ele.rowID}','${ele.columnObjectID}',this.value)"`;
	}
	if (imdependArray.length > 0) {
		queryData.imDependOnAnyOne = imdependArray;
	}
	let queryTemp = `<div class="form-group">
					<label>${ele.element.label}</label>
					<select name="${name}" ${multiple} id="queryDropdown${ele.columnID}" ${required} class="form-control select2" ${onChange}>
					</select>
			   </div>`;
	$("#" + ele.templateID + "-drop-zone-" + ele.columnObjectID).html(queryTemp);
	getQueryDropdownScript(`${ele.columnID}`, queryData);
}

function getOnchangeDependantData(tempID, rowID, colID, value) {

	let templateIndex = anyOneDependOnMeObject.findIndex(i => i.columnID == colID);
	let object = null;
	if (templateIndex !== -1) {
		object = anyOneDependOnMeObject[templateIndex];
	}

	if (object) {
		let formdata = new FormData();
		formdata.set('data', JSON.stringify(object));
		formdata.set('value', value);
		app.request("getOnchangeDependantData", formdata).then(res => {
			if (res.status === 200) {
				res.data.map((c, i) => {
					setInputValue(c.id, c.value);
				});
			}
		});
	}
}

function setInputValue(ele, value, fileArray = '', id = "") {
	if (ele === null) return;
	eleID = ele.replace(/[0-9]/g, '');
	switch (eleID) {
		case "text":
		case "number":
		case "queryParam":
		case "hidden":
		case "email":
		case "textarea":
			$("#" + ele).val("");
			$("#" + ele).val(value);
			break;
		case "date":
			$("#" + ele).val("");
			if (value != "" && value != null && value != "0000-00-00 00:00:00") {
				$("#" + ele).val(getFormattedDate(value));
			}
			break;
		case "file":
			$("#div_" + ele).html('');
			if (value != null && value != "") {
				let templateIndex = fileArray.findIndex(i => i.ele === ele);
				let object = null;
				if (templateIndex !== -1) {
					object = fileArray[templateIndex];
					$("#div_" + ele).html(`<div class="">${value}<span class="badge badge-primary ml-2 px-2 trigger--fire-modal-1" data-toggle="tooltip" title data-confirm="Are You Sure?|This action can be delete file. Do you want to continue?" 
					data-confirm-yes="deleteColumnDataFromtable('${object.column}','${object.table}','${id}','${ele}')" data-original-title="Upload Documents" style="cursor: pointer"><i class="fa fa-times"></i></span></div>`);
					app.confirmationBox();
				}
			}
			break;
		case "singleSelection":
			$("#" + ele).val("").trigger('change');
			if (value != "" && value != null) {
				$("#" + ele).val(value.split(','));
				$("#" + ele).select2();
			}
			break;
		case "multipleSelection":
			$("#" + ele).val("").trigger('change');
			if (value != "" && value != null) {
				$("#" + ele).val(value.split(','));
				$("#" + ele).select2();
			}
			break;
		case "radio":
			$('[name="' + ele + '"]').removeAttr('checked');
			$("input[name=" + ele + "][value=" + value + "]").attr('checked', 'checked');
			break;
		case "checkbox":
			$("input[name='" + ele + "[]']").each(function () {
				this.checked = false;
			});
			if (value != "" && value != null) {
				$.each(value.split(","), function (i, e) {
					$("input[name='" + ele + "[]'][value='" + e + "']").prop("checked", true);
				});
			}
			break;
		case "queryDropdown":
			// console.log(value);
			// $("#"+ele).val(value);
			// $("#"+ele).select2();
			break;
	}
}

function getFormattedDate(value) {
	let todayDate = new Date(value);
	let yyyy = todayDate.getFullYear();
	let mm = todayDate.getMonth() + 1; // Months start at 0!
	let dd = todayDate.getDate();

	if (dd < 10) dd = '0' + dd;
	if (mm < 10) mm = '0' + mm;

	// return dd + '/' + mm + '/' + yyyy;
	return yyyy + '-' + mm + '-' + dd;
}

function excelReportForm(excelData) {
	var excelTempInput = ``;
	if (excelData.element.hasOwnProperty('data')) {
		var i = 1;
		excelData.element.data.forEach(e => {
			if ((e.datatype != null && e.datatype != "") && (e.label != "" && e.label != null)) {
				let type = "text";
				if (e.datatype == 1) {
					type = "text";
				} else if (e.datatype == 2) {
					type = "date";
				} else if (e.datatype == 3) {
					type = "number";
				}
				if (e.value != null || e.value != "") {
					excelTempInput += `<div class="col-md-12">
						<lable>${e.label}</lable>
						<input class="form-control" type="${type}" id="input_val${i}" name="input_val${i}" required>
						</div>`;
				} else {
					let exp = explode(",", $value);
					let option = "<option value='' selected disabled>Select Option</option>";
					for (var j = 0; j < exp.length; j++) {
						option += `<option value='${$exp + j}'>${$exp + j}</option>`;
					}
					excelTempInput += `<div class="col-md-12">
						<lable>${e.label}</lable>
						<select class="form-control" id="input_val${i}" name="input_val${i}" required>
						${option}
						</select>
						</div>`;
				}
			}
			i++;
		})
	}
	let buttons = ``;
	if (excelData.element.hasOwnProperty('report_type')) {
		if (excelData.element.report_type.excel_report_type === 1) {
			buttons += `<button type="button" class="btn btn-primary ml-2" onclick="DownloadData('${excelData.templateID}','${excelData.rowID}','${excelData.columnObjectID}',1)"><i class="fa fa-download"></i> Excel Report</button>`;
		}
		if (excelData.element.report_type.pdf_report_type === 1) {
			buttons += `<button type="button" class="btn btn-primary ml-2" onclick="DownloadData('${excelData.templateID}','${excelData.rowID}','${excelData.columnObjectID}',2)"><i class="fa fa-download"></i> PDF Report</button>`;
		}
		if (excelData.element.report_type.csv_report_type === 1) {
			buttons += `<button type="button" class="btn btn-primary ml-2" onclick="DownloadData('${excelData.templateID}','${excelData.rowID}','${excelData.columnObjectID}',3)"><i class="fa fa-download"></i> CSV Report</button>`;
		}
	}
	let excelTemp = `<form id="download_form_${excelData.templateID}">
				${excelTempInput}
				<br>
				<div style="float:right">
					<button type="button" class="btn btn-primary" onclick="ShowData('${excelData.templateID}','${excelData.rowID}','${excelData.columnObjectID}')">Show Report</button>
				  	${buttons}
				  </div>
				  </form>
				<div class="col-md-12 table-responsive" id="reportDiv_${excelData.templateID}"></div>`;
	$("#" + excelData.templateID + "-drop-zone-" + excelData.columnObjectID).html(excelTemp);
}

function ShowData(templateID, rowID, colID) {
	$("#reportDiv_" + templateID).html("");
	let form = document.getElementById(`download_form_${templateID}`);
	let formdata = new FormData(form);
	formdata.set('templateID', templateID);
	formdata.set('rowID', rowID);
	formdata.set('colID', colID);
	app.request("getReportDatatableStructure", formdata).then(res => {
		if (res.status === 200) {
			if (res.head.length > 0) {
				let tableTemp = `<table class="table table-bordered" id="table_data1" style="width: 100%;">
						<thead>`;
				res.head.forEach(e => {
					tableTemp += `<th>${e.data}</th>`;
				});
				tableTemp += `</thead>
							<tbody>
							</tbody>
							</table>`;
				$("#reportDiv_" + templateID).html(tableTemp);
				getDatatable(res.body, res.head);
			}
		}
	});
}

function getDatatable(data, head) {
	$('#table_data1').DataTable({
		data: data,
		columns: head
	});
}

function DownloadData(templateID, rowID, colID, type) {
	let data = {};
	let a = $("#download_form_" + templateID).find('[name]').length;
	let cnt = 0;
	$("#download_form_" + templateID).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		if (value != "" && value != null) {
			cnt++;
		}
	});
	if (a == cnt) {
		var loginForm = $('#download_form_' + templateID).serializeArray();
		var loginFormObject = {};
		$.each(loginForm,
			function (i, v) {
				loginFormObject[v.name] = v.value;
			});
		loginFormObject['templateID'] = templateID;
		loginFormObject['rowID'] = rowID;
		loginFormObject['colID'] = colID;
		loginFormObject['type'] = type;
		const x = JSON.stringify(loginFormObject);
		window.location.href = baseURL + "DatatableEditorController/DownloadData?formdata=" + x;
	} else {
		app.errorToast('Fill complete form')
	}
}

function deleteColumnDataFromtable(columnName, tableName, id, ele) {
	let formdata = new FormData();
	formdata.set('columnName', columnName);
	formdata.set('tableName', tableName);
	formdata.set('id', id);
	app.request("deleteColumnDataFromtable", formdata).then(res => {
		if (res.status === 200) {
			$("#div_" + ele).html('');
			app.successToast(res.body);
		} else {
			app.errorToast(res.body);
		}
	});
}

// Input Search functions

function searchInputForm(ele) {
	let required = '';
	let onChange = '';
	if (ele.element.checkbox == 1) {
		required = 'data-valid="required" data-msg="Please Fill this Field"';
	}
	let queryTemp = `<div class="form-group">
				<label>${ele.element.label}</label>
				<div class="d-flex">
					<input type="text" name="text${ele.columnID}" id="text${ele.columnID}" placeholder="Search Here" ${required} class="form-control">
					<button type="button" class="btn btn-sm btn-primary" onclick="searchInputData('${ele.templateID}','${ele.rowID}','${ele.columnObjectID}','${ele.columnID}')"><i class="fa fa-search"></i></button>
				</div>

			</div>`;
	$("#" + ele.templateID + "-drop-zone-" + ele.columnObjectID).html(queryTemp);
}

function searchInputData(tempID, rowID, colObjectID, columnID) {
	if ($("#text" + columnID).val() != "") {
		$("#searchModal").modal('show');
		// searchDataTable
		let formData = new FormData();
		formData.set("templateID", tempID);
		formData.set("rowID", rowID);
		formData.set("columnID", colObjectID);
		formData.set("searchValue", $("#text" + columnID).val());
		app.request("getSearchInputData", formData).then(response => {
			if (response.status == 200) {
				let temp = getDataTableStructure(`searchInput${colObjectID}`, response.data);
				$("#searchDataTable").html(temp);
				loadSearchTableData(tempID, rowID, colObjectID, response.data, $("#text" + columnID).val());
			}
		});
	} else {
		app.errorToast('Fill Search');
	}
}

function loadSearchTableData(form_id, rowID, columnID, header, searchValue) {
	let tableId = `dynamicDataTable_searchInput${columnID}`;
	let TableCol = header.map(col => {
		if (col != "Action") {
			return {
				data: `${col}`
			}
		} else {
			return {
				data: 'Action',
				render: (d, t, r, m) => {
					return `<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" onclick="loadValueInINput('${btoa(JSON.stringify(d))}')"><i class="fa fa-search"></i></button></div>`;
				}
			}
		}
	});
	app.dataTable(tableId, {
		url: baseURL + "getInputSearchDataTableData",
		data: {
			templateID: form_id,
			rowID: rowID,
			columnID: columnID,
			searchValue: searchValue
		}
	}, TableCol, undefined, () => {
		app.confirmationBox();
	});
}

function loadValueInINput(data) {
	$("#searchModal").modal('hide');
	data = atob(data);
	data = JSON.parse(data);
	data.map((c, i) => {
		setInputValue(c.id, c.value);
	});
}

// dropdown with button
function dropdownButtonForm(ele) {
	console.log(ele);
	if (ele.element.hasOwnProperty('dropdownButton_dropdown')) {
		let querydropdown = ele.element.dropdownButton_dropdown;
		querydropdown.forEach(e => {
			console.log(e);
			let queryData = {"templateID": ele.templateID, "rowID": ele.rowID, "columnID": ele.columnObjectID};
			let selectTemp = ``;
			if (e.dropdowntype == 1 || e.dropdowntype == 2) {
				let option = e.option.map(o => {
					return `<option value="${o.id}">${o.text}</option>.`;
				});

				selectTemp = `<select name="queryDropdown${ele.columnID}" id="queryDropdown${ele.columnID}" class="form-control">
									${option}
							</select>`;

			} else {
				let onChange = '';

				let dependArray = [];
				let imdependArray = [];

				if (e.method == 1) {
					queryData.queryDependentTable = e.queryDependentTable;
					if (e.hasOwnProperty('queryDependentOn')) {
						if (e.queryDependentOn !== "Select One") {
							queryData.valueCheck = e.queryDependentValueCheck;
							queryData.dependOn = e.queryDependentOn;
						}
					}

					if (e.hasOwnProperty('queryisDependentOnOther')) {
						e.queryisDependentOnOther.forEach(e => {
							dependArray.push({Id: e.dValue, Value: e.dColumn});
						});
					}


					if (e.hasOwnProperty('queryDependentWhere')) {
						let whereArray = [];
						e.queryDependentWhere.forEach(e => {
							if (e[1] == 2) {
								if (e[2] != null) {
									e[2] = e[2].replace('#', "");
									whereArray.push({name: e[0], value: $("#session_" + e[2]).val()});
								}
							} else {
								whereArray.push({name: e[0], value: e[2]});
							}
						});
						queryData.whereArray = whereArray;
					}
				}
				if (dependArray.length > 0) {
					queryData.anyOneDepend = dependArray;
					anyOneDependOnMeObject.push(queryData);
					onChange = `onchange="getOnchangeDependantData('${ele.templateID}','${ele.rowID}','${ele.columnObjectID}',this.value)"`;
				}
				selectTemp = `<select name="queryDropdown${ele.columnID}" id="queryDropdown${ele.columnID}"  class="form-control select2" ${onChange}>
							</select>`;
			}
			let buttonTemp = ``;
			if (e.hasOwnProperty('button')) {
				let queryBtn = e.button;
				buttonTemp = `<button type="button" class="btn btn-primary btn-sm"  id="button${ele.columnID}" onclick="modalOpen('${queryBtn.templateId}','templateForm','${queryBtn.whereElementId}','queryDropdown${ele.columnID}')"><i class="fa fa-plus"></i></button>`;
				// data-toggle="modal" data-target="#templateModal"
			}
			let queryTemp = `<div class="form-group">
								<label>${ele.element.label}</label>
								<div class="d-flex">${selectTemp + buttonTemp}</div>
								
						   </div>`;
			$("#" + ele.templateID + "-drop-zone-" + ele.columnObjectID).html(queryTemp);
			console.log(queryData);
			if (e.dropdowntype == 3) {
				getQueryDropdownScript(`${ele.columnID}`, queryData);
			}
		})
	}
}

function modalOpen(tempId, divId, whereElementId, columnEleId) {
	let val = $("#" + columnEleId).val();
	if (val != "" && val != null) {

		$("#templateModal").modal('show');
		modalTemplateLoad(tempId, divId, whereElementId, val);

	}
}

function modalTemplateLoad(tempId, divId, whereElementId, val) {
	if ($('input[type="hidden"][data-id]').length) {
		if ($('input[type="hidden"][data-id]').attr('data-id') == whereElementId) {
			$('input[type="hidden"][data-id]').remove();
		}
	}

	$("#hiddenFields").append(`<input type="hidden" id="${whereElementId}" data-id="${whereElementId}" name="${whereElementId}" value="${val}">`);
	templateForm(tempId, divId);
}

function modalOpenWithValue(tempId, divId, whereElementId, val) {
	if (val != "" && val != null) {

		$("#templateModal").modal('show');
		modalTemplateLoad(tempId, divId, whereElementId, val);
	}
}

function modalOpenWithMultipleValue(tempId, divId, whereElementId, r) {

	if (whereElementId.length > 0) {
		$("#templateModal").modal('show');
		whereElementId.map(e => {
			if ($('input[type="hidden"][data-id]').length) {
				if ($('input[type="hidden"][data-id]').attr('data-id') == e.queryPara) {
					$('input[type="hidden"][data-id]').remove();
				}
			}
			console.log(e.queryPara + '||' + r['_' + e.column]);
			$("#hiddenFields").append(`<input type="hidden" id="${e.queryPara}" data-id="${e.queryPara}" name="${e.queryPara}" value="${r['_' + e.column]}">`);
		});

		templateForm(tempId, divId);
	}
}

// show hide column
function showHideFormColumns(value, columnId) {

	let templateIndex = hideShowArray.findIndex(i => i.columnObjectID == columnId);
	let object = null;
	if (templateIndex !== -1) {
		object = hideShowArray[templateIndex];
	}
	if (object) {
		object.hideshowColumns.map(e => {
			if (e.type == 2) {
				if (e.visibleFields != "") {
					setHideShowValue(e, value);
				}
			} else if (e.type == 1) {
				if (e.visibleFields != "") {
					setHideShowValue(e, -1);
				}
			}
		});
	}
}

function setHideShowValue(e, value) {
	e.visibleFields.map(v => {
		if (v.hasOwnProperty(value)) {
			if (v[value].length > 0) {
				v[value].forEach(i => {
					let parentFormGroup = $("#" + i).parent();
					let parentDropZone = parentFormGroup.parent();
					let columnDic = parentDropZone.parent();
					if (v['action'] == 1) {
						columnDic.show();
					} else if (v['action'] == 0) {
						columnDic.hide();
					} else if (v['action'] == 2) {
						columnDic.toggle();
					}
				});
			}
		}
	});
}

// need to move in inventory js
function getAllWareHouseLocation(type) {
	if (type == 2) {
		$("#it_customer_div").show();
		$("#it_customer_name").show();
	} else {
		$("#it_customer_div").hide();
		$("#it_customer_name").hide();
		$("#it_customer_name").val('');
	}
	let formdata = new FormData();
	formdata.set('type', type);
	app.request('getAllWareHouseLocation', formdata).then(res => {
		if (res.status === 200) {
			$(".i_location").html('');
			$(".i_location").html(res.locations);
			$(".i_location").select2();
		} else {
			$(".i_location").html('');
			$(".i_location").html(res.locations);
			$(".i_location").select2();
		}
	}).catch(error => console.log(error));
}

function saveLeaseReturnData(form) {
	let item_no = $("#i_item_no").val();
	let i_location = $("#i_location").val();

	let formdata = new FormData(form);

	app.request('saveLeaseReturnData', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			ShowForm(118);
			$("#inventoryModal").modal('hide');
			$("#" + form.id)[0].reset();
			window.location.reload(true);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function saveInventoryTransferData(form) {
	let location = $("#it_location").val();
	let it_customer_id = $("#it_customer_id").val();
	let it_location_type = $("#it_location_type").val();

	let formdata = new FormData(form);
	formdata.set('product_type', $("#cartproductType").val());
	app.request('saveInventoryTransferData', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			if ($("#cartproductType").val() == 1) {
				ShowForm(118);
			} else {
				ShowForm(146);
			}
			$("#leaseCartModal").modal('hide');
			$("#" + form.id)[0].reset();
			window.location.reload(true);
		} else {
			app.errorToast(res.body);
		}

	}).catch(error => console.log(error));

}

function getCustomerName(id, type) {
	let location_type = 'it_location_type';
	let customer_div = 'it_customer_div';
	let customer_name = 'it_customer_name';
	let customer_id = 'it_customer_id';
	if (type == 2) {
		location_type = 'it_location_type';
		customer_div = 'it_customer_div';
		customer_name = 'it_customer_name';
		customer_id = 'it_customer_id';


	}
	if (type == 3) {
		location_type = 'it_location_type';
		customer_div = 'it_customer_div';
		customer_name = 'it_customer_name';
		customer_id = 'it_customer_id';
		customer_location = 'it_location';
	}
	if (id == 'Select One') {
		app.errorToast('Please Select Location');
	}
	if (id != -1 && id != 'Select One') {
		if ($("#" + location_type).val() == 2) {
			$("#" + customer_div).show();
			let formdata = new FormData();
			formdata.set('id', id);
			app.request('getCustomerName', formdata).then(res => {
				if (res.status === 200) {
					$("#it_location").html('');
					$('#it_location').append(res.data);

					// $("#"+customer_name).val('');
					// $("#"+customer_name).val(res.customer);
					// $("#"+customer_id).val('');
					// $("#"+customer_id).val(res.customer_id);
				} else {
					$("#it_location").html('');
					$('#it_location').append(res.data);

					// $("#"+customer_name).val('');
					// $("#"+customer_name).val(res.customer);
					// $("#"+customer_id).val('');
					// $("#"+customer_id).val(res.customer_id);
				}
			}).catch(error => console.log(error));
		} else {
			$("#" + customer_div).hide();
		}
	} else {
		$("#" + customer_div).hide();
	}
}

function saveInventoryCardItemData(formdata) {
	app.request('saveInventoryCardItemData', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
		} else {
			app.errorToast(res.body);
		}

		getLeaseTransferCount();
		getAcceptanceItemCount();
		getAcceptdataCount();
	}).catch(error => console.log(error));

}

function getLeaseCardModal(type) {
	//alert(type);


	if (type == 1) {
		var total_cart_count = $('#productCartCount').val();
		//alert(total_cart_count);
	} else {
		var total_cart_count = $('#itemCartCount').val();
	}
	if (total_cart_count != 0) {
		$("#leaseCartModal").modal('show');
	} else {
		app.errorToast('No data available');
	}
	$('#leaseCartModal').on('shown.bs.modal', function (event) {
		$("#cartproductType").val(type);
		let formdata = new FormData();
		app.request('getCustomerList', formdata).then(res => {
			if (res.status === 200) {
				$(".customer_list").html('');
				$(".customer_list").html(res.data);
				$(".customer_list").select2();
			} else {
				$(".customer_list").html('');
				$(".customer_list").html(res.data);
				$(".customer_list").select2();
			}
		}).catch(error => console.log(error));
		getChalanNumber();
		getLeaseTransferCount();
		getAllLeaseCartDetails(type);
		if (type === 1) {
			templateForm(143, 'leaseCart');
		} else {
			getAllTransferCartDetails();
		}

		getAllWareHouseLocation(1);
	})
}

function getChalanNumber() {
	let formdata = new FormData();
	app.request('getChalanNumber', formdata).then(res => {
		if (res.status === 200) {
			$(".lc_chalan_no").val('');
			$(".lc_chalan_no").val(res.random);
		} else {
			$(".lc_chalan_no").val('');
			$(".lc_chalan_no").val(res.random);
		}
	}).catch(error => console.log(error));
}

function getCustomerLocation(id, type = null, product_type = 1) {
	let formdata = new FormData();
	formdata.set('customer_id', id);
	app.request('getCustomerLocation', formdata).then(res => {
		if (res.status === 200) {
			if (type == 1) {
				if (product_type == 1) {
					$("#i_location").html('');
					$("#i_location").html(res.data);
					$("#i_location").select2();
				} else {
					$("#ir_location").html('');
					$("#ir_location").html(res.data);
					$("#ir_location").select2();
				}

			} else {
				$("#lc_customer_location").html('');
				$("#lc_customer_location").html(res.data);
				$("#lc_customer_location").select2();
			}
		} else {
			if (type == 1) {
				if (product_type == 1) {
					$("#i_location").html('');
					$("#i_location").html(res.data);
					$("#i_location").select2();
				} else {
					$("#ir_location").html('');
					$("#ir_location").html(res.data);
					$("#ir_location").select2();
				}
			} else {
				$("#lc_customer_location").html('');
				$("#lc_customer_location").html(res.data);
				$("#lc_customer_location").select2();
			}
		}
	}).catch(error => console.log(error));
}

function saveItemLeaseTransactionData(form) {

	let formdata = new FormData(form);
	app.request('saveItemLeaseTransactionData', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			$("#leaseCartModal").modal('hide');
			let type = $("#inventorytype").val();
			if (type == 1) {
				ShowForm(118);
			} else {
				ShowForm(146);
			}
			$("#" + form.id)[0].reset();
			window.location.reload(true);
		} else {
			app.errorToast(res.body);

		}
	}).catch(error => console.log(error));


}

/*---new code add---*/

/*$('#inventoryTransferForm').submit(function(){
	var location = $('#it_location').val();
	if(location == 'Select One' || location == '')
	{
		$('.err_show').html('Please select location').css('color','red');
		//return false;
	}
    //alert(location);
});*/


function transfer_valid() {
	app.formValidation();
}

/*---new code add---*/


function getAllLeaseCartDetails(type) {
	let formdata = new FormData();
	formdata.set('type', type);
	app.request('getAllLeaseCartDetails', formdata).then(res => {
		let temp = '';
		if (type === 1) {
			temp = `
				<table class="table">
					<thead>
					<tr>
					<th>Item No.</th>
					<th>Product Name</th>
					<th>Taxable Amount</th>
					<th>Action</th>
					</tr>
					</thead>
				<tbody>
				`;
		} else {
			temp = `
				<table class="table">
					<thead>
					<tr>
					<th>Item No.</th>
					<th>Item Name</th>
					<th>Item Quantity</th>
					<th>Taxable Amount</th>
					<th>Qty</th>
					<th>Action</th>
					</tr>
					</thead>
				<tbody>
				`;
		}
		if (res.status === 200) {
			if (res.body.length > 0) {
				temp += getTableDesign(res.body, type);
			} else {
				temp += `<tr><td colspan="4">No Item In Cart</td></tr>`;
			}
		}

		temp += `</tbody>
				</table>
			`;
		$("#leaseCartTable").html(temp);
		app.formValidation();

	}).catch(error => console.log(error));
}

function getAllTransferCartDetails() {
	let formdata = new FormData();
	app.request('getAllTransferCartDetails', formdata).then(res => {
		let temp = '';
		temp = `
				<table class="table mt-2" id="tdata">
					<thead>
					<tr>
					<th>Item No.</th>
					<th>Product Name</th>
					<th>Location</th>
					<th>Qty</th>
					<th>Action</th>
					</tr>
					</thead>
				<tbody>
				`;

		if (res.status === 200) {
			if (res.body.length > 0) {
				temp += getTransferTableDesign(res.body);
			} else {
				temp += `<tr><td colspan="4">No Item In Cart</td></tr>`;
			}
		}

		temp += `</tbody>
				</table>
			`;
		$("#leaseCart").html(temp);
		$("#tdata").DataTable()

		app.formValidation();

	}).catch(error => console.log(error));
}

function getLeaseTransferCount() {
	let formdata = new FormData();
	formdata.set('product_type', $("#inventorytype").val());
	app.request('getLeaseTransferCount', formdata).then(res => {
		if (res.status === 200) {

			let lease_count = res.lease_count;
			let transfer_count = res.transfer_count;
			$('#cart-empty').show();
			if (lease_count == 0 && transfer_count == 0) {
				$('#cart-empty').hide();
			}
			$('#productCartCount').val(res.total);
			$('#itemCartCount').val(res.total);
			$('#cartcount').html('');
			$('#cartcount').append(res.total);
			$('#cartcounts').html('');
			$('#cartcounts').append(res.total);
			$('#lease-tab3').hide();

			$('#lease-tab3').removeClass('active show');
			$('#leasePanel').removeClass('active show');
			$('#transfer-tab3').hide();
			$('#transfer-tab3').removeClass('active show');
			$('#transferPanel').removeClass('active show');

			if (lease_count > 0 && transfer_count > 0) {

				$('#lease-tab3').show();
				// $('#lease-tab3').click();
				$('#transfer-tab3').show();

				$('#lease-tab3').addClass('active show');
				$('#leasePanel').addClass('active show');

			} else {
				if (lease_count > 0) {
					$('#lease-tab3').show();

					// $('#lease-tab3').click();
					$('#lease-tab3').addClass('active show');
					$('#leasePanel').addClass('active show');
				}
				if (transfer_count > 0) {
					$('#transfer-tab3').show();

					// $('#transfer-tab3').click();
					$('#transfer-tab3').addClass('active show');
					$('#transferPanel').addClass('active show');
				}
			}

		} else {

			//app.errorToast(res.body);

			$('#cartcount').html('');
			$('#cartcount').append(res.total);
			$('#cartcounts').html('');
			$('#cartcounts').append(res.total);
			$('#cart-empty').hide();
			$('#lease-tab3').hide();
			$('#leasePanel').removeClass('active show');
			$('#transfer-tab3').hide();
			$('#transferPanel').removeClass('active show');
		}

	}).catch(error => console.log(error));
}

function getTableDesign(data, type) {
	let temp = ``;
	let leaseAtId = [];
	if (type === 1) {
		data.map((e, i) => {
			leaseAtId.push(`leastamt${i}`);
			temp += `<tr id="leaseCartDiv${i}">
						<td>${e.Item_no}</td>
						<td>${e.ItemName}</td>
						<td><input type="number" name="leaseAmount[]" class="form-control" id="leastamt${i}" data-valid="required|min=1" data-msg="Please Enter Valid Amount"  data-error="#lease-error${i}" min="1">
						<input type="hidden" name="serial_no[]" value="${e.serial_no}">
						<input type="hidden" name="item_id[]" value="${e.item_id}">
						<input type="hidden" name="item_type[]" value="${e.item_type}">
						<input type="hidden" name="in_location[]" value="${e.in_location}">
						<div id="lease-error${i}"></div></td>
						<td>
						<button type="button" class="btn btn-sm btn-primary mx-1"
						onclick="deleteLeaseCartItem(${e.id},'leaseCartDiv${i}')" ><i class="fa fa-trash"></i></button></td>
					</tr>`;
		})
	} else {
		data.map((e, i) => {
			leaseAtId.push(`leastamt${i}`);
			temp += `<tr id="leaseCartDiv${i}">
						<td>${e.Item_no}</td>
						<td>${e.ItemName}</td>
						<td>${e.before_qty}</td>
						<td><input type="number" name="leaseAmount[]" class="form-control" id="leastamt${i}" data-valid="required" data-msg="Please Enter Valid Amount" data-error="#lease-error${i}" onchange="checkTaxAmt(this.value,leastamt${i})" onkeyup="checkTaxAmt(this.value,leastamt${i})" onkeydown="checkTaxAmt(this.value,leastamt${i})">
						<input type="hidden" name="serial_no[]" value="${e.serial_no}">
						<input type="hidden" name="item_id[]" value="${e.item_id}">
						<input type="hidden" name="item_type[]" value="${e.item_type}">
						<input type="hidden" name="in_location[]" value="${e.in_location}">
						<div id="lease-error${i}"></div></td>
						<td><input type="number" name="qty[]" class="form-control" id="qty${i}" data-valid="required" data-msg="Please Fill the Amount" data-error="#qty-error${i}"  onkeyup="checkQuantity(this.value,'cartTableleaseBeforeQty${i}','Lease','qty${i}')"  onchange="checkQuantity(this.value,'cartTableleaseBeforeQty${i}','Lease','qty${i}')" onkeydown="checkQuantity(this.value,'cartTableleaseBeforeQty${i}','Lease','qty${i}')"></td>
						<input type="hidden" name="cartTableleaseBeforeQty[]" value="${e.before_qty}" id="cartTableleaseBeforeQty${i}"><td>
						<button type="button" class="btn btn-sm btn-primary mx-1"
						onclick="deleteLeaseCartItem(${e.id},'leaseCartDiv${i}')" ><i class="fa fa-trash"></i></button></td>
					</tr>`;
		})
	}
	return temp;
}

function getTransferTableDesign(data) {
	let temp = ``;
	let leaseAtId = [];
	data.map((e, i) => {
		leaseAtId.push(`leastamt${i}`);
		temp += `<tr id="leaseCartDiv${i}">
						<td>${e.Item_no}</td>
						<td>${e.ItemName}</td>
						<td>${e.location}</td>
						<td><input type="number" name="transferqty[]" class="form-control" id="transferqty${i}" data-valid="required" data-msg="Please Fill the Amount" data-error="#qty-error${i}" onkeyup="checkQuantity(this.value,'cartTableBeforeQty${i}','Transfer','transferqty${i}')" onkeydown="checkQuantity(this.value,'cartTableBeforeQty${i}','Transfer','transferqty${i}')" onchange="checkQuantity(this.value,'cartTableBeforeQty${i}','Transfer','transferqty${i}')">
						<input type="hidden" name="cartTableId[]" value="${e.id}">
						<input type="hidden" name="cartTableBeforeQty[]" value="${e.before_qty}" id="cartTableBeforeQty${i}">
						<div id="qty-error${i}"></div> </td>
						<td><button type="button" class="btn btn-sm btn-primary mx-1"
						onclick="deleteLeaseCartItem(${e.id},'leaseCartDiv${i}')" ><i class="fa fa-trash"></i></button></td>
					</tr>`;
	})

	return temp;
}

function checkQuantity(value, id, type, inputId) {
	let inventory_qty = $("#" + id).val();
	// console.log(inventory_qty);
	if (parseInt(value) > parseInt(inventory_qty) || parseInt(value) <= 0) {
		app.errorToast(' Quantity should be less than or equal to ' + inventory_qty);
		$("#" + inputId).val('');
	}
}


/*---NEW CODE ---*/
function checkTaxAmt(val, inputTaxId) {
	if (parseInt(val) <= 0) {
		app.errorToast('Taxable Amount should be greater than 0');
	}


}


/*$('#id').on("keyup keydown change",function(event){

    //code that's working like a charm
});*/


/*---NEW CODE ---*/


function deleteLeaseCartItem(id, divId) {
	if (confirm('Are you sure you want to Remove From Cart?')) {
		let formdata = new FormData();
		formdata.set('id', id);
		app.request('deleteLeaseCartItem', formdata).then(res => {
			if (res.status === 200) {
				app.successToast(res.body);
				$("#" + divId).remove();
			} else {
				app.errorToast(res.body);
			}
			getLeaseTransferCount();
			getAcceptanceItemCount();
			getAcceptdataCount();
		}).catch(error => console.log(error));
	}
}

function saleTypeValidation(type) {
	$("#lc_customer").val(null).trigger('change');

	if (type == 1) {
		$("input[name='lc_lease_start_date']").rules("add", {
			required: true,
			messages: {
				required: "Please Fill the Amount"
			}
		});
		$("input[name='lc_lease_end_date']").rules("add", {
			required: true,
			messages: {
				required: "Please Fill the Amount"
			}
		});
		$("input[name='lc_po_no']").rules("add", {
			required: true,
			messages: {
				required: "Please Fill the Po No."
			}
		});
		$("#lc_customer_location").html('');
		$("#lc_customer").val(null).trigger('change');
		$("#lc_lease_start_date_div").show();
		$("#lc_lease_end_date_div").show();
		$("#lc_po_no_div").show();
		$("#lc_detail").hide();
		$(".hide_div").show();
	} else if (type == 2 || type == 3) {
		$("#lc_customer_location").html('');
		$(".hide_div").show();
		$("#lc_detail").hide();
		$("#lc_lease_start_date_div").hide();
		$("#lc_lease_end_date_div").hide();
		$("#lc_po_no_div").hide();
		$("input[name='lc_lease_start_date']").rules("remove");
		$("input[name='lc_lease_end_date']").rules("remove");
		$("input[name='lc_po_no']").rules("remove");
	} else if (type == 4) {
		$(".hide_div").hide();
		$(".lc_detail").show();
		$("#lc_lease_start_date_div").hide();
		$("#lc_lease_end_date_div").hide();
		$("#lc_po_no_div").hide();
		$("input[name='lc_lease_start_date']").rules("remove");
		$("input[name='lc_lease_end_date']").rules("remove");
		$("input[name='lc_po_no']").rules("remove");
		let formdata = new FormData();
		formdata.set('type', 1);
		app.request("getAllWareHouseLocation", formdata).then(res => {
			if (res.status === 200) {
				$("#lc_customer_location").html('');
				$("#lc_customer_location").html(res.locations);
				$("#lc_customer_location").select2();
			} else {
				app.errorToast(res.body);
			}
		}).catch(error => console.log(error));

	}

}

function cartEmpty(event) {
	let formdata = new FormData();
	formdata.set('product_type', $("#cartproductType").val());
	app.request('cartEmpty', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			$('#leaseCartModal').modal('hide');
			window.location.reload(true);

			getLeaseTransferCount();
			getAcceptdataCount();
			getAcceptanceItemCount();
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getLocationForReturnmodal(type, product_type = 1) {
	if (type == 1) {
		getAllWareHouseLocation(type)
	} else {
		let cust_id = $("#i_customer_id").val();
		if (product_type == 2) {
			cust_id = $("#ir_customer_id").val();
		}

		getCustomerLocation(cust_id, 1, product_type);
	}
}

function saveLeaseReturnDatareturn(form) {
	let item_no = $("#ir_item_no").val();
	let formdata = new FormData(form);

	app.request('saveLeaseReturnDataReturn', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			ShowForm(146);

			$("#inventoryModalReturn").modal('hide');
			$("#" + form.id)[0].reset();
			window.location.reload(true);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}


function saveConsumption(form) {
	let item_no = $("#ir_item_no").val();
	let formdata = new FormData(form);
	app.request('saveConsumption', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			let type = $("#inventorytype").val();
			if (type == 1) {
				ShowForm(118);
			} else {
				ShowForm(146);
			}
			$("#consumptionModal").modal('hide');
			$("#" + form.id)[0].reset();
			window.location.reload(true);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getAcceptdataCount() {

	let formdata = new FormData();
	//---new add
	formdata.set('type', $("#inventorytype").val());
	app.request('getAcceptdataCount', formdata).then(res => {
		if (res.status === 200) {
			// app.successToast(res.body);
			$('#productAcceptCount').val(res.total);
			$('#acceptcount').html('');
			$('#acceptcount').append(res.total);
		} else {
			$('#productAcceptCount').val(res.total);
			$('#acceptcount').html('');
			$('#acceptcount').append(res.total);
		}
	}).catch(error => console.log(error));
}

function getAcceptanceItemCount() {
	let formdata = new FormData();
	formdata.set('type', $("#inventorytype").val());
	app.request('getAcceptanceItemCount', formdata).then(res => {
		if (res.status === 200) {
			// app.successToast(res.body);
			$('#itemAcceptCount').val(res.total);
			$('#acceptcounts').html('');
			$('#acceptcounts').append(res.total);

		} else {
			$('#itemAcceptCount').val(res.total);
			$('#acceptcounts').html('');
			$('#acceptcounts').append(res.total);
			// app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function generateBillReport(form) {
	let bill_year = $("#bill_year").val();
	let bill_type = $("#bill_type").val();
	let report_type = $("#report_type").val();
	if (bill_year == '') {
		app.errorToast('Please Select Year');
	} else if (bill_type == '') {
		app.errorToast('Please Select Type');
	}
	window.location.href = baseURL + 'generateBillReport/' + bill_year + '/' + bill_type + '/' + report_type;
	$("#billGenerateReportModal").modal('hide');
	$("#" + form.id)[0].reset();
}

function billReport() {
	$("#billGenerateReportModal").modal('show');
	$('#billGenerateReportModal').on('shown.bs.modal', function (event) {

		let formdata = new FormData();
		app.request('getCustomerBillYear', formdata).then(res => {
			if (res.status === 200) {
				$("#bill_year").html('');
				$("#bill_year").html(res.data);
				$("#bill_year").select2();
			} else {
				$("#bill_year").html('');
				$("#bill_year").html(res.data);
				$("#bill_year").select2();
			}
		}).catch(error => console.log(error));


	})
}

function vendorBillReport() {
	$("#billVendorReportModal").modal('show');
	$('#billVendorReportModal').on('shown.bs.modal', function (event) {

		let formdata = new FormData();
		app.request('getVendorBillYear', formdata).then(res => {
			if (res.status === 200) {
				$("#bill_years").html('');
				$("#bill_years").html(res.data);
				$("#bill_years").select2();
			} else {
				$("#bill_years").html('');
				$("#bill_years").html(res.data);
				$("#bill_years").select2();
			}
		}).catch(error => console.log(error));

	})
}

function generateVendorBillReport(form) {
	let bill_years = $("#bill_years").val();
	let bill_types = $("#bill_types").val();
	let report_types = $("#report_types").val();

	if (bill_years == '') {
		app.errorToast('Please Select Year');
	} else if (bill_types == '') {
		app.errorToast('Please Select Type');
	}
	window.location.href = baseURL + 'generateVendorBillReport/' + bill_years + '/' + bill_types + '/' + report_types;
	$("#billVendorReportModal").modal('hide');
	$("#" + form.id)[0].reset();
}

function dataTableAdjustment() {
	$($.fn.dataTable.tables(true)).DataTable()
		.columns.adjust();
}

function editLeaseDate(d, r) {
	let rowString = JSON.stringify(r);
	let rowEncode = JSON.parse(rowString);
	let rowDecode = btoa(rowString);
	let id = rowEncode['_item_id'];
	let buttonString = ``;
	buttonString += `<button type="button" class="btn btn-primary btn-sm" onclick="openLeaseEditForm('${id}')"><i class="fa fa-pen"></i></button>
<button type="button" class="btn btn-primary btn-sm ml-2" onclick="addLeasetoBill('${rowDecode}')"><i class="fa fa-cart-arrow-down"></i></button>`;
	return buttonString;
}

function openLeaseEditForm(id) {
	$("#leaseEditModal").modal('show');
	$("#item_id").val(id);
	let formdata = new FormData();
	formdata.set('id', id);
	app.request('getLeaseDetails', formdata).then(res => {
		if (res.status === 200) {
			$("#customer_name").val(res.customer_name);
			$("#order_id").val(res.order_id);
			$("#brand_name").val(res.brand_name);
			$("#item_name").val(res.item_name);
			$("#lease_start").val(res.lease_start);
			$("#lease_end").val(res.lease_end);
			$("#taxable_amount").val(res.taxable_amount);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function UpdateLeaseDate(form_id) {
	let formd = document.getElementById(form_id);
	let formdata = new FormData(formd);
	app.request('UpdateLeaseDetails', formdata).then(res => {
		if (res.status === 200) {
			$("#leaseEditModal").modal('hide');
			ShowForm(113);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function addLeasetoBill(data) {
	let rData = atob(data);

	let customer_id = $("#customer_id").val();
	let ordeDetails = JSON.parse(rData);
	console.log(ordeDetails);
	let formdata = new FormData();
	formdata.set('customer_id', customer_id);
	formdata.set('order_id', ordeDetails['Order Id']);
	formdata.set('item_id', ordeDetails['_item_id']);
	formdata.set('item_name', ordeDetails['Item Name']);
	formdata.set('service_tag', ordeDetails['ServiceTag']);
	app.request('addLeasetoBillCart', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			billingCartCount();
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function billingCartCount() {
	return new Promise(function (resolve, reject) {
		let customer_id = $("#customer_id").val();
		let formdata = new FormData();
		formdata.set('customer_id', customer_id);
		app.request('getbillingCartCount', formdata).then(res => {
			if (res.status === 200) {
				$("#billingCartCount").html(res.body);
				$("#billingCartCnt").val(res.body);
				resolve(res);
			} else {
				app.errorToast(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function materialReturnShowHide(d, r) {
	let rowString = JSON.stringify(r);
	let rowEncode = btoa(rowString);
	let buttonString = ``;
	if (r['_order_status'] != 9) {
		buttonString += `<button type="button" class="btn btn-primary" onclick="openMaterialReturn('${rowEncode}','templateForm')">Return</button>`;
	}
	return buttonString;
}

function openMaterialReturn(materialdata, materialform) {
	let rdecode = atob(materialdata);
	let RDecoded = JSON.parse(rdecode);
	console.log(RDecoded);

	$("#returnmaterialModal").modal('show');

	$("#com_material_id").val(RDecoded['_id'])
	$("#ARN_no").val(RDecoded['ARN No']);
	$("#material_code").val(RDecoded['Material Code']);
	$("#material_name").val(RDecoded['Material Name']);
	$("#vendor_name").val(RDecoded['_vendor_name']);
	$("#supply_quantity").val(RDecoded['Quantity']);
}

function ReturnMaterial(form_id) {
	let formd = document.getElementById(form_id);
	let formdata = new FormData(formd);
	app.request('saveReturnMaterialData', formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			$("#returnmaterialModal").modal('hide');
			ShowForm(192);

		} else {
			app.errorToast(res.body);
		}
	});
}


function inventoryMaterialViewModal(d, r) {
	let rowString = JSON.stringify(r);
	let rowEncode = btoa(rowString);
	let buttonString = ``;
	buttonString += `<button type="button" class="btn btn-primary btn-sm" onclick="inventoryMaterialModal('${rowEncode}')"><i class="fa fa-eye"></i></button>`;
	return buttonString;
}

function inventoryMaterialModal(materialdata) {
	let rdecode = atob(materialdata);
	let RDecoded = JSON.parse(rdecode);
	console.log(RDecoded);

	$("#inventoryMaterialView").modal('show');


	$("#ARN_no_t").html(RDecoded['ARN No']);
	$("#material_code_t").html(RDecoded['Material Code']);
	$("#material_name_t").html(RDecoded['Material Name']);

	getTransactionData(RDecoded['_id']);


}


function getTransactionData(id) {
	let formdata = new FormData();
	formdata.set('id', id);
	app.request("getTransactionData", formdata).then(res => {
		$("#transactionTable").DataTable({
			destroy: true,
			order: [],
			data: res.data,
			columns: [
				{data: 0},
				{data: 1},
				{data: 2},
				{data: 3},
				{data: 4},
				{data: 5},
			],
			fnRowCallback: (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {

			}
		});
	}).catch(error => console.log(error));
}

let arr = [0];

function addMoreRow(cnt) {

	let count = $("#" + cnt).val();
	count++;
	let tablecolumn = '';
	getQueryTableData().then(res => {
		tablecolumn = res;

		arr.push(count);
		var html = ``;
		html += `<div class="row" id="addnewRow${count}">`;
		html += `<div class="col-2">`;
		html += `<div class="form-group">`;
		html += `<lable>Column Name</lable>`;
		html += `<input type="text" name="addmore_text_name${count}" id="addmore_text_name${count}" placeholder="Enter Column Name" 
					class="form-control">`;
		html += `</div>`;
		html += `</div>`;
		html += `<div class="col-2">`;
		html += `<div class="form-group">`;
		html += `<lable>Select Type</lable>`;
		html += `<select class="form-control" id="addmore_selecttype${count}" name="addmore_selecttype${count}">`;
		html += `<option value="numeric">Numeric</option>`;
		html += `<option value="dropdown">Dropdown</option>`;
		html += `<option value="text">Text</option>`;
		html += `<option value="date">Date</option>`;
		html += `<option value="hidden">Hidden</option>`;
		html += `</select>`;
		html += `</div>`;
		html += `</div>`;
		html += `<div class="col-1">`;
		html += `<div class="form-group">`;
		html += `<lable>Is Query</lable>`;
		html += `<select class="form-control" id="addmore_query${count}" name="addmore_query${count}">`;
		html += `<option value="0">No</option>`;
		html += `<option value="1">Yes</option>`;
		html += `</select>	`;
		html += `</div>`;
		html += `</div>`;
		html += `<div class="col-3">`;
		html += `<div class="form-group">`;
		html += `<lable>Attribute Value</lable>`;
		html += `<input type="text" name="attribute_value${count}" id="attribute_value${count}" placeholder="Enter Attribute Value" class="form-control">`;
		html += `</div>`;
		html += `</div>`;
		html += `<div class="col-2">`;
		html += `<div class="form-group">`;
		html += `<lable>Default Value</lable>`;
		html += `<input type="text" name="default_value${count}" id="default_value${count}" placeholder="Default Value" class="form-control">`;
		html += `</div>`;
		html += `</div>`;
		html += `<div class="col-md-2">`;
		html += `<label>Select Column Name </label>`;
		html += `<select name="query_addmore_column" id="query_addmore_column${count}" class="form-control queryDropdownSelectColumn${count} select2">${tablecolumn}</select>`;
		html += `</div>`;
		html += `<div class="col-2">`;
		html += `<button type="button" class="btn btn-sm btn-danger remove" onclick="removeAddMoreRow(${count})"><i class="fa fa-minus"></i></button>`;
		html += `</div>`;
		html += `</div>`;

		$('#newRowadd').append(html);
		$("#" + cnt).val(count);
	});
}

let queryOption = '';

function getQueryTableData() {
	return new Promise(function (resolve, reject) {
		let table_name = $("#addmore_query_table").val();

		getTableColumn(table_name).then(res => {
			queryOption = '';
			getLabels().then(result => {
				let fields = res;
				for (let i = 0; i < fields.length; i++) {
					queryOption += `<option value="${fields[i]}">${fields[i]}</option>`;

				}

				$(".queryDropdownSelectColumn0").html('');
				$(".queryDropdownSelectColumn0").html(queryOption).select2();

				resolve(queryOption);
			});
		});
	});
}

function getTableColumn(table_name) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('table_name', table_name);
		app.request('getTableColumn', formdata).then(res => {
			if (res.status === 200) {
				let data = res.data;
				resolve(data);
			} else {
				reject();
			}
		}).catch(error => console.log(error));
	});
}

function getAllTablesNamesList() {
	app.request("getAllTablesList", null).then(res => {

		$("#addmore_query_table").html(res.option);
		$("#addmore_query_table").select2({

			allowClear: true
		});

	}).catch(error => console.log(error));
}

function getLabels() {

	return new Promise(function (resolve, reject) {
		let options = '<option selected>Select One</option>';
		let rows = pageConfiguration.rows.sort((a, b) => a.seqNo - b.seqNo).map((row) => {
			let colTemplate = row.cols.map(columnObject => {
				let hiddenRow = pageConfiguration.hiddenFields.find(h => (h.row === row.id && h.col === columnObject.id))
				if (hiddenRow) {

				}
				if (columnObject.hasOwnProperty('element')) {
					if (columnObject.element != null) {
						if (columnObject.element.hasOwnProperty('type')) {
							let option_id = columnObject.element.type + "" + columnObject.id;
							if (columnObject.element.hasOwnProperty('label')) {
								options += `<option value="${option_id}">${columnObject.element.label}</option>`;
							}
						}
					}
				}
			})
		})
		resolve(options);
	});
}

function getOptionValue() {
	return new Promise(function (resolve, reject) {

		queryOption = '';
		getLabels().then(result => {
			let fields = res;
			for (let i = 0; i < fields.length; i++) {
				queryOption += `<option value="${fields[i]}">${fields[i]}</option>`;

			}


			resolve(queryOption);
		});

	});
}

function addMoreRows(column_id, rdecode, form_id, mode = 1) {

	return new Promise(function (resolve, reject) {
		let cnt;
		if (mode === 1) {
			cnt = $("#no_rows_count").val();
			cnt++;
		} else {
			cnt = column_id
		}
		//let cnt = column_id ; //$("#no_rows_count").val();
		//cnt++;
		let columnObject = JSON.parse(atob(rdecode));
		console.log(columnObject);
		let querytablename = columnObject.element.query_table_name;

		// let cntval=0;
		loadAddmoreDivData(columnObject, column_id, form_id, mode, 2).then(r => {
			let content = '';
			content += `<div class="align-items-end row" id="addMoreDiv${cnt}">
			<input type="hidden" class="form-control addmore" id="pk_${cnt}"  name="update_addmore[]" value="0">
			<input type="hidden" name="query_table" id="query_table" value="${querytablename}">
			<div class="col-md-11">
			
			  <div class="row">`;
			content += r;

			content += `</div>
				</div>
				<div class="col-md-1">
					<div class="">
					<button class="btn btn-primary" type="button" onclick="removeAddMoreDiv(${cnt},'${querytablename}')"><i class="fa fa-trash"></i>
						</button>
				
					</div>
				</div>
				
				</div>`;

			// cntval ++;


			$("#addMoreRows").append(content);

			$("#no_rows_count").val(cnt);
			app.formValidation();
			resolve(content);
		})

	});
}

function loadAddMoreRowsData(columnObject, form_id, column_id) {
	let eleData = columnObject.element.addmore_option_array;
	let querytablename = columnObject.element.query_table_name;
	let rowString = JSON.stringify(columnObject);
	let rdecode = btoa(rowString);
	let cnt = $("#no_rows_count").val();
	let content = ``;
	return loadAddmoreDivData(columnObject, column_id, form_id).then(r => {
		content += `<form method="post" id="addMoreForm" data-form-valid="saveAddMoreData" enctype="multipart/form-data">
              <input type="hidden" name="template_id" id="addMoreTemplateId" value="${form_id}">
		<div  id="addMoreRows">
		 <div class="align-items-end row" id="addMoreDiv0">
		 <input type="hidden" class="form-control addmore" id="pk_0"  name="update_addmore[]" value="0">
		 <input type="hidden" name="query_table" id="query_table" value="${querytablename}">
		      <div class="col-md-11">
			 		 <input type="hidden" name="no_rows_count" id="no_rows_count" value="0">
					  
		            <div class="row">`;

		content += r;

		content += `</div>
			</div>
			<div class="col-md-1">
				<div class="">
				<button class="btn btn-primary" type="button" onclick="removeAddMoreDiv(0,'${querytablename}')"><i class="fa fa-trash"></i>
					</button>
			
				</div>
			</div>
			
		</div></div>`;
		content += `
		<div class="">
			<button class="btn btn-primary" type="button" id="addMore${form_id}" onclick="addMoreRows(${column_id},'${rdecode}',${form_id})">(Add More)
				</button>
				
			</div>
			
		<div class="form-group">
		<button type="submit" class="btn btn-primary" data-form="${form_id}" id="button${column_id}"  style="margin-top: 30px">Save</button>
		</div>
</form>`;
		return content;
	});


}

let cnt = -1;

function loadAddmoreDivData(columnObject, column_id, form_id, mode = 1, type = 1) {

	if (mode == 1 && type == 1) {
		column_id = 0;
	} else {
		column_id = $("#no_rows_count").val();
		column_id++;
	}
	if (mode == 2 && type == 2) {
		column_id = column_id;
	}
	let eleData = columnObject.element.addmore_option_array;
	let content = '';
	let tablecolumn = '';
	cnt++;
	return Promise.all(eleData.map(async (obj, i) => {


		if (obj.selecttype == 'numeric') {
			content += `
			<div class="col-md-3 mb-3">
			
			<label>${obj.text_name}</label>
				<input type="number" class="form-control" id="numeric${obj.col_id}_${cnt}"  name="numeric${obj.col_id}[]">
			
			</div>`;
		} else if (obj.selecttype == 'text') {
			content += `<div class="col-md-3 mb-3">
			
			<label>${obj.text_name}</label>
				<input type="text" class="form-control" id="text${obj.col_id}_${cnt}"  name="text${obj.col_id}[]">
			
			</div>`;
		} else if (obj.selecttype == 'dropdown') {

			let options = '';
			if (obj.more_query == 1) {
				options = await getDropdownArray(obj.value_attributes);
				// $("#dropdown" + obj.col_id + "_" + cnt).html('');
				// 	$("#dropdown" + obj.col_id + "_" + cnt).html(res);
				content += `<div class="col-md-3 mb-3">
			<label>${obj.text_name}</label>
			<select name="dropdown${obj.col_id}[]" id="dropdown${obj.col_id}_${column_id}" class="form-control select2">
			${options}
			</select>
			</div>`;
			} else {
				options = obj.value_attributes;
				content += `<div class="col-md-3 mb-3">
			<label>${obj.text_name}</label>
			<select name="dropdown${obj.col_id}[]" id="dropdown${obj.col_id}_${column_id}" class="form-control select2">
			${options}
			</select>
			</div>`;
			}

		} else if (obj.selecttype == 'date') {
			content += `<div class="col-md-3 mb-3">
			
			<label>${obj.text_name}</label>
			<input type="date" class="form-control" id="date${obj.col_id}_${column_id}"  name="date${obj.col_id}[]">
		
			</div>`;
		} else if (obj.selecttype === 'hidden') {
			content += `<div class="col-md-3 mb-3">
				
			<label>${obj.text_name}</label>
			<input type="hidden" class="form-control" id="hidden${obj.col_id}_${column_id}"  name="hidden${obj.col_id}[]">
			
			</div>`;
		}

	})).then(() => {
		return content;
	});

}

function removeAddMoreDiv(cnt, query_table) {


	let query_id = $("#pk_" + cnt).val();
	$("#no_rows_count" + cnt).val(cnt);
	let formData = new FormData();
	formData.set("query_table", query_table);
	formData.set("query_id", query_id);
	requestToAjax("removeAddmoreRows", formData).then(res => {
		if (res.status == 200) {
			$("#addMoreDiv" + cnt).remove();
			cnt--;
			// app.successToast(res.body);
		} else {
			// app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});

}


function saveAddMoreData(form) {


	let formd = document.getElementById(form.id);
	let formdata = new FormData(formd);
	app.request("saveAddMore", formdata).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}


function getDropdownArray(query) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('query', query);
		app.request("getOptions", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.data);
			} else {
				resolve(res.data);
			}
		}).catch(error => console.log(error));
	});
}

function getEditData(id) {
	let formdata = new FormData();
	formdata.set('id', id);
	// console.log(formdata);
	app.request("UpdateAddMore", formdata).then(res => {
		if (res.status === 200) {

			let cnt = res.col;
			let data = res.data;

			let countr = $("#no_rows_count").val();
			for (let i = 0; i < data.length; i++) {

				if (i !== 0 && data.length > 1) {
					//$("#addMore" + id).click();
					countr++;
					addMoreRows(countr, btoa(JSON.stringify(res.templateFormat)), id, 2).then(r => {
						$.map(data[i], function (val, index) {
							if (index.includes('date')) {
								$("#" + index + "_" + i).val(myDate(val));
							} else {
								$("#" + index + "_" + i).val(val);
							}

						});
					});
				} else {
					$.map(data[i], function (val, index) {
						if (index.includes('date')) {
							$("#" + index + "_" + i).val(myDate(val));

						} else {
							$("#" + index + "_" + i).val(val);
						}
					});
				}

				$("#no_rows_count").val(countr);
			}
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function myDate(val) {
	let date = new Date(val);
	var d = date.getDate();
	var m = date.getMonth() + 1; //Month from 0 to 11
	var y = date.getFullYear();
	return '' + y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);

}

function addBMR(data = null,type) {

	$("#bmr_update_id").val('');
	$("#bmr_name").val('');

	$("#paramCount").val(0);
	$("#newBMRModal").modal('show');
	$("#group_type").val(type);
	$("#queryparameterows").html('');
	if (data != null) {
		let data_arr = JSON.parse(atob(data));
		let id = data_arr['_id'];
		let name = data_arr['Report Name'];

		$("#bmr_update_id").val(id);
		$("#bmr_name").val(name);
		$("#bmr-list").hide();

		editBMRParamDetails(id);
	} else {
		$("#bmr-list").show();
		templateForm(208, 'queryparamdata');
		getBMRList();
		getQueryParameterList(0);
	}
}

function addNewBMR() {
	let name = $("#bmr_name").val();
	let type = $("#group_type").val();
	if (name != null && name != '') {

		let formd = document.getElementById('BMRForm');
		let formdata = new FormData(formd);

		app.request('addNewBMR', formdata).then(res => {
			if (res.status === 200) {
				$("#newBMRModal").modal('hide');
				app.successToast(res.body);
				if(type == 1){
					ShowForm(204);
				}else{
					ShowForm(209);
				}
			} else {
				app.errorToast(res.body);
			}
		}).catch(error => console.log(error));
	} else {
		app.errorToast('BMR Name cannot be Empty');
	}
}


function editBMRDetails(d, r) {
	let rowString = JSON.stringify(r);
	let rowEncode = btoa(rowString);
	let buttonString = ``;
	buttonString += `<button type="button" class="btn btn-primary btn-sm" onclick="addBMR('${rowEncode}')"><i class="fa fa-pen"></i></button>`;
	return buttonString;
}

function getBMRList() {
	app.request("getBMRList", null).then(res => {
		if (res.status === 200) {
			$("#bmr_list").html(res.data);
			$("#bmr_list").select2();
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getBMRNameList(id) {
	let formdata = new FormData();
	formdata.set('id', id);
	app.request("getBMRNameList", formdata).then(res => {
		if (res.status === 200) {
			let copydata = '_copy';
			$("#bmr_name").val(res.data + copydata);
			// app.successToast(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getQueryParameterList(cnt) {
	return new Promise(function (resolve, reject) {
		app.request("getQueryParameterList", null).then(res => {
			queryparameterOptions = res.data;
			$("#queryparameter_list_"+cnt).html(queryparameterOptions);
			$("#queryparameter_list_"+cnt).select2();

			$("#parameter_type_"+cnt).val('');
			$("#param_value_"+cnt).val('');
			resolve(queryparameterOptions);
		}).catch(error => console.log(error));
	});
}

function addMoreparam() {
	return new Promise(function (resolve, reject) {
		let cnt = $("#paramCount").val();
		cnt++;

		let queryparam = `
	<div class="row" id="queryparamdiv_${cnt}">
		<div class="col-md-5">
			 <div class="form-group" id="queryparameter_id_${cnt}">
			<label for="">Select QueryParameter</label>
			<select name="queryparameter_list[]" id="queryparameter_list_${cnt}" class="form-control select2">
			</select>
			 </div>
		</div>
		<div class="col-md-2">
			 <div class="form-group" id="query_parameter_type_${cnt}">
			<label for="">Select Type</label>
			<select name="parameter_type[]" id="parameter_type_${cnt}" class="form-control select2">
			<option value="1">Static</option>
			<option value="2">Session</option>
			<option value="3">Query</option>
			</select>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group" id="query_parameter_value_${cnt}">
			<label for="">Enter Parameter Value</label>
			<textarea class="form-control" name="param_value[]" id="param_value_${cnt}"></textarea>
			 </div>
		</div>
		<div class="col-md-1">
			<div>
			<button class="btn btn-primary" type="button" style="margin-top:40px;" onclick="removeQueryParam(${cnt});"><i class="fa fa-trash"></i>
			</button>
			</div>
		
		</div>
		
	</div>`;
		$("#queryparameterows").append(queryparam);
		// getQueryParameterList();
		$("#queryparameter_list_" + cnt).html(queryparameterOptions);
		$("#queryparameter_list_" + cnt).select2();
		$("#paramCount").val(cnt);
		app.formValidation();

		resolve(queryparam);
	})
}

function removeQueryParam(rowindex) {
	$("#queryparamdiv_" + rowindex).remove();
	rowindex--;
	$("#paramCount").val(rowindex);
}

function editBMRParamDetails(id) {
	let type = $("#groupPageType").val();
	let formdata = new FormData();
	formdata.set('id', id);
	formdata.set('type', type);
	app.request("getBMRParamData", formdata).then(res => {
		if (res.status === 200) {
			let data = res.data;
			data.map((r, i) => {
				if (i === 0) {
					getQueryParameterList(0).then(q => {
						$("#queryparameter_list_0").val(r.param_name).trigger('change');
						$("#parameter_type_0").val(r.param_type);
						$("#param_value_0").val(r.param_value);
					})
				} else {
					addMoreparam().then(e => {
						getQueryParameterList(i).then(q => {
							$("#queryparameter_list_" + i).val(r.param_name).trigger('change');
							$("#parameter_type_" + i).val(r.param_type);
							$("#param_value_" + i).val(r.param_value);
						})
					})
				}
			})
		} else {
			getQueryParameterList(0).then(q=>{
				$("#parameter_type_0").val('');
				$("#param_value_0").val('');
			})
		}
	}).catch(error => console.log(error));
}




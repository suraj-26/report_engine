const pageConfiguration = {
	rows: [],
	hiddenFields: [],
	operation: [],
	queryParamFields: []
};

let allQueryParamFields = {
	self: []
};
const dataTableFormArray = [];
let selectedColumn = null;
$(document).ready(function () {
	showComponents(1);
	$("#pageContainer").sortable({
		appendTo: 'body',
		helper: 'clone',
		cursor: "move",
		update: function (event, ui) {
			var dataArray = $(this).sortable("toArray");
			dataArray.forEach((i, index) => {

				let id = parseInt(i.split("-")[1]);
				let itemIndex = pageConfiguration.rows.findIndex((rowItem) => rowItem.id === id)
				if (itemIndex !== -1) {
					pageConfiguration.rows[itemIndex].seqNo = index;
				}
			});
			console.log(pageConfiguration.rows);
		}
	}).disableSelection();

	getAllTablesNames();
	getAllTemplateOptions();
	getAllTablesNamesList();


});

function findCount(element) {
	return document.querySelectorAll(element).length;
}

function addRow(value) {

	let counter = findCount('div');

	let rowId = checkRowAlreadyExist(counter);
	console.log(rowId);
	// let rowId = ++counter;
	let rowObject = {
		id: rowId,
		seqNo: pageConfiguration.rows.length,
		cols: []
	};
	let iterationCount;
	switch (parseInt(value)) {
		case 12:
			iterationCount = 1;
			break;
		case 6:
			iterationCount = 2;
			break;
		case 4:
			iterationCount = 3;
			break;
		case 3:
			iterationCount = 4;
			break;
	}
	let cols = iteration(iterationCount, parseInt(value), rowId, rowId);
	let colTemplate = cols.map(i => {
		rowObject.cols.push(i.column);
		return i.raw
	}).join("");
	let row = `<div id="rowGroup-${rowId}" class="rowGroup">
                    <span class="d-row" id="close-row-${rowId}" onclick="removeRow(${rowId})">x</span>
                    <div class="d-g-row row my-1" id="${rowId}" data-id="${rowId}">
                        ${colTemplate}
                    </div>
                </div>`;
	$("#pageContainer").append(row);
	if (pageConfiguration.rows.find(x => x.id == rowId)) {

	} else {
		pageConfiguration.rows.push(rowObject);
	}
}
function checkRowAlreadyExist(eleId)
{
	let count=++eleId;
	let rowEle=document.getElementById(count);
	if(rowEle)
	{
		return checkRowAlreadyExist(count);
	}
	else
	{
		return count;
	}
}
function updateElementInColumn(row, column, controlId, type, html) {

	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === column);
		if (colObject) {

			if (colObject.element != null) {
				if (colObject.element.hasOwnProperty('label') || colObject.element.hasOwnProperty('default_value')
					|| colObject.element.hasOwnProperty('queryTable') || colObject.element.hasOwnProperty('method') || colObject.element.hasOwnProperty('report_name')) {
					colObject.element.id = controlId;
					colObject.element.type = type;
				} else {
					if (controlId) {
						 colObject.element = {id: controlId, type: type};
					} else if (html) {
						colObject.html = html;
					}
				}
			} else {
				if (controlId) {
					colObject.element = {id: controlId, type: type};
				} else if (html === 1) {
					colObject.html = '';
				} else if (html){
					colObject.html = html;
				}
			}
		}
	}
}

function attachToColumn(element, dropZone, type, html = null) {
	let node = document.getElementById(element);
	let controlId = null;
	let controlType = null;


	if (node.dataset.hasOwnProperty("control_id")) {
		controlId = parseInt(node.dataset.control_id);
	}
	if (node.dataset.hasOwnProperty("type")) {
		controlType = node.dataset.type;
	}
	let dropZoneDiv = document.getElementById('drop-zone-' + dropZone);
	let row = parseInt(dropZoneDiv.dataset.row);
	let col = parseInt(dropZoneDiv.dataset.col);

	let newbtnId = findCount('.config_btn');
	let cloneElement = node.cloneNode();

	if (type) {
		let newId = findCount('.copy-control');
		++newId;
		// ++newbtnId;
		if (node.dataset.hasOwnProperty("lable")) {
			cloneElement.id = node.dataset.lable + "-" + newId;
			node.childNodes.forEach(e => {
				e.childNodes.forEach(r => {
					r.childNodes.forEach(items => {
						if (items.nodeType === 1) {
							if (items.hasAttribute("type")) {
								let type = items.getAttribute("type");
								if (type === "hidden") {
									if (items.dataset.hasOwnProperty("type")) {
										if (items.dataset.type == "queryParam") {
											items.id = node.dataset.lable + '-control-' + newId;
											console.log(pageConfiguration);
											console.log(cloneElement.id + "----" + items.id + "----" + row + "----" + col);
											pageConfiguration.queryParamFields.push({
												nodeId: cloneElement.id,
												element: {"id": items.id, "type": "hidden"},
												row: row,
												col: col
											});
										}
									} else {
										items.id = node.dataset.lable + '-control-' + newId;
										console.log(cloneElement.id + "----" + items.id + "----" + row + "----" + col);
										pageConfiguration.hiddenFields.push({
											nodeId: cloneElement.id,
											element: {"id": items.id, "type": "hidden"},
											row: row,
											col: col

										});
									}
								}
							}
						}
					})
				})
			})
		}
	}
	cloneElement.classList.add("mb-0");

	node.childNodes.forEach(items => {
		cloneElement.appendChild(items.cloneNode(true));
	});
	cloneElement.style.border = "none";

	if (element != 'lable-1' && element != 'button-1' && element != 'control-16' && element != 'clear-1') {
		cloneElement.getElementsByTagName('button')[0].id = 'config_btn-' + newbtnId;
		cloneElement.getElementsByTagName('button')[0].addEventListener('click', function () {
			setConfigurations(row, col, controlType);
		});
	} else if (element == 'button-1') {
		cloneElement.getElementsByTagName('button')[1].id = 'config_btn-' + newbtnId;
		cloneElement.getElementsByTagName('button')[1].addEventListener('click', function () {
			setConfigurations(row, col, controlType);
		});
	} else if (element == 'control-16') {
		cloneElement.getElementsByTagName('button')[0].id = 'config_btn-' + newbtnId;
		cloneElement.getElementsByTagName('button')[0].addEventListener('click', function () {
			setDatatableConfigurations(row, col, controlType);
		});
	} else if (element == 'lable-1') {
		cloneElement.getElementsByTagName('div')[0].id = 'lable_btn' + newbtnId;
		$("#lable_btn" + newbtnId).attr('contenteditable', 'false');
	}


	var htmlObject = '';
	if(html != null){
		var temp = document.createElement('div');
		temp.innerHTML = html;
		htmlObject = temp.firstChild;
		dropZoneDiv.append(htmlObject);
	}else{
		if(element == 'clear-1'){
			cloneElement = '';
		}
		dropZoneDiv.append(cloneElement);
	}
	if (controlId) {
		updateElementInColumn(row, col, controlId, controlType);
	} else {
		if(element == 'clear-1'){
			updateElementInColumn(row, col, controlId, controlType, 1);
		}else{
			if(html != null){
				updateElementInColumn(row, col, controlId, controlType,html);
			}else{
				updateElementInColumn(row, col, controlId, controlType, cloneElement.outerHTML);
			}
		}
	}

	selectedColumn = null;
	$("#" + dropZone).css({'background-color': 'white'});
	$("#config_btn-" + newbtnId).show();

}

function setSelectedColumn(id) {
	let dropZoneDiv = document.getElementById('drop-zone-' + id);
	if (dropZoneDiv != null) {
		dropZoneDiv.childNodes.forEach(items => {
			dropZoneDiv.removeChild(items);
		});
	}
	selectedColumn = id;
	$("#" + id).css({'background-color': '#ffc1079c'});
}

// if type is not null then generate new control id
function setControlToDropZone(element, type = null, html = null) {
	if (selectedColumn) {
		return attachToColumn(element, selectedColumn, type, html);
	}
}

function removeRow(rowId) {
	pageConfiguration.rows = pageConfiguration.rows.filter(i => i.id !== rowId);
	$("#rowGroup-" + rowId).remove();
	if (allQueryParamFields != "") {
		Object.keys(allQueryParamFields).filter((k) => {
			if (k === 'self') {
				let selfQuery = allQueryParamFields.self;
				allQueryParamFields.self = selfQuery.filter((e, i) => {
					if (e.hasOwnProperty('rowId')) {
						if (e.rowId !== rowId) {
							return e;
						}
					}
				});

			} else {
				if (allQueryParamFields[k].hasOwnProperty('rowId')) {
					if (allQueryParamFields[k].rowId === rowId) {
						return allQueryParamFields[k];
					}
				}
			}
		}).forEach(i => {
			delete allQueryParamFields[i];
		});


	}
}

function iteration(value, colSize, counter, rowId,e=null,type=1) {
	let colCounter=counter;
	let cols = [];

	if( e !== null) {
		e.cols.map(c=>{
			let colObject = getColumn(colSize, c.id, rowId)
			cols.push(colObject);
		})
	}else{
		for (let i = 0; i < value; i++) {
			let colId=++colCounter;
			if(type===1)
			{
				colId=checkRowAlreadyExist(colCounter);
				colCounter=colId;
			}
			let colObject = getColumn(colSize, colId, rowId)
			cols.push(colObject);
		}
	}
	return cols;

}

function getColumn(column, counter, rowId) {
	let columnObject = {
		id: counter,
		size: column,
		element: null,
		html: ''
	}

	return {
		column: columnObject,
		raw: `<div class=" d-col col-md-${column} col-sm-12" id="${counter}"  ondblclick="setSelectedColumn(${counter})">
                                            <div id="drop-zone-${counter}" data-col="${counter}"  data-row="${rowId}"></div>
                                        </div>`
	}
}

function setConfigurations(row, col, type) {

	$("#ConfigurationModal").modal('show');

	$("#shot_text_section").hide();
	$("#textarea_section").hide();
	$("#number_section").hide();
	$("#date_section").hide();
	$("#file_section").hide();
	$("#single_selection_section").hide();
	$("#multiple_selection_section").hide();
	$("#radio_section").hide();
	$("#checkbox_section").hide();
	$("#hidden_section").hide();
	$("#button_section").hide();
	$("#excel_report_section").hide();
	$("#pdf_report_section").hide();
	$("#csv_report_section").hide();
	$("#queryDropdown_section").hide();
	$("#queryParam_section").hide();
	$("#templateForm_section").hide();
	$("#handson_section").hide();
	$("#inputSearch_section").hide();
	$("#modalButton_section").hide();
	$("#dropdownButton_section").hide();
	$("#email_section").hide();
	$("#addmore_section").hide();

	if (type == 'hidden') {
		$("#hidden_section").show();
		$("#hidden_form")[0].reset();
		$("#hidden_row").val(row);
		$("#hidden_col").val(col);

		let hiddenRow = pageConfiguration.hiddenFields.find(h => (h.row === row && h.col === col));
		if (hiddenRow) {
			if (hiddenRow.element.hasOwnProperty('default_value_option')) {
				if (hiddenRow.element.default_value_option == 'session_value') {
					$('#hidden_default_value_option2').prop('checked', true);
				} else {
					$('#hidden_default_value_option1').prop('checked', true);
				}
				$("#hidden_default_value").val(hiddenRow.element.default_value);
			}
		}
	} else if (type == 'button') {
		$("#button_section").show();
		$("#button_row").val(row);
		$("#button_col").val(col);

		// if (pageConfiguration.hasOwnProperty('table')) {
		//
		// 	$("#toTable").select2('destroy');
		// 	$("#toTable").val(pageConfiguration.table).select2();
		// 	let Maincnt = pageConfiguration.table.length;
		// 	getButtonConfig(pageConfiguration.table,1).then(res=>{
		// if (pageConfiguration.hasOwnProperty('method')) {
		// 	let method = pageConfiguration.method;
		//
		// 	$("#config_method"+Maincnt).val(method[0]).trigger('change');
		// 	if(method[0] == 2){
		// 		let update_data = JSON.parse(method[1]);
		// 		createUpdateDiv(Maincnt).then(res=>{
		// 			for (const key in update_data) {
		// 				let i = key.split('_');
		// 				let val = update_data[key].split(":");
		// 				$("#where_value_"+Maincnt+"_" + i[2]).val(val[1]).trigger('change');
		// 			}
		// 		});
		// 	}
		// }
		// if (pageConfiguration.hasOwnProperty('data')) {
		// 	let data = pageConfiguration.data;
		// 	data = JSON.parse(data);
		// 	newqueryData = data;
		// 	$("#queryString"+Maincnt).val(JSON.stringify(newqueryData));
		// 	for (const key in data) {
		// 		let i = key.split('_');
		// 		let val = data[key].split(":");
		// 		$("#config_option_"+Maincnt+"_" + i[1]).val(val[1]).trigger('change');
		// 		$("#config_checkbox_"+Maincnt+"_" + i[1]).prop('checked', true);
		// 	}
		// }
		if (pageConfiguration.hasOwnProperty('operation')) {
			let data = pageConfiguration.operation;
			data = JSON.stringify(data);
			$("#btnConfig").val(data);
		}
		// });
		// }

	} else if (type == "queryParam") {
		$("#queryParam_section").show();
		$("#queryParam_form")[0].reset();
		$("#queryParam_row").val(row);
		$("#queryParam_col").val(col);
		let hiddenRow = pageConfiguration.queryParamFields.find(h => (h.row === row && h.col === col));
		if (hiddenRow) {
			$("#queryParam_default_value").val(hiddenRow.element.default_value);
		}
	} else {
		let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
		if (rowObject) {
			let colObject = rowObject.cols.find(colItem => colItem.id === col);
			switch (type) {
				case "text":
					$("#short_text_form")[0].reset();
					$("#text_row").val(row);
					$("#text_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#shot_text_name").val(colObject.element.label);
							$("#shot_text_place_value").val(colObject.element.placeholder);
							if (colObject.element.checkbox === 1) {
								$("#shot_text_checkbox").prop('checked', true);
							} else {
								$("#shot_text_checkbox").prop('checked', false);
							}
							if (colObject.element.hasOwnProperty('defaultHide')) {
								if (colObject.element.defaultHide === 1) {
									$("#shot_text_hide").prop('checked', true);
								} else {
									$("#shot_text_hide").prop('checked', false);
								}
							}
							if (colObject.element.hasOwnProperty('custom_validation')) {
								$("#text_custom_validation").val(colObject.element.custom_validation);
							}
						}
					}
					$("#shot_text_section").show();
					break;
				case "number":

					$("#number_form")[0].reset();
					$("#number_row").val(row);
					$("#number_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#number_name").val(colObject.element.label);
							$("#number_min_value").val(colObject.element.min_value);
							$("#number_max_value").val(colObject.element.max_value);
							if(colObject.element.hasOwnProperty('min_length'))
							{
								$("#number_min_length").val(colObject.element.min_length);
							}
							if(colObject.element.hasOwnProperty('max_length'))
							{
								$("#number_max_length").val(colObject.element.max_length);
							}
							if (colObject.element.checkbox === 1) {
								$("#number_checkbox").prop('checked', true);
							} else {
								$("#number_checkbox").prop('checked', false);
							}
						}
					}
					$("#number_section").show();
					break;
				case "textarea":
					$("#textarea_section").show();
					$("#textarea_form")[0].reset();
					$("#textarea_row").val(row);
					$("#textarea_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#shot_textarea_name").val(colObject.element.label);
							$("#textarea_placeholder").val(colObject.element.placeholder);
							if (colObject.element.checkbox === 1) {
								$("#textarea_checkbox").prop('checked', true);
							} else {
								$("#textarea_checkbox").prop('checked', false);
							}
						}
					}
					break;
				case "date":
					$("#date_section").show();
					$("#date_form")[0].reset();
					$("#date_row").val(row);
					$("#date_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#date_name").val(colObject.element.label);
							$("#date_default_value").val(colObject.element.default_value_option);
							$("#specific_date_value").val(colObject.element.default_value);
							$("#date_min_value").val(colObject.element.min_value);
							$("#date_max_value").val(colObject.element.max_value);
							if (colObject.element.checkbox === 1) {
								$("#date_checkbox").prop('checked', true);
							} else {
								$("#date_checkbox").prop('checked', false);
							}
							getdateOption();
						}
					}

					break;
				case "file":
					$("#file_section").show();
					$("#file_form")[0].reset();
					$("#file_row").val(row);
					$("#file_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#file_name").val(colObject.element.label);
							if (colObject.element.checkbox === 1) {
								$("#file_checkbox").prop('checked', true);
							} else {
								$("#file_checkbox").prop('checked', false);
							}
						}
					}
					break;
				case "singleSelection":
					$("#").show();
					$("#single_form")[0].reset();
					$("#single_row").val(row);
					$("#single_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#single_name").val(colObject.element.label);
							$("#single_option_array").val(JSON.stringify(colObject.element.option_array));
							if(colObject.element.hasOwnProperty('hideshow_array'))
							{
								$("#single_hideshow_array").val(JSON.stringify(colObject.element.hideshow_array));
							}
							$("#single_default_value").val(colObject.element.default_value);
							if (colObject.element.checkbox === 1) {
								$("#single_checkbox").prop('checked', true);
							} else {
								$("#single_checkbox").prop('checked', false);
							}
							if (colObject.element.hasOwnProperty('defaultHide')) {
								if (colObject.element.defaultHide === 1) {
									$("#single_defaultHide").prop('checked', true);
								} else {
									$("#single_defaultHide").prop('checked', false);
								}
							}
						}
					}

					break;
				case "multipleSelection":
					$("#multiple_selection_section").show();
					$("#multiple_form")[0].reset();
					$("#multiple_row").val(row);
					$("#multiple_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#multiple_name").val(colObject.element.label);
							$("#multiple_option_array").val(JSON.stringify(colObject.element.option_array));
							$("#multiple_default_value").val(colObject.element.default_value);
							if (colObject.element.checkbox === 1) {
								$("#multiple_checkbox").prop('checked', true);
							} else {
								$("#multiple_checkbox").prop('checked', false);
							}
							if (colObject.element.hasOwnProperty('defaultHide')) {
								if (colObject.element.defaultHide === 1) {
									$("#multiple_defaultHide").prop('checked', true);
								} else {
									$("#multiple_defaultHide").prop('checked', false);
								}
							}

						}
					}


					break;
				case "radio":
					$("#radio_section").show();
					$("#radio_form")[0].reset();
					$("#radio_row").val(row);
					$("#radio_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#radio_name").val(colObject.element.label);
							$("#radio_option_array").val(JSON.stringify(colObject.element.option_array));
							$("#radio_default_value").val(colObject.element.default_value);
							if (colObject.element.checkbox === 1) {
								$("#radio_checkbox").prop('checked', true);
							} else {
								$("#radio_checkbox").prop('checked', false);
							}
						}
					}
					break;
				case "checkbox":
					$("#checkbox_section").show();
					$("#checkbox_form")[0].reset();
					$("#checkbox_row").val(row);
					$("#checkbox_col").val(col);

					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#checkbox_name").val(colObject.element.label);
							$("#checkbox_option_array").val(JSON.stringify(colObject.element.option_array));
							$("#checkbox_default_value").val(colObject.element.default_value);
							if (colObject.element.checkbox === 1) {
								$("#checkbox_check").prop('checked', true);
							} else {
								$("#checkbox_check").prop('checked', false);
							}
						}
					}

					break;
				case "excel_report":
					$("#excel_report_section").show();
					$("#excel_report_row").val(row);
					$("#excel_report_col").val(col);
					$("#excel_report_form")[0].reset();
					if (colObject) {
						if (colObject.element.hasOwnProperty('report_name')) {
							$("#excel_report_name").val(colObject.element.report_name);
							$("#excel_sub_report_name").val(colObject.element.sub_report_name);
							$("#excel_query_data").val(colObject.element.query);
							if (colObject.element.hasOwnProperty('data')) {
								var eleData = colObject.element.data;
								var j = 1;
								for (var i = 0; i < eleData.length; i++) {
									console.log(eleData[i].param);
									$("#excel_param" + j).val(eleData[i].param);
									$("#excel_datatype" + j).val(eleData[i].datatype);
									$("#excel_lable" + j).val(eleData[i].label);
									$("#excel_option" + j).val(eleData[i].option);
									j++;
								}
							}
							if (colObject.element.hasOwnProperty('report_type')) {
								$("#excel_report_type").prop("checked", false);
								$("#pdf_report_type").prop("checked", false);
								$("#csv_report_type").prop("checked", false);
								if (colObject.element.report_type.excel_report_type === 1) {
									$("#excel_report_type").prop("checked", true);
								}
								if (colObject.element.report_type.pdf_report_type === 1) {
									$("#pdf_report_type").prop("checked", true);
								}
								if (colObject.element.report_type.csv_report_type === 1) {
									$("#csv_report_type").prop("checked", true);
								}
							}
						}
					}
					break;
				case "pdf_report":
					$("#pdf_report_section").show();
					$("#pdf_report_row").val(row);
					$("#pdf_report_col").val(col);
					break;
				case "csv_report":
					$("#csv_report_section").show();
					$("#csv_report_row").val(row);
					$("#csv_report_col").val(col);
					break;
				case "queryDropdown":
					$("#queryDropdown_section").show();
					$("#queryDropdown_form")[0].reset();
					$("#queryDropdown_row").val(row);
					$("#queryDropdown_col").val(col);

					if (colObject) {
						console.log(colObject);
						if (colObject.element.hasOwnProperty('label')) {
							$("#queryDropdown_label").val(colObject.element.label);
						}
						if(colObject.element.hasOwnProperty('checkbox'))
						{
							if (colObject.element.checkbox === 1) {
								$("#queryDropdown_checkbox").prop('checked', true);
							} else {
								$("#queryDropdown_checkbox").prop('checked', false);
							}
						}
						if(colObject.element.hasOwnProperty('multiple'))
						{
							if (colObject.element.multiple === 1) {
								$("#queryDropdown_multiple").prop('checked', true);
							} else {
								$("#queryDropdown_multiple").prop('checked', false);
							}
						}
						if (colObject.element.hasOwnProperty('defaultHide')) {
							if (colObject.element.defaultHide === 1) {
								$("#queryDropdown_defaultHide").prop('checked', true);
							} else {
								$("#queryDropdown_defaultHide").prop('checked', false);
							}
						}

						if (colObject.element.hasOwnProperty('method')) {
							if (colObject.element.method == 1) {
								changeTab('form_through_div', 'normal_query_div', 1)
								$("#query_table").select2('destroy');
								$("#query_table").val(colObject.element.queryDependentTable).select2();
								if(colObject.element.queryDependentTable!=null) {
									getQueryDropdownData().then(res => {
										$("#query_option_value").val(colObject.element.queryDependentoptionValue).trigger('change');
										$("#query_option_name").val(colObject.element.queryDependentoptionName).trigger('change');

										if (colObject.element.hasOwnProperty('queryDependentValueCheck')) {
											if (colObject.element.queryDependentOn != "" && colObject.element.queryDependentOn != "Select One") {
												$('#is_dependent_div').removeClass('d-none');
												$("#dependent_value_check").val(colObject.element.queryDependentValueCheck).trigger('change');
												$("#dependent_on").val(colObject.element.queryDependentOn).trigger('change');
											}
										}

										if (colObject.element.hasOwnProperty('queryisDependentOnOther')) {
											$("#is_dependent_row_div").html('');
											$("#is_dependent_count").val(0);
											let other_data = colObject.element.queryisDependentOnOther;
											for (let i = 0; i < other_data.length; i++) {
												getDependentDiv().then(res => {
													let val = i + 1;
													$("#dependent_value" + val).val(other_data[i].dValue).trigger('change');
													$("#dependent_column" + val).val(other_data[i].dColumn).trigger('change');
													$("#dependent_query" + val).val(other_data[i].dQuery).trigger('change');
												});
											}
										}

										if (colObject.element.hasOwnProperty('queryisimDependentOnOther')) {
											$("#is_im_dependent_div").html('');
											$("#is_im_dependent_count").val(0);
											let other_data = colObject.element.queryisimDependentOnOther;
											for (let i = 0; i < other_data.length; i++) {
												getImDependentDiv().then(res => {
													let val = i + 1;
													$("#dependent_on" + val).val(other_data[i].dValue).trigger('change');
													$("#dependent_value_check" + val).val(other_data[i].dColumn).trigger('change');
												});
											}
										}

										if (colObject.element.hasOwnProperty('queryDependentWhere')) {
											$("#query_where_div").html('');
											$("#where_row_count").val(0);
											let other_data = colObject.element.queryDependentWhere;
											for (let i = 0; i < other_data.length; i++) {
												getDependentWhereDiv().then(res => {
													let val = i + 1;
													$("#query_where_column" + val).val(other_data[i][0]).trigger('change');
													$("#query_where_value" + val).val(other_data[i][1]).trigger('change');
													$("#query_where_value2_" + val).val(other_data[i][2]).trigger('change');
												});
											}
										}

									});
								}
							} else {
								changeTab('normal_query_div', 'form_through_div', 2)
								$("#normal_query").val(colObject.element.normal_query);
							}
						}
					}

					break;
				case "templateForm":
					$("#templateForm_section").show();
					$("#templateForm_form")[0].reset();
					$("#templateForm_row").val(row);
					$("#templateForm_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#templateForm_name").val(colObject.element.label);
							$("#templateForm_default_value").val(colObject.element.default_value).trigger('change').select2();
							$("#onload").val(colObject.element.default_value_option).trigger('change');
						}
					}

					break;

				case "Handsontable":
					$("#handson_section").show();
					$("#handson_form")[0].reset();
					$("#handson_row").val(row);
					$("#handson_col").val(col);
					handsonOp().then(res => {
						if (colObject) {
							if (colObject.element.hasOwnProperty('handson_id')) {
								$("#handsontable").val(colObject.element.handson_id).trigger('change');
							}
						}
					})
					break;
				case "inputSearch":
					$("#inputSearch_section").show();
					$("#inputSearch_form")[0].reset();
					$("#inputSearch_row").val(row);
					$("#inputSearch_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#inputSearch_name").val(colObject.element.label);
							$("#inputSearch_query").val(colObject.element.inputSearch_query);
						}
					}
					break;
				case "modalButton":
					$("#modalButton_section").show();
					$("#modalButton_form")[0].reset();
					$("#modalButton_row").val(row);
					$("#modalButton_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#modalButton_name").val(colObject.element.label);
							$("#modalButtonId").val(colObject.element.modalButtonId);
							$("#modalButtonId").select2();
						}
					}
					break;
				case "dropdownButton":
					$("#dropdownButton_section").show();
					$("#dropdownButton_form")[0].reset();
					$("#dropdownButton_row").val(row);
					$("#dropdownButton_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#dropdownButton_name").val(colObject.element.label);
							$("#dropdownButton_dropdown").val(JSON.stringify(colObject.element.dropdownButton_dropdown));
						}
					}
					break;
				case "email":
					$("#email_text_form")[0].reset();
					$("#email_row").val(row);
					$("#email_col").val(col);
					if (colObject) {
						if (colObject.element.hasOwnProperty('label')) {
							$("#email_text_name").val(colObject.element.label);
							$("#email_text_place_value").val(colObject.element.placeholder);
							if (colObject.element.checkbox === 1) {
								$("#email_text_checkbox").prop('checked', true);
							} else {
								$("#email_text_checkbox").prop('checked', false);
							}
						}
					}
					$("#email_section").show();
					break;

					case "addmore":
						$("#addmore_text_form")[0].reset();
						$("#newRowadd").empty();
						console.log(colObject);
						$("#addmore_row").val(row);
						$("#addmore_col").val(col);
						let addmorecount =parseInt($("#addmorecount").val());
						if(colObject)
						{
							if (colObject.element.hasOwnProperty('addmore_option_array')) {
								// getAddmoreData(row,col,type);
								var eleData = colObject.element.addmore_option_array;
									console.log(eleData);

									$("#label").val(colObject.element.label);
									$("#addmore_query_table").val(colObject.element.query_table_name).trigger('change');
									getQueryTableData().then(res =>
										{
											tablecolumn=res;
									eleData.map((obj,i) => {

										  if(i==0){

											$("#addmore_text_name"+i).val(obj.text_name);
											$("#addmore_selecttype"+i).val(obj.selecttype);
											$("#addmore_query"+i).val(obj.more_query);
											$("#attribute_value"+i).val(obj.value_attributes);
											$("#default_value"+i).val(obj.value_defaults);
											$("#query_addmore_column"+i).val(obj.column_name);
											// $(".queryDropdownSelectColumn"+i).html('');
			                                // $(".queryDropdownSelectColumn"+i).html(tablecolumn).select2();


										  }else{

										arr.push(i);
											var html = ``;
												html += `<div class="row" id="addnewRow${i}">`;
												html += `<div class="col-2">`;
												html += `<div class="form-group">`;
												html +=  `<lable>Column Name</lable>`;
												html += `<input type="text" name="addmore_text_name${i}" id="addmore_text_name${i}" value="${obj.text_name}" placeholder="Enter Column Name" class="form-control">`;
												html += `</div>`;
												html += `</div>`;
												html +=  `<div class="col-2">`;
												html +=  `<div class="form-group">`;
												html +=  `<lable>Select Type</lable>`;
												html +=  `<select class="form-control" id="addmore_selecttype${i}" name="addmore_selecttype${i}">`;
												html +=  `<option value="numeric">Numeric</option>`;
												html +=  `<option value="dropdown">Dropdown</option>`;
												html +=  `<option value="text">Text</option>`;
												html +=  `<option value="date">Date</option>`;
												html +=  `<option value="hidden">Hidden</option>`;
												html +=  `</select>`;
												html += `</div>`;
												html += `</div>`;
												html +=  `<div class="col-1">`;
												html +=  `<div class="form-group">`;
												html +=  `<lable>Is Query</lable>`;
												html += `<select class="form-control" id="addmore_query${i}" name="addmore_query${i}">`;
												html += `<option value="0">No</option>`;
												html += `<option value="1">Yes</option>`;
												html += `</select>	`;
												html += `</div>`;
												html += `</div>`;
												html +=  `<div class="col-3">`;
												html +=  `<div class="form-group">`;
												html +=  `<lable>Attribute Value</lable>`;
												html +=  `<input type="text" name="attribute_value${i}" id="attribute_value${i}" value="${obj.value_attributes}"placeholder="Enter Attribute Value" class="form-control">`;
												html += `</div>`;
												html += `</div>`;
												html +=  `<div class="col-2">`;
												html +=  `<div class="form-group">`;
												html +=  `<lable>Default Value</lable>`;
												html +=  `<input type="text" name="default_value${i}" id="default_value${i}" value="${obj.value_defaults}" placeholder="Default Value" class="form-control">`;
												html += `</div>`;
												html += `</div>`;
												html += `<div class="col-md-2">`;
												html += `<label>Select Column Name </label>`;
												html += `<select name="query_addmore_column${i}" id="query_addmore_column${i}" class="form-control queryDropdownSelectColumn${i} select2" >${tablecolumn}</select>`;
												html += `</div>`;
												html += `<div class="col-2">`;
												html += `<button type="button" class="btn btn-sm btn-danger remove" onclick="removeAddMoreRow(${i})"><i class="fa fa-minus"></i></button>`;
												html += `</div>`;
												html += `</div>`;
												$('#newRowadd').append(html);
												$("#addmore_selecttype"+i).val(obj.selecttype);
											    $("#addmore_query"+i).val(obj.more_query);


												// $(".queryDropdownSelectColumn"+i).html('');
			                                    // $(".queryDropdownSelectColumn"+i).html(tablecolumn).select2();
												$("#query_addmore_column"+i).val(obj.column_name).trigger('change');
												$("#addmorecount").val(i);


										    }

								        });
								});
							}


						}

						$("#addmore_section").show();
						break;

			}
		}
	}
}

function saveData(form_id) {
	$("#ConfigurationModal").modal('hide');
	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});

	let checkbox = $('#' + form_id + ' input[name="checkbox"]:checked').length
	let defaultHide = $('#' + form_id + ' input[name="defaultHide"]:checked').length
	let id = data.id;
	let row = parseInt(data.row);
	let col = parseInt(data.col);
	let hideshowArray=null;
	if(data.hideshow_array!="" && data.hideshow_array!="null" && data.hideshow_array!=null && !data.hideshow_array.length === 0)
	{
		if(isArray(data.hideshow_array.length))
		{
			hideshowArray=JSON.parse(data.hideshow_array);
		}
	}

	id = parseInt(id);
	switch (id) {
		case 1:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, data.placeholder,null,null,null,null,null,null,null,null,null,data.custom_validation)
			break;
		case 2:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, data.placeholder)
			break;
		case 3:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, null, data.min_value, data.max_value,null,null,null,null,data.min_length,data.max_length)
			break;
		case 4:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, null, data.min_value, data.max_value, data.default_value, data.default_value_option)
			break;
		case 5:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, null)
			break;
		case 6:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, null, null, null, data.default_value, null, JSON.parse(data.option_array),null,null,null,hideshowArray)
			break;
		case 7:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, null, null, null, data.default_value, null, JSON.parse(data.option_array))
			break;
		case 8:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, null, null, null, data.default_value, null, JSON.parse(data.option_array))
			break;
		case 9:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, null, null, null, data.default_value, null, JSON.parse(data.option_array))
			break;
		case 10:
			let default_value = $('#' + form_id + ' input[name="default_value_option"]:checked').val();

			updateConfigInColumn(row, col, 1, null, null,defaultHide, null, null, null, data.default_value, default_value, null);
			break;
		case 11:
			getLabels();
			break;
		case 17:
			updateConfigInColumn(row, col, 2, null, null,defaultHide, null, null, null, data.default_value, null, null);
			break;
		case 18:
			updateConfigInColumn(row, col, 3, data.label, null,defaultHide, null, null, null, data.default_value, data.default_value_option, null);
			break;
		case 23:
			updateConfigInColumn(row, col, 0, data.label, checkbox,defaultHide, data.placeholder)
			break;
	}
}

function saveReportData(form_id) {
	$("#ConfigurationModal").modal('hide');
	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});

	let value_array = [];
	for (let i = 1; i < 6; i++) {
		let param = $("#excel_param" + i).val();
		let datatype = $("#excel_datatype" + i).val();
		let label = $("#excel_lable" + i).val();
		let option = $("#excel_option" + i).val();

		let report_data = {param: param, datatype: datatype, label: label, option: option};
		value_array.push(report_data);
	}
	let report_type = {excel_report_type: 0, pdf_report_type: 0, csv_report_type: 0};
	if ($('#excel_report_type').is(':checked')) {
		report_type.excel_report_type = 1;
	}
	if ($('#pdf_report_type').is(':checked')) {
		report_type.pdf_report_type = 1;
	}
	if ($('#csv_report_type').is(':checked')) {
		report_type.csv_report_type = 1;
	}
	let row = parseInt(data.row);
	let col = parseInt(data.col);
	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === col);
		if (colObject) {
			colObject.element.report_name = data.report_name;
			colObject.element.sub_report_name = data.sub_report_name;
			colObject.element.report_type = report_type;
			colObject.element.query = data.query_data;
			colObject.element.data = value_array;
		}
	}
}

function saveInputSearchData(form_id) {
	$("#ConfigurationModal").modal('hide');
	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});
	let row = parseInt(data.row);
	let col = parseInt(data.col);
	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === col);
		if (colObject) {
			colObject.element.label = data.label;
			colObject.element.inputSearch_query = data.inputSearch_query;
		}
	}
}

function saveModalButtonData(form_id) {
	$("#ConfigurationModal").modal('hide');
	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});
	let row = parseInt(data.row);
	let col = parseInt(data.col);
	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === col);
		if (colObject) {
			colObject.element.label = data.label;
			colObject.element.modalButtonId = data.modalButtonId;
		}
	}
}

function saveDropdownWithButtonData(form_id) {
	$("#ConfigurationModal").modal('hide');
	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});
	let dropdownData1=JSON.parse(data.dropdownButton_dropdown);
	let dropdownData=dropdownData1[0];
	let row = parseInt(data.row);
	let col = parseInt(data.col);
	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === col);
		if (colObject) {
			colObject.element.label = data.label;
			colObject.element.method=dropdownData.method;
			colObject.element.queryDependentTable = dropdownData.queryDependentTable;
			colObject.element.dropdowntype = dropdownData.dropdowntype;
			colObject.element.queryDependentoptionValue = dropdownData.queryDependentoptionValue;
			colObject.element.queryDependentoptionName = dropdownData.queryDependentoptionName;
			colObject.element.queryDependentValueCheck = dropdownData.queryDependentValueCheck;
			colObject.element.queryDependentOn = dropdownData.queryDependentOn;
			colObject.element.queryisDependentOnOther = dropdownData.queryisDependentOnOther;
			colObject.element.queryisimDependentOnOther = dropdownData.queryisimDependentOnOther;
			colObject.element.queryDependentWhere = dropdownData.queryDependentWhere;
			colObject.element.dropdownButton_dropdown=dropdownData1;
		}
	}
}


function updateConfigInColumn(row, column, elType, label, checkbox = null,defaultHide=null, placeholder = null, min_value = null, max_value = null, default_value = null, default_value_option = null, option_array = null, is_html = null,min_length=null,max_length=null,hideshow_array=null,custom_validation=null) {

// 17 parameter - need to do a object format for data access
	if (elType === 1) {
		let rowObject = pageConfiguration.hiddenFields.find(rowItem => rowItem.col === column);
		if (rowObject) {
			rowObject.element.default_value = default_value;
			rowObject.element.default_value_option = default_value_option;
		}
	} else if (elType === 2) {
		let rowObject = pageConfiguration.queryParamFields.find(rowItem => rowItem.col === column);
		if (rowObject) {
			rowObject.element.default_value = default_value;
			if (!allQueryParamFields.self.includes(default_value)) {
				allQueryParamFields.self.push({'rowId': row, 'colId': column, 'value': default_value});
			}
		}
	} else {
		let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
		if (rowObject) {
			let colObject = rowObject.cols.find(colItem => colItem.id === column);
			if (colObject) {
				colObject.element.label = label;
				colObject.element.checkbox = checkbox;
				colObject.element.placeholder = placeholder;
				colObject.element.min_value = min_value;
				colObject.element.max_value = max_value;
				colObject.element.default_value = default_value;
				colObject.element.default_value_option = default_value_option;
				colObject.element.option_array = option_array;
				colObject.element.min_length = min_length;
				colObject.element.max_length = max_length;
				colObject.element.defaultHide = defaultHide;
				colObject.element.hideshow_array = hideshow_array;
				colObject.element.custom_validation = custom_validation;

				if (elType === 3) {
					getTemplateQueryParam(row, column, default_value);
				}
			}
		}
	}
	console.log(pageConfiguration);
}

function getdateOption() {
	let val = $("#date_default_value").val();

	if (val == 'specific_date') {
		$('#specific_date_value').show();
		$('#min_div').hide();
		$('#max_div').hide();
	} else if (val == 'today_date') {
		$('#specific_date_value').hide();
		$('#min_div').hide();
		$('#max_div').hide();
	} else {
		$('#specific_date_value').hide();
		$('#min_div').show();
		$('#max_div').show();
	}
}

function saveQueryDropdown(form_id) {
	$("#ConfigurationModal").modal('hide');

	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});

	let checkbox = $('#' + form_id + ' input[name="checkbox"]:checked').length;
	let defaultHide = $('#' + form_id + ' input[name="defaultHide"]:checked').length;
	let multiple = $('#' + form_id + ' input[name="queryDropdown_multiple"]:checked').length;
	// console.log(checkbox);
	if (data.method == 1) {
		let is_dependent_count = $("#is_dependent_count").val();
		is_dependent_count = parseInt(is_dependent_count);
		let dependent_array = [];
		for (let i = 1; i <= is_dependent_count; i++) {
			let value = $("#dependent_value" + i).val();
			let column = $("#dependent_column" + i).val();
			let query = $("#dependent_query" + i).val();
			let report_data = {dValue: value, dQuery: query, dColumn: column};
			dependent_array.push(report_data);
		}

		let is_im_dependent_count = $("#is_im_dependent_count").val();
		is_im_dependent_count = parseInt(is_im_dependent_count);
		let im_dependent_array = [];
		for (let i = 1; i <= is_im_dependent_count; i++) {
			let column = $("#dependent_value_check" + i).val();
			let value = $("#dependent_on" + i).val();
			let report_data = {dValue: value, dColumn: column};
			im_dependent_array.push(report_data);
		}

		let query_where_count = $("#where_row_count").val();
		query_where_count = parseInt(query_where_count);
		let where_array = [];
		for (let i = 1; i <= query_where_count; i++) {
			let where_column = $("#query_where_column" + i).val();
			let where_value = $("#query_where_value" + i).val();
			let where_value2 = $("#query_where_value2_" + i).val();

			let report_data = [where_column, where_value, where_value2];
			where_array.push(report_data);
		}

		let row = parseInt(data.row);
		let col = parseInt(data.col);
		let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
		if (rowObject) {
			let colObject = rowObject.cols.find(colItem => colItem.id === col);
			if (colObject) {
				colObject.element.label = data.label;
				colObject.element.checkbox = checkbox;
				colObject.element.method = data.method;
				colObject.element.queryDependentTable = data.query_table;
				colObject.element.queryDependentoptionValue = data.query_option_value;
				colObject.element.queryDependentoptionName = data.query_option_name;
				colObject.element.queryDependentValueCheck = data.dependent_value_check;
				colObject.element.queryDependentOn = data.dependent_on;
				colObject.element.queryisDependentOnOther = dependent_array;
				colObject.element.queryisimDependentOnOther = im_dependent_array;
				colObject.element.queryDependentWhere = where_array;
				colObject.element.defaultHide =defaultHide;
				colObject.element.multiple = multiple;
			}
		}
	} else {
		let row = parseInt(data.row);
		let col = parseInt(data.col);
		let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
		if (rowObject) {
			let colObject = rowObject.cols.find(colItem => colItem.id === col);
			if (colObject) {
				colObject.element.label = data.label;
				colObject.element.checkbox = checkbox;
				colObject.element.method = data.method;
				colObject.element.normal_query = data.normal_query;
			}
		}
	}
}

function getTemplateDataById(temp_id) {
	let formdata = new FormData();
	formdata.set('id', temp_id);
	app.request("ShowForm", formdata).then(res => {
		if (res.status === 200) {
			// app.successToast(res.body);
			let data = res.data;
			let template = data.template_structure;

			$("#template_name").val(data.template_name);
			showComponents(data.configuration);
			if (data.configuration == 2) {
				$("#page_configuration").prop("checked", true);
			} else if (data.configuration == 3) {
				$("#div_configuration").prop("checked", true);
			} else {
				$("#template_configuration").prop("checked", true);
			}
			if (data.query_param != "" && data.query_param != null) {
				allQueryParamFields = JSON.parse(data.query_param);
			}
			console.log(template);
	let tableS = JSON.parse(template);
			loadConfiguration(tableS);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function loadConfiguration(tableS) {
	console.log(tableS);
	pageConfiguration.rows = tableS.rows;
	pageConfiguration.operation = tableS.operation;
	// pageConfiguration.table = tableS.table;
	// pageConfiguration.method = tableS.method;
	pageConfiguration.hiddenFields = tableS.hiddenFields;
	if (tableS.queryParamFields == undefined) {
		pageConfiguration.queryParamFields = [];
	} else {
		pageConfiguration.queryParamFields = tableS.queryParamFields;
	}
	if (pageConfiguration.rows.length > 0) {
		pageConfiguration.rows.sort((a, b) => a.seqNo - b.seqNo).map((e) => {
			let cols_length = e.cols.length;
			if (cols_length === 1) {
				AddrowClone(12, e.id,e);
			} else if (cols_length === 2) {
				AddrowClone(6, e.id,e);
			} else if (cols_length === 3) {
				AddrowClone(4, e.id,e);
			} else if (cols_length === 4) {
				AddrowClone(3, e.id,e);
			}
			e.cols.map(col => {
				if (col.element != null) {
					setSelectedColumn(col.id);
					if (col.element.hasOwnProperty('type')) {
						getColumnDesign(col.element.type);
					}
				} else {
					setSelectedColumn(col.id);
					getColumnDesign('label', col.html);
				}
			});
		});
	}

}

function getColumnDesign(type, html = null) {
	switch (type) {
		case "text":
			setControlToDropZone('control-1');
			break;
		case "textarea":
			setControlToDropZone('control-2');
			break;
		case "number":
			setControlToDropZone('control-3');
			break;
		case "date":
			setControlToDropZone('control-4');
			break;
		case "file":
			setControlToDropZone('control-5');
			break;
		case "singleSelection":
			setControlToDropZone('control-6');
			break;
		case "multipleSelection":
			setControlToDropZone('control-7');
			break;
		case "radio":
			setControlToDropZone('control-8');
			break;
		case "checkbox":
			setControlToDropZone('control-9');
			break;
		case "queryDropdown":
			setControlToDropZone('control-12');
			break;
		case "excel_report":
			setControlToDropZone('control-13');
			break;
		case "pdf_report":
			setControlToDropZone('control-14');
			break;
		case "csv_report":
			setControlToDropZone('control-15');
			break;
		case "button":
			setControlToDropZone('button-1', 1);
			break;
		case "hidden":
			setControlToDropZone('hidden-1', 1);
			break;
		case "lable":
			break;
		case "datatable":
			setControlToDropZone('control-16');
			break;
		case "queryParam":
			setControlToDropZone('control-17', 1);
			break;
		case "templateForm":
			setControlToDropZone('control-18');
			break;
		case "Handsontable":
			setControlToDropZone('control-19');
			break;
		case "inputSearch":
			setControlToDropZone('control-20');
			break;
		case "modalButton":
			setControlToDropZone('control-21');
			break;

		case "dropdownButton":
			setControlToDropZone('control-22');
			break;
		case "email":
			setControlToDropZone('control-23');
			break;

		case "label" :
			setControlToDropZone('lable-1', 1, html);
			break;
		case "addmore" :
			setControlToDropZone('control-24');
			break;
	}
}


function AddrowClone(value, rowId,e=null) {
	let rowObject = {
		id: rowId,
		seqNo: pageConfiguration.rows.length,
		cols: []
	};
	let iterationCount;
	switch (parseInt(value)) {
		case 12:
			iterationCount = 1;
			break;
		case 6:
			iterationCount = 2;
			break;
		case 4:
			iterationCount = 3;
			break;
		case 3:
			iterationCount = 4;
			break;
	}
	let cols = [];
	if(e !== null ){
		cols = iteration(iterationCount, parseInt(value), rowId, rowId,e,2);
	}else{
		cols = iteration(iterationCount, parseInt(value), rowId, rowId,2);
	}
	let colTemplate = cols.map(i => {
		rowObject.cols.push(i.column);
		return i.raw
	}).join("");
	let row = `<div id="rowGroup-${rowId}" class="rowGroup">
                    <span class="d-row" id="close-row-${rowId}" onclick="removeRow(${rowId})">x</span>
                    <div class="d-g-row row my-1" id="${rowId}" data-id="${rowId}">
                        ${colTemplate}
                    </div>
                </div>`;
	$("#pageContainer").append(row);
	if (pageConfiguration.rows.find(x => x.id == rowId)) {

	} else {
		pageConfiguration.rows.push(rowObject);
	}
}


//datatable configuration

function copyText(id) {
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($('#' + id).text()).select();
	document.execCommand("copy");
	$temp.remove();
}

function setDatatableConfigurations(row, col, type) {
	$("#DatatableConfigurationModal").modal('show');

	// loadDataTable();

	$("#datatable_row").val(row);
	$("#datatable_col").val(col);
	getAllTables().then(res => {
		let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
		if (rowObject) {
			let colObject = rowObject.cols.find(colItem => colItem.id === col);
			switch (type) {
				case "datatable":
					$("#ConfigurationModal").modal('hide');
					$("#DatatableConfigurationModal").modal('show');
					$("#datatableForm")[0].reset();
					$("#datatable_row").val(row);
					$("#datatable_col").val(col);
					if (colObject) {
						console.log(colObject);
						if (colObject.element.hasOwnProperty('queryTable')) {
							$("#queryTable").val(colObject.element.queryTable).trigger('change').select2();

							$("#rawQueryTableSelectColumn").val(JSON.stringify(colObject.element.rawQueryTableSelectColumn));
							$("#rawQueryTableSearchColumn").val(JSON.stringify(colObject.element.rawQueryTableSearchColumn));
							$("#rawQueryTableOrderColumn").val(JSON.stringify(colObject.element.rawQueryTableOrderColumn));
							$("#rawQueryTableFileColumn").val(JSON.stringify(colObject.element.rawQueryTableFileColumn));
							$("#queryTableWhereCondition").val(JSON.stringify(colObject.element.queryTableWhereCondition));
							$("#queryTableFilterCondition").val(JSON.stringify(colObject.element.queryTableFilterCondition));
							$("#queryTableActionCondition").val(JSON.stringify(colObject.element.queryTableActionCondition));
							$("#rawQueryTableGroupColumn").val(JSON.stringify(colObject.element.rawQueryTableGroupColumn));
							$("#actionButtonName").val(colObject.element.actionButtonName);
						}
					}
					break;
			}
		}
	});
}

function getAllTables() {
	return new Promise(function (resolve, reject) {
		app.request("getAllTables", null).then(response => {
			$("#queryTable").empty();
			$("#queryTable").append(response.option);
			$("#queryTable").select2();
			resolve(response.options);
		}).catch(error => {
			console.log(error);
			app.errorToast("Something went wrong")
		});
	});
}

function saveDatatableForm(form_id) {
	$("#DatatableConfigurationModal").modal('hide');
	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});


	let row = parseInt(data.row);
	let col = parseInt(data.col);
	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === col);
		if (colObject) {
			if (data.queryTableActionCondition != "") {
				data.queryTableActionCondition = JSON.parse(data.queryTableActionCondition);
			}
			if (data.queryTableFilterCondition != "") {
				data.queryTableFilterCondition = JSON.parse(data.queryTableFilterCondition);
			}
			if (data.queryTableWhereCondition != "") {
				data.queryTableWhereCondition = JSON.parse(data.queryTableWhereCondition);
			}
			if (data.rawQueryTableFileColumn != "") {
				data.rawQueryTableFileColumn = JSON.parse(data.rawQueryTableFileColumn);
			}
			if (data.rawQueryTableOrderColumn != "") {
				data.rawQueryTableOrderColumn = JSON.parse(data.rawQueryTableOrderColumn);
			}
			if (data.rawQueryTableSearchColumn != "") {
				data.rawQueryTableSearchColumn = JSON.parse(data.rawQueryTableSearchColumn);
			}
			if (data.rawQueryTableSelectColumn != "") {
				data.rawQueryTableSelectColumn = JSON.parse(data.rawQueryTableSelectColumn);
			}
			if (data.rawQueryTableGroupColumn != "") {
				data.rawQueryTableGroupColumn = JSON.parse(data.rawQueryTableGroupColumn);
			}
			colObject.element.queryTable = data.queryTable;
			colObject.element.queryTableActionCondition = data.queryTableActionCondition;
			colObject.element.queryTableFilterCondition = data.queryTableFilterCondition;
			colObject.element.queryTableWhereCondition = data.queryTableWhereCondition;
			colObject.element.rawQueryTableFileColumn = data.rawQueryTableFileColumn;
			colObject.element.rawQueryTableOrderColumn = data.rawQueryTableOrderColumn;
			colObject.element.rawQueryTableSearchColumn = data.rawQueryTableSearchColumn;
			colObject.element.rawQueryTableSelectColumn = data.rawQueryTableSelectColumn;
			colObject.element.rawQueryTableGroupColumn = data.rawQueryTableGroupColumn;
			colObject.element.actionButtonName = data.actionButtonName;
		}
	}
}

function getAllColumns(value) {

	let formData = new FormData();
	formData.set("TableName", value);
	app.request("getAllColumns", formData).then(response => {

		$("#queryTableSelectColumn").empty();
		$("#queryTableSelectColumn").append(response.option);
		$("#queryTableSelectColumn").select2();

	}).catch(error => {
		console.log(error);
		app.errorToast("Something went wrong")
	})
}


//supriya code

function getAllTemplateOptions() {
	app.request("getAllTemplateListOptions", null).then(response => {
		$("#templateForm_default_value").empty();
		$("#templateForm_default_value").append(response.body);
		$("#templateForm_default_value").select2();

		$("#btnActionId").empty();
		$("#btnActionId").append(response.body);
		$("#btnActionId").select2();

		$("#modalButtonId").empty();
		$("#modalButtonId").append(response.body);
		$("#modalButtonId").select2();
	}).catch(error => {
		console.log(error);
		app.errorToast("Something went wrong")
	})
}

function getTemplateQueryParam(row, column, id) {
	let formData = new FormData();
	formData.set("templateId", id);
	app.request("getTemplateQueryParam", formData).then(response => {
		if (response.status === 200) {
			let tdata = response.body;
			if (tdata.query_param != "" && tdata.query_param != null) {
				if (allQueryParamFields.hasOwnProperty(id)) {
					let tempParam = JSON.parse(tdata.query_param);
					if (allQueryParamFields[id].rowId == row && allQueryParamFields[id].colId == column) {
						allQueryParamFields[id].value = tempParam.self;
					}
				} else {
					let tempParam = JSON.parse(tdata.query_param);
					let tempName = tdata.id;
					let tempObj = {};
					tempObj[id] = {'rowId': row, 'colId': column, 'value': tempParam.self};
					Object.assign(allQueryParamFields, tempObj);
				}
			}
		} else {
			console.log(response.body);
		}
	}).catch(error => {
		console.log(error);
		app.errorToast("Something went wrong")
	})
}

function handsonOp() {

	return new Promise(function (resolve, reject) {
		app.request("getHandson", null).then(res => {
			if (res.status === 200) {
				$("#handsontable").html('');
				$("#handsontable").html(res.data);
				$("#handsontable").select2();
			} else {
				$("#handsontable").html('');
				$("#handsontable").html(res.data);
				$("#handsontable").select2();
			}
			resolve(res);
		}).catch(error => console.log(error));
	});
}


function saveHandson() {
	let row = $("#handson_row").val();
	let col = $("#handson_col").val();
	row = parseInt(row);
	col = parseInt(col);
	let handson_id = $("#handsontable").val();
	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === col);
		if (colObject) {
			colObject.element.handson_id = handson_id;
		}
	}
	$("#ConfigurationModal").modal('hide');
}

function showComponents(type) {
	if (type == 1) {
		$(".temp_conf").show();
		$(".page_conf").hide();
		$(".div_conf").hide();
	} else if (type == 3) {
		$(".temp_conf").hide();
		$(".page_conf").hide();
		$(".div_conf").show();
	} else {
		$(".temp_conf").show();
		$(".page_conf").show();
		$(".div_conf").show();
	}
}

function showButtonActionConfig() {
	if ($("#btnAction").is(":checked")) {
		$("#actionConfig").show();
	} else {
		$("#actionConfig").hide();
	}
}

function saveDataAddMore(form_id) {
	$("#ConfigurationModal").modal('hide');
	let addmorecount =parseInt($("#addmorecount").val());
	let addmore_option_array =[];
	let label = $("#label").val();
	let query_table_name = $("#addmore_query_table").val();
		for(let i=0; i<arr.length; i++)
		{

			let addmore_text_name = $("#addmore_text_name" + arr[i]).val();
			let addmore_selecttype = $("#addmore_selecttype" + arr[i]).val();
			let addmore_query = $("#addmore_query" + arr[i]).val();
			let attribute_value = $("#attribute_value" + arr[i]).val();
			let default_value = $("#default_value" + arr[i]).val();
			let query_addmore_column = $("#query_addmore_column" + arr[i]).val();
			let form_data = {};

			form_data.text_name=addmore_text_name;
			form_data.selecttype=addmore_selecttype;
			form_data.more_query=addmore_query;
			form_data.value_attributes=attribute_value;
			form_data.value_defaults=default_value;
			form_data.column_name=query_addmore_column;
			form_data.col_id = i;
			form_data.input = addmore_selecttype+''+i;
			addmore_option_array.push(form_data);
			console.log(addmore_option_array);
		}




	let data = {};
	$("#" + form_id).find('[name]').each(function (i, v) {
		var input = $(this), // resolves to current input element.
			name = input.attr('name'),
			value = input.val();
		data[name] = value;
	});

	let row = parseInt(data.row);
	let col = parseInt(data.col);
	let rowObject = pageConfiguration.rows.find(rowItem => rowItem.id === row);
	if (rowObject) {
		let colObject = rowObject.cols.find(colItem => colItem.id === col);
		if (colObject) {
			colObject.element.label=label;
			colObject.element.query_table_name=query_table_name;
			colObject.element.addmore_option_array = addmore_option_array;

			}
	}
}

	function removeAddMoreRow(id)
	{
		let countofrow=$("#addmorecount").val();
		$("#addnewRow" + id).remove();
         let data=countofrow--;


		 arr=arr.filter(function(value,index,ar){
			return value!==id;
		 });

		 console.log(arr);
		$("#addmorecount").val(data);



	}






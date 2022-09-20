$(document).ready(function () {
	$("#template_detail_div").sortable({});
	getTableList();
	getAllTablesFromDatabase();
	getAllHashKeysOnLoad();
});

function createUpdateSpreadsheet1() {
	$("#CreateHandsonModal").modal('show');
	$("#template_detail_div").html('');
	$("#template_name").val('');
	$("#template_count").val(1);
	$("#hashKey").val('').trigger('change');
	createAddTemplateRow();
}

function createAddTemplateRow(data = null) {
	var template_count = $("#template_count").val();


	template_count = (template_count * 1) + 1;
	$("#template_count").val(template_count);
	var rowClass = '';
	var closeBtn = '';

	var attribute_name = '';
	var option_data = '';
	var sequence = '';
	var formula = '';
	var value_type = '';
	var default_value = '';
	var customQuery='';
	var dependant_col='';
	var id = 0;
	var options = `<option value="numeric">Numeric</option>
					<option value="dropdown">Dropdown</option>
					<option value="text">Text</option>
					<option value="date">Date</option>
					<option value="hidden">Hidden</option>
`;
	var optionsValueType = `<option value="1">Manual</option>
					<option value="2">Calculated</option>`;
	var optionsisQuery = `<option value="0">No</option>
					<option value="1">Yes</option>`;
	var optionsisDependant = `<option value="0">No</option>
					<option value="1">Yes</option>`;
	var optionsisReadonly=`<option value="0">No</option>
					<option value="1">Yes</option>`;
	if (data != null) {
		id = data.id;
		attribute_name = data.column_name;
		option_data = data.option_data;
		sequence = data.sequence;
		default_value = data.default_value;
		options = `<option value="numeric" ${data.column_type == 'numeric' ? 'selected' : ''}>Numeric</option>
							<option value="dropdown"  ${data.column_type == 'dropdown' ? 'selected' : ''}>Dropdown</option>
							<option value="text"  ${data.column_type == 'text' ? 'selected' : ''}>Text</option>
							<option value="date"  ${data.column_type == 'date' ? 'selected' : ''}>Date</option>
							<option value="hidden"  ${data.column_type == 'hidden' ? 'selected' : ''}>Hidden</option>
`;
		optionsValueType = `<option value="1" ${data.value_type == '1' ? 'selected' : ''}>Manual</option>
							<option value="2"  ${data.value_type == '2' ? 'selected' : ''}>Calculated</option>`;
		optionsisQuery = `<option value="1" ${data.is_query == '1' ? 'selected' : ''}>Yes</option>
							<option value="0"  ${data.is_query == '0' ? 'selected' : ''}>No</option>`;
		optionsisDependant = `<option value="1" ${data.is_anyoneDep == '1' ? 'selected' : ''}>Yes</option>
							<option value="0"  ${data.is_anyoneDep == '0' ? 'selected' : ''}>No</option>`;
		optionsisReadonly=`<option value="1" ${data.is_readonly == '1' ? 'selected' : ''}>Yes</option>
							<option value="0"  ${data.is_readonly == '0' ? 'selected' : ''}>No</option>`;
		formula = data.formula;
		if(data.custom_query!=null)
		{
			customQuery=data.custom_query;
		}
		if(data.dependant_col!=null)
		{
			dependant_col=data.dependant_col;
		}

	}
	var rowClass1 = 'HdnSqc';
	if (template_count > 1) {
		rowClass = 'new_attr_row';

		closeBtn = `<button type="button" onclick="remove_div(this,${id});" class="btn btn-link mt-4 remove_row roundCornerBtn4 xs_btn"><i class="fa fa-times-circle closeButton"></i></button>`;
	}
	var template_detail = `<li class="row ${rowClass} handle" id="sort_${template_count}">
					<div class="col-md-2">
					<label>Column Name</label>
					<input type="hidden" name="hdn_update_id[]" value="${id}">
					<input type="hidden" class="${rowClass1}" name="sequenceNumber[]" value="${template_count}">
					<input type="text" name="attribute_name[]" class="form-control" placeholder="Column Name" value="${attribute_name}">
					</div>
					<div class="col-md-2 ">
					<label>Select type</label>
					<select name="attribute_type[]" class="form-control">
					<option value="">Select type</option>
					${options}
					</select>
					</div>
					<div class="col-md-1">
					<label>Is Query</label>
					<select class="form-control" name="is_query[]" id="is_query${template_count}"  >
					${optionsisQuery}</select>
					</div>
					<div class="col-md-2">
					<label>Attribute Value</label>
					<input type="text" name="attribute_query[]"   class="form-control" placeholder="Options/Query" value="${option_data}">
					</div>
					
					<div class="col-md-2">
					<label>Value Type</label>
					<select class="form-control" name="valueType[]" id="valueType${template_count}" onchange="ChangeEvent(this.value,${template_count})" >
					${optionsValueType}</select>
					</div>
					<div class="col-md-2" id="Divformula_${template_count}" style="display: none">
					<label>Formula</label><small style="font-size: 68% !important;">(Sequence Should start with 0)</small><br>
					<input type="text" class="form-control" id="formula_${template_count}" value="${formula}" name="formulaColumns${template_count}[]">
					</div>
					
					<div class="col-md-2">
					<label>Default Value</label>
					<input type="text" class="form-control" name="default_value[]" value="${default_value}" id="default_value${template_count}">
					</div>
					
					<div class="col-md-2">
					<label>Is Anyone Depend</label>
					<select name="is_anyoneDep[]" class="form-control" id="is_anyoneDep${template_count}" onchange="CheckDependancy(this.value,${template_count})">
					${optionsisDependant}
					</select>
					</div>
					
					<div class="col-md-4" id="DivCustomQuery_${template_count}" style="display: none">
					<label>Custom Query</label>
					<textarea type="text" class="form-control" name="custom_query[]" value="${default_value}" id="custom_query${template_count}">
					</textarea>
					</div>
					
					<div class="col-md-2" id="DivDepColumn_${template_count}" style="display: none">
					<label>Dependant Columns</label><small style="font-size: 68% !important;">(Sequence Should start with 0)</small>
					<input type="text" class="form-control" name="dependant_col[]" value="${default_value}" id="dependant_col${template_count}">
					</div>
					<div class="col-md-2">
					<label>Is ReadOnly</label>
					<select class="form-control" name="is_readonly[]" id="is_readonly${template_count}"  >
					${optionsisReadonly}</select>
					</div>
					
					<div class="col-md-1 ">
					${closeBtn}
					</div>
					</li>`;
	$("#template_detail_div").append(template_detail);
	ChangeEvent($('#valueType' + template_count).val(), template_count, formula);
	CheckDependancy($('#is_anyoneDep' + template_count).val(), template_count,`${customQuery}`,`${dependant_col}`);
}
function CheckDependancy(value, count,customQuery='',depColumns='')
{
	if (value == 1) {
		$("#custom_query" + count).val(customQuery);
		$("#dependant_col" + count).val(depColumns);
		$("#DivCustomQuery_" + count).show();
		$("#DivDepColumn_" + count).show();
	} else {
		$("#DivCustomQuery_" + count).hide();
		$("#DivDepColumn_" + count).hide();
	}
}
function ChangeEvent(value, count, formula = '') {
	console.log(value);
	if (value == 2) {
		/*var option =``;
		for(var i=count;i>1;i--){
			option +=`<option value='${i}'>Column${i}</option>`;
		}*/
		/*$("#formula_"+count).html(option);
		$("#formula_"+count).select2();
		if(formula != ''){
			var myArray = formula.split(",");
			$("#formula_"+count).val(myArray).trigger('change');
		}*/
		$("#formula_" + count).val(formula);
		$("#formula_" + count).show();
		$("#Divformula_" + count).show();
	} else {
		$("#formula_" + count).hide();
		$("#Divformula_" + count).hide();
	}

}

function remove_div(elm, id) {
	if (id == 0) {
		$(elm).closest('li.new_attr_row').remove();
		$("#template_count").val(($("#template_count").val() * 1) - 1);
	} else {

		removeRowfromDB(id);
		$(elm).closest('li.new_attr_row').remove();
		$("#template_count").val(($("#template_count").val() * 1) - 1);
	}

}

function removeRowfromDB(id) {
	var template_id = $("#template_id").val();
	let formData = new FormData();
	formData.append('template_id', template_id);
	formData.append('id', id);
	if (confirm('Are you sure you want to Delete this?')) {
		// Save it!
		requestToAjax("RemoveRowFromDBFunc", formData).then(res => {
			if (res.status == 200) {

				app.successToast(res.body);
			} else {
				app.errorToast(res.body);
			}
		}).catch((e) => {
			console.log(e);
		});
	}

}

function changeStatus(temp_id, status) {
	let formData = new FormData();
	formData.append('template_id', temp_id);
	formData.append('status', status);
	if (confirm('Are you sure you want to Delete this?')) {
		// Save it!
		requestToAjax("TE_deactiveTemplate", formData).then(res => {
			if (res.status == 200) {

				app.successToast(res.body);
				getTableList();
			} else {
				app.errorToast(res.body);
			}
		}).catch((e) => {
			console.log(e);
		});
	}
}

function CreateTemplate() {
	var form_data = document.getElementById('template');
	let formData = new FormData(form_data);
	// var formData=$("#template").serialize();
	// console.log($("input[name='attribute_name[]'").val());
	if ($("#template_name").val() === "") {

		if ($("#template_name").val() == "") {
			$("#template_error").html('Enter Template Name');
		}
	} else if ($("input[name='attribute_name[]'").val() === "") {
		app.errorToast('Add Atleast One Attribute');
	} else {
		$.ajax({
			url: base_url + 'addHandsonTemplate',
			type: "POST",
			dataType: "json",
			data: formData,
			contentType: false,
			processData: false,
			success: function (res) {
				console.log(res);
				if (res.status == 200) {
					app.successToast(res.body);
					document.getElementById("template").reset();
					$("#CreateHandsonModal").modal('hide');
					/*if(res.prefill==1)
					{
						$("#spreadsheetTemplateId").val(res.temp_id);
						$("#spreadsheetTemplateName").val(res.temp_name);
						$("#handonTableModal").modal('show');
					}*/
					getTableList();
					//getListsheet();
				} else {
					app.errorToast(res.body);
				}
			}, error: function (error) {
				app.errorToast("Something went wrong please try again");
			}
		});
	}
}

function getTableList() {
	let formData = new FormData();
	formData.set('company_id', 'all');
	requestToAjax("getTablesList", formData).then(res => {
		console.log("table lists response = " + res);
		$("#table_lists").DataTable({
			destroy: true,
			order: [],
			data: res.data,
			"pagingType": "simple_numbers",
			columns: [

				{data: 0},
				{data: 1},
				{
					data: 3,
					render: (d, t, r, m) => {
						var status = 'Active';
						if (d == 1) {
							status = 'Active';
						} else {
							status = 'Inactive';
						}
						return `<button type="button" onclick="changeStatus(${r[2]},${d})" class="btn btn-link">${status}</button>`;
					}
				},
				{
					data: 2,
					render: (d, t, r, m) => {
						return `<button type="button" onclick="editfun(${d},'${r[1]}',${r[4]})" class="btn btn-link"><i class="fa fa-pen"></i></button>`;
					}
				},
				{
					data: 3,
					render: (d, t, r, m) => {
						return `<button type="button" onclick="addConfiguration(${d},'${r[1]}',${r[4]})" class="btn btn-link"><i class="fa fa-table"></i></button>`;
					}
				},
				{
					data: 3,
					render: (d, t, r, m) => {
						return `<button type="button" onclick="addTransactions(${d},'${r[1]}',${r[4]})" class="btn btn-link"><i class="fa fa-tasks"></i></button>`;
					}
				},
				{
					data: 3,
					render: (d, t, r, m) => {
						return `<button type="button" onclick="prefillData(${d},'${r[1]}',${r[4]})" class="btn btn-link"><i class="fa fa-file-code"></i></button>`;
					}
				},
			],
			fnRowCallback: (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
				var status = 'Activ';
				if (aData[3] == 1) {
					status = 'Active';
				} else {
					status = 'Inactive';
				}
				$('td:eq(2)', nRow).html(`<button type="button" onclick="changeStatus(${aData[2]},${aData[3]})" class="btn btn-link">${status}</button>`);
				$('td:eq(3)', nRow).html(`<button type="button" onclick="editfun(${aData[2]},'${aData[1]}',${aData[4]})" class="btn btn-link"><i class="fa fa-pen"></i></button>`);
				$('td:eq(4)', nRow).html(`<button type="button" onclick="addConfiguration(${aData[2]},'${aData[1]}',${aData[4]})" class="btn btn-link"><i class="fa fa-table"></i></button>`);
				$('td:eq(5)', nRow).html(`<button type="button" onclick="addTransactions(${aData[2]},'${aData[1]}',${aData[4]})" class="btn btn-link"><i class="fa fa-tasks"></i></button>`);
				$('td:eq(6)', nRow).html(`<button type="button" onclick="prefillData(${aData[2]},'${aData[1]}',${aData[4]})" class="btn btn-link"><i class="fa fa-file-code"></i></button>`);
			}
		});
	}).catch((e) => {
		console.log(e);
	});
}

function requestToAjax(routeItem, itemObject) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: base_url + routeItem,
			type: "POST",
			dataType: "json",
			data: itemObject,
			contentType: false,
			processData: false,
			success: function (result) {
				resolve(result);
			}, error: function (error) {
				app.errorToast("Something went wrong please try again");
				reject(error);
			}
		});
	});
}

function editfun(template_id, template_name, prefill) {
	$("#template_count").val(0);
	let formData = new FormData();
	formData.append('id', template_id);
	formData.append('template_name', template_name);

	requestToAjax("edithandsontemplate", formData).then(res => {
		if (res.status == 200) {
			$("#CreateHandsonModal").modal('show');
			$('ul#template_detail_div').empty();
			$("#template_name").val('').val(res.template_name);
			hashKeys = '';
			let props = '';
			let show_total = '';
			$("#template_id").val('').val(template_id);
			$("#company_id").val('').val(res.company_id);
			$.each(res.body, function (key, val) {
				createAddTemplateRow(val);
				hashKeys = val.hash_key;
				props = val.props;
				show_total = val.show_total;
				required_fields = val.required_fields;
			});
			$("#props").val('').val(props);
			$("#show_total").val('').val(show_total);
			$("#required_fields").val('').val(required_fields);
			if (hashKeys != '') {
				var myarr = hashKeys.split(",");
				$('#hashKey').val(myarr).change();
			}

			// $("#template_count").val('').val(res.body.length);
			if (prefill != "") {
				$("input[name=prefill][value=" + prefill + "]").prop('checked', true);
			}

		} else {
			app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});
}

function getAllTablesFromDatabase() {
	requestToAjax("TE_getAllTablesFromDatabase").then(res => {
		var option = ``;
		option += `<option value="" selected disabled>Select Table Name</option>`;
		var dataArray = [];
		$(res.data).each(function (i, d) {
			option += `<option value="${d}">${d}</option>`;
			dataOption = {"id": d, "text": d};
			dataArray.push(dataOption);
		});
		$("#table_name").select2({
			data: dataArray,
		});
		/*$("#table_name").html('');
		$("#table_name").html(option);
		$("#table_name").select2();*/
	}).catch((e) => {
		console.log(e);
	});

}

function addConfiguration(temp_id, temp_name) {
	$("#ConfiguarationModal").modal('show');
	$("#Conftemplate_id").val(temp_id);
	getTemplateData(temp_id);
}

function getTemplateData(temp_id) {
	let formData = new FormData();
	formData.append('temp_id', temp_id);
	requestToAjax("TE_getTemplateData", formData).then(res => {
		if (res.status == 200) {


			var html = '';
			html += `<div class="row"><input type="hidden" name="map_temp_id" id="map_temp_id"  value="${temp_id}">
<div class="col-md-6 mt-2">Column Name</div>
<div class="col-md-6 mt-2">Database Column Name</div>
</div>`;
			$('#table_name').select2('destroy');
			$('#table_name').val(res.tableName).select2();

			$("#where_condition").val(res.where_condition);
			getDBColumnNamesClone(res.tableName).then(res1 => {
				$(res.data).each(function (i, d) {
					var option = ``;
					option += res1;
					html += `
				<div class="row">
				<div class="col-md-6 mt-2">
				<input type="hidden" name="map_col_id[]" value="${d.id}">
				<input type="text" class="form-control" readonly id="colName${i}" name="colName[]" value="${d.column_name}" >
				</div>
				<div class="col-md-6 mt-2">
				<select class="form-control DBColumns" required id="DBColname${i}" name="DBColname[]"  >
				${option}
				</select>
				</div>
				</div>
				`;
				});
				$("#ConfigDiv").html(html);
				$(res.data).each(function (i, d) {
					var Colname = '';
					if (res.dataTemplateMapping[d.id] != null && res.dataTemplateMapping[d.id] != '') {
						Colname = res.dataTemplateMapping[d.id];
					}
					$("#DBColname" + i).val(Colname).trigger('change');
				});
			}).catch((e) => {
				console.log(e);
			});

		} else {
			app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});

}

function getDBColumnNames(TableName, num) {
	console.log(num);
	if (num == 1) {
		let formData = new FormData();
		formData.append('TableName', TableName);
		formData.append('map_temp_id', $('#Conftemplate_id').val());
		requestToAjax("TE_getDBColumnNames", formData).then(res => {
			var option = ``;
			option += `<option value="" selected disabled>Select Column Name</option>`;
			$(res.data).each(function (i, d) {
				option += `<option value="${d}">${d}</option>`;
			});

			$(".DBColumns").html('');
			$(".DBColumns").html(option);
		}).catch((e) => {
			console.log(e);
		});
	} else {
		return;
	}

}

function getDBColumnNamesClone(TableName) {
	return new Promise((resolve, reject) => {
		let formData = new FormData();
		formData.append('TableName', TableName);
		formData.append('map_temp_id', $('#Conftemplate_id').val());
		requestToAjax("TE_getDBColumnNames", formData).then(res => {
			var option = ``;
			option += `<option value="" selected disabled>Select Column Name</option>`;
			$(res.data).each(function (i, d) {
				option += `<option value="${d}">${d}</option>`;
			});

			resolve(option);
		}).catch((e) => {
			console.log(e);
		});
	});
}

function SaveConfiguration() {
	var form_data = document.getElementById('configurationForm');
	let formData = new FormData(form_data);

	requestToAjax("TE_SaveConfiguration", formData).then(res => {
		if (res.status == 200) {

			app.successToast(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});
}

function addTransactions(temp_id, temp_name) {
	$("#TransactionModal").modal('show');
	getHandson(temp_id, 'TransactionHandson');
}

let hotDiv;
let DefaultValuesArray = [];

function getHandson(temp_id, div_id, insert_id = null) {
	// insert_id = {"T1": 24};
	$("#transTemplate").val(temp_id);
	let formData = new FormData();
	formData.append('temp_id', temp_id);

	formData.append('insert_id', JSON.stringify(insert_id));

	DefaultValuesArray = insert_id;
	requestToAjax("TE_getHandsonTableData", formData).then(result => {
		if (result.status == 200) {
			var columns = result.columnHeaders;
			var rows = result.rows;
			if (rows == null) {
				rows = [];
			}
			var types = result.columnTypes;
			var hideArra = result.hideArra;
			var columnSummary = result.columnSummary;
			var defaultVal = result.defaultVal;
			var dataSchema = result.dataSchema;
			var formula = result.formula;
			var props = result.props;
			let show_total = result.show_total;
			var anyoneDepend = result.anyoneDepend;
			var hideColumn = {
				// specify columns hidden by default
				columns: hideArra,
				copyPasteEnabled: false,
			};
			var fill_table = result.fill_table;
			// if(fill_table==2)
			// {
			// 	var route_pos = result.route_pos;
			// 	if(route_pos!=null && route_pos!="" && route_pos!=0)
			// 	{
			// 		var eleExists=$("#index_"+route_pos);
			// 		if(eleExists)
			// 		{
			//
			// 		}
			// 	}
			// 	var route_pos_query = result.route_pos_query;
			// }

			createHandonTable(columns, rows, types, div_id, hideColumn, readonlyArray = [], columnSummary, null, dataSchema, formula,props,anyoneDepend,show_total);
		} else {
			app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});
}

function createHandonTable(columnsHeader, columnRows, columnTypes, divId, hideColumn = true, readonlyArray, columnSummary, prefillValueType = null, dataSchema, formula = null,props=null,anyoneDepend=null,show_total=null) {
	$(".filterBtn").show();
	var element = document.getElementById(divId);

	let prop_id = show_total.split('||');
	let total_prop = '';
	let total_id = '';
	if(prop_id.length >1){
		total_prop = prop_id[0];
		total_id = prop_id[1];
	}
	hotDiv != null ? hotDiv.destroy() : '';
	hotDiv = new Handsontable(element, {
		data: columnRows,
		startRows: 20,
		dataSchema: dataSchema,
		colHeaders: columnsHeader,
		// formulas: true,
		formulas: {
			engine: HyperFormula
		},
		manualColumnResize: true,
		manualRowResize: true,
		columnSummary: columnSummary,

		// ],
		columns: columnTypes,
		cells: function (row, col, prop) {
			var cellProperties = {};
			return cellProperties;
		},
		beforeChange: function (changes, source) {
			var row = changes[0][0];
			var prop = changes[0][1];
			var value = changes[0][3];
		},
		afterChange: function (changes, source) {
			if (changes) {
				var row = changes[0][0];
				var prop = changes[0][1];
				var value = changes[0][3];

				if (props.includes(prop)) {
					let data = hotDiv.getDataAtRow(row);
					Object.keys(formula).forEach(e=>{
						let formula_string = formula[e];
						console.log(formula_string);
						formula_string = formula_string.split(')').join('').split('(').join('').split('+').join(',').split('-').join(',').split('*').join(',').split('/')
						formula_string = formula_string[0].split(',');

						let val = '';
						let formula_expression = formula[e];
						formula_string.map(r=>{
							let g = r.split('#');
							console.log(r);
							if(formula[e].includes(g[1])){
								formula_expression = formula_expression.replaceAll(r,data[g[1]]);
							}
						});
						val = eval(formula_expression);
						data[e] = val;
						hotDiv.setDataAtRowProp(row,e,val);
					});
				}
				if(anyoneDepend!=null && anyoneDepend!="")
				{
					var attchList = anyoneDepend.map((e,indx) =>{
						console.log(e);
						if(prop==e.columnNo)
						{
							var data=getDataOnChange(value,e).then(el=>{
								if(el.status===200)
								{
									if(el.body.length>0)
									{
										var anyDep=el.body;
										anyDep.map(dval=>{
											this.setDataAtRowProp(row,dval.col,dval.value);
										});
									}
								}
							});
							this.render();
						}
					});
				}
				if(prop == total_prop){
					let columns_to_be_sum = [total_prop]; // will be provided dynamically.
					let sums = [];
					let visualdata = hotDiv.getData();
					let rows = visualdata.length;

					for (let k = 0; k < columns_to_be_sum.length; k++) {
						sums[columns_to_be_sum[k]] = 0;
						for (let row = 0; row < rows; row += 1) {
							sums[columns_to_be_sum[k]] = sums[columns_to_be_sum[k]] + parseInt(hotDiv.getSourceDataAtCell(hotDiv.toPhysicalRow(row), columns_to_be_sum[k]));
						}
					}
					$("#"+total_id).show();
					$("#"+total_id).val(sums[columns_to_be_sum[0]]);
				}
			}
		},
		stretchH: 'all',
		colWidths: '100%',
		width: '100%',
		height: 500,
		rowHeights: 33,
		rowHeaders: true,
		filters: true,
		allowRemoveRow: true,
		contextMenu: true,
		// contextMenu: ['undo', 'redo', 'readonly', 'alignment', 'copy', 'cut','remove_row'],
		hiddenColumns: hideColumn,
		minSpareRows: 0,
		dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
		licenseKey: 'non-commercial-and-evaluation'
	});
	hotDiv.validateCells();


	hotDiv.alter('insert_row',hotDiv.countRows(), 20)

}
function getDataOnChange(value,eObj) {
	return new Promise(function (resolve,reject){
		$.ajax({
			url: base_url + "getDataOnChange",
			type: "POST",
			dataType: "json",
			data:{value:value,query:eObj.query,columnType:eObj.columnType,columns:eObj.columns},
			success: function (result) {
				resolve(result);
			},
			error: function (error) {
				console.log(error);
				// $.LoadingOverlay("hide");
			}
		});
	});
}
function SaveTransaction() {

	let formData = new FormData();

	var reportData = hotDiv.getData();
	formData.append('reportData', JSON.stringify(reportData));
	formData.append('DefaultValuesArray', JSON.stringify(DefaultValuesArray));
	formData.append('TemplateId', $("#transTemplate").val());
	requestToAjax("TE_SaveTransaction", formData).then(res => {
		if (res.status == 200) {
			app.successToast(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});
}

$('#handonTableModal').on('shown.bs.modal', function (event) {
	var temp_id = $("#spreadsheetTemplateId").val();
	getHandsonTableForprefillData(temp_id);
});

function prefillData(temp_id, temp_name) {
	$("#spreadsheetTemplateId").val(temp_id);
	$("#spreadsheetTemplateName").val(temp_name);
	$("#handonTableModal").modal('show');
	getHandsonTableForprefillData(temp_id);
}

function getHandsonTableForprefillData(temp_id) {
	let formData = new FormData();
	formData.append('temp_id', temp_id);
	formData.append('code', 1);

	requestToAjax("TE_getHandsonTableData", formData).then(result => {
		if (result.status == 200) {
			var columns = result.columnHeaders;
			var rows = result.rows;
			if (rows == null) {
				var rows = [
					['', '', '', '', ''],
				];
			}

			var types = result.columnTypes;
			var hideArra = result.hideArra;
			var columnSummary = result.columnSummary;
			var hideColumn = {
				// specify columns hidden by default
				columns: hideArra,
				copyPasteEnabled: false,
			};
			console.log(columns);
			createHandonTable(columns, rows, types, 'PrefillHandson', hideColumn, readonlyArray = [], columnSummary);
		} else {
			app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});
}

function savePrefillData() {
	var reportData = hotDiv.getData();
	let formData = new FormData();
	formData.append('reportData', JSON.stringify(reportData));
	formData.append('TemplateId', $("#spreadsheetTemplateId").val());
	requestToAjax("TE_SavePrefillData", formData).then(res => {
		if (res.status == 200) {

			app.successToast(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch((e) => {
		console.log(e);
	});
}

function getAllHashKeys() {
	return new Promise((resolve, reject) => {
		requestToAjax("TE_getAllHashKeys").then(res => {
			var option = ``;

			$(res.data).each(function (i, d) {
				var val = "#" + d.hash_key;
				option += `<option value="${d.hash_key}">${val}</option>`;
			});
			console.log(option);
			resolve(option);
		}).catch((e) => {
			console.log(e);
		});
	});
}

function getAllHashKeysOnLoad() {

	getAllHashKeys().then(res => {
		$("#hashKey").html(res);
		$("#hashKey").select2();
	}).catch((e) => {
		console.log(e);
	});
}

// id provision
function handleClick(ele) {
	if ($(ele).val() == '1') {
		$("#routePosDiv").hide();
		$("#routePosQueryDiv").hide();
	} else {
		$("#routePosDiv").show();
		$("#routePosQueryDiv").show();
	}
}

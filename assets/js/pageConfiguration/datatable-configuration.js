
function copyText(id)
{
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($('#'+id).text()).select();
	document.execCommand("copy");
	$temp.remove();
}
function setDatatableConfigurations(row, col, type)
{
	$("#DatatableConfigurationModal").modal('show');

	// loadDataTable();
	
	$("#datatable_row").val(row);
	$("#datatable_col").val(col);
	getAllTables().then(res => {
		console.log('supi');
		let isDatatableEmpty=isEmpty(dataTableFormArray);
		if(isDatatableEmpty==false)
		{
			getDatatableConfigureData(row);
		}
	});
}	
	let tableColumnsOption = null;
	let tableOption = null;
	let QueryStringParameter = null;
	function getAllTables(section_id = null, element_id = null) {
		return new Promise(function (resolve, reject) {
			app.request("getAllTables", null).then(response => {

				tableOption = response.option;
				$("#queryTable").empty();
				$("#queryTable").append(response.option);
				$("#queryTable").select2();
				$("#filterQueryTable").empty();
				$("#filterQueryTable").append(response.option);
				$("#filterQueryTable").select2();
				if (section_id != null && element_id != null) {
					getSelecteDatableData(section_id, element_id);
				}
				resolve(response.options);
			}).catch(error => {
				console.log(error);
				app.errorToast("Something went wrong")
			});
		});
	}

	function getAllColumns(value, type = -1, data = null, count = null) {

		let formData = new FormData();
		formData.set("TableName", value);
		app.request("getAllColumns", formData).then(response => {
				
			if (type === -1) {
				tableColumnsOption = response.option;
				$("#queryTableSelectColumn").empty();
				$("#queryTableSelectColumn").append(response.option);
				$("#queryTableSelectColumn").select2();
				$("#queryTableSearchColumn").empty();
				$("#queryTableSearchColumn").append(response.option);
				$("#queryTableSearchColumn").select2();
				$("#queryTableOrderColumn").empty();
				$("#queryTableOrderColumn").append(response.option);
				$("#queryTableOrderColumn").select2();

				$("#queryTableOrderColumn").empty();
				$("#queryTableOrderColumn").append(response.option);
				$("#queryTableOrderColumn").select2();

				$("#queryTableFileColumn").empty();

				$("#queryTableFileColumn").append(response.option);
				$("#queryTableFileColumn").select2();

				if (data != null) {
					getSelectSelectedColumn(data);
				}

			} else {
				$("#filterKeyValue_" + count).empty();
				$("#filterKeyValue_" + count).append(response.option);
				$("#filterKeyValue_" + count).select2();
				$("#filterKey_" + count).empty();
				$("#filterKey_" + count).append(response.option);
				$("#filterKey_" + count).select2();
				// getSelectedFilterSelection(data, count);
			}


		}).catch(error => {
			console.log(error);
			app.errorToast("Something went wrong")
		})
	}
	function getAllColumns1(value, type = -1, data = null, count = null) {
		return new Promise(function (resolve, reject) {
			let formData = new FormData();
			formData.set("TableName", value);
			app.request("getAllColumns", formData).then(response => {
					
				if (type === -1) {
					tableColumnsOption = response.option;
					$("#queryTableSelectColumn").empty();
					$("#queryTableSelectColumn").append(response.option);
					$("#queryTableSelectColumn").select2();
					$("#queryTableSearchColumn").empty();
					$("#queryTableSearchColumn").append(response.option);
					$("#queryTableSearchColumn").select2();
					$("#queryTableOrderColumn").empty();
					$("#queryTableOrderColumn").append(response.option);
					$("#queryTableOrderColumn").select2();

					$("#queryTableOrderColumn").empty();
					$("#queryTableOrderColumn").append(response.option);
					$("#queryTableOrderColumn").select2();

					$("#queryTableFileColumn").empty();

					$("#queryTableFileColumn").append(response.option);
					$("#queryTableFileColumn").select2();

					if (data != null) {
						getSelectSelectedColumn(data);
					}
				} else {
					$("#filterKeyValue_" + count).empty();
					$("#filterKeyValue_" + count).append(response.option);
					$("#filterKeyValue_" + count).select2();
					$("#filterKey_" + count).empty();
					$("#filterKey_" + count).append(response.option);
					$("#filterKey_" + count).select2();
					// getSelectedFilterSelection(data, count);
				}
				resolve(true);

			}).catch(error => {
				console.log(error);
				app.errorToast("Something went wrong")
			})
		});
	}

	let filterCount = 0;
	let filterArray = [];

	function addFilterSection() {
		filterCount++;
		let templateString = filterTemplate(filterCount);
		$("#filterSection").append(templateString);
		filterArray.push({});
		if (tableColumnsOption != null) {
			$("#filterTableColumn_" + filterCount).empty();
			$("#filterTableColumn_" + filterCount).append(tableColumnsOption);
			$("#filterTableColumn_" + filterCount).select2();
		}

	}

	function filterTypeSection(counter, value) {
		if (parseInt(value) === 3) {
			$("#dropDownQueryTableSection_" + counter).removeClass("d-none");
			$("#dropDownKeySection_" + counter).removeClass("d-none");
			$("#dropDownKeyValueSection_" + counter).removeClass("d-none");
			$("#dropDownCustomKeyValueSection_" + counter).removeClass("d-none");
			$("#dropDownConditionSection_" + counter).removeClass("d-none");
			if (tableOption != null) {
				$("#filterQueryTable_" + counter).empty();
				$("#filterQueryTable_" + counter).append(tableOption);
				$("#filterQueryTable_" + counter).select2();
			}

		} else {
			$("#dropDownQueryTableSection_" + counter).addClass("d-none");
			$("#dropDownKeySection_" + counter).addClass("d-none");
			$("#dropDownKeyValueSection_" + counter).addClass("d-none");
			$("#dropDownCustomKeyValueSection_" + counter).addClass("d-none");
			$("#dropDownConditionSection_" + counter).addClass("d-none");
		}

	}

	function filterTemplate(counter) {

		return `<div class="form-row my-2 form_row" id="filter_row_${counter}">
					<div class="form-group col-sm-4 col-md-2">
						<label>Filter On Column</label>
						<select class="w-100" id="filterTableColumn_${counter}"  name="filterQueryTable_${counter}">
						<option>Table Name</option>
						</select>
					</div>
					<div class="form-group col-sm-4 col-md-2">
						<label>Filter value type</label>
						<select class="form-control"
							id="filterValueType_${counter}" name="filterValueType_${counter}"
							onchange="filterTypeSection(${counter},this.value)">
							<option value="-1" disabled selected>Select Value Type</option>
						<option value="1">Date</option>
						<option value="2">DateTime</option>
						<option value="3">DropDown</option>
						</select>
					</div>
					<div class="form-group col-sm-4 col-md-2 d-none"  id="dropDownQueryTableSection_${counter}">
						<label>Filter DropDown Query Table</label>
						<select class="form-control" id="filterQueryTable_${counter}" onchange="getAllColumns(this.value,2,null,'${counter}')" name="filterQueryTable_${counter}">

						</select>
					</div>
					<div class="form-group col-sm-4 col-md-2 d-none" id="dropDownKeySection_${counter}">
						<label>Filter DropDown key Column</label>
						<select class="form-control" id="filterKey_${counter}" name="filterKey_${counter}">

						</select>
					</div>
					
					<div class="form-group col-sm-4 col-md-2 d-none" id="dropDownKeyValueSection_${counter}">
						<label>Filter DropDown key value Column</label>
						<select class="form-control" id="filterKeyValue_${counter}" name="filterKeyValue_${counter}">

						</select>
					</div>
					<div class="form-group col-sm-4 col-md-2 d-none" id="dropDownCustomKeyValueSection_${counter}">
						<label>Filter Custom Key@Value</label>
						<input type="text" class="form-control" id="filterCustomKeyValue_${counter}" name="filterCustomKeyValue_${counter}"/>
					</div>
					<div class="form-group col-sm-4 col-md-2 d-none" id="dropDownConditionSection_${counter}">
						<label>Filter DropDown condition</label>
						<input type="text" class="form-control" id="filterQueryCondition_${counter}" name="filterQueryCondition_${counter}"/>
					</div>
					<div class="form-group col-sm-4 col-md-2">
						<label>Filter DropDown Static values</label>
						<input type="text" class="form-control" id="filterStaticValue_${counter}" name="filterStaticValue_${counter}"/>
					</div>
					<div class="form-group col-sm-1 col-md-1">
						
						<button type="button" class="btn btn-primary btn-sm" onclick="removedatatableRow('filter_row_${counter}')"><i class="fa fa-times"></i></button>
					</div>
				</div>`
	}

	let whereCount = 0;
	let whereArray = [];

	function addWhereSection() {
		whereCount++;
		let templateString = whereTemplate(whereCount);
		if (tableColumnsOption != null) {
			$("#WhereSection").append(templateString);
			whereArray.push({});
			$("#whereTableColumn_" + whereCount).empty();
			$("#whereTableColumn_" + whereCount).append(tableColumnsOption);
			$("#whereTableColumn_" + whereCount).select2();
			getQueryStringParaForWhere(whereCount);
		} else {
			app.errorToast("Select Primary Table")
		}
	}

	function whereTemplate(counter) {
		return `<div class="form-row my-2 form_row" id="where_row_${counter}">
					<div class="form-group col-sm-4 col-md-2">
						<label>Where On Column</label>
						<select class="w-100" id="whereTableColumn_${counter}"  name="whereQueryTable_${counter}">
						<option>Table Name</option>
						</select>
					</div>
					<div class="form-group col-sm-4 col-md-3" id="whereDropDownKeyValueSection_${counter}">
						<label>Where Query Parameter Value</label>
						<select class="form-control" id="whereValue_${counter}" name="whereValue_${counter}">

						</select>
					</div>
					<div class="form-group col-sm-4 col-md-2">
						<label>Where Static values</label>
						<input type="text" class="form-control" id="whereStaticValue_${counter}" name="whereStaticValue_${counter}"/>
					</div>
					<div class="form-group col-sm-1 col-md-1">
						
						<button type="button" class="btn btn-primary btn-sm" onclick="removedatatableRow('where_row_${counter}')"><i class="fa fa-times"></i></button>
					</div>
				</div>`
	}
	function getQueryStringParaForWhere(counter) {
		//QueryStringParameter

		$("#whereValue_" + counter).empty();
		if (QueryStringParameter != null) {
			$("#whereValue_" + counter).append(QueryStringParameter);
		} else {
			$("#whereValue_" + counter).append(QueryStringParameter);
		}

	}

	// function getQueryStringParaForWhere(counter) {
	// 	//QueryStringParameter
	// 	app.request("getQueryStringPara").then(response => {
	// 		$("#whereValue_" + counter).empty();
	// 		if (response.status === 200) {
	// 			$("#whereValue_" + counter).append(response.data);
	// 		} else {
	// 			$("#whereValue_" + counter).append(response.data);
	// 		}
	// 	});
	// }
	function getQueryStringParaForWhere1(counter) {
		//QueryStringParameter
		let KeyOptions='<option value=""></option>';
		app.request("TE_getAllHashKeys").then(response => {
			if (response.status === 200) {
				response.data.map(e=>{
					KeyOptions+='<option value="#'+e.hash_key+'">'+e.hash_key+'</option>';
				});
				QueryStringParameter = KeyOptions;
			} else {
				QueryStringParameter = KeyOptions;
			}
		});
	}

	let actionCount = 0;
	let actionArray = [];

	function addActionSection() {
		actionCount++;
		let templateString = actionTemplate(actionCount);
		$("#actionSection").append(templateString);
		actionArray.push({});
		if (tableColumnsOption != null) {
			$("#actionButtonPrimary_" + actionCount).empty();
			$("#actionButtonPrimary_" + actionCount).append(tableColumnsOption);
			$("#actionButtonPrimary_" + actionCount).select2();
		}
	}

	function saveDatatableForm() {

		whereArray.forEach((object, index) => {
			index++;
			let whereTableColumn = $("#whereTableColumn_" + index).val();
			if (whereTableColumn !== "") {
				object.WhereTableColumn = whereTableColumn;
			} else {
				delete object.WhereTableColumn;
			}

			let whereValueType = $("#whereValue_" + index).val();
			if (whereValueType != null) {
				if (whereValueType.length > 0) {
					object.WhereColumnValue = whereValueType;
				} else {
					if ($("#whereStaticValue_" + index).val() !== "" && $("#whereStaticValue_" + index).val() !== null) {
						object.WhereColumnValue = $("#whereStaticValue_" + index).val();
					} else {
						delete object.WhereColumnValue;
					}
				}
			} else {
				if ($("#whereStaticValue_" + index).val() !== "" && $("#whereStaticValue_" + index).val() !== null) {
					object.WhereColumnValue = $("#whereStaticValue_" + index).val();
				} else {
					delete object.WhereColumnValue;
				}
			}

		});


		filterArray.forEach((object, index) => {
			index++;
			let filterTableColumn = $("#filterTableColumn_" + index).val();
			if (filterTableColumn !== "") {
				object.FilterTableColumn = filterTableColumn;
			} else {
				delete object.FilterTableColumn;
			}
			let filterValueType = $("#filterValueType_" + index).val();
			if (filterValueType !== "" && filterValueType != null) {
				object.filterValueType = filterValueType;
			} else {
				delete object.FilterTableColumn;
			}
			let filterQueryTable = $("#filterQueryTable_" + index).val();
			if (filterQueryTable !== "" && filterQueryTable != null) {
				object.filterQueryTable = filterQueryTable;
			} else {
				delete object.filterQueryTable;
			}

			let filterKey = $("#filterKey_" + index).val();
			if (filterKey !== "" && filterKey != null) {
				object.filterKey = filterKey;
			} else {
				delete object.filterKey;
			}

			let filterKeyValue = $("#filterKeyValue_" + index).val();
			if (filterKeyValue !== "" && filterKeyValue != null) {
				object.filterKeyValue = filterKeyValue;
			} else {
				delete object.filterKeyValue;
			}

			let customfilterValue = $("#filterCustomKeyValue_" + index).val();
			if (customfilterValue !== "" && customfilterValue != null) {
				object.customfilterValue = customfilterValue;
			} else {
				delete object.customfilterValue;
			}

			let filterQueryCondition = $("#filterQueryCondition_" + index).val();
			if (filterQueryCondition !== "" && filterQueryCondition != null) {
				object.filterQueryCondition = filterQueryCondition;
			} else {
				delete object.filterQueryCondition;
			}

			let filterStaticValue = $("#filterStaticValue_" + index).val();
			if (filterStaticValue !== "" && filterStaticValue != null) {
				object.filterStaticValue = filterStaticValue;
			} else {
				delete object.filterStaticValue;
			}
		});

		actionArray.forEach((object, index) => {
			index++;
			let actionButtonPrimary = $("#actionButtonPrimary_" + index).val();
			if (actionButtonPrimary !== "" && actionButtonPrimary != null) {
				object.actionButtonPrimary = actionButtonPrimary;
			} else {
				delete object.actionButtonPrimary;
			}
			let actionButtonIcon = $("#actionButtonIcon_" + index).val();
			if (actionButtonIcon !== "" && actionButtonIcon != null) {
				object.actionButtonIcon = actionButtonIcon;
			} else {
				delete object.actionButtonIcon;
			}
			let actionButtonType = $("#actionButtonType_" + index).val();
			if (actionButtonType !== "" && actionButtonType != null) {
				object.actionButtonType = actionButtonType;
			} else {
				delete object.actionButtonType;
			}
			if (actionButtonType === "2") {
				object.actionButtonRedirectTemplate = $("#datatable_section_id").val();
				object.actionButtonRedirectUpdateParam = "#" + object.actionButtonPrimary;
			} else {
				let actionButtonRedirectTemplate = $("#actionButtonRedirectTemplate_" + index).val();
				if (actionButtonRedirectTemplate !== "" && actionButtonRedirectTemplate != null) {
					object.actionButtonRedirectTemplate = actionButtonRedirectTemplate;
				} else {
					delete object.actionButtonRedirectTemplate;
				}
				let actionButtonRedirectQueryParam = $("#actionButtonRedirectQueryParam_" + index).val();
				if (actionButtonRedirectQueryParam !== "" && actionButtonRedirectQueryParam != null) {
					object.actionButtonRedirectQueryParam = actionButtonRedirectQueryParam;
				} else {
					delete object.actionButtonRedirectQueryParam;
				}
			}


			let actionButtonExecutionQuery = $("#actionButtonExecutionQuery_" + index).val();
			if (actionButtonExecutionQuery !== "" && actionButtonExecutionQuery != null) {
				object.actionButtonExecutionQuery = actionButtonExecutionQuery;
			} else {
				delete object.actionButtonExecutionQuery;
			}
			let actionButtonModalTemplate = $("#actionButtonModalTemplate_" + index).val();
			if (actionButtonModalTemplate !== "" && actionButtonModalTemplate != null) {
				object.actionButtonModalTemplate = actionButtonModalTemplate;
			} else {
				delete object.actionButtonModalTemplate;
			}
			let actionButtonModalQueryParam = $("#actionButtonModalQueryParam_" + index).val();
			if (actionButtonModalQueryParam !== "" && actionButtonModalQueryParam != null) {
				object.actionButtonModalQueryParam = actionButtonModalQueryParam;
			} else {
				delete object.actionButtonModalQueryParam;
			}
			let actionButtonRedirectPage = $("#actionButtonRedirectPage_" + index).val();
			if (actionButtonRedirectPage !== "" && actionButtonRedirectPage != null) {
				object.actionButtonRedirectPage = actionButtonRedirectPage;
			} else {
				delete object.actionButtonRedirectPage;
			}
		});

		
		let queryTable = $("#queryTable").val();
		let queryTableSelectColumn = "";
		let customWhereCondition = $("#queryTableWhereCondition").val();

		let fileColumn = $('#queryTableFileColumn').val();
		let fileOperation = $("#rb_check_download").is("checked") ? $("#rb_check_download").val() :
				$("#rb_check_both_download").is("checked") ? $("#rb_check_both_download").val() : 1;

		if ($("#queryTableSelectColumn").val().length !== 0) {
			if ($("#rawQueryTableSelectColumn").val() !== "") {
				let selectColumn = $("#queryTableSelectColumn").val().join();
				selectColumn += "," + $("#rawQueryTableSelectColumn").val();
				queryTableSelectColumn = selectColumn
			} else {
				queryTableSelectColumn = $("#queryTableSelectColumn").val();
			}
		} else {
			if ($("#rawQueryTableSelectColumn").val() !== "") {
				queryTableSelectColumn = $("#rawQueryTableSelectColumn").val();
			}
		}

		let queryTableSearchColumn = "";
		if ($("#queryTableSearchColumn").val().length !== 0) {
			if ($("#rawQueryTableSearchColumn").val() !== "") {
				let searchColumn = $("#queryTableSearchColumn").val().join();
				searchColumn += "," + $("#rawQueryTableSearchColumn").val();
				queryTableSearchColumn = searchColumn;
			} else {
				queryTableSearchColumn = $("#queryTableSearchColumn").val();
			}
		} else {
			if ($("#rawQueryTableSearchColumn").val() !== "") {
				queryTableSearchColumn = $("#rawQueryTableSearchColumn").val();
			}
		}

		let queryTableOrderColumn = "";
		if ($("#queryTableOrderColumn").val().length !== 0) {
			queryTableOrderColumn = $("#queryTableOrderColumn").val();
		}

		let queryTableOrderColumnDirection = $("#queryTableOrderColumnDirection").val();

		let dataTableSyncType = $("#serverSideSync").is("checked") ? $("#serverSideSync").val() :
				$("#clientSideSync").is("checked") ? $("#clientSideSync").val() : 1;
		let dataTableWhereCondition = $("#queryTableWhereCondition").val();

		if (queryTable !== "" && queryTable != null
				&& queryTableSelectColumn !== "" && queryTableSelectColumn != null
				&& queryTableSearchColumn !== "" && queryTableSearchColumn != null
				&& queryTableOrderColumn !== "" && queryTableOrderColumn != null
				&& queryTableOrderColumnDirection !== "" && queryTableOrderColumnDirection != null
				&& dataTableSyncType !== "" && dataTableSyncType != null
		) {
			let dataTableForm={};
			dataTableForm.query_master_id= $("#query_master_id").val();
			dataTableForm.element_id= $("#element_id").val();
			dataTableForm.section_id=$("#datatable_section_id").val();
			dataTableForm.queryTable=queryTable;
			dataTableForm.customWhereCondition=customWhereCondition;
			dataTableForm.queryTableSelectColumn=queryTableSelectColumn;
			dataTableForm.queryTableSearchColumn=queryTableSearchColumn;
			dataTableForm.queryTableOrderColumn= queryTableOrderColumn;
			dataTableForm.queryTableOrderColumnDirection= queryTableOrderColumnDirection;
			dataTableForm.dataTableSyncType=dataTableSyncType;
			dataTableForm.queryTableWhereCondition= dataTableWhereCondition;
			dataTableForm.queryFileColumn=fileColumn;
			dataTableForm.queryFileOperation=fileOperation;

			if (filterArray.length > 0) {
				dataTableForm.filterData=JSON.stringify(filterArray);
			}
			if (actionArray.length > 0) {
				dataTableForm.actionData= JSON.stringify(actionArray);
			}
			if (whereArray.length > 0) {
				dataTableForm.whereData=JSON.stringify(whereArray);
			}
			dataTableFormArray.push(dataTableForm);
			// console.log(dataTableForm);
			// app.request("saveDataTableEditor", dataTableForm).then(response => {
			// 	console.log(response);
			// 	if (response.status === 200) {
			// 		app.successToast(response.body);
			// 		// loadDataTable($("#element_id").val(), $("#datatable_section_id").val());
			// 		$("#uploadCompany")[0].reset();
			// 		$("#datatableOpBtn").click();
			// 	} else {
			// 		app.errorToast(response.body);
			// 	}

			// }).catch(error => {
			// 	console.log(error);
			// 	app.errorToast("Something Went Wrong");
			// })
		} else {
			app.errorToast("Please fill all required values")
		}

	}

	function actionTypeSection(counter, value) {
		if (parseInt(value) === 1) {
			$("#actionSectionRedirectTemplate_" + counter).removeClass("d-none");
			$("#actionSectionRedirectQueryParam_" + counter).removeClass("d-none");
			getAllSection('actionButtonRedirectTemplate_' + counter)
		} else {
			$("#actionSectionRedirectTemplate_" + counter).addClass("d-none");
			$("#actionSectionRedirectQueryParam_" + counter).addClass("d-none");
			$("#actionButtonRedirectTemplate_" + counter).val("");
			$("#actionButtonRedirectQueryParam_" + counter).val("");
		}
		if (parseInt(value) === 2) {
			$("#actionSectionRedirectPage_" + counter).removeClass("d-none");
		} else {
			$("#actionButtonRedirectPage_" + counter).val("");
			$("#actionSectionRedirectPage_" + counter).addClass("d-none");

		}

		if (parseInt(value) === 3) {
			$("#actionSectionExecutionQuery_" + counter).removeClass("d-none");
		} else {
			$("#actionSectionExecutionQuery_" + counter).addClass("d-none");
			$("#actionButtonExecutionQuery_" + counter).val("");
		}
		if (parseInt(value) === 4) {
			$("#actionSectionModalTemplate_" + counter).removeClass("d-none");
			$("#actionSectionModalQueryParam_" + counter).removeClass("d-none");
		} else {
			$("#actionSectionModalTemplate_" + counter).addClass("d-none");
			$("#actionSectionModalQueryParam_" + counter).addClass("d-none");
			$("#actionButtonModalTemplate_" + counter).val("");
			$("#actionButtonModalQueryParam_" + counter).val("");
		}


	}

	function actionTemplate(counter) {

		return `
		<div class="form-row my-2 form_row" id="action_row_${counter}">
			<div class="form-group col-md-2">
				<label>Primary column</label>
				<select class="w-100" id="actionButtonPrimary_${counter}" name="actionButtonPrimary_${counter}">
				<option>Table Name</option>
				</select>
			</div>
			<div class="form-group col-md-2">
				<label>Icon class</label>
				<select  class="form-control" id="actionButtonIcon_${counter}" name="actionButtonIcon_${counter}" >
				<option value="">Select Icon</option>
				<option value="fa fa-pen">fa fa-pen</option>
				<option value="fa fa-trash">fa fa-trash</option>
				<option value="fa fa-eye">fa fa-eye</option>
				</select>

			</div>
			<div class="form-group col-md-2">
				<label>Action Type</label>
				<select class="form-control" id="actionButtonType_${counter}" name="actionButtonType_${counter}"
                 onchange="actionTypeSection(${counter},this.value)">
				<option value="-1" >Select Action Type</option>
				<option value="1">Redirection To new Template</option>
				<option value="2">Update Operation</option>
				<option value="3">Execute Query</option>
				<option value="4">Custom Button</option>
				</select>
			</div>
			<div class="form-group col-md-2 d-none" id="actionSectionRedirectTemplate_${counter}">
				<label>Redirection Template </label>
				<select class="form-control" id="actionButtonRedirectTemplate_${counter}" name="actionButtonRedirectTemplate_${counter}">
				<option>Select key/value Column</option>
				</select>
			</div>
			<div class="form-group col-md-2 d-none" id="actionSectionRedirectQueryParam_${counter}">
				<label>Extra Query Parameter Value</label>
				<input type="text" class="form-control" id="actionButtonRedirectQueryParam_${counter}" name="actionButtonRedirectQueryParam_${counter}"/>
			</div>
			<div class="form-group col-md-2 d-none"  id="actionSectionExecutionQuery_${counter}">
				<label>Execution Query</label>
				<textarea class="form-control"  id="actionButtonExecutionQuery_${counter}" name="actionButtonExecutionQuery_${counter}"></textarea>
			</div>
			<div class="form-group col-sm-1 col-md-1">		
				<button type="button" class="btn btn-primary btn-sm" onclick="removedatatableRow('action_row_${counter}')"><i class="fa fa-times"></i></button>
			</div>
		</div>
		`;
	}

	function loadDataTable(element_id, section_id) {
		let formData = new FormData();
		formData.set("element_id", element_id)
		formData.set("section_id", section_id)
		app.request(baseURL + "getDataTableTemplate", formData).then(response => {

			if (response.status === 200) {
				var div_id = element_id.replace("#", "");
				$("#dynamic_datatable_" + div_id).empty();
				$("#dynamic_datatable_" + div_id).append(response.body);

				app.dataTable(response.tableID, {
					url: baseURL + "getDataTableData",
					data: {
						element_id: element_id,
						section_id: section_id
					}
				});
			}
		})
	}

	function dynamicFilter(element_id, index, filterColumn, type, value, section_id) {
		var div_id = element_id.replace("#", "");
		app.dataTable("dynamicDataTable_" + div_id, {
			url: baseURL + "getDataTableData",
			data: {
				element_id: element_id,
				section_id: section_id,
				filterColumn: filterColumn,
				filterByValue: value
			}
		});
	}

	function getAllSection(element) {
		app.request(baseURL + "getAllSection", null).then(response => {
			tableOption = response.option;
			$(`#${element}`).empty();
			$(`#${element}`).append(response.body);
			$(`#${element}`).select2();

		}).catch(error => {
			console.log(error);
			app.errorToast("Something went wrong")
		})
	}
	function removedatatableRow(divId)
	{
		$("#"+divId).remove();
	}
	function saveDataTable()
	{
		let form_data=new FormData();
		form_data.set('data',JSON.stringify(dataTableForm));
		app.request("saveDataTableEditor", form_data).then(response => {
				console.log(response);
				if (response.status === 200) {
					app.successToast(response.body);
					// loadDataTable($("#element_id").val(), $("#datatable_section_id").val());
					$("#uploadCompany")[0].reset();
					$("#datatableOpBtn").click();
				} else {
					app.errorToast(response.body);
				}

			}).catch(error => {
				console.log(error);
				app.errorToast("Something Went Wrong");
			})
	}
	function findById(id) {
	  for (var i = 0; i < dataTableFormArray.length; i++) {
	    if (dataTableFormArray[i].element_id == id) {
	      return dataTableFormArray[i];
	    }
	  }
	}
	function getDatatableConfigureData(id) {
		
		let dataTableForm=findById(id);
		console.log(dataTableForm);
		if(dataTableForm!="")
		{
			$("#queryTable").select2('destroy');
			$("#queryTable").val(dataTableForm.queryTable).select2();
			// $("#queryTable").val(dataTableForm.queryTable).trigger('change');
			// $("#queryTable").select2();
			getAllColumns1(dataTableForm.queryTable).then(res=>{
				//select column
				if (dataTableForm.queryTableSelectColumn != null && dataTableForm.queryTableSelectColumn != "") {
					if(Array.isArray(dataTableForm.queryTableSelectColumn))
					{
						$("#queryTableSelectColumn").val(dataTableForm.queryTableSelectColumn).trigger('change');
						$("#queryTableSelectColumn").select2();
					}
					else
					{
						let select_column=dataTableForm.queryTableSelectColumn.split(',');
						var subqueryString = "";
						for (var i = 0; i < select_column.length; i++) {
								var string = select_column[i];
								if (string.indexOf(' as ') >= 0) {
									subqueryString += string + ",";

								} else {
									$("#queryTableSelectColumn option[value=" + select_column[i] + "]").prop("selected", true);
								}
							}
						subqueryString = subqueryString.replace(/,+$/, '');
						$("#rawQueryTableSelectColumn").val(subqueryString);
						$("#queryTableSelectColumn").select2();
					}
				}

				//search column
				if (dataTableForm.queryTableSearchColumn != null && dataTableForm.queryTableSearchColumn != "") {
					if(Array.isArray(dataTableForm.queryTableSearchColumn))
					{
						$("#queryTableSearchColumn").val(dataTableForm.queryTableSearchColumn).trigger('change');
						$("#queryTableSearchColumn").select2();
					}
					else
					{
						var search_column = dataTableForm.queryTableSearchColumn.split(',');
						let subqueryStringSearch = "";
						for (var i = 0; i < search_column.length; i++) {
							var stringSelect = search_column[i];
							if (stringSelect.indexOf(' as ') >= 0) {
								subqueryStringSearch += stringSelect + ",";
							} else {
								$("#queryTableSearchColumn option[value=" + search_column[i] + "]").prop("selected", true);
							}
						}
						subqueryStringSearch = subqueryStringSearch.replace(/,+$/, '');
						$("#rawQueryTableSearchColumn").val(subqueryStringSearch);
						$("#queryTableSearchColumn").select2();
					}
				}
				

				//order column
				if (dataTableForm.queryTableOrderColumn != null && dataTableForm.queryTableOrderColumn != "") {
					$("#queryTableOrderColumn").val(dataTableForm.queryTableOrderColumn).trigger('change');
					$("#queryTableOrderColumn").select2();
				}

				//datatable types
				if (dataTableForm.dataTableSyncType != null && dataTableForm.dataTableSyncType != "") {
					if (dataTableForm.dataTableSyncType == 1) {
						$("#serverSideSync").prop("checked", true);
					} else {
						$("#clientSideSync").prop("checked", true);
					}
				}

				//file column
				if(dataTableForm.queryFileColumn!=null && dataTableForm.queryFileColumn!="")
				{
					$("#queryTableFileColumn").val(dataTableForm.queryFileColumn).trigger('change');
					$("#queryTableFileColumn").select2();
				}

				//order direction
				if(dataTableForm.queryTableOrderColumnDirection!=null && dataTableForm.queryTableOrderColumnDirection!="")
				{
					$("#queryTableOrderColumnDirection").val(dataTableForm.queryTableOrderColumnDirection);
				}

				//file operation
				if(dataTableForm.queryFileOperation!=null && dataTableForm.queryFileOperation!="")
				{
					if (dataTableForm.queryFileOperation == 1) {
						$("#rb_check_download").prop("checked", true);
					} else {
						$("#rb_check_both_download").prop("checked", true);
					}
				}

				//where condition
				//custom
				if(dataTableForm.queryTableWhereCondition!=null && dataTableForm.queryTableWhereCondition!="")
				{
					$("#queryTableWhereCondition").val(dataTableForm.queryTableWhereCondition);
				}
				//array
				if(dataTableForm["whereData"] !== undefined)
				{
					$("#WhereSection").empty();
					get_whereEditData(dataTableForm.whereData);
				}
			});
			
			
		}
	}
	function get_whereEditData(where_condition) {
	if (where_condition != null && where_condition != "") {
		
		var cntr = 1;
		var where_condition = where_condition.split(',');

		for (var i = 0; i < where_condition.length; i++) {
			var where = "where" + cntr;
			where = where_condition[i].split('|');
			
			if (where.length > 2) {
				addWhereSection();
				var whereTableColumn = where[0].split(':');
				if (whereTableColumn.length > 1) {
					$("#whereTableColumn_" + cntr + " option[value=" + whereTableColumn[1] + "]").prop("selected", true);
					$("#whereTableColumn_" + cntr).select2();
				}
				var whereTableValue = where[1].split(':');
				if (whereTableValue.length > 2) {
					$("#whereValue_" + cntr + " option[value='" + whereTableValue[1] + ":" + whereTableValue[2] + "']").prop("selected", true);
					$("#whereValue_" + cntr).select2();
				} else if (whereTableValue.length > 1) {
					$("#whereStaticValue_" + cntr).val(whereTableValue[1]);
				}
			}
			if(where.length>=1)
			{
				var whereCustomWhere = where[0].split(':');
				if(whereCustomWhere[0]=="customWhereCondition")
				{
					$("#queryTableWhereCondition").val(whereCustomWhere[1]);
				}
			}
			cntr++;
		}
	}
}
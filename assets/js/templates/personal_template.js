var clone, before, parent;
let sortList;
$(document).ready(function () {
	let departmentID = localStorage.getItem("Department_id");
	$('#department_id').val(departmentID);
	fetchSection(departmentID);
})

sortList = $('#questionListSortable').sortable({
	connectWith: ".connected-sortable",
	tolerance: 'pointer',
	scroll: true,
	revert: true,
	receive: function (event, ui) {
		let typeElement = parseInt($(ui.item[0]).attr("data-type"));

		let id = Math.floor((Math.random() * 100) + 1);


		switch (typeElement) {
			case 1: // short text
				ui.item.replaceWith(shortText(id, 1));
				break;
			case 2: // long text
				ui.item.replaceWith(longText(id, 2));
				break;
			case 3: // drop down
				ui.item.replaceWith(dropDown(id, 3));
				break;
			case 4: // drop down
				ui.item.replaceWith(multipleDropDown(id, 4));
				break;
			case 5: // date element
				ui.item.replaceWith(dateElement(id, 5));
				break;
			case 6:
				ui.item.replaceWith(number(id, 6));
				break;
			case 7:
				ui.item.replaceWith(attachment(id, 7));
				break;
			case 8:
				ui.item.replaceWith(querydropdown(id, 8));
				break;
			case 9:
				ui.item.replaceWith(fixquerydropdown(id, 9));
				break;
			case 10:
				ui.item.replaceWith(fixnumber(id, 10));
				break;
			case 11:
				ui.item.replaceWith(label(id, 11));
				break;
			case 12:
				ui.item.replaceWith(checkboxGroup(id, 12));
				break;
			case 13:
				ui.item.replaceWith(radioGroup(id, 13));
				break;

		}

	},
	update: function (event, ui) {
		let typeSequence = $('#questionListSortable').sortable("toArray", {attribute: 'data-type'});
		let idSequence = $('#questionListSortable').sortable("toArray", {attribute: 'data-id'});
		$('#elementSequenceType').val(typeSequence.join())
		$('#elementSequenceId').val(idSequence.join())
	}
}).disableSelection();

$('#questionMasterSortable').sortable({
	connectWith: ".connected-sortable",
	helper: "clone",
	revert: true,
	start: function (event, ui) {
		$(ui.item).show();
		clone = $(ui.item).clone();
		before = $(ui.item).prev();
		parent = $(ui.item).parent();
	},

	remove: function (event, ui) {
		if (before.length)
			before.after(clone);
		else
			parent.prepend(clone);
	}
}).disableSelection();

function onHistorySetting(e) {
	if ($(e).is(":checked")) {
		$('input[type="hidden"].custom-switch-input.history').val("on")
	} else {
		$('input[type="hidden"].custom-switch-input.history').val("off")
	}

}

function loadTemplateElement(section_id, department_id) {
	let formData = new FormData();
	formData.set("department_id", department_id);
	formData.set("section_id", section_id);
	app.request(baseURL + "personal/fetch_section_details", formData).then(response => {
		if (response.status === 200) {
			if (response.body.length > 0) {

				let sectionDetails = response.body[0].section.split("|||");
				$('#department_id').val(department_id);
				$('#section_id').val(sectionDetails[0]);
				$("#section_name").val(sectionDetails[1]);
				let typeElementArray = [];
				let elementID = [];
				let element = response.body.map(i => {
					let id = Math.floor((Math.random() * 100) + 1);
					let typeElement = parseInt(i.ans_type);
					typeElementArray.push(typeElement);
					elementID.push(i.id);
					if (parseInt(i.is_history) === 1) {
						$('#ck_template_history').attr("checked", "checked");
					} else {
						$('#ck_template_history').attr("checked", false);
					}
					let section = i.section.split("|||");

					if (parseInt(section[2]) === 1) {
						$('#ck_template_transaction').attr("checked", "checked");

					} else {
						$('#ck_template_transaction').attr("checked", false);
					}

					let items = "";
					switch (typeElement) {
						case 1: // short text
							items = shortText(i.id, 1, i.id, i);
							break;
						case 2: // long text
							items = longText(i.id, 2, i.id, i);
							break;
						case 3: // drop down
							items = dropDown(i.id, 3, i.id, i);
							break;
						case 4: // drop down
							items = multipleDropDown(i.id, 4, i.id, i);
							break;
						case 5: // date element
							items = dateElement(i.id, 5, i.id, i);
							break;
						case 6:
							items = number(i.id, 6, i.id, i);
							break;
						case 7:
							items = attachment(i.id, 7, i.id, i);
							break;
						case 8:
							items = querydropdown(i.id, 8, i.id, i);
							break;
						case 9:
							items = fixquerydropdown(i.id, 9, i.id, i);
							break;
						case 10:
							items = fixnumber(i.id, 10, i.id, i);
							break;
						case 11:
							items = label(i.id, 11, i.id, i);
							break;
						case 12:
							items = checkboxGroup(i.id, 12, i.id, i);
							break;
						case 13:
							items = radioGroup(i.id, 13, i.id, i);
							break;
					}
					return items;

				});
				$('#questionListSortable').empty();
				$('#questionListSortable').append(element);
				$('#elementSequenceType').val(typeElementArray.join());
				$('#elementSequenceId').val(elementID.join());
			}
		}
	})

}

function deleteSection(section_id, department_id) {
	let formData = new FormData();
	formData.set("section_id", section_id)
	app.request(baseURL + "deleteSection", formData).then(response => {
		if (response.status === 200) {
			app.successToast(response.body);
			fetchSection(department_id);
		} else {
			app.errorToast(response.body);
		}
	}).catch(error => {
		console.log("deleteSection : ", error), app.errorToast("Something went wrong")
	})
}

function fetchSection(department_id) {

	let formData = new FormData();
	formData.set("department_id", department_id)
	app.request(baseURL + "personal/fetch_template_sections", formData).then(response => {
		if (response.status === 200) {
			let sectionTemplate = ``;
			if (response.body.length > 0) {
				sectionTemplate +=`<tr>
										<td>Create New Section</td>
										<td>
											<a class="btn btn-primary btn-sm btn-action" onclick="loadNewSection(${department_id})">New</a>
										</td>
									</tr>`;
				sectionTemplate += response.body.map(s => {
					return `
					<tr>
						<td>${s.name}</td>
						<td>
							<a class="btn btn-primary btn-action mr-1" data-toggle="tooltip"
							   title="" data-original-title="Edit" onclick="loadTemplateElement(${s.id},${s.department_id})"><i
							class="fas fa-pencil-alt"></i></a>
							<a class="btn btn-danger btn-acton trigger--fire-modal-1" 
							   onclick="deleteSection(${s.id},${s.department_id})"><i class="fas fa-trash"></i></a>
						</td>
					</tr>
					`
				});

			} else {
				sectionTemplate = `
				<tr>
					<td>No Section</td>
					<td>					
					</td>
				</tr>
				`;
			}
			$("#sectionTableBody").empty();
			$("#sectionTableBody").append(sectionTemplate);
			// app.confirmationBox();
		} else {
			app.errorToast(response.body);
		}

	}).catch(error => {
		console.log("fn_fetchSection : ", error);
		app.errorToast("Something went wrong")
	})
}


// save form
function uploadSection(form) {
	document.getElementById("templateFornBtn").disabled = true;
	let formData = new FormData(form);
	app.request("personal/save_template", formData).then(response => {
		if (response.status === 200) {
			console.log(response.body);
			app.successToast("save");
			// location.reload();
			loadNewSection(department_id);
			document.getElementById("templateFornBtn").disabled = false;
			fetchSection(localStorage.getItem("Department_id"));
		} else {
			app.errorToast(response.body);
			document.getElementById("templateFornBtn").disabled = false;
		}
	})
}

function deleteTemplateItems(id, type) {
	switch (type) {
		case 1: // short text
			$(`#shortText_${id}`).remove();
			break;
		case 2: // long text
			$(`#longText_${id}`).remove();
			break;
		case 3: // drop down
			$(`#dropDown_${id}`).remove();
			break;
		case 4: // drop down
			$(`#multiDropDown_${id}`).remove();
			break;
		case 5: // date element
			$(`#date_${id}`).remove();
			break;
		case 6:
			$(`#number_${id}`).remove();
			break;
		case 7:
			$(`#attachment_${id}`).remove();
			break;
		case 8:
			$(`#queryDropDown_${id}`).remove();
			break;
		case 9:
			$(`#fixnumber_${id}`).remove();
			break;
		case 10:
			$(`#fixqueryDropDown_${id}`).remove();
			break;
		case 11:
			$(`#label_${id}`).remove();
			break;
		case 12:
			$(`#check_box_${id}`).remove();
			break;
		case 13:
			$(`#radio_box_${id}`).remove();
			break;
			
	}
}

function deleteElement(id, type, elementID) {
	if (elementID != null) {
		let formData = new FormData();
		formData.set("templateID", elementID);
		app.request(baseURL + "deleteTemplateElement", formData).then(response => {
			// console.log(response);
			if(response.status===200){
				app.successToast(response.body);
			deleteTemplateItems(id, type);
			let elements = $("#elementSequenceId").val();
			let elementTypes = $("#elementSequenceType").val();
			let elementArray = elements.split(",");
			let elementIndex = elementArray.findIndex(i => parseInt(i) === parseInt(elementID));
			elementArray.splice(elementIndex, 1);
			$("#elementSequenceId").val(elementArray.join(','));
			let elementTypeArray = elementTypes.split(",");
			let typeIndex = elementTypeArray.findIndex(i => parseInt(i) === parseInt(type));
			elementTypeArray.splice(elementIndex, 1);
			$("#elementSequenceType").val(elementTypeArray.join(','));
			}else{
				app.errorToast(response.body);
			}
		}).catch(error => {
			console.log(error);
		})
	} else {
		deleteTemplateItems(id, type);
	}


}

function deleteOptions(id, helmValue, value) {
	let newObjectIndex = optionsValues.findIndex(i => {
		return i.name === helmValue
	})
	if (newObjectIndex !== -1) {
		let valueIndex = optionsValues[newObjectIndex].values.findIndex(i => {
			return i === value;
		})
		if (valueIndex !== -1) {
			optionsValues[newObjectIndex].values.splice(valueIndex, 1);
			$(`#${helmValue}`).val(optionsValues[newObjectIndex].values.join())
		}
	}
	$(`#${id}`).remove();
}

let optionsValues = [];

function addOptions(id, type) {
	let value;
	let length;
	let elm;
	let idName;
	let helm, helmValue = "";
	let objectIndex;
	if (type === 2) {
		idName = "multi_item_";
		value = $(`#multi_drop_down_option_${id}`).val();
		elm = $(`#multi_drop_down_option_list_${id}`);
		helm = $(`#multi_drop_down_option_value_${id}`);
		helmValue = 'multi_drop_down_option_value_' + id;
		objectIndex = optionsValues.findIndex(i => {
			return i.name === 'multi_drop_down_option_value_' + id
		})
		length = elm.children().length;
		$(`#multi_drop_down_option_${id}`).val('');
	} else {
		idName = "drop_down_item_";
		helmValue = 'drop_down_option_value_' + id;
		value = $(`#drop_down_option_${id}`).val();
		elm = $(`#drop_down_option_list_${id}`)
		helm = $(`#drop_down_option_value_${id}`);
		objectIndex = optionsValues.findIndex(i => {
			return i.name === 'drop_down_option_value_' + id
		})
		length = elm.children().length;
		$(`#drop_down_option_${id}`).val('');
	}


	length++;
	let template = `<li class="list-group-item" id="${idName + length}">
				<div class="custom-header p-0">
					<div class="title">${value}</div>
					<div class="action">
						<button type="button" class="btn btn-link text-dark" onclick="deleteOptions('${idName + length}','${helmValue}','${value}')">
						<i class="fas fa-times"></i>
						</button>
					</div>
				</div>
			</li>`;
	if (objectIndex !== -1) {
		optionsValues[objectIndex].values.push(value);
		helm.val(optionsValues[objectIndex].values.join());
	} else {
		let newOptionObject = {
			name: helmValue,
			values: [value]
		}
		optionsValues.push(newOptionObject);
		let newObjectIndex = optionsValues.findIndex(i => {
			return i.name === helmValue
		})
		if (newObjectIndex !== -1) {
			helm.val(optionsValues[newObjectIndex].values.join());
		}

	}


	elm.append(template);

}

function shortText(id, type, elementId = null, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="short_text_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}

	}
	return `
<li class="list-group-item ui-state-default" id="shortText_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Short Text</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},1,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="short_text_${id}" placeholder="" value="${value}"/>
		${hiddenIDElement}
	</div>
	<div class="form-group my-0 py-0">
		<button class="btn btn-link btn-sm" type="button"
		onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
		add placeholder
		</button>
		<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
			<input type="text" class="form-control" name="short_text_placeholder_${id}"
			value="${placeholder}"
			placeholder="write something here"/>
		</div>
	</div>
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
		<input type="checkbox" name="ck_short_text_required_${id}" ${is_required}
		class="custom-switch-input">
		<span class="custom-switch-indicator"></span>
		<span class="custom-switch-description">Required</span>
		</label>
	</div>
	<input type="hidden" name="ck_short_text_history_${id}"
     class="custom-switch-input history">	
</li>
`;
}

function longText(id, type, elementId, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="long_text_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}

	}
	return `
<li class="list-group-item ui-state-default" id="longText_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Long Text</div>
		<div class="action">
		<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},2,${elementId})">
		<i class="fas fa-trash"></i>
		</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input  class="form-control" name="long_text_${id}" value="${value}"/> 
		${hiddenIDElement}
	</div>
	<div class="form-group my-0 py-0">
		<button class="btn btn-link btn-sm" type="button"
		onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
		add placeholder
		</button>
	<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
		<input type="text" class="form-control" name="long_text_placeholder_${id}"
		value="${placeholder}"
		   placeholder="write something here"/>
		</div>
	</div>
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
		<input type="checkbox" name="ck_long_text_required_${id}" ${is_required}
		class="custom-switch-input">
		<span class="custom-switch-indicator"></span>
		<span class="custom-switch-description">Required</span>
		</label>
	</div>
	
		<input type="hidden" name="ck_long_text_history_${id}"
		class="custom-switch-input history">
		
</li>`;
}

function dropDown(id, type, elementId, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="drop_down_id_${elementId}" value="${elementId}"/> `;
		id = elementId;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	let options = "";
	let optionsValue = "";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}
		if (data.options !== null) {
			let optionsList = data.options.split(",");
			let values = [];

			options = optionsList.map(i => {
				let token = i.split("|||");
				let helmValue = 'drop_down_option_value_' + elementId;
				let idName = "drop_down_item_" + token[0];
				values.push(token[1]);
				return `<li class="list-group-item" id="${idName}">
				<div class="custom-header p-0">
					<div class="title">${token[1]}</div>
					<div class="action">
						<button type="button" class="btn btn-link text-dark" onclick="deleteOptions('${idName}','${helmValue}','${token[1]}')">
						<i class="fas fa-times"></i>
						</button>
					</div>
	</div>
			</li>`
			}).join(" ");
			let newOptionObject = {
				name: `drop_down_option_value_${id}`,
				values: values
			}
			optionsValues.push(newOptionObject);
			optionsValue = values.join();

		}

	}
	return `
	<li class="list-group-item ui-state-default" id="dropDown_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Drop-down</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},3,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="drop_down_${id}" placeholder="" value="${value}"/>
		${hiddenIDElement}
	</div>
	<div class="form-group my-0 py-0">
		<button class="btn btn-link btn-sm" type="button"
		onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
		add placeholder
		</button>
		<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
			<input type="text" class="form-control" name="drop_down_placeholder_${id}"
			value="${placeholder}"
			  placeholder="write something here"/>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<div class="title">Drop-down Options</div>
		<div class="input-group mb-3">
			<input type="text" class="form-control"  id="drop_down_option_${id}" autocomplete="off">
			<div class="input-group-append">
				<button class="btn btn-primary" type="button" onclick="addOptions(${id},1)">
				Add
				</button>
			</div>
			<input type="hidden" id="drop_down_option_value_${id}" name="drop_down_option_value_${id}" value="${optionsValue}">
		</div>
		<ul class="list-group list-group-flush" id="drop_down_option_list_${id}">		
			${options}
		</ul>
	</div>
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
			<input type="checkbox" name="ck_drop_down_required_${id}" ${is_required}
			   class="custom-switch-input">
			<span class="custom-switch-indicator"></span>
			<span class="custom-switch-description">Required</span>
		</label>
	</div>
	<input type="hidden" name="ck_drop_down_history_${id}"
    class="custom-switch-input history">	
</li>
	`;
}

function fixquerydropdown(id, type, elementId, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="fixquery_drop_down_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	let options = "";

	let visible1 = "d-none";
	console.log(data);
	if (data != null) {

		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}


	}
	return `
	<li class="list-group-item ui-state-default" id="fixqueryDropDown_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Multiple Selection Drop-down with Fix query</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},10,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="fixquery_drop_down_${id}" placeholder="" value="${value}" />
		${hiddenIDElement}
	</div>
	<div class="form-group my-0 py-0">
		<button class="btn btn-link btn-sm" type="button"
		onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
		add placeholder
		</button>
		<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
			<input type="text" class="form-control" name="query_drop_down_placeholder_${id}"
			value="${placeholder}"
			  placeholder="write something here"/>
		</div>
	</div>
	
	<div class="form-group my-0 py-0">
		<div class="title">Select Table</div>
		<div class="input-group mb-3">
		<select id="fix_query_table_${id}" name="fix_query_table_${id}" class="form-control">
		<option>Select Table</option>
		</select>
			
		</div>
		<div class="form-group my-0 py-0">
		<div class="title">Select Key</div>
		<div class="input-group mb-3">
<select id="fix_query_key_${id}" name="fix_query_key_${id}" class="form-control">
		<option>Select Key</option>
		</select>			
			
		</div>
		<div class="form-group my-0 py-0">
		<div class="title">Select Value</div>
		<div class="input-group mb-3">
			
<select id="fix_query_value_${id}" name="fix_query_value_${id}" class="form-control">
		<option>Select Value</option>
		</select>			
			
		</div>
			<div class="form-group my-0 py-0">
		<div class="title">Select Where Column</div>
		<div class="input-group mb-3">
			
<select id="fix_query_wherecol_${id}" name="fix_query_wherecol_${id}" class="form-control">
		<option>Select Where Column</option>
		</select>			
			
		</div>
		<div class="form-group my-0 py-0">
		<div class="title">Enter where value</div>
		<div class="input-group mb-3">
			
<input type="text" class="form-control"id=" fix_query_whereval_${id}" name="fix_query_whereval_${id}">			
			
		</div>
		
	</div>
	
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
			<input type="checkbox" name="ck_fixquery_drop_down_required_${id}" ${is_required}
			   class="custom-switch-input">
			<span class="custom-switch-indicator"></span>
			<span class="custom-switch-description">Required</span>
		</label>
	</div>
	<input type="hidden" name="ck_fixquery_drop_down_history_${id}"
    class="custom-switch-input history">
	
</li>
	`;
}

function querydropdown(id, type, elementId, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="query_drop_down_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	let options = "";
	let custom_query = "";
	let dependancy = "";
	let ext_template_id = "";
	let is_extended_template = "";
	let visible1 = "d-none";
	console.log(data);
	if (data != null) {

		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}
		if (data.custom_query !== null) {
			custom_query = data.custom_query;

		}
		if (data.dependancy !== null) {
			dependancy = data.dependancy;
			visible1 = "";
		}

		if (parseInt(data.is_extended_template) === 1) {
			is_extended_template = "checked";
			get_all_template(id, data.ext_template_id);
		}


	}
	return `
	<li class="list-group-item ui-state-default" id="queryDropDown_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Multiple Selection Drop-down with query</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},8,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="query_drop_down_${id}" placeholder="" value="${value}" />
		${hiddenIDElement}
	</div>
	<div class="form-group my-0 py-0">
		<button class="btn btn-link btn-sm" type="button"
		onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
		add placeholder
		</button>
		<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
			<input type="text" class="form-control" name="query_drop_down_placeholder_${id}"
			value="${placeholder}"
			  placeholder="write something here"/>
		</div>
	</div>
	
	<div class="form-group my-0 py-0">
		<div class="title">Query for Drop-down</div>
		<div class="input-group mb-3">
			
			<textarea class="form-control" row="3" name="query_drop_down_option_${id}" id="query_drop_down_option_${id}">
			${custom_query}
			</textarea>
		</div>
		
	</div>
	<div class="form-group my-0 py-0">
		<button class="btn btn-link btn-sm" type="button"
		onclick="$('#txt_dependant_box_${id}').toggleClass('d-none')">
		is dependant?
		</button>
		<div class="form-group my-0 py-0 ${visible1}" id="txt_dependant_box_${id}">
			<input type="text" class="form-control" name="query_drop_down_dependant_${id}"
			value="${dependancy}"
			  placeholder="write dependancy name here"/>
		</div>
	</div>
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
			<input type="checkbox" onchange="$('#txt_template_box_${id}').toggleClass('d-none');get_all_template('${id}');" name="ck_query_drop_down_exttemp_${id}" ${is_extended_template}
			   class="custom-switch-input">
			<span class="custom-switch-indicator"></span>
			<span class="custom-switch-description">Extended Template</span>
		</label>
	</div>
	<div class="form-group my-0 py-0 ${visible1}" id="txt_template_box_${id}">
			<select class="form-control" name="slct_template_${id}" id="slct_template_${id}">
			<option value="-1">Select Template</option>
			</select>
		</div>
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
			<input type="checkbox" name="ck_query_drop_down_required_${id}" ${is_required}
			   class="custom-switch-input">
			<span class="custom-switch-indicator"></span>
			<span class="custom-switch-description">Required</span>
		</label>
	</div>
	<input type="hidden" name="ck_query_drop_down_history_${id}"
    class="custom-switch-input history">
	
</li>
	`;
}

function multipleDropDown(id, type, elementId, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="multi_drop_down_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	let options = "";
	let optionsValue = "";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}
		if (data.options !== null) {
			let optionsList = data.options.split(",");
			let values = [];
			options += optionsList.map(i => {
				let token = i.split("|||");
				let helmValue = 'multi_drop_down_option_value_' + elementId;
				let idName = "multi_item_" + token[0];
				values.push(token[1]);
				return `<li class="list-group-item" id="${idName}">
				<div class="custom-header p-0">
					<div class="title">${token[1]}</div>
					<div class="action">
						<button type="button" class="btn btn-link text-dark" onclick="deleteOptions('${idName}','${helmValue}','${token[1]}')">
						<i class="fas fa-times"></i>
						</button>
					</div>
	</div>	
			</li>`

			}).join(" ");
			let newOptionObject = {
				name: `multi_drop_down_option_value_${id}`,
				values: values
			}
			optionsValues.push(newOptionObject);
			optionsValue = values.join();
		}

	}
	return `
	<li class="list-group-item ui-state-default" id="multiDropDown_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Multiple Selection Drop-down</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},4,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="multi_drop_down_${id}" placeholder="" value="${value}" />
		${hiddenIDElement}
	</div>
	<div class="form-group my-0 py-0">
		<button class="btn btn-link btn-sm" type="button"
		onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
		add placeholder
		</button>
		<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
			<input type="text" class="form-control" name="multi_drop_down_placeholder_${id}"
			value="${placeholder}"
			  placeholder="write something here"/>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<div class="title">Drop-down Options</div>
		<div class="input-group mb-3">
			<input type="text" class="form-control"  id="multi_drop_down_option_${id}" autocomplete="off">
			<div class="input-group-append">
				<button class="btn btn-primary" type="button" onclick="addOptions(${id},2)">
				Add
				</button>
			</div>
			<input type="hidden" id="multi_drop_down_option_value_${id}" name="multi_drop_down_option_value_${id}" value="${optionsValue}">
		</div>
		<ul class="list-group list-group-flush" id="multi_drop_down_option_list_${id}">		
			${options}
		</ul>
	</div>
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
			<input type="checkbox" name="ck_multi_drop_down_required_${id}" ${is_required}
			   class="custom-switch-input">
			<span class="custom-switch-indicator"></span>
			<span class="custom-switch-description">Required</span>
		</label>
	</div>
	<input type="hidden" name="ck_multi_drop_down_history_${id}"
    class="custom-switch-input history">
	
</li>
	`;
}

function dateElement(id, type, elementId, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="date_text_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}

	}
	return `
	<li class="list-group-item ui-state-default" id="date_${id}"  data-type="${type}" data-id="${id}">
		<div class="custom-header">
			<div class="title">Date</div>
			<div class="action">
				<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},5,${elementId})">
				<i class="fas fa-trash"></i>
				</button>
			</div>
		</div>
		<div class="form-group my-0 py-0">
			<input type="text" class="form-control" name="date_element_${id}" placeholder="" value="${value}"/>
			${hiddenIDElement}
		</div>		
		<div class="form-group my-0 py-0">
			<button class="btn btn-link btn-sm" type="button"
			onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
			add placeholder
			</button>
			<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
				<input type="text" class="form-control" name="date_placeholder_${id}"
				  value="${placeholder}" placeholder="write something here"/>
			</div>
		</div>
		<div class="custom-control-inline">
			<label class="custom-switch mt-2">
				<input type="checkbox" name="ck_date_text_required_${id}" ${is_required}
				class="custom-switch-input">
				<span class="custom-switch-indicator"></span>
				<span class="custom-switch-description">Required</span>
			</label>
		</div>
		
			<input type="hidden" name="ck_date_text_history_${id}"
			class="custom-switch-input history">
			
	</li>
`;
}

function number(id, type, elementId = null, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="number_text_id_${elementId}" value="${elementId}"/> `;
	}

	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}

	}
	return `
	<li class="list-group-item ui-state-default" id="number_${id}"  data-type="${type}" data-id="${id}">
		<div class="custom-header">
			<div class="title">Number</div>
			<div class="action">
				<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},6,${elementId})">
				<i class="fas fa-trash"></i>
				</button>
			</div>
		</div>
		<div class="form-group my-0 py-0">
			<input type="text" class="form-control" name="number_element_${id}" placeholder="" value="${value}"/>
			${hiddenIDElement}
		</div>		
		<div class="form-group my-0 py-0">
			<button class="btn btn-link btn-sm" type="button"
			onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
			add placeholder
			</button>
			<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
				<input type="text" class="form-control" name="number_placeholder_${id}"
				value="${placeholder}"
				  placeholder="write something here"/>
			</div>
		</div>
		<div class="custom-control-inline">
			<label class="custom-switch mt-2">
				<input type="checkbox" name="ck_number_text_required_${id}" ${is_required}
				class="custom-switch-input">
				<span class="custom-switch-indicator"></span>
				<span class="custom-switch-description">Required</span>
			</label>
		</div>
		
			<input type="hidden" name="ck_number_text_history_${id}"
			class="custom-switch-input history">
			
	</li>
`;
}

function fixnumber(id, type, elementId = null, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="fixnumber_text_id_${elementId}" value="${elementId}"/> `;
	}

	let value = "";
	let is_required = "";
	let placeholder = "";
	let custom_query = "";
	let visible = "d-none";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}
		if (data.custom_query !== null) {
			custom_query = data.custom_query;
		}

	}
	return `
	<li class="list-group-item ui-state-default" id="fixnumber_${id}"  data-type="${type}" data-id="${id}">
		<div class="custom-header">
			<div class="title">Number</div>
			<div class="action">
				<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},9,${elementId})">
				<i class="fas fa-trash"></i>
				</button>
			</div>
		</div>
		<div class="form-group my-0 py-0">
			<input type="text" class="form-control" name="fixnumber_element_${id}" placeholder="" value="${value}"/>
			${hiddenIDElement}
		</div>		
		<div class="form-group my-0 py-0">
			<button class="btn btn-link btn-sm" type="button"
			onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
			add placeholder
			</button>
			<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
				<input type="text" class="form-control" name="fixnumber_placeholder_${id}"
				value="${placeholder}"
				  placeholder="write something here"/>
			</div>
		</div>
		<div class="input-group mb-3">
			
			<textarea class="form-control" row="3" name="fixnumber_drop_down_option_${id}" id="fixnumber_drop_down_option_${id}">
			${custom_query}
			</textarea>
		</div>
		<div class="custom-control-inline">
			<label class="custom-switch mt-2">
				<input type="checkbox" name="fixck_number_text_required_${id}" ${is_required}
				class="custom-switch-input">
				<span class="custom-switch-indicator"></span>
				<span class="custom-switch-description">Required</span>
			</label>
		</div>
		
			<input type="hidden" name="fixck_number_text_history_${id}"
			class="custom-switch-input history">
			
	</li>
`;
}

function attachment(id, type, elementId, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="attachment_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}

	}
	return `
	<li class="list-group-item ui-state-default" id="attachment_${id}"  data-type="${type}" data-id="${id}">
		<div class="custom-header">
			<div class="title">Attachment</div>
			<div class="action">
				<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},7,${elementId})">
				<i class="fas fa-trash"></i>
				</button>
			</div>
		</div>
		<div class="form-group my-0 py-0">
			<input type="text" class="form-control" name="attachment_element_${id}" placeholder="" value="${value}"/>
			${hiddenIDElement}
		</div>		
		<div class="form-group my-0 py-0">
			<button class="btn btn-link btn-sm" type="button"
			onclick="$('#txt_description_box_${id}').toggleClass('d-none')">
			add placeholder
			</button>
			<div class="form-group my-0 py-0 ${visible}" id="txt_description_box_${id}">
				<input type="text" class="form-control" name="attachment_placeholder_${id}"
				 value="${placeholder}" placeholder="write something here"/>
			</div>
		</div>
		<div class="custom-control-inline">
			<label class="custom-switch mt-2">
				<input type="checkbox" name="ck_attachment_text_required_${id}" ${is_required}
				class="custom-switch-input">
				<span class="custom-switch-indicator"></span>
				<span class="custom-switch-description">Required</span>
			</label>
		</div>
		
			<input type="hidden" name="ck_attachment_text_history_${id}"
			class="custom-switch-input history">
			
	</li>
`;
}

function get_all_template(id, id1 = "") {

	$.ajax({
		url: baseURL + "gettemplatelist",
		type: "POST",
		dataType: "json",
		success: function (result) {
			$('#slct_template_' + id).html("");
			if (result['status'] == 200) {
				$('#slct_template_' + id).html(result.data);
				if (id1 != "") {
					$('#slct_template_' + id).val(id1);
				}
			} else {
				$('#slct_template_' + id).html(result.data);
			}
		}, error: function (error) {

			console.log('Something went wrong please try again');
		}
	});
}

function label(id, type, elementId = null, data = null) {
	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="label_text_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let is_required = "";
	let placeholder = "";
	let visible = "d-none";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}

	}

	return `
<li class="list-group-item ui-state-default" id="label_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Label</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},11,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="label_${id}" placeholder="" value="${value}"/>		
		${hiddenIDElement}
	</div>		
</li>
`;
}

function radioGroup(id, type, elementId = null, data = null) {

	let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="radio_box_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let options = "";
	let optionsValue = "";
	let is_required = "";
	let placeholder = "";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}
		if (data.options !== null) {
			let optionsList = data.options.split(",");
			let values = [];

			options = optionsList.map(i => {
				let token = i.split("|||");
				let helmValue = 'radio_box_option_value_' + elementId;
				let idName = "radio_item_" + token[0];
				values.push(token[1]);
				return `<li class="list-group-item" id="${idName}">
				<div class="custom-header p-0">
					<div class="title">${token[1]}</div>
					<div class="action">
						<button type="button" class="btn btn-link text-dark" onclick="deleteOptions('${idName}','${helmValue}','${token[1]}')">
						<i class="fas fa-times"></i>
						</button>
					</div>
	</div>
			</li>`
			}).join(" ");
			let newOptionObject = {
				name: `radio_box_option_value_${id}`,
				values: values
			}
			optionsValues.push(newOptionObject);
			optionsValue = values.join();

		}

	}
	return `
<li class="list-group-item ui-state-default" id="radio_box_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Radio Group</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},13,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="radio_box_${id}" placeholder="" value="${value}"/>		
	</div>	
	<div class="form-group my-0 py-0">
		<div class="title">Radio Options</div>
		<div class="input-group mb-3">
			<input type="text" class="form-control"  id="radio_box_option_${id}" autocomplete="off">
			<div class="input-group-append">
				<button class="btn btn-primary" type="button" onclick="addGroupOption(${id},1)">
				Add
				</button>
			</div>
			<input type="hidden" id="radio_box_option_value_${id}" name="radio_box_option_value_${id}" value="${optionsValue}">
		${hiddenIDElement}
		</div>
		<ul class="list-group list-group-flush" id="radio_box_option_list_${id}">\t\t
		${options}
		</ul>
	</div>	
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
		<input type="checkbox" name="ck_radio_box_required_${id}" ${is_required}
		 class="custom-switch-input">
		<span class="custom-switch-indicator"></span>
		<span class="custom-switch-description">Required</span>
		</label>
	</div>
</li>
`;
}

function checkboxGroup(id, type, elementId = null, data = null) {
	console.log(elementId);
let hiddenIDElement = "";
	if (elementId != null) {
		hiddenIDElement = `<input type="hidden" name="check_box_id_${elementId}" value="${elementId}"/> `;
	}
	let value = "";
	let options = "";
	let optionsValue = "";
	let is_required = "";
	let placeholder = "";
	if (data != null) {
		value = data.name;
		if (parseInt(data.is_required) === 1) {
			is_required = "checked";
		}
		if (data.placeholder !== null) {
			placeholder = data.placeholder;
			visible = "";
		}
		if (data.options !== null) {
			let optionsList = data.options.split(",");
			let values = [];

			options = optionsList.map(i => {
				let token = i.split("|||");
				let helmValue = 'check_box_option_value_' + elementId;
				let idName = "checkbox_item_" + token[0];
				values.push(token[1]);
				return `<li class="list-group-item" id="${idName}">
				<div class="custom-header p-0">
					<div class="title">${token[1]}</div>
					<div class="action">
						<button type="button" class="btn btn-link text-dark" onclick="deleteOptions('${idName}','${helmValue}','${token[1]}')">
						<i class="fas fa-times"></i>
						</button>
					</div>
	</div>
			</li>`
			}).join(" ");
			let newOptionObject = {
				name: `check_box_option_value_${id}`,
				values: values
			}
			optionsValues.push(newOptionObject);
			optionsValue = values.join();

		}

	}
	return `
<li class="list-group-item ui-state-default" id="check_box_${id}"  data-type="${type}" data-id="${id}">
	<div class="custom-header">
		<div class="title">Check box Group</div>
		<div class="action">
			<button type="button" class="btn btn-link text-dark" onclick="deleteElement(${id},12,${elementId})">
			<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	<div class="form-group my-0 py-0">
		<input type="text" class="form-control" name="check_box_${id}" placeholder="" value="${value}"/>		
	</div>	
	<div class="form-group my-0 py-0">
	<div class="title">Check box Options</div>
		<div class="input-group mb-3">
			<input type="text" class="form-control"  id="check_box_option_${id}" autocomplete="off">
			<div class="input-group-append">
				<button class="btn btn-primary" type="button" onclick="addGroupOption(${id},2)">
				Add
				</button>
			</div>
			<input type="hidden" id="check_box_option_value_${id}" name="check_box_option_value_${id}" value="${optionsValue}">
			${hiddenIDElement}
		</div>
		<ul class="list-group list-group-flush" id="check_box_option_list_${id}">
		${options}
		</ul>
	</div>	
	<div class="custom-control-inline">
		<label class="custom-switch mt-2">
		<input type="checkbox" name="ck_check_box_required_${id}" ${is_required}
		 class="custom-switch-input">
		<span class="custom-switch-indicator"></span>
		<span class="custom-switch-description">Required</span>
		</label>
	</div>
</li>
`;
}

function addGroupOption(id, type) {
	let value;
	let length;
	let elm;
	let idName;
	let helm, helmValue = "";
	let objectIndex;
	if (type === 2) {
		idName = "check_box_item_";
		value = $(`#check_box_option_${id}`).val();
		elm = $(`#check_box_option_list_${id}`);
		helm = $(`#check_box_option_value_${id}`);
		helmValue = 'check_box_option_value_' + id;
		objectIndex = optionsValues.findIndex(i => {
			return i.name === 'check_box_option_value_' + id
		})
		length = elm.children().length;
		$(`#check_box_option_${id}`).val('');
	} else {
		idName = "radio_box_item_";
		helmValue = 'radio_box_option_value_' + id;
		value = $(`#radio_box_option_${id}`).val();
		elm = $(`#radio_box_option_list_${id}`)
		helm = $(`#radio_box_option_value_${id}`);
		objectIndex = optionsValues.findIndex(i => {
			return i.name === 'radio_box_option_value_' + id
		})
		length = elm.children().length;
		$(`#radio_box_option_${id}`).val('');
	}


	length++;
	let template = `<li class="list-group-item" id="${idName + length}">
				<div class="custom-header p-0">
					<div class="title">${value}</div>
					<div class="action">
						<button type="button" class="btn btn-link text-dark" onclick="deleteOptions('${idName + length}','${helmValue}','${value}')">
						<i class="fas fa-times"></i>
						</button>
					</div>
				</div>
			</li>`;
	if (objectIndex !== -1) {
		optionsValues[objectIndex].values.push(value);
		helm.val(optionsValues[objectIndex].values.join());
	} else {
		let newOptionObject = {
			name: helmValue,
			values: [value]
		}
		optionsValues.push(newOptionObject);
		let newObjectIndex = optionsValues.findIndex(i => {
			return i.name === helmValue
		})
		if (newObjectIndex !== -1) {
			helm.val(optionsValues[newObjectIndex].values.join());
		}

	}


	elm.append(template);

}

function loadNewSection(department_id)
{
	$("#doctor_form").trigger('reset');
	$("#section_id").val('');
	$("#elementSequenceType").val('');
	$("#elementSequenceId").val('');
	$("#questionListSortable").empty();
	document.getElementById("templateFornBtn").disabled = false;
}
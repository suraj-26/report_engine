var clone, before, parent;
let sortList;
$(document).ready(function () {
	let departmentID = localStorage.getItem("Department_id");
	let departmentName = localStorage.getItem("Department_name");

	$('#department_id').val(departmentID);
	$('#departmentName').html(departmentName);
	fetchSection(departmentID);
	GetallTable1();
	getAllsections();

	getQueryStringPara();

	$('#editor').trumbowyg({
		 btnsDef: {
	        // Create a new dropdown
	        image: {
	            dropdown: ['insertImage', 'upload', 'base64'],
	            ico: 'insertImage'
	        }
	    },
		btns: [
			['viewHTML'],
			['undo', 'redo'],
			['formatting'],
			['strong', 'em', 'del'],
			['fontfamily'],
			['btnGrp-semantic'],
			['superscript', 'subscript'],
			['link'],
			['image'],
			['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
			['unorderedList', 'orderedList'],
			['btnGrp-lists'],
			['foreColor', 'backColor'], // this isnt working right now
			['horizontalRule'],
			['removeformat'],
			['fullscreen'],
			['fontsize'],
			['table'],

		],
		plugins: {
			fontsize: {
				sizeList: [
					'12px',
					'14px',
					'18px',
					'22px'
				],
				allowCustomSize: false
			},
				 allowTagsFromPaste: {
	            allowedTags: ['h4', 'p', 'br','div']
	        },
	        resizimg: {
	            minSize: 64,
	            step: 16,
	        },
	         // Add imagur parameters to upload plugin for demo purposes
	        upload: {
	            serverPath: baseURL+'uploadTrumbowygImage',
	            fileFieldName: 'image[]',
	            urlPropertyName: 'data.link'
	        }
		},
		semantic:  {
	        'div': 'div', 
	        'lable':'lable'
	    },
	    hideButtonTexts: true,
	    imageWidthModalEdit: true,
	    urlProtocol: true
	});


	$("#htmlFormModalOp").on('show.bs.modal', function (e) {
		$("#addInputHere").empty();
	});
	$('#dropBox').parent().on('keydown', function (event) {

		if (event.which == 8) {
			s = window.getSelection();
			r = s.getRangeAt(0)
			el = r.startContainer.childNodes[0].parentElement;
			var pos='';
			if(el.classList.contains('spanLabel'))
			{
				pos=el.classList.contains('spanLabel');
			}else{
				el = r.startContainer.childNodes[0];
				pos=el.classList.contains('spanLabel');
			}
			// Check if the current element is the .label
			if (pos) {
				// Check if we are exactly at the end of the .label element
				if (r.startOffset == r.endOffset) {
					// prevent the default delete behavior
					
					event.preventDefault();
					if (el.classList.contains('highlight')) {
						// remove the element
						// console.log(el.outerHTML);
						var $str = $(el);
						var strhtml=$str.prop('innerHTML');
						// console.log(el.firstChild.id);

						
						var elemntIdField="#"+el.firstChild.id;
						// .replace('<div class="spanLabel highlight" data-tbw-flag="true">','').replace('</div>','');
						//let textNumberArray=[];
						// let textTypeArray=[];
						// let textArray=[];
						// for(var i=0;i<textMainInputArray.length;i++)
						// {
						// 	console.log(textMainInputArray[i]);
						// }
						// console.log(elemntIdField);
						// console.log(textMainInputArray);
						let elementIndex = textArray.findIndex(i => i.trim() === elemntIdField);
						// console.log(elementIndex);
						removeIndexValue(elementIndex);
						

						let elementDropdownIndex = dropDownArray.findIndex(i => i === elemntIdField);
						dropDownArray.splice(elementDropdownIndex, 1);


						el.remove();
					} else {
						el.classList.add('highlight');
					}
					return;
				}
			}
		}
		event.target.querySelectorAll('div.spanLabel.highlight').forEach(function(el) { el.classList.remove('highlight');})

	});


})

// sortList = $('#questionListSortable').sortable({
// 	connectWith: ".connected-sortable",
// 	tolerance: 'pointer',
// 	scroll: true,
// 	revert: true,
// 	receive: function (event, ui) {
// 		let typeElement = parseInt($(ui.item[0]).attr("data-type"));

// 		let id = Math.floor((Math.random() * 100) + 1);


// 		switch (typeElement) {
// 			case 1: // short text
// 				ui.item.replaceWith(shortText(id, 1));
// 				break;
// 			case 2: // long text
// 				ui.item.replaceWith(longText(id, 2));
// 				break;
// 			case 3: // drop down
// 				ui.item.replaceWith(dropDown(id, 3));
// 				break;
// 			case 4: // drop down
// 				ui.item.replaceWith(multipleDropDown(id, 4));
// 				break;
// 			case 5: // date element
// 				ui.item.replaceWith(dateElement(id, 5));
// 				break;
// 			case 6:
// 				ui.item.replaceWith(number(id, 6));
// 				break;
// 			case 7:
// 				ui.item.replaceWith(attachment(id, 7));
// 				break;
// 			case 8:
// 				ui.item.replaceWith(querydropdown(id, 8));
// 				break;
// 			case 9:
// 				ui.item.replaceWith(fixquerydropdown(id, 9));
// 				break;
// 			case 10:
// 				ui.item.replaceWith(fixnumber(id, 10));
// 				break;
// 			case 11:
// 				ui.item.replaceWith(label(id, 11));
// 				break;
// 			case 12:
// 				ui.item.replaceWith(checkboxGroup(id, 12));
// 				break;
// 			case 13:
// 				ui.item.replaceWith(radioGroup(id, 13));
// 				break;
// 			case 14:
// 				ui.item.replaceWith(button(id, 14));
// 				break;

// 		}

// 	},
// 	update: function (event, ui) {
// 		let typeSequence = $('#questionListSortable').sortable("toArray", {attribute: 'data-type'});
// 		let idSequence = $('#questionListSortable').sortable("toArray", {attribute: 'data-id'});
// 		$('#elementSequenceType').val(typeSequence.join())
// 		$('#elementSequenceId').val(idSequence.join())
// 	}
// }).disableSelection();

function removeIndexValue(elementIndex)
{
	if(elementIndex!=-1){
	textMainInputArray.splice(elementIndex, 1);
	

	let elementsText = $("#elementSequenceText").val();
	let elementTextArray = elementsText.split(",");
	
	elementTextArray.splice(elementIndex, 1);
	$("#elementSequenceText").val(elementTextArray.join(','));
	textArray.splice(elementIndex, 1);

	let elementsType = $("#elementSequenceType").val();
	let elementsTypeArray = elementsType.split(",");
	elementsTypeArray.splice(elementIndex, 1);
	$("#elementSequenceType").val(elementsTypeArray.join(','));
	textTypeArray.splice(elementIndex, 1);

	let elementsId = $("#elementSequenceId").val();
	let elementsIdArray = elementsId.split(",");
	elementsIdArray.splice(elementIndex, 1);
	$("#elementSequenceId").val(elementsIdArray.join(','));
	textNumberArray.splice(elementIndex, 1);
	}
}
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
	app.request(baseURL + "htmlform/fetch_section_details", formData).then(response => {
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
						case 14:
							items = button(i.id, 14, i.id, i);
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
	app.request(baseURL + "htmlform/deleteSection", formData).then(response => {
		if (response.status === 200) {
			app.successToast(response.body);
			fetchSection(department_id);
		} else {
			app.errorToast(response.body);
		}
	}).catch(error => {
		console.log("htmlform/deleteSection : ", error), app.errorToast("Something went wrong")
	})
}

function fetchSection(department_id) {

	let formData = new FormData();
	formData.set("department_id", department_id)
	app.request(baseURL + "htmlform/fetch_template_sections", formData).then(response => {
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
						<td class="btn-group" style="height:35px!important;">
							
							<a class="btn btn-primary btn-action" id="htmlFormModalBtn" data-toggle="modal" data-target="#htmlFormModal" onclick="get_addedSectionData(${s.id})"><i
							class="fas fa-pencil-alt"></i></a>
							<a class="btn btn-danger btn-acton trigger--fire-modal-1" 
							   onclick="deleteSection(${s.id},${s.department_id})"><i class="fas fa-trash"></i></a>
							<a class="btn btn-primary btn-acton " 
							   onclick="openParameterModal(${s.id},${s.department_id})" style="color:white;"><i class="fas fa-eye"></i></a>
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
function uploadSection1(form) {

	document.getElementById("templateFornBtn").disabled = true;
	let formData = new FormData(form);
	app.request("htmlform/save_template", formData).then(response => {
		if (response.status === 200) {
			console.log(response.body);
			app.successToast("save");
			// location.reload();
			loadNewSection(department_id);
			document.getElementById("templateFornBtn").disabled = false;
			fetchSection(localStorage.getItem("Department_id"));
			$("#htmlFormModal").modal('show');
			get_addedSectionData(response.section_id);
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
		case 14:
			$(`#button_${id}`).remove();
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

function shortText(id, type, elementId, data = null) {
	return `<input type="text" class="form-control" id="${elementId}" name="${elementId}" placeholder="#${elementId}" disabled="">`;
}

function longText(id, type, elementId, data = null) {

	return `<textarea name="${elementId}" id="${elementId}" class="form-control" placeholder="#${elementId}" disabled=""></textarea>`;
}

function dropDown(id, type, elementId, data = null) {
	return `<select name="${elementId}" id="${elementId}" class="form-control" disabled=""><option value="" selected="" disabled="">#${elementId}</option></select>`;
}

function fixquerydropdown(id, type, elementId, data = null) {
	return `<select name="${elementId}" id="${elementId}" class="form-control" disabled><option value="" selected disabled="">#${elementId}</option></select>`;
}

function querydropdown(id, type, elementId, data = null) {
	
	return `<select name="${elementId}" id="${elementId}" class="form-control" disabled=""><option value="" selected="" disabled="">#${elementId}</option></select>`;
}

function multipleDropDown(id, type, elementId, data = null) {

	return `<select name="${elementId}" id="${elementId}" class="form-control" disabled=""><option value="" selected="" disabled="">#${elementId}</option></select>`;
}

function dateElement(id, type, elementId, data = null) {
	
	return `<input type="date" name="${elementId}" disabled="" id="${elementId}" placeholder="${elementId}" class="form-control">#${elementId}`;
}

function number(id, type, elementId, data = null) {
	return `<input type="number" name="${elementId}" id="${elementId}" placeholder="#${elementId}" disabled="" class="form-control">`;
}
function button(id, type, elementId, data = null) {
	return `<input type="button" class="btn btn-primary" name="${elementId}" id="${elementId}" value="Form Button #${elementId}" disabled="">`;
}
function excelbutton(id, type, elementId, data = null) {
	
	return `<input type="button" class="btn btn-primary" name="${elementId}" id="${elementId}" value="Excel Button #${elementId}" disabled="">`;
}

function pdfbutton(id, type, elementId, data = null) {
	return `<input type="button" class="btn btn-primary" name="${elementId}" id="${elementId}" value="Pdf Button #${elementId}" disabled="">`;
}
function datatablebutton(id, type, elementId, data = null) {
	return `<input type="button" class="btn btn-primary" name="${elementId}" id="${elementId}" value="Datatable #${elementId}" disabled="">`;
}
function csvbutton(id, type, elementId, data = null) {
	return `<input type="button" class="btn btn-primary" name="${elementId}" id="${elementId}" value="CSV Button #${elementId}" disabled="">`;
}
function datatable_reportbutton(id, type, elementId, data = null) {
	return `<input type="button" class="btn btn-primary" name="${elementId}" id="${elementId}" value="Datatable Report #${elementId}" disabled="">`;
}


function fixnumber(id, type, elementId = null, data = null) {
	return `<input type="number" name="${elementId}" id="${elementId}" disabled="" placeholder="#${elementId}" class="form-control">
`;
}

function attachment(id, type, elementId, data = null) {
	
	return `<input type="file" name="${elementId}" id="${elementId}" disabled="" placeholder="#${elementId}" class="form-control">#${elementId}`;
}

function label(id, type, elementId = null, data = null) {
	return `<label>${elementId}<label>`;
}

function radioGroup(id, type, elementId, data = null) {

	return `${elementId}<input type="radio" name="${elementId}" id="${elementId}" disabled="" class="">`;
}

function checkboxGroup(id, type, elementId, data = null) {
	
	return `${elementId}<input type="checkbox" name="${elementId}" id="${elementId}" disabled="" class="">`;
}
function row_section(id, type, elementId, data = null)
{
	return `<div class="div_display" id="${elementId}"></div>`;
}
function div_section(id, type, elementId, data = null)
{
	return `<div class="div_display" id="${elementId}"></div>`;
}

function get_all_template(id, id1 = "") {

	$.ajax({
		url: baseURL + "htmlform/getFreeFormtemplatelist",
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
let textMainInputArray=[];
let historyTableButton;
let primaryTableColumnArray=[];
let queryStringParaArray;
let requiredFieldArray=[];
let dropdownOptionsArray=[];
let querydropdownOptionsArray=[];
let textNumberArray=[];
let textTypeArray=[];
let textArray=[];
let dropDownArray=[];
let tableDataArray;
function insertAtCaret(areaId, text,type) {
	var txtarea = document.getElementById(areaId);
	if (!txtarea) {
		return;
	}
	var textNo=Math.floor((Math.random() * 100) + 1);
	if(textNumberArray.length>0)
	{
		for(var i=0;i<textNumberArray.length;i++)
		{
			if(textTypeArray[i]==type && textNumberArray[i]==textNo)
			{
				var textNo=Math.floor((Math.random() * 100) + 1);
			}
		}
	}
	var textName=text+textNo;

	if(type!=21 && type!=22 && type!=11){
	textTypeArray.push(type);
	textNumberArray.push(textNo);

	$('#elementSequenceType').val(textTypeArray.join());
	$('#elementSequenceId').val(textNumberArray.join());

	textArray.push(textName);

	if(type==3 || type==4 || type==12 || type==13)
	{
		dropDownArray.push({type:type,text:textName,value:''});
	}
	if(type==18)
	{
		historyTableButton=textName;
	}
	$('#elementSequenceText').val(textArray.join());
}


	let v_data='';
	textName = textName.replace("#","");
	let items = "";
	switch (type) {
		case "1": // short text
			items = shortText(textNo, 1, textName,v_data);
			break;
		case "2": // long text
			items = longText(textNo, 2, textName, v_data);
			break;
		case "3": // drop down
			items = dropDown(textNo, 3, textName, v_data);
			break;
		case "4": // drop down
			items = multipleDropDown(textNo, 4, textName, v_data);
			break;
		case "5": // date element
			items = dateElement(textNo, 5, textName, v_data);
			break;
		case "6":
			items = number(textNo, 6, textName, v_data);
			break;
		case "7":
			items = attachment(textNo, 7, textName, v_data);
			break;
		case "8":
			items = querydropdown(textNo, 8, textName, v_data);
			break;
		case "9":
			items = fixquerydropdown(textNo, 9, textName, v_data);
			break;
		case "10":
			items = fixnumber(textNo, 10, textName, v_data);
			break;
		case "11":
			items = label(textNo, 11, textName, v_data);
			break;
		case "12":
			items = checkboxGroup(textNo, 12, textName, v_data);
			break;
		case "13":
			items = radioGroup(textNo, 13, textName, v_data);
			break;
		case "14":
			items = button(textNo, 14, textName, v_data);
			break;
		case "15":
			items = excelbutton(textNo, 15, textName, v_data);
			break;
		case "16":
			items = pdfbutton(textNo, 16, textName, v_data);
			break;
		case "17":
			items = datatablebutton(textNo, 17, textName, v_data);
			break;
		case "19":
			items = csvbutton(textNo, 19, textName, v_data);
			break;
		case "20":
			items = datatable_reportbutton(textNo, 20, textName, v_data);
			break;
		case "21":
			items = row_section(textNo, 21, textName, v_data);
			break;
		case "22":
			items = div_section(textNo, 22, textName, v_data);
			break;
	}
	// items = row_section(textNo, 21, textName, v_data);
	
	
	if(type!=21 && type!=22 && type!=11)
	{
		textMainInputArray.push(items);
		
	}
	items='<div class="spanLabel">'+items+'</div>';

	// if(type!=18){
	// 	$('#editor').trumbowyg('execCmd', {
	// 		cmd: 'insertHtml',
	// 		param: textName2,
	// 		forceCss: false,
	// 	});
		
	// }
	// $(".spanLabel").draggable();
	return items;


}

function storeColumnViewValue(value,id)
{
	$("#"+id).val(value);
	$('#primary_table').val('');
	$('#primary_table').select2({allowClear: true});
	// $('#editor').trumbowyg('html', '');
	$('#dropBox').html('');
}
function loadNewSection(department_id)
{
	dropdownOptionsArray=[];
	textNumberArray=[];
	textTypeArray=[];
	textArray=[];
	dropDownArray=[];

	$("#doctor_form").trigger('reset');
	$("#section_id").val('');
	$("#elementSequenceType").val('');
	$("#elementSequenceId").val('');
	$("#questionListSortable").empty();
	// $('#editor').trumbowyg('html','');
	$('#dropBox').html('');
	$("#htmlFormModalData").empty();
	$("#edit_buttons").empty();


	document.getElementById("templateFornBtn").disabled = false;
}

function updateHistory(){
	if($('#history_unabled').is(':checked')){
		insertAtCaret('dropBox', '#datatable_button_',18);
	}
	else
	{
		let elementsType = $("#elementSequenceType").val();
		let elementsTypeArray = elementsType.split(",");
		let elementID=18;
		let elementIndex = elementsTypeArray.findIndex(i => parseInt(i) === parseInt(elementID));
		removeIndexValue(elementIndex);
	}
}

function get_addedSectionData(section_id)
{
	$("#htmlFormModalData").empty();

	// $('#editor').trumbowyg('html', '');
	$('#dropBox').html('');
	$("#htmlSectionId").val(section_id);
	let formData = new FormData();
	formData.set("section_id", section_id);
	app.request(baseURL + "htmlform/fetch_template_sectionsHtml", formData).then(response => {
		if (response.status === 200) {
			dropdownOptionsArray=[];
			textNumberArray=[];
			textTypeArray=[];
			textArray=[];
			dropDownArray=[];
			textMainInputArray=[];
			var userdata=response.data;
			$("#htmlSectionId").val(section_id);

			// $('#editor').trumbowyg('html', userdata.raw_html);
			$('#dropBox').append(userdata.raw_html);
			$( ".div_drag" ).draggable({containment: "parent"});
			 $( ".div_drag" ).resizable({containment: "parent",
			 minHeight: 50});
			// $("#htmlFormModalData").html(userdata.section_html);
			$("#section_name").val(userdata.name);
			$("#section_id").val(userdata.id);
			$("#form_type").val(userdata.form_type);
		
			$("#colorPicker").val(userdata.card_color);
			$("#colorCardBodyPicker").val(userdata.card_body_color);
			$("#colorCardBodyTextPicker").val(userdata.card_body_text_color);
			
			// console.log(userdata.primary_table);
			if(userdata.form_type!=1)
			{
				document.getElementById('TableDivDisplay').style.display="block";
				$("#primary_table option[value='"+userdata.primary_table+"']").prop("selected", true);
				$("#primary_table").select2();
			}
			else
			{
				document.getElementById('TableDivDisplay').style.display="none";
			}
			if(userdata.form_type==3){
				$("#div_dependantSection").show();
					$("#depend_section_id").val(userdata.dependant_section_id);
					
			}

			$("#department_id").val(userdata.department_id);
			// console.log(userdata.history_unabled);
			if(userdata.history_unabled == 1){
				$('#history_unabled').prop('checked', true);

			}
			var str=userdata.query_string_parameter;
			var arr=str.split(",");
			$("#QueryStringParameter").select2();
			var str2=[];
			for(i=0;i<arr.length;i++){
				str2.push(arr[i]);
			}
			$("#QueryStringParameter").val(str2).trigger("change");

			if(userdata.html_section_types!=null)
			{
				textTypeArray=userdata.html_section_types.split(',');
				$("#elementSequenceType").val(userdata.html_section_types);
			}
			if(userdata.html_section_mainInput!=null)
			{
				textMainInputArray=userdata.html_section_mainInput.split(',');
			}
			console.log(textMainInputArray);
			if(userdata.html_section_ids!=null)
			{
				textNumberArray=userdata.html_section_ids.split(',');
				$("#elementSequenceId").val(userdata.html_section_ids);
			}
			if(userdata.html_section_text!=null)
			{
				textArray=userdata.html_section_text.split(',');
				$("#elementSequenceText").val(userdata.html_section_text);
			}

			var design=``;
			var typeArray=userdata.html_section_types;
			var typearr = typeArray.split(",");
			var textArraystr=userdata.html_section_text;
			var textarr = textArraystr.split(",");
			if(typearr.length>0)
			{
				for(var i=0;i<typearr.length;i++)
				{
					if(typearr[i]==14)
					{

						design+=`<button type="button" class="btn btn-primary m-2" 
						onclick="getButtonModal('${userdata.id}','${userdata.department_id}',
						'${textarr[i]}',${typearr[i]},'${userdata.html_section_text}')">`+textarr[i]+`</button>`;
					}
					if(typearr[i]==8)
					{
						design+=`<button type="button" class="btn btn-primary m-2" 
						onclick="getQueryModal('${userdata.id}','${userdata.department_id}',
						'${textarr[i]}',${typearr[i]},'${userdata.html_section_text}')">`+textarr[i]+`</button>`;
						// get_queryAjax(userdata.id,userdata.department_id,textarr[i],typearr[i]);
					}
					if(typearr[i]==15 || typearr[i]==16 )
					{
						design+=`<button type="button" class="btn btn-primary m-2" 
						onclick="getExcelPdfModal('${userdata.id}','${userdata.department_id}',
						'${textarr[i]}',${typearr[i]},'${userdata.html_section_text}')">`+textarr[i]+`</button>`;
					}
					if(typearr[i]==17 || typearr[i]==18)
					{
						design+=`<button type="button" class="btn btn-primary m-2" 
						onclick="getDatatableModal('${userdata.id}','${userdata.department_id}',
						'${textarr[i]}',${typearr[i]},'${userdata.html_section_text}')">`+textarr[i]+`</button>`;
					}
					if(typearr[i]==19)
					{
						design+=`<button type="button" class="btn btn-primary m-2" 
						onclick="getExcelPdfModal('${userdata.id}','${userdata.department_id}',
						'${textarr[i]}',${typearr[i]},'${userdata.html_section_text}')">`+textarr[i]+`</button>`;
					}
					if(typearr[i]==20)
					{
						design+=`<button type="button" class="btn btn-primary m-2" 
						onclick="getExcelPdfModal('${userdata.id}','${userdata.department_id}',
						'${textarr[i]}',${typearr[i]},'${userdata.html_section_text}')">`+textarr[i]+`</button>`;
					}

				}

			}

			$("#edit_buttons").html(design);
			if(userdata.opt!=null)
			{
				var mystr=userdata.opt;
				var myarr = mystr.split("@");
				for (var i = 0; i < myarr.length; i++) {
					var myop = myarr[i].split("||");
					if(myop.length>2)
					{
						dropDownArray.push({type:myop[2],text:myop[1],value:myop[0]});
					}

				}
			}
			if(userdata.req!=null)
			{
				var reqstr=userdata.req;
				var reqarr = reqstr.split("@");
				for (var i = 0; i < reqarr.length; i++) {
					var reqop = reqarr[i].split("||");
					if(reqop.length>5)
					{
						requiredFieldArray.push({id:reqop[0],type:reqop[1],is_req:reqop[2],default_val:reqop[3],
							min_val:reqop[4],max_val:reqop[5],placeholder:reqop[6]});
					}

				}
			}

			console.log(dropDownArray);
			// app.confirmationBox();
		} else {
			app.errorToast(response.body);
		}

	}).catch(error => {
		console.log("get_addedSectionData : ", error);
		app.errorToast("Something went wrong")
	})
}
function get_queryAjax(section_id,department_id,id,type)
{
	let formData = new FormData();
	formData.set("department_id", department_id);
	formData.set("section_id", section_id);
	formData.set("field_id", id);
	formData.set("field_type", type);
	app.request(baseURL + "htmlform/getQueryDropdownAjax", formData).then(response => {
		if (response.status === 200) {
			// app.successToast(response.body);
			// get_addedSectionData(section_id);
			// console.log(response.data);
			$("#htmlFormModalData").append(response.data);
			// app.confirmationBox();
		} else {
			// app.errorToast(response.body);
		}

	}).catch(error => {
		console.log("saveDesignWindow : ", error);
		app.errorToast("Something went wrong")
	})
}
function getDesignWindow()
{
	$("#htmlFormModalData").toggle('d-none');
	$("#htmlFormModalDatatrumbyog").toggle('d-none');
}
function saveDesignWindow()
{
	// var editor_data=$("#editor").val();
	var editor_data=$("#dropBox").html();
	var section_id=$("#htmlSectionId").val();
	let formData = new FormData();
	formData.set("editor_data", editor_data);
	formData.set("section_id", section_id);
	app.request(baseURL + "htmlform/save_template_sectionsHtml", formData).then(response => {
		if (response.status === 200) {
			app.successToast(response.body);
			get_addedSectionData(section_id);
			// app.confirmationBox();
		} else {
			app.errorToast(response.body);
		}

	}).catch(error => {
		console.log("saveDesignWindow : ", error);
		app.errorToast("Something went wrong")
	})
}
// save form
function uploadSection(form) {
	if(dropDownArray.length>0){
//modal open
		$("#htmlFormModalOpBtn").click();
		get_inputBoxForDropdown(dropDownArray);
		required_field();

	}else{
		$("#htmlFormModalOpBtn").click();
		get_inputBoxForDropdown(dropDownArray);
		required_field();
		// let formData = new FormData(form);
		// uploadSectionData(formData,1);
	}

}

function uploadSectionData(formData,type)
{
	// document.getElementById("templateFornBtn").disabled = true;
	app.request("htmlform/save_template_sectionsHtml", formData).then(response => {
		if (response.status === 200) {
			console.log(response.body);
			app.successToast("save");
			// location.reload();
			loadNewSection(department_id);
			// document.getElementById("templateFornBtn").disabled = false;
			fetchSection(localStorage.getItem("Department_id"));
			// get_addedSectionData(response.section_id);
			if(type==2)
			{
				$("#htmlFormModalOpBtn").click();
			}
		} else {
			app.errorToast(response.body);
			// document.getElementById("templateFornBtn").disabled = false;
		}
	});
}

function get_inputBoxForDropdown(dropDownArray)
{

	for(var i=0;i<dropDownArray.length;i++)
	{
		var data=dropDownArray[i].text;
		var data_id=data.split("#");
		if(dropDownArray[i].type==14)
		{
			var options=get_buttonQueryData(data_id,textArray);
		}
			// else if(dropDownArray[i].type==8)
			// {
			// 	var options=get_queryDropdownData(dropDownArray[i],textArray);
		// }
		else
		{
			var options=`<div class="row">
			<div class="title col-md-3 mt-2"> ${dropDownArray[i].text} options</div>
			<div class=" col-md-9 form-group my-0 py-0 mt-2">
				<input type="text" class="form-control" name="option_${data_id[1]}" 
				id="option_${data_id[1]}" placeholder="add options comma(,) seperated" value="${dropDownArray[i].value}"/>
			</div>
			</div>
			`;
		}

		$("#addInputHere").append(options);
	}


}
function required_field()
{
	// console.log(requiredFieldArray);
	var template=`<div class="mt-4">`;
	template+=`<div class="row">
				<div class="col-md-3"><h6>Field Name</h6></div>
				<div class="col-md-1"><h6>Is Required</h6></div>
				<div class="col-md-2"><h6>Default value</h6></div>
				<div class="col-md-2"><h6>Min value</h6></div>
				<div class="col-md-2"><h6>Max value</h6></div>
				<div class="col-md-2"><h6>Placeholder</h6></div>
				</div>`;
	for(var i=0;i<textTypeArray.length;i++)
	{
		if(textTypeArray[i]!=14 && textTypeArray[i]!=15 && textTypeArray[i]!=16 && textTypeArray[i]!=17 && textTypeArray[i]!=18  && textTypeArray[i]!=19 && textTypeArray[i]!=20)
		{
			var checked="";
			var value1="off";
			var value="";
			var min_value="";
			var max_value="";
			var date_value="";
			var placeholder_value="";
			if(requiredFieldArray.length>0)
			{
				for(var j=0;j<requiredFieldArray.length;j++){

					if(requiredFieldArray[j]['id']==textArray[i])
					{
						if(requiredFieldArray[j]['is_req']==1)
						{
							checked="checked";
							value1="on";
						}
						if(requiredFieldArray[j]['type']==5)
						{
							var date_split=requiredFieldArray[j]['default_val'].split(',');
							// console.log(date_split);
							if(date_split.length>1)
							{
								date_value=date_split[0];
								value=date_split[1];
							}

						}
						else
						{
							value=requiredFieldArray[j]['default_val'];
						}

						min_value=requiredFieldArray[j]['min_val'];
						max_value=requiredFieldArray[j]['max_val'];
						placeholder_value=requiredFieldArray[j]['placeholder'];
						break;
					}
				}
			}
			template+=`<div class="row">`;
			template+=`<div class="title col-md-3 mt-3"> ${textArray[i]}</div>
						<div class="col-md-1 form-group my-0 py-0 mt-2">
							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="ck_required_${textArray[i]}"
									   class="custom-switch-input" id="ck_required_${textArray[i]}" value="${value1}" ${checked}
									    onchange="get_ck_required(this)">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description"></span>
								</label>
							</div>
						</div>
					`;
			if(textTypeArray[i]==1 || textTypeArray[i]==2 || textTypeArray[i]==3 || textTypeArray[i]==4 || textTypeArray[i]==12 || textTypeArray[i]==13)
			{
				template+=`<div class="col-md-2 form-group my-0 py-0 mt-1">
						<input type="text" class="form-control" name="df_v_${textArray[i]}" id="df_v_${textArray[i]}" value="${value}"/>
					   </div>
					   <div class="col-md-2 form-group my-0 py-0 mt-1">
					   </div>
					    <div class="col-md-2 form-group my-0 py-0 mt-1">
					   </div>
					   `;
			}
			if(textTypeArray[i]==6)
			{
				template+=`<div class="col-md-2 form-group my-0 py-0 mt-1">
						<input type="number" class="form-control" name="df_v_${textArray[i]}" id="df_v_${textArray[i]}" value="${value}"/>
					   </div>
					   <div class="col-md-2 form-group my-0 py-0 mt-1">
						<input type="number" class="form-control" name="df_minv_${textArray[i]}" id="df_minv_${textArray[i]}" value="${min_value}"/>
					   </div>
					   <div class="col-md-2 form-group my-0 py-0 mt-1">
						<input type="number" class="form-control" name="df_maxv_${textArray[i]}" id="df_maxv_${textArray[i]}" value="${max_value}"/>
					   </div>`;
			}
			if(textTypeArray[i]==5)
			{
				template+=`<div class="col-md-2 form-group my-0 py-0 mt-1">
						<select class="form-control date_select_${textArray[i]}" name="date_select_${textArray[i]}" id="date_select_${textArray[i]}" onchange="getChangeDate(this.value,'${textArray[i]}')">
						`;
				var style='style="display:none"';
				if(date_value!=2 && date_value!=3){
					template+=`	<option value="1" selected>select Date Option</option>
										<option value="2">Today</option>
										<option value="3">Specific</option>`;
				}
				else if(date_value==2)
				{
					template+=`	<option value="1">select Date Option</option>
							<option value="2" selected>Today</option>
							<option value="3">Specific</option>`;
				}else if(date_value==3)
				{
					style='style="display:block"';
					template+=`
							<option value="1">select Date Option</option>
							<option value="2">Today</option>
							<option value="3" selected>Specific</option>`;
				}
				template+=`	</select>
						<input type="date" class="form-control" `+style+` name="df_v_${textArray[i]}" id="df_v_${textArray[i]}" value="${value}"/>
					   </div>
					   <div class="col-md-2 form-group my-0 py-0 mt-1">
						<input type="date" class="form-control" name="df_minv_${textArray[i]}" id="df_minv_${textArray[i]}" value="${min_value}"/>
					   </div>
					   <div class="col-md-2 form-group my-0 py-0 mt-1">
						<input type="date" class="form-control" name="df_maxv_${textArray[i]}" id="df_maxv_${textArray[i]}" value="${max_value}"/>
					   </div>`;

			}
			template+=`<div class="col-md-2 form-group my-0 py-0 mt-1">
						<input type="text" class="form-control" name="df_placeholder_${textArray[i]}" id="df_placeholder_${textArray[i]}" value="${placeholder_value}"/>
					   </div>
					   `;
			template+=`</div>`;
		}
	}
	template+=`</div>`;
	$("#addInputHereRequiredHere").html(template);
}
function getChangeDate(value,id)
{
	var div_id="df_v_"+id;
	if(value==3)
	{
		document.getElementById(div_id).style.display = 'block';
	}
	else
	{
		document.getElementById(div_id).style.display = 'none';
	}
}
function getformTypeSection(value)
{
	if(value==1)
	{
		document.getElementById('TableDivDisplay').style.display = 'none';
		if($('#history_unabled').is(':checked')){
			$('#history_unabled').click();
		}
	}
	else
	{
		document.getElementById('TableDivDisplay').style.display = 'block';
		if(value==2)
		{
			if($('#history_unabled').is(':checked')){

			}
			else
			{
				$('#history_unabled').click();
			}
		}
		
		if(value == 3){
			if($('#history_unabled').is(':checked')){
				$('#history_unabled').click();
			}
			$("#div_dependantSection").show();
			getAllsections();
		}
	}
}

function getAllsections(){
	var department_id=$("#department_id").val();
	$.ajax({
		type: "POST",
		url: baseURL+"GetallSections",
		dataType: "json",
		data:{department_id},
		success: function (result) {

			$("#depend_section_id").html(result.data);
			// listColumns=result.data;

		}, error: function (error) {

		}
	});
}
function get_ck_required(e)
{
	if ($(e).is(":checked")) {
		$(e).val("on");
	}
	else
	{
		$(e).val("off");
	}
}

function addFormDesignOptions()
{
	if(dropDownArray.length>0)
	{
		for(var i=0;i<dropDownArray.length;i++)
		{
			var data=dropDownArray[i].text;
			var data_id=data.split("#");
			if(dropDownArray[i].type==3 || dropDownArray[i].type==4 || dropDownArray[i].type==12 || dropDownArray[i].type==13)
			{

				// console.log(dropDownArray[i]);
				var opid="#option_"+data_id[1];
				var opt=$(opid).val();
				// console.log(opt);
				var id=dropDownArray[i].text;
				var newElement = {};
				newElement['id'] = id;
				newElement['options'] = opt;
				newElement['type'] = dropDownArray[i].type;
				dropdownOptionsArray.push(newElement);
			}
		}
	}
	let isRequiredArray=[];
	if(textArray.length>0)
	{
		for(var i=0;i<textArray.length;i++)
		{
			if(textTypeArray[i]!=14 && textTypeArray[i]!=15 && textTypeArray[i]!=16 && textTypeArray[i]!=17 && textTypeArray[i]!=18 && textTypeArray[i]!=19 && textTypeArray[i]!=20){
				var chek_re="ck_required_"+textArray[i];
				var chek_re1=document.getElementById(chek_re).value;
				var opt=0;
				if(chek_re1=='on'){
					opt=1;
				}
				var df_v_ele="";
				var df_minv_ele="";
				var df_maxv_ele="";
				var df_placeholder_ele="";
				if(textTypeArray[i]==1 || textTypeArray[i]==2 || textTypeArray[i]==3 || textTypeArray[i]==4
					|| textTypeArray[i]==5 ||textTypeArray[i]==6 || textTypeArray[i]==12 ||textTypeArray[i]==13)
				{
					if(textTypeArray[i]==5)
					{
						var date_v="date_select_"+textArray[i];
						var date_v_ele=document.getElementById(date_v).value;
						var df_v="df_v_"+textArray[i];
						var df_v_e=document.getElementById(df_v).value;
						var df_v_ele=date_v_ele+','+df_v_e;
						console.log(date_v_ele);
					}
					else
					{
						var df_v="df_v_"+textArray[i];
						var df_v_ele=document.getElementById(df_v).value;
					}
					var df_placeholder="df_placeholder_"+textArray[i];
					var df_placeholder_ele=document.getElementById(df_placeholder).value;
				}


				if(textTypeArray[i]==5 ||textTypeArray[i]==6)
				{
					var df_minv="df_minv_"+textArray[i];
					var df_minv_ele=document.getElementById(df_minv).value;
					var df_maxv="df_maxv_"+textArray[i];
					var df_maxv_ele=document.getElementById(df_maxv).value;
				}

				// console.log(chek_re1);
				var newElement1 = {};
				newElement1['id'] = textArray[i];
				newElement1['is_req'] = opt;
				newElement1['type'] = textTypeArray[i];
				newElement1['default_val'] = df_v_ele;
				newElement1['min_val'] = df_minv_ele;
				newElement1['max_val'] = df_maxv_ele;
				newElement1['placeholder'] = df_placeholder_ele;
				isRequiredArray.push(newElement1);
			}
		}
	}
	console.log(isRequiredArray);
	var form =document.getElementById('doctor_form');
	var queryString=$("#queryString").val();
	var finalCount=$("#finalCount").val();
	var ButtonInsertID=$("#ButtonInsertID").val();
	var colorPicker=$("#colorPicker").val();
	var colorCardBodyPicker=$("#colorCardBodyPicker").val();
	var colorCardBodyTextPicker=$("#colorCardBodyTextPicker").val();
	// console.log(colorPicker);
	let formData = new FormData(form);

	formData.set("textMainInputArray",textMainInputArray);
	formData.set("dropDownOptions",JSON.stringify(dropdownOptionsArray));
	formData.set("isRequired",JSON.stringify(isRequiredArray));

	formData.set("primaryTableColumnArray",primaryTableColumnArray);

	formData.set("historyTableButton",historyTableButton);
	formData.set("editor",$("#dropBox").html());
	// var circularquery=CircularJSON.stringify(querydropdownOptionsArray);
	// var circularquerystr= JSON.parse(circular);


	formData.set("queryString",JSON.stringify(queryString));
	formData.set("finalCount",finalCount);
	formData.set("ButtonInsertID",ButtonInsertID);
	formData.set("colorPicker",colorPicker);
	formData.set("colorCardBodyPicker",colorCardBodyPicker);
	formData.set("colorCardBodyTextPicker",colorCardBodyTextPicker);

	uploadSectionData(formData,2);

}
let opdata;
let hashopt;
let listColumns;
function get_queryDropdownData(dropdownArr_index,hashtextarray)
{
	var data=dropdownArr_index.text;
	var data_id=data.split("#");
	// console.log(data);

	opdata=getSelectTableOptions(data_id[1]);
	hashopt=getSelectHashOptions(hashtextarray);
	// console.log(opdata);
	var options=`<div class="">
			<div class="mt-2"> <strong>${dropdownArr_index.text} options</strong> 
			<a type="button" class="btn btn-link ml-2 btn_class" onclick="getHideDiv('form_through_${data_id[1]}','mormal_query_${data_id[1]}')">form through</a>
			<a type="button" class="btn btn-link ml-2 btn_class" onclick="getHideDiv('mormal_query_${data_id[1]}','form_through_${data_id[1]}',)">normal query</a>
			</div>
			<div class="mt-2" id="mormal_query_${data_id[1]}" style="display:none">
				<label>Query : </label><textarea class="form-control" name="textquery_${data_id[1]}" id="textquery_${data_id[1]}"></textarea>
			</div>
			<div class="mt-2" id="form_through_${data_id[1]}">
			<div>
				From : <select name="select_${data_id[1]}" id="select_${data_id[1]}" class="selectTable" onchange="getListOfColumns1(this.value,'tableColumns')">
				${opdata}</select>
			</div>
			<div id="dropdownQueryyData_${data_id[1]}">
			</div>
			<div class="mt-2">
				select value: <select name="key_${data_id[1]}" id="key_${data_id[1]}" class="tableColumns">
				</select>
				select name: <select name="name_${data_id[1]}" id="name_${data_id[1]}" class="tableColumns">
				</select>
			</div>
			<div class="mt-2">
				<input type="hidden" value="1" name="wherecount_${data_id[1]}" id="wherecount_${data_id[1]}" class="form-control-field">
			
				<label>where column: </label><select name="column1_${data_id[1]}" id="column1_${data_id[1]}" class="tableColumns">
				</select>
				<label>dependancy:</label> <select name="value1_${data_id[1]}" id="value1_${data_id[1]}" class="">
				${hashopt}</select> 
				<input type="text" name="text1_${data_id[1]}" id="text1_${data_id[1]}" placeholder="Enter value here" class="form-control-field" />
			</div>
			<div id="whereIds_${data_id[1]}">
			</div>
			<button type="button" name="add_where" id="add_where" onclick="getWhereColumns('${data_id[1]}','${hashtextarray}')" class="btn btn-primary">
			<i class="fa fa-plus"></i></button>
			</div>
			</div>
			<script>$("#select_${data_id[1]}").select2();
			$("#key_${data_id[1]}").select2();
			$("#name_${data_id[1]}").select2();
			$("#column1_${data_id[1]}").select2();
			$("#value1_${data_id[1]}").select2();
			</script>`;

	return options;
}
function getHideDiv(div_id1,div_id2) {
	console.log(div_id2);
	$("#"+div_id1).show();
	$("#"+div_id2).hide();
}
function GetallTable1(){


	$.ajax({
		type: "POST",
		url: baseURL+"Html_template/getAllTablenames",
		dataType: "json",
		//data:{p_id},
		success: function (result) {
			tableDataArray=result.data;
			$("#primary_table").append(result.option);
			$("#primary_table").select2();
		}, error: function (error) {

		}
	});
}

function getSelectTableOptions(id)
{
	// console.log(tableDataArray);
	var options=``;
	if(tableDataArray.length>0)
	{
		options+=`<option value=''>Select Table</option>`;
		for (var i = 0; i < tableDataArray.length; i++) {
			// options+=`<option value='`+tableDataArray[i]+`'>`+tableDataArray[i]+`</option>`;
			options += '<option value="'+tableDataArray[i]+'">'+tableDataArray[i]+'</option>';
		}
	}
	// $("#"+id).html(options);
	return options;

}
function getSelectHashOptions(hashtextarray)
{
	var options=``;
	if(hashtextarray.length>0)
	{
		options+=`<option value=''>Select Hash Id</option>`;
		for (var i = 0; i < hashtextarray.length; i++) {
			options += '<option value="'+hashtextarray[i]+'">'+hashtextarray[i]+'</option>';
		}
	}
	// $("#"+id).html(options);
	return options;
}
function getListOfColumns1(TableName){
	$.ajax({
		type: "POST",
		url: baseURL+"Html_template/GetAllColumns",
		dataType: "json",
		data:{TableName},
		success: function (result) {

			$(".tableColumns").html(result.data);
			$(".tableColumns1").html(result.data);
			listColumns=result.data;

		}, error: function (error) {

		}
	});
}
function getListOfColumns2(TableName){
	$.ajax({
		type: "POST",
		url: baseURL+"Html_template/GetAllColumns",
		dataType: "json",
		data:{TableName},
		success: function (result) {

			$(".tableColumns1").html(result.data);
			// listColumns=result.data;

		}, error: function (error) {

		}
	});
}
function getWhereColumns(data_id,hashoptionsArray,table_name)
{
	var count=$("#wherecount_"+data_id).val();
	var k=parseInt(count)+1;
	if(table_name=='')
	{
		table_name=$("#select_"+data_id).val();
	}
	getListOfColumns2(table_name);
	// console.log(hashopt);
	var data=`<div class="mt-2 row" id="div${k}_${data_id}">
				<div class="col-md-4">
				<label>where column: </label><select name="column${k}_${data_id}" id="column${k}_${data_id}" class="tableColumns1">
				</select>
				</div>
				<div class="col-md-4">
				<label>Where value: </label>
				<select name="para_value${k}_${data_id}" id="para_value${k}_${data_id}" class="form-control">
				`+queryStringParaArray+`
				</select>
				</div>
				<div class="col-md-3">
				<label>Where value: </label>
				<input type="text" name="text${k}_${data_id}" id="text${k}_${data_id}" placeholder="Enter value here" class="form-control">
				</div>
				<div class="col-md-1">
				<label>Remove where</label>
				<button type="button" class="btn btn-primary" id="button${k}_${data_id}" onclick="removeWhereOptions('div${k}_${data_id}','${data_id}',${k})">
				<i class="fa fa-times"></i></button>
				</div>
				<script>$("#column${k}_${data_id}").select2();
				$("#para_value${k}_${data_id}").select2();
				$("#value${k}_${data_id}").select2();</script>
			</div>
			
			`;
	$("#wherecount_"+data_id).val(k);
	$("#whereIds_"+data_id).append(data);
}
function removeWhereOptions(div_id,data_id,no)
{
	var count=$("#wherecount_"+data_id).val();
	$("#"+div_id).remove();
	for(var i=no;i<count;i++){
		var j=i+1;
		var column="column"+i+'_'+data_id;
		var value="value"+i+'_'+data_id;
		var text="text"+i+'_'+data_id;
		var button="button"+i+'_'+data_id;
		document.getElementById("column"+j+'_'+data_id).name = column;
		document.getElementById("column"+j+'_'+data_id).id = column;

		document.getElementById("text"+j+'_'+data_id).name = text;
		document.getElementById("text"+j+'_'+data_id).id = text;
		$("#button"+j+'_'+data_id).attr("onclick","removeWhereOptions('div"+i+"_"+data_id+"','"+data_id+"',"+i+")");
		document.getElementById("button"+j+'_'+data_id).id = button;
		$("#div"+j+'_'+data_id).attr("id","div"+i+"_"+data_id);
		var data=`<script>$("#column`+i+`_${data_id}").select2();
				</script>`;
		$("#whereIds_"+data_id).append(data);
	}
	var k=parseInt(count)-1;
	$("#wherecount_"+data_id).val(k);
}

function getQueryModal(section_id,department_id,id,type,textarray)
{
	let formData = new FormData();
	formData.set("section_id",section_id);
	formData.set("department_id",department_id);
	formData.set("field_id",id);
	formData.set("field_type",type);
	formData.set("textarray",textArray);
	// formData.set("querydropDownOptions",JSON.stringify(querydropdownOptionsArray));
	app.request("Html_template/getQueryDropdownData", formData).then(response => {
		if (response.status === 200) {
			$("#querydropdownOpBtn").click();
			$("#querydropdownOpHere").html(response.data);
		}
	});


}
function getDatatableModal(section_id,department_id,id,type,textarray)
{
	$("#datatableOpBtn").click();
	$("#datatable_section_id").val(section_id);
	$("#element_id").val(id);
	getAllTables(section_id,id);
	// loadDataTable();
	getQueryStringParaForWhere1();
	$("#queryTableSelectColumn").select2();
	$("#queryTableSearchColumn").select2();
	$("#queryTableOrderColumn").select2();
	filterCount = 0;
	filterArray = [];
	whereCount = 0;
	whereArray = [];
	actionCount = 0;
	actionArray = [];
	$("#WhereSection").empty();
	$("#filterSection").empty();
	$("#actionSection").empty();
	$("#query_master_id").val('');
	$('#queryTableSelectColumn').val('');
	$('#queryTableSelectColumn').select2({allowClear: true});
	$('#queryTableSearchColumn').val('');
	$('#queryTableSearchColumn').select2({allowClear: true});
	$('#queryTableOrderColumn').val('');
	$('#queryTableOrderColumn').select2({allowClear: true});
	$('#queryTableOrderColumnDirection').val('');
	}
function getSelecteDatableData(section_id,id)
{
	let formData = new FormData();
	formData.set("section_id", section_id);
	formData.set("element_id", id);
	$("#query_master_id").val('');
	app.request(baseURL + "fetchTemplateDatatableData", formData).then(response => {
		if(response.status==200){
			
			var userdata=response.data;
			if(userdata!="")
			{
				$("#query_master_id").val(userdata.id);
				$("#queryTable option[value="+userdata.table_name+"]").prop("selected", true);
				$("#queryTable").select2();
				$("#filterQueryTable option[value="+userdata.table_name+"]").prop("selected", true);
				$("#filterQueryTable").select2();
				getAllColumns(userdata.table_name,type=-1,userdata);
				
				if(userdata.table_type!=null && userdata.table_type!="")
				{
					if(userdata.table_type==1)
					{
						$("#serverSideSync").prop("checked", true);
					}
					else
					{
						$("#clientSideSync").prop("checked", true);
					}
				}
			}
		}
		else{
			app.errorToast(response.body);
		}

	});

}
function getSelectSelectedColumn(userdata)
{
	$('#queryTableSelectColumn').val('');
	$('#queryTableSelectColumn').select2({allowClear: true});
	$('#queryTableSearchColumn').val('');
	$('#queryTableSearchColumn').select2({allowClear: true});
	$('#queryTableOrderColumn').val('');
	$('#queryTableOrderColumn').select2({allowClear: true});
	$('#queryTableOrderColumnDirection').val('');
	if(userdata.select_column!=null && userdata.select_column!="")
	{
		var select_column=userdata.select_column.split(',');
		for(var i=0;i<select_column.length;i++)
		{
			$("#queryTableSelectColumn option[value="+select_column[i]+"]").prop("selected", true);
			
		}
		$("#queryTableSelectColumn").select2();
	}
	if(userdata.search_column!=null && userdata.search_column!="")
	{
		var search_column=userdata.search_column.split(',');
		for(var i=0;i<search_column.length;i++)
		{
			$("#queryTableSearchColumn option[value="+search_column[i]+"]").prop("selected", true);
			
		}
		$("#queryTableSearchColumn").select2();
	}
	if(userdata.order_column!=null && userdata.order_column!="")
	{
		var order_column=userdata.order_column.split(',');
		for(var i=0;i<order_column.length;i++)
		{
			$("#queryTableOrderColumn option[value="+order_column[i]+"]").prop("selected", true);
			
		}
		$("#queryTableOrderColumn").select2();
	}
	if(userdata.order_direction!=null && userdata.order_direction!="")
	{
		$("#queryTableOrderColumnDirection option[value="+userdata.order_direction+"]").prop("selected", true);
	}
	if(userdata.where_condition!=null && userdata.where_condition!="")
	{	
		$("#WhereSection").empty();
		get_whereEditData(userdata);
	}
	if(userdata.filter_columns!=null && userdata.filter_columns!="")
	{
		$("#filterSection").empty();
		get_filterEditData(userdata);
	}
	if(userdata.action_columns!=null && userdata.action_columns!="")
	{
		$("#actionSection").empty();
		get_actionEditData(userdata);
	}
	
}
function get_whereEditData(userdata)
{
	if(userdata.where_condition!=null && userdata.where_condition!="")
	{	var cntr=1;
		var where_condition=userdata.where_condition.split(',');
		for(var i=0;i<where_condition.length;i++)
		{	
			addWhereSection();
			var where="where"+cntr;
			where=where_condition[i].split('|');
			if(where.length>2)
			{
				var whereTableColumn=where[0].split(':');
				if(whereTableColumn.length>1){
					$("#whereTableColumn_"+cntr+" option[value="+whereTableColumn[1]+"]").prop("selected", true);
					$("#whereTableColumn_"+cntr).select2();
				}
				var whereTableValue=where[1].split(':');
				if(whereTableValue.length>2){
					$("#whereValue_"+cntr+" option[value='"+whereTableValue[1]+":"+whereTableValue[2]+"']").prop("selected", true);
					$("#whereValue_"+cntr).select2();
				}
				else if(whereTableValue.length>1)
				{
					$("#whereStaticValue_"+cntr).val(whereTableValue[1]);
				}
			}
			cntr++;
		}
	}
}
function get_filterEditData(userdata)
{
	if(userdata.filter_columns!=null && userdata.filter_columns!="")
	{
		var cnt=1;
		var filter_columns=userdata.filter_columns.split(',');
		for(var i=0;i<filter_columns.length;i++)
		{	
			addFilterSection();
			var filter="filter"+cnt;
			filter=filter_columns[i].split('|');
			for(var j=0;j<filter.length;j++)
			{
				var filterTableColumn=filter[j].split(':');
				if(filterTableColumn.length>1){
					if(filterTableColumn[0]=="FilterTableColumn")
					{
						$("#filterTableColumn_"+cnt+" option[value="+filterTableColumn[1]+"]").prop("selected", true);
						$("#filterTableColumn_"+cnt).select2();
					}
					if(filterTableColumn[0]=="filterValueType")
					{
						$("#filterValueType_"+cnt+" option[value="+filterTableColumn[1]+"]").prop("selected", true);
						filterTypeSection(cnt,filterTableColumn[1]);
					}
					if(filterTableColumn[0]=="filterStaticValue")
					{
						$("#filterStaticValue_"+cnt).val(filterTableColumn[1]);
					}
					if(filterTableColumn[0]=="filterQueryTable")
					{
						$("#filterQueryTable_"+cnt+" option[value="+filterTableColumn[1]+"]").prop("selected", true);
						$("#filterQueryTable_"+cnt).select2();
						getAllColumns(filterTableColumn[1],3,filter,cnt);
					}
					if(filterTableColumn[0]=="filterQueryCondition")
					{
						$("#filterQueryCondition_"+cnt).val(filterTableColumn[1]);
					}
					if(filterTableColumn[0]=="filterStaticValue")
					{
						$("#filterStaticValue_"+cnt).val(filterTableColumn[1]);
					}
					
				}
			}
			cnt++;
		}

	}
}
function getSelectedFilterSelection(filterColumn,count)
{
	for(var j=0;j<filterColumn.length;j++)
	{
		var filterTableColumn=filterColumn[j].split(':');
		if(filterTableColumn.length>1){
			if(filterTableColumn[0]=="filterKeyValue")
			{
				$("#filterKeyValue_"+count+" option[value="+filterTableColumn[1]+"]").prop("selected", true);
				$("#filterKeyValue_"+count).select2();
				break;
			}
		}
	}
}

function get_actionEditData(userdata)
{
	if(userdata.action_columns!=null && userdata.action_columns!="")
	{
		var cntl=1;
		var action_columns=userdata.action_columns.split(',');
		for(var i=0;i<action_columns.length;i++)
		{
			addActionSection();
			var action="action"+cntl;
			action=action_columns[i].split('|');
			for(var j=0;j<action.length;j++)
			{
				var actionTableColumn=action[j].split(':');
				if(actionTableColumn.length>1){
					if(actionTableColumn[0]=="actionButtonPrimary")
					{
						$("#actionButtonPrimary_"+cntl+" option[value="+actionTableColumn[1]+"]").prop("selected", true);
						$("#actionButtonPrimary_"+cntl).select2();
					}
					if(actionTableColumn[0]=="actionButtonIcon")
					{
						$("#actionButtonIcon_"+cntl).val(actionTableColumn[1]);
					}
					if(actionTableColumn[0]=="actionButtonType")
					{
						$("#actionButtonType_"+cntl+" option[value="+actionTableColumn[1]+"]").prop("selected", true);
						actionTypeSection(cntl,actionTableColumn[1]);
					}
					if(actionTableColumn[0]=="actionButtonRedirectTemplate")
					{
						$("#actionButtonRedirectTemplate_"+cntl+" option[value="+actionTableColumn[1]+"]").prop("selected", true);
						$("#actionButtonRedirectTemplate_"+cntl).select2();
					}
					if(actionTableColumn[0]=="actionButtonRedirectQueryParam")
					{
						$("#actionButtonRedirectQueryParam_"+cntl).val(actionTableColumn[1]);
					}
					if(actionTableColumn[0]=="actionButtonExecutionQuery")
					{
						$("#actionButtonExecutionQuery_"+cntl).val(actionTableColumn[1]);
					}
					
				}
			}
			cntl++;
		}
		
	}
}
function getExcelPdfModal(section_id,department_id,id,type,textarray)
{
	let formData = new FormData();
	formData.set("section_id",section_id);
	formData.set("department_id",department_id);
	formData.set("id",id);
	formData.set("field_type",type);
	// formData.set("textarray",textArray);
	// formData.set("querydropDownOptions",JSON.stringify(querydropdownOptionsArray));
	app.request("Html_template/getExcelPdfData", formData).then(response => {

		$("#querydropdownOpBtn").click();
		$("#querydropdownOpHere").html(response.data);

	});
}

function saveFormData(){

	$.ajax({
		type: "POST",
		url: baseURL+"Html_template/saveFormData",
		dataType: "json",
		data:$('#query_form').serialize(),
		success: function (result) {
			if(result.status==200){
				app.successToast(result.body);
				//$('#query_form')[0].reset();
				document.getElementById("query_form").reset();//form1 is the form id.
				$("#querydropdownOpBtn").click();
				// getTableQuery();

				// toggle_div();

			}else{
				app.errorToast(result.body);
			}

		}, error: function (error) {
			app.errorToast('Something went wrong please try again');
		}
	});
}
function getDataHTML(id,section_id,type,hash_id){
	// $("#mainDashboard").hide();
	// $("#OtherDashboard").show();
	$.ajax({
		type: "POST",
		url: baseURL+"Html_template/getReportFormData",
		dataType: "json",
		data:{id:id,section_id:section_id,type:type,hash_id:hash_id},
		success: function (result) {
			$("#querydropdownOpHere").html('');
			if(result.status==200){
				$("#querydropdownOpBtn").click();
				$("#querydropdownOpHere").html(result.data);
			}else{
				$("#querydropdownOpBtn").click();
				$("#querydropdownOpHere").html(result.data);
			}

		}, error: function (error) {
			app.errorToast('Something went wrong please try again');
		}
	});
}
function DownloadData(field_id){

	var form_id='download_form_'+field_id;
	var loginForm = $('#'+form_id).serializeArray();
	// console.log(loginForm);
	var loginFormObject = {};
	$.each(loginForm,
		function(i, v) {
			loginFormObject[v.name] = v.value;
		});
	const x=JSON.stringify(loginFormObject);

	//	var x=$("#query_id").val();

	window.location.href= baseURL+"Html_template/DownloadData?formdata="+ x;
}
//DownloadNewpdf
function DownloadPDFData(field_id){

	var form_id='download_form_'+field_id;
	var loginForm = $('#'+form_id).serializeArray();
	var loginFormObject = {};
	$.each(loginForm,
		function(i, v) {
			loginFormObject[v.name] = v.value;
		});
	const x=JSON.stringify(loginFormObject);

	//	var x=$("#query_id").val();

	window.location.href= baseURL+"Html_template/DownloadNewpdf?formdata="+ x;
}

function DownloadCSVData(field_id){
	var form_id='download_form_'+field_id;
	var loginForm = $('#'+form_id).serializeArray();
	var loginFormObject = {};
	$.each(loginForm,
		function(i, v) {
			loginFormObject[v.name] = v.value;
		});
	const x=JSON.stringify(loginFormObject);

	//	var x=$("#query_id").val();

	window.location.href= baseURL+"Html_template/DownloadNewcsv?formdata="+ x;
}
function queryDropdownSave(section_id,department_id,id,type)
{
	var data=id;
	var data_id=data.split("#");
	var newElement1 = {};
	var sel_id="select_"+data_id[1];
	newElement1['id'] = data_id[1];
	newElement1['dop_id'] = sel_id;
	newElement1['type'] = type;
	var myElement = document.getElementById(sel_id);
	if(myElement!=null)
	{
		var myEleValue= document.getElementById(sel_id).value;
		if(myEleValue!="")
		{

			newElement1['table_name'] = myEleValue;
			newElement1['select_key'] = $("#key_"+data_id[1]).val();
			newElement1['select_value'] =$("#name_"+data_id[1]).val();
			newElement1['dep_column'] = $("#dep_column_"+data_id[1]).val();
			newElement1['dep_value'] =$("#dep_value_"+data_id[1]).val();
			var where_id="wherecount_"+data_id[1];
			var whereValue= document.getElementById(where_id).value;
			let wheredataArray=[];
			if(whereValue>0)
			{

				for (var i = 1; i <= whereValue; i++) {
					var newWhereElement = {};
					var wherecol_id="column"+i+"_"+data_id[1]+"";
					var wherecol_ele=document.getElementById(wherecol_id);
					if(wherecol_ele!=null)
					{
						var wherecol_val= document.getElementById(wherecol_id).value;
						if(wherecol_val!="")
						{
							var wherepara_ele="para_value"+i+"_"+data_id[1]+"";
							var wheretext_ele="text"+i+"_"+data_id[1]+"";
							var wherepara_val= document.getElementById(wherepara_ele).value;
							var wheretext_val= document.getElementById(wheretext_ele).value;
							if (wheretext_val!="" || wherepara_val!="") {
								newWhereElement['where_col']=wherecol_val;
								if(wherepara_val!="")
								{
									newWhereElement['where_text']="#"+wherepara_val;
								}
								else
								{
									newWhereElement['where_text']=wheretext_val;
								}

								wheredataArray.push(newWhereElement);
							}
						}
					}
				}
			}
			newElement1['where_op'] = wheredataArray;
		}

	}
	// console.log(newElement);
	newElement1['raw_query'] = '';
	querydropdownOptionsArray.push(newElement1);
	// console.log(querydropdownOptionsArray);
	var tab=$("#select_"+data_id[1]).val();
	var rawq=$("#textquery_"+data_id[1]).val();
	if(tab=="" && rawq=="")
	{
		app.errorToast('add query');
	}
	else
	{
		let formData = new FormData();
		formData.set("section_id",section_id);
		formData.set("department_id",department_id);
		formData.set("field_id",data_id[1]);
		formData.set("field_type",type);
		formData.set("querydropDownOptions",JSON.stringify(querydropdownOptionsArray));

		formData.set("select_key",$("#key_"+data_id[1]).val());
		formData.set("select_value",$("#name_"+data_id[1]).val());
		app.request("htmlform/save_dropdown_sectionsHtml", formData).then(response => {
			if (response.status === 200) {
				// console.log(response.body);
				// app.successToast(response.body);
				// $("#querydropdownOpHereDefault").html(response.data);
				$("#querydropdownOpBtn").click();
				document.getElementById('templateFornBtn').click();

			} else {
				app.errorToast(response.body);
				document.getElementById('templateFornBtn').click();
				// document.getElementById("templateFornBtn").disabled = false;
			}
		});
	}

}
function save_html_querydrop_default(field_id,field_type,section_id)
{
	var def_val=$("#default_query_"+field_id).val();
	if(def_val=="")
	{
		aqpp.errorToast('select default value');
	}
	else
	{	let formData = new FormData();
		formData.set("section_id",section_id);
		formData.set("department_id",$("#department_id").val());
		formData.set("field_id",field_id);
		formData.set("field_type",field_type);
		formData.set("default_val",def_val);

		app.request("htmlform/save_html_querydrop_default", formData).then(response => {
			if (response.status === 200) {
				// console.log(response.body);
				app.successToast(response.body);
				$("#querydropdownOpBtn").click();

			} else {
				app.errorToast(response.body);
				// document.getElementById("templateFornBtn").disabled = false;
			}
		});
	}
}
function getPrimaryTableData(table_name)
{
	$.ajax({
		type: "POST",
		url: baseURL+"getTableColumnsAndDatatypes",
		dataType: "json",
		data:{table_name:table_name},
		success: function (response) {
			if (response.status === 200) {
				// app.successToast(response.body);
				getPrimaryTableDataDesign(response.data);
			} else {
				app.errorToast(response.body);
			}
		}, error: function (error) {
			app.errorToast("Something went Wrong");
		}
	});
}
function getPrimaryTableDataDesign(data)
{
	var column_viewDesign=$("#column_view").val();
	let tableDesign=``;
	if (data.length > 0) {
		
		tableDesign +=`<table class="table"><tbody>`;
		if(column_viewDesign==1)
		{
			tableDesign += getOneColumnView(data);
		}
		else if(column_viewDesign==2)
		{
			tableDesign += getTwoColumnViewWithLabel(data);
		}
		else if(column_viewDesign==3)
		{
			tableDesign += getTwoColumnViewWithTextbox(data,3);
		}
		else if(column_viewDesign==4)
		{
			tableDesign += getTwoColumnViewWithTextbox(data,4);
		}

		var textName = getPrimaryColumnCreateField("",14,1);
		
		if(column_viewDesign==1)
		{
			tableDesign +=`<tr><td>${textName.textName2}</td></tr>`;
		}
		else if(column_viewDesign==2 || column_viewDesign==3)
		{
			tableDesign +=`<tr><td> </td><td>${textName.textName2}</td></tr>`;
		}
		else
		{
			tableDesign +=`<tr><td> </td><td></td><td></td><td>${textName.textName2}</td></tr>`;
		}

		tableDesign +=`</tbody></table><br>`;
		// $('#editor').trumbowyg('html', '');
		$('#dropBox').html('');
		// $('#editor').trumbowyg('execCmd', {
		// 	cmd: 'insertHtml',
		// 	param: tableDesign,
		// 	forceCss: false,
		// });
		$('#dropBox').append(tableDesign);
		$(".spanLabel").draggable();
		$( ".div_drag" ).draggable({containment: "parent"});
		$( ".div_drag" ).resizable({containment: "parent",
			 minHeight: 50});
	}
}

	let primaryTableArray=[];
function getOneColumnView(data)
{
	var tableDesign=``;
		let element = data.map((i,index) => {
			
				var textName = getPrimaryColumnCreateField(i['column'],i['type'],0);
				
				var primaryInsertvar=i['column']+':'+textName.textname;
					tableDesign +=`<tr><td>${i['column']} ${textName.textName2}</td></tr>`;
			
				primaryTableArray.push(primaryInsertvar);
			
			primaryTableColumnArray.push(i['column']);

		});

		$("#primaryTableInsert").val(primaryTableArray.join('|'));
	return tableDesign;
}
function getTwoColumnViewWithLabel(data)
{
	var tableDesign=``;
		let element = data.map((i,index) => {
			
				var textName = getPrimaryColumnCreateField(i['column'],i['type'],0);
				
				var primaryInsertvar=i['column']+':'+textName.textname;
				
					tableDesign +=`<tr><td>${i['column']}</td><td>${textName.textName2}</td></tr>`;
				
				primaryTableArray.push(primaryInsertvar);
			
			primaryTableColumnArray.push(i['column']);

		});

		$("#primaryTableInsert").val(primaryTableArray.join('|'));
	return tableDesign;
}
function getTwoColumnViewWithTextbox(data,type)
{
	var tableDesign=``;
	let evenA=[];
	let oddA=[];
		let element = data.map((i,index) => {
			
				 if (index % 2 === 0) {
				   evenA.push(i);
				  } else {
				   oddA.push(i);
				  }
			
		});
		
		for(var i=0;i<evenA.length;i++)
		{
			tableDesign +=`<tr>`;
			tableDesign +=getEvenDataValue(i,evenA,type);
			tableDesign +=getEvenDataValue(i,oddA,type);
			tableDesign +=`</tr>`;
		}
	
	return tableDesign;
}
function getEvenDataValue(indexe,data,type)
{
	var design=``;
	if(indexe<data.length)
	{
		
		// let element = data.map((i,index) => {
			for (var i = 0; i < data.length; i++) {
			if(indexe==i)
			{
				// console.log(data[i].column)
				var textName = getPrimaryColumnCreateField(data[i].column,data[i].type,0);
				var primaryInsertvar=data[i].column+':'+textName.textname;
				if(type==3){
				design+=`<td>${data[i].column}${textName.textName2}</td>`;
				}else if(type==4){
					design+=`<td>${data[i].column}</td><td>${textName.textName2}</td>`;
				}
				primaryTableArray.push(primaryInsertvar);
				primaryTableColumnArray.push(data[i].column);
			
				break;
			}
		}
			$("#primaryTableInsert").val(primaryTableArray.join('|'));
			// });
	}
	return design;

}
function getPrimaryColumnCreateField(column,type,buttontype)
{
	let textreturn={};
	var text='';
	if(type==6)
	{
		text="#number_";
	}
	else if(type==5)
	{
		text="#date_";
	}
	else if(type==14)
	{
		text="#button_";
	}
	else
	{
		text="#shorttext_";
	}

	var textNo=Math.floor((Math.random() * 100) + 1);
	if(textNumberArray.length>0)
	{
		for(var j=0;j<textNumberArray.length;j++)
		{
			if(textTypeArray[j]==type && textNumberArray[j]==textNo)
			{
				textNo=Math.floor((Math.random() * 100) + 1);
			}
		}
	}

	textTypeArray.push(type);
	textNumberArray.push(textNo);

	$('#elementSequenceType').val(textTypeArray.join());
	$('#elementSequenceId').val(textNumberArray.join());
	var textName=text+textNo;
	textreturn['textname']=textName;
	textArray.push(textName);
	if(type==14)
	{
		$("#primaryTableButton").val(textName);
	}
	$('#elementSequenceText').val(textArray.join());
	textName = textName.replace("#","");
	let items='';
	let v_data ='';
	if(type==6)
	{
		items = number(textNo, 6, textName, v_data);
	}
	else if(type==5)
	{
		items = dateElement(textNo, 5, textName, v_data);
	}
	else if(type==14)
	{
		items = button(textNo, 5, textName, v_data);
	}
	else
	{
		items = shortText(textNo, 1, textName,v_data);
	}

	textMainInputArray.push(items);
	var textName2='<div class="spanLabel">'+items+'</div>';
	var parser = new DOMParser();
		var doc = parser.parseFromString(textName2, 'text/html');
		var nodeCopy= doc.body.firstChild;

		nodeCopy.setAttribute("draggable", true);
		nodeCopy.setAttribute("droppable", true);
		nodeCopy.classList.add('div_drag');	
		// console.log(nodeCopy);
		
		var $html = $(nodeCopy);    
		var str = $html.prop('outerHTML');

		// console.log(str);
	textreturn['textName2']=str;
	return textreturn;

}
//-------------------------changes by pooja-----------------------------
let countInsert=1;
InsertQueryArray=[];
let newqueryData = {};

function getButtonModal(section_id,department_id,btn_id,btn_type,textarray){
	countInsert=1;
	InsertQueryArray=[];
	newqueryData = {};
	$("#htmlqueryModal").modal('show');
	getQueryStringPara2();
	var html=`<hr><div>
	<button id='insertButton' type='button' class="btn btn-link" onclick="getInsertButtonForm('${textarray}',1,'${section_id}')">Insert Query</button>
	<button id='updateButton' type='button' class="btn btn-link" onclick="getInsertButtonForm('${textarray}',2,'${section_id}')">Update Query</button>
	<button id='DeleteButton' type='button' class="btn btn-link" onclick="getInsertButtonForm('${textarray}',3),'${section_id}'">Delete Query</button>
	</div>
	<input type="hidden" id="queryString" name="queryString">
	<input type="hidden" id="queryDepartmentID" name="queryDepartmentID" value="${department_id}">
	<input type="hidden" id="querysection_id" name="querysection_id" value="${section_id}">
	<input type="hidden" id="ButtonInsertID" name="ButtonInsertID" value="${btn_id}">
	<div id="appendToThis"></div>
	`;
	$("#divAppendData").html(html);
	getButtoQueryTable(section_id,department_id,btn_id);

}

function get_buttonQueryData(data1,textarray){


	//return html;
}

function getInsertButtonForm(textarray,quertType,section_id){
	$("#querySaveBtn").show();
	if(quertType==3){
		var checkboxselectall=``;

	}else{
		var checkboxselectall=`<div class="col-md-4">
			<input type='checkbox' id="q_checkHead${countInsert}"  onclick="checkall(${countInsert})" >  Select All
			</div>`;
	}
	var html=`<input type="hidden" id="finalCount" name="finalCount" value="${countInsert}">
	<div class="row"><div class="col-md-8">
	<input type="hidden" id="queryType${countInsert}" name="queryType${countInsert}" value="${quertType}">
	<select class="form-control" onchange="getListOfColumns('${textarray}',${countInsert},${quertType},${section_id})" 
	id='optionTable_${countInsert}' name='optionTable_${countInsert}'style="border-radius: 20px;">
			</select></div>
		<div class="col-md-4">
		<input type='checkbox' id='insertIDGenerate${countInsert}' name="insertIDGenerate${countInsert}" onclick="getListOfColumns('${textarray}',${countInsert},${quertType},${section_id})" >Generate InsertId
			</div>
			${checkboxselectall}
			
			</div>
			<div id="appendColumnData_${countInsert}"></div>
			`;
	$("#appendToThis").prepend(html);
	appendTableoptions(countInsert);
	countInsert++;
	$("#finalCount").val(countInsert);
}

function appendTableoptions(count){
	$.ajax({
		type: "POST",
		url: baseURL+"getAllTablenames",
		dataType: "json",
		//data:{p_id},
		success: function (result) {

			$("#optionTable_"+count).html(result.option);

		}, error: function (error) {

		}
	});
}

function getListOfColumns(dataArray,count,type,section_id){
	var TableName=$("#optionTable_"+count).val();
	$("#q_checkHead"+count).prop('checked', false);
	checkall(count);
	newqueryData['TableName_'+count]=TableName;
	$("#queryString").val(JSON.stringify(newqueryData));



	$.ajax({
		type: "POST",
		url: baseURL+"GetAllColumns",
		dataType: "json",
		data:{TableName,count,dataArray,type,section_id},
		success: function (result) {

			$("#appendColumnData_"+count).html(result.data);
			var finalCount=$("#finalCount").val();
			for(var i=0;i< finalCount;i++){
				if($("#insertIDGenerate"+i).prop('checked')){
					$("option[value='#insertID"+i+"']").remove();
					$('.hashoptionclass').append(
						`<option value='#insertID${i}'>#insertID${i}</option>`);
				}else{
					$("option[value='#insertID"+i+"']").remove();
				}
			}


		}, error: function (error) {

		}
	});

}
function get_dependant_value(id)
{
	$("#txt_dependant_"+id).toggleClass("d-none")
}
function create_array(cnt1,cnt2){
	if($("#checkColumn"+cnt1+"_"+cnt2).prop('checked')){
		var data1=$("#columnName_"+cnt1+"_"+cnt2).val();
		var data2=$("#fieldName_"+cnt1+"_"+cnt2).val();
		newqueryData['otherData'+cnt1+"_"+cnt2]=data1+":"+data2;
		$("#queryString").val(JSON.stringify(newqueryData));
		console.log(newqueryData);
	}else{
		console.log('removed data');
		var k="otherData"+cnt1+"_"+cnt2;
		console.log(k);
		delete newqueryData[k];
		$("#queryString").val(JSON.stringify(newqueryData));
		console.log(newqueryData);
	}


}

function checkall(id){
	arr_length=$(".q_check"+id).length;

	if($("#q_checkHead"+id).prop('checked')){
		$(".q_check"+id).prop('checked', true);
		var cn=1;
		for(i=0;i<arr_length;i++){
			var data1=$("#columnName_"+id+"_"+cn).val();
			var data2=$("#fieldName_"+id+"_"+cn).val();
			newqueryData['otherData'+id+"_"+cn]=data1+":"+data2;
			$("#queryString").val(JSON.stringify(newqueryData));
			cn++;
		}
	}else{
		$(".q_check"+id).prop('checked', false);
		console.log(2);
		var cn=1;
		for(i=0;i<arr_length;i++){
			var k="otherData"+id+"_"+cn;
			delete newqueryData[k];
			$("#queryString").val(JSON.stringify(newqueryData));
			cn++;
		}
	}
	console.log(newqueryData);
}

function insertDataForm(btn_id,section_id){
	var form =document.getElementById('form_'+section_id);
	// var formValid = document.forms['form_'+section_id].checkValidity();
	var formValid = document.forms['form_'+section_id].reportValidity();
	/* var URL=window.location.href;
	var arr=URL.split('/');

	if(arr[6] != ''){
		var queryparameter_hidden=arr[6];
	}else{
		var queryparameter_hidden=null;
	} */
	var queryparameter_hidden=$("#queryparameter_hidden").val();
	if(formValid==true)
	{
		let formData = new FormData(form);
		formData.append("queryparameter_hidden",queryparameter_hidden)
		formData.set("btn_id",btn_id);

		app.request("InsertFordataUsingButton", formData).then(response => {
			if (response.status === 200) {
				app.successToast(response.body);
			} else {
				app.errorToast(response.body);
				// document.getElementById("templateFornBtn").disabled = false;
			}
		});
	}
}

function addQueryData(){
	var form =document.getElementById('QueryDAtaForm');
	let formData = new FormData(form);
	app.request("SaveQueryDataHTML", formData).then(response => {
		if (response.status === 200) {
			app.successToast(response.body);
		} else {
			app.errorToast(response.body);

		}
	});

}
function appendWhereHtml(count,optionT,optionfield){
	var counter1=$("#Uwherecount_"+count).val();
	console.log(counter1);
	var counter= (counter1 * 1) + 1;
	var html =`<br><div class='row'>
		   <div class='col-md-4'>
		   <select class='form-control' id='WherecolumnName_${count}_${counter}'
		   name='WherecolumnName_${count}_${counter}'>
		   ${atob(optionT)}
		   </select>
		   </div>
		   <div class='col-md-4'>
		   <select class='form-control' id='WherefieldName_${count}_${counter}' 
		   name='WherefieldName_${count}_${counter}'>
		  ${atob(optionfield)}
		   </select>
		   </div>
		    <div class='col-md-4'>
		  <input type='text' class='form-control' id='WheretextfieldName_${count}_${counter}'
		  name='WheretextfieldName_${count}_${counter}'>
		   </div>
		   </div>
		   <div id='divwhereAppend_${count}_${counter}'>
		   </div>
		   `;
	console.log(html);
	$("#divwhereAppend_"+count+"_"+counter1).html(html);
	$("#Uwherecount_"+count).val(counter);

}

function getButtoQueryTable(section_id,department_id,btn_id){
	let formData = new FormData();
	formData. append("section_id",section_id);
	formData. append("department_id",department_id);
	formData. append("btn_id",btn_id);
	app.request("SaveQueryDataHTMLTable", formData).then(response => {
		if (response.status === 200) {
			$("#tableQuery").html(response.data);
			var redirection=response.redirection;
			var arr=redirection.split("|");
			
				var arr2=arr[0].split(":");
					var redirection_type=arr2[1];
					$("#formActionType").val(redirection_type);
				getOtherActiondiv(redirection_type,arr);
			/* 	if(redirection_type == 1){
					var arr3=arr[1].split(":");
					$("#route1").val(arr3[1]);
					var arr4=arr[2].split(":");
					var array = arr4[1].split(',');
					$("#qeryparam1").val(array).trigger("change");
				}else if(redirection_type == 2){
					var arr3=arr[1].split(":");
					console.log(arr3);
					$("#sectionselect2").val(arr3[1]);
					var arr4=arr[2].split(":");
					var array = arr4[1].split(',');
					$("#qeryparam2").val(array).trigger("change");
				} */
		} else {
			$("#tableQuery").html(response.data);

		}
	});
}

function functionEditQuery(id){
	$("#querySaveBtn").hide();
	let formData = new FormData();
	formData. append("id",id);
	app.request("GetQueryDatatoEdit", formData).then(response => {
		if (response.status === 200) {
			$("#EditQueryDIv").html(response.data);
		} else {
			$("#EditQueryDIv").html(response.data);

		}
	});
}

function FunUpdateQueryForm(){
	//UpdateQueryForm
	var formActionType=$("#formActionType").val();
	var sectionselect2=$("#sectionselect2").val();
	var route1=$("#route1").val();
	var qeryparam1=$("#qeryparam1").val();
	var qeryparam2=$("#qeryparam2").val();
	var form =document.getElementById('UpdateQueryForm');
	
	let formData = new FormData(form);
	formData.append("formActionType",formActionType);
	formData.append("sectionselect2",sectionselect2);
	formData.append("route1",route1);
	formData.append("qeryparam1",qeryparam1);
	formData.append("qeryparam2",qeryparam2);
	app.request("UpdatequeryTable", formData).then(response => {
		if (response.status === 200) {
			app.successToast(response.data);
		} else {
			app.errorToast(response.data);

		}
	});
}

function openParameterModal(section_id,dept_id){
	$("#RedirctModal").modal('show');
	$("#QS_section_id").val(section_id)
	let formData = new FormData();
	formData.append('section_id',section_id);
	formData.append('dept_id',dept_id);
	app.request("GetQueryParamData", formData).then(response => {
		if (response.status === 200) {
			$("#queryParamString").html(response.data);
			$('#redirectButton').show();
		} else {
			app.errorToast(response.body);
			$("#queryParamString").html(response.data);
			$('#redirectButton').show();

		}
	});
}

function redirectToanotherFor(){
	var form =document.getElementById('queryForm');
	let formData = new FormData(form);
	var x = $("#queryForm").serializeArray();
	var Obj={};
	$.each(x, function(i, field) {
		Obj[field.name]=field.value;
	});
	var newOBJ=JSON.stringify(Obj);
	newOBJ=btoa(newOBJ);
	var section_id=$("#QS_section_id").val();
	var myArr = baseURL.split("/");

	window.location = "http://localhost/new_covid/html_form_view/"+section_id+"/"+newOBJ;

}

function getQueryStringPara(){
	//QueryStringParameter
	app.request("getQueryStringPara").then(response => {
		if (response.status === 200) {
			$("#QueryStringParameter").html(response.data);
			queryStringParaArray=response.data;
		} else {
			$("#QueryStringParameter").html(response.data);
			queryStringParaArray=response.data;
		}
	});
}
function getQueryStringPara2(){
	//QueryStringParameter
	app.request("getQueryStringPara2").then(response => {
		if (response.status === 200) {
			$("#qeryparam1").html(response.data);
			$("#qeryparam2").html(response.data);
		} else {
			$("#qeryparam1").html(response.data);
			$("#qeryparam2").html(response.data);
		}
	});
}
function GetAllSections(value,arr){
	//QueryStringParameter
	var department_id= $("#department_id").val();
	console.log(department_id);
	let formData = new FormData();
	formData.append('department_id',department_id);
	app.request("GetallSections",formData).then(response => {
		if (response.status === 200) {
			$("#sectionselect2").html(response.data);
			if(arr != null){
		var redirection_type=value;
		if(redirection_type == 1){
			var arr3=arr[1].split(":");
			$("#route1").val(arr3[1]);
			var arr4=arr[2].split(":");
			var array = arr4[1].split(',');
			$("#qeryparam1").val(array).trigger("change");
		}else if(redirection_type == 2){
			var arr3=arr[1].split(":");
			
			console.log("pooja");
			console.log(arr3);
			$("#sectionselect2").val(arr3[1]);
			var arr4=arr[2].split(":");
			var array = arr4[1].split(',');
			console.log(array);
			$("#qeryparam2").val(array).trigger("change");
		}else{
			
		}
	}
		} else {
			$("#sectionselect2").html(response.data);
		}
	});
}

//getCsvModal

function getOtherActiondiv(value,arr=null){
	
	if(value == 1){
		$("#div_first").show();
		$("#div_second").hide();
	}else if(value == 2){
		$("#div_second").show();
		$("#div_first").hide();
		GetAllSections(value,arr);
	}else{
		$("#div_first").hide();
		$("#div_second").hide();
	}
	getQueryStringPara2();
	
}

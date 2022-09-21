let BMR_obj;
let KP = [];
$(document).ready(function () {
	getUserProfiles();
	getKeyPairs();
	getPagesList();
	getGroupsList();
});
var editor1 = new RichTextEditor("#summernote");
let currentInput = document.getElementById('summernote');
$(document).on('focus', 'textarea', function () {
	currentInput = this;
})

let keys = [];
let staticArr = [];

function setTextBoxCode(type, inputName = '') {
	let elementCnt = $("#elementCount").val();
	elementCnt = (elementCnt * 1) + 1;
	$("#elementCount").val(elementCnt);
	let eleV = '';
	switch (type) {
		case 1:
			eleV = 'Input' + elementCnt;
			break;
		case 2:
			eleV = 'histable' + elementCnt;
			break;
		case 3:
			eleV = inputName;
			break;
		case 4:
			eleV = 'materialTable' + elementCnt;
			break;
		case 5:
			eleV = 'indentTable' + elementCnt;
			break;
		case 6:
			eleV = 'processChart' + elementCnt;
			break;
	}
	let elementTag = '<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' + eleV + '</span>';

	editor1.insertHTML(elementTag)
	editor1.collapse(false);
	editor1.focus();
	keys.push(eleV);
	getKeyPairs();
	if (type == 3) {
		staticArr.push(eleV);
	}
}

function saveHtml() {

	let page_name = $("#section_name").val();
	let page_id = $("#page_id").val();
	let page_type = 2;
	let page_id_count = $('#page_id_count').val();
	let bmr_no = $("#bmr_no").val();
	let p_id = parseInt(page_id);
	if (page_name != null && page_name != '' && page_type != -1) {

		let bmr_no = $("#bmr_no").val();
		let production_id = $("#production_id").val();
		let html_code = editor1.getHTMLCode();
		let keys_arr = keys;
		let keyPairs = hosController.getData();
		let tablename = $("#table_name").val();
		if (staticArr == undefined) {
			staticArr = [];
		}
		html_code = removeHtmlStructure(html_code, keyPairs);
		let is_config = false;
		if ($("#is_config").prop("checked")) {
			is_config = true;
		}

		let staticFields = staticArr;
		let html_obj = [];
		let pageObject = {
			"page_name": page_name,
			"page_type": page_type,
			"html_code": html_code,
			"keys": keys_arr,
			"keyPairs": keyPairs,
			"dataset": [],
			"staticFields": staticFields,
			"tablename": tablename,
			"is_config": is_config,
		};
		if (p_id != null && p_id != '' && !isNaN(p_id)) {

			BMR_obj.pages = BMR_obj.pages.map(r => {

				if (r.page_id === p_id) {
					r.page_name = page_name;
					r.page_type = page_type;
					r.html_code = html_code;
					r.keys = keys_arr;
					r.keyPairs = keyPairs;
					r.staticFields = staticFields;
					r.tablename = tablename;
					r.is_config = is_config;
				}
				return r;
			});


		} else {

			p_id = BMR_obj.pages.length + 1;
			pageObject.page_id = p_id;
			BMR_obj.pages.push(pageObject);
		}


		html_obj.push(BMR_obj);


		let form = document.getElementById('page_form');
		let formdata = new FormData(form);
		formdata.set('html_obj', JSON.stringify(html_obj));
		formdata.set('type', $("#type").val());
		formdata.set('page_name', page_name);
		formdata.set('page_id', p_id.toString());
		formdata.set('bmr_no', bmr_no);
		console.log(formdata);
		app.request("saveHtmlTemplate", formdata).then(res => {
			if (res.status === 200) {
				app.successToast(res.body);
				$("#update_id").val(res.insert_id);
				// $("#isconfigure").reload();
				addNewPage();
				getAllTablesNames();
			} else {
				app.errorToast(res.body);
			}
		}).catch(error => console.log(error));
	} else {
		app.errorToast('Page Name & Type should not be empty');
	}
}


let hosController;
let userProfiles;
let users;
let grouplist;
let pageslist;
let labellist;

function handson(data, columnsS, hiddenColumn) {

	if (data.length == 0) {
		data = [
			['', '', '', '', ''], '',
		];
	}
	const container = document.getElementById('keyPairsDiv');
	hosController != null ? hosController.destroy() : "";
	hosController = new Handsontable(container, {
		data: data,
		colHeaders: [
			"Keys",
			"Control Type",
			"Default Value",
			"Access Control",
			"Formula",
			"Status",
			"Label",
			"Groups",
			"Pages",
			"Page Input Label"
		],
		manualColumnResize: true,
		manualRowResize: true,
		columns: columnsS,
		beforeChange: function (changes, source) {
			var row = changes[0][0];
			var prop = changes[0][1];
			var value = changes[0][3];
		},
		afterChange: function (changes, source) {
			if (changes) {
				var row = changes[0][0];
				var value = changes[0][3];
				var prop = changes[0][1];
				if (prop == 7) {
					var data = value.split('-');
					var id = "";
					if (data.length > 1 && data !== 'none') {
						id = data[0];
						getLabelData(id,1).then(e => {
							this.setCellMeta(row, 8, 'source', e);
							this.setDataAtCell(row, 8, e[0]);
							this.render();
						});
					}else{
						this.setDataAtCell(row, 8, '');
						this.render();
					}
				}
				if (prop == 8) {
					var data = value.split('-');
					let grp_id = this.getDataAtCell(row,7);
					let group_id = grp_id.split("-");
					var id = "";
					if (data.length > 1 && data !== 'none') {
						id = data[0];
						getLabelData(id,2,group_id[0]).then(e => {
							this.setCellMeta(row, 9, 'source', e);
							this.setDataAtCell(row, 9, e[0]);
							this.render();
						});
					}else{
						this.setDataAtCell(row, 9, '');
						this.render();
					}
				}
			}
		},
		stretchH: 'all',
		colWidths: '100%',
		width: '100%',
		height: 320,
		rowHeights: 23,
		rowHeaders: true,
		filters: true,
		contextMenu: true,
		hiddenColumns: {
			// specify columns hidden by default
			columns: hiddenColumn,
			copyPasteEnabled: false,
		},
		dropdownMenu: ['filter_by_condition', 'filter_by_value', 'filter_action_bar'],
		licenseKey: 'non-commercial-and-evaluation'
	});
	hosController.validateCells();
}


function getKeyPairs(type = null) {
	console.log(labellist);
	let keyArr = keys;

	let data = [];
	if (KP.length > 0) {
		data = KP;
	}

	if (type !== 1) {
		if (keyArr.length > 0) {

			if (KP.length > 0) {
				let lastel = keyArr[keyArr.length - 1];
				let arr = [lastel, 'text', '', '', '', 'active'];
				data.push(arr);
			} else {
				keyArr.map(r => {
					let arr = [r, 'text', '', '', '', 'active'];
					data.push(arr);
				});
				KP = data;
			}
		} else {
			data = ['', '', '', '', '', '', '',''];
		}
	}

	let hiddenColumn = [];
	let columns = [
		{type: 'text'},
		{type: 'dropdown', source: ['text','label','number', 'date','file','table', 'dropdown', 'calculated','checkbox']},
		{type: 'text'},
		{type: 'dropdown', source: users},
		{type: 'text'},
		{type: 'dropdown', source: ['active', 'inactive']},
		{type: 'text'},
		{type: 'dropdown', source: grouplist},
		{type: 'dropdown', source: pageslist},
		{type: 'dropdown', source: labellist}
	];
	handson(data, columns, hiddenColumn);
}


function getUserProfiles() {
	app.request('userProfiles', null).then(res => {
		if (res.status === 200) {
			userProfiles = res.data;
			users = res.users;
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getPagesList() {
	let id = $("#update_id").val();
	let type = $("#type").val();
	let formdata = new FormData();
	formdata.set('id', id);
	formdata.set('type', type);
	app.request('getPagesList', formdata).then(res => {
		if (res.status === 200) {
			$("#sectionTableBody").html(res.body);
			let pagesdata = res.pages;
			let bmr_no = $("#bmr_no").val();
			let production_id = $("#production_id").val();

			BMR_obj = {
				"bmr_no": bmr_no,
				"production_id": production_id,
				"pages": []
			}
			BMR_obj.pages.push(...pagesdata);

		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

function getPageDataToEditor(id, page_id) {

	let formdata = new FormData();
	formdata.set('id', id);
	formdata.set('page_id', page_id);
	formdata.set('type', $("#type").val());
	app.request('getPageDataToEditor', formdata).then(res => {
		if (res.status === 200) {
			let obj = JSON.parse(res.bmr_object);

			BMR_obj = obj[0];
			setPageEditorData(res.body.html_code, res.body.keys, res.body.keyPairs, res.body.staticFields, res.body.tablename, res.body.is_config);
			$("#section_name").val(res.body.page_name);
			$("#update_id").val(res.id);
			$("#page_id").val(res.body.page_id);
			$("#page_type").val(res.body.page_type);
			$('#alltablename').select2('destroy');
			$('#alltablename').val(res.body.tablename).select2();
			if (res.body.is_config == true) {
				$('#is_config').prop('checked', true);
			} else {
				$('#is_config').prop('checked', false);
			}

			// $("#sectionTableBody").html(res.body);
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}


function setPageEditorData(htmlCode, keys_arr, keyPairs, staticFields, tablename, is_config) {

	if (htmlCode != "" && htmlCode != null) {
		keys = keys_arr;
		staticArr = staticFields;
		editor1.setHTMLCode(htmlCode);
		console.log(is_config);

		if (is_config == true) {
			$('#is_config').prop('checked', true);
		} else {
			$('#is_config').prop('checked', false);
		}

		$("#alltablename").append(tablename);
		$("#alltablename").select2();
		if (keyPairs != null) {
			KP = keyPairs;
			keyInputData(keyPairs);

			setKeyPairs(keyPairs);
			$("#elementCount").val(keys.length);
		}
	}
}

function setKeyPairs(data) {

	$("#keyPairsDiv").empty();
	let hiddenColumn = [];
	let columns = [
		{type: 'text'},
		{type: 'dropdown', source: ['text', 'label','number', 'date','file','table', 'dropdown', 'calculated','checkbox']},
		{type: 'text'},
		{type: 'dropdown', source: users},
		{type: 'text'},
		{type: 'dropdown', source: ['active', 'inactive']},
		{type: 'text'},
		{type: 'dropdown', source: grouplist},
		{type: 'dropdown', source: pageslist},
		{type: 'dropdown', source: labellist}
	];
	handson(data, columns, hiddenColumn);
}

function addNewPage() {
	$('#is_config').prop('checked', false);
	$("#keybutton").html('');
	$("#elementCount").val(0);
	$("#section_name").val('');
	$("#page_id").val('');
	editor1.setText('');
	keys = [];
	KP = [];

	let columns = [
		{type: 'text'},
		{type: 'dropdown', source: ['text','label','number', 'date','file','table', 'dropdown', 'calculated','checkbox']},
		{type: 'text'},
		{type: 'dropdown', source: users},
		{type: 'text'},
		{type: 'dropdown', source: ['active', 'inactive']},
		{type: 'text'},
		{type: 'dropdown', source: grouplist},
		{type: 'dropdown', source: pageslist},
		{type: 'dropdown', source: labellist}
	];
	handson(['', '', '', '', '', '', '', '', '',''], columns, []);
	getGroupsList();
}

function setTextBoxInput(inputName) {
	KP = KP.map(r => {
		if (r[0] === inputName) {
			r[5] = 'active';
		}
		return r;
	})
	let elementTag = '<span class="inputfiled" style="background-color: rgb(255, 255, 0);">' + inputName + '</span>';
	editor1.insertHTML(elementTag)
	editor1.collapse(false);
	editor1.focus();

	getKeyPairs(1);
	keyInputData(KP);

}

function keyInputData(keyPairs) {
	$("#keybutton").html('');
	keyPairs.map((r, i) => {
		if (r[5] === 'inactive') {
			inputVal = r[0];
			let keysbutton = `<button onclick="setTextBoxInput('${inputVal}')">${inputVal}</button>&nbsp;`;
			$("#keybutton").append(keysbutton);
		}
		return keyPairs;
	})
}

function removeHtmlStructure(html, keypair) {
	keypair.map(r => {
		if (r[5] == 'inactive') {
			let replacekey = `<span class="inputfiled" style="background-color: rgb(255, 255, 0);">${r[0]}</span>`;

			html = html.replace(replacekey, '');
		}
	})
	return html;
}

function getLabelData(id,queryType,grp_id=null) {
	return new Promise(function (resolve, reject) {
		let bmr_id = $("#bmr_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('bmr_id', bmr_id);
		formdata.set('id', id);
		formdata.set('group_id', grp_id);
		formdata.set('type', type);
		formdata.set('queryType', queryType);
		app.request("getPageLabelData", formdata).then(res => {
			if (res.status === 200) {
				if(queryType === 1){
					pageslist = res.data;
				}else{
					labellist = res.data;
				}
				resolve(res.data);
			} else {
				resolve(res.data);
			}
		}).catch(error => console.log(error));
	});
}

function getGroupsList() {
	let type = $("#type").val();
	let formdata = new FormData();
	formdata.set('type', type);
	app.request('getGroupsList', formdata).then(res => {
		if (res.status === 200) {
			grouplist = res.data;
		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}

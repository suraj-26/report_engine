$(document).ready(function () {
	getReportData();
	getReportPageData();
	getQueryParam();
});

function getReportData() {
	let report_id = $("#report_id").val();
	let type = $("#type").val();
	let formdata = new FormData();
	formdata.set('report_id', report_id);
	formdata.set('type', type);
	app.request("getReportData", formdata).then(res => {
		if (res.status === 200) {
			showPagesList(res.body.code);
		}
	}).catch(error => console.log(error));
}

let pageContent = null;

function showPagesList(data) {
	$("#pagesList").html('');
	$("#bmr_pages").html('');
	let pagesData = JSON.parse(data);
	pagesData = pagesData[0].pages;
	pageContent = pagesData;
	pagesData.map((e, i) => {
		let color = '#ffa42633';
		if (e.dataset.length > 0) {
			color = '#28a7452b';
		}
		let tablehtml = `<tr style="background-color: ${color};"><td><span onclick="showPage('${i}','${e.page_id}')">${e.page_name}</span></td></tr>`;
		let page_list = `<li><a class="nav-link" href="#"><span  style="line-height: 18px;" onclick="showPage('${i}','${e.page_id}')">${e.page_name}</span></a></li>`;
		$("#pagesList").append(tablehtml);
		$("#bmr_pages").append(page_list);
	});
}

async function showPage(indexId, pageId) {
	$("#bmrdetailspage").show();
	$("#printBtn").show();
	if (pageContent != null) {
		if (indexId in pageContent) {
			let templateIndex = pageContent.findIndex(m => (m.page_id == pageId));
			let object = null;
			if (templateIndex !== -1) {
				object = pageContent[templateIndex];
			}
			if (object) {
				let page = object;
				let pageInput = await changeInputTextToHTML(page.keyPairs, page.dataset, page.keys, page.page_id, page.is_config, page.page_type, page.staticFields);
				let pageCode = page.html_code;


				if (pageInput.length > 0) {
					pageInput.map((rInp, ind) => {
						let replacekey = `<span class="inputfiled" style="background-color: rgb(255, 255, 0);">${rInp.key}</span>`;
						pageCode = pageCode.replace(replacekey, rInp.html);
					});
				}
				$("#report_page").html(pageCode);
				$("#page_id").val(pageId);
				$("#page_input").val(page.keys);
				$("#pageName").html(page.page_name);
				$("#bmr_pages").html('');
			}
		}
	}
}

async function changeInputTextToHTML(keyPairs, dataset, keys, page_id, is_config = false, page_type = 2, static_field = null) {
	let user_id = $("#user_id").val();
	let usertype = $("#type").val();
	let keyPairValue = getInputFilledValue(dataset, keys);
	let keyPairInputArr = [];

	if (keyPairs.length > 0) {
		let formulas = await getFormula(keyPairs);

		return Promise.all(keyPairs.map(async (inp, index, arr) => {
			let options = '';
			let onchange = '';
			if (inp.length > 3) {
				if (inp[1] != '' && inp[1] != null) {
					let type = inp[1];
					let inputValue = '';
					let readOnly = 'readOnly';
					if (typeof keyPairValue[inp[0]] !== 'undefined') {
						inputValue = keyPairValue[inp[0]];
					}

					if (inp[7] != null && inp[7] != '' && inp[7] !== 'none') {
						inputValue = await getPageLabelDataShow(inp[7], inp[8], inp[9]);
					}
					if (usertype == 1) {
						readOnly = '';
					} else {
						if (page_type == 1) {
							readOnly = '';
							if (static_field != null) {
								if (static_field.includes(inp[0])) {
									readOnly = 'readonly';
								}
							}
						} else {
							if (inp[3] != '' && inp[3] != null) {
								let accessUser = inp[3].split('-');
								if (accessUser.length > 1) {
									if (user_id === accessUser[0]) {
										readOnly = '';
									}
								}
							}
						}
					}

					if (formulas.hasOwnProperty('formulas') && formulas.formulas[0] != undefined) {
						if (formulas.formulas[0].includes(index)) {
							onchange = `onkeyup="getCalculatedValue('${formulas.formula_string}','${formulas.inputVal}','${btoa(JSON.stringify(keyPairs))}')"`;
						}
					}

					switch (type) {
						case "text":

							if (inp[2].includes('select') && inp[2] !== '' && inp[2] !== null) {
								inputValue = await getColumnNames(inp[2], inputValue, page_id, 1);
							}

							if (inp[2].includes('#') && inp[2] !== '' && inp[2] !== null && !inp[2].includes('select')) {
								inputValue = await getParamValue(inp[2]);
							}

							if (is_config === true) {
								inputValue = '';
							}

							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="text" name="${inp[0]}" ${onchange} id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Fill Field" class="form-control">`
							});
							break;
						case "label":

							if (inp[2].includes('select') && inp[2] !== '' && inp[2] !== null) {
								inputValue = await getColumnNames(inp[2], inputValue, page_id, 1);
							}

							if (inp[2].includes('#') && inp[2] !== '' && inp[2] !== null && !inp[2].includes('select')) {
								inputValue = await getParamValue(inp[2]);
							}

							if (is_config === true) {
								inputValue = '';
							}

							keyPairInputArr.push({
								"key": inp[0],
								"html": `<span id="${inp[0]}">${inputValue}</span>`
							});
							break;
						case "number":

							if (inp[2].includes('select') && inp[2] !== '' && inp[2] !== null) {
								inputValue = await getColumnNames(inp[2], inputValue, 1);
							}

							if (inp[2].includes('#') && inp[2] !== '' && inp[2] !== null && !inp[2].includes('select')) {
								inputValue = await getParamValue(inp[2]);
							}

							if (is_config === true) {
								inputValue = '';
							}

							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="number" name="${inp[0]}" ${onchange} id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Fill Field" class="form-control">`
							});
							break;
						case "date":

							if (inp[2].includes('select') && inp[2] !== '' && inp[2] !== null) {
								inputValue = await getColumnNames(inp[2], inputValue, 1);
							}

							if (inp[2].includes('#') && inp[2] !== '' && inp[2] !== null && !inp[2].includes('select')) {
								inputValue = await getParamValue(inp[2]);
							}

							if (is_config === true) {
								inputValue = '';
							}

							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="date" name="${inp[0]}" ${onchange} id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Fill Field" class="form-control">`
							});
							break;
						case "file":
							let filesAng = '';
							let fileNames = '';
							if (inputValue != "" && inputValue != null) {
								fileNames = await getAwsLinkToDownload(inputValue);
								if (fileNames.length > 0) {
									fileNames.map((e, index) => {
										console.log(e);
										filesAng += `<a class="btn btn-link" href="${e.urlPath}" download><i class="fa fa-download"></i> ${e.filename}</a> `;
									});
								}
							}

							if (is_config === true) {
								inputValue = '';
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<div>${filesAng}</div>
											<input type="file" name="${inp[0]}[]" id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Fill Field" class="form-control" multiple>`
							});
							break;
						case "table":
							const InputKey = inp[0];
							if (InputKey.includes('histable')) {
								let hisData = await getHistoryTable();
								keyPairInputArr.push({"key": inp[0], "html": hisData});
							} else if (InputKey.includes('materialTable')) {
								let materialData = await getMaterialTable();
								keyPairInputArr.push({"key": inp[0], "html": materialData});
							} else if (InputKey.includes('indentTable')) {
								if (usertype == 2) {
									let indentData = await getIndentTable();
									keyPairInputArr.push({"key": inp[0], "html": indentData});
								}
							} else if (InputKey.includes('processChart')) {
								if (usertype == 2) {
									let indentData = await getProcessFlowChart();
									keyPairInputArr.push({"key": inp[0], "html": indentData});
								}
							}
							break;
						case "dropdown":
							if (inp[2].includes('select')) {
								$("#" + inp[0]).val(keyPairValue).trigger('change');
								options = await getColumnNames(inp[2], inputValue, 2);

							} else {
								let inputValueArr = inp[2].split(',');
								if (inputValueArr.length > 0) {
									inputValueArr.map(e => {
										let selected = '';
										if (inputValue == e) {
											selected = "selected";
										}

										if (is_config === true) {
											selected = '';
										}
										options += `<option ${selected} value="${e}">${e}</option>`;
									});
								}
							}
							keyPairInputArr.push({
								"key": inp[0],
								"html": `<select name="${inp[0]}" id="${inp[0]}" class="form-control">${options}</select>`
							});
							break;
						case "calculated":

							if (is_config === true) {
								inputValue = '';
							}

							keyPairInputArr.push({
								"key": inp[0],
								"html": `<input type="text" name="${inp[0]}" id="${inp[0]}" value="${inputValue}" ${readOnly} placeholder="Fill Field" class="form-control">`
							});
							break;
						case "checkbox":
							let checkValueArr = inp[2].split(',');
							let checkboxes = '';
							let checkboxInputArr = inputValue.split(',');
							if (checkValueArr.length > 0) {
								checkValueArr.map((e, indexC) => {
									let selected = '';
									if (checkboxInputArr.includes(e)) {
										selected = "checked";
									}
									if (is_config === true) {
										selected = '';
									}
									checkboxes += `&nbsp;&nbsp;&nbsp;<input type="checkbox" name="${inp[0]}[]" id="${inp[0] + indexC}" ${selected} value="${e}" ${readOnly} placeholder="Write here..." class="form_control mr-1">${e}`;
								});
							}
							keyPairInputArr.push({"key": inp[0], "html": `${checkboxes}`});
							break;
					}
				}
			}
		})).then(e => {
			return keyPairInputArr;
		});
	} else {
		return keyPairInputArr;
	}
}

function getInputFilledValue(dataset, keys) {
	let keyPairValue = [];
	if (dataset.length > 0) {
		$.each(dataset, function (index, obj) {
			$.each(obj, function (attr, value) {
				for (let k in value) {
					keyPairValue[k] = value[k].value;
				}
			});
		});
	}
	return keyPairValue;
}

function saveReportPageData() {
	let form = document.getElementById('saveReportPage');
	let formData = new FormData(form);
	formData.set('report_id', $("#report_id").val());
	formData.set('type', $("#type").val());
	app.request("saveReportPageData", formData).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);
			getReportData();
		}
	}).catch(error => console.log(error));
}

function getHistoryTable() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getHistoryTable", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getMaterialTable() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getMaterialTable", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getIndentTable() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getIndentTable", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getProcessFlowChart() {
	return new Promise(function (resolve, reject) {
		let report_id = $("#report_id").val();
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('report_id', report_id);
		formdata.set('type', type);
		app.request("getProcessFlowChart", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

function getColumnNames(query, inputValue, page_id, type = null) {
	return new Promise(function (resolve, reject) {
		let params = $("#queryParameters").val();
		let id = $("#report_id").val();
		let page_type = $("#type").val();
		let formdata = new FormData();
		formdata.set('query', query);
		formdata.set('inputValue', inputValue);
		formdata.set('type', type);
		formdata.set('params', params);
		formdata.set('page_id', page_id);
		formdata.set('id', id);
		formdata.set('page_type', page_type);
		app.request("getColumnNames", formdata).then(res => {
			if (res.status === 200) {

				resolve(res.data);
			} else {
				resolve(res.data);
			}
		}).catch(error => console.log(error));
	});
}

function getColumnNamesData(id) {

	let formData = new FormData();
	formData.set('id', id);
	app.request("getColumnNamesData", formData).then(res => {
		if (res.status === 200) {
			app.successToast(res.body);

		} else {
			app.errorToast(res.body);
		}
	}).catch(error => console.log(error));
}


function getFormula(keyPairs) {
	let formulas = '';
	let formula_string = '';
	let inputVal = '';
	keyPairs.map((r, i) => {
		if (r[1] === 'calculated') {
			formulas = r[4];
			formula_string = r[4];
			inputVal = r[0];
			formulas = formulas.replace(/#/g, "");

			formulas = formulas.split(')').join('').split('(').join('').split('+').join(',').split('-').join(',').split('*').join(',').split('/');

		}
	})
	return {
		formulas: formulas,
		formula_string: formula_string,
		inputVal: inputVal
	};
}

function getCalculatedValue(formula_string, inputVal, keyPairs) {
	let Kp = JSON.parse(atob(keyPairs));
	let formula_s = formula_string.split(')').join('').split('(').join('').split('+').join(',').split('-').join(',').split('*').join(',').split('/');
	formula_s = formula_s[0].split(',');
	// console.log(formula_s);
	formula_s.map(r => {

		let hashVal = r.split('#');

		let val = $("#" + Kp[hashVal[1]][0]).val();

		if (val != null && val != '') {
			val = val;
		} else {
			val = 0;
		}
		// let re = new RegExp(`${r}`, '');
		formula_string = formula_string.replaceAll(r, val);
	})
	let value = eval(formula_string);
	$("#" + inputVal).val(value);
}

function getReportPageData() {
	$("#pagesList").html('');
	let bmr_id = $("#bmr_id").val();
	let type = $("#type").val();
	let formdata = new FormData();
	formdata.set('bmr_id', bmr_id);
	formdata.set('type', type);
	app.request("getReportPageData", formdata).then(res => {
		if (res.status === 200) {
			showPagesList(res.body.code);
		}
	}).catch(error => console.log(error));
}

function getPageLabelDataShow(grouplist, pagelist, pagecontrol) {
	return new Promise(function (resolve, reject) {
		let inputValue = '';
		let datavalue = '';
		let group = grouplist.split('-');
		let group_id = group[0];
		let type = $("#type").val();
		let formdata = new FormData();
		formdata.set('bmr_id', group_id);
		formdata.set('type', type);

		app.request("getReportPageData", formdata).then(res => {
			if (res.status === 200) {
				let pdata = res.body.code;

				var url = $(location).attr('href').split(base_url).join('').split('/');
				let product_id = '';
				if (url.length === 4) {
					product_id = parseInt(url[3]);
				}


				pdata = JSON.parse(pdata);
				let pageCont = pdata[0].pages;
				pagelist = pagelist.split('-');
				pageCont.map(r => {
					if (r.page_name === pagelist[1]) {
						let keyPairs = r.keyPairs;
						keyPairs.map(rs => {

							if (rs[6] === pagecontrol) {
								inputValue = rs[0];
							}
						});

						let dataset = r.dataset;
						for (let i = 0; i < dataset.length; i++) {
							dataset[i].map(r => {
								if (product_id !== '') {
									if (dataset[i][dataset[i].length - 1].product_id.value === product_id) {
										for (let k in r) {
											if (k === inputValue) {
												datavalue = r[k].value;
											}
										}
									}
								} else {
									for (let k in r) {
										if (k === inputValue) {
											datavalue = r[k].value;
										}
									}
								}
							});
						}
					}
				});
				resolve(datavalue);
			}
		}).catch(error => console.log(error));
	});
}

function getQueryParam() {
	let id = $("#report_id").val();
	let type = $("#type").val();
	var url = $(location).attr('href').split(base_url).join('').split('/');
	let formdata = new FormData();
	formdata.set('id', id);
	formdata.set('type', type);
	formdata.set('url', url);
	app.request("getQueryParamData", formdata).then(res => {
		if (res.status === 200) {
			$("#queryParameters").val('');
			let params = res.params;
			params = btoa(JSON.stringify(params));
			$("#queryParameters").val(params);
		}
	}).catch(error => console.log(error));
}


function getParamValue(val) {
	let paramsData = $("#queryParameters").val();
	paramsData = JSON.parse(atob(paramsData));
	let name = val.split('#');
	let n = '';
	if (name.length > 0) {
		n = name[1];
	}
	return paramsData[n];
}

function getAwsLinkToDownload(file) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('file', file);
		app.request("getAwsLinkToDownload", formdata).then(res => {
			if (res.status === 200) {
				resolve(res.body);
			} else {
				resolve(res.body);
			}
		}).catch(error => console.log(error));
	});
}

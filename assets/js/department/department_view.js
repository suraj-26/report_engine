$(document).ready(function () {
	loadDepartment();
	getAllCompanies();
	$('#fire-modal-department').on('show.bs.modal', function (e) {
		$('#uploadDepartment').trigger('reset');

		$('#uploadDepartment')[0].reset();
		$('#dcompany_id').val('');
		$('#forward_department').val('');
		app.formValidation();
	});

	$('#fire-modal-users').on('show.bs.modal', function (e) {
		$('#uploadUsers').trigger('reset');

		$('#uploadUsers')[0].reset();
		$('#uAllCompanies').val('');
		// $('#dcompany_id').select2();
		$('#forward_user').val('');
		$('#roleDepartment').empty();
		app.formValidation();
	});


});

function checkTemplateType(button)
{
	// alert(button);
	//$("#"+button).val('off');
	if(button=='ch_free_template')
	{
		$("#ch_admin_template").prop('checked','checked');
		$("#ch_free_template").prop('checked',false);
	}else
	if(button=='ch_admin_template')
	{
		$("#ch_admin_template").prop('checked',false);
		$("#ch_free_template").prop('checked','checked');
	}
}
var depChk = '';

function serverRequest(requestURL, dataObject) {

	return new Promise((resolve, reject) => {
		$.ajax({
			type: "POST",
			url: baseURL + requestURL,
			dataType: "json",
			data: dataObject,
			success: function (result) {
				resolve(result);
			}, error: function (error) {
				console.log("Error in Server Request Function : ", error);
				reject('Something went wrong please try again');

			}
		});
	});
}

function loadDepartment(type = 1, companyId = null) {
	if (companyId != null) {
		if (parseInt(companyId) === 0) {
			companyId = null;
		}
	}
	app.dataTable("departmentTable", {
		url: baseURL+ "getDepartments",
		data: {
			type: type, companyId: companyId
		}
	}, undefined, (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {

		let status = parseInt(aData[3]);
		let value;
		if (status === 1) {
			value = `<div class="badge badge-pill badge-success ml-2" onclick="ChangeDepartmentStatus(${aData[6]},0)" style="cursor: pointer">Active</div>`;
		} else {
			value = `<div class="badge badge-pill badge-danger ml-2" onclick="ChangeDepartmentStatus(${aData[6]},1)" style="cursor: pointer">Inactive</div>`;
		}
		$('td:eq(3)', nRow).html(value);
		$('td:eq(4)', nRow).html(`<button class="btn btn-primary btn-action mr-1" type="button"                                         
                                         data-toggle="modal" data-target="#fire-modal-company" data-id="${aData[5]}"
                                         onclick="get_DepartmentDataById(${aData[6]}),modalName()" >
                                 			<i class="fas fa-pen"></i>
                           				 </button>
                           				 <button class="btn btn-primary btn-action mr-1" type="button"                                         
                                         onclick="setDepartment(${aData[6]},${aData[7]})"
                                         >
                                 			 <i class="fab fa-wpforms"></i>
                            			 </button>
                            			 <a href="${base_url}form_view/${aData[8]}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                            `);
	});
}
function setDepartment(id,is_admin) {
	localStorage.setItem("Department_id", id);
	if(is_admin==2)
	{
		window.location.href = baseURL + "personal_template";
	}
	else
	{
		window.location.href = baseURL + "form_template";
	}

}

function uploadDepartment(form) {

	app.request(baseURL + "saveDepartment", new FormData(form)).then(result => {
		if (result.status === 200) {
			app.successToast('uploaded successfully');
			loadDepartment();
			modalName();
		} else {
			app.errorToast('Not uploaded ');
		}
	}).catch(error => console.log(error));
}

function ChangeDepartmentStatus(depId, status) {
	let formData = new FormData();
	formData.set("depId", depId);
	formData.set("status", status);
	app.request(baseURL + "ChangeDepartmentStatus", formData).then(success => {
		if (success.status === 200) {
			app.successToast(success.body);
			loadDepartment();
		} else {
			app.errorToast(success.body);
		}
	}).catch(error => console.log(error));
}

function modalName() {
	$("#addDepartment").click();
}


function getAllCompanies() {
	app.request(baseURL + "fetchAllCompanies", null).then(result => {
		if (result.status === 200) {
			// above table view
			$("#allCompanies").empty();
			$("#allCompanies").append(result.option);
			// modal select element
			$("#dcompany_id").empty();
			$("#dcompany_id").append(result.option);
		} else {
			// above table view
			$("#allCompanies").empty();
			$("#allCompanies").append('<option>No data Found</option>');
			// modal select element
			$("#dcompany_id").empty();
			$("#dcompany_id").append('<option>No data Found</option>');
		}
	}).catch(error => console.log(error));

}

function getCompanyDepartment(companyId) {
	$.LoadingOverlay("show");
	$.ajax({
		type: "POST",
		url: base_url + 'DepartmentController/selectCompanyDepartment',
		dataType: "json",
		data: {companyId: companyId},

		success: function (result) {
			$.LoadingOverlay("hide");
			if (result.status === 200) {
				var user_data = result.data;

				$("#roleDepartment").html('');
				$("#roleDepartment").append(user_data);

			} else {
				app.errorToast('data not found ');

			}
		},
		error: function (error) {
			$.LoadingOverlay("hide");
			app.errorToast("something went wrong please try again");
		}
	});
}



function get_DepartmentDataById(depId) {
	$.LoadingOverlay("show");
	serverRequest(baseURL + "getDepartmentDataById", {depId: depId}).then(response => {

		$.LoadingOverlay("hide");
		if (response.status === 200) {
			var user_data = response.body;

			if (user_data[0]['is_admin'] != null && user_data[0]['is_admin'] != '') {
				let is_admin =parseInt(user_data[0]['is_admin']);
				if(is_admin===1){
					$("#ch_admin_template").prop('checked','checked');
					$("#ch_free_template").prop('checked',false);
				}else if(is_admin===2)
				{
					$("#ch_admin_template").prop('checked',false);
					$("#ch_free_template").prop('checked','checked');
				}

			}
			if (user_data[0]['name'] != null && user_data[0]['name'] != '') {
				$("#department_name").val(user_data[0]['name']);
			}
			if (user_data[0]['id'] != null && user_data[0]['id'] != '') {
				$("#forward_department").val(user_data[0]['id']);

			}
			if (user_data[0]['company_id'] != null && user_data[0]['company_id'] != '') {
				let comp = user_data[0]['company_id'];

				$("#dcompany_id option[value='" + comp + "']").prop("selected", true);
			}
			if (user_data[0]['description'] != null && user_data[0]['description'] != '') {
				$("#department_description").val(user_data[0]['description']);

			}

		} else {
			app.errorToast('data not found ');
		}

	}).catch(error => console.log(error));
}

function deleteDeaprtment(depId) {
	$.LoadingOverlay("show");
	serverRequest("DepartmentController/deleteDeaprtment", {depId: depId}).then(response => {
		$.LoadingOverlay("hide");
		if (response.status === 200) {
			app.successToast(response.body);
			loadDepartment();
		} else {
			app.errorToast(response.body);
		}
	}).catch(error => console.log(error));
}

function getUsersTableData(type = 1, companyId = null) {

	serverRequest("DepartmentController/getUsersTableData", {type: type, companyId: companyId}).then(response => {
		$("#usersTable").DataTable({
			"order": [[2, "asc"]],
			"bSort": false,
			destroy: true,
			// "bFilter": true,
			data: response.data,
			pagingType: 'full_numbers',
			columns: [
				{
					data: 0,
					render: (d, t, r, m) => {
						return `
                                <div class="widget-content">
                                    <div class="widget-heading">
                                       ${d}
                                    </div>
                                </div>
                            `;
					}

				},
				{
					data: 1,
					render: (d, t, r, m) => {
						return `<div>${d}</div>`;
					}
				},
				{
					data: 2,
					render: (d, t, r, m) => {
						return `<div>${d}</div>`;
					}
				},

				{
					data: 3,
					render: (d, t, r, m) => {
						return `<a class="btn btn-primary btn-action mr-1" data-toggle="modal" data-target="#fire-modal-privileges" data-id="2" >
                                                    <i class="fas fa-user-tag"></i>
                        </a>
                        <a class="btn btn-primary btn-action mr-1" data-toggle="modal" data-target="#fire-modal-users" data-id="2" onClick="get_usersDataById(${d})">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                         <a class="btn btn-danger btn-action"
                           data-toggle="tooltip" title=""
                           data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                           data-confirm-yes="deleteUser(${d})"
                            data-original-title="Delete">
                        <i class="fas fa-trash"></i></a>`;
					}
				}

			]


		});
	}).catch(error => console.log(error));
}

$("#uploadUsers").validate({

	rules: {
		uAllCompanies: 'required',
		user_name: 'required',
		user_email: 'required',
		password: 'required',
	},
	messages: {
		uAllCompanies: "Select customer",
		user_name: "Enter user Name",
		user_email: "Enter user email",
		password: 'Enter Password',
	},
	errorElement: 'span',

	submitHandler: function (form) {
		$.LoadingOverlay("show");

		var form_data = document.getElementById('uploadUsers');
		var Form_data = new FormData(form_data);

		$.ajax({
			type: "POST",
			url: base_url + 'DepartmentController/uploadUsers',
			dataType: "json",
			data: Form_data,
			contentType: false,
			processData: false,
			success: function (result) {
				$.LoadingOverlay("hide");
				if (result.status === 200) {

					app.successToast(result.data);
					getUsersTableData();
					$("#userAdd").click();

				} else {
					app.errorToast(result.data);
				}
			},
			error: function (error) {
				$.LoadingOverlay("hide");
				app.errorToast("something went wrong please try again");
			}
		});
	}
});









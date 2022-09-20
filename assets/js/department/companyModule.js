  $(document).ready(function () {
   //getDepartment();
   loadDepartment();
   getAllCompanies();
   getUsersTableData();
   $('#fire-modal-department').on('show.bs.modal', function (e) {
            $('#uploadDepartment').trigger('reset');

            $('#uploadDepartment')[0].reset();
            $('#dcompany_id').val('');
           // $('#dcompany_id').select2();
            $('#forward_department').val('');
    });

   $('#fire-modal-users').on('show.bs.modal', function (e) {
            $('#uploadUsers').trigger('reset');

            $('#uploadUsers')[0].reset();
            $('#uAllCompanies').val('');
           // $('#dcompany_id').select2();
            $('#forward_user').val('');
            $('#roleDepartment').empty();
    });


  });



  var depChk='';
  var base_url='http://localhost/new_covid/';
 function serverRequest(requestURL, dataObject) {

        return new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: base_url + requestURL,
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

function loadDepartment(type=1,companyId=null)
{

        serverRequest("DepartmentController/getDepartmentTableData", {type:type,companyId:companyId}).then(response => {
            $("#departmentTable").DataTable({
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
                            let status = parseInt(d);
                            let value ; if(status==1){value="Active"}else{value="Inactive"}
                            return `<div class="badge badge-pill badge-info ml-2">${value}</div>`;
                        }
                    },
                   
                    {
                        data: 5,
                        render: (d, t, r, m) => {
                            return `<button class="btn btn-primary btn-action mr-1" type="button"
                                        data-toggle="tooltip" data-placement="left" title data-original-title="View Detail"
                                        onclick="get_DepartmentDataById(${d}),modalName()"
                                 ">
                                 <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-danger btn-action trigger--fire-modal-1" type="button"
                                       data-toggle="tooltip" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="deleteDeaprtment(${d})" data-original-title="Delete" onclick="deleteDeaprtment(${d})"
                                 ">
                                 <i class="fas fa-trash"></i>
                            </button>`;
                        }
                    }

                ]


            });
        }).catch(error => console.log(error));

    }
//  function uploadDepartment(form)
//  {
    
//     app.request("DepartmentController/uploadDepartment",new FormData(form)).then(success=>{
//                         if (success.status === 200) {
                               
//                                 app.successToast(success.body);
                                
//                             } else {
//                                 app.errorToast(success.body);
                               
//                             }
                
//         }).catch(error=>console.log(error));
// }

function modalName()
{
    $("#addDepartment").click();
}
$("#uploadDepartment").validate({

        rules: {
            customer_name: 'required',
            department_name: 'required',

        },
        messages: {
            customer_name: "Select customer",
            department_name: "Enter department Name",

        },
        errorElement: 'span',

        submitHandler: function (form) {
           // $.LoadingOverlay("show");

            var form_data = document.getElementById('uploadDepartment');
            var Form_data = new FormData(form_data);

            $.ajax({
                type: "POST",
                url: 'http://localhost/new_covid/DepartmentController/uploadDepartment',
                dataType: "json",
                data: Form_data,
                contentType: false,
                processData: false,
                success: function (result) {
                   // $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    if (result.status === 200) {

                        app.successToast('uploaded successfully');
                        loadDepartment();
                        modalName();
                    } else {
                        app.errorToast('Not uploaded ');
                    }
                },
                error: function (error) {
                   // $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    app.errorToast("something went wrong please try again");
                }
            });
        }
    });
function getAllCompanies()
{
     $.ajax({
                type: "POST",
                url: base_url+'DepartmentController/selectAllCompanies',
                dataType: "json",
                data:'',
                contentType: false,
                processData: false,
                success: function (result) {
                    
                    if (result.status === 200) {
                        var user_data=result.data;
                       // console.log(user_data);
                        // var option='';
                        // for(var i=0;i<=user_data.length;i++)
                        // {
                        //     console.log(user_data[i]);
                        //     option += '<option value='+user_data[i]['id']+'>'+user_data[i]["name"]+'</option>';
                        // }
                        $("#dcompany_id").empty();
                        $("#allCompanies").empty();
                        $("#uAllCompanies").empty();
                        //$("#uAllCompanies").empty();
                        $("#userCompany").empty();

                        $("#dcompany_id").append(result.option);
                        $("#allCompanies").append(result.option);
                        $("#uAllCompanies").append(result.option);
                       // $("#uAllCompanies").append(result.option);
                        $("#userCompany").append(result.option);
                        

                    } else {
                       // toastr.error('data not found ');
                       $("#dcompany_id").append('<option>No data Found</option>');
                       $("#allCompanies").append('<option>No data Found</option>');
                       $("#uAllCompanies").append('<option>No data Found</option>');
                        $("#userCompany").append('<option>No data Found</option>');
                    }
                },
                error: function (error) {
                  //  $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                   app.errorToast("something went wrong please try again");
                }
            });
}
function getCompanyDepartment(companyId)
{
     $.ajax({
                type: "POST",
                url: base_url+'DepartmentController/selectCompanyDepartment',
                dataType: "json",
                data:{companyId:companyId},
               
                success: function (result) {
                    
                    if (result.status === 200) {
                        var user_data=result.data;
                       
                        $("#roleDepartment").html('');
                        $("#roleDepartment").append(user_data);

                    } else {
                       // toastr.error('data not found ');
                      
                    }
                },
                error: function (error) {
                  //  $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                   app.errorToast("something went wrong please try again");
                }
            });
}
function checkDepartment(source)
{
     checkboxes = document.getElementsByName('depCheck[]');
      for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
      }
}
function get_DepartmentDataById(depId)
{

    $.LoadingOverlay("show");
    $.ajax({
                type: "POST",
                url: base_url+'DepartmentController/getDepartmentDataById',
                dataType: "json",
                data: {depId:depId},
                success: function (result) {
                    $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    if (result.status === 200) {
                        var user_data=result.body;
                        //console.log(user_data);
                        
                            if(user_data[0]['name']!=null && user_data[0]['name']!='')
                            {
                                $("#department_name").val(user_data[0]['name']);
                           
                            }
                             if(user_data[0]['id']!=null && user_data[0]['id']!='')
                            {
                                $("#forward_department").val(user_data[0]['id']);
                           
                            }
                            if(user_data[0]['company_id']!=null && user_data[0]['company_id']!='')
                            {
                                let comp = user_data[0]['company_id'];
                            
                                $("#dcompany_id option[value='" + comp + "']").prop("selected", true);
                            }
                            if(user_data[0]['description']!=null && user_data[0]['description']!='')
                            {
                                $("#department_description").val(user_data[0]['description']);
                           
                            }
                        
                    } else {
                        app.errorToast('data not found ');
                    }
                },
                error: function (error) {
                    $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    app.errorToast("something went wrong please try again");
                }
            });
}

function deleteDeaprtment(depId)
{
     $.LoadingOverlay("show");
    $.ajax({
                type: "POST",
                url: base_url+'DepartmentController/deleteDeaprtment',
                dataType: "json",
                data: {depId:depId},
                success: function (result) {
                    $.LoadingOverlay("hide");
                    if (result.status === 200) {
                      app.successToast(result.body);
                       loadDepartment();
                    } else {
                        app.errorToast(result.body);
                    }
                },
                error: function (error) {
                    $.LoadingOverlay("hide");
                    app.errorToast("something went wrong please try again");
                }
            });
}

function getUsersTableData(type=1,companyId=null) {

    serverRequest("DepartmentController/getUsersTableData", {type:type,companyId:companyId}).then(response => {
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
            password:'required',
        },
        messages: {
            uAllCompanies: "Select customer",
            user_name: "Enter user Name",
            user_email: "Enter user email",
            password:'Enter Password',
        },
        errorElement: 'span',

        submitHandler: function (form) {
            $.LoadingOverlay("show");

            var form_data = document.getElementById('uploadUsers');
            var Form_data = new FormData(form_data);

            $.ajax({
                type: "POST",
                url: base_url+'DepartmentController/uploadUsers',
                dataType: "json",
                data: Form_data,
                contentType: false,
                processData: false,
                success: function (result) {
                    $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
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
//                                                                $("#loader12").hide();
                    app.errorToast("something went wrong please try again");
                }
            });
        }
    });

    function get_usersDataById(userId)
{
    $.LoadingOverlay("show");
    $.ajax({
                type: "POST",
                url: base_url+'DepartmentController/getUserDataById',
                dataType: "json",
                data: {userId:userId},
               
                success: function (result) {
                    $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    if (result.status === 200) {
                        var user_data=result.body;
                        console.log(user_data);
                        if(user_data[0]['id']!=null && user_data[0]['id']!='')
                            {
                                $("#forward_user").val(user_data[0]['id']);
                            }
                        if(user_data[0]['name']!=null && user_data[0]['name']!='')
                            {
                                $("#user_name").val(user_data[0]['name']);
                            }
                        
                        if(user_data[0]['company_id']!=null && user_data[0]['company_id']!='')
                        {
                            let comp = user_data[0]['company_id'];
                        
                            $("#uAllCompanies option[value='" + comp + "']").prop("selected", true);
                        }
                        if(user_data[0]['user_name']!=null && user_data[0]['user_name']!='')
                        {
                            $("#user_email").val(user_data[0]['user_name']);
                       
                        }
                        if(user_data[0]['password']!=null && user_data[0]['password']!='')
                        {
                            $("#user_pass").val(user_data[0]['password']);
                       
                        }
                        if(user_data[0]['alldepartments']!=null && user_data[0]['alldepartments']!='')
                        {
                             $("#roleDepartment").empty();
                            var str=user_data[0]['alldepartments'];
                            var dep = str.split(",");
                            depChk ='<div class="form-check">'
                                    +' <input class="form-check-input" type="checkbox"  onClick="checkDepartment(this)" id="defaultCheck">'
                                    +'  <label class="form-check-label" for="defaultCheck">'
                                     +'     All'
                                    +' </label>'
                                     +' </div>';
                                     var dep2='';
                                     if(user_data[0]['udepartment']!=null && user_data[0]['udepartment']!='')
                                     {
                                        var str2= user_data[0]['udepartment'];
                                        if(str2!=''){
                                         var dep2 = str2.split(",");
                                        }
                                     }
                                      
                                       $("#roleDepartment").append(depChk);
                                     for (var i = 0; i < dep.length; i++) {
                                      
                                      
                                         departmentCheckbox(dep[i],dep2);
                                    } 
                            //dep.forEach(departmentCheckbox());
                       
                        }
                    } else {
                        toastr.error('data not found ');
                    }
                },
                error: function (error) {
                    $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    toastr.error("something went wrong please try again");
                }
            });
}


function departmentCheckbox(dep,dep_data)
{
    //console.log(dep_data);
    var dep1 = dep.split("|||");
     var check='';
     if(dep_data!=''){
         for(var k=0;k<dep_data.length;k++)
        {
           var dep2= dep_data[k].split("|||");
            
           
            if(dep2[0]==dep1[0]){
             check= "checked";
            }
            

        }
    }

    
    depChk ='<div class="form-check">'
                +'<input class="form-check-input" name="depCheck[]" multiple type="checkbox" id="defaultCheck'+dep1[0]+'" value="'+dep1[0]+'" '+check+'>'
                +'<label class="form-check-label" for="defaultCheck'+dep1[0]+'">'
                +' ' + dep1[1] + ' '
                +' </label>'
                +'</div>';
                //$("#roleDepartment").append(depChk);
   // var element = document.getElementById('defaultCheck'+dep1[0]);
   
    
    $("#roleDepartment").append(depChk);
}
function deleteUser(userId)
{
     $.LoadingOverlay("show");
    $.ajax({
                type: "POST",
                url:  base_url+'DepartmentController/deleteUser',
                dataType: "json",
                data: {userId:userId},
               
                success: function (result) {
                    $.LoadingOverlay("hide");
                    if (result.status === 200) {
                      toastr.success(result.body);
                    } else {
                        toastr.error(result.body);
                    }
                },
                error: function (error) {
                    $.LoadingOverlay("hide");
                    toastr.error("something went wrong please try again");
                }
            });
}

$("#uploadUserDepartments").validate({

        rules: {
            user: 'required',
            departments: 'required'
        },
        messages: {
            user: "Select user",
            departments: "select departments"
        },
        errorElement: 'span',

        submitHandler: function (form) {
            $.LoadingOverlay("show");

            var form_data = document.getElementById('uploadUserDepartments');
            var Form_data = new FormData(form_data);

            $.ajax({
                type: "POST",
                url: "<?= base_url('DepartmentController/uploadUserDepartments') ?>",
                dataType: "json",
                data: Form_data,
                contentType: false,
                processData: false,
                success: function (result) {
                    $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    if (result.status === 200) {

                        toastr.success('uploaded successfully');

                    } else {
                        toastr.error('Not uploaded ');
                    }
                },
                error: function (error) {
                    $.LoadingOverlay("hide");
//                                                                $("#loader12").hide();
                    toastr.error("something went wrong please try again");
                }
            });
        }
    });
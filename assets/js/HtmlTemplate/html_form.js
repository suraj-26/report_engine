function get_forms(section_id, type = 0, queryparameter_hidden = null, department_id = null, url = null,divId='form_data') {
console.log(queryparameter_hidden);

    $("#form_data_report").html("");

    // console.log(section_id);
    if (queryparameter_hidden == null) {

        var queryparameter_hidden = $("#queryparameter_hidden").val();
    }else{
        $("#queryparameter_hidden").val(queryparameter_hidden);
    }

    if (url != null) {
        $("#printButton").hide();
        $("#CloseBillButton").hide();
        localStorage.setItem("section_type", 1);
        $(".a_menu").css({
            "text-decoration": 'none',
            "color": 'black'
        });
        $('.li_menu').addClass('menu-header_section1').removeClass('menu-header_section active');
        $('#a_menu_' + section_id).css({
            "text-decoration": 'none',
            "color": 'white'
        });
        $("#li_menu_" + section_id).addClass('menu-header_section active').removeClass('menu-header_section1');
        window.history.pushState('', '', atob(url));
        $("#section_id").val(section_id);
        $("#department_id").val(department_id);
        $("#queryparameter_hidden").val(queryparameter_hidden);

    }
    $.ajax({
        url: base_url + "getHtmlTemplateForm",
        type: "POST",
        dataType: "json",
        data: {section_id: section_id, queryparameter_hidden: queryparameter_hidden},
        success: function (result) {
            var data = result.data;
            if (result['status'] === 200) {
                $('#section_name').html(data.name);

                if (type == 0) {
                    $("#"+divId).html(data.section_html);
                } else {
                    $("#"+divId+"1").html(data.section_html);
                    var insert_id = localStorage.getItem("insert_id");
                    if (insert_id != "") {
                        $('#form_' + section_id).append('<input type="hidden" id="transaction_id" name="transaction_id" value="' + insert_id + '" />');
                    }

                }
                if (section_id == 87) {
                    $(".card_head_back87").remove();
                }
                if (section_id == 93) {
                    $(".card_head_back93").remove();
                }
                if (section_id == 96) {
                    $(".card_head_back96").remove();
                }
                if (section_id == 108) {
                    $(".card_head_back108").remove();
                }

                if (section_id == 128) {
                    $(".card_head_back128").remove();
                }

                if (section_id == 129) {
                    $(".card_head_back129").remove();
                }

                if (section_id == 132) {
                    $(".card_head_back132").remove();
                }
                if (section_id == 135) {
                    $(".card_head_back135").remove();
                }
                if (section_id == 140) {
                    $(".card_head_back140").remove();
                }
                if (section_id == 141) {
                    $(".card_head_back141").remove();
                }
                if (section_id == 143) {
                    $(".card_head_back143").remove();
                }
                if (section_id == 137) {
                    $(".card_head_back137").remove();
                }
                if (section_id == 138) {
                    $(".card_head_back138").remove();
                }
                if (section_id == 139) {
                    $(".card_head_back139").remove();
                }
                if (section_id == 144) {
                    $(".card_head_back144").remove();
                }

                var typeArray = data.html_section_types;
                var sectionArray = data.html_section_text;
                const anyonedepArr = typeArray.split(",");
                if (anyonedepArr != "") {
                    var anyonedepIndex = [];
                    for (var i = 0; i < anyonedepArr.length; i++) {
                        if (anyonedepArr[i] == 8) {
                            var depindex = typeArray.split(",").findIndex((e) => {
                                return parseInt(e) === 8;
                            });
                            anyonedepIndex.push(depindex);
                        }
                    }
                    if (anyonedepIndex.length > 0) {
                        for (var j = 0; j < anyonedepIndex.length; j++) {
                            if (anyonedepIndex[j] !== -1) {
                                var queryHashId = sectionArray.split(",")[anyonedepIndex[j]];
                                getAynoneDependantOnQueryDropdown(queryHashId, section_id, 0);
                            }
                        }
                    }
                }
                if (parseInt(data.history_unabled) === 1) {
                    var index = typeArray.split(",").findIndex((e) => {
                        return parseInt(e) === 18;
                    });
                    if (index !== -1) {
                        var dataTableElementId = sectionArray.split(",")[index];
                        loadDataTable(dataTableElementId, section_id, 1);
                    }

                }
                var index1 = typeArray.split(",").findIndex((e) => {
                    return parseInt(e) === 20;
                });
                if (index1 !== -1) {
                    var dataTableElementId1 = sectionArray.split(",")[index1];
                    ShowReportDataTable(dataTableElementId1, section_id);
                }
                // app.formValidation();

                //append form DataTable
                var Update_data = result.Update_data;
                Update_data = Update_data.data;
                var Mapping_Data = result.Mapping_Data;
                if (Mapping_Data != "") {
                    var array_string = (Mapping_Data.data.array_string).split("|");
                    array_string.map(a => {
                        var arr = a.split(":");
                        var column_name = arr[0];
                        var hash_name = arr[1];
                        if (hash_name.includes("#date_")) {
                            $(hash_name).val(getDate(Update_data[column_name]));
                        } else {
                            var string_check = "checkbox";
                            var string_num = "number";
                            var Stringquerydropdown = "querydropdown";
							var Sdropdown = "dropdown";
                            if (hash_name.indexOf(string_check) !== -1) {
                                $(hash_name).prop('checked', true);

                            } else if (hash_name.indexOf(string_num) !== -1) {
                                hash_name = hash_name.replace('#', '');
                                form_id = 'form_' + section_id;
                                oFormObject = document.forms[form_id];
                                oFormObject.elements[hash_name].value = parseInt(Update_data[column_name]);
                            } else if (hash_name.indexOf(Stringquerydropdown) !== -1) {
                                appendSelectData(hash_name, Update_data[column_name], section_id);
                            } else if (hash_name.indexOf(Sdropdown) !== -1) {
								$("" + hash_name + " option[value='" + Update_data[column_name] + "']").prop("selected", true);
								$("" + hash_name).select2();
							}  else {
                                $(hash_name).val(Update_data[column_name]);
                            }

                        }

                    });
                }

                $("[contenteditable='true']").each((i, e) => {
                    $(e).attr("contenteditable", false);
                });
                if (typeArray != "" && typeArray != null) {
                    var typeArrayValue = typeArray.split(",");
                    console.log(typeArrayValue);
                    var ansTypeArray = ["17", "18", "24"];
                    var trueVariable = false;
                    for (var i = 0; i < ansTypeArray.length; i++) {
                        console.log(typeArrayValue.indexOf(ansTypeArray[i]));
                        if (typeArrayValue.indexOf(ansTypeArray[i]) != -1) {
                            trueVariable = true;
                            break;
                        }
                    }
                    console.log(trueVariable);
                    if (trueVariable == false) {
                        getFormInputValues(section_id, queryparameter_hidden);
                    }
                }
                return Promise.resolve(1);
            } else {
                return Promise.resolve(0);
            }
        }
    });
}

function appendSelectData(hashkey, value, section_id) {
    $.ajax({
        type: "POST",
        url: base_url + "ReportMakerController/GetSelectData",
        dataType: "json",
        data: {hashkey: hashkey, section_id: section_id, value: value},
        success: function (result) {
            if (result.status == 200) {
                var newOption = new Option(result.option, result.id, true, true);
                $(hashkey).append(newOption).trigger('change');
            } else {

            }

        }, error: function (error) {

            alert('Something went wrong please try again');
        }
    });
}

function getDate(dateString) {
    var dateVal = new Date(dateString);
    var day = dateVal.getDate().toString().padStart(2, "0");
    var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
    var hour = dateVal.getHours().toString().padStart(2, "0");
    var minute = dateVal.getMinutes().toString().padStart(2, "0");
    var sec = dateVal.getSeconds().toString().padStart(2, "0");
    var ms = dateVal.getMilliseconds().toString().padStart(3, "0");
    var inputDate = dateVal.getFullYear() + "-" + (month) + "-" + (day);
    return inputDate;
}

function getDataHTML(id, section_id, type, hash_id) {
    // $("#mainDashboard").hide();
    // $("#OtherDashboard").show();
    $.ajax({
        type: "POST",
        url: base_url + "Html_template/getReportFormData",
        dataType: "json",
        data: {id: id, section_id: section_id, type: type, hash_id: hash_id},
        success: function (result) {
            $("#querydropdownOpHere").html('');
            if (result.status == 200) {
                $("#querydropdownOpBtn").click();
                $("#querydropdownOpHere").html(result.data);
            } else {
                $("#querydropdownOpBtn").click();
                $("#querydropdownOpHere").html(result.data);
            }

        }, error: function (error) {
            app.errorToast('Something went wrong please try again');
        }
    });
}

function DownloadData(field_id) {

    var form_id = 'download_form_' + field_id;
    var loginForm = $('#' + form_id).serializeArray();
    // console.log(loginForm);
    var loginFormObject = {};
    $.each(loginForm,
        function (i, v) {
            loginFormObject[v.name] = v.value;
        });
    const x = JSON.stringify(loginFormObject);

    //	var x=$("#query_id").val();

    window.location.href = base_url + "Html_template/DownloadData?formdata=" + x;
}

//DownloadNewpdf
function DownloadPDFData(field_id) {

    var form_id = 'download_form_' + field_id;
    var loginForm = $('#' + form_id).serializeArray();
    var loginFormObject = {};
    $.each(loginForm,
        function (i, v) {
            loginFormObject[v.name] = v.value;
        });
    const x = JSON.stringify(loginFormObject);

    //	var x=$("#query_id").val();

    window.location.href = base_url + "Html_template/DownloadNewpdf?formdata=" + x;
}

function DownloadCSVData(field_id) {
    var form_id = 'download_form_' + field_id;
    var loginForm = $('#' + form_id).serializeArray();
    var loginFormObject = {};
    $.each(loginForm,
        function (i, v) {
            loginFormObject[v.name] = v.value;
        });
    const x = JSON.stringify(loginFormObject);

    //	var x=$("#query_id").val();

    window.location.href = base_url + "Html_template/DownloadNewcsv?formdata=" + x;
}


function ShowReportDataTable(hash_key, section_id) {
    var transaction_id = $("#transaction_id").val();
    var h = hash_key;
    hash_key = h.replace("#", "");
    $.ajax({
        type: "POST",
        url: base_url + "Html_template/getDataTableReport",
        dataType: "json",
        data: {hash_key, section_id, transaction_id},
        success: function (result) {
            if (result.status == 200) {

                $("#" + hash_key).html(result.table);

                getDatatable(result.array_data, hash_key);

                //$("#table_data").dataTable();
            } else {
                $("#" + hash_key).html(result.table);
                //$("#table_data").dataTable();
            }

        }, error: function (error) {
            app.errorToast('Something went wrong please try again');
        }
    });


}

function getDatatable(data, id) {

    console.log(data);
    $('#table_data_' + id).DataTable({
        data: data
    });
}

function insertDataForm(btn_id, section_id) {
    var form = document.getElementById('form_' + section_id);
    // var formValid = document.forms['form_'+section_id].checkValidity();
    var formValid = document.forms['form_' + section_id].reportValidity();
    /* var URL=window.location.href;
    var arr=URL.split('/');

    if(arr[6] != ''){
        var queryparameter_hidden=arr[6];
    }else{
        var queryparameter_hidden=null;
    } */
    var queryparameter_hidden = $("#queryparameter_hidden").val();

    if (formValid == true) {
        let formData = new FormData(form);
        formData.append("queryparameter_hidden", queryparameter_hidden)
        formData.set("btn_id", btn_id);
        formData.set("section_id", section_id);
        let hiddenDivName = null;
        if (document.getElementById("hiddenDivName")) {
            hiddenDivName = document.getElementById("hiddenDivName").value;
        }

        app.request(base_url + "InsertFordataUsingButton", formData).then(response => {
            if (response.status === 200) {
                app.successToast(response.body);
                var dependant_section_id = response.dependant_section_id;
                var is_dependant = response.is_dependant;
                var section_id = response.this_section_id;
                if (dependant_section_id == 0 && is_dependant == 0) {
                    let object = JSON.parse(atob(queryparameter_hidden))
                    if (object.hasOwnProperty("update_id")) {
                        delete object.update_id;

                    }
                    var param = (btoa(JSON.stringify(object)));

                    var URL = window.location.href.replace(queryparameter_hidden, param);
                    //get_forms()
                    //	window.location.href=URL;
                    window.history.pushState('', '', URL);
					$("#queryparameter_hidden").val(param);
					document.getElementById('form_' + section_id).reset();
                    if (hiddenDivName !== null) {
                        get_forms(section_id, 0, null, null, null, hiddenDivName);
                    } else {
                        get_forms(section_id, 0);
                        //	window.location.href=URL;
                    }
                    if (dependant_section_id != 0) {
                        var insert_id = response.insert_id;
                        localStorage.setItem("insert_id", insert_id);
                        get_forms(dependant_section_id, 1);

                    }
                    if (is_dependant == 1) {
                        get_forms(section_id, 1);
                    }
					document.getElementById('form_' + section_id).reset();
					var myForm = $('#form_'+section_id);
					$("select", myForm).each(
						function () {
							$(this).select2('val',$(this).find('option:selected').val())
						}
					);
                    /* var redirection=response.redirection;
                    var arr=redirection.split("|");
                    var arr2=arr[0].split(":");
                    var redirection_type=arr2[1];
                    if(redirection_type == 1){ //redirrct to given route
                        var arr3=arr[1].split(":");
                        var route=arr3[0];

                        var arr4=arr[2].split(":");
                        var ExternalParamArray = arr4[1].split(',');

                    }else if(redirection_type == 2){ //redirect to another template
                        var arr3=arr[1].split(":");
                        var section_id=arr3[0];

                        var arr4=arr[2].split(":");
                        var ExternalParamArray = arr4[1].split(',');
                        redirectToanotherTemplate(section_id,queryparameter_hidden);
                    }else{ //transaction table changes

                    } */
                    //location.reload();
                } else {
                    app.errorToast(response.body);
                    // document.getElementById("templateFornBtn").disabled = false;
                }
            }
        });
    }
}

function redirectToanotherTemplate(section_id, queryparameter_hidden) {


}

function loadDataTable(element_id, section_id, type = 0) {

    var transaction_id = localStorage.getItem("insert_id");

    let formData = new FormData();
    formData.set("element_id", element_id)
    formData.set("section_id", section_id)
    formData.set("queryParameter", $("#queryparameter_hidden").val());

    app.request(base_url + "getDataTableTemplate", formData).then(response => {

        if (response.status === 200) {
            if (type === 0) {
                var div_id = element_id.replace("#", "");
                $("#dynamic_datatable_" + div_id).empty();
                $("#dynamic_datatable_" + div_id).append(response.body);

                app.dataTable(response.tableID, {
                    url: base_url + "getDataTableData",
                    data: {
                        element_id: element_id,
                        section_id: section_id,
                        transaction_id: transaction_id,
                        queryParameter: $("#queryparameter_hidden").val()
                    }
                });
            } else {
                $("#ShowHistory").removeClass("d-none");
                $("#ShowHistory").empty();
                $("#ShowHistory").append(response.body);

                app.dataTable(response.tableID, {
                    url: base_url + "getDataTableData",
                    data: {
                        element_id: element_id,
                        section_id: section_id,
                        transaction_id: transaction_id,
                        queryParameter: $("#queryparameter_hidden").val()
                    }
                });
            }
        }
    })
}

function dynamicFilter(element_id, index, filterColumn, type, value, section_id) {
    var div_id = element_id.replace("#", "");
    var transaction_id = localStorage.getItem("insert_id");
    app.dataTable("dynamicDataTable_" + div_id, {
        url: base_url + "getDataTableData",
        data: {
            element_id: element_id,
            transaction_id: transaction_id,
            section_id: section_id,
            filterColumn: filterColumn,
            filterByValue: value,
            queryParameter: $("#queryparameter_hidden").val()
        }
    });
}

function switch_form_history(id) {
    if (id == 1) { //show history
        $("#ShowForm").hide();
        $("#ShowHistory").show();
        $("#formButton").show();
        $("#HistoryButton").hide();

        var section_id = $("#section_id").val();

        let formData = new FormData();
        formData.set("section_id", section_id);
        app.request(base_url + "getDataTableElementId", formData).then(response => {
            if (response.status == 200) {
                //formButton HistoryButton
                var dataTableElementId = response.dataTableElementId;

                loadDataTable(dataTableElementId, section_id, 1);
            } else {

            }
        });
    } else {
        $("#ShowForm").show();
        $("#ShowHistory").hide();
        $("#formButton").hide();
        $("#HistoryButton").show();
    }
}

function GetFormHistorYButtons() {
    var section_id = $("#section_id").val();
    let formData = new FormData();
    formData.set("section_id", section_id);
    app.request(base_url + "CheckHistoryUnabled", formData).then(response => {
        if (response.status === 200) {
            //formButton HistoryButton
            $("#HistoryButton").show();
            //$("#formButton").show();
        } else {
            $("#HistoryButton").hide();
            $("#formButton").hide();
        }
    });
}

function dataTableExecution(elementId, sectionId, index, type, value) {

    let formData = new FormData();
    formData.set("transID", value);
    formData.set("elementID", elementId);
    formData.set("sectionID", sectionId);
    formData.set("index", index);
    app.request(base_url + "executionButton", formData).then(response => {
        if (response.status === 200) {
            app.successToast(response.body);
            loadDataTable(elementId, sectionId);
        } else {
            app.errorToast(response.body);
        }
    }).catch(error => {
            console.log(error);
            app.errorToast("Something went wrong")
        }
    );
}

function dataTableUpdate(elementId, sectionId, index, type, value, redirectSectionID) {

    let queryParameter = document.getElementById("queryparameter_hidden");
    let object = JSON.parse(atob(queryParameter.value))
    object.update_id = value;

    switch (parseInt(sectionId)) {
        case 128:
        case 129:
        case 132:
        case 135:
        case 140:
        case 141:
        case 96:
		case 148:
            GetEditableData(elementId, sectionId, index, type, value, redirectSectionID, object);
            break;
        default:
            let url = base_url + "/html_form_view/" + redirectSectionID + "/" + btoa(JSON.stringify(object));
            window.location.href = url;
            break;
    }
    //let url =base_url+"/html_navigation/18/"+redirectSectionID+"/"+btoa(JSON.stringify(object));
    //
}

function GetEditableData(elementId, sectionId, index, type, value, redirectSectionID, object) {

    var qp = btoa(JSON.stringify(object));
    $("#queryparameter_hidden").val(qp);
    let hiddenDivName = null;
    if (document.getElementById("hiddenDivName")) {
        hiddenDivName = document.getElementById("hiddenDivName").value;
    }
    if (hiddenDivName !== null) {
        get_forms(sectionId, 0, null, null, null, hiddenDivName);
    } else {
        get_forms(sectionId, 0);
    }

}

function exceltabledata(hash_key, section_id) {
    //dynamic_exceltable_
    let formData = new FormData();
    formData.append('section_id', section_id);
    formData.append('hash_key', hash_key);
//ExcelTableConfiguaration
    app.request(base_url + "getExcelTabledata", formData).then(response => {
        if (response.status === 200) {
            $("#dynamic_exceltable_" + hash_key).html('<table id="' + hash_key + '" class="table table-responsive table-bordered table-striped"></table>' +
                '<button  class="btn btn-primary" onclick="getexceldata(\'' + hash_key + '\')" type="button">Export to Json</button>');
            console.log(response.data);

            var arr_new = [];
            var final_data = response.data;
            $(final_data).each(function (i) {
                var obj = {};
                obj['name'] = final_data[i].input_name;
                obj['header'] = final_data[i].input_name;
                if (final_data[i].input_type == 1) {
                    obj['markup'] = '<input type="text" class="form-control">';
                }
                if (final_data[i].input_type == 2) {
                    obj['markup'] = '<input type="number" class="form-control">';
                }
                if (final_data[i].input_type == 3) {
                    obj['markup'] = '<input type="date" class="form-control">';
                }
                if (final_data[i].input_type == 4) {

                    var option = final_data[i].options;
                    if (option !== null) {
                        var arr = option.split(",");
                        var html_opt = '<option value="">Select Option</option>';
                        $(arr).each(function (j) {
                            html_opt += '<option value="' + arr[j] + '">' + arr[j] + '</option>';
                        });
                    }
                    obj['markup'] = '<select class="form-control " >' +
                        html_opt +
                        '</select>';
                }
                var id = final_data[i].id;
                if (final_data[i].input_type == 5) {

                    var option = final_data[i].options;
                    if (option !== null) {
                        var arr = option.split(",");
                        var html_opt = '<option selected disabled value="">Select Option</option>';
                        $(arr).each(function (j) {
                            html_opt += '<option value="' + arr[j] + '">' + arr[j] + '</option>';
                        });
                    }
                    obj['markup'] = '<select  class="form-control select21" multiple>' +
                        html_opt +
                        '</select><script>$(".select21").select2();</script>';
                }
                if (final_data[i].input_type == 8) {

                    var query = final_data[i].query;

                    obj['markup'] = '<select  class="form-control querydrp' + id + '" >' +

                        '<option selected >Please Select Option</option></select><script>getdrpdata(\'' + btoa(query) + '\',\'querydrp' + id + '\')</script>';
                }
                if (final_data[i].input_type == 6) {
                    var option = final_data[i].options;
                    var html_opt1 = "";
                    $(arr).each(function (j) {
                        html_opt1 += '<input type="radio" name="radio' + id + '" value="' + arr[j] + '">';
                        html_opt1 += arr[j] + " ";

                    });
                    html_opt1 += "<br>";
                    console.log(html_opt1);
                    obj['markup'] = html_opt1;
                }
                if (final_data[i].input_type == 7) {
                    var option = final_data[i].options;
                    var html_opt1 = "";
                    $(arr).each(function (j) {
                        html_opt1 += '<input type="checkbox" name="radio' + id + '" value="' + arr[j] + '">';
                        html_opt1 += arr[j] + " ";

                    });
                    html_opt1 += "<br>";
                    console.log(html_opt1);
                    obj['markup'] = html_opt1;
                }
                arr_new.push(obj);

            });
            var obj = {};
            obj['header'] = 'Action';
            obj['markup'] = '<button class="btn btn-link" title="delete this row"><i class=" fa fa-trash"></i></button>';
            obj['tabStop'] = false;
            arr_new.push(obj);
            //hash_key='exceltable_button_19';
            console.log();
            //console.log(arr_new);
            $('#' + hash_key).rsLiteGrid({
                cols: arr_new,
                onAddRow: function (event, $lastNewRow) {
                    $('button', $lastNewRow).click(function () {
                        $('#' + hash_key).rsLiteGrid('delRow', $lastNewRow);
                    });
                }

                // load table with 2 rows of data
            })
        } else {
            //$("#dynamic_exceltable_"+hash_key).html(response.data);
        }
    });
}

function getdrpdata(query, id) {
    $("." + id).select2(
        {
            ajax: {
                url: base_url + "HtmlFormController/get_data?query=" + query,
                type: "post",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            },
            minimumInputLength: 3
        }
    )
}

function getexceldata(hash_key) {
    console.log($('#' + hash_key).rsLiteGrid('getData'));
    var jsonstring = $('#' + hash_key).rsLiteGrid('getData');
    let formData = new FormData();
    formData.set("jsonString", JSON.stringify(jsonstring));
    app.request(base_url + "ExcelTabledataInsert", formData).then(response => {
        if (response.status === 200) {
            app.successToast(response.body);
        } else {
            app.errorToast(response.body);
        }
    }).catch(error => {
            console.log(error);
            app.errorToast("Something went wrong")
        }
    );
}

function save_Percentage(p_id) {
    let formData = new FormData();
    formData.set("p_id", p_id);
    formData.set("discountPercent", $("#discountPercent").val());
    app.request(base_url + "ReportMakerController/savePercentage", formData).then(response => {
        if (response.status === 200) {
            var queryparameter_hidden = $("#queryparameter_hidden").val();
            getDataofReport(12, queryparameter_hidden);
        } else {
            var queryparameter_hidden = $("#queryparameter_hidden").val();
            getDataofReport(12, queryparameter_hidden);
        }
    }).catch(error => {
            console.log(error);
            app.errorToast("Something went wrong")
        }
    );
}


let elementsSelector = [];

function getHeaders(haskey, section_id, dep_id) {
    let formData = new FormData();
    formData.set("section_id", section_id);
    formData.set("dep_id", dep_id);
    formData.set("haskey", haskey);
    app.request(base_url + "getHeaderConfiguration", formData).then(response => {
        if (response.status === 200) {

            let headers = [];
            let tableName = "";
            let operation = 2;
            elementsSelector = [];
            let columnObject = response.body.map(column => {
                let type = parseInt(column.type);
                headers.push(column.header);
                tableName = column.table_name;
                operation = column.operation;
                switch (type) {
                    case 0:
                        return {data: column.column_name}
                    case 1:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                if (val == null) {
                                    val = 0;
                                }
                                elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                                return `<input  class="form-control" type="text" value="${val}" name="${column.column_name + "-" + row.id}" />`
                            }
                        }
                    case 2:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                elementsSelector.push(`textarea[name='${column.column_name + "-" + row.id}']`);
                                return `<textarea class="form-control" name="${column.column_name + "-" + row.id}" ></textarea>`
                            }
                        }
                    case 3:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                if (column.configuration !== "") {
                                    let options = column.configuration.split(",");
                                    let optionTemplate = options.map(i => {
                                        return `<option value="${i}">${i}</option>`;
                                    })
                                    elementsSelector.push(`select[name='${column.column_name + "-" + row.id}']`);
                                    return `<select class="form-control" name="${column.column_name + "-" + row.id}">${optionTemplate}</select>`
                                } else {
                                    elementsSelector.push(`select[name='${column.column_name + "-" + row.id}']`);
                                    return `<select class="form-control" name="${column.column_name + "-" + row.id}"><option value="">No Data Found</option></select>`
                                }
                            }
                        }
                    case 4:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                if (column.configuration !== "") {
                                    let options = column.configuration.split(",");
                                    let optionTemplate = options.map(i => {
                                        return `<option value="${i}">${i}</option>`;
                                    })
                                    elementsSelector.push(`select[name='${column.column_name + "-" + row.id}']`);
                                    return `<select class="form-control" multiple name="${column.column_name + "-" + row.id}">${optionTemplate}</select>`
                                } else {
                                    elementsSelector.push(`select[name='${column.column_name + "-" + row.id}']`);
                                    return `<select class="form-control" multiple name="${column.column_name + "-" + row.id}"><option value="">No Data Found</option></select>`
                                }
                            }
                        }
                    case 5:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                if (row.check_id != null) {
                                    if (row.check_id == 1) {
                                        var check_data = "checked";
                                    } else {
                                        var check_data = "";
                                    }
                                } else {
                                    var check_data = "";
                                }

                                if (column.configuration !== "") {
                                    let options = column.configuration.split(",");
                                    let optionTemplate = options.map(i => {
                                        elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']:checked`);
                                        return `<input type="checkbox" ${check_data} name="${column.column_name + "-" + row.id}" /><label>${i}</label>`;
                                    })
                                    return `<div class="form-group">${optionTemplate}</div>`;
                                } else {
                                    elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']:checked`);
                                    return `<input type="checkbox" ${check_data} name="${column.column_name + "-" + row.id}" value="${val}" />`;
                                }
                            }
                        }
                    case 6:
                        elementsSelector.push(`input[name='${column.column_name}']:checked`);
                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                if (column.configuration !== "") {
                                    let options = column.configuration.split(",");
                                    let optionTemplate = options.map(i => {
                                        elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']:checked`);
                                        return `<input type="radio" name="${column.column_name + "-" + row.id}" value="${i}"/><label>${i}</label>`;
                                    })
                                    return `<div class="form-group">${optionTemplate}</div>`;
                                } else {
                                    return ``;
                                }
                            }
                        }
                    case 7:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                                return `<input type="date" class="form-control" name="${column.column_name + "-" + row.id}" />`
                            }
                        }
                    case 8:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                                return `<input type="file" class="form-control" name="${column.column_name + "-" + row.id}" />`
                            }
                        }
                    case 9:

                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                                return `<input type="number" class="form-control" name="${column.column_name + "-" + row.id}" />`
                            }
                        }
                    case 10:
                        return {
                            data: column.column_name,
                            render: async (val, type, row) => {
                                if (column.configuration !== "") {
                                    let formData = new FormData();
                                    formData.set("conf", column.configuration);
                                    let response = await app.request(baseURL + 'getColumnOptions', formData);
                                    let options = "";
                                    if (response.status === 200) {
                                        options = response.data;
                                    }
                                    elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                                    return `<select class="form-control" id="${column.column_name + "-" + row.id}" name="${column.column_name + "-" + row.id}">${options}</select>`;
                                }
                            }
                        }
                    case 11:
                        return {
                            data: column.column_name,
                            render: (val, type, row) => {
                                elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                                return `<input type="hidden" class="form-control" name="${column.column_name + "-" + row.id}" value="${val}" />`
                            }
                        }
                    case 12:
                        return {
                            // data: column.column_name,
                            // render: (val, type, row) => {
                            //  elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                            //  return `<input type="hidden" class="form-control" name="${column.column_name + "-" + row.id}" value="${val}" />`
                            // }

                            data: column.column_name,
                            render: (val, type, row) => {
                                if (column.configuration !== "") {
                                    let options = column.configuration.split("|");
                                    let optionTemplate;
                                    let dataTarget;
                                    let dataId;
                                    let dataIdValue;
                                    let arrayKeys=[];
                                    options.map(i => {
                                        let keys = i.split("=");
                                        console.log(keys);
                                        if (keys.length == 2) {
                                            if (keys[0] == 'keyColumn') {
                                                dataId = 'data-' + keys[1];
                                                arrayKeys.push(keys[1]);
                                                // let modal_id = keys[modalName];
                                                // optionTemplate =  '<button data-toggle="modal" class="btn btn-primary "><i class="fa fa-eye"></i></button>';
                                            }
                                            if (keys[0] == 'modalName') {
                                                dataTarget = keys[1]

                                            }
                                            if (keys[0] == 'valueColumn') {
                                                dataIdValue = keys[1]
                                            }
                                            optionTemplate = '<button type="button" data-toggle="modal" data-target="#' + dataTarget + '"  ' + dataId + '="' + val + '" class="btn btn-primary "><i class="fa fa-eye"></i></button>';

                                        }
                                        //elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']:checked`);
                                        // return `<input type="radio" name="${column.column_name + "-" + row.id}" value="${i}"/><label>${i}</label>`;
                                        //return `<button  data-toggle="${column.column_name}" data-${co}my_id_value"><i class="fa fa-pencil"></i></button>`;
                                    });
                                    // console.log(optionTemplate);
                                    return optionTemplate;
                                } else {
                                    return ``;
                                }
                            }
                        }
                    case 13:
                        return {
                            // data: column.column_name,
                            // render: (val, type, row) => {
                            // 	elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']`);
                            // 	return `<input type="hidden" class="form-control" name="${column.column_name + "-" + row.id}" value="${val}" />`
                            // }

                            data: column.column_name,
                            render: (val, type, row) => {
                                if (column.configuration !== "") {
                                    let options = column.configuration.split("|");
                                    let optionTemplate ;
                                    let dataTarget ;
                                    let dataId ;
                                    let dataIdValue ;
                                    let dataFunctionColumn;
                                    let dataIconColumn;
                                    console.log(options);
                                    options.map(i => {
                                        let keys = i.split("=");
                                        console.log(keys);
                                        if(keys.length ==  2){
                                            // if (keys[0] == 'keyColumn' ) {
                                            // 	dataId = 'data-'+keys[1];
                                            // 	// let modal_id = keys[modalName];
                                            // 	// optionTemplate =  '<button data-toggle="modal" class="btn btn-primary "><i class="fa fa-eye"></i></button>';
                                            // }
                                            if (keys[0] == 'modalName') {
                                                dataTarget = keys[1]
                                            }
                                            if(keys[0] == 'iconColumn'){
                                                dataIconColumn = keys[1]
                                            }
                                            if (keys[0] == 'functionColumn')
                                            {
                                                dataFunctionColumn = keys[1]
                                            }
                                            optionTemplate =  '<button type="button" data-toggle="modal" data-target="#'+dataTarget+'"  data-confirm="Realy?|Do you want to continue?" data-confirm-yes="'+dataFunctionColumn+'('+val+')" class="btn btn-primary "><i class="fa fa-'+dataIconColumn+'"></i></button>';

                                        }
                                        //elementsSelector.push(`input[name='${column.column_name + "-" + row.id}']:checked`);
                                        // return `<input type="radio" name="${column.column_name + "-" + row.id}" value="${i}"/><label>${i}</label>`;
                                        //return `<button  data-toggle="${column.column_name}" data-${co}my_id_value"><i class="fa fa-pencil"></i></button>`;
                                    });
                                    // console.log(optionTemplate);
                                    return optionTemplate;
                                } else {
                                    return ``;
                                }
                            }
                        }
                }
            });
            $("#transTablename_" + haskey).val(tableName);
            let header = `<tr>`;

            headers.forEach(h => {
                header += `<td>${h}</td>`;
            })
            header += "</tr>";
            $("#tableHead_" + haskey).append(header);
            let formData = new FormData();
            formData.set("section_id", section_id);
            formData.set("dep_id", dep_id);
            formData.set("haskey", haskey);
            formData.set("queryParam", $("#queryparameter_hidden").val());
            console.log($("#queryparameter_hidden").val());
            app.request(base_url + "admin/getDynamicFormData", formData).then(response => {
                if (response.status === 200) {
                    let data = response.body;
                    console.log(data);
                    $('#example_' + haskey).DataTable({
                        paging: false,
                        ordering: false,
                        data: data,
                        columns: columnObject
                    })
                    app.confirmationBox();
                    console.log(headers);
                    console.log(columnObject);
                }
            })
            let rows = [];
            $('#saveButton_' + haskey).click(function () {

                elementsSelector.forEach(e => {
                    $('#example_' + haskey).find(e).map((i, v) => {
                        let name = $(v).attr('name').split("-");

                        if (rows.hasOwnProperty(name[1])) {
                            let object = rows[name[1]];
                            object[name[0]] = $(v).val();
                            rows[name[1]] = object;
                        } else {
                            console.log(name);
                            let object = {};
                            object[name[0]] = $(v).val();
                            rows[name[1]] = object;
                        }

                    });
                })
                let formData = new FormData();
                formData.set("data", JSON.stringify(rows));
                formData.set("tableName", $("#transTablename_" + haskey).val());
                formData.set("section_id", section_id);
                formData.set("dep_id", dep_id);
                formData.set("haskey", haskey);
                formData.set("package_id", $("#querydropdown_99").val());
                app.request(base_url + "updateDynamicFormTransaction", formData).then(response => {
                    if (response.status === 200) {
                        app.successToast(response.body);
                    } else {
                        app.errorToast(response.body);
                    }
                })
                console.log(rows);
                return false;
            });

        } else {
            app.errorToast(response.body);
        }
    });

    getModalHtml();

}


function _delete(id){
    var master_id = id;
    $.ajax({
        url: base_url + "ReportMakerController/deleteMasterTest",
        type: "POST",
        dataType: "json",
        data: {master_id: master_id},
        success: function (result) {
            if (result == 1) {
                alert('Deleted');
            }else{
                alert('Data exists, Can not be deleted');
            }
        }
    })
}
function _delete(id){
    alert(id);
}
function showModalSection(id) {
    let qvalue=JSON.parse(atob($("#queryparameter_hidden").val()));
    if(qvalue.hasOwnProperty("branch_id")){
        qvalue.master_id=id;
        qvalue1=btoa(JSON.stringify(qvalue));
        get_forms(139,0,qvalue1,22,null,'dynamicBody');
    }
}
function getModalHtml() {
    var html = `<div class="modal fade" tabindex="-1" role="dialog" id="dynamicModal"  aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="dynamicBody"></section>
            </div>
        </div>
    </div>
</div>`;
    $("body").append(html);
    $("#dynamicBody").on('show.bs.modal', function(e) {
        var bookId = $(e.relatedTarget).data('id');
        let qvalue=JSON.parse(atob($("#queryparameter_hidden").val()));
        if(qvalue.hasOwnProperty("branch_id")){
            let queryParam= $("#queryparameter_hidden").val();
            get_forms(139,0,queryParam,22,null,'dynamicBody');
        }

    });
}

function getFormInputValues(section_id, queryPara) {
	let formData = new FormData();
	formData.set("section_id", section_id);
	formData.set("queryPara", queryPara);
	app.request(base_url + "getFormInputValues", formData).then(response => {
	if (response.status === 200) {
		// app.successToast(response.body);
		var userData = response.data;
		update_id = userData['id'];
		let queryParameter = document.getElementById("queryparameter_hidden");
		let object = JSON.parse(atob(queryParameter.value))
		object.update_id = update_id;
		console.log(object);
		newOBJ = JSON.stringify(object);
		newOBJ = btoa(newOBJ);
		$("#queryparameter_hidden").val(newOBJ);
		// var ChangeUrl = (base_url + `html_navigation/${department_id}/${section_id}/${newOBJ}`);
		// window.history.pushState('', '', ChangeUrl);

		var formInputValue = Object.entries(userData).map(([k, v]) => {
			var kWithoutHash = k.replace('#', '');
			var myEle = document.getElementById(kWithoutHash);
			// console.log(myEle);
			if (myEle) {
				var myEleText = kWithoutHash.split('_');
				myEleText = myEleText[0];
				if (myEleText == 'dropdown' || myEleText == 'multidropdown' || myEleText == 'checkbox'
					|| myEleText == 'radio' || myEleText == 'querydropdown') {


					if (myEleText == 'multidropdown' || myEleText == 'checkbox' || myEleText == 'radio') {
						if (myEleText == 'checkbox' || myEleText == 'radio') {

							if (v != "" && v != null) {
								var vValue = v.split(',');
								let vValueText = vValue.map(i => {
									if (myEleText == 'radio') {
										var radios = document.getElementsByName(kWithoutHash);
										for (var j = 0; j < radios.length; j++) {

											if (radios[j].value == i) {
												radios[j].checked = true;
												break;
											}
										}
									} else {
										// var radios = document.getElementById(kWithoutHash);
										// console.log(radios);
										$($("input#" + kWithoutHash + "[value='" + i + "']")[0]).attr('checked', true);
									}

								});
							}

						}


						if (myEleText == 'multidropdown') {


							var vValue = v.split(',');
							let vValueText = vValue.map(i => {
								$("#" + kWithoutHash + " option[value='" + i + "']").prop("selected", true);

							})
							if (myEleText == 'multidropdown' || myEleText == 'querydropdown' || myEleText == 'dropdown') {
								$("#" + kWithoutHash).select2();
							}

						} else {
							if (myEleText == 'dropdown') {
								$("#" + kWithoutHash + " option[value='" + v + "']").prop("selected", true);
								$("#" + kWithoutHash).select2();
							}

							if (myEleText == 'querydropdown') {
								// $("#"+kWithoutHash+" option[value='"+v+"']").prop("selected", true);
								// $("#"+kWithoutHash).select2("trigger", "select", {
								// 	data: { id: v }
								// });

								var data = {
									id: v
								};

								var newOption = new Option('', data.id, true, true);
								// $("#"+kWithoutHash).append(newOption).trigger('change');
								$("#" + kWithoutHash).trigger({
									type: 'select2:select',
									params: {
										data: v
									}
								});
								// $("#"+kWithoutHash).select2('data', {id: v, text: v});
								// $("#"+kWithoutHash).val(v).trigger("change");
							}

						}

					} else {
						if (myEleText == 'dropdown') {
							$("#" + kWithoutHash + " option[value='" + v + "']").prop("selected", true);
							$("#" + kWithoutHash).select2();
						}

						else if (myEleText == 'querydropdown') {
							// $("#"+kWithoutHash+" option[value='"+v+"']").prop("selected", true);
							// $("#"+kWithoutHash).select2("trigger", "select", {
							//  data: { id: v }
							// });

							var data = {
								id: v
							};

							var newOption = new Option('', data.id, true, true);
							// $("#"+kWithoutHash).append(newOption).trigger('change');
							$("#" + kWithoutHash).trigger({
								type: 'select2:select',
								params: {
									data: v
								}
							});
							// $("#"+kWithoutHash).select2('data', {id: v, text: v});
							// $("#"+kWithoutHash).val(v).trigger("change");
						}else if (myEleText == 'date') {
							$("" + k).val(formatDate(v));
						} else {
							$("" + k).val(v);
						}

					}
				}
				else {
					if (myEleText == 'date') {
						$("" + k).val(formatDate(v));
					} else {
						$("" + k).val(v);
					}

				}
			}
		});
		// $.LoadingOverlay("hide");
// console.log(formInputValue);
	} else {
		// app.errorToast(response.body);
		// $.LoadingOverlay("hide");
	}
});

}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}

function getAynoneDependantOnQueryDropdown(queryHashId, section_id, type) {
    var queryPara = $("#queryparameter_hidden").val();
    let formData = new FormData();
    formData.set("section_id", section_id);
    formData.set("queryHashId", queryHashId);
    formData.set("queryPara", queryPara);
    app.request(base_url + "getAnyoneDependantOnQueryDropdown", formData).then(response => {
        if (response.status === 200) {
            if (response.data != null && response != "") {
                console.log(response.data);
                var querydata = btoa(response.data);

                $(queryHashId).attr("onchange", "loadElementsValue(\"" + section_id + "\",\"" + queryHashId + "\",\"" + querydata + "\",\"" + queryPara + "\")");
            }
        } else {

        }
    });
}

function loadElementsValue(section_id, queryHashId, querydata, queryPara) {
    var hash_value = $(queryHashId).val();
    var formData1 = new FormData();
    formData1.set("hash_id", queryHashId);
    formData1.set("hash_value", hash_value);
    formData1.set("querydata", querydata);
    formData1.set("queryPara", queryPara);
    $.ajax({
        type: "POST",
        url: base_url + 'loadElementsValue',
        dataType: "json",
        data: formData1,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.status == 200) {
                var userData = result.query_data;
                if (userData.length > 0) {
                    for (var i = 0; i < userData.length; i++) {
                        // $(""+userData[i]->hash_key).val(userData[i]->hash_key_value);
                        var h = userData[i].hash_key;
                        var h_key = h.replace("#", "");
                        var form_q = 'form_' + section_id;
                        oFormObject = document.forms[form_q];
                        oFormObject.elements[h_key].value = userData[i].hash_key_value;
                    }
                }
            } else {
            }
        }
    });

}

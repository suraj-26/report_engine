<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>

<!-- Main Content start -->
<div class="main-content">
    <section class="section">

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="panel1">
                                        <div id="doctor_consult"></div>
                                        <div id="doctor_consult1"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="panel2" class="" >
                                        <div id="history"></div>
                                        <div id="history1"></div>
                                    </div>


                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="panel3" class="" >
                                        <div id="investigation"></div>
                                        <div id="investigation1"></div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div id="panel4" class="" >
                                        <div id="prescription"></div>
                                        <div id="prescription1"></div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('_partials/footer'); ?>

<script>
   let base_url="<?php echo base_url(); ?>";
   get_forms(85,0,'eyJicmFuY2hfaWQiOiIyIiwicGF0aWVudF9pZCI6NH0=',18,'aHR0cHM6Ly9jMTkuZG9jYW5nby5jb20vaHRtbF9uYXZpZ2F0aW9uLzE4Lzg1L2V5SmljbUZ1WTJoZmFXUWlPaUl5SWl3aWNHRjBhV1Z1ZEY5cFpDSTZOSDA9',1)
   get_forms(87,0,'eyJicmFuY2hfaWQiOiIyIiwicGF0aWVudF9pZCI6NH0=',18,
       'aHR0cHM6Ly9jMTkuZG9jYW5nby5jb20vaHRtbF9uYXZpZ2F0aW9uLzE4Lzg3L2V5SmljbUZ1WTJoZmFXUWlPaUl5SWl3aWNHRjBhV1Z1ZEY5cFpDSTZOSDA9',2);
    function get_forms(section_id, type = 0,queryparameter_hidden=null,department_id=null,url=null,panel=0) {
        //
        // $("#form_data_report").html("");
        //
        // // console.log(section_id);
        // if(queryparameter_hidden==null)
        // {
        //
        //     var queryparameter_hidden = $("#queryparameter_hidden").val();
        // }
        // if(url!=null)
        // {
        //     $("#printButton").hide();
        //     $("#CloseBillButton").hide();
        //     localStorage.setItem("section_type",1);
        //     $(".a_menu").css({
        //         "text-decoration":'none',
        //         "color":'black'
        //     });
        //     $('.li_menu').addClass('menu-header_section1').removeClass('menu-header_section active');
        //     $('#a_menu_'+section_id).css({
        //         "text-decoration":'none',
        //         "color":'white'
        //     });
        //     $("#li_menu_"+section_id).addClass('menu-header_section active').removeClass('menu-header_section1');
        //     // window.history.pushState('', '', atob(url));
        //     $("#section_id").val(section_id);
        //     $("#department_id").val(department_id);
        //     $("#queryparameter_hidden").val(queryparameter_hidden);
        // }
        $.ajax({
            url: base_url + "getHtmlTemplateForm",
            type: "POST",
            dataType: "json",
            data: {section_id: section_id,queryparameter_hidden:queryparameter_hidden},
            success: function (result) {
                var data = result.data;
                if (result['status'] === 200) {
                   // $('#section_name').html(data.name);

                    if(panel==1){
                        loadPanel(type,'doctor_consult',data,section_id,queryparameter_hidden=null,department_id=null)
                    }
                    if(panel==2){
                        loadPanel(type,'history',data,section_id,queryparameter_hidden=null,department_id=null)
                    }
                    if(panel==3){
                        loadPanel(type,'investigation',data,section_id,queryparameter_hidden=null,department_id=null)
                    }
                    if(panel==4){
                        loadPanel(type,'prescription1',data,section_id,queryparameter_hidden=null,department_id=null)
                    }

                } else {

                }
            }, error: function (error) {

                alert('Something went wrong please try again');
            }
        });
    }

    function loadPanel(type,panel,data,section_id,queryparameter_hidden=null,department_id=null) {
        if(type == 0){
            $("#"+panel).html(data.section_html);
        }else{
            $("#"+panel+"1").html(data.section_html);
            var insert_id = localStorage.getItem("insert_id");
            if(insert_id != ""){
                $('#form_'+section_id).append('<input type="hidden" id="transaction_id" name="transaction_id" value="'+insert_id+'" />');
            }

        }
        var typeArray = data.html_section_types;
        var sectionArray = data.html_section_text;
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
        var Update_data=result.Update_data;
        Update_data=Update_data.data;
        var Mapping_Data=result.Mapping_Data;
        if(Mapping_Data != ""){
            var array_string=(Mapping_Data.data.array_string).split("|");
            array_string.map(a=>{
                var arr=a.split(":");
                var column_name=arr[0];
                var hash_name=arr[1];
                if(hash_name.includes("#date_")){
                    $(hash_name).val(getDate(Update_data[column_name]));
                }else{
                    $(hash_name).val(Update_data[column_name]);
                }

            });
        }

        $("[contenteditable='true']").each((i,e)=>{
            $(e).attr("contenteditable",false);
        });
        if(typeArray!="" && typeArray!=null)
        {
            var typeArrayValue = typeArray.split(",");
            var ansTypeArray=[17,18,24];
            var trueVariable=false;
            for(var i=0;i<ansTypeArray.length;i++){
                if(typeArrayValue.indexOf(ansTypeArray[i]) !== -1)
                {
                    trueVariable=true;
                }
            }
            if(trueVariable==false)
            {
                getFormInputValues(section_id,queryparameter_hidden);
            }
        }
    }

</script>

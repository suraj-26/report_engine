<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
 
<style>

.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}
.dropdown-menu {
    width: 300px !important;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
	
}
.dropdown-item:hover,a:hover{
	background-color:#891635;
	color:white;
}
a:hover{
	color:white;

}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
	.table:not(.table-sm):not(.table-md):not(.dataTable) td, .table:not(.table-sm):not(.table-md):not(.dataTable) th {
		padding: 0 15px;
		height: 40px;
		vertical-align: middle;
		font-size: 13px;
	}

	.card_label {
		font-size: 11px;
		text-align: center;
		/* letter-spacing: .5px; */
		/* margin-top: 4px; */
		/*text-overflow: ellipsis;*/
		/* overflow: hidden; */
		/*white-space: nowrap;*/
	}

	.card-stats-item {
		/*width:30%!important;*/
		/*padding: 0px!important;*/
		/*padding: 5px 5px!important;*/
		/*border: 1px solid #e9ecef!important;*/
		/*margin: 3px!important;*/
	}

	.card {
		box-shadow: 0 4px 6px 6px rgb(0 0 0 / 3%);
	}

	.border_class {
		border: 1px solid #d3d3d35e !important;
		padding: 10px !important;
	}

	/*.bg-primary {
	   background-color: #6777ef !important;
   }*/
   .navbar .nav-link
   {
	   color:black;
   }
</style>
<!-- Main Content -->
<div class="main-content main-content1">
	<section class="section">
		<div class="section-header card-primary">
		
		
		<div id="DropDownDiv"></div>
		
		</div>
		<div class="section-body">
		
			<div class="">
				<div class="col-12 col-md-12">

					<div class="">

						<div class="card">
						<div id="OtherDashboard">
						
						</div><br>
						<div class="col-md-12" id="ViewTable" style="overflow-x: auto;"></div>
						<br>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<?php $this->load->view('_partials/footer'); ?>

<script>

	$(document).ready(function () {

		getDropdown();
	});

	
	//mainDashboard
	function go_toDashboard(){
		$("#mainDashboard").show();
		$("#OtherDashboard").hide();
		$("#ViewTable").hide();
		$("#dis_btn1").hide();
	}
	
	function getDropdown(){
		//getReports
		$.ajax({
			type: "POST",
			url: "<?= base_url("Report/getDropDown") ?>",
			dataType: "json",
			success: function (result) {
				console.log(result.data)
				$("#DropDownDiv").html(result.data);
				/* $('.dropdown-submenu a.test').on("click", function(e){
					
					$(this).next('ul').toggle();
					e.stopPropagation();
					e.preventDefault();
				  }); */
			}, error: function (error) {
				app.errorToast('Something went wrong please try again');
			}
		});
	}
	
	function getDataHTML(id){
		$("#mainDashboard").hide();
		$("#OtherDashboard").show();
		$("#dis_btn1").show();
		$.ajax({
			type: "POST",
			url: "<?= base_url("Report/getReportFormData") ?>",
			dataType: "json",
			data:{id},
			success: function (result) {
				$("#OtherDashboard").html("");
				$("#ViewTable").html("");
				if(result.status==200){
					$("#OtherDashboard").html(result.data);
				}else{
					$("#OtherDashboard").html(result.data);
				}
				$("#ViewTable").show();

			}, error: function (error) {
				app.errorToast('Something went wrong please try again');
			}
		});
	}
	
	function ShowData(){
		//download_form
		
		$.ajax({
			type: "POST",
			url: "<?= base_url("Report/ShowData") ?>",
			dataType: "json",
			data:$('#download_form').serialize(),
			success: function (result) {
				if(result.status==200){
					$("#ViewTable").html(result.table);
					if(result.is_dataTable == 1){
						getDatatable(result.array_data);
					}
					//$("#table_data").dataTable();
				}else{
					$("#ViewTable").html(result.table);
					//$("#table_data").dataTable();
				}

			}, error: function (error) {
				app.errorToast('Something went wrong please try again');
			}
		});
	}
	
	function getDatatable(data){
		$('#table_data1').DataTable( {
			data: data
		});
	}
	
	function DownloadData(){
		
		var loginForm = $('#download_form').serializeArray();
			var loginFormObject = {};
			$.each(loginForm,
			    function(i, v) {
			        loginFormObject[v.name] = v.value;
			    });
			const x=JSON.stringify(loginFormObject);
			
	//	var x=$("#query_id").val();
		
		window.location.href= "<?= base_url("Report/DownloadData?formdata=") ?>"+ x;
	}
</script>

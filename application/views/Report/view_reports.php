<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>

	
</style>

<!-- Main Content -->
<div class="main-content main-content1">
	<section class="section">
		<!-- <div class="section-header" style="border-top: 2px solid #891635">
			<h1> Sample Collection</h1>
		</div> -->
		<div class="card card-primary" style="border-top: 2px solid #891635">
		<div class="section-header card-primary" style="border-top: 2px solid #891635">
			<h1 style="color: #891635">Reports</h1>

		</div>
		
			<div class="card-body">
			<button class="btn btn-primary" onclick="toggle_div();reset_Form()" style="float:right"><i class="fa fa-plus"></i></button><br>
			
<div class="" id="query_data"style="display:none">
			
			</div>
			
			<br><hr><br>
			<table class="table table-hover table-striped table-bordered "
						   id="QueryTable" style="width:100%" role="grid">
						<thead>
						<tr>
							<th >Report Name</th>
							<th >Sub Report Name</th>
							<th>Query</th>
							<th>Display Type</th>
							<th>Parameters</th>
							<th>DataTypes</th>
							<th>Lables</th>
							<th>Options</th>
							<th >Action</th>


						</tr>
						</thead>
					</table>
			<div class="" id="getReports">
			
			</div>
			</div>
	</section>
</div>
</div>
 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
		 <h4 class="modal-title">Report Form</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
         
        </div>
        <div class="modal-body">
		<form id="download_form">
         <div id="reportDownloadForm">
		 
		 </div>
		  <div id="ViewTable">
		 
		 </div>
		 </div>
		 <div class="modal-footer">
          <button type="button" class="btn btn-primary"onclick="ShowData()">Save</button>
          <button type="button" class="btn btn-primary"onclick="DownloadData()">Download</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>
        
      </div>
    </div>
  </div>
  
<?php $this->load->view('_partials/footer'); ?>
<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet"/>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	//query_data
	$( document ).ready(function() {
    getHtmlForm();
//    getHtmlReports();
	getTableQuery();
	});
	function reset_Form(){
		$('#query_form')[0].reset();
	}
	
	function getUpdateData(id){
		window.scrollTo(0, 0);

		getHtmlForm(id);
	}
	
	function toggle_div(){
		
		 var x = document.getElementById("query_data");
			  if (x.style.display === "none") {
				x.style.display = "block";
			  } else {
				x.style.display = "none";
			  }
	}
	function getHtmlReports(){
		//getReports
		$.ajax({
			type: "POST",
			url: "<?= base_url("Report/getReportData") ?>",
			dataType: "json",
			success: function (result) {
				$("#getReports").html(result.data);

			}, error: function (error) {
				app.errorToast('Something went wrong please try again');
			}
		});
	}
	function getHtmlForm(id=null){
		if(id !== null){
		var x = document.getElementById("query_data");
			  if (x.style.display === "none") {
			  x.style.display = "block";
			  }
		}
		$.ajax({
			type: "POST",
			url: "<?= base_url("Report/getFormData") ?>",
			dataType: "json",
			data:{id},
			success: function (result) {
				$("#query_data").html(result.data);

			}, error: function (error) {
				app.errorToast('Something went wrong please try again');
			}
		});
	}
	
	function saveFormData(){
		
		$.ajax({
			type: "POST",
			url: "<?= base_url("Report/saveFormData") ?>",
			dataType: "json",
			data:$('#query_form').serialize(),
			success: function (result) {
				if(result.status==200){
					app.successToast(result.body);
					//$('#query_form')[0].reset();
					document.getElementById("query_form").reset();//form1 is the form id.

					getTableQuery();
					
					toggle_div();

				}else{
					app.errorToast(result.body);
				}

			}, error: function (error) {
				app.errorToast('Something went wrong please try again');
			}
		});
	}
	
	function get_report(id){
		$("#myModal").modal('show');
		//reportDownloadForm
		$.ajax({
			type: "POST",
			url: "<?= base_url("Report/getReportFormData") ?>",
			dataType: "json",
			data:{id},
			success: function (result) {
				if(result.status==200){
					$("#reportDownloadForm").html(result.data);
				}else{
					$("#reportDownloadForm").html(result.data);
				}

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
		var x=$("#query_id").val();
		window.location.href= "<?= base_url("Report/DownloadData?id=") ?>"+ x;
	}
	
	function getTableQuery(){
		
		app.dataTable('QueryTable', {
		url:"<?= base_url("Report/getQueryData") ?>"
	}, [
		{
			data: 0
		},{
			data: 1
		},
		{
			data: 2
		},
		{
			data: 3
		},
		{
			data: 4
		},
		{
			data: 5
		},
		{
			data: 6
		},
		{
			data: 7
		},
		
		{
			
			render: (d, t, r, m) => {
				return `<button class="btn btn-primary btn-action mr-1" type="button" onclick="getUpdateData('${r[8]}')"
                                  >
                                 <i class="fas fa-pen"></i>
                            </button>`;
				
			}
		}

	], (nRow, aData, iDisplayIndex, iDisplayIndexFull) => {
		$('td:eq(8)', nRow).html(`<button class="btn btn-primary btn-action mr-1" type="button" onclick="getUpdateData('${aData[8]}')"
                                  >
                                 <i class="fas fa-pen"></i>
                            </button>`);
		
	});
	}
</script>

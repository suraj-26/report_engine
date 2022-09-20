<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
	.custom-header {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		padding: 8px 0;
	}
</style>
<style>.form-control-field
		{
			font-size: 14px;
		    padding: 10px 15px;
		    height: 42px;
			background-color: #fdfdff;
   			border-color: #e4e6fc;
   			line-height: 1.5;
   			color: #495057;
   			background-clip: padding-box;
   			border: 1px solid #ced4da;
		    border-radius: .25rem;
		    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
		    margin-left:5px;
		}
		
	.main-content
	{
			width: 100%!important;
		    padding-left: 5px!important;
    		padding-right: 5px!important;
    		padding-top: 90px!important;
	}
	body.layout-2 .main-wrapper
	{
		padding: 0px!important;
	}
	.btn_class
	{
		color: #007bff;
    	text-decoration: underline;
	}
	.select2-container {
    width: 100%!important;
	}
	p.spanLabel.highlight {
	    background: #E1ECF4;
	    border: 1px dotted #39739d;
	}
	.section_title
	{
	    content: ' ';
	    border-radius: 5px;
	    height: 8px;
	    width: 30px;
	    background-color: #891635;
	    display: inline-block;
	    float: left;
	    margin-top: 6px;
	    margin-right: 15px;
	}
</style>
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1><span id="departmentName"></span></h1>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-3 col-lg-3">
					<div class="card">

						<div class="card-body">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active show" id="section-tab" data-toggle="tab"
									   href="#sectionTab" role="tab" aria-controls="section" aria-selected="false">Sections</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="question-tab" data-toggle="tab" href="#questionTab"
									   role="tab" aria-controls="question" aria-selected="true">Form Elements</a>
								</li>

							</ul>
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade" id="questionTab" role="tabpanel"
									 aria-labelledby="question-tab">
									<ul class="list-group connected-sortable" id="questionMasterSortable">

										<li class="list-group-item" data-type="1" onclick="insertAtCaret('editor', '#shorttext_',1);return false;">
											<i class="fas fa-font"></i> Short Text
										</li>
										<li class="list-group-item" data-type="2" onclick="insertAtCaret('editor', '#longtext_',2);return false;">
											<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAW0lEQVRIie2TMQrAMAwDL3ldSv//gaT/SIe4dOnggA0ddCCwFsmLQIgITuACZrAGcGBHdPijXuxIo2aGq+B/BcXk9dsFKVTWiuEdB07vYcCac8aaO9A2HxLigxsxxE0Q/2+PkwAAAABJRU5ErkJggg=="/>
											Long Text
										</li>
										<li class="list-group-item" data-type="3" onclick="insertAtCaret('editor', '#dropdown_',3);return false;">
											<i class="fas fa-chevron-circle-down"></i>
											Drop-down
										</li>
										<li class="list-group-item" data-type="4" onclick="insertAtCaret('editor', '#multidropdown_',4);return false;">
											<i class="far fa-caret-square-down"></i>
											Multiple Selection
										</li>
										<li class="list-group-item" data-type="5" onclick="insertAtCaret('editor', '#date_',5);return false;">
											<i class="far fa-calendar-alt"></i>
											Date
										</li>
										<li class="list-group-item" data-type="6" onclick="insertAtCaret('editor', '#number_',6);return false;">
											<i class="fas fa-hashtag"></i>
											Number
										</li>
										<li class="list-group-item" data-type="7" onclick="insertAtCaret('editor', '#file_',7);return false;">
											<i class="fas fa-paperclip"></i>
											Attachment
										</li>
										<!-- <li class="list-group-item" data-type="11" onclick="insertAtCaret('editor', '#label_',11);return false;">
											<i class="fas fa-font"></i> Label
										</li> -->
										<li class="list-group-item" data-type="12" onclick="insertAtCaret('editor', '#checkbox_',12);return false;">
											<i class="fas fa-font"></i> Checkbox group
										</li>
										<li class="list-group-item" data-type="13" onclick="insertAtCaret('editor', '#radio_',13);return false;">
											<i class="fas fa-font"></i> Radio group
										</li>
										
										<li class="list-group-item" data-type="8" onclick="insertAtCaret('editor', '#querydropdown_',8);return false;">
											<i class="fas fa-chevron-circle-down"></i>
											Query Drop-down
										</li>
										<li class="list-group-item" data-type="14" onclick="insertAtCaret('editor', '#button_',14);return false;">
											<i class="fas fa-caret-square-right"></i> Form Button
										</li>
										<li class="list-group-item" data-type="15" onclick="insertAtCaret('editor', '#excel_button_',15);return false;">
											<i class="fas fa-caret-square-right"></i> Excel Report Button
										</li>
										<li class="list-group-item" data-type="16" onclick="insertAtCaret('editor', '#pdf_button_',16);return false;">
											<i class="fas fa-caret-square-right"></i> PDF Report Button
										</li>
										<li class="list-group-item" data-type="17" onclick="insertAtCaret('editor', '#datatable_button_',17);return false;">
											<i class="fas fa-table"></i> DataTable Button
										</li>
										<li class="list-group-item" data-type="19" onclick="insertAtCaret('editor', '#csv_button_',19);return false;">
											<i class="fas fa-table"></i> CSV Report Button
										</li>
										<li class="list-group-item" data-type="20" onclick="insertAtCaret('editor', '#datatable_report_',20);return false;">
											<i class="fas fa-table"></i> DataTable Report
										</li>
										<!-- <li class="list-group-item" data-type="10" onclick="insertAtCaret('editor', '#numberwithvalue_',10);return false;">
											<i class="fas fa-hashtag"></i>
											Number with fixed value
										</li>
										<li class="list-group-item" data-type="9" onclick="insertAtCaret('editor', '#fixquerydropdown_',9);return false;">
											<i class="fas fa-chevron-circle-down"></i>
											Fix Query Drop-down
										</li>
										 -->
										
									</ul>
								</div>
								<div class="tab-pane fade active show" id="sectionTab" role="tabpanel"
									 aria-labelledby="section-tab">
									<div class="row">
										<div class="col-12">
											<table class="table table-striped mb-0">
												<thead>
												<tr>
													<th>Sections</th>
													<th></th>
												</tr>
												</thead>
												<tbody id="sectionTableBody">

												</tbody>
											</table>
										</div>
									</div>

								</div>
							</div>


						</div>
					</div>
				</div>
				<div class="col-12 col-md-9 col-lg-9">
					<div class="card">
						<form id="doctor_form" method="post" data-form-valid="uploadSection">
							<div class="card-body">
							
								<div class="form-group">
								<div class="col-md-12">
						<label class="custom-switch mt-2" style="float:right">
						<input type="checkbox" name="history_unabled" onchange="updateHistory()" class="custom-switch-input" id="history_unabled" >
						
						<span class="custom-switch-indicator"></span>
						<span class="custom-switch-description">History</span>
						</label>
						</div>
						<h2 class="section-title">Form Required Fields</h2>
							<div class="col-md-12">
									<label>Form Type</label>
									<select class="form-control" name="form_type" id="form_type" onchange="getformTypeSection(this.value)">
										<option value="1">HTML FORM</option>
										<option value="2">MASTER FORM</option>
										<option value="3">TRANSACTION FORM</option>
									</select>
								<div id="TableDivDisplay" class="mt-2" style="display: none;">
				                     <label class="d-block">Column View</label>
				                    <div class="form-row">
				                    	 <input type="hidden" name="column_view" id="column_view" value="2">
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="exampleRadios1" name="exampleRadios" value="1" class="custom-control-input" onclick="storeColumnViewValue(1,'column_view')">
											<label class="custom-control-label" for="exampleRadios1"> One Column View</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="exampleRadios2" name="exampleRadios" checked="" value="2" class="custom-control-input" onclick="storeColumnViewValue(2,'column_view')">
											<label class="custom-control-label" for="exampleRadios2"> Two Column View With lable</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="exampleRadios3" name="exampleRadios" value="3" class="custom-control-input" onclick="storeColumnViewValue(3,'column_view')">
											<label class="custom-control-label" for="exampleRadios3"> Two Column View With TextBox</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="exampleRadios4" name="exampleRadios" value="4" class="custom-control-input" onclick="storeColumnViewValue(4,'column_view')">
											<label class="custom-control-label" for="exampleRadios4"> Four Column View With lable</label>
										</div>
									</div>
									<label>Primary Table</label>
									<select class="form-control" name="primary_table" id="primary_table" onchange="getPrimaryTableData(this.value)" style="width: 100%!important">
									</select>
									<input type="hidden" name="primaryTableInsert" id="primaryTableInsert">
									<input type="hidden" name="primaryTableButton" id="primaryTableButton">
								</div>
										   </div>
								<div class="col-md-12">
									<label>Section Name</label>
									<input type="text" class="form-control" name="section_name" id="section_name" data-valid="required"
										   data-msg="Enter section name" />
										   </div>
										   <div class="col-md-12">
									<label>Query Parameter</label>
									<select class="form-control select2"
									name="QueryStringParameter[]" id="QueryStringParameter" multiple data-valid="required" data-msg="Add Query Parameters" style="width: 100%!important">
									</select>
									</div>
									<input type="hidden" name="section_id" id="section_id"/>
									<input type="hidden" name="department_id" id="department_id" />
									<input type="hidden" name="elementSequenceType" id="elementSequenceType"/>
									<input type="hidden" name="elementSequenceId" id="elementSequenceId"/>
									<input type="hidden" name="elementSequenceText" id="elementSequenceText"/>
								</div>
								<div class="col-md-12" id="div_dependantSection" style="display:none">
									<label>Select Dependancy</label>
									<select id="depend_section_id" class="form-control" name="depend_section_id"></select>
								</div>
								<h2 class="section-title">Form Editor</h2>
								<div class="droppable-box">
									<div id="editor" name="editor" contentEditable="true" class="ui-widget-content"></div>
								</div>
								<h2 class="section-title">Form Query Selections</h2>
								<div class="" id="edit_buttons">
									
								</div>
								 	
							</div>
							<div class="card-footer text-right">
								<button class="btn btn-primary mr-1" id="templateFornBtn" type="submit">Submit</button>
								<!-- <button class="btn btn-secondary" type="reset">Reset</button> -->
							</div>
						</form>
						<div class="card-footer">
							<!--<h2 class="section-title">Form Design View</h2>
							 <div class="bg-secondary droppable-box">
									<ul id="htmlFormModalData" class="list-group connected-sortable"
										style="min-height: 20px"></ul>
								</div> -->
							<div class="col-md-12" id="htmlFormModalData"></div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>
</div>

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg d-none" id="datatableOpBtn" data-toggle="modal" data-target="#datatableOp">Open Modal</button>
<!-- Modal -->
<div id="datatableOp" class="modal fade" role="dialog">
  <div class="modal-dialog modal-content" style="width: 90%!important;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title"></h4>
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        
      </div>
      <div class="modal-body">
      	<!-- <div id="datatableOpHere"></div> -->
      	
      
		<?php $this->load->view('DatatableTemplate/DatatableEditor'); ?>
      </div>
      <div class="modal-footer">
      	
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>

  </div>
</div>

<div id="RedirctModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title"></h4>
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        
      </div>
      <div class="modal-body">
	  <input type='hidden' id='QS_section_id' name='QS_section_id'>
	  <form id="queryForm" method="post">
      	 <div id="queryParamString"></div> 
      </form>	
      
		
      </div>
      <div class="modal-footer">
      	
        <button type="button" id='redirectButton'class="btn btn-primary" style="display:none" 
		onclick="redirectToanotherFor()" >GO</button>
      </div>
    </div>

  </div>
</div>
<?php $this->load->view('admin/HtmlFormTemplate/html_modal_page'); ?>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php $this->load->view('_partials/footer'); ?>
<style>
	.spanLabel{
		background-color: #0c0c0c24;
		padding: 4px;
	}
</style>

<script>
	$( function() {
		//$( "#editor" ).draggable();
		$( "#editor" ).droppable();
	} )
</script>

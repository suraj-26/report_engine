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
		</style>
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Html Form Template Details</h1>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-6 col-lg-6">
					<div class="card">
						<form id="doctor_form" method="post" data-form-valid="uploadSection">
							<div class="card-body">
								<div class="align-items-baseline justify-content-between px-4 row">
									<div class="section-title">Section Details</div>
									<div class="row">
										<label class="custom-switch custom-control-inline px-2">
											<input type="checkbox" class="custom-switch-input"
												   id="ck_template_history"
												   name="ck_template_history"
												   onchange="onHistorySetting(this)"
											>
											<span class="custom-switch-indicator"></span>
											<span class="custom-switch-description">History</span>
										</label>
										<label class="custom-switch custom-control-inline">
											<input type="checkbox" class="custom-switch-input" checked
												   id="ck_template_transaction"
												   name="ck_template_transaction">
											<span class="custom-switch-indicator"></span>
											<span class="custom-switch-description">Transaction Date</span>
										</label>
									</div>
								</div>

								<div class="form-group">
									<label>Section Name</label>
									<input type="text" class="form-control" name="section_name" id="section_name" data-valid="required"
										   data-msg="Enter section name" />
									<input type="hidden" name="section_id" id="section_id"/>
									<input type="hidden" name="department_id" id="department_id" />
									<input type="hidden" name="elementSequenceType" id="elementSequenceType"/>
									<input type="hidden" name="elementSequenceId" id="elementSequenceId"/>
								</div>
								<div class="bg-secondary droppable-box">
									<ul id="questionListSortable" class="list-group connected-sortable"
										style="min-height: 20px"></ul>
								</div>


							</div>
							<div class="card-footer text-right">
								<button class="btn btn-primary mr-1" id="templateFornBtn" type="submit">Submit</button>
								<!-- <button class="btn btn-secondary" type="reset">Reset</button> -->
							</div>
						</form>
					</div>
				</div>
				<div class="col-12 col-md-6 col-lg-6">
					<div class="card">

						<div class="card-body">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active show" id="section-tab" data-toggle="tab"
									   href="#sectionTab" role="tab" aria-controls="section" aria-selected="false">Setting</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="question-tab" data-toggle="tab" href="#questionTab"
									   role="tab" aria-controls="question" aria-selected="true">Questions</a>
								</li>

							</ul>
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade" id="questionTab" role="tabpanel"
									 aria-labelledby="question-tab">
									<ul class="list-group connected-sortable" id="questionMasterSortable">

										<li class="list-group-item" data-type="1">
											<i class="fas fa-font"></i> Short Text
										</li>
										<li class="list-group-item" data-type="2">
											<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAAW0lEQVRIie2TMQrAMAwDL3ldSv//gaT/SIe4dOnggA0ddCCwFsmLQIgITuACZrAGcGBHdPijXuxIo2aGq+B/BcXk9dsFKVTWiuEdB07vYcCac8aaO9A2HxLigxsxxE0Q/2+PkwAAAABJRU5ErkJggg=="/>
											Long Text
										</li>
										<li class="list-group-item" data-type="3">
											<i class="fas fa-chevron-circle-down"></i>
											Drop-down
										</li>
										<li class="list-group-item" data-type="4">
											<i class="far fa-caret-square-down"></i>
											Multiple Selection
										</li>
										<li class="list-group-item" data-type="5">
											<i class="far fa-calendar-alt"></i>
											Date
										</li>
										<li class="list-group-item" data-type="6">
											<i class="fas fa-hashtag"></i>
											Number
										</li>
										<li class="list-group-item" data-type="7">
											<i class="fas fa-paperclip"></i>
											Attachment
										</li>
										<li class="list-group-item" data-type="8">
											<i class="fas fa-chevron-circle-down"></i>
											Query Drop-down
										</li>
										<li class="list-group-item" data-type="10">
											<i class="fas fa-hashtag"></i>
											Number with fixed value
										</li>
										<li class="list-group-item" data-type="9">
											<i class="fas fa-chevron-circle-down"></i>
											Fix Query Drop-down
										</li>
										<li class="list-group-item" data-type="11">
											<i class="fas fa-font"></i> Label
										</li>
										<li class="list-group-item" data-type="12">
											<i class="fas fa-font"></i> Checkbox group
										</li>
										<li class="list-group-item" data-type="13">
											<i class="fas fa-font"></i> Radio group
										</li>
										<li class="list-group-item" data-type="14">
											<i class="fas fa-caret-square-right"></i> Button
										</li>
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
			</div>
		</div>
	</section>
</div>

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg d-none" data-toggle="modal" data-target="#htmlFormModal">Open Modal</button>

<!-- Modal -->
<div id="htmlFormModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title">Form Design</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
      	
        <div id="htmlFormModalDatatrumbyog">
        	<div><h6>Choose Your fileds Here...</h6><span id="htmlFormModalDataFileds"></span></div>
        	<textarea id="editor"></textarea>
        	<div class="row">
        	<button type="button" class="btn btn-primary mt-2" onclick="saveDesignWindow()" style="margin-left: auto;">Save Form</button></div>
        </div>
       	<div><h4>Form Design</h4></div>
       
         <div class="col-md-12" id="htmlFormModalData"></div>
      </div>
      <div class="modal-footer">
      	<input type="hidden" name="htmlSectionId" id="htmlSectionId" class="htmlSectionId">
      	<!-- <button type="button" class="btn btn-primary" onclick="getDesignWindow()">EditHtml</button> -->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php $this->load->view('_partials/footer'); ?>


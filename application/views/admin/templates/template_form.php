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
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>Template Details</h1>
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
								<button class="btn btn-primary mr-1" type="submit" id="templateFornBtn">Submit</button>
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


<?php $this->load->view('_partials/footer'); ?>


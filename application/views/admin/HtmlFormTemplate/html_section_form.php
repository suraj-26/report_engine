<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
	

	.accordion .accordion-header[aria-expanded="true"] {
		box-shadow: 0 2px 6px #891635 !important;
		background-color: #891635 !important;
	}
	option
	{
		text-transform: capitalize;
	}
	
</style>

<div class="main-content main-content1">
	<section class="section">
		<div class="section-header card-primary" style="border-top: 2px solid #891635">
			<h1>-</h1>
		</div>
		<div class="section-body">
			<div class=" justify-content-center">
				<div class="">
					<div class="card">
						<div class="card-body">
							<input type="hidden" id="section_id" name="section_id"
								   value="<?= $section_id ?>">
								   <input type="hidden" id="queryparameter_hidden" name="queryparameter_hidden"
								   value="<?= $queryParam ?>">
						
							
							
							
							
								<!-- <div class="card card_border">
					                <div class="card-header card_head_back">
					                    	<h4 id="section_name">-</h4>
					                    <div class="card-header-action">
					                    	<button class="btn btn-default" type="button" style="float:right;display:none" id="formButton" onclick="switch_form_history(2)"> View Form</button>
					                     	<button class="btn btn-default" type="button"id="HistoryButton" style="float:right;margin-right:10px;display:none;"onclick="switch_form_history(1)"> View History</button>
					                    </div>
					                </div>
				                  	<div class="card-body card_body">
					                	
					                </div>
				                </div> -->
				                <div id="ShowForm">
					                  		 <div id="form_data"></div>
					                  		 <div id="form_data1"></div>
					                    </div>
										<div id="ShowHistory" style="display:none">
											History Table show here	
										</div>
							
						
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="fire-modal-1" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Are You Sure?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				This action can not be undone. Do you want to continue?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-shadow" id="">Yes</button>
				<button type="button" class="btn btn-secondary" id="">Cancel</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('admin/HtmlFormTemplate/html_modal_page'); ?>

<?php $this->load->view('_partials/footer'); ?>
<script type="text/javascript">
	var base_url="<?php echo base_url(); ?>";
$(document).ready(function () {
	localStorage.clear();
	var section_id = $("#section_id").val();
	get_forms(section_id);
		GetFormHistorYButtons();
});

</script>
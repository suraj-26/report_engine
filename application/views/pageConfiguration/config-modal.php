<div class="modal fade"
	 role="dialog" id="ConfigurationModal"
	 style="display: none; padding-right: 17px;">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Configurations</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<section class="card" id="shot_text_section" style="display:none;">
					<form id="short_text_form">
						<input type="hidden" name="id" id="text_id" value="1">
						<input type="hidden" name="row" id="text_row" value="">
						<input type="hidden" name="col" id="text_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="shot_text_name">
							</div>
							<div class="form-group my-0 py-0">
								<button class="btn btn-link btn-sm" type="button"
										onclick="$('#shot_text_placeholder').toggleClass('d-none')">
									add placeholder
								</button>
								<div class="form-group my-0 py-0 d-none" id="shot_text_placeholder">
									<input type="text" class="form-control" id="shot_text_place_value" name="placeholder" value=""
										   placeholder="write something here">
								</div>
							</div>
							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="shot_text_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="defaultHide" id="shot_text_hide" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Default Hide</span>
								</label>
							</div>
							<div class="form-group">
								<label>Custom validation method & message</label>
								<input type="text" class="form-control" name="custom_validation" id="text_custom_validation">
								<small>Example : methodname||write message here</small>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('short_text_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>
				<section class="card" id="email_section" style="display:none;">
					<form id="email_text_form">
						<input type="hidden" name="id" id="email_id" value="23">
						<input type="hidden" name="row" id="email_row" value="">
						<input type="hidden" name="col" id="email_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="email_text_name">
							</div>
							<div class="form-group my-0 py-0">
								<button class="btn btn-link btn-sm" type="button"
										onclick="$('#email_text_placeholder').toggleClass('d-none')">
									add placeholder
								</button>
								<div class="form-group my-0 py-0 d-none" id="email_text_placeholder">
									<input type="text" class="form-control" id="email_text_place_value" name="placeholder" value=""
										   placeholder="write something here">
								</div>
							</div>
							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="email_text_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('email_text_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="textarea_section" style="display:none;">
					<form id="textarea_form">
						<input type="hidden" name="id" id="textarea_id" value="2">
						<input type="hidden" name="row" id="textarea_row" value="">
						<input type="hidden" name="col" id="textarea_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="shot_textarea_name">
							</div>
							<div class="form-group my-0 py-0">
								<button class="btn btn-link btn-sm" type="button"
										onclick="$('#shot_textarea_placeholder').toggleClass('d-none')">
									add placeholder
								</button>
								<div class="form-group my-0 py-0 d-none" id="shot_textarea_placeholder">
									<input type="text" class="form-control" id="textarea_placeholder" name="placeholder" value=""
										   placeholder="write something here">
								</div>
							</div>
							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="textarea_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('textarea_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="number_section" style="display:none;">
					<form id="number_form">
						<input type="hidden" name="id" id="number_id" value="3">
						<input type="hidden" name="row" id="number_row" value="">
						<input type="hidden" name="col" id="number_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="number_name">
							</div>
							<div class="section"><div class="section-title">Input Value</div></div>

							<div class="form-group">
								<label>Minimum Value</label>
								<input type="number" class="form-control" name="min_value" id="number_min_value">
							</div>

							<div class="form-group">
								<label>Maximum Value</label>
								<input type="number" class="form-control" name="max_value" id="number_max_value">
							</div>
							<div class="section"><div class="section-title">Input Length</div></div>
							<div class="form-group">
								<label>Min Length</label>
								<input type="number" class="form-control" name="min_length" id="number_min_length">
							</div>

							<div class="form-group">
								<label>Max Length</label>
								<input type="number" class="form-control" name="max_length" id="number_max_length">
							</div>

							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="number_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('number_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="date_section" style="display:none;">
					<form id="date_form">
						<input type="hidden" name="id" id="date_id" value="4">
						<input type="hidden" name="row" id="date_row" value="">
						<input type="hidden" name="col" id="date_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="date_name">
							</div>

							<div class="form-group">
								<label>Default Value</label>
								<select class="form-control" name="default_value_option" id="date_default_value"
										onchange="getdateOption()">
									<option value="-1" selected>Select Option</option>
									<option value="today_date">
										Today Date
									</option>
									<option value="specific_date">Specific Date</option>
								</select>


								<input type="date" class="form-control mt-2" style="display: none" name="default_value"
									   id="specific_date_value">
							</div>


							<div class="form-group" id="min_div">
								<label>Minimum Date</label>
								<input type="date" class="form-control" name="min_value" id="date_min_value">
							</div>

							<div class="form-group" id="max_div">
								<label>Maximum Date</label>
								<input type="date" class="form-control" name="max_value" id="date_max_value">
							</div>

							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="date_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('date_form')">Save</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="file_section" style="display:none;">
					<form id="file_form">
						<input type="hidden" name="id" id="file_id" value="5">
						<input type="hidden" name="row" id="file_row" value="">
						<input type="hidden" name="col" id="file_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="file_name">
							</div>

							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="file_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('file_form')">Save</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="single_selection_section" style="display:none;">
					<form id="single_form">
						<input type="hidden" name="id" id="single_id" value="6">
						<input type="hidden" name="row" id="single_row" value="">
						<input type="hidden" name="col" id="single_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="single_name">
							</div>

							<div class="form-group">
								<label>Option Array</label>
								<textarea name="option_array" id="single_option_array" class="form-control" rows="5"
										  cols="5" placeholder="Type Your Option Array Here..."></textarea>
								<small class="text-dark text-monospace">Example Array : [{"id":1, "text":"option1"},{"id":2, "text":"option2"}]</small>
								<span class="text-info m-2" title="copy" onclick="copyToClipboard()"><i class="fa fa-copy"></i></span>
							</div>
							<div class="form-group">
								<label>HideShowColumn Array</label>
								<textarea name="hideshow_array" id="single_hideshow_array" class="form-control" rows="5"
										  cols="5" placeholder="Type Your Option Array Here..."></textarea>
								<small class="text-dark text-monospace">Example Array :
									[{"type":1 // value base update,
									"visibleFields":[{"-1//add always minus one":["col1","col2"],"action":1 //show,0 //hide,2 //toogle}]},
									{"type":2 // onchange(this.value),
									"visibleFields":[{"1":["col1","col2"],"action":1 //show,0 //hide,2 //toogle}]}]</small>
								<span class="text-info m-2" title="copy" onclick="copyToClipboard()"><i class="fa fa-copy"></i></span>
							</div>

							<div class="form-group">
								<label>Default Value</label>
								<input type="text" class="form-control" name="default_value" id="single_default_value">
							</div>

							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="single_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="defaultHide" id="single_defaultHide" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Default Hide</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('single_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="multiple_selection_section" style="display:none;">
					<form id="multiple_form">
						<input type="hidden" name="id" id="multiple_id" value="7">
						<input type="hidden" name="row" id="multiple_row" value="">
						<input type="hidden" name="col" id="multiple_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="multiple_name">
							</div>

							<div class="form-group">
								<label>Option Array</label>
								<textarea name="option_array" id="multiple_option_array" class="form-control" rows="5"
										  cols="5" placeholder="Type Your Option Array Here..."></textarea>
								<small class="text-dark text-monospace">Example Array : [{"id":1, "text":"option1"},{"id":2, "text":"option2"}]</small>
								<span class="text-info m-2" title="copy" onclick="copyToClipboard()"><i class="fa fa-copy"></i></span>
							</div>

							<div class="form-group">
								<label>Default Value</label>
								<input type="text" class="form-control" name="default_value"
									   id="multiple_default_value">
							</div>

							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="multiple_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="defaultHide" id="multiple_defaultHide" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Default Hide</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('multiple_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="radio_section" style="display:none;">
					<form id="radio_form">
						<input type="hidden" name="id" id="radio_id" value="8">
						<input type="hidden" name="row" id="radio_row" value="">
						<input type="hidden" name="col" id="radio_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="radio_name">
							</div>

							<div class="form-group">
								<label>Option Array</label>
								<textarea name="option_array" id="radio_option_array" class="form-control" rows="5"
										  cols="5" placeholder="Type Your Option Array Here..."></textarea>
								<small class="text-dark text-monospace">Example Array : [{"id":1, "text":"option1"},{"id":2, "text":"option2"}]</small>
								<span class="text-info m-2" title="copy" onclick="copyToClipboard()"><i class="fa fa-copy"></i></span>
							</div>

							<div class="form-group">
								<label>Default Value</label>
								<input type="text" class="form-control" name="default_value" id="radio_default_value">
							</div>

							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="radio_checkbox" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('radio_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="checkbox_section" style="display:none;">
					<form id="checkbox_form">
						<input type="hidden" name="id" id="checkbox_id" value="9">
						<input type="hidden" name="row" id="checkbox_row" value="">
						<input type="hidden" name="col" id="checkbox_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="checkbox_name">
							</div>

							<div class="form-group">
								<label>Option Array</label>
								<textarea name="option_array" id="checkbox_option_array" class="form-control" rows="5"
										  cols="5" placeholder="Type Your Option Array Here..."></textarea>
								<small class="text-dark text-monospace">Example Array : [{"id":1, "text":"option1"},{"id":2, "text":"option2"}]</small>
								<span class="text-info m-2" title="copy" onclick="copyToClipboard()"><i class="fa fa-copy"></i></span>
							</div>

							<div class="form-group">
								<label>Default Value</label>
								<input type="text" class="form-control" name="default_value"
									   id="checkbox_default_value">
							</div>

							<div class="custom-control-inline">
								<label class="custom-switch mt-2">
									<input type="checkbox" name="checkbox" id="checkbox_check" class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">Required</span>
								</label>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('checkbox_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="hidden_section" style="display:none;">
					<form id="hidden_form">
						<input type="hidden" name="id" id="hidden_id" value="10">
						<input type="hidden" name="row" id="hidden_row" value="">
						<input type="hidden" name="col" id="hidden_col" value="">
						<div class="card-body">

							<div class="form-group">
								<label for="">Session names : </label> <b><?php print_r(implode(', ',array_keys((array)$this->session->user_session))); ?></b>
							</div>

							<div class="form-group">
								<label>
									Select One
								</label>
								<input type="radio" value="default_value" checked
									   onchange="$('#hidden_default_value').val('')" name="default_value_option"
									   id="hidden_default_value_option1">Default Value
								<input type="radio" value="session_value" onchange="$('#hidden_default_value').val('')"
									   name="default_value_option" id="hidden_default_value_option2">Session Value
							</div>

							<div class="form-group">
								<label>Value</label>
								<input type="text" placeholder="Enter your value here.." name="default_value"
									   class="form-control" id="hidden_default_value">
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('hidden_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="button_section" style="display:none;">
					<form id="button_form">
						<input type="hidden" name="id" id="button_id" value="11">
						<input type="hidden" name="row" id="button_row" value="">
						<input type="hidden" name="col" id="button_col" value="">
						<div class="card-body">

<!--							<div class="row">-->
<!--								<input type="hidden" name="MainCount" id="MainCount" value="0">-->
<!--								<div class="form-group col-12">-->
<!--									<button class="btn btn-primary float-right" onclick="AddTransaction()" type="button">Add More Transaction</button>-->
<!--								</div>-->
<!---->
<!--								<div class="col-md-12">-->
<!--									<div class="form-group">-->
<!--										<select class="form-control transactionBtn Maintable select2" onchange="getButtonData(1)" name="toTable"-->
<!--												id="toTable1">-->
<!---->
<!--										</select>-->
<!--									</div>-->
<!--								</div>-->
<!---->
<!--								<div class="col-md-12">-->
<!--									<div id="config_details1">-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->

<!--							<hr>-->
<!---->
<!--							<div class="MainDiv" id="MainDiv">-->
<!---->
<!---->
<!---->
<!--							</div>-->

							<div class="row col-12 m-2">
								<textarea class="form-control" cols="5" name="btnConfig" id="btnConfig">
								</textarea>
								<small class="text-dark text-monospace">Example Array :
									{"transaction":[{"id":"T1","table":"table","method":1,"column":[{"where_column":"value"}],"whereColumn":[{}],"dependent_on":[],
									"is_bulk":0},{"id":"T2","table":"TE_product_map","method":1,"column":[{"where_column":"value"}],"whereColumn":[{}],
									"dependent_on":["T1"],"is_bulk":1}],"action":{"method":1,"templateID":34,"extra":["T1.insert_id"]}}
								</small>
								<span class="text-info m-2" title="copy" onclick="copyToClipboard(1)"><i class="fa fa-copy"></i></span>
							</div>
							<div class="row col-12 m-2 d-none">
								<input type="checkbox" id="btnAction" name="btnAction" onclick="showButtonActionConfig()">
								<label for="btnAction">Action</label>
							</div>
							<div class="row col-12 m-2" id="actionConfig" style="display: none">
								<select name="btnActionId" id="btnActionId" class="templateOptions">

								</select>
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveConfigData()">Save</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="queryDropdown_section" style="display:none;">
					<form id="queryDropdown_form">
						<input type="hidden" name="id" id="queryDropdown_id" value="12">
						<input type="hidden" name="row" id="queryDropdown_row" value="">
						<input type="hidden" name="col" id="queryDropdown_col" value="">
						<div class="card-body">

							<input type="hidden" name="method" value="1" id="QueryDropdown_method">

							<div class="form-group">
								<button class="btn btn-link" onclick="changeTab('form_through_div','normal_query_div',1)" type="button">Form Through</button>
								<button class="btn btn-link" onclick="changeTab('normal_query_div','form_through_div',2)" type="button">Normal Query</button>
							</div>
							<div class="form-group row">
								<div class="form-group col-4">
									<label>Label</label>
									<input type="text" placeholder="type here.." class="form-control" name="label" id="queryDropdown_label">
								</div>
								<div class="custom-control-inline col-2">
									<label class="custom-switch mt-2">
										<input type="checkbox" name="checkbox" id="queryDropdown_checkbox" class="custom-switch-input">
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">Required</span>
									</label>
								</div>
								<div class="custom-control-inline col-2">
									<label class="custom-switch mt-2">
										<input type="checkbox" name="queryDropdown_multiple" id="queryDropdown_multiple" class="custom-switch-input">
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">Multiple</span>
									</label>
								</div>
								<div class="custom-control-inline">
									<label class="custom-switch mt-2">
										<input type="checkbox" name="defaultHide" id="queryDropdown_defaultHide" class="custom-switch-input">
										<span class="custom-switch-indicator"></span>
										<span class="custom-switch-description">Default Hide</span>
									</label>
								</div>

							</div>

							<div id="form_through_div">

								<div class="form-group row">
									<div class="col-md-6">
										<label>Select Table</label>
										<select name="query_table" onchange="getQueryDropdownData()" id="query_table" class="form-control select2"></select>
									</div>
									<div class="col-md-6">

									</div>
								</div>

								<div class="form-group row">

									<div class="col-md-6">
										<label>Select Option Value </label>
										<select name="query_option_value" id="query_option_value" class="form-control queryDropdownSelect select2"></select>
									</div>

									<div class="col-md-6">
										<label>Select Option Name </label>
										<select name="query_option_name" id="query_option_name" class="form-control queryDropdownSelect select2"></select>
									</div>
								</div>
								<div class="form-group row">
									<input type="hidden" name="where_row_count" id="where_row_count" value="0">
									<button class="btn btn-outline-danger" type="button" onclick="getDependentWhereDiv()">Add Where Condition</button>
								</div>
								<div class="form-group row" id="query_where_div"></div>

								<hr>
								<div class="form-group row">
<!--									<button class="btn btn-link" type="button" onclick="$('#is_dependent_div').toggleClass('d-none')">is Dependent ?</button>-->
									<input type="hidden" name="is_im_dependent_count" id="is_im_dependent_count" value="0">
									<button class="btn btn-outline-danger" type="button" onclick="getImDependentDiv()">is Dependent ?</button>
								</div>

								<div class="form-group row" id="is_dependent_div">

									<div class="col-md-6">
										<label>Where Dependent Value Check  </label>
										<select name="dependent_value_check" id="dependent_value_check" class="form-control queryDropdownSelect select2"></select>
									</div>

									<div class="col-md-6">
										<label>Dependent On </label>
										<select name="dependent_on" id="dependent_on" class="form-control queryDropdownLabel select2"></select>
									</div>
								</div>
								<div class="form-group row" id="is_im_dependent_div"></div>

								<div class="form-group row">
									<input type="hidden" name="is_dependent_count" id="is_dependent_count" value="0">
									<button class="btn btn-outline-danger" type="button" onclick="getDependentDiv()">is anyone Dependent ?</button>
								</div>
								<div class="form-group row" id="is_dependent_row_div"></div>





							</div>

							<div id="normal_query_div" style="display: none">
								<div class="form-group row">
									<label>Query :</label>
									<textarea class="form-control" rows="5" cols="5" name="normal_query" id="normal_query"></textarea>
								</div>
							</div>


							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveQueryDropdown('queryDropdown_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="excel_report_section" style="display:none;">
					<form id="excel_report_form">
						<input type="hidden" name="id" id="excel_report_id" value="13">
						<input type="hidden" name="row" id="excel_report_row" value="">
						<input type="hidden" name="col" id="excel_report_col" value="">
						<div class="card-body">
							<div class="form-group row">
								<div class="col-md-4">
									<lable>Report Name</lable>
									<input type="text" class="form-control" id="excel_report_name" value=""
										   name="report_name">
								</div>
								<div class="col-md-4">
									<lable>Button Display Name</lable>
									<input type="text" class="form-control" id="excel_sub_report_name" value=""
										   name="sub_report_name">
								</div>

							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<lable>Report Types</lable>
									<input type="checkbox" name="excel_report_type" checked id="excel_report_type"><label class="m-2"
											for="excel_report_type"> Excel Report</label>
									<input type="checkbox" name="pdf_report_type" checked id="pdf_report_type"><label class="m-2"
											for="pdf_report_type"> PDF Report</label>
									<input type="checkbox" name="csv_report_type" checked id="csv_report_type"><label  class="m-2"
											for="csv_report_type"> CSV Report</label>
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-12">
									<lable>Query</lable>
									<textarea class="form-control" id="excel_query_data" name="query_data"
											  style="height: 91px;"></textarea>
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter1</lable>
									<input type="text" class="form-control" value="" id="excel_param1" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype1</lable>
									<select class="form-control" id="excel_datatype1" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable1</lable>
									<input type="text" class="form-control" value="" id="excel_lable1" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option1</lable>
									<input type="text" class="form-control" value="" id="excel_option1" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter2</lable>
									<input type="text" class="form-control" value="" id="excel_param2" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype2</lable>
									<select class="form-control" id="excel_datatype2" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable2</lable>
									<input type="text" class="form-control" value="" id="excel_lable2" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option2</lable>
									<input type="text" class="form-control" value="" id="excel_option2" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter3</lable>
									<input type="text" class="form-control" value="" id="excel_param3" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype3</lable>
									<select class="form-control" id="excel_datatype3" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable3</lable>
									<input type="text" class="form-control" value="" id="excel_lable3" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option3</lable>
									<input type="text" class="form-control" value="" id="excel_option3" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter4</lable>
									<input type="text" class="form-control" value="" id="excel_param4" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype4</lable>
									<select class="form-control" id="excel_datatype4" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable4</lable>
									<input type="text" class="form-control" value="" id="excel_lable4" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option4</lable>
									<input type="text" class="form-control" value="" id="excel_option4" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter5</lable>
									<input type="text" class="form-control" value="" id="excel_param5" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype5</lable>
									<select class="form-control" id="excel_datatype5" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable5</lable>
									<input type="text" class="form-control" value="" id="excel_lable5" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option5</lable>
									<input type="text" class="form-control" value="" id="excel_option5" name="option[]">
								</div>
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button"
										onclick="saveReportData('excel_report_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="pdf_report_section" style="display:none;">
					<form id="pdf_report_form">
						<input type="hidden" name="id" id="pdf_report_id" value="14">
						<input type="hidden" name="row" id="pdf_report_row" value="">
						<input type="hidden" name="col" id="pdf_report_col" value="">
						<div class="card-body">
							<div class="form-group row">
								<div class="col-md-4">
									<lable>Report Name</lable>
									<input type="text" class="form-control" id="report_name" value=""
										   name="report_name">
								</div>
								<div class="col-md-4">
									<lable>Button Display Name</lable>
									<input type="text" class="form-control" id="sub_report_name" value=""
										   name="sub_report_name">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-12">
									<lable>Query</lable>
									<textarea class="form-control" id="query_data" name="query_data"
											  style="height: 91px;"></textarea>
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter1</lable>
									<input type="text" class="form-control" value="" id="param1" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype1</lable>
									<select class="form-control" id="datatype1" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable1</lable>
									<input type="text" class="form-control" value="" id="lable1" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option1</lable>
									<input type="text" class="form-control" value="" id="option1" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter2</lable>
									<input type="text" class="form-control" value="" id="param2" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype2</lable>
									<select class="form-control" id="datatype2" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable2</lable>
									<input type="text" class="form-control" value="" id="lable2" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option2</lable>
									<input type="text" class="form-control" value="" id="option2" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter3</lable>
									<input type="text" class="form-control" value="" id="param3" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype3</lable>
									<select class="form-control" id="datatype3" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable3</lable>
									<input type="text" class="form-control" value="" id="lable3" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option3</lable>
									<input type="text" class="form-control" value="" id="option3" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter4</lable>
									<input type="text" class="form-control" value="" id="param4" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype4</lable>
									<select class="form-control" id="datatype4" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable4</lable>
									<input type="text" class="form-control" value="" id="lable4" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option4</lable>
									<input type="text" class="form-control" value="" id="option4" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter5</lable>
									<input type="text" class="form-control" value="" id="param5" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype5</lable>
									<select class="form-control" id="datatype5" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable5</lable>
									<input type="text" class="form-control" value="" id="lable5" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option5</lable>
									<input type="text" class="form-control" value="" id="option5" name="option[]">
								</div>
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveReportData('pdf_report_form')">
									Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<section class="card" id="csv_report_section" style="display:none;">
					<form id="csv_report_form">
						<input type="hidden" name="id" id="csv_report_id" value="13">
						<input type="hidden" name="row" id="csv_report_row" value="">
						<input type="hidden" name="col" id="csv_report_col" value="">
						<div class="card-body">
							<div class="form-group row">
								<div class="col-md-4">
									<lable>Report Name</lable>
									<input type="text" class="form-control" id="report_name" value=""
										   name="report_name">
								</div>
								<div class="col-md-4">
									<lable>Button Display Name</lable>
									<input type="text" class="form-control" id="sub_report_name" value=""
										   name="sub_report_name">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-12">
									<lable>Query</lable>
									<textarea class="form-control" id="query_data" name="query_data"
											  style="height: 91px;"></textarea>
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter1</lable>
									<input type="text" class="form-control" value="" id="param1" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype1</lable>
									<select class="form-control" id="datatype1" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable1</lable>
									<input type="text" class="form-control" value="" id="lable1" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option1</lable>
									<input type="text" class="form-control" value="" id="option1" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter2</lable>
									<input type="text" class="form-control" value="" id="param2" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype2</lable>
									<select class="form-control" id="datatype2" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable2</lable>
									<input type="text" class="form-control" value="" id="lable2" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option2</lable>
									<input type="text" class="form-control" value="" id="option2" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter3</lable>
									<input type="text" class="form-control" value="" id="param3" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype3</lable>
									<select class="form-control" id="datatype3" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable3</lable>
									<input type="text" class="form-control" value="" id="lable3" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option3</lable>
									<input type="text" class="form-control" value="" id="option3" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter4</lable>
									<input type="text" class="form-control" value="" id="param4" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype4</lable>
									<select class="form-control" id="datatype4" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable4</lable>
									<input type="text" class="form-control" value="" id="lable4" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option4</lable>
									<input type="text" class="form-control" value="" id="option4" name="option[]">
								</div>
							</div>
							<div class="form-group row">

								<div class="col-md-3">
									<lable>parameter5</lable>
									<input type="text" class="form-control" value="" id="param5" name="param[]">
								</div>
								<div class="col-md-3">
									<lable>Datatype5</lable>
									<select class="form-control" id="datatype5" name="datatype[]">
										<option value="1">Text</option>
										<option value="2">Date</option>
										<option value="3">Numeric</option>
										<option value="4">Alpha Numeric</option>
									</select>
								</div>
								<div class="col-md-3">
									<lable>Lable5</lable>
									<input type="text" class="form-control" value="" id="lable5" name="label[]">
								</div>
								<div class="col-md-3">
									<lable>Option5</lable>
									<input type="text" class="form-control" value="" id="option5" name="option[]">
								</div>
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveReportData('csv_report_form')">
									Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<!-- start query parameter -->
				<section class="card" id="queryParam_section" style="display:none;">
					<form id="queryParam_form">
						<input type="hidden" name="id" id="queryParam_id" value="17">
						<input type="hidden" name="row" id="queryParam_row" value="">
						<input type="hidden" name="col" id="queryParam_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Key</label>
								<input type="text" placeholder="Enter your Key here.." name="default_value"
									   class="form-control" id="queryParam_default_value">
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveData('queryParam_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>
				<!-- end query parameter -->
				<!--start template form-->
					<section class="card" id="templateForm_section" style="display:none;">
						<form id="templateForm_form">
							<input type="hidden" name="id" id="templateForm_id" value="18">
							<input type="hidden" name="row" id="templateForm_row" value="">
							<input type="hidden" name="col" id="templateForm_col" value="">
							<div class="card-body">
								<div class="form-group">
									<label>Header Name</label>
									<input type="text" class="form-control" name="label" id="templateForm_name">
								</div>
								<div class="form-group">
									<label>Select Template</label>
									<select class="form-control templateOptions" name="default_value" id="templateForm_default_value">

									</select>
								</div>

								<div class="form-group">
									<label>Show Form on Onload?</label>
									<select name="default_value_option" id="onload" class="form-control select2">
										<option selected value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</div>

								<div class="form-group">
									<button class="btn btn-dark" type="button" onclick="saveData('templateForm_form')">Save
									</button>
								</div>
							</div>
						</form>
					</section>
				<!--end template form-->

				<section class="card" id="handson_section" style="display:none;">
					<form id="handson_form">
						<input type="hidden" name="id" id="handson_id" value="19">
						<input type="hidden" name="row" id="handson_row" value="">
						<input type="hidden" name="col" id="handson_col" value="">
						<div class="card-body">

							<div class="form-group row">
								<label>Select Handsontable</label>
								<select class="select2 form-conrol" id="handsontable" name="handsontable"></select>
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveHandson()">
									Save
								</button>
							</div>
						</div>
					</form>
				</section>

				<!--Start Input Search form-->
				<section class="card" id="inputSearch_section" style="display:none;">
					<form id="inputSearch_form">
						<input type="hidden" name="id" id="inputSearch_id" value="20">
						<input type="hidden" name="row" id="inputSearch_row" value="">
						<input type="hidden" name="col" id="inputSearch_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="inputSearch_name">
							</div>
							<div class="form-group py-0">
								<label>Query Configuration</label>
								<textarea name="inputSearch_query" id="inputSearch_query" cols="30" rows="10" class="form-control"></textarea>
								<small>[{"table":"TE_product_master","select":["column1","column2"],
										"searchColumn":["column1","column2"],
										"orderColumn":[["column1","direction1"],["name","asc"],["id","desc"]],
											"where":[
												{
												"columnName":"item_name",
												"columnValue":{"type":1,"typeValue":"session_name"}
												},
												{
												"columnName":"item_name",
												"columnValue":{"type":2,"typeValue":"static value"}
												},
												{
												"columnName":"item_name",
												"columnValue":{"type":3,"typeValue":"#firm_id"}
												},
												{
												"columnName":"item_name",
												"columnValue":{"type":4,"typeValue":"(select tt.price from TE_test tt where  tt.item_name=q.item_name)"}
												},
												{
												"columnName":"item_name",
												"columnValue":{"type":5,"typeValue":"inputSearch342"}
												}
											],

											"IsAnyoneDepend":[{"elementId":"label","type":1,"columnName":"id"},
													{"elementId":"label","type":2,"columnName":"id","queryValue":"(select tt.price from TE_test tt where  tt.item_name=?)"}]
										}]</small>
							</div>
							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveInputSearchData('inputSearch_form')">Save
								</button>
							</div>
						</div>
					</form>
				</section>
				<!--End Input Search form-->
				<!--Start Input Search form-->
				<section class="card" id="modalButton_section" style="display:none;">
					<form id="modalButton_form">
						<input type="hidden" name="id" id="modalButton_id" value="21">
						<input type="hidden" name="row" id="modalButton_row" value="">
						<input type="hidden" name="col" id="modalButton_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="modalButton_name">
							</div>
							<div class="form-group row">
								<label>Select template</label>
								<select name="modalButtonId" id="modalButtonId" class="templateOptions">

								</select>
							</div>

							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveModalButtonData('modalButton_form')">
									Save
								</button>
							</div>
						</div>
					</form>
				</section>
				<!--END Input Search form-->
				<!--Start dropdown with buttons form-->
				<section class="card" id="dropdownButton_section" style="display:none;">
					<form id="dropdownButton_form">
						<input type="hidden" name="id" id="dropdownButton_id" value="22">
						<input type="hidden" name="row" id="dropdownButton_row" value="">
						<input type="hidden" name="col" id="dropdownButton_col" value="">
						<div class="card-body">
							<div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="dropdownButton_name">
							</div>
							<div class="form-group row">
								<label>Dropdown Configuration</label>
								<textarea name="dropdownButton_dropdown" id="dropdownButton_dropdown" cols="30" rows="10" class="form-control"></textarea>
								<small>example : [{"label":"QueryDropdownNew","method":"1//query 2:normal query",
									"queryDependentTable":"TE_product_master",
														"queryDependentOn":"","queryDependentValueCheck":"",
														"queryDependentWhere":[["whereColumn=status","whereValueType=1//static 2//session","whereValue=1"]],
														"queryDependentoptionName":"name",
														"queryDependentoptionValue":"id",
														"queryisDependentOnOther":[{"dValue":"text370", "dQuery":"","dColumn":"name"}],
														"normal_query":"select * from TE_product_master where status=1"},
														"type":"1//single selection 2// multiple selection 3//query dropdown",
														"option":[{"id":1, "text":"option1"},{"id":2, "text":"option2"}],
														"button":{"type":"1//modal 2// form","whereElementId":"queryDropdown456","templateId":"45","label":"<i class='fa fa-plus'></i>"}
													}]
								</small>
							</div>


							<div class="form-group">
								<button class="btn btn-dark" type="button" onclick="saveDropdownWithButtonData('dropdownButton_form')">
									Save
								</button>
							</div>
						</div>
					</form>
				</section>
				<!--END Input Search form-->

				<!----Addmore Start----->
				<section class="card" id="addmore_section" style="display:none;">
					<form id="addmore_text_form">
						<input type="hidden" name="id" id="addmore_id" value="24">
						<input type="hidden" name="row" id="addmore_row" value="">
						<input type="hidden" name="col" id="addmore_col" value="">
						<div class="card-body">
						    <div class="form-group">
								<label>Label Name</label>
								<input type="text" class="form-control" name="label" id="label">
							</div>
							<div class="form-group row">
									<div class="col-md-6">
										<label>Select Table</label>
										<select name="query_table" onchange="getQueryTableData()" id="addmore_query_table" class="form-control select2"></select>
									</div>
									
							</div>
								
							<div class="form-group">
								<div class="row" id="addnewRow">
									<div class="col-md-2">
										<label>Column Name</label>
										<input type="text" class="form-control" id="addmore_text_name0"  name="addmore_text_name0" placeholder="Enter Column Name">
									</div>
									
									<div class="col-md-2">
											<lable>Select Type</lable>
											<select class="form-control" id="addmore_selecttype0" name="addmore_selecttype0">
												<option value="numeric">Numeric</option>
												<option value="dropdown">Dropdown</option>
												<option value="text">Text</option>
												<option value="date">Date</option>
												<option value="hidden">Hidden</option>
											</select>
									</div>
									<div class="col-md-1">
											<lable>Is Query</lable>
											<select class="form-control" id="addmore_query0" name="addmore_query0">
												<option value="0">No</option>
												<option value="1">Yes</option>
												
											</select>
									</div>
									<div class="col-md-3">
											<lable>Attribute Value</lable>
											<input type="text" class="form-control" value="" id="attribute_value0" name="attribute_value0" placeholder="Enter Attribute Value">
									</div>
									<div class="col-md-2">
											<lable>Default Value</lable>
											<input type="text" class="form-control" value="" id="default_value0" name="default_value0" placeholder="Enter Default Value">
									</div>
									<div class="col-md-2">
										<label>Select Column Name </label>
										<select name="query_addmore_column" id="query_addmore_column0" class="form-control queryDropdownSelectColumn0 select2" ></select>
									</div>
								</div>
								   
								<input type="hidden" name="addmorecount" id="addmorecount" value="0">
										
								<div id="newRowadd"></div><br>
								<div class="row">
									<div class="col-3">
											<button name="add" type="button" class="btn btn-sm btn-success add" id="addmorerow" onclick="addMoreRow('addmorecount')">
															<i class="fa fa-plus"></i>
											</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<button class="btn btn-dark" type="button" onclick="saveDataAddMore('addmore_text_form')">Save
									</button>
								</div>
							</div>
						</div>
					</form>
				</section>
				<!----Addmore End-------->
			</div>
		</div>
	</div>
</div>





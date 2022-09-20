<div class="modal fade" id="CreateHandsonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog modal-content">
		<div class="modal-content">
			<div class="modal-header">
				<h6 id="myModalLabel">Add SpreadSheet</h6>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
				</button>

			</div>
			<div class="modal-body">
				<form id="template" method="post">
					<div class="row" id="template_master_div">
						<div class="col-md-3 form-group">
							<input type="hidden" id="template_count" name="template_count" value="0">
							<input type="hidden" id="template_id" name="template_id">
							<label>Enter Spreadsheet Name</label>
							<input type="text" id="template_name" name="template_name" class="form-control"
								   placeholder="Spreadsheet Name">
							<span id="template_error"></span>
						</div>
						<div class="col-md-3 form-group">
							<label> <b>Select HashKey</b> : </label>
							<select class="" style="width: 80%" id="hashKey" multiple name="hashKey[]"></select>
						</div>

						<div class="col-md-3 form-group">
							<label> <b>Enter Props ID</b> : </label>
							<input type="text" id="props" name="props" class="form-control"
								   placeholder="example : 0,1,2">
						</div>

						<div class="col-md-3 form-group">
							<label> <b>Enter Required Fields</b> : </label>
							<input type="text" id="required_fields" name="required_fields" class="form-control"
								   placeholder="example : 0,1,2">
						</div>

						<div class="col-md-3 form-group">
							<label> <b>Show Total</b> : </label>
							<input type="text" id="show_total" name="show_total" class="form-control"
								   placeholder="example : prop||div_id ">
						</div>

						<div class="col-md-3 form-group">
							<label> <b>Fill Table Data</b> : </label>
							<input type="radio" id="fill_no" checked name="fill_table" onclick="handleClick(this)" value="1">
							<label for="fill_no">No</label>
							<input type="radio" id="fill_yes" name="fill_table" onclick="handleClick(this)" value="2">
							<label for="fill_yes">Yes</label>
						</div>
						<div class="col-md-3 form-group" id="routePosDiv" style="display: none;">
							<label> <b>From which route position</b> : </label>
							<input type="number" id="route_pos" name="route_pos" class="form-control" min="1"
								   placeholder="Enter position">
						</div>
						<div class="col-md-12 form-group" id="routePosQueryDiv" style="display: none;">
							<label> <b>Query</b> : </label>
							<textarea id="route_pos_query" name="route_pos_query" class="form-control"
								   placeholder="">
							</textarea>
							<small><b>Example : </b>[{"tableName":"product_master_table","selectColumn":[{"columnPosition":0,"columnName":"item_name"}],
								"whereCondition":[
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
								}
								],"orderColumn":[["name","desc"],["id","desc"]],"groupColumn":["id","item_name"]}]</small>
						</div>
						<!--	<div class="col-md-2 form-group"><label> <b>Prefill</b> : </label>
								<input type="radio" name="prefill" id="prefillyes" value="1"> <label
										for="prefillyes">Yes</label>
								<input type="radio" name="prefill" id="prefillno" value="0" checked> <label for="prefillno">No</label>
							</div>
	-->

					</div>

					<div class="row-fluid" id="sortable_div">
						<ul id="template_detail_div" class=""></ul>
					</div>

					<div id="add_more_div">
						<button type="button" id="btn_add_more" class="btn btn-info roundCornerBtn4 xs_btn"
								onclick="createAddTemplateRow()"><i class="fa fa-plus"></i></button>
					</div>

				</form>
			</div>
			<div class="modal-footer" id="template_footer">
				<button class="btn btn-default roundCornerBtn4" data-dismiss="modal" aria-hidden="true">Cancel</button>
				<button id="create_template1" class="btn btn-primary pull-right roundCornerBtn4" type="button"
						onclick="CreateTemplate()"><i class="fa fa-save"></i> Save
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="ConfiguarationModal" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog modal-content">
		<div class="modal-content">
			<div class="modal-header">
				<h6 id="myModalLabel">Add Configuration</h6>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
				</button>

			</div>
			<div class="modal-body">
				<form id="configurationForm" method="post">
					<input type="hidden" id="Conftemplate_id" name="Conftemplate_id">
					<div class="">
						<div class="col-md-12 d-flex">
							<div class="col-md-4">
								<div class="form-group">
									<label>Select Table</label>
									<select class="form-control" id="table_name" name="table_name" onchange="getDBColumnNames(this.value,1)"></select>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Where Condition</label>
									<input type="text" id="where_condition" class="form-control" name="where_condition" placeholder="EXAMPLE : and id = 1 ">
								</div>
							</div>
						</div>
					</div>
					<div id="ConfigDiv"></div>
				</form>
			</div>
			<div class="modal-footer" id="template_footer">
				<button class="btn btn-default roundCornerBtn4" data-dismiss="modal" aria-hidden="true">Cancel</button>
				<button id="create_template1" class="btn btn-primary pull-right roundCornerBtn4" type="button"
						onclick="SaveConfiguration()"><i class="fa fa-save"></i> Save
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="TransactionModal" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog modal-content">
		<div class="modal-content">
			<div class="modal-header">
				<h6 id="myModalLabel">Transactions</h6>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
				</button>

			</div>
			<div class="modal-body">
				<form id="TransactionForm" method="post">
					<input type="hidden" id="transTemplate" name="transTemplate">
					<div id="TransactionHandson"></div>
				</form>
			</div>
			<div class="modal-footer" id="template_footer">
				<button class="btn btn-default roundCornerBtn4" data-dismiss="modal" aria-hidden="true">Cancel</button>
				<button id="create_template1" class="btn btn-primary pull-right roundCornerBtn4" type="button"
						onclick="SaveTransaction()"><i class="fa fa-save"></i> Save
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="handonTableModal" role="dialog" aria-labelledby="spreadsheetTemplate" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form id="spreadsheetForm" name="spreadsheetForm">
				<div class="modal-header" style="display: flex;">
					<h5 class="modal-title" id="spreadsheetTemplate">SpreadSheet Table</h5>
					<button type="button" class="btn btn-primary btn-sm filterBtn" id="spreadSheetBtn" style="margin-left: auto;margin-right: 10px;" onclick="savePrefillData()">Save</button>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<div class="modal-body">
					<input type="hidden" id="spreadsheetTemplateId">
					<input type="hidden" id="spreadsheetTemplateName">



					<div id="PrefillHandson"></div>

				</div>
			</form>
		</div>
	</div>
</div>

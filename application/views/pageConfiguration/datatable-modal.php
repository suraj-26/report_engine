
<style type="text/css">
	.form_row
	{
		background-color: #d3d3d33d;
	}
	small
	{
		font-weight: bold;
	}

</style>
<div class="modal fade"
	 role="dialog" id="DatatableConfigurationModal"
	 style="display: none; padding-right: 17px;">
	<div class="modal-dialog modal-content" role="document" style="width:90%!important;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Datatable Configurations</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div class="">
	<section class="section">

		<div class="">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="">
						
						<div class="card-body p-0">
							<form id="datatableForm">
								<div class="modal-body p-0">
									<div class="card my-0 shadow-none">
										<div class="card-body p-0">
											<input type="hidden" name="id" id="datatable_id" value="16">
											<input type="hidden" name="row" id="datatable_row" value="">
											<input type="hidden" name="col" id="datatable_col" value="">

											<!--Table Query -->
											<div class="row">
												<div class="col-md-12">
													<div class="form-row">
														<div class="form-group col-md-4">
															<label for="queryTable">Table Name*</label>
															<select id="queryTable" class="form-control "
																	style="width: 100%" onchange="getAllColumns(this.value)" 
																	name="queryTable">
															</select>
														</div>
														<div class="form-group col-md-4">
															<label for="queryTableSelectColumn">Columns List</label>
															<select id="queryTableSelectColumn" class="form-control "
																	style="width: 100%"
																	name="queryTableSelectColumn">
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-12">
													<div class="form-row">
														<div class="form-group col-md-6">
															<label for="rawQueryTableSelectColumn">Select
																Columns*</label>
															<textarea id="rawQueryTableSelectColumn"
																	  name="rawQueryTableSelectColumn"
																	  class="form-control"
																	  placeholder="Select Columns"></textarea>
															<div>
																<small class="text-dark text-monospace">Example Array : <span id="sel_c">["column1 as COLUMN1","column2 as COLUMN2","column3","column4"]</span></small>
																<span class="text-info m-2" title="copy" onclick="copyText('sel_c')"><i class="fa fa-copy"></i></span>
															</div>
														</div>
														<div class="form-group col-md-6">
															<label>Search Columns*</label>
															<textarea id="rawQueryTableSearchColumn"
																	  name="rawQueryTableSearchColumn"
																	  class="form-control"
																	  placeholder="Search Columns"></textarea>
															<div>
																<small class="text-dark text-monospace">Example Array : <span id="sel_s">["column1","column2","column3","column4"]</span></small>
																<span class="text-info m-2" title="copy" onclick="copyText('sel_s')"><i class="fa fa-copy"></i></span>
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-12">
													<div class="form-row">
														<div class="form-group col-md-6">
															<label>Order Columns*</label>
															<textarea id="rawQueryTableOrderColumn"
																  name="rawQueryTableOrderColumn"
																	  class="form-control"
																	  placeholder="Order Columns"></textarea>
															<div>
																<small class="text-dark text-monospace">Example Array : <span id="ord_c">[["column1","direction1"],["name","asc"],["id","desc"]]</span></small>
																<span class="text-info m-2" title="copy" onclick="copyText('ord_c')"><i class="fa fa-copy"></i></span>
															</div>
														</div>
													
														<div class="form-group col-md-6">
															<label>File Columns</label>
															<textarea id="rawQueryTableFileColumn"
															  name="rawQueryTableFileColumn"
																  class="form-control"
																  placeholder="File Columns"></textarea>
															<div>
																<small class="text-dark text-monospace">Example Array : <span id="file_c">["column1","column2","column3","column4"]
																		[{"columnTitle":"Doc","columnName":[{"title":"<i class='fa fa-download'></i>","column":"invoice_copy"}}]//columns in select}]</span></small>
																<span class="text-info m-2" title="copy" onclick="copyText('file_c')"><i class="fa fa-copy"></i></span>
															</div>
														</div>
													</div>
												</div>
													
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-row">
														<div class="form-group col-md-6">
															<label>Group By</label>
															<textarea id="rawQueryTableGroupColumn"
																	  name="rawQueryTableGroupColumn"
																	  class="form-control"
																	  placeholder="Group Columns"></textarea>
															<div>
																<small class="text-dark text-monospace">Example Array : <span id="grp_c">["column1","column2"]</span></small>
																<span class="text-info m-2" title="copy" onclick="copyText('grp_c')"><i class="fa fa-copy"></i></span>
															</div>
														</div>
													</div>
												</div>
											</div>

											<!--Wheres -->
											<div class="section-title">Where Condition</div>
											<div class="form-row">
												<textarea id="queryTableWhereCondition"
															  name="queryTableWhereCondition"
																  class="form-control"
																  placeholder="Where Condition"></textarea>
												<div>
													<small class="text-dark text-monospace">Example Array : <span id="whe_c">
														[
															{
																"columnName":"item_name",
																"columnValue":{"type":1,"typeValue":"session_name","is_query":1//query where we replace value with ? ,0//direct value pass 2//find in set value replace with ?}
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
																"columnValue":{"type":5,"typeValue":"" // always blank}
															}
														]
													</span></small>
													<span class="text-info m-2" title="copy" onclick="copyText('whe_c')"><i class="fa fa-copy"></i></span>
												</div>
											</div>
											
											
											<hr/>
											<!--Filters -->
											<div class="section-title">Filters</div>
											<div class="form-row">
												<textarea id="queryTableFilterCondition"
															  name="queryTableFilterCondition"
																  class="form-control"
																  placeholder="Filters Condition"></textarea>
												<div>
													<small class="text-dark text-monospace">Example Array : <span id="fil_c">
														[{
															"label":"title",
															"column":"columnName",
															"type":1 //for date
														},
														{
															"label":"title",
															"column":"columnName",
															"type":2 //for datetime
														},
														{
															"label":"title",
															"column":"columnName",
															"type":3, //for dropdown
															"query":"select id,user_name as text from user_header_all where status=1",
															"staticOption":[{"id":1,"text":"data"}]
														}]
													</span></small>
													<span class="text-info m-2" title="copy" onclick="copyText('fil_c')"><i class="fa fa-copy"></i></span>
												</div>
											</div>
											<hr/>
											

											<!--action button -->

											<div class="section-title">Actions Button</div>
											<label for="">Enter Action Name</label>
											<input type="text" name="actionButtonName" id="actionButtonName" value="Action" class="col-md-4 form-control" placeholder="Enter Action Name Here">
											<div class="form-row mt-2">
												<textarea id="queryTableActionCondition"
															  name="queryTableActionCondition"
																  class="form-control"
																  placeholder="Action Condition"></textarea>
												<div>
													<small class="text-dark text-monospace">Example Array : <span id="act_c">
													[
														{
															"label":"title",	
															"column":"patient_id",
															"action":1 //update_operation ,
															"confirmation":"true",
															"staticValue":1,
															"query":{"query":"select id as value from user_header_all where data=1","type":1 // for session,"value":"session_name"} or {"query":"select id as value from user_header_all where data=?","type":2 // for static value,"value":"static value"} or {"query":""select id as value from user_header_all where data=1"","type":3 // for "query" parameter,"value":"#firm_id"}, or {"query":"select id as value from user_header_all where data=1","type":4 // column for given value,"value":""},
															"title":"button title"
														},
														{
															"label":"title",	
															"column":"columnName",
															"action":2 //Execute Query ,
															"confirmation":"false",
															"query":{"query":"select id as value from user_header_all where data=1","type":1 // for session,"value":"session_name"} or {"query":""select id as value from user_header_all where data=1"","type":2 // for static value,"value":"static value"} or {"query":""select id as value from user_header_all where data=1"","type":3 // for "query" parameter,"value":"#firm_id"},
															"title":"button title"
															},
														{
															"label":"title",	
															"column":"columnName",
															"action":3 //Redirection to new page,
															"redirection":{"path":"route here","type":1 // for session,"value":"session_name"} or {"path":"route "here"","type":2 // for static value,"value":"static value"} or {"path":"route here","type":3 // for "query" parameter,"value":"#firm_id"} or {"path":"route here","type":4 // for "url" parameter,"value":"index_2" // index number from where u want value}
															"target":0// same page 1//new page,
															"title":"button title"
															},

														{
															"label":"title",	
															"column":"columnName",
															"action":4 //Modal ,
															"modalID":"#modalId",
															"templateName":"Student Registration Form",
															"title":"button title"
														},
														{
															"label":"title",	
															"column":"columnName",
															"action":5 //Redirection to new template ,
															"templateID":"34",
															"redirection":[{"type":1//replace form on modal  2//same form replace 3// normal modal 4// normal modal with multiple parameter ,"queryPara":"item_id",
															"queryParaArray":[{"column":"columnName1","queryPara":"#item_id"},{"column":"columnName2","queryPara":"#product_id"}]}],
															"title":"button title"
														},
														{
															"label":"title",
															"column":"id",
															"action":6 //get edit data ,
															"templateID":"1",
															"title":"button title"
														},
														{
															"label":"title",
															"column":"id",
															"action":7 //insert operation ,
															"insertion":[{"table":"TE_product_master","column":[{"type":1//dataTable 2//session 3//hidden_field 4//queryPara 5//static,"tableCol":"name","selectCol":"name"},{"tableCol":"price","selectCol":"price"}]}],
															"title":"button title"
														},
															[
  {
    "label": "title",
    "column": "patient_id",
    "action": 8,
    "function": "functionname",
  }
]

													]
													</span></small>
													<span class="text-info m-2" title="copy" onclick="copyText('act_c')"><i class="fa fa-copy"></i></span>
												</div>
											</div>
											<hr/>
											
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button class="btn btn-primary mr-1" type="button" onclick="saveDatatableForm('datatableForm')">
										Save
									</button>
									
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
			</div>
		</div>
	</div>
</div>

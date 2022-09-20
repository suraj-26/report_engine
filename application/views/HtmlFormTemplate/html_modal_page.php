<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg d-none" id="htmlFormModalOpBtn" data-toggle="modal"
		data-target="#htmlFormModalOp">Open Modal
</button>
<!-- Modal -->
<div id="htmlFormModalOp" class="modal fade" role="dialog">

	<div class="modal-dialog modal-content" style="width:90%!important;">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Options & Validation</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->

			</div>
			<div class="modal-body">
				<div class="section_title"></div>
				<h6>Themes</h6>
				<div class="form-row col-md-12">
					<input type="hidden" name="theme_view" id="theme_view" value="1">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="themeRadios1" name="themeRadios" checked="" value="1"
							   class="custom-control-input"
							   onclick="themeSelection(1,'theme_view','#787A91','#EEEEEE','#EEEEEE','#141E61')">
						<label class="custom-control-label" for="themeRadios1"> Standard Theme</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="themeRadios2" name="themeRadios" value="2" class="custom-control-input"
							   onclick="themeSelection(2,'theme_view','#A2DBFA','#053742','#E8F0F2','#053742')">
						<label class="custom-control-label" for="themeRadios2"> Premiun Theme</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="themeRadios3" name="themeRadios" value="3" class="custom-control-input"
							   onclick="themeSelection(3,'theme_view','#47597E','#DBE6FD','#DBE6FD','#293B5F')">
						<label class="custom-control-label" for="themeRadios3"> Pro Theme</label>
					</div>
				</div>
				<div class="section_title"></div>
				<h6>Card Color</h6>
				<div id="ColorPickerDiv" class="row">
					<div class="col-md-3">
						<label>Choose Card Header Color</label>
						<input type="color" id="colorPicker" value="#787A91">
					</div>
					<div class="col-md-3">
						<label>Choose Card Header Text Color</label>
						<input type="color" id="colorHeaderTextPicker" value="#EEEEEE">
					</div>
					<div class="col-md-3">
						<label>Choose Card Body Color</label>
						<input type="color" id="colorCardBodyPicker" value="#EEEEEE">
					</div>
					<div class="col-md-3">
						<label>Choose Card Body Text Color</label>
						<input type="color" id="colorCardBodyTextPicker" value="#141E61">
					</div>
				</div>
				<div id="ColorPickerBodyDiv">

				</div>
				<div id="ColorPickerBodyTextDiv">

				</div>
				<hr/>
				<div class="section_title"></div>
				<h6>Options</h6>
				<div id="addInputHere"></div>
				<hr/>
				<div class="section_title"></div>
				<h6>Validation</h6>
				<div id="addInputHereRequiredHere"></div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="addFormDesignOptions()">Save</button>
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
			</div>
		</div>

	</div>
</div>

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg d-none" id="querydropdownOpBtn" data-toggle="modal"
		data-target="#querydropdownOp">Open Modal
</button>
<!-- Modal -->
<div id="querydropdownOp" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->

			</div>
			<div class="modal-body">
				<div id="querydropdownOpHere"></div>

				<div id="querydropdownOpHereDefault"></div>


			</div>
			<div class="modal-footer">

				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
			</div>
		</div>

	</div>
</div>

<!-- Modal -->
<div id="htmlqueryModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Button Query</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->

			</div>
			<div class="modal-body">
				<form id="QueryDAtaForm" method="post">
					<section>
						<div class="row">
							<div class="col-md-3">
								<select class="form-control" name="formActionType" id="formActionType"
										style="width: 100%" onchange="getOtherActiondiv(this.value)">
									<option value="1">Redirect other page</option>
									<option value="2">Redirect To Template</option>
									<option value="3">Return transaction id To same section</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="row" id="div_first">
									<div class="col-md-3">
										<div class="form-group">
											<label for="route1">Redirect Route</label>
											<input type="text" name="route1"
												   id="route1"
												   class="form-control"/>
										</div>
									</div>
									<div class="col-md-3">
										<label for="qeryparam1">Extra Query Parameter</label>
										<select type="text" name="qeryparam1[]"
												id="qeryparam1" multiple
												class="form-control select2">
										</select>
									</div>
								</div>
								<div class="row" id="div_second" style="display:none">

									<div class="col-md-3">
										<label for="sectionselect2">Select Template</label>
										<select type="text" name="sectionselect2"
												id="sectionselect2"
												class="form-control">
										</select>
									</div>
									<div class="col-md-3">
										<label for="qeryparam2">Extra Query Parameter</label>
										<select type="text" name="qeryparam2[]" id="qeryparam2" multiple
												class="form-control select2">
										</select>
									</div>
								</div>
							</div>
						</div>
					</section>
					<div id="divAppendData"></div>

					<button type="button" style="display:none" class="btn btn-primary" id="querySaveBtn"
							onclick="addQueryData()">Save
					</button>
				</form>
				<div id="EditQueryDIv">

				</div>
				<div id="tableQuery">

				</div>

			</div>
			<div class="modal-footer">

				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
			</div>
		</div>

	</div>
</div>

<div id="ExcelTableButton" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Excel Table Configuaration</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->

			</div>
<<<<<<< HEAD
			<form id="ExcelHeaderForm" method="post">
				<div class="modal-body">


					<input type="hidden" id="excelCount" name="excelCount" value="0">
					<input type="hidden" id="excelSectionID" name="excelSectionID">
					<input type="hidden" id="ExcelDeptId" name="ExcelDeptId">
					<input type="hidden" id="ExcelBtnID" name="ExcelBtnID">

					<div class="row">
						<div class="col-md-4">
							<select class="form-control" id="excelMappingTable" name="table_name"
									onchange="getAllColumnsListExcel(this.value)"
									style="width: 100%"></select>
						</div>
						<div class="col-md-6">
							<button class="btn btn-primary" type="button" onclick="getViewExcelHeader()"> Add Header
							</button>
						</div>
					</div>
					<br>
					<div id="AppendExcelDataToDiv"></div>


				</div>
				<div class="modal-footer">
					<div id="save_btn_excel">
					</div>
				</div>
			</form>
=======
			<div class="modal-body">
				<form id="ExcelHeaderForm" method="post">
				<div class="row">
					<input type="hidden" id="excelCount" name="excelCount" value="0">
					<input type="hidden" id="excelSectionID" name="excelSectionID" >
					<input type="hidden" id="ExcelDeptId" name="ExcelDeptId" >
					<input type="hidden" id="ExcelBtnID" name="ExcelBtnID" >
					<div>
						<button class="btn btn-primary" type="button" onclick="getViewExcelHeader()"> Add Header
						</button>
					</div>
				</div><br>
				<div id="AppendExcelDataToDiv"></div>

				<div id="save_btn_excel">

				</div>
				</form>
				<div id="excelDataTableView"></div>
				<div id="excelDataMapping"></div><br>
				<div id="excelDataMappingOther"></div>
			</div>
			<div class="modal-footer">

				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
			</div>
>>>>>>> 9e8a9109c651f108fcf463f654b4ef09203083ba
		</div>

	</div>
</div>

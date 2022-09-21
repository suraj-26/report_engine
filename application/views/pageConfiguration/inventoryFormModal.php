<!-- return lease modal data -->
<div class="modal fade" id="inventoryModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Lease Return</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="inventoryhiddenArea"></div>
				<div id="inventorytemplateForm">
					<form method="post" class="p-4" data-form-valid="saveLeaseReturnData" enctype="multipart/form-data"
						  id="leaseReturnForm" novalidate="novalidate">
						<div class="row">

							<input type="hidden" class="form-control" name="i_order_id" id="i_order_id">

							<input type="hidden" class="form-control" name="i_serial_id" id="i_serial_id">

							<input type="hidden" class="form-control" name="i_taxable_value" id="i_taxable_value">

							<input type="hidden" class="form-control" name="i_in_location" id="i_in_location">
							<div class="col-6">
								<label for="">Item No.</label>
								<input type="text" class="form-control" name="i_item_no" id="i_item_no" readonly>
							</div>
							<div class="col-6">
								<label for="">Chalan No.</label>
								<input type="text" name="lc_chalan_no" class="lc_chalan_no form-control" readonly
									   data-valid="required" data-msg="Please Fill the Chalan No">
							</div>
							<div class="col-6">
								<label for="">Customer Name</label>
								<input type="text" class="form-control" name="i_customer_name" id="i_customer_name"
									   readonly>
								<input type="hidden" name="i_customer_id" id="i_customer_id" class="form-control">
							</div>
							<div class="col-6">
								<label for="">Customer Location</label>
								<input type="text" class="form-control" name="i_customer_location"
									   id="i_customer_location" readonly>
							</div>
							<div class="col-6">
								<label for="">Invoice No.</label>
								<input type="text" class="form-control" name="i_invoice_no" id="i_invoice_no" readonly>
							</div>
							<div class="col-6">
								<label for="">Location Type</label>
								<select name="i_location_type" id="i_location_type" class="form-control"
										data-valid="required" data-msg="Please Fill the Details"
										onchange="getLocationForReturnmodal(this.value)">
									<option value="1">Our Location</option>
									<option value="2">Customer Location</option>
								</select>
							</div>
							<div class="col-6">
								<label for="">Location</label>
								<select name="i_location" id="i_location" class="form-control i_location"
										data-valid="required" data-msg="Please Select Location"
										onchange="getCustomerName(this.value,1)"></select>
							</div>
							<div class="col-6" id="i_customer_div" style="display: none;">
								<label for="">Customer Name</label>
								<!--<input type="text" name="i_customer_name" id="i_customer_name" class="form-control">-->
								<!--<input type="hidden" name="i_customer_id" id="i_customer_id" class="form-control">-->
							</div>
							<div class="col-6">
								<label for="">Date of Return</label>
								<input type="date" class="form-control" name="i_return_date" id="i_return_date"
									   data-valid="required" data-msg="Please Fill the Details">
							</div>
							<div class="col-6">
								<label for="">Return Type</label>
								<select name="i_return_type" id="i_return_type" class="form-control"
										data-valid="required" data-msg="Please Fill the Details">
									<option value="1">Normal</option>
									<option value="2">Scrap</option>
									<option value="3">Lost</option>
								</select>
							</div>
							<div class="col-6">
								<label for="">Reason</label>
								<textarea name="i_reason" id="i_reason" cols="30" rows="10"
										  class="form-control"></textarea>
							</div>
							<div class="col-6">
							</div>
							<div class="col-6">
								<button type="submit" class="btn btn-primary float-right mt-3">Save</button>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- form for lease to add in card modal data -->
<div class="modal fade" id="inventoryAddToCartModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Item to Cart</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="inventoryTrnsferhiddenArea"></div>
				<div id="inventoryTensfertemplateForm">
					<form method="post" class="p-4" data-form-valid="saveInventoryCardItemData"
						  enctype="multipart/form-data" id="inventoryAddToCartForm" novalidate="novalidate">
						<div class="row">
							<input type="hidden" class="form-control" name="c_item_id" id="c_item_id">
							<input type="hidden" class="form-control" name="c_product_type" id="c_product_type">
							<input type="hidden" class="form-control" name="c_order_id" id="c_order_id">
							<input type="hidden" class="form-control" name="c_in_location" id="c_in_location">
							<input type="hidden" class="form-control" name="c_serial_no" id="c_serial_no">
							<div class="col-6">
								<label for="">Lease Start Date</label>
								<input type="date" class="form-control" name="c_lease_start_date"
									   id="c_lease_start_date" data-valid="required" data-msg="Please Fill the Details">
							</div>
							<div class="col-6">
								<label for="">Lease End Date</label>
								<input type="date" class="form-control" name="c_lease_end_date" id="c_lease_end_date"
									   data-valid="required" data-msg="Please Fill the Details">
							</div>

							<div class="col-6">
								<button type="submit" class="btn btn-primary mt-4">Add To Cart</button>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Lease cart modal data -->
<div class="modal fade" id="leaseCartModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content" id="cartmodal">
			<div class="modal-header">
				<h4 class="modal-title">Cart Items</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="inventoryTrnsferhiddenArea"></div>
				<div class="col-md-12 text-right">
					<button type="button" id="cart-empty" class="btn btn-sm btn-primary mx-1" data-toggle="tooltip"
							title="" data-confirm="Are You Sure?|Do you want to continue?"
							data-confirm-yes="cartEmpty()" data-original-title="Action">Cart Empty
					</button>
					<input type="hidden" name="cartproductType" id="cartproductType" value="">
				</div>
				<div class="row">

					<ul class="nav nav-pills" id="cartTab3" role="tablist">
						<li class="nav-item">
							<a class="nav-link" id="lease-tab3" data-toggle="tab" href="#leasePanel" role="tab"
							   aria-controls="home" aria-selected="true" onclick="getChalanNumber()">Lease</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="transfer-tab3" data-toggle="tab" href="#transferPanel" role="tab"
							   aria-controls="profile" aria-selected="false"
							   onclick="transfer_valid();getChalanNumber();">Transfer</a>
						</li>
					</ul>
					<span id="errormsg" style="display:none;font-size: 15px;padding-left: 16px;">Cart Is Empty</span>
					<div class="tab-content" id="cartTabContent2">
						<div class="tab-pane fade" id="leasePanel" role="tabpanel" aria-labelledby="lease-tab3">
							<form method="post" class="p-4" data-form-valid="saveItemLeaseTransactionData"
								  enctype="multipart/form-data" id="leaseCartForm" novalidate="novalidate">
								<div class="row">
									<div class="col-6">
										<label for="">Type</label>
										<select name="lc_sale_type" id="lc_sale_type" class="form-control"
												data-valid="required" data-msg="Please Fill the Details"
												onchange="saleTypeValidation(this.value)">
											<option value="1" selected>Lease</option>
											<option value="2">New Sell</option>
											<option value="3">Old Sell</option>
											<!--<option value="4">Consumption</option>-->
										</select>
									</div>
									<div class="col-6">
										<label for="">Chalan No.</label>
										<input type="text" name="lc_chalan_no" class="lc_chalan_no form-control"
											   readonly data-valid="required" data-msg="Please Fill the Chalan No">
									</div>
									<div class="col-6 hide_div">
										<label for="">Customers</label>
										<select name="lc_customer" id="lc_customer" class="form-control customer_list"
												data-valid="required" data-msg="Please Fill the Details"
												onchange="getCustomerLocation(this.value)"></select>
									</div>
									<div class="col-6">
										<label for="">Location</label>
										<select name="lc_customer_location" id="lc_customer_location"
												class="form-control" data-valid="required"
												data-msg="Please Fill the Details"></select>
									</div>
									<div class="col-6 hide_div" id="lc_lease_start_date_div">
										<label for="">Lease Start Date</label>
										<input type="date" class="form-control" name="lc_lease_start_date"
											   id="lc_lease_start_date" data-valid="required"
											   data-msg="Please Fill the Details">
									</div>
									<div class="col-6 hide_div" id="lc_lease_end_date_div">
										<label for="">Lease End Date</label>
										<input type="date" class="form-control" name="lc_lease_end_date"
											   id="lc_lease_end_date" data-valid="required"
											   data-msg="Please Fill the Details">
									</div>
									<div class="col-6 hide_div" id="lc_po_no_div">
										<label for="">PO No.</label>
										<input type="text" class="form-control" name="lc_po_no" id="lc_po_no"
											   data-valid="required" data-msg="Please Fill the Details">
									</div>

									<div class="col-6 lc_detail" style="display: none;">
										<label for="">Detail</label>
										<textarea class="form-control reson" name="lc_detail"></textarea>
									</div>

									<div class="col-12 pt-4" id="leaseCartTable">


									</div>
									<div class="col-6">
									</div>
									<div class="col-6 text-right">
										<button type="submit" class="btn btn-primary mt-4">Proceed</button>
									</div>
								</div>
							</form>
						</div>

						<div class="tab-pane fade" id="transferPanel" role="tabpanel" aria-labelledby="transfer-tab3">
							<form method="post" class="p-4" data-form-valid="saveInventoryTransferData"
								  enctype="multipart/form-data" id="inventoryTransferForm" novalidate="novalidate">
								<div class="row">

									<div class="col-6">
										<label for="">Chalan No.</label>
										<input type="text" name="lc_chalan_no" class="lc_chalan_no form-control"
											   readonly data-valid="required" data-msg="Please Fill the Chalan No">
									</div>
									<div class="col-6">
										<label for="">Location Type</label>
										<select name="it_location_type" id="it_location_type" class="form-control"
												data-valid="required" data-msg="Please Fill the Details"
												onchange="getAllWareHouseLocation(this.value)">
											<option value="1">Our Location</option>
											<option value="2">Customer Location</option>
										</select>
									</div>

									<div class="col-6" id="it_customer_div" style="display:none;">
										<label for="">Customer Name</label>
										<select name="it_customer_name" id="it_customer_name"
												class="form-control customer_list" data-valid="required"
												data-msg="Please Fill the Details"
												onchange="getCustomerName(this.value,3)"></select>
									</div>
									<div class="col-6" id="it_customer_location">
										<label for="">Location</label><span class="err_show"></span>
										<select name="it_location" id="it_location" class="form-control i_location"
												data-valid="required" data-msg="Please Fill the Details"></select>
									</div>
									<div class="col-12" id="leaseCart">

									</div>
									<div class="col-6">
									</div>


									<div class="col-6 text-right">
										<button type="submit" class="btn btn-primary mt-4">Proceed</button>
									</div>
								</div>

							</form>
						</div>

					</div>

				</div>

			</div>
		</div>
	</div>
</div>
</div>


<!-------AcceptanceForm Modal---->
<div class="modal fade" id="consumptionModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Consumption</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">

				<form method="post" class="p-4" data-form-valid="saveConsumption" enctype="multipart/form-data"
					  id="consumptionForm" novalidate="novalidate">
					<div class="row">
						<input type="hidden" class="form-control" name="ir_order_id" id="cir_order_id">
						<input type="hidden" class="form-control" name="ir_item_id" id="cir_item_id">
						<input type="hidden" class="form-control" name="ir_serial_no" id="cir_serial_no">
						<input type="hidden" class="form-control" name="ir_taxable_value" id="cir_taxable_value">
						<input type="hidden" class="form-control" name="ir_in_location" id="cir_in_location">
						<input type="hidden" class="form-control" name="product_type" id="cir_product_type">

						<div class="col-6">
							<label for="">Chalan No.</label>
							<input type="text" name="lc_chalan_no" class="lc_chalan_no form-control" readonly
								   data-valid="required" data-msg="Please Fill the Chalan No">
						</div>
						<div class="col-6">
							<label for="">Brand Name</label>
							<input type="text" class="form-control" name="brand_name" id="c_brand_name" readonly>
						</div>

						<div class="col-6">
							<label for="">Product Name</label>
							<input type="text" class="form-control" name="product_name" id="c_product_name" readonly>
						</div>


						<div class="col-6">
							<label for="">Item No.</label>
							<input type="text" class="form-control" name="ir_item_no" id="cir_item_no" readonly>
						</div>
						<div class="col-6">
							<label for="">Location</label>
							<input type="text" class="form-control" name="ir_customer_location"
								   id="cir_customer_location" readonly>
						</div>

						<div class="col-6">
							<label for="">Quantity</label>
							<input type="number" class="form-control" name="ir_qty" id="cir_qty" data-valid="required"
								   data-msg="Please Fill the Details"
								   onkeyup="checkQuantity(this.value,'cir_before_qty','Consumed','cir_qty')"
								   onkeydown="checkQuantity(this.value,'cir_before_qty','Consumed','cir_qty')"
								   onchange="checkQuantity(this.value,'cir_before_qty','Consumed','cir_qty')">
							<input type="hidden" class="form-control" name="ir_before_qty" id="cir_before_qty">
						</div>

						<!--						<div class="col-6">-->
						<!--							<label for="">Location</label>-->
						<!--							<select name="ir_location" id="ir_location" class="form-control i_location" data-valid="required" data-msg="Please Fill the Details" onchange="getCustomerName(this.value,1)"></select>-->
						<!--						</div>-->
						<div class="col-6">
							<label for="">Reason</label>
							<textarea name="ir_reason" id="cir_reason" cols="30" rows="10"
									  class="form-control"></textarea>
						</div>
						<div class="col-6">
							<button type="submit" class="btn btn-primary float-right mt-3">Save</button>
						</div>

					</div>
				</form>

			</div>
		</div>
	</div>
</div>
</div>

<!-------Product AcceptanceForm Modal---->
<div class="modal fade" id="acceptanceFormdata">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">AcceptData</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="acceptformdata" class="col-12">
					<div id="acceptformdatatable">
					</div>
				</div>
				<div class="col-12 text-right" id="acceptsave">
					<button class="btn btn-primary mt-4" type="button" onclick="saveAcceptanceFormData()">Save</button>
				</div>


			</div>
		</div>
	</div>
</div>
</div>


<!-------Item AcceptanceForm Modal ---->
<div class="modal fade" id="acceptanceItemFormdata">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">AcceptData</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="acceptitemformdata" class="col-12">
					<div id="acceptitemformdatatable">
					</div>
				</div>
				<div class="col-12 text-right" id="acceptsavebtn">
					<button class="btn btn-primary mt-4 saveItemAccept" type="button"
							onclick="saveItemAcceptanceFormData()">Save
					</button>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- return item lease modal data -->
<div class="modal fade" id="inventoryModalReturn">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Lease Return</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="inventoryhiddenAreareturn"></div>
				<div id="inventorytemplateFormreturn">
					<form method="post" class="p-4" data-form-valid="saveLeaseReturnDatareturn"
						  enctype="multipart/form-data" id="leaseReturnForm1" novalidate="novalidate">
						<div class="row">

							<input type="hidden" class="form-control" name="ir_order_id" id="ir_order_id">

							<input type="hidden" class="form-control" name="ir_serial_id" id="ir_serial_id">

							<input type="hidden" class="form-control" name="ir_taxable_value" id="ir_taxable_value">

							<input type="hidden" class="form-control" name="ir_in_location" id="ir_in_location">
							<div class="col-6">
								<label for="">Chalan No.</label>
								<input type="text" name="lc_chalan_no" class="lc_chalan_no form-control" readonly
									   data-valid="required" data-msg="Please Fill the Chalan No">
							</div>
							<div class="col-6">
								<label for="">Item No.</label>
								<input type="text" class="form-control" name="ir_item_no" id="ir_item_no" readonly>
							</div>
							<div class="col-6">
								<label for="">Customer Name</label>
								<input type="text" class="form-control" name="ir_customer_name" id="ir_customer_name"
									   readonly>
								<input type="hidden" name="ir_customer_id" id="ir_customer_id" class="form-control">
							</div>
							<div class="col-6">
								<label for="">Customer Location</label>
								<input type="text" class="form-control" name="ir_customer_location"
									   id="ir_customer_location" readonly>
							</div>
							<div class="col-6">
								<label for="">Invoice No.</label>
								<input type="text" class="form-control" name="ir_invoice_no" id="ir_invoice_no"
									   readonly>
							</div>
							<div class="col-6">
								<label for="">Quantity</label>
								<input type="number" class="form-control" name="ir_qty" id="ir_qty"
									   data-valid="required" data-msg="Please Fill the Details"
									   onkeyup="checkQuantity(this.value,'ir_before_qty','Return','ir_qty')"
									   onkeydown="checkQuantity(this.value,'ir_before_qty','Return','ir_qty')"
									   onchange="checkQuantity(this.value,'ir_before_qty','Return','ir_qty')">
								<input type="hidden" class="form-control" name="ir_before_qty" id="ir_before_qty">
							</div>
							<div class="col-6">
								<label for="">Location Type</label>
								<select name="ir_location_type" id="ir_location_type" class="form-control"
										data-valid="required" data-msg="Please Fill the Details"
										onchange="getLocationForReturnmodal(this.value,2)">
									<option value="1">Our Location</option>
									<option value="2">Customer Location</option>
								</select>
							</div>
							<div class="col-6">
								<label for="">Location</label>
								<select name="ir_location" id="ir_location" class="form-control i_location"
										data-valid="required" data-msg="Please Fill the Details"
										onchange="getCustomerName(this.value,1)"></select>
							</div>
							<div class="col-6" id="i_customer_div" style="display: none;">
								<label for="">Customer Name</label>
								<!--<input type="text" name="i_customer_name" id="i_customer_name" class="form-control">-->
								<!--<input type="hidden" name="i_customer_id" id="i_customer_id" class="form-control">-->
							</div>
							<div class="col-6">
								<label for="">Date of Return</label>
								<input type="date" class="form-control" name="ir_return_date" id="ir_return_date"
									   data-valid="required" data-msg="Please Fill the Details">
							</div>
							<div class="col-6">
								<label for="">Return Type</label>
								<select name="ir_return_type" id="ir_return_type" class="form-control"
										data-valid="required" data-msg="Please Fill the Details">
									<option value="1">Normal</option>
									<option value="2">Scrap</option>
									<option value="3">Lost</option>
								</select>
							</div>
							<div class="col-6">
								<label for="">Reason</label>
								<textarea name="ir_reason" id="ir_reason" cols="30" rows="10"
										  class="form-control"></textarea>
							</div>
							<div class="col-6">
								<button type="submit" class="btn btn-primary float-right mt-3">Save</button>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!----------Generate Bill Report -->
<div class="modal fade" id="billGenerateReportModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Gererate Customers Billing Report</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="billGenerateReport"></div>
				<div id="billGenerateReportForm">
					<form method="post" class="p-4" data-form-valid="generateBillReport" enctype="multipart/form-data"
						  id="generateBillReports" novalidate="novalidate">
						<div class="row">
							<div class="col-6">
								<label for="">Select Year</label>
								<select name="bill_year" id="bill_year" class="form-control" data-valid="required"
										data-msg="Please Fill the Details">
								</select>
							</div>
							<div class="col-6">
								<label for="">Select Type</label>
								<select name="bill_type" id="bill_type" class="form-control" data-valid="required"
										data-msg="Please Fill the Details">
									<option value="1">Paid</option>
									<option value="0">UnPaid</option>
									<option value="2">All</option>
								</select>
							</div>
							<div class="col-6">
								<label for="">Select Report</label>
								<select name="report_type" id="report_type" class="form-control" data-valid="required"
										data-msg="Please Fill the Details">
									<option value="1">Bill Report</option>
									<option value="2">GST Report</option>

								</select>
							</div>
							<br>
							<div class="col-6">
							</div>
							<div class="col-6">
								<button type="submit" class="btn btn-primary float-right mt-3">Generate Bill Report
								</button>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!----------Generate Bill Report For Vendor -->
<div class="modal fade" id="billVendorReportModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Gererate Vendor Billing Report</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="billVendorGenerateReport"></div>
				<div id="billVendorGenerateReportForm">
					<form method="post" class="p-4" data-form-valid="generateVendorBillReport"
						  enctype="multipart/form-data" id="generateVendorBillReports" novalidate="novalidate">
						<div class="row">
							<div class="col-6">
								<label for="">Select Year</label>
								<select name="bill_year" id="bill_years" class="form-control" data-valid="required"
										data-msg="Please Select Year">
								</select>
							</div>
							<div class="col-6">
								<label for="">Select Type</label>
								<select name="bill_type" id="bill_types" class="form-control" data-valid="required"
										data-msg="Please Select Type">
									<option value="1">Paid</option>
									<option value="0">UnPaid</option>
									<option value="2">All</option>
								</select>
							</div>
							<div class="col-6">
								<label for="">Select Report</label>
								<select name="report_type" id="report_types" class="form-control" data-valid="required"
										data-msg="Please Fill the Details">
									<option value="1">Bill Report</option>
									<option value="2">GST Report</option>

								</select>
							</div>
							<br>
							<div class="col-6">

							</div>
							<div class="col-6">
								<button type="submit" class="btn btn-primary float-right mt-3">Generate Bill Report
								</button>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<!----------Lease Edit Modal-->
<div class="modal fade" id="leaseEditModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Update Lease Details</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div>
					<form method="post" class="p-4" enctype="multipart/form-data" id="customerleaseupdateform"
						  novalidate="novalidate">
						<input type="hidden" name="item_id" id="item_id">
						<div class="row">

							<div class="col-6">
								<div class="form-group">
									<label>Order ID</label>
									<input type="text" name="order_id" class="form-control" id="order_id" readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Customer Name</label>
									<input type="text" class="form-control" name="customer_name" id="customer_name"
										   readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Brand Name</label>
									<input type="text" class="form-control" name="brand_name" id="brand_name" readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Item Name</label>
									<input type="text" name="item_name" class="form-control" id="item_name" readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Lease Start Date</label>
									<input type="date" name="lease_start" class="form-control" id="lease_start">
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Lease End Date</label>
									<input type="date" name="lease_end" id="lease_end" class="form-control">
								</div>
							</div>


							<div class="col-6">
								<div class="form-group">
									<label>Taxable Amount</label>
									<input type="number" name="taxable_amount" id="taxable_amount" class="form-control">
								</div>
							</div>

							<div class="col-6">
								<button type="button" onclick="UpdateLeaseDate('customerleaseupdateform')"
										class="btn btn-primary float-right mt-3">Save
								</button>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!--------------Return Material-------------------------->
<div class="modal fade" id="returnmaterialModal">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Material Return</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div>
					<form method="post" class="p-4" enctype="multipart/form-data" id="materialreturnform"
						  novalidate="novalidate">
						<input type="hidden" name="com_material_id" id="com_material_id">
						<div class="row">

							<div class="col-6">
								<div class="form-group">
									<label>ARN No</label>
									<input type="text" name="ARN_no" class="form-control" id="ARN_no" readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Material Code</label>
									<input type="text" class="form-control" name="material_code" id="material_code"
										   readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Material Name</label>
									<input type="text" class="form-control" name="material_name" id="material_name"
										   readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Vendor Name</label>
									<input type="text" name="vendor_name" class="form-control" id="vendor_name"
										   readonly>
								</div>
							</div>

							<div class="col-6">
								<div class="form-group">
									<label>Quantity</label>
									<input type="text" name="supply_quantity" class="form-control" id="supply_quantity"
										   readonly>
								</div>
							</div>


							<div class="col-12">
								<div class="col-6">
									<button type="button" onclick="ReturnMaterial('materialreturnform')"
											class="btn btn-primary">Return
									</button>
								</div>
								<!-- <div class="col-6">
									  <button type="button"class="btn btn-primary">Cancle</button>
								</div> -->
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- new modal add inventoryMaterialView 3/8/2022-->
<div class="modal fade" tabindex="-1" role="dialog" id="inventoryMaterialView"
	 aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Material History </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body" id="ReceivedMaterialHistory_section">
				<div class="row col-12">
					<div class="col-4">
						<div class="form-group">
							<label class="text-muted">ARN No.: <span class="text-dark" id="ARN_no_t"></span></label>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label class="text-muted">Material Code: <span class="text-dark"
																		   id="material_code_t"></span></label>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label class="text-muted">Material Name: <span class="text-dark"
																		   id="material_name_t"></span></label>
						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-header">
					<table class="table table-striped" style="display: table;width: 100%!important;"
						   id="transactionTable">
						<thead>
						<tr>
							<td>Order No</td>
							<td>Stage</td>
							<td>User</td>
							<td>Qty</td>
							<td>Unit</td>
							<td>Date</td>
						</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<!-- New modal add -->


<!-- New BMR ADD MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" id="newBMRModal"
	 aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">BMR Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="card-body">
						<form id="BMRForm" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-12">

									<input type="hidden" name="bmr_update_id" id="bmr_update_id">
									<input type="hidden" name="group_type" id="group_type">
									<div class="form-group" id="bmr-list">
										<label for="">Group List</label>
										<select name="bmr_list" id="bmr_list" class="form-control select2"
												onchange="getBMRNameList(this.value)"></select>
									</div>
									<div class="form-group">
										<label>Group Name</label>
										<input type="text" name="bmr_name" class="form-control" id="bmr_name">
									</div>
								</div>
								<div class="col-md-12">
									<div class="row" id="queryparamdiv">

										<input type="hidden" name="paramCount" id="paramCount" value="0">
										<div class="col-md-5">
											<div class="form-group" id="queryparameter_id_1">
												<label for="">Select QueryParameter</label>
												<select name="queryparameter_list[]" id="queryparameter_list_0"
														class="form-control">
												</select>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group" id="query_parameter_type">
												<label for="">Select Type</label>
												<select name="parameter_type[]" id="parameter_type_0"
														class="form-control select2">
													<option value="1">Static</option>
													<option value="2">Session</option>
													<option value="3">Query</option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group" id="query_parameter_value">
												<label for="">Enter Parameter Value</label>
												<textarea class="form-control" name="param_value[]"
														  id="param_value_0"></textarea>
											</div>
										</div>
<!--										<div class="col-md-1">-->
<!--											<div>-->
<!--												<button class="btn btn-primary" type="button"-->
<!--														style="margin-top:40px;" onclick="removeQueryParam(0);">-->
<!--													<i class="fa fa-trash"></i>-->
<!--												</button>-->
<!--											</div>-->
<!---->
<!--										</div>-->
									</div>
									<div id="queryparameterows">
									</div>
									<div class="">
										<button class="btn btn-primary" type="button" id="addMorequeryparam"
												onclick="addMoreparam();">(Add More)
										</button>

									</div>

								</div>
								<button type="button" class="btn btn-primary float-right" style="margin-top:20px;"
										onclick="addNewBMR()">SAVE
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- New modal add -->


<!-- Add Material Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addmaterialmodal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Material Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="card-body">
						<form id="materialform" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-12">
									<input type="hidden" name="bmr_id" id="bmr_id" value="<?= $id ?>"/>
									<input type="hidden" name="material_update_id" id="material_update_id" value=""/>
									<div class="card">
										<div class="card-header">
											<h4 class="text-dark">Materials</h4>
											<div style="margin-left: auto">
												<div class="form-group d-flex flex-nowrap">
													<label>Qty</label>
													<input type="number" style="margin-left: 10px" name="production_qty"
														   id="production_qty" class="form-control" min="1">
													<select name="production_unit" id="production_unit"
															class="form-control-plaintext unit_select form-control-sm"
															style="margin-left: 10px">
														<option value="mg">Miligram</option>
														<option value="gm">Gram</option>
														<option value="kg">Kilo Gram</option>
														<option value="ml">Mililitre</option>
														<option value="ltr">Litre</option>
													</select>
												</div>

												<div class="form-group d-flex flex-nowrap">
													<label class="form-control-plaintext">Alternate Qty</label>
													<input type="number" style="margin-left: 10px" name="alternate_qty"
														   id="alternate_qty" class="form-control" min="1">
													<select name="alternate_unit" id="alternate_unit"
															class="form-control-plaintext unit_select form-control-sm"
															style="margin-left: 10px">
														<option value="mg">Miligram</option>
														<option value="gm">Gram</option>
														<option value="kg">Kilo Gram</option>
														<option value="ml">Mililitre</option>
														<option value="ltr">Litre</option>
													</select>
												</div>
											</div>
										</div>
										<div class="card-body">
											<div class="materials" id="material_data">
												<input type="hidden" name="material_count" value="0"
													   id="material_count">
												<div class="col-12 d-flex">
													<div class="col-2">
														<div class="form-group">
															<label>Name</label>
															<select class="form-control material_select"
																	onchange="getMaterialData(0)"
																	name="name" id="name0">
															</select>
														</div>
													</div>

													<div class="col-2">
														<div class="form-group">
															<label>Material Code No.</label>
															<input type="text" class="form-control"
																   name="material_code[]"
																   id="material_code0">
														</div>
													</div>
													<div class="col-2">
														<div class="form-group">
															<label>Grade / Specification Requirement</label>
															<input type="text" class="form-control" name="grade[]"
																   id="grade0">
														</div>
													</div>
													<div class="col-1">
														<div class="form-group">
															<label>Unit (mg/ml)</label>
															<select name="label_claim[]" id="label_claim0"
																	class="form-control unit_select">

															</select>
														</div>
													</div>
													<div class="col-2">
														<div class="form-group">
															<label>Std Quantity</label>
															<input type="number" class="form-control"
																   name="std_quantity[]"
																   id="std_quantity0" min="1">
														</div>
													</div>
													<div class="col-1">
														<div class="form-group">
															<label>Overages (%)</label>
															<input type="number" class="form-control" name="overages[]"
																   id="overages0" min="1">
														</div>
													</div>
													<div class="col-2">
														<div class="form-group">
															<label>Quantity</label>
															<input type="number" class="form-control" name="qty[]"
																   id="qty0" min="1">
														</div>
													</div>
												</div>

												<div id="material_div"></div>
												<div class="row justify-content-between px-4">
													<div>
														<?php if ($this->session->user_session->user_type == 1) { ?>
															<button class="btn btn-primary float-right mr-3"
																	onclick="addMoreMaterial(1)"
																	type="button">(Add More)
															</button>
														<?php } ?>
													</div>

												</div>
											</div>
										</div>
									</div>
								</div>

								<button type="button" class="btn btn-primary float-right" onclick="addMaterialBMR()">
									SAVE
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add Material Modal -->

<!-- Add Process Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="addprocessmodal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Process Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="card-body">
						<form id="processform" method="post" data-form-valid="addProcessBMR" novalidate="novalidate">
							<input type="hidden" name="bmr_id" id="bmr_id" value="<?= $id ?>"/>
							<input type="hidden" name="process_update_id" id="process_update_id" value=""/>
							<div class="row">
								<div class="col-md-6"><p>Process</p></div>
								<div class="col-md-6"><p>Sub-process</p></div>
							</div>
							<div id="PROCESS_PLAN">
								<div class="row py-2" id="process_plan_box_1">
									<div class="col-md-6">
										<div class="d-flex">
											<input type="radio" name="process_plan_required_1" class="ml-1 mr-2"
												   id="process_plan_required_1"/>
											<select class="form-control" data-valid="required"
													data-msg="Please Select Process" name="process_name"
													id="process_name_1"
													onchange="processChanges(this.value,1)"
													style="width: 100%"></select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="d-flex">
											<select class="form-control" name="template_name[]"
													id="template_name_1" multiple onchange="subProcessChange(1)"
													style="width: 100%"></select>
											<input type="hidden" name="isDependOn_1" id="is_depend_on_1"
												   value="0"/>
											<button class="btn btn-link text-dark"
													onclick="removeProcessItem(1)">
												<i class="fa fa-trash px-2"
												   style="cursor: pointer;"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="justify-content-between p-4 row">
								<button type="button" class="btn btn-primary"
										onclick="PROCESS_PLAN_add_row()"
										id="PROCESS_PLAN_add_rows">(Add More)
								</button>
							</div>

							<button type="submit" class="btn btn-primary float-right">SAVE
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add Process Modal -->


<!-- Key Pairs Configuration Modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="keyPairModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Key Pairs Configuration</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="card-body">
						<div id="keyPairsDiv1">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add Process Modal -->


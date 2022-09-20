<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
	.main-sidebar .sidebar-menu li ul.dropdown-menu {
		transform: translate3d(5px, 35px, 0px) !important;
	}

	.margin_top {
		/*margin-top: 70px!important;*/
	}

	.aside_scrollar {
		height: calc(100% - 100px);
		overflow-y: auto;
		/* width: 100%; */
		overflow-x: hidden;
	}

	.main-sidebar {
		z-index: 1 !important;
	}

	.main-sidebar .sidebar-brand {
		height: 80px !important;
	}

	.navbar-bg {
		position: fixed !important;
		z-index: 99 !important;
	}

	.navbar {
		position: fixed !important;
	}

</style>

<div class="main-sidebar sidebar-style-2 margin_top">
	<aside id="sidebar-wrapper" class="aside_scrollar">
		<div class="sidebar-brand">
		</div>
		<div class="sidebar-brand sidebar-brand-sm">

		</div>
		<ul class="sidebar-menu">
			<?php if ($this->uri->segment(1) == "view_product" || $this->uri->segment(1) == "product_details") { ?>
				<li class="sidebar-menu" id="product_link"
					onclick="storeMenuActiveStatus('product_link');getProductDetails()">
					<a class="nav-link">
						<i class="fas fa-pills"></i>
						<span>Product Detail</span>
					</a>
				</li>
				<li class="sidebar-menu" id="production_schedule_link"
					onclick="storeMenuActiveStatus('production_schedule_link');productionSchedule()">
					<a class="nav-link">
						<i class="fas fa-clipboard-list"></i>
						<span>Production Schedule</span>
					</a>
				</li>

				<li class="sidebar-menu active " id="create_indent_link"
					onclick="storeMenuActiveStatus('create_indent_link');getIndentData()">
					<a class="nav-link" id="create_indent">
						<i class="fas fa-indent"></i>
						<span>Create Indent</span>
					</a>
				</li>

				<li class="sidebar-menu " id="receive_material_link"
					onclick="storeMenuActiveStatus('receive_material_link');getReceiveMaterial()">
					<a class="nav-link">
						<i class="fas fa-recycle"></i>
						<span>Receive Material</span>
					</a>
				</li>
				<li class="sidebar-menu " id="return_material_link"
					onclick="storeMenuActiveStatus('return_material_link');getReturnMaterial()">
					<a class="nav-link">
						<i class="fa fa-reply"></i>
						<span>Return Material</span>
					</a>
				</li>


				<!--				<div class="mt-1 panel-group patientLeftSide sidebar-menu" id="patient_info_bar">-->
				<!--					<div class="panel panel-default nav-link" style="padding: 0px 22px!important;">-->
				<!--						<a data-toggle="collapse" class="selectPatientInfoA collapsed " style="color: #868e96!important;text-decoration: none;" id="selectPatientInfoA" href="#collapse1" aria-expanded="false">-->
				<!--							<div class="panel-heading">-->
				<!--								<div class="panel-title menu-header_section1" id="selectPatientInfo">-->
				<!--									<i class="fas fa-users-cog" style="margin-right: 20px;"></i> Manufacturing Process <i class="fa fa-angle-down"></i>-->
				<!--								</div>-->
				<!--							</div>-->
				<!--						</a>-->
				<!--						<div id="collapse1" class="panel-collapse collapse" style="">-->
				<!--							<ul class="ml-2 mr-2 list-group">-->
				<!--								--><? //= $process_sidebar ?>
				<!--							</ul>-->
				<!--						</div>-->
				<!--					</div>-->
				<!--				</div>-->
				<li class="sidebar-menu completed_side " id="patient_info_bar"
					onclick="storeMenuActiveStatus('patient_info_bar');getManufacturingProcess();">
					<a class="nav-link">
						<i class="fas fa-industry"></i>
						<span>Manufacturing Process</span>
					</a>
				</li>

				<li class="sidebar-menu completed_side" id="complete_link"
					onclick="storeMenuActiveStatus('complete_link');getCompleteProduction();">
					<a class="nav-link">
						<i class="fas fa-clipboard-check"></i>
						<span>Complete Schedule</span>
					</a>
				</li>
				<li class="dropdown" id="openpages">
					<input type="hidden" name="report_id" id="report_id">
					<input type="hidden" name="type" id="type" value="2">
					<input type="hidden" name="user_id" id="user_id"
						   value="<?php echo $this->session->user_session->id; ?>">
					<a href="#" class="nav-link has-dropdown" onclick="getReportData();getBMRReportPages();"><i
								class="fas fa-columns"></i> <span>BMR Report</span></a>
					<ul class="dropdown-menu" style="display: none;margin-top:-35px;height:175px;" id="bmr_pages">

					</ul>
				</li>


			<?php } else if ($this->uri->segment(1) == "material_process") { ?>

				<li class="sidebar-menu  active" id="material_details" onclick="getMaterialDetails();">
					<a class="nav-link">
						<i class="fas fa-check"></i>
						<span>Material Details</span>
					</a>
				</li>

				<?php if ($this->uri->segment(2) == "1") { ?>
					<li class="sidebar-menu" id="qc_testing" onclick="getQCtesting();">
						<a class="nav-link">
							<i class="fas fa-check"></i>
							<span>QC Testing</span>
						</a>
					</li>
				<?php } else if ($this->uri->segment(2) == "2") { ?>

					<li class="sidebar-menu" id="qa_testing" onclick="getQAtesting();">
						<a class="nav-link">
							<i class="fas fa-file"></i>
							<span>QA Testing</span>
						</a>
					</li>
				<?php } else if ($this->uri->segment(2) == "3") { ?>

					<li class="sidebar-menu" id="qc_testing_type_3" onclick="getQCtestingForType3();">
						<a class="nav-link">
							<i class="fas fa-check"></i>
							<span>QC Testing</span>
						</a>
					</li>

					<li class="sidebar-menu" id="qa_testing_type_3" onclick="getQAtestingForType3();">
						<a class="nav-link">
							<i class="fas fa-check"></i>
							<span>QA Testing</span>
						</a>
					</li>

				<?php } ?>


			<?php } else { ?>


				<?php if ($this->session->user_session->roles == 1 || $this->session->user_session->roles == 2) { ?>
					<li class="<?php echo $this->uri->segment(1) == 'ProductionSchedule' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("ProductionSchedule"); ?>">
							<i class="fas fa-clipboard-list"></i>
							<span>Production Schedule</span>
						</a>
					</li>
				<?php } ?>
				<?php if ($this->session->user_session->roles == 3) { ?>

					<li class="<?php echo $this->uri->segment(1) == 'materialSupply' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("materialSupply"); ?>">
							<i class="fa fa-cart-arrow-down"></i>
							<span>Order Material</span>
						</a>
					</li>
					<li class="<?php echo $this->uri->segment(1) == 'materialReturned' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("materialReturned"); ?>">
							<i class="fas fa-reply"></i>
							<span>Return Material</span>
						</a>
					</li>
				<?php } ?>
				<!---- Vendor Matrial Supply Start--->
				<?php if ($this->session->user_session->roles == 4) { ?>

					<li class="<?php echo $this->uri->segment(2) == '186' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("viewForm/186"); ?>">
							<i class="fas fa-users-cog"></i>
							<span>Inventory Management</span>
						</a>
					</li>

					<li class="<?php echo $this->uri->segment(2) == '194' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("viewForm/194"); ?>">
							<i class="fas fa-users-cog"></i>
							<span>Inventory History</span>
						</a>
					</li>

					<li class="<?php echo $this->uri->segment(1) == 'materialOrder' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("materialOrder"); ?>">
							<i class="fas fa-users-cog"></i>
							<span>Material Order</span>
						</a>
					</li>

					<li class="<?php echo $this->uri->segment(2) == '197' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("viewForm/197"); ?>">
							<i class="fas fa-users-cog"></i>
							<span>Order History</span>
						</a>
					</li>

					<li class="<?php echo $this->uri->segment(1) == 'vendormaterialSupply' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("vendormaterialSupply"); ?>">
							<i class="fa fa-cart-arrow-down"></i>
							<span>Material Supply</span>
						</a>
					</li>


					<!--					<li class="--><?php //echo $this->uri->segment(2) == '183' ? 'active' : ''; ?><!--">-->
					<!--						<a class="nav-link"-->
					<!--						   href="--><?php //echo base_url("viewForm/183"); ?><!--">-->
					<!--							<i class="fas fa-users-cog"></i>-->
					<!--							<span>Inventory</span>-->
					<!--						</a>-->
					<!--					</li>-->

					<li class="<?php echo $this->uri->segment(2) == '192' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("viewForm/192"); ?>">
							<i class="fas fa-users-cog"></i>
							<span>Return Materials</span>
						</a>
					</li>

					<li class="<?php echo $this->uri->segment(1) == 'materialSupply' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("materialSupply"); ?>">
							<i class="fa fa-cart-arrow-down"></i>
							<span>Issue Indent</span>
						</a>
					</li>
					<li class="<?php echo $this->uri->segment(1) == 'materialReturned' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("materialReturned"); ?>">
							<i class="fas fa-reply"></i>
							<span>Production Return</span>
						</a>
					</li>

				<?php } ?>
				<?php if ($this->session->user_session->roles == 5) { ?>
					<li class="<?php echo $this->uri->segment(2) == '187' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("viewForm/187"); ?>">
							<i class="fas fa-users-cog"></i>
							<span>Material List</span>
						</a>
					</li>
				<?php } ?>


				<?php if ($this->session->user_session->roles == 6) { ?>
					<li class="<?php echo $this->uri->segment(2) == '188' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("viewForm/188"); ?>">
							<i class="fas fa-users-cog"></i>
							<span>Material List</span>
						</a>
					</li>
				<?php } ?>
				<!-----Vendor Matrial Supply End----->
				<?php if ($this->session->user_session->user_type == 1 && $this->session->user_session->roles == 1) { ?>
					<li class="<?php echo $this->uri->segment(2) == '167' ? 'active' : ''; ?>">
						<a class="nav-link"
						   href="<?php echo base_url("viewForm/167"); ?>">
							<i class="fas fa-list"></i>
							<span>Product List</span>
						</a>
					</li>
				<?php }
				if ($this->session->user_session->user_type == 1 && $this->session->user_session->roles == 1) { ?>
					<li class="dropdown">
						<a href="#" class="nav-link has-dropdown" onclick="openSubMenu('master_module')"><i
									class="fas fa-columns"></i> <span>Master</span></a>
						<ul class="dropdown-menu" style="display: none;margin-top: -35px;" id="master_module">
							<li class="<?php echo $this->uri->segment(1) == 'template_list' ? 'active' : ''; ?>">
								<a class="nav-link" href="<?php echo base_url(); ?>template_list">
									<i class="fas fa-users-cog"></i>
									<span>Template List</span>
								</a>
							</li>
							<li class="<?php echo $this->uri->segment(2) == 'view_departments' ? 'active' : ''; ?>">
								<a class="nav-link"
								   href="<?php echo base_url("admin/view_departments"); ?>">
									<i class="fas fa-users-cog"></i>
									<span>Process</span>
								</a>
							</li>
							<li class="<?php echo $this->uri->segment(2) == '173' ? 'active' : ''; ?>">
								<a class="nav-link"
								   href="<?php echo base_url("viewForm/173"); ?>">
									<i class="fas fa-users-cog"></i>
									<span>Facility</span>
								</a>
							</li>
							<li class="<?php echo $this->uri->segment(2) == '176' ? 'active' : ''; ?>">
								<a class="nav-link"
								   href="<?php echo base_url("viewForm/176"); ?>">
									<i class="fas fa-users-cog"></i>
									<span>Users</span>
								</a>
							</li>
							<li class="<?php echo $this->uri->segment(2) == '191' ? 'active' : ''; ?>">
								<a class="nav-link"
								   href="<?php echo base_url("viewForm/191"); ?>">
									<i class="fas fa-users-cog"></i>
									<span>Vendors</span>
								</a>
							</li>
							<li class="<?php echo $this->uri->segment(2) == '181' ? 'active' : ''; ?>">
								<a class="nav-link"
								   href="<?php echo base_url("viewForm/181"); ?>">
									<i class="fas fa-users-cog"></i>
									<span>Materials</span>
								</a>
							</li>

							<li class="<?php echo $this->uri->segment(2) == '165' ? 'active' : ''; ?>">
								<a class="nav-link"
								   href="<?php echo base_url("viewForm/165"); ?>">
									<i class="fas fa-users-cog"></i>
									<span>Main Process Area</span>
								</a>
							</li>

							<!--							<li class="-->
							<?php //echo $this->uri->segment(2) == '170' ? 'active' : ''; ?><!--">-->
							<!--								<a class="nav-link"-->
							<!--								   href="-->
							<?php //echo base_url("viewForm/170"); ?><!--">-->
							<!--									<i class="fas fa-users-cog"></i>-->
							<!--									<span>Sub Process List</span>-->
							<!--								</a>-->
							<!--							</li>-->
							<li class="<?php echo $this->uri->segment(2) == '207' ? 'active' : ''; ?>">
								<a class="nav-link" href="<?php echo base_url(); ?>viewForm/207">
									<i class="fas fa-users-cog"></i>
									<span>Query Parameter</span>
								</a>
							</li>
							<li class="<?php echo $this->uri->segment(2) == '204' ? 'active' : ''; ?>">
								<a class="nav-link" href="<?php echo base_url(); ?>viewForm/204">
									<i class="fas fa-users-cog"></i>
									<span>BMR Reports</span>
								</a>
							</li>


						</ul>
					</li>

				<?php }
			} ?>
		</ul>
	</aside>
</div>

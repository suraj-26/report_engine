<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
	.card .card_border101 .shadow-none,
	.card .card_border102 .shadow-none,
	.card .card_border103 .shadow-none,
	.card .card_border104 .shadow-none,
	.card .card_border105 .shadow-none,
	{
		padding: 0 !important;
	}

	.card-body.card_body101, .card-body.card_body102, .card-body.card_body103, .card-body.card_body104, .card-body.card_body105 {
		padding: 0 !important;
	}

</style>
<style type="text/css">

	.tabs {
		position: relative;
		overflow: hidden;
		margin: 0 auto;
		width: 100%;
		font-weight: 300;
		font-size: 1.25em;
	}

	/* Nav */
	.tabs nav {
		text-align: center;
	}

	.tabs nav ul {
		position: relative;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: -moz-flex;
		display: -ms-flex;
		display: flex;
		margin: 0 auto;
		padding: 0;
		max-width: 1200px;
		list-style: none;
		-ms-box-orient: horizontal;
		-ms-box-pack: center;
		-webkit-flex-flow: row wrap;
		-moz-flex-flow: row wrap;
		-ms-flex-flow: row wrap;
		flex-flow: row wrap;
		-webkit-justify-content: center;
		-moz-justify-content: center;
		-ms-justify-content: center;
		justify-content: center;
	}

	.tabs nav ul li {
		position: relative;
		z-index: 1;
		display: block;
		margin: 0;
		text-align: center;
		-webkit-flex: 1;
		-moz-flex: 1;
		-ms-flex: 1;
		flex: 1;
	}

	.tabs nav a {
		position: relative;
		display: block;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		line-height: 2.5;
	}

	.tabs nav a span {
		vertical-align: middle;
		font-size: 1em;
	}

	.tabs nav li.tab-current a {
		color: #74777b;
	}

	.tabs nav a:focus {
		outline: none;
	}

	/* Icons */
	.icon::before {
		z-index: 10;
		display: inline-block;
		margin: 0 0.4em 0 0;
		vertical-align: middle;
		text-transform: none;
		font-weight: normal;
		font-variant: normal;
		font-size: 1.3em;
		font-family: 'stroke7pixeden';
		line-height: 1;
		speak: none;
		-webkit-backface-visibility: hidden;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	/* Content */
	.content-wrap {
		position: relative;
	}

	.content-wrap section {
		display: none;
		/*margin: 0 auto;*/
		padding: 0px !important;
		max-width: 100%;
		text-align: center;
	}

	.content-wrap section.content-current {
		display: block;
	}

	.content-wrap section p {
		margin: 0;
		padding: 0.75em 0;
		color: rgba(40, 44, 42, 0.05);
		font-weight: 900;
		font-size: 4em;
		line-height: 1;
	}

	/* Fallback */
	.no-js .content-wrap section {
		display: block;
		padding-bottom: 2em;
		border-bottom: 1px solid rgba(255, 255, 255, 0.6);
	}

	.no-flexbox nav ul {
		display: block;
	}

	.no-flexbox nav ul li {
		min-width: 15%;
		display: inline-block;
	}

	/*****************************/
	/* Underline */
	/*****************************/

	.tabs-style-underline nav {
		background: #fff;
	}

	.tabs-style-underline nav a {
		padding: 0.25em 0 0.5em !important;
		border-left: 1px solid #e7ecea;
		-webkit-transition: color 0.2s;
		transition: color 0.2s;
	}

	.tabs-style-underline nav li:last-child a {
		border-right: 1px solid #e7ecea;
	}

	.tabs-style-underline nav li a::after {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 6px;
		background: #891635;
		content: '';
		-webkit-transition: -webkit-transform 0.3s;
		transition: transform 0.3s;
		-webkit-transform: translate3d(0, 150%, 0);
		transform: translate3d(0, 150%, 0);
	}

	.tabs-style-underline nav li.tab-current a::after {
		-webkit-transform: translate3d(0, 0, 0);
		transform: translate3d(0, 0, 0);
	}

	.tabs-style-underline nav a span {
		font-weight: 700;
	}

	.tabs-style-underline nav a:hover {
		text-decoration: none !important;
		color: #74777b !important;
	}

	.fa_class {
		font-size: 22px !important;
	}

	@media screen and (max-width: 58em) {
		.tabs nav a.icon span {
			display: none;
		}

		.tabs nav a:before {
			margin-right: 0;
		}
	}

	#dynamicDataTableFilter_0 {
		margin-top: 6px !important;
	}
	#icu .sticky_value {
		color: white;
	}
	#icu th{
		color: white;
	}

</style>
<!-- Main Content start -->
<div class="main-content">
	<section class="section">

		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-primary">

						<div class="card-body px-1">
							<section>
								<input type="hidden" id="department_id" name="department_id"
									   value="">
								<input type="hidden" id="section_id" name="section_id"
									   value="">
								<input type="hidden" id="queryparameter_hidden" name="queryparameter_hidden"
									   value="">
								<div class="tabs tabs-style-underline">
									<nav>

										<ul>
											<li class="tab-current"><a href="#ipd" class="icon"
																	   onclick="get_forms(155,0,'ipd'),dynamicFilter('#datatable_button_72',0,``,3,1,155)"><i
															class="fa fa-hospital mr-1 fa_class"></i> <span> IPD</span></a>
											</li>
											<li class=""><a href="#opd" class="icon"
															onclick="opdOperation()"
												><i
															class="fa fa-stethoscope mr-1 fa_class"></i>
													<span> OPD</span></a></li>
											                                            <li class=""><a href="#icu" class="icon" onclick="get_dataICU()"><i
											                                                            class="fa fa-procedures mr-1 fa_class"></i>
											                                                    <span> ICU</span></a></li>
											<!--                                            <li class=""><a href="#emergency" class="icon"-->
											<!--                                                            onclick="get_forms(104,0,'emergency')"><i-->
											<!--                                                            class="fa fa-heartbeat mr-1 fa_class"></i>-->
											<!--                                                    <span> Emergency</span></a></li>-->
											<!--                                            <li class=""><a href="#operation" class="icon"-->
											<!--                                                            onclick="get_forms(105,0,'operation')"><i-->
											<!--                                                            class="fa fa-diagnoses mr-1 fa_class"></i> <span> Operation Theatre</span></a>-->
											<!--                                            </li>-->
										</ul>
									</nav>
									<div class="content-wrap">
										<section id="ipd" class="content-current">

										</section>
										<section id="opd"></section>
										<section id="icu">
											<div class="section-header card-primary" style="border-top: 0px!important;">
												<h1 style="color: #891635">ICU Management</h1>
												<button class="btn btn-link pt-3" onclick="open_modal_bed()"><i
															class="fa fa-bed" style="font-size:16px"></i></button>
												<div class="card-header-action d-flex" style="margin-left: auto; ">
													<div class="align-items-center">
														<div class="form-group mx-2 my-0">
															<select id="searchGender" class="form-control"
																	onchange="get_roomdetailstable()"
																	style="border-radius: 20px;">
																<option value="0">All</option>
																<option value="1">Male</option>
																<option value="2">Female</option>
															</select>
														</div>
													</div>
													<div class="align-items-center">
														<div class="form-group mx-2 my-0">
															<select id="searchAge" class="form-control"
																	onchange="get_roomdetailstable()"
																	style="border-radius: 20px;">
																<option selected disabled>Select Age</option>
																<option value="0-20">0-20</option>
																<option value="21-40">21-40</option>
																<option value="41-60">41-60</option>
																<option value="age>60">age >60</option>
																<option value="0">All</option>
															</select>
														</div>
													</div>
													<div class="align-items-center">
														<div class="form-group mx-2 my-0">
															<select id="searchPatient" class="form-control"
																	onchange="get_roomdetailstable()"
																	style="border-radius: 20px;">
																<option selected disabled>Select Patient</option>

															</select>
														</div>
													</div>
												</div>


											</div>

											<div class="">

												<div id="show_room_details" style="overflow-x: auto;">
												</div>
											</div>
										</section>
										<section id="emergency">

										</section>
										<section id="operation">

										</section>
									</div><!-- /content -->
								</div><!-- /tabs -->
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
</div>

<button type="button" class="btn btn-info btn-lg d-none" id="icu_bed_medicine_button" data-toggle="modal"
		data-target="#icu_bed_medicine_modal">Open Modal
</button>
<div class="modal fade" id="icu_bed_medicine_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4><span id="headerIcuPanel"></span></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-md-12" id="icu_bed_medicine_data">

				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="IcubedModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5>ICU Bed Management</h5>
			</div>
			<div class="modal-body">
				<div class="row justify-content-sm-around" id="bed_data">

				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="largeVideo" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="row justify-content-center align-items-center">
					<canvas id="large_video_panel" width="500" height="400">
						No Supported
					</canvas>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Main Content end -->
<?php $this->load->view('_partials/footer'); ?>

<script type="text/javascript">
	const videoStreaming = 'https://vs.ecovisrkca.com/';
	let cameraCapture = null;
	var base_url = "<?php echo base_url(); ?>";
	$(document).ready(function () {
		get_forms(155, 0, 'ipd').then(r=>{
			dynamicFilter('#datatable_button_94',0,``,3,1,156);
		});
		[].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
			new CBPFWTabs(el);
		});
	});

	function opdOperation() {
		get_forms(156,0,'opd').then(r=>{
			if(r==1){
				dynamicFilter('#datatable_button_94',0,``,3,1,156)
			}

		})
	}

	function get_dataICU() {
		get_roomdetailstable();
		
		$('#largeVideo').on('show.bs.modal', function (e) {
			let camera = parseInt($(e.relatedTarget).data('camera'));
			largeVideoPanel(-1);
			largeVideoPanel(camera);
		})
		$('#largeVideo').on('hide.bs.modal', function (e) {
			disconnectVideo();
		})
	}

</script>

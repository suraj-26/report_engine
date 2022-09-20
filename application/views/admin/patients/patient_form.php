<?php
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	$url = "https://";
else
	$url = "http://";
$url .= $_SERVER['HTTP_HOST'];
$url .= $_SERVER['REQUEST_URI'];
$patientId = basename($url);
// print_r($patientId);
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
$permission_array=$this->session->user_permission;
?>
<?php
$code = '<?xml version="1.0" encoding="UTF-8"?>
    <PrintLetterBarcodeData uid="218659385355" 
    name="Pooja Rajendra Lote" gender="F" yob="1997" 
    loc="Dhamani" vtc="Dhamani" po="Khalapur" dist="Raigarh" 
    subdist="Khalapur" state="Maharashtra" pc="410202" dob="31/07/1997"/>';
// $xml=simplexml_load_string($code);
// // print_r($xml);
// $uid= $xml['uid'];
// $name= $xml['name'];
// $gender= $xml['gender'];
// $year_of_birth= $xml['yob'];
// $location= $xml['loc'];
// $vtc= $xml['vtc'];
// $post= $xml['po'];
// $dist= $xml['dist'];
// $subdist= $xml['subdist'];
// $pc= $xml['pc'];
// $dob= $xml['dob'];

if (!isset($patient_id)) {
	$patient_id = 0;
}

?>
<style>
	.error {
		color: red;
	}

	#my_camera {
		border: none !important;
	}

	#my_camera video {
		border-radius: 50%;
		object-fit: cover;

	}
</style>
<section id="vidscan" style="display: none;">
	<div class="container">

		<div id="overlay" onclick="off()">
			<div class="col-md-12 col-sm-12" style="padding: 100px;"><span class="close">&times;</span>

				<video id="preview" style="width: 100%;height: 500px;"></video>
			</div>
		</div>
	</div>
</section>

<div class="main-content main-content1">
	<section class="section">


		<!--		<div class="row">-->
		<!--			<div class="col-2 col-md-2 text-center">-->
		<!--				--><?php //$this->load->view('_partials/left_sidebar'); ?>
		<!--			</div>-->
		<!--			<div class="col-10 col-md-10">-->

		<div class="section-body">
			<div class="justify-content-center">

				<div class="">
					<div class="card">
						<div class="section-header card-primary">
							<h1>Aadhar Details</h1>
						</div>
						<form id="patientForm" method="post" data-validate="ture" onsubmit="return false">
							<input type="text" id="addhar_img" name="patient_adhhar_image"
								   style="display: none;">
							<input type="hidden" id="searchSelectedPatient" name="searchSelectPatient" />
							<div class="card-body">
								<?php if($this->uri->segment(2)=="new_patients" || $this->uri->segment(1)=="new_patients"){ ?>
									<div class="section-title">Search Patient</div>
									<div class="form-group">
										<div class="input-group mb-3">
											<input type="text" id="search_patient" name="search_patient"
												   class="form-control"
												   placeholder="Search patient by name or aadhar number"/>
											<div class="input-group-append">
												<button class="btn btn-primary" onclick="searchPatients($('#search_patient').val())" type="button">search</button>
											</div>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<ul class="list-group list-group-flush" id="suggestion_list">
									</ul>
								</div>
								<div class="card d-none" id="search_patient_exist">
									<div class="card-header">
										<div class="buttons">
											<nav aria-label="Page navigation example">
												<ul class="pagination pagination-sm" id="patient_list">

												</ul>
											</nav>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-3">
												<p class="card-title">Name</p>
												<p class="card-subtitle" id="search_patient_name"></p>
											</div>
											<div class="col-md-3">
												<p class="card-title">Aadhar Number</p>
												<p class="card-subtitle" id="search_patient_aadhar"></p>
											</div>
											<div class="col-md-3>
												<p class=" card-title">Type</p>
											<p class="card-subtitle" id="search_patient_type"></p>
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<button class="btn btn-primary" type="button" onclick="setSelectedPatient()">
										Submit
									</button>
								</div>
							</div>
								<div class="section-title">Aadhar Details</div>

								<div class="form-group">
									<div class="input-group mb-3">
										<input class="m-1" type="radio" id="adhardetails1" checked name="adhardetails" onclick="checkadhar(1)" value="1">
										<label >Yes</label>
										<input class="m-1" type="radio" id="adhardetails2" name="adhardetails" onclick="checkadhar(2)" value="2">
										<label >No</label>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group mb-3">
										<input type="text" id="adhhar_no" name="adhhar_no" onfocusout="search()"
											   class="form-control" placeholder="Aadhar Number"
											   aria-label="" autocomplete="off">
										<!--											<div class="input-group-append">-->
										<!--												<button class="btn btn-primary" onclick="on()" type="button">Scan Aadhar-->
										<!--													Card-->
										<!--												</button>-->
										<!--											</div>-->
									</div>
								</div>

								<div class="form-group">
									<label>Name</label>
									<input type="hidden" id="patientId" name="patientId"
										   value="<?php echo $patient_id; ?>">
									<input type="text" id="name" class="form-control" name="name" data-required
										   data-required-msg>
								</div>
								<div class="form-group">
									<label>Profile Photo</label>
									<input type="hidden" id="p_img" name="patient_image"/>


									<button class="btn btn-primary" onclick="cap_image()" type="button">Open
										Camera
									</button>
									<div id="results"></div>
									<div id="my_camera" style="display: none;"></div>
									<button class="btn btn-primary" style="display: none;"
											onclick="take_snapshot()" id="cap_btn" type="button">Capture
									</button>
								</div>
								<div class="form-group">
									<label class="d-block">Gender</label>
									<div class="form-check form-check-inline">
										<input class="form-check-input gender" value="1" type="radio"
											   name="gender"
											   id="maleRadio">
										<label class="form-check-label" for="maleRadio">
											Male
										</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input gender" value="2" type="radio"
											   name="gender"
											   id="femaleRadio">
										<label class="form-check-label" for="femaleRadio">
											Female
										</label>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label>Birth Date</label>
										<input type="date" id="dob"  class="form-control" name="birth_day">
									</div>
									<div class="form-group col-md-6">
										<label>Blood Group</label>
										<select class="form-control" name="blood_group" id="bloodGroup">
											<option value="">Select Blood Group</option>
											<option value="A+">A+</option>
											<option value="A-">A-</option>
											<option value="B+">B+</option>
											<option value="B-">B-</option>
											<option value="o+">O+</option>
											<option value="o-">O-</option>
											<option value="AB+">AB+</option>
											<option value="AB-">AB-</option>
											<option value="Unknown-">unknown</option>
										</select>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label>Contact Number</label>
										<input type="text" class="form-control"
											   name="contact" id="contact">
										<input type="text" style="display: none;" id="vtc" class="form-control"
											   name="vtc">
									</div>
								</div>
								<div class="form-group">
									<label>Address</label>
									<textarea class="form-control" id="loc" name="location"></textarea>
								</div>
								<div class="form-row">
									<div class="form-group col-md-4">
										<label>District</label>
										<input type="text" id="dist" class="form-control" name="dist">
									</div>
									<div class="form-group col-md-4">
										<label>Sub-district</label>
										<input type="text" id="subdist" class="form-control" name="sub_dist">
									</div>
									<div class="form-group col-md-4">
										<label>Pin Code</label>
										<input type="text" id="pc" class="form-control" name="postal_code">
									</div>
								</div>
								<div class="section-title">Other Details</div>
								<div class="form-row">
									<div class="form-group col-md-4">
										<label>Payer</label>
										<input type="text" class="form-control" name="admission_payer"
											   id="admission_payer">
									</div>
									<div class="form-group col-md-4">
										<label>Company</label>
										<input type="text" class="form-control" name="patient_company" id="patient_company">
									</div>

								</div>
								<div class="form-group">
										<label>Work Location</label>
										<textarea class="form-control" id="work_location" name="work_location"></textarea>
									</div>


								<div class="section-title">Admission Details</div>
							<div class="form-row">
								<div class="form-group">
									<label>Admission Mode</label>
								</div>
							</div>
							<div class="form-group">
								<label class="custom-switch custom-control-inline">
									<input type="radio" name="admission_mode" id="ckteleconsult"
										   onchange="toggleAdmissionMode(this,1)"
										   class="custom-switch-input" value="1">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">OPD</span>
								</label>
								<label class="custom-switch  custom-control-inline">
									<input type="radio" name="admission_mode" id="ckadmission"
										   onchange="toggleAdmissionMode(this,2)" checked
										   value="2"
										   class="custom-switch-input">
									<span class="custom-switch-indicator"></span>
									<span class="custom-switch-description">IPD</span>
								</label>
							</div>
								<div class="form-group">
									<label id="lableAdmit">Admission Date</label>
									<input type="datetime-local"  class="form-control" name="admission_date"
										   id="admission_date">
								</div>



								<div class="form-group d-none" id="doctorName">
									<label id="">Consultant</label>
									<select class="form-control" name="doctor_name" id="doctor_name">
										<option selected disabled>Select Doctor Name</option>
									</select>
								</div>
								<!--<div class="form-group d-none" id="teleconsultStartDdate">
									<label>Teleconsultation Start Date</label>
									<input type="datetime-local" class="form-control" onkeydown="return false" name="admission_teleconsult_date"
										   id="admission_date">
								</div>
-->
							</div>
							<div class="card-footer text-right">
							<?php
		if (in_array("patient_registration", $permission_array) || in_array("patient_edit",$permission_array)){
		?>
			<button class="btn btn-primary mr-1" id="submit_btn" type="Submit">Submit</button>
	<?php	}
		?>

								<button class="btn btn-secondary" type="reset">Reset</button>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>
		<!--			</div>-->
		<!--		</div>-->
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
<!-- <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/dynamsoft-javascript-barcode@7.2.2-v2/dist/dbr.js" data-productKeys="LICENSE-KEY"></script>  -->


<script type="text/javascript">



	function toggleAdmissionMode(e, id) {

		if ($(e).is(":checked")) {
			if (id == 2) {
				$('#teleconsultStartDdate').addClass('d-none');
				$('#doctorName').addClass('d-none');
				$('#lableAdmit').html('Admission Date');
			} else {
				$('#teleconsultStartDdate').removeClass('d-none');
				$('#doctorName').removeClass('d-none');
				$('#lableAdmit').html('Consultation Date');
			}
		}
	}

	function on() {
		document.getElementById("vidscan").style.display = "block";
		document.getElementById("overlay").style.display = "block";
		document.getElementById("preview").style.display = 'block';

		let scanner = new Instascan.Scanner({video: document.getElementById('preview')});
		scanner.addListener('scan', function (content) {
			// var data = $.parseXML(content);

			// alert(content);

			// alert(content);
			// document.getElementById("text").innerText=content;
			// document.getElementById("fill").style.display='block';
			scanner.stop();
			document.getElementById("overlay").style.display = "none";
			document.getElementById("vidscan").style.display = "none";


			fill(content);

		});
		Instascan.Camera.getCameras().then(function (cameras) {
			if (cameras.length > 0) {
				scanner.start(cameras[0]);
			} else {
				console.error('No cameras found.');
			}
		}).catch(function (e) {
			console.error(e);
		});
	}

	function off() {
		document.getElementById("overlay").style.display = "none";
		document.getElementById("vidscan").style.display = "none";
		let scanner = new Instascan.Scanner({video: document.getElementById('preview')});
		scanner.stop();
	}

	function fill(code) {
		// console.log(code);
		if (code.includes('8?') == true) {
			var cd = code.substring(40);
			var code = '<?xml version = "1.0" encoding = "UTF-8"?>' + cd;


		}
		if (code.includes('<?xml version = "1.0" encoding = "UTF-8"?>') == true) {
			var code = code;
		} else {
			// alert("hii");
			document.getElementById("vidscan1").style.display = "block";
			document.getElementById("overlay1").style.display = "block";
			cap_image1();

			function cap_image1() {
				document.getElementById("my_camera1").style.display = "block";

				Webcam.set({
					width: 320,
					height: 240,
					image_format: 'jpeg',
					jpeg_quality: 90
				});
				Webcam.attach('#my_camera1');
				document.getElementById("cap_btn1").style.display = "block";
			}

		}


		// console.log(code);

		// app.request(baseURL+"patientController/fill")
		$.ajax({
			url: 'https://covidcare.docango.com/patientController/fill',
			type: "POST",
			data: {code},
			success: function (success) {
				success = jQuery.parseJSON(success);
				var data = success.xml_arr;
				var uid = data['uid'][0];
				var name = data['name'][0];
				var gender = data['gender'][0];
				var yob = data['year_of_birth'][0];
				var loc = data['location'][0];
				var vtc = data['vtc'][0];
				var dist = data['dist'][0];
				var pc = data['pc'][0];

				// console.log(data['uid'][0]);

				// document.getElementById("u_id").value(uid);
				console.log(data);

				document.getElementById("adhhar_no").value = uid;
				document.getElementById("name").value = name;
				if (gender == "M" || gender == "MALE") {
					document.getElementById("maleRadio").checked = 'true';
				} else {
					document.getElementById("femaleRadio").checked = 'true';
				}
				document.getElementById("loc").value = loc + "," + vtc;
				document.getElementById("vtc").value = vtc;
				document.getElementById("dist").value = dist;
				document.getElementById("pc").value = pc;


			}, error: function (error) {
				alert("something went to wrong");
			}
		});
	}

	function checkadhar(id){
		if(id==1){
			$("#adhhar_no").show();
		}else{
			$("#adhhar_no").hide();
		}
	}



	function check_billing_status(){
	var p_id = localStorage.getItem("patient_id");
	$.ajax({
            type: "POST",
            url: '<?= base_url("check_billing_status")?>',
            dataType: "json",
            async: false,
            cache: false,
			data:{p_id},
            success: function (result) {

                if (result.status == 200) {
					var value=result.value;
                   if(value == 1){

					   $("#submit_btn").prop('disabled', true);
				   }else{
					    $("#submit_btn").prop('disabled', false);
				   }
                } else {

                }
            }
        });
}

	let searchPatient = [];
	let selectedPatientPostion = 0;

	function searchPatients(searchValue) {
		searchPatient = [];
		if (searchValue != "") {
			let formData = new FormData();
			formData.set("searchValue", searchValue);
			app.request("searchPatient", formData).then(response => {
				if (response.status === 200) {
					searchPatient = response.body
					$("#suggestion_list").empty();
					searchPatient.map((p, i) => {
						$("#suggestion_list").append(
								`<li class="d-flex justify-content-between list-group-item">${p.patient_name + ' ' + p.adhar_no} <span class="btn btn-primary" onclick="loadPatientFromCash(${i})">get Details</span></li>`
						);
					});
					selectedPatientPostion = 0;
				} else {
					app.errorToast(response.body);
				}
			})
		}
		else {
			$("#suggestion_list").empty();
		}

	}

	function setSelectedPatient() {
		let patientObject = searchPatient[selectedPatientPostion];
		$("#searchSelectedPatient").val(patientObject.id);
		if (patientObject.adhar_no != null && patientObject.adhar_no != '') {
			$("#adhhar_no").val(patientObject.adhar_no);

		}
		if (patientObject.patient_name != null && patientObject.patient_name != '') {
			$("#name").val(patientObject.patient_name);

		}
		if (patientObject.gender != null && patientObject.gender != '') {
			let comp = patientObject.gender;
			if (comp == 1) {
				var male = document.getElementById("maleRadio");
				male.checked = true;
			} else {
				var female = document.getElementById("femaleRadio");
				female.checked = true;
			}
		}
		if (patientObject.birth_date != null && patientObject.birth_date != '') {
			$("#dob").val(patientObject.birth_date);

		}
		if (patientObject.blood_group != null && patientObject.blood_group != '') {
			let blood = patientObject.blood_group;
			$("#bloodGroup option[value='" + blood + "']").prop("selected", true);
		}

		if (patientObject.contact != null && patientObject.contact != '') {
			$("#contact").val(patientObject.contact);

		}
		if (patientObject.other_contact != null && patientObject.other_contact != '') {
			$("#other_contact").val(patientObject.other_contact);

		}
		$("#loc").val("");
		$("#dist").val("");
		$("#subdist").val("");
		$("#pc").val("");
		if (patientObject.address != null && patientObject.address != '') {
			$("#loc").val(patientObject.address);

		}
		if (patientObject.district != null && patientObject.district != '') {
			$("#dist").val(patientObject.district);

		}
		if (patientObject.sub_district != null && patientObject.sub_district != '') {
			$("#subdist").val(patientObject.sub_district);

		}
		if (patientObject.pin_code != null && patientObject.pin_code != '') {
			$("#pc").val(patientObject.pin_code);

		}

		if (patientObject.admission_date != null && patientObject.admission_date != '') {
			var now = new Date();
			now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
			document.getElementById('admission_date').value = now.toISOString().slice(0,16);
		}

		if (patientObject.admission_mode != null && patientObject.admission_mode != '') {
			if (parseInt(patientObject.admission_mode) == 2) {
				$("#ckadmission").attr("checked", "checked");
			} else {
				$("#ckteleconsult").attr("checked", "checked");
				var consultInput=document.getElementById('ckteleconsult');
				toggleAdmissionMode(consultInput,1);
			}
		}
		$("#admission_payer").val("");
		$("#patient_company").val("");
		$("#work_location").val("");
		if (patientObject.payer != null && patientObject.payer != '') {

			$("#admission_payer").val(patientObject.payer);

		}
		if (patientObject.patient_company != null && patientObject.patient_company != '') {

			$("#patient_company").val(patientObject.patient_company);

		}
		if (patientObject.work_location != null && patientObject.work_location != '') {

			$("#work_location").val(patientObject.work_location);

		}
		$("#suggestion_list").empty();
		$("#search_patient").val('');
	}
	function loadPatientFromCash(postion) {
		let patientObject = searchPatient[postion];
		selectedPatientPostion = postion;
		setSelectedPatient();
		// $("#patient_list li").removeClass('active')
		// $(`#patient_list li:nth-child(${postion+2})`).addClass('active');
		// $("#search_patient_name").html(patientObject.patient_name);
		// $("#search_patient_aadhar").html(patientObject.adhar_no);
		// let type=parseInt(patientObject.admission_mode);
		// if(type==2){
		// 	$("#search_patient_type").html("IPD");
		// }else{
		// 	$("#search_patient_type").html("OPD");
		// }
		// $("#search_patient_type").html(patientObject.patient_type);
	}

</script>


<?php $this->load->view('_partials/footer');

if ($patient_id != 0) {
	echo "<script>get_PatientDataById(" . $patient_id . ")</script>";

}
?>

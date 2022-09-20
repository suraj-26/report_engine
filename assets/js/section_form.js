
//on readyState


// user in dynamic form submision
function save_form_data(form) {

	app.request("sectionSave",new FormData(form)).then(result=>{
		var firm_data = result.firm_data;

		if (result['code'] == '200') {
			app.successToast("Save Changes");
			location.reload();

		} else {
			app.errorToast("Failed To Save Data")
		}
	}).catch(error=>{
		console.log(error)
		app.errorToast('Something went wrong please try again');
	})

}
// use in discharge form
function saveDischarge(form) {

	app.request("patientDischarge",new FormData(form)).then(result=>{
		if (result.status == 200) {
			app.successToast(result.body)
		} else {
			app.errorToast(result.body)
		}
	}).catch(error=>{
		console.log(error)
		app.errorToast('Something went wrong please try again');
	})

}
function getDischargeDetails(patientId) {

	let formData = new FormData();
	formData.set("patientId",patientId);
	app.request("https://covidcare.ecovisrkca.com/new_patients/getPatientData", formData).then(response => {
		if (response.status === 200) {
			var user_data = response.body;
			if (user_data[0]['discharge_date'] != null && user_data[0]['discharge_date'] != '') {
				let date = app.getDate(user_data[0]['discharge_date']);
				$("#discharge_date").val(date);
			}
			if (user_data[0]['diagnostic'] != null && user_data[0]['diagnostic'] != '') {
				$("#patient_diagnostic").val(user_data[0]['diagnostic']);
			}
			if (user_data[0]['treated_hospital'] != null && user_data[0]['treated_hospital'] != '') {
				$("#patient_treated_hospital").val(user_data[0]['treated_hospital']);
			}
			if (user_data[0]['course_hospital'] != null && user_data[0]['course_hospital'] != '') {
				$("#patient_course_hospital").val(user_data[0]['course_hospital']);
			}
			if (user_data[0]['followup_date'] != null && user_data[0]['followup_date'] != '') {
				// let date = app.getDate(user_data[0]['followup_date']);
				let followup_date = user_data[0]['followup_date'];
				$("#patient_follow_up").val(followup_date);
			}
			if (user_data[0]['swab_report'] != null && user_data[0]['swab_report'] != '') {
				let blood = user_data[0]['swab_report'];
				$("#patient_swab_report option[value='" + blood + "']").prop("selected", true);
			}
			// ----
			if (user_data[0]['event'] != null && user_data[0]['event'] != '') {
				let event = user_data[0]['event'];
				$("#event option[value='" + event + "']").prop("selected", true);
			}
			if (user_data[0]['significant_event'] != null && user_data[0]['significant_event'] != '') {
				let significant_event = user_data[0]['significant_event'];
				$("#sign_event").val(significant_event);
			}
			if (user_data[0]['discharge_condition'] != null && user_data[0]['discharge_condition'] != '') {
				let discharge_condition = user_data[0]['discharge_condition'];
				$("#cndn_discharge_time").val(discharge_condition);
			}
			if (user_data[0]['medication'] != null && user_data[0]['medication'] != '') {
				let medication = user_data[0]['medication'];
				$("#medication").val(medication);
			}
			if (user_data[0]['physical_activity'] != null && user_data[0]['physical_activity'] != '') {
				let physical_activity = user_data[0]['physical_activity'];
				$("#physical_activity").val(physical_activity);
			}
			if (user_data[0]['urgent_care'] != null && user_data[0]['urgent_care'] != '') {
				let urgent_care = user_data[0]['urgent_care'];
				$("#urgent_care").val(urgent_care);
			}
			if (user_data[0]['is_transfered'] != null && user_data[0]['is_transfered'] != '') {
				let is_transfered = user_data[0]['is_transfered'];
				$('input[name="is_transfer"][value="' + is_transfered + '"]').prop('checked', true);
				if(is_transfered==1)
				{
					$("#transfer_section").show();
					if (user_data[0]['transfer_to'] != null && user_data[0]['transfer_to'] != '') {
						let trasfer_to = user_data[0]['transfer_to'];
						$("#trasfer_to").val(trasfer_to);
					}
					if (user_data[0]['transfer_reason'] != null && user_data[0]['transfer_reason'] != '') {
						let trans_reason = user_data[0]['transfer_reason'];
						$("#trans_reason").val(trans_reason);
					}
				}
				else {
					$("#transfer_section").hide();
				}
			}

		} else {
			app.errorToast('data not found ');
		}
	});
}

function get_patient_details(){
	var patient_id=$("#patient_id").val();
	$.ajax({
		type: 'POST',
		url:  "getpatientdatadis",
		data: {p_id: patient_id},
		success: function (success) {
			success = JSON.parse(success);
			if (success.status == 200) {
				let date = success.data;

				$("#admission_date").val( date.split(' ')[0]);
				$("#atime").val(date.split(' ')[1]);
				$("#medication_auto").val(success.medication);


			} else {

			}

		}

	});
}
function is_transfer_fun(id){
	if(id == 1){
		$("#transfer_section").show();
	}else{
		$("#transfer_section").hide();
	}
}

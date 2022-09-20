function loadCustomvalidation() {
	$.validator.addMethod("checkInvoiceNoPurchaseOrder", function (value, element) {
		// let datacheck=await checkInvoiceNoPurchaseOrder(value);
		// return datacheck.status;
			let isSuccess=true;
		if($("input[type='hidden'][name='update_id']")){
			let updateOperation=$("input[type='hidden'][name='update_id']").val();
			if(updateOperation===''){
				$.ajax({
					url: baseURL+"checkInvoiceNoPurchaseOrder",
					data: {invoice_no: value},
					type: "post",
					dataType: "json",
					async: false,
					success: function (msg) {
						console.log(msg.status);
						isSuccess = msg.status === true ? true : false;
					}
				});
				console.log(isSuccess);
				return isSuccess;
			}else{
				$(element).attr("readonly",true)
			}
		}
		return isSuccess;

	},"Invoice Number already exist");
	$.validator.addMethod("checkInvoiceNoPurchaseOrderReturn", function (value, element) {
		$.ajax({
			url: baseURL+"checkInvoiceNoPurchaseOrderReturn",
			data: {invoice_no: value},
			type: "post",
			dataType: "json",
			async: false,
			success: function (msg) {
				console.log(msg.status);
				isSuccess = msg.status === true ? true : false;
			}
		});
		console.log(isSuccess);
		return isSuccess;
	},'Invoice Number already exist');
	$.validator.addMethod("checkInvoiceNoSalesOrder", function (value, element) {
		$.ajax({
			url: baseURL+"checkInvoiceNoSalesOrder",
			data: {invoice_no: value},
			type: "post",
			dataType: "json",
			async: false,
			success: function (msg) {
				console.log(msg.status);
				isSuccess = msg.status === true ? true : false;
			}
		});
		console.log(isSuccess);
		return isSuccess;
	},'Invoice Number already exist.' );
	$.validator.addMethod("checkInvoiceNoSaleOrderReturn", function (value, element) {
		$.ajax({
			url: baseURL+"checkInvoiceNoSaleOrderReturn",
			data: {invoice_no: value},
			type: "post",
			dataType: "json",
			async: false,
			success: function (msg) {
				console.log(msg.status);
				isSuccess = msg.status === true ? true : false;
			}
		});
		console.log(isSuccess);
		return isSuccess;
	},'Invoice Number already exist.');

	$.validator.addMethod("checkInvoiceNoSaleBilling", function (value, element) {
		$.ajax({
			url: baseURL+"checkInvoiceNoSaleBilling",
			data: {invoice_no: value},
			type: "post",
			dataType: "json",
			async: false,
			success: function (msg) {
				console.log(msg.status);
				isSuccess = msg.status === true ? true : false;
			}
		});
		console.log(isSuccess);
		return isSuccess;
	},'Invoice Number already exist.');


	$.validator.addMethod("checkServiceTagNo", function (value, element) {
		$.ajax({
			url: baseURL+"checkServiceTagNo",
			data: {serial_no: value},
			type: "post",
			dataType: "json",
			async: false,
			success: function (msg) {
				console.log(msg.status);
				isSuccess = msg.status === true ? true : false;
			}
		});
		console.log(isSuccess);
		return isSuccess;
	},'Service Tag No already exist.');
	$.validator.addMethod("checkChalanNumber", function (value, element) {
		$.ajax({
			url: baseURL+"checkChalanNumber",
			data: {serial_no: value},
			type: "post",
			dataType: "json",
			async: false,
			success: function (msg) {
				console.log(msg.status);
				isSuccess = msg.status === true ? true : false;
			}
		});
		console.log(isSuccess);
		return isSuccess;
	},'Service Tag No already exist.');
}

function checkInvoiceNoPurchaseOrder(value) {

		let formdata = new FormData();
		formdata.set('invoice_no', value);
		return app.request('checkInvoiceNoPurchaseOrder', formdata);

}
function checkInvoiceNoPurchaseOrderReturn(value) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('invoice_no', value);
		app.request('checkInvoiceNoPurchaseOrderReturn', formdata).then(res => {
			if (res.status === 200) {
				resolve(true);
			} else {
				reject(false);
			}
		}).catch(error => console.log(error));
	});
}
function checkInvoiceNoSalesOrder(value) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('invoice_no', value);
		app.request('checkInvoiceNoSalesOrder', formdata).then(res => {
			if (res.status === 200) {
				resolve(true);
			} else {
				reject(false);
			}
		}).catch(error => console.log(error));
	});
}
function checkInvoiceNoSaleOrderReturn(value) {
	return new Promise(function (resolve, reject) {
		let formdata = new FormData();
		formdata.set('invoice_no', value);
		app.request('checkInvoiceNoSaleOrderReturn', formdata).then(res => {
			if (res.status === 200) {
				resolve(true);
			} else {
				reject(false);
			}
		}).catch(error => console.log(error));
	});
}

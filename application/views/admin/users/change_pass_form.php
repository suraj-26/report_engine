
<?php
defined('BASEPATH') or exit('No direct script access allowed');
$userid = "";
if (isset($this->session->user_session)) {
	// $username=$this->session->user_session->name;
	$userid=$this->session->user_session->id;
}else{
	redirect("");
}

$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content main-content1">
	<section class="section">
		<div class="section-header" style="border-top: 2px solid #891635;">
			<h1>Change Password</h1>
		</div>
		<div class="section-body">
			<div class="align-items-center">
				<div class="col-12 col-md-12">
					<div class="card">
						
						<div class="card-body">
							<div class="table-responsive">
								<div class="dataTables_wrapper no-footer">
									<form id="change_pass_form" >
				<div class="modal-body py-0">
					<div class="card my-0 shadow-none">
						<div class="card-body">
							
							
							<div class="form-group  py-0">
							 <!-- <p><?= $userid ?></p>  -->
							 <input type="hidden" class="form-control" value="<?= $userid ?>" name="id" id="" data-valid="required">
									   
								<label>Old Password</label>
								<input type="password" class="form-control" name="old_pass" id="user_email" data-valid="required"
									   data-msg="Enter username">
							</div>
							<div class="form-group  py-0">
								<label>New Password</label>
								<input type="password" class="form-control" name="new_pass" id="new_pass" data-valid="required"
									   data-msg="Enter Password">
							</div>
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary mr-1" onclick="change_password()" type="button">Submit</button>
					<button class="btn btn-secondary" type="reset">Reset</button>
				</div>
			</form>
								</div>
							</div>
							
							<div class="col-12 col-md-12">
				<h5>Default Access ICU <input type="checkbox" onclick="change_access()"  name="icu_default" id="icu_default"></h5>
				</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('_partials/footer'); ?>
<script type="text/javascript">
$( document ).ready(function() {
   getData();
});
	function change_password()
	{
		// alert("hii");
		var formdata=$("#change_pass_form").serialize();
		// alert(formdata);
		
		
		$.ajax({
            type:'POST',
            url:baseURL+"chnage_pass",
            data:formdata,
            success:function(resp)
            {
                // alert(resp);
                if (resp==2) {
                    // alert('');
                     app.successToast('new password is empty');
                    
                }
                else if (resp==0) {
                	app.successToast('incorrect username and password');
                }
                else
                {
                	app.successToast('update successfully');

                }
            }

        });
		
		
	}
	function change_access(){
		var userid=$("#id").val();
		if($("#icu_default").prop('checked') == true){
			
			var id=2;
		}else{
			var id=1;
		}
			$.ajax({
            type:'POST',
            url:baseURL+"change_access",
			data:{id,userid},
			dataType:'json',
            success:function(resp)
            {
                // alert(resp);
                if (resp.status==200) {
                    // alert('');
                     app.successToast(resp.body);
					 getData();
                    
                }
                else
                {
                	app.successToast(resp.body);

                }
            }

        });
		}
		
		function getData(){
			$.ajax({
            type:'POST',
            url:baseURL+"getDefaultAccess",
			dataType:'json',
            success:function(result)
            {
				
                if (result.status==200) {
                    // alert('');
                    
					$('#icu_default').prop('checked', true);
                    
                }
                else
                {
                	  
					$('#icu_default').prop('checked', false);
					  

                }
                
            }

        });
		}
</script>

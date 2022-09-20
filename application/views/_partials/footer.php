<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><style type="text/css">
	@media (max-width: 575.98px) {
		.main-footer1
		{
			text-align: center;
			margin-bottom: 20px;
		}
	}
</style>
<footer class="main-footer main-footer1">
	<div class="footer-left">
		Copyright &copy; <?= date('Y') ?>
		<div class="bullet"></div>
		Design By <a href="https://gbtech.in/">Gbtech</a>
	</div>
	<div class="footer-right">

	</div>
</footer>
</div>
<!-- wrapper end -->
</div>

<?php $this->load->view('_partials/js'); ?>
<script>
	
	function openSubMenu(id) {
		$("#"+id).toggle();
	}


	$("#openpages").click(function () {
		
		$("#bmr_pages").slideToggle();
});

	function loadAnotherDomain(email,password,url,company_id,user_id) {
		let obj={};
		obj.email=email;
		obj.password=password;
		obj.remote_url=url;
		obj.company_id=company_id;
		obj.user_id=user_id;
		$(location).attr('href', 'https://kep.ecovisrkca.com/BoardLoginController/LoginFromDomain?data=' + encodeURIComponent(JSON.stringify(obj)));
		// $(location).attr('href', 'http://localhost/board_system/BoardLoginController/LoginFromDomain?data=' + encodeURIComponent(JSON.stringify(obj)));
	}

</script>

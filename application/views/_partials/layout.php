<?php
defined('BASEPATH') or exit('No direct script access allowed');

$username = $this->session->user_session->name;
?>
<style>
	.navbar
	{
		left: 0px!important;
	}
	.headerMenu
	{
		color: white;
	}
	.headerMenu:hover
	{
		color: white;
		text-decoration: none;
	}
	.main-sidebar .sidebar-brand
	{
		height: 40px!important;
	}
	.main-content
	{
		padding-left: 280px;
		padding-right: 30px;
	}
	.container
	{
		padding: 20px 40px;
		max-width: 100%!important;
	}
	.navbar-bg
	{
		height: 70px;
	}
	.main-wrapper-1 .section .section-header
	{
		margin-left: 0px;
	}
	.buttons-excel
	{
		background-color: #891635;
	}
</style>
<body>
<div id="app">
	<div class="main-wrapper main-wrapper-1">
		<div class="navbar-bg" style="background: #891635;"></div>
		<nav class="navbar navbar-expand-lg main-navbar">
			<form class="form-inline mr-auto">
				<ul class="navbar-nav mr-3">
<!--					<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>-->
<!--					</li>-->
<!--					<li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i-->
<!--									class="fas fa-search"></i></a></li>-->
					<li>
						<a href="#" class="headerMenu d-flex">
							<img src="<?= base_url()?>assets/images/sai_logo.jpg" alt="Pharma" style="width: 140px;margin-right: 10px;height: 57px;" class="mt-2">
							<h3 class="mt-3"></h3>
						</a>
					</li>
				</ul>

			</form>
			<ul class="navbar-nav navbar-right">
				<li><a class="nav-link nav-link-lg nav-link-user" onclick="loadAnotherDomain('<?php echo $this->session->user_session->user_name ?>','<?php echo $this->session->user_session->password ?>',
							'<?php echo base_url() ?>','<?php echo $this->session->user_session->company_id ?>','<?php echo $this->session->user_session->id ?>')" style="cursor: pointer;color: white;">
						<div class="d-sm-none d-lg-inline-block">Board</div>
					</a></li>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown"
										class="nav-link dropdown-toggle nav-link-lg nav-link-user">
						<div class="d-sm-none d-lg-inline-block">Hi, <?= $username ?></div>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?= base_url('logout'); ?>" class="dropdown-item has-icon text-danger">
							<i class="fas fa-sign-out-alt"></i> Logout
						</a>
					</div>
				</li>
			</ul>
		</nav>

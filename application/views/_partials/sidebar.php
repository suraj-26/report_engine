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

			<li class="<?php echo $this->uri->segment(2) == 'view_departments' ? 'active' : ''; ?>">
				<a class="nav-link"
				   href="<?php echo base_url("admin/view_departments"); ?>">
					<i class="fas fa-users-cog"></i>
					<span>Reliance Engine</span>
				</a>
			</li>

			<li class="<?php echo $this->uri->segment(2) == 'view_departments' ? 'active' : ''; ?>">
				<a class="nav-link"
				   href="<?php echo base_url("admin/view_departments"); ?>">
					<i class="fas fa-users-cog"></i>
					<span>Report Engine</span>
				</a>
			</li>

			<li class="<?php echo $this->uri->segment(1) == 'template_list' ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo base_url(); ?>template_list">
					<i class="fas fa-users-cog"></i>
					<span>Dynamic Engine</span>
				</a>
			</li>

			<li class="<?php echo $this->uri->segment(2) == '204' ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo base_url(); ?>viewForm/204">
					<i class="fas fa-users-cog"></i>
					<span>Template Engine</span>
				</a>
			</li>

			<li class="<?php echo $this->uri->segment(2) == '209' ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo base_url(); ?>viewForm/209">
					<i class="fas fa-users-cog"></i>
					<span>Template Child Group</span>
				</a>
			</li>
		</ul>
	</aside>
</div>

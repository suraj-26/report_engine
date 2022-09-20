<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/mycommon.css">
<?php
defined('BASEPATH') or exit('No direct script access allowed');
$user_id = null;
$role = 0;
$dep = 0;
$patient_mediine_table = "";
$hospital_room_table = "";
$permission_array = $this->session->user_permission;
if (isset($this->session->user_session)) {
    $session_data = $this->session->user_session;
    $user_id = $session_data->id;
    $role = $session_data->roles;
    $dep = $session_data->departments;
    $hospital_room_table = $this->session->user_session->hospital_room_table;//hospital_room_table
    $hospital_bed_table = $this->session->user_session->hospital_bed_table;//hospital_bed_table
    $patient_bed_history_table = $this->session->user_session->patient_bed_history_table;//patient_bed_history_table
    $patient_mediine_table = $this->session->user_session->patient_mediine_table;//dose details table
    $patient_medicine_history_table = $this->session->user_session->patient_medicine_history_table;//dose details table
} else {
    redirect("");
}

if(isset($this->session->user_session->default_access)){
    $default_access = $this->session->user_session->default_access;
}else{
    $default_access = "";
}




?>
<style type="text/css">
    .main-sidebar {
        margin-top: 30px !important;
    }

    .menu-header_section li a {
        color: white !important;
    }

    .main-sidebar .sidebar-menu li a {

        display: contents !important;
        color: black;
        font-weight: bold;
        cursor: pointer!important;
    }

    .main-sidebar .sidebar-menu a {

        text-decoration: none;
        cursor: pointer!important;
    }

    .menu-header_section {
        background: #891635 !important;
        color: white !important;
        padding: 5px 0px 5px 0px !important;
    }

    .menu-header_section1 {
        background: #bbb8b9 !important;
        color: black !important;
        padding: 5px 0px 5px 0px !important;
        font-weight: bold;
    }

    .active1 {
        color: white !important;
    }

    .menu-header_section1 a:hover {
        text-decoration: none;
    }

    .selectPatientInfoA {
        color: black !important;
        font-weight: bold !important;
    }

    .selectPatientInfoB {
        color: white !important;
        font-weight: bold !important;
    }


    .main-sidebar {
        display: block;
    }

    .main-sidebar1 {
        display: none;
    }

    .navbar {
        position: absolute;
    }

    .mobile_three {
        display: none;
    }

    @media (max-width: 800px) {
        .main-sidebar {
            display: none;
        }

        .mobile_search {
            display: none;
        }

        .mobile_three {
            display: block;
            margin-bottom: 0;


        }

        .main-sidebar1 {
            display: block;
            padding: 10px;
        }

        .navbar {
            position: relative;

        }

        .buttons .btn {
            margin: 0 3px 7px 0;
        }
        body.layout-2 .main-content1 {
            padding-top: 5px;
            padding-left: 5px;
            padding-right: 5px;
        }

    }

</style>

<!-- main sidebar menu start -->
<div class="main-sidebar ">
    <!-- brand start -->
    <div class="sidebar-brand">
        <?php if ($role == 1) { ?>
            <a href="<?php echo base_url(); ?>patient_info">Covid-19</a>
        <?php } else { ?>
            <a href="<?php echo base_url(); ?>admin/dashboard">Covid-19</a>
        <?php } ?>
    </div>
    <!-- brand end -->
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="<?php echo base_url(); ?>dist/index">DP</a>
    </div>
    <!-- side menu start -->
    <div class="text-center">
        <ul class="sidebar-menu">
            <div class="text-center">
                <div class="profile-widget-header">
                    <img alt="image" id="patient_profile" src="<?= base_url() ?>assets/img/avatar/avatar-1.png"
                         class="rounded-circle profile-widget-picture"
                         style="box-shadow: 0 4px 8px rgb(0 0 0 / 3%); width: 120px;position: relative;z-index: 1;">
                </div>
                <div class="mt-2">
                    <b style="text-transform: uppercase;"><span id="patient_nameSidebar"></span></b>
                </div>
                <div class="">
                    <small style="text-transform: uppercase;"><span id="patient_adharSidebar"></span></small>
                </div>
            </div>


        </ul>
        <ul class="sidebar-menu" id="form_navigation_menu">
            <a class="a_menu" id="a_menu_0"
               href="<?= base_url("patient/dashboard")?>"
               >
                <li class="mt-2 li_menu menu-header_section" id="li_menu_0">
                    <span>Patient Profile</span>
                </li>
            </a>
            <a class="a_menu" id="a_menu_1" onclick="getSubSection(1,'menu_1')"
               style="color: black">
                <li class="mt-2 li_menu menu-header_section active" id="li_menu_1">
                    <span>Consent Forms</span>
                </li>
            </a>
            <a class="a_menu" id="a_menu_2" onclick="getSubSection(2,'menu_2')"
               style="color: black">
                <li class="mt-2 li_menu menu-header_section1" id="li_menu_2">
                    <span>Surgical Details</span>
                </li>
            </a>
            <a class="a_menu" id="a_menu_3" onclick="getSubSection(3,'menu_3')"
               style="color: black">
                <li class="mt-2 li_menu menu-header_section1" id="li_menu_3">
                    <span>Anesthesia Evaluation</span>
                </li>
            </a>
            <a class="a_menu" id="a_menu_4" onclick="getSubSection(4,'menu_4')"
               style="color: black">
                <li class="mt-2 li_menu menu-header_section1" id="li_menu_4">
                    <span>Anesthesia Events</span>
                </li>
            </a>
            <a class="a_menu" id="a_menu_5" onclick="getSubSection(5,'menu_5')"
               style="color: black">
                <li class="mt-2 li_menu menu-header_section1" id="li_menu_5">
                    <span>Intra Operative Records</span>
                </li>
            </a>
            <a class="a_menu" id="a_menu_6" onclick="getSubSection(6,'menu_6')"
               style="color: black">
                <li class="mt-2 li_menu menu-header_section1" id="li_menu_6">
                    <span>Procedure Details</span>
                </li>
            </a>
            <!-- <a class="a_menu" id="a_menu_3" onclick="getSubSection(3,'menu_3')"
               style="color: black">
                <li class="mt-2 li_menu menu-header_section1" id="li_menu_3">
                    <span>Surgical Safety Checklist</span>
                </li>
            </a> -->

        </ul>
    </div>
    <!-- side menu close -->
</div>


<div class="main-sidebar1 mobile_menu_hide">
    <div class="media mb-1">
        <img alt="image" class="mr-3 rounded-circle" width="30" id="patient_profile1"
             src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png">
        <div class="media-body">
            <div class="media-right" style="margin-top: -3px;"><span id="patient_adharSidebar1"
                                                                     style="font-size: 10px;"></span></div>
            <div class="media-title"><span id="patient_nameSidebar1" style="font-size: 12px;"></span></div>
            <!-- <div class="text-job text-muted"></div> -->
        </div>
    </div>
    <!-- button start -->
    <div class="buttons" id="from_navigation_menu_mobile">


    </div>
    <!-- button close -->
</div>
<!-- main sidebar menu1 end -->



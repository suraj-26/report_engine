<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style type="text/css">
    @media (max-width: 800px) {

        body.layout-2 .main-content {
            padding-top: 5px!important;
            padding-left: 5px!important;
            padding-right: 5px!important;
        }

    }
   .content-wrap section
    {
    	text-align: unset!important;
    }
</style>

<!-- Main Content -->
<div class="main-content" >
	<section class="section section-body_new">
		<div class="section-header card-primary">
			<h1 id="tabHeader_name">-</h1>
		</div>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card">
							<input type="hidden" id="department_id" name="department_id"
						   value="<?= $department_id ?>">
						   <input type="hidden" id="s_type" name="s_type"
						   value="<?= $section_id ?>">
							<input type="hidden" id="section_id" name="section_id"
						   value="">
						   <input type="hidden" id="queryparameter_hidden" name="queryparameter_hidden"
						   value="<?= $queryParam ?>">
                     
						<div class="">
 							<div class="tabs tabs-style-underline">
                                    <nav class="mb-2">
                                       
                                    <ul class="all_menu d-none" id="menu_1">
                                        <li class="tab-current" id="patient_conset_li"><a href="#pcf"  class="icon" onclick="get_forms(110,0,'pcf')"><i class="fa fa-user-injured mr-1 fa_class"></i> <span> Patient Consent Forms</span></a></li>
                                    </ul>
                                    <ul class="all_menu d-none" id="menu_2">
                                        <li class="tab-current" id="surgical_history_li"><a href="#sh" class="icon" onclick="get_forms(115,0,'sh')"><i class="fa fa-file-medical-alt mr-1 fa_class"></i> <span> Surgical History </span></a></li>
                                        <li class="" id="surgical_safety_li"><a href="#ssc" class="icon" onclick="get_forms(119,0,'ssc')"><i class="fa fa-user-shield mr-1 fa_class"></i> <span> Surgical Safety Checklist </span></a></li>
                                    </ul>
                                    <ul class="all_menu d-none" id="menu_3">
                                        <li class="tab-current" id="anesthesia_li"><a href="#pae1" class="icon" onclick="get_forms(125,0,'pae1')"><i class="fa fa-pills mr-1 fa_class"></i> <span> Pre Anesthesia Evaluation (1/2) </span></a></li>
                                        <li class=""><a href="#pae2" class="icon" onclick="get_forms(126,0,'pae2')"><i class="fa fa-pills mr-1 fa_class"></i> <span> Pre Anesthesia Evaluation (2/2) </span></a></li>
                                        <li class=""><a href="#pae3" class="icon" onclick="get_forms(118,0,'pae3')"><i class="fa fa-file-medical-alt mr-1 fa_class"></i> <span> Anesthesia Plan </span></a></li>


                                    </ul>
                                    <ul class="all_menu d-none" id="menu_4">
                                        <li class="tab-current"  id="anesthesia_event_li"><a href="#pae4" class="icon" onclick="get_forms(124,0,'pae4')"><i class="fa fa-file-contract mr-1 fa_class"></i> <span> Anesthesia Events (1/2) </span></a></li>
                                        <li class=""><a href="#pae5" class="icon" onclick="get_forms(123,0,'pae5')"><i class="fa fa-file-contract mr-1 fa_class"></i> <span> Anesthesia Events (2/2) </span></a></li>
                                        <li class=""><a href="#pae6" class="icon" onclick="get_forms(109,0,'pae6')"><i class="fa fa-file-contract mr-1 fa_class"></i> <span> Post Anesthesia Recovery Sheet </span></a></li>
                                        </ul>
                                    <ul class="all_menu d-none" id="menu_5">
                                        <li class="tab-current"  id="intra_oper_li"><a href="#ioar" class="icon" onclick="get_forms(121,0,'ioar')"><i class="fa fa-notes-medical mr-1 fa_class"></i> <span> Intra Operative Anesthesia Record </span></a></li>
                                        <li class=""><a href="#iopr" class="icon" onclick="get_forms(122,0,'iopr')"><i class="fa fa-notes-medical mr-1 fa_class"></i> <span> Intra Operative Parameters Record </span></a></li>
                                    </ul>
                                    <ul class="all_menu d-none" id="menu_6">
                                        <li class="tab-current"  id="ot_procedure_li"><a href="#onp" class="icon" onclick="get_forms(120,0,'onp')"><i class="fa fa-file-contract mr-1 fa_class"></i> <span> OT Notes / Procedure </span></a></li>
                                        <li class=""><a href="#pspo" class="icon" onclick="get_forms(111,0,'pspo')"><i class="fa fa-file-contract mr-1 fa_class"></i> <span> Post Surgery / Procedure Orders </span></a></li>
                                        </ul>
                                    </nav>
                                    <div class="content-wrap">
                                        <section id="pcf" class="content-current">

                                        </section>
                                        <section id="sh"></section>
                                    <section id="ssc">

                                        </section>
                                    <section id="pae1"></section>
                                    <section id="pae2"></section>
                                    <section id="pae3"></section>
                                    <section id="pae4"></section>
                                    <section id="pae5"></section>
                                    <section id="pae6"></section>

                                    <section id="ioar"></section>
                                    <section id="iopr"></section>

                                    <section id="onp"></section>
                                    <section id="pspo"></section>
                                       
                                    </div><!-- /content -->
                            </div><!-- /tabs -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>




<?php $this->load->view('_partials/footer'); ?>
<script type="text/javascript">
	var base_url="<?php echo base_url(); ?>";
$(document).ready(function () {
	 [].slice.call( document.querySelectorAll( '.tabs' ) ).forEach( function( el ) {
            new CBPFWTabs( el );
        });
    var s_type=$('#s_type').val();
        $('#a_menu_'+s_type).click();
        // getSubSection(s_type,'menu_1');
});

function getSubSection(s_type,menu_id)
{
	// console.log(s_type);
        $('#s_type').val(s_type);

	$(".all_menu").addClass('d-none');
	$("#"+menu_id).removeClass('d-none');
	 $(".a_menu").css({
        "text-decoration":'none',
        "color":'black'
    });
    $('.li_menu').addClass('menu-header_section1').removeClass('menu-header_section active');
    $('#a_menu_'+s_type).css({
        "text-decoration":'none',
        "color":'white'
    });
    $("#li_menu_"+s_type).addClass('menu-header_section active').removeClass('menu-header_section1');
	if(parseInt(s_type)==1)
	{
            $('#patient_conset_li').click();
		get_forms(110,0,'pcf');
		$("#tabHeader_name").html('Consent Forms');
	}
	if(parseInt(s_type)==2)
	{
            $('#surgical_history_li').click();
            get_forms(115,0,'sh');
            $("#tabHeader_name").html('Surgical Details');
        }
        if(parseInt(s_type)==3)
        {
            $('#anesthesia_li').click();
            get_forms(125,0,'pae1');
            $("#tabHeader_name").html('Anesthesia Evaluation');
        }
        if(parseInt(s_type)==4)
        {
            $('#anesthesia_event_li').click();
            get_forms(124,0,'pae4');
		$("#tabHeader_name").html('Anesthesia Events ');
	}
        if(parseInt(s_type)==5)
        {
            $('#intra_oper_li').click();
            get_forms(121,0,'ioar');
            $("#tabHeader_name").html('Intra Operative Record');
        }
        if(parseInt(s_type)==6)
        {
            $('#ot_procedure_li').click();
            get_forms(120,0,'onp');
            $("#tabHeader_name").html('Procedures');
        }
}

</script>

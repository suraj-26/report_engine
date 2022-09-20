<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools , Templates
 * and open the template in the editor.
 */

class Global_model extends CI_Model
{
	/*
	 * GET ALL OFFICES
	 */

	public function get_all_firms($firm_id, $user_type)
	{
		if ($user_type == '2') {
//            echo 'Hq';
			try {
				return $this->db->query("select firm_name,firm_id from partner_header_all where firm_activity_status='1' and firm_type='3'  and   reporting_to in (select boss_id from partner_header_all where  firm_id='$firm_id')")->result();
			} catch (Exception $exc) {
				log_message('error', $exc->getMessage());
				return null;
			}
		} else if ($user_type == '5') {
//            echo"HR";
			try {
				return $this->db->query("SELECT (select GROUP_CONCAT(firm_id) from partner_header_all where firm_activity_status='1' and firm_type='3' and FIND_IN_SET(`firm_id`,hr_authority)) as Firm_id,(select GROUP_CONCAT(firm_name) from partner_header_all where firm_activity_status='1' and FIND_IN_SET(`firm_id`,hr_authority)) as firm_name from `user_header_all` where `user_type`='5' and firm_id='$firm_id'")->row();
			} catch (Exception $exc) {
				log_message('error', $exc->getMessage());
				return null;
			}
		} else {
			return null;
		}
	}

	public function enquiry_employee($user_id)
	{
		$where = array(
			'allot_to' => $user_id,
			'status' => 1,
		);
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('enquiry_header_all');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return count($result);
		} else {
			return false;
		}
	}

	//enquiry_generated
	public function enquiry_generated($firm_id)
	{
		$where = "firm_id = '$firm_id' AND status= '0'";
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('enquiry_header_all');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return count($result);
		} else {
			return false;
		}
	}

	//enquiry_submitted
	public function enquiry_submitted($firm_id)
	{
		$where = "firm_id = '$firm_id' AND status= '2'";
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('enquiry_header_all');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return count($result);
		} else {
			return false;
		}
	}

	/*
	 * GET ALL USERS
	 */

	public function get_all_designations($firm_id)
	{ //function to get all designation
		$this->db->select('designation_id,designation_name');
		$this->db->from('designation_header_all');
		$this->db->where('firm_id', $firm_id);
		$query = $this->db->get();
//        print_r($this->db->last_query());
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result;
		} else {
			return FALSE;
		}
	}

	public function user_is_ca($user_id)
	{ //done by pooja
		$this->db->select('user_type');
		$this->db->from('user_header_all');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$result = $query->row();
			$designation_id = $result->user_type;
			return $designation_id;
		} else {
			return FALSE;
		}
	}

	public function get_all_template($firm_id)
	{ //function to get all template.....done by pooja lote
		$this->db->select('template_id, template_path');
		$this->db->from('template_master_data');
		$this->db->where('firm_id', $firm_id);
		$query = $this->db->get();
//        print_r($this->db->last_query());
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result;
		} else {
			return FALSE;
		}
	}

	public function number_of_customer($firm_id)
	{
		$this->db->select('count(customer_id) as num_cust');
		$this->db->from('customer_mapping_data');
		$this->db->where('firm_id', $firm_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$result = $query->row();
			return $result;
		} else {
			return FALSE;
		}
	}

	public function number_of_employee($firm_id)
	{
		$query = $this->db->query("SELECT count(user_id) as num_emp from user_header_all where firm_id = '$firm_id' AND user_type='4'");
		if ($query->num_rows() > 0) {
			$result = $query->row();
			return $result;
		} else {
			return FALSE;
		}
	}

	//enquiry_ca
	public function enquiry_ca($firm_id)
	{
		$where = "firm_id = '$firm_id' AND status IN(0,2)";
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('enquiry_header_all');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return count($result);
		} else {
			return false;
		}
	}

	//enquiry_web_enquiry
	public function enquiry_web_enquiry()
	{
		$where = array(
			'status' => 0,
		);
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('web_customer_service');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return count($result);
		} else {
			return false;
		}
	}

//    public function get_all_enquiry($firm_id) { //function to get all enquiry.....done by pooja lote
//        $this->db->select('enquiry_id');
//        $this->db->from('enquiry_header_all');
//        $this->db->where('firm_id', $firm_id);
//        $query = $this->db->get();
////        print_r($this->db->last_query());
//        if ($query->num_rows() > 0) {
//            $result = $query->result();
//            return $result;
//        } else {
//            return FALSE;
//        }
//    }

	public function get_all_users($firm_id = '')
	{
		$session_data = $this->session->user_session;

		if ($firm_id == '') {

			if ($session_data != null) {

				$firm_id = $session_data->firm_id;
				$user_type = $session_data->user_type;
				if ($user_type == '2') {
					try {
						return $this->db->query("SELECT  u.user_id,  u.user_name ,u.email,p.firm_name from `user_header_all` u join partner_header_all p on u.firm_id=p.firm_id where user_id in
(SELECT user_id from user_header_all where activity_status='1' and user_type!='6' and linked_with_boss_id in (SELECT boss_id from partner_header_all where reporting_to in (SELECT boss_id  from partner_header_all where firm_id ='$firm_id')) ) and user_type='4'  group by email")->result();
					} catch (Exception $exc) {
						log_message('error', $exc->getMessage());
						return array();
					}
				} else if ($user_type == '3') {
					try {
						return $this->db->query("SELECT  u.user_id,  u.user_name ,u.email,p.firm_name from `user_header_all` u join partner_header_all p on u.firm_id=p.firm_id where user_id in
(SELECT user_id from user_header_all where activity_status='1' and  user_type!='6' and linked_with_boss_id in (SELECT boss_id from partner_header_all where reporting_to in (SELECT boss_id  from partner_header_all where firm_id ='$firm_id')) ) and user_type='4'  group by email")->result();
					} catch (Exception $exc) {
						log_message('error', $exc->getMessage());
						return array();
					}
				}
			}
		} else if ($firm_id == '0') {
			$firm_id = $session_data->firm_id;
			$user_type = $session_data->user_type;
			if ($user_type == '2') {

				try {
					return $this->db->query("SELECT  u.user_id,  u.user_name ,u.email,p.firm_name from `user_header_all` u join partner_header_all p on u.firm_id=p.firm_id where user_id in
(SELECT user_id from user_header_all where activity_status='1' and  user_type!='6' and  linked_with_boss_id in (SELECT boss_id from partner_header_all where reporting_to in (SELECT boss_id  from partner_header_all where firm_id ='$firm_id')) )  group by email")->result();
				} catch (Exception $exc) {
					log_message('error', $exc->getMessage());
					return array();
				}
			} else {
				try {
					return $this->db->query("SELECT  u.user_id,  u.user_name ,u.email,p.firm_name from `user_header_all` u join partner_header_all p on u.firm_id=p.firm_id where user_id in
(SELECT user_id from user_header_all where activity_status='1' and  linked_with_boss_id in (SELECT boss_id from partner_header_all where reporting_to in (SELECT boss_id  from partner_header_all where boss_id = (SELECT reporting_to  from partner_header_all where firm_id ='$firm_id'))) )  group by email")->result();
				} catch (Exception $exc) {
					log_message('error', $exc->getMessage());
					return array();
				}
			}
		} else if ($firm_id == "1") {
			$group_firm_id = $session_data->firm_id;
			try {
				return $this->db->query("SELECT * from user_header_all where activity_status='1' and  linked_with_boss_id in  (SELECT boss_id  from partner_header_all where group_company in (SELECT group_company  from partner_header_all where group_company!='' and firm_id ='$group_firm_id'))   group by email")->result();
			} catch (Exception $exc) {
				log_message('error', $exc->getMessage());
				return array();
			}
		} else {
			try {
				return $this->db->query("SELECT u.user_id,u.date_of_joining,u.email,u.mobile_no,u.activity_status,(select designation_name from designation_header_all d where d.designation_id=u.designation_id and d.firm_id=u.firm_id group by designation_name) as designation_name, u.user_name ,p.firm_name from `user_header_all` u join partner_header_all p on u.firm_id=p.firm_id where user_id in
(SELECT user_id from user_header_all where activity_status='1' and  linked_with_boss_id in (SELECT boss_id  from partner_header_all where firm_id ='" . $firm_id . "')) ")->result();
			} catch (Exception $exc) {
				log_message('error', $exc->getMessage());
				return array();
			}
		}
	}

	public function  generate_order($query){
		$order_id = 'O_' . rand(100, 100000);
		$raw= $query;
		$query = str_replace("#id", $order_id, $query);
		$result=$this->db->query($query)->row();
		if(is_object($result)){
			return $this->generate_order($raw);
		}else{
			return $order_id;
		}
	}

	public function generation_id($id)
	{

		if ($id == 'user_id') {


			$user_id = 'U_' . rand(10, 1000);
			$this->db->select('*');
			$this->db->from('user_header_all');
			$this->db->where('user_id', $user_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return $this->generation_id($id);
			} else {
				return $user_id;
			}
		} else if ($id == "firm_id") {

			$result = $this->db->query('SELECT firm_id FROM `partner_header_all` ORDER BY firm_id DESC LIMIT 0, 1');
			if ($result->num_rows() > 0) {
				$data = $result->row();
				return str_pad(++$data->user_id, 5, '0', STR_PAD_LEFT);
			} else {
				return 'Firm_1001';
			}
		} else if ($id == "boss_id") {

			$result = $this->db->query('SELECT boss_id FROM `partner_header_all` ORDER BY boss_id DESC LIMIT 0, 1');
			if ($result->num_rows() > 0) {
				$data = $result->row();
				return str_pad(++$data->user_id, 5, '0', STR_PAD_LEFT);
			} else {
				return 'B_0000000001';
			}
		} else if ($id == "desig_id") { //done by pooja
			$designation_id = 'Desig_' . rand(100, 100000);
			$this->db->select('*');
			$this->db->from('designation_header_all');
			$this->db->where('designation_id', $designation_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return $this->generation_id($id);
			} else {
				return $designation_id;
			}
		} else if ($id == "task_id") { //done by pooja
			$task_id = 'TA' . rand(100, 100000);
			$this->db->select('*');
			$this->db->from('task_header_all');
			$this->db->where('task_id', $task_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return $this->generation_id($id);
			} else {
				return $task_id;
			}
		} else if ($id == "plan_id") { //done by pooja
			$plan_id = 'Plan_' . rand(100, 100000);
			$this->db->select('*');
			$this->db->from('task_planning_all');
			$this->db->where('plan_id', $plan_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return generate_plan_id();
			} else {
				return $plan_id;
			}
		} else if ($id == "checklist_id") { //done by rajashree
			$check_id = 'chk_' . rand(100, 100000);
			$this->db->select('*');
			$this->db->from('checklist_header_all');
			$this->db->where('check_id', $check_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return generation_id($id);
			} else {
				return $check_id;
			}
		} else if ($id == "template_id") { //done by rajashree
			$template_id = 'temp_' . rand(100, 100000);
			$this->db->select('*');
			$this->db->from('template_master_data');
			$this->db->where('template_id', $template_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return generation_id($id);
			} else {
				return $template_id;
			}
		} else if ($id == "contract_id") { //done by akshay
			$cont_id = 'cont_' . rand(100, 100000);
			$this->db->select('*');
			$this->db->from('contract_mast_data');
			$this->db->where('contract_id', $cont_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return generation_id($id);
			} else {
				return $cont_id;
			}
		} else if ($id == 'depart_id') {
			$cont_id = 'dept_' . rand(100, 100000);
			$this->db->select('*');
			$this->db->from('department_data');
			$this->db->where('dep_id', $cont_id);
			$this->db->get();
			if ($this->db->affected_rows() > 0) {
				return generation_id($id);
			} else {
				return $cont_id;
			}
		}
	}

	/*
	 *
	 * array of state
	 */

	public function state()
	{

		return array("Andaman & Nicobar", "Andhra Pradesh", "Arunachal Pradesh",
			"Assam", "Bihar", "Chandigarh", "Chhattisgarh", "Dadra & Nagar Haveli", "Daman & Diu",
			"Delhi", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu & Kashmir", "Jharkhand",
			"Karnataka", "Kerala", "Lakshadweep", "Madhya Pradesh", "Maharashtra", "Manipur",
			"Meghalaya", "Mizoram", "Nagaland", "Orissa", "Pondicherry", "Punjab", "Rajasthan",
			"Sikkim", "Tamil Nadu", "Tripura", "Telangana", "Uttar Pradesh", "Uttaranchal", "West Bengal");
	}

	/*
	 *
	 * array of cities
	 */

	public function city($id)
	{

		$s_a = "";
		switch ($id) {
			case "Andaman & Nicobar":
				$s_a = " Alipur , Andaman Island , Anderson Island , Arainj-Laka-Punga , Austinabad , Bamboo Flat , Barren Island , Beadonabad , Betapur , Bindraban , Bonington , Brookesabad , Cadell Point , Calicut , Chetamale , Cinque Islands , Defence Island , Digilpur , Dolyganj , Flat Island , Geinyale , Great Coco Island , Haddo , Havelock Island , Henry Lawrence Island , Herbertabad , Hobdaypur , Ilichar , Ingoie , Inteview Island , Jangli Ghat , Jhon Lawrence Island , Karen , Kartara , KYD Islannd , Landfall Island , Little Andmand , Little Coco Island , Long Island , Maimyo , Malappuram , Manglutan , Manpur , Mitha Khari , Neill Island , Nicobar Island , North Brother Island , North Passage Island , North Sentinel Island , Nothen Reef Island , Outram Island , Pahlagaon , Palalankwe , Passage Island , Phaiapong , Phoenix Island , Port Blair , Preparis Island , Protheroepur , Rangachang , Rongat , Rutland Island , Sabari , Saddle Peak , Shadipur , Smith Island , Sound Island , South Sentinel Island , Spike Island , Tarmugli Island , Taylerabad , Titaije , Toibalawe , Tusonabad , West Island , Wimberleyganj , Yadita";
				break;
			case "Andhra Pradesh":
				$s_a = " Achampet , Adilabad , Adoni , Alampur , Allagadda , Alur , Amalapuram , Amangallu , Anakapalle , Anantapur , Andole , Araku , Armoor , Asifabad , Aswaraopet , Atmakur , B. Kothakota , Badvel , Banaganapalle , Bandar , Bangarupalem , Banswada , Bapatla , Bellampalli , Bhadrachalam , Bhainsa , Bheemunipatnam , Bhimadole , Bhimavaram , Bhongir , Bhooragamphad , Boath , Bobbili , Bodhan , Chandoor , Chavitidibbalu , Chejerla , Chepurupalli , Cherial , Chevella , Chinnor , Chintalapudi , Chintapalle , Chirala , Chittoor , Chodavaram , Cuddapah , Cumbum , Darsi , Devarakonda , Dharmavaram , Dichpalli , Divi , Donakonda , Dronachalam , East Godavari , Eluru , Eturnagaram , Gadwal , Gajapathinagaram , Gajwel , Garladinne , Giddalur , Godavari , Gooty , Gudivada , Gudur , Guntur , Hindupur , Hunsabad , Huzurabad , Huzurnagar , Hyderabad , Ibrahimpatnam , Jaggayyapet , Jagtial , Jammalamadugu , Jangaon , Jangareddygudem , Jannaram , Kadiri , Kaikaluru , Kakinada , Kalwakurthy , Kalyandurg , Kamalapuram , Kamareddy , Kambadur , Kanaganapalle , Kandukuru , Kanigiri , Karimnagar , Kavali , Khammam , Khanapur (AP) , Kodangal , Koduru , Koilkuntla , Kollapur , Kothagudem , Kovvur , Krishna , Krosuru , Kuppam , Kurnool , Lakkireddipalli , Madakasira , Madanapalli , Madhira , Madnur , Mahabubabad , Mahabubnagar , Mahadevapur , Makthal , Mancherial , Mandapeta , Mangalagiri , Manthani , Markapur , Marturu , Medachal , Medak , Medarmetla , Metpalli , Mriyalguda , Mulug , Mylavaram , Nagarkurnool , Nalgonda , Nallacheruvu , Nampalle , Nandigama , Nandikotkur , Nandyal , Narasampet , Narasaraopet , Narayanakhed , Narayanpet , Narsapur , Narsipatnam , Nazvidu , Nelloe , Nellore , Nidamanur , Nirmal , Nizamabad , Nuguru , Ongole , Outsarangapalle , Paderu , Pakala , Palakonda , Paland , Palmaneru , Pamuru , Pargi , Parkal , Parvathipuram , Pathapatnam , Pattikonda , Peapalle , Peddapalli , Peddapuram , Penukonda , Piduguralla , Piler , Pithapuram , Podili , Polavaram , Prakasam , Proddatur , Pulivendla , Punganur , Putturu , Rajahmundri , Rajampeta , Ramachandrapuram , Ramannapet , Rampachodavaram , Rangareddy , Rapur , Rayachoti , Rayadurg , Razole , Repalle , Saluru , Sangareddy , Sathupalli , Sattenapalle , Satyavedu , Shadnagar , Siddavattam , Siddipet , Sileru , Sircilla , Sirpur Kagaznagar , Sodam , Sompeta , Srikakulam , Srikalahasthi , Srisailam , Srungavarapukota , Sudhimalla , Sullarpet , Tadepalligudem , Tadipatri , Tanduru , Tanuku , Tekkali , Tenali , Thungaturthy , Tirivuru , Tirupathi , Tuni , Udaygiri , Ulvapadu , Uravakonda , Utnor , V.R. Puram , Vaimpalli , Vayalpad , Venkatgiri , Venkatgirikota , Vijayawada , Vikrabad , Vinjamuru , Vinukonda , Visakhapatnam , Vizayanagaram , Vizianagaram , Vuyyuru , Wanaparthy , Warangal , Wardhannapet , Yelamanchili , Yelavaram , Yeleswaram , Yellandu , Yellanuru , Yellareddy , Yerragondapalem , Zahirabad ";
				break;
			case "Arunachal Pradesh":
				$s_a = " Along , Anini , Anjaw , Bameng , Basar , Changlang , Chowkhem , Daporizo , Dibang Valley , Dirang , Hayuliang , Huri , Itanagar , Jairampur , Kalaktung , Kameng , Khonsa , Kolaring , Kurung Kumey , Lohit , Lower Dibang Valley , Lower Subansiri , Mariyang , Mechuka , Miao , Nefra , Pakkekesang , Pangin , Papum Pare , Passighat , Roing , Sagalee , Seppa , Siang , Tali , Taliha , Tawang , Tezu , Tirap , Tuting , Upper Siang , Upper Subansiri , Yiang Kiag ";
				break;
			case "Assam":
				$s_a = " Abhayapuri , Baithalangshu , Barama , Barpeta Road , Bihupuria , Bijni , Bilasipara , Bokajan , Bokakhat , Boko , Bongaigaon , Cachar , Cachar Hills , Darrang , Dhakuakhana , Dhemaji , Dhubri , Dibrugarh , Digboi , Diphu , Goalpara , Gohpur , Golaghat , Guwahati , Hailakandi , Hajo , Halflong , Hojai , Howraghat , Jorhat , Kamrup , Karbi Anglong , Karimganj , Kokarajhar , Kokrajhar , Lakhimpur , Maibong , Majuli , Mangaldoi , Mariani , Marigaon , Moranhat , Morigaon , Nagaon , Nalbari , Rangapara , Sadiya , Sibsagar , Silchar , Sivasagar , Sonitpur , Tarabarihat , Tezpur , Tinsukia , Udalgiri , Udalguri , UdarbondhBarpeta";
				break;
			case "Bihar" :
				$s_a = " Adhaura , Amarpur , Araria , Areraj , Arrah , Arwal , Aurangabad , Bagaha , Banka , Banmankhi , Barachakia , Barauni , Barh , Barosi , Begusarai , Benipatti , Benipur , Bettiah , Bhabhua , Bhagalpur , Bhojpur , Bidupur , Biharsharif , Bikram , Bikramganj , Birpur , Buxar , Chakai , Champaran , Chapara , Dalsinghsarai , Danapur , Darbhanga , Daudnagar , Dhaka , Dhamdaha , Dumraon , Ekma , Forbesganj , Gaya , Gogri , Gopalganj , H.Kharagpur , Hajipur , Hathua , Hilsa , Imamganj , Jahanabad , Jainagar , Jamshedpur , Jamui , Jehanabad , Jhajha , Jhanjharpur , Kahalgaon , Kaimur (Bhabua) , Katihar , Katoria , Khagaria , Kishanganj , Korha , Lakhisarai , Madhepura , Madhubani , Maharajganj , Mahua , Mairwa , Mallehpur , Masrakh , Mohania , Monghyr , Motihari , Motipur , Munger , Muzaffarpur , Nabinagar , Nalanda , Narkatiaganj , Naugachia , Nawada , Pakribarwan , Pakridayal , Patna , Phulparas , Piro , Pupri , Purena , Purnia , Rafiganj , Rajauli , Ramnagar , Raniganj , Raxaul , Rohtas , Rosera , S.Bakhtiarpur , Saharsa , Samastipur , Saran , Sasaram , Seikhpura , Sheikhpura , Sheohar , Sherghati , Sidhawalia , Singhwara , Sitamarhi , Siwan , Sonepur , Supaul , Thakurganj , Triveniganj , Udakishanganj , Vaishali , Wazirganj";
				break;
			case "Chandigarh":
				$s_a = "Chandigarh, Mani Marja";
				break;
			case "Chhattisgarh":
				$s_a = " Ambikapur , Antagarh , Arang , Bacheli , Bagbahera , Bagicha , Baikunthpur , Balod , Balodabazar , Balrampur , Barpalli , Basana , Bastanar , Bastar , Bderajpur , Bemetara , Berla , Bhairongarh , Bhanupratappur , Bharathpur , Bhatapara , Bhilai , Bhilaigarh , Bhopalpatnam , Bijapur , Bilaspur , Bodla , Bokaband , Chandipara , Chhinagarh , Chhuriakala , Chingmut , Chuikhadan , Dabhara , Dallirajhara , Dantewada , Deobhog , Dhamda , Dhamtari , Dharamjaigarh , Dongargarh , Durg , Durgakondal , Fingeshwar , Gariaband , Garpa , Gharghoda , Gogunda , Ilamidi , Jagdalpur , Janjgir , Janjgir-Champa , Jarwa , Jashpur , Jashpurnagar , Kabirdham-Kawardha , Kanker , Kasdol , Kathdol , Kathghora , Kawardha , Keskal , Khairgarh , Kondagaon , Konta , Korba , Korea , Kota , Koyelibeda , Kuakunda , Kunkuri , Kurud , Lohadigundah , Lormi , Luckwada , Mahasamund , Makodi , Manendragarh , Manpur , Marwahi , Mohla , Mungeli , Nagri , Narainpur , Narayanpur , Neora , Netanar , Odgi , Padamkot , Pakhanjur , Pali , Pandaria , Pandishankar , Parasgaon , Pasan , Patan , Pathalgaon , Pendra , Pratappur , Premnagar , Raigarh , Raipur , Rajnandgaon , Rajpur , Ramchandrapur , Saraipali , Saranggarh , Sarona , Semaria , Shakti , Sitapur , Sukma , Surajpur , Surguja , Tapkara , Toynar , Udaipur , Uproda , Wadrainagar";
				break;
			case "Dadra & Nagar Haveli":
				$s_a = " Amal , Amli , Bedpa , Chikhli , Dadra & Nagar Haveli , Dahikhed , Dolara , Galonda , Kanadi , Karchond , Khadoli , Kharadpada , Kherabari , Kherdi , Kothar , Luari , Mashat , Rakholi , Rudana , Saili , Sili , Silvassa , Sindavni , Udva , Umbarkoi , Vansda , Vasona , Velugam ";
				break;
			case "Daman & Diu" :
				$s_a = " Brancavare , Dagasi , Daman , Diu , Magarvara , Nagwa , Pariali , Passo Covo ";
				break;
			case "Delhi":
				$s_a = " Central Delhi , East Delhi , New Delhi , North Delhi , North East Delhi , North West Delhi , South Delhi , South West Delhi , West Delhi ";
				break;
			case "Goa" :
				$s_a = " Canacona , Candolim , Chinchinim , Cortalim , Goa , Jua , Madgaon , Mahem , Mapuca , Marmagao , Panji , Ponda , Sanvordem , Terekhol ";
				break;
			case "Gujarat":
				$s_a = " Ahmedabad , Ahwa , Amod , Amreli , Anand , Anjar , Ankaleshwar , Babra , Balasinor , Banaskantha , Bansada , Bardoli , Bareja , Baroda , Barwala , Bayad , Bhachav , Bhanvad , Bharuch , Bhavnagar , Bhiloda , Bhuj , Billimora , Borsad , Botad , Chanasma , Chhota Udaipur , Chotila , Dabhoi , Dahod , Damnagar , Dang , Danta , Dasada , Dediapada , Deesa , Dehgam , Deodar , Devgadhbaria , Dhandhuka , Dhanera , Dharampur , Dhari , Dholka , Dhoraji , Dhrangadhra , Dhrol , Dwarka , Fortsongadh , Gadhada , Gandhi Nagar , Gariadhar , Godhra , Gogodar , Gondal , Halol , Halvad , Harij , Himatnagar , Idar , Jambusar , Jamjodhpur , Jamkalyanpur , Jamnagar , Jasdan , Jetpur , Jhagadia , Jhalod , Jodia , Junagadh , Junagarh , Kalawad , Kalol , Kapad Wanj , Keshod , Khambat , Khambhalia , Khavda , Kheda , Khedbrahma , Kheralu , Kodinar , Kotdasanghani , Kunkawav , Kutch , Kutchmandvi , Kutiyana , Lakhpat , Lakhtar , Lalpur , Limbdi , Limkheda , Lunavada , M.M.Mangrol , Mahuva , Malia-Hatina , Maliya , Malpur , Manavadar , Mandvi , Mangrol , Mehmedabad , Mehsana , Miyagam , Modasa , Morvi , Muli , Mundra , Nadiad , Nakhatrana , Nalia , Narmada , Naswadi , Navasari , Nizar , Okha , Paddhari , Padra , Palanpur , Palitana , Panchmahals , Patan , Pavijetpur , Porbandar , Prantij , Radhanpur , Rahpar , Rajaula , Rajkot , Rajpipla , Ranavav , Sabarkantha , Sanand , Sankheda , Santalpur , Santrampur , Savarkundla , Savli , Sayan , Sayla , Shehra , Sidhpur , Sihor , Sojitra , Sumrasar , Surat , Surendranagar , Talaja , Thara , Tharad , Thasra , Una-Diu , Upleta , Vadgam , Vadodara , Valia , Vallabhipur , Valod , Valsad , Vanthali , Vapi , Vav , Veraval , Vijapur , Viramgam , Visavadar , Visnagar , Vyara , Waghodia , Wankaner ";
				break;
			case "Haryana" :
				$s_a = " Adampur Mandi , Ambala , Assandh , Bahadurgarh , Barara , Barwala , Bawal , Bawanikhera , Bhiwani , Charkhidadri , Cheeka , Chhachrauli , Dabwali , Ellenabad , Faridabad , Fatehabad , Ferojpur Jhirka , Gharaunda , Gohana , Gurgaon , Hansi , Hisar , Jagadhari , Jatusana , Jhajjar , Jind , Julana , Kaithal , Kalanaur , Kalanwali , Kalka , Karnal , Kosli , Kurukshetra , Loharu , Mahendragarh , Meham , Mewat , Mohindergarh , Naraingarh , Narnaul , Narwana , Nilokheri , Nuh , Palwal , Panchkula , Panipat , Pehowa , Ratia , Rewari , Rohtak , Safidon , Sirsa , Siwani , Sonipat , Tohana , Tohsam , Yamunanagar ";
				break;
			case "Himachal Pradesh":
				$s_a = " Amb , Arki , Banjar , Bharmour , Bilaspur , Chamba , Churah , Dalhousie , Dehra Gopipur , Hamirpur , Jogindernagar , Kalpa , Kangra , Kinnaur , Kullu , Lahaul , Mandi , Nahan , Nalagarh , Nirmand , Nurpur , Palampur , Pangi , Paonta , Pooh , Rajgarh , Rampur Bushahar , Rohru , Shimla , Sirmaur , Solan , Spiti , Sundernagar , Theog , Udaipur , Una";
				break;
			case "Jammu & Kashmir":
				$s_a = " Akhnoor , Anantnag , Badgam , Bandipur , Baramulla , Basholi , Bedarwah , Budgam , Doda , Gulmarg , Jammu , Kalakot , Kargil , Karnah , Kathua , Kishtwar , Kulgam , Kupwara , Leh , Mahore , Nagrota , Nobra , Nowshera , Nyoma , Padam , Pahalgam , Patnitop , Poonch , Pulwama , Rajouri , Ramban , Ramnagar , Reasi , Samba , Srinagar , Udhampur , Vaishno Devi ";
				break;
			case "Jharkhand":
				$s_a = " Bagodar , Baharagora , Balumath , Barhi , Barkagaon , Barwadih , Basia , Bermo , Bhandaria , Bhawanathpur , Bishrampur , Bokaro , Bolwa , Bundu , Chaibasa , Chainpur , Chakardharpur , Chandil , Chatra , Chavparan , Daltonganj , Deoghar , Dhanbad , Dumka , Dumri , Garhwa , Garu , Ghaghra , Ghatsila , Giridih , Godda , Gomia , Govindpur , Gumla , Hazaribagh , Hunterganj , Ichak , Itki , Jagarnathpur , Jamshedpur , Jamtara , Japla , Jharmundi , Jhinkpani , Jhumaritalaiya , Kathikund , Kharsawa , Khunti , Koderma , Kolebira , Latehar , Lohardaga , Madhupur , Mahagama , Maheshpur Raj , Mandar , Mandu , Manoharpur , Muri , Nagarutatri , Nala , Noamundi , Pakur , Palamu , Palkot , Patan , Rajdhanwar , Rajmahal , Ramgarh , Ranchi , Sahibganj , Saraikela , Simaria , Simdega , Singhbhum , Tisri , Torpa ";
				break;
			case "Karnataka":
				$s_a = " Afzalpur , Ainapur , Aland , Alur , Anekal , Ankola , Arsikere , Athani , Aurad , Bableshwar , Badami , Bagalkot , Bagepalli , Bailhongal , Bangalore , Bangalore Rural , Bangarpet , Bantwal , Basavakalyan , Basavanabagewadi , Basavapatna , Belgaum , Bellary , Belthangady , Belur , Bhadravati , Bhalki , Bhatkal , Bidar , Bijapur , Biligi , Chadchan , Challakere , Chamrajnagar , Channagiri , Channapatna , Channarayapatna , Chickmagalur , Chikballapur , Chikkaballapur , Chikkanayakanahalli , Chikkodi , Chikmagalur , Chincholi , Chintamani , Chitradurga , Chittapur , Cowdahalli , Davanagere , Deodurga , Devangere , Devarahippargi , Dharwad , Doddaballapur , Gadag , Gangavathi , Gokak , Gowribdanpur , Gubbi , Gulbarga , Gundlupet , H.B.Halli , H.D. Kote , Haliyal , Hampi , Hangal , Harapanahalli , Hassan , Haveri , Hebri , Hirekerur , Hiriyur , Holalkere , Holenarsipur , Honnali , Honnavar , Hosadurga , Hosakote , Hosanagara , Hospet , Hubli , Hukkeri , Humnabad , Hungund , Hunsagi , Hunsur , Huvinahadagali , Indi , Jagalur , Jamkhandi , Jewargi , Joida , K.R. Nagar , Kadur , Kalghatagi , Kamalapur , Kanakapura , Kannada , Kargal , Karkala , Karwar , Khanapur , Kodagu , Kolar , Kollegal , Koppa , Koppal , Koratageri , Krishnarajapet , Kudligi , Kumta , Kundapur , Kundgol , Kunigal , Kurugodu , Kustagi , Lingsugur , Madikeri , Madugiri , Malavalli , Malur , Mandya , Mangalore , Manipal , Manvi , Mashal , Molkalmuru , Mudalgi , Muddebihal , Mudhol , Mudigere , Mulbagal , Mundagod , Mundargi , Murugod , Mysore , Nagamangala , Nanjangud , Nargund , Narsimrajapur , Navalgund , Nelamangala , Nimburga , Pandavapura , Pavagada , Puttur , Raibag , Raichur , Ramdurg , Ranebennur , Ron , Sagar , Sakleshpur , Salkani , Sandur , Saundatti , Savanur , Sedam , Shahapur , Shankarnarayana , Shikaripura , Shimoga , Shirahatti , Shorapur , Siddapur , Sidlaghatta , Sindagi , Sindhanur , Sira , Sirsi , Siruguppa , Somwarpet , Sorab , Sringeri , Sriniwaspur , Srirangapatna , Sullia , T. Narsipur , Tallak , Tarikere , Telgi , Thirthahalli , Tiptur , Tumkur , Turuvekere , Udupi , Virajpet , Wadi , Yadgiri , Yelburga , Yellapur ";
				break;
			case "Kerala":
				$s_a = " Adimaly , Adoor , Agathy , Alappuzha , Alathur , Alleppey , Alwaye , Amini , Androth , Attingal , Badagara , Bitra , Calicut , Cannanore , Chetlet , Ernakulam , Idukki , Irinjalakuda , Kadamath , Kalpeni , Kalpetta , Kanhangad , Kanjirapally , Kannur , Karungapally , Kasargode , Kavarathy , Kiltan , Kochi , Koduvayur , Kollam , Kottayam , Kovalam , Kozhikode , Kunnamkulam , Malappuram , Mananthodi , Manjeri , Mannarghat , Mavelikkara , Minicoy , Munnar , Muvattupuzha , Nedumandad , Nedumgandam , Nilambur , Palai , Palakkad , Palghat , Pathaanamthitta , Pathanamthitta , Payyanur , Peermedu , Perinthalmanna , Perumbavoor , Punalur , Quilon , Ranni , Shertallai , Shoranur , Taliparamba , Tellicherry , Thiruvananthapuram , Thodupuzha , Thrissur , Tirur , Tiruvalla , Trichur , Trivandrum , Uppala , Vadakkanchery , Vikom , Wayanad ";
				break;
			case "Lakshadweep":
				$s_a = " Agatti Island , Bingaram Island , Bitra Island , Chetlat Island , Kadmat Island , Kalpeni Island , Kavaratti Island , Kiltan Island , Lakshadweep Sea , Minicoy Island , North Island , South Island ";
				break;
			case "Madhya Pradesh":
				$s_a = " Agar , Ajaigarh , Alirajpur , Amarpatan , Amarwada , Ambah , Anuppur , Arone , Ashoknagar , Ashta , Atner , Babaichichli , Badamalhera , Badarwsas , Badnagar , Badnawar , Badwani , Bagli , Baihar , Balaghat , Baldeogarh , Baldi , Bamori , Banda , Bandhavgarh , Bareli , Baroda , Barwaha , Barwani , Batkakhapa , Begamganj , Beohari , Berasia , Berchha , Betul , Bhainsdehi , Bhander , Bhanpura , Bhikangaon , Bhimpur , Bhind , Bhitarwar , Bhopal , Biaora , Bijadandi , Bijawar , Bijaypur , Bina , Birsa , Birsinghpur , Budhni , Burhanpur , Buxwaha , Chachaura , Chanderi , Chaurai , Chhapara , Chhatarpur , Chhindwara , Chicholi , Chitrangi , Churhat , Dabra , Damoh , Datia , Deori , Deosar , Depalpur , Dewas , Dhar , Dharampuri , Dindori , Gadarwara , Gairatganj , Ganjbasoda , Garoth , Ghansour , Ghatia , Ghatigaon , Ghorandogri , Ghughari , Gogaon , Gohad , Goharganj , Gopalganj , Gotegaon , Gourihar , Guna , Gunnore , Gwalior , Gyraspur , Hanumana , Harda , Harrai , Harsud , Hatta , Hoshangabad , Ichhawar , Indore , Isagarh , Itarsi , Jabalpur , Jabera , Jagdalpur , Jaisinghnagar , Jaithari , Jaitpur , Jaitwara , Jamai , Jaora , Jatara , Jawad , Jhabua , Jobat , Jora , Kakaiya , Kannod , Kannodi , Karanjia , Kareli , Karera , Karhal , Karpa , Kasrawad , Katangi , Katni , Keolari , Khachrod , Khajuraho , Khakner , Khalwa , Khandwa , Khaniadhana , Khargone , Khategaon , Khetia , Khilchipur , Khirkiya , Khurai , Kolaras , Kotma , Kukshi , Kundam , Kurwai , Kusmi , Laher , Lakhnadon , Lamta , Lanji , Lateri , Laundi , Maheshwar , Mahidpurcity , Maihar , Majhagwan , Majholi , Malhargarh , Manasa , Manawar , Mandla , Mandsaur , Manpur , Mauganj , Mawai , Mehgaon , Mhow , Morena , Multai , Mungaoli , Nagod , Nainpur , Narsingarh , Narsinghpur , Narwar , Nasrullaganj , Nateran , Neemuch , Niwari , Niwas , Nowgaon , Pachmarhi , Pandhana , Pandhurna , Panna , Parasia , Patan , Patera , Patharia , Pawai , Petlawad , Pichhore , Piparia , Pohari , Prabhapattan , Punasa , Pushprajgarh , Raghogarh , Raghunathpur , Rahatgarh , Raisen , Rajgarh , Rajpur , Ratlam , Rehli , Rewa , Sabalgarh , Sagar , Sailana , Sanwer , Sarangpur , Sardarpur , Satna , Saunsar , Sehore , Sendhwa , Seondha , Seoni , Seonimalwa , Shahdol , Shahnagar , Shahpur , Shajapur , Sheopur , Sheopurkalan , Shivpuri , Shujalpur , Sidhi , Sihora , Silwani , Singrauli , Sirmour , Sironj , Sitamau , Sohagpur , Sondhwa , Sonkatch , Susner , Tamia , Tarana , Tendukheda , Teonthar , Thandla , Tikamgarh , Timarani , Udaipura , Ujjain , Umaria , Umariapan , Vidisha , Vijayraghogarh , Waraseoni , Zhirnia ";
				break;
			case "Maharashtra":
				$s_a = " Achalpur , Aheri , Ahmednagar , Ahmedpur , Ajara , Akkalkot , Akola , Akole , Akot , Alibagh , Amagaon , Amalner , Ambad , Ambejogai , Amravati , Arjuni Merogaon , Arvi , Ashti , Atpadi , Aurangabad , Ausa , Babhulgaon , Balapur , Baramati , Barshi Takli , Barsi , Basmatnagar , Bassein , Beed , Bhadrawati , Bhamregadh , Bhandara , Bhir , Bhiwandi , Bhiwapur , Bhokar , Bhokardan , Bhoom , Bhor , Bhudargad , Bhusawal , Billoli , Brahmapuri , Buldhana , Butibori , Chalisgaon , Chamorshi , Chandgad , Chandrapur , Chandur , Chanwad , Chhikaldara , Chikhali , Chinchwad , Chiplun , Chopda , Chumur , Dahanu , Dapoli , Darwaha , Daryapur , Daund , Degloor , Delhi Tanda , Deogad , Deolgaonraja , Deori , Desaiganj , Dhadgaon , Dhanora , Dharani , Dhiwadi , Dhule , Dhulia , Digras , Dindori , Edalabad , Erandul , Etapalli , Gadhchiroli , Gadhinglaj , Gaganbavada , Gangakhed , Gangapur , Gevrai , Ghatanji , Golegaon , Gondia , Gondpipri , Goregaon , Guhagar , Hadgaon , Hatkangale , Hinganghat , Hingoli , Hingua , Igatpuri , Indapur , Islampur , Jalgaon , Jalna , Jamkhed , Jamner , Jath , Jawahar , Jintdor , Junnar , Kagal , Kaij , Kalamb , Kalamnuri , Kallam , Kalmeshwar , Kalwan , Kalyan , Kamptee , Kandhar , Kankavali , Kannad , Karad , Karjat , Karmala , Katol , Kavathemankal , Kedgaon , Khadakwasala , Khamgaon , Khed , Khopoli , Khultabad , Kinwat , Kolhapur , Kopargaon , Koregaon , Kudal , Kuhi , Kurkheda , Kusumba , Lakhandur , Langa , Latur , Lonar , Lonavala , Madangad , Madha , Mahabaleshwar , Mahad , Mahagaon , Mahasala , Mahaswad , Malegaon , Malgaon , Malgund , Malkapur , Malsuras , Malwan , Mancher , Mangalwedha , Mangaon , Mangrulpur , Manjalegaon , Manmad , Maregaon , Mehda , Mekhar , Mohadi , Mohol , Mokhada , Morshi , Mouda , Mukhed , Mul , Mumbai , Murbad , Murtizapur , Murud , Nagbhir , Nagpur , Nahavara , Nanded , Nandgaon , Nandnva , Nandurbar , Narkhed , Nashik , Navapur , Navi Mumbai , Ner , Newasa , Nilanga , Niphad , Omerga , Osmanabad , Pachora , Paithan , Palghar , Pali , Pandharkawada , Pandharpur , Panhala , Paranda , Parbhani , Parner , Parola , Parseoni , Partur , Patan , Pathardi , Pathari , Patoda , Pauni , Peint , Pen , Phaltan , Pimpalner , Pirangut , Poladpur , Pune , Pusad , Pusegaon , Radhanagar , Rahuri , Raigad , Rajapur , Rajgurunagar , Rajura , Ralegaon , Ramtek , Ratnagiri , Raver , Risod , Roha , Sakarwadi , Sakoli , Sakri , Salekasa , Samudrapur , Sangamner , Sanganeshwar , Sangli , Sangola , Sanguem , Saoner , Saswad , Satana , Satara , Sawantwadi , Seloo , Shahada , Shahapur , Shahuwadi , Shevgaon , Shirala , Shirol , Shirpur , Shirur , Shirwal , Sholapur , Shri Rampur , Shrigonda , Shrivardhan , Sillod , Sinderwahi , Sindhudurg , Sindkheda , Sindkhedaraja , Sinnar , Sironcha , Soyegaon , Surgena , Talasari , Talegaon S.Ji Pant , Taloda , Tasgaon , Thane , Tirora , Tiwasa , Trimbak , Tuljapur , Tumsar , Udgir , Umarkhed , Umrane , Umrer , Urlikanchan , Vaduj , Velhe , Vengurla , Vijapur , Vita , Wada , Wai , Walchandnagar , Wani , Wardha , Warlydwarud , Warora , Washim , Wathar , Yavatmal , Yawal , Yeola , Yeotmal ";
				break;
			case "Manipur":
				$s_a = " Bishnupur , Chakpikarong , Chandel , Chattrik , Churachandpur , Imphal , Jiribam , Kakching , Kalapahar , Mao , Mulam , Parbung , Sadarhills , Saibom , Sempang , Senapati , Sochumer , Taloulong , Tamenglong , Thinghat , Thoubal , Ukhrul ";
				break;
			case "Meghalaya":
				$s_a = " Amlaren , Baghmara , Cherrapunjee , Dadengiri , Garo Hills , Jaintia Hills , Jowai , Khasi Hills , Khliehriat , Mariang , Mawkyrwat , Nongpoh , Nongstoin , Resubelpara , Ri Bhoi , Shillong , Tura , Williamnagar";
				break;
			case "Mizoram":
				$s_a = " Aizawl , Champhai , Demagiri , Kolasib , Lawngtlai , Lunglei , Mamit , Saiha , Serchhip";
				break;
			case "Nagaland":
				$s_a = " Dimapur , Jalukie , Kiphire , Kohima , Mokokchung , Mon , Phek , Tuensang , Wokha , Zunheboto ";
				break;
			case "Orissa":
				$s_a = " Anandapur , Angul , Anugul , Aska , Athgarh , Athmallik , Attabira , Bagdihi , Balangir , Balasore , Baleswar , Baliguda , Balugaon , Banaigarh , Bangiriposi , Barbil , Bargarh , Baripada , Barkot , Basta , Berhampur , Betanati , Bhadrak , Bhanjanagar , Bhawanipatna , Bhubaneswar , Birmaharajpur , Bisam Cuttack , Boriguma , Boudh , Buguda , Chandbali , Chhatrapur , Chhendipada , Cuttack , Daringbadi , Daspalla , Deodgarh , Deogarh , Dhanmandal , Dharamgarh , Dhenkanal , Digapahandi , Dunguripali , G. Udayagiri , Gajapati , Ganjam , Ghatgaon , Gudari , Gunupur , Hemgiri , Hindol , Jagatsinghapur , Jajpur , Jamankira , Jashipur , Jayapatna , Jeypur , Jharigan , Jharsuguda , Jujumura , Kalahandi , Kalimela , Kamakhyanagar , Kandhamal , Kantabhanji , Kantamal , Karanjia , Kashipur , Kendrapara , Kendujhar , Keonjhar , Khalikote , Khordha , Khurda , Komana , Koraput , Kotagarh , Kuchinda , Lahunipara , Laxmipur , M. Rampur , Malkangiri , Mathili , Mayurbhanj , Mohana , Motu , Nabarangapur , Naktideul , Nandapur , Narlaroad , Narsinghpur , Nayagarh , Nimapara , Nowparatan , Nowrangapur , Nuapada , Padampur , Paikamal , Palla Hara , Papadhandi , Parajang , Pardip , Parlakhemundi , Patnagarh , Pattamundai , Phiringia , Phulbani , Puri , Puruna Katak , R. Udayigiri , Rairakhol , Rairangpur , Rajgangpur , Rajkhariar , Rayagada , Rourkela , Sambalpur , Sohela , Sonapur , Soro , Subarnapur , Sunabeda , Sundergarh , Surada , T. Rampur , Talcher , Telkoi , Titlagarh , Tumudibandha , Udala , Umerkote ";
				break;
			case "Pondicherry":
				$s_a = " Bahur , Karaikal , Mahe , Pondicherry , Purnankuppam , Valudavur , Villianur , Yanam ";
				break;
			case "Punjab":
				$s_a = " Abohar , Ajnala , Amritsar , Balachaur , Barnala , Batala , Bathinda , Chandigarh , Dasua , Dinanagar , Faridkot , Fatehgarh Sahib , Fazilka , Ferozepur , Garhashanker , Goindwal , Gurdaspur , Guruharsahai , Hoshiarpur , Jagraon , Jalandhar , Jugial , Kapurthala , Kharar , Kotkapura , Ludhiana , Malaut , Malerkotla , Mansa , Moga , Muktasar , Nabha , Nakodar , Nangal , Nawanshahar , Nawanshahr , Pathankot , Patiala , Patti , Phagwara , Phillaur , Phulmandi , Quadian , Rajpura , Raman , Rayya , Ropar , Rupnagar , Samana , Samrala , Sangrur , Sardulgarh , Sarhind , SAS Nagar , Sultanpur Lodhi , Sunam , Tanda Urmar , Tarn Taran , Zira ";
				break;
			case "Rajasthan":
				$s_a = " Abu Road , Ahore , Ajmer , Aklera , Alwar , Amber , Amet , Anupgarh , Asind , Aspur , Atru , Bagidora , Bali , Bamanwas , Banera , Bansur , Banswara , Baran , Bari , Barisadri , Barmer , Baseri , Bassi , Baswa , Bayana , Beawar , Begun , Behror , Bhadra , Bharatpur , Bhilwara , Bhim , Bhinmal , Bikaner , Bilara , Bundi , Chhabra , Chhipaborad , Chirawa , Chittorgarh , Chohtan , Churu , Dantaramgarh , Dausa , Deedwana , Deeg , Degana , Deogarh , Deoli , Desuri , Dhariawad , Dholpur , Digod , Dudu , Dungarpur , Dungla , Fatehpur , Gangapur , Gangdhar , Gerhi , Ghatol , Girwa , Gogunda , Hanumangarh , Hindaun , Hindoli , Hurda , Jahazpur , Jaipur , Jaisalmer , Jalore , Jhalawar , Jhunjhunu , Jodhpur , Kaman , Kapasan , Karauli , Kekri , Keshoraipatan , Khandar , Kherwara , Khetri , Kishanganj , Kishangarh , Kishangarhbas , Kolayat , Kota , Kotputli , Kotra , Kotri , Kumbalgarh , Kushalgarh , Ladnun , Ladpura , Lalsot , Laxmangarh , Lunkaransar , Mahuwa , Malpura , Malvi , Mandal , Mandalgarh , Mandawar , Mangrol , Marwar-Jn , Merta , Nadbai , Nagaur , Nainwa , Nasirabad , Nathdwara , Nawa , Neem Ka Thana , Newai , Nimbahera , Nohar , Nokha , Onli , Osian , Pachpadara , Pachpahar , Padampur , Pali , Parbatsar , Phagi , Phalodi , Pilani , Pindwara , Pipalda , Pirawa , Pokaran , Pratapgarh , Raipur , Raisinghnagar , Rajgarh , Rajsamand , Ramganj Mandi , Ramgarh , Rashmi , Ratangarh , Reodar , Rupbas , Sadulshahar , Sagwara , Sahabad , Salumber , Sanchore , Sangaria , Sangod , Sapotra , Sarada , Sardarshahar , Sarwar , Sawai Madhopur , Shahapura , Sheo , Sheoganj , Shergarh , Sikar , Sirohi , Siwana , Sojat , Sri Dungargarh , Sri Ganganagar , Sri Karanpur , Sri Madhopur , Sujangarh , Taranagar , Thanaghazi , Tibbi , Tijara , Todaraisingh , Tonk , Udaipur , Udaipurwati , Uniayara , Vallabhnagar , Viratnagar ";
				break;
			case "Sikkim":
				$s_a = " Barmiak , Be , Bhurtuk , Chhubakha , Chidam , Chubha , Chumikteng , Dentam , Dikchu , Dzongri , Gangtok , Gauzing , Gyalshing , Hema , Kerung , Lachen , Lachung , Lema , Lingtam , Lungthu , Mangan , Namchi , Namthang , Nanga , Nantang , Naya Bazar , Padamachen , Pakhyong , Pemayangtse , Phensang , Rangli , Rinchingpong , Sakyong , Samdong , Singtam , Siniolchu , Sombari , Soreng , Sosing , Tekhug , Temi , Tsetang , Tsomgo , Tumlong , Yangang , Yumtang ";
				break;
			case "Tamil Nadu":
				$s_a = " Ambasamudram , Anamali , Arakandanallur , Arantangi , Aravakurichi , Ariyalur , Arkonam , Arni , Aruppukottai , Attur , Avanashi , Batlagundu , Bhavani , Chengalpattu , Chengam , Chennai , Chidambaram , Chingleput , Coimbatore , Courtallam , Cuddalore , Cumbum , Denkanikoitah , Devakottai , Dharampuram , Dharmapuri , Dindigul , Erode , Gingee , Gobichettipalayam , Gudalur , Gudiyatham , Harur , Hosur , Jayamkondan , Kallkurichi , Kanchipuram , Kangayam , Kanyakumari , Karaikal , Karaikudi , Karur , Keeranur , Kodaikanal , Kodumudi , Kotagiri , Kovilpatti , Krishnagiri , Kulithalai , Kumbakonam , Kuzhithurai , Madurai , Madurantgam , Manamadurai , Manaparai , Mannargudi , Mayiladuthurai , Mayiladutjurai , Mettupalayam , Metturdam , Mudukulathur , Mulanur , Musiri , Nagapattinam , Nagarcoil , Namakkal , Nanguneri , Natham , Neyveli , Nilgiris , Oddanchatram , Omalpur , Ootacamund , Ooty , Orathanad , Palacode , Palani , Palladum , Papanasam , Paramakudi , Pattukottai , Perambalur , Perundurai , Pollachi , Polur , Pondicherry , Ponnamaravathi , Ponneri , Pudukkottai , Rajapalayam , Ramanathapuram , Rameshwaram , Ranipet , Rasipuram , Salem , Sankagiri , Sankaran , Sathiyamangalam , Sivaganga , Sivakasi , Sriperumpudur , Srivaikundam , Tenkasi , Thanjavur , Theni , Thirumanglam , Thiruraipoondi , Thoothukudi , Thuraiyure , Tindivanam , Tiruchendur , Tiruchengode , Tiruchirappalli , Tirunelvelli , Tirupathur , Tirupur , Tiruttani , Tiruvallur , Tiruvannamalai , Tiruvarur , Tiruvellore , Tiruvettipuram , Trichy , Tuticorin , Udumalpet , Ulundurpet , Usiliampatti , Uthangarai , Valapady , Valliyoor , Vaniyambadi , Vedasandur , Vellore , Velur , Vilathikulam , Villupuram , Virudhachalam , Virudhunagar , Wandiwash , Yercaud ";
				break;
			case "Tripura":
				$s_a = " Agartala , Ambasa , Bampurbari , Belonia , Dhalai , Dharam Nagar , Kailashahar , Kamal Krishnabari , Khopaiyapara , Khowai , Phuldungsei , Radha Kishore Pur , Tripura ";
				break;
			case "Uttar Pradesh":
				$s_a = " Achhnera , Agra , Akbarpur , Aliganj , Aligarh , Allahabad , Ambedkar Nagar , Amethi , Amiliya , Amroha , Anola , Atrauli , Auraiya , Azamgarh , Baberu , Badaun , Baghpat , Bagpat , Baheri , Bahraich , Ballia , Balrampur , Banda , Bansdeeh , Bansgaon , Bansi , Barabanki , Bareilly , Basti , Bhadohi , Bharthana , Bharwari , Bhogaon , Bhognipur , Bidhuna , Bijnore , Bikapur , Bilari , Bilgram , Bilhaur , Bindki , Bisalpur , Bisauli , Biswan , Budaun , Budhana , Bulandshahar , Bulandshahr , Capianganj , Chakia , Chandauli , Charkhari , Chhata , Chhibramau , Chirgaon , Chitrakoot , Chunur , Dadri , Dalmau , Dataganj , Debai , Deoband , Deoria , Derapur , Dhampur , Domariyaganj , Dudhi , Etah , Etawah , Faizabad , Farrukhabad , Fatehpur , Firozabad , Garauth , Garhmukteshwar , Gautam Buddha Nagar , Ghatampur , Ghaziabad , Ghazipur , Ghosi , Gonda , Gorakhpur , Gunnaur , Haidergarh , Hamirpur , Hapur , Hardoi , Harraiya , Hasanganj , Hasanpur , Hathras , Jalalabad , Jalaun , Jalesar , Jansath , Jarar , Jasrana , Jaunpur , Jhansi , Jyotiba Phule Nagar , Kadipur , Kaimganj , Kairana , Kaisarganj , Kalpi , Kannauj , Kanpur , Karchhana , Karhal , Karvi , Kasganj , Kaushambi , Kerakat , Khaga , Khair , Khalilabad , Kheri , Konch , Kumaon , Kunda , Kushinagar , Lalganj , Lalitpur , Lucknow , Machlishahar , Maharajganj , Mahoba , Mainpuri , Malihabad , Mariyahu , Math , Mathura , Mau , Maudaha , Maunathbhanjan , Mauranipur , Mawana , Meerut , Mehraun , Meja , Mirzapur , Misrikh , Modinagar , Mohamdabad , Mohamdi , Moradabad , Musafirkhana , Muzaffarnagar , Nagina , Najibabad , Nakur , Nanpara , Naraini , Naugarh , Nawabganj , Nighasan , Noida , Orai , Padrauna , Pahasu , Patti , Pharenda , Phoolpur , Phulpur , Pilibhit , Pitamberpur , Powayan , Pratapgarh , Puranpur , Purwa , Raibareli , Rampur , Ramsanehi Ghat , Rasara , Rath , Robertsganj , Sadabad , Safipur , Sagri , Saharanpur , Sahaswan , Sahjahanpur , Saidpur , Salempur , Salon , Sambhal , Sandila , Sant Kabir Nagar , Sant Ravidas Nagar , Sardhana , Shahabad , Shahganj , Shahjahanpur , Shikohabad , Shravasti , Siddharthnagar , Sidhauli , Sikandra Rao , Sikandrabad , Sitapur , Siyana , Sonbhadra , Soraon , Sultanpur , Tanda , Tarabganj , Tilhar , Unnao , Utraula , Varanasi , Zamania ";
				break;
			case "Uttaranchal":
				$s_a = " Almora , Bageshwar , Bhatwari , Chakrata , Chamoli , Champawat , Dehradun , Deoprayag , Dharchula , Dunda , Haldwani , Haridwar , Joshimath , Karan Prayag , Kashipur , Khatima , Kichha , Lansdown , Munsiari , Mussoorie , Nainital , Pantnagar , Partapnagar , Pauri Garhwal , Pithoragarh , Purola , Rajgarh , Ranikhet , Roorkee , Rudraprayag , Tehri Garhwal , Udham Singh Nagar , Ukhimath , Uttarkashi ";
				break;
			case "West Bengal":
				$s_a = " Adra , Alipurduar , Amlagora , Arambagh , Asansol , Balurghat , Bankura , Bardhaman , Basirhat , Berhampur , Bethuadahari , Birbhum , Birpara , Bishanpur , Bolpur , Bongoan , Bulbulchandi , Burdwan , Calcutta , Canning , Champadanga , Contai , Cooch Behar , Daimond Harbour , Dalkhola , Dantan , Darjeeling , Dhaniakhali , Dhuliyan , Dinajpur , Dinhata , Durgapur , Gangajalghati , Gangarampur , Ghatal , Guskara , Habra , Haldia , Harirampur , Harishchandrapur , Hooghly , Howrah , Islampur , Jagatballavpur , Jalpaiguri , Jhalda , Jhargram , Kakdwip , Kalchini , Kalimpong , Kalna , Kandi , Karimpur , Katwa , Kharagpur , Khatra , Krishnanagar , Mal Bazar , Malda , Manbazar , Mathabhanga , Medinipur , Mekhliganj , Mirzapur , Murshidabad , Nadia , Nagarakata , Nalhati , Nayagarh , Parganas , Purulia , Raiganj , Rampur Hat , Ranaghat , Seharabazar , Siliguri , Suri , Takipur , Tamluk";
				break;
			case "Telangana":
				$s_a = " Hyderabad, Warangal,Nizamabad,Khammam,Karimnagar,Ramagundam,Mahabubnagar,Nalgonda,Adilabad,Suryapet,Miryalaguda";
				break;
		}
		return $s_a;
	}

	/*
	 * @param to_email id,subject ,message
	 */

	function sendEmail($to, $subject, $message)
	{
		$from_email = 'value@gbtech.in'; //change this to yours
		$this->load->library('email');
		//configure email settings
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.gbtech.in'; //smtp host name
		$config['smtp_port'] = '587'; //smtp port number 587 on server
		$config['smtp_user'] = $from_email;
		$config['smtp_pass'] = 'gbtech@2019'; //$from_email password
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n"; //use double quotes
		$this->email->initialize($config);

		//send mail
		$this->email->from($from_email, 'RMT Team');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if ($this->email->send()) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * @param number, message
	 */

	public function sendSms($no, $message)
	{

//		//Prepare you post parameters
//		$postData = array(
//			'userid' => "bharat@gbtech.in",
//			"password"=>"Bharat@123",
//			'msg' => $message,
//			'mobnum' => $no,
//			'senderid' => 'TBSLTD'
//		);
//
//
//		$url="http://sms.tivre.com/httppush/send_smsSch.asp";
////		$url = "https://control.msg91.com/sendhttp.php";
//		//$url = "https://control.msg91.com/api/sendhttp.php?authkey=138906AXhOHtw6e588c8fda&mobiles=8319547270&message=Test&sender=MSGIND&route=4&country=91&schtime=2017-01-30%2000:00:00&response=Hey";
//
//		$ch = curl_init();
//		curl_setopt_array($ch, array(
//			CURLOPT_URL => $url,
//			CURLOPT_RETURNTRANSFER => true,
//			CURLOPT_POST => true,
//			CURLOPT_POSTFIELDS => $postData
//			//,CURLOPT_FOLLOWLOCATION => true
//		));
//
//
//		//Ignore SSL certificate verification
////		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
////		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//
//		var_dump($postData);
//		//get response
//		$output = json_decode(curl_exec($ch));
//
//		curl_close($ch);

//		if ($output->type == "success") {
//			return 1;
//		} else {
//			return 0;
//		}
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://sms.tivre.com/httppush/send_smsSch.asp?userid=bharat@gbtech.in&password=Bharat@123&msg=Tivre%20OTP%20code%20is%20323456%20-Tivre&mobnum='.$no.'&senderid=TBSLTD',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Cookie: ASPSESSIONIDQSQCCQBD=GFPNLCMBHODPKBAACHBEFBBI'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	/*
	 * return file name
	 *
	 */

	function check_file_exist($upload_path)
	{
		$filesnames = array();

		foreach (glob('./' . $upload_path . '/*.*') as $file_NAMEEXISTS) {
			$file_NAMEEXISTS;
			$filesnames[] = str_replace("./" . $upload_path . "/", "", $file_NAMEEXISTS);
		}
		return $filesnames;
	}

	function upload_multiple_file_new($upload_path, $inputname, $combination = "")
	{

		$combination = (explode(",", $combination));
//		$check_file_exist = $this->check_file_exist($upload_path);
		if (isset($_FILES[$inputname]) && $_FILES[$inputname]['error'] != '4') {
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = '*';
//            $config['max_size'] = '20000000';    //limit 10000=1 mb
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;

			$this->load->library('upload', $config);

			if (is_array($_FILES[$inputname]['name'])) {
				$count = count($_FILES[$inputname]['name']); // count element
				$files = $_FILES[$inputname];
				$images = array();
				$dataInfo = array();
				if ($count > 0) {
					if (in_array("1", $combination)) {
						for ($j = 0; $j < $count; $j++) {
							$fileName = $files['name'][$j];
							if (in_array($fileName, $check_file_exist)) {
								$response['status'] = 201;
								$response['body'] = $fileName . " Already exist";
								return $response;
							}
						}
					}
					$inputname = $inputname . "[]";
					for ($i = 0; $i < $count; $i++) {
						$_FILES[$inputname]['name'] = $files['name'][$i];
						$_FILES[$inputname]['type'] = $files['type'][$i];
						$_FILES[$inputname]['tmp_name'] = $files['tmp_name'][$i];
						$_FILES[$inputname]['error'] = $files['error'][$i];
						$_FILES[$inputname]['size'] = $files['size'][$i];
						$fileName = $files['name'][$i];
						//get system generated File name CONCATE datetime string to Filename
						if (in_array("2", $combination)) {
							$date = date('Y-m-d H:i:s');
							$randomdata = strtotime($date);
							$fileName = $randomdata . $fileName;
						}
						$images[] = $fileName;
						$config['file_name'] = $fileName;
						$this->upload->initialize($config);
						$up = $this->upload->do_upload($inputname);
						$dataInfo[] = $this->upload->data();
					}
//                    var_dump($dataInfo);

					$file_with_path = array();
					foreach ($dataInfo as $row) {
						$raw_name = $row['raw_name'];
						$file_ext = $row['file_ext'];
						$file_name = $raw_name . $file_ext;
						if(!empty($file_name)){
							$file_with_path[] = $upload_path . "/" . $file_name;
						}
					}
					if (count($file_with_path) > 0) {
						$response['status'] = 200;
						$response['body'] = $file_with_path;
					} else {
						$response['status'] = 201;
						$response['body'] = $file_with_path;
					}
					return $response;
				} else {
					$response['status'] = 201;
					$response['body'] = array();
					return $response;
				}
			} else {
				$response['status'] = 201;
				$response['body'] = array();
				return $response;
			}
		} else {
			$response['status'] = 201;
			$response['body'] = array();
			return $response;
		}
	}



	function upload_multiple_file_new1($upload_path, $inputname, $combination = "")
	{

		$combination = (explode(",", $combination));

		$check_file_exist = $this->check_file_exist($upload_path);
		if (isset($_FILES[$inputname]) && $_FILES[$inputname]['error'] != '4') {

			$files = $_FILES;
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = '*';
//            $config['max_size'] = '20000000';    //limit 10000=1 mb
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;

			$this->load->library('upload', $config);

			if (is_array($_FILES[$inputname]['name'])) {
				$count = count($_FILES[$inputname]['name']); // count element
				$files = $_FILES[$inputname];
				$images = array();
				$dataInfo = array();
				if ($count > 0) {
					if (in_array("1", $combination)) {
						for ($j = 0; $j < $count; $j++) {
							$fileName = $files['name'][$j];
							if (in_array($fileName, $check_file_exist)) {
								$response['status'] = 201;
								$response['body'] = $fileName . " Already exist";
								return $response;
							}
						}
					}
					$inputname = $inputname . "[]";
					for ($i = 0; $i < $count; $i++) {
						$_FILES[$inputname]['name'] = $files['name'][$i];
						$_FILES[$inputname]['type'] = $files['type'][$i];
						$_FILES[$inputname]['tmp_name'] = $files['tmp_name'][$i];
						$_FILES[$inputname]['error'] = $files['error'][$i];
						$_FILES[$inputname]['size'] = $files['size'][$i];
						$fileName = $files['name'][$i];
						//get system generated File name CONCATE datetime string to Filename
						if (in_array("2", $combination)) {
							$date = date('Y-m-d H:i:s');
							$randomdata = strtotime($date);
							$fileName = $randomdata . $fileName;
						}
						$images[] = $fileName;

						$config['file_name'] = $fileName;

						$this->upload->initialize($config);
						$up = $this->upload->do_upload($inputname);
						//var_dump($up);
						$dataInfo[] = $this->upload->data();
					}
					//var_dump($dataInfo);

					$file_with_path = array();
					foreach ($dataInfo as $row) {
						$raw_name = $row['raw_name'];
						$file_ext = $row['file_ext'];
						$file_name = $raw_name . $file_ext;
						if(!empty($file_name)){
							$file_with_path[] = $upload_path . "/" . $file_name;
						}
					}
					if (count($file_with_path) > 0) {
						$response['status'] = 200;
						$response['body'] = $file_with_path;
					} else {
						$response['status'] = 201;
						$response['body'] = $file_with_path;
					}
					return $response;
				} else {
					$response['status'] = 201;
					$response['body'] = array();
					return $response;
				}
			} else {
				$response['status'] = 201;
				$response['body'] = array();
				return $response;
			}
		} else {
			$response['status'] = 201;
			$response['body'] = array();
			return $response;
		}
	}

	function upload_multiple_file($upload_path)
	{

		if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] != '4') {
			$files = $_FILES;
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = '*';
			$config['max_size'] = '50000000';    //limit 10000=1 mb
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;

			$this->load->library('upload', $config);

			if (is_array($_FILES['userfile']['name'])) {
				$count = count($_FILES['userfile']['name']); // count element
				$files = $_FILES['userfile'];
				$images = array();

				foreach ($files['name'] as $key => $image) {
					$_FILES['userfile[]']['name'] = $files['name'][$key];
					$_FILES['userfile[]']['type'] = $files['type'][$key];
					$_FILES['userfile[]']['tmp_name'] = $files['tmp_name'][$key];
					$_FILES['userfile[]']['error'] = $files['error'][$key];
					$_FILES['userfile[]']['size'] = $files['size'][$key];

					$fileName = $image;

					$images[] = $fileName;

					$config['file_name'] = $fileName;

					$this->upload->initialize($config);

					if ($this->upload->do_upload('userfile[]')) {
						$this->upload->data();
					} else {
						return false;
					}
				}

				return $images;
			} else {

				$this->upload->initialize($config);
				$_FILES['userfile']['name'] = $files['userfile']['name'];
				$_FILES['userfile']['type'] = $files['userfile']['type'];
				$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'];
				$_FILES['userfile']['error'] = $files['userfile']['error'];
				$_FILES['userfile']['size'] = $files['userfile']['size'];

				$fileName = preg_replace('/\s+/', '_', str_replace(' ', '_', $_FILES['userfile']['name']));
				$data = array('upload_data' => $this->upload->data());
				if (empty($fileName)) {
					$response['status'] = 203;
					$response['body'] = "file is empty";
					return false;
				} else {
					$file = $this->upload->do_upload('userfile');
					if (!$file) {
						$error = array('upload_error' => $this->upload->display_errors());
						$response['status'] = 204;
						$response['body'] = $files['userfile']['name'] . ' ' . $error['upload_error'];
						return $response;
					} else {
						$response['status'] = 200;
						$response['body'] = $fileName;
						return $response;
					}
				}
			}
		} else {
			$response['status'] = 200;
			$response['body'] = "";
			return $response;
		}
	}

	/*
	 * return file name
	 *
	 */

	function upload_file($upload_path)
	{

		if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] != '4') {
			$files = $_FILES;
			if (is_array($_FILES['userfile']['name'])) {
				$count = count($_FILES['userfile']['name']); // count element
			} else {
				$count = 1;
			}
			$_FILES['userfile']['name'] = $files['userfile']['name'];
			$_FILES['userfile']['type'] = $files['userfile']['type'];
			$_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'];
			$_FILES['userfile']['error'] = $files['userfile']['error'];
			$_FILES['userfile']['size'] = $files['userfile']['size'];
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = '*';
			$config['max_size'] = '500000';    //limit 10000=1 mb
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$fileName = preg_replace('/\s+/', '_', str_replace(' ', '_', $_FILES['userfile']['name']));
			$data = array('upload_data' => $this->upload->data());
			if (empty($fileName)) {
				$response['status'] = 203;
				$response['body'] = "file is empty";
				return false;
			} else {
				$file = $this->upload->do_upload('userfile');
				if (!$file) {
					$error = array('upload_error' => $this->upload->display_errors());
					$response['status'] = 204;
					$response['body'] = $files['userfile']['name'] . ' ' . $error['upload_error'];
					return $response;
				} else {
					$response['status'] = 200;
					$response['body'] = $fileName;
					return $response;
				}
			}
		} else {
			$response['status'] = 200;
			$response['body'] = "";
			return $response;
		}
	}

	public function CheckEmailExist($email)
	{
		$this->db->select('*');
		$this->db->where('email', $email);
		if ($this->db->get('user_header_all')->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	public function CheckGstExist($gst)
	{
		$this->db->select('*');
		$this->db->where('gst_no', $gst);
		if ($this->db->get('client_header_all')->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	public function getMailConfigurtaion($email, $user_id)
	{
		try {
			return $this->db->where(array('email' => $email, 'user_id' => $user_id, 'status' => 1))
				->get('email_configuration')->row();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			return null;
		}
	}

	public function getMailConfigurtaionByUserID($user_id)
	{
		try {
			return $this->db->where(array('user_id' => $user_id, 'status' => 1))->order_by('default', 'desc')
				->get('email_configuration')->result();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			return array();
		}
	}

	function addConfiguration($config)
	{
		try {
			$this->db->trans_start();
			$this->db->insert('email_configuration', $config);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				log_message('info', "insert configuration Transaction Rollback");
				$result = FALSE;
			} else {
				$this->db->trans_commit();
				log_message('info', "insert configuration Transaction Commited");
				$result = TRUE;
			}
			$this->db->trans_complete();
		} catch (Exception $exc) {
			log_message('error', $exc->getMessage());
			$result = FALSE;
		}
		return $result;
	}

}

?>

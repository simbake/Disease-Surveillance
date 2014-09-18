<?php
class DNR_Facilities extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['report_view'] = "dnr_facilities_v";
		$this -> base_params($data);
	}
	
	public function view_list(){ 
		$year = $this->input->post("year");
		$epiweek = $this->input->post("epiweek");
		$district = $this -> session -> userdata('district_province_id');
		$dnr_facilities = Facilities::getDNRFacilities($year,$epiweek,$district);
		$data['dnr_facilities'] = $dnr_facilities; 
		$data['report_view'] = "dnr_facilities_v";
		$data['small_title'] = "DNR Facilities in ".$year." Epiweek ".$epiweek;
		$this -> base_params($data);
	}
 

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "dnr_facilities";
		$data['title'] = "System Reports";
		
		$data['content_view'] = "reports_v";
		$data['banner_text'] = "'DNR' Facilities";
		$data['link'] = "reports_management";

		$this -> load -> view('template', $data);
	}

}

<?php
class Facility_Reports extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['quality_view'] = "facility_reports_v";
		$this -> base_params($data);
	}

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "facility_reports";
		$data['title'] = "Data Quality";
		$data['content_view'] = "data_quality_v";
		$data['banner_text'] = "Facility Reports";
		$data['link'] = "data_quality_management";
		$this -> load -> view('template', $data);
	}

	public function get_list() {
		$year = $this -> input -> post('year');
		$epiweek = $this -> input -> post('epiweek');
		$access_level=$this->session->userdata('user_indicator');
		if($access_level=='county_clerk'){
			 $county_id=$this->session->userdata('county_id'); 
			 $data['reports'] = Facility_Surveillance_Data::getReports($year, $epiweek,$county_id);
		
		}
		else if($access_level=='district_clerk'){
		$district = $this -> session -> userdata('district_province_id');
		$data['reports'] = Facility_Surveillance_Data::getReports($year, $epiweek,$district);
		}
		$data['quality_view'] = "facility_reports_listing_v";
		$data['total_diseases'] = Disease::getTotal();
		$data['epiweek'] = $epiweek;
		$data['year'] = $year;
		$data['small_title'] = "List of all Facility Reports in " . $year . " Epiweek " . $epiweek;
		$this -> base_params($data);
	} 

}

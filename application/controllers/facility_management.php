<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Facility_Management extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this -> load -> library('pagination');
	}

	public function index() {
		$this -> whole_list();
	}

	public function whole_list() {
		$facilities = Facilities::getPagedFacilities();
		$data['facilities'] = $facilities;
		$data['banner_text'] = "All Facilities";
		$data['title'] = "Facility Management::All Facilities";
		$data['module_view'] = "view_facilities_view";
		$this -> new_base_params($data);
	}

	public function district_list($offset = 0) {
		$district = $this -> session -> userdata("district_province_id");
		//echo "district:".$district;
		$items_per_page = 20;
		$number_of_facilities = Facilities::getTotalNumber($district);
		$facilities = Facilities::getPagedFacilities($offset, $items_per_page, $district);
		
		$access_level=$this->session->userdata("user_indicator");
		if($access_level=="county_clerk"){
		$county_id=$this->session->userdata("county_id");
		$county_name=County::getNames($county_id);
		//echo $county_name;
		foreach($county_name as $county);
		
		$number_of_facilities = Facilities::getTotalNumberCounty($county['Name']);
		$facilities = Facilities::getPagedFacilitiesCounty($offset, $items_per_page, $county['Name']);
		}
		if ($number_of_facilities > $items_per_page) {
			$config['base_url'] = base_url() . "facility_management/district_list/";
			$config['total_rows'] = $number_of_facilities;
			$config['per_page'] = $items_per_page;
			$config['uri_segment'] = 3;
			$config['num_links'] = 5;
			$this -> pagination -> initialize($config);
			$data['pagination'] = $this -> pagination -> create_links();
		}

		$data['facilities'] = $facilities;
		$data['quality_view'] = "view_district_facilities";
		$data['styles'] = array("pagination.css");
		$data['quick_link'] = "facility_management";
		$data['title'] = "Data Quality";
		$data['content_view'] = "data_quality_v";
		$data['banner_text'] = "My Facilities";
		$data['link'] = "data_quality_management";
		$this -> load -> view('template_v', $data);
	}

	public function view_list() {
		$additional_facilities = new Additional_Facilities();
		$returned = $additional_facilities -> getExtraFacilities($this -> session -> userdata('district_province_id'));
		$data['facilities'] = $returned;
		$data['title'] = "Facility Management::My Additional Facilities";
		$data['content_view'] = "view_extra_facilities_view";
		$data['link'] = "facility_management";
		$this -> base_params($data);
	}

	public function add() {
		$data['title'] = "Facility Management::Add Extra Facility";
		$data['content_view'] = "add_extra_facility_view";
		$data['quick_link'] = "new_extra_facility";
		$this -> base_params($data);
	}

	public function new_facility() {
		$data['quality_view'] = "add_facility_view";
		$data['styles'] = array("pagination.css");
		$data['quick_link'] = "facility_management";
		$data['sub_link'] = "add_facility";
		$data['title'] = "Data Quality";
		$data['content_view'] = "data_quality_v";
		$data['banner_text'] = "Add Facility";
		$data['link'] = "data_quality_management";
		$this -> load -> view('template_v', $data);
	}

	public function search_facility() {
		$data['banner_text'] = "Search Facility";
		$data['title'] = "Facility Management::Search Facility";
		$data['module_view'] = "search_facility_view";
		$this -> new_base_params($data);

	}

	public function search() {
		$search_term = $this -> input -> post('search');
		$data['facilities'] = Facilities::search($search_term);
		$data['search_term'] = $search_term;
		$data['quality_view'] = "search_facilities_result_view";
		$data['styles'] = array("pagination.css");
		$data['quick_link'] = "facility_management";
		$data['sub_link'] = "add_facility";
		$data['title'] = "Data Quality";
		$data['content_view'] = "data_quality_v";
		$data['banner_text'] = "Add Facility";
		$data['link'] = "data_quality_management";
		$this -> load -> view('template_v', $data);
	}

	public function facility_search() {
		$search_term = $this -> input -> post('search');
		$data['facilities'] = Facilities::search($search_term);
		$data['banner_text'] = "'" . $search_term . "' Results";
		$data['title'] = "Facility Management::Searches Facilities";
		$data['module_view'] = "view_facilities_view";
		$this -> new_base_params($data);
	}

	//This method is for saving the details of additional facilities
	public function change_ownership($code) {
		$district = $this -> session -> userdata("district_province_id");
		$facility = Facilities::getFacility($code);
		$facility -> district = $district;
		$facility -> save();
		redirect("facility_management/district_list");
	}

	public function change_reporting($code, $reporting) {
		$facility = Facilities::getFacility($code);
		$facility -> reporting = $reporting;
		$facility -> save();
		redirect("facility_management");
	}

	private function base_params($data) {
		$data['scripts'] = array("jquery-ui.js", "tab.js");
		$data['styles'] = array("jquery-ui.css", "tab.css");

		$this -> load -> view('template_v', $data);

	}

	private function new_base_params($data) {
		$data['content_view'] = "admin_view";
		$data['quick_link'] = "facility_management";
		$data['link'] = "system_administration";
		$this -> load -> view('template_v', $data);

	}

	public function get_district_facilities($district) {
		$facilities = Facilities::getDistrictFacilities($district);
		$facilities_array = array();
		$counter = 0;
		foreach ($facilities as $facility) {
			$facility_details = array("facility_code" => utf8_encode($facility -> facilitycode), "name" => utf8_encode($facility -> name));
			$facilities_array[$counter] = $facility_details;
			$counter++;
		}
		echo json_encode($facilities_array);
	}

}

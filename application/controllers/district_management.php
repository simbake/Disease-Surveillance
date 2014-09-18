<?php

class District_Management extends MY_Controller {
	function __construct() {

		parent::__construct();
		$this -> load -> library('pagination');
	}

	public function index() {
		$this -> view_list();
	}

	public function view_list($offset = 0) {
		$items_per_page = 15;
		$number_of_districts = District::getTotalNumber();
		$districts = District::getPagedDistricts($offset, $items_per_page);
		if ($number_of_districts > $items_per_page) {
			$config['base_url'] = base_url() . "district_management/view_list/";
			$config['total_rows'] = $number_of_districts;
			$config['per_page'] = $items_per_page;
			$config['uri_segment'] = 3;
			$config['num_links'] = 5;
			$this -> pagination -> initialize($config);
			$data['pagination'] = $this -> pagination -> create_links();
		}

		$data['districts'] = $districts;
		$data['title'] = "District Management::All My Districts";
		$data['module_view'] = "view_districts_view";
		$this -> base_params($data);
	}

	public function add() {
		$data['title'] = "District Management::Add New District"; 
		$data['quick_link'] = "new_district";
		$data['module_view'] = "add_district_view";
		$data['quick_link'] = "new_district";
		$data['provinces'] = Province::getAll();
		$data['counties'] = County::getAll();
		$this -> base_params($data);
	}

	public function save() {
		$name = $this -> input -> post("name");
		$province = $this -> input -> post("province");
		$county = $this -> input -> post("county");
		$latitude = $this -> input -> post("latitude");
		$longitude = $this -> input -> post("longitude");
		 
		$district_id = $this -> input -> post("district_id");

		//Check if we are in editing mode first; if so, retrieve the edited record. if not, create a new one!
		if (strlen($district_id) > 0) {
			$district = District::getDistrict($district_id);
			$district = $district[0];
		 
		} else {
			$district = new District();
		}

		$district -> Name = $name;
		$district -> Province = $province;
		$district -> County = $county;
		$district -> Latitude = $latitude;
		$district -> Longitude = $longitude;
		$district -> save();
		redirect("district_management");
	}

	public function change_availability($code, $availability) {
		$district = District::getDistrict($code);
		$district = $district[0];
		$district -> Disabled = $availability;
		$district -> save();
		redirect("district_management");
	}

	public function edit_district($code) {
		$district = District::getDistrict($code); 
		$data['district'] = $district[0];
		$data['title'] = "District Management::Edit " . $district -> name . " District";
		$data['module_view'] = "add_district_view";
		$data['quick_link'] = "new_district";
		$data['provinces'] = Province::getAll();
		$data['counties'] = County::getAll();
		$this -> base_params($data);
	}

	private function base_params($data) {
		$data['scripts'] = array("jquery-ui.js", "tab.js");
		$data['styles'] = array("jquery-ui.css", "tab.css","pagination.css");
		$data['quick_link'] = "district_management";
		$data['link'] = "admin_management";
		$data['content_view'] = "admin_view";
		$data['banner_text'] = "Districts Listing";
		$this -> load -> view('template', $data);

	}

}

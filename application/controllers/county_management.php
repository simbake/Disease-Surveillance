<?php

class County_Management extends MY_Controller {
	function __construct() {

		parent::__construct();
		$this -> load -> library('pagination');
	}

	public function index() {
		$this -> view_list();
	}

	public function view_list($offset = 0) {
		$items_per_page = 15;
		$number_of_counties = County::getTotalNumber();
		$counties = County::getPagedCounties($offset, $items_per_page);
		if ($number_of_counties > $items_per_page) {
			$config['base_url'] = base_url() . "county_management/view_list/";
			$config['total_rows'] = $number_of_counties;
			$config['per_page'] = $items_per_page;
			$config['uri_segment'] = 3;
			$config['num_links'] = 5;
			$this -> pagination -> initialize($config);
			$data['pagination'] = $this -> pagination -> create_links();
		}

		$data['counties'] = $counties;
		$data['title'] = "County Management::All My Counties";
		$data['module_view'] = "view_counties_view";
		$this -> base_params($data);
	}

	public function add() {
		$data['title'] = "County Management::Add New County";
		$data['quick_link'] = "new_county";
		$data['module_view'] = "add_county_view";
		$data['quick_link'] = "new_county";
		$data['provinces'] = Province::getAll();
		$this -> base_params($data);
	}

	public function save() {
		$name = $this -> input -> post("name");
		$province = $this -> input -> post("province");
		$latitude = $this -> input -> post("latitude");
		$longitude = $this -> input -> post("longitude");

		$county_id = $this -> input -> post("county_id");

		//Check if we are in editing mode first; if so, retrieve the edited record. if not, create a new one!
		if (strlen($county_id) > 0) {
			$county = County::getCounty($county_id);
			$county = $county[0];

		} else {
			$county = new County();
		}

		$county -> Name = $name;
		$county -> Province = $province;
		$county -> Latitude = $latitude;
		$county -> Longitude = $longitude;
		$county -> save();
		redirect("county_management");
	}

	public function change_availability($code, $availability) {
		$county = County::getCounty($code);
		$county = $county[0];
		$county -> Disabled = $availability;
		$county -> save();
		redirect("county_management");
	}

	public function edit_county($code) {
		$county = County::getCounty($code);
		$data['county'] = $county[0];
		$data['title'] = "County Management::Edit " . $county -> Name;
		$data['module_view'] = "add_county_view";
		$data['quick_link'] = "new_county";
		$data['provinces'] = Province::getAll();
		$this -> base_params($data);
	}

	private function base_params($data) {
		$data['scripts'] = array("jquery-ui.js", "tab.js");
		$data['styles'] = array("jquery-ui.css", "tab.css", "pagination.css");
		$data['quick_link'] = "county_management";
		$data['link'] = "admin_management";
		$data['content_view'] = "admin_view";
		$data['banner_text'] = "Counties Listing";
		$this -> load -> view('template', $data);

	}

}

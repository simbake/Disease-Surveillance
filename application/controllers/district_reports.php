<?php
class District_Reports extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['quality_view'] = "district_reports_v";
		$this -> base_params($data);
	}

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "district_reports";
		$data['title'] = "Data Quality";
		$data['content_view'] = "data_quality_v";
		$data['banner_text'] = "District Reports";
		$data['link'] = "data_quality_management";
		$this -> load -> view('template', $data);
	}

	public function get_list() {
		$year = $this -> input -> post('year');
		$epiweek = $this -> input -> post('epiweek');
		$data['reports'] = Surveillance::getReports($year, $epiweek);
		$data['quality_view'] = "district_reports_listing_v";
		$data['total_diseases'] = Disease::getTotal();
		$data['epiweek'] = $epiweek;
		$data['year'] = $year;
		$data['small_title'] = "List of all District Reports in " . $year . " Epiweek " . $epiweek;
		$this -> base_params($data);
	} 

}

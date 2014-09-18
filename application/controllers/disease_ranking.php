<?php
class Disease_Ranking extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['module_view'] = "disease_ranking_v";
		$data['diseases'] = Disease::getAll();
		$this -> base_params($data);
	}

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css","pagination.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "disease_ranking";
		$data['title'] = "Data Quality";
			$data['content_view'] = "admin_view"; 
		$data['banner_text'] = "Disease Ranking";
		$data['link'] = "admin_management";
		$this -> load -> view('template', $data);
	}
 

	public function get_list($year = 0, $epiweek = 0, $disease = 0, $offset = 0) {
		$this -> load -> library('pagination');
		if ($year == 0 && $epiweek == 0) {
			$year = $this -> input -> post('year');
			$epiweek = $this -> input -> post('epiweek');
			$disease = $this -> input -> post('disease');
		}
		$items_per_page = 15;
		$number_of_facilities = Facility_Surveillance_Data::getTotalRankedReports($year, $epiweek, $disease);
		$facilities = Facility_Surveillance_Data::getRankedReports($year, $epiweek, $disease, $offset, $items_per_page);
		
		if ($number_of_facilities > $items_per_page) {
			$config['base_url'] = base_url() . "disease_ranking/get_list/" . $year . "/" . $epiweek . "/" . $disease . "/";
			$config['total_rows'] = $number_of_facilities;
			$config['per_page'] = $items_per_page;
			$config['uri_segment'] = 6;
			$config['num_links'] = 5;
			$this -> pagination -> initialize($config);
			$data['pagination'] = $this -> pagination -> create_links();
		}
		$disease_object = Disease::getDisease($disease);
		$data['reports'] = $facilities;
		$data['total_diseases'] = Disease::getTotal();
		$data['banner_text'] = "All Facilities";
		$data['epiweek'] = $epiweek;
		$data['year'] = $year;
		$data['module_view'] = "disease_ranking_listing_v"; 
		$data['small_title'] = $disease_object->Name." Reports for " . $year . " Epiweek " . $epiweek;
		$this -> base_params($data);
	}

}

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
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
		$this -> load -> view('template_v', $data);
	}
 

	public function get_list($year = 0, $epiweek = 0, $disease = 0) {
		if ($year == 0 && $epiweek == 0) {
			$year = $this -> input -> post('year');
			$epiweek = $this -> input -> post('epiweek');
			$disease = $this -> input -> post('disease');
		}
		
		$number_of_facilities = Facility_Surveillance_Data::getTotalRankedReports($year, $epiweek, $disease);
		$facilities = Facility_Surveillance_Data::getRankedReports($year, $epiweek, $disease);
		
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

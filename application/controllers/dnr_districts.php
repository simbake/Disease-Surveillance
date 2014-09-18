<?php
class DNR_Districts extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['report_view'] = "dnr_districts_v";
		$this -> base_params($data);
	}
	
	public function view_list(){ 
		$year = $this->input->post("year");
		$epiweek = $this->input->post("epiweek");
		$dnr_districts = District::getDNRDistricts($year,$epiweek);
		$provinces = Province::getAll();
		$data['provinces'] = $provinces;
		$data['dnr_districts'] = $dnr_districts; 
		$data['report_view'] = "dnr_districts_v";
		$data['small_title'] = "DNR Districts in ".$year." Epiweek ".$epiweek;
		$this -> base_params($data);
	}
 

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "dnr_districts";
		$data['title'] = "System Reports";
		
		$data['content_view'] = "reports_v";
		$data['banner_text'] = "'DNR' Districts";
		$data['link'] = "reports_management";

		$this -> load -> view('template', $data);
	}

}

<?php
class Analyses_Management extends MY_Controller {
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$data = array();
		$this -> base_params($data);
	}

	public function base_params($data) {
		$data['title'] = "Data Analyses";
		$data['content_view'] = "management_dashboard_v"; 
		$data['scripts'] = array("FusionCharts/FusionCharts.js");
		$data['diseases'] = Disease::getAll();
		$data['banner_text'] = "Data Analyses";
		$data['link'] = "analyses_management";
		$this -> load -> view("template", $data);
	}

}

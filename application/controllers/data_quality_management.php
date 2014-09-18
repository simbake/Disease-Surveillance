<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Data_Quality_Management extends MY_Controller {
	function __construct() {
		parent::__construct();
	}

	public function index() {
		redirect("facility_reports");
	}

	public function base_params($data) {
		$data['title'] = "Data Quality";
		$data['content_view'] = "data_quality_v";
		$data['banner_text'] = "Data Quality";
		$data['link'] = "data_quality_management";
		$this -> load -> view("template", $data);
	}

}

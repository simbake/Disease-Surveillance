<?php
class Zoonotic_Data_Management extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() { 
		$this -> add();
	}

	public function add() {
		$data['title'] = "Zoonotic Data";
		$data['content_view'] = "zoonotic_data_add_v";
		$data['banner_text'] = "Zoonotic Data";
		$data['link'] = "submissions_management";
		$data['quick_link'] = "zoonotic_data_management";
		$this -> load -> view("template", $data);
	}

}

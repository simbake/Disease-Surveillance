<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Admin_Management extends MY_Controller {
	function __construct() {

		parent::__construct();
		$this -> load -> library('pagination');
	}

	public function index() {
		redirect("district_management");
	}

}

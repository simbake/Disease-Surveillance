<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Home_Controller extends MY_Controller {
	function __construct() {
		parent::__construct();
	}

	public function index() {

		$this -> home();
	}

	public function home() {
		//
		$rights = User_Right::getRights($this -> session -> userdata('access_level'));
		$menu_data = array();
		$menus = array();
		$counter = 0;
		foreach ($rights as $right) {
			$menu_data['menus'][$right -> Menu] = $right -> Access_Type;
			$menus['menu_items'][$counter]['url'] = $right -> Menu_Item -> Menu_Url;
			$menus['menu_items'][$counter]['text'] = $right -> Menu_Item -> Menu_Text;
			$counter++;
		}
		$this -> session -> set_userdata($menu_data);
		$this -> session -> set_userdata($menus);
		$data['title'] = "System Home";
		$data['content_view'] = "data_analyses_v";
		$data['diseases'] = Disease::getAllObjects();
		$data['districts'] = District::getAll();
		$data['scripts'] = array("assets/FusionCharts/FusionCharts.js");
		$data['banner_text'] = "System Home";
		$indicator = $this -> session -> userdata('user_indicator');
		if($indicator == "district_clerk"){
			$data['districts'] = District::getDistrict($this -> session -> userdata('district_province_id'));
		}
		else if($indicator == "county_clerk"){
			//echo "County_clerk:".$this -> session -> userdata('county_id');
			$data['districts'] = District::getDistrictCounty($this -> session -> userdata('county_id'));
		}
		$data['link'] = "home";  
		$this -> load -> view("template_v", $data);
	}

	function management_dashboard() {
		$data['title'] = "System Home";
		$data['content_view'] = "management_dashboard_v"; 
		$data['banner_text'] = "System Home";
		$data['link'] = "home";
		$data['scripts'] = array("assets/FusionCharts/FusionCharts.js");
		$this -> load -> view("template_v", $data);
	}

}

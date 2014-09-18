<?php
class MOH_503 extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() { 
		$this -> moh_503_tb();
	}
	
	public function add_view(){
	$access_level=$this->session->userdata("access_level");
	if($access_level=="7"){
		$facilities=Facilities::getAll();

	}
	else{
	$district=$this->session->userdata("district_province_id");
	$facilities=Facilities::getDistrictFacilities($district);
	}
	    $diseases = Disease::getAllObjects();
		
		//print_r($facilities);
		$data['diseases'] = $diseases;
		$data['facilities'] = $facilities;
	    $data['title'] = "MOH 503";
		$data['content_view'] = "moh503_v";
		$data['banner_text'] ="";// "Line Listing Form";
		$data['link'] = "moh_503";
		$data['quick_link'] = "test";
		$this -> load -> view("template", $data);
	
	}
	
	public function save(){
	//echo "save controller function";
	    $user_id=$this->session->userdata("user_id");
		$district=$this->session->userdata("district_province_id");
		
		//print_r($district);
	    $lg = new moh_503_db();
		$lg -> user_id = $user_id;
		$lg -> names = $_POST['names'];
		$lg -> facility = $_POST['facility'];
		/*foreach($district as $dist){
		$lg -> district = $dist->district;
		}*/
		$lg -> district = $district;
		$lg -> disease = $_POST['disease'];
		$lg -> patient = $_POST['patient'];
		$lg -> physical_add = $_POST['phy_add'];
		$lg -> phone = $_POST['telephone'];
		$lg -> age = $_POST['age'];
		$lg -> sex = $_POST['sex'];
		$lg -> date_seen = $_POST['seen_date'];
		$lg -> date_onset = $_POST['onset_date'];
		$lg -> vaccine_no = $_POST['vaccine_no'];
		$lg -> specimen_taken = $_POST['specimen_taken'];
		if($_POST['specimen_taken']=="Yes"){
		$lg -> date_taken = $_POST['specimen_date'];
		$lg -> type = $_POST['specimen_type'];
		$lg -> results = $_POST['specimen_results'];
		$lg -> status = $_POST['status'];
		}
		$lg -> comments = $_POST['comments'];
		$lg -> submit_date =date("Y-m-d G:i:s",time());
		$lg -> save();
	    //$this->session->flashdata();
		//$this -> add_view();
		redirect("moh_503/add_view");
	}
	
	public function moh_503_tb($offset = 0){
	
		$items_per_page = 20;
		$access_level=$this->session->userdata("access_level");
		if($access_level=="7"){
		$number_of_moh503 =moh_503_db::getTotalNumber();
		$moh_503 = moh_503_db::getPagedmoh($offset, $items_per_page);
		}
		else{
		$district=$this->session->userdata("district_province_id");
		$number_of_moh503 =moh_503_db::getTotalNumberDist($district);
		$moh_503 = moh_503_db::getPagedmoh_dist($offset, $items_per_page, $district);
		}
		
		
		//$moh_503=moh_503_db::getbyDistrict($district);
		
		//getPagedmoh($offset, $items, $district)
		/*$number_of_users = Users::getTotalNumber();
		$users = Users::getPagedUsers($offset, $items_per_page);*/
		if ($number_of_moh503 > $items_per_page) {
			$config['base_url'] = base_url() . "moh_503/moh_503_tb/";
			$config['total_rows'] = $number_of_users;
			$config['per_page'] = $items_per_page;
			$config['uri_segment'] = 3;
			$config['num_links'] = 5;
			$this -> pagination -> initialize($config);
			$data['pagination'] = $this -> pagination -> create_links();
		}

		
		
		$data['moh'] = $moh_503;
	    $data['title'] = "MOH 503 Table";
		$data['content_view'] = "moh_503_tb";
		//$data['banner_text'] ="";
		$data['link'] = "moh_503";
		$data['quick_link'] = "moh_503_v";
		$this -> base_params($data);
	
	}
	
	public function base_params($data) {
		$data['scripts'] = array("jquery-ui.js", "tab.js");
		$data['styles'] = array("jquery-ui.css", "tab.css", "pagination.css");
		//$data['content_view'] = "admin_view";
		//$data['quick_link'] = "moh_503_v";
		$data['banner_text'] = "MOH 503";
		//$data['link'] = "admin_management";
		$this -> load -> view('template', $data);
	}
	
	/*public function listing($offset = 0) {
		$items_per_page = 20;
		$number_of_users = Users::getTotalNumber();
		$users = Users::getPagedUsers($offset, $items_per_page);
		if ($number_of_users > $items_per_page) {
			$config['base_url'] = base_url() . "user_management/listing/";
			$config['total_rows'] = $number_of_users;
			$config['per_page'] = $items_per_page;
			$config['uri_segment'] = 3;
			$config['num_links'] = 5;
			$this -> pagination -> initialize($config);
			$data['pagination'] = $this -> pagination -> create_links();
		}

		$data['users'] = $users;
		$data['title'] = "User Management::All System Users";
		$data['module_view'] = "view_users_view";
		$this -> base_params($data);
	}*/
	
	
	}
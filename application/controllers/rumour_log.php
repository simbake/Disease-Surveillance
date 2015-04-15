<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rumour_Log extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() { 
		$this -> add();
	}

	public function add() {
		$provinces = Province::getAll();
		$districts = District::getAll();
		$facilities = Facilities::getAll();
		$diseases = Disease::getAllObjects();

		$data['provinces'] = $provinces;
		$data['districts'] = $districts;
		$data['facilities'] = $facilities;
		$data['diseases'] = $diseases;
		
		$data['scripts'] = array("jquery.ui.core.js","jquery.ui.datepicker.js","jquery.ui.widget.js");		
		$data['styles'] = array("jquery.ui.all.css");
		$data['title'] = "Rumour Data";
		$data['content_view'] = "rumour_view";
		$data['banner_text'] = "Rumour Data";
		$data['link'] = "submissions_management";
		$data['quick_link'] = "rumour_log";
		$this -> base_params($data);
		//$this -> load -> view("template", $data);
	}
	
	public function save(){
			
		$province = $this -> input -> post("province");
		$district = $this -> input -> post("district");
		$facility = $this -> input -> post("facility");
		$date_received= $this -> input -> post("date_received");
		$disease = $this -> input -> post("disease");
		$source = $this -> input -> post("source");
		$cases = $this -> input -> post("cases");
		$death = $this -> input -> post("death");
		$fatality = $this -> input -> post("fatality");
		$results = $this -> input -> post("results");
		$onset= $this -> input -> post("onset");
		$first= $this -> input -> post("first");
		$intervention= $this -> input -> post("intervention");
		$nresponse = $this -> input -> post("nresponse");
		$comments = $this -> input -> post("comments");
		
		$Rumourlog = new Rumourlog();
		
		$Rumourlog -> province = $province;
		$Rumourlog -> district = $district;
		$Rumourlog -> facility = $facility;
		$Rumourlog -> date_received = $date_received;
		$Rumourlog -> disease = $disease;
		$Rumourlog -> source = $source;
		$Rumourlog -> casesreported = $cases;
		$Rumourlog -> deaths = $death;
		$Rumourlog -> fatality = $fatality;
		$Rumourlog -> results = $results;
		$Rumourlog -> onsetdate = $onset;
		$Rumourlog -> firstseen = $first;
		$Rumourlog -> intervention = $intervention;
		$Rumourlog -> nresponse = $nresponse;
		$Rumourlog -> comments = $comments;
		
		
		$Rumourlog -> save();
		redirect("rumour_log/add");
	}
	private function base_params($data) {
		$data['title'] = "Rumour Data";
		$data['content_view'] = "rumour_view";
		$data['banner_text'] = "Rumour Data";		
		$data['quick_link'] = "rumour_log";
		$this -> load -> view('template_v', $data);
	}
	}
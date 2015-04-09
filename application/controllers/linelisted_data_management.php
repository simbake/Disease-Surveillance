<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Linelisted_Data_Management extends MY_Controller {

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
		$data['title'] = "Line List Data";
		$data['content_view'] = "linelist_data_add_v";
		$data['banner_text'] = "Linelist Data";
		$data['link'] = "submissions_management";
		$data['quick_link'] = "linelisted_data_management";
		$this -> base_params($data);
		//$this -> load -> view("template", $data);
	}
	
	public function save(){
			
		$province = $this -> input -> post("province");
		$district = $this -> input -> post("district");
		$date_received= $this -> input -> post("date_received");
		$facility = $this -> input -> post("facility");
		$disease = $this -> input -> post("disease");
		$names = $this -> input -> post("names");
		$patient = $this -> input -> post("patient");
		$village = $this -> input -> post("village");		
		$sex = $this -> input -> post("sex");
		$age = $this -> input -> post("age");
		$date_facility = $this -> input -> post("date_facility");
		$onset_date = $this -> input -> post("onset_date");
		$dosage_number = $this -> input -> post("dosage_number");
		$specimen_date = $this -> input -> post("specimen_date");
		$specimen_type = $this -> input -> post("specimen_type");
		$lab_results = $this -> input -> post("lab_results");
		$outcome = $this -> input -> post("outcome");
		$comments = $this -> input -> post("comments");
		
		$Linelist = new Line_list();
		
		$Linelist -> Facility = $facility;
		$Linelist -> District = $district;
		$Linelist -> Date_Received = $date_received;
		$Linelist -> Province = $province;
		$Linelist -> Disease = $disease;
		$Linelist -> Names = $names;
		$Linelist -> Patient = $patient;
		$Linelist -> Village = $village;
		$Linelist -> Sex = $sex;
		$Linelist -> Age = $age;
		$Linelist -> Date_facility = $date_facility;
		$Linelist -> Onset_date = $onset_date;
		$Linelist -> Dosage_number = $dosage_number;
		$Linelist -> Specimen_date = $specimen_date;
		$Linelist -> Specimen_type = $specimen_type;
		$Linelist -> Lab_results = $lab_results;
		$Linelist -> Outcome = $outcome;
		$Linelist -> Comments = $comments;
		
		$Linelist -> save();
		redirect("linelisted_data_management/add");
	}
	
	

	private function base_params($data) {
		$data['title'] = "Linelisted Data";
		$data['content_view'] = "linelist_data_add_v";
		$data['banner_text'] = "Linelisted Data";		
		$data['quick_link'] = "linelisted_data_management";
		$this -> load -> view('template_v', $data);
	}
	

}
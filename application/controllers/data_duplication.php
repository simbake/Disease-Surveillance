<?php
class Data_Duplication extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['quality_view'] = "data_duplication_v";
		$this -> base_params($data);
	}

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "data_duplication";
		$data['title'] = "Data Quality";
		$data['content_view'] = "data_quality_v";
		$data['banner_text'] = "Data Duplication";
		$data['link'] = "data_quality_management";
		$this -> load -> view('template', $data);
	}

	public function analyze() {
		$year = $this -> input -> post('year');
		$epiweek = $this -> input -> post('epiweek');
		$district = $this -> session -> userdata('district_province_id');
		//$county=$this->session->userdata("county_id");
		if($this -> input -> post('facility_surveillance')){
		$data['duplicates'] = Facility_Surveillance_Data::getDuplicates($year, $epiweek, $district);
		$data['duplicate_content']="facility";
		$data['small_title'] = "Facilities with duplicate reports in " . $year . " Epiweek " . $epiweek;
		}
		else if($this -> input -> post('surveillance')){
		$data['duplicates'] = Surveillance::getDuplicatez($year, $epiweek, $district);
		$data['duplicate_content']="surveillance";	
		$data['small_title'] = "Records with duplicate surveillance data reports in " . $year . " Epiweek " . $epiweek;
		}
		$data['quality_view'] = "data_duplication_v";
		$data['total_diseases'] = Disease::getTotal();
		$data['epiweek'] = $epiweek;
		$data['year'] = $year;
		
		$this -> base_params($data);
	}

	public function view_details($epiweek, $year, $facility) {
		$data['surveillance_data'] = Facility_Surveillance_Data::getSurveillanceData($epiweek, $year, $facility);
		$data['lab_data'] = Facility_Lab_Weekly::getWeeklyFacilityLabData($epiweek, $year, $facility);
		$data['quality_view'] = "data_duplication_details_v";
		$data['diseases'] = Disease::getAllObjects();
		$this -> base_params($data);
	}
	public function view_details_surveillance($epiweek, $year) {
		//echo "epiweek ".$epiweek;
		//echo "year".$year;
		$district=$this->session->userdata("district_province_id");
		//echo $district;
		$data['surveillance_data'] = Surveillance::getSurveillanceData($epiweek, $year,$district);
		$data['lab_data'] = Lab_Weekly::getWeeklyFacilityLabData($epiweek, $year,$district);
		$data['quality_view'] = "data_duplicate_surveillance";
		$data['diseases'] = Disease::getAllObjects();
		$this -> base_params($data);
	}

	public function edit_duplicate($facility, $number_of_diseases, $first_surveillance_id, $malaria_data_id) {
		$last_surveillance_id = $first_surveillance_id + $number_of_diseases - 1;
		$district = $this -> session -> userdata('district_province_id');
		$data['facilities'] = Facilities::getDistrictFacilities($district);
		$diseases = Disease::getAllObjects();
		$data['admin_facility'] = $facility;
		$data['diseases'] = $diseases;
		$data['prediction'] = Surveillance::getPrediction();
		$data['surveillance_data'] = Facility_Surveillance_Data::getSurveillanceDataRange($first_surveillance_id, $last_surveillance_id);
		$data['lab_data'] = Facility_Lab_Weekly::getLabObjects($malaria_data_id);
		$data['editing'] = true;
		$data['scripts'] = array("special_date_picker.js", "validationEngine-en.js", "validator.js");
		$data["styles"] = array("validator.css");
		$data['title'] = "Duplicate Data Editing";
		$data['content_view'] = "weekly_data_add_v";
		$data['banner_text'] = "Weekly Data Correction";
		$data['link'] = "data_quality_management";
		$this -> load -> view("template", $data);
	}

	public function delete_duplicate($number_of_diseases, $first_surveillance_id, $malaria_data_id, $district, $epiweek, $year) {
		
		$last_surveillance_id = $first_surveillance_id + $number_of_diseases - 1;
		$surveillance_records = Facility_Surveillance_Data::getSurveillanceDataRange($first_surveillance_id, $last_surveillance_id);
		$facility = $surveillance_records[0]->Facility;
		//loop through all the records returned, delete them and update the relevant district data
		foreach ($surveillance_records as $surveillance_record) {
			//Get the district surveillance data
			$district_data = Surveillance::getDistrictDiseaseData($epiweek, $year, $district,$surveillance_record->Disease);
			$lcase = $surveillance_record -> Lcase;
			$ldeath = $surveillance_record -> Ldeath;
			$gcase = $surveillance_record -> Gcase;
			$gdeath = $surveillance_record -> Gdeath;
			//Edit the number of submitted reports also
			$district_data -> Submitted -= 1;
			$district_data -> Lcase -= $lcase;
			$district_data -> Ldeath -= $ldeath;
			$district_data -> Gcase -= $gcase;
			$district_data -> Gdeath -= $gdeath;

			$district_data -> save();
			$surveillance_record -> delete(); 
		}
		//Get the district malaria lab data
		$lab_data = Lab_Weekly::getWeeklyDistrictLabData($epiweek, $year, $district);
		$lab_data = $lab_data[0];
		//Get the facility malaria lab data
		$facility_lab_data = Facility_Lab_Weekly::getLabObjects($malaria_data_id);
		$facility_lab_data = $facility_lab_data[0];

		$totaltestedlessfive = $facility_lab_data -> Malaria_Below_5;
		$totaltestedgreaterfive = $facility_lab_data -> Malaria_Above_5;
		$totalpositivelessfive = $facility_lab_data -> Positive_Below_5;
		$totalpositivegreaterfive = $facility_lab_data -> Positive_Above_5;

		$lab_data -> Malaria_Below_5 -= $totaltestedlessfive;
		$lab_data -> Malaria_Above_5 -= $totaltestedgreaterfive;
		$lab_data -> Positive_Below_5 -= $totalpositivelessfive;
		$lab_data -> Positive_Above_5 -= $totalpositivegreaterfive;
		$lab_data -> save();
		$facility_lab_data -> delete();

		//Log the action
		$log = new Data_Delete_Log();
		$log -> Deleted_By = $this -> session -> userdata('user_id');
		$log -> Facility_Affected = $facility;
		$log -> Epiweek = $epiweek;
		$log -> Reporting_Year = $year;
		$log -> Timestamp = date('U');
		$log -> save();
		redirect("data_delete_management");
	}
public function delete_surveillance_duplicate($number_of_diseases, $first_surveillance_id, $malaria_data_id, $district, $epiweek, $year) {
		
		$last_surveillance_id = $first_surveillance_id + $number_of_diseases - 1;
		$surveillance_records = Surveillance::getSurveillanceDataRange($first_surveillance_id, $last_surveillance_id);
		//$facility = $surveillance_records[0]->Facility;
		
		//loop through all the records returned, delete them and update the relevant district data
		foreach ($surveillance_records as $surveillance_record) {
			//Get the district surveillance data
			$surveillance_record -> delete(); 
		}
		//Get the district malaria lab data
		/*$lab_data = Lab_Weekly::getWeeklyDistrictLabData($epiweek, $year, $district);
		$lab_data = $lab_data[0];
		
		//Get the facility malaria lab data
		$lab_data -> delete();*/

		//Log the action
		$log = new Data_Delete_Log();
		$log -> Deleted_By = $this -> session -> userdata('user_id');
		$log -> Facility_Affected = $facility;
		$log -> Epiweek = $epiweek;
		$log -> Reporting_Year = $year;
		$log -> Timestamp = date('U');
		$log -> save();
		redirect("data_delete_management");
	}

}

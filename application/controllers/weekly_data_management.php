<?php
ini_set('error_reporting','1');
class Weekly_Data_Management extends MY_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() {

		$this -> add();
	}

	public function add($data) {
        
		$access_level = $this -> session -> userdata('user_indicator'); //Get user access level.
		
		if ($access_level == "district_clerk") { //DDSC
			$district = $this -> session -> userdata('district_province_id');
			$data['facilities'] = Facilities::getDistrictFacilitiesArrays($district);
			$diseases = Disease::getAllObjects();
			$data['diseases'] = $diseases;
			$data['editing'] = false;
			$data['prediction'] = Facility_Surveillance_Data::getPrediction();
			$data['scripts'] = array("special_date_picker.js", "validationEngine-en.js", "validator.js");
			$data["styles"] = array("validator.css");
			$this -> base_params($data);
		} 
		else if ($access_level == "system_administrator") { // System Administrator
			
			if (!isset($data['editing'])) {
				redirect("disease_ranking");
			}
			$data['facilities'] = Facilities::getFacilityArray($data['admin_facility']);
			$diseases = Disease::getAllObjects();
			$data['diseases'] = $diseases;
			$data['editing'] = false;
			$data['prediction'] = Facility_Surveillance_Data::getPrediction();
			$data['scripts'] = array("special_date_picker.js", "validationEngine-en.js", "validator.js");
			$data["styles"] = array("validator.css");
			$this -> base_params($data);
		} else { // unauthorized user
			$data['title'] = "Weekly Data";
			$data['content_view'] = "error_message_v";
			$data['error_message'] = "You are not authorized to add weekly epidemiological data. Please contact the system administrator for assistance!";
			$data['banner_text'] = "Weekly Data";
			$data['link'] = "submissions_management";
			$data['quick_link'] = "weekly_data_management";
			$this -> load -> view("template", $data);
		}
	}

	public function edit_weekly_data($epiweek, $reporting_year, $facility) {
		$facility_object = Facilities::getFacility($facility);
		$district = $this -> session -> userdata('district_province_id');
		$access_level = $this -> session -> userdata('user_indicator');
		//Check if the facility that the user wants to edit data for is in their district
		if ($facility_object -> district == $district || $access_level == "system_administrator") {
			$data['surveillance_data'] = Facility_Surveillance_Data::getSurveillanceData($epiweek, $reporting_year, $facility);
			$data['lab_data'] = Facility_Lab_Weekly::getWeeklyFacilityLabData($epiweek, $reporting_year, $facility);
			$data['editing'] = true;
			$data['admin_facility'] = $facility;
			$this -> add($data);
		} else {
			$data['title'] = "Weekly Data";
			$data['content_view'] = "error_message_v";
			$data['error_message'] = "You are not authorized to edit epidemiological data for facilities outside your district. Please contact the system administrator for assistance!";
			$data['banner_text'] = "Weekly Data";
			$data['link'] = "submissions_management";
			$data['quick_link'] = "weekly_data_management";
			$this -> load -> view("template", $data);
		}

	}

	public function delete_weekly_data($epiweek, $reporting_year, $facility) {
		$data['surveillance_data'] = Facility_Surveillance_Data::getSurveillanceData($epiweek, $reporting_year, $facility);
		$data['title'] = "Delete Weekly Data";
		$data['content_view'] = "delete_facility_weekly_data_v";
		$data['banner_text'] = "Delete Data";
		$data['link'] = "submissions_management";
		$this -> load -> view("template", $data);
	}

	public function corrupt_data($epiweek, $reporting_year, $facility) {
		$data['surveillance_data'] = Facility_Surveillance_Data::getSurveillanceData($epiweek, $reporting_year, $facility);
		$data['title'] = "Data Corruption";
		$data['content_view'] = "data_inconsistency_v";
		$data['banner_text'] = "Corrupt Data";
		$this -> load -> view("template", $data);
	}

	public function confirm_delete_weekly_data($epiweek, $reporting_year, $facility) {
		$district = $this -> session -> userdata('district_province_id');
		//Get the diseases
		$diseases = Disease::getAllObjects();
		//Loop through the diseases to update the relevant data
		foreach ($diseases as $disease) {
			//Get the facility surveillance data
			$facility_data = Facility_Surveillance_Data::getFacilityDiseaseData($epiweek, $reporting_year, $facility, $disease -> id);
			$district = $facility_data -> District;
			$lcase = $facility_data -> Lcase;
			$ldeath = $facility_data -> Ldeath;
			$gcase = $facility_data -> Gcase;
			$gdeath = $facility_data -> Gdeath;
			$facility_data -> delete();
		}
		//Get the facility malaria lab data
		$facility_lab_data = Facility_Lab_Weekly::getWeeklyFacilityLabData($epiweek, $reporting_year, $facility);
		$facility_lab_data = $facility_lab_data[0];

		$totaltestedlessfive = $facility_lab_data -> Malaria_Below_5;
		$totaltestedgreaterfive = $facility_lab_data -> Malaria_Above_5;
		$totalpositivelessfive = $facility_lab_data -> Positive_Below_5;
		$totalpositivegreaterfive = $facility_lab_data -> Positive_Above_5;
		$facility_lab_data -> delete();
		$this -> update_district_record($district, $epiweek, $reporting_year);
		//Log the action
		$log = new Data_Delete_Log();
		$log -> Deleted_By = $this -> session -> userdata('user_id');
		$log -> Facility_Affected = $facility;
		$log -> Epiweek = $epiweek;
		$log -> Reporting_Year = $reporting_year;
		$log -> Timestamp = date('U');
		$log -> save();
		redirect("data_delete_management");
	}

	public function update_district_record($district, $epiweek, $reporting_year) {
		$this -> load -> database();
		//retrieve the original district summary record
		//Code to retrieve the cases vs. deaths variables
		$district_sql = "SELECT disease FROM `surveillance` where district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year'";
		$district_query = $this -> db -> query($district_sql);
		$district_surveillance_data = $district_query -> result_array();

		$district_data_array = array();
		foreach ($district_surveillance_data as $district_data) {
			$district_data_array[$district_data['disease']] = $district_data['disease'];

		}
		//var_dump($district_surveillance_data);

		//Code to retrieve the submitted and expected variables
		$expected_facilities = Facilities::getExpected($district);

		//Get the actual reported facilities
		$sql = "SELECT count(distinct facility) as total FROM `facility_surveillance_data` where district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year'";
		$query = $this -> db -> query($sql);
		$reported_array = $query -> row_array();
		$reported_facilities = $reported_array['total'];
		//echo $reported_facilities;
		//echo $expected_facilities;

		//Get the number of timely reports (4 days before the week ending)
		$timeliness_sql = "select count(distinct facility) as timely_reports from facility_surveillance_data where district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year' and str_to_date(date_created,'%Y-%m-%d') <= DATE_ADD(str_to_date(week_ending,'%Y-%m-%d'), INTERVAL 3 day)";
		$timeliness_query = $this -> db -> query($timeliness_sql);
		$timeliness_array = $timeliness_query -> row_array();
		$timely_facilities = $timeliness_array['timely_reports'];

		//Code to retrieve the cases vs. deaths variables
		$facilities_sql = "SELECT disease,sum(lcase) as lcase,sum(ldeath) as ldeath,sum(gcase) as gcase,sum(gdeath) as gdeath,`date_created`, `created_by`, `week_ending`, `reported_by`, `designation`, `date_reported`, `reporting_year`, `total_diseases` FROM `facility_surveillance_data` where district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year' group by disease";
		$facilities_query = $this -> db -> query($facilities_sql);
		$facility_surveillance_data = $facilities_query -> result_array();
		//loop the summaries from the facility and replace the ristrict summaries
		foreach ($facility_surveillance_data as $facility_data) {
			//Check whether any district data was returned, if not, generate an insert query, if so, generate an update query
			if (!isset($district_data_array[$facility_data['disease']])) {
				//generate insert statement
				$insert_query = "INSERT INTO `surveillance`(`disease`, `lcase`, `ldeath`, `date_created`, `created_by`, `epiweek`, `submitted`,`timely_reports`, `expected`, `district`, `week_ending`, `gcase`, `gdeath`, `reported_by`, `designation`, `date_reported`, `reporting_year`, `total_diseases`) VALUES ('" . $facility_data['disease'] . "','" . $facility_data['lcase'] . "','" . $facility_data['ldeath'] . "','" . $facility_data['date_created'] . "','" . $facility_data['created_by'] . "','" . $epiweek . "','" . $reported_facilities. "','" . $timely_facilities . "','" . $expected_facilities . "','" . $district . "','" . $facility_data['week_ending'] . "','" . $facility_data['gcase'] . "','" . $facility_data['gdeath'] . "','" . $facility_data['reported_by'] . "','" . $facility_data['designation'] . "','" . $facility_data['date_reported'] . "','" . $facility_data['reporting_year'] . "','" . $facility_data['total_diseases'] . "')";
				$insert = $this -> db -> query($insert_query);
				//echo $insert . "da";
			} else {
				//generate update statement
				$update_query = "UPDATE `surveillance` SET `lcase`='" . $facility_data['lcase'] . "',`ldeath`='" . $facility_data['ldeath'] . "',`submitted`='" . $reported_facilities . "',`timely_reports`='" . $timely_facilities. "',`expected`='" . $expected_facilities . "',`gcase`='" . $facility_data['gcase'] . "',`gdeath`='" . $facility_data['gdeath'] . "',`total_diseases`='" . $facility_data['total_diseases'] . "' WHERE district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year' and disease = '" . $facility_data['disease'] . "'";

				$update = $this -> db -> query($update_query);
				//echo $update;
			}
		}
		//If no facility data was returned, then delete the report
		if (!isset($facility_surveillance_data[0])) {
			$delete_district_data = "delete from surveillance where district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year'";
			//echo $delete_district_data;
			$delete = $this -> db -> query($delete_district_data);
			//echo $delete;
		}

		//Proceed to update the lab results
		//retrieve the district lab result
		$district_lab_sql = "SELECT district FROM `lab_weekly` where district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year'";
		$district_lab_query = $this -> db -> query($district_lab_sql);
		$district_lab_data = $district_lab_query -> row_array();
		//var_dump($district_lab_surveillance_data);
		//retrieve the totals of the facility lab reports
		$facility_lab_results = "SELECT `epiweek`, `week_ending`, `district`, `facility`, `remarks`, sum(malaria_below_5) as malaria_below_5, sum(malaria_above_5) as malaria_above_5, sum(positive_below_5) as positive_below_5, sum(positive_above_5) as positive_above_5, `date_created`, `reporting_year` FROM `facility_lab_weekly` WHERE district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year'";
		$facilities_lab_query = $this -> db -> query($facility_lab_results);
		$facility_lab_data = $facilities_lab_query -> row_array();
		//Check if district lab data is available. If so, update it. If not, create it.
		if (isset($district_lab_data['district'])) {
			$update_lab_query = "UPDATE `lab_weekly` SET `remarks`='" . $facility_lab_data['remarks'] . "',`malaria_below_5`='" . $facility_lab_data['malaria_below_5'] . "',`malaria_above_5`='" . $facility_lab_data['malaria_above_5'] . "',`positive_below_5`='" . $facility_lab_data['positive_below_5'] . "',`positive_above_5`='" . $facility_lab_data['positive_above_5'] . "' WHERE district = '$district' and epiweek = '$epiweek' and reporting_year = '$reporting_year'";
			$lab_update = $this -> db -> query($update_lab_query);
			//echo $lab_update;
		} else {
			$insert_lab_query = "INSERT INTO `lab_weekly`(`epiweek`, `week_ending`, `district`, `facility`, `remarks`, `malaria_below_5`, `malaria_above_5`, `positive_below_5`, `positive_above_5`, `date_created`, `reporting_year`) VALUES ('" . $epiweek . "','" . $facility_lab_data['week_ending'] . "','" . $district . "','','" . $facility_lab_data['remarks'] . "','" . $facility_lab_data['malaria_below_5'] . "','" . $facility_lab_data['malaria_above_5'] . "','" . $facility_lab_data['positive_below_5'] . "','" . $facility_lab_data['positive_above_5'] . "','" . $facility_lab_data['date_created'] . "','" . $reporting_year . "')";
			$lab_insert = $this -> db -> query($insert_lab_query);
			//echo $lab_insert . "in";
		}

	}

	public function save() {
		$this -> load -> database();
		$i = 0;
		$valid = $this -> _validate_submission();
		if ($valid == false) {
			//$this -> add();
			redirect("weekly_data_management");
		} else {
			$editing = false;
			$existing_district_data = false;
			$existing_district_lab_data = false;
			$diseases = Disease::getAllObjects();
			$district = $this -> session -> userdata('district_province_id');
			$editing_district_id = $this -> input -> post('editing_district_id');
			if (strlen($editing_district_id) > 0) {
				$district = $editing_district_id;
			}
			//Getting form data
			$weekending = $this -> db -> escape_str($this -> input -> post("week_ending"));
			$reporting_year = $this -> db -> escape_str($this -> input -> post("reporting_year"));
			$epiweek = $this -> db -> escape_str($this -> input -> post("epiweek"));
			$facility = $this -> db -> escape_str($this -> input -> post("facility"));
			$lcase = $this -> input -> post("lcase");
			$ldeath = $this -> input -> post("ldeath");
			$gcase = $this -> input -> post("gcase");
			$gdeath = $this -> input -> post("gdeath");
			$reported_by = $this -> db -> escape_str($this -> input -> post("reported_by"));
			$designation = $this -> db -> escape_str($this -> input -> post("designation"));
			$lab_id = $this -> input -> post("lab_id");
			$surveillance_ids = $this -> input -> post("surveillance_ids");
			
			//Check if a duplicate for facility data exists
			$data_exists = Facility_Surveillance_Data::getFacilityData($epiweek, $reporting_year, $facility);
			if ($lab_id > 0) {
				$editing = true;
			}
			//If there is a duplicate, redirect the user to the input form and inform them
			if ($data_exists -> id && $editing == false) {
				
				//store data in flashdata that will be used to display duplicate notification
				$this->session->set_flashdata('duplicate_facility', Facilities::getFacility($facility));
				$this->session->set_flashdata('duplicate_epiweek', $epiweek);
				$this->session->set_flashdata('duplicate_reporting_year', $reporting_year);
				$this->session->set_flashdata('existing_data', true);
				//echo "Duplicate detected";
				redirect("weekly_data_management/");
				
				/*$data = array();
				$data['duplicate_facility'] = Facilities::getFacility($facility);
				$data['duplicate_epiweek'] = $epiweek;
				$data['duplicate_reporting_year'] = $reporting_year;
				$data['existing_data'] = true;
				$this -> add($data);
				return;*/
			}

			$total_diseases = Disease::getTotal();
			$timestamp = date('d/m/Y');
			$i = 0;
			foreach ($diseases as $disease) {
				if ($editing == true) {
					//If we are editing, retrieve the record being edited from the database
					$surveillance = Facility_Surveillance_Data::getSurveillance($surveillance_ids[$i]);

				} else {
					//If not, create a new surveillance record
					$surveillance = new Facility_Surveillance_Data();
				}

				//Populate the district surveillance data

				//Populate the facility surveillance data
				$surveillance -> clearRelated();
				$surveillance -> Week_Ending = $weekending;
				$surveillance -> Epiweek = $epiweek;
				$surveillance -> District = $district;
				$surveillance -> Facility = $facility;
				$surveillance -> Lcase = $lcase[$i];
				$surveillance -> Ldeath = $ldeath[$i];
				$surveillance -> Gcase = $gcase[$i];
				$surveillance -> Gdeath = $gdeath[$i];
				$surveillance -> Disease = $disease;
				$surveillance -> Reporting_Year = $reporting_year;
				$surveillance -> Created_By = $this -> session -> userdata('user_id');
				$surveillance -> Date_Created = date("Y-m-d");
				$surveillance -> Reported_By = $reported_by;
				$surveillance -> Designation = $designation;
				$surveillance -> Total_Diseases = $total_diseases;
				$surveillance -> Date_Reported = $timestamp;
				$surveillance -> save();
				$i++;
			}//end foreach

			//Lab Data
			//also retrieve the aggregated district record to update it
			//Check if we are editing the facility lab data
			if ($editing == true) {
				$labdata = Facility_Lab_Weekly::getLabObject($lab_id);
			} else {
				$labdata = new Facility_Lab_Weekly();
			}
			//data from the form//
			$totaltestedlessfive = $this -> input -> post("total_tested_less_than_five");
			$totaltestedgreaterfive = $this -> input -> post("total_tested_greater_than_five");
			$totalpositivelessfive = $this -> input -> post("total_positive_less_than_five");
			$totalpositivegreaterfive = $this -> input -> post("total_positive_greater_than_five");
			$remarks = $this -> input -> post("remarks");
			

			//Populate the facility lab data
			$labdata -> Epiweek = $epiweek;
			$labdata -> Week_Ending = $weekending;
			$labdata -> District = $district;
			$labdata -> Facility = $facility;
			$labdata -> Malaria_Below_5 = $totaltestedlessfive;
			$labdata -> Malaria_Above_5 = $totaltestedgreaterfive;
			$labdata -> Positive_Below_5 = $totalpositivelessfive;
			$labdata -> Positive_Above_5 = $totalpositivegreaterfive;
			$labdata -> Remarks = $remarks;
			$labdata -> Reporting_Year = $reporting_year;
			$labdata -> Date_Created = date("Y-m-d");
			$labdata -> save();
			
			$this -> update_district_record($district, $epiweek, $reporting_year);
			if ($editing) {
				$success_message= "You have successfully edited data for <b>" . $labdata -> Facility_Object -> name . "</b>";
				$this->session->set_flashdata('success_message',$success_message);
				redirect("weekly_data_management/");
			}
			if (!$editing) {
		
				$success_message= "You have successfully added weekly data for <b>" . $labdata -> Facility_Object -> name . "</b>";
				$this->session->set_flashdata('success_message',$success_message);
				redirect("weekly_data_management/");
			}
		}
	}//end save

	private function _validate_submission() {
		$this -> form_validation -> set_rules('facility', 'Facility', 'trim|required|min_length[1]|max_length[25]');

		$temp_validation = $this -> form_validation -> run();
		if ($temp_validation) {
			$this -> form_validation -> set_rules('facility', 'Duplicate Recordset', 'trim|required|callback_weekly_duplication');
			return $this -> form_validation -> run();
		} else {
			return $temp_validation;
		}
	}//end validate_submission

	public function weekly_duplication($facility) {
		$lab_id = $this -> input -> post("lab_id");
		$epiweek = $this -> input -> post("epiweek");
		$year = $this -> input -> post("reporting_year");
		if (strlen($lab_id) > 0) {
			return TRUE;
		}
		$data = Facility_Surveillance_Data::getFacilityData($epiweek, $year, $facility);
		if ($data -> id) {
			$this -> form_validation -> set_message('weekly_duplication', 'A report for this week already exists!');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/*
	 * Function to check if district data for a particular week exists
	 *
	 */
	function check_facility_data($epiweek, $year, $facility) {
		$data = Facility_Surveillance_Data::getFacilityData($epiweek, $year, $facility);
		if ($data -> id) {
			echo "yes";
		} else {
			echo "no";
		}
	}
	function update_district_reports($week, $year){
		$this -> load -> database();
		$districts_sql = "select distinct district from surveillance where epiweek = '$week' and reporting_year = '$year'";
		$districts_query = $this -> db -> query($districts_sql);
		$districts_array = $districts_query -> result_array();
		foreach($districts_array as $districts_data){
			$this->update_district_record($districts_data['district'], $week, $year);
			echo "Updated For district: ".$districts_data['district']."<br>";
		}
		
		
		
	}

	public function base_params($data) {
		$data['title'] = "Weekly Data";
		$data['content_view'] = "weekly_data_add_v";
		$data['banner_text'] = "Weekly Data";
		$data['link'] = "submissions_management";
		$data['quick_link'] = "weekly_data_management";
		$this -> load -> view("template", $data);
	}

}//end class

<?php
class Raw_Data extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['settings_view'] = "raw_data_v";
		$this -> base_params($data);
	}

	public function export() {
		$surveillance_data_requested = $this -> input -> post('surveillance');
		$malaria_data_requested = $this -> input -> post('malaria');
		$year = $this -> input -> post('year_from');
		$start_week = $this -> input -> post('epiweek_from');
		$end_week = $this -> input -> post('epiweek_to');
		$access_level = $this -> session -> userdata('user_indicator');
		$facility_surveillance=$this->input->post('facility_surveillance');
		
		if ($access_level == "district_clerk") {
			$surveillance_data=$this -> get_district_raw_data($surveillance_data_requested, $malaria_data_requested, $year, $start_week, $end_week);
		} else if($access_level == "county_clerk"){
			
			$surveillance_data=$this -> get_county_raw_data($surveillance_data_requested, $malaria_data_requested, $year, $start_week, $end_week);
		}
		
		 else if ($access_level == "provincial_clerk") {
			$surveillance_data=$this -> get_province_raw_data($surveillance_data_requested, $malaria_data_requested, $year, $start_week, $end_week);
		} else if (($access_level == "national_clerk" || $access_level == "system_administrator") && !$facility_surveillance) {
			$surveillance_data=$this -> get_national_raw_data($surveillance_data_requested, $malaria_data_requested, $year, $start_week, $end_week);
		}
		else if(($access_level == "national_clerk" || $access_level == "system_administrator") && $facility_surveillance){
			
		$surveillance_data=$this -> facility_national_surveillance($surveillance_data_requested, $year, $start_week, $end_week);
			
		}
		

	}

	public function get_national_raw_data($surveillance_data_requested, $malaria_data_requested, $year, $start_week, $end_week) {
		if (strlen($surveillance_data_requested) > 0) {
			$surveillance_data = Surveillance::getRawDataArray($year, $start_week, $end_week);
			$excell_headers = "Disease\t District Name\t County Name\t Province Name\t Week Number\t Week Ending\t Cases (Less Than 5)\t Cases (Greater Than 5)\t Total Cases\t Deaths (Less Than 5)\t Deaths (Greater Than 5)\t Total Deaths\tYear\t Reported By\t Designation\t Date Reported\t\n";
			$excell_data = "";
			foreach ($surveillance_data as $result_set) {
				//$excell_data .= $result_set -> Disease_Object -> Name . "\t" . $result_set -> District_Object -> Name . "\t" . $result_set -> District_Object -> Province_Object -> Name . "\t" . $result_set -> Epiweek . "\t" . $result_set -> Week_Ending . "\t" . $result_set -> Lmcase . "\t" . $result_set -> Lfcase . "\t" . $result_set -> Gmcase . "\t" . $result_set -> Gfcase . "\t" . ($result_set -> Lmcase + $result_set -> Lfcase + $result_set -> Gmcase + $result_set -> Gfcase) . "\t" . $result_set -> Lmdeath . "\t" . $result_set -> Lfdeath . "\t" . $result_set -> Gmdeath . "\t" . $result_set -> Gfdeath . "\t" . ($result_set -> Lmdeath + $result_set -> Lfdeath + $result_set -> Gmdeath + $result_set -> Gfdeath) . "\t" . $result_set -> Reporting_Year . "\t" . $result_set -> Reported_By . "\t" . $result_set -> Designation . "\t" . $result_set -> Date_Reported . "\t";
				$excell_data .= $result_set['Disease_Name'] . "\t" . $result_set['District_Name'] . "\t" .$result_set['County_Name']. "\t". $result_set['Province_Name'] . "\t" . $result_set['Epiweek'] . "\t" . $result_set['Week_Ending'] . "\t" . $result_set['Lcase'] ."\t" . $result_set['Gcase'] . "\t" . ($result_set['Lcase'] + $result_set['Gcase']) . "\t" . $result_set['Ldeath'] . "\t" . $result_set['Gdeath'] . "\t" . ($result_set['Ldeath'] + $result_set['Gdeath']) . "\t" . $result_set['Reporting_Year'] . "\t" . $result_set['Reported_By'] . "\t" . $result_set['Designation'] . "\t" . $result_set['Date_Reported'] . "\t";
				$excell_data .= "\n";
				flush();
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Surveillance_Data (" . $year . " epiweek " . $start_week . " to epiweek " . $end_week . ").xls");
			// Fix for crappy IE bug in download.
			header("Pragma: ");
			header("Cache-Control: ");
			echo $excell_headers . $excell_data;
		} else if (strlen($malaria_data_requested) > 0) {
			$malaria_data = Lab_Weekly::getRawData($year, $start_week, $end_week);
			$excell_headers = "District\t County\t Province\t Week Number\t Week Ending\t Tested (Less Than 5)\t Tested (Greater Than 5)\t Total Tested\t Positive (Less Than 5)\t Positive (Greater Than 5)\t Total Positive\t\n";
			$excell_data = "";
			foreach ($malaria_data as $result_set) {
				$excell_data .= $result_set -> District_Object -> Name . "\t" . $result_set -> District_Object->County_Object -> Name . "\t" .$result_set -> District_Object -> Province_Object -> Name . "\t" . $result_set -> Epiweek . "\t" . $result_set -> Week_Ending . "\t" . $result_set -> Malaria_Below_5 . "\t" . $result_set -> Malaria_Above_5 . "\t" . ($result_set -> Malaria_Below_5 + $result_set -> Malaria_Above_5) . "\t" . $result_set -> Positive_Below_5 . "\t" . $result_set -> Positive_Above_5 . "\t" . ($result_set -> Positive_Below_5 + $result_set -> Positive_Above_5) . "\t";
				$excell_data .= "\n";
				flush();
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Malaria_Test_Data (" . $_POST['year_from'] . " epiweek " . $_POST['epiweek_from'] . " to epiweek " . $_POST['epiweek_to'] . ").xls");
			// Fix for crappy IE bug in download.
			header("Pragma: ");
			header("Cache-Control: ");
			echo $excell_headers . $excell_data;
		}
	}

	public function get_district_raw_data($surveillance_data_requested, $malaria_data_requested, $year, $start_week, $end_week) {
		$district = $this -> session -> userdata('district_province_id');
		if (strlen($surveillance_data_requested) > 0) {
			$surveillance_data = Facility_Surveillance_Data::getRawDataArray($year, $start_week, $end_week,$district);
			$excell_headers = "Disease\t Facility Name\t Week Number\t Week Ending\t Cases (Less Than 5)\t Cases (Greater Than 5)\t Total Cases\t Deaths (Less Than 5)\t Deaths (Greater Than 5)\t Total Deaths\tYear\t Reported By\t Designation\t Date Reported\t\n";
			$excell_data = "";
			foreach ($surveillance_data as $result_set) {
				//$excell_data .= $result_set -> Disease_Object -> Name . "\t" . $result_set -> District_Object -> Name . "\t" . $result_set -> District_Object -> Province_Object -> Name . "\t" . $result_set -> Epiweek . "\t" . $result_set -> Week_Ending . "\t" . $result_set -> Lmcase . "\t" . $result_set -> Lfcase . "\t" . $result_set -> Gmcase . "\t" . $result_set -> Gfcase . "\t" . ($result_set -> Lmcase + $result_set -> Lfcase + $result_set -> Gmcase + $result_set -> Gfcase) . "\t" . $result_set -> Lmdeath . "\t" . $result_set -> Lfdeath . "\t" . $result_set -> Gmdeath . "\t" . $result_set -> Gfdeath . "\t" . ($result_set -> Lmdeath + $result_set -> Lfdeath + $result_set -> Gmdeath + $result_set -> Gfdeath) . "\t" . $result_set -> Reporting_Year . "\t" . $result_set -> Reported_By . "\t" . $result_set -> Designation . "\t" . $result_set -> Date_Reported . "\t";
				$excell_data .= $result_set['Disease_Name'] . "\t" . $result_set['Facility_Name'] . "\t". $result_set['Epiweek'] . "\t" . $result_set['Week_Ending'] . "\t" . $result_set['Lcase'] . "\t" . $result_set['Gcase'] . "\t" . ($result_set['Lcase'] + $result_set['Gcase']) . "\t" . $result_set['Ldeath'] . "\t" . $result_set['Gdeath'] . "\t" . ($result_set['Ldeath'] + $result_set['Gdeath']) . "\t" . $result_set['Reporting_Year'] . "\t" . $result_set['Reported_By'] . "\t" . $result_set['Designation'] . "\t" . $result_set['Date_Reported'] . "\t";
				$excell_data .= "\n";
				flush();
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Surveillance_Data (" . $year . " epiweek " . $start_week . " to epiweek " . $end_week . ").xls");
			// Fix for crappy IE bug in download.
			header("Pragma: ");
			header("Cache-Control: ");
			echo $excell_headers . $excell_data;
		} else if (strlen($malaria_data_requested) > 0) {
			$malaria_data = Facility_Lab_Weekly::getRawData($year, $start_week, $end_week,$district);
			$excell_headers = "Facility\t Week Number\t Week Ending\t Tested (Less Than 5)\t Tested (Greater Than 5)\t Total Tested\t Positive (Less Than 5)\t Positive (Greater Than 5)\t Total Positive\t\n";
			$excell_data = "";
			foreach ($malaria_data as $result_set) {
				$excell_data .= $result_set -> Facility_Object -> name . "\t". $result_set -> Epiweek . "\t" . $result_set -> Week_Ending . "\t" . $result_set -> Malaria_Below_5 . "\t" . $result_set -> Malaria_Above_5 . "\t" . ($result_set -> Malaria_Below_5 + $result_set -> Malaria_Above_5) . "\t" . $result_set -> Positive_Below_5 . "\t" . $result_set -> Positive_Above_5 . "\t" . ($result_set -> Positive_Below_5 + $result_set -> Positive_Above_5) . "\t";
				$excell_data .= "\n";
				flush();
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Malaria_Test_Data (" . $_POST['year_from'] . " epiweek " . $_POST['epiweek_from'] . " to epiweek " . $_POST['epiweek_to'] . ").xls");
			// Fix for crappy IE bug in download.
			header("Pragma: ");
			header("Cache-Control: ");
			echo $excell_headers . $excell_data;
		}
	}
	
	public function get_county_raw_data($surveillance_data_requested, $malaria_data_requested, $year, $start_week, $end_week) {
		$county_id = $this -> session -> userdata('county_id');
		$county_name=County::getName($county_id);
		//echo "county";
		if (strlen($surveillance_data_requested) > 0) {
			$surveillance_data = Facility_Surveillance_Data::getRawDataArrayCounty($year, $start_week, $end_week,$county_name);
			$excell_headers = "Disease\t Facility Name\t Week Number\t Week Ending\t Cases (Less Than 5)\t Cases (Greater Than 5)\t Total Cases\t Deaths (Less Than 5)\t Deaths (Greater Than 5)\t Total Deaths\tYear\t Reported By\t Designation\t Date Reported\t\n";
			$excell_data = "";
			foreach ($surveillance_data as $result_set) {
				//$excell_data .= $result_set -> Disease_Object -> Name . "\t" . $result_set -> District_Object -> Name . "\t" . $result_set -> District_Object -> Province_Object -> Name . "\t" . $result_set -> Epiweek . "\t" . $result_set -> Week_Ending . "\t" . $result_set -> Lmcase . "\t" . $result_set -> Lfcase . "\t" . $result_set -> Gmcase . "\t" . $result_set -> Gfcase . "\t" . ($result_set -> Lmcase + $result_set -> Lfcase + $result_set -> Gmcase + $result_set -> Gfcase) . "\t" . $result_set -> Lmdeath . "\t" . $result_set -> Lfdeath . "\t" . $result_set -> Gmdeath . "\t" . $result_set -> Gfdeath . "\t" . ($result_set -> Lmdeath + $result_set -> Lfdeath + $result_set -> Gmdeath + $result_set -> Gfdeath) . "\t" . $result_set -> Reporting_Year . "\t" . $result_set -> Reported_By . "\t" . $result_set -> Designation . "\t" . $result_set -> Date_Reported . "\t";
				$excell_data .= $result_set['Disease_Name'] . "\t" . $result_set['Facility_Name'] . "\t". $result_set['Epiweek'] . "\t" . $result_set['Week_Ending'] . "\t" . $result_set['Lcase'] . "\t" . $result_set['Gcase'] . "\t" . ($result_set['Lcase'] + $result_set['Gcase']) . "\t" . $result_set['Ldeath'] . "\t" . $result_set['Gdeath'] . "\t" . ($result_set['Ldeath'] + $result_set['Gdeath']) . "\t" . $result_set['Reporting_Year'] . "\t" . $result_set['Reported_By'] . "\t" . $result_set['Designation'] . "\t" . $result_set['Date_Reported'] . "\t";
				$excell_data .= "\n";
				flush();
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Surveillance_Data (" . $year . " epiweek " . $start_week . " to epiweek " . $end_week . ").xls");
			// Fix for crappy IE bug in download.
			header("Pragma: ");
			header("Cache-Control: ");
			echo $excell_headers . $excell_data;
		} else if (strlen($malaria_data_requested) > 0) {
			$malaria_data = Facility_Lab_Weekly::getRawDataCounty($year, $start_week, $end_week,$county_id);
			$excell_headers = "Facility\t Week Number\t Week Ending\t Tested (Less Than 5)\t Tested (Greater Than 5)\t Total Tested\t Positive (Less Than 5)\t Positive (Greater Than 5)\t Total Positive\t\n";
			$excell_data = "";
			foreach ($malaria_data as $result_set) {
				$excell_data .= $result_set -> Facility_Object -> name . "\t". $result_set -> Epiweek . "\t" . $result_set -> Week_Ending . "\t" . $result_set -> Malaria_Below_5 . "\t" . $result_set -> Malaria_Above_5 . "\t" . ($result_set -> Malaria_Below_5 + $result_set -> Malaria_Above_5) . "\t" . $result_set -> Positive_Below_5 . "\t" . $result_set -> Positive_Above_5 . "\t" . ($result_set -> Positive_Below_5 + $result_set -> Positive_Above_5) . "\t";
				$excell_data .= "\n";
				flush();
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Malaria_Test_Data (" . $_POST['year_from'] . " epiweek " . $_POST['epiweek_from'] . " to epiweek " . $_POST['epiweek_to'] . ").xls");
			// Fix for crappy IE bug in download.
			header("Pragma: ");
			header("Cache-Control: ");
			echo $excell_headers . $excell_data;
		}
	}
	
	public function facility_national_surveillance($surveillance_data_requested, $year, $start_week, $end_week){
		
			// /echo "that";
			$surveillance_data = Facility_Surveillance_Data::getFacilityRawDataArray($year, $start_week, $end_week);
			//$excell_headers = "Disease\t Facility Name\t Week Number\t Week Ending\t Cases (Less Than 5)\t Cases (Greater Than 5)\t Total Cases\t Deaths (Less Than 5)\t Deaths (Greater Than 5)\t Total Deaths\tYear\t Reported By\t Designation\t Date Reported\t\n";
			$excell_headers = "Disease\t Facility Name\t County\t District\t Week Number\t Week Ending\t Cases (Less Than 5)\t Cases (Greater Than 5)\t Total Cases\t Deaths (Less Than 5)\t Deaths (Greater Than 5)\t Total Deaths\tYear\t Reported By\t Designation\t Date Reported\t\n";

			$excell_data = "";
			foreach ($surveillance_data as $result_set) {
				//$excell_data .= $result_set -> Disease_Object -> Name . "\t" . $result_set -> District_Object -> Name . "\t" . $result_set -> District_Object -> Province_Object -> Name . "\t" . $result_set -> Epiweek . "\t" . $result_set -> Week_Ending . "\t" . $result_set -> Lmcase . "\t" . $result_set -> Lfcase . "\t" . $result_set -> Gmcase . "\t" . $result_set -> Gfcase . "\t" . ($result_set -> Lmcase + $result_set -> Lfcase + $result_set -> Gmcase + $result_set -> Gfcase) . "\t" . $result_set -> Lmdeath . "\t" . $result_set -> Lfdeath . "\t" . $result_set -> Gmdeath . "\t" . $result_set -> Gfdeath . "\t" . ($result_set -> Lmdeath + $result_set -> Lfdeath + $result_set -> Gmdeath + $result_set -> Gfdeath) . "\t" . $result_set -> Reporting_Year . "\t" . $result_set -> Reported_By . "\t" . $result_set -> Designation . "\t" . $result_set -> Date_Reported . "\t";
				//$excell_data .= $result_set['Disease_Name'] . "\t" . $result_set['Facility_Name'] . "\t". $result_set['Epiweek'] . "\t" . $result_set['Week_Ending'] . "\t" . $result_set['Lcase'] . "\t" . $result_set['Gcase'] . "\t" . ($result_set['Lcase'] + $result_set['Gcase']) . "\t" . $result_set['Ldeath'] . "\t" . $result_set['Gdeath'] . "\t" . ($result_set['Ldeath'] + $result_set['Gdeath']) . "\t" . $result_set['Reporting_Year'] . "\t" . $result_set['Reported_By'] . "\t" . $result_set['Designation'] . "\t" . $result_set['Date_Reported'] . "\t";
				$excell_data .= $result_set['Disease_Name'] . "\t" . $result_set['Facility_Name'] . "\t" . $result_set['facility_county']."\t" .$result_set['district_name']. "\t". $result_set['Epiweek'] . "\t" . $result_set['Week_Ending'] . "\t" . $result_set['Lcase'] . "\t" . $result_set['Gcase'] . "\t" . ($result_set['Lcase'] + $result_set['Gcase']) . "\t" . $result_set['Ldeath'] . "\t" . $result_set['Gdeath'] . "\t" . ($result_set['Ldeath'] + $result_set['Gdeath']) . "\t" . $result_set['Reporting_Year'] . "\t" . $result_set['Reported_By'] . "\t" . $result_set['Designation'] . "\t" . $result_set['Date_Reported'] . "\t";

				$excell_data .= "\n";
				flush();
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Surveillance_Data (" . $year . " epiweek " . $start_week . " to epiweek " . $end_week . ").xls");
			// Fix for crappy IE bug in download.
			header("Pragma: ");
			header("Cache-Control: ");
			echo $excell_headers . $excell_data;
	

		
	}

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "raw_data";
		$data['title'] = "System Reports";
		$data['report_view'] = "raw_data_v";
		$data['content_view'] = "reports_v";
		$data['banner_text'] = "Raw Data";
		$data['link'] = "reports_management";

		$this -> load -> view('template', $data);
	}

}

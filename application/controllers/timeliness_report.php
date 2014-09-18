<?php
class Timeliness_Report extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['report_view'] = "timeliness_report_v";
		$this -> base_params($data);
	}

	public function download() {
		$this -> load -> database();
		$year = $this -> input -> post("year");
		$epiweek = $this -> input -> post("epiweek");
		$districts = District::getAll();
		//$districts = District::getAll();
		$access_level=$this->session->userdata('user_indicator');
		if($access_level=='county_clerk'){
			 $county_id=$this->session->userdata('county_id');
			 $districts = District::getDistrictCounty($county_id);
		
		}
		$data_buffer = "Week " . $epiweek . "\n";
		$data_buffer .= "District\tExpected Facilities\tTimely Facilities\tTimeliness(%)\t\n";
		$sql = "select district,count(*) as facilities from (select district from facility_surveillance_data fd where epiweek = '$epiweek' and reporting_year = '$year' and date_created <= DATE_SUB(week_ending, INTERVAL 4 day) group by facility) late_facilities group by district";
		$early_facilities_query = $this -> db -> query($sql);
		$early_facilities_data = $early_facilities_query -> result_array();
		$early_facilities = array();
		foreach ($early_facilities_data as $facility_data) {
			$early_facilities[$facility_data['district']] = $facility_data['facilities'];
		}
		//Get data for all epiweeks
		foreach ($districts as $district) {
			$district_expectation = Surveillance::getDistrictData($epiweek, $year, $district->id);
			$expected_facilities = $district_expectation->Expected; 
			$facilities = 0;
			if (isset($early_facilities[$district->id])) {
				$facilities = $early_facilities[$district->id]; 
			}
			$rate = 0;
			if ($expected_facilities > 0 && $facilities > 0) {
				$rate = number_format((($facilities / $expected_facilities) * 100), 1);
			}
			$data_buffer .= $district->Name . "\t" . $expected_facilities . "\t" . $facilities . "\t" . $rate . "\n";
		}

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=Timeliness Report.xls");
		// Fix for crappy IE bug in download.
		header("Pragma: ");
		header("Cache-Control: ");
		echo $data_buffer;
	}

	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "timeliness_report";
		$data['title'] = "System Reports";

		$data['content_view'] = "reports_v";
		$data['banner_text'] = "Intra-District Reporting";
		$data['link'] = "reports_management";

		$this -> load -> view('template', $data);
	}

}

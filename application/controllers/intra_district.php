<?php
class Intra_District extends MY_Controller {

	//required
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$this -> show_interface();
	}

	public function show_interface() {
		$data = array();
		$data['report_view'] = "intra_district_v";
		$data['diseases'] = Disease::getAll();
		$this -> base_params($data);
	}

	public function download() {
		$this -> load -> database();
		$year = $this -> input -> post("year");
		$disease = $this -> input -> post("disease");
		$disease_object = Disease::getName($disease);
		$data_buffer = $disease_object -> Name . "\n";
		$data_buffer .= "Epiweek\tCases\tDeaths\tIntra-district reporting rate(%)\t\n";
		//Get data for all epiweeks
		for ($x = 1; $x <= 53; $x++) {
			$cases = Surveillance::getDiseaseCases($disease, $x, $year);
			$deaths = Surveillance::getDiseaseDeaths($disease, $x, $year);
			$sql = "select sum(submitted) as submitted, sum(expected) as expected from (select submitted,expected from surveillance where epiweek = '" . $x . "' and reporting_year = '" . $year . "' group by district) expected_data";
			 
			$reporting_query = $this -> db -> query($sql);
			$reporting_data = $reporting_query -> result_array();
			$expected_facilities = 0;
			$reported_facilities = 0;
			if (isset($reporting_data[0])) {
				$expected_facilities = $reporting_data[0]['expected'];
				$reported_facilities = $reporting_data[0]['submitted'];
			}
			$rate = 0;
			if ($expected_facilities > 0 && $reported_facilities > 0) {
				$rate = number_format((($reported_facilities / $expected_facilities) * 100), 1);
			}
			$data_buffer .= $x . "\t" . $cases . "\t" . $deaths . "\t" . $rate . "\n";
		}
		
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=Intra District Reporting.xls");
		// Fix for crappy IE bug in download.
		header("Pragma: ");
		header("Cache-Control: ");
		echo $data_buffer;
	}
	
	public function base_params($data) {
		$data['styles'] = array("jquery-ui.css");
		$data['scripts'] = array("jquery-ui.js");
		$data['quick_link'] = "intra_district";
		$data['title'] = "System Reports";

		$data['content_view'] = "reports_v";
		$data['banner_text'] = "Intra-District Reporting";
		$data['link'] = "reports_management";

		$this -> load -> view('template', $data);
	}

}

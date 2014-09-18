<?php
class Map_Display extends MY_Controller {
	function __construct() {
		parent::__construct();
	}

	public function index() {
		$data = array();
		$this -> base_params($data);
	}

	public function get_data($disease, $type, $week_from, $week_to, $reporting_year, $district) {
		$this -> load -> database();
		$statisticts = "";
		if ($type == 1) {
			$statisticts = "lcase + gcase";
		} elseif ($type == 2) {
			$statisticts = "ldeath+gdeath";
		}
		$sql = "select sum($statisticts) as totals,f.id as facility_id, f.name as facility_name, concat(landline,' / ',mobile) as phone_numbers, latitude,longitude from facility_surveillance_data fs left join facilities f on fs.facility = f.facilitycode where epiweek between '$week_from' and '$week_to' and reporting_year = '$reporting_year' and fs.district = '$district' group by fs.facility";
		$statistics_query = $this -> db -> query($sql);
		$map_data = $statistics_query -> result_array();
		echo "<markers>";
		foreach ($map_data as $map_element) {

			//Check if there are any values to report
			if ($map_element['totals'] > 0) {
				//Make sure this facility has coordinates
				if (strlen($map_element['latitude']) > 0 && strlen($map_element['longitude']) > 0) {
					echo '<marker name="' . $map_element['facility_name'] . '" contact="' . $map_element['phone_numbers'] . '" lat="' . $map_element['latitude'] . '" lng="' . $map_element['longitude'] . '" facility_id="' . $map_element['facility_id'] . '" totals="' . $map_element['totals'] . '"/>';
				}

			}

		}
		echo "</markers>";
	}

	public function base_params($data) {
		$data['title'] = "Country Map View";
		$data['content_view'] = "map_display_v";
		$data['diseases'] = Disease::getAll();
		$data['districts'] = District::getAll();
		$data['scripts'] = array("markerclusterer/src/markerclusterer.js", "markerclusterer/src/jsapi.js");
		$data['banner_text'] = "Country Map View";
		$data['link'] = "map_display";
		$this -> load -> view("template", $data);
	}

}

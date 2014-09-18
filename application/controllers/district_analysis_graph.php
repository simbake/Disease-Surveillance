<?php
class District_Analysis_Graph extends MY_Controller {
	function __construct() {
		parent::__construct();
	}

	function index() {
		$this -> dashboard();
	}

	function get_cummulative_graph($year = 0, $diseases = "0", $district = 0, $facility = 0, $type = 1, $epiweek_from = 0, $epiweek_to = 53) {
		$graph_sub_title = "";
		if ($year == 0) {
			$year = date('Y');
		}
		//Start creating the sql query
		$epiweek_start_copy = $epiweek_from;
		$epiweek_start_copy2 = $epiweek_from;
		$epiweek_end_copy = $epiweek_to;
		$antigen_count = 0;
		$antigen_array = array();
		$disease_data = array();
		$selected_diseases = array();
		$this -> load -> database();
		//If no antigens have been specified, get data for dpt1 and 3
		if (strlen($diseases) == 1) {
			$selected_diseases = array("1");
		} else {
			$selected_diseases = explode("-", $diseases);
			array_pop($selected_diseases);
		}
		//For each of the selected diseases, retrieve the totals
		foreach ($selected_diseases as $selected_disease) {
			$disease_object = Disease::getDisease($selected_disease);
			//array_push($antigen_array, $antigen_titles[$selected_antigen]);
			//Finish creating the query based on whether the user is filtering down to a district or not
			$sql = "select disease,epiweek,sum(gcase+lcase) as cases from facility_surveillance_data where disease = '" . $selected_disease . "'";
			if ($district == 0) {
				$graph_sub_title = "Nationwide";
				$sql .= " and reporting_year = '" . $year . "' and abs(epiweek) between '" . $epiweek_from . "' and '" . $epiweek_to . "'";
			} else if ($district > 0) {
				if ($facility == 0) {
					$district_object = District::getDistrict($district);
					$graph_sub_title = $district_object[0] -> Name . " District";
					$sql .= " and district = '" . $district . "' and reporting_year = '" . $year . "' and abs(epiweek) between '" . $epiweek_from . "' and '" . $epiweek_to . "'";
				}
				if ($facility > 0) {
					$facility_name = Facilities::getFacilityName($facility);
					$graph_sub_title = $facility_name;
					$sql .= " and facility = '" . $facility . "' and reporting_year = '" . $year . "' and abs(epiweek) between '" . $epiweek_from . "' and '" . $epiweek_to . "'";
				}
			}
			$sql .= " group by epiweek order by abs(epiweek) asc";
			echo $sql;
			$query = $this -> db -> query($sql);
			$cases = $query -> result_array();
			foreach ($cases as $case) {
				$disease_data[$case['disease']][$case['epiweek']] = $case['cases'];
				$disease_data[$case['disease']]['disease'] = $disease_object -> Name;
			} 
		}
		$chart = '<chart caption="'.$graph_sub_title.'" subcaption="Cases For ' . $year . '" connectNullData="1" showValues="0" formatNumberScale="0" lineDashGap="6" xAxisName="Epiweek" yAxisName="Number of Cases" showValues="0" showBorder="0" showAlternateHGridColor="0" divLineAlpha="10"  bgColor="FFFFFF"  exportEnabled="1" exportHandler="' . base_url() . 'Scripts/FusionCharts/ExportHandlers/PHP/FCExporter.php" exportAtClient="0" exportAction="download">
<categories>';

		for ($epiweek_from = $epiweek_from; $epiweek_from <= $epiweek_to; $epiweek_from++) {
			$chart .= '<category label="' . $epiweek_from . '"/>';
		}
		$chart .= '</categories>';
		//Loop through all the months in the specified year
		$days = array();
		$counter = 0;
		if (sizeof($disease_data) > 0) {
			foreach ($selected_diseases as $selected_disease) {
				$disease_dataset = $disease_data[$selected_disease];
				$chart .= "<dataset seriesName='" . $disease_dataset['disease'] . "'>";
				//Keep track of the cummulative totals
				$cummulative = 0;
				$counter = 0;
				while ($epiweek_start_copy < $epiweek_end_copy) {
					if (isset($disease_dataset[$epiweek_start_copy])) {
						//check if the next value is non-existent, if so, display a dotted line, else, display a kawaida line
						if (sizeof($disease_dataset) != $counter) {
							if ($type == 0) {
								$cummulative += $disease_dataset[$epiweek_start_copy];
							} else if ($type == 1) {
								$cummulative = $disease_dataset[$epiweek_start_copy];
							}

							if (isset($disease_dataset[$epiweek_start_copy + 1])) {
								$chart .= '<set value="' . $cummulative . '"/>';
							} else {
								$chart .= '<set value="' . $cummulative . '" dashed="1"/>';
							}
						}

					} else {
						$chart .= '<set  />';
					}
					$counter++;

					$epiweek_start_copy++;
				}
				$epiweek_start_copy = $epiweek_start_copy2;
				$chart .= "</dataset>";
			}
		}

		$chart .= "</chart>";
		echo $chart;
	}

}

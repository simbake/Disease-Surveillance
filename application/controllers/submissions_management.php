<?php

class Submissions_Management extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	function index() {
		$currentyear = date('Y');
		$submissions = Surveillance::getLastEpiweek($currentyear);

		$provinces = Province::getAll();

		$diseases = Disease::getAllObjects();

		$districts = District::getAll();

		$years = Surveillance::getYears();

		//an array with variables that can be accessed in the 'submissions_v' view
		$data['selected_epiweek'] = $submissions -> epiweek;
		$data['provinces'] = $provinces;
		$data['districts'] = $districts;
		$data['years'] = $years;
		$data['diseases'] = $diseases;
		$data['values'] = $this -> getAll($submissions -> epiweek);
		$data['content_view'] = 'submissions_v';
		$this -> base_params($data);

	}

	function filter() {
		$epiweek = $_POST['epiweek'];
		$filterdyear = $_POST['filteryear'];
		$provinceId = $_POST['province'];

		$districtId = $_POST['district'];
		$district = District::getName($districtId);
		$districtName = $district -> Name;
		$districts = District::getAll();
		$provinces = Province::getAll();

		$diseases = Disease::getAllObjects();

		$years = Surveillance::getYears();

		$data['selected_epiweek'] = $epiweek;
		$data['provinces'] = $provinces;
		$data['districts'] = $districts;
		$data['years'] = $years;
		$data['diseases'] = $diseases;
		$data['districtName'] = $districtName;
		$data['values'] = $this -> getPerDistrict($districtId, $epiweek, $provinceId, $filterdyear);
		$data['content_view'] = 'submissions_distr_v';
		$this -> base_params($data);
	}

	function getDistrict() {
		$segs = $this -> uri -> segment_array();
		$provinceId = $segs[3];
		$districts = District::getNameAndId($provinceId);
		$allDistricts = "";
		foreach ($districts as $districts) {
			$allDistricts .= $districts -> id;
			$allDistricts .= "+";
			$allDistricts .= $districts -> Name;
			$allDistricts .= "_";
		}
		echo $allDistricts;
	}

	public function getAll($epiweek) {
		$diseases = Disease::getAllObjects();
		$value = "";
		foreach ($diseases as $disease) {
			$sums = Surveillance::getSums($epiweek, $disease -> id);
			$value[$disease -> id][0] = $sums -> lmcase;
			$value[$disease -> id][1] = $sums -> lfcase;
			$value[$disease -> id][2] = $sums -> lmdeath;
			$value[$disease -> id][3] = $sums -> lfdeath;
			$value[$disease -> id][4] = $sums -> gmcase;
			$value[$disease -> id][5] = $sums -> gfcase;
			$value[$disease -> id][6] = $sums -> gmdeath;
			$value[$disease -> id][7] = $sums -> gfdeath;

			$cumulative = Surveillance::getCumulative($epiweek, $disease -> id, date('Y'));
			$value[$disease -> id][8] = $cumulative -> lmcase;
			$value[$disease -> id][9] = $cumulative -> lfcase;
			$value[$disease -> id][10] = $cumulative -> lmdeath;
			$value[$disease -> id][11] = $cumulative -> lfdeath;
			$value[$disease -> id][12] = $cumulative -> gmcase;
			$value[$disease -> id][13] = $cumulative -> gfcase;
			$value[$disease -> id][14] = $cumulative -> gmdeath;
			$value[$disease -> id][15] = $cumulative -> gfdeath;
		}
		return $value;
	}

	public function provincialDetails($epiweek, $diseaseId) {
		$currentyear = date('Y');
		$submissions = Surveillance::getLastEpiweek($currentyear);

		$provinces = Province::getAll();

		$years = Surveillance::getYears();

		$data['selected_epiweek'] = $submissions -> epiweek;
		$data['provinces'] = $provinces;
		$data['years'] = $years;
		$data['values'] = $this -> getAllProvinces($epiweek, $diseaseId, $provinces);

		$name = Disease::getName($diseaseId);
		$data['diseaseName'] = $name -> Name;
		$data['content_view'] = 'submissions_prov_v';
		$this -> base_params($data);
	}

	public function getAllProvinces($epiweek, $diseaseId, $provinces) {
		$value = "";
		foreach ($provinces as $provinces) {
			$sums = Surveillance::getProvincialSums($epiweek, $diseaseId, $provinces -> id);
			$value[$provinces -> id][0] = $sums -> lmcase;
			$value[$provinces -> id][1] = $sums -> lfcase;
			$value[$provinces -> id][2] = $sums -> lmdeath;
			$value[$provinces -> id][3] = $sums -> lfdeath;
			$value[$provinces -> id][4] = $sums -> gmcase;
			$value[$provinces -> id][5] = $sums -> gfcase;
			$value[$provinces -> id][6] = $sums -> gmdeath;
			$value[$provinces -> id][7] = $sums -> gfdeath;

			$cumulative = Surveillance::getProvincialCumulative($epiweek, $diseaseId, $provinces -> id, date('Y'));
			$value[$provinces -> id][8] = $cumulative -> lmcase;
			$value[$provinces -> id][9] = $cumulative -> lfcase;
			$value[$provinces -> id][10] = $cumulative -> lmdeath;
			$value[$provinces -> id][11] = $cumulative -> lfdeath;
			$value[$provinces -> id][12] = $cumulative -> gmcase;
			$value[$provinces -> id][13] = $cumulative -> gfcase;
			$value[$provinces -> id][14] = $cumulative -> gmdeath;
			$value[$provinces -> id][15] = $cumulative -> gfdeath;
		}
		return $value;
	}

	public function getPerDistrict($districtId, $epiweek, $provinceId, $filterdyear) {
		$diseases = Disease::getAllObjects();
		$value = "";
		foreach ($diseases as $disease) {
			$sums = Surveillance::getDistrictSums($districtId, $epiweek, $filterdyear, $disease -> id);
			$value[$disease -> id][0] = $sums -> lmcase;
			$value[$disease -> id][1] = $sums -> lfcase;
			$value[$disease -> id][2] = $sums -> lmdeath;
			$value[$disease -> id][3] = $sums -> lfdeath;
			$value[$disease -> id][4] = $sums -> gmcase;
			$value[$disease -> id][5] = $sums -> gfcase;
			$value[$disease -> id][6] = $sums -> gmdeath;
			$value[$disease -> id][7] = $sums -> gfdeath;

			$cumulative = Surveillance::getDistrictCumulative($districtId, $epiweek, $provinceId, $disease -> id, $filterdyear);
			$value[$disease -> id][8] = $cumulative -> lmcase;
			$value[$disease -> id][9] = $cumulative -> lfcase;
			$value[$disease -> id][10] = $cumulative -> lmdeath;
			$value[$disease -> id][11] = $cumulative -> lfdeath;
			$value[$disease -> id][12] = $cumulative -> gmcase;
			$value[$disease -> id][13] = $cumulative -> gfcase;
			$value[$disease -> id][14] = $cumulative -> gmdeath;
			$value[$disease -> id][15] = $cumulative -> gfdeath;
		}
		return $value;
	}

	function getTrend($disease = 0, $from = "", $to = "", $year = 0) {
		if ($year == 0) {
			$year = date('Y');
		}
		if ($from == "") {
			$from = 1;
		}
		if ($to == "") {
			$to = 52;
		}
		$label_from = "Epiweek " . $from;
		$label_to = "Epiweek " . $to;
		$loop_from = $from;
		$loop_to = $to;
		$disease_name = "";
		if ($disease == "0") {
			$disease = Disease::getFirstDisease();
			$disease_name = $disease -> Name;
			$disease = $disease -> id;
		} else {
			$disease = Disease::getDisease($disease);
			$disease_name = $disease -> Name;
			$disease = $disease -> id;
		}
		$this -> load -> database();
		//Save the daily data points
		$days = array();
		//Start generating the graph
		$chart = '<chart caption="Weekly Activity for ' . $disease_name . '" connectNullData="1" lineDashGap="6"  labelDisplay="Rotate" slantLabels="1" subcaption="From ' . $label_from . ' to ' . $label_to . '" xAxisName="Epiweek" yAxisName="Reported No." showValues="0" showBorder="0" showAlternateHGridColor="0" divLineAlpha="10"  bgColor="FFFFFF"  exportEnabled="1" exportHandler="' . base_url() . 'Scripts/FusionCharts/ExportHandlers/PHP/FCExporter.php" exportAtClient="0" exportAction="download">';
		//Generate the categories i.e. the dates
		$chart .= '<categories>';
		$counter = 0;
		while ($loop_from <= $loop_to) {
			$days[$counter] = $loop_from;
			$chart .= '<category label="' . $loop_from . '"/>';
			$loop_from++;
			$counter++;
		}
		$chart .= '</categories>';
		$sql = "SELECT sum(lcase+gcase) as total,epiweek FROM `surveillance` s where abs(epiweek) between '" . $from . "' and '" . $to . "' and reporting_year = '" . $year . "' and disease = '" . $disease . "' group by epiweek order by abs(epiweek) asc";
		$query = $this -> db -> query($sql);
		$disease_data = $query -> result_array();
		$chart .= '<dataset seriesName="Cases">';
		//make the date the key of the array
		$values_array = array();
		foreach ($disease_data as $data) {
			$values_array[$data['epiweek']] = $data['total'];
		}
		$counter = 0;
		foreach ($days as $day) {
			//if a value exists, continue
			if (isset($values_array[$day])) {
				//check if the next value is non-existent, if so, display a dotted line, else, display a kawaida line
				if (sizeof($values_array) != $counter) {
					if (isset($values_array[$days[$counter + 1]])) {

						$chart .= '<set value="' . $values_array[$day] . '"/>';
					} else {
						$chart .= '<set value="' . $values_array[$day] . '" dashed="1"/>';
					}
				}

			} else {
				$chart .= '<set  />';
			}
			$counter++;
		}
		$chart .= '</dataset>';
		//Do one for deaths
		$sql = "SELECT sum(ldeath+gdeath) as total,epiweek FROM `surveillance` s where abs(epiweek) between '" . $from . "' and '" . $to . "' and reporting_year = '" . $year . "' and disease = '" . $disease . "' group by epiweek order by abs(epiweek) asc";
		$query = $this -> db -> query($sql);
		$disease_data = $query -> result_array();
		$chart .= '<dataset seriesName="Deaths">';
		//make the date the key of the array
		$values_array = array();
		foreach ($disease_data as $data) {
			$values_array[$data['epiweek']] = $data['total'];
		}
		$counter = 0;
		foreach ($days as $day) {
			//if a value exists, continue
			if (isset($values_array[$day])) {
				//check if the next value is non-existent, if so, display a dotted line, else, display a kawaida line
				if (sizeof($values_array) != $counter) {
					if (isset($values_array[$days[$counter + 1]])) {

						$chart .= '<set value="' . $values_array[$day] . '"/>';
					} else {
						$chart .= '<set value="' . $values_array[$day] . '" dashed="1"/>';
					}
				}

			} else {
				$chart .= '<set  />';
			}
			$counter++;
		}
		$chart .= '</dataset>';

		$chart .= '
		<styles>
<definition>
<style name="Anim1" type="animation" param="_xscale" start="0" duration="1"/>
<style name="Anim2" type="animation" param="_alpha" start="0" duration="0.6"/>
<style name="DataShadow" type="Shadow" alpha="40"/>
</definition>
<application>
<apply toObject="DIVLINES" styles="Anim1"/>
<apply toObject="HGRID" styles="Anim2"/>
<apply toObject="DATALABELS" styles="DataShadow,Anim2"/>
</application>
</styles>
		</chart>';
		echo $chart;
	}

	public function base_params($data) {
		$data['title'] = "Submissions";
		$data['banner_text'] = "Submissions";
		$data['link'] = "submissions_management";
		$this -> load -> view("template", $data);
	}

}
?>
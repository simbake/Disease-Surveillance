<?php
class Surveillance extends Doctrine_Record {
	public function setTableDefinition() {
		$this -> hasColumn('Disease', 'varchar', 32);
		$this -> hasColumn('Lcase', 'varchar', 32);
		$this -> hasColumn('Ldeath', 'varchar', 32);
		$this -> hasColumn('Date_Created', 'varchar', 32);
		$this -> hasColumn('Created_By', 'varchar', 32);
		$this -> hasColumn('Epiweek', 'varchar', 4);
		$this -> hasColumn('Submitted', 'varchar', 15);
		$this -> hasColumn('Timely_Reports', 'varchar', 10);
		$this -> hasColumn('Expected', 'varchar', 15);
		$this -> hasColumn('District', 'varchar', 15);
		$this -> hasColumn('Week_Ending', 'varchar', 20);
		$this -> hasColumn('Gcase', 'varchar', 32);
		$this -> hasColumn('Gdeath', 'varchar', 32);
		$this -> hasColumn('Reported_By', 'varchar', 32);
		$this -> hasColumn('Designation', 'varchar', 32);
		$this -> hasColumn('Date_Reported', 'varchar', 32);
		$this -> hasColumn('Reporting_Year', 'varchar', 5);
		$this -> hasColumn('Total_Diseases', 'varchar', 5);
	}

	public function setUp() {
		$this -> setTableName('surveillance');
		$this -> hasOne('Disease as Disease_Object', array('local' => 'Disease', 'foreign' => 'id'));
		$this -> hasOne('Users as Record_Creator', array('local' => 'Created_By', 'foreign' => 'id'));
		$this -> hasOne('District as District_Object', array('local' => 'District', 'foreign' => 'id'));
	}

	public static function getWeekEnding($year, $epiweek) {
		$query = Doctrine_Query::create() -> select("Week_Ending") -> from("surveillance") -> where("Reporting_Year = '$year' and epiweek = '$epiweek'") -> limit(1);
		$week_ending = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		if (isset($week_ending[0])) {
			return $week_ending[0]['Week_Ending'];
		} else {
			return null;
		}

	}

	public static function getRawData($year, $start_week, $end_week) {
		$query = Doctrine_Query::create() -> select("*") -> from("surveillance") -> where("Reporting_Year = '$year' and abs(epiweek) between '$start_week' and '$end_week'") -> orderBy("abs(epiweek)");
		$surveillance = $query -> execute();
		return $surveillance;
	}

	public static function getRawDataArray($year, $start_week, $end_week) {
		$query = Doctrine_Query::create() -> select("s.*, s.Disease_Object.Name as Disease_Name, d.Name as District_Name, d.Province_Object.Name as Province_Name,d.County_Object.Name as County_Name") -> from("surveillance s, s.District_Object d") -> where("Reporting_Year = '$year' and abs(Epiweek) between '$start_week' and '$end_week'") -> OrderBy("abs(Epiweek) asc");
		$surveillance = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $surveillance;
	}

	public static function getWeeklySummaries($year, $epiweek, $district) {
		$query = Doctrine_Query::create() -> select("Submitted, Expected,Timely_Reports, Disease, Lcase+Gcase as Cases, Ldeath+Gdeath as Deaths") -> from("surveillance") -> where("Reporting_Year = '$year' and Epiweek = '$epiweek' and District = '$district'") -> OrderBy("abs(Disease) asc");
		$surveillance = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $surveillance;
	}

	public static function getWeeklyDiseaseSummaries($year, $epiweek, $disease) {
		$query = Doctrine_Query::create() -> select("sum(Lcase+Gcase) as Cases, sum(Ldeath+Gdeath) as Deaths") -> from("surveillance") -> where("Reporting_Year = '$year' and Epiweek = '$epiweek' and Disease = '$disease'");
		$surveillance = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $surveillance[0];
	}

	public static function getAnnualDiseaseSummaries($year, $disease) {
		$query = Doctrine_Query::create() -> select("sum(Lcase+Gcase) as Cases, sum(Ldeath+Gdeath) as Deaths") -> from("surveillance") -> where("Reporting_Year = '$year' and Disease = '$disease'");
		$surveillance = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $surveillance[0];
	}

	public function getLastEpiweek($currentyear) {
		$query = Doctrine_Query::create() -> select("MAX(Epiweek) AS epiweek") -> from("surveillance") -> where("Reporting_Year='$currentyear'");
		$result = $query -> execute();
		return $result[0];
	}

	public function getYears() {
		$query = Doctrine_Query::create() -> select("DISTINCT Reporting_Year as filteryear") -> from("surveillance") -> orderBy("Reporting_Year DESC");
		$result = $query -> execute();
		return $result;
	}

	public function getSums($epiweek, $diseaseId) {
		$query = Doctrine_Query::create() -> select("SUM( Lmcase ) AS lmcase, SUM( Lfcase ) AS lfcase, SUM( Lmdeath ) AS lmdeath, SUM( Lfdeath ) AS lfdeath, SUM( Gmcase ) AS gmcase, SUM( Gfcase ) AS gfcase, SUM( Gmdeath ) AS gmdeath, SUM( Gfdeath ) AS gfdeath") -> from("surveillance") -> where("Epiweek = '$epiweek' and Disease='$diseaseId'");
		$result = $query -> execute();
		return $result[0];
	}

	public function getCumulative($epiweek, $diseaseId, $currentYear) {
		$query = Doctrine_Query::create() -> select("SUM( Lmcase ) AS lmcase, SUM( Lfcase ) AS lfcase, SUM( Lmdeath ) AS lmdeath, SUM( Lfdeath ) AS lfdeath, SUM( Gmcase ) AS gmcase, SUM( Gfcase ) AS gfcase, SUM( Gmdeath ) AS gmdeath, SUM( Gfdeath ) AS gfdeath") -> from("surveillance") -> where("Epiweek BETWEEN 1 AND '$epiweek' AND Disease='$diseaseId' and Reporting_Year='$currentYear'");
		$result = $query -> execute();
		return $result[0];
	}

	public function getProvincialSums($epiweek, $diseaseId, $provinceId) {
		$query = Doctrine_Query::create() -> select("SUM( Lmcase ) AS lmcase, SUM( Lfcase ) AS lfcase, SUM( Lmdeath ) AS lmdeath, SUM( Lfdeath ) AS lfdeath, SUM( Gmcase ) AS gmcase, SUM( Gfcase ) AS gfcase, SUM( Gmdeath ) AS gmdeath, SUM( Gfdeath ) AS gfdeath") -> from("surveillance,district") -> where("Epiweek = '$epiweek' AND Disease='$diseaseId' AND surveillance.District = district.id AND district.Province = '$provinceId' ");
		$result = $query -> execute();
		return $result[0];
	}

	public function getProvincialCumulative($epiweek, $diseaseId, $provinceId, $currentYear) {
		$query = Doctrine_Query::create() -> select("SUM( Lmcase ) AS lmcase, SUM( Lfcase ) AS lfcase, SUM( Lmdeath ) AS lmdeath, SUM( Lfdeath ) AS lfdeath, SUM( Gmcase ) AS gmcase, SUM( Gfcase ) AS gfcase, SUM( Gmdeath ) AS gmdeath, SUM( Gfdeath ) AS gfdeath") -> from("surveillance,district") -> where("Epiweek BETWEEN 1 AND '$epiweek' AND Reporting_Year='$currentYear' AND district.Province = '$provinceId' AND surveillance.District = district.id AND Disease='$diseaseId'");
		$result = $query -> execute();
		return $result[0];
	}

	public function getDistrictSums($districtId, $epiweek, $selectedYear, $diseaseId) {
		$query = Doctrine_Query::create() -> select("SUM(lmcase) AS lmcase,SUM(Lfcase) AS lfcase,SUM(Lmdeath) AS lmdeath,SUM(Lfdeath) AS lfdeath,SUM(Gmcase) AS gmcase,SUM(Gfcase) AS gfcase,SUM(Gmdeath) AS gmdeath,SUM(Gfdeath) AS gfdeath") -> from("surveillance") -> where("District = '$districtId' AND surveillance.Disease = '$diseaseId' AND Epiweek='$epiweek' AND Reporting_Year = '$selectedYear'");
		$result = $query -> execute();
		return $result[0];
	}

	public function getDistrictCumulative($districtId, $epiweek, $provinceId, $diseaseId, $selectedYear) {
		$query = Doctrine_Query::create() -> select("SUM(Lmcase) AS lmcase,SUM(Lfcase) AS lfcase,SUM(Lmdeath) AS lmdeath,SUM(Lfdeath) AS lfdeath,SUM(Gmcase) AS gmcase,SUM(Gfcase) AS gfcase,SUM(Gmdeath) AS gmdeath,SUM(Gfdeath) AS gfdeath") -> from("surveillance,district") -> where("District = '$districtId' AND district.Province = '$provinceId' AND surveillance.Disease = '$diseaseId' AND Epiweek between 1 and '$epiweek' AND Reporting_Year = '$selectedYear'");

		$result = $query -> execute();
		return $result[0];
	}

	public function getPrediction() {
		$year_query = Doctrine_Query::create() -> select("Reporting_Year") -> from("Surveillance") -> orderBy("Reporting_Year DESC") -> limit(1);
		$year_result = $year_query -> execute();
		$last_year = $year_result[0] -> Reporting_Year;
		$week_query = Doctrine_Query::create() -> select("Epiweek,Week_Ending") -> from("Surveillance") -> where("Reporting_Year = '$last_year'") -> orderBy("abs(Epiweek) DESC") -> limit(1);
	
		$week_result = $week_query -> execute();
		$last_epiweek = $week_result[0] -> Epiweek;
		$last_weekending = $week_result[0] -> Week_Ending;
		$result[0] = $last_year;
		$result[1] = $last_epiweek;
		$result[2] = $last_weekending;
		return $result;
	}

	public function getDistrictData($epiweek, $year, $district) {
		$query = Doctrine_Query::create() -> select("id,expected") -> from("surveillance") -> where("Reporting_Year='$year' and Epiweek='$epiweek' and District = '$district'") -> limit(1);
		$result = $query -> execute();
		return $result[0];
	}

	public function getDistrictDiseaseData($epiweek, $year, $district, $disease) {
		$query = Doctrine_Query::create() -> select("id") -> from("surveillance") -> where("Reporting_Year='$year' and Epiweek='$epiweek' and District = '$district' and Disease = '$disease'") -> limit(1);
		$result = $query -> execute();
		return $result[0];
	}

	public function getSurveillanceData($epiweek, $reporting_year, $district) {
		$query = Doctrine_Query::create() -> select("*") -> from("surveillance") -> where("Reporting_Year='$reporting_year' and Epiweek='$epiweek' and District = '$district'");
		$result = $query -> execute();
		return $result;
	}

	public function getSurveillance($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("surveillance") -> where("id = '$id'");
		$result = $query -> execute();
		return $result[0];
	}

	public function getDuplicates($year, $epiweek) {
		$total_diseases = Disease::getTotal();
		$query = Doctrine_Query::create() -> select("District, Reporting_Year, Week_Ending, Epiweek, count(id) as Records") -> from("surveillance") -> where("epiweek = '$epiweek' and Reporting_Year = '$year'") -> groupBy("District") -> having("Records > '$total_diseases'");
		$result = $query -> execute();
		return $result;
	}

	public function getSurveillanceDataArray($epiweek, $reporting_year, $district) {
		$query = Doctrine_Query::create() -> select("*") -> from("surveillance") -> where("Reporting_Year='$reporting_year' and Epiweek='$epiweek' and District = '$district'");
		$result = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $result;
	}

	public function getSurveillanceDataRange($first_surveillance_id, $last_surveillance_id) {
		$query = Doctrine_Query::create() -> select("*") -> from("surveillance") -> where("id between '$first_surveillance_id' and $last_surveillance_id");
		$result = $query -> execute();
		return $result;
	}

	public function getDiseaseCases($disease, $epiweek, $year) {
		$query = Doctrine_Query::create() -> select("sum(Lcase+Gcase) as Cases") -> from("surveillance") -> where("Reporting_Year='$year' and Epiweek='$epiweek' and Disease = '$disease'");
		$result = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $result[0]['Cases'];
	}

	public function getDiseaseDeaths($disease, $epiweek, $year) {
		$query = Doctrine_Query::create() -> select("sum(Ldeath+Gdeath) as Cases") -> from("surveillance") -> where("Reporting_Year='$year' and Epiweek='$epiweek' and Disease = '$disease'");
		$result = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $result[0]['Cases'];
	}

	public function getReports($year, $epiweek) {
		$query = Doctrine_Query::create() -> select("*") -> from("surveillance") -> where("Reporting_Year = '$year' and Epiweek = '$epiweek'") -> groupBy("District");
		$result = $query -> execute();
		return $result;
	}

}

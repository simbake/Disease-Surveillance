<?php
class Elnino extends Doctrine_Record {
    public function setTableDefinition() {
        $this -> hasColumn('Epiweek', 'varchar', 4);
        $this -> hasColumn('District', 'varchar', 15);
        $this -> hasColumn('Week_Ending', 'varchar', 20);
        $this -> hasColumn('Reported_By', 'varchar', 32);
        $this -> hasColumn('Telephone', 'varchar', 15);
        $this -> hasColumn('Email', 'varchar', 32);
        $this -> hasColumn('Drug_month', 'varchar', 15);
        $this -> hasColumn('Buffer_Ors', 'int', 1);
        $this -> hasColumn('Buffer_Iv', 'varchar', 1);
        $this -> hasColumn('Antimalarial', 'varchar', 1);
        $this -> hasColumn('Steering_Group', 'varchar', 1);
        $this -> hasColumn('Cholera', 'varchar', 1);
        $this -> hasColumn('Malaria_Positivity', 'varchar', 1);
        $this -> hasColumn('Rain', 'varchar', 1);
        $this -> hasColumn('Floods', 'varchar', 1);
        $this -> hasColumn('Displaced_Persons', 'int', 5);
        $this -> hasColumn('Displaced_Persons_7', 'int', 5);
        $this -> hasColumn('Deaths_7', 'int', 5);
        $this -> hasColumn('Deaths', 'int', 5);
        $this -> hasColumn('Outbreak_Name', 'varchar', 25);
        $this -> hasColumn('Date_Reported', 'varchar', 32);
        $this -> hasColumn('Date_Created', 'varchar', 32);
        $this -> hasColumn('Reporting_Year', 'varchar', 5);
    }

    public function setUp() {
        $this -> setTableName('elnino');
        $this -> hasOne('Users as Record_Creator', array('local' => 'Created_By', 'foreign' => 'id'));
        $this -> hasOne('District as District_Object', array('local' => 'District', 'foreign' => 'id'));
    }

    public static function getWeekEnding($year, $epiweek) {
        $query = Doctrine_Query::create() -> select("Week_Ending") -> from("Elnino") -> where("Reporting_Year = '$year' and epiweek = '$epiweek'") -> limit(1);
        $week_ending = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
        if (isset($week_ending[0])) {
            return $week_ending[0]['Week_Ending'];
        } else {
            return null;
        }

    }

    public function getLastEpiweek($currentyear) {
        $query = Doctrine_Query::create() -> select("MAX(Epiweek) AS epiweek") -> from("Elnino") -> where("Reporting_Year='$currentyear'");
        $result = $query -> execute();
        return $result[0];
    }

    public function getYears() {
        $query = Doctrine_Query::create() -> select("DISTINCT Reporting_Year as filteryear") -> from("Elnino") -> orderBy("Reporting_Year DESC");
        $result = $query -> execute();
        return $result;
    }

    public function getPrediction() {
        $year_query = Doctrine_Query::create() -> select("Reporting_Year") -> from("Elnino") -> orderBy("Reporting_Year DESC") -> limit(1);
        $year_result = $year_query -> execute();
        $last_year = $year_result[0] -> Reporting_Year;
        $week_query = Doctrine_Query::create() -> select("Epiweek,Week_Ending") -> from("Elnino") -> where("Reporting_Year = '$last_year'") -> orderBy("abs(Epiweek) DESC") -> limit(1);
        $week_result = $week_query -> execute();
        $last_epiweek = $week_result[0] -> Epiweek;
        $last_weekending = $week_result[0] -> Week_Ending;
        $result[0] = $last_year;
        $result[1] = $last_epiweek;
        $result[2] = $last_weekending;
        return $result;
    }

    public function getDistrictData($epiweek, $year, $district) {
        $query = Doctrine_Query::create() -> select("id") -> from("Elnino") -> where("Reporting_Year='$year' and Epiweek='$epiweek' and District = '$district'") -> limit(1);
        $result = $query -> execute();
        return $result[0];
    }

    public function getElninoData($epiweek, $reporting_year, $district) {
        $query = Doctrine_Query::create() -> select("*") -> from("Elnino") -> where("Reporting_Year='$reporting_year' and Epiweek='$epiweek' and District = '$district'");
        $result = $query -> execute();
        return $result;
    }

    public function getElnino($id) {
        $query = Doctrine_Query::create() -> select("*") -> from("Elnino") -> where("id = '$id'");
        $result = $query -> execute();
        return $result[0];
    }

    public function getTotalBuffers($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_Buffers") -> from("Elnino") -> where("Buffer_Ors = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_Buffers;
    }

    public function getTotalIvs($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_Ivs") -> from("Elnino") -> where("Buffer_Iv = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_Ivs;
    }

    public function getTotalAntimalarial($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_Antimalarial") -> from("Elnino") -> where("Antimalarial = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_Antimalarial;
    }

    public function getTotalSteeringGroup($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_SteeringGroup") -> from("Elnino") -> where("Steering_Group = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_SteeringGroup;
    }

    public function getTotalCholera($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_Cholera") -> from("Elnino") -> where("Cholera = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_Cholera;
    }

    public function getTotalMalariaPositivity($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_MalariaPositivity") -> from("Elnino") -> where("Malaria_Positivity = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_MalariaPositivity;
    }

    public function getTotalRain($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_Rain") -> from("Elnino") -> where("Rain = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_Rain;
    }

    public function getTotalFloods($epiweek) {
        $query = Doctrine_Query::create() -> select("COUNT(District) as Total_Floods") -> from("Elnino") -> where("Floods = '0' AND Epiweek = '$epiweek'");
        $count = $query -> execute();
        return $count[0] -> Total_Floods;
    }

    public function getTotalDisplacedPersons() {
        $query = Doctrine_Query::create() -> select("SUM(Displaced_Persons) as Total_DisplacedPersons") -> from("Elnino");
        $count = $query -> execute();
        return $count[0] -> Total_DisplacedPersons;
    }

    public function getTotalDeaths() {
        $query = Doctrine_Query::create() -> select("SUM(Deaths) as Total_Deaths") -> from("Elnino");
        $count = $query -> execute();
        return $count[0] -> Total_Deaths;
    }

    public function getEpiweeks() {
        $query = Doctrine_Query::create() -> select("DISTINCT Epiweek AS Epiweek") -> from("Elnino") -> orderBy("abs(Epiweek) Asc");
        $result = $query -> execute();
        return $result;
    }

    public function getElninoDistricts($epiweek, $province) {
        $query = Doctrine_Query::create() -> select("District.Name,Elnino.Buffer_Ors") -> from("Elnino,District") -> where("Elnino.Buffer_Ors = '0' AND Epiweek = '$epiweek' AND Elnino.District = District.id AND District.Province = '$province' ");
        $result = $query -> execute();
        return $result;
    }

    public static function getRawDataArray($year, $start_week, $end_week) {
        $query = Doctrine_Query::create() -> select("Epiweek,District,Week_Ending,Reported_By,Drug_month,Buffer_Ors,Buffer_Iv,Antimalarial,Steering_Group,Cholera,Malaria_Positivity,Rain,Floods,Displaced_Persons,Displaced_Persons_7,Deaths_7,Deaths,Outbreak_Name,Date_Reported") -> from("Elnino") -> where("Reporting_Year = '$year' and abs(Epiweek) between '$start_week' and '$end_week'") -> OrderBy("abs(Epiweek) asc");
        $elnino = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
        return $elnino;
    }

}

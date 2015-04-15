<?php
class County extends Doctrine_Record {

	public function setTableDefinition() {
		$this -> hasColumn('Name', 'varchar', 100);
		$this -> hasColumn('Province', 'int', 14); 
		$this -> hasColumn('Latitude', 'varchar', 100);
		$this -> hasColumn('Longitude', 'varchar', 100);
		$this -> hasColumn('Disabled', 'varchar', 1);
	}//end setTableDefinition

	public function setUp() {
		$this -> setTableName('county');
		$this -> hasOne('Province as Province_Object', array('local' => 'Province', 'foreign' => 'id')); 
	}//end setUp

	public function getAll() {
		$query = Doctrine_Query::create() -> select("*") -> from("County")->where("Disabled = '0'")->OrderBy("Name asc");
		$countyData = $query -> execute();
		return $countyData;
	}//end getAll

	public function getCounty($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("County") -> where("id = '$id' and Disabled='0'")->orderBy("Name asc");
		$countyData = $query -> execute();
		return $countyData;
	}

	public static function getProvinceCounties($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("County") -> where("Province = '$id' and Disabled = '0'");
		$countyData = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $countyData;
	} 

	public function getName($id) {
		$query = Doctrine_Query::create() -> select("Name") -> from("County") -> where(" id = '$id'");
		$countyData = $query -> execute();
		return $countyData[0];
	} 
	
	public function getNames($id) {
		$query = Doctrine_Query::create() -> select("Name") -> from("County") -> where(" id = '$id'");
		$countyData = $query -> execute();
		return $countyData;
	} 
	
	public static function getTotalNumber() {
		$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Counties") -> from("County");
		$countyData = $query -> execute();
		return $countyData[0] -> Total_Counties;
	}

	public function getPagedCounties() {
		$query = Doctrine_Query::create() -> select("*") -> from("County")-> orderBy("Name");
		$countyData = $query -> execute();
		return $countyData;
	}

}//end class
?>
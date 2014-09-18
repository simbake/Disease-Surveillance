<?php
class District extends Doctrine_Record {

	public function setTableDefinition() {
		$this -> hasColumn('Name', 'varchar', 100);
		$this -> hasColumn('Province', 'int', 14);
		$this -> hasColumn('Comment', 'varchar', 32);
		$this -> hasColumn('Flag', 'int', 32);
		$this -> hasColumn('Latitude', 'varchar', 100);
		$this -> hasColumn('Longitude', 'varchar', 100);
		$this -> hasColumn('Disabled', 'varchar', 1);
		$this -> hasColumn('County', 'varchar', 10);
	}//end setTableDefinition

	public function setUp() {
		$this -> setTableName('districts');
		$this -> hasOne('Province as Province_Object', array('local' => 'Province', 'foreign' => 'id'));
		$this -> hasOne('County as County_Object', array('local' => 'County', 'foreign' => 'id'));
		$this -> hasMany('Surveillance as Surveillance', array('local' => 'id', 'foreign' => 'District'));
	}//end setUp

	public function getAll() {
		$query = Doctrine_Query::create() -> select("*") -> from("District") -> where("Disabled = '0'") -> OrderBy("Name asc");
		$districtData = $query -> execute();
		return $districtData;
	}//end getAll

	public function getDistrict($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("District") -> where("id = '$id'");
		$districtData = $query -> execute();
		return $districtData;
	}
	
	public function getDistrictCounty($county) {
		$query = Doctrine_Query::create() -> select("*") -> from("District") -> where("county = '$county' AND disabled='0'");
		$countyDistrictData = $query -> execute();
		return $countyDistrictData;
	}

	public static function getProvinceDistrict($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("District") -> where("Province = '$id' and Disabled = '0'");
		$districtData = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $districtData;
	}

	public static function getCountyDistrict($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("District") -> where("County = '$id' and Disabled = '0'");
		$districtData = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $districtData;
	}

	public static function getDNRDistricts($year, $epiweek) {
		$query = Doctrine_Query::create() -> select("d.Name, d.Province") -> from("district d") -> leftJoin("d.Surveillance s on d.id = s.district and reporting_year = '$year' and epiweek = '$epiweek'") -> where("s.district is null and d.Disabled = '0'");
		$districts = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $districts;
	}

	public function getName($districtId) {
		$query = Doctrine_Query::create() -> select("Name") -> from("district") -> where(" id = '$districtId'");
		$results = $query -> execute();
		return $results[0];
	}

	public function getNameAndId($provinceId) {
		$query = Doctrine_Query::create() -> select("*") -> from("district") -> where("Province = '$provinceId'");
		$results = $query -> execute();
		return $results;
	}

	public static function getTotalNumber() {
		$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Districts") -> from("District");
		$count = $query -> execute();
		return $count[0] -> Total_Districts;
	}

	public function getPagedDistricts($offset, $items) {
		$query = Doctrine_Query::create() -> select("*") -> from("District") -> orderBy("name") -> offset($offset) -> limit($items);
		$districts = $query -> execute();
		return $districts;
	}

}//end class
?>
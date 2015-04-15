<?php
class Facilities extends Doctrine_Record {
	public function setTableDefinition() {
		$this -> hasColumn('facilitycode', 'int', 32);
		$this -> hasColumn('name', 'varchar', 100);
		$this -> hasColumn('county', 'varchar', 5);
		$this -> hasColumn('district', 'varchar', 5);
		$this -> hasColumn('email', 'varchar', 50);
		$this -> hasColumn('mobile', 'varchar', 50);
		$this -> hasColumn('reporting', 'varchar', 1);
		$this -> hasColumn('latitude', 'varchar', 20);
		$this -> hasColumn('longitude', 'varchar', 20);
	}

	public function setUp() {
		$this -> setTableName('facilities');
		$this -> hasOne('District as Parent_District', array('local' => 'district', 'foreign' => 'id'));
		$this -> hasOne('Facility_Surveillance_Data as Surveillance', array('local' => 'facilitycode', 'foreign' => 'Facility'));
	}

	public function getDistrictFacilities($district) {
		$query = Doctrine_Query::create() -> select("facilitycode,name") -> from("Facilities") -> where("District = '" . $district . "' and reporting = '1'") -> orderBy("name asc");
		$facilities = $query -> execute();
		return $facilities;
	}

	public function getDistrictFacilitiesArrays($district) {
		$query = Doctrine_Query::create() -> select("facilitycode,name") -> from("Facilities") -> where("District = '" . $district . "' and reporting = '1'") -> orderBy("name asc");
		$facilities = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $facilities;
	}

	public static function search($search) {
		$query = Doctrine_Query::create() -> select("facilitycode,name") -> from("Facilities") -> where("name like '%" . $search . "%' || facilitycode like '%" . $search . "%'");
		$facilities = $query -> execute();
		return $facilities;
	}

	public static function getFacilityName($facility_code) {
		$query = Doctrine_Query::create() -> select("name") -> from("Facilities") -> where("facilitycode = '$facility_code'");
		$facility = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $facility[0]['name'];
	}

	public static function getTotalNumber($district = 0) {
		if ($district == 0) {
			$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Facilities") -> from("Facilities");
		} else if ($district > 0) {
			$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Facilities") -> from("Facilities") -> where("district = '$district'");
		}
		$count = $query -> execute();
		return $count[0] -> Total_Facilities;
	}
	
	public static function getTotalNumberCounty($county = 0) {
		
			//$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Facilities") -> from("Facilities");
		
			$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Facilities") -> from("Facilities") -> where("county = '$county'");
		
		$count = $query -> execute();
		return $count[0] -> Total_Facilities;
	}

	public static function getExpected($district) {
		$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Facilities") -> from("Facilities") -> where("district = '$district' and reporting = '1'");
		$count = $query -> execute();
		return $count[0] -> Total_Facilities;
	}

	public static function getAllExpected() {
		$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Facilities") -> from("Facilities") -> where("reporting = '1'");
		$count = $query -> execute();
		return $count[0] -> Total_Facilities;
	}

	public static function getAll() {
		$query = Doctrine_Query::create() -> select("*") -> from("Facilities") -> where("reporting = '1'");
		$count = $query -> execute();
		return $count;
	}

	public function getPagedFacilities($district = 0) {
		if ($district == 0) {
			$query = Doctrine_Query::create() -> select("*") -> from("Facilities") -> orderBy("name asc");
		} else if ($district > 0) {
			$query = Doctrine_Query::create() -> select("*") -> from("Facilities") -> where("district = '$district'") -> orderBy("name");
		}

		$facilities = $query -> execute();
		return $facilities;
	}
	
	public function getPagedFacilitiesCounty($offset, $items, $county ) {
		
			//$query = Doctrine_Query::create() -> select("*") -> from("Facilities") -> orderBy("name asc") -> offset($offset) -> limit($items);
		//} else if ($county > 0) {
			$query = Doctrine_Query::create() -> select("*") -> from("Facilities") -> where("county = '$county'") -> orderBy("name") -> offset($offset) -> limit($items);
		//}

		$facilities = $query -> execute();
		return $facilities;
	}

	public static function getFacility($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("Facilities") -> where("facilitycode = '$id'");
		$facility = $query -> execute();
		return $facility[0];
	}

	public static function getFacilityArray($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("Facilities") -> where("facilitycode = '$id'");
		$facility = $query -> execute();
		return $facility;
	}

	public static function getDNRFacilities($year, $epiweek, $district) {
		$query = Doctrine_Query::create() -> select("f.name") -> from("facilities f") -> leftJoin("f.Surveillance s on f.facilitycode = s.facility and reporting_year = '$year' and epiweek = '$epiweek'") -> where("s.facility is null  and f.district = '$district'");
		$districts = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $districts;
	}

}

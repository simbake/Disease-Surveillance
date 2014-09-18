<?php
class moh_503_db extends Doctrine_Record {

	public function setTableDefinition() {
		$this -> hasColumn('id', 'int', 32);
		$this -> hasColumn('user_id', 'int', 100);
		$this -> hasColumn('names', 'text', 32);
		$this -> hasColumn('facility', 'int', 11);
		$this -> hasColumn('district', 'int', 11);
		$this -> hasColumn('disease', 'int', 11);
		$this -> hasColumn('patient', 'text', 32);
		$this -> hasColumn('physical_add', 'text', 32);
		$this -> hasColumn('phone', 'text', 32);
		$this -> hasColumn('age', 'text', 32);
		$this -> hasColumn('sex', 'text', 32);
		$this -> hasColumn('date_seen', 'date');
		$this -> hasColumn('date_onset', 'date');
		$this -> hasColumn('vaccine_no', 'int');
		$this -> hasColumn('specimen_taken', 'text');
		$this -> hasColumn('date_taken', 'text');
		$this -> hasColumn('type', 'text');
		$this -> hasColumn('results', 'text');
		$this -> hasColumn('status', 'text');
		$this -> hasColumn('comments', 'text');
		$this -> hasColumn('submit_date', 'datetime');
	}//end setTableDefinition

	public function setUp() {
		$this -> setTableName('moh_503');
		$this -> hasOne('Users as users', array('local' => 'user_id', 'foreign' => 'id'));
		$this -> hasOne('Facilities as facilities', array('local' => 'facility', 'foreign' => 'facilitycode'));
		$this -> hasOne('District as districts', array('local' => 'district', 'foreign' => 'id'));
		$this -> hasOne('Disease as diseases', array('local' => 'disease', 'foreign' => 'id'));
		//$this -> hasOne('Users as users', array('local' => 'user_id', 'foreign' => 'id'));
		
	}
	
	public function getAll() {
		$query = Doctrine_Query::create() -> select("*") -> from("moh_503");
		$moh_data = $query -> execute();
		return $moh_data;
	}
	public function getbyUser($user_id) {
		$query = Doctrine_Query::create() -> select("*") -> from("moh_503")->where("user_id='$user_id'");
		$moh_data = $query -> execute();
		return $moh_data;
	}
	public function getbyDistrict($district) {
		$query = Doctrine_Query::create() -> select("*") -> from("moh_503")->where("district='$district'");
		$moh_data = $query -> execute();
		return $moh_data;
	}
	
	public static function getTotalNumberDist($district) {
		$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Moh") -> from("moh_503_db")->where("district='$district'");
		$count = $query -> execute();
		return $count[0] -> Total_Moh;
	}
	
	public static function getTotalNumber() {
		$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Moh") -> from("moh_503_db");
		$count = $query -> execute();
		return $count[0] -> Total_Moh;
	}
	
	public function getPagedmoh_dist($offset, $items, $district) {
		$query = Doctrine_Query::create() -> select("*") -> from("moh_503_db")->where("district='$district'") -> orderBy("id desc") -> offset($offset) -> limit($items);
		$moh_data = $query -> execute();
		return $moh_data;
	}
	public function getPagedmoh($offset, $items) {
		$query = Doctrine_Query::create() -> select("*") -> from("moh_503_db")-> orderBy("id desc") -> offset($offset) -> limit($items);
		$moh_data = $query -> execute();
		return $moh_data;
	}
	
	
	
	
	
	
	}
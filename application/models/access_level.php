<?php
class Access_Level extends Doctrine_Record {
	public function setTableDefinition() {
		$this -> hasColumn('Level_Name', 'varchar', 50);
		$this -> hasColumn('Description', 'text');	
		$this -> hasColumn('Indicator', 'varchar', 100);	
	}

	public function setUp() {
		$this -> setTableName('access_level');
		$this -> hasMany('Users as Users', array(
		'local' => 'id',
		'foreign' => 'Access_Level'));
	}
 	public function getAll() {
		$query = Doctrine_Query::create() -> select("*") -> from("Access_Level");
		$levels = $query -> execute();
		return $levels;
	}
 

}

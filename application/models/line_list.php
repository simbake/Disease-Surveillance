<?php
class Line_list extends Doctrine_Record {
	public function setTableDefinition() {
		$this -> hasColumn('Facility', 'int', 15);
		$this -> hasColumn('District', 'int', 15);
		$this -> hasColumn('Date_Received', 'varchar', 15);
		$this -> hasColumn('Province', 'int', 15);
		$this -> hasColumn('Disease', 'varchar', 25);
		$this -> hasColumn('Names', 'varchar', 25);
		$this -> hasColumn('Patient', 'varchar', 15);
		$this -> hasColumn('Village', 'varchar', 25);
		$this -> hasColumn('Sex', 'varchar', 15);
		$this -> hasColumn('Age', 'int', 15);
		$this -> hasColumn('Date_facility', 'varchar', 15);
		$this -> hasColumn('Onset_date', 'varchar', 15);
		$this -> hasColumn('Dosage_number', 'int', 15);
		$this -> hasColumn('Specimen_date', 'varchar', 15);
		$this -> hasColumn('Specimen_type', 'varchar', 15);
		$this -> hasColumn('Lab_results', 'varchar', 255);
		$this -> hasColumn('Outcome', 'varchar', 15);
		$this -> hasColumn('Comments', 'varchar', 255);
	}//end setTableDefinition

	public function setUp() {
		$this -> setTableName('linelist');
		$this -> hasOne('Province as Province_Object', array('local' => 'Province', 'foreign' => 'ID'));
		$this -> hasOne('District as District_Object', array('local' => 'District', 'foreign' => 'ID'));
		$this -> hasOne('Facility as Facility_Id', array('local' => 'Facility', 'foreign' => 'Facility code'));
	}//end setUp

	public function getAll() {
		$query = Doctrine_Query::create() -> select("*") -> from("Line_list");
		$llData = $query -> execute();
		return $llData;
	}//end getAll

}//end class

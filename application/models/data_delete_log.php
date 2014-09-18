<?php
class Data_Delete_Log extends Doctrine_Record {
	public function setTableDefinition() {
		$this -> hasColumn('Deleted_By', 'varchar', 10);
		$this -> hasColumn('Facility_Affected', 'varchar', 00);
		$this -> hasColumn('Epiweek', 'varchar', 10);
		$this -> hasColumn('Reporting_Year', 'varchar', 10);
		$this -> hasColumn('Timestamp', 'varchar', 32);
	}

	public function setUp() {
		$this -> setTableName('data_delete_log');
		$this -> hasOne('Users as Record_Creator', array('local' => 'Deleted_By', 'foreign' => 'id'));
		$this -> hasOne('Facilities as Facility_Object', array('local' => 'Facility_Affected', 'foreign' => 'facilitycode'));
	}

	public function getTotalLogs() {
		$query = Doctrine_Query::create() -> select("count(*) as Total_Logs") -> from("Data_Delete_Log");
		$total = $query -> execute();
		return $total[0]['Total_Logs'];
	}

	public function getPagedLogs($offset, $items) {
		$query = Doctrine_Query::create() -> select("*") -> from("Data_Delete_Log") -> offset($offset) -> limit($items)->orderBy("id desc");
		$logs = $query -> execute(array());
		return $logs;
	}

}

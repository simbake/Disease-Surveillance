<?php
class Province extends Doctrine_Record {

	public function setTableDefinition() {
		$this -> hasColumn('Name', 'varchar', 100);
	}//end setTableDefinition

	public function setUp() {
		$this -> setTableName('provinces');
	}//end setUp

	public function getAll() {
		$query = Doctrine_Query::create() -> select("*") -> from("Province");
		$provinceData = $query -> execute();
		return $provinceData;
	}//end getAll

	public function getProvince($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("Province")->where("id = '$id'");
		$provinceData = $query -> execute();
		return $provinceData;
	}
	
	public function getCountyProvice($county){
		
		$query = Doctrine_Query::create() -> select("*") -> from("Province p, county c")->where("c.id='$county' AND c.province=p.id");
		$provinceData = $query -> execute();
		return $provinceData;
		
	}

}//end class
?>
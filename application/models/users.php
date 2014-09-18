<?php
class Users extends Doctrine_Record {

	public function setTableDefinition() {
		$this -> hasColumn('Name', 'varchar', 100);
		$this -> hasColumn('Username', 'varchar', 12);
		$this -> hasColumn('Password', 'varchar', 32);
		$this -> hasColumn('Access_Level', 'varchar', 1);
		$this -> hasColumn('Disabled', 'varchar', 5);
		$this -> hasColumn('District_Or_Province', 'varchar', 50);
		$this -> hasColumn('county', 'varchar', 50);
		$this -> hasColumn('Timestamp', 'varchar', 32);
		$this -> hasColumn('Can_Delete', 'varchar', 5);
		$this -> hasColumn('Can_Download_Raw_Data', 'varchar', 5);
	}

	public function setUp() {
		$this -> setTableName('users');
		$this -> hasMutator('Password', '_encrypt_password');
		$this -> hasOne('Access_Level as Access', array('local' => 'Access_Level', 'foreign' => 'id'));
		$this -> hasOne('District as District_Object', array('local' => 'District_Or_Province', 'foreign' => 'id'));
		$this -> hasOne('Province as Province_Object', array('local' => 'District_Or_Province', 'foreign' => 'id'));
		$this -> hasOne('County as county', array('local' => 'county', 'foreign' => 'id'));
		$this -> hasOne('Users as Creator', array('local' => 'Added_By', 'foreign' => 'id'));
	}

	protected function _encrypt_password($value) {
		$this -> _set('Password', md5($value));
	}

	public function login($username, $password) {

		$query = Doctrine_Query::create() -> select("*") -> from("Users") -> where("Username = '" . $username . "'");
		$user = $query -> fetchOne();
		if ($user) {

			$user2 = new Users();
			$user2 -> Password = $password;

			if ($user -> Password == $user2 -> Password) {
				return $user;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	//added by dave
	public function getAccessLevels() {
		$levelquery = Doctrine_Query::create() -> select("id,access,level") -> from("Access_Level");
		$accesslevels = $levelquery -> execute();
		return $accesslevels;
	}

	//facilities...
	public function getFacilityData() {
		$facilityquery = Doctrine_Query::create() -> select("facilitycode,name") -> from("facilities");
		$accesslevels = $query -> execute();
		return $accesslevels;
	}

	//get all users
	public function getAll() {
		$query = Doctrine_Query::create() -> select("u.Name,u.Username, a.Level_Name as Access, u.Email_Address, u.Phone_Number, b.Name as Creator") -> from("Users u") -> leftJoin('u.Access a, u.Creator b');
		$users = $query -> execute(array(), Doctrine::HYDRATE_ARRAY);
		return $users;
	}

	public static function getTotalNumber() {
		$query = Doctrine_Query::create() -> select("COUNT(*) as Total_Users") -> from("Users");
		$count = $query -> execute();
		return $count[0] -> Total_Users;
	}

	public function getPagedUsers($offset, $items) {
		$query = Doctrine_Query::create() -> select("*") -> from("Users") -> orderBy("id desc") -> offset($offset) -> limit($items);
		$users = $query -> execute();
		return $users;
	}

	public static function getUser($id) {
		$query = Doctrine_Query::create() -> select("*") -> from("Users") -> where("id = '$id'");
		$user = $query -> execute();
		return $user[0];
	}

	public static function userExists($username) {
		if ($u = Doctrine::getTable('Users') -> findOneByUsername($username)) {

			return TRUE;
		} else {
			return FALSE;
		}
	}

 

}

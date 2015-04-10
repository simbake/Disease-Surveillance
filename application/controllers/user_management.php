<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User_Management extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this -> load -> library('pagination');
	}

	public function index() {
		$this -> listing();
	}

	public function login() {
		$data = array();
		$data['title'] = "System Login";
		$this -> load -> view("login_v", $data);
	}

	public function logout() {
		$this -> session -> sess_destroy();
		redirect("user_management/login");
	}

	public function change_password() {
		$data = array();
		$data['title'] = "Change User Password";
		$data['content_view'] = "change_password_v";
		$data['quick_link'] = "user_management";
		$data['banner_text'] = "Change Pass";
		$data['link'] = "system_administration";
		$this -> load -> view('template_v', $data);
	}

	public function save_new_password() {
		$valid = $this -> _submit_validate_password();
		if ($valid) {
			$user = Users::getUser($this -> session -> userdata('user_id'));
			$user -> Password = $this -> input -> post("new_password");
			$user -> save();
			redirect("user_management/logout");
		} else {
			$this -> change_password();
		}
	}

	public function add() {
		$data['title'] = "User Management::Add New User";
		$data['module_view'] = "add_user_view";
		$data['levels'] = Access_Level::getAll();
		$data['districts'] = District::getAll();
		$data['provinces'] = Province::getAll();
		$data['counties'] = County::getAll();
		$this -> base_params($data);
	}

	public function edit_user($id) {
		$user = Users::getUser($id);
		$data['user'] = $user;
		$data['title'] = "User Management::Edit " . $user -> Name . "'s Details";
		$data['title'] = "User Management::Add New User";
		$data['module_view'] = "add_user_view";
		$data['levels'] = Access_Level::getAll();
		$data['districts'] = District::getAll();
		$data['provinces'] = Province::getAll();
		$this -> base_params($data);
	}

	public function save() {
		$user_id = $this -> input -> post("user_id");
		$valid = false;
		if ($user_id > 0) {
			//The user is editing! Modify the validation
			$user = Users::getUser($user_id);
			$valid = $this -> _submit_validate($user);
		} else {
			$valid = $this -> _submit_validate();
			$user = new Users();
		}
		if ($valid) {
			$name = $this -> input -> post("name");
			$username = $this -> input -> post("username");
			$password = "123456";
			$user_group = $this -> input -> post("user_group");
			$province = $this -> input -> post("province");
			$district = $this -> input -> post("district");
			$county=$this->input ->post("county");
			$user_can_download_raw_data = $this -> input -> post("user_can_download_raw_data");
			$user_can_delete = $this -> input -> post("user_can_delete");
			$user -> Name = $name;
			$user -> Password = $password;
			$user -> Access_Level = $user_group;
			$user -> Username = $username;
			$user -> Disabled = '0';
			$user -> Timestamp = date('U');
			$user -> Can_Delete = $user_can_delete;
			$user -> Can_Download_Raw_Data = $user_can_download_raw_data;
			if($county!=null || $county!=""){
			$user->county=$county;
			}

			if (strlen($district) > 0) {
				$user -> District_Or_Province = $district;
			} else if (strlen($province) > 0) {
				$user -> District_Or_Province = $province;
			} else {
				$user -> District_Or_Province = '';
			}
			$user -> save();

			redirect("user_management/listing");
		} else {
			$this -> add();
		}
	}

	private function _submit_validate($user = false) {
		// validation rules
		$this -> form_validation -> set_rules('name', 'Full Name', 'trim|required|min_length[2]|max_length[50]');
		$this -> form_validation -> set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[50]');
		$this -> form_validation -> set_rules('user_group', 'User Group', 'trim|required|min_length[1]|max_length[50]');
		$temp_validation = $this -> form_validation -> run();
		if ($temp_validation) {
			//If the user is editing, if the username changes, check whether the new username exists!
			if ($user) {
				if ($user -> Username != $this -> input -> post('username')) {
					$this -> form_validation -> set_rules('username', 'Username', 'trim|required|callback_unique_username');
				}
			} else {
				$this -> form_validation -> set_rules('username', 'Username', 'trim|required|callback_unique_username');
			}

			return $this -> form_validation -> run();
		} else {
			return $temp_validation;
		}

	}

	private function _submit_validate_password() {
		// validation rules
		$this -> form_validation -> set_rules('old_password', 'Current Password', 'trim|required|min_length[6]|max_length[20]');
		$this -> form_validation -> set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[20]|matches[new_password_confirm]');
		$this -> form_validation -> set_rules('new_password_confirm', 'New Password Confirmation', 'trim|required|min_length[6]|max_length[20]');
		$temp_validation = $this -> form_validation -> run();
		if ($temp_validation) {
			$this -> form_validation -> set_rules('old_password', 'Current Password', 'trim|required|callback_correct_current_password');
			return $this -> form_validation -> run();
		} else {
			return $temp_validation;
		}

	}

	public function unique_username($usr) {
		$exists = Users::userExists($usr);
		if ($exists) {
			$this -> form_validation -> set_message('unique_username', 'The Username already exists. Enter another one.');
			return FALSE;
		} else {
			return TRUE;
		}

	}

	public function correct_current_password($pass) {
		$user = Users::getUser($this -> session -> userdata('user_id'));
		$dummy_user = new Users();
		$dummy_user -> Password = $pass;
		if ($user -> Password != $dummy_user -> Password) {
			$this -> form_validation -> set_message('correct_current_password', 'The current password you provided is not correct.');
			return FALSE;
		} else {
			return TRUE;
		}

	}

	public function listing() {
		$users = Users::getPagedUsers();
		$data['users'] = $users;
		$data['title'] = "User Management::All System Users";
		$data['module_view'] = "view_users_view";
		$this -> base_params($data);
	}

	public function authenticate() {
		$data = array();
		$validated = $this -> _submit_validate_login();
		if ($validated) {
			$username = $this -> input -> post("username");
			$password = $this -> input -> post("password");
			$remember = $this -> input -> post("remember");
			$logged_in = Users::login($username, $password);
			//This code checks if the credentials are valid
			if ($logged_in == false) {
				$data['invalid'] = true;
				$data['title'] = "System Login";
				$this -> load -> view("login_v", $data);
			}
			//If the credentials are valid, continue
			else {
				//check to see whether the user is active
				if ($logged_in -> Disabled == "1") {
					$data['inactive'] = true;
					$data['title'] = "System Login";
					$this -> load -> view("login_v", $data);
				}
				//looks good. Continue!
				else {
					$session_data = array('user_id' => $logged_in -> id, 'user_indicator' => $logged_in -> Access -> Indicator, 'access_level' => $logged_in -> Access_Level, 'username' => $logged_in -> Username, 'full_name' => $logged_in -> Name, 'district_province_id' => $logged_in -> District_Or_Province,'county_id' => $logged_in -> county, 'can_delete' => $logged_in -> Can_Delete, 'can_download_raw_data' => $logged_in -> Can_Download_Raw_Data);
					$this -> session -> set_userdata($session_data);

					redirect("home_controller");
				}

			}

		} else {
			$data = array();
			$data['title'] = "System Login";
			$this -> load -> view("login_v", $data);
		}
	}

	private function _submit_validate_login() {
		// validation rules
		$this -> form_validation -> set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]');
		$this -> form_validation -> set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[20]');

		return $this -> form_validation -> run();
	}

	public function go_home($data) {
		$data['title'] = "System Home";
		$data['content_view'] = "home_v";
		$data['banner_text'] = "Dashboards";
		$data['link'] = "home";
		$this -> load -> view("template_v", $data);
	}

	public function base_params($data) {
		$data['scripts'] = array("jquery-ui.js", "tab.js");
		$data['styles'] = array("jquery-ui.css", "tab.css", "pagination.css");
		$data['content_view'] = "admin_view";
		$data['quick_link'] = "user_management";
		$data['banner_text'] = "System Users";
		$data['link'] = "admin_management";
		$this -> load -> view('template_v', $data);
	}

	public function change_availability($code, $availability) {
		$user = Users::getUser($code);
		$user -> Disabled = $availability;
		$user -> save();
		redirect("user_management/listing");
	}
}

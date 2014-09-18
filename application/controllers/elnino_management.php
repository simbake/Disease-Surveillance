<?php
error_reporting(E_ALL^E_NOTICE);
class Elnino_Management extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        $this -> add();
    }

    public function add($data = array()) {
        $access_level = $this -> session -> userdata('user_indicator');
        if ($access_level == "district_clerk") {
            $district = $this -> session -> userdata('district_province_id');
            $provinces = Province::getAll();
            $districts = District::getAll();

        }
        $data['elnino_diseases'] = Disease::getElninoDiseases();
        $data['provinces'] = $provinces;
        $data['districts'] = $districts;
        $data['editing'] = false;
        $data['prediction'] = elnino::getPrediction();
        $data['scripts'] = array("special_date_picker.js", "validationEngine-en.js", "validator.js");
        $data["styles"] = array("validator.css");
        $this -> base_params($data);
    }

    public function edit_elnino_data($epiweek, $reporting_year, $district) {
        $provinces = Province::getAll();
        $districts = District::getAll();

        $data['provinces'] = $provinces;
        $data['districts'] = $districts;
        $data['prediction'] = Elnino::getPrediction();
        $data['elnino_data'] = Elnino::getElninoData($epiweek, $reporting_year, $district);
        $data['editing'] = true;
        $data['scripts'] = array("special_date_picker.js", "validationEngine-en.js", "validator.js");
        $data["styles"] = array("validator.css");
        $this -> base_params($data);
    }

    public function save() {
        $district = $this -> session -> userdata('district_province_id');        
        $week_ending = $this -> input -> post("week_ending");
        $epiweek = $this -> input -> post("epiweek");
        $reported_by = $this -> input -> post("reported_by");
        $telephone = $this -> input -> post("telephone");
        $email = $this -> input -> post("email");
        $drug_month = $this -> input -> post("drug_month");
        $buffer_ors = $this -> input -> post("buffer_ors");
        $buffer_iv = $this -> input -> post("buffer_iv");
        $antimalarial = $this -> input -> post("antimalarial");
        $steering_group = $this -> input -> post("steering_group");
        $cholera = $this -> input -> post("cholera");
        $malaria_positivity = $this -> input -> post("malaria_positivity");
        $rain = $this -> input -> post("rain");
        $floods = $this -> input -> post("floods");
        $displaced_persons = $this -> input -> post("displaced_persons");
        $displaced_persons_7 = $this -> input -> post("displaced_persons_7");
        $deaths_7 = $this -> input -> post("deaths_7");
        $deaths = $this -> input -> post("deaths");
        $outbreak_name = $this -> input -> post("outbreak_name");
        $reporting_year = $this -> input -> post("reporting_year");


        $valid = $this -> _validate_submission();
        $elnino = new Elnino();
        if ($valid == false) {
            $this -> add();
        } else {
            $timestamp = date('d/m/Y');
            $elnino -> District = $district;
            $elnino -> Week_Ending = $week_ending;
            $elnino -> Date_Created = date("Y-m-d");
            $elnino -> Epiweek = $epiweek;
            $elnino -> Reported_By = $reported_by;
            $elnino -> Telephone = $telephone;
            $elnino -> Email = $email;
            $elnino -> Drug_month = $drug_month;
            $elnino -> Buffer_Ors = $buffer_ors;
            $elnino -> Buffer_Iv = $buffer_iv;
            $elnino -> Antimalarial = $antimalarial;
            $elnino -> Steering_Group = $steering_group;
            $elnino -> Cholera = $cholera;
            $elnino -> Malaria_Positivity = $malaria_positivity;
            $elnino -> Rain = $rain;
            $elnino -> Floods = $floods;
            $elnino -> Displaced_Persons = $displaced_persons;
            $elnino -> Deaths = $deaths;
            $elnino -> Displaced_Persons_7 = $displaced_persons_7;
            $elnino -> Deaths_7 = $deaths_7;
            $elnino -> Outbreak_Name = $outbreak_name;
            $elnino -> Reporting_Year = $reporting_year;
            $elnino -> Date_Created = date("Y-m-d");
            $elnino -> Date_Reported = $timestamp;

            $elnino -> save();
            redirect("elnino_management/add");
        }//end else

    }//end save

    private function _validate_submission() {
        $this -> form_validation -> set_rules('reported_by', 'Your Name', 'trim|required|min_length[2]|max_length[40]');
        $this -> form_validation -> set_rules('telephone', 'Your PHone Number', 'trim|required|min_length[10]|max_length[20]');
        $this->  form_validation->  set_rules('email', 'Email', 'required|valid_email');
        $this -> form_validation -> set_rules('drug_month', 'Drugs', 'trim|required|min_length[3]|max_length[20]');
        return $this -> form_validation -> run();
    }//end validate_submission

    public function base_params($data) {
        $data['title'] = "Weekly Data";
        $data['content_view'] = "elnino_v";
        $data['banner_text'] = "El Nino Data";
        $data['link'] = "submissions_management";
        $data['quick_link'] = "elnino_management";
        $this -> load -> view("template", $data);
    }

}//end class

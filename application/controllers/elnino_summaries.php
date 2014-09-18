<?php
class Elnino_Summaries extends MY_Controller {

    //required
    function __construct() {
        parent::__construct();
    }

    public function index() {        
        $this -> summaries();
    }

    public function summaries(){
        error_reporting(0);
        $total_buffers = Elnino::getTotalBuffers();        
        $total_ivs = Elnino::getTotalIvs();
        $total_antimalarial = Elnino::getTotalAntimalarial();
        $total_steering_group = Elnino::getTotalSteeringGroup();
        $total_cholera = Elnino::getTotalCholera();
        $total_malaria_positivity = Elnino::getTotalMalariaPositivity();
        $total_rain = Elnino::getTotalRain();
        $total_floods = Elnino::getTotalFloods();
        $total_deaths = Elnino::getTotalDeaths();        
        $total_displaced_persons = Elnino::getTotalDisplacedPersons();
        $total_districts = District::getTotalNumber();
        $data['total_districts'] = $total_districts;
        $data['antimalarial'] = $total_antimalarial;
        $data['steering_group'] = $total_steering_group;
        $data['cholera'] = $total_cholera;
        $data['malaria_positivity'] = $total_malaria_positivity;
        $data['rain'] = $total_rain;
        $data['floods'] = $total_floods;
        $data['deaths'] = $total_deaths;
        $data['displaced_persons'] = $total_displaced_persons;
        $data['buffers'] = $total_buffers;    
        $data['ivs'] = $total_ivs;        
        $this -> base_params($data);        
    }
    
    public function base_params($data) {
        $data['styles'] = array("jquery-ui.css");
        $data['scripts'] = array("jquery-ui.js");
        $data['quick_link'] = "elnino_reports";
        $data['title'] = "System Reports";
        $data['report_view'] = "summaries_v";
        $data['content_view'] = "elnino_reports_v";
        $data['banner_text'] = "Elnino Reports";
        $data['link'] = "reports_management";

        $this -> load -> view('template', $data);
    }

}

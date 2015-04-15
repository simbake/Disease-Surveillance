<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Elnino_Districts extends MY_Controller {

    //required
    function __construct() {
        parent::__construct();
    }

    public function index() {
        error_reporting(0);        
        $values = array($epiweek, $province);

        $data['provinces'] = Province::getAll();
        $data['epiweeks'] = Elnino::getEpiweeks();        

        $data['styles'] = array("jquery-ui.css");
        $data['scripts'] = array("jquery-ui.js");
        $data['quick_link'] = "elnino_districts";
        $data['title'] = "System Reports";
        $data['report_view'] = "elnino_districts_v";
        $data['content_view'] = "elnino_reports_v";
        $data['scripts'] = array("FusionCharts/FusionCharts.js");
        $data['banner_text'] = "Elnino Reports";
        $data['link'] = "reports_management";

        $this -> load -> view('template_v', $data);
    }

    public function dave() {
        $epiweek = $_POST['epiweek'];
        $province = $_POST['province'];
        $values = array($epiweek, $province);

        $data['provinces'] = Province::getAll();
        $data['epiweeks'] = Elnino::getEpiweeks();
        $data['values'] = $values;


        $this -> load -> database();
        $sql = 'SELECT name,buffer_ors,buffer_iv,antimalarial,drug_month,steering_group,cholera,malaria_positivity,outbreak_name,deaths,displaced_persons FROM elnino, districts WHERE buffer_ors = 0 AND epiweek = ? AND elnino.district = districts.id AND districts.province = ?';
        $query = $this -> db -> query($sql, array($epiweek, $province));
        $data['buffers'] = $query -> result_array();

        $data['styles'] = array("jquery-ui.css");
        $data['scripts'] = array("jquery-ui.js");
        $data['quick_link'] = "elnino_districts";
        $data['title'] = "System Reports";
        $data['report_view'] = "elnino_districts_v";
        $data['content_view'] = "elnino_reports_v";
        $data['scripts'] = array("FusionCharts/FusionCharts.js");
        $data['banner_text'] = "Elnino Reports";
        $data['link'] = "reports_management";

        $this -> load -> view('template_v', $data);
    }

}

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Elnino_Raw_Data extends MY_Controller {

    //required
    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this -> show_interface();
    }

    public function show_interface() {
        $data = array();
        $data['settings_view'] = "elnino_raw_data_v";
        $this -> base_params($data);
    }

    public function export() {
        $elnino_data_requested = $this -> input -> post('elnino');        
        $year = $this -> input -> post('year_from');
        $start_week = $this -> input -> post('epiweek_from');
        $end_week = $this -> input -> post('epiweek_to');
        if (strlen($elnino_data_requested) > 0) {
            $elnino_data = Elnino::getRawDataArray($year, $start_week, $end_week);
            $excell_headers = "Epiweek\t District Name\t Week Ending\t Reported By\t Month Last KEMSA Drugs Received\t Buffer Stocks ORS\t Buffer Stocks IV\t Antimalarial Drugs\t Steering Group\t Cholera\t Malaria Positivity\t Rain\t Floods\t Displaced Persons\t Displaced Persons in the Last 7 Days\t Deaths in the last 7 Days\t Deaths\t Outbreak Name\t Date Reported\t\n";            
            $excell_data = "";
            foreach ($elnino_data as $result_set) {
                
            $excell_data .= $result_set['Epiweek'] . "\t" . $result_set['District'] . "\t" . $result_set['Week_Ending'] . "\t" . $result_set['Reported_By'] . "\t" . $result_set['Drug_month'] . "\t" 
                . $result_set['Buffer_Ors'] . "\t" . $result_set['Buffer_Iv'] . "\t" . $result_set['Antimalarial'] . "\t" . $result_set['Steering_Group'] . "\t" . $result_set['Cholera'] . "\t" . 
                $result_set['Malaria_Positivity'] . "\t" . $result_set['Rain'] . "\t" . $result_set['Floods'] . "\t" . $result_set['Displaced_Persons'] . "\t" . $result_set['Displaced_Persons_7'] . "\t" . $result_set['Deaths_7'] . "\t" . $result_set['Deaths'] . "\t" . $result_set['Outbreak_Name'] . "\t" . $result_set['Date_Reported'] . "\t";
                $excell_data .= "\n";
            }
            header("Content-type: application/vnd.ms-excel; name='excel'");
            header("Content-Disposition: filename=Elnino_Data (" . $year . " epiweek " . $start_week . " to epiweek " . $end_week . ").xls");
            // Fix for crappy IE bug in download.
            header("Pragma: ");
            header("Cache-Control: ");
            echo $excell_headers . $excell_data;
        }
    }

    public function base_params($data) {
        $data['styles'] = array("jquery-ui.css");
        $data['scripts'] = array("jquery-ui.js");
        $data['quick_link'] = "elnino_raw_data";
        $data['title'] = "System Reports";
        $data['report_view'] = "elnino_raw_data_v";
        $data['content_view'] = "elnino_reports_v";
        $data['banner_text'] = "Raw Data";
        $data['link'] = "reports_management";

        $this -> load -> view('template_v', $data);
    }

}

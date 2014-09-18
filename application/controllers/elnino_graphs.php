<?php
class Elnino_Graphs extends MY_Controller {

    //required
    function __construct() {
        parent::__construct();
    }

    public function index() {
        error_reporting(E_ALL^E_NOTICE);
        $epiweek = $_POST['epiweek'];
        $values = array($epiweek);
        $data['values'] = $values;
        $data['epiweeks'] = Elnino::getEpiweeks();
        $data['styles'] = array("jquery-ui.css");
        $data['scripts'] = array("jquery-ui.js");
        $data['quick_link'] = "elnino_graphs";
        $data['title'] = "System Reports";
        $data['report_view'] = "graphs_v";
        $data['content_view'] = "elnino_reports_v";
        $data['scripts'] = array("FusionCharts/FusionCharts.js");
        $data['banner_text'] = "Elnino Reports";
        $data['link'] = "reports_management";

        $this -> load -> view('template', $data);
    }

    function ORS($epiweek) {
        $total_buffers = Elnino::getTotalBuffers($epiweek);
        $total_districts = District::getTotalNumber();
        $a = $total_districts - $total_buffers;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '1' caption='ORS Buffers' >";

        $strXML .= "<set label='Districts with adequate buffer of ORS' value='$total_buffers'/>";
        $strXML .= "<set label='Districts with inadequate buffer of ORS' value='$a'/>";
        $strXML .= "</chart>";
        echo $strXML;

    }

    //$total_deaths = Elnino::getTotalDeaths();
    //  $total_displaced_persons = Elnino::getTotalDisplacedPersons();

    function IV($epiweek) {

        $total_ivs = Elnino::getTotalIvs($epiweek);
        $total_districts = District::getTotalNumber();
        $b = $total_districts - $total_ivs;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '0' caption='Fluid Buffers' >";

        $strXML .= "<set label='Adequate buffer stock of fluids' value='$total_ivs'/>";
        $strXML .= "<set label='Inadequate buffer stock of fluids' value='$b'/>";
        $strXML .= "</chart>";
        echo $strXML;
    }

    function antimalarial($epiweek) {

        $total_antimalarial = Elnino::getTotalAntimalarial($epiweek);
        $total_districts = District::getTotalNumber();
        $c = $total_districts - $total_antimalarial;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '1' caption='Districts with antimalarial drugs' >";

        $strXML .= "<set label='In all government hospitals' value='$total_antimalarial'/>";
        $strXML .= "<set label='Some government  hospitals' value='$c'/>";
        $strXML .= "</chart>";
        echo $strXML;
    }

    function steering($epiweek) {

        $total_steering_group = Elnino::getTotalSteeringGroup($epiweek);
        $total_districts = District::getTotalNumber();
        $d = $total_districts - $total_steering_group;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '0' caption='Steering Group Discussions' >";

        $strXML .= "<set label='Steering Groups meeting' value='$total_steering_group'/>";
        $strXML .= "<set label='Steering Group not meeting' value='$d'/>";
        $strXML .= "</chart>";
        echo $strXML;
    }

    function cholera($epiweek) {

        $total_cholera = Elnino::getTotalCholera($epiweek);
        $total_districts = District::getTotalNumber();
        $e = $total_districts - $total_cholera;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '1' caption='Confirmed Cholera Case in the last 7 Days' >";

        $strXML .= "<set label='Confrimed cholera cases reported' value='$total_cholera'/>";
        $strXML .= "<set label='Confirmed cholera cases not reported' value='$e'/>";
        $strXML .= "</chart>";
        echo $strXML;
    }

    function malaria_positivity($epiweek) {

        $total_malaria_positivity = Elnino::getTotalMalariaPositivity($epiweek);
        $total_districts = District::getTotalNumber();
        $f = $total_districts - $total_malaria_positivity;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '0' caption='Malaria Positivity in the last 7 days' >";

        $strXML .= "<set label='Upsurge in malaria positivity' value='$total_malaria_positivity'/>";
        $strXML .= "<set label='No upsurge in malaria positivity' value='$f'/>";
        $strXML .= "</chart>";
        echo $strXML;
    }

    function rain($epiweek) {

        $total_rain = Elnino::getTotalRain($epiweek);
        $total_districts = District::getTotalNumber();
        $g = $total_districts - $total_rain;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '1' caption='Rain in the last 7 days' >";

        $strXML .= "<set label='Districts with rain' value='$total_rain'/>";
        $strXML .= "<set label='Districts without rain' value='$g'/>";
        $strXML .= "</chart>";
        echo $strXML;
    }

    function floods($epiweek) {

        $total_floods = Elnino::getTotalFloods($epiweek);
        $total_districts = District::getTotalNumber();
        $h = $total_districts - $total_floods;

        $strXML = "<chart palette='2' bgColor='ffffff' showborder = '0' caption='Floods in the Last 7 Days' >";

        $strXML .= "<set label='Districts with floods' value='$total_floods'/>";
        $strXML .= "<set label='Districts without floods' value='$h'/>";
        $strXML .= "</chart>";
        echo $strXML;
    }

}

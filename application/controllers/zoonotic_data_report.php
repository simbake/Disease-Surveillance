<?php

class Zoonotic_Data_Report extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_data();
       
   /*    $views['pageOne'] = $this->load->view('view_name', $data, true);
	   $views['pageTwo'] = $this->load->view('view_name2', $data, true);
     */
    //// $views['pageOne'] = $this->load_data();
//	 $views['pageTwo'] = $this->load_data();  
       $this_>loading();
       
    }

    public function select_period(){
        $per = $_POST['period'];
        if($per == 1){
            $from = $_POST['start_date'];
            $to = $_POST['end_date'];

            $this->load_data($per,$from,$to);
        }else if($per == 2){
            $from = $_POST['month'];
            $to = $_POST['year'];

            $this->load_data($per,$from,$to);
        }else if($per == 3){
            $from = $_POST['year'];
            $to = 0;

            $this->load_data($per,$from,$to);
        }
    }
    
/**Function: load_data
 * This is the first function to be loaded when viewing occurences of zoonotic diseases.
 * It loads a map of Kenya with occurrences per district.
 * It also shows a table with the number of occurrences of specific diseases per province
 */
    function load_data($per = 0, $from = 0, $to = 0) {
        $this->track();
        
        $diseases = Zoonotic::getAll();
        $provinces = Province::getAll();
        $counties = County::getAll();
        $districts = District::getAll();
        $years = $this->years();
        
        $data['scripts'] = array("FusionCharts/FusionCharts.js");

        $central = $this->occurences_per_prov(1);

        $coast = $this->occurences_per_prov(2);

        $eastern = $this->occurences_per_prov(3);

        $nairobi = $this->occurences_per_prov(4);

        $northEastern = $this->occurences_per_prov(6);

        $nyanza = $this->occurences_per_prov(7);

        $riftValley = $this->occurences_per_prov(8);

        $western = $this->occurences_per_prov(9);
        
        $this->load->database();
        $this->db->select('name')
                ->from('zoonotic_diseases');

        $disease = $this->db->get()->result_array();
        
        $count  = 0;
        foreach ($disease as $dis){//
            $nationals[] = array('disease' =>$dis['name'],'central'=>(string)$central[$count],'coast'=>(string)$coast[$count],'eastern'=>(string)$eastern[$count], 'nairobi'=>(string)$nairobi[$count], 'northEastern'=>(string)$northEastern[$count], 'nyanza'=>(string)$nyanza[$count], 'riftValley'=>(string)$riftValley[$count], 'western'=>(string)$western[$count]);
            $count++;
            $data['nationals'] = $nationals;
        }
        $data['diseases'] = $diseases;
        $data['provinces'] = $provinces;
        $data['counties'] = $counties;
        $data['districts'] = $districts;
        $data['years'] = $years;
        
        //mapping
        
        $this->load->helper('date');
        
        if($per == 0){
            $dist_ocs = $this->district_occurences(mdate("%Y"));
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".mdate("%Y");
        }else if($per == 1){
            $start = $from;
            $end = $to;
            
            $this->load->helper('date');
            
            $format_ = 'D, d M Y';
            $start_ = strtotime($start);
            $end_ = strtotime($end);
            
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for the period between ".date($format_, $start_)." and ".date($format_, $end_);
            
            $this->load->database();

            $this->db->distinct();
            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('reporting_date >=',$start_)
                    ->where('reporting_date <=',$end_)
                    ->where('confirmation',1)
                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

            $occurences = $this->db->get()->result_array();
            
            $dist_ocs = $occurences;
        }else if($per == 2){
            $month = $from;
            $year = $to;
            
            $start_day = 1;
            if($month == 01 || $month == 03 || $month == 05 || $month == 07 || $month == 08 || $month == 10 || $month == 12){
                $end_day = 31;
            }else if($month == 02){
                $end_day = 28;
            }else if($month == 02 && $year % 4 == 0){
                $end_day = 29;
            }else{
                $end_day = 30;
            }
            
            $start = $month.'/'.$start_day.'/'.$year;
            $end = $month.'/'.$end_day.'/'.$year; 
            
            $format_ = 'M Y';
            $start_ =  strtotime($start);
            $end_ =  strtotime($end);
            //echo date($format_, $tstamp);
            
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for  ".date($format_, $start_);
            
            $this->load->database();

            $this->db->distinct();
            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('reporting_date >=',$start_)
                    ->where('reporting_date <=',$end_)
                    ->where('confirmation',1)
                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

            $occurences = $this->db->get()->result_array();
            
            $dist_ocs = $occurences;
        }else if($per == 3){
            $start = $from;
            $dist_ocs = $this->district_occurences($start);
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".$start;
        }
        
        $this->load->library('googlemaps');

        $config['center'] = '0.4252, 36.7517'; //cordinates for Kenya
        $config['zoom'] = '6';
        $this->googlemaps->initialize($config);
        $config['cluster'] = TRUE;

        foreach($dist_ocs as $dist_oc){
            if($dist_oc['latitude'] !=0 &&$dist_oc['longitude'] !=0){
            $marker = array();
            $marker['position'] = $dist_oc['latitude'].','. $dist_oc['longitude'];
            if($per == 0){
                $start_ = 0;
                $end_ = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.mdate("%Y").'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 1){
                $year = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$year.'/'.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 2){
                $year = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$year.'/'.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 3){
                $start_ = 0;
                $end_ = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$from.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else{
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'">Click to view details</a>';
            }
            $marker['title'] = $dist_oc['name'];
            $marker['animation'] = 'DROP';
            $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($dist_oc['confirmed_disease_id']).'.png';
            $this->googlemaps->add_marker($marker);
            }
        }
        $seven_days_ago  = mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
        $today = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        $format = 'Y-m-d';

        $this->db->distinct();
        $this->db->select('ip')
                    ->from('tracker')
                    ->where('date >=',date($format,$seven_days_ago))
                    ->where('date <=',date($format,$today));

        $weekly_visitors = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('ip')
                ->from('tracker')
                ->where('date =',date('Y-m-d'));
        $visitors = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('ip')
                ->from('tracker');
        $all_visitors = $this->db->get()->result_array();

        $data['visitors'] = count($visitors);
        $data['weekly_visitors'] = count($weekly_visitors);
        $data['all_visitors'] = count($all_visitors);

        $data['map'] = $this->googlemaps->create_map();

        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_report_v";
		$data['livestock_content_view'] = "Livestock_Testing";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home_controller";
        $data['quick_link'] = "zoonotic_data_report";

        $access = $this -> session -> userdata('user_indicator');
        if($access == ""){
            $data['res_view'] = "zoonotic_resources_v";
            $this->load->view("public_template", $data);
        }else{
            $this->load->view("template", $data);
        }
    }

/**Function: occurences_per_prov
 * This function takes a province_id (allocated in the calling function) and returns an array 
 * of the number of occurrences of all ZnN diseases for the given province.
 */
    public function occurences_per_prov($prov = 0) {
        $diseases = Zoonotic::getAll();
        $this->load->database();

        foreach ($diseases as $disease) {
            $sql = 'select * from zoonotic_surveillance_data where abs(confirmed_disease_id) = ? and abs(province_id) = ? and abs(confirmation) = ?';
            $query = $this->db->query($sql, array($disease['ID'], $prov, 1));
            $ocs = $query->num_rows();
            $totalOcs[] = $ocs;
        }
        return $totalOcs;
    }

/**Function: occurences_per_dist
 * This function takes a ditrict_id and returns an array of occurences of all diseases per facility in all the
 * facilities within the given district.
 */
    public function occurences_per_dist($prov = 0) {
        $diseases = Zoonotic::getAll();
        $this->load->database();

        foreach ($diseases as $disease) {
            $sql = 'select * from zoonotic_surveillance_data where abs(confirmed_disease_id) = ? and abs(province_id) = ? and abs(confirmation) = ?';
            $query = $this->db->query($sql, array($disease['ID'], $prov, 1));
            $ocs = $query->num_rows();
            $totalOcs[] = $ocs;
        }
        return $totalOcs;
    }
    
/**Function: district_occurences
 * This function returns an array of district names and coordinates with occurences of ZnN diseases for the entire country.
 */  
    public function district_occurences($year) {
        $this->load->database();

        $this->db->distinct();
        $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                ->from('zoonotic_surveillance_data')
                ->where('year',$year)
                ->where('confirmation',1)
                ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

        $updates = $this->db->get()->result_array();
        return $updates;
    }

/**Function: years
 * This function returns an array of numbers from 1890 to 2100
 */
    public function years() {
        $x = 1890;
        while ($x < 2101) {
            $x + 1;
            $years[] = $x;
            $x++;
        }
        //echo '{"results":'.json_encode($years).'}';
        return $years;
    }

/**Function: diseaseTrendGraph
 * This function takes a district_id, year and disease_id and returns a graph of the trends of the given
 * disease for the selected district within the given year.
 */
    function diseaseTrendGraph($district, $year, $disease) {
        $this->load->database();

        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district);

        $result_district = $this->db->get()->row_array();
        
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $disease);

        $result_disease = $this->db->get()->row_array();

        $district_name = $result_district['name'];
        $disease_name = $result_disease['name'];
        
        $caption = "Disease trends for ".$disease_name." in ".$district_name." sub county for the year ".$year;
        
        $sql = 'select * from zoonotic_surveillance_data where abs(district_id) = ? and abs(confirmed_disease_id) = ? and abs(year) = ? and abs(confirmation) = ?';
        $query = $this->db->query($sql, array($district, $disease, $year, 1));
        $ocs = $query->result_array();
        
        $this->load->helper('date');
        $form = 'm/d/Y';
        
        $jan = 0;
        $feb = 0;
        $mar = 0;
        $apr = 0;
        $may = 0;
        $jun = 0;
        $jul = 0;
        $aug = 0;
        $sep = 0;
        $oct = 0;
        $nov = 0;
        $dec = 0;
        foreach ($ocs as $oc) {
            $rep_date = $oc['reporting_date'];//date($form, $rep_date)
            if (substr(date($form, $rep_date), 0, 2) == 01) {
                $jan++;
            } else if (substr(date($form, $rep_date), 0, 2) == 02) {
                $feb++;
            } else if (substr(date($form, $rep_date), 0, 2) == 03) {
                $mar++;
            } else if (substr(date($form, $rep_date), 0, 2) == 04) {
                $apr++;
            } else if (substr(date($form, $rep_date), 0, 2) == 05) {
                $may++;
            } else if (substr(date($form, $rep_date), 0, 2) == 06) {
                $jun++;
            } else if (substr(date($form, $rep_date), 0, 2) == 07) {
                $jul++;
            } else if (substr(date($form, $rep_date), 0, 2) == 08) {
                $aug++;
            } else if (substr(date($form, $rep_date), 0, 2) == 09) {
                $sep++;
            } else if (substr(date($form, $rep_date), 0, 2) == 10) {
                $oct++;
            } else if (substr(date($form, $rep_date), 0, 2) == 11) {
                $nov++;
            } else if (substr(date($form, $rep_date), 0, 2) == 12) {
                $dec++;
            } else {
                
            }
            $cases = array($jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec);
        }
         
        $months = array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');

        $strXML = "<chart palette='2' decimals='0' useRoundEdges='1' legendBorderAlpha='0' showBorder='0' labelStep='1' caption='$caption' shownames='1' showvalues='0' xAxisName='Months' yAxisName='Occurence Cases'>
               <categories>";
        foreach ($months as $month) {
            $strXML .= "<category label='$month'/>";
        }
        $strXML .= "</categories>
               <dataset seriesName='Number of Cases' color='AFD8F8' showValues='1'>";

        foreach ($cases as $case) {
            $strXML .= "<set value='$case'/>";
        }

        $strXML .= "</dataset>        
               </chart>";
        echo $strXML;
    }
    
/**Function: compareDiseaseTrendGraph
 * This function takes a district_id, year and disease_id and returns a graph of the trends of the given
 * disease for the selected district within the given year.
 */
    function compareDiseaseTrendGraph($district1, $year1, $disease1, $district2, $year2, $disease2) {
        $this->load->database();

        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district1);

        $result_district1 = $this->db->get()->row_array();
        
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $disease1);

        $result_disease1 = $this->db->get()->row_array();

        $district_name1 = $result_district1['name'];
        $disease_name1 = $result_disease1['name'];
        
        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district2);

        $result_district2 = $this->db->get()->row_array();
        
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $disease2);

        $result_disease2 = $this->db->get()->row_array();

        $district_name2 = $result_district2['name'];
        $disease_name2 = $result_disease2['name'];
        
        $caption = "Comparison of disease trends for ".$disease_name1." in ".$district_name1." sub county for the year ".$year1." and ".$disease_name2." in ".$district_name2." sub county for the year ".$year2;
        
        $sql = 'select * from zoonotic_surveillance_data where abs(district_id) = ? and abs(confirmed_disease_id) = ? and abs(year) = ? and abs(confirmation) = ?';
        $query = $this->db->query($sql, array($district1, $disease1, $year1, 1));
        $ocs = $query->result_array();
        
        $this->load->helper('date');
        $form1 = 'm/d/Y';
        
        $jan = 0;
        $feb = 0;
        $mar = 0;
        $apr = 0;
        $may = 0;
        $jun = 0;
        $jul = 0;
        $aug = 0;
        $sep = 0;
        $oct = 0;
        $nov = 0;
        $dec = 0;
        foreach ($ocs as $oc) {
            $rep_date = $oc['reporting_date'];
            if (substr(date($form1, $rep_date), 0, 2) == 01) {
                $jan++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 02) {
                $feb++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 03) {
                $mar++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 04) {
                $apr++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 05) {
                $may++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 06) {
                $jun++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 07) {
                $jul++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 08) {
                $aug++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 09) {
                $sep++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 10) {
                $oct++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 11) {
                $nov++;
            } else if (substr(date($form1, $rep_date), 0, 2) == 12) {
                $dec++;
            } else {
                
            }
            $cases1 = array($jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct, $nov, $dec);
        }
        
        $sql = 'select * from zoonotic_surveillance_data where abs(district_id) = ? and abs(confirmed_disease_id) = ? and abs(year) = ? and abs(confirmation) = ?';
        $query = $this->db->query($sql, array($district2, $disease2, $year2, 1));
        $ocs2 = $query->result_array();
        $jan2 = 0;
        $feb2 = 0;
        $mar2 = 0;
        $apr2 = 0;
        $may2 = 0;
        $jun2 = 0;
        $jul2 = 0;
        $aug2 = 0;
        $sep2 = 0;
        $oct2 = 0;
        $nov2 = 0;
        $dec2 = 0;
        foreach ($ocs2 as $oc) {
            $rep_date2 = $oc['reporting_date'];
            if (substr(date($form1, $rep_date2), 0, 2) == 01) {
                $jan2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 02) {
                $feb2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 03) {
                $mar2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 04) {
                $apr2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 05) {
                $may2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 06) {
                $jun2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 07) {
                $jul2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 08) {
                $aug2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 09) {
                $sep2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 10) {
                $oct2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 11) {
                $nov2++;
            } else if (substr(date($form1, $rep_date2), 0, 2) == 12) {
                $dec2++;
            } else {
                
            }
            $cases2 = array($jan2, $feb2, $mar2, $apr2, $may2, $jun2, $jul2, $aug2, $sep2, $oct2, $nov2, $dec2);
        }

        $months = array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sept', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');

        $strXML = "<chart palette='2' decimals='0' useRoundEdges='1' legendBorderAlpha='0' showBorder='0' labelStep='1' caption='$caption' shownames='1' showvalues='0' xAxisName='Months' yAxisName='Occurence Cases'>
               <categories>";
        
        foreach ($months as $month) {
            $strXML .= "<category label='$month'/>";
        }
        $disease1_ = $disease_name1." in ".$district_name1;
        $disease2_ = $disease_name2." in ".$district_name2;
        $strXML .= "</categories>
               
        <dataset seriesName='$disease1_' color='AFD8F8' showValues='1'>";

        foreach ($cases1 as $case1) {
            $strXML .= "<set value='$case1'/>";
        }

        $strXML .= "</dataset> 
            
        <dataset seriesName='$disease2_' color='AFD8888' showValues='1'>";

        foreach ($cases2 as $case2) {
            $strXML .= "<set value='$case2'/>";
        }

        $strXML .= "</dataset>

               </chart>";
        echo $strXML;
    }
    
/**Function: facility_coords_per_disease
 * This function takes a district_id, year and a disease_id and returns an array of the coordinates of the facilities
 * within that given district for the given disease and the selected year
 */ 
    public function facility_coords_per_disease($district = 0, $disease = 0, $year = 0) {
        
        $this->load->database();

        $this->db->distinct();
        $this->db->select('facility_id,facilities.name,facilities.longitude,facilities.latitude,district_id,county_id')
                ->from('zoonotic_surveillance_data')
                ->where('district_id', $district)
                ->where('confirmed_disease_id', $disease)
                ->where('year', $year)
                ->where('confirmation',1)
                ->join('facilities', 'zoonotic_surveillance_data.facility_id = facilities.facilitycode');

        $facs = $this->db->get()->result_array();
        return $facs;
    }
    
/**Function: national_data (UNUSED)
 * This function returns an array of the occurrences of all
 * disease per province in the country.
 */
    public function national_data($disease = 0) {
        $this->load->database();

        foreach ($disease as $dis) {
            $sql = 'select * from zoonotic_surveillance_data where abs(confirmed_disease_id) = ?';
            $query = $this->db->query($sql, array($disease));
            $ocs = $query->num_rows();
            $totalOcs[] = array('name' => $facility -> Name, 'oc' => $ocs);
        }
        return $totalOcs;
        
        $this->db->distinct();
        foreach ($disease as $dis) {
            $this->db->select('provinces.name')
                    ->from('zoonotic_surveillance_data')
                    ->where('confirmed_disease_id', $dis)
                    ->join('provinces', 'zoonotic_surveillance_data.province_id = provinces.ID');

            $facs = $this->db->get()->result_array();
        }
        return $facs;
    }
        
/**Function: facility_data
 * This function takes a disease_id and a district_id and returns an array of the occurrences of the given
 * disease per facility in the given district.
 */
    public function facility_data($disease = 0, $district = 0, $year = 0) {
        $facilities = Facilities::getDistrictFacilities($district);
        $this->load->database();

        foreach ($facilities as $facility) {
            $sql = 'select * from zoonotic_surveillance_data where abs(confirmed_disease_id) = ? and abs(facility_id) = ? and abs(confirmation) = ? and abs(year) = ?';
            $query = $this->db->query($sql, array($disease, $facility->facilitycode,1,$year));
            $ocs = $query->num_rows();
            $totalOcs[] = array('name' => $facility -> Name, 'oc' => $ocs);
        }
        return $totalOcs;
    }
    
/**Function searchResults
 * This function is  called when search variables for a given disease within a certain location for a given
 * year are selected to return:
 * - a map of the occurrences of the disease within the given district in the given year per facility
 * - a graph for the disease trends for the year
 */
    function searchResults(){
        $district = $_POST['district'];
        $disease = $_POST['disease'];
        $year = $_POST['year'];
        
        $this->load->database();

        $this->db->select('name,latitude,longitude')
                ->from('districts')
                ->where('ID', $district);

        $result_district1 = $this->db->get()->row_array();
        
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $disease);

        $result_disease1 = $this->db->get()->row_array();

        $district_name = $result_district1['name'];
        $disease_name = $result_disease1['name'];
        
        $diseases = Zoonotic::getAll();
        $provinces = Province::getAll();
        $counties = County::getAll();
        $districts = District::getAll();
        $years = $this->years();

        $facilities = Facilities::getDistrictFacilities($district);

        foreach ($facilities as $facility) {
            $fac_data = $this->facility_data($disease,$district,$year);
            $data['fac_data'] = $fac_data;
        }
        
        $data['district_name'] = $district_name;
        $data['disease_name'] = $disease_name;
        
        $data['diseases'] = $diseases;
        $data['provinces'] = $provinces;
        $data['counties'] = $counties;
        $data['districts'] = $districts;
        $data['years'] = $years;
        
        $data['district'] = $district;
        $data['disease'] = $disease;
        $data['year'] = $year;

        $data['facilities'] = $facilities;

        $data['scripts'] = array("FusionCharts/FusionCharts.js");
        
        //mapping
        $cords = $this->facility_coords_per_disease($district, $disease, $year);
        
        $this->load->library('googlemaps');

        $config['center'] = $result_district1['latitude'].','.$result_district1['longitude'];
        $config['zoom'] = '10';
        $this->googlemaps->initialize($config);

        foreach($cords as $cord){
            if($cord['latitude'] !=0 &&$cord['longitude'] !=0){
            $marker = array();
            $marker['position'] = $cord['latitude'].','. $cord['longitude'];
            $marker['infowindow_content'] = $cord['name'];
            $marker['title'] = $cord['name'];
            $marker['animation'] = 'DROP';
//            $marker['onclick'] = 'alert("You just clicked me!!")';
//            $marker['onmouseover'] = $name;
            $marker['infowindow_content']=$cord['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/all_facility_data_v/'.$cord['facility_id'].'/'.$cord['district_id'].'/'.$cord['county_id'].'/'.$disease.'">Click to view details</a>';
            $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($disease).'.png';
            $this->googlemaps->add_marker($marker);
            }
        }

        $data['map'] = $this->googlemaps->create_map();

        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_search_results";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home";
        $data['quick_link'] = "zoonotic_data_report";

        
        $access = $this -> session -> userdata('user_indicator');
        if($access == ""){
            $data['res_view'] = "zoonotic_resources_v";
            $this->load->view("public_template", $data);
        }else{
            $this->load->view("template", $data);
        }
        
    }
    
/**Function: facility_coords
 * This function takes a district_id and returns an array of the coordinates of the facilities
 * within that given district
 */ 
    public function facility_coords($district = 0,$diseaseId = 0,$year = 0, $start = 0, $end = 0) {
        if($year != 0){
            $this->load->database();

            $this->db->distinct();
            $this->db->select('facility_id,facilities.name,facilities.longitude,facilities.latitude,district_id,county_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('district_id', $district)
                    ->where('confirmed_disease_id', $diseaseId)
                    ->where('year', $year)
                    ->where('confirmation',1)
                    ->join('facilities', 'zoonotic_surveillance_data.facility_id = facilities.facilitycode');

            $facs = $this->db->get()->result_array();
        }else if($start != 0){
            $this->load->database();

            $this->db->distinct();
            $this->db->select('facility_id,facilities.name,facilities.longitude,facilities.latitude,district_id,county_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('district_id', $district)
                    ->where('confirmed_disease_id', $diseaseId)
                    ->where('reporting_date >=',$start)
                    ->where('reporting_date <=',$end)
                    ->join('facilities', 'zoonotic_surveillance_data.facility_id = facilities.facilitycode');

            $facs = $this->db->get()->result_array();
        }else{
            $this->load->database();

            $this->db->distinct();
            $this->db->select('facility_id,facilities.name,facilities.longitude,facilities.latitude,district_id,county_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('district_id', $district)
                    ->where('confirmed_disease_id', $diseaseId)
                    ->join('facilities', 'zoonotic_surveillance_data.facility_id = facilities.facilitycode');

            $facs = $this->db->get()->result_array();
        }
        return $facs;
    }
    
/**Function: all_facility_data
 * This function fetches data specific to a facility and returns an array of the same
 */
    function all_facility_data_read($facility_id,$diseaseId){
        $this->load->database();
        $this->db->distinct();
        $this->db->select('lab_id,reporting_date,sex,age,suspected_disease_id,lab,sample_type_id,results,date_of_result')
                ->from('zoonotic_surveillance_data')
                ->where('facility_id', $facility_id)
                ->where('confirmation', 1)
                ->where('confirmed_disease_id', $diseaseId);

        $facility_data = $this->db->get()->result_array();
        $size = count($facility_data);
        $facility_data_a = "";
        $c = 0;
        while ($c < $size) {
            $lab_sample_id = $facility_data[$c]['lab_id'];
            
            $ocs_date = $facility_data[$c]['reporting_date'];
            
            $sex_v = $facility_data[$c]['sex'];
            if($sex_v==1){
                $sex = "Male";
            }else{
                $sex = "Female";
            }
            
            $age = $facility_data[$c]['age'];
            
            $suspected_desease_id = $facility_data[$c]['suspected_disease_id'];
            $this->db->distinct();
            $this->db->select('name')
                    ->from('zoonotic_diseases')
                    ->where('ID', $suspected_desease_id);
            $disease_name_ = $this->db->get()->result_array();
            $disease_name = $disease_name_[0]['name'];
            
            $lab_id = $facility_data[$c]['lab'];
            $this->db->distinct();
            $this->db->select('name')
                    ->from('laboratory')
                    ->where('ID', $lab_id);
            $lab_name_ = $this->db->get()->result_array();
            $lab_name = $lab_name_[0]['name'];
            
            $sample_type_id = $facility_data[$c]['sample_type_id'];
            $this->db->distinct();
            $this->db->select('sample_name')
                    ->from('lab_samples')
                    ->where('ID', $sample_type_id);
            $sample_type_ = $this->db->get()->result_array();
            $sample_type = $sample_type_[0]['sample_name'];
            
            $results = $facility_data[$c]['results'];
            
            $lab_res_date = $facility_data[$c]['date_of_result'];
            
//            echo $ocs_date.' '.$sex.' '.$disease_name.' '.$lab_name;
            $facility_data_a[] = array('sample_lab_id' =>$lab_sample_id,'reporting_date' =>$ocs_date,'sex' =>$sex,'age' => $age,'suspected_disease'=>$disease_name, 'lab_id'=>$lab_id, 'lab_name'=>$lab_name, 'sample_type'=>$sample_type ,'results'=>$results, 'lab_res_date'=>$lab_res_date);
            
            $c++;
        }
        return $facility_data_a;
    }
    
/**Function: all_district_data
 * This function returns a view with:
 * - table showing the fine details of the occurences reported within a given facility
 */
    function all_facility_data_v($facility_id = 0,$district_id = 0,$county_id = 0,$diseaseId = 0){
        $this->load->database();
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $diseaseId);

        $disease_name_ = $this->db->get()->result_array(); 
        
        $data['disease_name'] = $disease_name_[0]['name'];
        
        //getting facility details
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('facilities')
                ->where('facilitycode', $facility_id);

        $facility_name_ = $this->db->get()->result_array(); 
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district_id);

        $district_name_ = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('county')
                ->from('counties')
                ->where('ID', $county_id);

        $county_name_ = $this->db->get()->result_array();
        
        $facility_details = array('facility_name' =>$facility_name_[0]['name'],'district_name' =>$district_name_[0]['name'],'county_name'=>$county_name_[0]['county']);
        
        //getting facility data
        
        $facility_data_a = $this->all_facility_data_read($facility_id,$diseaseId);
        
        $total_cases = count($facility_data_a);
        
        $male = 0;
        $female = 0;
        foreach($facility_data_a as $data_a){
            if($data_a['sex']=='Male'){
               $male++; 
            }else if($data_a['sex']=='Female'){
                $female++;
            }
        }
        
        $data['facility_ID'] = $facility_id;
        
        $data['disease_ID'] = $diseaseId;
        
        $data['district_ID'] = $district_id;
        
        $data['county_ID'] = $county_id;
        
        $data['facility_data_a'] = $facility_data_a;
        
        $data['total_cases'] = $total_cases;
        
        $data['male_cases'] = $male;
        
        $data['female_cases'] = $female;
        
        $data['facility_details'] = $facility_details;
        
        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_facility_all_data";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home";
        $data['quick_link'] = "zoonotic_data_report";
        
        $access = $this -> session -> userdata('user_indicator');
        if($access == ""){
            $data['res_view'] = "zoonotic_resources_v";
            $this->load->view("public_template", $data);
        }else{
            $this->load->view("template", $data);
        }
    }
    
/**Function: searchDistrictResults
 * This function is called when a district is selected form the map of the country
 * It returns:
 * - a map for the given district with occurrences of all diseases per facility
 * - a table with occurrences of each disease for that district
 */
    function searchDistrictResults($latitude = 0,$longitude = 0,$district = 0,$diseaseId = 0,$year = 0,$start = 0, $end = 0){
        //mapping
        //echo $year;
        $fac_ocs = $this->facility_coords($district,$diseaseId,$year,$start,$end);
        
        $this->load->database();

        $this->db->distinct();
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $diseaseId);

        $disease_name = $this->db->get()->result_array(); 
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district);

        $district_name = $this->db->get()->result_array();
        
        $this->load->library('googlemaps');

        $config['center'] = $latitude.','.$longitude;
        $config['zoom'] = '10';
        $this->googlemaps->initialize($config);
        
        foreach($fac_ocs as $fac_oc){
            if($fac_oc['latitude'] !=0 &&$fac_oc['longitude'] !=0){
            $marker = array();
            $marker['position'] = $fac_oc['latitude'].','. $fac_oc['longitude'];
            $marker['infowindow_content'] = $fac_oc['name'];
            $marker['title'] = $fac_oc['name'];
            $marker['animation'] = 'DROP';
            $marker['infowindow_content']=$fac_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/all_facility_data_v/'.$fac_oc['facility_id'].'/'.$fac_oc['district_id'].'/'.$fac_oc['county_id'].'/'.$diseaseId.'">Click to view details</a>';
//            $marker['onclick'] = 'alert("You just clicked me!!")';
//            $marker['onmouseover'] = $name;
            $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($diseaseId).'.png';
            $this->googlemaps->add_marker($marker);
            }
        }
        $data['map'] = $this->googlemaps->create_map();
        $data['disease_name'] = $disease_name;
        $data['district_name'] = $district_name;

        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_district_search_results";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home";
        $data['quick_link'] = "zoonotic_data_report";
        
        $access = $this -> session -> userdata('user_indicator');
        if($access == ""){
            $data['res_view'] = "zoonotic_resources_v";
            $this->load->view("public_template", $data);
        }else{
            $this->load->view("template", $data);
        }
    }
    
    function specific_disease_facility_occurence($disease_id){
        $this->load->database();

        $this->db->distinct();
        $this->db->select('facility_id,district_id,county_id,facilities.name,facilities.longitude,facilities.latitude,confirmed_disease_id')
                ->from('zoonotic_surveillance_data')
                ->where('confirmed_disease_id',$disease_id)
                ->where('confirmation',1)
                ->join('facilities', 'zoonotic_surveillance_data.facility_id = facilities.facilitycode');

        $updates = $this->db->get()->result_array();
        return $updates;
    }
    
/**Function: compareResults
 * This function returns a view with:
 * - a map with markers showing the occurence of the 2 diseases to be compared per facility in the given districts
 * - a frequency chart showing comparison in trends of the 2 diseases
 * - tables showing occurence of the 2 diseases to be compared per facility
 */
    function compareResults($dist = 0, $yr = 0, $dis = 0){
//        $district = $_POST['districtC'];
//        $disease = $_POST['diseaseC'];
//        $year = $_POST['yearC'];
          $district = $this -> input -> post("districtC");
          $disease = $this -> input -> post("diseaseC");
          $year = $this -> input -> post("yearC");
//        echo 'districtC='.$district.' diseaseC='.$disease.' yearC='.$year;
//        echo 'district='.$dist.' disease='.$dis.' year='.$yr;
        
        $this->load->database();

        $this->db->select('name')
                ->from('districts')
                ->where('ID', $dist);

        $result_district1 = $this->db->get()->row_array();
        
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $dis);

        $result_disease1 = $this->db->get()->row_array();

        $district_name1 = $result_district1['name'];
        $disease_name1 = $result_disease1['name'];
        
        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district);

        $result_district2 = $this->db->get()->row_array();
        
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $disease);

        $result_disease2 = $this->db->get()->row_array();
        
        $district_name2 = $result_district2['name'];
        $disease_name2 = $result_disease2['name'];
//        echo "District ".$district_name2;
//        echo "Disease ".$disease_name2;
        $facilities1 = Facilities::getDistrictFacilities($dist);

        foreach ($facilities1 as $facility) {
            $fac_data1 = $this->facility_data($dis,$dist,$yr);
            $data['fac_data1'] = $fac_data1;
        }
        
        $facilities2 = Facilities::getDistrictFacilities($district);

        foreach ($facilities2 as $facility) {
            $fac_data2 = $this->facility_data($disease,$district,$year);
            $data['fac_data2'] = $fac_data2;
        }
        
        $data['district1'] = $dist;
        $data['year1'] = $yr;
        $data['disease1'] = $dis;
        
        $data['district2'] = $district;
        $data['year2'] = $year;
        $data['disease2'] = $disease;
        
        $data['scripts'] = array("FusionCharts/FusionCharts.js");
        
        $data['district1_name'] = $district_name1;
        $data['disease1_name'] = $disease_name1;
        
        $data['district2_name'] = $district_name2;
        $data['disease2_name'] = $disease_name2;
        
        $diseases = Zoonotic::getAll();
        $data['diseases'] = $diseases;
        
        //mapping
        $fac_oc1 = $this->specific_disease_facility_occurence($dis);
        $fac_oc2 = $this->specific_disease_facility_occurence($disease);
        
        $this->load->library('googlemaps');

            $config['center'] = '0.4252, 36.7517'; //cordinates for Kenya
            $config['zoom'] = '6';
            $this->googlemaps->initialize($config);
            $config['cluster'] = TRUE;

            foreach($fac_oc1 as $fac_oc){
                if($fac_oc['latitude'] !=0 &&$fac_oc['longitude'] !=0){
                    //echo $dist_oc['confirmed_disease_id'];
                $marker = array();
                $marker['position'] = $fac_oc['latitude'].','. $fac_oc['longitude'];
                $marker['infowindow_content']=$fac_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/all_facility_data_v/'.$fac_oc['facility_id'].'/'.$fac_oc['district_id'].'/'.$fac_oc['county_id'].'/'.$fac_oc['confirmed_disease_id'].'">Click to view details</a>';
//                $marker['infowindow_content']=$fac_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$fac_oc['latitude'].'/'.$fac_oc['longitude'].'/'.$fac_oc['district_id'].'/'.$fac_oc['confirmed_disease_id'].'">Click to view details</a>';
                $marker['title'] = $fac_oc['name'];
                $marker['animation'] = 'DROP';
    //            $marker['infowindow_content'] = $dist_oc['name'];
    //            $marker['onclick'] = 'alert("You just clicked me!!")';
    //            $marker['onmouseover'] = $name;
                $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($fac_oc['confirmed_disease_id']).'.png';
                $this->googlemaps->add_marker($marker);
                }
            }
            
            foreach($fac_oc2 as $fac_oc){
                if($fac_oc['latitude'] !=0 &&$fac_oc['longitude'] !=0){
                    //echo $dist_oc['confirmed_disease_id'];
                $marker = array();
                $marker['position'] = $fac_oc['latitude'].','. $fac_oc['longitude'];
                $marker['infowindow_content']=$fac_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/all_facility_data_v/'.$fac_oc['facility_id'].'/'.$fac_oc['district_id'].'/'.$fac_oc['county_id'].'/'.$fac_oc['confirmed_disease_id'].'">Click to view details</a>';
//                $marker['infowindow_content']=$fac_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$fac_oc['latitude'].'/'.$fac_oc['longitude'].'/'.$fac_oc['district_id'].'/'.$fac_oc['confirmed_disease_id'].'">Click to view details</a>';
                $marker['title'] = $fac_oc['name'];
                $marker['animation'] = 'DROP';
    //            $marker['infowindow_content'] = $dist_oc['name'];
    //            $marker['onclick'] = 'alert("You just clicked me!!")';
    //            $marker['onmouseover'] = $name;
                $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($fac_oc['confirmed_disease_id']).'.png';
                $this->googlemaps->add_marker($marker);
                }
            }
        $data['map'] = $this->googlemaps->create_map();
        
        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_compare_results";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home";
        $data['quick_link'] = "zoonotic_data_report";
        
        $access = $this -> session -> userdata('user_indicator');
        if($access == ""){
            $data['res_view'] = "zoonotic_resources_v";
            $this->load->view("public_template", $data);
        }else{
            $this->load->view("template", $data);
        }
    }
    
    function specific_disease_district_occurence($disease_id){
        $this->load->database();

        $this->db->distinct();
        $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                ->from('zoonotic_surveillance_data')
                ->where('confirmed_disease_id',$disease_id)
                ->where('confirmation',1)
                ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

        $updates = $this->db->get()->result_array();
        /**Data processed by date starts here**/
//        $this->load->helper('date');
//        
//        if($per == 0){
//            $dist_ocs = $this->district_occurences(mdate("%Y"));
//            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".mdate("%Y");
//        }else if($per == 1){
//            $start = $from;
//            $end = $to;
//            
//            $this->load->helper('date');
//            
//            $format_ = 'D, d M Y';
//            $start_ = strtotime($start);
//            $end_ = strtotime($end);
//            
//            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for the period between ".date($format_, $start_)." and ".date($format_, $end_);
//            
//            $this->load->database();
//
//            $this->db->distinct();
//            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
//                    ->from('zoonotic_surveillance_data')
//                    ->where('reporting_date >=',$start_)
//                    ->where('reporting_date <=',$end_)
//                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');
//
//            $occurences = $this->db->get()->result_array();
//            
//            $dist_ocs = $occurences;
//        }else if($per == 2){
//            $month = $from;
//            $year = $to;
//            
//            $start_day = 1;
//            if($month == 01 || $month == 03 || $month == 05 || $month == 07 || $month == 08 || $month == 10 || $month == 12){
//                $end_day = 31;
//            }else if($month == 02){
//                $end_day = 28;
//            }else if($month == 02 && $year % 4 == 0){
//                $end_day = 29;
//            }else{
//                $end_day = 30;
//            }
//            
//            $start = $month.'/'.$start_day.'/'.$year;
//            $end = $month.'/'.$end_day.'/'.$year; 
//            
//            $format_ = 'M Y';
//            $start_ =  strtotime($start);
//            $end_ =  strtotime($end);
//            //echo date($format_, $tstamp);
//            
//            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for  ".date($format_, $start_);
//            
//            $this->load->database();
//
//            $this->db->distinct();
//            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
//                    ->from('zoonotic_surveillance_data')
//                    ->where('reporting_date >=',$start_)
//                    ->where('reporting_date <=',$end_)
//                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');
//
//            $occurences = $this->db->get()->result_array();
//            
//            $dist_ocs = $occurences;
//        }else if($per == 3){
//            $start = $from;
//            $dist_ocs = $this->district_occurences($start);
//            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".$start;
//        }
        /**Data processed by date ends here**/
        return $updates;
    }
    
    function checked_diseases(){
        $checked =  $_POST['disese_name'];
        
        $diseases = Zoonotic::getAll();
        $provinces = Province::getAll();
        $counties = County::getAll();
        $districts = District::getAll();
        $years = $this->years();

        $data['scripts'] = array("FusionCharts/FusionCharts.js");

        $central = $this->occurences_per_prov(1);

        $coast = $this->occurences_per_prov(2);

        $eastern = $this->occurences_per_prov(3);

        $nairobi = $this->occurences_per_prov(4);

        $northEastern = $this->occurences_per_prov(6);

        $nyanza = $this->occurences_per_prov(7);

        $riftValley = $this->occurences_per_prov(8);

        $western = $this->occurences_per_prov(9);
        
        $this->load->database();
        $this->db->select('name')
                ->from('zoonotic_diseases');

        $disease = $this->db->get()->result_array();
        
        $count  = 0;
        foreach ($disease as $dis){
            $nationals[] = array('disease' =>$dis['name'],'central'=>(string)$central[$count],'coast'=>(string)$coast[$count],'eastern'=>(string)$eastern[$count], 'nairobi'=>(string)$nairobi[$count], 'northEastern'=>(string)$northEastern[$count], 'nyanza'=>(string)$nyanza[$count], 'riftValley'=>(string)$riftValley[$count], 'western'=>(string)$western[$count]);
            $count++;
            $data['nationals'] = $nationals;
        }
        $data['diseases'] = $diseases;
        $data['provinces'] = $provinces;
        $data['counties'] = $counties;
        $data['districts'] = $districts;
        $data['years'] = $years;
       
        //mapping
        
        foreach($checked as $check){//start of loop
            $dist_ocs = $this->specific_disease_district_occurence($check);

            $this->load->library('googlemaps');

            $config['center'] = '0.4252, 36.7517'; //cordinates for Kenya
            $config['zoom'] = '6';
            $this->googlemaps->initialize($config);
            $config['cluster'] = TRUE;

            foreach($dist_ocs as $dist_oc){
                if($dist_oc['latitude'] !=0 &&$dist_oc['longitude'] !=0){
                    //echo $dist_oc['confirmed_disease_id'];
                $marker = array();
                $marker['position'] = $dist_oc['latitude'].','. $dist_oc['longitude'];
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'">Click to view details</a>';
                $marker['title'] = $dist_oc['name'];
                $marker['animation'] = 'DROP';
    //            $marker['infowindow_content'] = $dist_oc['name'];
    //            $marker['onclick'] = 'alert("You just clicked me!!")';
    //            $marker['onmouseover'] = $name;
                $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($dist_oc['confirmed_disease_id']).'.png';
                $this->googlemaps->add_marker($marker);
                }
            }
        }//end of loop
        $data['map'] = $this->googlemaps->create_map();
        
        $data['map_title'] = "Distribution of occurrence of specific Zoonotic and Neglected diseases within the various districts in Kenya";

        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_report_v";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home";
        $data['quick_link'] = "zoonotic_data_report";

        $access = $this -> session -> userdata('user_indicator');
        if($access == ""){
            $data['res_view'] = "zoonotic_resources_v";
            $this->load->view("public_template", $data);
        }else{
            $this->load->view("template", $data);
        }
    }
    /*This method create a view for public consumption only
     * NB: Any methods below this point relate to public view only
     */
    public function public_view($per = 0, $from = 0, $to = 0){
        $diseases = Zoonotic::getAll();
        $provinces = Province::getAll();
        $counties = County::getAll();
        $districts = District::getAll();
        $years = $this->years();

        $data['scripts'] = array("FusionCharts/FusionCharts.js");

        $central = $this->occurences_per_prov(1);

        $coast = $this->occurences_per_prov(2);

        $eastern = $this->occurences_per_prov(3);

        $nairobi = $this->occurences_per_prov(4);

        $northEastern = $this->occurences_per_prov(6);

        $nyanza = $this->occurences_per_prov(7);

        $riftValley = $this->occurences_per_prov(8);

        $western = $this->occurences_per_prov(9);
        
        $this->load->database();
        $this->db->select('name')
                ->from('zoonotic_diseases');

        $disease = $this->db->get()->result_array();
        
        $count  = 0;
        foreach ($disease as $dis){//
            $nationals[] = array('disease' =>$dis['name'],'central'=>(string)$central[$count],'coast'=>(string)$coast[$count],'eastern'=>(string)$eastern[$count], 'nairobi'=>(string)$nairobi[$count], 'northEastern'=>(string)$northEastern[$count], 'nyanza'=>(string)$nyanza[$count], 'riftValley'=>(string)$riftValley[$count], 'western'=>(string)$western[$count]);
            $count++;
            $data['nationals'] = $nationals;
        }
        $data['diseases'] = $diseases;
        $data['provinces'] = $provinces;
        $data['counties'] = $counties;
        $data['districts'] = $districts;
        $data['years'] = $years;
        
        //mapping
        
        $this->load->helper('date');
        
        if($per == 0){
            $dist_ocs = $this->district_occurences(mdate("%Y"));
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".mdate("%Y");
        }else if($per == 1){
            $start = $from;
            $end = $to;
            
            $this->load->helper('date');
            
            $format_ = 'D, d M Y';
            $start_ = strtotime($start);
            $end_ = strtotime($end);
            
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for the period between ".date($format_, $start_)." and ".date($format_, $end_);
            
            $this->load->database();

            $this->db->distinct();
            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('reporting_date >=',$start_)
                    ->where('reporting_date <=',$end_)
                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

            $occurences = $this->db->get()->result_array();
            
            $dist_ocs = $occurences;
        }else if($per == 2){
            $month = $from;
            $year = $to;
            
            $start_day = 1;
            if($month == 01 || $month == 03 || $month == 05 || $month == 07 || $month == 08 || $month == 10 || $month == 12){
                $end_day = 31;
            }else if($month == 02){
                $end_day = 28;
            }else if($month == 02 && $year % 4 == 0){
                $end_day = 29;
            }else{
                $end_day = 30;
            }
            
            $start = $month.'/'.$start_day.'/'.$year;
            $end = $month.'/'.$end_day.'/'.$year; 
            
            $format_ = 'M Y';
            $start_ =  strtotime($start);
            $end_ =  strtotime($end);
            //echo date($format_, $tstamp);
            
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for  ".date($format_, $start_);
            
            $this->load->database();

            $this->db->distinct();
            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('reporting_date >=',$start_)
                    ->where('reporting_date <=',$end_)
                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

            $occurences = $this->db->get()->result_array();
            
            $dist_ocs = $occurences;
        }else if($per == 3){
            $start = $from;
            $dist_ocs = $this->district_occurences($start);
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".$start;
        }
        
        $this->load->library('googlemaps');

        $config['center'] = '0.4252, 36.7517'; //cordinates for Kenya
        $config['zoom'] = '6';
        $this->googlemaps->initialize($config);
        $config['cluster'] = TRUE;

        foreach($dist_ocs as $dist_oc){
            if($dist_oc['latitude'] !=0 &&$dist_oc['longitude'] !=0){
            $marker = array();
            $marker['position'] = $dist_oc['latitude'].','. $dist_oc['longitude'];
            if($per == 0){
                $start_ = 0;
                $end_ = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.mdate("%Y").'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 1){
                $year = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$year.'/'.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 2){
                $year = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$year.'/'.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 3){
                $start_ = 0;
                $end_ = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$from.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else{
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'">Click to view details</a>';
            }
            $marker['title'] = $dist_oc['name'];
            $marker['animation'] = 'DROP';
            $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($dist_oc['confirmed_disease_id']).'.png';
            $this->googlemaps->add_marker($marker);
            }
        }

        $data['map'] = $this->googlemaps->create_map();

        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_report_v";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home_controller";
        $data['quick_link'] = "zoonotic_data_report";

        $this->load->view("public_template", $data);
    }
    
    public function getDiseaseColorCode($diseaseId){
        $this->load->database();
        
        if($diseaseId != 99 && $diseaseId != 100){
            $this->db->distinct();
                $this->db->select('color_code')
                        ->from('zoonotic_diseases')
                        ->where('ID ',$diseaseId);

            $code = $this->db->get()->result_array();
            $color_code = $code[0]['color_code'];
        }else{
            $color_code = "";
        }
        return $color_code;
    }
    
    public function track(){
        // get ip
        $ip = $_SERVER['REMOTE_ADDR'];
        $query_string = $_SERVER['QUERY_STRING'];
        $http_referer = $_SERVER['HTTP_REFERER'];
        $http_user_agent = $_SERVER['HTTP_USER_AGENT'];
        //$remote_host = $_SERVER['REMOTE_HOST'];
        $request_uri = $_SERVER['REQUEST_URI'];
        
        // check if it's a bot
        if ($this->is_bot())
            $isbot = 1;
        else
            $isbot = 0;
        
        include('ip2locationlite.class.php');
        //Load the class
        $ipLite = new ip2location_lite;
        $ipLite->setKey('8c6e513dcc592b19cf968dda54e631375cd1f5bde9c5c3060f05390d933c6aac');

        //Get errors and locations
        $locations = $ipLite->getCity($ip);
        $errors = $ipLite->getError();

        //Getting the result
//        if (!empty($locations) && is_array($locations)) {
//        foreach ($locations as $field => $val) {
//                if ($field == 'countryName')
//                        $country = $val;
//            if ($field == 'cityName')
//                        $city = $val;
//        }
//        }

        // insert into db
        date_default_timezone_set('UTC');
        $date = date("Y-m-d");
        $time = date("H:i:s");
        
        $this->load->database();
        
        $data = array(
            //'country' => $country ,
            //'city' =>  $city,
            'date' => $date,
            'time' => $time,
            'ip' => $ip,
            'query_string' => $query_string,
            'http_referer' => $http_referer,
            'http_user_agent' => $http_user_agent,
            //'remote_host' => $remote_host,
            //'request_uri' => $request_uri,
            'isbot' => $isbot,
            //'page' => $page
        );
            // insert the data
        $this->db->insert('tracker', $data);
    }
    
    public function is_bot(){
	$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
		"looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
		"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
		"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
		"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
		"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
		"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
		"Butterfly","Twitturls","Me.dium","Twiceler","Purebot","facebookexternalhit",
		"Yandex","CatchBot","W3C_Validator","Jigsaw","PostRank","Purebot","Twitterbot",
		"Voyager","zelist");

	foreach($botlist as $bot){
		if(strpos($_SERVER['HTTP_USER_AGENT'],$bot)!==false)
		return true;	// Is a bot
	}
	return false;	// Not a bot
    }
    
    public function nationalDiseaseTrendGraph($disease) {
        $this->load->database();
        
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $disease);

        $result_disease = $this->db->get()->row_array();
        
        $disease_name = $result_disease['name'];
        
        $caption = "Disease trends for ".$disease_name;
                
        $central = $this->occurences_per_prov_per_disease(1,$disease);

        $coast = $this->occurences_per_prov_per_disease(2,$disease);

        $eastern = $this->occurences_per_prov_per_disease(3,$disease);

        $nairobi = $this->occurences_per_prov_per_disease(4,$disease);

        $northEastern = $this->occurences_per_prov_per_disease(6,$disease);

        $nyanza = $this->occurences_per_prov_per_disease(7,$disease);

        $riftValley = $this->occurences_per_prov_per_disease(8,$disease);

        $western = $this->occurences_per_prov_per_disease(9,$disease);
        
        
        $cases = array($central, $coast, $eastern, $nairobi, $northEastern, $nyanza, $riftValley, $western);
        echo $cases;
         
        $provinces = array('1' => 'Central', '2' => 'Coast', '3' => 'Eastern', '4' => 'Nairobi', '5' => 'North Eastern', '6' => 'Nyanza', '7' => 'Rift Valley', '8' => 'Western');

        $strXML = "<chart palette='2' decimals='0' useRoundEdges='1' legendBorderAlpha='0' showBorder='0' labelStep='1' caption='$caption' shownames='1' showvalues='0' xAxisName='Provinces' yAxisName='Occurence Cases'>
               <categories>";
        foreach ($provinces as $province) {
            $strXML .= "<category label='$province'/>";
        }
        $strXML .= "</categories>
               <dataset seriesName='Number of Cases' color='AFD8F8' showValues='1'>";

        foreach ($cases as $case) {
            $strXML .= "<set value='$case'/>";
        }

        $strXML .= "</dataset>        
               </chart>";
        echo $strXML;
    }
    
    public function occurences_per_prov_per_disease($prov = 0, $disease = 0) {
        $this->load->database();

        $sql = 'select * from zoonotic_surveillance_data where abs(confirmed_disease_id) = ? and abs(province_id) = ? and abs(confirmation) = ?';
        $query = $this->db->query($sql, array($disease, $prov, 1));
        $ocs = $query->num_rows();
        
        return $ocs;
    }
    
    function all_facility_data_read_pdf($facility_id,$diseaseId){
        $this->load->database();
        $this->db->distinct();
        $this->db->select('lab_id,reporting_date,sex,age,suspected_disease_id,lab,sample_type_id,results,date_of_result')
                ->from('zoonotic_surveillance_data')
                ->where('facility_id', $facility_id)
                ->where('confirmation', 1)
                ->where('confirmed_disease_id', $diseaseId);

        $facility_data = $this->db->get()->result_array();
        $size = count($facility_data);
        $facility_data_a = array();
        $c = 0;
        $format_ = 'D, d M Y';
        while ($c < $size) {
            $lab_sample_id = $facility_data[$c]['lab_id'];
            //date($format_,$data_['oc_date'])
            $ocs_date = date($format_,(int)($facility_data[$c]['reporting_date']));
            
            $sex_v = $facility_data[$c]['sex'];
            if($sex_v==1){
                $sex = "Male";
            }else{
                $sex = "Female";
            }
            
            $age = $facility_data[$c]['age'];
            
            $suspected_desease_id = $facility_data[$c]['suspected_disease_id'];
            $this->db->distinct();
            $this->db->select('name')
                    ->from('zoonotic_diseases')
                    ->where('ID', $suspected_desease_id);
            $disease_name_ = $this->db->get()->result_array();
            $disease_name = $disease_name_[0]['name'];
            
            $lab_id = $facility_data[$c]['lab'];
            $this->db->distinct();
            $this->db->select('name')
                    ->from('laboratory')
                    ->where('ID', $lab_id);
            $lab_name_ = $this->db->get()->result_array();
            $lab_name = $lab_name_[0]['name'];
            
            $sample_type_id = $facility_data[$c]['sample_type_id'];
            $this->db->distinct();
            $this->db->select('sample_name')
                    ->from('lab_samples')
                    ->where('ID', $sample_type_id);
            $sample_type_ = $this->db->get()->result_array();
            $sample_type = $sample_type_[0]['sample_name'];
            
            $results = $facility_data[$c]['results'];
            
            $lab_res_date = date($format_,(int)($facility_data[$c]['date_of_result']));
            
            $facility_data_a[] = array('sample_id'=>$lab_sample_id,'oc_date'=>$ocs_date,'sex'=>$sex,'age'=>$age,'disease_name'=>$disease_name,'lab_name'=>$lab_name,'sample'=>$sample_type ,'results'=>$results,'res_date'=>$lab_res_date);
            
            $c++;
        }
        return $facility_data_a;
    }
    
    public function download_pdf($facility_id = 0,$diseaseId = 0, $district_id = 0, $county_id = 0){
        $this->load->library('fpdf');
        
        $facility_data = $this -> all_facility_data_read_pdf($facility_id,$diseaseId);
        
        $this->load->database();
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $diseaseId);

        $disease_name_ = $this->db->get()->result_array(); 
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('facilities')
                ->where('facilitycode', $facility_id);

        $facility_name_ = $this->db->get()->result_array(); 
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district_id);

        $district_name_ = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('county')
                ->from('counties')
                ->where('ID', $county_id);

        $county_name_ = $this->db->get()->result_array();
        
        $pdf = new FPDF('L','mm','A4');
        $pdf->SetFont('Arial','B',14);
        $pdf->AddPage();
        
        //Title
        $pdf->Cell(270,12,'Occurences of '.$disease_name_[0]['name'].' in '.$facility_name_[0]['name'].', '.$district_name_[0]['name'].' Sub-county, '.$county_name_[0]['county'].' County',0,0,'C',0);
        
        $pdf->SetFillColor(255,0,0);
        $pdf->SetTextColor(255);
        
        $pdf->Ln();
        
        //Header
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(26,10,'Lab Sample ID',1,0,'C',true);
        $pdf->Cell(32,10,'Reporting Date',1,0,'C',true);
        $pdf->Cell(27,10,'Patient Gender',1,0,'C',true);
        $pdf->Cell(30,10,'Patient Age(yrs.)',1,0,'C',true);
        $pdf->Cell(35,10,'Suspected Disease',1,0,'C',true);
        $pdf->Cell(40,10,'Reporting Lab',1,0,'C',true);
        $pdf->Cell(23,10,'Sample Type',1,0,'C',true);
        $pdf->Cell(28,10,'Results',1,0,'C',true);
        $pdf->Cell(32,10,'Lab Results Date',1,1,'C',true);

        //Data
        $format_ = 'D, d M Y';
        $pdf->SetFont('Arial','',10);
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        
        $fill = false;
        foreach($facility_data as $data_){
            $pdf->Cell(26,9,$data_['sample_id'],1,0,'C',$fill);
            $pdf->Cell(32,9,$data_['oc_date'],1,0,'C',$fill);
            $pdf->Cell(27,9,$data_['sex'],1,0,'C',$fill);
            $pdf->Cell(30,9,$data_['age'],1,0,'C',$fill);
            $pdf->Cell(35,9,$data_['disease_name'],1,0,'C',$fill);
            $pdf->Cell(40,9,$data_['lab_name'],1,0,'C',$fill);
            $pdf->Cell(23,9,$data_['sample'],1,0,'C',$fill);
            $pdf->Cell(28,9,$data_['results'],1,0,'C',$fill);
            $pdf->Cell(32,9,$data_['res_date'],1,1,'C',$fill);
            $fill = !$fill;
        }
        $pdf->Output('Occurences of '.$disease_name_[0]['name'].' in '.$facility_name_[0]['name'].', '.$district_name_[0]['name'].' Sub-county, '.$county_name_[0]['county'].' County.pdf', 'D');
    }
    /*Data output in CSV, without using libraries*/
    public function download_csv($facility_id = 0,$diseaseId = 0, $facility_name =0, $district_name = 0, $county_name = 0){
        $facility_data = $this -> all_facility_data_read_pdf($facility_id,$diseaseId);
        $this->load->database();
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $diseaseId);

        $disease_name_ = $this->db->get()->result_array();
        
        $filename = 'Occurences of '.$disease_name_[0]['name'].' in '.urldecode($facility_name);
        $fieldnames = 'Lab Sample,Reporting Date,Patient Gender,Patient Age(yrs),Suspected Disease,Reporting Lab,Sample Type,Results,Lab Results Date';
        $columnnames = explode(",", $fieldnames);
        
        $headers = '';
        $data = '';
        
        if (count($facility_data) == 0) {
            echo '<p>The table appears to have no data.</p>';
        }else{
//            $title = 'Occurences of '.$disease_name_[0]['name'].' in '.urldecode($facility_name).', '.urldecode($district_name).' Sub-county, '.$county_name.' County'.",";
            $i = 0;
            for($i = 0;$i<= 8;$i++){
                $headers .= $columnnames[$i].",";
            }
            foreach ($facility_data as $row) {
                $line = '';
                foreach($row as $value) {                                            
                        if ((!isset($value)) OR ($value == "")) {
                            $value = "\t";
                        } else {
                            $value = str_replace('"', '""', $value);
                            $value = '"' . $value . '"' . ",";
                        }
                        $value = utf8_decode($value);
                        $line .= $value;
                }
                $data .= trim($line)."\n";
            }
            $data = str_replace("\r","",$data);

            header("Content-type: application/x-msdownload");
            header("Content-Disposition: attachment; filename=$filename.csv");
            echo "$headers\n$data"; 
        }
    }
    
    /*Exporting data to sql using library*/
    public function download_excel_($facility_id = 0,$diseaseId = 0, $district_id = 0, $county_id = 0){
        $facility_data = $this -> all_facility_data_read_pdf($facility_id,$diseaseId);
        
        $this->load->database();
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('zoonotic_diseases')
                ->where('ID', $diseaseId);

        $disease_name_ = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('facilities')
                ->where('facilitycode', $facility_id);

        $facility_name_ = $this->db->get()->result_array(); 
        
        $this->db->distinct();
        $this->db->select('name')
                ->from('districts')
                ->where('ID', $district_id);

        $district_name_ = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('county')
                ->from('counties')
                ->where('ID', $county_id);

        $county_name_ = $this->db->get()->result_array();
        
        $title = 'Occurences of '.$disease_name_[0]['name'].' in '.$facility_name_[0]['name'].', '.$district_name_[0]['name'].' Sub-county, '.$county_name_[0]['county'].' County';
        
        $this->load->library('phpexcel');
        $this->load->library('phpexcel/iofactory');
        
        $objPHPExcel = new PHPExcel();
        // Set the active Excel worksheet to sheet 0 
        $objPHPExcel->setActiveSheetIndex(0);  
        // Initialise the Excel row number 
        $rowCount = 1;  
        $fieldnames = 'Lab Sample,Reporting Date,Patient Gender,Patient Age(yrs),Suspected Disease,Reporting Lab,Sample Type,Results,Lab Results Date';
        $columnnames = explode(",", $fieldnames);
        //start of printing column names as names of MySQL fields  
        $column = 'A';
        for($i = 0;$i<= 8;$i++){
            $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $columnnames[$i]);
            $column++;
        }
        
        //start while loop to get data  
        $rowCount = 2; 
        foreach ($facility_data as $row) {
                $column = 'A';
                foreach($row as $value) {                                            
                        if ((!isset($value)) OR ($value == "")) {
                            $value = NULL;
                        } else {
                            $value = strip_tags($value);
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $value);
                        $column++;
                }
                $rowCount++;
            }
            
            foreach(range('A','I') as $columnID) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            
            // Redirect output to a client’s web browser (Excel5) 
            header('Content-Type: application/vnd.ms-excel'); 
            header('Content-Disposition: attachment;filename="'.$title.'.xls"'); 
            header('Cache-Control: max-age=0'); 
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5'); 
            $objWriter->save('php://output');
    }
    
    public function lab_data(){
        $id =  $_POST['id'];
        
//        $this->load->database();
//        
//        $this->db->distinct();
//        $this->db->select('name')
//                ->from('laboratory')
//                ->where('ID', $id);
//        $lab_name_ = $this->db->get()->result_array();
//        $lab_name = $lab_name_['name'];
//        var_dump($lab_name_);
        echo '<div>
                    <p>NAME</>
             </div>';
//        echo '<div>
//                    <p>'.$id.'</>
//                    <table style="margin: 10px auto; width: 700px">
//                        <tr>
//                            <td><b>Lab Name :</b></td>
//                            <td>Lab Name</td>
//                            <td><b>Lab Category :</b></td>
//                            <td>Private</td>
//                        </tr>
//                        <tr>
//                            <td><b>County :</b></td>
//                            <td>One</td>
//                            <td><b>District :</b></td>
//                            <td>two</td>
//                        </tr>
//                     </table> 
//                    
//                </div>';




   function load_livestock($per = 0, $from = 0, $to = 0) {
        $this->track();
        
        $diseases = Zoonotic::getAll();
        $provinces = Province::getAll();
        $counties = County::getAll();
        $districts = District::getAll();
        $years = $this->years();
        
        $data['scripts'] = array("FusionCharts/FusionCharts.js");

        $central = $this->occurences_per_prov(1);

        $coast = $this->occurences_per_prov(2);

        $eastern = $this->occurences_per_prov(3);

        $nairobi = $this->occurences_per_prov(4);

        $northEastern = $this->occurences_per_prov(6);

        $nyanza = $this->occurences_per_prov(7);

        $riftValley = $this->occurences_per_prov(8);

        $western = $this->occurences_per_prov(9);
        
        $this->load->database();
        $this->db->select('name')
                ->from('zoonotic_diseases');

        $disease = $this->db->get()->result_array();
        
        $count  = 0;
        foreach ($disease as $dis){//
            $nationals[] = array('disease' =>$dis['name'],'central'=>(string)$central[$count],'coast'=>(string)$coast[$count],'eastern'=>(string)$eastern[$count], 'nairobi'=>(string)$nairobi[$count], 'northEastern'=>(string)$northEastern[$count], 'nyanza'=>(string)$nyanza[$count], 'riftValley'=>(string)$riftValley[$count], 'western'=>(string)$western[$count]);
            $count++;
            $data['nationals'] = $nationals;
        }
        $data['diseases'] = $diseases;
        $data['provinces'] = $provinces;
        $data['counties'] = $counties;
        $data['districts'] = $districts;
        $data['years'] = $years;
        
        //mapping
        
        $this->load->helper('date');
        
        if($per == 0){
            $dist_ocs = $this->district_occurences(mdate("%Y"));
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".mdate("%Y");
        }else if($per == 1){
            $start = $from;
            $end = $to;
            
            $this->load->helper('date');
            
            $format_ = 'D, d M Y';
            $start_ = strtotime($start);
            $end_ = strtotime($end);
            
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for the period between ".date($format_, $start_)." and ".date($format_, $end_);
            
            $this->load->database();

            $this->db->distinct();
            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('reporting_date >=',$start_)
                    ->where('reporting_date <=',$end_)
                    ->where('confirmation',1)
                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

            $occurences = $this->db->get()->result_array();
            
            $dist_ocs = $occurences;
        }else if($per == 2){
            $month = $from;
            $year = $to;
            
            $start_day = 1;
            if($month == 01 || $month == 03 || $month == 05 || $month == 07 || $month == 08 || $month == 10 || $month == 12){
                $end_day = 31;
            }else if($month == 02){
                $end_day = 28;
            }else if($month == 02 && $year % 4 == 0){
                $end_day = 29;
            }else{
                $end_day = 30;
            }
            
            $start = $month.'/'.$start_day.'/'.$year;
            $end = $month.'/'.$end_day.'/'.$year; 
            
            $format_ = 'M Y';
            $start_ =  strtotime($start);
            $end_ =  strtotime($end);
            //echo date($format_, $tstamp);
            
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases for  ".date($format_, $start_);
            
            $this->load->database();

            $this->db->distinct();
            $this->db->select('district_id,districts.name,districts.longitude,districts.latitude,confirmed_disease_id')
                    ->from('zoonotic_surveillance_data')
                    ->where('reporting_date >=',$start_)
                    ->where('reporting_date <=',$end_)
                    ->where('confirmation',1)
                    ->join('districts', 'zoonotic_surveillance_data.district_id = districts.ID');

            $occurences = $this->db->get()->result_array();
            
            $dist_ocs = $occurences;
        }else if($per == 3){
            $start = $from;
            $dist_ocs = $this->district_occurences($start);
            $data['map_title'] = "Distribution of occurrence of Zoonotic and Neglected diseases within the various districts in Kenya in ".$start;
        }
        
        $this->load->library('googlemaps');

        $config['center'] = '0.4252, 36.7517'; //cordinates for Kenya
        $config['zoom'] = '6';
        $this->googlemaps->initialize($config);
        $config['cluster'] = TRUE;

        foreach($dist_ocs as $dist_oc){
            if($dist_oc['latitude'] !=0 &&$dist_oc['longitude'] !=0){
            $marker = array();
            $marker['position'] = $dist_oc['latitude'].','. $dist_oc['longitude'];
            if($per == 0){
                $start_ = 0;
                $end_ = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.mdate("%Y").'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 1){
                $year = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$year.'/'.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 2){
                $year = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$year.'/'.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else if($per == 3){
                $start_ = 0;
                $end_ = 0;
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'/'.$from.'/'.$start_.'/'.$end_.'">Click to view details</a>';
            }else{
                $marker['infowindow_content']=$dist_oc['name'].'<br><br><a href="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/searchDistrictResults/'.$dist_oc['latitude'].'/'.$dist_oc['longitude'].'/'.$dist_oc['district_id'].'/'.$dist_oc['confirmed_disease_id'].'">Click to view details</a>';
            }
            $marker['title'] = $dist_oc['name'];
            $marker['animation'] = 'DROP';
            $marker['icon'] = 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/'.$this->getDiseaseColorCode($dist_oc['confirmed_disease_id']).'.png';
            $this->googlemaps->add_marker($marker);
            }
        }
        $seven_days_ago  = mktime(0, 0, 0, date("m")  , date("d")-7, date("Y"));
        $today = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        $format = 'Y-m-d';

        $this->db->distinct();
        $this->db->select('ip')
                    ->from('tracker')
                    ->where('date >=',date($format,$seven_days_ago))
                    ->where('date <=',date($format,$today));

        $weekly_visitors = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('ip')
                ->from('tracker')
                ->where('date =',date('Y-m-d'));
        $visitors = $this->db->get()->result_array();
        
        $this->db->distinct();
        $this->db->select('ip')
                ->from('tracker');
        $all_visitors = $this->db->get()->result_array();

        $data['visitors'] = count($visitors);
        $data['weekly_visitors'] = count($weekly_visitors);
        $data['all_visitors'] = count($all_visitors);

        $data['map'] = $this->googlemaps->create_map();

        $data['title'] = "Zoonotic Data";
        $data['content_view'] = "zoonotic_data_report_v";
		$data['livestock_content_view'] = "Livestock_Testing";
        $data['banner_text'] = "Zoonotic and Neglected Tropical Diseases Data";
        $data['link'] = "home_controller";
        $data['quick_link'] = "zoonotic_data_report";

        $access = $this -> session -> userdata('user_indicator');
        if($access == ""){
            $data['res_view'] = "zoonotic_resources_v";
            $this->load->view("Livestock_Testing", $data);
        }else{
            $this->load->view("template", $data);
        }
    }




	function loading(){
		$this->load->view("load", $data);
		
		
		

	}












    }
}


?>

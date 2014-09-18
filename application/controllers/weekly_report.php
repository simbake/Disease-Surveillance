<?php 
class Weekly_Report extends MY_Controller {

    //required
    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->show_interface();
    }
    
    public function show_interface() {
        $data = array();
        $data['settings_view'] = "weekly_report_v";
        $data['districts'] = District::getAll();
        $data['provinces'] = Province::getAll();
        $this->base_params($data);
    }
    
    public function generate() {
    
        $bata_buffer = "";
        $year = $this->input->post('year_from');
        $epiweek = $this->input->post('epiweek_to');
        $province = $this->input->post('province');
        $district = $this->input->post('district');
        $display_type = $this->input->post('display_type');
        $weekending = Surveillance::getWeekEnding($year, $epiweek);
        $provinces = array();
        $districts = array();
        
        //Check if a province has been specified
        if ($province > 0) {
            //if so, retrieve it's details from the database
            $provinces = Province::getProvince($province);
        } else {
            //if not, retrieve all provinces
            $provinces = Province::getAll();
        }
        //Check if a district has been specified
        if ($district > 0) {
            //if so, retrieve it's details from the database
            $districts = District::getDistrict($district);
            //also, retrieve the province details for this district
            $provinces = Province::getProvince($districts[0]['Province']);
            
        } else {
            //if not, empty the array
            $districts = array();
        }
        
        //Start displaying the header of the table
        $bata_buffer .= " <table class='data-table'>
            <tr style='background: #F5D2AE;'>
                <th rowspan=2>Province</th>
                <th rowspan=2>District</th>
                <th rowspan=2>Reports Expected</th>
                <th rowspan=2>Reports Received</th>
                <th rowspan=2>%RR</th>";
        $diseases["reports"] = "reports";
        $diseases["submitted"] = "submitted";
        $diseases["percentage"] = "percentage";
        $disease_array = Disease::getAll();
        foreach ($disease_array as $disease) {
            if ($disease['Name'] == 'Malaria') {
                $diseases[$disease['id']] = $disease['Name'];
                $diseases["tested"] = "tested";
                $diseases["positive"] = "positive";
                $bata_buffer .= "<th rowspan=2>".$disease['Name']."</th>";
                $bata_buffer .= "<th  colspan=2 style='color:green;'>".$disease['Name']." Indicators</th>";
            } else {
                $diseases[$disease['id']] = $disease['Name'];
                $bata_buffer .= "<th rowspan=2>".$disease['Name']."</th>";
            }
        }
        
        //Finish Displaying the Header
        $bata_buffer .= " </tr>
            <tr style='background: #F5D2AE'>
                 <th >Tested</th><th >Positive</th>
            </tr>
		";
        //Start retrieving all the rows for the data
        foreach ($provinces as $province_object) {
            $bata_buffer .= "<tr class='even'><td style='font-weight:bold; font-size:14px'>".$province_object->Name."</td></tr>";
            $province_districts = array();
            //check if a district was specified
            if (count($districts) > 0) {
                $province_districts = $districts;
            } else {
                //Get all the districts for this province
                $province_districts = district::getProvinceDistrict($province_object->id);
            }
            //loop through all the districts to get their data
            foreach ($province_districts as $province_district) {
                $available_data = array();
                $surveillance_counter = 2;
                $bata_buffer .= "<tr class='even' style='background:#C4E8B7'><td></td><td>".$province_district['Name']."</td>";
                $surveillance_data = Surveillance::getWeeklySummaries($year, $epiweek, $province_district['id']);
                //Check if any surveillance data exists
                if (isset($surveillance_data[0])) {
                    $available_data['reports'] = $surveillance_data[0]['Expected'];
                    $available_data['submitted'] = $surveillance_data[0]['Submitted'];
                    //Calculate the reporting
                    $available_data['percentage'] = floor(($available_data['submitted'] / $available_data['reports']) * 100);
                    //Display these Parameters
                    $bata_buffer .= "<td>".$available_data['reports']."</td><td>".$available_data['submitted']."</td><td>".$available_data['percentage']."</td>";
                } else {
                    $bata_buffer .= "<td>DNR</td><td>DNR</td><td>0</td>";
                }
                
                //Check if there is any surveillance data
                if (isset($surveillance_data[0])) {
                    //Loop through all the surveillance data returned
                    foreach ($surveillance_data as $disease_data) {
                        $bata_buffer .= "<td>".$disease_data['Cases']."(".$disease_data['Deaths'].")</td>";
                        //Check if the disease is malaria and if so, get the lab data and display it
                        if ($disease_data['Disease'] == 1) {
                            //Get malaria data
                            $lab_weekly_data = Lab_Weekly::getWeeklyLabData($year, $epiweek, $province_district['id']);
                            //Check if any data exists
                            if (isset($lab_weekly_data)) {
                                $bata_buffer .= "<td>".$lab_weekly_data['Tested']."</td><td>".$lab_weekly_data['Positive']."</td>";
                            }
                            //if not, display NA
                            else {
                                $bata_buffer .= "<td>DNR</td><td>DNR</td>";
                            }
                        }
                    }
                }
                //If there's no data, Show NA
                else {
                    $total_diseases = count($disease_array);
                    $total_elements = $total_diseases + 1;
                    for ($x = 0; $x <= $total_elements; $x++) {
                        $bata_buffer .= "<td>DNR</td>";
                    }
                }
                //Marks the end of data for one district
                $bata_buffer .= "</tr>";
            }//End districts loop
        }//End provinces loop
        
        //Finish the table
        $bata_buffer .= "</table>";
        //Start section that shows cumulative data
        $bata_buffer .= "<table class='data-table'>
            <tr style='background: #F5D2AE;'>
                <th rowspan='2' colspan='5'>Cumulative Summaries</th>";
        //Loop through all the diseases to display their names
        foreach ($disease_array as $disease) {
            if ($disease['Name'] == 'Malaria') {
                $diseases[$disease['id']] = $disease['Name'];
                $diseases["tested"] = "tested";
                $diseases["positive"] = "positive";
                $bata_buffer .= "<th rowspan=2>".$disease['Name']."</th>";
                $bata_buffer .= "<th  colspan=2 style='color:green;'>".$disease['Name']." Indicators</th>";
            } else {
                $diseases[$disease['id']] = $disease['Name'];
                $bata_buffer .= "<th rowspan=2>".$disease['Name']."</th>";
            }
        }//end diseases loop
        $bata_buffer .= "</tr>
            <tr style='background: #F5D2AE'>
                <th >Tested</th><th >Positive</th>
            </tr>";
            
        //Get the malaria lab data summaries
        $lab_weekly_summary = Lab_Weekly::getWeeklyLabSummaries($year, $epiweek);
        //Start Displaying this week summary
        $bata_buffer .= "<tr class='even'><td rowspan='2' colspan='5'>Week ".$epiweek." Summary</td>";
        //Get the summary for the week. Disease cases vs. deaths
        $disease_deaths = array();
        foreach ($disease_array as $disease_object) {
            $disease_summaries = Surveillance::getWeeklyDiseaseSummaries($year, $epiweek, $disease_object['id']);
            $bata_buffer .= "<td>".$disease_summaries['Cases']."</td>";
            $disease_deaths[$disease_object['id']] = $disease_summaries['Deaths'];
            //check if the disease is Malaria. If so, display lab data
            if ($disease_object['Name'] == "Malaria") {
                $bata_buffer .= "<td rowspan=2>".$lab_weekly_summary['Tested']."</td>";
                $bata_buffer .= "<td rowspan=2>".$lab_weekly_summary['Positive']."</td>";
            }
        }
        //Finish the cases row
        $bata_buffer .= "</tr>";
        //Start the deaths row
        $bata_buffer .= "<tr>";
        //Loop through one more time to display the total number of deaths
        foreach ($disease_array as $disease_object) {
            $bata_buffer .= "<td>(".$disease_deaths[$disease_object['id']].")</td>";
        }
        //finish the deaths row
        $bata_buffer .= "</tr>";
        
        //Get the annual summary
        //Get the malaria lab data summaries
        $lab_weekly_summary = Lab_Weekly::getAnnualLabSummaries($year);
        //Start Displaying this week summary
        $bata_buffer .= "<tr class='even' style='background:#BB00FF'><td rowspan='2' colspan='5'>Years Cummulative Summary</td>";
        //Get the summary for the week. Disease cases vs. deaths
        $disease_deaths = array();
        foreach ($disease_array as $disease_object) {
            $disease_summaries = Surveillance::getAnnualDiseaseSummaries($year, $disease_object['id']);
            $bata_buffer .= "<td>".$disease_summaries['Cases']."</td>";
            $disease_deaths[$disease_object['id']] = $disease_summaries['Deaths'];
            //check if the disease is Malaria. If so, display lab data
            if ($disease_object['Name'] == "Malaria") {
                $bata_buffer .= "<td rowspan=2>".$lab_weekly_summary['Tested']."</td>";
                $bata_buffer .= "<td rowspan=2>".$lab_weekly_summary['Positive']."</td>";
            }
        }
        //Finish the cases row
        $bata_buffer .= "</tr>";
        //Start the deaths row
        $bata_buffer .= "<tr class='even' style='background:#BB00FF'>";
        //Loop through one more time to display the total number of deaths
        foreach ($disease_array as $disease_object) {
            $bata_buffer .= "<td>(".$disease_deaths[$disease_object['id']].")</td>";
        }
        //finish the deaths row
        $bata_buffer .= "</tr></table>";
        
        $this->generatePDF($year, $bata_buffer, $epiweek, $weekending, $display_type);

        
    }
    
    function generatePDF($year, $data, $epiweek, $weekending, $display_type) {
        if ($display_type == "Download Report PDF") {
            $html_title = "<img src='Images/coat_of_arms.png' style='position:absolute; width:160px; width:160px; top:0px; left:0px; margin-bottom:-100px;margin-right:-100px;'></img>";
        } else if ($display_type == "View Report") {
            $html_title = "<img src='".base_url()."Images/coat_of_arms.png' style='position:absolute; width:160px; width:160px; top:0px; left:0px; margin-bottom:-100px;margin-right:-100px;'></img>";
        }
        
        $html_title .= "<h2 style='text-align:center; text-decoration:underline;'>Republic of Kenya</h2>";
        $html_title .= "<h3 style='text-align:center; text-decoration:underline;'>Ministry of Public Health and Sanitation</h3>";
        $html_title .= "<h1 style='text-align:center; text-decoration:underline;'>WEEKLY EPIDEMIOLOGICAL BULLETIN</h1>";
        $html_title .= " <h3 style='padding:10px; text-decoration:underline; text-align:center; color:#3B68A8'>Week $epiweek ($year)  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Week Ending $weekending</h3> ";

        
        $html_footer = '<table><tr><td><strong><u>Key</u></strong></td></tr><tr><td><strong>DNR</strong></td><td>District did not report for this disease</td><td><strong>MM</strong></td><td>Meningococcal Meningitis</td></tr>
    <tr><td><strong> AFP </strong></td> <td> Acute Flaccid Paralysis </td> <td><strong> VHF </strong></td> <td> Viral Hemorrhagic Fever </td></tr>
    <tr><td><strong> NNT </strong></td> <td> Neonatal Tetanus </td> <td><strong> NEP </strong></td> <td> North Eastern Province </td></tr>
    <tr><td><strong> HF </strong></td> <td>Health Facilities </td> <td><strong> % RR </strong></td> <td> Health Facility reporting rate for this week </td></tr>
    <br/>
    <tr><td><strong>Timely reports</strong></td> <td>Reports received before Wednesday</td></tr>
    <tr><td><strong>Completeness (Intra-district)</strong></td>  <td>Proportion of health facilities reporting in a district/province</td></tr>
    <tr><td><strong>Timeliness</strong></td> <td>Proportion of district in a province/country reporting on time</td></tr>
    <tr><td><strong>Weighted Aggregate Score</strong></td> <td>Composite Surveillance Score (3 for Timeliness, 2 for Completeness,1 for Complete reports)</td></tr></table>';

    
        if ($display_type == "Download Report PDF") {
            $this->load->library('mpdf');
            $this->mpdf = new mPDF('', 'A4-L', 0, '', 15, 15, 16, 16, 9, 9, '');
            $this->mpdf->SetTitle('WEEKLY EPIDEMIOLOGICAL BULLETIN');
            $this->mpdf->WriteHTML($html_title);
            $this->mpdf->simpleTables = true;
            $this->mpdf->WriteHTML('<br/>');
            $this->mpdf->WriteHTML('<br/>');
            $this->mpdf->WriteHTML('<br/>');
            $this->mpdf->WriteHTML($data);
            $this->mpdf->WriteHTML($html_footer);
			$report_name = "Week $epiweek Surveillance Report.pdf";
            $this->mpdf->Output($report_name,'D');
            
        } else if ($display_type == "View Report") {
            echo $html_title.$data.$html_footer;
        }
    }
    
    function ob_file_callback($buffer) {
        global $ob_file;
        fwrite($ob_file, $buffer);
        return $buffer;
    }
    
    public function base_params($data) {
        $data['styles'] = array("jquery-ui.css");
        $data['scripts'] = array("jquery-ui.js");
        $data['quick_link'] = "weekly_report";
        $data['title'] = "System Reports";
        $data['report_view'] = "weekly_report_v";
        $data['content_view'] = "reports_v";
        $data['banner_text'] = "Weekly Report";
        $data['link'] = "reports_management";
        
        $this->load->view('template', $data);
    }
    
}

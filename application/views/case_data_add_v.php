<?php
if (!isset($existing_data)) {
	$existing_data = false;
}
?>
<style>
	.disease_input {
		width: 50px;
		height: 20px;
		margin: 0 auto !important;
	}
	#prediction {
		width: 200px;
		margin: 0 auto;
	}
	#data_exists_error {
		border: 1px solid red;
		width: 500px;
		display: none;
		margin: 5px auto;
		padding: 10px;
	}


</style>
<script type="text/javascript">
	$(function() {
//$("#entry-form").validationEngine();
//$('#entry-form').validationEngine();

$("#confirm_variables").click(function() {
$("#epiweek").attr("value", $("#predicted_epiweek").text());
$("#weekending").attr("value", $("#predicted_weekending").text());
$("#reporting_year").attr("value", $("#predicted_year").attr("value"));
$('#prediction').slideUp('slow');
});
$("#facility").change(function() {
if($("#epiweek").attr("value") > 0) {
checkFacilityData();
}
});

$("#dob").datepicker({ 
altFormat : "DD,d MM, yy",
changeYear : true
});  
$("#onset_date").datepicker({ 
altFormat : "DD,d MM, yy",
changeYear : true
});  
$("#date_seen").datepicker({ 
altFormat : "DD,d MM, yy",
changeYear : true
}); 
$("#notification_date").datepicker({ 
altFormat : "DD,d MM, yy",
changeYear : true
});
$("#admission_date").datepicker({ 
altFormat : "DD,d MM, yy",
changeYear : true
});     
});


</script>
<div class="view_content">
	<?php
	$disease_surveillance_data = array();
	if(isset($surveillance_data)){
	//First check if this data is complete. If not, show the user an error page 
	if(!isset($surveillance_data[($surveillance_data[0]->Total_Diseases)-1])){
		$corrupt_link = base_url()."weekly_data_management/corrupt_data/".$surveillance_data[0]->Epiweek."/".$surveillance_data[0]->Reporting_Year."/".$surveillance_data[0]->Facility;
		redirect($corrupt_link);
	}
	$week_data = $surveillance_data[0];
	$epiweek = $week_data->Epiweek;
	$editing_district_id = $week_data->District;
	$returned_facility = $week_data->Facility;
	$week_ending = $week_data->Week_Ending;
	$reporting_year = $week_data->Reporting_Year;
	$reported_by = $week_data->Reported_By;
	$designation = $week_data->Designation;
	foreach($surveillance_data as $data){
	$disease_surveillance_data[$data->Disease]['lcase'] = $data->Lcase;
	$disease_surveillance_data[$data->Disease]['ldeath'] = $data->Ldeath;
	$disease_surveillance_data[$data->Disease]['gcase'] = $data->Gcase;
	$disease_surveillance_data[$data->Disease]['gdeath'] = $data->Gdeath;
	$disease_surveillance_data[$data->Disease]['surveillance_id'] = $data->id;
	}
	}
	else{
	$editing_district_id = "";
	$epiweek = "";
	$submitted  = "";
	$expected = "";
	$returned_facility = "";
	$week_ending = "";
	$reporting_year = "";
	$reported_by = "";
	$designation = "";
	foreach($diseases as $disease){
	$disease_surveillance_data[$disease->id]['lcase'] = '';
	$disease_surveillance_data[$disease->id]['ldeath'] = '';
	$disease_surveillance_data[$disease->id]['gcase'] = '';
	$disease_surveillance_data[$disease->id]['gdeath'] = '';
	$disease_surveillance_data[$disease->id]['surveillance_id'] = '';
	}
	}
	if(isset($lab_data)){
	$week_data = $lab_data[0];
	$lab_id = $week_data->id;
	$malaria_below_5 = $week_data->Malaria_Below_5;
	$malaria_above_5  = $week_data->Malaria_Above_5;
	$positive_below_5 = $week_data->Positive_Below_5;
	$positive_above_5 = $week_data->Positive_Above_5;
	$positive_above_5 = $week_data->Positive_Above_5;
	$remarks = $week_data->Remarks;
	}
	else{
	$lab_id = "";
	$malaria_below_5 = "";
	$malaria_above_5  = "";
	$positive_below_5 = "";
	$positive_above_5 = "";
	$remarks = "";
	}
	$attributes = array('id' => 'entry-form', 'class'=>'stdform');
	echo form_open('case_data_management/save', $attributes);
	echo validation_errors('<p class="error">', '</p>');
	?>

	<table  style="margin: 5px auto; border: 2px solid #EEEEEE;">
		<tr>
			<td><b>Reporting Date:</b></td><td>
			<input readonly="" type="text" name="week_ending" id="weekending" class="validate[required]" value="<?php echo $week_ending;?>"/>
			</td>
			<td><b>Reporting Officer: </b></td>
			<td>
			<input type="text" name="reporting_officer" id="reporting_officer" class="validate[required]" value="<?php echo $epiweek;?>"/>
			<input type="hidden" name="reporting_year" id="reporting_year" value="<?php echo $reporting_year;?>"/>
			<input type="hidden" name="lab_id" id="lab_id" value="<?php echo $lab_id;?>"/>
			<input type="hidden" name="editing_district_id" id="editing_district_id" value="<?php echo $editing_district_id;?>"/>
			</td>
		</tr>
			<tr>
			<td><b>Facility: </b></td><td>
			<select name="facility" id="facility"   class="validate[required]">
				<option value="0">Select Facility</option>
				<?php
				foreach ($facilities as $facility) {
					if ($facility['facilitycode'] == $returned_facility) {
						echo '<option selected value="' . $facility['facilitycode'] . '">' . $facility['name'] . '</option>';
					} else {
						echo '<option value="' . $facility['facilitycode'] . '">' . $facility['name'] . '</option>';
					}
				}//end foreach
				?>
			</select></td> 
						<td><b>Disease: </b></td><td>
			<select name="disease" id="disease"   class="validate[required]">
				<option value="0">Select Disease</option>
				<?php
				foreach ($case_diseases as $disease_object) {
					if ($disease_object['id'] == $returned_facility) {
						echo '<option selected value="' . $disease_object['id'] . '">' . $disease_object['Name'] . '</option>';
					} else {
						echo '<option value="' . $disease_object['id'] . '">' . $disease_object['Name'] . '</option>';
					}
				}//end foreach
				?>
			</select></td> 
		</tr>
	</table>
	<form class="stdform" action="" method="post">
		<div class="one_half">
                    	<div class="widgetbox uncollapsible">
                            <div class="title"><h2 class="general"><span>Identification</span></h2></div>
                            <div class="widgetcontent stdform">
                            	
                        <p>
                        	<label>Name of Patient</label>
                            <span class="field"><input type="text" name="patient_name" class="longinput"></span>
                            <small class="desc">Small description of this field.</small>
                        </p>
               		  <p>
                        	<label>Sex</label>
                            <span class="formwrapper">
                            	<input type="radio" name="sex" value="1"> Female &nbsp;&nbsp;
                            	<input type="radio" name="sex" value="2"> Male
                            </span>
                        </p>
                        <p>
                        	<label>Date of Birth</label>
                            <span class="field"><input type="text" name="dob" id="dob" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Residence</label>
                            <span class="formwrapper">
                            	<input type="radio" name="residence" value="1"> Urban &nbsp;&nbsp;
                            	<input type="radio" name="residence" value="2"> Rural
                            </span>
                        </p>
                        <div class="title"><h2 class="general"><span>Tracer Information</span></h2></div>
                        <p>
                        	<label>Parent/Guardian Name</label>
                            <span class="field"><input type="text" name="guardian_name" class="longinput"></span> 
                        </p>
                       <p>
                        	<label>Location Details</label>
                         	 <span class="field"><textarea cols="80" rows="5" class="longinput"></textarea></span>
                         	   <small class="desc">Include all avialable details regarding this patients location including; the village, nearby landmarks, estate, house number, e.t.c.</small>
                        </p>
                        <p>
                        	<label>Tel. no. of immediate contact</label>
                            <span class="field"><input type="text" name="contact_number" id="contact_number" class="smallinput"></span>
                        </p>
                            </div><!--widgetcontent-->
                        </div><!--widgetbox-->
                                            	<div class="widgetbox uncollapsible">
                            <div class="title"><h2 class="general"><span>For Accute Flaccid Paralysis</span></h2></div>
                            <div class="widgetcontent stdform">
                           <p>
                        	<label>Date of onset of weakness</label>
                            <span class="field"><input type="text" name="afp_onset" id="afp_onset" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Signs and symptoms</label>
                            <span class="formwrapper">
                            	<span class="checkbox"><input type="checkbox" name="afp_symptoms" value="0"></span> Fever at onset of paralysis </br>
                            	<span class="checkbox"><input type="checkbox" name="afp_symptoms" value="1"></span> Sudden onset of paralysis </br>
                                <span class="checkbox"><input type="checkbox" name="afp_symptoms" value="2"></span> Paralysis progressed &lt; 3 days </br>
                                <span class="checkbox"><input type="checkbox" name="afp_symptoms" value="3"></span> Flaccid (Floppy)
                            </span>
                        </p>
                        <p>
                        	<label>Site(s) of paralysis</label>
                            <span class="formwrapper">
                            	<span class="checkbox"><input type="checkbox" name="afp_site" value="0"></span> Left Leg &nbsp;&nbsp;
                            	<span class="checkbox"><input type="checkbox" name="afp_site" value="1"></span> Right Leg </br>
                                <span class="checkbox"><input type="checkbox" name="afp_site" value="2"></span> Left Arm &nbsp;&nbsp;
                                <span class="checkbox"><input type="checkbox" name="afp_site" value="3"></span> Right Arm
                            </span>
                        </p>
                        <p>
                        	<label>Name &amp; Tel. of Clinician</label>
                            <span class="field"><input type="text" name="afp_clinician" class="longinput"></span>
                            <small class="desc">Enter the name and telephone number separated by a hyphen (-).</small>
                        </p>
               		<small class="desc"><b>NB: Follow-up Examination</b> MUST be done after 60 days from onset of paralysis using  the 60 days follow up form </small>
</div>
</div>
                    	<div class="widgetbox uncollapsible">
                            <div class="title"><h2 class="general"><span>For Measles Cases Only</span></h2></div>
                            <div class="widgetcontent stdform">
                        <p>
                        	<label>Presence of Fever</label>
                            <span class="formwrapper">
                            	<input type="radio" name="measles_fever" value="1"> Yes 
                            	<input type="radio" name="measles_fever" value="2"> No 
                            </span>
                        </p>
                        <p>
                        	<label>Date of onset of rash</label>
                            <span class="field"><input type="text" name="measles_onset" id="measles_onset" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Type of rash</label>
                            <span class="formwrapper">
                            	<span class="checkbox"><input type="checkbox" name="afp_symptoms" value="0"></span> Maculopapular
                            	<span class="checkbox"><input type="checkbox" name="afp_symptoms" value="1"></span> Other 
                            </span>
                        </p>
                        <p>
                        	<label>Contact investigation</label>
                            <span class="formwrapper">
                            	<input type="radio" name="measles_investigation" value="1"> Yes 
                            	<input type="radio" name="measles_investigation" value="2"> No 
                            </span>
                            <small class="desc">Was home of patient visited for contact investigation?</small>
                        </p>
                        <p>
                        	<label>Date of investigation</label>
                            <span class="field"><input type="text" name="measles_investigation_date" id="measles_investigation_date" class="smallinput"></span>
                        </p>
                    
</div>
</div>
                    	<div class="widgetbox uncollapsible">
                            <div class="title"><h2 class="general"><span>Laboratory Information</span></h2></div>
                            <div class="widgetcontent stdform">
                            	<small class="desc">Specimen collection (To be completed by the health facility) If lab specimen was collected, complete the following information and send a copy of this form to the lab with the specimen. For AFP don’t collect specimen if onset of paralysis is more than 60 days old</small>
                        <p>
                        	<label>Was specimen collected?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="lab_specimen" value="1"> Yes 
                            	<input type="radio" name="lab_specimen" value="2"> No 
                            </span>
                        </p>
                        <p>
                        	<label>If no, why?</label>
                            <span class="field"><input type="text" name="lab_specimen_reason" class="largeinput"></span>
                        </p>
                         <p>
                        	<label>Date of collection</label>
                            <span class="field"><input type="text" name="lab_collection_date" id="lab_collection_date" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Specimen type?</label>
                            <span class="formwrapper">
                            	<input type="checkbox" name="lab_specimen_type" value="1"> Stool &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="2"> Blood &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="3"> CSF</br>
                            	<input type="checkbox" name="lab_specimen_type" value="4"> OPS &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="5"> NS &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="6"> Animal Tissue
                            </span>
                        </p>
                        <p>
                        	<label>Other specimen type?</label>
                            <span class="field"><input type="text" name="lab_specimen_other_type" class="largeinput"></span>
                        </p>
                        <p>
                        	<label>Date sent to lab</label>
                            <span class="field"><input type="text" name="lab_date_sent" id="lab_date_sent" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Name of the lab</label>
                            <span class="field"><input type="text" name="lab_name" class="smallinput"></span>
                        </p>
                       <p>
                        	<label>Preliminary lab results?</label>
                         	 <span class="field"><textarea cols="80" rows="5" class="longinput" name="lab_results"></textarea></span>
                        </p>
                       
                    
</div>
</div>
</div>
		<div class="one_half last">
                    	<div class="widgetbox uncollapsible">
                            <div class="title"><h2 class="general"><span>Clinical Information</span></h2></div>
                            <div class="widgetcontent stdform">
                            	
      					  <p>
                        	<label>Date of Onset</label>
                            <span class="field"><input type="text" name="onset_date" id="onset_date" class="smallinput"></span>
                        </p>
                         <p>
                        	<label>Date first seen</label>
                            <span class="field"><input type="text" name="date_seen" id="date_seen" class="smallinput"></span>
                        </p>
                       <p>
                        	<label>Date notified</label>
                            <span class="field"><input type="text" name="notification_date" id="notification_date" class="smallinput"></span>
                            <small class="desc">This is the date the health facility notified the district level</small>
                        </p>
               		  <p>
                        	<label>Hospitalized?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="hospitalized" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="hospitalized" value="2"> No
                            </span>
                        </p>
                        <p>
                        	<label>Admission date</label>
                            <span class="field"><input type="text" name="admission_date" id="admission_date" class="smallinput"></span> 
                        </p>
                        <p>
                        	<label>IP/OP Number</label>
                            <span class="field"><input type="text" name="ipop"  class="smallinput"></span>
                        </p> 
                        <div class="title"><h2 class="general"><span>Vaccination History</span></h2></div>
                         <small class="desc">Vaccination history for disease under investigation; Measles, polio (exclude birth dose of OPV), NNT (TT in mother), Yellow fever, Meningitis and suspected Avian Influenza</small>
                         <p>
                        	<label>Vaccinated?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="vaccinated" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="vaccinated" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="vaccinated" value="3"> Unknown
                            </span>
                             <small class="desc">Was patient vaccinated against illness? (Including campaign)</small>
                            
                        </p>
                       <p>
                        	<label>No. of doses</label>
                         	 <span class="field"><input type="text" name="dose_number" id="dose_number" class="smallinput"></span> 
                         	   <small class="desc">If yes, number of doses administered</small>
                        </p>
                        <p>
                        	<label>Vaccinated in last 2 months?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="vaccinated_previous" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="vaccinated_previous" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="vaccinated_previous" value="3"> Unknown
                            </span>
                            
                        </p>
                       <p>
                        	<label>Vaccine</label>
                         	 <span class="field"><input type="text" name="vaccine_previous" id="vaccine_previous" class="smallinput"></span> 
                         	   <small class="desc">If yes, enter the vaccine administered</small>
                        </p>
                         <p>
                        	<label>Status of Patient</label>
                            <span class="formwrapper">
                            	<input type="radio" name="patient_status" value="1"> Still Hospitalized &nbsp;&nbsp;
                            	<input type="radio" name="patient_status" value="2"> Discharged &nbsp;&nbsp;
                            	<input type="radio" name="patient_status" value="3"> Dead
                            </span>
                            
                        </p>
                            </div><!--widgetcontent-->
                        </div><!--widgetbox-->
                                            	<div class="widgetbox uncollapsible">
                            <div class="title"><h2 class="general"><span>For Neonatal Tetanus Only</span></h2></div>
                           <h3>Delivery Practices</h3>
                            <div class="widgetcontent stdform">
                        <p>
                        	<label>Where was the baby delivered?</label>
                            <span class="formwrapper">
                            	<span class="checkbox"><input type="checkbox" name="nnt_delivered" value="0"></span> Health facility </br>
                            	<span class="checkbox"><input type="checkbox" name="nnt_delivered" value="1"></span> Home by trained health worker </br>
                                <span class="checkbox"><input type="checkbox" name="nnt_delivered" value="2"></span> Home by trained attendant </br>
                                <span class="checkbox"><input type="checkbox" name="nnt_delivered" value="3"></span> Unknown
                            </span>
                        </p>
                        <p>
                        	<label>Sterile blade?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_blade" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_blade" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_blade" value="3"> Unknown
                            </span>
                            <small class="desc">Was the cord cut with a sterile blade?</small>
                        </p>
                        <p>
                        	<label>How was the cord sump treated or dressed?</label>
                         	 <span class="field"><textarea cols="80" rows="5" class="longinput" name="nnt_sump"></textarea></span>
                        </p>
                        <h3>Baby's Symptoms</h3>
                        <p>
                        	<label>Age of baby</label>
                            <span class="field"><input type="text" name="nnt_age" class="smallinput"></span>
                            <small class="desc">How old was the baby (in days) when this illness began?</small>
                        </p>
                        <p>
                        	<label>Proper suckling?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_suckling" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_suckling" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_suckling" value="3"> Unknown
                            </span>
                            <small class="desc">At birth, did the baby suck normally?</small>
                        </p>
                       <p>
                        	<label>Unable to suck?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_suckling" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_suckling" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_suckling" value="3"> Unknown
                            </span>
                            <small class="desc">At birth, did the baby suck normally?</small>
                        </p>
                       <p>
                        	<label>Convulsions, Stiffness or Fits?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_convulsion" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_convulsions" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_convulsions" value="3"> Unknown
                            </span>
                            <small class="desc">Did the baby have convulsions, stiffness or fits?</small>
                        </p>
                         <p>
                        	<label>Neonatal Tetanus?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_confirmation" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_confirmation" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_confirmation" value="3"> Unknown
                            </span>
                            <small class="desc">Was the case confirmed as Neonatal Tetanus? (If yes to the last 3 questions)</small>
                        </p>
                        <h3>Treatment</h3>
                       <p>
                        	<label>Was baby treated at facility?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_treated" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_treated" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_treated" value="3"> Unknown
                            </span>
                        </p>
                        <p>
                        	<label>Is the mother alive?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_mother" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_mother" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_mother" value="3"> Unknown
                            </span>
                            <small class="desc">If not, complete case investigation form for maternal deaths</small>
                        </p>
                        <h3>Case Response</h3>
                         <small class="desc">Sensitize birth attendants and community leaders on safe delivery practices and cord care. Provide booster TT doses to mother of NNT case and women of child-bearing age in community</small>
                   	 <p>
                        	<label>Case response for the mother?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_case_response" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_case_response" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_case_response" value="3"> Unknown
                            </span>
                             <small class="desc">Did case response for the mother take place</small>
                        </p>
                         <p>
                        	<label>Case response in community?</label>
                            <span class="formwrapper">
                            	<input type="radio" name="nnt_community_response" value="1"> Yes &nbsp;&nbsp;
                            	<input type="radio" name="nnt_community_response" value="2"> No &nbsp;&nbsp;
                            	<input type="radio" name="nnt_community_response" value="3"> Unknown
                            </span>
                             <small class="desc">Did case response take place in her community?</small>
                        </p>
</div>
</div>
</div>
 
        	
         
                   <div class="two_third" id="laboratory_section">
                    	<div class="widgetbox uncollapsible">
                            <div class="title"><h2 class="general"><span>Final Lab Results</span></h2></div>
                            <div class="widgetcontent stdform">
                           
                       <p>
                        	<label>Date sample received</label>
                            <span class="field"><input type="text" name="sample_received_date" id="sample_received_date" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Final Results</label>
                         	 <span class="field"><textarea cols="80" rows="5" class="longinput" name="lab_results"></textarea></span>
                        </p>
                        <p>
                        	<label>If no, why?</label>
                            <span class="field"><input type="text" name="lab_specimen_reason" class="largeinput"></span>
                        </p>
                         <p>
                        	<label>Date of collection</label>
                            <span class="field"><input type="text" name="lab_collection_date" id="lab_collection_date" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Specimen type?</label>
                            <span class="formwrapper">
                            	<input type="checkbox" name="lab_specimen_type" value="1"> Stool &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="2"> Blood &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="3"> CSF</br>
                            	<input type="checkbox" name="lab_specimen_type" value="4"> OPS &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="5"> NS &nbsp;&nbsp;
                            	<input type="checkbox" name="lab_specimen_type" value="6"> Animal Tissue
                            </span>
                        </p>
                        <p>
                        	<label>Other specimen type?</label>
                            <span class="field"><input type="text" name="lab_specimen_other_type" class="largeinput"></span>
                        </p>
                        <p>
                        	<label>Date sent to lab</label>
                            <span class="field"><input type="text" name="lab_date_sent" id="lab_date_sent" class="smallinput"></span>
                        </p>
                        <p>
                        	<label>Name of the lab</label>
                            <span class="field"><input type="text" name="lab_name" class="smallinput"></span>
                        </p>
                       <p>
                        	<label>Preliminary lab results?</label>
                         	 <span class="field"><textarea cols="80" rows="5" class="longinput" name="lab_results"></textarea></span>
                        </p>
                       
                    
</div>
</div>
</div> 
                        
                        <br clear="all"><br>
                        
                        <p class="stdformbutton">
                        	<button class="submit radius2">Submit Button</button>
                            <input type="reset" class="reset radius2" value="Reset Button">
                        </p>
                        
                        
                    </form>
                  
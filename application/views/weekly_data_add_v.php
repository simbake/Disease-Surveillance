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
$('#submit_form').click(function(e){
	 e.preventDefault();
	  var cases_pass = true;
	  var deaths_pass = true;
	  var cases_counter = 0;
	  var response_counter = 0;
	//Loop through all the diseases that should trigger alerts both for cases and deaths reported
	$('.alert_cases').each(function() { 
		cases_counter++;
		response_counter++;
		//get the disease for this input box
		var disease = $(this).attr("disease");
		//Get the number reported
		var reported = $(this).attr("value");
		//Get the threshold
		var alert_level = $(this).attr("alert_cases");
		console.log(reported+" and "+alert_level);
		if(parseInt(reported)>=parseInt(alert_level)){
			var question = "Are you sure you want to report "+reported+" "+disease+"?";
			var r=confirm(question);
			if(r == false){
				response_counter--;
			} 
		}
	});
	if(parseInt(cases_counter) != parseInt(response_counter)){
		cases_pass = false;
	} 
	var deaths_counter = 0;
	var deaths_response_counter = 0;
	$('.alert_deaths').each(function() {
		deaths_counter++;
		deaths_response_counter++;
		//get the disease for this input box
		var disease = $(this).attr("disease");
		//Get the number reported
		var reported = $(this).attr("value");
		//Get the threshold
		var alert_level = $(this).attr("alert_deaths");
		console.log(reported+" and "+alert_level);
		if(parseInt(reported)>=parseInt(alert_level)){
			
			var question = "Are you sure you want to report "+reported+" "+disease+"?";
			var r=confirm(question);
			if(r == false){
				deaths_response_counter--;
			} 
		}
	}); 
	if(parseInt(deaths_counter) != parseInt(deaths_response_counter)){
		deaths_pass = false;
	}  
	if(cases_pass == false || deaths_pass == false){
		
		return false;
	}
	else{
		if($('#entry-form').validationEngine("validate")){
			$('#entry-form').submit();
		}
		
	}
	
});
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

$("#weekending").datepicker({
altField : "#epiweek",
altFormat : "DD,d MM, yy",
firstDay : 1,
maxDate: "+7",
changeYear : true,
onClose : function(date, inst) {
//Create a new date object from the date selected
var new_date = new Date(date);
//Retrieve the week number and reporting year for this date object
var week_data = getWeek(new_date);
$("#epiweek").attr("value", week_data[0]);
$("#reporting_year").attr("value", week_data[1]);
if($("#facility").attr("value") > 0) {
checkFacilityData();
}
},
beforeShowDay : function(date) {
//Disable all days except sundays
var day = date.getDay();
return [(day != 1 && day != 2 && day != 3 && day != 4 && day != 5 && day != 6)];
}
});
$(".zero_reporting").change(function() {

zeroReporting(this.id);
});
$("#check_all").change(function(){
	$(".zero_reporting").each(function(){
		$(this).change();
		$(this).attr("checked",true);
		//zeroReporting($(this).attr("id"));
	})
});
});
/*
* Function that checks if facility data exists
*/
function checkFacilityData() {
$("#data_exists_error").slideUp("slow");
var epiweek = $("#epiweek").attr("value");
var reporting_year = $("#reporting_year").attr("value");
var facility = $("#facility").attr("value");
var url =  '<?php echo base_url()?>'+"weekly_data_management/check_facility_data/" + epiweek + "/" + reporting_year + "/" + facility;
$.get(url, function(data) {
if(data == "yes") {
var edit_url = '<?php echo base_url()?>'+"weekly_data_management/edit_weekly_data/" + epiweek + "/" + reporting_year + "/" + facility;
	var error_html = "<p>Data for this facility already exists! <a href='" + edit_url + "' class='link'>Click here</a> to edit the data instead!</p>";
	$("#data_exists_error").html(error_html);
	$("#data_exists_error").css("border-color", "red");
	$("#data_exists_error").slideDown("slow");
	} else {
		$("#data_exists_error").html("<p>You can enter surveillance data for this facility</p>");
		$("#data_exists_error").css("border-color", "green");
		$("#data_exists_error").slideDown("slow");
	}
	});
	}

	/*
	 * Function that calculates the epiweek of a given date
	 */
	function getWeek(date) {
		var reporting_year = "";
		var checkDate = new Date(date.getTime());
		//Retrieve the reporting year from this date
		reporting_year = checkDate.getFullYear();
		// Find Sunday of this week starting on Monday
		checkDate.setDate(checkDate.getDate() + 7 - (checkDate.getDay() || 7));
		//get the time for that sunday
		var time = checkDate.getTime();
		//Compare this with January 1st of that year
		//set the month to january
		checkDate.setMonth(0);
		//set the date to 1st january
		checkDate.setDate(1);
		//Calculate the modulous of the difference to determine how many days in that year fall in the first week
		var week_days_in_year = (((time - checkDate) / 86400000) % 7) + 1;
		//Calculate the week number
		var week_number = Math.floor(Math.round((time - checkDate) / 86400000) / 7);
		//If the number of days falling in the first week are greater than 4, increment the weeknumber by 1 since these days will be considered as the first week of the year
		if(week_days_in_year >= 4) {
			week_number += 1;
		}
		//If the week number is '0' assign the week number of the last week of the previous year
		if(week_number == 0) {
			//Set the year to the previous year
			checkDate.setYear(checkDate.getFullYear() - 1);
			//Retrieve the reporting year from this date
			reporting_year = checkDate.getFullYear();
			//set month as december
			checkDate.setMonth(11);
			//set date as 24th
			checkDate.setDate(24);
			//Call this function again to retrieve the week number of the 2nd last week of the previous year. 24th December is set as the date since it is guaranteed to be in this last week.
			var last_week = arguments.callee(checkDate);
			//Increment this week number to get the last week of that year
			week_number = last_week[0] += 1;
		}
		var return_array = new Array(week_number, reporting_year);
		return return_array;
	}

	function zeroReporting(id) {
		var temp = id.split("_");
		var disease = temp[1];
		var lcase = "lcase_" + disease;
		$("#" + lcase).attr("value", "0");
		var ldeath = "ldeath_" + disease;
		$("#" + ldeath).attr("value", "0");
		var gcase = "gcase_" + disease;
		$("#" + gcase).attr("value", "0");
		var gdeath = "gdeath_" + disease;
		$("#" + gdeath).attr("value", "0");
	}
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
	$attributes = array('id' => 'entry-form');
	echo form_open('weekly_data_management/save', $attributes);
	echo validation_errors('<p class="error">', '</p>');
	?>

	<table  style="margin: 5px auto; border: 2px solid #EEEEEE;">
		<tr>
			<td><b>Week Ending:</b></td><td>
			<input readonly="" type="text" name="week_ending" id="weekending" class="validate[required]" value="<?php echo $week_ending;?>"/>
			</td>
			<td><b>Epiweek: </b></td>
			<td>
			<input type="text" name="epiweek" id="epiweek" readonly="" class="validate[required,custom[onlyNumberSp]]" value="<?php echo $epiweek;?>"/>
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
		</tr>
		
	</table>
	<div id="data_exists_error" <?php
		if ($existing_data == true) { echo "style='display:block'";
		}
	?>>
		<?php
		if ($existing_data == true) {
			$edit_link = base_url()."weekly_data_management/edit_weekly_data/".$duplicate_epiweek."/".$duplicate_reporting_year."/". $duplicate_facility->facilitycode;
			echo "<p>Epiweek " . $duplicate_epiweek . " data for <b>" . $duplicate_facility-> name . "</b> already exists for " . $duplicate_reporting_year . ".</p><p><a class='link' style='margin:0' href='$edit_link'>Click Here</a> to edit the existing data or select different parameters.</p>";
		}
		?>
	</div>
	<table class="data-table" style="margin: 0 auto;">
		<tr>
			<th rowspan="2">Disease</th>
			<th colspan="2">&le;5 Years</th>
			<th colspan="2">&ge;5 Years</th>
			<th rowspan="2">Zero Reporting</br><input type="checkbox" id ="check_all"></th>
		</tr>
		<tr>
			<th>Cases</th>
			<th>Deaths</th>
			<th>Cases</th>
			<th>Deaths</th>
		</tr>
		<?php
$counter = 1;
foreach ($diseases as $disease) {
$rem = $counter %2;
$class = "odd";
if($rem == 0){
$class = "even";
}
		?>
		<tr class="<?php echo $class;?>">
			
			<td><?php echo $disease -> Name;?></td>
			<?php if($disease->Has_Lcase == "1"){?>
			<td style="background-color: #C4E8B7">
			<input <?php echo "disease='".$disease -> Name." Cases Under 5'"; if($disease->Alert_Cases>0){echo "alert_cases='".$disease->Alert_Cases."'";}?> type="text" name="lcase[]" id="<?php echo "lcase_" . $disease -> id;?>"   class="<?php if($disease->Alert_Cases>0){echo "alert_cases ";}?>disease_input validate[required,custom[integer,min[0]]]" value="<?php echo $disease_surveillance_data[$disease -> id]['lcase'];?>"/>
			</td> 
			<?php }
			else{?>
				<td style="background-color: #C4E8B7">N/A <input name="lcase[]" type="hidden" /></td> 
			<?php }
			if($disease->Has_Ldeath == "1"){
			?>
			<td style="background-color: #C4E8B7">
			<input <?php echo "disease='".$disease -> Name." Deaths Under 5'";  if($disease->Alert_Deaths>0){echo "alert_deaths='".$disease->Alert_Deaths."'";}?> type="text" name="ldeath[]" id="<?php echo "ldeath_" . $disease -> id;?>"   class="<?php if($disease->Alert_Deaths>0){echo "alert_deaths ";}?>disease_input validate[required,custom[integer,min[0]]]" value="<?php echo $disease_surveillance_data[$disease -> id]['ldeath'];?>"/>
			</td>
			<?php }
			else{?>
				<td style="background-color: #C4E8B7">N/A <input name="ldeath[]" type="hidden" /></td> 
			<?php }
			if($disease->Has_Gcase == "1"){
			?>
			<td style="background-color: #C4E8B7">
			<input <?php echo "disease='".$disease -> Name." Cases Above 5'";  if($disease->Alert_Cases>0){echo "alert_cases='".$disease->Alert_Cases."'";}?> type="text" name="gcase[]" id="<?php echo "gcase_" . $disease -> id;?>"   class="<?php if($disease->Alert_Cases>0){echo "alert_cases ";}?>disease_input validate[required,custom[integer,min[0]]]" value="<?php echo $disease_surveillance_data[$disease -> id]['gcase'];?>"/>
			</td>
			<?php }
			else{?>
				<td style="background-color: #C4E8B7">N/A<input name="gcase[]" type="hidden" /></td> 
			<?php }
			if($disease->Has_Gdeath == "1"){
			?> 
			<td style="background-color: #C4E8B7">
			<input <?php echo "disease='".$disease -> Name." Deaths Above 5'";  if($disease->Alert_Deaths>0){echo "alert_deaths='".$disease->Alert_Deaths."'";}?> type="text" name="gdeath[]" id="<?php echo "gdeath_" . $disease -> id;?>"   class="<?php if($disease->Alert_Deaths>0){echo "alert_deaths ";}?>disease_input validate[required,custom[integer,min[0]]]" value="<?php echo $disease_surveillance_data[$disease -> id]['gdeath'];?>"/>
			</td>
			<?php }
			else{?>
			<td style="background-color: #C4E8B7">N/A<input name="gdeath[]" type="hidden" /></td> 
			<?php }
			?> 
			<td>
			<input type="checkbox" id ="<?php echo "check_" . $disease -> id;?>" class="zero_reporting">
			</td>
			<input type="hidden" name="surveillance_ids[]" value="<?php echo $disease_surveillance_data[$disease->id]['surveillance_id']?>" />
		</tr>
		<?php
		$counter ++;
		}//end foreach
		?>
	</table>
	<table class="data-table" style="margin: 10px auto;">
		<tr class="odd">
			<th colspan="1">Laboratory Weekly Malaria Confirmation</th>
			<th colspan="2">&le;5 years</th>
			<th colspan="7">&ge;5years</th>
		</tr>
		<tr class="even">
			<td colspan="1"><strong> Total Number Tested </strong></td>
			<td colspan="2" style="background-color: #C4E8B7">
			<input type="text"  id="total_tested_less_than_five" name="total_tested_less_than_five" class="validate[required,custom[onlyNumberSp]]" value="<?php echo $malaria_below_5;?>">
			</td>
			<td colspan="7" style="background-color: #C4E8B7">
			<input type="text" name="total_tested_greater_than_five" id="total_tested_greater_than_five" class="validate[required,custom[onlyNumberSp]]" value="<?php echo $malaria_above_5;?>">
			</td>
		</tr>
		<tr >
			<td colspan="1"><strong> Total Number Positive </strong></td>
			<td colspan="2" style="background-color: #C4E8B7">
			<input type="text" id="total_positive_less_than_five" name="total_positive_less_than_five" class="validate[required,custom[onlyNumberSp]]" value="<?php echo $positive_below_5;?>">
			</td>
			<td colspan="7" style="background-color: #C4E8B7">
			<input type="text" id="total_positive_greater_than_five" name="total_positive_greater_than_five" class="validate[required,custom[onlyNumberSp]]" value="<?php echo $positive_above_5;?>">
			</td>
		</tr>
		<tr class="even">
			<td colspan="1"><strong> Remarks </strong></td>
			<td colspan="9">			<textarea name="remarks" rows="2" cols="50" value="<?php echo $remarks;?>"><?php echo $remarks;?></textarea></td>
		</tr>
		<tr>
			<td><strong> Reported by </strong></td>
			<td style="background-color: #C4E8B7"  colspan="4">
			<input type="text" name="reported_by" id="reported_by" value="<?php echo $reported_by;?>">
			</td>
			<td colspan="2"><strong> Designation </strong></td>
			<td style="background-color: #C4E8B7"  colspan="4">
			<input type="text" name="designation" id="designation" value="<?php echo $designation;?>">
			</td>
		</tr>
	</table>
	<table style="margin: 5px auto;">
		<tr>
			<td>
			<input name="save" id="submit_form" type="submit" class="button" value="Save " style="width:200px; height: 30px; font-size: 16px; letter-spacing: 2px !important" />
			</td>
		</tr>
	</table>
	</form>
</div>
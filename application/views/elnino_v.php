
<script type="text/javascript">
	$(function() {
		$("#entry-form").validationEngine();
		$("#confirm_variables").click(function() {
			$("#epiweek").attr("value", $("#predicted_epiweek").text());
			$("#weekending").attr("value", $("#predicted_weekending").text());
			$("#reporting_year").attr("value", $("#predicted_year").attr("value"));
			$('#prediction').slideUp('slow');
		});
		$("#province").change(function() {
			//Get the selected province
			var province = $(this).attr("value");
			$("#district").children('option').remove();
			$.each($("#district_container").children('option'), function(i, v) {
				var current_province = $(this).attr("province");
				if (current_province == province) {
					$("#district").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
				} else if (province == 0) {
					$("#district").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
				}
			});
			//Loop through the list of all districts and display the ones from this province

		});

		$("#weekending").datepicker({
			altField : "#epiweek",
			altFormat : "DD,d MM, yy",
			firstDay : 1,
			changeYear : true,
			onClose : function(date, inst) {
				//Create a new date object from the date selected
				var new_date = new Date(date);
				//Retrieve the week number and reporting year for this date object
				var week_data = getWeek(new_date);
				$("#epiweek").attr("value", week_data[0]);
				$("#reporting_year").attr("value", week_data[1]);

			},
			beforeShowDay : function(date) {
				//Disable all days except sundays
				var day = date.getDay();
				return [(day != 1 && day != 2 && day != 3 && day != 4 && day != 5 && day != 6)];
			}
		});

	});
	/*
	 * Function that checks if district data exists
	 */

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
		if (week_days_in_year >= 4) {
			week_number += 1;
		}
		//If the week number is '0' assign the week number of the last week of the previous year
		if (week_number == 0) {
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

</script>

<div class="view_content">
<?php if($editing == false){
    ?>
<div id="prediction">
        <table  style="margin: 5px auto; border: 2px solid #EEEEEE; width: 200px">
            <caption>
                Predicted Variables
            </caption>
            <tr>
                <td>Weekending: </td><td id="predicted_weekending"><?php echo $prediction[2]; ?></td>
            </tr>
            <tr>
                <td>Epiweek: </td><td id="predicted_epiweek"><?php echo $prediction[1]; ?></td>
                <input type="hidden" id="predicted_year" value="<?php echo $prediction[0]; ?>"/>
            </tr>
            <tr>
                <td colspan="2">
                <button class="button" id="confirm_variables" style="float:right">
                    Confirm
                </button></td>
            </tr>
        </table>
    </div>
    <?php
    }

    if(isset($elnino_data)){
    $week_data = $elnino_data[0];
    $epiweek = $week_data->Epiweek;
    $returned_district = $week_data->District;
    $week_ending = $week_data->Week_Ending;
    $reporting_year = $week_data->Reporting_Year;
    $reported_by = $week_data->Reported_By;
    //$designation = $week_data->Designation;
    $telephone = $week_data->Telephone;

    $email = $week_data->Email;
    $displaced_persons_7 = $week_data -> Displaced_Persons_7;
    $deaths_7 = $week_data -> Deaths_7;
    $drug_month = $week_data->Drug_month;
    $buffer_ors = $week_data->Buffer_Ors;
    $buffer_iv = $week_data->Buffer_Iv;
    $antimalarial = $week_data->Antimalarial;
    $steering_group = $week_data->Steering_Group;
    $cholera = $week_data->Cholera;
    $malaria_positivity = $week_data->Malaria_Positivity;
    $rain = $week_data->Rain;
    $floods = $week_data->Floods;
    $displaced_persons = $week_data->Displaced_Persons;
    $deaths = $week_data->Deaths;
    $outbreak_name = $week_data->Outbreak_Name;
    }
    else{
    $displaced_persons_7 = "";
    $deaths_7 = "";
    $epiweek = "";
    $returned_district = "";
    $week_ending = "";
    $reporting_year = "";
    $reported_by = "";
    //$designation = "";
    $telephone = "";
    $email = "";
    $drug_month = "";
    $buffer_ors = "";
    $buffer_iv = "";
    $antimalarial = "";
    $steering_group = "";
    $cholera = "";
    $malaria_positivity = "";
    $rain = "";
    $floods = "";
    $displaced_persons = "";
    $deaths = "";
    $outbreak_name = "";
    }
    $attributes = array('id' => 'entry-form');
    echo form_open('elnino_management/save', $attributes);
    echo validation_errors('<p class="error">', '</p>');
    ?>    
    
    <table  style="margin: 5px auto; border: 2px solid #EEEEEE;">
    <tr>
    <td><b>Week Ending:</b></td><td>
    <input type="text" name="week_ending" id="weekending" class="validate[required]" value="<?php echo $week_ending; ?>"/>
    </td>
    <td><b>Epiweek: </b></td>
    <td>
    <input type="text" name="epiweek" id="epiweek" readonly="" class="validate[required,custom[onlyNumberSp]]" value="<?php echo $epiweek; ?>"/>
    <input type="hidden" name="reporting_year" id="reporting_year" value="<?php echo $reporting_year; ?>"/>    
    </td>
    </tr>
            </table>
        <table class="data-table" style="margin: 0 auto;">
        <tr>
            <th>El &Ntilde;ino Information Entry</th>
            <th></th>            
        </tr>
        <tr>
            <th>Name of DDSC</th>
            <td><input type="text" value="<?php echo $reported_by; ?>" id="ddsc" name="reported_by"/></td>
        </tr>
        <tr>
            <th>Telephone of DDSC</th>
            <td><input type="text" value="<?php echo $telephone; ?>" id="telephone" name="telephone"/></td>
        </tr>
        <tr>
            <th>Email Address</th>
            <td><input type="text" value="<?php echo $email; ?>" id="email" name="email"/></td>
        </tr>
        
        <tr>
            <th>Month district received last Kemsa drugs</th>
            <td><input type="text" value="<?php echo $drug_month; ?>" id="drug_month" name="drug_month"/></td>
        </tr>
        
        <tr>
            <th>Adequate Buffer stock of ORS available?(Sachets equivalent to 1.6% of the population) Y/N</th>
            <td>                
                <select name="buffer_ors">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Adequate Buffer stock of IV fluids available?(500mls bottles equivalent to 0.4% of population) Y/N</th>
            <td>                
                <select name="buffer_iv">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Do all governement hospitals have  antimalarial drugs available? Y/N</th>
            <td>                
                <select name="antimalarial">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Has District Steering Group started meeting to discuss Elnino ? Y/N</th>
            <td>                
                <select name="steering_group">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Any confirmed cholera cases reported  in the last 7 days? Y/N</th>
            <td>                
                <select name="cholera">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Any upsurge in malaria positivity rate in the last 2 weeks? Y/N</th>
            <td>                
                <select name="malaria_positivity">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Any rain in the district last 7 days? Y/N</th>
            <td>                
                <select name="rain">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Any floods in the last 7 days Y/N</th>
            <td>                
                <select name="floods">
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        
        
        <tr>
            <th>Number of displaced persons due to rains/ floods in the last 7 days?</th>
            <td>                
              <input type="text" name="displaced_persons_7" value="<?php echo $displaced_persons_7; ?>" id="displaced_persons_7"/>
            </td>
        </tr>
        
        
        <tr>
            <th>Cumuative number of displaced persons due to rains/ floods?</th>
            <td>                
              <input type="text" name="displaced_persons" value="<?php echo $displaced_persons; ?>" id="displaced_persons"/>
            </td>
        </tr>
        
        <tr>
            <th>Number of deaths due to floods/rain in the last 7 days</th>
            <td>                
              <input type="text" name="deaths_7" value="<?php echo $deaths_7; ?>" id="deaths_7"/>
            </td>
        </tr>
        
        
        <tr>
            <th>Cumulative number of deaths due to floods/rain</th>
            <td>                
              <input type="text" name="deaths" value="<?php echo $deaths; ?>" id="deaths"/>
            </td>
        </tr>
        
        <tr>
            <th>Current outbreak in the district(type name of disease)</th>
            <td>                
              <select name="outbreak_name" id="outbreak_name">
                <option value="0">Select Outbreak</option>
                <?php
                foreach ($elnino_diseases as $elnino) {                    
                        echo '<option selected value="' . $elnino -> Name . '">' . $elnino -> Name . '</option>';                    
                }//end foreach
                ?>
            </select>
            </td>
        </tr>
    </table>
    <table style="margin: 5px auto;">
        <tr>
            <td>
            <input name="save" type="submit" class="button" value="Save " style="width:200px; height: 30px; font-size: 16px; letter-spacing: 2px !important" />
            </td>
        </tr>
    </table>
    </form>
</div>
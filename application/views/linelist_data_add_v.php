<head>
	<script>
		$(function() {
			$("#province").change(function() {
				//Get the selected province
				var province = $(this).attr("value");
				$("#district").children('option').remove();
				$("#district").append($("<option></option>").attr("value", "0").text("All Districts"));
				$.each($("#district_container").children('option'), function(i, v) {
					var current_province = $(this).attr("province");
					if(current_province == province) {
						$("#district").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
					} else if(province == 0) {
						$("#district").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
					}
				});
				//Loop through the list of all districts and display the ones from this province

			});
		});
		$(function() {
			$("#district").change(function() {
				//Get the selected district
				var district = $(this).attr("value");
				$("#facility").children('option').remove();
				$("#facility").append($("<option></option>").attr("value", "0").text("All Facilities"));
				$.each($("#facility_container").children('option'), function(i, v) {
					var current_district = $(this).attr("district");
					if(current_district == district) {
						$("#facility").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
					} else if(district == 0) {
						$("#facility").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
					}
				});
				//Loop through the list of all districts and display the ones from this province

			});
		});
		$(function() {
			$("#in").change(function() {
				if($('#in').is(':checked')) {
					document.getElementById("out").checked = false;
				}
			});
		});
		$(function() {
			$("#out").change(function() {
				if($('#out').is(':checked')) {
					document.getElementById("in").checked = false;
				}
			});
		});


	$(function() {
		$( "#date_received" ).datepicker();
		$( "#onset_date" ).datepicker();
		$( "#date_facility" ).datepicker();
		$( "#specimen_date" ).datepicker();
	});
	
	</script>
</head>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$attributes = array('enctype' => 'multipart/form-data');
echo form_open('linelisted_data_management/save', $attributes);
echo validation_errors('
<p class="error">', '</p>
');
?>

<table style="margin: 5px auto; border: 2px solid #EEEEEE;">
	<tr>
		<caption>
			<b>Health Facility Line-Listing Form</b>
		</caption>
	</tr>
	<tbody>
		<tr>
			<td>Province
			<select name="province" id="province">
				<option value="0">Select Province</option>
				<?php
				foreach ($provinces as $province) {
					echo '<option value="' . $province -> id . '">' . $province -> Name . '</option>';
				}//end foreach
				?>
			</select></td>
			<td> District
			<select id="district" name="district" >
				<option value="0">All Districts</option>
				<?php
				foreach ($districts as $district) {
					echo '<option value="' . $district -> id . '" province="' . $district -> Province . '" >' . $district -> Name . '</option>';
				}//end foreach
				?>
			</select>
			<select id="district_container" style="display: none">
				<option value="">Select District</option>
				<?php
				foreach ($districts as $district) {
					echo '<option value="' . $district -> id . '" province="' . $district -> Province . '" >' . $district -> Name . '</option>';
				}//end foreach
				?>
			</select></td>
			<td> Date received at district
			<input type="text" name="date_received"  id="date_received" required/>
			</td>
		</tr>
		<tr>
			<td> Health Facility
			<select id="facility" name="facility" >
				<option value="0">All Facilities</option>
				<?php
				foreach ($facilitiess as $facility) {
					echo '<option value="' . $facility -> id . '" district="' . $facility -> District . '" >' . $facility -> Name . '</option>';
				}//end foreach
				?>
			</select>
			<select id="facility_container" style="display: none">
				<option value="">Select Facility</option>
				<?php
				foreach ($facilities as $facility) {
					echo '<option value="' . $facility -> id . '" district="' . $facility -> District . '" >' . $facility -> Name . '</option>';
				}//end foreach
				?>
			</select></td>
			<td> Disease/Condition
			<input type="text" name="disease" id="disease" required/>
			</td>
		</tr>
	</tbody>
</table>
<!--patient data-->
<table style="margin: 5px auto; border: 2px solid #EEEEEE;" id="linelister">
	<tr>
		<th>A</th>
		<th>B</th>
		<th>C</th>
		<th>D</th>
		<th>E</th>
		<th>F</th>
		<th>G</th>
		<th>H</th>
		<th>I</th>
		<th>J</th>
		<th>K</th>
		<th>L</th>
	</tr>
	<tr>
		<th></th>
		<th>Names</th>
		<th>Patients(Check as appropriate)</th>
		<th>Village or Town and Neighbourhood
		<br>
		INDICATE Major Landmarks</th>
		<th>Sex</th>
		<th>Age</th>
		<th>Date seen at health facility</th>
		<th>Date of onset of disease</th>
		<th>Number of doses of vaccine(
		<br>
		Exclude doses given within 14 days of onset)</th>
		<th>Lab Tests</th>
		<th>Outcome</th>
		<th>Comments</th>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<th>
		<table>
			<tr>
				<th>In patient</th>
				<th>Out patient</th>
			</tr>
		</table></th>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<th>
		<table>
			<tr>
				<th>Specimen taken</th>
				<th>Lab results</th>
			</tr>
		</table></th>
		<th>
		<table>
			<tr>
				<th>Alive or Dead</th>
			</tr>
		</table></th>
		<td></td>
	</tr>
	<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td>
	<table>
		<tr>
			<th> Date </th>
			<th> Type </th>
		</tr>
	</table></td>
	
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr id="new_patient_row">		
		<td></td>
		
		<!--Names-->
		<td>
		<input type="names" name="names" style="width: 40px" required/>
		</td>
		
		<!--Patient-->
		<td>
		<table>
			<tr>
				<td>
				<input type="checkbox" name="patient" id="in" value="in"/>
				</td>
				<td>
				<input type="checkbox" name="patient" id="out" value="out"/>
				</td>
			</tr>
		</table>
		</td>
		
		<!--village-->
		<td>
		<input type="text" name="village" style="width: 40px" required/>
		</td>
		
		<!--sex-->
		<td>
		<select name="sex" id="sex">
			<option value="male">Male</option>
			<option value="female">Female</option>
		</select>
		</td>
		
		<!--age-->
		<td>
		<input type="text" name="age" style="width: 40px" required/>
		</td>
		
		<!--facility-->
		<td>
		<input type="text" name="date_facility" style="width: 40px" id="date_facility"/>
		</td>
		
		<!--onset-->
		<td>
		<input type="text" name="onset_date" style="width: 40px" id="onset_date"/>
		</td>
		
		<!--dosage-->
		<td>
		<input required type="text" name="dosage_number" style="width: 40px" />
		</td>
		
		<!--specimen-->
		<td>
		<table>
			<tr>
				<td>
				<input type="text" name="specimen_date" style="width: 40px" id="specimen_date"/>
				</td>
				<td>
				<input type="text" name="specimen_type" style="width: 40px"/>
				</td>
				<td>
				<input required type="text" name="lab_results" style="width: 40px"/>	
				</td>
			</tr>
		</table>
		</td>
		
		<!--outcome-->
		<td>
		<select name="outcome">
			<option value="alive">Alive</option>
			<option value="dead">Dead</option>
		</select>
		</td>
		
		<!--comments-->
		<td>
			<textarea name="comments"></textarea>
		</td>
		
		
		<td>
		<input type="button" value="+" id="add_new_patient" class="greenbutton"/>
		</td>
		
		<td>
		<input type="button" value="-" id="remove_patient" class="greenbutton"/>
		</td>
		
	</tr>
	<tr>
		<td>
		<input name="submit" type="submit" class="button" value="Save Data"></td>
	</tr>
</table>
<script>
	$("#add_new_patient").click(function() {
		var cloned_object = $('#new_patient_row').clone(true);
		cloned_object.insertAfter('#new_patient_row');
	});

	$("#remove_patient").click(function() {
		 $("#new_patient_row").after().remove();
	});
</script>

<?php echo form_close();?>
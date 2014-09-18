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
		$( "#date" ).datepicker();
	});
	
	</script>
</head>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$attributes = array('enctype' => 'multipart/form-data');
echo form_open('rumour_log/save', $attributes);
echo validation_errors('
<p class="error">', '</p>
');
?>

<table style="margin: 5px auto; border: 2px solid #EEEEEE;">
	<tr>
		<caption>
			<b>National log of Suspected Outbreaks and Rumours</b>
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
			<td> Health Facility
			<select id="facility" name="facility" >
				<option value="0">All Facilities</option>
				<?php
				foreach ($facilities as $facility) {
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
			
		</tr>
		<tr>
			<td> Date received at district
			<input type="text" name="date_received"  id="date_received" required/>
			</td>
			
		</tr>
	</tbody>
</table>
<!--patient data-->
<table width="75%;" style="margin: 5px auto; border: 2px solid #EEEEEE;" id="linelister">
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

	</tr>
	<tr>
		
		
		<th>Disease</th>
		<th>Source</th>
		<th>Cases Reported</th>
		<th>No of Deaths Reported</th>
		<th>Case Fatality Rate (%)</th>
		<th>Results</th>
		<th>Onset Date</th>
		<th>First Seen Date</th>
		<th>Date intervention began</th>
		<th>Type of National Response</th>
		<th>Comments</th>

	</tr>
	
	
	<tr id="new_patient_row">		
		
		
		
		<td>
		<select name="disease" id="disease">
				<option value="0" selected>--Select Disease--</option>
				<?php
				foreach ($diseases as $disease) {
					echo "<option value='$disease->id'>$disease->Name</option>";
				}
				?>
			</select></td>
		
		<!--village-->
		<td>
		<input type="text" name="source" required > 
		</td>
		
		<!--sex-->
		<td>
		<input type="text" name="cases" required style="width: 60px" required /> 
		</td>
		
		<!--age-->
		<td>
		<input type="text" name="death"  style="width: 60px" required/>
		</td>
		
		<!--facility-->
		<td>
		<input type="text" name="fatality" id="" style="width: 60px" />
		</td>
		
		<!--onset-->
		<td>
		<select name="results" id="sex">
			<option value="Confirmed">Confirmed</option>
			<option value="Ruled Out">Ruled Out</option>
			<option value="Unknown">Unknown</option>
		</select>
		</td>
		
		
		<td>
			<input type="text" name="onset" id="onset_date" style="width: 100px"/>
		</td>
		<td>
			<input type="text" name="first" id="date_facility" style="width: 80px"/>
		</td>
		<td>
			<input type="text" name="intervention" id="date" style="width: 100px"/>
		</td>
		<td>
		<select name="nresponse" id="sex">
			<option value="Confirmed">Confirmed</option>
			<option value="Ruled Out">Ruled Out</option>
			<option value="Unknown">Unknown</option>
		</select>
		</td>
		
		
		<!--outcome-->
		<td>
		<textarea name="comments"></textarea>
		</td>
		
		<!--comments-->

		
		
		<td>
		<input type="button" value="+" id="add_new_patient" class="greenbutton"/>
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
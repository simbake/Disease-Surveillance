<?php
$this -> load -> helper('url');
$this -> load -> helper('form');
?>
<script >
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

</script>
<style>
	select{
		width:150px;
	}
</style>
<div  class="section">
	<div class="section-title" align="center">
		<strong>SUBMISSIONS LIST  FOR EPIWEEK <?php echo $selected_epiweek;?></strong>
	</div>
</div>
<div align="center">
	<?php echo form_open('submissions_management/filter');?>
	<table>
		<tr>
			<td> Province
			<select name="province" id="province" name="province" >
				<option value="0">All Provinces</option>
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
			<td>Epiweek
			<select name="epiweek" id="epiweek">
				<option value="0">Select Epiweek</option>
				<?php
				for ($w = 1; $w <= 53; $w++) {
					echo "<option>" . $w . "</option>";
				}
				?>
			</select></td>
			<td> Year
			<select name="filteryear" id="filteryear">
				<option selected value="0">Select Year</option> 
				<?php
				foreach ($years as $years) {
					echo "<option value='$years->filteryear'>$years->filteryear</option>";
				}
					?>
			</select></td>
			<td>
			<input name="filter" type="submit" class="button" value="Filter"/>
			</td>
		</tr>
	</table>
	<?php echo form_close();?>
</div>
<div id="draggable" class="section" align="center">
	<table class="data-table">
		<tr>
			<th>&nbsp;</th>
			<th colspan="4">&le; 5 Years</th>
			<th colspan="4">&ge; 5 Years</th>
			<th colspan="4">Total</th>
			<th colspan="4">Cummulative Total
			<br />
			(As from 1st January) </th>
			<th rowspan="3"> Action </th>
		</tr>
		<tr>
			<th>Diseases</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
			<!--<th>Tasks</th>-->
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th>M</th>
			<th>F</th>
			<th>M</th>
			<th>F</th>
			<th>M</th>
			<th>F</th>
			<th>M</th>
			<th>F</th>
			<th>M</th>
			<th>F</th>
			<th>M</th>
			<th>F</th>
			<th>M</th>
			<th>F</th>
			<th>M</th>
			<th>F</th>
		</tr>
		<tr>
			<?php
			/*
			 * the iterator below is used to display values from a multi-dimensional array based on the disease id.
			 */
			foreach ($diseases as $disease) {
				echo "<tr>";
				echo "<td>" . $disease -> Name . "</td>";
				echo "<td>" . (int)$values[$disease -> id][0] . "</td>";
				echo "<td>" . (int)$values[$disease -> id][1] . "</td>";
				echo "<td>" . (int)$values[$disease -> id][2] . "</td>";
				echo "<td>" . (int)$values[$disease -> id][3] . "</td>";
				echo "<td>" . (int)$values[$disease -> id][4] . "</td>";
				echo "<td>" . (int)$values[$disease -> id][5] . "</td>";
				echo "<td>" . (int)$values[$disease -> id][6] . "</td>";
				echo "<td>" . (int)$values[$disease -> id][7] . "</td>";

				echo "<td>" . ($values[$disease -> id][0] + $values[$disease -> id][4]) . "</td>";
				echo "<td>" . ($values[$disease -> id][1] + $values[$disease -> id][5]) . "</td>";
				echo "<td>" . ($values[$disease -> id][2] + $values[$disease -> id][6]) . "</td>";
				echo "<td>" . ($values[$disease -> id][3] + $values[$disease -> id][7]) . "</td>";

				echo "<td>" . ($values[$disease -> id][8] + $values[$disease -> id][12]) . "</td>";
				echo "<td>" . ($values[$disease -> id][9] + $values[$disease -> id][13]) . "</td>";
				echo "<td>" . ($values[$disease -> id][10] + $values[$disease -> id][14]) . "</td>";
				echo "<td>" . ($values[$disease -> id][11] + $values[$disease -> id][15]) . "</td>";

				echo "<td>";
				echo anchor('Submissions_Management/provincialDetails' . "/" . $selected_epiweek . "/" . $disease -> id, 'Breakdown', array("class" => "link"));
				echo "</td>";
				echo "</tr>";
			}//end of while
			?>
		</tr>
	</table>
</div>

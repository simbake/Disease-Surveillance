<?php
$this -> load -> helper('url');
?>
<script >
	json_obj = {
"url" : "<?php echo site_url("Submissions_c/getDistrict");?>
	"
	};
	var url = json_obj.url;

	function getDistricts() {
		var province_id = document.getElementById("province").value;
		if(province_id == 0) {
			document.getElementById("districts").innerHTML = "<select id=\"districts\" name=\"districts\" id=\"districts\" ><option selected value=\"0\">Select District</option>;							</select>";
		} else {
			var url_concat = url + "/" + province_id;
			var xmlhttp;
			if(window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var ajaxResponse = xmlhttp.responseText;
					var idNameConcat = ajaxResponse.split("_");
					var districts = "<select id=\"districts\" name=\"districts\" id=\"districts\" >";
					for( i = 0; i < idNameConcat.length - 1; i++) {
						var separate = idNameConcat[i].split("+");
						var value = "<option value=" + separate[0] + ">" + separate[1] + "</option>";
						districts += value;
					}
					districts += "</select>";
					document.getElementById("districts").innerHTML = districts;
				}
			}

			xmlhttp.open("GET", url_concat, true);
			xmlhttp.send();
		}
	}
</script>
<div  class="section">
	<div class="section-title" align="center">
		<strong>AGGREGATED IDSR WEEKLY EPIDEMIC MONITORING FORMS FOR EPIWEEK <?php echo $selected_epiweek;?></strong>
	</div>
</div>
<div align="center">
	<?php echo form_open('Submissions_c/filter');?>
	<table>
		<tr>
			<td> Province
			<select name="province" id="province" name="province" onclick="getDistricts();" >
				<option value="0">Select Province</option>
				<?php
				foreach ($provinces as $province) {
					echo "<option selected value='$province->id'>$province->Name</option>" . "<br>";
				}
				?>
			</select></td>
			<td> District
			<select id="districts" name="districts" id="districts" >
				<option selected value="0">Select District</option>
			</select></td>
			<td>Epiweek
			<select name="epiweek">
				<option selected>Select Epiweek <?php
				for ($w = 1; $w <= 52; $w++) {
					echo "<option>" . $w;
				}
					?>
			</select></td>
			<td> Year
			<select name="filteryear">
				<option selected>Select Year <?php
				foreach ($years as $years) {
					echo "<option selected value='$years->filteryear'>$years->filteryear</option>";
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
			<th colspan="17"><?php echo $diseaseName;?></th>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th colspan="4">&le; 5 Years</th>
			<th colspan="4">&ge; 5 Years</th>
			<th colspan="4">Total</th>
			<th colspan="4">Cummulative Total
			<br />
			(As from 1st January) </th>
		</tr>
		<tr>
			<th>Provinces</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
			<th colspan="2">Cases</th>
			<th colspan="2">Deaths</th>
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
			foreach ($provinces as $provinces) {
				echo "<tr>";
				echo "<td>" . $provinces -> Name . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][0] . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][1] . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][2] . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][3] . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][4] . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][5] . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][6] . "</td>";
				echo "<td>" . (int)$values[$provinces -> id][7] . "</td>";

				echo "<td>" . ($values[$provinces -> id][0] + $values[$provinces -> id][4]) . "</td>";
				echo "<td>" . ($values[$provinces -> id][1] + $values[$provinces -> id][5]) . "</td>";
				echo "<td>" . ($values[$provinces -> id][2] + $values[$provinces -> id][6]) . "</td>";
				echo "<td>" . ($values[$provinces -> id][3] + $values[$provinces -> id][7]) . "</td>";

				echo "<td>" . ($values[$provinces -> id][8] + $values[$provinces -> id][12]) . "</td>";
				echo "<td>" . ($values[$provinces -> id][9] + $values[$provinces -> id][13]) . "</td>";
				echo "<td>" . ($values[$provinces -> id][10] + $values[$provinces -> id][14]) . "</td>";
				echo "<td>" . ($values[$provinces -> id][11] + $values[$provinces -> id][15]) . "</td>";
				echo "</tr>";
			}//end of while
			?>
		</tr>
	</table>
</div>

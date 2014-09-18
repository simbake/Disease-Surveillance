<?php 
$this->load->helper('url');
$this->load->helper('form');
?>
<script >
	json_obj = {
		"url" : "<?php echo site_url("Submissions_c/getDistrict"); ?>"
	};
	var url=json_obj.url;
	
	function getDistricts(){
		var province_id=document.getElementById("province").value;
		if(province_id==0){
			document.getElementById("districts").innerHTML = "<select id=\"districts\" name=\"districts\" id=\"districts\" ><option selected value=\"0\">Select District</option>;							</select>";
		}
		else{
			var url_concat= url+"/"+province_id; 
			var xmlhttp;
	        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	        	xmlhttp = new XMLHttpRequest();
	        }
	        else {// code for IE6, IE5
	            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	        }	                        
	        xmlhttp.onreadystatechange = function(){
	        	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {	                                    
	        		var ajaxResponse=xmlhttp.responseText;
	            	var idNameConcat=ajaxResponse.split("_");
	            	var districts="<select id=\"districts\" name=\"districts\" id=\"districts\" >";           
	            	for(i = 0; i < idNameConcat.length-1; i++){
	            		var separate= idNameConcat[i].split("+");
	              		var value= "<option value="+separate[0]+">"+separate[1]+"</option>";
	              		districts+=value;
	            	}
	            	districts+="</select>";
	            	document.getElementById("districts").innerHTML = districts;	                        	                                
	        	}
	        }	    
	                         
	        xmlhttp.open("GET", url_concat, true);
	        xmlhttp.send();
       }
	}
</script>
<div  class="section">
		
		<div class="section-title" align="center"><strong>DETAILS FOR <?php echo $districtName; ?> FOR EPIWEEK <?php echo $selected_epiweek; ?></strong></p></div>
</div>
<div align="center">
	<?php echo form_open('Submissions_c/filter'); ?>
		<table>
			<tr>
				<td> Province 
				<select name="province" id="province" name="province" onclick="getDistricts();" >
					<option value="0">Select Province</option>
					<?php
					foreach($provinces as $province){
						echo "<option selected value='$province->id'>$province->Name</option>" . "<br>";
					}
					?>
				</select>
				</td>
				<td> District 
				<select id="districts" name="districts" id="districts" >
					<option selected value="0">Select District</option>							
				</select></td>
				<td>Epiweek
				<select name="epiweek">
					<option selected>Select Epiweek
						<?php
							for ($w = 1; $w <= 53; $w++) {
								echo "<option>" . $w;
							}
						?>
				</select>
				</td>
				<td> Year 
					<select name="filteryear"><option selected>Select Year
						<?php
							foreach($years as $years){
								echo "<option selected value='$years->filteryear'>$years->filteryear</option>" ;
							}
						?>
					</select>
				</td>
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
			<th rowspan="3">
			Action
			</th>
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
		foreach($diseases as $disease){
			echo "<tr>";
			echo "<td>" . $disease->Name. "</td>";
			echo "<td>" . (int)$values[$disease->id][0]. "</td>";
			echo "<td>" . (int)$values[$disease->id][1]. "</td>";
			echo "<td>" . (int)$values[$disease->id][2] . "</td>";
			echo "<td>" . (int)$values[$disease->id][3] . "</td>";
			echo "<td>" . (int)$values[$disease->id][4] . "</td>";
			echo "<td>" . (int)$values[$disease->id][5] . "</td>";
			echo "<td>" . (int)$values[$disease->id][6] . "</td>";
			echo "<td>" . (int)$values[$disease->id][7] . "</td>";

			echo "<td>" . ($values[$disease->id][0] + $values[$disease->id][4]) . "</td>";
			echo "<td>" . ($values[$disease->id][1] + $values[$disease->id][5]) . "</td>";
			echo "<td>" . ($values[$disease->id][2] + $values[$disease->id][6]) . "</td>";
			echo "<td>" . ($values[$disease->id][3] + $values[$disease->id][7]) . "</td>";

			echo "<td>" . ($values[$disease->id][8] + $values[$disease->id][12]) . "</td>";
			echo "<td>" . ($values[$disease->id][9] + $values[$disease->id][13]) . "</td>";
			echo "<td>" . ($values[$disease->id][10] + $values[$disease->id][14]) . "</td>";
			echo "<td>" . ($values[$disease->id][11] + $values[$disease->id][15]) . "</td>";

			echo "<td>"; echo anchor('Submissions_c/provincialDetails'."/".$selected_epiweek."/".$disease->id,'Breakdown',array("class"=>"link")); echo "</td>";
			echo "</tr>";
		}//end of while
		
		?>
		</tr>
	</table>
</div>		

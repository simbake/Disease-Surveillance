<?php
if (isset($county)) {
	$name = $county -> Name;
	$province_id = $county -> Province;
	$latitude = $county -> Latitude;
	$longitude = $county -> Longitude;
	$county_id = $county->id;
} else {
	$name = "";
	$province_id = "";
	$latitude = "";
	$longitude = "";
	$county_id = "";

}
$attributes = array('enctype' => 'multipart/form-data');
echo form_open('county_management/save', $attributes);
echo validation_errors('
<p class="error">', '</p>
');
?>
<input type="hidden" name="county_id" value = "<?php echo $county_id; ?>"/>
<table border="0" class="data-table" style="margin: 5px auto">
	<tr>
		<th class="subsection-title" colspan="2">County Details</th>
	</tr>
	<tbody>
		<tr>
			<td><span class="mandatory">*</span> County Name</td>
			<td><?php

			$data_search = array('name' => 'name', 'value' => $name);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td><span class="mandatory">*</span> Region</td>
			<td>
			<select name="province">
				<?php
foreach($provinces as $province){
				?>
				<option value="<?php echo $province->id?>" <?php
				if ($province -> id == $province_id) {echo "selected";
				}
				?> ><?php echo $province->Name
					?></option>
				<?php }?>
			</select></td>
		</tr>
		<tr>
			<td>HQ Latitude</td>
			<td><?php

			$data_search = array('name' => 'latitude', 'value' => $latitude);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td>HQ Longitude</td>
			<td><?php

			$data_search = array('name' => 'longitude', 'value' => $longitude);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
		<td align="center" colspan=2>
		<input name="submit" type="submit"
		class="button" value="Save County Details">
		</td>
	</tr>
	</tbody>
</table>  
<?php echo form_close();?>
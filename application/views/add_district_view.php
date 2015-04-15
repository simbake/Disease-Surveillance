<?php
if (isset($district)) {
	$name = $district -> Name;
	$province_id = $district -> Province;
	$county_id = $district -> County;
	$latitude = $district -> Latitude;
	$longitude = $district -> Longitude;
	$district_id = $district->id;
} else {
	$name = "";
	$province_id = "";
	$county_id = "";
	$latitude = "";
	$longitude = "";
	$district_id = "";

}
$attributes = array('enctype' => 'multipart/form-data');
echo form_open('district_management/save', $attributes);
echo validation_errors('
<p class="error">', '</p>
');
?>
<input type="hidden" name="district_id" value = "<?php echo $district_id; ?>"/>
<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					District Details
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">

	<tbody>
		<tr>
			<td><span class="mandatory">*</span> District Name</td>
			<td><?php

			$data_search = array('name' => 'name', 'value' => $name);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td><span class="mandatory">*</span>County</td>
			<td>
			<select name="county">
				<?php
foreach($counties as $county){
				?>
				<option value="<?php echo $county->id?>" <?php
				if ($county -> id == $county_id) {echo "selected";
				}
				?> ><?php echo $county->Name
					?></option>
				<?php }?>
			</select></td>
		</tr>
		<tr>
			<td><span class="mandatory">*</span>Region</td>
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
			<td> Latitude</td>
			<td><?php

			$data_search = array('name' => 'latitude', 'value' => $latitude);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td> Longitude</td>
			<td><?php

			$data_search = array('name' => 'longitude', 'value' => $longitude);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
		<td align="center" colspan=2>
		<input name="submit" type="submit"
		class="btn btn-primary" value="Save District">
		</td>
	</tr>
	</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>  
<?php echo form_close();?>
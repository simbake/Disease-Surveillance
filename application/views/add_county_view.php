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



<br/>
<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">County Details <span class="glyphicon glyphicon-globe" style=""></span></h3>
				</div>

 <div class="panel-body">
 	<div class="table-responsive">

<table class="table table-striped  table-responsive table-bordered"  width="auto">
	
	<tbody>
		<tr>
			<td><label for="name">*County Name</label></td>
			<td><?php

			$data_search = array('name' => 'name', 'value' => $name);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td><label for="province">*Region</label></td>
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
			<td><label for="latitude">HQ Latitude</label></td>
			<td><?php

			$data_search = array('name' => 'latitude', 'value' => $latitude);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td><label for="longitude">HQ Longitude</label></td>
			<td><?php

			$data_search = array('name' => 'longitude', 'value' => $longitude);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
		<td align="center" colspan=2>
		<input name="submit" type="submit" class="btn btn-info " value="Save County Details"/>
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
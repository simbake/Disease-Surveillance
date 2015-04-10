<script>
$(document).ready( function () {
    $('#county_table').dataTable();
} );
    </script>
<div class= "container-fluid">
    <div class="row"> 
		 	<div class="container-fluid">
<div class="panel panel-primary">
				<div class="panel-heading">
					Counties
				</div>
 <div class="panel-body ">
 	  
        <table  style="margin-left: 0;" id="county_table" class="table table-striped table-bordered table-hover" width="100%">
	
		<thead>
			<tr><a href="<?php echo  site_url("county_management/add");?>" class="btn btn-primary pull-left">New County</a></tr>
			<br/><br/><br/>
		<tr>
			
		<th>Name</th>
		<th>Province</th>
		<th>Latitude</th>
		<th>Longitude</th>
		<th>Disabled?</th> 
		<th>Action</th>
	</tr>
	</thead>
					
							<tbody>
	<?php
foreach($counties as $county){
	?>
	<tr>
		<td><?php echo $county -> Name;?></td>
		<td><?php echo $county -> Province_Object -> Name;?></td>
		<td><?php echo $county -> Latitude;?></td>
		<td><?php echo $county -> Longitude;?></td>
		<td><?php
			if ($county -> Disabled == 0) {echo "No";
			} else {echo "Yes";
			};
		?></td>
		 
		<td><a href="<?php echo base_url()."county_management/edit_county/".$county->id?>"  class='label label-primary'><span class="glyphicon glyphicon-refresh"></span>Edit </a>| <?php
if($county->Disabled == 0){
		?>
		<a href="<?php echo base_url()."county_management/change_availability/".$county->id."/1"?>" class='label label-danger'><span class='glyphicon glyphicon-off'></span>  Disable</a><?php }
			else{
		?>
		<a href="<?php echo base_url()."county_management/change_availability/".$county->id."/0"?>" class='label label-info'><span class='glyphicon glyphicon-off'></span>Enable</a><?php }?></td>
	</tr>
	<?php }?>
	</tbody>
</table>

</div>
</div>
</div>
</div>
</div>
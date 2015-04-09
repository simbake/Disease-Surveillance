<!-- <div id="sub_menu">
<a href="<?php echo  site_url("district_management/add");?>" class="top_menu_link sub_menu_link first_link <?php if($quick_link == "vaccine_management"){echo "top_menu_active";}?>">New District</a>  
 
</div> -->

<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					Districts
				</div>

 <div class="panel-body">
 	<div class="table-responsive">
        <table class="table table-responsive table-hover table-striped" id="example" width="100%" >
	
		<thead>
			<tr><a href="<?php echo  site_url("district_management/add");?>" class="btn btn-info pull-left">New District</a></tr>
			<br/><br/><br/>
		<tr>
		<th>Name</th>
		<th>County</th>
		<th>Region</th>		
		<th>Latitude</th>
		<th>Longitude</th>
		<th>Disabled?</th>
		<th>Records</th>
		<th>Action</th>
	</tr>
					</thead>
					
							<tbody>
	<?php
foreach($districts as $district){
	?>
	<tr>
		<td><?php echo $district -> Name;?></td>
		<td><?php echo $district -> County_Object -> Name;?></td>
		<td><?php echo $district -> Province_Object -> Name;?></td>		
		<td><?php echo $district -> Latitude;?></td>
		<td><?php echo $district -> Longitude;?></td>
		<td><?php
			if ($district -> Disabled == 0) {echo "No";
			} else {echo "Yes";
			};
		?></td>
		<td><?php echo count($district -> Surveillance);?></td>
		<td><a href="<?php echo base_url()."district_management/edit_district/".$district->id?>" class='label label-primary'><span class="glyphicon glyphicon-refresh"></span> Edit </a>| <?php
if($district->Disabled == 0){
		?>
		<a  href="<?php echo base_url()."district_management/change_availability/".$district->id."/1"?>" class='label label-danger'><span class='glyphicon glyphicon-off'></span> Disable</a><?php }
			else{
		?>
		<a  href="<?php echo base_url()."district_management/change_availability/".$district->id."/0"?>" class='label label-info'><span class='glyphicon glyphicon-off'></span> Enable</a><?php }?></td>
	</tr>
	<?php }?>
	</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>


<?php
if (!isset($sub_link)) {
	$sub_link = "";
}
?>

<script>
	$(document).ready(function() {
    $('#facility_table').DataTable();
} );
    </script>
    
<!-- <div id="sub_menu" style="margin:5px;">
	<a href="<?php echo site_url('facility_management/search_facility');?>" class="top_menu_link sub_menu_link first_link  <?php
	if ($sub_link == "search_facility") {echo "top_menu_active";
	}
	?>">Search Facility</a>
</div> -->
<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default ">
				<div class="panel-heading">
					Facilities
				</div>

 <div class="panel-body">
 	 <div class="table-responsive">
        <table id="facility_table" class="display" cellspacing="0" width="100%">
	
		<thead>
			
		<tr>
		<th>Facility Code</th>
		<th>Name</th>
		<th>District</th>
		<th>Reporting</th>
		<th>Action</th>
	</tr>
	</thead>
					
							<tbody>
	<?php
foreach($facilities as $facility){
	?>
	<tr>
		<td><?php echo $facility -> facilitycode;?></td>
		<td><?php echo $facility -> name;?></td>
		<td><?php echo $facility -> Parent_District -> Name;?></td>
		<td><?php
		if ($facility -> reporting == 0) {echo "No";
		} else {echo "Yes";
		};
		?></td>
		<td><?php
if($facility->reporting == 1){
		?>
		<a  href="<?php echo base_url()."facility_management/change_reporting/".$facility->facilitycode."/0"?>" class='label label-danger'><span class='glyphicon glyphicon-off'></span>Not Reporting</a><?php }
			else{
		?>
		<a  href="<?php echo base_url()."facility_management/change_reporting/".$facility->facilitycode."/1"?>" class='label label-info'><span class='glyphicon glyphicon-off'></span> Reporting</a><?php }?></td>
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

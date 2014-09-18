<div id="sub_menu">
<a href="<?php echo  site_url("district_management/add");?>" class="top_menu_link sub_menu_link first_link <?php if($quick_link == "vaccine_management"){echo "top_menu_active";}?>">New District</a>  
 
</div>
<?php if (isset($pagination)):
?>
<div style="width:450px; margin:0 auto 60px auto">
	<?php echo $pagination;?>
</div>
<?php endif;?>
<table border="0" class="data-table" style="margin: 5px auto">
	<th class="subsection-title" colspan="11">Districts</th>
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
		<td><a href="<?php echo base_url()."district_management/edit_district/".$district->id?>" class="link">Edit </a>| <?php
if($district->Disabled == 0){
		?>
		<a class="link" style="color:red" href="<?php echo base_url()."district_management/change_availability/".$district->id."/1"?>">Disable</a><?php }
			else{
		?>
		<a class="link" style="color:green" href="<?php echo base_url()."district_management/change_availability/".$district->id."/0"?>">Enable</a><?php }?></td>
	</tr>
	<?php }?>
</table>
<?php if (isset($pagination)):
?>
<div style="width:450px; margin:0 auto 60px auto">
	<?php echo $pagination;?>
</div>
<?php endif;?>
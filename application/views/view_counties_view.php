<div id="sub_menu">
<a href="<?php echo  site_url("county_management/add");?>" class="top_menu_link sub_menu_link first_link <?php if($quick_link == "count_management"){echo "top_menu_active";}?>">New County</a>  
 
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
		<th>Province</th>
		<th>Latitude</th>
		<th>Longitude</th>
		<th>Disabled?</th> 
		<th>Action</th>
	</tr>
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
		 
		<td><a href="<?php echo base_url()."county_management/edit_county/".$county->id?>" class="link">Edit </a>| <?php
if($county->Disabled == 0){
		?>
		<a class="link" style="color:red" href="<?php echo base_url()."county_management/change_availability/".$county->id."/1"?>">Disable</a><?php }
			else{
		?>
		<a class="link" style="color:green" href="<?php echo base_url()."county_management/change_availability/".$county->id."/0"?>">Enable</a><?php }?></td>
	</tr>
	<?php }?>
</table>
<?php if (isset($pagination)):
?>
<div style="width:450px; margin:0 auto 60px auto">
	<?php echo $pagination;?>
</div>
<?php endif;?>
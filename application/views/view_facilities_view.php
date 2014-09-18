<?php
if (!isset($sub_link)) {
	$sub_link = "";
}
?>
<div id="sub_menu" style="margin:5px;">
	<a href="<?php echo site_url('facility_management/search_facility');?>" class="top_menu_link sub_menu_link first_link  <?php
	if ($sub_link == "search_facility") {echo "top_menu_active";
	}
	?>">Search Facility</a>
</div>
<?php if (isset($pagination)):
?>
<div style="width:450px; margin:0 auto 60px auto">
	<?php echo $pagination;?>
</div>
<?php endif;?>
<table border="0" class="data-table" style="margin:0 auto;">
	<th class="subsection-title" colspan="11">Facilities</th>
	<tr>
		<th>Facility Code</th>
		<th>Name</th>
		<th>District</th>
		<th>Reporting</th>
		<th>Action</th>
	</tr>
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
		<a class="link" style="color:red" href="<?php echo base_url()."facility_management/change_reporting/".$facility->facilitycode."/0"?>">Not Reporting</a><?php }
			else{
		?>
		<a class="link" style="color:green" href="<?php echo base_url()."facility_management/change_reporting/".$facility->facilitycode."/1"?>">Reporting</a><?php }?></td>
	</tr>
	<?php }?>
</table>
<?php if (isset($pagination)):
?>
<div style="width:450px; margin:0 auto 60px auto">
	<?php echo $pagination;?>
</div>
<?php endif;?>
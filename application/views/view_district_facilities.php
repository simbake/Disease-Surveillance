<?php 
if(!isset($sub_link)){
	$sub_link = "";
}
?>
<?php 
$access_level=$this->session->userdata("user_indicator");
		

?>
<div id="sub_menu" style="margin:5px;">
	<a href="<?php echo site_url('facility_management/new_facility');?>" class="top_menu_link sub_menu_link first_link  <?php
	if ($sub_link == "new_facility") {echo "top_menu_active";
	}
	?>">New Facility</a>
</div>
<?php  if (isset($pagination)): ?>
<div style="width:450px; margin:0 auto 60px auto">
<?php echo $pagination; ?>
</div>
<?php endif; ?>
<table border="0" class="data-table" style="margin:0 auto">
	<th class="subsection-title" colspan="11">Current Facilities</th>
	<tr>
		<th>MFL Code</th> 
		<th>Name</th>		
		<th>District</th>
		<th>Reporting</th>    
	</tr>
 <?php  
 foreach($facilities as $facility){?>
 <tr>
 <td>
 <?php echo $facility->facilitycode;?>
 </td> 
   <td>
 <?php echo $facility->name;?>
 </td>
   <td>
 <?php echo $facility->Parent_District->Name;?>
 </td>
 <td><?php
		if ($facility -> reporting == 0) {echo "No";
		} else {echo "Yes";
		};
		?></td>
 </tr>
 
 <?php }
 ?>
	 
 

</table> 
<?php if (isset($pagination)): ?>
<div style="width:450px; margin:0 auto 60px auto">
<?php echo $pagination; ?>
</div>
<?php endif; ?>
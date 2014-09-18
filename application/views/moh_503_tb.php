<div id="sub_menu">
<a href="<?php echo  site_url("moh_503/add_view");?>" class="top_menu_link sub_menu_link first_link <?php if($quick_link == "add_user"){echo "top_menu_active";}?>">Add MOH 503</a>  
 
</div>
<?php if (isset($pagination)): ?>
<div style="width:450px; margin:0 auto 60px auto">
<?php echo $pagination; ?>
</div>
<?php endif; ?>
<table border="0" class="data-table" style="margin:0 auto ">
	<th class="subsection-title" colspan="19">MOH 503</th>
	<tr>
		<th>Facility</th> 
		<th>District</th>		
		<th>Names</th> 
		<th>Disease</th> 
		<th>Patient</th> 
		<th>Physical Address</th> 
		<th>Phone</th> 
		<th>Age</th> 
		<th>Sex</th>
		<th>Date Seen</th>
		<th>Date of Onset</th>
		<th>Vaccine no.</th>
		<th>Specimen Taken?</th>
		<th>Date Taken</th>
		<th>Specimen Type</th>
		<th>Status</th>
		<th>Results</th>
		<th>Comments</th>
		<th>Submit Date</th>
	</tr>
 <?php 
 foreach($moh as $mo){?>
 <tr>
 <td>
 <?php echo $mo->facilities->name;?>
 </td> 
   <td>
 <?php echo $mo->districts->Name;?>
 </td>
  <td>
 <?php echo $mo->names;?>
 </td>
   <td>
 <?php echo $mo->diseases->Name; ?>
 </td>
   <td>
 <?php echo $mo->patient; ?>
 </td>
   <td>
 <?php echo $mo->physical_add; ?>
 </td>
   <td>
 <?php echo $mo->phone; ?>
 </td>
   <td>
 <?php echo $mo->age; ?>
 </td>
   <td>
 <?php echo $mo->sex; ?>
 </td>
   <td>
 <?php echo $mo->date_seen; ?>
 </td>
   <td>
 <?php echo $mo->date_onset; ?>
 </td>
   <td>
 <?php echo $mo->vaccine_no; ?>
 </td>
   <td>
 <?php echo "<span style='color:green'><strong>".$mo->specimen_taken."</strong></span>"; ?>
 </td>
   <td>
 <?php echo $mo->date_taken; ?>
 </td>
   <td>
 <?php echo $mo->type; ?>
 </td>
   <td>
 <?php echo $mo->status; ?>
 </td>
   <td>
 <?php echo $mo->results; ?>
 </td>
   <td>
 <?php echo $mo->comments ?>
 </td>
 <td>
 <?php echo "<strong>".$mo->submit_date."</strong>" ?>
 </td>
   
 </tr>
 
 <?php }
 ?>
	 
 

</table> 
<?php if (isset($pagination)): ?>
<div style="width:450px; margin:0 auto 60px auto">
<?php echo $pagination; ?>
</div>
<?php endif; ?>
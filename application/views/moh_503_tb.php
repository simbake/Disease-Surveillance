<div id="sub_menu">
	<br/>
<a href="<?php echo  site_url("moh_503/add_view");?>" class='btn btn-info'>Add MOH 503</a>  
 	<br/><br/>
</div>

<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					MOH 503
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
	
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
</div>
</div>
</div>
</div>
</div>
</div>
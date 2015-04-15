<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo $small_title;?>
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">

	<tr>
		<th>Reporting Facility</th>
		<th>Reported By</th>
		<th>Designation</th>
		<th>Entered By</th>
		<th>Date Entered</th>
		<th>Action</th>
	</tr>
	<?php
$report_counter = 0;
foreach($reports as $report){
$rem = $report_counter %2;
$class = "odd";
if($rem == 0){
$class = "even";
}
$editing_link = base_url()."weekly_data_management/edit_weekly_data/".$epiweek."/".$year."/".$report->Facility;
$deleting_link = base_url()."weekly_data_management/delete_weekly_data/".$epiweek."/".$year."/".$report->Facility;
	?>
	<tr class="<?php echo $class;?>">
		<td><?php echo $report -> Facility_Object -> name;?></td>
		<td><?php echo $report -> Reported_By;?></td>
		<td><?php echo $report -> Designation;?></td>
		<td><?php echo $report -> Record_Creator -> Name . " (" . $report -> Record_Creator -> Access -> Level_Name . ")";?></td>
		<td><?php echo $report -> Date_Created;?></td>
		<td><a href="<?php echo $editing_link;?>" class="link">Edit Report</a> <a href="<?php echo $deleting_link;?>" class="link">Delete Report</a></td>
	</tr>
	<?php 
$report_counter++;
}?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
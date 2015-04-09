<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
				Data Deletion Logs
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">

	<tr>
		<th>Deleted By</th>
		<th>Facility Record Affected</th>
		<th>Epiweek</th>
		<th>Reporting Year</th>
		<th>Timestamp</th> 
	</tr>
	<?php
$log_counter = 0;
foreach($logs as $log){
$rem = $log_counter %2;
$class = "odd";
if($rem == 0){
$class = "even";
} 
	?>
	<tr class="<?php echo $class;?>">
		<td><?php echo $log -> Record_Creator -> Name . " (" . $log -> Record_Creator -> Access -> Level_Name . ")";?></td>
		<td><?php echo $log -> Facility_Object->name;?></td>
		<td><?php echo $log -> Epiweek;?></td>
		<td><?php echo $log -> Reporting_Year;?></td>
		<td><?php echo date("l jS \of F Y h:i:s A",$log -> Timestamp);?></td>
	
	</tr>
	<?php 
$log_counter++;
}?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>


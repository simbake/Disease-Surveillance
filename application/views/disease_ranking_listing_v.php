<<<<<<< HEAD

<script>
	
	$(document).ready(function() {
        $('#dataTables-example').dataTable(
		{
		
		}
		);
    });
</script>

<table class="" id="dataTables-example" width="100%">
			<!--<caption>
			<?php //echo $small_title;?>
		</caption>-->
		<thead>
=======
<script>
	$(document).ready(function() {
    $('#example').DataTable();
} );
    </script>
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
>>>>>>> Develop
	<tr>
		<th>Reporting Facility</th>
		<th>District</th>
		<th>Reported By</th>
		<th>Designation</th>
		<th>Entered By</th>
		<th>Date Entered</th>
		<th>Cases</th>
		<th>Deaths</th>
		<th>Action</th>
	</tr>
	</thead>
	
	<tbody>
		<?php
//$report_counter = 0;
foreach($reports as $report){
$editing_link = base_url()."weekly_data_management/edit_weekly_data/".$report->Epiweek."/".$year."/".$report->Facility;
$deleting_link = base_url()."weekly_data_management/delete_weekly_data/".$report->Epiweek."/".$year."/".$report->Facility;
	?>
	<tr>
		<td><?php echo $report -> Facility_Object -> name;?></td>
		<td><?php echo $report -> District_Object -> Name;?></td>
		<td><?php echo $report -> Reported_By;?></td>
		<td><?php echo $report -> Designation;?></td>
		<td><?php echo $report -> Record_Creator -> Name . " (" . $report -> Record_Creator -> Access -> Level_Name . ")";?></td>
		<td><?php echo $report -> Date_Created;?></td>
		<td><?php echo ($report -> Gcase+$report->Lcase);?></td>
		<td><?php echo ($report -> Gdeath+$report->Ldeath);?></td>
		<td><a href="<?php echo $editing_link;?>" class='label label-primary'><span class="glyphicon glyphicon-refresh"></span> Edit Report</a> 
		<a href="<?php echo $deleting_link;?>" class='label label-danger'><span class='glyphicon glyphicon-off'></span>  Delete Report</a></td>
	</tr>
<<<<<<< HEAD
<?php } ?>
	</tbody>

</table>
=======
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
>>>>>>> Develop

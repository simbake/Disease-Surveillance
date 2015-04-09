
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
		<td><a href="<?php echo $editing_link;?>" class="link">Edit Report</a> <a href="<?php echo $deleting_link;?>" class="link">Delete Report</a></td>
	</tr>
<?php } ?>
	</tbody>

</table>
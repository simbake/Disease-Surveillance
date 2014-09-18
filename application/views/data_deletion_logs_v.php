<table class="data-table" style="margin: 5px auto">
			<caption>
			Data Deletion Logs
		</caption>
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

<?php if (isset($pagination)):
?>
<div style="width:450px; margin:0 auto 60px auto">
	<?php echo $pagination;?>
</div>
<?php endif;?>
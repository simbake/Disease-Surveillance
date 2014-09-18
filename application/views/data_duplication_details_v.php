<?php
$total_records = sizeof($surveillance_data);
$initial_recordset_size = $surveillance_data[0]->Total_Diseases;

$record_set_id = 0;
$record_sets = array();
$surveillance_sets = get_record_set(0, $initial_recordset_size, $surveillance_data, $record_set_id, $record_sets, $total_records);

function get_record_set($start_point, $number_of_records, $surveillance_data, $record_set_id, $record_sets, $total_records) {

	$record_set_details = array();
	$end_point = $start_point;
	$record_set_stop = $start_point + $number_of_records;
	$disease_index = 0;
	for ($init = $start_point; $init < $record_set_stop; $init++) {
		$record_set_details[$disease_index] = $surveillance_data[$init];
		$end_point++;
		$disease_index++;
	}
	$record_sets[$record_set_id] = $record_set_details;

	$record_set_id++;

	//If we're not yet at the end, recursively call this function
	if ($end_point < $total_records) {
		$new_recordset_size = $surveillance_data[$end_point]->Total_Diseases;
		$record_sets = get_record_set($end_point, $new_recordset_size, $surveillance_data, $record_set_id, $record_sets, $total_records);
		return $record_sets;
	} 
	return $record_sets;

}
$counter = 0; 
//Loop through all the records sets that have been retrieved
foreach ($surveillance_sets as $set) {
	$malaria_data = $lab_data[$counter];
	$malaria_id = $malaria_data->id;
	$surveillance_id = $set[0]->id;
	if($malaria_data->id == ""){
		$malaria_id = "0";
	}
	if($set[0]->id == ""){
		$surveillance_id = "0";
	}
	$edit_url = base_url()."data_duplication/edit_duplicate/".$set[0] -> Facility."/".$set[0] -> Total_Diseases."/".$surveillance_id."/".$malaria_id;
	$delete_url = base_url()."data_duplication/delete_duplicate/".$set[0] -> Total_Diseases."/".$surveillance_id."/".$malaria_id."/".$set[0]->District."/".$set[0]->Epiweek."/".$set[0]->Reporting_Year;
?>
<style>
	.section {
		border: 1px solid #F1F1F1;
		width: 800px;
		margin: 5px auto;
		padding: 5px;
	}
</style>
<div  class="section" align="center">
	<table class="data-table" style="margin: 10px auto;">
		<tr class="even">
			<td ><strong>Reporting Facility </strong></td>
			<td><?php echo $set[0] -> Facility_Object -> name;?></td>
		</tr>
		<tr class="odd">
			<td ><strong> Reporting Epiweek </strong></td>
			<td><?php echo $set[0] -> Epiweek;?></td>
		</tr>
		<tr class="odd">
			<td ><strong> Reporting Weekending </strong></td>
			<td><?php echo $set[0] -> Week_Ending;?></td>
		</tr>
		<tr class="even">
			<td ><strong> Reporting Year </strong></td>
			<td><?php echo $set[0] -> Reporting_Year;?></td>
		</tr>
		<tr class="odd">
			<td ><strong> Data Entered By </strong></td>
			<td><?php echo $set[0] -> Record_Creator -> Name . " (" . $set[0] -> Record_Creator -> Access -> Level_Name . ")";?></td>
		</tr>
		<tr class="even">
			<td ><strong> Data Entered On </strong></td>
			<td><?php echo $set[0] -> Date_Created;?></td>
		</tr>
	</table>
	<table class="data-table">
		<tr>
			<th rowspan="2">Diseases</th>
			<th colspan="2">&le; 5 Years</th>
			<th colspan="2">&ge; 5 Years</th>
		</tr>
		<tr>
			<th>Cases</th>
			<th>Deaths</th>
			<th>Cases</th>
			<th>Deaths</th>
		</tr> 
		<?php
$disease_counter = 1;
foreach($diseases as $disease){
$rem = $disease_counter %2;
$class = "odd";
if($rem == 0){
$class = "even";
}
		?>
		<tr class="<?php echo $class;?>">
			<td><?php echo $disease -> Name;?></td>
			<td><?php if(isset($set[$disease -> id - 1])){echo $set[$disease -> id - 1] -> Lcase;} else{echo "N/A";}?></td>
			<td><?php if(isset($set[$disease -> id - 1])){echo $set[$disease -> id - 1] -> Ldeath;} else{echo "N/A";}?></td>
			<td><?php if(isset($set[$disease -> id - 1])){echo $set[$disease -> id - 1] -> Ldeath;} else{echo "N/A";}?></td>
			<td><?php if(isset($set[$disease -> id - 1])){echo $set[$disease -> id - 1] -> Ldeath;} else{echo "N/A";}?></td> 
		</tr>
		<?php
		$disease_counter++;
		}
		?>
	</table>
	<table class="data-table" style="margin: 10px auto;">
		<tr class="odd">
			<th >Laboratory Weekly Malaria Confirmation</th>
			<th >&le;5 years</th>
			<th >&ge;5years</th>
		</tr>
		<tr class="even">
			<td ><strong> Total Number Tested </strong></td>
			<td><?php echo $malaria_data -> Malaria_Below_5;?></td>
			<td><?php echo $malaria_data -> Malaria_Above_5;?></td>
		</tr>
		<tr >
			<td ><strong> Total Number Positive </strong></td>
			<td><?php echo $malaria_data -> Positive_Below_5;?></td>
			<td><?php echo $malaria_data -> Positive_Above_5;?></td>
		</tr>
		<tr class="even">
			<td ><strong> Remarks </strong></td>
			<td colspan="2"><?php echo $malaria_data -> Remarks;?></td>
		</tr>
		<tr>
			<td><strong> Reported by </strong></td>
			<td colspan="2"><?php echo $set[0] -> Reported_By;?></td>
		</tr>
		<tr class="even">
			<td ><strong> Designation </strong></td>
			<td colspan="2"><?php echo $set[0] -> Designation;?></td>
		</tr>
	</table>
	<a href="<?php echo $edit_url;?>" class="link">Edit Record</a> -or- <a href="<?php echo $delete_url;?>" class="link">Delete Record</a>
</div>
<?php
$counter++;
}
?>
<?php
$current_year = date('Y');
$earliest_year = $current_year - 5;
?>
<script type="text/javascript">
	$(function() {
		$("#year").change(function() {
			var selected_year = $(this).attr("value");
			//Get the last year of the dropdown list
			var last_year = $(this).children("option:last-child").attr("value");
			//If user has clicked on the last year element of the dropdown list, add 5 more
			if($(this).attr("value") == last_year) {
				last_year--;
				var new_last_year = last_year - 5;
				for(last_year; last_year >= new_last_year; last_year--) {
					var cloned_object = $(this).children("option:last-child").clone(true);
					cloned_object.attr("value", last_year);
					cloned_object.text(last_year);
					$(this).append(cloned_object);
				}
			}
		});
	});

</script>
<style type="text/css">
	#filter {
		border: 2px solid #DDD;
		display: block;
		width: 80%;
		margin: 10px auto;
	}
	.filter_input {
		border: 1px solid black;
	}

</style>

	<?php
	$attributes = array("method" => "POST");
	echo form_open('data_duplication/analyze', $attributes);
	?>
	
	<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					Timeliness Reports
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
	<fieldset>
		<legend>
			Select Filter Options
		</legend>
		<label for="year">Select
			Year</label>
		<select name="year" id="year">
			<?php
for($x=$current_year;$x>=$earliest_year;$x--){
			?>
			<option value="<?php echo $x;?>"
			<?php
			if ($x == $current_year) {echo "selected";
			}
			?>><?php echo $x;?></option>
			<?php }?>
		</select>
		<label for="epiweek">Epiweek</label>
		<select
		name="epiweek">
			<?php
for($x=1;$x<=53;$x++){
			?>
			<option value="<?php echo $x;?>"><?php echo $x;?></option>
			<?php }?>
		</select>
		<input type="submit" name="facility_surveillance" class="button"	value="Analyze facility surveillance duplication in period" />
		<input type="submit" name="surveillance" class="button"	value="Analyze surveillance data duplication in period" />
	</fieldset>
	</table>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	
	</form> <?php
if(isset($duplicates[0]) && $duplicate_content=="facility"){
	?>
	<div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
		<caption>
			<?php echo $small_title;?>
		</caption>
		<th>Facility Code</th>
		<th>Facility Name</th>
		<th>Recordsets</th>
		<th>Action</th>
		<?php
		foreach ($duplicates as $duplicate) {
			$details_url = base_url() . "data_duplication/view_details/" . $epiweek . "/" . $year . "/" . $duplicate -> Facility_Object -> facilitycode;
			echo "<tr><td>" . $duplicate -> Facility_Object -> facilitycode . "</td><td>" . $duplicate -> Facility_Object -> name . "</td><td>" . $duplicate -> Records / $total_diseases . "</td><td><a class='link' href='" . $details_url . "'>View Details</a></td></tr>";
		}
		?>
	</table>
	</div>
	</div>
	
	
	<?php }
else if(isset($duplicates[0]) && $duplicate_content=="surveillance"){ ?>
	
	<div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
		<caption>
			<?php echo $small_title;?>
		</caption>
		<!--<th>Facility Code</th>
		<th>Facility Name</th>-->
		<th>Recordsets</th>
		<th>Action</th>
		<?php
		foreach ($duplicates as $duplicate) {
			//$details_url="";
			$details_url = base_url() . "data_duplication/view_details_surveillance/" . $epiweek . "/" . $year;
			echo "<tr><td>" . $duplicate -> Records / $total_diseases . "</td><td><a class='link' href='" . $details_url . "'>View Details</a></td></tr>";
		}
		?>
	</table>
	</div>
	</div>

			
			
	<?php	}
	
		else if(isset($duplicates) && sizeof($duplicates) == 0){
		echo "<p style='margin:5px'>No duplicates were found in your selection. Change the parameters and try again</p>";
		}
	?>


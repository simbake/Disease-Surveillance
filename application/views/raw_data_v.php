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
	$attributes = array("method"=>"POST");
	echo form_open('raw_data/export',$attributes);
	?>
	<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					Raw Data
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
		<fieldset>
			<legend>
				Select Filter Options
			</legend>
			<label for="year_from">Select
				Year</label>
			<select name="year_from" id="year">
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
			<label for="epiweek_from">Starting Epiweek</label>
			<select
			name="epiweek_from">
				<?php
for($x=1;$x<=53;$x++){
				?>
				<option value="<?php echo $x;?>"><?php echo $x;?></option>
				<?php }?>
			</select>
			-- <label for="epiweek_to">Final Epiweek</label>
			<select
			name="epiweek_to">
				<?php
for($x=53;$x>=1;$x--){
				?>
				<option value="<?php echo $x;?>"><?php echo $x;?></option>
				<?php }?>
			</select>
			<input type="submit" name="surveillance" class="button"	value="Download Surveillance Data" />
			<input type="submit" name="malaria" class="button"	value="Download Malaria Lab Data" />
			<?php $access_level = $this -> session -> userdata('user_indicator');
			  if($access_level == "national_clerk" || $access_level == "system_administrator"){   ?>
			<input type="submit" name="facility_surveillance" id="facility_surveillance" class="button"	value="Download Facility Surveillance" />
			<?php } ?>
			
		</fieldset>
	</form>
	</table>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>


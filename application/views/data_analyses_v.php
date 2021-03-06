<script>
$(function() {
		var chart = new FusionCharts("<?php echo base_url()."assets/Scripts/FusionCharts/Charts/MSLine.swf"?>", "ChartId", "850", "450", "0", "0");
		var url = '<?php echo base_url()."district_analysis_graph/get_cummulative_graph"?>'; 
		chart.setDataURL(url);
		chart.render("immunization_graph_container");
		$("#filter_district").change(function() {
			var selected_district = $(this).attr("value");
				 $('#filter_facility').slideUp('slow', function() {
					    // Animation complete.
					  });
					$.each($("#filter_facility"), function(i, v) {
						$(this).children('option').remove();
						$(this).append($("<option value='0'>All Facilities</option>"));
					});
					$.ajax({
					  url: '<?php echo base_url()."facility_management/get_district_facilities/"?>'+selected_district,
					  success: function(data) {
					  	var json_data = jQuery.parseJSON(data);
					  	$.each(json_data, function() {
					  		var code = this['facility_code'];
					  		var facility_name = this['name']; 
					  		$("#filter_facility").append($("<option></option>").attr("value", code).text(facility_name));
					  	});
					   $('#filter_facility').slideDown('slow', function() {
					    // Animation complete.
					  });
					  	
					  }
					});
		});
		$("#filter_graph").click(function(){
			var vaccine_string = "";
				$(".diseases").each(function(index,item) { 
					if($(this).is(':checked')){
						var vaccine_id = $(this).attr("disease");
				  		vaccine_string += vaccine_id+"-";
					}		 
				});
				var selected_year = $("#filter_year").find(":selected").attr("value");
				var selected_district = $("#filter_district").find(":selected").attr("value");
				var selected_facility = $("#filter_facility").find(":selected").attr("value");
				var selected_type = $("#filter_type").find(":selected").attr("value");
				var start_epiweek = $("#epiweek_from").find(":selected").attr("value");
				var end_epiweek = $("#epiweek_to").find(":selected").attr("value");
				var chart = new FusionCharts("<?php echo base_url()."assets/Scripts/FusionCharts/Charts/MSLine.swf"?>", "ChartId", "850", "450", "0", "0");	
				var url = '<?php echo base_url();?>district_analysis_graph/get_cummulative_graph/'+selected_year+'/'+vaccine_string+'/'+selected_district+'/'+selected_facility+'/'+selected_type+'/'+start_epiweek+'/'+end_epiweek; 
				chart.setDataURL(url);
				chart.render("immunization_graph_container");
		});
});
		
</script>

<script>
	$(document).ready(function() {
    $('#analytics_table').DataTable();
} );
    </script>
    
<br/><br/>
<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-primary">
				<div class="panel-heading">
					Filter Diseases
				</div>

 <div class="panel-body ">
 
        <table  style="margin-left: 0;" id="analytics_table" class="table table-striped table-bordered table-hover" width="100%">
			<tr>
			<?php 
				$counter = 0;
				foreach($diseases as $disease){
					if($counter%8 == 0 && $counter != 0){?> </tr><tr><?php }
					?>
				<td>
				<input type="checkbox" <?php if($disease->Name == 'Malaria'){echo 'checked';}?> class="diseases"  disease="<?php echo $disease->id;?>"/>
				<?php echo $disease->Name;?> </td>								
					
				<?php 
				 $counter++; 
				}
			?>
			
			</tr>
			
		<br/><br/>
	
			<tr>
				


		<b>District:</b>
		
		<select id="filter_district" style="width: 110px;">
			<option value="0">All Districts</option>
			<?php 
foreach($districts as $district){
		if(strlen($district->Name)>0){
			?>
			<option value="<?php echo $district->id;?>"><?php echo $district->Name;?></option>
			<?php 
			}
			}
			?>
		</select>
		<b>Facility:</b>
		<select id="filter_facility" style="width: 110px;">
			<option value="0">All Facilities</option>
		</select>
		<b>Graph Type:</b>
		<select id="filter_type" style="width: 110px;">
			<option value="0">Cummulative Cases</option>
			<option value="1" selected="">Month-on-Month Cases</option>
		</select>
		
		<b>Epi. From:</b>
		<select id="epiweek_from" style="width: 50px;">
						<?php 
$counter = 0;
for($x=0;$x<=53;$x++){
			?>
			<option <?php
			if ($counter == 0) {echo "selected";
			}
			?> value="<?php echo $x;?>"><?php echo $x;?></option>
			<?php
			$counter++; 
			}
			?>
		</select>
				<b>Epi. To:</b>
		<select id="epiweek_to" style="width: 50px;">
						<?php 
$counter = 0;
for($x=53;$x>=0;$x--){
			?>
			
			<option <?php
			if ($counter == 0) {echo "selected";
			}
			?> value="<?php echo $x;?>"><?php echo $x;?></option>
			<?php
			$counter++; 
			}
			?>
		</select>
		
<b>Year:</b>
		<select id="filter_year">
			<?php
$year = date('Y');
$counter = 0;
for($x=0;$x<=10;$x++){
			?>
			<option <?php
			if ($counter == 0) {echo "selected";
			}
			?> value="<?php echo $year;?>"><?php echo $year;?></option>
			<?php
			$counter++;
			$year--;
			}
			?>
			
		</select>
		
		<input type="button" id="filter_graph" value="Filter Graph" class="btn "/>
	<br/><br/><br/>
	</tr>
	
		</table>

</div>
</div>
</div>
</div>
</div>
</div>
	
	
	<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-success">
				<div class="panel-heading">
					Immunization Graph
				</div>
	<div id = "immunization_graph_container"></div>
</div>
</div>
</div>

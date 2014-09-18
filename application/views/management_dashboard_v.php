<script type="text/javascript">
$(function() {
	$("#larger_daily_purchases_graph_container").dialog( {
			height: 520,
			width: 980,
			modal: true,
			autoOpen: false
	});
	$("#date_from").datepicker({
			defaultDate : new Date(),
			changeYear : true,
			changeMonth : true,
			dateFormat: 'dd-mm-yy'
	});
	$("#date_to").datepicker({
			defaultDate : new Date(),
			changeYear : true,
			changeMonth : true,
			dateFormat: 'dd-mm-yy'
	});
	var chart = new FusionCharts("<?php echo base_url()."Scripts/FusionCharts/Charts/MSLine.swf"?>", "daily_trend", "970", "600", "0", "0");
	var url = '<?php echo base_url()."submissions_management/getTrend/0"?>'; 
	chart.setDataURL(url);
	chart.render("activity_graph");
	$("#filter_graph").click(function(){ 
				var date_from = $("#from").attr("value");
				var date_to = $("#to").attr("value"); 
				var disease = $("#disease").attr("value");
				var year = $("#year").attr("value");  
				var chart = new FusionCharts("<?php echo base_url()."Scripts/FusionCharts/Charts/MSLine.swf"?>", "ChartId", "900", "600", "0", "0");	
				var url = '<?php echo base_url()?>submissions_management/getTrend/'+disease+'/'+date_from+'/'+date_to+'/'+year+'';
				console.log(url); 
				chart.setDataURL(url);
				chart.render("activity_graph");
	});
});
</script>
<style>
	.top_graphs_container{
		width:980px;
		margin: 0 auto;
		overflow: hidden;
	}
	.graph{
		width:970px;  
	}
	.graph_title{
		letter-spacing: 1px;
		font-size: 10px;
		font-weight: bold;
		margin: 0 auto;
		width:300px;
	}
	#notifications_panel{
		width:100%;
	}
	.message{
		width:300px;
	}
	.notification_link{
		text-decoration: none;
		float:left;
		margin: 5px;
	}
	h2{
		margin-left:100px; 
	}
	.disease_container{  
		float:left;
		position: relative;
		padding: .5em 0;
	}
	ul.diseases {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	.disease-text {
		margin-right: 0; 
		line-height: 1.3;
		margin-right: 1.75em;
		display: inline-block;
		}
</style>
<div class="top_graphs_container">
<div class="graph">
<h2>Daily Activity</h2> 
<div id="daily_rg_filter">
	
	<fieldset style="width:900px; display: inline;"><legend>Disease to Analyse</legend>
		
		<select id="disease">
		<?php 
		foreach($diseases as $disease){?>
			<option value="<?php echo $disease['id']?>"><?php echo $disease['Name']?></option>
		<?php }
		?>
	</select>

	
	<b>From: </b>	<select id="from">
		<?php 
			for($x=1; $x<=52; $x++){ ?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
		<?php }
		?>
	</select> <b>To: </b>	<select id="to">
		<?php 
			for($x=52; $x>=1; $x--){ ?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
		<?php }
		?>
	</select>
	<b>Year: </b><select id="year">
	<?php  
		$year = date('Y');
		$counter = 0;
		for($x=0;$x<=10;$x++){ ?>
			<option <?php if($counter == 0){echo "selected";}?> value="<?php echo $year;?>"><?php echo $year;?></option>
			
		<?php 
		$counter++;
		$year--;
		}
	?>
	</select>
	<input type="button" id="filter_graph" value="Filter Graph" class="button"/>
	</fieldset>
	
</div>
<div id = "activity_graph" title="Daily Activity Graph" ></div>
</div>

</div>
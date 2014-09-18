<?php
error_reporting(E_ALL^E_NOTICE);
$this -> load -> helper('url');
?>

<script type="text/javascript">
	$(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId", "750", "500", "0", "0");
	chart.setDataURL('<?php echo base_url() . 'elnino_graphs/ORS/'.$values[0]; ?>');
	chart.render("ORSgraph");
	});


$(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId1", "750", "500", "0", "0");
    chart.setDataURL('<?php echo base_url() . 'elnino_graphs/IV/'.$values[0]; ?>');
    chart.render("IVgraph");
    });
    
    
    $(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId2", "750", "500", "0", "0");
    chart.setDataURL('<?php echo base_url() . 'elnino_graphs/antimalarial/'.$values[0]; ?>');
    chart.render("antimalarialgraph");
    });
    
    $(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId3", "750", "500", "0", "0");
    chart.setDataURL('<?php echo base_url() . 'elnino_graphs/steering/'.$values[0]; ?>');
    chart.render("steeringgraph");
    });
    
    
    $(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId4", "750", "500", "0", "0");
    chart.setDataURL('<?php echo base_url() . 'elnino_graphs/cholera/'.$values[0]; ?>');
    chart.render("choleragraph");
    });
    
    
    $(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId5", "750", "500", "0", "0");
    chart.setDataURL('<?php echo base_url() . 'elnino_graphs/malaria_positivity/'.$values[0]; ?>');
    chart.render("malaria_positivitygraph");
    });
    
    
    $(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId6", "750", "500", "0", "0");
    chart.setDataURL('<?php echo base_url() . 'elnino_graphs/rain/'.$values[0]; ?>');
    chart.render("raingraph");
    });
    
    
    $(function(){
var chart = new FusionCharts('<?php echo base_url().'Scripts/FusionCharts/Charts/Pie2D.swf'?>', "ChartId7", "750", "500", "0", "0");
    chart.setDataURL('<?php echo base_url() . 'elnino_graphs/floods/'.$values[0]; ?>');
    chart.render("floodsgraph");
    });
    
</script>

<table>
    <tr>
        <?php echo form_open('elnino_graphs/index');?>
        <td>Epiweek
            <select name="epiweek" id="epiweek">
                <option value="0" selected>--Select Epiweek--</option>
                <?php
                foreach ($epiweeks as $epiweek) {
                    echo "<option value='$epiweek->Epiweek'>$epiweek->Epiweek</option>";
                }
                ?>
            </select><input name="filter" type="submit" class="button" value="Filter"/></td>
            <?php echo form_close();?>
    </tr>
	<tr>
		<td><div id="ORSgraph"></div></td>
		<td><div id="IVgraph"></div></td>
	</tr>

<tr>
        <td><div id="antimalarialgraph"></div></td>
        <td><div id="steeringgraph"></div></td>
    </tr>
    
    <tr>
        <td><div id="choleragraph"></div></td>
        <td><div id="malaria_positivitygraph"></div></td>
    </tr>
    
    <tr>
        <td><div id="raingraph"></div></td>
        <td><div id="floodsgraph"></div></td>
    </tr>
</table>
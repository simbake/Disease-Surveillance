<?php
error_reporting(0);
$this -> load -> helper('url');
$this -> load -> helper('form');
?>

<table>
	<tr>
		<?php echo form_open('elnino_districts/dave'); ?>
		<td>Epiweek
		<select name="epiweek" id="epiweek">
			<option value="0" selected>--Select Epiweek--</option>
			<?php
            foreach ($epiweeks as $epiweek) {
                echo "<option value='$epiweek->Epiweek'>$epiweek->Epiweek</option>";
            }
			?>
		</select></td>
		<td>Province
		<select name="province" id="province">
			<option value="0" selected>--Select Province--</option>
			<?php
            foreach ($provinces as $province) {
                echo "<option value='$province->id'>$province->Name</option>";
            }
			?>
		</select></td>
		<td>
		<input name="filter" type="submit" class="button" value="Filter"/>
		</td>
		<?php echo form_close(); ?>
	</tr>
</table>
<div id="buffers">
	<table class="data-table">
		<tr>
			<th colspan="3">Buffers</th>
		</tr>
		<tr>
			<th>District Name</th>
			<th>Adequate Buffer Stock of ORS available</th>
			<th>Adequate Buffer Stock of IV fluids available</th>
		</tr>
		<?php foreach($buffers as $buffer){
		?>
		<tr>
			<td> <?php echo $buffer['name']; ?> </td>
			<td>
			<?php
            if ($buffer['buffer_ors'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            };
			?></td>
			<td> <?php
            if ($buffer['buffer_iv'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?> 
			</td>
		</tr>
		<?php } ?>
	</table>
</div>

<div id="drugs">
    <table class="data-table">
        <tr>
            <th colspan="3">Drugs</th>
        </tr>
        <tr>
            <th>District Name</th>
            <th>Month district received last Kemsa drugs</th>
            <th>Do all governement hospitals have  antimalarial drugs available</th>
        </tr>
        <?php foreach($buffers as $buffer){
        ?>
        <tr>        
        <td> <?php echo $buffer['name']; ?> </td>
        <td> <?php echo $buffer['drug_month']; ?> </td>    
        <td>
            <?php
            if ($buffer['antimalarial'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?> 
        </td>    
        </tr>
        <?php } ?>
    </table>
</div>

<div id="districtstuff">
    <table class="data-table">
        <tr>
            <th colspan="3">District Information</th>
        </tr>
        <tr>
            <th>District Name</th>
            <th>Has District Steering Group started meeting to discuss Elnino</th>
            <th>Any confirmed cholera cases reported  in the last 7 days</th>
            <th>Any upsurge in malaria positivity rate in the last 2 weeks</th>
        </tr>
        <?php foreach($buffers as $buffer){
        ?>
        <tr>        
        <td> <?php echo $buffer['name']; ?> </td>
        <td>
            <?php
            if ($buffer['steering_group'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?> 
        </td>
        <td>
            <?php
            if ($buffer['cholera'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?> 
        </td>    
        <td>
            <?php
            if ($buffer['malaria_positivity'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?> 
        </td>
        </tr>
        <?php } ?>
    </table>


</div>

<div id="rain">
    <table class="data-table">
        <tr>
            <th colspan="3">Rains and Floods</th>
        </tr>
        <tr>
            <th>District Name</th>
            <th>Any rain in the district last 7 days</th>
            <th>Any floods in the last 7 days</th>
            <th>Cumuative number of displaced persons due to rains/ floods</th>
            <th>Cumulative number of deaths due to floods/rain</th>
            <th>Current outbreak in the district</th>
        </tr>
        <?php foreach($buffers as $buffer){
        ?>
        <tr>        
        <td> <?php echo $buffer['name']; ?> </td>
        <td>
            <?php
            if ($buffer['rain'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?> 
        </td>
        <td>
            <?php
            if ($buffer['floods'] == 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?> 
        </td>    
        <td><?php echo $buffer['displaced_persons']; ?></td>
        <td><?php echo $buffer['deaths']; ?></td>
        <td><?php echo $buffer['outbreak_name']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
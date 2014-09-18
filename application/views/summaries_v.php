<table class="data-table" style="margin: 5px auto">
	<tr>
		<th>Districts with adequate buffer stock of ORS available</th><td><?php echo $buffers; ?></td>
	</tr>
	<tr>
		<th>Districts without adequate buffer stock of ORS available </th><td><?php echo $total_districts - $buffers; ?></td>
	</tr>
	<tr>
		<th>Districts with adequate buffer stock of fluids available </th><td><?php echo $ivs; ?></td>
	</tr>
	<tr>
		<th>Districts without adequate buffer stock of fluids available </th><td><?php echo $total_districts - $ivs; ?></td>
	</tr>
	<tr>
		<th>Districts with antimalarial drugs available in all government hospitals? </th><td><?php echo $antimalarial; ?></td>
	</tr>
	<tr>
        <th>Districts without antimalarial drugs available in some government  hospitals? 
 </th><td><?php echo $total_districts - $antimalarial; ?></td>
    </tr>
    <tr>
        <th>Districts that District Steering Group has started meeting to discuss Elnino 
 </th><td><?php echo $steering_group; ?></td>
    </tr>
    <tr>
        <th>Districts that District Steering Group has not started meeting to discuss Elnino 
</th><td><?php echo $total_districts - $steering_group; ?></td>
    </tr>
    <tr>
        <th>Districts that confrimed cholera cases have been reported  in the last 7 days? 
 </th><td><?php echo $cholera; ?></td>
    </tr>
    <tr>
        <th>Districts that confirmed cholera cases have not been reported  in the last 7 days? 
</th><td><?php echo $total_districts - $cholera; ?></td>
    </tr>
    <tr>
        <th>District with upsurge in malaria positivity in the last 7 days
 </th><td><?php echo $malaria_positivity; ?></td>
    </tr>
    <tr>
        <th>District without upsurge in malaria positivity in the last 7 days
 </th><td><?php echo $total_districts - $malaria_positivity; ?></td>
    </tr>
    <tr>
        <th>Districts with rain in the last 7 days
 </th><td><?php echo $rain; ?></td>
    </tr>
    <tr>
        <th>Districts without rain in the last 7 days
 </th><td><?php echo $total_districts - $rain; ?></td>
    </tr>
    <tr>
        <th>Districts with floods in the last 7 days 
 </th><td><?php echo $floods; ?></td>
    </tr>
    <tr>
        <th>Districts without floods in the last 7 days 
 </th><td><?php echo $total_districts - $floods; ?></td>
    </tr>
    <tr>
<th>Cumuative number of displaced persons due to rains/ floods? </th><td><?php echo $displaced_persons; ?></td>
		</tr>
		    <tr>
<th>Cumulative number of deaths due to floods/rain
</th><td><?php echo $deaths; ?></td>
        </tr>
</table>
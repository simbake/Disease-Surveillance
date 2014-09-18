<div class="message information" style="margin:5px auto; width:700px;">
							<h2>Note!</h2>
							<p>Click on the name of any facility to 'move' it to your list of facilities. This change will be immediate</p>
						</div>
<div>
	<table border="0" class="data-table" style="margin: 0 auto">
		<th class="subsection-title" colspan="11">Search Results for '<?php echo $search_term;?>'</th>
		<tr>
			<th>MFL Code</th>
			<th>Facility Name</th>
			<th>Current District</th>
		</tr>
		<?php
foreach($facilities as $facility){
		?>
		<tr>
			<td><?php echo $facility->facilitycode?></td>
			<td><a class="link" href="<?php echo site_url("facility_management/change_ownership/" . $facility -> facilitycode);?>" ><?php echo $facility->name?></a>
			</td> <td><?php echo $facility->Parent_District->Name;?></td>
			</td>
		</tr>
		<?php }?>
	</table>
</div>
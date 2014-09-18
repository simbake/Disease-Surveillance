<div id="sub_menu">
	<a href="<?php echo site_url("user_management/listing");?>" class="top_menu_link sub_menu_link first_link <?php
	if ($quick_link == "add_user") {echo "top_menu_active";
	}
?>">&lt; &lt; Listing</a>
</div>
<script type="text/javascript">
	$(document).ready(function() {county_selector
	$("#region_selector").css("display", "none");
				$("#district_selector").css("display", "none");
				$("#county_selector").css("display", "none");
		$(".user_group").change(function() {
			var identifier = $(this).find(":selected").attr("usergroup");
			if(identifier == "provincial_clerk") {
				$("#district_selector").css("display", "none");
				$("#region_selector").css("display", "table-row");
				$("#county_selector").css("display", "none");
			} else if(identifier == "district_clerk") {
				$("#region_selector").css("display", "none");
				$("#district_selector").css("display", "table-row");
				$("#county_selector").css("display", "none");
			}
else if(identifier == "county_clerk"){
$("#county_selector").css("display", "table-row");
$("#region_selector").css("display", "none");
$("#district_selector").css("display", "none");
}			
			else {
				$("#region_selector").css("display", "none");
				$("#district_selector").css("display", "none");
				$("#county_selector").css("display", "none");
			}
		});
	});

</script>
<?php
if (isset($user)) {
	$name = $user -> Name;
	$district_province_id = $user -> District_Or_Province;
	$user_group = $user -> Access_Level;
	$username = $user -> Username;
	$user_id = $user -> id;
	$user_can_download_raw_data = $user -> Can_Download_Raw_Data;
	$user_can_delete = $user -> Can_Delete;
} else {
	$name = "";
	$district_province_id = "";
	$user_group = "";
	$username = "";
	$user_id = "";
	$user_can_download_raw_data = "0";
	$user_can_delete = "0";

}
$attributes = array('enctype' => 'multipart/form-data');
echo form_open('user_management/save', $attributes);
echo validation_errors('
<p class="error">', '</p>
');
?>
<input type="hidden" name="user_id" value = "<?php echo $user_id;?>"/>
<table border="0" class="data-table" style="margin:0 auto">
	<tr>
		<th class="subsection-title" colspan="2">User Details</th>
	</tr>
	<tbody>
		<tr>
			<td><span class="mandatory">*</span> Full Name</td>
			<td><?php

			$data_search = array('name' => 'name', 'value' => $name);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td><span class="mandatory">*</span> Username</td>
			<td><?php

			$data_search = array('name' => 'username', 'value' => $username);
			echo form_input($data_search);
			?></td>
		</tr>
		<tr>
			<td>User Can Download Raw Data?</td>
			<td>
			<input type="radio" name="user_can_download_raw_data" value="0" <?php if($user_can_download_raw_data == "0"){echo "checked = ''";}?>/> No<br />
			<input type="radio" name="user_can_download_raw_data" value="1" <?php if($user_can_download_raw_data == "1"){echo "checked = ''";}?>/> Yes<br />
			</td>
		</tr>
		<tr>
			<td> User Can Delete Records?</td>
			<td>
			<input type="radio" name="user_can_delete" value="0" <?php if($user_can_delete == "0"){echo "checked = ''";}?>/> No<br />
			<input type="radio" name="user_can_delete" value="1" <?php if($user_can_delete == "1"){echo "checked = ''";}?>/> Yes<br /></td>
		</tr>
		<tr>
			<td> User Group</td>
			<td>
			<select name="user_group" class="user_group">
				<option value=''>None Selected</option>
				<?php
foreach($levels as $level){
				?>
				<option value="<?php echo $level->id?>" usergroup="<?php echo $level->Indicator?>" <?php
				if ($level -> id == $user_group) {echo "selected";
				}
				?> ><?php echo $level->Level_Name
					?></option>
				<?php }?>
			</select></td>
		</tr>
		<tr id="region_selector">
			<td> Province</td>
			<td>
			<select name="province" >
				<option value="">None Selected</option>
				<?php
foreach($provinces as $province){
				?>
				<option value="<?php echo $province->id?>" <?php
				if ($province -> id == $district_province_id) {echo "selected";
				}
				?> ><?php echo $province->Name
					?></option>
				<?php }?>
			</select></td>
		</tr>
		<tr id="district_selector">
			<td> District</td>
			<td>
			<select name="district" >
				<option value="">None Selected</option>
				<?php
foreach($districts as $district){
				?>
				<option value="<?php echo $district->id?>" <?php
				if ($district -> id == $district_province_id) {echo "selected";
				}
				?> ><?php echo $district->Name
					?></option>
				<?php }?>
			</select></td>
		</tr>
		<tr id="county_selector">
			<td> County</td>
			<td>
			<select name="county" >
				<option value="">None Selected</option>
				<?php
				foreach($counties as $county){
				?>
				<option value="<?php echo $county->id  ?>"><?php echo $county->Name ?></option>
			<?php } ?>
			</select></td>
		</tr>
		
		<tr>
			<td align="center" colspan=2>
			<input name="submit" type="submit"
			class="button" value="Save User">
			</td>
		</tr>
	</tbody>
</table>
<?php echo form_close();?>
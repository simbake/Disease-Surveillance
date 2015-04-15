<script type="text/javascript">
	$(document).ready(function() {
           $("#province").change(function() {
                //Get the selected province
                var province = $(this).attr("value");
                $("#county").children('option').remove();
                $.each($("#county_container").children('option'), function(i, v) {
                    var current_province = $(this).attr("province");
                    if(current_province == province) {
                        $("#county").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
                    } else if(province == 0) {
                        $("#county").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
                    }
                });
            });
        });
</script>
<script type="text/javascript">
	$(document).ready(function() {
            $("#county").change(function() {
                //Get the selected county
                var county = $(this).attr("value");
                $("#district").children('option').remove();
                $.each($("#district_container").children('option'), function(i, v) {
                    var current_county = $(this).attr("county");
                    if(current_county == county) {
                        $("#district").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
                    } else if(county == 0) {
                        $("#district").append($("<option></option>").attr("value", $(this).attr("value")).text($(this).text()));
                    }
                });
            });
        });
</script>

<script type="text/javascript">
    function validateForm(){
			var facilityName=document.forms["facilityData"]["facility_name"].value;
                        var facilityType=document.forms["facilityData"]["facility_type"].value;
                        var facilityDistrict=document.forms["facilityData"]["district"].value;
                        var facilityCounty=document.forms["facilityData"]["county"].value;
                        var facilityProvince=document.forms["facilityData"]["province"].value;
                        var facilityLongitude=document.forms["facilityData"]["facility_longitude"].value;
                        var facilityLatitude=document.forms["facilityData"]["facility_latitude"].value;
                        
                        if ((facilityName==null || facilityName=="")){
			  alert("Please enter a facility name.");
			  document.forms["facilityData"]["facility_name"].focus();
			  return false;
			}
                        if ((facilityType==null || facilityType=="0")){
			  alert("Please select a facility type.");
			  document.forms["facilityData"]["facility_type"].focus();
			  return false;
			}
                        if ((facilityDistrict==null || facilityDistrict=="0")){
			  alert("Please select a district.");
			  document.forms["facilityData"]["district"].focus();
			  return false;
			}
                        if ((facilityCounty==null || facilityCounty=="0")){
			  alert("Please select a county.");
			  document.forms["facilityData"]["county"].focus();
			  return false;
			}
                        if ((facilityProvince==null || facilityProvince=="0")){
			  alert("Please select a province/region.");
			  document.forms["facilityData"]["province"].focus();
			  return false;
			}
                        if ((facilityLongitude==null || facilityLongitude=="")){
			  alert("Please enter a longitude value.");
			  document.forms["facilityData"]["facility_longitude"].focus();
			  return false;
			}
                        if ((facilityLatitude==null || facilityLatitude=="")){
			  alert("Please enter a latitude value.");
			  document.forms["facilityData"]["facility_latitude"].focus();
			  return false;
			}
    }
</script>
    
<?php
$attributes = array('id' => 'facilityData','onsubmit'=>'return validateForm();');
    if($task == 1){
        //save new facility
        echo form_open('zoonotic_data_administration/save_facility', $attributes);
    }elseif($task == 2){
        //edit facility
        echo form_open('zoonotic_data_administration/save_edit_facility/'.$facilityID.'/'.$districtID.'/'.$districtName, $attributes);
    }
?>
<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					Facility Details
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
        	
    
            <tbody>
                <?php if($task == 1){
                echo "<tr'>
                        <td><span class='mandatory'>*</span>Facility Code</td>
                        <td>";?>
                            <?php
                                    echo "<input id='facility_code' name ='facility_code' type='text' value =''></input>";
                            ?>
                        <?php echo "</td>
                    </tr>";}elseif($task == 2){
                        echo "<tr style='display: none;'>
                        <td><span class='mandatory'>*</span>Facility Code</td>
                        <td>";?>
                            <?php
                                    echo "<input id='facility_code' name ='facility_code' type='text' value =''></input>";
                            ?>
                        <?php echo "</td>
                    </tr>";
                    }?>
                    <tr>
                        <td><span class="mandatory">*</span> Facility Name</td>
                        <td>
                            <?php
                                if(isset($facilityName)){
                                    echo "<input id='facility_name' name ='facility_name' type='text' value ='".urldecode($facilityName)."'></input>";
                                }else{
                                    echo "<input id='facility_name' name ='facility_name' type='text' value =''></input>";
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Facility Type</td>
                        <td>
                            <select id="facility_type" name="facility_type">
                                        <option value="0" <?php if($facilityType == 0){?>selected<?php }; ?>>Select Facility Type</option>
                                        <?php
                                        foreach ($facility_types as $facility_type) {
                                                echo "<option value='$facility_type->id'";
                                                if($facilityType == $facility_type->id){
                                                    echo " selected ";   
                                                    };
                                                echo ">$facility_type->Name</option>";
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Province/Region</td>
                        <td>
                            <select id="province" name="province">
                                        <option value="0" <?php if($districtProvince == 0){?>selected<?php }; ?>>Select Province</option>
                                        <?php
                                        foreach ($provinces as $province) {
                                                echo "<option value='$province->id'";
                                                if($facilityProvince == $province->id){
                                                    echo " selected ";   
                                                    };
                                                echo ">$province->Name</option>";
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>County</td>
                        <td>
                            <select id="county" name="county">
                                        <option value="0" <?php if($facilityCounty == 0){?>selected<?php }; ?>>Select County</option>
                                        <?php
                                        foreach ($counties as $county) {
                                                echo "<option value='$county->id'";
                                                if($facilityCounty == $county->id){
                                                    echo " selected ";   
                                                    };
                                                echo ">$county->County</option>";
                                        }
                                        ?>
                            </select>
                            <select id="county_container" style="display: none">
				<option value="">Select County</option>
				<?php
				foreach ($counties as $county) {
                                    echo '<option province="'. $county -> province_id .'" value="' . $county -> id . '">' . $county -> County . '</option>';
				}
				?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td>Sub County</td>
                        <td>
                            <select id="district" name="district">
                                        <option value="0" <?php if($facilityDistrict == 0){?>selected<?php }; ?>>Select Sub County</option>
                                        <?php
                                        foreach ($districts as $district) {
                                                echo "<option value='$district->id'";
                                                if($facilityDistrict == $district->id){
                                                    echo " selected ";   
                                                    };
                                                echo ">$district->Name</option>";
                                        }
                                        ?>
                            </select>
                            <select id="district_container" style="display: none">
				<option value="">Select Sub County</option>
				<?php
                                    foreach ($districts as $district) {
                                        echo '<option value="' . $district -> id . '" county="'. $district -> county .'">' . $district -> Name . '</option>';
//                                        echo "<option value='$district->id'";
//                                        echo "county='$district->county'";
//                                        echo">$district->Name</option>";
                                    }
                                    ?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td>Facility Longitude</td>
                        <td>
                            <?php
                                if(isset($facilityLongitude)){
                                    echo "<input id='facility_longitude' name ='facility_longitude' type='number' value ='".urldecode($facilityLongitude)."'></input>";
                                }else{
                                    echo "<input id='facility_longitude' name ='facility_longitude' type='number' value =''></input>";
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Facility Latitude</td>
                        <td>
                            <?php
                                if(isset($facilityLatitude)){
                                    echo "<input id='facility_latitude' name ='facility_latitude' type='number' value ='".urldecode($facilityLatitude)."'></input>";
                                }else{
                                    echo "<input id='facility_latitude' name ='facility_latitude' type='number' value =''></input>";
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan=2>
                            <input name="submit" type="submit"
                            class="button" value="<?php
                            if($task == 1){
                                echo "Save Facility";   
                            }elseif($task == 2){
                                echo "Edit Facility"; 
                            };
                            ?>">
                        </td>
                    </tr>
            </tbody>
    </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    

<?php echo form_close();?>
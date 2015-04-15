<link href="<?php echo base_url().'CSS/messi.min.css'?>" type="text/css" rel="stylesheet"/>
<script src="<?php echo base_url().'Scripts/messi.min.js'?>"  type="text/javascript"></script>

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
			var labName=document.forms["labData"]["lab_name"].value;
			var labType=document.forms["labData"]["labType"].value;
                        var email=document.forms["labData"]["email"].value;
                        var atpos=email.indexOf("@");
                        var dotpos=email.lastIndexOf(".");
                        
                        if ((labName==null || labName=="")){
			  alert("Please enter the lab name.");
			  document.forms["labData"]["lab_name"].focus();
			  return false;
			}
			if ((labType==null || labType=="0")){
			  alert("Please select a lab type.");
			  document.forms["labData"]["labType"].focus();
			  return false;
			}
                        if(email!=""){
                            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length){
                                alert("Not a valid e-mail address");
                                document.forms["labData"]["email"].focus();
                                return false;
                            }
                        }else{
                            
                        }
    }
</script>

<?php
if ($result == 2) {?>
    <script type="text/javascript">
        $(function(){
            new Messi('The lab details have been edited successfully.',{title:'Lab Details Edited', titleClass: 'success',modal:true, buttons: [{id: 0,label:'Close',val:'X'}]});
        });
    </script>
<?php
}elseif($result == 1){?>
    <script type="text/javascript">
        $(function(){
            new Messi('Please enter the lab name.',{title:'Lab Name Missing', titleClass: 'anim error',modal:true, buttons: [{id: 0,label:'Close',val:'X'}]});
        });
    </script>
<?php
}elseif($result == 3){?>
    <script type="text/javascript">
        $(function(){
            new Messi('The new lab has been successfully added.',{title:'New Lab Added', titleClass: 'success',modal:true, buttons: [{id: 0,label:'Close',val:'X'}]});
        });
    </script>
<?php
}
?>

<?php
    $attributes = array('id' => 'labData','onsubmit'=>'return validateForm();');
    if ($task == 1){
        //save
        echo form_open('zoonotic_data_administration/save_lab', $attributes);
    }elseif($task == 2){
        //edit
        echo form_open('zoonotic_data_administration/save_lab_edit/'.$labID, $attributes);
    }
?>
<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					Lab Details
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
        	
	<tbody>
		<tr>
			<td><span>*</span> Lab Name</td>
			<td>
                            <?php
                            if(isset($lab)){
                                echo "<input id='lab_name' name ='lab_name' type='text' value ='".urldecode($lab)."'></input>";
                            }else{
                                echo "<input id='lab_name' name ='lab_name' type='text' value =''></input>";
                            }
                            ?>
                        </td>
		</tr>
		<tr>
			<td> Lab Type</td>
			<td>
			<select id="labType" name="labType">
                            <option value="0" <?php if($labType == 0){?>selected<?php }; ?>>   Select Lab Type   </option>
                            <option value="1" <?php if($labType == 1){?>selected<?php }; ?>>Provincial Lab</option>
                            <option value="2" <?php if($labType == 2){?>selected<?php }; ?>>District Lab</option>
                            <option value="3" <?php if($labType == 3){?>selected<?php }; ?>>Private Lab</option>
                            <option value="4" <?php if($labType == 4){?>selected<?php }; ?>>Research Lab</option>
                        </select>
                        </td>
		</tr>
		<tr id="region_selector">
			<td> Province/Region</td>
			<td>
			<select id="province" name="province">
                                    <option value="0" <?php if($labProvince == 0){?>selected<?php }; ?>>Select Province</option>
                                    <?php
                                    foreach ($provinces as $province) {
                                            echo "<option value='$province->id'";
                                            if($labProvince == $province->id){
                                                 echo " selected ";   
                                                };
                                            echo ">$province->Name</option>";
                                    }
                                    ?>
                        </select>
                        </td>
		</tr>
                <tr>
                    <td><p id ="count">County</td>
                    <td>
                            <select id="county" name="county">
                                    <option value="0" <?php if($labCounty == 0){?>selected<?php }; ?>>Select County</option>
                                    <?php
                                    foreach ($counties as $county) {
                                        echo '<option province="'. $county -> province_id .'" value="' . $county -> id . '"';
                                            if($labCounty == $county -> id){
                                                 echo " selected "; 
                                                        
                                                };
                                        echo ">$county->County</option>";
//                                        echo '<option province="'. $county -> province_id .'" value="' . $county -> id . '">' . $county -> County . '</option>';
                                    }
                                    ?>
                            </select>
                            </p>
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
		<tr id="district_selector">
			<td><p id ="dist">Sub County</td>
                        <td>
                            <select id="district" name="district">
                                    <option value="0" <?php if($labDistrict == 0){?>selected<?php }; ?>>Select Sub County</option>
                                    <?php
                                    foreach ($districts as $district) {
                                        echo '<option county="'. $district -> county .'" value="' . $district -> id . '"';
                                            if($labDistrict == $district -> id){
                                                 echo " selected "; 
                                                        
                                                };
                                        echo ">$district->Name</option>";
//                                        echo '<option value="' . $district -> id . '" county="'. $district -> county .'">' . $district -> Name . '</option>';
                                    }
                                    ?>
                            </select>
                            </p>
                            <select id="district_container" style="display: none">
				<option value="">Select Sub County</option>
				<?php
                                    foreach ($districts as $district) {
                                        echo '<option value="' . $district -> id . '" county="'. $district -> county .'">' . $district -> Name . '</option>';
                                    }
                                    ?>
                            </select> 
                        </td> 
                        </tr>
                        <tr>
                            <td>
                                <p>Web site</p>
                            </td>
                            <td>
                                <?php
                                if(isset($website)){
                                    if($website == "0"){
                                        echo "<input id='website' name ='website' type='text' value =''></input>";
                                    }else{
                                        echo "<input id='website' name ='website' type='text' value ='".$website."'></input>";
                                    }
                                }else{
                                    echo "<input id='website' name ='website' type='text' value =''></input>";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Email Address
                            </td>
                            <td>
                                <?php
                                if(isset($email)){
                                    if($email == "0"){
                                        echo "<input id='email' name ='email' type='text' value =''></input>";
                                    }else{
                                        echo "<input id='email' name ='email' type='text' value ='".$email."'></input>";
                                    }
                                }else{
                                    echo "<input id='email' name ='email' type='text' value =''></input>";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Phone Number
                            </td>
                            <td>
                                <?php
                                if(isset($phone_num)){
                                    if($phone_num == "0"){
                                        echo "<input id='phoneNum' name ='phoneNum' type='number' value =''></input>";
                                    }
                                    else{
                                        echo "<input id='phoneNum' name ='phoneNum' type='number' value ='".$phone_num."'></input>";
                                    }
                                }else{
                                    echo "<input id='phoneNum' name ='phoneNum' type='number' value =''></input>";
                                }
                                ?>
                            </td>
                        </tr>
		<tr>
			<td align="center" colspan=2>
			<input name="submit" type="submit"
			class="button" value="<?php
                            if($task == 1){
                                echo "Save Lab";   
                            }elseif($task == 2){
                                echo "Edit Lab"; 
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
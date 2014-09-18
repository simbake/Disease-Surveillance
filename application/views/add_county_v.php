<script src="<?php echo base_url().'Scripts/jquery-1.8.0.min.js'?>"  type="text/javascript"></script>
<script src="<?php echo base_url().'Scripts/jquery-ui-1.8.23.custom.min.js'?>"  type="text/javascript"></script>
 
<script src="<?php echo base_url().'Scripts/jquery.datepick.js'?>" type="text/javascript"></script>

<link href="<?php echo base_url().'CSS/jquery.datepick.css'?>" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url().'CSS/ui-lightness/jquery-ui-1.8.23.custom.css'?>"  type="text/css" rel="stylesheet" />

<script type="text/javascript">
    function validateForm(){
			var countyName=document.forms["countyData"]["county_name"].value;
                        var countyProvince=document.forms["countyData"]["province"].value;
                        
                        if ((countyName==null || countyName=="")){
			  alert("Please enter a county name.");
			  document.forms["countyData"]["county_name"].focus();
			  return false;
			}
                        if ((countyProvince==null || countyProvince=="0")){
			  alert("Please select a province/region.");
			  document.forms["countyData"]["province"].focus();
			  return false;
			}
    }
</script>
    
<?php
$attributes = array('id' => 'countyData','onsubmit'=>'return validateForm();');
    if($task == 1){
        //save new province
        echo form_open('zoonotic_data_administration/save_county', $attributes);
    }elseif($task == 2){
        //edit province
        if($cause == 1){
            echo form_open('zoonotic_data_administration/save_edit_county/'.$countyID.'/'.$cause.'/'.$countyProvince.'/'.$provinceName, $attributes);
        }elseif($cause == 2){
            echo form_open('zoonotic_data_administration/save_edit_county/'.$countyID.'/'.$cause.'/'.$countyProvince.'/'.$provinceName, $attributes);
        }
    }
?>
<div>
    <table border="0" class="data-table" style="margin:0 auto">
            <tr>
                    <th class="subsection-title" colspan="2">County Details</th>
            </tr>
            <tbody>
                    <tr>
                        <td><span class="mandatory">*</span> County Name</td>
                        <td>
                            <?php
                                if(isset($countyName)){
                                    echo "<input id='county_name' name ='county_name' type='text' value ='".urldecode($countyName)."'></input>";
                                }else{
                                    echo "<input id='county_name' name ='county_name' type='text' value =''></input>";
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Province/Region</td>
                        <td>
                            <select id="province" name="province">
                                        <option value="0" <?php if($countyProvince == 0){?>selected<?php }; ?>>Select Province</option>
                                        <?php
                                        foreach ($provinces as $province) {
                                                echo "<option value='$province->id'";
                                                if($countyProvince == $province->id){
                                                    echo " selected ";   
                                                    };
                                                echo ">$province->Name</option>";
                                        }
                                        ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan=2>
                            <input name="submit" type="submit"
                            class="button" value="<?php
                            if($task == 1){
                                echo "Save County";   
                            }elseif($task == 2){
                                echo "Edit County"; 
                            };
                            ?>">
                        </td>
                    </tr>
            </tbody>
    </table>
</div>
<?php echo form_close();?>
<link href="<?php echo base_url().'CSS/bootstrap.css'?>" type="text/css" rel="stylesheet"/> 
<link href="<?php echo base_url().'CSS/dataTables.bootstrap.css'?>" type="text/css" rel="stylesheet"/> 

<script src="<?php echo base_url().'Scripts/bootstrap.js'?>" type="text/javascript"></script> 
<script src="<?php //echo base_url().'Scripts/dataTables.bootstrap.js'?>" type="text/javascript"></script> 
<script src="<?php //echo base_url().'Scripts/jquery.dataTables.js'?>" type="text/javascript"></script> 
<script>
$(function() {
    $( "#onset_date" ).datepicker();
	$( "#specimen_date" ).datepicker();
	$( "#seen_date" ).datepicker();
  });
  

  $(function() {
			$(document).ready(function() {

			$('#specimen_hide').hide();
			$('#specimen_hides').hide();	
			//$('#itemother_hide').hide();
			//$('#conditions').hide();
			
			});

		}); 
		
		function specimen_hide(){
		if($("#specimen_taken").val() == "Yes"){
	//	$('#specimen_hide').hide();
		document.getElementById("specimen_hide").style.display="";
		document.getElementById("specimen_hides").style.display="";
			//$('#specimen_hides').hide();
		}
		else{
		$('#specimen_hide').hide();
			$('#specimen_hides').hide();
		}
		
		
		}
</script>
<div id="sub_menu">
<a href="<?php echo  site_url("moh_503/");?>" class="top_menu_link sub_menu_link first_link <?php if($quick_link == "add_user"){echo "top_menu_active";}?>"><< View MOH 503</a>  
 
</div>
<div class="view_content">


        <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Line Listing Form.
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form  role="form" method="post" action="<?php echo base_url().'moh_503/save'?>" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Names: </label>
                                            <input type="text" class="form-control" name="names" id="names" required>
                                            <!--<p class="help-block">Example block-level help text here.</p>-->
                                        </div>
										
								
										
						
										
										<div class="form-group">
                                            <label>Patient: </label><br/>
                                            <label class="radio-inline">
											
                                                <input type="radio" name="patient" id="patient" value="Inpatient" checked>Inpatient
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="patient" id="patient" value="Inpatient">Outpatient
                                            </label>
                                        
                                        </div>
										
									   
										<div class="form-group">
                                            <label>Telephone no: </label>
                                            <input type="number" class="form-control" name="telephone" id="telephone" required>
                                            <!--<p class="help-block">Example block-level help text here.</p>-->
                                        </div>
										
										<div class="form-group">
                                            <label>Physical Address: </label>
                                            <input type="text" class="form-control"  name="phy_add" id="phy_add" required/>
                                        </div>
										
										
										<div class="form-group">
                                            <label>Date of onset: </label>
                                            <input type="text" class="form-control"  name="onset_date" id="onset_date" required/>
                                        </div>
										
										<div class="form-group">
								 
                                            <label>Specimen taken: </label>
                                            <select class="form-control" name="specimen_taken" onchange="specimen_hide()" id="specimen_taken" required>
										
											<option value="No">No</option>
											<option value="Yes">Yes</option>
											
											</select>
											
                                        </div>
										
										<div id="specimen_hide">
										<br/>
										<div class="form-group">
                                            <label>Type: </label>
                                            <input type="text" class="form-control"  name="specimen_type" id="specimen_type"/>
                                        </div>
										</div>
										
										<div class="form-group">
								
                                            <textarea style="width:100%;" placeholder="Comments" class="form-control" id="comments" name="comments" rows="4"></textarea>
                                        </div>
										
										
                                   
                                </div>
								
                                <!-- /.col-lg-6 (nested) -->
                                <div class="col-lg-6">
								
                                  <div class="form-group">
								  <?php //print_r($diseases);?>
                                            <label>Facility: </label>
                                            <select class="form-control" name="facility" id="facility" required>
											<!--<option disabled>Non Selected</option>-->
											<?php
											//print_r($facilities);
											foreach($facilities as $facil){
											?>
											<option value="<?php echo $facil->facilitycode;?>"><?php echo $facil->name; ?></option>
											<?php
											
											}
											?>
											</select>
											
                                        </div>
										<div class="form-group">
								  <?php //print_r($diseases);?>
                                            <label>Disease/Condition: </label>
                                            <select class="form-control" name="disease" id="disease" required>
											<!--<option disabled>Non Selected</option>-->
											<?php
											foreach($diseases as $dis){
											?>
											<option value="<?php echo $dis->id;?>"><?php echo $dis->Name; ?></option>
											<?php
											//echo "<option value='".$dis->id."'>".$dis->name."</option>";
											}
											?>
											</select>
											
                                        </div>
										
										<div class="form-group">
                                            <label>Sex: </label><br/>
                                            <label class="radio-inline">
											
                                                <input type="radio" name="sex" id="sex" value="Male" checked>Male
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sex" id="sex" value="Female">Female
                                            </label>
                                        
                                        </div>
										
										
										<div class="form-group">
                                            <label>Age: </label>
                                            <input type="number" class="form-control" name="age" id="age" max="100" required>
                                            <!--<p class="help-block">Example block-level help text here.</p>-->
                                        </div>
										
										<div class="form-group">
                                            <label>Date seen: </label>
                                            <input type="text" class="form-control"  name="seen_date" id="seen_date" required/>
                                        </div>
										
										<div class="form-group">
                                            <label>No of doses of vaccine: </label>
                                            <input type="number" class="form-control" name="vaccine_no" id="vaccine_no" required>
                                            <!--<p class="help-block">Example block-level help text here.</p>-->
                                        </div>
										
										<div id="specimen_hides">
										<div class="form-group">
                                            <label>Date taken: </label>
                                            <input type="text" class="form-control"  name="specimen_date" id="specimen_date"/>
                                        </div>
										<div class="form-group">
                                            <label>Results: </label>
                                            <input type="text" class="form-control"  name="specimen_results" id="specimen_results" />
                                        </div>
										
										</div>
										<div class="form-group">
                                            <label>Status: </label><br/>
                                            <label class="radio-inline">
											
                                                <input type="radio" name="status" id="status" value="Alive" required/>Alive
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="status" id="status" value="Dead">Dead
                                            </label>
                                        
                                        </div>
										
										
										
										
										
							
										
										
            
                                </div>
			                    <div class="col-lg-12">
								<button type="submit" name="bookin" id="bookin" class="btn btn-default">Submit Button</button>
                                        <button type="reset" class="btn btn-default">Reset Button</button>
								</div>
										
								</form>
								
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>

</div>

<?php 
if(!isset($sub_link)){
	$sub_link = "";
}
?>
<?php 
$access_level=$this->session->userdata("user_indicator");
		

?>
<script>
$(document).ready( function () {
    $('#district_facility_table').dataTable();
} );
<link href="<?php echo base_url().'assets/DataTables/media/css/jquery.dataTables.css'?>" type="text/css" rel="stylesheet"/>
 <script src="<?php echo base_url().'assets/DataTables/media/js/jquery.js'?>" type="text/javascript"></script>	  
  <script src="<?php echo base_url().'assets/DataTables/media/js/jquery.dataTables.js'?>" type="text/javascript"></script>
  
</script>
<div id="sub_menu" style="margin:5px;">
	<a href="<?php echo site_url('facility_management/new_facility');?>" class='btn btn-info'>New Facility</a>
</div>

<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					Current Facilities
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="facility_table" class="table table-striped table-bordered table-hover" width="100%">
	
	<tr>
		<th>MFL Code</th> 
		<th>Name</th>		
		<th>District</th>
		<th>Reporting</th>    
	</tr>
 <?php  
 foreach($facilities as $facility){?>
 <tr>
 <td>
 <?php echo $facility->facilitycode;?>
 </td> 
   <td>
 <?php echo $facility->name;?>
 </td>
   <td>
 <?php echo $facility->Parent_District->Name;?>
 </td>
 <td><?php
		if ($facility -> reporting == 0) {echo "No";
		} else {echo "Yes";
		};
		?></td>
 </tr>
 
 <?php }
 ?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>


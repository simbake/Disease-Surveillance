<!-- <div id="sub_menu">
<a href="<?php echo  site_url("user_management/add");?>" class="top_menu_link sub_menu_link first_link <?php if($quick_link == "add_user"){echo "top_menu_active";}?>">New User</a>  
 
</div> -->

<div class="panel panel-default">
				<div class="panel-heading">
					Users
				</div>

 <div class="panel-body">
        <table class="table table-responsive table-hover table-striped" id="example" width="100%" >
	
		<thead>
			<tr><a href="<?php echo  site_url("user_management/add");?>" class="btn btn-info pull-left">New User</a></tr>
			<br/><br/><br/>
		<tr>
		<tr>
		<th>Full Name</th> 
		<th>Username</th>		
		<th>User Group</th> 
		<th>Province</th> 
		<th>District</th> 
		<th>Can Delete?</th> 
		<th>Can Download Raw Data</th> 
		<th>Disabled?</th> 
		<th>Action</th>
	</tr>
	</thead>
					
							<tbody>
 <?php 
 foreach($users as $user){?>
 <tr>
 <td>
 <?php echo $user->Name;?>
 </td> 
   <td>
 <?php echo $user->Username;?>
 </td>
  <td>
 <?php echo $user->Access->Level_Name;?>
 </td>
   <td>
 <?php 
 if($user->Access->Indicator == "provincial_clerk"){
 	 echo $user->Province_Object->Name;
 }
?>
 </td>
   <td>
 <?php 
  if($user->Access->Indicator == "district_clerk"){
 	 echo $user->District_Object->Name;
 } ?>
 </td>
   <td>
 <?php
   if( $user->Can_Delete == "1"){
 	 echo "<span style='color:green'>Yes</span>";
 } 
 else{
 	 echo "<span style='color:red'>No</span>";
 }?>
 </td>
   <td>
   	 <?php
   if( $user->Can_Download_Raw_Data == "1"){
 	 echo "<span style='color:green'>Yes</span>";
 } 
 else{
 	 echo "<span style='color:red'>No</span>";
 }?> 
 </td>
 
  <td>
 <?php if($user->Disabled == 0){echo "No";}else{echo "Yes";};?>
 </td>
 <td>
  <a href="<?php echo base_url()."user_management/edit_user/".$user->id?>" class='label label-primary'><span class="glyphicon glyphicon-refresh"></span>Edit </a>|
  <?php
  if($user->Disabled == 0){?>
  	   <a href="<?php echo base_url()."user_management/change_availability/".$user->id."/1"?>" class='label label-danger'><span class='glyphicon glyphicon-off'></span> Disable</a> 
  <?php }
  else{?>
  	   <a href="<?php echo base_url()."user_management/change_availability/".$user->id."/0"?>" class='label label-info'><span class='glyphicon glyphicon-off'></span> Enable</a> 
 <?php }
  ?>

 </td>
 </tr>
 
 <?php }
 ?>
 </tbody>
</table>
</div>
</div>
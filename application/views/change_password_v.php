<?php
echo validation_errors('
<p class="error">','</p>
'); 
?>

<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
					Change Password
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
<form action="<?php echo base_url().'user_management/save_new_password'?>" method="post" style="margin:0 auto; width:300px;">
	<td><label for="old_password">Old Password</label></td>
	<!-- <label> <strong class="label">Old Password</strong> -->
		<tr></tr><input type="password" name="old_password" id="old_password"></tr>
	<td><label for="new_password">Confirm New Password</label></td>
	<tr>	<input type="password" name="new_password" id="new_password"></tr>
		<td></td><input type="password" name="new_password_confirm" id="new_password_confirm"></td>
	</label>
	
		<tr><input type="submit" class="btn" name="register" id="register" value="Change Password"></tr>
 
</form>
</table>
</div>
</div>
</div>
</div>
</div>
</div>

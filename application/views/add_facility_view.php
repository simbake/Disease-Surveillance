<?php
$attributes = array('enctype' => 'multipart/form-data');
echo form_open('facility_management/search',$attributes);
echo validation_errors('
<p class="error">','</p>
'); 
?>
<div class="row">
		 	<div class="container-fluid">
		 		
          <div class="col-lg-12">
<div class="panel panel-default">
				<div class="panel-heading">
				Search for Facility in MFL List
				</div>

 <div class="panel-body ">
 	   <div class="table-responsive">
        <table  style="margin-left: 0;" id="dataTables-example" class="table table-striped table-bordered table-hover" width="100%">
	
	<tbody> 
		<tr>
			<td><span class="mandatory">*</span> Facility Name</td>
			<td><?php

			$data_search = array(
				'name'        => 'search',
			);
			echo form_input($data_search); ?></td>
		</tr>
	 
			<tr>
				<td align="center" colspan=2><input name="submit" type="submit"
					class='btn btn-info' value="Search"> </td>
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
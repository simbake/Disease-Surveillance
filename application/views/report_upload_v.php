<link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />
<link href="<?php echo base_url() ?>assets/css/drag_drop/css/drag_drop.css" rel="stylesheet" />
<!-- JavaScript Includes -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="<?php echo base_url() ?>assets/css/drag_drop/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="<?php echo base_url() ?>assets/css/drag_drop/js/jquery.ui.widget.js"></script>
		<script src="<?php echo base_url() ?>assets/css/drag_drop/js/jquery.iframe-transport.js"></script>
		<script src="<?php echo base_url() ?>assets/css/drag_drop/js/jquery.fileupload.js"></script>
		
		<!-- Our main JS file -->
		<script src="<?php echo base_url() ?>assets/css/drag_drop/js/script.js"></script>
		<script type="text/javascript">
			function change_year(yearz){
			if(yearz){
				clear_field();
				var base_url=<?php echo base_url() ?>;
			//document.getElementById("form_change").innerHTML="<form id='upload' method='post' action='"+base_url+"report_upload/drag_drop/'"+yearz+" enctype='multipart/form-data'>";
			}
			}
			function clear_field(){
			document.getElementById("form_change").innerHTML="";
			return;	
			}
			
		</script>
	
	<div class="row">
            <!-- <div class="col-lg-12">
                <h2 class="page-header">Menu Tabs</h2>
            </div> -->
            <div class="col-lg-12">

                <ul id="myTab" class="nav nav-pills ">
                    <li class="active"><a href="<?php echo site_url("district_management");?>" data-toggle="tab"><i class="fa fa-tree"></i>Districts</a>
                    </li>
                    <li class=""><a href="<?php echo site_url("county_management");?>" data-toggle="tab"><i class="fa fa-car"></i> Counties</a>
                    </li>
                    <li class=""><a href="<?php echo site_url("facility_management");?>" data-toggle="tab"><i class="fa fa-support"></i>Facilities</a>
                    </li>
                    <li class=""><a href="<?php echo site_url('disease_ranking');?>" data-toggle="tab"><i class="fa fa-database"></i>Disease Ranking</a>
                    </li>
                    <li class=""><a href="<?php echo site_url("user_management/listing");?>" data-toggle="tab"><i class="fa fa-database"></i> Users</a>
                    </li>
                    <li class=""><a href="<?php echo site_url("report_upload");?>" data-toggle="tab"><i class="fa fa-database"></i>Upload</a>
                    </li>
                </ul>

               

            </div>
        </div>
	
	
		<a id="form_change">
<form id="upload" method="post" action="<?php echo base_url() ?>report_upload/drag_drop/" enctype="multipart/form-data">
	</a>
	<select name="upload_year" id="upload_year">
		<?php
		$curr_year=date("Y"); 
		while($curr_year>=2012){
			echo "<option value='$curr_year'>$curr_year</option>";
			$curr_year--;
		}
		?>
	</select>
			<div id="drop">
				Drop Files Here

				<a>Browse</a>
				<input type="file" name="upl" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>

</form>

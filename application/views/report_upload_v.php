<link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />
<link href="<?php echo base_url() ?>css/drag_drop/css/drag_drop.css" rel="stylesheet" />
<!-- JavaScript Includes -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="<?php echo base_url() ?>css/drag_drop/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="<?php echo base_url() ?>css/drag_drop/js/jquery.ui.widget.js"></script>
		<script src="<?php echo base_url() ?>css/drag_drop/js/jquery.iframe-transport.js"></script>
		<script src="<?php echo base_url() ?>css/drag_drop/js/jquery.fileupload.js"></script>
		
		<!-- Our main JS file -->
		<script src="<?php echo base_url() ?>css/drag_drop/js/script.js"></script>
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
		<a id="form_change">
<form id="upload" method="post" action="<?php echo base_url() ?>report_upload/drag_drop/" enctype="multipart/form-data">
	</a>
			<div id="drop">
				Drop Files Here

				<a>Browse</a>
				<input type="file" name="upl" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>

</form>

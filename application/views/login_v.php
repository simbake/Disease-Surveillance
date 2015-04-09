<?php ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>DDSR | <?php echo $title;?> </title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
    <link rel="icon" href="<?php echo base_url().'assets/images/coat_of_arms.png'?>" type="image/x-icon" />
    <link href="<?php echo base_url().'assets/css/style.css'?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo base_url().'assets/css/jquery-ui.css'?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.css'?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo base_url().'assets/bootstrap/css/bootstrap-responsive.css'?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo base_url().'assets/css/datepicker.css'?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo base_url().'assets/datatable/TableTools.css'?>" type="text/css" rel="stylesheet"/>
	<link href="<?php echo base_url().'assets/datatable/dataTables.bootstrap.css'?>" type="text/css" rel="stylesheet"/>

	<script src="<?php echo base_url().'assets/scripts/bootstrap-datepicker.js'?>" type="text/javascript"></script>
	<script src="<?php echo base_url().'assets/scripts/jquery.js'?>" type="text/javascript"></script>
	
    <script type="text/javascript">
	
    </script>
	
 <style>
.panel-success>.panel-heading {
color: white;
background-color: #528f42;
border-color: #528f42;
border-radius:0;

}
.navbar-default {
background-color: white;
border-color: #e7e7e7;
}
</style>

<script>
  			$(function() {
  	
  	   $( "#month" ).combobox({
        	selected: function(event, ui) {
        		
           var data =$("#year").val();
           var month =$("#month").val();
           //var name =encodeURI($("#desc option:selected").text());
          
          
        var url = "<?php echo base_url().'report_management/monthly' ?>
			"
			$.ajax({
			type: "POST",
			data: "year="+data+"&month="+month,
			url: url,
			beforeSend: function() {
			$("#contentlyf").html("");
			},
			success: function(msg) {
			$("#contentlyf").html(msg);

			}
			});
			return false;

			}
			});

			$("#disease").combobox({
			selected: function(event, ui) {

			var dyear =$("#dyear").val();
			var dmonth =$("#dmonth").val();
			var dise=$("#disease").val();
			var names =encodeURI($("#disease option:selected").text());

			var url = "
<?php echo base_url().'report_management/daily' ?>
	"
	$.ajax({
	type: "POST",
	data: "year="+dyear+"&month="+dmonth+"&disease="+dise+"&name="+names,
	url: url,
	beforeSend: function() {
	$("#contently").html("");
	},
	success: function(msg) {
	$("#contently").html(msg);

	}
	});
	return false;

	}
	});

	});
  </script>
  </head>  
  

<body>
	
<!-- Fixed navbar -->
   <div class="navbar navbar-default navbar-fixed-top" id="">
   <div class="container" style="width: 100%;">
        <div class="navbar-header " > 
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
         
          <a style="margin-top: auto;" >
          	<img style="display:inline-block; width:auto%; width: 100px; height: 16%;margin-top:-10%" src="<?php echo base_url()?>assets/images/coat_of_arms.png" class="img-rounded img-responsive " alt="Responsive image" id="logo" >
          	</a>
				<div id="logo_text" style="display:inline-block; margin-top: 0%">
					<span style="font-size: 1.20em;font-weight: bold; ">Ministry of Health</span><br />
					<span style="font-size: 0.95em;font-weight: bold;">Disease Surveillance and Response Unit</span><br/>
					<span style="font-size: 0.95em;">Electronic Intergreted Disease Surveillance and Response</span><br />	
					<br />
					<span>
					
				
                   
                   </span>
				</div>
				
						
				
        </div>
        

        <div class="navbar-collapse collapse" style="font-weight: bold" id="navigate">
          
          <div class="nav navbar-nav navbar-right">
          	
          	  <div class="row">
          		
          	<div class="col-md-12">

            </div>
            
          	</div>
          	
          <div class="row">
          	<div class="col-md-12">
          	
          </div>
          </div>
          
            </div>
         </div><!--/.nav-collapse -->
      </div>
      
      
      <div class="container-fluid" style="/*border: 1px solid #036; */ height: 30px;" id="extras-bar">
      	<div class="row">
      		
      		<div class="col-md-4" style="font-weight:bold; ">
      		
      		 	
     		</div>
      		<div class="col-md-4">     			
      		</div>
      	</div>      	
      </div>	
      </div>
<br/><br/><br/><br/><br/><br/><br/><br/>
<div class="container">
    <div class="row vertical-offset-100">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Please sign in</h3>
			 	</div>
			  	<div class="panel-body">
			  		<form action="<?php echo base_url().'user_management/authenticate'?>" method="post" accept-charset="UTF-8" role="form" >
                    <fieldset>
			    	  	<div class="form-group">
			    		    <input class="form-control" placeholder="Username" name="username" id="username" type="text">
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" placeholder="Password" name="password" id="password" type="password" value="">
			    		</div>
			    		<!-- <div class="checkbox">
			    	    	<label>
			    	    		<input name="remember" type="checkbox" value="Remember Me"> Remember Me
			    	    	</label>
			    	    </div> -->
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Sign in"  name="register" id="register">
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
		
    <div id="bottom_ribbon" align="center">
        <div id="footer" >
 <?php $this->load->view("footer_v");?>
    </div>
    </div>
</body>
</html>

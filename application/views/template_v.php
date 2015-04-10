<?php
if (!$this -> session -> userdata('user_id')) {
	redirect("User_Management/login");
}
if (!isset($link)) {
	$link = null;
}
if (!isset($quick_link)) {
	$quick_link = null;
}
$access_level = $this -> session -> userdata('user_indicator');
$user_is_administrator = false;
$user_is_nascop = false;
$user_is_pharmacist = false;

if ($access_level == "system_administrator") {
	$user_is_administrator = true;
}
if ($access_level == "pharmacist") {
	$user_is_pharmacist = true;

}
if ($access_level == "nascop_staff") {
	$user_is_nascop = true;
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>DDSR | <?php echo $title;?> </title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
   <link rel="icon" href="<?php echo base_url().'assets/images/coat_of_arms.png'?>" type="image/x-icon" />
   <link href="<?php echo base_url().'assets/css/style.css'?>" type="text/css" rel="stylesheet"/> 
   
       <!--Bootstrap & Bootstrap Datatables==========================  -->
   <link href="<?php echo base_url().'assets/bootstrap/css/bootstrap.css'?>" type="text/css" rel="stylesheet"/> 
   <link href="<?php echo base_url().'assets/main/bootstrap.min.css'?>" type="text/css" rel="stylesheet"/>
   <link href="<?php echo base_url().'assets/main/dataTables.bootstrap.css'?>" type="text/css" rel="stylesheet"/>
   
   <!--Jquery UI ==========================  -->
   <link href="<?php echo base_url().'assets/css/jquery-ui.css'?>" type="text/css" rel="stylesheet"/>
   
   <!--Jquery & Jquery Datatables==========================  -->
   <script src="<?php echo base_url().'assets/main/jquery-1.10.2.min.js'?>" type="text/javascript"></script> 	
   <script src="<?php echo base_url().'assets/main/jquery.dataTables.min.js'?>" type="text/javascript"></script>
   <script src="<?php echo base_url().'assets/main/dataTables.bootstrap.js'?>" type="text/javascript"></script>
   
    <!--Online Backup files ==========================  -->
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css"> 
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	
	

    <script type="text/javascript">
	
    </script>
	
 <!-- <style>
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
</style> -->

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
         
          <a style="margin-top:auto;" >
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
          	<ul class="nav navbar-nav navbar-right">
     	<?php
	//Code to loop through all the menus available to this user!
	//Fetch the current domain
	$menus = $this -> session -> userdata('menu_items');
	$current = $this -> router -> class;
	$counter = 0;
		?>
 	<li class=""><a href="<?php echo site_url().'home_controller';?>" class="">Home</a> </li>

      	<?php
foreach($menus as $menu){?>
		<li><a href="<?php echo base_url() . $menu['url'];?>" class=" "><?php echo $menu['text'];?></a> </li> 
		<?php
$counter++;
}
	?>
		<!-- <li><a href="<?php echo site_url().'sms/index ';?>" class=" ">Reports</a> </li>  -->
	 
                            
            <li class="dropdown ">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user" ></span><?php echo $this -> session -> userdata('full_name');?> | <?php echo $this -> session -> userdata('user_indicator');?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a style="background: whitesmoke;color: black !important" href="<?php echo site_url("user_management/change_password");?>"><span class="glyphicon glyphicon-pencil" style="margin-right: 2%; "></span>Change password</a></li>  
                <li class="divider" style="color:#356635"></li>            
                <li><a style="background: whitesmoke;color: black !important" href="<?php echo site_url("user_management/logout");?>" ><span class="glyphicon glyphicon-off" style="margin-right: 2%;"></span>Log out</a></li>               
              </ul>
            </li>  
            <!-- <li><a href="<?php echo site_url().'User_Management/login';?>" class=" "><span class="glyphicon glyphicon-user" ></span>Login</a> </li>  -->	
          </ul>
          </div>
          </div>
          
            </div>
         </div><!--/.nav-collapse -->
      </div>
      
      <div class="container-fluid" style="/*border: 1px solid #036; */ height: 30px;" id="extras-bar">
      	<div class="row">
      		
      		<div class="col-md-4" style="font-weight:bold; ">
      		<span style="margin-left:2%;">  <?php echo $banner_text;?> </span>
      		 	
     		</div>
      		<div class="col-md-4">     			
      		</div>
      		<div class="col-md-4"  style="text-align: right;">
      			<?php  echo date('l, dS F Y'); ?>
             <span id="clock" style="font-size:0.85em; " ></span>
      		</div> 
      	</div>      	
      </div>	
      </div>

 
	<div class="row">
	
		
	</div>
</div><br/>

<!-- /.modal --> 

<div>  <!--container-->
<?php $this -> load -> view($content_view);?>
    </div> <!-- /container -->
    
 
      <div id="footer">
      <div class="container">
        <p class="text-muted"> Government of Kenya &copy <?php echo date('Y');?>. All Rights Reserved
</p>
        
      </div>
    </div>
    
    <script type="text/javascript">
    /*
 * Auto logout
 */
var timer = 0;
function set_interval() {
  showTime()
  // the interval 'timer' is set as soon as the page loads
  timer = setInterval("auto_logout()", 240000);
  // the figure '1801000' above indicates how many milliseconds the timer be set to.
  // Eg: to set it to 5 mins, calculate 3min = 3x60 = 180 sec = 180,000 millisec.
  // So set it to 180000
}

function reset_interval() {
  showTime()
  //resets the timer. The timer is reset on each of the below events:
  // 1. mousemove   2. mouseclick   3. key press 4. scroliing
  //first step: clear the existing timer

  if(timer != 0) {
    clearInterval(timer);
    timer = 0;
    // second step: implement the timer again
    timer = setInterval("auto_logout()", 240000);
    // completed the reset of the timer
  }
}

function auto_logout() {

  // this function will redirect the user to the logout script
  window.location = "<?php echo base_url(); ?>user_management/logout";
}

/*
* Auto logout end
*/
  function showTime()
{
var today=new Date();
var h=today.getHours();
var m=today.getMinutes();
var s=today.getSeconds();
// add a zero in front of numbers<10
h=checkTime(h);
m=checkTime(m);
s=checkTime(s);
$("#clock").text(h+":"+m);
t=setTimeout('showTime()',1000);

}
function checkTime(i)
{
if (i<10)
  {
  i="0" + i;
  }
return i;
}  
	$(document).ready(function() {
					$('.alert-success').fadeOut(10000, function() {
    // Animation complete.
});
});
</script>

</html>
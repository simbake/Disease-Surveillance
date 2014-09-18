<?php
error_reporting(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>

<link href="<?php echo base_url().'CSS/style.css'?>" type="text/css" rel="stylesheet"/> 
<link href="<?php echo base_url().'CSS/public_view_style.css'?>" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url().'CSS/jquery-ui.css'?>" type="text/css" rel="stylesheet"/> 
<link href="<?php echo base_url().'CSS/bluetabs.css'?>" type="text/css" rel="stylesheet"/>
<script src="<?php echo base_url().'Scripts/dropdowntabs.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'Scripts/jquery.js'?>" type="text/javascript"></script> 
<script src="<?php echo base_url().'Scripts/jquery-ui.js'?>" type="text/javascript"></script> 
<script src="jquery.js"></script>

<?php
if (isset($script_urls)) {
	foreach ($script_urls as $script_url) {
		echo "<script src=\"" . $script_url . "\" type=\"text/javascript\"></script>";
	}
}
?>

<?php
if (isset($scripts)) {
	foreach ($scripts as $script) {
		echo "<script src=\"" . base_url() . "Scripts/" . $script . "\" type=\"text/javascript\"></script>";
	}
}
?>


 
<?php
if (isset($styles)) {
	foreach ($styles as $style) {
		echo "<link href=\"" . base_url() . "CSS/" . $style . "\" type=\"text/css\" rel=\"stylesheet\"/>";
	}
}
?>  

</head>

<body>
    <div id="wrapper">
        <div align="center">
            <?php
                echo '<img src="http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/Images/Coat 2.jpg" alt="Coat of Arms" id="coat"/>'; 
            ?>
        </div>
        <div id="header">
            <div id="banner" style="font-size: 30px" align="center">
                Ministry of Public Health and Sanitation
            </div>
            <div id="department" align="center">
                Division of Disease Surveillance and Response
            </div>
            <div id="nav">
                <ul class="menu">
                    <li>
                        <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/idsr';?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/idsr/aboutus.php';?>">About Us</a>
                    </li>
                    <li>
                        <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/idsr/strategy.php';?>">IDSR Strategy</a>
                    </li>
                    <li>
                        <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/idsr/reports.php';?>">Reports</a>
                    </li>
                    <li class="active">
                        <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance/zoonotic_data_report/load_data';?>">Zoonotic Diseases</a>
                    </li>
                    <li>
                        <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/idsr/resources.php';?>">Resources</a>
                    </li>
                    <li>
                        <a href="http://ddsrkenya.blogspot.com/">Blog</a>
                    </li>
                    <li>
                        <a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance';?>">Login</a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="main_wrapper"> 
            <div class="tabs">
                <div class="tab">
                    <input type="radio" id="tab-1" name="tab-group-1" checked/>
                    <label for="tab-1" style="font-size: 15px">Human</label>

                    <div class="content">
                        <?php $this -> load -> view($content_view);?>
                    </div>
                </div>
                
                <div class="tab">
                    <input type="radio" id="tab-2" name="tab-group-1">
                    <label for="tab-2" style="font-size: 15px">Wildlife</label>

                    <div class="content">
                        Under development
                    </div>
                </div>
<div class="tab">
                    <input type="radio" id="tab-2" name="tab-group-1">
                    <label for="tab-2" style="font-size: 15px">Livestock</label>

                    <div class="content">
                        Under development
                    </div>
                </div>                
                <div class="tab">
                    <input type="radio" id="tab-3" name="tab-group-1">
                    <label for="tab-3" style="font-size: 15px">Vectors</label>

                    <div class="content">
                        Under development.
                    </div>
                </div>
                
                <div class="tab">
                    <input type="radio" id="tab-4" name="tab-group-1">
                    <label for="tab-4" style="font-size: 15px">Resources</label>

                    <div class="content">
                        <?php $this -> load -> view($res_view);?>
                    </div>
                </div>
                
                <div class="tab">
                    <input type="radio" id="tab-5" name="tab-group-1">
                    <label for="tab-5"><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/zoonoticblog';?>" style ="text-decoration: none; font-size: 15px;">Blog</a></label>

                    <div class="content">
                        Under development.
                    </div>
                </div>
                <div class="tab">
                    <input type="radio" id="tab-6" name="tab-group-1">
                    <label for="tab-6" style="font-size: 15px"><a href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/Disease-Surveillance';?>" style ="text-decoration: none; font-size: 15px;">Login</a></label>

                    <div class="content">
                    </div>
                </div>
            </div>    
        </div><!-- end main wrapper -->
    </div><!--End Wrapper div-->
    <div id="footer_ribbon">
        <div id="footer" style ="font-size: 20px;">
            <?php $this -> load -> view("footer_v");?>
        </div>
    </div>
</body>
</html>

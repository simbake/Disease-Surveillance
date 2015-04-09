<?php
if (!isset($quick_link)) {
	$quick_link = null;
}
?>
<div id="sub_menu">
	<br/><br/>
	<?php
if($this -> session -> userdata('can_download_raw_data') == '1'){
	?>
	<a href="<?php echo site_url('raw_data');?>" class='btn btn-info'>Raw Data</a>
	<?php
	}
	$access_level = $this -> session -> userdata('user_indicator');
	if($access_level == "district_clerk"){
	?>
	<a href="<?php echo site_url("dnr_facilities");?>"  class='btn btn-info'>'DNR' Facilities</a>
	<?php }
		else if ($access_level == "national_clerk"){
	?>
	<a href="<?php echo site_url("dnr_districts");?>"  class='btn btn-info'>'DNR' Districts</a>
	<a href="<?php echo site_url("intra_district");?>"  class='btn btn-info'>Intra-District Reporting</a>
	<?php }?>
	<a href="<?php echo site_url("timeliness_report");?>"  class='btn btn-info'>Timeliness Report</a>
	<a href="<?php echo site_url("weekly_report_demo");?>"  class='btn btn-info'>Weekly Report</a>
</div>
<br/><br/>
<div id="main_content">
	<?php
	$this -> load -> view($report_view);
	?>
</div>

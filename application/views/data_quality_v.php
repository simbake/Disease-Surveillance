<?php
if (!isset($quick_link)) {
	$quick_link = null;
}
?>
<div id="sub_menu">
	<br/>	<br/>
	<a href="<?php echo site_url('facility_reports');?>"  class='btn btn-primary'>Submitted Reports</a>
	<a href="<?php echo site_url('facility_management/district_list');?>" class='btn btn-primary'>My Facilities</a>
	<a href="<?php echo site_url('data_duplication');?>" class='btn btn-primary'>Duplication Check</a>

	<a href="<?php echo site_url('data_delete_management');?>" class='btn btn-primary''>Deletion Logs</a>
</div>
	<br/>	<br/>
<div id="main_content">
	<?php
	$this -> load -> view($quality_view);
	?>
</div>

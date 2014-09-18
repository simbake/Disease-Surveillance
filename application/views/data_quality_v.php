<?php
if (!isset($quick_link)) {
	$quick_link = null;
}
?>
<div id="sub_menu">
	<a href="<?php echo site_url('facility_reports');?>" class="top_menu_link sub_menu_link first_link  <?php
	if ($quick_link == "facility_reports") {echo "top_menu_active";
	}
	?>">Submitted Reports</a>
	<a href="<?php echo site_url('facility_management/district_list');?>" class="top_menu_link sub_menu_link  <?php
	if ($quick_link == "facility_management") {echo "top_menu_active";
	}
	?>">My Facilities</a>
	<a href="<?php echo site_url('data_duplication');?>" class="top_menu_link sub_menu_link  <?php
	if ($quick_link == "data_duplication") {echo "top_menu_active";
	}
	?>">Duplication Check</a>

	<a href="<?php echo site_url('data_delete_management');?>" class="top_menu_link sub_menu_link last_link  <?php
	if ($quick_link == "data_delete") {echo "top_menu_active";
	}
	?>">Deletion Logs</a>
</div>
<div id="main_content">
	<?php
	$this -> load -> view($quality_view);
	?>
</div>

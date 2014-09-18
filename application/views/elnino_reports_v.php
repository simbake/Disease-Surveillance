<?php
if (!isset($quick_link)) {
    $quick_link = null;
}
?>
<div id="sub_menu">
	
	<a href="<?php echo site_url("elnino_summaries"); ?>" class=" top_menu_link sub_menu_link first_link  <?php
    if ($quick_link == "summaries") {echo "top_menu_active";
    }
	?>">Summaries</a>
	
	<a href="<?php echo site_url("elnino_graphs"); ?>" class=" top_menu_link sub_menu_link  <?php
    if ($quick_link == "graphs") {echo "top_menu_active";
    }
	?>">Graphs</a>
	
	<a href="<?php echo site_url('elnino_districts');?>" class="top_menu_link sub_menu_link  <?php
    if ($quick_link == "elnino_districts") {echo "top_menu_active";
    }
    ?>">El Nino District Data</a>
    
    <a href="<?php echo site_url('elnino_raw_data');?>" class="top_menu_link sub_menu_link last_link  <?php
    if ($quick_link == "elnino_raw_data") {echo "top_menu_active";
    }
    ?>">Elnino Raw Data</a>
</div>
<div id="main_content">
	<?php
    $this -> load -> view($report_view);
	?>
</div>

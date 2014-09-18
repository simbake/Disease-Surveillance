<style>
	.message {
	display: block;
	padding: 10px 20px;
	margin: 15px;
	}
	.warning {
	background: #FEFFC8 url('<?php echo base_url()?>Images/Alert_Resized.png') 20px 50% no-repeat;
	border: 1px solid #F1AA2D;
	}
	.message h2 {
	margin-left: 60px;
	margin-bottom: 5px;
	}
	.message p {
	width: auto;
	margin-bottom: 0;
	margin-left: 60px;
	}
</style>

<div class="message warning">
	<h2>Confirm Deletion</h2>
	<p>
		Are you sure you want to delete week <b><?php echo $surveillance_data[0]->Epiweek." (".$surveillance_data[0]->Reporting_Year.")";?></b> surveillance and lab data for <b><?php echo $surveillance_data[0]->Facility_Object->name;?></b>?
	</p>
	<p>
		<?php 
		$confirm_deletion_link = base_url()."weekly_data_management/confirm_delete_weekly_data/".$surveillance_data[0]->Epiweek."/".$surveillance_data[0]->Reporting_Year."/".$surveillance_data[0]->Facility;
		?>
		<a class="link" href="<?php echo $confirm_deletion_link;?>">Yes</a>
		<a class="link" href="<?php echo site_url('facility_reports');?>">No</a>
	</p>
</div>
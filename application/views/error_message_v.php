<style>
	.message {
		display: block;
		padding: 10px 20px;
		margin-bottom: 15px;
	}
	.message p {
		width: auto;
		margin-bottom: 0;
		margin-left: 60px;
	}
	.message h2 {
		margin-left: 60px;
		margin-bottom: 5px;
	}
	.unauthorized {
		background: #fdcea4 url('<?php echo base_url();?>Images/stop_48.png') 20px 50% no-repeat;
		border: 1px solid #c44509;
	}	.message p {
		color: #555;
	}
	.message h2 {
		color: #333;
	}	.message:hover {
		cursor: pointer;
	}
	p {
		font-size: 14px;
		line-height: 20px;
		margin-bottom: 15px; 
	}
</style>
<div class="message unauthorized close">
	<h2>Error!</h2>
	<p>
		<?php echo $error_message;?>
	</p>
</div>
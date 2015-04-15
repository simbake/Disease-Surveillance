/**
 *@author Kariuki & Mureithi
 */
  $(document).ready(function () {
  	//default 
  $('.page-header').html('Notifications');
 
 //ajax_request_replace_div_content('reports/expiries_dashboard',"#notification");
  $('[data-toggle=offcanvas]').click(function () {
    $('.row-offcanvas').toggleClass('active');
  });
  
  $(window).resize(function() {
    if (($(window).width() < 768))
    {
        $( ".col-md-2,.col-md-10" ).css( "position", "" );
    };
});
//expiries function
$("#expiries").on('click', function(){
$('.page-header').html('Expiries');
});
//Notifications function
$("#notifications").on('click', function(){
$('.page-header').html('Notifications');
});
//stocking_levels function
$("#stocking_levels").on('click', function(){
$('.page-header').html('Stocking Levels');
});
//Consumption function
$("#consumption").on('click', function(){
$('.page-header').html('Consumption');
});
//Consumption function
$("#system_usage").on('click', function(){
$('.page-header').html('System Usage');
});

 		function ajax_request_replace_div_content(function_url,div){
		var function_url =url+function_url;
		var loading_icon=url+"assets/img/loader.gif";
		$.ajax({
		type: "POST",
		url: url,
		beforeSend: function() {
		$(div).html("<img style='margin-left:20%;' src="+loading_icon+">");
		},
		success: function(msg) {
		$(div).html(msg);
		}
		});
		}
});
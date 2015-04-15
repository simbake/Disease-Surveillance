/**
 * @author Kariuki
 */
/**  HCMP datepicker option */
json_obj = { "url" : "Images/calendar.gif'",};

var baseUrl=json_obj.url;

  function getLastDayOfYearAndMonth(year, month)
{
    return(new Date((new Date(year, month + 1, 1)) - 1)).getDate();
}    // for the last day of the month
	function refreshDatePickers() {
		var counter = 0;
		$('.clone_datepicker').each(function() {
		var this_id = $(this).attr("id"); // current inputs id
        var new_id = counter +1; // a new id
        $(this).attr("id", new_id); // change to new id
        $(this).removeClass('hasDatepicker'); // remove hasDatepicker class
        $(this).datepicker({ 
        	beforeShowDay: function(date)
    {
        // getDate() returns the day [ 0 to 31 ]
        if (date.getDate() ==
            getLastDayOfYearAndMonth(date.getFullYear(), date.getMonth()))
        {
            return [true, ''];
        }

        return [false, ''];
    },
        	        dateFormat: 'd M yy', 
        	        buttonImage: baseUrl,
					changeMonth: true,
			        changeYear: true
				});; // re-init datepicker
				counter++;
		});				
  }
  //	-- Datepicker	showing just the last day of the month 			
	$(".clone_datepicker").datepicker({
	beforeShowDay: function(date)
    {
        // getDate() returns the day [ 0 to 31 ]
     if (date.getDate() ==
         getLastDayOfYearAndMonth(date.getFullYear(), date.getMonth()))
        {
            return [true, ''];
        }
        return [false, ''];
    },				
	dateFormat: 'd M yy', 
	changeMonth: true,
	changeYear: true,
	buttonImage: baseUrl,       });	
	
	  //	-- Datepicker	limit today		
	$(".clone_datepicker_normal_limit_today").datepicker({
    maxDate: new Date(),				
	dateFormat: 'd M yy', 
	changeMonth: true,
	changeYear: true,
	buttonImage: baseUrl,       });	
	
		function refresh_clone_datepicker_normal_limit_today() {
		var counter = 0;
		$('.clone_datepicker_normal_limit_today').each(function() {
		var this_id = $(this).attr("id"); // current inputs id
        var new_id = counter +1; // a new id
        $(this).attr("id", new_id); // change to new id
        $(this).removeClass('hasDatepicker'); // remove hasDatepicker class
        $(this).datepicker({ 
        	        maxDate: new Date(),
        	        dateFormat: 'd M yy', 
        	        buttonImage: baseUrl,
					changeMonth: true,
			        changeYear: true
				});; // re-init datepicker
				counter++;
		});				
  }
 /******************---------------END--------------------------**********************/
 /* HCMP calculate the actual stock value */
function calculate_actual_stock(actual_units,pack_unit_option,user_input,target_total_units_field,input_object,option){
	
  var user_input=parseInt(user_input);

  if (pack_unit_option == 'Pack_Size'){
  		//unit_size
  	var actual_unit_size=parseInt(actual_units);}
   else{
	//do this other
	var actual_unit_size=1; }
 
    var total_commodity_available_stock=actual_unit_size*user_input;
    
    if(isNaN(total_commodity_available_stock)){
     total_commodity_available_stock=0;
    }
    if(target_total_units_field=='return'){
    return total_commodity_available_stock;
    }else{
     input_object.closest("tr").find(target_total_units_field).val(total_commodity_available_stock);	
    }
    
 
}
 /******************---------------END--------------------------**********************/
/* HCMP AJAX request and console response for comfirmation  */
function ajax_simple_post_with_console_response(url, data){
	          $.ajax({
	          type: "POST",
	          data: data,
	          url: url,
	          beforeSend: function() {
	           // console.log("data to send :"+data);
	          },
	          success: function(msg) { console.log(msg);}
	         });
}
 /******************---------------END--------------------------**********************/
/* HCMP system confirmation message box */
function dialog_box(body_html_data,footer_html_data){
	
	        $('#communication_dialog .modal-body').html("");
			$('#communication_dialog .modal-footer').html("");
            //set message dialog box 
            $('#communication_dialog .modal-footer').html(footer_html_data);
            $('#communication_dialog .modal-body').html(body_html_data);
            $('#communication_dialog').modal('show');
            $(".clone_datepicker").datepicker({
	beforeShowDay: function(date)
    {
        // getDate() returns the day [ 0 to 31 ]
     if (date.getDate() ==
         getLastDayOfYearAndMonth(date.getFullYear(), date.getMonth()))
        {
            return [true, ''];
        }
        return [false, ''];
    },				
	dateFormat: 'd M yy', 
	changeMonth: true,
	changeYear: true,
	buttonImage: baseUrl,       });	
	
}
/******************---------------END--------------------------**********************/
 function number_format (number, decimals, dec_point, thousands_sep) {
    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');
    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'
    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');
    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'
    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'
    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    // *    example 13: number_format('1 000,50', 2, '.', ' ');
    // *    returns 13: '100 050.00'
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function confirm_if_the_user_wants_to_save_the_form(form_id){
	
	   var buttons='<button type="button" class="save_issue btn btn-sm btn-success" data-dismiss="modal">Save</button>'+
       '<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>';
      dialog_box("Kindly confirm the values before saving",buttons);
      $('.save_issue').on('click', function() {
      $(form_id).submit();   
      });
}



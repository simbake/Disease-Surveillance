var disableToolbarResize = false;

if (typeof jQuery != 'undefined' && typeof MooTools != 'undefined' ) { 
	// fix for Bootstrap Carousel - conflicting with mootools-more
	(function($) {
		    Element.implement({
		        slide: function(how, mode){
		            return this;
		        }
		    });
    	})(jQuery);
}

(function($) {
	function wToolbar() {
		if (typeof wrightWrapperToolbar === 'undefined')
			wrightWrapperToolbar = '.wrapper-toolbar';
			
		$(wrightWrapperToolbar).each(function() {
			$(this).css('min-height',$(this).find('.navbar:first').height() + 'px');
		});
	}
	
	function fixImagesIE() {
		$('img').each(function() {
			if ($(this).attr('width') != undefined)
				$(this).width($(this).attr('width'));
		});
	}
	
	wToolbar();
	fixImagesIE();

	$(window).load(function () {
		if (!disableToolbarResize)
			wToolbar();
	});
	$(window).resize(function() {
		if (!disableToolbarResize)
			wToolbar();
	});
})(jQuery);

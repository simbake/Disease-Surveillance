jQuery(document).ready(function() {
	function stickyFooter() {
		if (jQuery('#footer')) {
			var h = jQuery('#footer').height();
			jQuery('.wrapper-footer').height(h);
		}
	}
	jQuery(window).load(function () {
		jQuery('#footer.sticky').css('bottom','0')
			.css('position','absolute')
			.css('z-index','1000');
		stickyFooter();
	});
	stickyFooter();
	jQuery(window).resize(function() {
		stickyFooter();
	});
});

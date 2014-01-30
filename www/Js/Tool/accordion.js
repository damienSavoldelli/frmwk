jQuery(document).ready(function() {
    // Accordions
	jQuery('.accordion li div').hide(); // Hide all content
	//jQuery('.accordion li:first-child div').show(); // Show first accordion content by default
	jQuery('.accordion .accordion-switch').click( // On click hide all accordion content and open clicked one
		function() {
			jQuery(this).parent().siblings().find('div').slideUp();
			jQuery(this).next().slideToggle();
			return false;
		}
	);
});
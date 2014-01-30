jQuery(document).ready(function() {

    jQuery('.notification-details').hide();    
    jQuery('.show-notification-details').click( //On click toggle notification details
		function () {
	       jQuery('.notification-details').removeClass('hidden');
			jQuery(this).next('.notification-details').slideToggle();
			return false;
		}
	); 
	
	jQuery('.close-notification').click( // On click slide up notification
		function () {
			jQuery(this).parent().fadeTo(350, 0, function () {jQuery(this).slideUp(600);});
			return false;
		}
	); 
});
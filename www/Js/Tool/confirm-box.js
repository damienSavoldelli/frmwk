jQuery(document).ready(function() {

    var confirm_box = jQuery('.confirm_box');
        
    confirm_box.bind('click', function(){
    
        if (confirm(jQuery(this).attr("rel")))
        {
            return true;
        }
        
        return false;
    });
});
<div id="{PAGINATION_ID}">
<!-- BEGIN pagination -->

    <!-- BEGIN neg_ss_pagination -->
    <a href="{URL_NEG}" ><span class="paginate_button">-</span></a>
    <!-- END neg_ss_pagination -->
        <!-- BEGIN ss_pagination -->
    <a href="{URL_PAGE}" >{SPAN_B}{NUM_PAGE}{SPAN_E}</a>
        <!-- END ss_pagination -->
    <!-- BEGIN pos_ss_pagination -->
    <a href="{URL_POS}" ><span class="paginate_button">+</span></a>
    <!-- END pos_ss_pagination -->

<!-- END pagination -->
</div>


<!-- BEGIN ajax -->
<script type="text/javascript">
if(typeof(MDL_PAGINATION) == "undefined") var MDL_PAGINATION = new Array;
MDL_PAGINATION['{PAGINATION_ID}'] = new Array;
MDL_PAGINATION['{PAGINATION_ID}']['p'] = 1;
MDL_PAGINATION['{PAGINATION_ID}']['lines_by_page'] = {LINES_BY_PAGE};
//MDL_PAGINATION['{PAGINATION_ID}']['data'] = "";

$(document).ready(function() {
    $('{PAGINATION_CALL_ELEMENT}').unbind('click');
    $('{PAGINATION_CALL_ELEMENT}').bind('click', function() {

	    if(INACTIF==true) return false;
    	//$('#{PAGINATION_ID}').html("");
    	
    	var ajxCall = eval("{FCT_LOAD_LINES}");

    	ajxCall.success(function (data) {
			if(data!=false) {             
	            
	            var total_item = data[0].length;
	                                        
	            items_total  = data[1];
	            pages        = data[2];
	            current_page = MDL_PAGINATION['{PAGINATION_ID}']['p'];
	            
	            // Affichage + ou - 1 page
	            //-------------------------
	            var html_pagination = "";                          
	            //affichage du - si on est pas sur la première
	            if(current_page!=1) {
	                html_pagination += " <span class='paginate_button cursor_hand' id='{PAGINATION_ID}-moins'> - </span> ";
	            }

	            // Affichage + ou - 10 pages
	            //-------------------------
	            if(current_page>10) {
	                html_pagination += " <span class='paginate_button cursor_hand' id='{PAGINATION_ID}-moins_10'> -10 </span> ";
	            }

	            // Affichage raccourcis
	            //-------------------------

	            if(current_page>6 && pages>10) {
	            // première page
	                html_pagination += " <span class='{PAGINATION_ID}-page paginate_button cursor_hand' id='{PAGINATION_ID}-page-1'> 1 ...</span> ";
	            }

	            if(pages!=1) {

	                var obj_pagination = new pagination();                    
	                obj_pagination.init(items_total, MDL_PAGINATION['{PAGINATION_ID}']['lines_by_page'], current_page);
	                obj_pagination.Generate();


	                for(var i= 0; i < obj_pagination.output.length; i++) {
	                    var class_page = " paginate_button";
	                    //document.write(obj_pagination.output[i]);
	                    if(!obj_pagination.output[i]['link']) {
	                        // current_page
	                        class_page = " paginate_active"
	                    }

	                    if(pages>10) {
	                        if((obj_pagination.output[i]['page']>current_page-4 || current_page<=6)
	                        && (obj_pagination.output[i]['page']<current_page+4 || current_page>=(pages-5))) {
	                            html_pagination += " <span class='{PAGINATION_ID}-page"+class_page+" cursor_hand' id='{PAGINATION_ID}-page-"+obj_pagination.output[i]['page']+"'> "+obj_pagination.output[i]['page']+" </span> ";
	                        }
	                    } else {
	                        html_pagination += " <span class='{PAGINATION_ID}-page"+class_page+" cursor_hand' id='{PAGINATION_ID}-page-"+obj_pagination.output[i]['page']+"'> "+obj_pagination.output[i]['page']+" </span> ";
	                    }
	                }

	                if(current_page<(pages-5)) { // dernière page
	                    html_pagination += " <span class='{PAGINATION_ID}-page paginate_button cursor_hand' id='{PAGINATION_ID}-page-"+pages+"'> ... "+pages+"</span> ";
	                }

	                if(current_page<=pages-10) {
	                    html_pagination += " <span class='paginate_button cursor_hand' id='{PAGINATION_ID}-plus_10'> +10 </span> ";
	                }

	                //affichage du + si on est pas sur la dernière page
	                if(current_page!=pages) {
	                    html_pagination += " <span class='paginate_button cursor_hand' id='{PAGINATION_ID}-plus'> + </span> ";
	                }  
	            }
	                
	            html_pagination += "</span>";
	                
	            jQuery('#{PAGINATION_ID}').html(html_pagination);

	            $('#{PAGINATION_ID}-plus').unbind('click');
	            $('#{PAGINATION_ID}-plus').bind('click', function() {
	            	if(INACTIF==true) return false;
	                MDL_PAGINATION['{PAGINATION_ID}']['p']++;
	                $('{PAGINATION_CALL_ELEMENT}').trigger('click');
	            });
	            
	            $('#{PAGINATION_ID}-moins').unbind('click');    
	            $('#{PAGINATION_ID}-moins').bind('click', function() {
	            	if(INACTIF==true) return false;                
	                MDL_PAGINATION['{PAGINATION_ID}']['p']--;
	                $('{PAGINATION_CALL_ELEMENT}').trigger('click');
	            });
	            
	            $('#{PAGINATION_ID}-plus_10').unbind('click');   
	            $('#{PAGINATION_ID}-plus_10').bind('click', function() {
	            	if(INACTIF==true) return false;                
	                MDL_PAGINATION['{PAGINATION_ID}']['p'] += 10; 
	                $('{PAGINATION_CALL_ELEMENT}').trigger('click');
	            });
	            
	            $('#{PAGINATION_ID}-moins_10').unbind('click');
	            $('#{PAGINATION_ID}-moins_10').bind('click', function() {
	            	if(INACTIF==true) return false;                
	                MDL_PAGINATION['{PAGINATION_ID}']['p'] -= 10;
	                $('{PAGINATION_CALL_ELEMENT}').trigger('click');
	            });
	                
	            $('.{PAGINATION_ID}-page').unbind('click');    
	            $('.{PAGINATION_ID}-page').bind('click', function() {
	            	if(INACTIF==true) return false;
	                var expression   = new RegExp('{PAGINATION_ID}-page-','');
	                MDL_PAGINATION['{PAGINATION_ID}']['p'] =  parseInt(jQuery(this).attr("id").replace(expression, ''));
	                $('{PAGINATION_CALL_ELEMENT}').trigger('click');
	            });

	            $('#{PAGINATION_ID}').removeClass('hidden');

	        }
	        else
	        {
	            jQuery('#{PAGINATION_ID}-pagination').html('');
	            // $('#items_found span').html(0);
	        }  
		});


    	
	});
});
</script>
<!-- END ajax -->
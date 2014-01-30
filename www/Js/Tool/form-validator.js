/* 
Classes css pour les controles javascript sur les inputs
    la première est utilisé comme le nom du champs =>  ex: vous_devez_accepter_les_cgu
    required => chamsp requis pour la validation du form
    max => caractères max, récupère la valeur de maxlength="25" pour le nombre
    min => caractères min, récupère la _x_ juste après pour le nombre
    inf => doit être un nombre inférieur à _x_ situé juste après
    sup => doit être un nombre supérieur à _x_ situé juste après
*/







var INACTIF = false;

$(document).ready(function() {

	$('form').bind('submit', function(){

		function alertUser(message, supClass, selector)
		{	
			if(INACTIF == true) return false;
			INACTIF = true;
		    $(selector).empty();
		    $(selector).addClass("alert-"+supClass).append(message).slideDown("slow", function(){
		        setTimeout(function() {
		        	$(selector).fadeOut("slow", function() {
						$(this).css({ "visibility": "hidden", "opacity": 0, display: 'block' }).slideUp('slow', function() {
							$(this).css({ "visibility": "visible", "opacity": 1, display: 'none' });
							INACTIF = false;
						});
					});
		        }, 3000);
		    });
		}

		var form  = $(this);
		form      = form[0];
		var max_i = form.length-1;
		var password_index = ""; 
		$('.form-group').removeClass('has-error');
		
		for(i=0;i<=max_i;i++){

			// Type radio
			if(form[i].id == "rad_mme" || form[i].id == "rad_m" || form[i].id == "rad_mlle" ) {
				if($('input[type=radio][name=civilite]:checked').length == 0) {
					// alert('Vous devez choisir votre sexe');
					alertUser('Vous devez choisir votre sexe', 'danger', $('#error_civilite'));
					$('#civilite_FORMGROUP').addClass('has-error');
					return false;
				}
			}

			// Les classes déterminent les vérifications		   
			if(form[i].className!=""){ 
				var classes = form[i].className.split(' ');
				var max_c   = classes.length;

				for(c=0;c<max_c;c++){
	                
					if(c==0){						
						var field_label = classes[c].replace(/_Q_/g,"'"); 
						field_label     = field_label.replace(/_/g," ");						
					}

	                switch(classes[c]){
				    	case 'required':
				    			form[i].requis = true;
					       		switch(form[i].type){
					       			case 'checkbox':
					       				if(form[i].checked==false) {
					       				    // alert(field_label.capitalize());
					       				    alertUser(field_label.capitalize(), $('#'+form[i].id.replace(/inp_/,'error_')));										    
										    return false;	
					       				}	
					       			break;
						       		default:
							       		if($.trim(form[i].value) == "") {									     
										    // alert('Le champ '+field_label+' est requis');
										    alertUser('Le champ '+field_label+' est requis', 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));									
										    $('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
										    form[i].focus();
										    return false;	
										}
										break;
								}
				    		break;
				    	case 'email':	
				    			if(form[i].requis==true || $.trim(form[i].value) != ""){
						       		if(!test_email(form[i].value.toLowerCase())) {	
						       			$('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
									    form[i].focus();
									    return false;	
									}									
								}
				    		break;
				    	case 'tel': // fixe et mobile
				    		if(form[i].requis==true  || $.trim(form[i].value) != "") {
				                if(!$.trim(form[i].value.match(/(\+33|0)(1|2|3|4|5|6|7)\d{8}$/))) {
				                	$('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
				                	alertUser('Le '+field_label+' n\'est pas valide', 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));	
				                    // alert('Le '+field_label+' n\'est pas valide');
								    form[i].focus();
								    return false;                    
				                }
				            }
			        		break;
			            case 'mobile':
			                if(form[i].requis==true  || $.trim(form[i].value) != "") { 
				                if(!$.trim(form[i].value.match(/(\+33|0)(6|7)\d{8}$/))) {
				                	$('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
				                	alertUser('Le '+field_label+' n\'est pas valide', 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));
				                    // alert('Le '+field_label+' n\'est pas valide');
								    form[i].focus();
								    return false; 
							    }                         
			                }
			                break;
			            case 'tel_fixe':
			            	if(form[i].requis==true  || $.trim(form[i].value) != "") {
				                if(!$.trim(form[i].value.match(/(\+33|0)(1|2|3|4|5)\d{8}$/))) {
				                	$('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
				                	alertUser('Le '+field_label+' n\'est pas valide', 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));
				                    // alert('Le '+field_label+' n\'est pas valide');
								    form[i].focus();
								    return false;                        
				                }
				            }
			                break;
				    	case 'max':				    			
				    			if(form[i].requis==true || $.trim(form[i].value) != ""){				    				
						       		var max_carac = classes[c+1].replace(/_/g,"");	
						       		max_carac=	form[i].maxlength;				       		
						       		if(form[i].value.length > max_carac) {									     
									    $('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
									    alertUser('Le champs '+field_label+' ne peut faire plus de '+max_carac+' caractères', 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));
									    // alert('Le champs '+field_label+' ne peut faire plus de '+max_carac+' caractères');
									    form[i].focus();
									    return false;	
									}
								}
				    		break;	
				    	case 'min':				    			
				    			if(form[i].requis==true || $.trim(form[i].value) != ""){				    		
						       		var min_carac = classes[c+1].replace(/_/g,"");							       						       		
						       		if(form[i].value.length < min_carac) {
						       			$('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');											    
									    alertUser('Le champs '+field_label+' doit faire minimum '+min_carac+' caractères', 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));
									    // alert('Le champs '+field_label+' doit faire minimum '+min_carac+' caractères');
									    form[i].focus();
									    return false;	
									}
								}
				    		break;		
				    	case 'inf':				    			
				    			if(form[i].requis==true || $.trim(form[i].value) != ""){
						       		var nb_inf = classes[c+1].replace(/_/g,"");							       					       		
						       		if(form[i].value > parseInt(nb_inf)) {									     
									    $('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
									    alertUser('Le champs '+field_label+' doit être inférieur ou égal à '+nb_inf, 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));
									    // alert('Le champs '+field_label+' doit être inférieur à '+nb_inf);
									    form[i].focus();
									    return false;	
									}
								}
				    		break;	
				    	case 'sup':				    			
				    			if(form[i].requis==true || $.trim(form[i].value) != ""){				    				
						       		var nb_sup = classes[c+1].replace(/_/g,"");							       						       		
						       		if(form[i].value < parseInt(nb_sup)) {		
						       			$('#'+form[i].id.replace(/inp_/,'')+'_FORMGROUP').addClass('has-error');
									    alertUser('Le champs '+field_label+' doit être du supérieur ou égal à '+nb_sup, 'danger', $('#'+form[i].id.replace(/inp_/,'error_')));
									    // alert('Le champs '+field_label+' doit être du supérieur à '+nb_sup);
									    form[i].focus();
									    return false;	
									}
								}
				    		break;	    	
			    	}    

				}

			}
			
		    switch(form[i].type){
		    	case 'password': 
			       	password_index=i;
		    		break;		    	
		    }    
		}
       	
       	// On récupère le mot de passe pour le crypter en md5
       	// Puis on met à vide le champs mot de passe et son champs de confirmation
		if(password_index!=""){
			form[password_index+1].value = MD5(form[password_index].value);
			form[password_index].value   = ""; // champs password
			form[password_index-1].value = ""; // et son champs de confirmation
		}

       	return true;
	});
});

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}


function test_email(email)
{
    var countryTLDs=/^(ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$/;
    var gTLDs=/^(aero|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org)$/;
    var basicAddress=/^(.+)@(.+)$/;
    var specialChars='\\(\\)><@,;:\\\\\\\"\\.\\[\\]';
    var validChars='\[^\\s'+specialChars+'\]';
    var validCharset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789\'-_.';
    var quotedUser='(\"[^\"]*\")';
    var atom=validChars+'+';
    var word='('+atom+'|'+quotedUser+')';
    var validUser=new RegExp('^'+word+'(\.'+word+')*$');
    var symDomain=new RegExp('^'+atom+'(\.'+atom+')*$');
    var matchArray=email.match(basicAddress);
      
    if(matchArray==null) {
        alert('L\'adresse Email semble incorrecte,\nveuillez vérifier la syntaxe.');        
        return false;
    } else {
        var user=matchArray[1];
        var domain=matchArray[2];
        for(cpt=0;cpt<user.length;cpt++) {
            if(validCharset.indexOf(user.charAt(cpt))==-1) {
                alert('L\'adresse Email contient des caractères invalides,\nveuillez vérifier la partie avant l\'arobase.');
                return false;
            }
        }

        for(cpt=0;cpt<domain.length;cpt++) {
            if(validCharset.indexOf(domain.charAt(cpt))==-1) {
                alert('L\'adresse Email contient des caractères invalides,\nveuillez vérifier la partie après l\'arobase.');
                return false;
            }
        }
       
        if(user.match(validUser)==null) {
            alert('L\'adresse Email semble incorrecte,\nveuillez vérifier la partie avant l\'arobase.');
            return false;
        }  

        var atomPat=new RegExp('^'+atom+'$');
        var domArr=domain.split('.');
        var len=domArr.length;
        
        for(cpt=0;cpt<len;cpt++) {
            if(domArr[cpt].search(atomPat)==-1) {
                alert('L\'adresse Email semble incorrecte,\nveuillez vérifier la partie après l\'arobase.');
                return false;
            }
        }

        if((domArr[domArr.length-1].length==2)&&(domArr[domArr.length-1].search(countryTLDs)==-1)) {
            alert('L\'adresse Email semble incorrecte,\nveuillez vérifier le suffixe du domaine.');
            return false;
        }
        
        if((domArr[domArr.length-1].length>2)&&(domArr[domArr.length-1].search(gTLDs)==-1)) {
            alert('L\'adresse Email semble incorrecte,\nveuillez vérifier le suffixe du domaine.');
            return false;
        }

        if((domArr[domArr.length-1].length<2)||(domArr[domArr.length-1].length>6)) {
            alert('L\'adresse Email semble incorrecte,\nveuillez vérifier le suffixe du domaine.');
            return false;
        }

        if(len<2) {
            alert('L\'adresse Email semble incorrecte.');
            return false;
        }
    }
    return true;
}
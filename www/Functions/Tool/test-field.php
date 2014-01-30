<?php
/** 
 * Test un champs et retourne un message d'erreur ou vide si le champs n'a pas d'erreur
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson
 * @version 1.1 
 * @return string
 * @creation 22/04/2013
 * @since 1.1 08/05/2013 PSR @see https://github.com/php-fig/fig-standards/tree/master/accepted
 */
namespace Functions\Tool;

function testField($field_value, $field_name, $type = '', $max = 999, $min = 0) {
	
	$error_message = '';
	// Vide
	if((empty($field_value) || trim($field_value) == '') && $type != 'checkbox') {			    	
		$error_message = ucfirst($field_name).' est vide';
	}

	// Taille max & min
	if($error_message=='') {
		if(gettype($field_value)=="string") {
			if($max != 999 && strlen($field_value) > $max) {
				$error_message = ucfirst($field_name).' doit faire au max '.$max.' caractères.';
			}

			if($min != 0 && strlen($field_value) < $min) {
				$error_message = ucfirst($field_name).' doit faire au minimum '.$min.' caractères.';
			}

		} elseif (gettype($field_value) == "integer") {
			if($max != 999 && $field_value < $max) {
				$error_message = ucfirst($field_name).' doit être supérieur à '.$max;
			}

			if($min != 0 && $field_value > $min) {
				$error_message = ucfirst($field_name).' doit être inférieur à '.$min;			
			}
		}
	}

	// Vérif selon le type
	if($error_message == '') {	        
        switch ($type) {
        	case 'email':
        		if(!preg_match("#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,5}$#", $field_value)) {
        		    $error_message = 'Erreur dans le format de l\'email';     	
        		}
        		break;        	
			case 'tel': // fixe et mobile
                if(!preg_match("^(\+33|0)(1|2|3|4|5|6|7)\d{8}$^", $field_value)) {
                    $error_message = 'Le n° de téléphone n\'est pas valide';                    
                }
        		break;
            case 'mobile':
                if(!preg_match("^(\+33|0)(6|7)\d{8}$^", $field_value)) {
                    $error_message = 'Le n° de téléphone mobile n\'est pas valide';                    
                }
                break;
            case 'tel_fixe':
                if(!preg_match("^(\+33|0)(1|2|3|4|5)\d{8}$^", $field_value)) {
                    $error_message = 'Le n° de téléphone fixe n\'est pas valide';                    
                }
                break;
			case 'conf_mdp':
				global $password;
        		if($field_value != $password) {
        			$error_message = 'Les mots de passe ne correspondent pas';
        		}
        		break;
        	case 'int':
        		if(is_nan($field_value)) {
        		    $error_message = ucfirst($field_name).' doit être un nombre';     	
        		}	
        		break;
        	case 'string':
        		if(gettype($field_value) != 'string') {
        		    $error_message = 'Erreur dans '.$field_name;     	
        		}	
        		break;
        	case 'checkbox':
        		if($field_value == '') {
        		    $error_message = $field_name;     	
        		}	
        		break;
            case 'radio':
                if($field_value == '') {
                    $error_message = $field_name;       
                }   
                break;
        	case 'md5':
                if(!preg_match("/^[a-f0-9]{32}$/", $field_value)) {
        		    $error_message = $field_name." n'est pas au format md5";     	
        		}	
        	    break;
        	default:        		
        		break;
        }
    }
    return $error_message;
}
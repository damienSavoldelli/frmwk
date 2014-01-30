<?php
/** 
 * Ce module affiche un formulaire de connexion
 * - Functions/Tool/test-field.php
 * - Js/Tool/md5.js
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson @ purevoyance.com
 * @version 2.0 
 * @date 03-12-2012
 * @update Frmwrk 1.2 04/2013    
 * @update Params labels etc. 1.2 07/05/2013
 */
// ------------------------------------------
// ---- Liste des paramètres disponibles ----
// ------------------------------------------
//
// *************** FORMULAIRE ***************
// $param['css'] // Feuille de style
// $param['action'] // Lien action
// $param['label'] // Labels
//     $param['label']['email']
//     $param['label']['password']
//     $param['label']['forget_password']
//     $param['label']['signup']
//     $param['label']['signin']
//     $param['label']['error_connexion']
//     $param['label']['TYPE'] // type de label ouside ou inside
// $param['link'] // Liens
//     $param['link']['forget_password']
//     $param['link']['signin']
// $param['placeholder'] // Placeholder, true ou false
//     $param['placeholder']['email'] // true ou false
//     $param['placeholder']['password'] // true ou false
//
// *************** TRAITEMENT ***************
// $param['class_account'] // Classe compte utilisée
// $param['redirect'] // Url de redirection en cas de connexion 
// $param['session']
// $param['session']['session_name']
// $param['session']['end_session_url']
// $param['session']['session_start']


// Inclusion du fichier de langue
$lang = "fre";
if(isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}
require_once "Module/Base-auth/lang/".$lang."/lang.php";

// BOF Modification des labels par défaut ---- ---- ---- ----
$labelEMAIL          = $LANG["labelEMAIL"];
$labelPASSWD         = $LANG["labelPASSWD"];
$labelFORGETPASSWD   = $LANG["labelFORGETPASSWD"];
$labelSIGNUP         = $LANG["labelSIGNUP"];
$labelSIGNIN         = $LANG["labelSIGNIN"];
$labelERRORCONNEXION = $LANG['labelERRORCONNEXION'];
$labelTYPE           = 'outside';

if(isset($param['label'])) {
    if(isset($param['label']['email']))            $labelEMAIL        = $param['label']['email'];
    if(isset($param['label']['password']))         $labelPASSWD       = $param['label']['password'];
    if(isset($param['label']['forget_password']))  $labelFORGETPASSWD = $param['label']['forget_password'];
    if(isset($param['label']['signup']))           $labelSIGNUP       = $param['label']['signup'];
    if(isset($param['label']['signin']))           $labelSIGNIN       = $param['label']['signin'];
    if(isset($param['label']['error_connexion']))  $labelSIGNIN       = $param['label']['error_connexion'];
    if(isset($param['label']['TYPE']))             $labelTYPE         = $param['label']['TYPE'];
}

$this->set_var('labelEMAIL',          $labelEMAIL);
$this->set_var('labelPASSWD',         $labelPASSWD);
$this->set_var('labelFORGETPASSWD',   $labelFORGETPASSWD);
$this->set_var('labelSIGNUP',         $labelSIGNUP);
$this->set_var('labelSIGNIN',         $labelSIGNIN);
$this->set_var('labelERRORCONNEXION', $labelERRORCONNEXION);
$this->set_var('PASSWORD',            $labelPASSWD);

$this->set_var('HIDDEN_ERRORCONNEXION', 'none');

// EOF Modification des labels par défaut ---- ---- ---- ----

// BOF Placeholder or not placeholder ---- ---- ---- ----
$placeholderEMAIL  = false;
$placeholderPASSWD = false;
if(isset($param['placeholder'])) {
    if($param['placeholder'] == true) {
        $placeholderEMAIL   = true;    
        $placeholderPASSWD  = true;
    } elseif($param['placeholder'] == false) {
        $placeholderEMAIL   = false;    
        $placeholderPASSWD  = false;
    }
    if(isset($param['placeholder']['email']))      $placeholderEMAIL        = $param['placeholder']['email'];
    if(isset($param['placeholder']['password']))   $placeholderPASSWD       = $param['placeholder']['password'];
}

if($placeholderEMAIL) {
    $this->set_var('PLACEHOLDER_EMAIL', 'placeholder="'.$labelEMAIL.'"');
} else {
    $this->set_var('PLACEHOLDER_EMAIL', '');    
}

if($placeholderPASSWD) {
    $this->set_var('PLACEHOLDER_PASSWORD', 'placeholder="'.$labelPASSWD.'"');
} else {
    $this->set_var('PLACEHOLDER_PASSWORD', '');    
}

// Ajout du script pour ie
if(!$placeholderEMAIL && !$placeholderPASSWD) {
    $this->set_block($file_name_TPL, 'IF_PLACEHOLDER', '$IF_PLACEHOLDER');
    $this->set_var('$IF_PLACEHOLDER', '');
}

// EOF Placeholder or not placeholder ---- ---- ---- ----

// BOF Les labels email et password sont soit dans l'input soit dans des balises label ---- ---- ---- ----
if($labelTYPE == 'inside') {
    $this->set_block($file_name_TPL, 'LABEL_TYPE_OUTSIDE', '$LABEL_TYPE_OUTSIDE');
    $this->set_var('$LABEL_TYPE_OUTSIDE', '');
    $this->set_var('EMAIL',               $labelEMAIL);
}
// EOF Les labels email et password sont soit dans l'input soit dans des balises label ---- ---- ---- ----

// BOF Modification des liens par défaut ---- ---- ---- ----
$linkSIGNUP       = "inscription.php";
$linkFORGETPASSWD = "mot-de-passe-oublie.php";

if(isset($param['link'])) {
    if(isset($param['link']['signup']))          $linkSIGNUP        = $param['link']['signup'];
    if(isset($param['link']['forget_password'])) $linkFORGETPASSWD  = $param['link']['forget_password'];
}

$this->set_var('linkFORGETPASSWD',       $linkFORGETPASSWD);
$this->set_var('linkSIGNUP',             $linkSIGNUP);

// EOF Modification des liens par défaut ---- ---- ---- ----

// BOF Feuille de style spécifique ---- ---- ---- ----
if(isset($param['css'])) {
    $this->add_css($param['css']);
}
// EOF Feuille de style spécifique ---- ---- ---- ----

// BOF Envoie sur vers une page différente de la page actuelle en cas de tentative de connexion ---- ---- ---- ----
$LINK_ACTION = "";
if(isset($param['action'])) {
    $LINK_ACTION = $param['action'];    
}
$this->set_var('LINK_ACTION', $LINK_ACTION);
// EOF Envoie sur vers une page différente de la page actuelle en cas de tentative de connexion ---- ---- ---- ----

$this->set_var('EMAIL', '');
$this->add_js('tool/md5');

// La méthode du form ne renvoie pas vers une autre page
// On traite la connexion
if($LINK_ACTION == "") {
    if(isset($_POST['connexion'])) {      
        
        global $objAuth;

        require_once "Functions/Tool/test-field.php";
        extract($_POST);
        $this->set_var('EMAIL',    $email);
        $this->set_var('PASSWORD', $password);

        $error = false;
        
        if(Functions\Tool\testField($email, "l'adresse email", "email", 30, 5) != "") $error = true;
        if(Functions\Tool\testField($md5,   "", "md5") != "") $error = true;
        
        if(!$error) {
            
            // BOF class account ---- ---- ---- ----
            $class_account = "Classes\Tool\Account";
            if(isset($param['class_account'])) {
                // Cas où on gère les comptes avec une autre classe
                $class_account = $param['class_account'];
            }
            // EOF class account ---- ---- ---- ----

            $objAccount = new $class_account;
            // BOF class table ---- ---- ---- ----           
            if(isset($param['class_table'])) {
                // La table utilisée est différente
                $objAccount->setValue('class_table', $param['class_table']);
            }
            // EOF class table ---- ---- ---- ----

            $resultTestAuth = $objAccount->testAuth($email, $md5);
            
            if(isset($resultTestAuth[0])) {

                $session_name = '';
                $end_session_url = $_SERVER["SCRIPT_NAME"];
                $session_start = 'true';

                if(isset($param['session'])) {
                    if(isset($param['session']['session_name']))
                        $session_name    = $param['session']['session_name'];
                    
                    if(isset($param['session']['end_session_url']))
                        $end_session_url = $param['session']['end_session_url'];
                    
                    if(isset($param['session']['session_start']))
                        $session_start   = $param['session']['session_start'];

                    if(isset($objAuth)) $session_start = 'false';
                }

                $objAuth = new Classes\Tool\Auth($resultTestAuth[0]['id'], array('session_name' => $session_name, 'end_session_url' => $end_session_url, 'session_start' => $session_start));                     
                
                include_once R.PATH_FCT_TOOL."redirect.php";
                
                $redirect = $_SERVER["SCRIPT_NAME"];
                if(isset($param['redirect'])) {
                    $redirect = $param['redirect'];
                }
                
                $objAccount->getRecord($resultTestAuth[0]['id']);
                $objAccount->setValue('ts_last_co', time());
                $objAccount->updateRecord();
                Functions\Tool\redirect($redirect);

            } else {
                $this->set_var('HIDDEN_ERRORCONNEXION', "");
            }

        } else {
            $this->set_var('HIDDEN_ERRORCONNEXION', "");    
        }        
    }
}
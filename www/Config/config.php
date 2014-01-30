<?php
    /**
     * Fichier de configuration générale du site
     * @author Paul-Jean Poirson <pjpoirson@gmail.com>
     * @copyright Paul-Jean Poirson
     * @version 1.1
     * @creation 2009
     * @update 28/01/2013
     * PSR 
     * https://github.com/php-fig/fig-standards/tree/master/accepted
     */
    // Framework v 1.6.4

    /**
       BOF Configuration du site
    **/
    define('SITE_TITLE',       'FrmWK v1.6.6');
    define('DOMAINE','http://'.$_SERVER['SERVER_NAME'].'/'); //http://localhost
    define('ROOT',   'frmwk/www/'); // /frmwk/

    define('VERSION',time());    
    define('AUTHOR', ""); 
    define('ANALYTICS_CODE', '');
    define('SESSION_NAME', "MY_SESSION_NAME"); 

    if(!defined("PRODUCTION"))    define('PRODUCTION',  false);
    
    if(!defined("DEBUG_EMAIL"))   define('DEBUG_EMAIL', 'mydebugadress@test.com');

    // Base de données ----
    if($_SERVER['SERVER_NAME'] == 'localhost') {
        define('DB_SERVER','localhost');
        define('DB_USER',  'root');
        define('DB_PASS',  '');
    } else {
        define('DB_SERVER','');
        define('DB_USER',  '');
        define('DB_PASS',  '');
    }

    define('DB_NAME','frmwk');
    /**
       EOF Configuration du site
    **/

    date_default_timezone_set("Europe/Paris");

    define('TIMESTAMP',    time());
    define('EXT_TPL',      '.tpl');
    define('BASENAME',     basename($_SERVER['SCRIPT_NAME']));

    // Notification des erreurs ----
    if(!defined("ERROR_NOTIFICATION_DISPLAY") || !defined("ERROR_NOTIFICATION_LOG") || !defined("ERROR_NOTIFICATION_MAIL")) {
        if(PRODUCTION) {
            if(!defined("ERROR_NOTIFICATION_DISPLAY")) define('ERROR_NOTIFICATION_DISPLAY', false);
            if(!defined("ERROR_NOTIFICATION_LOG"))     define('ERROR_NOTIFICATION_LOG',     true);
            if(!defined("ERROR_NOTIFICATION_MAIL"))    define('ERROR_NOTIFICATION_MAIL',    true);
        } else {
            if(!defined("ERROR_NOTIFICATION_DISPLAY")) define('ERROR_NOTIFICATION_DISPLAY', true);
            if(!defined("ERROR_NOTIFICATION_LOG"))     define('ERROR_NOTIFICATION_LOG',     false);
            if(!defined("ERROR_NOTIFICATION_MAIL"))    define('ERROR_NOTIFICATION_MAIL',    false);
        }
    }

    // Déclaration des chemins
    define('PATH_RESET_CSS',        'reset.css');

    define('PATH_COMMON_CSS',       'common.css');
    define('PATH_COMMON_CSS_IE',    'commonIE.css');
    define('PATH_COMMON_CSS_IE6',   'commonIE6.css');
    define('PATH_COMMON_CSS_IE7',   'commonIE7.css');

    define('PATH_MODULE_CSS',       'module.css');
    define('PATH_MODULE_CSS_IE',    'moduleIE.css');
    define('PATH_MODULE_CSS_IE6',   'moduleIE6.css');
    define('PATH_MODULE_CSS_IE7',   'moduleIE7.css');

    define('PATH_TEMPLATE',         'Template/');
    define('PATH_TEMPLATE_COMMON',  'common/');
    define('PATH_TEMPLATE_FOOTER',  'footer/');
    define('PATH_TEMPLATE_HEADER',  'header/');
    define('PATH_TEMPLATE_MODULE',  'Module/');

    define('PATH_CONFIG',           'Config/');
    define('PATH_CLASS',            'Classes/');
    define('PATH_CLASS_TOOL',       PATH_CLASS.'Tool/');
    define('PATH_CLASS_ENGINE',     PATH_CLASS.'Engine/');
    define('PATH_FUNCTION',         'Functions/');
    define('PATH_FCT_TOOL',         PATH_FUNCTION.'Tool/');
    define('PATH_FCT_ENGINE',       PATH_FUNCTION.'Engine/');
    define('PATH_LANG',             'Lang/');
    define('PATH_IMAGES',           'Picture/');

    define('PATH_CSS',              'Css/');
    define('PATH_CSS_ALL',          'all/');
    define('PATH_CSS_IE',           'ie/');
    define('PATH_CSS_IE6',          'ie6/');
    define('PATH_CSS_IE7',          'ie7/');

    define('PATH_JS',               'Js/');
    define('PATH_SWF',              'Swf/');


    if(!defined('R')) {
        $save_root = DOMAINE.ROOT;
        str_replace("/", "", $save_root, $modif_count_PATH);
        $modif_count_PATH -=2; // http:"//"

        $save_PHP_SELF = $_SERVER['PHP_SELF'];
        str_replace("/", "", $save_PHP_SELF, $count_for_PATH);
        $count_for_PATH -= $modif_count_PATH;

        $r_include = '';
        for($i = 0; $i < $count_for_PATH-1; $i++ ){
            $r_include .= '../';
        }

        define('R', $r_include);
    }

    // Gestion de la langue ----
    if(!isset($_SESSION['lang'])){
        $_SESSION['lang'] = 'fre';
    }
    elseif(isset($_POST['change_lang'])){        
        if($_POST['change_lang']=='eng' 
        || $_POST['change_lang']=='fre'
        || $_POST['change_lang']=='spa'){
            $_SESSION['lang'] = $_POST['change_lang'];
        }else{
            $_SESSION['lang'] = 'fre';
        }
    }

    define('XML_FIRST_LINE', '<?xml version="1.0" encoding="UTF-8"?>');
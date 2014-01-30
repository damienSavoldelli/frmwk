<?php  

    require 'Config/config.php';
    require 'Config/loader.php';

    $obj_user = new Classes\Tool\Account();

    $params                               = array();
    $params['label']                      = array();
    $params['label']['email']             = "Email";
    $params['label']['password']          = "Mot de passe";
    $params['label']['forget_password']   = "Mot de passe oublié ?";
    $params['label']['signup']            = "Inscription";
    $params['label']['signin']            = "Connexion";
    // $params['label']['TYPE']              = 'inside';
    $params['link']                       = array();
    $params['link']['forget_password']    = 'recuperation-mot-de-passe.php';
    $params['link']['signup']             = 'inscription.php';
    $params['placeholder']                = true;
    $params['redirect']                   = "index.php";
    $params['session']                    = array();
    $params['session']['session_start']   = 'true';

    $params['session']['session_name']    = SESSION_NAME;
    $params['session']['end_session_url'] = 'index.php';
    $params['class_account']              = 'Classes\Tool\Account';

    $t->load_module('Base-auth', 'MDL_AUTH', $params);
    
    $t->display_page();
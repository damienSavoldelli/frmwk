<?php
    define("TEMPLATE_OFF", true);
    
    require 'Config/config.php';
    require 'Config/loader.php';
    
    $objAuth = new Classes\Tool\Auth("", array('session_name' => SESSION_NAME_CLIENT, 'session_start' => 'true', 'end_session_url' => 'index.php'));
    $objAuth->testAuthSession();
    $objAuth->stopAuthSession();
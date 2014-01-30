<?php 
    define("TEMPLATE_OFF", true);
    require 'Config/config.php';
    require 'Config/loader.php';
    
    $objTracking = new Classes\Tool\Tracking();
    $objTracking->cptClic();
    $objTracking->testLead();
    

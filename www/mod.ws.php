<?php 
	define("TEMPLATE_OFF", true);
    require 'Config/config.php';
    require 'Config/loader.php';
	
	$objWs = new Classes\Exemple\Webservice($_GET);
	


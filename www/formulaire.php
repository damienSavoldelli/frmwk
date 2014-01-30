<?php 

    require 'Config/config.php';
    require 'Config/loader.php';
	

	$t->add_css('general'); 
	$t->add_css('footer'); 
	$t->add_js('tool/form-validator');
   
    $SHORT_MONTH  = array('', 'Janv.','Fev.','Mars','Avril','Mai','Juin','Juillet','Août','Sept','Oct.','Nov.','Dec.');
    
    // --- BOF Initialisation ---

    // Si password field
    $t->add_js('tool/md5');

    // Select date init
    $jour    = date('d');
    $mois    = date('m');
    $annee   = date('Y');
    $heure   = date('H');
    $minutes = date('i');

    // Msg errors init
    $errors_msg = array();		  
	$errors_msg['civilite'] 	 = "";
	$errors_msg['prenom']   	 = "";
	$errors_msg['date']     	 = "";
    $errors_msg['age']           = "";
	$errors_msg['portable'] 	 = "";
	$errors_msg['email']    	 = "";
	$errors_msg['password']    	 = "";
	$errors_msg['password_conf'] = "";
	$errors_msg['accord']        = "";
	$errors_msg['conditions']    = "";	

	foreach($errors_msg as $key => $msg){
		$t->set_var($key.'_ERROR', '');			
		$t->set_var($key.'_VALUE', '');
        $t->set_var($key.'_CLASS', '');
        $t->set_var($key.'_ERROR_STYLE', 'visibility: visible; opacity: 1; display: none;'); 
	}

	// Checkbox init
	$accord     = "";
	$conditions = "";

	$t->set_var('mme_civilite_CHECKED',  '');
	$t->set_var('mlle_civilite_CHECKED', '');
	$t->set_var('m_civilite_CHECKED',    '');


	// --- EOF Initialisation ---

    // --- BOF Traitements validation formulaire ---
    if(!empty($_POST)) {

        require PATH_FCT_TOOL.'/test-field.fct.php';

		extract($_POST);

        $errors_msg['prenom']          = field_test($prenom,   'votre prénom',         'string',  25, 2);
		$errors_msg['age']             = field_test($age,      'votre age',            'int',     18, 77);
		$errors_msg['portable']        = field_test($portable, 'votre n° de portable ', 'tel',    10, 10);
		$errors_msg['email']           = field_test($email,    'votre email ',          'email',  40, 4);
		$errors_msg['password']        = field_test($password, 'votre mot de passe',    '',       15, 6);

		if($errors_msg['password']=='')
		    $errors_msg['password_conf']   = field_test($password_conf,    'votre confirmation de mot de passe', 'conf_mdp', 15, 6);
		$errors_msg['conditions'] = field_test($conditions, 'Vous devez accepter les cgu', 'checkbox');

		$t->set_var($civilite.'_civilite_CHECKED', 'checked="checked"');

		foreach($errors_msg as $key => $msg) {
			$t->set_var($key.'_ERROR', $msg);			
			$t->set_var($key.'_VALUE', eval('if(isset($'.$key.')){return $'.$key.';}else{return "";}'));
            $t->set_var($key.'_CLASS', 'has-error'); 
            $t->set_var($key.'_ERROR_STYLE', 'visibility: visible; opacity: 1; display: block;');
		}
	}
    // --- EOF Traitements validation formulaire ---

    // --- BOF Remplissage des select --- 
    // Select Jour 
    $t->set_block('BODY', 'liste_j', '$liste_j');
    for($jourCpt = 1; $jourCpt < 32; $jourCpt ++) {
        $selected = ($jour==$jourCpt) ? "selected='selected'" : "";
        
        $t->set_var('selected', $selected);
        $t->set_var('jour', $jourCpt);
        $t->parse('$liste_j', 'liste_j', true);
    }

    // Select Mois 
    $t->set_block('BODY', 'liste_m', '$liste_m');
    for($moisCpt = 1; $moisCpt < 13; $moisCpt ++) {
        $selected = ($mois == $moisCpt) ? "selected='selected'" : "";
        
        $t->set_var('selected', $selected);
        $t->set_var('mois',     $moisCpt);
        $t->set_var('mois_lbl', $SHORT_MONTH[$moisCpt]);
        $t->parse('$liste_m', 'liste_m', true);
    }
    

    // Select Année 
	$t->set_block('BODY', 'liste_a', '$liste_a');
    for($anneeCpt = 1920; $anneeCpt <= date('Y'); $anneeCpt ++) {
        $selected = ($annee==$anneeCpt) ? "selected='selected'" : "";
        
        $t->set_var('selected', $selected);
        $t->set_var('annee',    $anneeCpt);
        $t->parse('$liste_a',   'liste_a', true);
    }

	// Select Heure
    $t->set_block('BODY', 'liste_h', '$liste_h');
    for($heureCpt = 1; $heureCpt < 24; $heureCpt ++) {
        $selected = ($heure == $heureCpt) ? "selected='selected'" : "";
        
        $t->set_var('selected', $selected);
        if($heureCpt < 10) $heureCpt = "0".$heureCpt;
        $t->set_var('heure', $heureCpt);
        $t->parse('$liste_h', 'liste_h', true);
    }

    // Select Minutes
    $t->set_block('BODY', 'liste_mn', '$liste_mn');
    for($mnCpt = 0; $mnCpt < 60; $mnCpt++)
    {
        $selected = ($minutes == $mnCpt) ? "selected='selected'" : "";
        
        $t->set_var('selected', $selected);
        if($mnCpt < 10) $mnCpt = "0".$mnCpt;

        // Tranches de mn
        if($mnCpt == 00 || $mnCpt == 15 || $mnCpt == 30 || $mnCpt == 45) {
            $t->set_var('minutes', $mnCpt);
            $t->parse('$liste_mn', 'liste_mn', true);
        }
    }
    // --- EOF Remplissage des select ---

	$t->display_page();
?>
<?php 
    require 'Config/config.php';
    require 'Config/loader.php';    

    // Inclusion css DOMAINE.ROOT./Css/mon-fichier-css.css ----------------------------
    //ajoute DOMAINE.ROOT./Css/mon-fichier-css.css (pas besoin de l'extension)
    //$t->add_css('mon-fichier-css');

    // Inclusion js DOMAINE.ROOT./Js/mon-fichier-js.js --------------------------------
    //ajoute DOMAINE.ROOT./Js/mon-fichier-js.js (pas besoin de l'extension)
    //$t->add_js('mon-fichier-js');  

    $t->set_var('PAGE_TITLE', SITE_TITLE);

    // Objet et namespace -------------------------------------------------------------
    //$obj_exemple = new Classe\Exemple\Maclasse(id_record);
    //$obj_exemple->UpdateRecord();

    // Variable de template simple ----------------------------------------------------
    //$t->set_var('MYVAR_1',           'MYVAR_1_VALUE');

    // Gestion des BLOCK de template --------------------------------------------------
    // $t->set_block('BODY', 'liste', '$liste');
    // for($i=1;$i<=13;$i++)
    // {
    //     $t->set_var('I',$i);
    //     $t->parse('$liste', 'liste', true);
    // }   

    // Inclusion d'un module -----------------------------------------------------------
    // $t->load_module("Base-pagination", "MDL_PAGINATION");

    // Affichage de la page
    // Possibilité de passé un header personnalisé en premier paramètre
    // exemple: header-accueil => chargera comme header le fichier Template/common/header/header-accueil.tpl (pas besoin de l'extension)
    // par défaut c'est header-default qui est chargé
    $t->display_page();
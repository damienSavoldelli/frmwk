<?php
    /**
     * Fichier de chargement du site
     * @author Paul-Jean Poirson <pjpoirson@gmail.com>
     * @copyright Paul-Jean Poirson
     * @version 1.2
     * @creation 2009
     * @update 28/01/2013
     * @since v1.2 modification du fichier de langue par défaut
     */

    if(file_exists(R.PATH_LANG.'/'.$_SESSION['lang'].'/global.php'))
        require R.PATH_LANG.'/'.$_SESSION['lang'].'/global.php';

    // Inclusions ----
    require R.PATH_FCT_ENGINE.'/Log.php';
    require R.PATH_FCT_ENGINE.'/ErrorHandler.php';
    require R.PATH_CLASS_ENGINE.'/SplClassLoader.php';

    $old_error_handler = set_error_handler("userErrorHandler", E_ALL);

    $includePathClass = (R == "") ? null : R;
    $classLoader = new SplClassLoader('Classes\Engine',  $includePathClass);
    $classLoader->register();
    $classLoader = new SplClassLoader('Classes\Tool',    $includePathClass);
    $classLoader->register();
    // $classLoader = new SplClassLoader('Classes\Exemple', $includePathClass);
    // $classLoader->register();


    if(!defined('TEMPLATE_OFF')) {
        $t = new Classes\Engine\Template(R.PATH_TEMPLATE);

        if(file_exists(R.PATH_TEMPLATE.str_replace('.php', '', BASENAME).EXT_TPL)) {   
            // Déclaration du fichier template
            $t->set_file('BODY', str_replace('.php', '', BASENAME).EXT_TPL);

            $t->add_page_infos('categorie',   '');
            $t->add_page_infos('title',       SITE_TITLE);
            $t->add_page_infos('description', '');
            $t->add_page_infos('keywords',    '');

            // Remplissage automatique des variables de langue
            // if(isset($VAR_LANG) && BASENAME == "global.php") {
            //     $t->set_block('BODY', 'VAR_LANG', '$VAR_LANG');

            //     foreach($VAR_LANG as $key => $value) {
            //         $t->set_var("VARIABLE", $key);
            //         $t->set_var("VALUE",    $value);
            //         $t->parse('$VAR_LANG', 'VAR_LANG', true);
            //     }

            //     foreach($VAR_LANG as $key => $value) {
            //         $t->set_var($key, $value);
            //     }
            // } else {
            //     $t->set_var('$VAR_LANG', '');
            // }
            
            if(isset($VAR_LANG_GLB)) {
                foreach($VAR_LANG_GLB as $key => $value) {
                    $t->set_var($key, $value);
                }
            }
            /*
            $t->set_var('LANG_eng_SELECTED', '');
            $t->set_var('LANG_fre_SELECTED', '');
            $t->set_var('LANG_spa_SELECTED', '');
            
            $t->set_var('LANG_'.$_SESSION['lang'].'_SELECTED', ' selected="selected" '); 
            */
        } else {
            die(".tpl n'existe pas");
        }
    }
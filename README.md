Ce framework intègre:

* Html5 Boilerplate v4.2.1
* jQuery v1.11.0
* Bootstrap v3.0.3 (less)

* respond js
* html5shiv 
* swfobject v2

Update v1.6.6 -------------------------------
* Modification    Config/config.php 28/01/2014
* Ajout           Classes/Tool/Pagination.php 17/11/2013
* Modification    jQuery de v1.9.1 à v1.11.0
* Suppression     Skeleton / Jquery UI
* Ajout           Bootsrap / html5shiv
* page de modèle
* index + exemple de formulaire + exemple de page de connexion
* logout.php

Update 07/2013 v1.6.4 -------------------------------
* Ajout Classes/Tool/Tracking.php 01/07/2013

Update 06/2013 v1.6.2 -------------------------------
* Changement des PATH dans les header
* Config/config.php - Modification 
    - PATH_TEMPLATE_MODULE => M majuscule
    - Ajout de date_default_timezone_set("Europe/Paris");
* Class/Engine/SPDO.php - Modification - Ajout Charset UTF8 - 07/06/2013
* Module/Base-pagination - Ajout - 07/06/2013

Update 05/2013 v1.6.1 -------------------------------
* Classes/Tool/Webservice Modification 14/05/2013

Update 05/2013 v1.6 -------------------------------
* Modification Functions/Engine/log.php 07/05/2013
* Modification Functions/Tool/redirect.php 07/05/2013
* Ajout Functions/Tool/format-file-name.php 07/05/2013
* Modification module Base-auth 08/05/2013
* Modification Functions/Tool/test-field.php 08/05/2013
* Modification Classes/Tool/Account.php 08/05/2013
* Ajout Auth.sql.gz pour Classes/Tool/Auth.php 08/05/2013
* Modification Classes/Engine/SPDO.php 08/05/2013
* Modification Classes/Tool/Auth.php 08/05/2013
* Ajout DEBUG_MODE dans config.php 13/05/2013
* Modification logout.php 13/05/2013

Update 01/05/2013 v1.4.1 -------------------------------
* Modification Classes/Tool/Auth
* Modification Classes/Tool/Webservice

Update 25/04/2013 v1.3 -------------------------------
* Module Auth
* Class Auth
* Class Account

Update 16/04/2013 v1.2 -------------------------------
* Boilerplate v4.2.0
* jQuery v1.9.1
* Apple-touch icon
* balises META Facebook open graph
* balises META Twitter
* Cas appel fichier sans TEMPLATE
* Classe Webservice

Update 15/04/2013 v1.0 -------------------------------

* Ajout des espaces de nom
* Mise aux normes PSR
* Intégration de PDO

-----------------------------------------------------------------------------
-------------------------- -- | DOCUMENTATION | -- --------------------------
-----------------------------------------------------------------------------
1)..............CONFIGURATION
    1.1)............HTACCESS
    1.2)............config.php
    1.3)............BASE DE DONNEES
    1.4)............ESPACES DE NOM
    1.5)............CSS - BOOTSRAP
2)..............MODULES 

-----------------------------------------------------------------------------

1)  CONFIGURATION |
-------------------
1.1) HTACCESS |
---------------
Error 404 l.81 ErrorDocument 404 http://localhost/frmwk_v1.6.4/www/error-404.php

1.2) config.php |
-----------------
Le fichier de config se trouve dans Config/config.php

define('SITE_TITLE',       'FrmWK v1.6.6');
define('DOMAINE','http://'.$_SERVER['SERVER_NAME'].'/'); //http://localhost
define('ROOT',   'frmwk_v1.6.6/www/'); // /frmwrk_v1.6.6/

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

1.3) BASE DE DONNEES |
---------------------
Créer une nouvelle base de données frmwk ou modifier le fichier de config à votre convenance (voir paragraphe 1.2 DB_NAME)
Les informations de connexion sont à renseigner dans le fichier Config/config.php => DB_SERVER, DB_USER, DB_PASS

1.4) ESPACES DE NOM |
---------------------
AJout dans Config/loader l.27
$classLoader = new SplClassLoader('Classes\Exemple', $includePathClass);
$classLoader->register();

1.5) CSS - BOOTSRAP |
----------------------
Customiser bootsrap www\Css\bootstrap.less
Insérer boostrap dans votre feuille css globale @import "bootstrap-3.0.3/bootstrap.less";

2)  MODULES |
-------------

* paramètres *
-------------
$param = array();
Exemple d'appel de module avec paramètres 
$paramPagination = array('type' => 'html','nb_ligne' => $PAGI_NBLIGNES, 'total_ligne' => count($arrProduits), 'url_custom' => 'liste-produits-p{PAGE}');
$t->load_module("Base-pagination", "MDL_PAGINATION", $paramPagination);

* set_block à l'intérieur d'un module *
---------------------------------------
$this->set_block($file_name_TPL, 'LABEL_TYPE_OUTSIDE', '$LABEL_TYPE_OUTSIDE');
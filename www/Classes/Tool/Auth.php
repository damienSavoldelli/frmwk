<?php
 /**
 * Class de gestion des authetifications
 * La table authentification doit faire partie de la base principale
 * Nécessite un import de Auth.sql.gz 
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 2012
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1.1 25/04/2013
 * PSR => https://github.com/php-fig/fig-standards/tree/master/accepted
 */
namespace Classes\Tool;
use Classes\Engine as Engine;
use Functions\Engine as FctEngine;
use PDO;

class Auth extends Engine\Base 
{
    
    protected $class_table = "authentification";
    
    // @var varchar $id
    protected $id = '';

    // @var varchar $session_name
    protected $session_name = '';
    
    // @var varchar account_id
    protected $account_id = '';
    
    // @var varchar token
    protected $token = '';

    // @var varchar lang
    protected $lang = 'fre';
        
    // @var varchar id de la session
    protected $session_id = '';
    
    // @var varchar ip
    protected $ip = '';
    
    // @var varchar user agent
    protected $user_agent = '';

    // @var timestamp date du début de session
    protected $first_activity = '';
    
    // @var timestamp last activity
    protected $last_activity = '';

    // @var timestamp last activity
    protected $status = '';
    
    // @var url de redirection en cas de fin de session
    protected $end_session_url = "";
    
    /**
     * Constructeur     
     * @param integer $account_id
     * @param array $arrCriteria
     * string $arrCriteria['session_name']
     * string $arrCriteria['end_session_url']
     * string $arrCriteria['session_start']  
     */    
    function __construct($account_id = '', $arrCriteria = '')
    {  
        if(isset($arrCriteria['session_name'])) {
            session_name($arrCriteria['session_name']);
            $this->session_name = $arrCriteria['session_name'];
        }

        if(isset($arrCriteria['end_session_url']))
            $this->end_session_url = $arrCriteria['end_session_url'];

        if(isset($arrCriteria['session_start'])) {
            if($arrCriteria['session_start'] != 'false') {
                session_start();
            }

            if($arrCriteria['session_start'] == 'session_regenerate_id') {
                session_regenerate_id();
                FctEngine\log_("session_regenerate_id() ".session_id(), '../'.R.'/logs/auth.log');
            }
        }

        if(!defined('WS_SESSIDCRYPTED'))
            define('WS_SESSIDCRYPTED', md5(session_id()));

        if(!defined('DEBUG_MODE'))
            define('DEBUG_MODE', false);

        // En cas de connexion on paut créer l'authentification au moment de la construction
        if($account_id != '') $this->startAuthSession($account_id);
    }
        
    /**
     * Fonction d'initialisation d'une authentification
     * A appeler juste après la connexion à un compte     * 
     * @param integer $account_id     
     */
    public function startAuthSession($account_id)
    {   
        $LOGS = $account_id." --- startAuthSession(".$account_id.") l.".__LINE__;

        // Remplissage des variable de contrôle
        $this->account_id       = $account_id;
        $_SESSION['account_id'] = $account_id;
        $this->session_id       = session_id();
        $this->ip               = $_SERVER['REMOTE_ADDR'];
        $this->user_agent       = $_SERVER['HTTP_USER_AGENT'];
        $this->first_activity   = time();
        $this->last_activity    = time();
        $this->status           = 1;
        $this->token            = floor(time()*mt_rand(1,9)*1.254856);         
        
        // Infos de session
        $_SESSION['account_id']    = $this->account_id;       
        $_SESSION['token']         = $this->token; 
        $_SESSION['lang']          = $this->lang;          
        
        // On vérifie que le compte n'est pas connecté ailleurs 
        $query = "SELECT * FROM ".$this->class_table." 
                  WHERE account_id = :account_id 
                    AND session_name = :session_name
                    AND status = 1";
        $objPDOStatement = Engine\SPDO::getInstance(DB_NAME)->prepare($query); 
        
        if(! $objPDOStatement->execute(array('account_id' => $this->account_id, 'session_name' => $this->session_name))) {
            
            Engine\SPDO::getInstance(DB_NAME)->sqlErrors($objPDOStatement, 'Table: '.$this->class_table.' Classe: '.__CLASS__.'.php Function: '.__FUNCTION__.'() l.'.__LINE__);
            if(DEBUG_MODE) {
                $LOGS .= " => Erreur SQL - stopAuthSession() l.".__LINE__;
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }
            $this->stopAuthSession();
            return false;
        }

        $arrReturn = array();
        
        // Il y a déjà une session active en base pour ce compte 
        // On désactive les autres comptes connectés
        if($objPDOStatement->rowCount() != 0) {            
            $LOGS .= " => Il y a déjà une session active en base pour ce compte - Déconnexion des autres comptes";
            $objPDOStatement->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $objPDOStatement->fetch();
                                 
            $query = "UPDATE ".$this->class_table."
                      SET status = 0 
                      WHERE account_id = :account_id";
            $objPDOStatement = Engine\SPDO::getInstance(DB_NAME)->prepare($query); 
        
            if(! $objPDOStatement->execute(array('account_id' => $this->account_id))) {
                
                Engine\SPDO::getInstance(DB_NAME)->sqlErrors($objPDOStatement, 'Table: '.$this->class_table.' Classe: '.__CLASS__.'.php Function: '.__FUNCTION__.'() l.'.__LINE__);
                    
                if(DEBUG_MODE) {
                    $LOGS .= " => Erreur SQL - stopAuthSession() l.".__LINE__;
                    FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
                }

                $this->stopAuthSession();
                return false;
            }
        }
        unset($this->token);
        unset($this->end_session_url);
        $this->insertRecord();

        $LOGS .= " => Démarrage session this->session_id: ".$this->session_id." - insertRecord() l.".__LINE__;
        if(DEBUG_MODE) {
            FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
        }
    }
    
    /**
     * A utiliser pour test l'authentification sur les pages privées 
     * @param string $actionEchec Comportement en d'échec: killSession => tue la session
     * @return bolean true ou false
     */
    public function testAuthSession($actionEchec = 'killSession')
    {   echo $this->end_session_url;
        if(!isset($_SESSION['account_id']) 
        || !isset($_SESSION['token'])) {
            $LOGS = "account_id inconnu --- testAuthSession() !isset(_SESSION[account_id]) || !isset(_SESSION[token]) - stopAuthSession() l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }

            if($actionEchec == 'killSession')
                $this->stopAuthSession();

            return false;
        }

        $this->account_id    = $_SESSION['account_id'];
        $LOGS = $this->account_id." --- testAuthSession() l.".__LINE__;

        $this->token         = $_SESSION['token'];
        $this->lang          = $_SESSION['lang'];
        $this->session_id    = session_id();
        $this->ip            = $_SERVER['REMOTE_ADDR'];
        $this->user_agent    = $_SERVER['HTTP_USER_AGENT'];
        $this->last_activity = time();
        $this->status        = 1;
                
        // Test dans la base de données
        $query = "SELECT * FROM ".$this->class_table." 
                  WHERE account_id = :account_id
                    AND session_name = :session_name
                    AND status = 1
                    AND session_id = :session_id";
        $objPDOStatement = Engine\SPDO::getInstance(DB_NAME)->prepare($query); 

        if(! $objPDOStatement->execute(array('account_id' => $this->account_id, 'session_name' => $this->session_name, 'session_id' => $this->session_id))) {
            Engine\SPDO::getInstance(DB_NAME)->sqlErrors($objPDOStatement, 'Table: '.$this->class_table.' Classe: '.__CLASS__.'.php Function: '.__FUNCTION__.'() l.'.__LINE__);
            $LOGS .= " => Erreur SQL - stopAuthSession() l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }

            if($actionEchec == 'killSession')
                $this->stopAuthSession();

            return false;
        }

        $arrReturn = array();
        
        // Le compte n'est plus connecté en base
        if($objPDOStatement->rowCount() == 0) { 
            $LOGS .= " => Pas de session active en base pour ce compte this->session_id: ".$this->session_id."- stopAuthSession() l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }

            if($actionEchec == 'killSession')
                $this->stopAuthSession(); 

            return false;
        }
        
        // Le compte est connecté en base, on récupère les infos pour comparer
        if($objPDOStatement->rowCount() == 1) {
            $objPDOStatement->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $objPDOStatement->fetch();
            $LOGS .= " => Session active récupérée en base l.".__LINE__;
        } else {
            $LOGS .= " => Plusieurs sessions actives en base pour le même compte - stopAuthSession() l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }

            if($actionEchec == 'killSession')
                $this->stopAuthSession(); 

            return false;
        }

        $objPDOStatement->closeCursor();    
        $this->first_activity = $rows['first_activity'];
        $this->id = $rows['id'];

        // Si l'id de session, l'ip ou l'user agent n'est pas le même on déconnecte
        if($this->session_id != $rows['session_id']) { 
            $LOGS .= " => $this->session_id != $rows[session_id] - stopAuthSession() l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }

            if($actionEchec == 'killSession')
                $this->stopAuthSession();

            return false;
        }
        if($this->ip         != $rows['ip']) { 
            $LOGS .= " => $this->ip != $rows[ip] - stopAuthSession() l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }

            if($actionEchec == 'killSession')
                $this->stopAuthSession();

            return false;
        }
        if($this->user_agent != $rows['user_agent']) { 
            $LOGS .= " => $this->user_agent != $rows[user_agent] - stopAuthSession() l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }

            if($actionEchec == 'killSession')
                $this->stopAuthSession();

            return false;
        }
        
        $temp_token = $this->token;
        $temp_end_session_url = $this->end_session_url;
        unset($this->token);
        unset($this->end_session_url);
        
        $this->updateRecord();

        $LOGS .= " => Mise à jour session en base - updateRecord() l.".__LINE__;
        if(DEBUG_MODE) {
            FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
        }

        $this->token = $temp_token;
        $this->end_session_url = $temp_end_session_url;
        return true;
    }
        
     /**
     * Détruit le session actuelle et redirige
     */
    public function stopAuthSession()
    {   
        $redirect = 1; // Redirection ok
        if($this->end_session_url == '') $redirect = 0;

        $LOGS = $this->account_id." --- stopAuthSession(".$redirect.") l.".__LINE__;
                
        // On détruit la session et les cookies
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"],   $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_unset();
        session_destroy();
        $LOGS .= " => Destruction Session et Cookies  l.".__LINE__;

        if($this->account_id != "") {
            $query = "UPDATE ".$this->class_table."
                      SET status = 0 
                      WHERE account_id = :account_id";
            $objPDOStatement = Engine\SPDO::getInstance(DB_NAME)->prepare($query); 
        
            if(! $objPDOStatement->execute(array('account_id' => $this->account_id))) {                
                Engine\SPDO::getInstance(DB_NAME)->sqlErrors($objPDOStatement, 'Table: '.$this->class_table.' Classe: '.__CLASS__.'.php Function: '.__FUNCTION__.'() l.'.__LINE__);
                                
                return false;
            }   
        }

        if($redirect==1) {
            $LOGS .= " => redirect = 1 => Redirection ".$this->end_session_url." l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }
            header('Location: '.$this->end_session_url);            
            die();
        } else {
            $LOGS .= " => redirect = 0 =>  end_session_url:'".$this->end_session_url."' l.".__LINE__;
            if(DEBUG_MODE) {
                FctEngine\log_($LOGS, '../'.R.'/logs/auth.log');
            }
            //die('logout');
        }
    }
    
    /**
     * Test du token dans le cas de requêtes ajax
     */
    public function testToken()
    {
        if(! isset($_SESSION['token']) || ! isset($_REQUEST['token']))
            $this->stopAuthSession();
        
        if($_SESSION['token'] != $_REQUEST['token']) {
            $this->stopAuthSession();
        } else {
            return true;
        }
    }  
}
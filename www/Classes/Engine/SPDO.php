<?php
 /**
 * Singleton et PDO, on stocke une instance de PDO
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 15/04/2013
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1
 * PSR => https://github.com/php-fig/fig-standards/tree/master/accepted
 */
namespace Classes\Engine;
use PDO;
use Functions\Engine as Engine; 

class SPDO
{
    private $PDOInstance = null;
    private static $instance = null;

    private static $PARAM_hote        = DB_SERVER; // le chemin vers le serveur
    private static $PARAM_port        = '3306';
    private static $PARAM_nom_bd      = DB_NAME; // le nom de votre base de données
    private static $PARAM_utilisateur = DB_USER; // nom d'utilisateur pour se connecter
    private static $PARAM_mot_passe   = DB_PASS; // mot de passe de l'utilisateur pour se connecter

    public function __construct($PARAM_hote = '', $PARAM_port = '3306', $PARAM_nom_bd = '', $PARAM_utilisateur = '', $PARAM_mot_passe = '')
    {  
        if($PARAM_hote != '')        self::$PARAM_hote        =$PARAM_hote;
        if($PARAM_port != '3306')    self::$PARAM_port        =$PARAM_port;
        if($PARAM_nom_bd != '')      self::$PARAM_nom_bd      =$PARAM_nom_bd;
        if($PARAM_utilisateur != '') self::$PARAM_utilisateur =$PARAM_utilisateur;
        if($PARAM_mot_passe != '')   self::$PARAM_mot_passe   =$PARAM_mot_passe;

        try{
            $this->PDOInstance = new PDO('mysql:host='.self::$PARAM_hote.';port='.self::$PARAM_port.';dbname='.self::$PARAM_nom_bd.';charset=utf8', self::$PARAM_utilisateur, self::$PARAM_mot_passe);
            $this->PDOInstance->exec("set names utf8");
        }
        catch(Exception $e){
            echo 'Erreur : '.$e->getMessage().'<br />';
            echo 'N° : '.$e->getCode();
        }
    }
    
    /**
    * Crée et retourne l'objet SPDO
    *
    * @access public
    * @static
    * @param void
    * @return SPDO $instance
    */
    public static function getInstance($PARAM_nom_bd = '', $PARAM_hote = '', $PARAM_port = '3306', $PARAM_utilisateur = '', $PARAM_mot_passe = '')
    {  
        if(is_null(self::$instance) 
            || ($PARAM_hote!=''        &&  $PARAM_hote!=self::$PARAM_hote)
            || ($PARAM_port!=''        &&  $PARAM_port!=self::$PARAM_port)
            || ($PARAM_nom_bd!=''      &&  $PARAM_nom_bd!=self::$PARAM_nom_bd)
            || ($PARAM_utilisateur!='' &&  $PARAM_utilisateur!=self::$PARAM_utilisateur)
            || ($PARAM_mot_passe!=''   &&  $PARAM_mot_passe!=self::$PARAM_mot_passe)){

            if($PARAM_hote != '')        self::$PARAM_hote        =$PARAM_hote;
            if($PARAM_port != '3306')    self::$PARAM_port        =$PARAM_port;
            if($PARAM_nom_bd != '') {
                self::$PARAM_nom_bd      =$PARAM_nom_bd;
            } else {
                // Table par défaut
                self::$PARAM_nom_bd      =DB_NAME;
            }
            if($PARAM_utilisateur != '') self::$PARAM_utilisateur =$PARAM_utilisateur;
            if($PARAM_mot_passe != '')   self::$PARAM_mot_passe   =$PARAM_mot_passe;
            
            self::$instance = new SPDO($PARAM_hote, $PARAM_port, $PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
        }
        return self::$instance;
    } 

    // Prévient les utilisateurs sur le clônage de l'instance
    public function sqlErrors($objPDOStatement, $infos)
    {                
        $arrErrorsInfos = $objPDOStatement->errorInfo();

        $logs = '

'.$infos.'

Code d\'erreur SQLSTATE: '.$arrErrorsInfos[0].'
Code d\'erreur: '.$arrErrorsInfos[1].'
Message: '.$arrErrorsInfos[2].'

'.$objPDOStatement->queryString.'

';

        if(ERROR_NOTIFICATION_DISPLAY == true){
            $message =
            '<div style="text-align:left;padding:10px;border:1px solid #000;background-color:#FFB6B6;">
                <b>'.$logs.'</b>
            </div>';
            echo $message;
        }


        if(ERROR_NOTIFICATION_MAIL == true) {
            $message ='
---------------------------------------------------
'.date('d/m/Y H:i:s').' '.$_SERVER['REMOTE_ADDR'].'

  '.$_SERVER['SCRIPT_NAME'].'
  '.$_SERVER['QUERY_STRING'].'  
  '.$log;

                foreach($_REQUEST as $key => $value){
                    $message .= $key.' = "'.$value.'"
                ';
                }

                $message .=
                '

                REFERER = '.$_SERVER["HTTP_REFERER"].'

                ';


            mail(DEBUG_EMAIL, "Erreur ".date("d-m-Y H:i:s"),$message);
        }

        if(ERROR_NOTIFICATION_LOG == true) {
            Engine\log_($logs, '../'.R.'/logs/spdo/errors.log');
        }        
    }

    public function __call($method, $arguments)
    {
        // Log
        // CPT query
        return call_user_func_array(array(self::getInstance()->PDOInstance, $method), $arguments);       
    }

    // Prévient les utilisateurs sur le clônage de l'instance
    public function __clone()
    {
        trigger_error('Le clônage n\'est pas autorisé.', E_USER_ERROR);
    } 
}
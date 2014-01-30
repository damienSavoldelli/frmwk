<?php
/**
 * Classe Message
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 11/11/2013
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1.0 11/11/2013
 * @since 1.0 11/11/2013
 */

namespace Classes\Tool;
use Classes\Engine as Engine;
use Classes\Tool as Tool;
use Functions\Engine as FctEngine;
use PDO;

class Message extends Engine\Base 
{
    protected $class_table = "messages";
    
    protected $id            = "";
    protected $ts_insert     = "";
    protected $client_email  = "";
    protected $client_prenom = "";
    protected $sujet         = "";
    protected $message       = "";
    protected $lu_nonlu      = 0;
    protected $deleted       = "";
    protected $id_reponse    = 0;
    protected $id_envoyeur   = "";
    protected $id_receveur   = "";
    protected $id_coach      = "";
                    
    function __construct($id='')
    {       
        global $EXT_DB;       
        $this->class_table = $EXT_DB.$this->class_table;
            
        Engine\SPDO::getInstance();
        if($id != '') $this->getRecord($id);
    }

    /**
     * Récupère la valeur de la variable passée en paramètre
     * @var string $var
     */
    public function getValue($var)
    {
        switch($var) {
            case 'sujet':
                $return = htmlspecialchars($this->$var, ENT_NOQUOTES);
                break;            
            case 'message':
                $return  = str_replace('<br />', '{BR}', nl2br($this->$var));
                // echo '- RETURN 1 -'.$return.'- /RETURN 1 -';
                $return = htmlspecialchars($return, ENT_NOQUOTES);
                // echo '- RETURN 2 -'.$return.'- /RETURN 2 -';
                $return = str_replace('{BR}', '<br>', $return);
                // echo '- RETURN 3 -'.$return.'- /RETURN 3 -';
                break;            
            default:     
                $return = $this->$var;           
                break;
        }
        return $return;
    }
    
    /**
     * Récupère une liste de messages en fonction de filtres     
     * @param array $arrCriteria - tableau de filtre
     * @param bolean $only_count - seulement un comptage   
     */    
    public function getList($arrCriteria = '', $onlyCount = false)
    {   
        $arrQuerySecure = array();

        $query  =  "SELECT *                        
                    FROM ".$this->class_table."
                    WHERE 1";

        if(count($arrCriteria)!=0) {
            
            // id
            if(isset($arrCriteria['id'])) {
                $query .= " AND id= :id";
                $arrQuerySecure['id'] = $arrCriteria['id'];
            }

            // deleted
            if(isset($arrCriteria['deleted'])) {
                $query .= " AND deleted= :deleted";
                $arrQuerySecure['deleted'] = $arrCriteria['deleted'];
            }

            // id_receveur
            if(isset($arrCriteria['id_receveur'])) {
                $query .= " AND id_receveur= :id_receveur";
                $arrQuerySecure['id_receveur'] = $arrCriteria['id_receveur'];
            }

            // id_envoyeur
            if(isset($arrCriteria['id_envoyeur'])) {
                $query .= " AND id_envoyeur= :id_envoyeur";
                $arrQuerySecure['id_envoyeur'] = $arrCriteria['id_envoyeur'];
            }

            // id_reponse
            if(isset($arrCriteria['id_reponse'])) {
                $query .= " AND id_reponse= :id_reponse";
                $arrQuerySecure['id_reponse'] = $arrCriteria['id_reponse'];
            }

            // lu_nonlu
            if(isset($arrCriteria['lu_nonlu'])) {
                $query .= " AND lu_nonlu= :lu_nonlu";
                $arrQuerySecure['lu_nonlu'] = $arrCriteria['lu_nonlu'];
            }

            // date_insert
            // if(isset($arrCriteria['ts_insert'])) {
            //     $query .= " AND ts_insert= :ts_insert";
            //     $arrQuerySecure['ts_insert'] = $arrCriteria['ts_insert'];
            // }
                      
            // ORDER BY
            if(isset($arrCriteria['ORDER BY'])) {
                $query .= " ORDER BY ".$arrCriteria['ORDER BY'];
            }

            // LIMIT
            if(isset($arrCriteria['LIMIT'])) {
                $query .= " LIMIT ".$arrCriteria['LIMIT'];
            }
        }
        // echo $query;
        $objPDOStatement = Engine\SPDO::getInstance()->prepare($query);    
        if(!$objPDOStatement->execute($arrQuerySecure)) {
            Engine\SPDO::getInstance()->sqlErrors($objPDOStatement, 'Table: '.$this->class_table.' Classe: '.__CLASS__.'.php Function: '.__FUNCTION__.'() l.'.__LINE__);
            return false; 
        }
        
        $arrReturn = array();
               
        if($onlyCount)  return $objPDOStatement->rowCount();

        if($objPDOStatement->rowCount()!=0){            
            $cpt=0;
            $objPDOStatement->setFetchMode(PDO::FETCH_ASSOC);
            while ($message = $objPDOStatement->fetch()) {
                foreach($this as $key => $value){
                    if($key=="class_table") continue;
                    // if($key=="date_insert") $message[$key] = date('d/m/Y H:i:s', $message[$key]);
                    if($key=="message"){
                        $message[$key] = str_replace('<br />', '{BR}', nl2br($message[$key]));
                        $message[$key] = htmlspecialchars($message[$key], ENT_NOQUOTES);
                        $message[$key] = str_replace('{BR}', '<br>', $message[$key]);
                    }


                    
                    $arrReturn[$cpt][$key] = $message[$key];
                }    
                $cpt++;
            }
            $objPDOStatement->closeCursor();            
        }

        if(isset($arrCriteria['random']))
            shuffle($arrReturn);

        return $arrReturn;
    }

    // $id_reponse = 0 => message répondu / $id_reponse = 999999999999999 => pas de filtre
    public function getMessagesList($id_receveur = 0, $id_envoyeur = 0, $id_reponse = 999999999999999)
    {
        // MESSAGES_BY_PAGE à définir
        if($id_receveur!=0)
            if(intval($id_receveur)==0) return false;

        if($id_envoyeur!=0)
            if(intval($id_envoyeur)==0) return false;

        if(!isset($_GET['p'])) {
            $p=1;
        } else {
            $p=intval($_GET['p']);
            if($p==0) return false;
        }
        $limit = (($p-1)*MESSAGES_BY_PAGE).', '.MESSAGES_BY_PAGE;
                
        $arr_criteria                = array();

        if($id_receveur!=0)
            $arr_criteria['id_receveur'] = $id_receveur;
        
        if($id_envoyeur!=0)
            $arr_criteria['id_envoyeur'] = $id_envoyeur;

        if($id_reponse != 999999999999999) {
            $arr_criteria['id_reponse']  = $id_reponse; 
        }

        $arr_criteria['deleted']     = 0;        
        $arr_criteria['ORDER BY']    = "ts_insert DESC";
            
        $arrReturn['nb_messages']    = $this->getList($arr_criteria, true);
            
        if($limit !="")
            $arr_criteria['LIMIT']       = $limit;
            
        $arrReturn['messages']       = $this->getList($arr_criteria);
        $arrReturn['total_pages']    = ceil($arrReturn['nb_messages']/MESSAGES_BY_PAGE);
        
        return $arrReturn;
    }
}
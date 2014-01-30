<?php
 /**
 * Class de tracking
 * Nécessite un import de Tracking.sql.gz
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 01/07/2013
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1.0 01/07/2013
 */
namespace Classes\Tool;
use Classes\Engine as Engine;
use PDO;

class Tracking extends Engine\Base 
{
    protected $class_table    = "tracking_clic";
    protected $id             = 0;
    protected $date_clic      = 0;
    protected $ip             = "";
    protected $conv           = 0;
    protected $tc1            = 0;
    protected $tc2            = 0;
    protected $id_partenaire  = 0;

    function __construct($id='')
    {  
        Engine\SPDO::getInstance();
        if($id != '') $this->GetRecord($id);
    }
        
    /**
     * Ajout d'un clic    
     */
    public function cptClic()
    {
        if(isset($_COOKIE['tracking'])) return false;
        if(!isset($_REQUEST['idp'])) return false;
        $idp = intval($_REQUEST['idp']);
        $arrPartenaire = $this->getPartenaire($idp);
        if(!isset($arrPartenaire[0])) return false;
        $this->id_partenaire = $idp;
        $this->date_clic = time();

        $this->ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_REQUEST['ipClic'])) { // Ip passée en paramètre, on teste si ipv4 ou ipv6
            if(preg_match("^([0-9]{1,3}\.){3}[0-9]{1,3}$^", $_REQUEST['ipClic']) 
            || preg_match("^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$^", $_REQUEST['ipClic'])) {
                $this->ip = $_REQUEST['ipClic'];   
            }   
        }

        if(isset($_REQUEST['tc1'])) $this->tc1 = $_REQUEST['tc1'];
        if(isset($_REQUEST['tc2'])) $this->tc2 = $_REQUEST['tc2'];

        $this->insertRecord();
        setcookie('tracking', $idp.'_'.$this->id, time()+3600);

        return true;
    }  


    /**
     * Test si convertion ou pas
     */
    public function testLead()
    {
        if(!isset($_COOKIE['tracking'])) return false;
        $arr_temp = explode('_', $_COOKIE['tracking']);
        $id_partenaire = $arr_temp[0];
        $id_clic       = $arr_temp[1];
        
        $this->getRecord($id_clic);
        if($this->id_partenaire != $arr_temp[0]) return false;
        if($this->conv != 0) return false;
        $this->conv = 1;
        $this->updateRecord();
        return true;
    }  

    /**
     * Récupération d'un partenaire    
     */
    public function getPartenaire($idp)
    {
        $query = "SELECT id FROM tracking_partenaire
                  WHERE id = :id";

        $objPDOStatement = Engine\SPDO::getInstance()->prepare($query);       
                   
        if(!$objPDOStatement->execute(array('id' => $idp))) {
            SPDO::getInstance()->sqlErrors($objPDOStatement, 'Table: '.$this->class_table.' Classe: '.__CLASS__.'.php Function: '.__FUNCTION__.'() l.'.__LINE__);
            return false; 
        }
        
        $arrReturn = array();

        if($objPDOStatement->rowCount() != 0) {
            $objPDOStatement->setFetchMode(PDO::FETCH_ASSOC);
            $arrReturn[0] = $objPDOStatement->fetch();
        }
        $objPDOStatement->closeCursor();

        return $arrReturn;
    }
}
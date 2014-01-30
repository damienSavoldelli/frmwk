<?php
 /**
 * Class de gestion de compte
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 2012
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1.1.1 24/04/2013
 * @since 1.1 2 04/04/2013 PSR @see https://github.com/php-fig/fig-standards/tree/master/accepted
 * @since 1.1.2 08/05/2013 suppression de certaines propriétés
 * @since 1.1.4 29/01/2014 Modifications propriétés de base + nom de la table
*/
namespace Classes\Tool;
use Classes\Engine as Engine;
use PDO;

class Account extends Engine\Base 
{
    
    protected $class_table    = "account";
    
    protected $id             = "";
    protected $email          = "";
    protected $password       = "";  
    protected $mobile         = "";   
    protected $optin          = 0;
    protected $double_optin   = 0;
    protected $banni          = 0;
        
    function __construct($id='')
    {  
        Engine\SPDO::getInstance();
        if($id != '') $this->GetRecord($id);
    }

    /**
     * Tests email / password     
     * @param string $email - email du membre
     * @param string $password - mot de passe du membre     
     */
    public function testAuth($email, $password)
    {
        $query = "SELECT id FROM ".$this->class_table."
                  WHERE password = :password
                  AND   email    = :email
                  AND   banni    = 0";

        $objPDOStatement = Engine\SPDO::getInstance()->prepare($query);       
                   
        if(!$objPDOStatement->execute(array('password' => $password, 'email' => $email))) {
            Engine\SPDO::getInstance()->sqlErrors($objPDOStatement, 'Table: '.$this->class_table.' Classe: '.__CLASS__.'.php Function: '.__FUNCTION__.'() l.'.__LINE__);
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
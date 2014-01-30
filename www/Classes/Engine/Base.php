<?php
/**
 * Cette classe permet de gérer des objets par / à leur enregistrement en database
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 26/01/2012
 * @copyright Paul-Jean Poirson (c) 2012
 * @version 1.1
 * @update 15/04/2013
 * namespace, PSR, PDO
 * https://github.com/php-fig/fig-standards/tree/master/accepted
 */
namespace Classes\Engine;
use PDO;

class Base
{    
    protected $class_table = "";

    /**
     * Affecte une valeur à une variable
     * @var string $var
     * @var string $value     
     */
    public function setValue($var, $value)
    {
        $this->$var = $value;
    }

    /**
     * Récupère la valeur de la variable passée en paramètre
     * @var string $var
     */
    public function getValue($var)
    {
        return $this->$var;
    }

    /**
     * Remplit les variables de classe par les valeurs récupérées en base
     * @var array $fetch_assoc
     */
    public function dbToObjectVar($fetch_assoc)
    {
        foreach($fetch_assoc as $key => $value){
            $this->$key = $value;
        }
    }

    /**
     * Récupère un enregistrement en base 
     * et affecte la valeur de ses champs aux propriétés de l'objet
     * @var interger $id
     */
    public function getRecord($id = '')
    {   
        if($id=='')$id = $this->id;
        if(intval($id)=='')die('');

        $this->id = $id;
                
        $query     = "SELECT * FROM ".$this->class_table." WHERE id=".SPDO::getInstance()->quote($this->id, PDO::PARAM_INT);
        $resultats = SPDO::getInstance()->query($query);

        if(!$resultats) return false;

        if($resultats->rowCount()!=0){
            $resultats->setFetchMode(PDO::FETCH_ASSOC);
            $this->dbToObjectVar($resultats->fetch());

            $resultats->closeCursor();
            return true;
        }else{
            $resultats->closeCursor();            
            return false;
        }
    }

    /**
     * Insert un nouvel enregistrement en base
     */
    public function insertRecord()
    {   
        $array_query = array();

        $query  = "INSERT INTO ".$this->class_table."
                (";

        $cpt = 0;
        foreach($this as $key => $value){
            if($key=="id" || $key=="class_table") continue; // id is auto-incremented

            if($cpt!=0) $query  .= ', ';
            $cpt++;
            $query  .= "`".$key."`";
        }
        
        $query  .= ') VALUES
                (';
                  
        $cpt = 0;
        foreach($this as $key => $value){
            if($key=="id" || $key=="class_table") continue; // id is auto-incremented

            if($cpt!=0) $query  .= ', ';
            $query .= ':'.$key;
            $cpt++;
            
            $array_query[$key] = $value;                        
        }

        $query  .= ')';        
        $query = SPDO::getInstance()->prepare($query);        
        
        if($query->execute($array_query)){
            $this->id = SPDO::getInstance()->lastInsertId();
            return true;
        }else{
            return false;
        }             
    }
    
    /**
     * Update de l'enregistrement
     */
    public function updateRecord()
    {
        $array_query = array();

        $cpt=0;
        $query  = "UPDATE ".$this->class_table."
                    SET ";
                
        foreach($this as $key => $value){
            if($key=="id" || $key=="class_table") continue; // id is auto-incremented

            if($cpt!=0) $query  .= ', ';
            $cpt++;
            $query  .= '`'.$key.'`= :'.$key;

            $array_query[$key] = $value;
        }

        $query  .= ' WHERE id= :id';
        $array_query['id'] = $this->id;

        $query = SPDO::getInstance()->prepare($query); 
        
        if($query->execute($array_query)){
            return true;
        }else{
            return false;
        } 
    }

    /**
     * Suppression de l'enregistrement
     */
    public function deleteRecord()
    {
        $query  = "DELETE FROM ".$this->class_table."
                   WHERE id= :id";

        $query = SPDO::getInstance()->prepare($query); 
        
        if($query->execute(array('id' => $this->id))){
            return true;
        }else{
            return false;
        } 
    }

    public function __destruct()
    {

    }
}
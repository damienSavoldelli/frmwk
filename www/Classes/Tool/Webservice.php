<?php
 /**
 * Class de webservice simple
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 16/04/2013
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1.2.1 14/05/2013
 * @update v1.2.1 
 * 14/05/2013 Ajout du format "test"
 * 16/05/2013 Modification ReturnError pour le format json
 */
namespace Classes\Tool;

class Webservice
{
    protected $uid            = "";
    protected $key            = "";
    protected $arrQuery       = "";
    protected $method         = "";
    protected $format         = "xml";
    protected $arrError       = array();
    protected $arrResponse    = array();
    protected $responseLbl    = "responses";
    protected $responseObj    = "response";

    protected $uidToken       = 45678;
    protected $stringToken    = "ws test2";   

    public function __construct($arrQuery)
    {
        $this->arrQuery = $arrQuery;
        extract($arrQuery);
        
        // Les paramètres principaux ne sont pas passés
        if(!isset($uid) || !isset($key) || !isset($method)){
            $this->arrError[] = "Erreur de variable 001.";
            $this->ReturnError();
        
        // Ou ils sont vides
        }elseif(empty($uid) || empty($key) || empty($method)){
            $this->arrError[] = "Erreur de variable 002.";
            $this->ReturnError();
        } 

        if(isset($format)){
            switch ($format){
                case 'xml':
                     $this->format = 'xml';
                     break;
                case 'json':
                     $this->format = 'json';
                     break;
                case 'test':
                     $this->format = 'test';
                    break;
                 default:
                     $this->arrError[] = "Format inexistant.";
                     $this->ReturnError();
                     break;
            } 
        }     
        
        // On enlève les espaces en début et fin de chaîne
        $this->uid    = trim(intval($uid));
        $this->key    = trim($key);
        $this->method = trim($method);
        
        // On vérifie que les propriétés ne sont pas vides suite au traitement
        if(empty($this->uid) || empty($this->key) || empty($this->method)){
            $this->arrError[] = "Erreur de variable 003.";
            $this->ReturnError();
        }

        // l'uid est un entier de 6 caractère
        if(strlen($this->uid) != 6){
            $this->arrError[] = "Erreur de variable 004.";
            $this->ReturnError();            
        } 

        $this->TestKey();
        
        if(method_exists($this, $method)){
            $this->$method();   
        }else{
            $this->arrError[] = "Cette méthode n'existe pas.";
            $this->ReturnError();  
        }


    }

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
     * Teste la clé passée en paramètre lors de l'appel
     */
    public function testKey(){ 
        if($this->uid != $this->uidToken){
            $this->arrError[] = "Erreur dans la clé 001.";
            $this->ReturnError();
        }elseif(md5($this->stringToken.$this->uid.$this->method) != $this->key){    
            $this->arrError[] = "Erreur dans la clé 002.";
            $this->ReturnError();
        }        
    }

    /**
     * Retourne les erreurs
     */
    public function returnError()
    {       
        switch($this->format){
            case 'xml':
                header ('Access-Control-Allow-Origin: *');
                header ("Content-Type:text/xml");
                echo XML_FIRST_LINE;
echo "
<response>
    <status><![CDATA[KO]]></status>
    <errors>
";
                foreach($this->arrError as $error){
            echo "
        <error>
            <![CDATA[".$error."]]>
        </error>
            ";
                }
echo "
    </errors>
</response>";
            break;
            case 'json':
                header ('Access-Control-Allow-Origin: *');
                header ('Content-Type: application/json');


                echo json_encode(array('errors' => $this->arrError));
            break;
            case 'test':
                echo "<pre>".print_r(array('errors' => $this->arrError), true)."</pre>";
            break;
        }
        die();         
    }

    /**
     * Retourne les erreurs
     */
    public function returnResponse()
    {    

        function expandTree($branch) {
            foreach($branch as $attribut => $value){
                    echo "
            <".$attribut.">";
                        if(gettype($value)!="array"){
                echo "<![CDATA[".$value."]]>";
                        } else {
                            developpTree($value);                        
                        }
                echo "</".$attribut.">";            
            }
        }



        switch($this->format){
            case 'xml':
                header ('Access-Control-Allow-Origin: *');
                header ("Content-Type:text/xml");
                echo XML_FIRST_LINE;
echo "
<response>
    <status><![CDATA[OK]]></status>
    <".$this->responseLbl.">
";
                $cpt = -1;
                foreach($this->arrResponse as $arrCpt => $Response){
                    if($cpt!=$arrCpt){
    echo "            
        <".$this->responseObj.">";
                    }
                    

                    expandTree($Response);

                    if($cpt!=$arrCpt){
    echo "            
        </".$this->responseObj.">
            ";
                        $cpt++;
                    }
                }
echo "
    </".$this->responseLbl.">
</response>";
            break;
            case 'json':
                header ('Access-Control-Allow-Origin: *');
                header ('Content-Type: application/json');

                echo json_encode($this->arrResponse);
            break;
            case 'test':
                echo "<pre>".print_r($this->arrResponse, true)."</pre>";
            break;
        }
        die();        
    }
}
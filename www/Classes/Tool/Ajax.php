<?php
 /**
 * Classe ajax extend Tool\Webservice
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 2013
 * @copyright Paul-Jean Poirson (c) 2012-20**
 * @version 1
 */
namespace Classes\Tool;
use Classes\Tool as Tool;

class Ajax extends Tool\Webservice
{
    protected $uidToken       = 666999;
    protected $stringToken    = "mysecretstring";
    protected $objAuth        = "";   
    protected $format         = "json";
    
    /**
     * Teste l'authentification
     */
    public function testAuth()
    {
        global $VAR_LANG, $objAuth;

        $this->objAuth = $objAuth;
        $this->objAuth->testAuthSession(); 
    }

    /**
     * Teste la clé passée en paramètre lors de l'appel
     */
    public function testKey() 
    { 
        if($this->objAuth == "") $this->testAuth();
        if($this->uid != $this->uidToken) {
            $this->arrError[] = "Erreur dans la clé 001.";
            $this->ReturnError();
        } elseif(md5($this->stringToken.md5(session_id()).$this->uid.$this->method) != $this->key) {    
            $this->arrError[] = "Erreur dans la clé 002.";
            $this->ReturnError();
        }        
    }
}
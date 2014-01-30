<?php
 /**
 * Class ajax
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 16/04/2013
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1.1
 */
namespace Classes\Exemple;
use Classes\Tool as Tool;

class Ajax extends Tool\Ajax 
{    
    
    public function myMethod()
    {       
        // global $VAR_LANG;

        // $this->arrQuery Contient les variables passées en POST / GET
        if(!isset($this->arrQuery['testError'])) {
            $this->arrResponse[]['var1'] = "var1 value";
            $this->arrResponse[]['var2'] = "var2 value";
            $this->ReturnResponse();
        } else {
            $this->arrError[] = "testError";
            $this->ReturnError();
        }        
    }
}
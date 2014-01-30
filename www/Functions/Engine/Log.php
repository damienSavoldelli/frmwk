<?php
/**
 * Log error
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson
 * @version 1.0
 * @creation 12-2011
 * @update 28-03-2013 
 */

namespace Functions\Engine;

function log_($log, $path)
{  
    file_put_contents($path, '
---------------------------------------------------
'.date('d/m/Y H:i:s').' '.$_SERVER['REMOTE_ADDR'].'

  '.$_SERVER['SCRIPT_NAME'].'
  '.$_SERVER['QUERY_STRING'].'  
  '.$log, FILE_APPEND);
    if(filesize($path)>5000000) {
            rename($path, $path.date('-Y-m-d'));    
    }         
}
?>

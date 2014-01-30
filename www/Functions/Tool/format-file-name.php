<?php
/** 
 * Transforme une string en chaine propre
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson
 * @version 1.1 
 * @creation 2009
 * @update 06/05/2013
 * PSR
 * https://github.com/php-fig/fig-standards/tree/master/accepted
 */
namespace Functions\Tool;

function formatFileName($str, $more = false) 
{
    if((!isset($more)) || ($more == false)) {
        $max = 2;
    } else {
        $max = $more;
    }

    $str = utf8_decode($str);         
    $str = strtr($str,utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÌÍÎÏìíîïÙÚÛÜùúûüÿÑñÇç'`"),
                                  "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeIIIIiiiiUUUUuuuuyNnCc  ");
         
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9_\.\s]/',' ',$str);
    $str = preg_replace('/[^a-z0-9_\s]\./','',trim($str));
    $str = str_replace('.',' ',$str);
    $str = str_replace('_',' ',$str);
    $str = " ".$str." ";
 
    $str = preg_replace('| .{1,'.$max.'} |', ' ', $str);
    $str = preg_replace('| .{1,'.$max.'} |', ' ', $str);
    $rep = array(" quel ", " crois ", " etes ", " quand ", " suis ", " aux "," moi ", " sont ", " quelle ", " quoi ", " mon ", " est ", " plus ", " que ", " vous ", " faites ", " par "," dans "," pour "," pas "," les "," des "," que "," une "," avec "," qui "," sur "," mes ");
    $str = str_replace($rep," ",$str);
    $str = trim($str);
    $str = preg_replace('/[\s]+/','-',$str);
 
    if(count(explode("-", $str)) >= 8) {
        $max++;
        $str = cleanUrl(str_replace('-',' ',$str), $max);
    }
    
    if(!$more) {
        if((!isset($str)) || ($str == "")) {
            $str = "punseo";
        }
    }
    return $str;
}
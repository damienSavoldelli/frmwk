<?php
/** 
 * Tronque une chaine
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson
 * @version 1.1 
 * @creation 2009
 * @update 12/02/2014
 * @since 12/02/2014
 * Ajout à Frmwk 
 * PSR
 * https://github.com/php-fig/fig-standards/tree/master/accepted
 */
namespace Functions\Tool;

/*
@param string - $text - la chaine à tronquer
@param integer - $nchars, le nombre de caractères voulus
@param string - $delim le délimiteur sur lequel se fait la coupure (le point par défaut)
*/
function truncText($text, $nchars=55, $delim = ' ') 
{
    if ($nchars < strlen($text))
    {
        $n = strpos($text, $delim, $nchars);
        if ($n !== false) 
        {
            $text = substr($text, 0, $n+1);
        }
    }
    return $text;
}

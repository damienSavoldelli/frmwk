<?php
/** 
 * Redirects and exits the script
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson
 * @creation 22/03/2011
 * @version 1.0 
 */
namespace Functions\Tool;

function redirect($url)
{
    header('Location: '.$url);
    die();
}
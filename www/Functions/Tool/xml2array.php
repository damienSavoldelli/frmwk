<?php
/** 
 * Transforme un xml en tableau
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson
 * @creation 07/11/2013
 * @version 1.0 
 */
// namespace Functions\Tool;

function XML2Array(SimpleXMLElement $parent)
{
    $array = array();

    foreach ($parent as $name => $element) {
        ($node = & $array[$name])
        && (1 === count($node) ? $node = array($node) : 1)
        && $node = & $node[];

        $node = $element->count() ? XML2Array($element) : trim($element);
    }

    return $array;
}

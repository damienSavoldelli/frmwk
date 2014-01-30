<?php
 /**
 * Class de pagination
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @creation 07/04/2012
 * @copyright Paul-Jean Poirson (c) 2013
 * @version 1 07/04/2012
 */
namespace Classes\Tool;

class Pagination {

    public $output; // Sortie HTML;
    public $nbtotal; // Nombre total de liens, de news, de n'importe quoi :)
    public $_getName; // Nom du _GET pour l'affichage des pages !
    
    public $nbmaxparpage; // Nombre d'affichage par page
    public $nbdepages; // Nombre de pages nécessaires
    public $minid; // Retourne l'ID du premier enregistrement pour la page en cours
    public $pageencours; // La page en cours
    
    public function __construct( $nbtotal, $nbmaxparpage = 10, $getName = 'page') {
        $this->nbtotal      = (int) $nbtotal;
        $this->nbmaxparpage = (int) $nbmaxparpage;
        $this->nbdepages    = ceil($this->nbtotal / $this->nbmaxparpage);
        $this->_getName     = $getName;
    }
    
    public function Generate() {
        
        unset($this->output);
        
        $this->pageencours = ( isset($_GET[$this->_getName]) && (int) $_GET[$this->_getName] > 1 ) ? (int) $_GET[$this->_getName] : 1;
        
        $this->minid = ( $this->pageencours - 1 ) * $this->nbmaxparpage;
        if ( $this->nbdepages > 1 ) {
            for ( $i=1; $i <= $this->nbdepages; $i++ ) {
                if ( $i === $this->pageencours ) {
                    $this->output[] = array('link' => FALSE, 'page' => $i);
                } else {
                    $this->output[] = array('link' => TRUE, 'page' => $i);
                }
            }
        } else {
            $this->output = NULL;
        }
    
    }

}
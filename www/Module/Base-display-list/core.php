<?php
    /** 
     * Ce module affiche une pagination
     * @author Paul-Jean Poirson <pjpoirson@gmail.com>
     * @copyright Paul-Jean Poirson
     * @version 2.0 
     * @update Frmwrk 2 07/06/2013    
     */

    // if(!isset($param['GET_var']))  $param['GET_var']        = 'p';
    // if(!isset($param['nb_ligne'])) $param['nb_ligne']       = 150;
    // if(!isset($param['total_ligne'])) $param['total_ligne'] = 150;
    /*if(!isset($param['url'])) {
        $param['url'] = DOMAINE.preg_replace('|&p=[0-9+]|', '', $_SERVER['REQUEST_URI']);
    }*/

    // Inclusion du fichier de langue
    $lang = "fre";
    if(isset($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
    }

    // require_once "Module/Base-pagination/lang/".$lang."/lang.php";

    // Remise à 0 dans le cas où le module est call 2 fois
    $this->set_var('$HEADER_TH_TABLE', '');
    $this->set_var('$LIGNES_TABLE',    '');

    if(isset($param['HEADER_TABLE'])) {

        $this->set_block($file_name_TPL, 'HEADER_TH_TABLE',  '$HEADER_TH_TABLE');
        foreach ($param['HEADER_TABLE'] as $value) {
            $this->set_var('HEADER_TABLE_LABEL', $value['label']);
            $this->parse('$HEADER_TH_TABLE', 'HEADER_TH_TABLE', true);
        }
    } else {
        $this->set_block($file_name_TPL, 'HEADER_TABLE',  '$HEADER_TABLE');
        $this->set_var('$HEADER_TABLE', '');
    }

    if(isset($param['data'])) {
        $this->set_block($file_name_TPL, 'TD_TABLE',      '$TD_TABLE');
        $this->set_block($file_name_TPL, 'LIGNES_TABLE',  '$LIGNES_TABLE');
        foreach ($param['data'] as $index_data => $data) {
            $this->set_var('$TD_TABLE', "");
            foreach($data as $field => $value){
                
                // if(isset($param['display_none'][$field])) continue;
                if(!isset($param['display'][$field])) continue;
                if(isset($param['format'][$field]['type'])){ 

                    foreach ($param['format'][$field]['type'] as $keyType => $valueType) {                      
                        switch($valueType) {
                            case 'date': 
                                    $value = date('d/m/Y H:i', $value);
                                break;
                            case 'link': 

                                    $baliselink = $param['format'][$field]['value'];
                                    if(isset($param['format'][$field]['placeholder_field'])){
                                        foreach ($param['format'][$field]['placeholder_field'] as $placeholder_field => $value_placeholder_field) {
                                            $baliselink = str_replace($placeholder_field, $param['data'][$index_data][$value_placeholder_field], $baliselink);
                                        }
                                    }

                                    $value = $baliselink.$value.'</a>';
                                break;
                        }
                    }
                }
                $this->set_var('TD_TABLE_LABEL', $value);
                $this->parse('$TD_TABLE',  'TD_TABLE', true);
            }
            if(isset($param['actions'])) {

                $actions = $param['actions']['value'];
                if(isset($param['actions']['placeholder_field'])){
                    foreach ($param['actions']['placeholder_field'] as $placeholder_field => $value_placeholder_field) {
                        $actions = str_replace($placeholder_field, $param['data'][$index_data][$value_placeholder_field], $actions);
                    }
                }

                $this->set_var('TD_TABLE_LABEL', $actions);
                $this->parse('$TD_TABLE',  'TD_TABLE', true);
            }

            $this->parse('$LIGNES_TABLE',  'LIGNES_TABLE', true);
        }



    } else {
        $this->set_block($file_name_TPL, 'LIGNES_TABLE',  '$LIGNES_TABLE');
        $this->set_var('$LIGNES_TABLE', '');
    }


    // $this->set_block($file_name_TPL, 'neg_ss_pagination', 'var_neg_ss_pagination');
    // $this->set_block($file_name_TPL, 'pos_ss_pagination', 'var_pos_ss_pagination');
    // $this->set_block($file_name_TPL, 'pagination',        'var_pagination');


    // if($param['type']!='ajax') {

    //     $objPagination = new Classes/Tool/Pagination($param['total_ligne'], $param['nb_ligne'], $param['GET_var']);
    //     $objPagination->Generate();
            
    //     $premiereLigne = $objPagination->minid;
    //     $derniereLigne = $objPagination->minid + $param['nb_ligne'];

    //     $current_p = $objPagination->pageencours;

    //     $this->set_block($file_name_TPL, 'ajax', '$ajax');
    //     $this->set_var('$ajax', '');


    //     $this->set_block($file_name_TPL, 'ss_pagination',     'var_ss_pagination');
    //     $this->set_block($file_name_TPL, 'neg_ss_pagination', 'var_neg_ss_pagination');
    //     $this->set_block($file_name_TPL, 'pos_ss_pagination', 'var_pos_ss_pagination');
    //     $this->set_block($file_name_TPL, 'pagination',        'var_pagination');
    
    //     // On vérifie que y'a bien plusieurs pages !
    //     if ( isset($objPagination->output) && is_array($objPagination->output) ) {
    
    //         //affichage du - si on est pas sur la première page
    //         if($current_p!=1) {
    //             $this->set_var('URL_NEG',$param['url'].'&p='.($current_p-1));
    //             $this->parse('var_neg_ss_pagination','neg_ss_pagination',true);
    //         } else {
    //             $this->set_var('var_neg_ss_pagination','');
    //         }
    
    //         //affichage du + si on est pas sur la dernière page
    //         if($current_p!=$objPagination->nbdepages) {
    //             $this->set_var('URL_POS',$param['url'].'&p='.($current_p+1));
    //             $this->parse('var_pos_ss_pagination','pos_ss_pagination',true);
    //         } else {
    //             $this->set_var('var_pos_ss_pagination','');
    //         }
    
    
    //         foreach ( $objPagination->output as $key ) { // On parcours le tableau
    //             if ( $key['link'] ) {
    //                 $this->set_var('SPAN_B','<span class="paginate_button">');
    //                 $this->set_var('SPAN_E','</span>');
    //             } else {                 
    //                 // Si on est sur la bonne page, on met en gras
    //                 $this->set_var('SPAN_B','<span class="paginate_active">');
    //                 $this->set_var('SPAN_E','</span>');                    
    //             }
    //             $this->set_var('URL_PAGE',$param['url'].'&p='.$key['page']);
    //             $this->set_var('NUM_PAGE',$key['page']);
    //             $this->parse('var_ss_pagination','ss_pagination',true);
    //         }
    
    //         $this->parse('var_pagination','pagination',true);
    
    //     } else {        
    //         $this->set_var('var_pos_ss_pagination','');
    //         $this->set_var('var_neg_ss_pagination','');
    //         $this->set_var('var_ss_pagination','');
    //         $this->set_var('var_pagination','');
    //     }
    // } else {
    //     /*if(!isset($param['url'])) {
    //         $param['url'] = DOMAINE.preg_replace('|&p=[0-9+]|', '', $_SERVER['REQUEST_URI']);
    //     }*/
        
    //     $FCT_LOAD_LINES = "";
    //     if(isset($param['fct'])) $FCT_LOAD_LINES = $param['fct'];

    //     $PAGINATION_ID = "pagination";
    //     if(isset($param['paginationID'])) $PAGINATION_ID = $param['paginationID'];
        
    //     $PAGINATION_CALL_ELEMENT = "";
    //     if(isset($param['paginationCallEl'])) $PAGINATION_CALL_ELEMENT = $param['paginationCallEl'];
        
    //     $LINES_BY_PAGE = 1;
    //     if(isset($param['lines_by_page'])) $LINES_BY_PAGE = $param['lines_by_page'];

    //     $this->set_var('FCT_LOAD_LINES',              $FCT_LOAD_LINES);
    //     $this->set_var('PAGINATION_ID',               $PAGINATION_ID);
    //     $this->set_var('PAGINATION_CALL_ELEMENT',     $PAGINATION_CALL_ELEMENT);
    //     $this->set_var('LINES_BY_PAGE',               $LINES_BY_PAGE);
    // }
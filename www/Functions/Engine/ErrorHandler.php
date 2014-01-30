<?php
    /**
     * Fonction de gestion des erreurs
     * @author Paul-Jean Poirson <pjpoirson@gmail.com>
     * @copyright Paul-Jean Poirson
     * @version 1.0
     * @creation 2011
     * @update 15/04/2013
     * PSR 
     * https://github.com/php-fig/fig-standards/tree/master/accepted
     */ 
    function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars)
    {
        $errortype = array (
                    E_ERROR           => "E_ERROR",
                    E_WARNING         => "Warning",
                    E_PARSE           => "Parse Error",
                    E_NOTICE          => "E_NOTICE",
                    E_CORE_ERROR      => "E_CORE_ERROR",
                    E_CORE_WARNING    => "E_CORE_WARNING",
                    E_COMPILE_ERROR   => "E_COMPILE_ERROR",
                    E_COMPILE_WARNING => "E_COMPILE_WARNING",
                    E_USER_ERROR      => "E_USER_ERROR",
                    E_USER_WARNING    => "E_USER_WARNING",
                    E_USER_NOTICE     => "E_USER_NOTICE",
                    E_STRICT          => "E_STRICT"
                    );

        if(ERROR_NOTIFICATION_DISPLAY==true){
            $message =
            '<div style="text-align:left;padding:10px;border:1px solid #000;background-color:#FFB6B6;">
                <b>'.$errortype[$errno].'</b><br />
                    '.$errmsg.'<br />
                <b> in '.$filename.' on L.'.$linenum.'</b>
            </div>';
            echo $message;
        }


        if(ERROR_NOTIFICATION_MAIL==true){
            $message =
            '   '.date('d/m H:i:s').
            '
                PHP_SELF = '.$_SERVER["PHP_SELF"].'

                '.$errortype[$errno].'
                '.$errmsg.'
                l. '.$linenum.' in '.$filename.'

                VARIABLES
                ';

                foreach($_REQUEST as $key => $value){
                    $message .= $key.' = "'.$value.'"
                ';
                }

                $message .=
                '

                REFERER = '.$_SERVER["HTTP_REFERER"].'

                ';
            mail(DEBUG_EMAIL,"Erreur ".date("d-m-Y H:i:s"),$message);
        }

        if(ERROR_NOTIFICATION_LOG == true){
            $error_details =
'
PHP_SELF = '.$_SERVER["PHP_SELF"].'
'.$errortype[$errno].'
'.$errmsg.'
l. '.$linenum.' in '.$filename.'

';

            if(isset($_SERVER["HTTP_REFERER"])){
                $error_details .= '
REFERER = '.$_SERVER["HTTP_REFERER"].'

';
            }
$error_details .= '
VARIABLES
';

            foreach($_REQUEST as $key => $value){
                $error_details .= $key.' = "'.$value.'"
';
            }

            $error = $errmsg.' l.'.$linenum.' in '.$filename.'('.$_SERVER['REQUEST_URI'].' '.$_SERVER['REMOTE_ADDR'].')
';
            $save_PHP_SELF = $_SERVER['PHP_SELF'];
            str_replace("/", "", $save_PHP_SELF, $count_for_PATH);

            //include_once R."function/fct-log.php";
            Functions\Engine\log_($error,         R."../logs/website-errors.log");
            Functions\Engine\log_($error_details, R."../logs/website-errors-details.log");
        }
    }
?>
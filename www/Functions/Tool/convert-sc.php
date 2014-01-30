<?php
/** 
 * Convertit des seconde en temps 00:00:00
 * @author Paul-Jean Poirson <pjpoirson@gmail.com>
 * @copyright Paul-Jean Poirson
 * @version 1.1 
 * @creation 2009
 * @update 06/05/2013
 * PSR
 * https://github.com/php-fig/fig-standards/tree/master/accepted
 */

namespace Functions\Tool;

function convertSc($time)
{
    /* 86400 = 3600*24 c'est à dire le nombre de secondes dans un seul jour ! donc là on vérifie si le nombre de secondes donné contient des jours ou pas */
    if ($time>=86400) {
        // Si c'est le cas on commence nos calculs en incluant les jours        
        // on divise le nombre de seconde par 86400 (=3600*24)
        // puis on utilise la fonction floor() pour arrondir au plus petit
        $jour = floor($time / 86400);
        // On extrait le nombre de jours
        $reste = $time % 86400;
        
        $heure = floor($reste / 3600);
        // puis le nombre d'heures
        $reste = $reste%3600;
        
        $minute = floor($reste/60);
        // puis les minutes
        
        $seconde = $reste%60;
        // et le reste en secondes
        // on rassemble les résultats en forme de date
        $result = $jour.'j '.$heure.'h '.$minute.'mn '.$seconde.'s';
    }
    elseif ($time < 86400 AND $time>=3600) {
        // si le nombre de secondes ne contient pas de jours mais contient des heures
        // on refait la même opération sans calculer les jours
        $heure = floor($time/3600);
        $reste = $time%3600;
        
        $minute = floor($reste/60);
        
        $seconde = $reste%60;
        
        $result = $heure.'h ';
            
        if($seconde < 10) $seconde ='0'.$seconde;
        if($minute < 10)  $minute  ='0'.$minute;

        if($minute ==0 && $seconde !=0) $result .= $minute.'mn '.$seconde.'s';
        if($minute !=0 && $seconde ==0) $result .= $minute.'mn ';
        if($minute !=0 && $seconde !=0) $result .= $minute.'mn '.$seconde.'s';
            
    }
    elseif ($time<3600 AND $time>=60) {
        // si le nombre de secondes ne contient pas d'heures mais contient des minutes        
        $minute = floor($time/60);
        $seconde = $time%60;
        $result = $minute.'mn ';
        if($seconde!=0) $result .= $seconde.'s';
    }
    elseif ($time < 60)
    // si le nombre de secondes ne contient aucune minutes
    {
        $result = $time.'s';
    }
        
    return $result;
}
<?php
    require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

    $response = '';
    $newdb= DB::getInstance()->query('SELECT * FROM `balloonshighscore` ORDER BY score desc limit 0,5');
   
        if(!$newdb->count()) {
            echo '<span class="highscore">Keine Scores!</span>'; 
        } else {
            $response .=
                    '<span class="highscore">Highscores: </span><br>';
                foreach($newdb->results() as $new) {
                   
                    $response .=
                    '<span class="highscore"><b>'.$new->name.'</b>: <i>'.$new->score.'</i></span><br>';
                }
            echo $response;
        }
        
?>   
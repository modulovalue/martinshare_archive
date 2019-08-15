<?php
    require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
    $user = new User();
    $userklasse= $user->data()->username;
    
    $stunde = Input::get('stunde');
    $welchezeit = Input::get('welchezeit');
    $newdb = DB::getInstance()->query('SELECT Distinct stunde FROM `TTT'.$userklasse.'` WHERE stunde = `'.$stunde.'` ');
    
    if($welchezeit == "beginn") {
        echo $newdb->beginn;
    } else if($welchezeit == "ende") {
        echo $newdb->ende;
    }
    

?>   
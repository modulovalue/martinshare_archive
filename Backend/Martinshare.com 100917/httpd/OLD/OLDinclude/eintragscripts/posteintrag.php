<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

if(Input::exists()) {
    $validate = new Validate();
    $validation = $validate-> check($_POST, array( 
        'dbw' => array(
            'required' => true
        ),
        'fach' => array(
            'required' => true
        ),
        'beschreibung' => array(
        ),
        'datum' => array(
            'required' => true
        )
    )); 
    
    
    if($validation->passed()) {
        $w_db = array('a' => 'arbeitstermine', 'h' => 'hausaufgaben' , 's' => 'sonstiges');
        $eintragart = strtr(Input::get('dbw'), $w_db);
        $fach            = Input::get('fach');
        $beschreibung    = Input::get('beschreibung');
        $datum           = Input::get('datum');
        
        $etwas = new EintragCRUD();
        $insert = $etwas->newEintrag($eintragart, $fach, $beschreibung, $datum);

        if($insert) {
            Session::flash('eintragerfolgreich', 'Dein Eintrag wurde gespeichert!');
        } else {
            Session::flash('erroreintrag', 'Fehler! bitte kontaktiere den Support');
        }
    } else {
        foreach($validation->errors() as $error) {
            Session::flash('erroreintrag', $error.'<br>');
        }
    }
}
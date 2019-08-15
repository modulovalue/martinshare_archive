<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

if(Input::exists()) {
    $validate = new Validate();
    $validation = $validate-> check($_POST, array( 
        'eintragid' => array(
            'required' => true,
            'letandnum' => true
        ),
        'newname' => array(
            'required' => true
        ),
        'newbeschreibung' => array(
        ),
        'neweintragdatum' => array(
            'required' => true
        )
    )); 
    
    if($validation->passed()) {
        $eintragid       = Input::get('eintragid');
        $fach            = Input::get('newname');
        $beschreibung    = Input::get('newbeschreibung');
        $datum           = Input::get('neweintragdatum');
        
        $etwas = new EintragCRUD();
        $update = $etwas->updateEintrag($eintragid, $fach, $beschreibung, $datum);

        if($update) {
            Session::flash('updateerfolgreich', 'Dein Eintrag wurde erfolgreich aktualisiert!');
        } else {
            Session::flash('errorupdateeintrag', 'Updatefehler! bitte kontaktiere den Support');
        }
    } else {
        foreach($validation->errors() as $error) {
            Session::flash('erroreintrag', $error.'<br>');
        }
    }
}
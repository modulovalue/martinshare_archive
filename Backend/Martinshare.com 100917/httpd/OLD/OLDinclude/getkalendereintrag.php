<?php

require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
$l = setlocale(LC_TIME, 'deu', 'de_DE.UTF-8');
$eintragtyplang = array("h" => "H",
                        "a" => "A",
                        "s" => "S");
$user = new User();
$userklasse= $user->data()->username;
$response = '';

$datum = Input::get('datum');
$newdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE datum  = "'.$datum.'"  ORDER BY datum DESC');

//Eintraege::getEintraege($datum, "DESC");

$response .= '<span class="mvccontentdatum" data-timestamp="'.strtotime($datum).'" data-sqldatum="'.$datum.'"> &nbsp;'.strftime("%A, %d. %B %Y",strtotime($datum)).'</span> <div style="clear: both"></div>';
if(!$newdb->count()) {
    $response .= '<span class="mvccontentbeschreibung mvcnocontent">Keine Aufgaben! </span> <div class="mvcpartyimg"> </div>';
} else {
    foreach($newdb->results() as $new) {

    $response .= '<div class="mvceintrag" data-id="'.$new->id.'">
    <span class="mvccontentfach">'.$new->name.'</span>
    <span class="mvcnotiztyp mvcnotiztyp'.$new->typ.'">'.strtr($new->typ, $eintragtyplang).'</span><br>
    <span class="mvccontentbeschreibung">'.$new->beschreibung.'</span>
    
    </div>
   
    
    ';
    
    }
} 

echo $response;
    
?>
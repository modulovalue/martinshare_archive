<?php
require 'slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once '../include/coreohnecheck.php';

$user = new User(); 
if(!$user->hasPermission("manager")) { 
    exit(404);
}

$app = new \Slim\Slim();

$app->post('/posteintrag/', function () use ($app) {
     
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
            
            $eintragart      = $app->request()->params('dbw');
            $fach            = $app->request()->params('fach');
            $beschreibung    = $app->request()->params('beschreibung');
            $datum           = $app->request()->params('datum');
            
            
            $user = new User();
            
            $insert = DB::getInstance()->insert('`eintraege`', array(
                        'userid'        => $user->data()->id,
                        'typ'           => $eintragart,
                        'name'          => $fach,
                        'beschreibung'  => $beschreibung,
                        'datum'         => escape($datum),
                        'deleted'       => 0
                        )); 
                        
    
            if($insert) {
                
                Session::flash('eintragerfolgreich', 'Dein Eintrag wurde gespeichert!');
                    
                                   
                $artdespushs = "Neu: ";
                $typ = $app->request()->params('dbw');
                
                $summary = "Neuer Eintrag";
                
                switch($typ) {
                    case "h":
                        $summary = "Neue Hausaufgabe";
                        break;
                    case "a":
                        $summary = "Neuer Arbeitstermin";
                        break;
                    case "s":
                        $summary = "Neues Sonstiges";
                        break;
                }
    
                Push::pushtoandroid("Fach: ".$fach, $beschreibung, $summary." bis: ", $datum , rand(0,2000), 0, $user->data()->id, $typ);
                Push::pushtoios($user->data()->username, $artdespushs.$fach, $datum, $beschreibung);
                
                
            } else {
                Session::flash('erroreintrag', 'Fehler! bitte kontaktiere den Support');
            }
        } else {
            foreach($validation->errors() as $error) {
                Session::flash('erroreintrag', $error.'<br>');
            }
        }
    
});




$app->post('/updateeintrag/', function () use ($app) {
     
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
        
        $eintragid       = $app->request()->params('eintragid');
        $fach            = $app->request()->params('newname');
        $beschreibung    = $app->request()->params('newbeschreibung');
        $datum           = $app->request()->params('neweintragdatum');
        $typ             = "NOPE"; 
        $oldversion      = "";
        
        $user = new User();
        
        if(isDeletable($eintragid, $user->data()->id)) {
            
            $anfragesql = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = ? AND `id` = ? ',
            array($user->data()->id, $eintragid));
        
            if($anfragesql->count() == 1) {
                 
                $olduserid          = $anfragesql->first()->userid;
                $oldid              = $anfragesql->first()->id;
                $oldtyp             = $anfragesql->first()->typ;
                $oldname            = $anfragesql->first()->name;
                $oldbeschreibung    = $anfragesql->first()->beschreibung;
                $olddatum           = $anfragesql->first()->datum;
                $olderstelldatum    = $anfragesql->first()->erstelldatum;
                $olddeleted         = $anfragesql->first()->deleted;
                $oldversion         = $anfragesql->first()->version;
                
                $insert = DB::getInstance()->insert('`eintraege_history`', array(
                                'userid'        => $olduserid,
                                'id'            => $oldid,
                                'typ'           => $oldtyp,
                                'name'          => $oldname,
                                'beschreibung'  => $oldbeschreibung,
                                'datum'         => $olddatum,
                                'erstelldatum'  => $olderstelldatum,
                                'deleted'       => $olddeleted,
                                'version'       => $oldversion
                                )); 
                                
                if(!$insert) {
                    $app->contentType('text/plain; charset=utf-8');
                    
                    $app->response->headers->set('Reason', "Fehler 111 bitte versuche es nochmal");
                    $app->response()->status(409);
                    
                } else {
                        
                    $update = DB::getInstance()->updatewhereuserid('`eintraege`', $eintragid, $user->data()->id,
                                    array(
                                    'name'          => escape($fach),
                                    'beschreibung'  => escape($beschreibung),
                                    'typ'           => $oldtyp,
                                    'datum'         => escape($datum),
                                    'version'       => ($oldversion + 1)
                                    )); 
                                    
                    if(!$update) {
                        
                        Session::flash('errorupdateeintrag', 'Updatefehler! bitte kontaktiere den Support');
                        $app->contentType('text/plain; charset=utf-8');
                        $app->response->headers->set('Reason', "Fehler 222, bitte versuche es nochmal");
                        $app->response()->status(409);
                        
                    } else {
                        
                        Session::flash('updateerfolgreich', 'Dein Eintrag wurde erfolgreich aktualisiert!');
                        $artdespushs = "Update: ";
                        $summary = "Ein Update";
                        switch($typ) {
                            case "h":
                                $artdespushs = "Update: ";
                                break;
                            case "a":
                                $artdespushs = "Update: ";
                                break;
                            case "s":
                                $artdespushs = "Update: ";
                                break;
                        }
                        
                        
                        Push::pushtoandroid("Fach: ".$fach ,$beschreibung, $summary." fällig bis: ", $datum, $id, 0, $user->data()->id, $typ );
                        Push::pushtoios($user->data()->username, $artdespushs.$fach, $datum, $beschreibung);
                    
                    }
            
                    
                }
            }
            
        } else {
            $app->contentType('text/plain; charset=utf-8');
            $app->response->headers->set('Reason', "Eintrag wurde bereits entfernt. Eintrag kann nicht aktualisiert werden");
            $app->response()->status(409);
        }
        
    } else {
        foreach($validation->errors() as $error) {
            echo $error;
            Session::flash('erroreintrag', $error.'<br>');
        }
    }
     
});


$app->post('/getversionhistory/', function () use ($app) {
    
    $userid      = $app->request()->params('userid');
    $id          = $app->request()->params('id');

    $anfragesql = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = "'.$userid.'" AND `id` = "'.$id.'" ');
    
    $anfragesqlcount = $anfragesql->count();
    
    if($anfragesqlcount == 1) {

        $anfrage = DB::getInstance()->query('
            SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted, version FROM `eintraege`       
            WHERE `userid` = "'.$userid.'" AND `id` = "'.$id.'"
            UNION
            SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted, version FROM `eintraege_history` 
            WHERE `userid` = "'.$userid.'" AND `id` = "'.$id.'"
            ORDER BY version DESC ');
        
        if($anfrage) {
            $app->response()->status(200);
            $app->response->headers->set('Test', "______________________________");
            echo json_encode($anfrage->results());
            
        } else {
            $app->response()->status(409);
            $app->response->headers->set('Reason', "Fehler 1253 bitte versuche es nochmal");
            echo "tttttt";
        } 
        
    } else {
        $app->response()->status(409);
        $app->response->headers->set('Reason', "Fehler 6724 bitte versuche es nochmal");
            echo "errrrer";
    }
});


$app->post('/deleteeintrag/', function () use ($app) {
    
    $userid      = $app->request()->params('userid');
    $id          = $app->request()->params('id');
    
    $user = new User($username);
        
    if(isDeletable($id, $userid)) {
        
        $update = DB::getInstance()->updatewhereuserid('`eintraege`', $id, $userid, array(
                        'deleted'          => 1
                        )); 
        if(!$update) {
            $app->response()->status(400);
        } else {
            //Push::pushtoandroid("Fach: ".$fach ,$beschreibung, $summary." fällig bis: ", $datum, $id, $key, $user->data()->id, $typ );
            //Push::pushtoios($user->data()->username, $artdespushs.$fach, $datum, $beschreibung);
        }
    } else {
        
        $app->contentType('text/plain; charset=utf-8');
        $app->response->headers->set('Reason', "Eintrag wurde bereits entfernt. Eintrag kann nicht entfernt werden.");
        $app->response()->status(409);
    }
        
});

 

$app->post('/getformatteddatefromtimestamp/', function () use ($app) {
    
    echo date('Y-m-d', $app->request()->params('timestampp'));
    
});



$app->post('/getkalender/', function () use ($app) {
    
    
    
    $l = setlocale(LC_TIME, 'deu', 'de_DE.UTF-8'); 
    $zeit = strtotime(Input::get('zeit'));
    if($app->request()->params('monat')) {
        $monat = $app->request()->params('monat');
    } else {
        $monat = 0;
    }
    
    $kal_datum = strtotime($monat.' month');
    $kal_tage_gesamt = date("t", $kal_datum);
    $kal_start_timestamp = mktime(0,0,0,date("n",$kal_datum),1,date("Y",$kal_datum));
    $kal_start_tag = date("N", $kal_start_timestamp);
    $kal_ende_tag = date("N", mktime(0,0,0,date("n",$kal_datum),$kal_tage_gesamt,date("Y",$kal_datum)));
    
    ?>
        
        <div class="mvcheader">
            <div class="calendarheader">
                
                <span class="backmonth">
                    <button class="buttoncal" style="color: black; text-decoration: none; padding:0px;"><</button>
                </span>
                
                <span class="mvcmonth" data-month="<?php echo strftime("%b.%y", $kal_datum); ?>">
                    <?php echo strftime("%B %Y", $kal_datum); ?>
                </span>
                
                <span class="forwardmonth">
                   <button  class="buttoncal" style="color: black; text-decoration: none;padding:0px;">></button>
                </span>
                
            </div>
        </div>
        <div class="mvcweekdays">
            <span class="mvcweekday">
            Mo
            </span>
            <span class="mvcweekday">
            Di
            </span>
            <span class="mvcweekday">
            Mi
            </span>
            <span class="mvcweekday">
            Do
            </span>
            <span class="mvcweekday">
            Fr
            </span>
            <span class="mvcweekday">
            Sa
            </span>
            <span class="mvcweekday">
            So
            </span>
        </div>
    <?php
    $user = new User();
    $userklasse= $user->data()->username;
    
    for($i = 1; $i <= $kal_tage_gesamt+($kal_start_tag-1)+(7-$kal_ende_tag); $i++)
    {
    $kal_anzeige_akt_tag = $i - $kal_start_tag;
    $kal_anzeige_heute_timestamp = strtotime($kal_anzeige_akt_tag." day", $kal_start_timestamp);
    $kal_anzeige_heute_tag = date("j", $kal_anzeige_heute_timestamp);
    
    $datum = date('Y-m-d',$kal_anzeige_heute_timestamp);
    $response =  $datum;
                    $notiz = '<div class="mvcnotizcon">';
                    $newdb = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = '.$user->data()->id.' AND datum  = "'.$datum.'" ');
                    foreach($newdb->results() as $new) {
                        
                        switch($new->typ) {
                            case 'h':
                                if (strpos($notiz,'H') === false) {
                                   $notiz .= '<span class="mvcnotiztypkleinh">H </span>';
                                }
                                break;
                            case 'a':
                                if (strpos($notiz,'A') === false) {
                                   $notiz .= '<span class="mvcnotiztypkleina">A </span>';
                                }
                                break;
                            case 's':
                                if (strpos($notiz,'S') === false) {
                                   $notiz .= '<span class="mvcnotiztypkleins">S </span>';
                                }
                                break;
                            
                        }
                        
                    }
    
        $notiz .= '</div>';
    
        if(date("N",$kal_anzeige_heute_timestamp) == 1) {
            echo " <span >";
        }
        if(date("dmY", strtotime('today')) == date("dmY", $kal_anzeige_heute_timestamp)) {
            echo " <span class=\" mvcday mvctoday  kal_aktueller_tag\" data-timestamp=\"$response\">",$kal_anzeige_heute_tag,"$notiz</span>";
        }
        else if($kal_anzeige_akt_tag >= 0 AND $kal_anzeige_akt_tag < $kal_tage_gesamt) {
            echo " <span class=\" mvcday  kal_standard_tag\" data-timestamp=\"$response\">",$kal_anzeige_heute_tag,"$notiz</span>";
        } else {
            echo " <span class=\" mvcday mvcdaybefore kal_vormonat_tag\" data-timestamp=\"$response\">",$kal_anzeige_heute_tag,"$notiz</span>";
        }
        
        if(date("N",$kal_anzeige_heute_timestamp) == 7) {
            echo " </span>";
        }
            
    }
    
    
});



$app->post('/getkalendereintrag/', function () use ($app) {
    
    
    $eintragtyplang = array("h" => "H",
                        "a" => "A",
                        "s" => "S");
    $user = new User();
    
    $response = '';
    
    $datum = $app->request()->params('datum');
    $newdb = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = '.$user->data()->id.' AND datum  = "'.$datum.'"  ORDER BY datum DESC');
    
    
    $response .= '<span class="mvccontentdatum" data-timestamp="'.strtotime($datum).'" data-sqldatum="'.$datum.'"> &nbsp;'.strftime("%A, %d. %B %Y",strtotime($datum)).'</span> <div style="clear: both"></div>';
    if(!$newdb->count()) {
        $response .= '<span class="mvccontentbeschreibung mvcnocontent">Keine Aufgaben! </span> <div class="mvcpartyimg"> </div>';
    } else {
        foreach($newdb->results() as $new) {
    
        $beschreibung = "";
        if($new->beschreibung !== "") {
            $beschreibung = $new->beschreibung;
        } 
        
        $deleted = "";
        if($new->deleted == 1) {
            $deleted = '<button data-deleted="'.$new->deleted.'" class="btn btn-default mvccontentdeleted" disabled>Gelöscht</button>';
        } else {
            $deleted = '<button data-id="'.$new->id.'" data-deleted="'.$new->deleted.'" class="btn btn-default mvccontentdeleted">Löschen</button>';
        }
        
        $verdis = $new->version == 1 ? 'disabled' : '';
        
        $historybtntext = $new->version == 1 ? 'Verlauf' : 'Verlauf ('.$new->version.')';
        
        $history = '<button data-id="'.$new->id.'" data-history="'.$new->version.'" class="btn btn-default  mvccontenthistory"'. $verdis .'>'.$historybtntext.'</button>';
      
        $title = "";
        
        if($new->deleted == 1) {
            $title = '<span class="mvccontentfachdeleted">'.$new->name.' <i style="padding-left: 2px; font-size: 12px;" class="fa fa-trash"></i></span>';
        } else {
            $title = '<span class="mvccontentfach">'.$new->name.' <i style="padding-left: 2px; font-size: 12px;" class="fa fa-pencil"></i></span>';
        }
        
        $response .= '<div class="mvceintrag" data-id="'.$new->id.'" data-timestamp="'.strtotime($datum).'" data-sqldatum="'.$datum.'">
        '.$title.'
        <span class="mvcnotiztyp mvcnotiztyp'.$new->typ.'">'.strtr($new->typ, $eintragtyplang).'</span><br>
        <span class="mvccontentbeschreibung">'.$beschreibung.'</span>
            <div class="mvcontentfooter">
            '.$deleted.'
            <div class="mvcdivider" style="float:left; width: 10%"> &nbsp
            </div>
            '.$history.'
            </div>
        </div>
       
        ';
        
        }
    } 
    
    echo $response;
    
});




$app->post('/getkalendersearchresults/', function () use ($app) {
    
    $l = setlocale(LC_TIME, 'deu', 'de_DE.UTF-8');
    $eintragtyplang = array("h" => "H",
                            "a" => "A",
                            "s" => "S");
    $user = new User();
    $response = '';
    
    $suchstring = $app->request()->params('suchstring');
    
    $newdb = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = '.$user->data()->id.' AND ( beschreibung LIKE "%'.$suchstring.'%" OR name LIKE "%'.$suchstring.'%" ) ORDER BY datum DESC');
    
    if (preg_match('/[A-Z]+[a-z]+[0-9]+/', $suchstring)) {
                
        $response .= '
        <span class="mvccontentdatum" style="box-shadow: 0px 0px 0px 0px;"> Bitte geben Sie nur Zahlen von 0-9 und Buchstaben von a-z ein. <div style="clear: both"></div>
        <br>';
                
    } else {
        
        if(!$newdb->count()) {
            $response .= '<span class="mvccontentdatum" style="box-shadow: 0px 0px 0px 0px;">'.$newdb->count().' Suchergebnis(se) für "'.$suchstring.'"</span> <div style="clear: both"></div>';
        } else {
             
            $response .= '
            <span class="mvccontentdatum" style="box-shadow: 0px 0px 0px 0px;"> '.$newdb->count().' Suchergebnis(se) für "'.$suchstring.'"</span> <div style="clear: both"></div>
            <br>';
            foreach($newdb->results() as $new) {
                
            $response .= '
            <div class="text-center">'.strftime("%A, %d. %B %Y",strtotime($new->datum)).'</div>
            
                <div class="mvceintrag" data-id="'.$new->id.'" data-timestamp="'.strtotime($new->datum).'" data-sqldatum="'.$new->datum.'">
                <span class="mvccontentfach">'.$new->name.'</span>
                <span class="mvcnotiztyp mvcnotiztyp'.$new->typ.'">'.strtr($new->typ, $eintragtyplang).'</span><br>
                <span class="mvccontentbeschreibung">'.$new->beschreibung.'</span>
            </div>
           
            ';
            }
        }
    }
    
    echo $response;
    
});


$app->run();



function isDeletable($id, $userid) {
    
    $query = DB::getInstance()->query('SELECT deleted FROM `eintraege` WHERE `id` = ? AND `userid` = ? ', array($id, $userid));
    
    if($query->count() < 1) {
        return false;
    } else {
        return $query->first()->deleted == 0 ? true : false;
    }
}



?>
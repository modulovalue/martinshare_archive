<?php
require 'slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once '../include/coreohnecheck.php';
$app = new \Slim\Slim();


$app->post('/login/', function () use ($app) {
    
        $username = $app->request()->params('username');
        $password = $app->request()->params('password');
        $key      = $app->request()->params('key');
        $device   = $app->request()->params('device');
        $pushid   = $app->request()->params('pushid');
        
        if(!isLoggedIn($key, $username)) {
            
            $newkey = generateKey();
            $loginAttempt = logUserIn($username, $password, $newkey);
            
            if(!$loginAttempt) {
                //falsche Daten
                $app->response()->status(403);
            } else {

                $user = new User($username);

                $anfrage = DB::getInstance()->query("UPDATE `MOBILE_API_LOGIN` SET `device` = '".$device."', `pushID` = '".$pushid."' WHERE `key` = '".$newkey."' and  `userid` = '".$user->data()->id."' ");
                
                $app->contentType('application/json');
                echo $loginAttempt;
            }
            
        } else {
            // bereits eingeloggt
            $app->response()->status(200);
        }
});

$app->post('/logout/', function () use ($app) {
    if(!isLoggedIn($app->request()->params('username'), $app->request()->params('key'))) {
        $app->response()->status(403);            
    } else {
        logUserOut($app->request()->params('username'), 
                   $app->request()->params('key'));
    }
});

$app->post('/getnamesuggestion/', function () use ($app) {
    if(!isLoggedIn($app->request()->params('username'), $app->request()->params('key'))) {
        $app->response()->status(403);            
    } else {
        
        $date        = $app->request()->params('date');
        $userid      = getUserID($app->request()->params('username'));
        
            
        $anfrage = DB::getInstance()->query('
        SELECT name
        FROM eintraege
        WHERE deleted = 0
        AND typ NOT LIKE "s"
        AND WEEKDAY(datum) = ?
        AND userid = ?
        AND YEAR(erstelldatum) > YEAR(NOW()) - 1 
        AND MONTH(erstelldatum) > 8 
        GROUP BY name, userid, WEEKDAY(datum)
        HAVING COUNT(name) >= 2
        ORDER BY userid DESC, WEEKDAY(datum),  COUNT(name) DESC', array(date('w', strtotime( $date)) - 1, $userid));
        
        
        $stack = array();
        
        $arraysubjects = array("Deutsch", "Englisch", "Informationstechnik", "Informatik", "Chemie", "GGK", "Geschichte",
            "Computertechnik", "Erdkunde", "CT", "IT", "Französisch", "Spanisch", "Italienisch", "Physik",
            "Mathematik", "Mathe", "Wirtschaft", "Religion Evangelisch", "Religion Katholisch", "Ethik",
            "Sport", "Frei", "BWL", "ME-M", "SK", "Projekt", "Maschinentechnik", "Elektrotechnik",
            "Biologie", "Griechisch", "Kunst", "Latein", "Musik", "Naturwissenschaften",
            "Pädagogik", "Philosophie", "Politik", "Recht", "Religion", "Sozialkunde");
            

        if(strlen($app->request()->params('name')) == 0) {
           
            if($anfrage->count() > 0) {
                foreach( $anfrage->results() as $key => $value ){
                    array_push($stack, array("name" => html_entity_decode($value->name)));
                }
                
            }
            
        } else {
            foreach( $arraysubjects as $key ) {
                array_push($stack, array("name" => $key));
            }
        }
        
        echo json_encode($stack);
          
    }
});

$app->post('/isloggedin/', function () use ($app) {
    if(!isLoggedIn($app->request()->params('username'), $app->request()->params('key'))) {
        $app->response()->status(403);            
    }
});

$app->post('/geteintraege/', function () use ($app) {
    
    $username    = $app->request()->params('username');
    $key         = $app->request()->params('key');
    $lastchanged = $app->request()->params('lastchanged');
     
    $app->contentType('application/json');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {

        $anfrage = DB::getInstance()->query('SELECT max(erstelldatum) FROM `eintraege` WHERE `userid` = '.getUserID($username).' ');
        
        if($anfrage) {
            $letztereintrag = json_decode(json_encode($anfrage->first()), true);
            $app->response->headers->set('Letztesupdate', $letztereintrag['max(erstelldatum)']);
            
            if($lastchanged == $letztereintrag['max(erstelldatum)']) {
                $app->response->headers->set('Haschanged', 0);          
                
            } else {
                $app->response->headers->set('Haschanged', 1);
            }
                $order = "DESC";
                
                //$timestamp = strtotime('-3 months');
                //$dateminusmonths = date("Y-m-d", $timestamp);
                //AND `datum` >= "'.$dateminusmonths.'" 
                $eintraege = DB::getInstance()->query('SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted, version FROM `eintraege` WHERE `userid` = '.getUserID($username).' ORDER BY datum '.$order.' ');
                $app->response->headers->set('Eintraegecount', count($eintraege->results()));
                echo json_encode($eintraege->results());
           
        } else {
            $app->response()->status(403);
        } 
        
       
    }
});

$app->post('/getactivity/', function () use ($app) {
    
    $username    = $app->request()->params('username');
    $key         = $app->request()->params('key');
    
    $app->contentType('application/json');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {
        $anfrage = DB::getInstance()->query('
            SELECT userid, typ, name, beschreibung, id, datum, erstelldatum, UNIX_TIMESTAMP(erstelldatum) as erstelldatumts, deleted, MAX( version ) AS version
            FROM (
                SELECT *
                FROM `eintraege`
                WHERE `userid` = '.getUserID($username).'
                    UNION
                SELECT *
                FROM `eintraege_history`
                WHERE `userid` = '.getUserID($username).'
            ) AS u
            GROUP BY id
            ORDER BY `u`.`erstelldatum` DESC 
            LIMIT 0,15
        ');

        $arrayOuterContainer = array();
        
        
        foreach ($anfrage->results() as $key => $value) {
            
            $arrayInnerContainer = array();
            
            $deleted = 0;
            $versionconcat = "";
            $beschreibung ="";
            $version = "1";
            foreach ($value as $key => $value2) {
                
                switch ($key) {
                    case "userid":
                        break;
                        
                    case "typ":
                        $arrayInnerContainer["typ"] = $value2;
                        $arrayInnerContainer["titletyp"] = $value2;
                        break;
                        
                    case "id":
                        $arrayInnerContainer["id"] = $value2;
                        $arrayInnerContainer["atype"] = "show";
                        $arrayInnerContainer["acontent"] = $value2;
                        break;
                        
                    case "datum": 
                        $arrayInnerContainer["datum"] = $value2;
                        break;
                    
                    case "erstelldatum": 
                        $arrayInnerContainer["erstelldatum"] = $value2;
                        break;
                        
                    case "erstelldatumts": 
                        $t = time() - intval($value2);
                        $arrayInnerContainer["vortimestamp"] = "".$t ;
                        break;
                        
                    case "name":
                        $arrayInnerContainer["name"] = $value2;
                        break;
                        
                    case "beschreibung":
                        $arrayInnerContainer["beschreibung"] = $value2;
                        break;
                        
                    case "deleted":
                        $arrayInnerContainer["deleted"] = $value2;
                        if($value2 == "1") {
                            $deleted = 1;
                        }
                        break;
                    case "version":
                        
                        $version = $value2;
                        $arrayInnerContainer["version"] = $value2;
                        if($value2 != "1") {
                            //Adds that to the title at the top
                            //$versionconcat = ", Version: $value2";
                            $versionconcat = " ";
                        }
                        break;
                }
            }
            
            //toptitle
            if($deleted == 1) {
                $arrayInnerContainer["toptitle"] = "";
                $arrayInnerContainer["titlestyle"] = "deleted";
            } else {
                if($version != "1") {
                    $arrayInnerContainer["titlestyle"] = "update";
                } else {
                    $arrayInnerContainer["titlestyle"] = "new";
                }
                $arrayInnerContainer["toptitle"] = $versionconcat;
            }
            
            
            array_push($arrayOuterContainer, $arrayInnerContainer);
        }
        
        if($anfrage) {
           // debug(json_encode($arrayOuterContainer));
            echo json_encode($arrayOuterContainer);
        } else {
            $app->response()->status(403);
        } 
        
    }
});

$app->post('/getversionhistory/', function () use ($app) {
    
    $username    = $app->request()->params('username');
    $key         = $app->request()->params('key');
    $id          = $app->request()->params('id');
     
    $app->contentType('application/json');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {
    
        $anfragesql = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = "'.getUserID($username).'" AND `id` = "'.$id.'" ');
        
        $anfragesqlcount = $anfragesql->count();
        
        if($anfragesqlcount == 1) {

            $anfrage = DB::getInstance()->query('
                SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted, version FROM `eintraege`        
                WHERE `userid` = "'.getUserID($username).'" AND `id` = "'.$id.'"
                UNION
                SELECT id, typ, name, beschreibung, datum, erstelldatum, deleted, version FROM `eintraege_history` 
                WHERE `userid` = "'.getUserID($username).'" AND `id` = "'.$id.'"
                ORDER BY version DESC ');
            
            if($anfrage) {
                $app->response()->status(200);
                $app->response->headers->set('Test', "______________________________");
                echo json_encode($anfrage->results());
                
            } else {
                $app->response()->status(409);
                $app->response->headers->set('Reason', "Fehler 1253 bitte versuche es nochmal");
            } 
            
        } else {
            $app->response()->status(409);
            $app->response->headers->set('Reason', "Fehler 6724 bitte versuche es nochmal");
        }
       
    }
});


$app->post('/getstundenplan/', function () use ($app) {
    
    $username = $app->request()->params('username');
    $key      = $app->request()->params('key');
    
    $app->contentType('text/plain');
    
    if(!isLoggedIn($username, $key)) {
        
        $app->response->setBody("http://www.martinshare.com/images/keinstundenplanvorhanden.png");
        $app->response()->status(403);
    } else {
        $domain = "http://www.martinshare.com";
        $filename = "/images/stundenplaene/".getName($username)."/stundenplan.jpg";
        if (!file_exists("../images/stundenplaene/".getName($username)."/stundenplan.jpg")) {
          $filename = "/images/keinstundenplanvorhanden.png"; 
        } 
       
        $app->response->setBody($domain.$filename);
    }
});

$app->post('/neuereintrag/', function () use ($app) {
    
    //TODO trim($str," ")
    $username     = $app->request()->params('username');
    $key          = $app->request()->params('key');
    $typ          = $app->request()->params('typ');
    $fach         = $app->request()->params('fach');
    $beschreibung = $app->request()->params('beschreibung');
    $datum        = $app->request()->params('datum');
    
    
    $app->contentType('text/plain');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {
        
        $user = new User($username);
        
        
        $anfragesql = DB::getInstance()->query("SELECT * FROM eintraege WHERE userid = ? AND typ = ? AND name = ? AND beschreibung = ? AND datum = ? ",
        array(
            getUserID($username),
            $typ,
            $fach,
            $beschreibung,
            $datum
            ));
        
        //$anfragesql = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = "'.getUserID($username).'" AND `typ` = "'.$typ.'" AND `name` = "'.$fach.'" AND `beschreibung` = "'.$beschreibung.'" AND `datum` = "'.$datum.'" ');
        
        $anfragesqlcount = $anfragesql->count();
        
        if($anfragesqlcount < 1) {
        
                //TODO make typ, datum right. Check for being the right datatype
            $insert = DB::getInstance()->insert('`eintraege`', array(
                        'userid'        => getUserID($username),
                        'typ'           => escape($typ),
                        'name'          => escape($fach),
                        'beschreibung'  => escape($beschreibung),
                        'datum'         => escape($datum),
                        'deleted'       => 0
                        )); 
            
            if(!$insert) {
                $app->response()->status(400);
            } else {
                $artdespushs = "Neu: ";
                
                $summary = "Neuer Eintrag";
                switch($typ) {
                    case "h":
                        $artdespushs = "Neu: ";
                        $summary = "Neue Hausaufgabe";
                        break;
                    case "a":
                        $artdespushs = "Neu: ";
                        $summary = "Neuer Arbeitstermin";
                        break;
                    case "s":
                        $artdespushs = "Neu: ";
                        $summary = "Neues Sonstiges";
                        break;
                        
                }
                
                Push::pushtoandroid("Fach: ".$fach, $beschreibung, $summary." bis: ", $datum , $id, $key, $user->data()->id, $typ);
                Push::pushtoios($user->data()->username, $artdespushs.$fach, $datum, $beschreibung);
            
            }
        }
    }
});

$app->post('/sendfeedback/', function () use ($app) {
    
    $username     = $app->request()->params('username');
    $key          = $app->request()->params('key');
    $message      = $app->request()->params('message');
    $device       = $app->request()->params('device');
    
    $app->contentType('text/html; charset=utf-8');
    
    $messagemaxlength = 500;
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else if(strlen($message) < 5) {
        $app->response->headers->set('Reason', "Die Nachricht ist zu kurz");
        $app->response()->status(409);
    } else {

        if(strlen($message) <= $messagemaxlength) {
            
            $insert = DB::getInstance()->insert('`mobilefeedbackmessages`', array(
                        'userid'           => getUserID($username),
                        'message'          => $message,
                        'device'           => $device
                        )); 
                        
            if(!$insert) {
                $app->response->headers->set('Reason', "Fehler 913 bitte versuche es nochmal");
                $app->response()->status(409);
            } else {
                $app->response()->status(200);
                
            }
        } else {
            $app->response->headers->set('Reason', "Bitte verkuerze die Nachricht um ". (strlen($message) - $messagemaxlength) ." Zeichen und probiere es nochmal");
            $app->response()->status(409);
        }
    }
});

$app->post('/updateeintrag/', function () use ($app) {
    
    $username     = $app->request()->params('username');
    $key          = $app->request()->params('key');
    $id           = $app->request()->params('id');
    $fach         = $app->request()->params('fach');
    $typ          = $app->request()->params('typ');
    $beschreibung = $app->request()->params('beschreibung');
    $datum        = $app->request()->params('datum');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {
        
        $user = new User($username);
        
        if(isDeletable($id, $user->data()->id)) {
            
            $anfragesql = DB::getInstance()->query('SELECT * FROM `eintraege` WHERE `userid` = "'.$user->data()->id.'" AND `id` = "'.$id.'" ');
            
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
            
            
                    //TODO validate datum und typ
                        
                    $update = DB::getInstance()->updatewhereuserid('`eintraege`', escape($id), getUserID($username), array(
                                    'name'          => escape($fach),
                                    'beschreibung'  => escape($beschreibung),
                                    'typ'           => escape($typ),
                                    'datum'         => escape($datum),
                                    'version'       => ($oldversion + 1)
                                    )); 
                    if(!$update) {
                        $app->contentType('text/plain; charset=utf-8');
                        $app->response->headers->set('Reason', "Fehler 222, bitte versuche es nochmal");
                        $app->response()->status(409);
                    } else {
                        
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
                        
                        
                        Push::pushtoandroid("Fach: ".$fach ,$beschreibung, $summary." fällig bis: ", $datum, $id, $key, $user->data()->id, $typ );
                        Push::pushtoios($user->data()->username, $artdespushs.$fach, $datum, $beschreibung);
                    
                    }
            
                    
                }
            }
            
        } else {
                    $app->contentType('text/plain; charset=utf-8');
                    $app->response->headers->set('Reason', "Eintrag wurde bereits entfernt. Eintrag kann nicht aktualisiert werden");
                    $app->response()->status(409);
        }
    }
    
});


$app->post('/deleteeintrag/', function () use ($app) {
    
    $username     = $app->request()->params('username');
    $key          = $app->request()->params('key');
    $id           = $app->request()->params('id');
    
    $app->contentType('text/plain');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
        
    } else {
        
        $user = new User($username);
        
        if(isDeletable($id, getUserID($username))) {
            
            $update = DB::getInstance()->updatewhereuserid('`eintraege`', $id, getUserID($username), array(
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
        
    }
    
});


$app->post('/letztesupdate/', function () use ($app) {
    
    $username     = $app->request()->params('username');
    $key          = $app->request()->params('key');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {
        $anfrage = DB::getInstance()->query('SELECT max(erstelldatum) FROM `eintraege` WHERE `userid` = '.getUserID($username).' ');

        if($anfrage) {
            $letztereintrag = json_decode(json_encode($anfrage->first()), true);
            $app->response->headers->set('Letztesupdate', $letztereintrag['max(erstelldatum)']);
        } else {
            $app->response()->status(400);
        }
    }
});

$app->post('/getvertretungsplan/', function () use ($app) {
    
    $username     = $app->request()->params('username');
    $key          = $app->request()->params('key');
    
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {
        
        $user = new User($username);
        
        $domain = "http://www.martinshare.com";
        
        $scanned_directory = array_diff(scandir($_SERVER['DOCUMENT_ROOT']."/vertretungsplan/".$user->data()->schule_id), array('..', '.'));
       
        $errors = array_filter($scanned_directory);


        if (!empty($errors)) {
            
            $app->response->headers->set('Pragma', "no-cache");
            $app->response->headers->set('Cache-Control', "no-cache");
            $app->response->headers->set('Seiten', count($scanned_directory));
            $app->response->headers->set('Domain', $domain);
            $app->response->headers->set('Folder', "/vertretungsplan/");
            $app->response->headers->set('Schule', $user->data()->schule_id);
            echo json_encode($scanned_directory);
        } else { 
            $emptydir = array_diff(scandir($_SERVER['DOCUMENT_ROOT']."/vertretungsplan/noplan"), array('..', '.'));
            $app->response->headers->set('Pragma', "no-cache");
            $app->response->headers->set('Cache-Control', "no-cache");
            $app->response->headers->set('Seiten', count($emptydir));
            $app->response->headers->set('Domain', $domain);
            $app->response->headers->set('Folder', "/vertretungsplan/");
            $app->response->headers->set('Schule', "noplan");
            echo json_encode($emptydir);
        }
        
        /*
        if($anfrage) {
            $letztereintrag = json_decode(json_encode($anfrage->first()), true);
            $app->response->headers->set('Letztesupdate', $letztereintrag['max(erstelldatum)']);
        } else {
            $app->response()->status(400);
        }
        */
    }
    
});

$app->post('/checkpush/', function () use ($app) {
    
    $username     = $app->request()->params('username');
    $key          = $app->request()->params('key');
    $pushkey      = $app->request()->params('pushkey');
    
    if(!isLoggedIn($username, $key)) {
        $app->response()->status(403);
    } else {
        
        $user = new User($username);
        
        //$anfrage = DB::getInstance()->query("SELECT * FROM `MOBILE_API_LOGIN` WHERE `key` = '".$key."' and `userid` = '".$user->data()->id."' and `pushID` = '".$pushkey."' ");
        
        $anfrage = DB::getInstance()->query("UPDATE `MOBILE_API_LOGIN` SET `pushID` = '".$pushkey."' WHERE `key` = '".$key."' and  `userid` = '".$user->data()->id."' ");
                
        $app->response->headers->set('Haspushid', $anfrage->count());
        echo "ok";
    }
    
});

$app->get('/token/', function () use ($app) {
   echo substr(generateKey(), 0, 20);
});

$app->run();



function generateKey() {
    return Token::generate();
}

function isDeletable($id, $userid) {
    
    $query = DB::getInstance()->query('SELECT deleted FROM `eintraege` WHERE `id` = ? AND `userid` = ? ', array($id, $userid));
    
    if($query->count() < 1) {
        return false;
    } else {
        return $query->first()->deleted == 0 ? true : false;
    }
}

function isLoggedIn($username, $key) {
    $user = new User($username);
    if($user->hasPermission("user")) {
        $anfrage = DB::getInstance()->query("SELECT * FROM `MOBILE_API_LOGIN` WHERE `key` = ? and `userid` = ? ", array($key, $user->data()->id ));
        return $anfrage->count();
    } else {
        return false;
    }
}

function getName($username) {
    $user = new User($username);
    return $user->data()->username;
}

function getUserID($username) {
    $user = new User($username);
    return $user->data()->id;
}

function logUserIn($username, $password, $newkey) {
    $user = new User($username);
            if($user->exists()) {
                if($user->data()->password === Hash::make($password, $user->data()->salt)) {
                    $insert = DB::getInstance()->insert('`MOBILE_API_LOGIN`', array(
                                                'key'            => $newkey,
                                                'userid'         => $user->data()->id
                                                ));  
                    if($insert) {
                        $arr = array('key' => $newkey, 'username' => $user->data()->username);
                        return json_encode($arr);
                    }
                }
            }
    return false;
}

function logUserOut($username, $key) {
    $user = new User($username);
    $anfrage = DB::getInstance()->query("DELETE FROM `MOBILE_API_LOGIN` WHERE `key` = '".$key."' and  `userid` = '".$user->data()->id."' ");
    
}

function debug($str) {
    $txt = $str. PHP_EOL;
    $myfile = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/debug/print.txt', $txt, FILE_APPEND);
}

?>
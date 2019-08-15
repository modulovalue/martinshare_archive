<?php

require '../slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
require_once $_SERVER['DOCUMENT_ROOT']. '/include/coreohnecheck.php';
$app = new \Slim\Slim();

$app->get('/eintraege/', function () use ($app) {

        $user = new User();
        $data = $user->data();
            
        if($user->isLoggedIn()) {

            $user = new User();
            $data = $user->data();
            
            $etwas = new EintragCRUD();
            $eintraege = $etwas->getAlleEintraege("DESC");
            $app->response->headers->set('Username', $data->username);
            $app->response->headers->set('Eintraegecount', count($eintraege));
            echo json_encode($eintraege);

        } else if (!$user->isLoggedIn()) {
            
               echo "nicht eingeloggt";
        
        }
    }
);


$app->get('/checkdatum/', function () use ($app) {

        $user = new User();
        $data = $user->data();
    
        if($user->isLoggedIn()) {
            
            $etwas = new EintragCRUD();
            $eintraege = $etwas->getLastChanged();
            $eintraege = json_decode(json_encode($eintraege), true);
            $etwas1 = new EintragCRUD();
            $eintraege1 = $etwas1->getAlleEintraege("DESC");
            $app->response->headers->set('Eintraegecount', count($eintraege1));
            $app->response->headers->set('Username', $data->username);
            print_r($eintraege['max(erstelldatum)']);
            
        } else if (!$user->isLoggedIn()) {
            
               $app->response()->status(400);
            
        }
      
    }
    
);


$app->post('/login/', function () use ($app) {
    
        $user = new User();
        $user->logout();
    
        $body = $app->request()->getBody();
        
        $body = utf8_encode($body);
        $body = json_decode($body);
        $username = $body->username;
        $password = $body->password;

        $user = new User();
        $data = $user->data();
        
        if(!$user->isLoggedIn()) {
            
            $login = $user->login($username, $password, true);
            
            if($login) {
                
                $user = new User();
                $data = $user->data();
                
                $etwas = new EintragCRUD();
                $eintraege = $etwas->getAlleEintraege("DESC");
                $app->response->headers->set('Username', $data->username);
                $app->response->headers->set('Eintraegecount', count($eintraege));
                
                echo json_encode($eintraege);
                
            } else {
                
               $app->response()->status(400);
               echo "falscher username oder passwort konnte nicht einloggen";
               
            }
            
        } else if ($user->isLoggedIn()) {
            
            echo "bereits eingeloggt";
            $app->response()->status(403);
        }
      
    }
    
);

$app->post('/testing/', function () use ($app) {
    

        $body = $app->request()->getBody();
       
        $body = json_decode($body);
        $username = $body->username;
        $password = $body->password;
        
        $user = new User();
        $data = $user->data();
        
        if(!$user->isLoggedIn()) {
            
            $login = $user->login($username, $password, true);
            
            if($login) {
                
                $user = new User();
                $data = $user->data();
                
                echo "ERFOLGREICH EINGELOGGT";
            } else {
                echo "FEHLER BEIM EINLOGGEN";
            }

        } else if ($user->isLoggedIn()) {
            echo $data->username."LOGGED IN";
        }

        
        
    }
    
);

$app->post('/eintraegeabsenden/', function () use ($app) {
    
    $user = new User();

    $body = $app->request()->getBody();
        
    $body = utf8_encode($body);
    $body = json_decode($body);

    if($user->isLoggedIn()) {  
        $passed = false;
        $art = $body->dbw;
        $fach = $body->fach;
        $beschreibung = $body->beschreibung;
        $datum = $body->datum;
           if($art === "arbeitstermine" || $art === "sonstiges" || $art === "hausaufgaben" ) {
               if($fach !== "" && $datum !== "") {
                   if($fach !== null && $datum !== null) {
                   $passed = true;
                   }
               }
           }
        
        if($passed) {
           
            
            $etwas = new EintragCRUD();
            $insert = $etwas->newEintrag($art, $fach, $beschreibung, $datum);
    
            if($insert) {
               
            } else {
               $app->response()->status(400);
            }
            
        } else {
            $app->response()->status(402);
            
        }
        
    } else if (!$user->isLoggedIn()) {
        
        $app->response()->status(401);
        
    }  
});


$app->post('/eintraegeupdaten/', function () use ($app) {
    
    $user = new User();

    $body = $app->request()->getBody();
        
    $body = utf8_encode($body);
    $body = json_decode($body);

    if($user->isLoggedIn()) {  
        
        $passed = false;
        
        $id = $body->id;
        $fach = $body->fach;
        $beschreibung = $body->beschreibung;
        $datum = $body->datum;
        
            if($fach !== "" && $datum !== "") {
                if($fach !== null && $datum !== null) {
                    
                    $passed = true;
                
                }
            }
        
        if($passed) {
           
            $etwas = new EintragCRUD();
            $insert = $etwas->updateEintrag($id, $fach, $beschreibung, $datum);
    
            if($insert) {
               
            } else {
               $app->response()->status(400);
            }
            
        } else {
            $app->response()->status(402);
            
        }
        
    } else if (!$user->isLoggedIn()) {
        
        $app->response()->status(401);
        
    }  
});


$app->get('/isloggedin/', function () use ($app) {
    
        $user = new User();
        $data = $user->data();
        
        if(!$user->isLoggedIn()) {

            $app->response()->status(400);
            
        } else if ($user->isLoggedIn()) {
            echo $data->username;
        }
      
    }
    
);


$app->get('/logout/', function () {
        
    $user = new User();
    $user->logout();
    setcookie ("klasse", "", time() - 1);
    exit();
        
    }
);

        
$app->run();
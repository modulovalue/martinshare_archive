<?php
require 'slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once '../include/coreohnecheck.php';

$user = new User(); 
if(!$user->hasPermission("admin")) { 
    exit(404);
}

$app = new \Slim\Slim();

$app->post('/countperdate/', function () use ($app) {
     
    
    $sql = DB::getInstance()->query('
    SELECT t.db_date AS erstelldatum, COUNT(e.id) AS count
    FROM time_dimension t 
    LEFT JOIN eintraege e 
    ON CAST(e.erstelldatum AS Date) = t.db_date
    WHERE t.db_date >= now()-interval '.$app->request()->params("monthrange").' week 
    AND t.db_date < NOW()
    GROUP BY t.db_date
    ');
        
    echo json_encode($sql->results(), JSON_NUMERIC_CHECK);
    
});


$app->post('/countperuser/', function () use ($app) {
    
    $usertype               = $app->request()->params('usertype');
    
    if($usertype == "manager") {
        
    $sql = DB::getInstance()->query('    
        SELECT s.namelang AS name, COUNT(e.id) AS data
        FROM users u 
        LEFT JOIN eintraege e 
        ON e.userid = u.id
        LEFT JOIN schulen s 
        ON  u.schule_id = s.id
        WHERE e.erstelldatum >= now()-interval '.$app->request()->params("monthrange").' week 
        GROUP BY u.schule_id
        ORDER BY u.joined
    ');
    
            
    } else {
    $sql = DB::getInstance()->query('    
        SELECT u.username AS name, COUNT(e.id) AS data
        FROM users u 
        LEFT JOIN eintraege e 
        ON e.userid = u.id
        LEFT JOIN schulen s 
        ON  u.schule_id = s.id
        WHERE e.erstelldatum >= now()-interval '.$app->request()->params("monthrange").' week 
        GROUP BY u.id
        ORDER BY u.joined
    ');
    
    }
    
    
    echo json_encode($sql->results(), JSON_NUMERIC_CHECK);
    
});

$app->post('/homepagelogin/', function () use ($app) {
    
    $sql = DB::getInstance()->query('
    SELECT t.db_date AS erstelldatum, COUNT(u.user_id) AS count
    FROM time_dimension t 
    LEFT JOIN users_session u
    ON CAST(u.datum AS Date) = t.db_date
    WHERE t.db_date >= now()-interval '.$app->request()->params("monthrange").' week 
    AND t.db_date < NOW()
    GROUP BY t.db_date
    ');
        
    echo json_encode($sql->results(), JSON_NUMERIC_CHECK);
    
});

$app->post('/mobilelogin/', function () use ($app) {
    
    $sql = DB::getInstance()->query('
    SELECT t.db_date AS erstelldatum, COUNT(m.userid) AS count
    FROM time_dimension t 
    LEFT JOIN `MOBILE_API_LOGIN` m
    ON CAST(m.erstelldatum AS Date) = t.db_date
    WHERE t.db_date >= now()-interval '.$app->request()->params("monthrange").' week 
    AND t.db_date < NOW()
    GROUP BY t.db_date
    ');
        
    echo json_encode($sql->results(), JSON_NUMERIC_CHECK);
    
});

$app->post('/getschools/', function () use ($app) {
    $sql = DB::getInstance()->query('SELECT id, namelang, namekurz FROM `schulen`');
    echo json_encode($sql->results(), JSON_NUMERIC_CHECK);
});

$app->post('/dodelete/', function () use ($app) {
    
    $id               = $app->request()->params('id');
    $delete           = $app->request()->params('todelete');
    
    if($delete == "1"  || $delete == "0") { 
        $update = DB::getInstance()->update('`mobilefeedbackmessages`', $id, array(
                    'deleted'          => $delete
                    )); 
        if(!$update) {
            $app->response()->status(400);
        } else {
            $app->response()->status(200);
        }
    } else {
        $app->response()->status(400);
    }  
    
});

$app->post('/doread/', function () use ($app) {
    
    $id             = $app->request()->params('id');
    $read           = $app->request()->params('isread');
    
    if($read == "1"  || $read == "0") { 
        
        $readup = DB::getInstance()->update('`mobilefeedbackmessages`', $id, array(
                    'isread'          => $read
                    )); 
                    
                    
        if(!$readup) {
            $app->response()->status(400);
            echo "db update error";
        } else {
            $app->response()->status(200);
        }
    } else {
        echo "params not ok";
        $app->response()->status(400);
    }  
    
});

$app->get('/getmobilemessages/:order/:deleted', function ($order, $deleted) use ($app) {
    
    $ordertrue = "";
    if($order == "asc") {
        $ordertrue = "ASC";
    } else {
        $ordertrue = "DESC";
    }
    
    $deletedtrue = "";
    if($deleted == "notdeleted") {
        $deletedtrue = "WHERE deleted = 0";
    } else if($deleted == "deleted") {
        $deletedtrue = "WHERE deleted = 1";
    } else {
        $deletedtrue = "";
    }
    
    $sql = DB::getInstance()->query('
    SELECT m.id, s.username, m.device, m.message, m.isread, m.deleted, m.created
    FROM mobilefeedbackmessages m
    JOIN users s
    ON m.userid = s.id
    '.$deletedtrue.'
    ORDER BY m.isread, m.created '.$ordertrue.'
    ');
    
    echo json_encode($sql->results(), JSON_NUMERIC_CHECK);
    
});

$app->post('/registerschool/', function () use ($app) {
   
    $namelang           = $app->request()->params('namelang');
    $namekurz           = $app->request()->params('namekurz');
    $homepage           = $app->request()->params('homepage');
    $vertretungsplankey = $app->request()->params('vertretungsplankey');
    
    $insert = DB::getInstance()->insert('`schulen`', array(
                        'namelang'            => $namelang,
                        'namekurz'            => $namekurz,
                        'homepage'            => $homepage,
                        'vertretungsplankey'  => $vertretungsplankey,
                        'planallow'           => 1
                        )); 
    
    if(!$insert) {
        $app->response()->status(404);
    } else {
        $app->response()->status(200);
    }

});

$app->post('/registeruser/', function () use ($app) {
   
       $validate = new Validate();
       
       $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 14,
                'unique' => 'users'
            ),
            'schule_id' => array(
                'required' => true,
                'schoolexists' => true
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
               'required' => true,
               'matches' => 'password'
            )
        ));
        
        if($validation->passed()) {
            $user = new User();
            $schule = new School(Input::get('schule_id'));
            
            $salt = Hash::salt(32);
            try {
        
                $user->create(array(
                    'username'      => $schule->data()->namekurz."-".Input::get('username'),
                    'password'      => Hash::make(Input::get('password'), $salt),
                    'schule_id'     => Input::get('schule_id'),
                    'salt'          => $salt,
                    'joined'        => date('Y-m-d H:i:s'),
                    'group'         => 1
                ));
                
                $to      = 'info@martinshare.com';
                $subject = 'Neuer Benutzer: '.Input::get('username');
                $message = 'Neuer Benutzer  Benutzername: '.Input::get('username').'  Passwort: '.Input::get('password').'  Schule: '.Input::get('schule_id') ;
                $headers = 'From: neuerlogin@martinshare.com' . "\r\n" .
                    'Reply-To: info@example.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($to, $subject, $message, $headers);
                
                Session::flash('register', 'Nutzer wurde erfolgreich registriert!');
                $app->response()->status(200);
                
            } catch(Exception $e) {
                $app->response()->status(404);
            }
            
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
            $app->response()->status(404);
        }

});

$app->run();

?>
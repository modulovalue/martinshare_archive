<?php

require 'slim/Slim/Slim.php';

\Slim\Slim::registerAutoloader();
require_once '../include/coreohnecheck.php';
$app = new \Slim\Slim();


date_default_timezone_set('Europe/Berlin');


$app->get('/randomkey/', function () use ($app) {
   echo Token::generatekey();
});


$app->post('/check/', function () use ($app) {
    
    $key = $app->request()->params('Key');
    
    if(checkKey($key) == 1) {
        $schule = getSchule($key);
        $app->response->headers->set('Schule', $schule['namelang']);
        $app->response->headers->set('Homepage', $schule['homepage']);
        $app->response()->status(200);
    } else {
        $app->response()->status(401);
    }
   
});



$app->post('/delete/', function () use ($app) {
    $pufferzeit = 5;
    $key = $app->request()->params('Key');
    
    if(checkKey($key) == 1) {
        $app->response->headers->set('Pufferzeit', $pufferzeit);
        $schule = getSchule($key);
        
        if(strtotime($schule['lastupdate']) < (strtotime(date('Y-m-d H:i:s'))-$pufferzeit) ) {
            $path = "../vertretungsplan/".$schule['id'];
            $files = glob($path.'/*'); // get all file names
            foreach($files as $file){ // iterate files
              if(is_file($file))
                 unlink($file); // delete file
            }
            $app->response()->status(200);
        } else {
            $app->response()->status(304);
        }
    } else {
        $app->response()->status(401);
    }
   
});


$app->get('/status/', function () use ($app) {
    $app->response()->status(200);
});



$app->post('/upload/', function () use ($app) {
    
    $key = $app->request()->params('Key');
    
    if(checkKey($key) == 1) {
        $schule = getSchule($key);
        
        $filename = $_FILES['file']['name'];

        $path = $_SERVER['DOCUMENT_ROOT']."/vertretungsplan/".$schule['id'];
        
        DB::getInstance()->query("UPDATE schulen SET `lastupdate` = '".date('Y-m-d H:i:s')."' WHERE `vertretungsplankey` = '".$key."'");
        
        if(file_exists($path."/".$_FILES['file']['name'])) { 
            unlink($path."/".$_FILES['file']['name']);
        }
        
        move_uploaded_file($_FILES['file']['tmp_name'], $path."/".$_FILES['file']['name']);
        
            $pathOfPlan = $path."/".$filename;
            $content = file_get_contents($pathOfPlan);
            $content = preg_replace('#<meta http-equiv="refresh"(.*?)>#', '', $content);
            file_put_contents($pathOfPlan, $content);
            
        $app->response()->status(200);
    } else {
        
        $app->response()->status(401);
    }
        
});

$app->run();

function checkKey($key) {
    $anfrage = DB::getInstance()->query("SELECT * FROM `schulen` WHERE `vertretungsplankey` = '".$key."' and `planallow` = 1 ");
    return $anfrage->count();
}

function getSchule($key) {
    $anfrage = DB::getInstance()->query("SELECT * FROM `schulen` WHERE `vertretungsplankey` = '".$key."' and `planallow` = 1 ");
    $letztereintrag = json_decode(json_encode($anfrage->first()), true);
    return $letztereintrag;
}






?>
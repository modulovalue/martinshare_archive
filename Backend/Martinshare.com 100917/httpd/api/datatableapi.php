<?php
require 'slim/Slim/Slim.php';
require_once '../include/library/medoo.php';
\Slim\Slim::registerAutoloader();
require_once '../include/coreohnecheck.php';

$app = new \Slim\Slim();

$app->response->headers->set('Access-Control-Allow-Origin', '*');
$app->response->headers->set("Cache-Control", "no-cache");
//$app->response->headers->set("Access-Control-Allow-Methods", "GET, PUT, POST, OPTIONS");
//$app->response->headers->set("Access-Control-Allow-Headers", "Content-Type, Accept");
//$app->response->headers->set("Access-Control-Max-Age", "1728000");


$app->get('/hotspots/', function () use ($app) {
    $data = db()->query("
        SELECT hsl.name, hs.lat, hs.lon
        FROM hotspots hs
        LEFT JOIN hotspotlist hsl
        ON hs.id=hsl.id")->fetchAll(PDO::FETCH_ASSOC);
    $results = array("data" => $data);
    echo json_encode ($results);
});

$app->get('/datatableschools/', function () use ($app) {
    
    $data = db()->query("
                        SELECT school.id, school.email, school.homepage, school.name, school.anschrift, school.plz, school.ort, COUNT(listofplans.schoolid) AS 'plancount'
                        FROM school
                        LEFT JOIN listofplans ON school.id = listofplans.schoolid
                        GROUP BY school.id
                    ")->fetchAll();
                
    $results = array(
                        "sEcho"                 => 1,
                        "iTotalRecords"         => count($data),
                        "iTotalDisplayRecords"  => count($data),
                        "aaData"                => $data
                    );
        
    echo json_encode ($results);
    
});

$app->post('/newschool/', function () use ($app) {
    
    $name = $searchTerm = $app->request()->params('name');
    $homepage = $searchTerm = $app->request()->params('homepage');
    $email = $searchTerm = $app->request()->params('email');
    
    debug($name . " " . $homepage . " " . $email);

    db()->insert("school", [
    	"name" => $name,
    	"homepage" => $homepage,
    	"email" => $email
    ]);
        
});

$app->get('/datatableplans/:schoolid/', function ($schoolid) use ($app) {
            
    $data = db()->select("listofplans", "*", [
    	"schoolid" => $schoolid
    ]);
    
    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData"=>$data);
        
    echo json_encode($results);
       
});

$app->post('/newplan/', function () use ($app) {
    
    $schoolid = $app->request()->params('schoolid');
    $planname = $app->request()->params('planname');
    $plantype = $app->request()->params('plantype');
    $planlink = $app->request()->params('planlink');
    
    if( $schoolid != "" && $planname != "" &&
        $plantype != "" && $planlink != "") {
    
        db()->insert("listofplans", [
        	"schoolid" => $schoolid,
        	"name" => $planname,
        	"type" => $plantype,
        	"link" => $planlink
        ]);        
        
    } else {
        $app->response()->status(400);            
    }
        
});

$app->post('/editaddress/', function () use ($app) {
    
    $schoolid = $app->request()->params('schoolid');
    $schoolanschrift = $app->request()->params('schoolanschrift');
    $schoolplz = $app->request()->params('schoolplz');
    $schoolort = $app->request()->params('schoolort');
    
    if( $schoolid != "" && $schoolanschrift != "" &&
        $schoolplz != "" && $schoolort != "") {
    
        db()->update("school", [
        	"plz" => $schoolplz,
        	"ort" => $schoolort,
        	"anschrift" => $schoolanschrift
        ],
        ["id" => $schoolid ]);        
        
    } else {
        $app->response()->status(400);            
    }
        
});

$app->get('/test/', function () use ($app) {
            
    $anfrage = db()->select("school", ["homepage", "id", "name"], [
    	"id" => 8
    ]);
    
    echo json_encode($anfrage);
       
});

$app->run();

function db() { 
    return new medoo([
        'database_type' => 'mysql',
        'database_name' => 'martinshare_com',
        'server' => 'martinshare.com.mysql',
        'username' => 'martinshare_com',
        'password' => 'xcBZz6w3',
        'charset' => 'utf8'
    ]);
}

function debug($str) {
    $txt = $str. PHP_EOL;
    $myfile = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/debug/print.txt', $txt, FILE_APPEND);
}


?>
<?php
require 'slim/Slim/Slim.php';
require_once '../include/library/medoo.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/register', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    $hash = Hash::unique();
    $anfrage = db()->query("
        INSERT INTO `hotspotuniqueids` (hash)
        VALUES ('".$hash."');
        ");
    echo $hash;
});

$app->get('/setpushid/:hash/:device/:pushid', function ($hash, $device, $pushid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    if(isRegistered($hash)) {
        $anfrage = db()->query("
            UPDATE hotspotuniqueids
            SET device = '".$device."', pushid = '".$pushid."'
            WHERE `hash` = '".$hash."'
            ");
    } else {
        return $response->withStatus(401);
    } 
});

$app->get('/setname/:hash/:name', function ($hash, $name) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    if(isRegistered($hash)) {
        $anfrage = db()->query("
            UPDATE hotspotuniqueids
            SET name = '".utf8_encode($name)."'
            WHERE `hash` = '".$hash."'
            ");
        return "ok";
    } else {
        return $response->withStatus(401);
    } 
});

$app->get('/closesthotspot/:fromlat/:fromlon', function ($fromlat, $fromlon) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    
    $data = db()->query("
        SELECT hs.hotspotid, hsl.name, hs.lat, hs.lon, hsl.radius
        FROM hotspots hs
        LEFT JOIN hotspotlist hsl
        ON hs.id=hsl.id")->fetchAll(PDO::FETCH_ASSOC);
        
    $array = array();
    
    foreach($data as $hotspot) {
        array_push($array, [
                "id" => $hotspot["hotspotid"] ,
                "name" => $hotspot["name"] ,
                "dst" => vincentyGreatCircleDistance($fromlat, $fromlon, $hotspot["lat"], $hotspot["lon"]),
                "radius" => $hotspot["radius"] ,
                "lat" => $hotspot["lat"] ,
                "lon" => $hotspot["lon"]
            ]
        );
    }
    echo json_encode ($array);
});

$app->get('/userjoined/:hash/:hotspotid', function ($hash, $hotspotid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    if(isRegistered($hash)) {
        $anfrage = db()->query("
            INSERT INTO `hotspotevents` (userid, hotspotid, eventid, payload)
            VALUES ('".getUserID($hash)."', '".$hotspotid."', '2', '{}');
            ");
    } else {
        return $response->withStatus(401);
    } 
});

$app->get('/userleft/:hash/:hotspotid', function ($hash, $hotspotid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    if(isRegistered($hash)) {
        $anfrage = db()->query("
            INSERT INTO `hotspotevents` (userid, hotspotid, eventid, payload)
            VALUES ('".getUserID($hash)."', '".$hotspotid."', '3', '{}');
            ");
    } else {
        return $response->withStatus(401);
    } 
});

$app->post('/message', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    
    $hash = $app->request()->params('userhash');
    $hotspotid = $app->request()->params('hotspotid');
    $message = $app->request()->params('message');
    
    if(isRegistered($hash)) {
        $array = array();
        array_push($array, ["message" => $message,"type" => "text"]);
        $anfrage = db()->query("
            INSERT INTO `hotspotevents` (userid, hotspotid, eventid, payload)
            VALUES ('".getUserID($hash)."', '".$hotspotid."', '1', '".json_encode($array)."');
            ");
        //var_dump($array);
        //echo json_encode($array);
        //var_dump (json_decode(json_encode($array)));
    } else {
        return $response->withStatus(401);
    } 
    
});

$app->get('/newname', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    echo getRandomUniqueName();
});

$app->run();

function isRegistered($hash) {
    $anfrage = db()->query("
            SELECT * 
            FROM `hotspotuniqueids`
            WHERE hash = '".$hash."'
            ")->fetchAll(PDO::FETCH_ASSOC);
    return (count($anfrage) >= 1);
}

function getUserID($hash) {
    $anfrage = db()->query("
            SELECT * 
            FROM `hotspotuniqueids`
            WHERE hash = '".$hash."'
            ")->fetchAll(PDO::FETCH_ASSOC);
    return ($anfrage[0]->id);
}

function getRandomUniqueName() {
    $data = db()->query("
    SELECT name FROM `randomnames` r
    WHERE NOT EXISTS 
    (SELECT name FROM `hotspotuniqueids` h WHERE r.name = h.name)
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if(count($data) > 0) {
        return $data[rand(0, count($data) - 1)]["name"];
    } else {
        return getRandomNameFromForeignApi();
    }
}
function getRandomNameFromForeignApi() {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://uinames.com/api/?region=germany'
    ));
    $resp = json_decode(curl_exec($curl));
    curl_close($curl);
    return $resp->name . " " . $resp->surname;
}

function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}

function db() { 
    return new medoo([
        'database_type' => 'mysql',
        'database_name' => 'martinshare_com',
        'server' => 'martinshare.com.mysql',
        'username' => 'martinshare_com',
        'password' => 'xcBZz6w3',
        'charset' => 'utf8mb4'
    ]);
}

?>
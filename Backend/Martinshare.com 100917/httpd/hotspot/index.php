<?php
require 'frameworks/slim/Slim/Slim.php';
require_once 'frameworks/medoo.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response->headers->set('Access-Control-Allow-Origin', "*");

$app->get('/register', function () use ($app) {
    $hash = Hash::unique();
    db()->insert("hotspotuniqueids", ["hash" => $hash]);
    echo $hash;
});

$app->get('/setpushid/:hash/:device/:pushid', function ($hash, $device, $pushid) use ($app) {
    whenRegistered($hash, $app, function(){
        db()->update("hotspotuniqueids", ["device" => $device, "pushid" => $pushid], ["hash" => $hash]);
    });
});

$app->get('/setname/:hash/:name', function ($hash, $name) use ($app) {
    whenRegistered($hash, $app, function(){
        db()->update("hotspotuniqueids", ["name" => $name], ["hash" => $hash]);
    });
});

$app->get('/closesthotspot/:fromlat/:fromlon', function ($fromlat, $fromlon) use ($app) {
    $data = db()->query("
        SELECT hs.hotspotid, hsl.name, hs.lat, hs.lon, hsl.radius, hsl.channels
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
                "lon" => $hotspot["lon"],
                "channels" => $hotspot["channels"]
            ]
        );
    }
    echo json_encode ($array);

});

$app->get('/userjoined/:hash/:hotspotid', function ($hash, $hotspotid) use ($app) {
    whenRegistered($hash, $app, function(){
        db()->insert("hotspotevents", ["userid" => getUserID($hash), "hotspotid" => $hotspotid, "eventid" => '2', "payload" => '{}']);
    });
});

$app->get('/userleft/:hash/:hotspotid', function ($hash, $hotspotid) use ($app) {
    whenRegistered($hash, $app, function(){
        db()->insert("hotspotevents", ["userid" => getUserID($hash), "hotspotid" => $hotspotid, "eventid" => '3', "payload" => '{}']);
    });
});

$app->post('/message', function () use ($app) {
    $hash = $app->request()->params('userhash');
    $hotspotid = $app->request()->params('hotspotid');
    $message = $app->request()->params('message');
    
    whenRegistered($hash, $app, function(){
        $array = array();
        array_push($array, ["message" => $message,"type" => "text"]);
        db()->insert("hotspotevents", ["userid" => getUserID($hash), "hotspotid" => $hotspotid, "eventid" => '1', "payload" => json_encode($array)]);
    });
    
});

$app->get('/newname', function () use ($app) {
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

function whenRegistered($hash, $app, $success) {
    if (isRegistered($hash)) {
        $success();
    } else {
        $app->response()->status(401);
    }
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

class Hash {
    public static function make($string, $salt = '') {
        return hash('sha256', $string . $salt);
    }
    public static function salt($length) {
        return mcrypt_create_iv($length);
    }
    public static function unique() {
        return self::make(uniqid());
    }
}

?>
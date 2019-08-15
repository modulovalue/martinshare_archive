<?php
require 'slim/Slim/Slim.php';
require_once '../include/library/medoo.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->get('/userliste', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");

    $data = db()->query("
        SELECT *
        FROM `vanbenutzer`
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($data);
});

$app->get('/lagerliste', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");

    $data = db()->query("
        SELECT *
        FROM `vanlager`
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($data);
});


$app->get('/warenliste', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");

    $data2 = db()->query("
        SELECT * 
        FROM `vanwaren`
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $array = array();
    
    foreach($data2 as $ware) {
        
        $warenid = $ware["id"];
        
    	$anfrage = db()->query("
        SELECT lagerid, einchecktyp, timestamp
        FROM `vaneinlagerung` 
        WHERE warenid = $warenid
        ORDER BY TIMESTAMP DESC 
        LIMIT 0 , 1
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($anfrage) < 1) {
            array_push($array, [
                    "id" => $ware["id"] ,
                    "name" => $ware["name"] ,
                    "available" => "false"
                ]
            );
        } else {
            if ($anfrage[0]["einchecktyp"] == "in") {
                
            array_push($array, [
                    "id" => $ware["id"] ,
                    "name" => $ware["name"] ,
                    "available" => "true"
                ]);
            } else {
                
            array_push($array, [
                    "id" => $ware["id"] ,
                    "name" => $ware["name"] ,
                    "available" => "false"
                ]);
            }
        }
    }
    
    echo json_encode($array);
});

$app->get('/registerware/:lagerid/:warenid/:lon/:lat', function ($lagerid, $warenid, $lon, $lat) use ($app) {
    
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    $anfrage = db()->query("
            SELECT * 
            FROM `vaneinlagerung`
            WHERE warenid = $warenid
            ORDER BY timestamp DESC
            LIMIT 0, 1
        ")->fetchAll(PDO::FETCH_ASSOC);
        
    $anfrage2 = db()->query("
            SELECT *
            FROM `vanwaren`
            WHERE id = '".$warenid."'
            ")->fetchAll(PDO::FETCH_ASSOC);
                
        
    if (count($anfrage) < 1) {
        $anfrage3 = db()->query("
        INSERT INTO `vaneinlagerung` (lagerid, warenid, lon, lat, einchecktyp)
        VALUES ($lagerid, $warenid, $lon, $lat, 'in');
        ");
        
        $array = array();
        array_push($array, [
                "typ" => "in",
                "name" => $anfrage2[0]["name"],
                "lagerid" => $lagerid
            ]
        );
        echo json_encode($array);
    } else {
        if ($anfrage[0]["einchecktyp"] == "in") {
            $anfrage3 = db()->query("
            INSERT INTO `vaneinlagerung` (lagerid, warenid, lon, lat, einchecktyp)
            VALUES ($lagerid, $warenid, $lon, $lat, 'out');
            ");
            
            $array = array();
            array_push($array, [
                    "typ" => "out",
                    "name" => $anfrage2[0]["name"],
                    "lagerid" => $lagerid
                ]
            );
            echo json_encode($array);
        } else {
            $anfrage3 = db()->query("
            INSERT INTO `vaneinlagerung` (lagerid, warenid, lon, lat, einchecktyp)
            VALUES ($lagerid, $warenid, $lon, $lat, 'in');
            ");
            
            $array = array();
            array_push($array, [
                    "typ" => "in",
                    "name" => $anfrage2[0]["name"],
                    "lagerid" => $lagerid
                ]
            );
            echo json_encode($array);
        }
    }
});


$app->get('/registeruser/:userid/:lagerid', function ($userid, $lagerid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    
    $anfrage = db()->query("
            SELECT * 
            FROM `vaneingecheckt`
            WHERE userid = $userid
            ORDER BY timestamp DESC
            LIMIT 0, 1
        ")->fetchAll(PDO::FETCH_ASSOC);
        
    $anfrage2 = db()->query("
            SELECT *
            FROM `vanbenutzer`
            WHERE id = '".$userid."'
            ")->fetchAll(PDO::FETCH_ASSOC);
                
                
    if (count($anfrage) < 1) {
        $anfrage3 = db()->query("
        INSERT INTO `vaneingecheckt` (userid, lagerid, einchecktyp)
        VALUES ($userid, $lagerid, 'in');
        ");
        
        $array = array();
        array_push($array, [
                "typ" => "in",
                "name" => $anfrage2[0]["name"],
                "userid" => $userid
            ]
        );
        echo json_encode($array);
    } else {
        if ($anfrage[0]["einchecktyp"] == "in") {
            $anfrage3 = db()->query("
            INSERT INTO `vaneingecheckt` (userid, lagerid, einchecktyp)
            VALUES ($userid, $lagerid, 'out');
            ");
            
            $array = array();
            array_push($array, [
                    "typ" => "out",
                    "name" => $anfrage2[0]["name"],
                    "userid" => $userid
                ]
            );
            echo json_encode($array);
        } else {
            $anfrage3 = db()->query("
            INSERT INTO `vaneingecheckt` (userid, lagerid, einchecktyp)
            VALUES ($userid, $lagerid, 'in');
            ");
            
            $array = array();
            array_push($array, [
                    "typ" => "in",
                    "name" => $anfrage2[0]["name"],
                    "userid" => $userid
                ]
            );
            echo json_encode($array);
        }
    }
});

$app->get('/checkuser/:userid/:lagerid', function ($userid, $lagerid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->query("
        INSERT INTO `vaneingecheckt` (userid, lagerid, einchecktyp)
        VALUES ($userid, $lagerid, 'in');
        ");
            
});

$app->get('/currentlagerofuser/:userid', function ($userid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->query("
    	SELECT * 
        FROM `vaneingecheckt`
        WHERE userid = '".$userid."'
        ORDER BY timestamp DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($anfrage) < 1) {
        echo "{}";
    } else {
        echo json_encode($anfrage[0]);
    }
});

$app->get('/currentuseroflager/:lagerid', function ($lagerid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->query("
        	SELECT * 
            FROM `vaneingecheckt`
            WHERE lagerid = '".$lagerid."'
            ORDER BY timestamp DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($anfrage) < 1) {
            echo "{}";
        } else {
            echo json_encode($anfrage[0]);
        }
});

$app->get('/newgegenstand/:name', function ($name) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->insert("vanwaren", ["name" => $name,]);
});

$app->get('/newvan/:name/:lon/:lat', function ($name, $lon, $lat) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->insert("vanlager", ["name" => $name, "lon" => $lon, "lat" => $lat, "typ" => "Van"]);
});

$app->get('/newlager/:name/:lon/:lat', function ($name, $lon, $lat) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->insert("vanlager", ["name" => $name, "lon" => $lon, "lat" => $lat, "typ" => "Lager"]);
});

$app->get('/newuser/:name/:skill', function ($name, $skill) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->insert("vanbenutzer", ["name" => $name, "skill" => $skill]);
});

$app->get('/finditem/:itemid', function ($itemid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->query("
    SELECT lagerid, TIMESTAMP
    FROM  `vaneinlagerung` 
    WHERE warenid = $itemid
    ORDER BY TIMESTAMP DESC 
    LIMIT 0 , 1
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($anfrage) < 1) {
        echo "{}";
    } else {
        $array = array();
        $anfrage2 = db()->query("
                SELECT *
                FROM `vanlager`
                WHERE id = '".$anfrage[0]["lagerid"]."'
                ")->fetchAll(PDO::FETCH_ASSOC);
        array_push($array, [
                "lon" => $anfrage2[0]["lon"] ,
                "lat" => $anfrage2[0]["lat"] ,
                "lagerid" => $anfrage[0]["lagerid"]
            ]
        );
        echo json_encode($array[0]);
    }
});

$app->get('/allitemsinlager/:lagerid', function ($lagerid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->query("
    	SELECT vl.warenid, vl.lon, vl.lat, vl.einchecktyp, vw.name, vl.timestamp
        FROM vaneinlagerung vl
        LEFT JOIN vanwaren vw
        ON vl.warenid=vw.id
        WHERE lagerid = $lagerid
            ORDER BY `timestamp` DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        
    if (count($anfrage) < 1) {
        echo "{}";
    } else {
        $array = array();
        
        foreach($anfrage as $data) {
            $alreadyexists = false;
            foreach($array as $data2) {
                if ($data2["warenid"] == $data["warenid"]) {
                    $alreadyexists = true;
                }
            }
            if (!$alreadyexists) {
                array_push($array, $data);
            }
        }
        
        $array2 = array();
        
        foreach($array as $data2) {
            if ($data2["einchecktyp"] == "in") {
                array_push($array2, $data2);
            }
        }
        
        echo json_encode($array2);
    }
});


$app->get('/getuserslager/:userid', function ($userid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    
    
	$anfrage = db()->query("
    	SELECT lagerid, timestamp, einchecktyp
        FROM vaneingecheckt 
        WHERE userid = $userid
        ORDER BY `timestamp` DESC
        LIMIT 0, 1
        ")->fetchAll(PDO::FETCH_ASSOC);
    
	$anfrage = db()->query("
    	SELECT lagerid, timestamp, einchecktyp
        FROM vaneingecheckt 
        WHERE userid = $userid
        ORDER BY `timestamp` DESC
        LIMIT 0, 1
        ")->fetchAll(PDO::FETCH_ASSOC);
    if (count($anfrage) < 1) {
        echo "{}";
    } else {
        if ($anfrage[0]["einchecktyp"] == "in") {
            echo json_encode($anfrage);
        } else {
            echo "{}";
        }
    }
});



$app->get('/updatelagerpos/:lagerid/:lon/:lat', function ($lagerid, $lon, $lat) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
    $anfrage = db()->query("
            UPDATE vanlager
            SET lon = '".$lon."', lat = '".$lat."'
            WHERE `id` = '".$lagerid."'
        ");
});

$app->get('/historyware/:wareid', function ($wareid) use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', "*");
	$anfrage = db()->query("
        	SELECT vl.warenid, vl.lagerid, vl.lon, vl.lat, vl.einchecktyp, vw.name, vl.timestamp
            FROM vaneinlagerung vl
            LEFT JOIN vanlager vw
            ON vl.lagerid=vw.id
            WHERE vl.warenid = $wareid
            ORDER BY `timestamp` DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($anfrage) < 1) {
            echo "{}";
        } else {
            echo json_encode($anfrage);
        }
});

function getlagerpos($lagerid) {
    $anfrage = db()->query("
            SELECT *
            FROM `vanlager`
            WHERE id = '".$lagerid."'
            ")->fetchAll(PDO::FETCH_ASSOC);
    return $anfrage;
}

$app->run();

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
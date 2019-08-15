<?php

require $_SERVER['DOCUMENT_ROOT']. '/frameworks/parsesdk/autoload.php';

//require_once $_SERVER['DOCUMENT_ROOT']. '/include/coreohnecheck.php';

setlocale(LC_TIME, 'de_DE', 'deu_deu');
    
use Parse\ParsePush;
use Parse\ParseClient;
use Parse\ParseInstallation;

class Push {


    
    //get PushID's give userid & device
    public static function getpushids($userid, $device) {
        
        $anfrage = DB::getInstance()->query('SELECT pushID FROM `MOBILE_API_LOGIN` WHERE userid = '.$userid.' AND device = "'.$device.'" AND pushID <> "0"  ');
        
        $eintrag = json_decode(json_encode($anfrage->results(), true), true);
        
        $newArr = array();
        foreach ($eintrag as $subarray) {
        array_push($newArr, $subarray["pushID"]);
        }
        
        return $newArr;
    }
    
    public static function pushtoios($username, $title, $date, $message) {
    
        ParseClient::initialize( "1RpRz1obmjV58rUwZPATjbQVdwUvwoLWuUf5oDhQ", "wzMgqrCF9ICzq3qiRWPORkpFtAF8AwwF3nhfXws0", "RLQa2cOezmByGwR6eWQlOBwsrtuPT2ZIitjlnplc" );
        
        $prettydate = Push::prettyDate($date);
        $data = array("alert" => $title." (".$prettydate.")"."\r\n".$message);
        ParsePush::send(array( "channels" => [$username], "data" => $data ));
        
    }
    
    public static function pushtoiosurl($message, $url) {
    
        ParseClient::initialize( "1RpRz1obmjV58rUwZPATjbQVdwUvwoLWuUf5oDhQ", "wzMgqrCF9ICzq3qiRWPORkpFtAF8AwwF3nhfXws0", "RLQa2cOezmByGwR6eWQlOBwsrtuPT2ZIitjlnplc" );
        $query = ParseInstallation::query();
        $query->equalTo("deviceType", "ios");
        $data = array("alert" => $message, "url" => $url);
        ParsePush::send(array( "where" => $query, "data" => $data ));
        
    }
    
    
    public static function pushtoandroid($title, $message, $summary, $date, $id, $key, $userid, $typ = "normal") {
    
        $prettydate = Push::prettyDate($date);
        // API access key from Google API's Console
        $androidapikey = 'AIzaSyCCWGvu_Kl9NvNg7w3CN1vipBc8sV0QSFY';
        // prep the bundle
        $msg = array
        (
            'title'         => $title,
            'message'       => $message,
            'typ'       => $typ,
            'id'        => $id,
            'summary'   => $summary.$prettydate,
            'vibrate'   => 0,
            'sound'     => 0
        );
        
        
        if($key != 0) {
            $anfrag = DB::getInstance()->query('SELECT pushID FROM `MOBILE_API_LOGIN` WHERE userid = "'.$userid.'" AND `key` = "'.$key.'"');
        
            if($anfrag) {
                $userAPIKEY = get_object_vars($anfrag->first())["pushID"];
                $registrationIds = array_diff(Push::getpushids($userid, "android"), [$userAPIKEY]); 
            } else {
                $registrationIds = Push::getpushids($userid, "android"); 
            }
        } else {
            $registrationIds = Push::getpushids($userid, "android"); 
        }
        
        $pusharray = array();
        
        foreach ($registrationIds as $subarray) {
            array_push($pusharray, $subarray);
        }
    
        $fields = array ('registration_ids' => $pusharray, 'data' => $msg);
        
        $headers = array
        (
            'Authorization: key=' . $androidapikey,
            'Content-Type: application/json'
        );
        
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
    
        // echo $result;
        
    }





    public static function prettyDate($datee) {
        
        $a = strptime($datee, '%Y-%m-%d');
        $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
    
        if ($datee == date('Y-m-d')) {
                return "Heute";
        } else if ($datee == date('Y-m-d', strtotime('tomorrow'))) {
                return "Morgen";
        } else if ($datee == date('Y-m-d', strtotime('tomorrow + 1 day'))) {
                return "Übermorgen";
        } else {
            return date("d.m.Y", $timestamp); 
        }
      
    }

    //Get days from now to a date
    public static function getDaysTo($bis) {
        
        $a = strptime($bis, '%Y-%m-%d');
        $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
    
        $startTimeStamp = time();
        $endTimeStamp = $timestamp;
        $timeDiff = $endTimeStamp - $startTimeStamp;
        $numberDays = $timeDiff/86400;  // 86400 seconds in one day
        // and you might want to convert to integer
        $numberDays = intval($numberDays);
        
        $sum = "";
        if($numberDays > -1) {
            $sum = "in ". $numberDays." Tagen";
        } else {
            $sum = "vor ". abs($numberDays)." Tagen";
        }
        
        $sum = $sum." (".Push::prettyDate($bis).") ";
        return $sum;
    }

}

?>
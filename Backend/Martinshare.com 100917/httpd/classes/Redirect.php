<?php
class Redirect {
    public static function to($location = null) {
        if($location) {
            if(is_numeric($location)) {
                switch($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include $_SERVER['DOCUMENT_ROOT'].'/include/errors/404.php';
                        exit();
                    break;
                    #case 502:
                    #    
                    #break;
                }
            }
            //if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') {

             header("Location: https://www.martinshare.com/$location");
            //} else {

           //  header("Location: http://martinshare.com/$location");
            //}
         
            exit();
        }
    }
}
<?php


require_once "coreohnecheck.php";

if(!Session::get(Config::get('chat/session_name')) && Cookie::exists(Config::get('remember/cookie_name'))) {
       
                        $cookiehash = escape(Cookie::get(Config::get('remember/cookie_name')));
                        $user = new User();
                        $userid = $user->data()->id;
                        $chatname = DB::getInstance()->query('SELECT * FROM `users_session` WHERE user_id="'.$userid.'" AND hash="'.$cookiehash.'" ');
                         
                        Session::put(Config::get('chat/session_name'),$chatname->first()->name);
}


if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name')) ) {
    
        $hash = Cookie::get(Config::get('remember/cookie_name'));
        $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
        
        if($hashCheck->count()) {
            $user = new User($hashCheck->first()->user_id);
            $user->login();
            $userklasse= $user->data()->username;
            
        } else if(!$hashCheck->count()) {
            $user = new User();
            $user->logout();
            setcookie ("klasse", "", time() - 1);
            Redirect::to('index.php');
        }
}

$user = new User();

if($user->isLoggedIn()) {
    if($dontvisitifloggedin) {
    Redirect::to('start/index.php');
    }
}

if(!$user->isLoggedIn()) {
    if($noLogin != true) {
    Redirect::to('index.php');
    }
}



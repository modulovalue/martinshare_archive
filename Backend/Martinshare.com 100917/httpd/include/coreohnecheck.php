<?php
ini_set('session.cookie_domain', '.martinshare.com' );
session_start();

 # Config::get('mysql/host'); ZUGRIFF AUF CONFIG
 
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'martinshare.com.mysql',
        'username' => 'martinshare_com',
        'password' => 'xcBZz6w3',
        'db' => 'martinshare_com',
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 94670778,
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token',
    ),
    'chat' => array(
        'session_name' => 'chatname',
        'cookie_name' => 'chatname',
    ),
    'includes' => array(
        'header' => 'include/header.php',
        'navbar' => 'include/navbar.php',
        'footer' => 'include/footer.php',
    ),
    'includesindex' => array(
        'navbar' => 'include/presentpage/navbar.php',
    ),
    'users' => array(
        'sessiontabelle' => 'users_session',
        
    ),
);

spl_autoload_register(function($class) {
    $filename = $_SERVER['DOCUMENT_ROOT']. '/classes/' . $class . '.php';
    
    if (file_exists($filename)) {
        require_once $filename;
    }

});

require_once "sanitize.php";
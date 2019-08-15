<?php
require_once 'include/core.inc.php';
Session::put(Config::get('chat/session_name'), '');
$user = new User();
$user->logout();
setcookie ("klasse", "", time() - 1);
Redirect::to('index.php');
exit();
?>
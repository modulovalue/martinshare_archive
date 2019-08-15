<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';


    $nameraw = escape(Input::get('name'));
    $result = preg_replace("/[^a-zA-Z0-9]+/", "", $nameraw);
    $name = substr($result, 0, 18);
    $score = escape(Input::get('score'));
    $posthighscore = DB::getInstance()->query('INSERT INTO `balloonshighscore` (`name` ,`score`)VALUES ("'.$name.'","'.$score.'")');
   

?>

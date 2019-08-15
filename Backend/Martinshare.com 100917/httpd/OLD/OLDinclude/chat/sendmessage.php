<?php

        require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

            $user = new User();
            $userid = $user->data()->id;
            $userklasse = $user->data()->username;
            $chatname = Session::get(Config::get('chat/session_name'));
            $zieltabelle ="CHAT$userklasse";
            $message = escape(Input::get('message'));
            $posttabelle = DB::getInstance()->query('INSERT INTO `'.$zieltabelle.'` (`name` ,`message`)VALUES ("'.$chatname.'","'.$message.'")');
           
        
?>
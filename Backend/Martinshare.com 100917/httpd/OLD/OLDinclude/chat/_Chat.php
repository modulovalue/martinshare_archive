<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

            if(Session::get(Config::get('chat/session_name'))) {  
                $user = new User();
                $userklasse= $user->data()->username;
                $timestamp = Input::get('timestamp');

                $newdb = DB::getInstance()->query('SELECT * FROM `CHAT'.$userklasse.'` ORDER BY datum ASC ');
               
                if(!$newdb->count()) {
                    echo'<p></p><span class="notchat">Keine Nachrichten</span>';
                    
                } else{
                   
                    foreach($newdb->results() as $new) {
                    $datum_f = date('d.m.y', strtotime($new->datum));
                    $timestamp = strtotime($new->datum) + 60*60;
                        print'
                        <div class="messageleft">
                            <span class="name">'.$new->name.'</span>
                            <span class="datum">'.date(" G:i", $timestamp ).'</span>
                            <p class="nachricht1">'.$new->message.'</p>
                        </div>';
                    }
                  
                }

		        ?>
		
            <?php  } else {
                        echo'<span class="notchat"><br>Bitte wähle einen Namen in den <a href="/einstellungen.php" style="color: #A52A2A;">Einstellungen</a> </span>';
                    } ?>   
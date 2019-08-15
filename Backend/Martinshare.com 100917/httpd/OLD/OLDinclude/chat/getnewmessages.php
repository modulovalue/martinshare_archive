<?php
    require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
    if(Session::get(Config::get('chat/session_name'))) {  
    $user = new User();
    $userklasse= $user->data()->username;
    $timestamp = Input::get('timestamp');
    $response = '';
    $newdbcheck = DB::getInstance()->query('SELECT * FROM `CHAT'.$userklasse.'` ORDER BY datum Desc');
    $newtimestamp = strtotime($newdbcheck->first()->datum);
        if(!$newdbcheck->count()) {
            
            echo json_encode(array("inhalt" => '<p></p><span class="notchat">Keine Nachrichten</span>', "zeit" => "0")); 
            
        } else if($newtimestamp > $timestamp) {
           
                
                $newdb = DB::getInstance()->query('SELECT * FROM `CHAT'.$userklasse.'` ORDER BY datum ASC Limit 0,30');
                    foreach($newdb->results() as $new) {
                        $messagedatum = strtotime($new->datum) + 60*60;
                        $response .=
                        '<div class="messageleft">
                        <span class="name">'.$new->name.'</span>
                        <span class="datum">'.date('G:i',$messagedatum).'</span>
                        <p class="nachricht1">'.$new->message.'</p>
                        </div>';
                    }
                echo json_encode(array("inhalt" => $response, "zeit" => $newtimestamp));
          
        } else {
                echo json_encode(array("inhalt" => '', "zeit" => ''));
        }
    } else { 
        $response = '<span class="notchat"><br>Bitte wähle einen Namen in den <a href="/einstellungen.php" style="color: #A52A2A;">Einstellungen</a> </span>';
        echo json_encode(array("inhalt" => $response, "zeit" => Input::get('timestamp')));
    } 
   
    
        
?>   
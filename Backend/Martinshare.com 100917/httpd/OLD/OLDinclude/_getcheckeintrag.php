<?php
                require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
                setlocale(LC_TIME, "de_DE");
                $user = new User();
                $userklasse= $user->data()->username;
                $datum = strtotime(Input::get('datum'));
                
                $datum = date('Y-m-d',$datum);
                $response = '';
                $newdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE datum  = "'.$datum.'" art = `h` ORDER BY datum DESC');
                if($newdb->count())
                {
                    $response .= 'H ';
                }
                $newdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE datum  = "'.$datum.'" art = `a`  ORDER BY datum DESC');
                if($newdb->count())
                {
                    $response .= ' ';
                }
                $newdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE datum  = "'.$datum.'" art = `s`  ORDER BY datum DESC');
                if($newdb->count())
                {
                    $response .= ' ';
                }
                
                echo $response;
?>
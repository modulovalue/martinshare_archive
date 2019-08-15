            <?php
            echo '<center>';
               
                $viertertag = 86400*4;
                $wann = array(
                    '+0 day' => '/images/done.png',
                    '+1 day' => '/images/red-bg.png',
                    '+2 day' => '/images/orange-bg.png',
                    '+3 day' => '/images/yellow-bg.png',
                    );               
                $user = new User();
                $userklasse= $user->data()->username;
                $newdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` ORDER BY `erstelldatum` DESC LIMIT 0 , 3');
                
                if( !$newdb->count() ) {
                echo"";
                
                } else if(strtotime($newdb->first()->erstelldatum) > strtotime('-85 minutes') && strtotime($newdb->first()->erstelldatum) < strtotime('now') ) {
               
                echo '<p>Zuletzt eingereicht: <p><p></p>';
                echo"<table class='data-table' rules='rows'>
                    <col />
                    <col width='50%' />
                    <col />
                        <tr>
                            <th class='fach' style='text-align: left'>Fach&nbsp;</th>
                            <th class='beschreibung' style='text-align: center'>Beschreibung&nbsp;</th>
                            <th class='datum' style='text-align: right'>Fällig bis:&nbsp;</th>
                        </tr>";
                    foreach($newdb->results() as $new) {
                    $datum_f = date('d.m.y', strtotime($new->datum));
                    $datum_check = strtotime($new->datum);
                    
                        if((strtotime($new->datum) < strtotime('+1 day')) && (strtotime($new->datum) > strtotime('today'))) {
                            $datum_f = 'Morgen';
                        }
                        else if(strtotime($new->datum) == strtotime('today')) {
                            $datum_f = 'Heute';
                        }
                        else if((strtotime($new->datum) >= strtotime('+1 day')) && (strtotime($new->datum) < strtotime('+6 day')) ) {

                        $tagzahl = date("w", strtotime($new->datum));
                           switch($tagzahl) {
                               case 1:
                               $datum_f = 'Mo';
                               break;
                               case 2:
                               $datum_f = 'Di';
                               break;
                               case 3:
                               $datum_f = 'Mi';
                               break;
                               case 4:
                               $datum_f = 'Do';
                               break;
                               case 5:
                               $datum_f = 'Fr';
                               break;
                               case 6:
                               $datum_f = 'Sa';
                               break;
                               case 0:
                               $datum_f = 'So';
                               break;
                                }
                            }
                            
                        foreach($wann as $bis => $bg) { 
                                $Atime = strtotime($new->datum) - strtotime("today");
                                $Btime = strtotime($bis) - strtotime("today") ;
                                if ( strtotime($new->erstelldatum) > strtotime('-85 minutes') && strtotime($new->erstelldatum) < strtotime('now') ) {
                                    if ( $Atime < $Btime && $Atime < $viertertag) {
                                        print "<tr>
                                	            <td  class='fach' style='text-align: left; background: url($bg)'>
                                	            <form action='/bearbeiten.php' method='POST'>
                                                    <button class='fachbtn' type='submit' name='submit' value='".$new->id."'>
                                                        ".$new->name."
                                                    </button>
                                                </form>
                                                </td>
                                                <td  class='beschreibung' style='text-align: left; background: url($bg)'>
                                                    ".$new->beschreibung.$space."
                                                </td>";
                                                print "<td  class='datum' style='text-align: right; background: url($bg)'>
                                                    ".$datum_f. ." 
                                                        </td>
                                                </tr>";
                                        break;
                                        } else if ( $Atime >= $viertertag ) {
                                        print "<tr>
                                                <td class='fach' style='text-align: left'>
                                                <form action='/bearbeiten.php' method='POST'>
                                                    <button class='fachbtn' type='submit' name='submit' value='".$new->id."'>
                                                        ".$new->name."
                                                    </button>
                                                </form>
                                                </td>
                                                <td  class='beschreibung' style='text-align: left'>
                                                    ".$new->beschreibung.$space."
                                                </td>";
                                            print "<td  class='datum' style='text-align: right'>".$datum_f. ."</td>
                                                   </tr>";
                                        break;
                                	    }
                                    }
                                }
                            }
                        echo "</table> <br> </center>";                     
                    }
                    ?>
<?php
$pageTitle = 'Archiv';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body class="firstpage" id="Archiv">';
include Config::get('includes/navbar');
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">

                <center>
                
                <h1>Archiv</h1>
                <p><small><small>(Hausaufgaben + Arbeitstermine + Sonstiges zusammengefasst) </small> </small> </p>
<?php

                $user = new User();
                $userklasse= $user->data()->username;
               
                $newdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` ORDER BY datum DESC');
                
                if(!$newdb->count()) {
                    echo'<p>Kein Inhalt </p>';
                } else {
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
                    if(strtotime("today") >  $datum_check) {

                                    print "<tr>
                            	            <td  class='fach' style='text-align: left'>
                            	            ".$new->name."
                                            </td>
                                            <td  class='beschreibung' style='text-align: left;'>
                                                ".$new->beschreibung."
                                            </td>";
                                            
                                    if ($datum_f != "30.11.-1") {
                                            print "<td  class='datum' style='text-align: right;'>
                                                ".$datum_f." 
                                                    </td>
                                            </tr>";
                                    }
                                }
                            } 
                        }
                        echo "</table> <br>";                         
                    
		        ?>
</center>

            </div>
        </div>
    </div>
    <?php include Config::get('includes/footer'); ?>
</body>
</html>
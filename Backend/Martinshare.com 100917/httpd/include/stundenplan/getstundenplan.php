<?php
    require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
    $user = new User();
    $userklasse= $user->data()->username;
    $response = '';
    $newdb = DB::getInstance()->query('SELECT Distinct stunde FROM `TT'.$userklasse.'` ORDER BY stunde desc ');
    $stundenctr = $newdb->first()->stunde;
    $wochentagcounter = 1;

for ($i = 1; $i <= $stundenctr; $i++) {
    
    $newzeitdb = DB::getInstance()->query('SELECT * FROM `TTTJDSR-TGITGM-2016` WHERE stunde = '.$i.' ');
    echo($newzeitdb->first()->beginn);
    $response .= '  <tr class="mvttrow mvttrow'.$i.'">
                <td class="mvttlessoncell mvttlessonhours mvttdayhours " colspan="8">
                    <div>
                        <span class="mvttbegin">'.substr($newzeitdb->first()->beginn, 0, -3).'</span><br>
                        <span>-</span><br>
                        <span class="mvttend">'.substr($newzeitdb->first()->ende, 0, -3).'</span>
                    </div>
                </td>
                ';
    for ($j = 1; $j < 6; $j++) { 
        $ersteset = false;
        
        $newdb = DB::getInstance()->query('SELECT * FROM `TT'.$userklasse.'` WHERE stunde = "'.$i.'" AND wochentag = "'.$j.'" ORDER BY stunde,wochentag Asc ');
        
        $colspannew = $newdb->count();
        $neuescolspan = 9 - $colspannew;
        
        // echo "<pre>"; print_r($newdb->results()); echo "</pre>";
        if($newdb->count()) {
            foreach($newdb->results() as $new) {
                $fach = $new->fach;
                
                if($neuescolspan == 1){
                    $response .=
                        '<td class="mvttlessoncell mvttparent mvttday'.$j.' mvdaycell" colspan="8" >
                        <span data-tag="'.$j.'" data-stunde="'.$i.'" data-id="'.$new->id.'" class=" mvttday'.$j.' mvttmaincell">'.$fach.'</span>
                    </td>';
                } else {
                    
                    if(!$ersteset) {
                        $response .=
                        '<td class="mvttlessoncell mvttparent mvttday'.$j.' mvdaycell" colspan="'.$neuescolspan.'" >
                            <span data-tag="'.$j.'" data-stunde="'.$i.'" data-id="'.$new->id.'" class=" mvttday'.$j.' mvttmaincell">'.$fach.'</span>
                        </td>';
                        $ersteset = true;
                        
                    } else {
                        $response .=
                        '<td class="mvttlessoncell mvttnoparent mvttday'.$j.' mvdaycell" colspan="1" >
                            <span data-tag="'.$j.'" data-stunde="'.$i.'" data-id="'.$new->id.'" class=" mvttday'.$j.' mvttmaincell">'.$fach.'</span>
                        </td>';
                    }
                }
            }
                      
        } else {
            $response .='<td class="mvttlessoncell mvttparent mvttday'.$j.' mvdaycell" colspan="8" >
                        <span data-tag="'.$j.'" data-stunde="'.$i.'" data-id="" class=" mvttday'.$j.' mvttmaincell">Leer</span>
                    </td>';
        }
    }
$response .= '</tr>';
}
    echo $response;
?>   
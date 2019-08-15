
<?php
$viertertag = 86400*4;
$wann = array(
    '+0 day' => '/images/done.png',
    '+1 day' => '/images/red-bg.png',
    '+2 day' => '/images/orange-bg.png',
    '+3 day' => '/images/yellow-bg.png',
);
    
setlocale(LC_TIME, 'de_DE');
    
if(Session::exists('eintragerfolgreich')) {
    echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> '.Session::flash('eintragerfolgreich'),'</div>';
}
    
$eintraege = new EintragCRUD();
$newdb = $eintraege->getEintragQuery($_typ, "Asc");
    
if(!$newdb->count()) {
    echo'<p>Kein Inhalt </p>';
} else {
    echo"<table class='data-table' rules='rows'>
    <col />
    <col width='50%' />
    <col />
    <tr>
        <th class='fach' >Fach&nbsp;</th>
        <th class='beschreibung' >Beschreibung&nbsp;</th>
        <th class='datum' >Fällig bis:&nbsp;</th>
    </tr>";
    foreach($newdb->results() as $new) {
        
        $datum_check = strtotime($new->datum);
        if(strtotime("today") <= strtotime('today', $datum_check)) {
            if(($datum_check < strtotime('+1 day')) && ($datum_check > strtotime('today'))) {
                
                $datum_f = 'Morgen';
                
            } else if($datum_check == strtotime('today')) {
                
                $datum_f = 'Heute';
                
            } else if(($datum_check >= strtotime('+1 day')) && ($datum_check < strtotime('+6 day')) ) {
                
                $datum_f = strftime(" %A", $datum_check);
                
            } else {
                
               $datum_f = strftime(" %d.%m.%Y", $datum_check);
               
            }
            
            foreach($wann as $bis => $bg) { 
                $Atime = strtotime($new->datum) - strtotime("today");
                $Btime = strtotime($bis) - strtotime("today") ;
                
                #<form action='/bearbeiten.php' method='POST'>
                #                        <button class='fachbtn' type='submit' name='submit' value='".$new->id."'>
                #                            ".$new->name."
                #                        </button>
                #                    </form>
                if ( $Atime < $Btime && $Atime < $viertertag) {
                    print  "<tr>
                                <td class='fach' style='text-align: left; background: url($bg)'>
                                ".$new->name."
                                </td>
                                <td class='beschreibung' style='text-align: left; background: url($bg)'>".$new->beschreibung."</td>
                                <td class='datum' style='text-align: right; background: url($bg)'>".$datum_f."</td>
                            </tr>";
                    break;
                } else if ( $Atime >= $viertertag ) {
                print  "<tr>
                            <td class='fach' style='text-align: left'>
                            ".$new->name."
                            </td>
                            <td class='beschreibung' style='text-align: left'>".$new->beschreibung."</td>
                            <td class='datum' style='text-align: right'>".$datum_f."</td>
                        </tr>";
                break;
                }
            }
        } 
    }
    echo "</table> <br>";                         
}
 
    

?>

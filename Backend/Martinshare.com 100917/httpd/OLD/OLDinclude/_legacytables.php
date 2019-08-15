<?php
$viertertag = 86400*4;
$wann = array(
    '+0 day' => 'done.png',
    '+1 day' => 'red-bg.png',
    '+2 day' => 'orange-bg.png',
    '+3 day' => 'yellow-bg.png',
);

    
    
    $space = "   ";

    while ($datensatz = mysql_fetch_array($query)) {
    $datum_f = date('d.m.y', strtotime($datensatz['datum']));
    
    foreach($wann as $bis => $bg)
    { 
        $Atime = strtotime($datensatz['datum']) - strtotime("today");
        $Btime = strtotime($bis) - strtotime("today") ;
        
        if ( $Atime < $Btime && $Atime < $viertertag) {
    	    print "<tr><td style='text-align: left; background: url(/images/$bg)'><form action='/bearbeiten.php' method='POST'>
            <button type='submit' name='submit' value='".$datensatz['id']."'>".$datensatz['name']."</button></form></td>";
            print "<td style='text-align: center; background: url(/images/$bg)'>".$datensatz['beschreibung'].$space."</td>";
            if ($datum_f != "30.11.-1") {print "<td style='text-align: right; background: url(/images/$bg)'>".$datum_f. ."</td></tr>";}
            break;
        }   
        else if ( $Atime >= $viertertag ) {
            print "<tr><td style='text-align: left'><form action='/bearbeiten.php' method='POST'>
            <button type='submit' name='submit' value='".$datensatz['id']."'>".$datensatz['name']."</button></form></td>";
            print "<td style='text-align: center'>".$datensatz['beschreibung'].$space."</td>";
            if ($datum_f != "30.11.-1") {print "<td style='text-align: right'>".$datum_f. ."</td></tr>";}
            break;
    	}
    } 
    
    /*  //Alter Weg zum anzeigen + färben. Bei Notfall entkommentieren!!! (wenn neuer Weg probleme bereiten sollte)
    
	if ($datum_f == date('d.m.y', strtotime('0 day'))) {
	    print "<tr><td style='text-align: left; background: url(/images/done.png)'><form action='/bearbeiten.php' method='POST'>
        <button type='submit' name='submit' value='".$datensatz['id']."'>".$datensatz['name']."</button></form></td>";
        print "<td style='text-align: center; background: url(/images/done.png)'>".$datensatz['beschreibung'].$space."</td>";
        if ($datum_f != "30.11.-1") {print "<td style='text-align: right; background: url(/images/done.png)'>".$datum_f. ."</td></tr>";}
    }
    
    else if ($datum_f == date('d.m.y', strtotime('+1 day'))) {
        print "<tr><td style='text-align: left; background: url(/images/red-bg.png)'><form action='/bearbeiten.php' method='POST'>
        <button type='submit' name='submit' value='".$datensatz['id']."'>".$datensatz['name']."</button></form></td>";
        print "<td style='text-align: center; background: url(/images/red-bg.png)'>".$datensatz['beschreibung'].$space."</td>";
        if ($datum_f != "30.11.-1") {print "<td style='text-align: right; background: url(/images/red-bg.png)'>".$datum_f. ."</td></tr>";}
    }
    
    else if ($datum_f == date('d.m.y', strtotime('+2 day'))) {
        print "<tr><td style='text-align: left; background: url(/images/orange-bg.png)'><form action='/bearbeiten.php' method='POST'>
        <button type='submit' name='submit' value='".$datensatz['id']."'>".$datensatz['name']."</button></form></td>";
        print "<td style='text-align: center; background: url(/images/orange-bg.png)'>".$datensatz['beschreibung'].$space."</td>";
        if ($datum_f != "30.11.-1") {print "<td style='text-align: right; background: url(/images/orange-bg.png)'>".$datum_f. ."</td></tr>";}
    }
    
    else if ($datum_f == date('d.m.y', strtotime('+3 day'))) {
        print "<tr><td style='text-align: left; background: url(/images/yellow-bg.png)'><form action='/bearbeiten.php' method='POST'>
        <button type='submit' name='submit' value='".$datensatz['id']."'>".$datensatz['name']."</button></form></td>";
        print "<td style='text-align: center; background: url(/images/yellow-bg.png)'>".$datensatz['beschreibung'].$space."</td>";
        if ($datum_f != "30.11.-1") {print "<td style='text-align: right; background: url(/images/yellow-bg.png)'>".$datum_f. ."</td></tr>";}
    }

    else {
        print "<tr><td style='text-align: left'><form action='/bearbeiten.php' method='POST'>
        <button type='submit' name='submit' value='".$datensatz['id']."'>".$datensatz['name']."</button></form></td>";
        print "<td style='text-align: center'>".$datensatz['beschreibung'].$space."</td>";
        if ($datum_f != "30.11.-1") {print "<td style='text-align: right'>".$datum_f. ."</td></tr>";}
	} */
}
echo "</table>";
?>

<br><br>
<!--<div align="center">
	<h3>Legende:</h3>
    <table rules='rows' >
    <tr><td style='text-align: left'>Fällig bis:</td><td style='text-align: center'>Farbe</td></tr>
    <tr><td style='text-align: left'>Heute</td><td style='text-align: center; background: url(/images/done.png)'>Weiß</td></tr>
    <tr><td style='text-align: left'>Morgen</td><td style='text-align: center; background: url(/images/red-bg.png)'>Rot</td></tr>
    <tr><td style='text-align: left'>In 2 Tagen</td><td style='text-align: center; background: url(/images/orange-bg.png)'>Orange</td></tr>
    <tr><td style='text-align: left'>In 3 Tagen</td><td style='text-align: center; background: url(/images/yellow-bg.png)'>Gelb</td></tr>
</div>
</table> -->
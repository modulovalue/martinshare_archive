<?php
$pageTitle = 'Einreichen';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body id="Einreichen">';
include Config::get('includes/navbar');

$user = new User();
$userklasse= $user->data()->username;

if (!empty($_POST["db_select"])) {$welche_db = htmlentities($_POST["db_select"]);}
if (!empty($_POST["name"])) {$name = htmlentities($_POST["name"]);}
if (!empty($_POST["beschreibung"])) {$beschreibung = htmlentities($_POST["beschreibung"]);}
if (!empty($_POST["datum"])) {$datum = htmlentities($_POST["datum"]);}

if (! (empty($_POST["db_select"]) & empty($_POST["name"]))) {
    
    $w_db = array('arbeitstermine' => 'a',
                  'hausaufgaben' => 'h',
                  'sonstiges' => 's',);
    
    include 'include/connect.inc.php';
    foreach($w_db as $art => $abbr)
    {
    if ($welche_db == $art) {
        $query = mysql_query("INSERT INTO `".$userklasse."` (typ, name, beschreibung, datum) VALUES ('$art', '$name', '$beschreibung', '$datum')")
        or die("INSERT fehlgeschlagen: ".mysql_error());}
        Session::flash('eintragerfolgreich', 'Dein Eintrag wurde gespeichert!');
    }
    
    mysql_close();

if ($welche_db == "arbeitstermine") {echo '<meta http-equiv="refresh" content="0; URL=arbeitstermine.php">';}
if ($welche_db == "hausaufgaben") {echo '<meta http-equiv="refresh" content="0; URL=hausaufgaben.php">';}
if ($welche_db == "sonstiges") {echo '<meta http-equiv="refresh" content="0; URL=sonstiges.php">';}
}

?>
    

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Einreichen</h1>
                
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
                                                <td  class='fach' style='text-align: left'>
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
                
                
                <p>Art:</p>
	        	<form action="/einreichen.php" method="POST">
	        	<select name="db_select" size="3">
	        	<option value="arbeitstermine">Arbeitstermin</option>
	        	<option value="hausaufgaben">Hausaufgabe</option>
	        	<option value="sonstiges">Sonstiges</option>
	        	</select>

	        	<p></p>
	        	<p>Details:</p>

	        	<center>
    	        	<div style="max-width: 220px;" class="input-group input-group-sm">
                      <span class="input-group-addon">Fach</span>
        	        	<input type="text" id="autocomplete" required="" name="name" class="form-control" placeholder="Fach" cols="20" rows="1">
    	        	</div>
    	        </center>
	        	<br>

                <center>
    	        	<div style="max-width: 200px;" class="input-group">
	        	        <textarea name="beschreibung" id="beschreibung" placeholder="Beschreibung" cols="25" rows="5"></textarea>
                    </div>
    	        </center>

	        	<br>
                <p>Fällig am:</p>
	        	<input type="date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder="yyyy-mm-dd"  name="datum" />
	        	
	        	<p><br></p>
	        	<input type="submit" style="height:35px; width:130px"  value="Einreichen">
	        	</form>
	        	
                <?php
                $target_path = 'images/stundenpläne/'.$userklasse.'/';
                $stundenplan = "$target_path/stundenplan.png";
                    if (file_exists($stundenplan)) {
                    
                    echo"
                    <br />
                    <h4><a href=\"javascript:toggle('stundenplan')\">Stundenplan</a></h4>
                    <div id=\"stundenplan\" style=\"display: none\">";
                    echo "<span class='zoom' id='ex2'>
            		<img src='$stundenplan' width='280' alt='Stundenplan'/>
                	</span>
                	</div><br>";
                      } 
                ?>
                </div>
            </div>
        </div>
    </div>
     <?php include Config::get('includes/footer'); ?>
     
    <script type="text/javascript" src="include/autocomplete/jquery.autocomplete.js"></script>
    
	<script type="text/javascript">

	$(function () {
		'use strict';

		var nhlTeams = [ "Deutsch",
		"Englisch",
		"Informationstechnik",
		"Chemie",
		"GGK",
		"Geschichte",
		"Computertechnik",
		"CT",
		"IT",
		"Französisch",
		"Spanisch",
		"Italienisch",
		"Physik",
		"Mathematik",
		"Wirtschaft",
		"Religion Evangelisch",
		"Religion Katholisch",
		"Ethik",
		"Sport",
		"Frei",
		"Maschinentechnik",
		"Elektrotechnik",];
		var nhl = $.map(nhlTeams, function (team) { return { value: team, data: { category: 'Fächer' }}; });
		var Faecher = nhl;

		// Initialize autocomplete with local lookup:
		$('#autocomplete').devbridgeAutocomplete({
			lookup: Faecher,
			minChars: 1,
			onSelect: function (suggestion) {
				$('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data.category);
			},
			showNoSuggestionNotice: false,
			noSuggestionNotice: 'Nichts gefunden',
			groupBy: 'category'
		});
		

	});
	</script>
     
    <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
    <script>
    webshims.setOptions('forms-ext', {types: 'date'});
    webshims.polyfill('forms forms-ext');
    </script>
    
</body>
</html>
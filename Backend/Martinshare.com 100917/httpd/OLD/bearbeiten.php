<?php
$pageTitle = 'Bearbeiten';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body>';
include Config::get('includes/navbar');
$user = new User();
$userklasse= $user->data()->username;

if (!empty($_POST["submit"])) {
    $id = $_POST["submit"];
    
    include 'include/connect.inc.php';
    $query = mysql_query('SELECT * FROM `'.$userklasse.'` WHERE id='.$id.'') or die("SELECT fehlgeschlagen: ".mysql_error());
    
    $datensatz = mysql_fetch_array($query);
    
    $e_typ = $datensatz['typ'];
    $e_name = $datensatz['name'];
    $e_beschreibung = $datensatz['beschreibung'];
    $e_datum = $datensatz['datum'];
    
    mysql_close($link);
}

if (!empty($_POST["changeEntry"])) {
    $id = $_POST["id_key"];
    $welche_db = $_POST["db_select"];

    if ($welche_db == "arbeitstermine") {$typ = "a";}
    if ($welche_db == "hausaufgaben") {$typ = "h";}
    if ($welche_db == "sonstiges") {$typ = "s";}
    
    $name = htmlentities($_POST["name"]);
    $beschreibung = htmlentities($_POST["beschreibung"]);
    $datum = htmlentities($_POST["datum"]);
    
    include 'include/connect.inc.php';
    $query = mysql_query('DELETE FROM `'.$userklasse.'` WHERE id='.$id.'') or die("DELETE fehlgeschlagen: ".mysql_error());
    $query = mysql_query("INSERT INTO `".$userklasse."` (typ, name, beschreibung, datum) VALUES ('$typ', '$name', '$beschreibung', '$datum')") or die("INSERT fehlgeschlagen: ".mysql_error());
    
    mysql_close($link);
    
    if ($typ == "a") {echo '<meta http-equiv="refresh" content="0; URL=arbeitstermine.php">';}
    if ($typ == "h") {echo '<meta http-equiv="refresh" content="0; URL=hausaufgaben.php">';}
    if ($typ == "s") {echo '<meta http-equiv="refresh" content="0; URL=sonstiges.php">';}
    
    Session::flash('eintragerfolgreich', 'Der Eintrag wurde geändert!');
    }


?>
<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
<script>
webshims.setOptions('forms-ext', {types: 'date'});
webshims.polyfill('forms forms-ext');
</script>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Eintrag bearbeiten</h1>
                <p>Art ändern ?:</p>
	        	<form action="/bearbeiten.php" method="POST">
	        	<select name="db_select" size="3">
	        
	        	<option <?php if ($e_typ == 'a') {echo 'selected="selected"';} ?> value="arbeitstermine">Arbeitstermin</option>
	        	<option <?php if ($e_typ == 'h') {echo 'selected="selected"';} ?> value="hausaufgaben">Hausaufgabe</option>
	        	<option <?php if ($e_typ == 's') {echo 'selected="selected"';} ?> value="sonstiges">Sonstiges</option>
	        	</select>

	        	<p></p>
	        	<p>Details:</p>
	        	<p><?php echo $userklasse ?></p>
	        	<textarea required="" name="name" placeholder="Fach" cols="20" rows="1"><?php echo $e_name ?></textarea>
	        	<p></p>
	        	<textarea name="beschreibung" placeholder="Beschreibung" cols="25" rows="5"><?php echo $e_beschreibung ?></textarea>
	        	<p></p>
	        	<p>Fällig am:</p>
	        	<input type="date" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder="yyyy-mm-dd"  name="datum" value=<?php echo $e_datum ?>>
	        	<p><br></p>
	        	<input type='hidden' name='id_key' value="<?php echo $id ?>">
	        	<input type="submit" name="changeEntry" value="Eintrag aktualisieren">
	        	</form>
            </div>
        </div>
    </div>
    
    <?php include Config::get('includes/footer'); ?>

</body>
</html>
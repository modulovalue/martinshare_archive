<?php
$pageTitle = 'Einreichen';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

if(Input::exists()) {
        $validate = new Validate();
        $validation = $validate-> check($_POST, array( 
            'db_select' => array(
                'required' => true
            ),
            'name' => array(
                'required' => true
            ),
            'beschreibung' => array(
            ),
            'datum' => array(
                'required' => true
            )
            )); 
        
        if($validation->passed()) {
            
            $eintragart = Input::get('db_select');
            $w_db = array('arbeitstermine' => 'a',
                  'hausaufgaben' => 'h',
                  'sonstiges' => 's',);
                  
            foreach($w_db as $art => $abbr) {
                if ($eintragart == $art) {
                    $eintragart = $abbr;
                }
            }
            $user = new User();
            $data = $user->data();
            $userklasse = $user->data()->username;
                $insert = DB::getInstance()->insert('`'.$userklasse.'`', array(
                        'typ'           => $eintragart,
                        'name'          => Input::get('name'),
                        'beschreibung'  => Input::get('beschreibung'),
                        'datum'         => Input::get('datum')
                        ));  
                if($insert) {
                    Session::flash('eintragerfolgreich', 'Dein Eintrag wurde gespeichert!');
                    if (Input::get('db_select') == "arbeitstermine") {
                        echo '<meta http-equiv="refresh" content="0; URL=arbeitstermine.php">';
                    }
                    if (Input::get('db_select') == "hausaufgaben") {
                        echo '<meta http-equiv="refresh" content="0; URL=hausaufgaben.php">';
                    }
                    if (Input::get('db_select') == "sonstiges") {
                        echo '<meta http-equiv="refresh" content="0; URL=sonstiges.php">';
                    }
                } else {
                    Session::flash('erroreintrag', 'Fehler! bitte kontaktiere den Support');
                }
            
        } else {
            foreach($validation->errors() as $error) {
                Session::flash('erroreintrag', $error.'<br>');
            }
        }
}

include Config::get('includes/header');
echo '</head>';
echo '<body id="Einreichen">';
include Config::get('includes/navbar');

?>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Einreichen</h1>
                <?php
                if(Session::exists('erroreintrag')) {
                   echo '<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> '.Session::flash('erroreintrag'),'</div>';
                }
                
                include 'include/showlastrecords.php';
		        ?>

                <p>Art:</p>
	        	<form action="" method="POST">
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

            </div>
        </div>
    </div>
     <?php include Config::get('includes/footer'); ?>
     
    <script type="text/javascript" src="include/autocomplete/jquery.autocomplete.js"></script>
    
	<script type="text/javascript" src="include/autocomplete/autocompletearray.js"></script>
     
    <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
    <script>
    webshims.setOptions('forms-ext', {types: 'date'});
    webshims.polyfill('forms forms-ext');
    </script>
    
</body>
</html>
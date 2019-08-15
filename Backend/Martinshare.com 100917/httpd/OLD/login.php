<?php
$noLogin = true;
$dontvisitifloggedin = true;
$pageTitle = 'Wähle deine Klasse';

require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        
        
        $validate = new Validate();
        $validation = $validate-> check($_POST, array( 
                'Benutzername' => array(
                    'required' => true),
                'Passwort' => array(
                    'required' => true)
            ));
        

        if($validation->passed()) {
            $user = new User();
            $data = $user->data();
            
            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('Benutzername'), Input::get('Passwort'), $remember);
            $user = new User();
            $data = $user->data();
            if($login) {
                    setcookie("klasse", $data->username, time() + 94670778, '/','.martinshare.com');
                    Session::flash('eingeloggt', 'Willkommen zurück <strong>'.Input::get('Benutzername').'</strong>! <span class="glyphicon glyphicon-thumbs-up"></span>');
                    Redirect::to('main.php');
            }
            
            else {
                Session::flash('errorlogin', 'Falsche Benutzername/Passwort Kombination <br>');
            }
        }
        
        else {
            foreach($validation->errors() as $error) {
                Session::flash('errorlogin', $error.'<br>');
            }
        }
    }
}

include Config::get('includes/header');
?>

<?php
echo '</head>';
echo '<body>';

$schulenneu = array('Josef-Durler-Schule Rastatt' => array("TGI-2015", "TGM-2015", "TGITGM-2016", "TGTM-2015"),
					                #'neueschule' => array("klasse0", "klasse1"),								
	);
$schulekurz = array('Josef-Durler-Schule Rastatt' => "JDSR-",
					   								
	);
?>

<script type="text/javascript">
  function setUsername(value) {
    $("#Benutzername").val(value);
    $("#Passwort").focus();
  }
</script>

    <div class="container">
        <div class="row">
            <div class="text-center"> 
                <h1>Martinshare</h1>

                <?php
                $_schulen = 'Schulenliste';
                echo '<h3>Gib deine Benutzerdaten ein</h3><p></p>';

                /*echo '<p>oder <a href="javascript:toggle(\''.$_schulen.'\')">wähle eine Klasse</a></p>';
                    echo '<div id=\''.$_schulen.'\' style="display: none">';
                        foreach($schulenneu as $name=>$klassen) {
                            echo '<p></p>';
                            $bg = '/images/schulenbanner/Josef-Durler-Schule Rastatt.jpg';
                            echo '
                            <section class="roundedcorners module parallax" style="background-image: url(\''.$bg.'\');" >
                            <h3>
                            <a href="javascript:toggle(\''.$name.'\')">'.$name.' </a>
                            </h3>
                            ';
                            echo '<div id=\''.$name.'\' style="display: none">';
                            echo '<hr noshade width="300" size="3" align="center">';
                                foreach($klassen as $klasse) {
                                echo'<p></p>';
                                echo' <input style="height: 40px; width: 200px" type="submit" 
                                      onclick="javascript:toggle(\''.$name.'\');
                                               javascript:toggle(\''.$_schulen.'\');
                                               javascript:setUsername(\''.$schulekurz[key($schulenneu)].$klasse.'\');
                                                                                  " value='.$klasse.' name="id">';
                                    
                                }
                            echo'<hr noshade width="300" size="3" align="center">';
                        }
                        echo"<br></div> 
                            </section>";
                    echo"</div>";
                    */
                ?> 
               
                <form action="" method="post" class="login">
                    <div class="loginfield">
                        <label for="Benutzername"><p>Benutzername:</p>
                            <input  style="height: 28px; width: 225px" 
                                    type="text" 
                                    name="Benutzername" 
                                    placeholder="Schule-Klasse-Abschlussjahr" 
                                    id="Benutzername" 
                                    class="login-input"
                                    autocomplete="on"
                                    value="<?php echo escape(Input::get('Benutzername'));?>">
                            </label>
                    </div>
                    
                    <div class="loginfield">
                        <label for="Passwort"><p>Passwort:</p>
                            <input  style="height: 28px; width: 225px" 
                                    type="password" 
                                    name="Passwort" 
                                    placeholder="Passwort" 
                                    id="Passwort" 
                                    class="login-input"
                                    autocomplete="off">
                        </label>
                    </div>
                    
                    <div class="loginfield">
                        <center><label for="checkbox"><p> Eingeloggt bleiben? 
                                
                                    <input  type="checkbox" 
                                            name="remember" 
                                            id= "remember"
                                            class="checkmark"
                                            checked></p>
                                
                        </label></center>
                    </div>
                    
                    <center>
                    <div class="loginfield">
                        <input type="submit" 
                               style="height: 30px; width: 100px" 
                               value="Log in"
                               class="login-input">
                    </div>        
                    </center>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                      
                    <?php if(Session::exists('errorlogin')) {
                        echo '<p> <font color="red">' . Session::flash('errorlogin') . ' </font></p>';
                    }
                    ?>
                </form>
                
                <br>
                <p><small>Durch die Nutzung unserer Webseite erklären Sie, dass Sie
                <br> die <a href="/nutzungsbedingungen.php">Nutzungsbedingungen</a> 
                gelesen haben, verstanden haben<br> und diese Bedingungen akzeptieren.</small></p>
            </div>
        </div>
    </div>

    <!--=========  Analytics  =========-->
    <!--<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-50057105-3', 'auto');
    ga('send', 'pageview');
    </script> -->
</body>
</html>
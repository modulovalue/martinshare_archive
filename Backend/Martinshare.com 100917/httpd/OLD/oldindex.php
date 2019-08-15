<?php
//if not logged in, and false, redirects to index
$noLogin = true;
//if true, redirects to main.php;
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

?>

    <div class="container">
        <div class="row ">
            <div class="col-lg-12 text-center">
                  <img style="padding-top: 9px; width: 300px;" src="./images/logomartinshare.png">
                  <b><p><a href="http://ios.martinshare.com">iOS</a> • <a href="http://android.martinshare.com">Android</a></p></b>
                
                    <p style="margin-bottom: 30px;"></p>
               
                    <h4>Gib deine Benutzerdaten ein</h4><p></p>

               
                <form action="" method="post" class="login">
                    <div class="loginfield">
                        <label for="Benutzername"><p>Benutzername:</p>
                            <input  style="height: 28px; width: 225px" 
                                    type="text" 
                                    name="Benutzername" 
                                    placeholder="Nutzername" 
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
                
                <i><h3>Du willst einen Login oder hast Fragen? <br>
                    <a href="mailto:info@martinshare.com?subject=Martinshare Frage"> info@martinshare.com</a></h3>
                </i>
                
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
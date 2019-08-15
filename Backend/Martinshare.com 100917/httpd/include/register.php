<?php
    $noLogin = false;
    include_once 'core.inc.php';
    

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        

        #Input::get('username');
       $validate = new Validate();
       $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'schule_id' => array(
                'required' => true
            ),
           # 'klasse' => array(
           #     'required' => true,
            #    'min' => 3
           # ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
               'required' => true,
               'matches' => 'password'
               
            ),
           # 'name' => array(
            #   'required' => true,
            #   'min' => 2,
           #    'max' => 50
           # )
        ));
        if($validation->passed()) {
            $user = new User();
            $salt = Hash::salt(32);
            try {
            
                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    #'klasse' => Input::get('klasse'),
                    'schule_id' => Input::get('schule_id'),
                        'salt' => $salt,
                        #'name' => Input::get('name'),
                        #WENN NICHT FUNKTIONIERT, JOINED AUSKOMMENTIEREN
                    'joined' => date('Y-m-d H:i:s'),
                    'group' => 1,
                       
                ));
                //
                //$to      = 'info@martinshare.com';
                //$subject = 'Neuer Benutzer: '.Input::get('username');
                //$message = 'Neuer Benutzer  Benutzername: '.Input::get('username').'  Passwort: '.Input::get//('password').'  Schule: '.Input::get('schule_id') ;
                //$headers = 'From: neuerlogin@martinshare.com' . "\r\n" .
                //    'Reply-To: info@example.com' . "\r\n" .
                //    'X-Mailer: PHP/' . phpversion();
                //
                //mail($to, $subject, $message, $headers);

            } catch(Exception $e) {
                die($e->getMessage());
            }
            
             include 'connect.inc.php';
            
            //$neuetable = mysql_query('    
            //                            CREATE TABLE IF NOT EXISTS `'.Input::get('username').'` (
            //                              `id` int(11) NOT NULL AUTO_INCREMENT,
            //                              `typ` char(1) DEFAULT NULL,
            //                              `name` text,
            //                              `beschreibung` text,
            //                              `datum` date DEFAULT NULL,
            //                              `erstelldatum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE //CURRENT_TIMESTAMP,
            //                              PRIMARY KEY (`id`)
            //                            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
            //            ');
            //if (!$neuetable) {
            //    Redirect::to('index.php');
            //    die('Ungültige Anfrage: ' . mysql_error($error));
            //}
            #weiterleitung mit nachricht
            Session::flash('register', 'Nutzer wurde erfolgreich registriert!');
            Redirect::to('index.php');
            #Redirect::to(404);
                
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}

?>
<form action="" method="post">

    <div class ="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>">
    </div>
    <!--<div class ="field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name'));?>">
    </div>-->
    
    <div class ="field">
        <label for="schule_id">Schuleid</label>
        <input type="text" name="schule_id" id="schule_id">
    </div>
    <!--
    <div class ="field">
        <label for="klasse">Klasse</label>
        <input type="text" name="klasse" id="klasse">
    </div>
    -->
    <div class ="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div class ="field">
        <label for="password_again">Password nochmal</label>
        <input type="password" name="password_again" id="password_again">
    </div>
    
    
    
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Register">
    
</form>
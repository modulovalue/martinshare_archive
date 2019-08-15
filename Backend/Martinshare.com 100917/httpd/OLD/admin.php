<?php

$pageTitle = 'Admin';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);
$user = new User();
$data = $user->data();
if($data->group != 2)
{
    Redirect::to('main.php');
}
include Config::get('includes/header');
echo '</head>';
echo '<body>';
include Config::get('includes/navbar');

?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-center">
                
<?php
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        
       $validate = new Validate();
       $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'schuleid' => array(
                'required' => true
            ),
            'klasse' => array(
                'required' => true,
                'min' => 3
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
               'required' => true,
               'matches' => 'password'
               
            ),
            'name' => array(
               'required' => '',
               'min' => 2,
               'max' => 50
            )
        ));
        if($validation->passed()) {
            
            $user = new User();
            $salt = Hash::salt(32);
            $newusername = Input::get('username');
            $newpassword = Hash::make(Input::get('password'), $salt);
            $newklasse = Input::get('klasse');
            $newname = Input::get('name');
            $joined = date('Y-m-d H:i:s');
            $newschuleid = Input::get('schuleid');
            $newabschjahr = '2016';
            
            
            $insert = DB::getInstance()->insert('`users`', array(
            'username' => $newusername,
            'password' => $newpassword,
                'salt' => $salt,
            'schuleid' => $newschuleid,
            'abschjahr'=> $newabschjahr,
              'klasse' => $newklasse,
                'name' => $newname,
              'joined' => $joined,
               'group' => '1'
            ));  
                if($insert) {
                
                } else {
                    die(print_r($insert));
                }
           /* try {
                
                    $user->create(array(
                        'username' => $newusername,
                        'password' => $newpassword,
                        'klasse' => $newklasse,
                        'schuleid' => $newschuleid,
                            'salt' => $salt,
                            'name' => $newname,
                          'joined' => $joined,
                           'group' => 1
                    ));
            } catch(Exception $e) {
                die($e->getMessage());
                
            }*/
            
            $neuetable =  DB::getInstance()->query('    
                    CREATE TABLE IF NOT EXISTS `'.$newusername.'` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `typ` char(1) DEFAULT NULL,
                      `name` text,
                      `beschreibung` text,
                      `datum` date DEFAULT NULL,
                      `erstelldatum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
                            ');
                if (!$neuetable) {
                    die('Ungültige Anfrage: ' . mysql_error($error));
                }

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

<h1>User registrieren</h1>
<br>
<p><?php Session::flash('register');?></p>
<br>
<?php 
$user = new User();
$data = $user->data(); ?>
<p><?php echo $data->group; ?></p>
<form action="" method="post">

    <div class ="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="">
    </div>
    <div class ="field">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="">
    </div>
    
    <div class ="field">
        <label for="schuleid">Schuleid</label>
        <input type="text" name="schuleid" id="schuleid">
    </div>
    
    <div class ="field">
        <label for="klasse">Klasse</label>
        <input type="text" name="klasse" id="klasse">
    </div>
    
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
                
                
                
                
                
                
            </div>
        </div>
    </div>
    <?php include Config::get('includes/footer'); ?>
</body>
</html>
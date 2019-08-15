<?php
$pageTitle = 'Einstellungen';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
if(Input::exists()) {
        $validate = new Validate();
        $validation = $validate-> check($_POST, array( 
                'nutzername' => array(
                    'required' => true,
                    'min' => 4,
                    'max' => 20,
                    'letandnum' => 'nix')
            ));
        
        if($validation->passed()) {
            $user = new User();
            $userid = $user->data()->id;
            $nameunique = DB::getInstance()->query('SELECT * FROM `users_session` WHERE user_id="'.$userid.'" AND name="'.escape(Input::get('nutzername')).'" ');
            if(!$nameunique->count()) {
                
                $chatname = escape(Input::get('nutzername'));
                    Session::put(Config::get('chat/session_name'), $chatname);
                    $cookiehash = escape(Cookie::get(Config::get('remember/cookie_name')));
                    $nameupdate = DB::getInstance()->query('UPDATE `users_session` SET `name` = "'.$chatname.'" WHERE `user_id` ="'.$userid.'" AND hash="'.$cookiehash.'" ');
                    Session::put(Config::get('chat/session_name'), $chatname);
                    Session::flash('nameerstellt', '<i>Hallo</i> <strong>'.$chatname.'</strong>');
                    Redirect::to('einstellungen.php');
        
            } else {
                    Session::flash('nameerror', 'NAME SCHON VORHANDEN');
                    Redirect::to('einstellungen.php');
            }
        }
        
        else {
            foreach($validation->errors() as $error) {
                Session::flash('nameerror', $error.'<br>');
                Redirect::to('einstellungen.php');
            }
        }
}
include Config::get('includes/header');




?>
</head>
<?php
echo '<body id="Einstellungen">';
include Config::get('includes/navbar');
$user = new User();
$userklasse= $user->data()->username;
?>

                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                <br>
                                <p> <?php $chatname = Session::get(Config::get('chat/cookie_name')); 
                                        if(!$chatname) {
                                            echo "Bitte wählen Sie einen Namen";
                                        } else {
                                            echo "Hallo $chatname";
                                        }
                                            ?> </p>
                                
                                <?php
                                if(Session::exists('nameerror')) {
                                echo '<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> '.Session::flash('nameerror'),'</div>';
                                    }
            
                                if(Session::exists('nameerstellt')) {
                                       echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> '.Session::flash('nameerstellt'),'</div>';
                                    }
                                ?>
                                    <h1>Einstellungen</h1>
                                    <br>
                                    <form action="" method="post">
                        	        	<center>
                        	        	<p>Um die zukünftigen features nutzen zu können, benötigst du einen Namen.</p><p>Bitte gib einen Namen ein!</p><br>
                        	        	
                            	        	<div style="max-width: 250px;" class="input-group input-group-sm">
                                              <span class="input-group-addon">Name</span>
                                	        	<input type="text" id="nutzername" required="" name="nutzername" class="form-control" placeholder="Name" cols="20" rows="1">
                            	        	</div>
                            	        </center>
                        	        	<br>
                        	        	<input type="submit" style="height:35px; width:130px"  value="Namen wählen">
                    	        	</form>
                    	        	<br>
                    	        	<!--<small><p>Bei jedem Ausloggen wird dein Name zurückgesetzt</p></small>-->
                                </div>
                            </div>
                        </div>
                    
      <?php include Config::get('includes/footer'); ?>
</body>
</html>
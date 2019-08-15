<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

$user = new User();

if($user->isLoggedIn()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));
        
        if($validation->passed()) {
            
            try {
                $user->update(array(
                    'name' => Input::get('name')    
                ));
                Redirect::to('index.php');
            } catch(Exception $e) {
                die($e->getMessage());
            }
            
        } else {
            foreach($validation->errors() as $error) {
                echo $error. '<br>';
            }
        }
    }
}


if($user->hasPermission('moderator')) {
    echo '<p>You are a moderator</p>';
}
?>

<form action="" method="post">
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?php echo escape($user->data()->name); ?>">
        
        <input type="submit" value="Update">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    </div>
</form>
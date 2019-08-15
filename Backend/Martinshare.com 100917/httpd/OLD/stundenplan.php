<?php
$pageTitle = 'Stundenplan';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body id="Stundenplan">';
include Config::get('includes/navbar');

$user = new User();
$userklasse= $user->data()->username;


$target_path = 'images/stundenplaene/'.$userklasse.'/';
if (! file_exists($target_path))
{mkdir($target_path, 0755, true);}



?>
<div class="container">
    <div class="row" >
        <div class="col-lg-12 text-center">
        <?php
        $user = new User();
        $userklasse= $user->data()->username;
        
        $target_path = 'images/stundenplaene/'.$userklasse.'/';
        if (! file_exists($target_path))
        {
            mkdir($target_path, 0755, true);
            
        }
        
        $stundenplan = $target_path."stundenplan.jpg";
        if (file_exists($stundenplan)) {
            echo "
            <span class='zoom' id='ex2' style='width: 80%' >
            <img src='$stundenplan'  alt='Stundenplan'/>
            <br>
            <p class='lead'>Zieh am Stundenplan!</p>
            </span>";
        } else {
            echo'<br><br><p>Wir benötigen noch Ihren Stundenplan! <br>Stundenpläne an:<br><br> <strong>info@martinshare.com</strong> <br><br>Betreff: '.$userklasse.' Stundenplan <br><br> Vielen Dank! </p>';
        }
        
        
        ?>
        <!-- <p><a href='/stundenplantest.php'>Htmlstundenplan-Demo<a><p> -->
        </div>

    </div>
</div> 
 <?php include Config::get('includes/footer'); ?>
</body>
</html>
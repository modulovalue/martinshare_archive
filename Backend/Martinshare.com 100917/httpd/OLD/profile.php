<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
$pageTitle = 'Profil';
include Config::get('includes/header');
?>

<?php
echo '</head >';

$user = new User();
$data = $user->data();
$schule = DB::getInstance()->get('schulen', array('id', '=', $data->schule_id)); 
$schulename = $schule->first()->namelang;
$bg = '/images/schulenbanner/'.$schulename.'.jpg';
                           
/*echo '<body id="Profil" style="background: linear-gradient(
      rgba(0, 0, 0, 0.7), 
      rgba(0, 0, 0, 0.7)
    ),url(\''.$bg.'\');  
    height: auto;
    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-attachment: fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    ">';*/
echo '<body id="Profil">';

include Config::get('includes/navbar');

?>
    
    
<div class="container">
    <div class="row" >
        <div class="col-lg-12 text-center">

        <?php 
        $user = new User();
        $data = $user->data();
        $schule = DB::getInstance()->get('schulen', array('id', '=', $data->schule_id)); 
        
        ?>

        <h3> <?php echo escape($data->username); ?> </h3>
        <p> <a class="Ausloggen" href="/ausloggen.php">Ausloggen</a></p>
        <!--<p>Abschlussjahr: <?php echo escape($data->abschjahr);?></p>
        <p>Klasse: <?php echo escape($data->klasse);?></p>
        <br>
        -->
        <h3><?php echo $schule->first()->namelang;;?></h3>
        <p>Homepage: <a href="<?php echo escape($schule->first()->homepage);?>"><?php echo escape($schule->first()->homepage);?></a></p>
        
        
        <?php# require_once('include/chat/chat.inc.php'); ?>
        
        </div>
    </div>
</div>




 <?php include Config::get('includes/footer'); ?>
 
    
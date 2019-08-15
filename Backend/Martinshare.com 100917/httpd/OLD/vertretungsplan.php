<?php
$pageTitle = 'Vertretungsplan';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
?>

<meta name="viewport" content="width=device-width, initial-scale=0.5, maximum-scale=1, user-scalable=yes">
</head>
<?php

$user = new User();

$path = "./vertretungsplan/".$user->data()->schule_id."/";

//$path2 = "/vertretungsplan/".$user->data()->schule_id."/";

$scanned_directory = array_diff(scandir($path), array('..', '.'));

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
    $httpq = "https";
} else { 
    $httpq = "http";
}
echo '<body style="background-attachment:fixed;" id="Vertretungsplan" style="padding-bottom: 0px;">';
include Config::get('includes/navbar');

function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}

?>

        <?php 
        $i = 0;
        if(!is_dir_empty($path)) {
        
            foreach($scanned_directory as $plan) {
            $i++;
            
            //ERSETZEN ZUM API UPLOAD HINZUFÜGEN

            //$pathOfPlan = dirname(__FILE__).$path2.$plan;
            //$content = file_get_contents($pathOfPlan);
            //$content = preg_replace('#<meta http-equiv="refresh"(.*?)>#', '', $content);
            //file_put_contents($pathOfPlan, $content);
            //print('http://www.martinshare.com/vertretungsplan/1/'.$plan);
            
            print '
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center" >
                        <p>Seite: '.$i.'</p>
                        <iframe style=" background: #fff;" src="'.$httpq.'://www.martinshare.com/vertretungsplan/'.$user->data()->schule_id.'/'.$plan.'" width="80%" height="90%">
                          <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen.</p>
                        </iframe>
                    </div>
                </div>
            </div>
            ';
            }
        } else {
            
            print '
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center" >
                        <iframe style=" background: #fff;" src="'.$httpq.'://www.martinshare.com/vertretungsplan/noplan/keinvertretungsplan.html" width="80%" height="90%">
                          <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen.</p>
                        </iframe>
                    </div>
                </div>
            </div>
            ';
        }
           
        
        ?>
        
    <?php include Config::get('includes/footer'); ?>
</body>
</html>
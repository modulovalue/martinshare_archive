<?php require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php'; ?>
<!DOCTYPE html>
<html lang="de">

<?php $markasactive = 3; ?>

<head>
    
    <?php include'include/headinclude.php'; ?>
		
    <meta name="viewport" content="width=device-width, initial-scale=0.5, maximum-scale=1, user-scalable=yes">

    <title>Martinshare - Vertretungsplan</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<?php

    $user = new User();
    
    $path = "../vertretungsplan/".$user->data()->schule_id."/";
    
    //$path2 = "/vertretungsplan/".$user->data()->schule_id."/";
    
    $scanned_directory = array_diff(scandir($path), array('..', '.'));
    
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
        $httpq = "https";
    } else { 
        $httpq = "http";
    }
   
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
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include'include/nav.php'; ?>
		
        <div id="page-wrapper" style="width: auto;">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row text-center">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Vertretungsplan
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Übersicht</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-paper-plane"></i> Vertretungsplan
                            </li>
                        </ol>
                    </div>
                </div>
               
    
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
                                <div class="col-xs-12 text-center">
                                    <p>Seite: '.$i.'</p>
                                    <iframe class="vertretungsplaniframe" frameborder="0" style=" background: #fff;" src="'.$httpq.'://www.martinshare.com/vertretungsplan/'.$user->data()->schule_id.'/'.$plan.'" width="80%" height="500px;">
                                        <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen.</p>
                                    </iframe>
                                </div>
                        ';
                        }
                    } else {
                        
                        print '
                                <div class="col-xs-12 text-center" >
                                    <iframe frameborder="0" class="vertretungsplaniframe" style=" background: #fff;" src="'.$httpq.'://www.martinshare.com/vertretungsplan/noplan/keinvertretungsplan.html" width="80%" height="500px;">
                                      <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen.</p>
                                    </iframe>
                                </div>
                        </div>
                        ';
                    }
                       
                    
                ?>
                <br><br>

            </div>
            <!-- /.container-fluid -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

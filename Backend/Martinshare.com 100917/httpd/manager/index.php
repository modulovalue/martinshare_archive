<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 1; ?>
<?php 

$user = new User(); 
if($user->hasPermission("manager")) { 
?>

<!DOCTYPE html>
<html lang="de">
<head>
    
    <?php include'include/headinclude.php'; ?>
    
    <meta charset="utf-8">    
    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=10.0, minimum-scale=0.5, user-scalable=yes" />
    <title>Martinshare - Übersicht</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>


    <link href="include/toggle/toggle.css" rel="stylesheet">
    <script src="include/toggle/toggle.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    
    <script src="../../js/xdate.js"></script>
    <script src="../../js/cookie.js"></script>
    <script src="include/theme1.css"></script>
    
   
    <script>

     
    </script>

    
</head>
<body>
    <div id="wrapper">
        
        
        <!-- Navigation -->
        <?php include'include/nav.php'; ?>
        
        
        <div id="page-wrapper">
            <div class="row">
                
                
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Schulverwaltung
                            </h3>
                        </div>
                        <div class="panel-body">
                            <p>Bitte wählen Sie eine der Kategorien aus, um Martinshare zu verwalten.</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script type="text/javascript">
       
    </script>
    
    
</body>
</html>
<?php } else {
    Redirect::to("index.php");
} ?>



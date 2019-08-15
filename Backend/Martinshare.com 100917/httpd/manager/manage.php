<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 2; ?>
<?php $user = new User(); 
if($user->hasPermission("manager")) { ?>

<!DOCTYPE html>
<html lang="de">
<head>
    <?php include'include/headinclude.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martinshare - Kontenverwaltung</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>   
    
    <script type="text/javascript">
        
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
                            <h3 class="panel-title"> Kontenverwaltung</h3>
                        </div>
                        <div class="panel-body">
                            insert datatable
                                           
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>    
</body>
</html>

<?php } else {
    Redirect::to("index.php");
} ?>

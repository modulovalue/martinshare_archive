<?php require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php'; ?>
<!DOCTYPE html>
<html lang="de">

<?php $markasactive = 4; ?>

<head>
    <?php include'include/headinclude.php'; ?>
	
    <meta name="viewport" content="width=device-width, initial-scale=0.5, maximum-scale=1.5, user-scalable=yes">

    <title>Martinshare - Stundenplan</title>

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

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include'include/nav.php'; ?>
		
        <div id="page-wrapper">

            <div class="container-fluid" >

                <!-- Page Heading -->
                <div class="row text-center">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Stundenplan
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Übersicht</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-table"></i> Stundenplan
                            </li>
                        </ol>
                    </div>
               
                <!-- /.row -->
                    <div class="col-md-12">
                        <?php
                            $user = new User();
                            $userklasse= $user->data()->username;
                            
                            $target_path = '../images/stundenplaene/'.$userklasse.'/';
                            if (! file_exists($target_path))
                            {
                                mkdir($target_path, 0755, true);
                                
                            }
                            
                            $stundenplan = $target_path."stundenplan.jpg";
                            if (file_exists($stundenplan)) {
                                echo "
                                <img class='framein img-responsive center-block' style='width:85%;'  src='$stundenplan'  alt='Stundenplan' />
                                ";
                            } else {
                                echo'<br><br><p>Wir benötigen noch Ihren Stundenplan! <br>Stundenpläne an:<br><br> <strong>info@martinshare.com</strong> <br><br>Betreff: '.$userklasse.' Stundenplan <br><br> Vielen Dank! </p>';
                            }
                            
                            
                        ?>
                    </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    
    <script src="js/bootstrap.min.js"></script>


	
</body>

</html>

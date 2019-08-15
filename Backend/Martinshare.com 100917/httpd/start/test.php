<?php require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php'; 
require_once $_SERVER['DOCUMENT_ROOT']. '/frameworks/resizeimage/function.resize.php';
?>
<!DOCTYPE html>
<html lang="en">

<?php $markasactive = 41; ?>


<head>
    <?php include'include/headinclude.php'; ?>
	
    <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1.5, user-scalable=yes">

    <title>Martinshare - Stundenplan Upload</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="css/calendar.css" rel="stylesheet">

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
                            Stundenplan Upload (Beta)
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Übersicht</a>
                            </li>
                            <li>
                                <i class="fa fa-table"></i>  <a href="stundenplan.php">Stundenplan</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-upload"></i> Stundenplan Upload (Beta)
                            </li>
                        </ol>
                    </div>
               
               
                            <div class="col-md-6 text-center">  
        				        <div class="mvcalendercontent framein center-block">
            						<div class="mvctabs">
            							<div class="mvctab mvcdaytab mvctabstyle" data-day="0">
            							Heute
            							</div>
            							<div class="mvctab mvcdaytab mvctabactive mvctabstyle" data-day="86400">
            							Morgen
            							</div>
            							<div class="mvctab mvcdaytab mvctabstyle" data-day="172800">
            							Übermorgen
            							</div>
            							<div class="eintragsstift mvctabstyle"><b>Eintragen</b></div>
            							<div class="mvctabstyle mvcsearchshowbutton"> <i class="glyphicon glyphicon-search"></i></div>
            						</div>
            						<div class="mvccontent">
            							<span class="mvccontentbeschreibung mvcnocontent ">Einen Moment </span>
            							<div class="mvcloadingimg"></div> 
            						</div>
            					</div>
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



    <script>
        
        $( document ).ready(function() {
            
        var calcontent = "";
        var searching = false;
        var mvcsearchbar = '<span class="mvccontentdatum">\
                                <div class="form-group"> \
                                    <input type="text" class="form-control mvcsearchbar" placeholder="Suchen..."> \
                                </div> \
                                    \
                                <button type="submit" class="btn btn-default mvcsearch"><i class="glyphicon glyphicon-search"></i></button> \
                                <button type="submit" class="btn btn-default mvcsearchbuttonclose"><i class="glyphicon glyphicon-remove"></i></button> \
                                <br> \
                            </span> \
                            <div class="mvcsearchresults"> \
                            </div> \
                            ';
            
         
            
            $(document).on("click",'.mvcsearchshowbutton',function () {
            
                if(searching == false) {
                    
                    searching = true;
                    
                    calcontent = $( ".mvccontent" ).html();
                
                    $( ".mvccontent" ).empty();
                    
                    $(mvcsearchbar).hide().appendTo(".mvccontent" ).show('fast');
                }
                
            
            });
            
            $(document).on("click",'.mvcsearchbuttonclose',function () {
            
                $( ".mvccontent" ).empty();

                $(".mvccontent").append(calcontent);
                
                searching = false;
            
            });
        
            $(document).on("click",'.mvcsearch',function () {
            
                $( ".mvcsearchresults" ).empty();
                
                getsuchergebnisse($( ".mvcsearchbar").val());
            
            });
        
        });
        
        function getsuchergebnisse( suchstring ) {
            $.post( "include/getkalendersearchresults.php", { suchstring: suchstring })
                .done(function( data ) {
                    $('.mvcsearchresults').html(data);
                });
        }
        
    </script>
    
    
</body>

</html>

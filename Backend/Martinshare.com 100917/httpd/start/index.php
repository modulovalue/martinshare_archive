<?php $markasactive = 1; ?>
<?php require_once '../include/core.inc.php'; ?>
<!DOCTYPE html>
<html lang="de">


<head>
    <?php include'include/headinclude.php'; ?>
	
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Martinshare - Übersicht</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Morris Charts CSS 
    <link href="css/plugins/morris.css" rel="stylesheet">
-->
    <link href="css/calendar.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>


    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css">
    
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
	
    <script src="//cdn.jsdelivr.net/xdate/0.8/xdate.min.js"></script>
	
	
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
               
                
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row text-center">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Übersicht
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Übersicht
                            </li>
                        </ol>
                    </div>
                </div>
               
               <div class="row">
                    <div class="col-md-12 col-xs-12">
                 
                
                <?php /*      
                        <?php 
                        date_default_timezone_set('Europe/Berlin');
                        $eintraege = new EintragCRUD();
                        
                        $heuteh = $eintraege->getEintragQuery("h", "DESC", date('Y-m-d',strtotime("+0 day") ) )->count();
                        $heutea = $eintraege->getEintragQuery("a", "DESC", date('Y-m-d',strtotime("+0 day") ) )->count();
                        $heutes = $eintraege->getEintragQuery("s", "DESC", date('Y-m-d',strtotime("+0 day") ) )->count();
                        $heutezahl = $heutea + $heutes + $heuteh;
                        
                        $morgenh = $eintraege->getEintragQuery("h", "DESC", date('Y-m-d',strtotime("+1 day") ) )->count();
                        $morgena = $eintraege->getEintragQuery("a", "DESC", date('Y-m-d',strtotime("+1 day") ) )->count();
                        $morgens = $eintraege->getEintragQuery("s", "DESC", date('Y-m-d',strtotime("+1 day") ) )->count();
                        $morgenzahl = $morgena + $morgens + $morgenh;
                        
                        $plus2h = $eintraege->getEintragQuery("h", "DESC", date('Y-m-d',strtotime("+2 day") ) )->count();
                        $plus2a = $eintraege->getEintragQuery("a", "DESC", date('Y-m-d',strtotime("+2 day") ) )->count();
                        $plus2s = $eintraege->getEintragQuery("s", "DESC", date('Y-m-d',strtotime("+2 day") ) )->count();
                        $plus2zahl = $plus2a + $plus2s + $plus2h;
                        
                        $plus3h = $eintraege->getEintragQuery("h", "DESC", date('Y-m-d',strtotime("+3 day") ) )->count();
                        $plus3a = $eintraege->getEintragQuery("a", "DESC", date('Y-m-d',strtotime("+3 day") ) )->count();
                        $plus3s = $eintraege->getEintragQuery("s", "DESC", date('Y-m-d',strtotime("+3 day") ) )->count();
                        $plus3zahl = $plus3a + $plus3s + $plus3h;
                        
                        function ha($zahl) {
                            if($zahl > 1 ||  $zahl == 0 ) {
                                echo "$zahl <small>Hausaufgaben</small>";
                            } else {
                                echo "$zahl <small>Hausaufgabe</small>";
                            }
                        }
                        
                        function so($zahl) {
                             if($zahl > 1) {
                                echo "$zahl <small>Sonstiges</small>";
                            } else {
                                echo "$zahl <small>Sonstiges</small>";
                            }
                        }
                        
                        function ar($zahl) {
                             if($zahl > 1 ||  $zahl == 0 ) {
                                echo "$zahl <small>Arbeiten</small>";
                            } else {
                                echo "$zahl <small>Arbeit</small>";
                            }
                        }
                        
                        ?>
                        
                        
                        
                            <div class="col-md-3 ">
                                <div class="panel panel-green ">
                                    <div class="panel-heading ">
                                        <div class="row">
                                            
                                            <div class="col-xs-5" style="text-align:center;">
                                                <div>  Heute</div>
                                                <div class="huge"><?php echo $heutezahl ?></div>
                                            </div>
                                            <div class="">
                                                <div><?php echo ha($heuteh) ?></div>
                                                <div><?php echo ar($heutea) ?></div>
                                                <div><?php echo so($heutes) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                   <!-- <a href="#">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a> -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-5" style="text-align:center;" >
                                                <div>Morgen</div>
                                                <div class="huge"><?php echo $morgenzahl ?></div>
                                            </div>
                                            <div class="">
                                                <div><?php echo ha($morgenh) ?></div>
                                                <div><?php echo ar($morgena) ?></div>
                                                <div><?php echo so($morgens) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<a href="#">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>-->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-5 " style="text-align:center;">
                                                <div><small>+2 Tage</small></div>
                                                <div class="huge"><?php echo $plus2zahl ?></div>
                                            </div>
                                            <div class="">
                                                <div><?php echo ha($plus2h) ?></div>
                                                <div><?php echo ar($plus2a) ?></div>
                                                <div><?php echo so($plus2s) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<a href="#">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>-->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="panel panel-red">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-5 " style="text-align: center;">
                                                <div><small>+3 Tage</small></div>
                                                <div class="huge"><?php echo $plus3zahl ?></div>
                                            </div>
                                            <div class="">
                                                <div><?php echo ha($plus3h) ?></div>
                                                <div><?php echo ar($plus3a) ?></div>
                                                <div><?php echo so($plus3s) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                   <!-- <a href="#">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a> -->
                                </div>
                            </div>
                        
                */ ?>
                        
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
                            
                            
                            <div class="col-md-6 text-center">
                                <div class="mvcalender center-block framein">
            					</div>	
        					</div>
        					
                    </div>
                </div>
                
            </div>
			<br><br>
        </div>
        <!-- /#page-wrapper -->


        <div class="modaleintragen modal fade modalstunde" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
          
                <div class="modal-header text-center" style="color: white;" >
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Neuer Eintrag</h4>
                </div>
                
                <div class="modal-body">
                    <form role="form">
    	                <center>
    	                    <select name="db_select" size="3">
        	                	<option value="a">Arbeitstermin</option>
        	                	<option value="h" selected>Hausaufgabe</option>
        	                	<option value="s">Sonstiges</option>
    	                	</select>
    	                </center>
    	                
                        <br>
                        
	                	<center>
    	                	<div style="max-width: 220px;" class="input-group input-group-sm">
                                <span class="input-group-addon">Fach</span>
        	                	<input type="text" id="autocomplete" required="" name="name" class="form-control" placeholder="Fach" cols="20" rows="1" required>
    	                	</div>
    	                </center>
    	                
	                	<br>

                        <center>
    	                	<div style="max-width: 200px;" class="input-group">
	                	        <textarea name="beschreibung" id="beschreibung" placeholder="Beschreibung" cols="25" rows="5"></textarea>
                            </div>
    	                </center>
        
	                	<br>
	                	
                        <p class="text-center" style="color: white;" >Fällig am:</p>
                        
	                	<center>
	                	    <input type="date" class="eintragdatum  center-block" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder="yyyy-mm-dd"  name="datum" />
	                	</center>
	                	
	                	<br><br>
	                	
	                	<center>
	                	    
                            <div class="modal-footer">
                            </div>
                            
                            <div>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                                <button type="button" class="btn btn-primary neueneintragabsenden">Speichern</button>
                            </div>
                        </center>
                        
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modalupdate modal fade modalstunde" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
          <br>
                <div class="modal-header text-center" style="color: white;" >
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Eintrag aktualisieren</h4>
                </div>
                
                <div class="modal-body">
                    <form role="form">
	                
	                	<center>
    	                	<div style="max-width: 220px;" class="input-group input-group-sm">
                                <span class="input-group-addon">Fach</span>
        	                	<input type="text" id="autocomplete" required="" name="newname" class="form-control" placeholder="Fach" cols="20" rows="1" required>
    	                	</div>
    	                </center>
    	                
	                	<br>

                        <center>
    	                	<div style="max-width: 200px;" class="input-group">
	                	        <textarea name="newbeschreibung" id="beschreibung" placeholder="Beschreibung" cols="25" rows="5"></textarea>
                            </div>
    	                </center>
        
	                	<br>
	                	
                        <p class="text-center" style="color: white;" >Fällig am:</p>
                        
    	                <center>
    	                    <input type="date" class="neweintragdatum text-center" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder="yyyy-mm-dd"  name="newdatum" />
    	                </center>
	                    
	                    <br><br>
	                    
	                	<center>
                            <div class="modal-footer">
                            </div>
                            
                            <div>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                                <button type="button" class="btn btn-primary updateeintragabsenden">Speichern</button>
                            </div>
                        </center>
                        
                    </form>
                </div>
            </div>
        </div>
                


    </div>
    <!-- /#wrapper -->


    <script src="include/martinsballoons/martinsballoons.js"></script>
    
    <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
    <script>
    webshims.setOptions('forms-ext', {types: 'date'});
    webshims.polyfill('forms forms-ext');
    </script>
    
    <?php include 'calendar/calendarjs.html'; ?>
    
</body>

</html>

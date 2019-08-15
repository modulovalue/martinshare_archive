<?php
$pageTitle = 'Martinshare';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

if(Input::exists()) {
        $validate = new Validate();
        $validation = $validate-> check($_POST, array( 
                'Name/Email' => array(
                    'required' => true),
                'Nachricht' => array(
                    'required' => true)
            ));
        
        if($validation->passed()) {
            $email = escape(Input::get('Name/Email')) ;
              $message = escape(Input::get('Nachricht')) ;
              $user = new User();
              $userklasse= $user->data()->username;
            
              mail( "info@martinshare.com", "Feedback Klasse: $userklasse", $message, "From: $email" );
                
                Session::flash('feedback', 'Deine Nachricht wurde <strong>erfolgreich</strong> übermittelt. Vielen Dank!');
                Redirect::to('main.php');
        }
        
        else {
            foreach($validation->errors() as $error) {
                Session::flash('errorsenden', $error.'<br>');
            }
        }
}
include Config::get('includes/header');
?>

<?php

echo '</head>';
echo '<body id="Martinshare">';
include Config::get('includes/navbar');

?>

 <link href="../css/calendar.css" rel="stylesheet">
    <div class="container">
        <div class="row" >
            <div class="col-lg-12 text-center">
            
            <?php 
            if(Session::exists('errorsenden')) {
                   echo '<div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> '.Session::flash('errorsenden'),'</div>';
                }
            if(Session::exists('eingeloggt')) {
                   echo '<div class="alert alert-info" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> '.Session::flash('eingeloggt'),'</div>';
                }
            if(Session::exists('feedback')) {
                   echo '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> '.Session::flash('feedback'),'</div>';
                }

                
                ?>
                <img style="padding-top: 9px; width: 250px;" src="./images/logomartinshare.png">
                
               
                <br>
               
                <?php
                $user = new User();
                
                if(Session::exists('success')) {
                    echo Session::flash('success');
                }
               
                echo '<b> <p> <a href="profile.php">'.$user->data()->username.'</a> </p> </b>
                
                    <p style="margin-bottom: 30px;"></p>
                    
                    ';q

                ?>
                
                <br>
                
<!-----------------------------------------------------------------KALENDER-->
        <div class="mvccalcon">
            <div class="mvinneroutercontainer">
                <div class="mvccontentcontainer">
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
                        <div class="eintragsstift mvctab mvctabstyle"><b>Einreichen</b></div>
                    </div>
                    <div class="mvccontent">
                        <span class="mvccontentbeschreibung mvcnocontent ">Einen Moment </span>
                        <div class="mvcloadingimg"></div> 
                    </div>
                </div>
            
                <div class="mvcalender">
                </div>
            </div>
        </div>
        
        
        <div class="modaleintragen modal fade modalstunde" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
          
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Neuer Eintrag</h4>
                </div>
                <?php include 'include/showlastrecords.php'; ?>
                <div class="modal-body">
                    <form role="form">
	                	<select name="db_select" size="3">
    	                	<option value="arbeitstermine">Arbeitstermin</option>
    	                	<option value="hausaufgaben" selected>Hausaufgabe</option>
    	                	<option value="sonstiges">Sonstiges</option>
	                	</select>
                        <br><br>
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
                        <p>Fällig am:</p>
	                	<input type="date" class="eintragdatum" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder="yyyy-mm-dd"  name="datum" />
	                	<br><br>
	                	
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                            <button type="button" class="btn btn-primary neueneintragabsenden">Speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="modalupdate modal fade modalstunde" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
          
                <div class="modal-header">
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
                        <p>Fällig am:</p>
	                	<input type="date" class="neweintragdatum" required="" data-date='{"startView": 2, "openOnMouseFocus": true}' placeholder="yyyy-mm-dd"  name="newdatum" />
	                	<br><br>
	                	
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                            <button type="button" class="btn btn-primary updateeintragabsenden">Speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                
    <script type="text/javascript" src="include/autocomplete/jquery.autocomplete.js"></script>
    
	<script type="text/javascript" src="include/autocomplete/autocompletearray.js"></script>
    <script src="include/martinsballoons/martinsballoons.js"></script>
    <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
    <script>
    webshims.setOptions('forms-ext', {types: 'date'});
    webshims.polyfill('forms forms-ext');
    </script>
    
        <script>
        
        $( document ).ready(function() {
        
            jetztmonat = 0;
            getkalender();
        
            $(document).on("click",'.eintragsstift',function () {
                   $('.modaleintragen').modal('show');
                   var datum = $('.mvccontentdatum').data('sqldatum');
                   $('.eintragdatum').val(datum);
            });
            
            $(document).on("click",'.neueneintragabsenden',function () {
            var db =  $('select[name="db_select"]').val();
            var fach =  $('input[name="name"]').val();
            var beschreibung =  $('textarea[name="beschreibung"]').val();
            var datum =  $('input[name="datum"]').val();
             
            $.post( "include/eintragscripts/posteintrag.php", {
                
            datum: datum, 
            dbw: db, 
            fach: fach, 
            beschreibung: beschreibung })
            
                .done(function( data ) {
                getkalender();
                $('.modaleintragen').modal('hide');
                });
            });
           
            var eintragsid;
            var eintragtimestamp;
            
            $(document).on("click",'.mvccontentfach ',function () {
            eintragsid = $(this).parents().data('id');
                   $('.modalupdate').modal('show');
            var datum = $('.mvccontentdatum').data('sqldatum');
                   $('input[name="newdatum"]').val(datum);
            var newname = $(this).text();
                   $('input[name="newname"]').val(newname);
            var newbeschreibung = $(this).parents().children('.mvccontentbeschreibung').text();
                   console.log(newbeschreibung);
                   console.log(eintragsid);
                   $('textarea[name="newbeschreibung"]').val(newbeschreibung);
            });
            
            $(document).on("click",'.updateeintragabsenden',function () {
            var newfach =  $('input[name="newname"]').val();
            var newbeschreibung =  $('textarea[name="newbeschreibung"]').val();
            var thisid = eintragsid;
            var newdatum =  $('input[name="newdatum"]').val();
             
            $.post( "include/eintragscripts/updateeintrag.php", {
            eintragid: thisid, 
            newname: newfach, 
            newbeschreibung: newbeschreibung, 
            neweintragdatum: newdatum })
            
                .done(function( data ) {
                getkalender();
                $('.modalupdate').modal('hide');
                });
            });
            
        
    
            $(document).on("click",'.mvcday',function () {
                $( "span" ).removeClass( "focused" );
                $(this).addClass("focused");
               
                var datume = $(this).data('timestamp');
                geteintrag(datume);
                    
                    
            });
                
            $(document).on("click",'.backmonth',function () {
            jetztmonat--; 
            getkalender();
            });
            
            $(document).on("click",'.forwardmonth',function () {
            jetztmonat++;
            getkalender();
            });
            
        
            $(document).on("click",'.mvcdaytab',function () {
                $('.mvctabactive').removeClass("mvctabactive");
                $(this).addClass("mvctabactive");
                var timestamp = Math.round(new Date().getTime() / 1000);
                timestamp += $(this).data('day');
                
                $.post("include/getformatteddatefromtimestamp.php", {timestampp: timestamp})
                    .done(function( data ) {
                        geteintrag(data);
                        
                        $( "span" ).removeClass( "focused" );
                        $( "span[data-timestamp=" + data + "]").addClass("focused");
                        
                    });
            });
            
        });
        

            function geteintrag( datum ) {
                loadingscreen(true);
                $.post( "include/getkalendereintrag.php", {datum: datum })
                    .done(function( data ) {
                        loadingscreen(false);
                        $('.mvccontent').html(data);
                    });
            }
        
            function loadingscreen( active ) {
                if(active) {
                    $('.mvccontent').addClass("spinner");
                            $('.mvccontent').html('<span class="mvcnocontent" > Wird Geladen</span> <div class="mvcloadingimg"></div>');
                } else {
                    $('.mvccontent').removeClass( "spinner" );
                }
            }
            
            function getkalender(datum) {
            if(!datum) {
                datum = '<?php echo date('Y-m-d',strtotime('today'));?>';
            } 
                stop();
                $.post( "include/getkalender.php", { zeit: datum, monat: jetztmonat})
                .done(function( data ) {
                    $('.mvcalender').html(data);
                    
                    var datume = '<?php echo date('Y-m-d',strtotime('+1 day'));?>';
                    
                    $( "span" ).removeClass( "focused" );
                    
                    $( "span[data-timestamp=" + datume + "]").addClass("focused");
                    
                    geteintrag(datume);
                    
                });
            }
            

        </script>
<!--KALENDER ENDE------------------------------------------------------->
               

                <br>
                <h3><a href="javascript:toggle('feedback')">Kontakt</a></h3>
                <div id="feedback" style="display: none">';
                <br>
                    <form method="post">
                        <p>Name/Email: </p><input name="Name/Email" type="text" placeholder="Name/Email" /><br><p></p>
                        <p>Nachricht:</p>
                        <textarea name="Nachricht" rows="15" cols="30" placeholder="Deine Nachricht" ></textarea>
                        <br><br>
                        <input type="submit" value="Absenden" />
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    
    
    <?php include Config::get('includes/footer'); ?>

    <!--=========  Analytics  =========
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-50057105-3', 'auto');
    ga('send', 'pageview');
    </script>
    -->
    </body>
</html>
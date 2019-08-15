<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 4; ?>
<?php $user = new User(); if($user->data()->group == 2) { ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include'include/headinclude.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martinshare - Schule Registrieren</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>   
    <script type="text/javascript">
        
        function setVertKey(){
            $.get( "https://www.martinshare.com/api/vertretungsplan.php/randomkey/", function( data ) {
                $('#plankeyfield').val(data);
            }); 
        }
        
        $(function () {
            
            setVertKey();
            
            $( "#postschoolregistration" ).submit(function( event ) {
                event.preventDefault();

                $.post( "https://www.martinshare.com/api/adminapi.php/registerschool/", {
                    namelang:           $('#namelang').val(), 
                    namekurz:           $('#nameshort').val(), 
                    homepage:           $('#schulhomepage').val(), 
                    vertretungsplankey: $('#plankeyfield').val() })
                
                    .done(function() {
                        alert( "second success" );
                    })
                    .fail(function() {
                        alert( "error" );
                    })
                    .error(function() {
                        alert( "erroreeeeeerre" );
                    })
                    .always(function() {
                        alert( "finished" );
                    });
                
            });
        });    
        
    </script>
        
    </script>
</head>
<body>
    <div id="wrapper">
    
        <!-- Navigation -->
        <?php include'include/nav.php'; ?>
        
        <div id="page-wrapper">

            <div class="row">

                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Schule Registrieren</h3>
                        </div>
                        <div class="panel-body">
                            
                            
                             <form id="postschoolregistration" method="post">
                                 
                             <div class="form-group ">
                              <label class="control-label requiredField" for="namelang">
                               Vollst&auml;ndiger Name der Schule
                               <span class="asteriskField">
                                *
                               </span>
                              </label>
                              <input class="form-control" id="namelang" name="namelang" type="text" required/>
                              <span class="help-block" id="hint_namelang">
                               z.B. Josef-Durler-Schule Rastatt
                              </span>
                             </div>
                             
                             <div class="form-group ">
                              <label class="control-label requiredField" for="nameshort">
                               Kurzer Name der Schule (max 5 Zeichen)
                               <span class="asteriskField">
                                *
                               </span>
                              </label>
                              <input class="form-control" id="nameshort" name="nameshort" placeholder="XXXXX" type="text" required/>
                              <span class="help-block" id="hint_nameshort">
                               z.B. JDSR
                              </span>
                             </div>
                             
                             <div class="form-group ">
                              <label class="control-label requiredField" for="schoolhomepage">
                               Homepage der Schule
                               <span class="asteriskField">
                                *
                               </span>
                              </label>
                              <input class="form-control" id="schulhomepage" name="schoolhomepage" placeholder="www.google.de" type="website" required/>
                              <span class="help-block" id="hint_schoolhomepage">
                               z.B. https://www.schule.de/
                              </span>
                             </div>
                             
                             <div class="form-group ">
                              <label class="control-label " for="plankey">
                               Vertretungsplan Schlüssel
                              </label>
                              <div class="input-group">
                                  <span class="input-group-btn">
                                    <button class="btn btn-primary " type="button" onclick="setVertKey();">Anfordern</button>
                                  </span>
                                  <input class="form-control " id="plankeyfield" name="plankeyfield" placeholder="xxxx" type="text" readonly>
                                </div><!-- /input-group -->
                              <span class="help-block" id="hint_plankey">
                               Zur Authentifizierung in <i>Martinshare PS</i> (Vertretungsplan Software)
                              </span>
                             </div>
                             <div class="form-group">
                              <div>
                               <button class="btn btn-primary " name="submit" type="submit">
                                Registrieren
                               </button>
                              </div>
                             </div>
                            </form>
                            
                                           
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

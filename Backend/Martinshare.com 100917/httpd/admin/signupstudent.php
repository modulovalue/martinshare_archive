<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 2; ?>
<?php $user = new User(); if($user->data()->group == 2) { ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include'include/headinclude.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martinshare - Schüler Registrieren</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>   
    <script type="text/javascript">
        
        $(function () {
            
            //
            //'username' => array(
            //    'required' => true,
            //    'min' => 2,
            //    'max' => 20,
            //    'unique' => 'users'
            //),
            //'schule_id' => array(
            //    'required' => true
            //),
            //'password' => array(
            //    'required' => true,
            //    'min' => 6
            //),
            //'password_again' => array(
            //   'required' => true,
            //   'matches' => 'password'
               
            var schools;
               
            $.post("https://www.martinshare.com/api/adminapi.php/getschools/", function(json) {
                $("#schule_id").html("");
                $("#schule_id").html("<option selected disabled hidden value=''> -- Bitte auswählen -- </option>");
                schools = json;
                $.each(json, function(key,value) {
                    $("#schule_id").append('<option value="'+ value["id"] +'">'+ value["namelang"] +'</option>');
                }); 
            }, 'json');

            $( "#schule_id" ).change(function() {
                $("#usernameschoolpart").text( schools[$( "#schule_id" )[0].selectedIndex-1]["namekurz"] + "-");
            });


            $( "#poststudentregistration" ).submit(function( event ) {
                event.preventDefault();

                $.post( "https://www.martinshare.com/api/adminapi.php/registeruser/", {
                    username:            $('#username').val(), 
                    schule_id:           $('#schule_id').val(), 
                    password:            $('#password').val(), 
                    password_again:      $('#password_again').val() })
                
                    .done(function(data) {
                        alert("Nutzer wurde erstellt");
                        document.getElementById("poststudentregistration").reset();
                    })
                    .fail(function(data) {
                        alert(JSON.stringify(data["responseText"]));
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
                            <h3 class="panel-title"> Klasse Registrieren</h3>
                        </div>
                        <div class="panel-body">

                            <form action="" id="poststudentregistration" method="post">
                               
                                    <label class="control-label" for="username">Benutzername</label>
                                <div class="input-group ">
                                    
                                    <span class="input-group-addon" id="usernameschoolpart"></span>
                                    <input class="form-control" type="text" name="username" id="username" required autocomplete="off" >
                                </div>
                                
                                <div class="form-group ">
                                    <label class="control-label" for="schule_id">Schule</label>
                                    <select class="form-control" name="schule_id" id="schule_id" required>
                                    
                                    
                                    </select>
                                    
                                </div>

                                <div class="form-group ">
                                    <label class="control-label" for="password">Password des Benutzers</label>
                                    <input class="form-control"  type="password" name="password" id="password" required autocomplete="off" >
                                </div>
                                
                                <div class="form-group ">
                                    <label class="control-label" for="password_again">Password bitte nochmal eingeben</label>
                                    <input class="form-control"  type="password" name="password_again" id="password_again" required autocomplete="off" >
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

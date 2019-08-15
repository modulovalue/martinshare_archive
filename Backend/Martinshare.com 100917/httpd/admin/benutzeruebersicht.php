<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 5; ?>
<?php $user = new User(); if($user->data()->group == 2) { ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include'include/headinclude.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martinshare - Benutzerübersicht</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />

    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>   
    <script type="text/javascript">
        
       // function setVertKey(){
       //     $.get( "https://www.martinshare.com/api/vertretungsplan.php/randomkey/", function( data ) {
       //         $('#plankeyfield').val(data);
       //     }); 
       // }
       // 
       // $(function () {
       //     
       //     setVertKey();
       //     
       //     $( "#postschoolregistration" ).submit(function( event ) {
       //         event.preventDefault();
//
       //         $.post( "https://www.martinshare.com/api/adminapi.php/registerschool/", {
       //             namelang:           $('#namelang').val(), 
       //             namekurz:           $('#nameshort').val(), 
       //             homepage:           $('#schulhomepage').val(), 
       //             vertretungsplankey: $('#plankeyfield').val() })
       //         
       //             .done(function() {
       //                 alert( "second success" );
       //             })
       //             .fail(function() {
       //                 alert( "error" );
       //             })
       //             .error(function() {
       //                 alert( "erroreeeeeerre" );
       //             })
       //             .always(function() {
       //                 alert( "finished" );
       //             });
       //         
       //     });
       // });    
        
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
                            <h3 class="panel-title"> Benutzerübersicht</h3>
                        </div>
                        <div class="panel-body">
                         
                                           
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

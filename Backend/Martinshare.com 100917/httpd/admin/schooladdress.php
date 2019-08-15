<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 8; ?>
<?php $user = new User(); if($user->data()->group == 2) { ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include'include/headinclude.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martinshare Vertretungsplan - Overview</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />
    
    <!-- datatable css 
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
    -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
    
    <!-- validation -->
    <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

    <!--    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script> -->
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>   
    
    <script>
        $( document ).ready(function() {
            
            
            $('#example').on( 'click', 'tr', function () {
                if( !$(this).hasClass('head') ) {
                    if ($(this).hasClass('selected') ) {
                        
                    } else {
                        table.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                }
            } );
         
            var table = $('#example').dataTable({ 
                "iDisplayLength": 150,
                "bProcessing": true,
                "sAjaxSource": "//martinshare.com/api/datatableapi.php/datatableschools/",
                "sPaginationType" : "full_numbers",
                "aoColumns": [
                    { mData: 'id', 
                        "sClass": "schoolid",
                        "sWidth": "3%" },
                    { mData: 'name', 
                        "sClass": "schoolname",
                        "sWidth": "25%"  },
                    { mData: 'anschrift' },
                    { mData: 'plz',
                        "sWidth": "10%" },
                    { mData: 'ort',
                        "sWidth": "10%" }, 
                    { mData: null,
                        "bSortable": false,
                        "mRender": function(data, type, full) {
                        return '<a data-toggle="modal" data-target="#myModal" class="btn btn-info btn-sm" href="#">' + "Edit"+ '</a>';
                      }}
                ]
            });   
            
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').focus();
            });
            
            $("#adresseabsenden").click( function() {
                    
                if($("#anschrift").val() != '' && $("#plz").val() != '' && $("#ort").val() != '') {
                    var jqxhr = $.post( "https://martinshare.com/api/datatableapi.php/editaddress/", { 
                         
                        schoolid: table.$('tr.selected').find(".schoolid").html(), 
                        schoolanschrift: $("#anschrift").val(), 
                        schoolplz: $("#plz").val(), 
                        schoolort: $("#ort").val()
                         
                     }, function() {
                           //alert( "success" );
                        })
                          .done(function() {
                          // alert( "second success" );
                            window.location.reload();
                        })
                          .fail(function() {
                          // alert( "error" );
                        })
                          .always(function() {
                            //alert( "finished" );
                        });
                } else {
                    //alert("überprüfe die daten");
                }
               
            });
            
        });
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
                        <div class="panel-body">
                            <br>
                                <table id="example" class="table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="head">
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Anschrift</th>
                                            <th>PLZ</th>
                                            <th>Ort</th>
                                            <th>Bearbeiten</th>
                                        </tr>
                                    </thead>
                                </table>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                  </div>
                  <div class="modal-body">
                    
                    <form id="neueschule" name="newschool" class="form-horizontal">
                        <fieldset>
                        
                        <!-- Form Name -->
                        <legend>Adresse</legend>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="name">Anschrift( Straße + Hausnummer</label>  
                          <div class="col-md-4">
                          <input id="anschrift" name="anschrift" placeholder="Straße + Hausnummer" class="form-control input-md" type="text">
                            
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="homepage">PLZ</label>  
                          <div class="col-md-4">
                          <input id="plz" name="plz" placeholder="PLZ" class="form-control input-md" type="text">
                            
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="email">Ort</label>  
                          <div class="col-md-4">
                          <input id="ort" name="ort" placeholder="Ort" class="form-control input-md" type="text">
                            
                          </div>
                        </div>
                        
                        
                        </fieldset>
                        </form>
                        
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="adresseabsenden" class="btn btn-primary">Absenden</button>
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

<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 6; ?>
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
                        //window.open("https://www.martinshare.com/admin/editplans.php?schoolid=" + table.$('tr.selected').find(".schoolid").html() + "&schoolname=" + table.$('tr.selected').find(".schoolname").html(),'_blank');
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
                        "sWidth": "3%" } ,
                    { mData: 'name', 
                        "sClass": "schoolname",
                        "sWidth": "25%"  },
                    { mData: 'homepage',
                        "bSortable": false,
                        "mRender": function(data, type, full) {
                            return '<a class="btn btn-info btn-sm" target="_blank" href='+data+'>' + data + '</a>';
                        }},
                    { mData: 'email',
                        "sWidth": "25%" },
                    { mData: 'plancount',
                        "sWidth": "10%",
                        "mRender": function(data, type, full) {
                        return full["plancount"] +  
                        ' <a class="btn btn-info btn-sm" target="_blank" href="https://www.martinshare.com/admin/editplans.php?schoolid=' + full[0] + '&schoolname=' + full[3] + '">Edit</a>';
                      }}, 
                    { mData: null,
                        "bSortable": false,
                        "mRender": function(data, type, full) {
                        return data + ', <a class="btn btn-info btn-sm" href="#">' + "Edit TODO"+ '</a>';
                      }}
                ]
            });   
            
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').focus();
            });
            
            $("#schuleabsenden").click( function() {
                
                if($("#nameform").val() != '' && $("#emailform").val() != '' && $("#homepageform").val() != '') {
                    
                     var jqxhr = $.post( "https://martinshare.com/api/datatableapi.php/newschool/", { 
                         
                        name: $("#nameform").val(), 
                        email: $("#emailform").val(), 
                        homepage: $("#homepageform").val()
                         
                     }, function() {
                           //alert( "success" );
                        })
                          .done(function() {
                           // alert( "second success" );
                            window.location.reload();
                        })
                          .fail(function() {
                           alert( "error" );
                        })
                          .always(function() {
                            //alert( "finished" );
                        });
                        
                } else {
                    alert("überprüfe die daten");
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
                            <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Neue Schule</button>
                            <br>
                                <table id="example" class="table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="head">
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Homepage</th>
                                            <th>E-Mail</th>
                                            <th>Plancount</th>
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
                        <legend>Neue Schule</legend>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="name">Schulname</label>  
                          <div class="col-md-4">
                          <input id="nameform" name="nameform" placeholder="Name" class="form-control input-md" type="text">
                            
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="homepage">E-Mail</label>  
                          <div class="col-md-4">
                          <input id="emailform" name="emailform" placeholder="E-Mail" class="form-control input-md" type="text">
                            
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="email">Homepage</label>  
                          <div class="col-md-4">
                          <input id="homepageform" name="homepageform" placeholder="Homepage" class="form-control input-md" type="text">
                            
                          </div>
                        </div>
                        
                        
                        </fieldset>
                        </form>
                        
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="schuleabsenden" class="btn btn-primary">Absenden</button>
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

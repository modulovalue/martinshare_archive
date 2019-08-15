<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 7; ?>
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
            
            <?php
                $schoolid = $_GET["schoolid"];
                $schoolname = $_GET["schoolname"];
                echo " var schoolid = '". $schoolid ."';";
                echo " var schoolname = '". $schoolname ."';";
                if($schoolname != '' && $schoolid != '') {
                    echo "$('.schoolinfo').html('"."ID: ".$schoolid.", Schulname: ".$schoolname."');";
                } else {
                    echo "$('.schoolinfo').html('Bitte wähle eine Schule');";
                }
            ?>
            
            $('#example').on( 'click', 'tr', function () {
                if( !$(this).hasClass('head') ) {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                }
            } );
         
            var table = $('#example').dataTable({
                "bProcessing": true,
                "sAjaxSource": "//martinshare.com/api/datatableapi.php/datatableplans/" + schoolid + "/",
                "sPaginationType" : "full_numbers",
                
                "aoColumns": [
                    { mData: 'id',
                      "sClass": "planid",
                      "sWidth": "10%"  } ,
                    { mData: 'name', 
                      "sClass": "planname",
                      "sWidth": "25%"  },
                    { mData: 'type',
                      "sWidth": "10%"   },
                    {  mData: 'link',
                      "bSortable": false,
                      "mRender": function(data, type, full) {
                        return '<a class="btn btn-info btn-sm" href="'+data+'">' + data + '</a>';
                      }}, 
                    {  mData: null,
                      "bSortable": false,
                      "mRender": function(data, type, full) {
                        return '<a class="btn btn-info btn-sm" href="data">' + 'TODO' + '</a>';
                      }}
                ]
            });   
            
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').focus();
            });
            

            $("#planabsenden").click( function() {
                
                if( $("#schoolid").val() != '' && 
                    $("#planname").val() != '' && 
                    $("#plantype").val() != '' && 
                    $("#planlink").val() != '') {
                    
                     var jqxhr = $.post( "https://martinshare.com/api/datatableapi.php/newplan/", { 
                        schoolid: schoolid, 
                        planname: $("#planname").val(), 
                        plantype: $("#plantype").val(), 
                        planlink: $("#planlink").val()
                     }, function() {
                           //alert( "success" );
                        })
                          .done(function() {
                            //alert( "second success" );
                            window.location.reload();
                        })
                          .fail(function() {
                            alert( "error" );
                        })
                          .always(function() {
                            //alert( "finished" );
                        });
                        
                } else {
                    alert("Überprüfe die Daten");
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
                            
                            <h3 class="schoolinfo"></h3> 
                            <br>
                            
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                              Neuer Plan
                            </button>
                            <br>
                            <table id="example" class="table" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="head">
                                        <th>id</th>
                                        <th>name</th>
                                        <th>type</th>
                                        <th>link</th>
                                        <th>mobileview</th>
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
                        <legend>Neuer Plan</legend>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="name">Schule</label>  
                          <div class="col-md-4">
                          <input name="name" value=<?php echo '"'.$_GET["schoolname"].'"'; ?> class="form-control input-md" type="text" readonly="readonly">
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="name">Planname</label>  
                          <div class="col-md-4">
                          <input id="planname" name="name" placeholder="Planname" class="form-control input-md" type="text">
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="homepage">Plantyp</label>  
                          <div class="col-md-4">
                          <input id="plantype" name="homepage" placeholder="Plantyp" class="form-control input-md" type="text">
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="email">Planlink</label>  
                          <div class="col-md-4">
                          <input id="planlink" name="email" placeholder="Planlink" class="form-control input-md" type="text">
                          </div>
                        </div>
                        
                        
                        </fieldset>
                        </form>
                        
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="planabsenden" class="btn btn-primary">Absenden</button>
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

<?php require_once '../include/core.inc.php'; ?>
<?php $markasactive = 3; ?>
<?php $user = new User(); if($user->data()->group == 2) { ?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <?php include'include/headinclude.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martinshare - Admin Feedback</title>

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="css/local.css" />
    
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>   
    <script src="//cdn.jsdelivr.net/xdate/0.8/xdate.min.js"></script>
    <script type="text/javascript">
        
        
        function reloadMessages() {
            
            var whichToShow = "notdeleted";
            var orderToShow = "asc";
            
            $.get("https://www.martinshare.com/api/adminapi.php/getmobilemessages/"+ orderToShow +"/"+whichToShow, function(json) {
                
                $("#messagescontainer").html("");
                if(json == "" ) {
                    $("#messagescontainer").append(
                       
                        '<div class="row mobilemessage"> \
                            <div class="col-xs-12"> \
                                <h3>Alle Nachrichten gelöscht</h3> \
                            </div> \
                        </div> \
                        <hr/>'
                        
                    );
                } else {
                    
                    var device, markCSS, trashCSS;
                    
                    $.each(json, function(i, el) {
                        
                        markCSS = "";
                        if(el.isread == 0) {
                            var markCSS = "background-color: rgb(1, 53, 20);";
                        } else if(el.isread == 1) {
                            var markCSS = "";
                        }
                        
                        trashCSS = "";
                        if(el.deleted == 1) {
                            trashCSS = ' <i class="fa fa-trash"></i>';
                        } else if(el.isread == 1) {
                            trashCSS = '';
                        }
                        
                        device = "";
                        if(el.device == "ios") {
                            device = ' <i class="fa fa-apple"></i>';
                        } else if(el.device == "android") {
                            device = ' <i class="fa fa-android"></i>';
                        } else {
                            device = el.device;
                        }
                        
                        $("#messagescontainer").append(
                       
                        '<div class="row mobilemessage"> \
                            <div style=" ' + markCSS + '" class="col-xs-12"> \
                                <h3>'+ device +'   '+ el.username +'  '+ trashCSS + ' </h3> \
                                <p> '+ el.message +' \
                                </p> \
                                <div class=""> \
                                    <a href="#" data-id="'+ el.id +'" data-deleted="'+ el.deleted +'" id="deletemessagelink"><i class="fa fa-trash"></i> Löschen </a>  \
                                    <a href="#" data-id="'+ el.id +'" data-isread="'+ el.isread +'" id="readmessagelink"> <i class="fa fa-book"></i> Gelesen</a> \
                                </div> <p></p> \
                                <small><i><span>Erstellt: '+ new XDate(el.created).toString('dd, MMMM yyyy HH:mm:ss ') +' \
                                </span></i> </small>\
                                <p></p>\
                            </div> \
                        </div> \
                        <hr/>'
                        );
                        
                    });
                    
                }
            }, "json");
        }
        
        
        $(document).ready(function(){

            reloadMessages();
               
            $(document).on('click',  "#deletemessagelink" , function(event) {
                event.preventDefault();
                
                var del = "";
                
                if($(this).data("deleted") == "1") {
                    del = "0";
                } else {
                    del = "1";
                }
                
                var idd = $(this).data("id");
                
                $.post("https://www.martinshare.com/api/adminapi.php/dodelete/", { id: idd, todelete: del })
                .done(function( data ) {
                    reloadMessages();
                });
            }); 
            
            $(document).on('click',  "#readmessagelink" , function(event) {
                event.preventDefault();
                
                var rea = "";
                
                if($(this).data("isread") == "1") {
                    rea = "0";
                } else {
                    rea = "1";
                }
                
                var idd = $(this).data("id");
                
                $.post("https://www.martinshare.com/api/adminapi.php/doread/", { id: idd, isread: rea })
                .done(function( data ) {
                    reloadMessages();
                });
                
            }); 
            
        });
        
        
    </script>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php include'include/nav.php'; ?>
        
       <div id="page-wrapper" class="container">
            <div class="row ">
                <div id="messagescontainer" class="col-sm-12">
                    
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<?php } else {
    Redirect::to("index.php");
} ?>
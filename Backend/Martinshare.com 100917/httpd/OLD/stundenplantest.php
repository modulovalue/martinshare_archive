<?php
$pageTitle = 'Arbeitstermine';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
?>

    <link href="include/bootstrapnotify/bootstrap-notify.css" rel="stylesheet">
    

</head>
<?php
echo '<body id="Arbeitstermine">';
include Config::get('includes/navbar');
$user = new User();
$userklasse= $user->data()->username;

?>


<div class="modal fade modalstunde" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
  
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Stundenplanänderung</h4>
    </div>
    
    <div class="modal-body">
    <p class="altestunde"></p>
        <form role="form">
          <div class="form-group">
            <label for="message-text" class="label label-default">Neue Stunde:</label>
            <textarea class="form-control" id="neuestundetext"></textarea>
          </div>
        </form>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-primary neuestundeabsenden">Ändern</button>
    </div>
    
  </div>
</div>


        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 text-center">
                <div class='notification '></div>
             <h1>Stundenplan</h1>
                <br>
                    <div class="mvtimetablecontainer">
                        <table class="mvtimetable" border="1">
                                
                          <thead>
                            <tr>
                              <th class="mvttheader mvttheaderempty" colspan="8"></th>
                              <th class="mvttheader mvttheader1" colspan="8" >Mo</th>
                              <th class="mvttheader mvttheader2" colspan="8" >Di</th>
                              <th class="mvttheader mvttheader3" colspan="8" >Mi</th>
                              <th class="mvttheader mvttheader4" colspan="8" >Do</th>
                              <th class="mvttheader mvttheader5" colspan="8" >Fr</th>
                              
                            </tr>
                          </thead>
                          
                          <tbody class="mvttcontent" style="text-align: center;">

                          </tbody>
                            
                        </table>
                        <br>
                            <div class="mvtteditfootercontrue" style="display: none;">
                                <a href="#" style="" class="mvttaddrow">Zeile hinzufügen</a>
                                    <p style="float; left; display: inline;">,&nbsp;</p>
                                <a href="#" style="" class="mvttremoverow">Zeile entfernen</a>
                                    <p style="float; left; display: inline;">&nbsp; oder&nbsp;</p>
                                <a href="#" style="" class="mvtteditsavetext">Speichern</a>
                            </div>
                            
                            <div class="mvtteditfooterconfalse" style="display: inline;">
                                <a href="#" style="" class="mvtteditmaketext">Bearbeiten</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include Config::get('includes/footer'); ?>

        
        <script src="include/bootstrapnotify/bootstrap-notify.js"></script>
        <script>
      
       

$( document ).ready(function() {

        var stundencounter = 0;
        
         var bearbeiten = false;
        
        getStundenplan();
    
        $(document).on("click",'.mvttplus',function () {
        
           
                var colspan =  $(this).closest('td').attr('colspan');
                if(colspan > 5) {
                    colspan--;
                    console.log(colspan);
                    $(this).closest('td').attr('colspan',colspan);
                    
                    var tag = $(this).closest('td').children().data("tag");
                    

                    $(this).closest('td').after('<td class="mvttlessoncell mvttday'+tag+' mvttnoparent mvdaycell" colspan="1" >\
                                    <span class=" mvttday'+tag+' mvttmaincell">Neue Stunde</span>\
                                    <img src="images/minus.png" class="mvttimgmini mvttminus">\
                                </td>');
                }
        });
        
        $(document).on("click",'.mvttminus',function () {
            colspan = $(this).closest('td').prevAll(".mvttparent").attr('colspan');
            colspan++;
            $(this).closest('td').prevAll(".mvttparent:first").attr('colspan',colspan);
            $(this).closest('td').remove();
            return false; 
            e.preventDefault();
        });
        
        var stundeid;
        var wochentag;
        var stunde;
        var beginn;
        var ende;
        var altestunde;
        var neuestunde;
        
        $(document).on("click",'.mvttmaincell',function () {
          if(bearbeiten) {
            
            stundeid = $(this).data('id');
            wochentag = $(this).data('tag');
            stunde = $(this).data('stunde');
            beginn = $(this).parents().parents().children('td:first').find('.mvttbegin').text();
            ende = $(this).parents().parents().children('td:first').find('.mvttend').text();
            altestunde = $(this).text();
            $('.altestunde').text(altestunde);
            $('.modal').modal('show');
          }
        });
        
        $(document).on("click",'.neuestundeabsenden',function () {
        //if(stundeid === "") {
        
        neuestunde = $('#neuestundetext').val();
        console.log(neuestunde);
            $.post( "include/stundenplan/postnewhour.php", {stunde: stunde, fach: neuestunde, beginn: beginn, ende: ende, wochentag: wochentag })
                .done(function( data ) {
                
                     $('.notification').notify({
                    message: { text: neuestunde+'wurde erfolgreich eingetragen!' },
                    type: "info"
                    }).show();
                });
                
            getStundenplan();
            removebuttons();
            appendbuttons();
            $('.modal').modal('hide');
            
       // } else {
         
           
                    
            //$('.modal').modal('hide');
        //}
        });
        
        $(document).on("click",'.mvttaddrow',function () {
            
            if($('.mvttrow').length < 7) {
            stundencounterzaehlen();
                $('.mvttcontent').append(getnewrow());
                removebuttons();
                appendbuttons();
                $('.notification').notify({
                message: { text: 'Eine neue Zeile wurde hinzugefügt!' },
                type: "info"
                }).show();
                
                //var addedrow = $('.mvttcontent').find('.mvttrow:last');
                
               /* $(addedrow).find('.mvttmaincell').each( function() {
                    var stunde = $(this).data( "stunde");
                    var inhalt = $(this).text();
                    var tag = $(this).data( "tag");
                    console.log(inhalt);
                    $.post( "include/stundenplan/addcell.php", {stunde: stunde, inhalt: inhalt, tag: tag })
                    .done(function( data ) {
                        
                    });
                    
                }); */
            
            } else {
                 $('.notification').notify({
                message: { text: 'Maximale Anzahl von Zeilen wurde erreicht!' },
                type: "warning"
                }).show();
            }
            
            return false; 
            e.preventDefault();
        });
        
        $(document).on("click",'.mvttremoverow',function () {
            if (confirm('Bist du sicher, dass du die letzte Zeile löschen willst?')) {   
                $('.mvttcontent').find('.mvttrow:last').remove();   
            } else {
            
            }       
            return false; 
            e.preventDefault();
        });
    
    
        $(document).on("click",'.mvtteditmaketext',function () {
        
           appendbuttons();
        
            bearbeiten = true;
            $('.mvtteditfooterconfalse').hide();
            $('.mvtteditfootercontrue').show();
            return false; 
            e.preventDefault();
        });
        
        $(document).on("click",'.mvtteditsavetext',function () {
        
           removebuttons();
            
            bearbeiten = false
            $('.mvtteditfooterconfalse').show();
            $('.mvtteditfootercontrue').hide();
           //console.log($(".mvttrow").length);
            return false; 
            e.preventDefault();
        });
 
});

        function getnewrow() {
            return '<tr class="mvttrow mvttrow'+stundencounter+'">\
                                    <td class="mvttlessoncell mvttlessonhours mvttdayhours" colspan="8" >\
                                        <div>\
                                            <span class="mvttbegin">08:88</span><br>\
                                            <span>bis</span><br>\
                                            <span class="mvttend">08:88</span>\
                                        </div>\
                                    </td>\
                                    <td class="mvttlessoncell mvttparent mvttday1 mvdaycell" colspan="8" >\
                                        <span data-tag="1" data-stunde="'+stundencounter+'" data-id="" class=" mvttday1 mvttmaincell">Neu</span>\
                                    </td>\
                                    <td class="mvttlessoncell mvttparent mvttday2 mvdaycell" colspan="8" >\
                                        <span data-tag="2" data-stunde="'+stundencounter+'" data-id="" class=" mvttday2 mvttmaincell">Neu</span>\
                                    </td>\
                                    \
                                    <td class="mvttlessoncell mvttparent mvttday3 mvdaycell" colspan="8" >\
                                        <span data-tag="3" data-stunde="'+stundencounter+'" data-id="" class=" mvttday3 mvttmaincell">Neu</span>\
                                    </td>\
                                    \
                                    <td class="mvttlessoncell mvttparent mvttday4 mvdaycell" colspan="8" >\
                                        <span data-tag="4" data-stunde="'+stundencounter+'" data-id="" class=" mvttday4 mvttmaincell">Neu</span>\
                                    </td>\
                                    \
                                    <td class="mvttlessoncell mvttparent mvttday5 mvdaycell" colspan="8" >\
                                        <span data-tag="5" data-stunde="'+stundencounter+'" data-id="" class=" mvttday5 mvttmaincell">Neu</span>\
                                    </td>\
                                   \
                                </tr>';
        }
        
        function stundencounterzaehlen() {
        stundencounter = $('.mvttrow').length;
        stundencounter++;
        
        console.log(stundencounter);
        }
        function removebuttons() {
             $('.mvttparent').each( function () {
                $(this).find('.mvttplus').remove();
            });
            $('.mvttnoparent').each( function () {
                $(this).find('.mvttminus').remove();
            });
        }
        function appendbuttons() {
             $('.mvttparent').each( function () {
                $(this).append('<img src="images/plus.png" class="mvttimgmini mvttplus">');
            });
            $('.mvttnoparent').each( function () {
                $(this).append('<img src="images/minus.png" class="mvttimgmini mvttminus">');
            });
        }
        function getStundenplan() {
        $.post( "include/stundenplan/getstundenplan.php", { })
            .done(function( data ) {
                $('.mvttcontent').html(data);
            });
        }

      </script>
</body>
</html>
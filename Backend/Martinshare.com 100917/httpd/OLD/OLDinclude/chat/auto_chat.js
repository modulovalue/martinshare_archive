
 $( document ).ready(function() {
    chattimestamp = 0;
    getnewmessages();
     $('#martinchat').animate({scrollTop: '99999'});
     
    var interval = setInterval(function() { getnewmessages(); }, 1500);
        getnewmessages();
});
    
function getnewmessages() {
    $.post( "include/chat/getnewmessages.php", { timestamp: chattimestamp })
    .done(function( data ) {
        
    var json = $.parseJSON(data); 

    if(json.zeit) {
        chattimestamp = json.zeit;
    }
    
    if(json.inhalt){
        $('#messagesload').html(json.inhalt);
        
        console.log(name)
        $( ".name" ).each(function() {
            if($(this).text() == name ) {
                $(this).parent().addClass('messageright');
                $(this).parent().removeClass('messageleft');
            }
        });
    
        $('#martinchat').animate({scrollTop: '99999'});
        
    }
    
    });
}

    

            $(document).on("click",'.mvcpartyimg',function () {start();});
    
            var punktezahl = 0;
            var leben = 3;
            var gamespeed = 100;
            var loop;
            var spawn;
            var spawnspeed = 1000;
            var ceillingheight = 33;
            var stage = 1;
            
            function startinterval() {
                if(loop){
                clearInterval(loop);
                }
                loop = setInterval(function() { 
                        $(".balloon").each(function() {
                            var newtop = parseInt($(this).css('top'));
                            newtop -= 2;
                            $(this).css('top', newtop + 'px');
                           
                        });
                        checkceilling();
                        if(leben == 0) {
                        stop();
                        gameover();
                        }
                    }, gamespeed);
            }
            
            function stopinterval() {
                clearInterval(loop);
                clearInterval(spawn);
            }
            
            function start() {
                punktezahl = 0;
                leben = 3;
                startinterval();
                $('.mvccontent').html("<div class='gamewindow'>\
                                            <div class='gameheader' style='text-align: center'>\
                                                <div class='gamescore' ><span class='badge'>0</span></div>\
                                                <div class='gamestage'  ><span class='badge progress-bar-info'>1</span></div>\
                                                <div class='gamelives' ><span class='badge progress-bar-danger'>3</span></div>\
                                                <div class='gametext'  >Martins Balloons</div>\
                                                <div class='spikes'></div>\
                                            </div>\
                                            <div class='gamefield' >\
                                            </div>\
                                        </div>");
                spawner();
            }
            
            function gameover() {
            
                $('.mvccontent').html("<div class='gamewindow'>\
                                            <div class='gameheader' style='text-align: center'>\
                                                <div class='gamescore' ><span class='badge'>"+punktezahl+"</span></div>\
                                                <div class='gamelives' ><span class='badge progress-bar-danger'>0</span></div>\
                                                <div class='gametext' >GameOver</div>\
                                                <div class='spikes'></div>\
                                            </div>\
                                            <div class='gamefield' >\
                                            <div class='highscorelist text-center'>\
                                            </div>\
                <form class='submitname' style='position: relative; bottom: 0;' >\
                    <div class='input-group' >\
                        <input type='text' name='name' class='form-control hsnamefield' style='border-radius: 0px 0px 0px 0px;' autocomplete='on' value='Anonymous' required>\
                            <span class='input-group-btn'>\
                            <button type='submit' class='btn btn-default submit' style='border-radius: 0px 0px 0px 0px; height: 34px;'>\
                                <span class='glyphicon glyphicon-send' aria-hidden='true'></span>\
                            </button>\
                        </span>\
                    </div>\
                </form>\
                                        </div>");
                gethighscore();
                
                $( ".submitname" ).submit(function( event ) {
                event.preventDefault();
                var name = $('.hsnamefield').val();
                
                $.post( "include/martinsballoons/posthighscore.php", {name: name, score: punktezahl })
                                .done(function( data ) {
                                gethighscore();
                                $('.submitname').remove();
                                
                                });
                });
            }
            
            function gethighscore() {
                $.post( "include/martinsballoons/gethighscore.php", {})
                    .done(function( data ) {
                    $('.highscorelist').html(data)
                });
            }
            function posthighscore(){
                function gethighscore() {
                var name = 
                $.post( "include/martinsballoons/gethighscore.php", {name: name})
                    .done(function( data ) {
                    $('.highscorelist').html(data)
                });
            }
            }
            function newballoon() {
                var width = randomwidth();
                var height = -30;
                height += $('.mvccontent').height();
                
                var ballon = $('.gamefield').append('<img src="../start/css/calendarimg/party.png" class="balloongray balloon" style="position: absolute; left; left:' + width + 'px; top: ' + height + 'px;" >');
                $(ballon).on('dragstart', function(event) { event.preventDefault(); });
            }
            
            function stop() {
                stopinterval();
            }
            
            function checkceilling() {
                $(".balloon").each(function() {
                if(parseInt($(this).css('top')) < ceillingheight) {
                     $(this).remove();
                     lebenweniger();
                }
                });
            }
            
            function spawner() {
                if(spawn){
                clearInterval(spawn);
                }
                spawn =  setInterval(function() { 
                newballoon();
                        
                    }, spawnspeed);
            }
            
            $(document).on("click",'.balloon',function () {
                punktehoch();
                $(this).remove();
            });
            
            function randomwidth() { return Math.floor(Math.random() * (($('.mvccontent').width()-40) + 1)); }
            function punktehoch() { punktezahl++; $('.gamescore').html('<span class="badge">'+punktezahl+'</span>'); }
            function stagehoch() { stage++; $('.gamestage').html('<span class="badge progress-bar-info">'+stage+'</span>'); }
            function lebenweniger() { leben--; $('.gamelives').html('<span class="badge progress-bar-danger">'+leben+'</span>'); }
            
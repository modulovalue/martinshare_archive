<div class="martinchatcon" >
            <div class="martinchatclosed" onclick="javascript:toggle('martinchatcon')">
                <a href="#" class="name" >Martinchat: <small><?php echo $userklasse;?></small></a>
            </div>
            
            <div class="chatcontainer" id="martinchatcon" style="display: none">
                <div class="martinchat" id="martinchat"  >
                  <!--  <div class="systemnachricht wichtig"> <span class="name">System</span> 
                        <p class="nachricht1">Sagen Sie Hallo!</p> 
                    </div>-->
                        <?php           
                        $user = new User();
                        $userklasse= $user->data()->username;
                        $neuechattabelle = DB::getInstance()->query('
                        CREATE TABLE IF NOT EXISTS `CHAT'.$userklasse.'` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(20) NOT NULL,
                          `message` text NOT NULL,
                          `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0');
                     ?>      
                     
                     
                <div id="messagesload">
                
            	</div>   
        		    	
        		<?php
        		   if(Session::get(Config::get('chat/session_name'))) {  
                ?>
                <!-- <div class="messageright wichtig messageleft nichtsowichtig"> -->
                <p></p><span class="notchat">Martinchat</span>
                </div>
                <form class="submitmessage" >
                    <div class="input-group" style="position: relative; bottom: 0;">
                        <input type="text" name="message" class="form-control messagefield" style="border-radius: 0px 0px 0px 0px;" autocomplete="off" required>
                            <span class="input-group-btn">
                            <button type="submit" class="btn btn-default submit" type="button" style="border-radius: 0px 0px 0px 0px; height: 34px;">
                                <span class="glyphicon glyphicon-send" aria-hidden="true"></span>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <?php  
        	}
        	?>
        	<script>
        	var name = "<?php echo Session::get(Config::get('chat/session_name')); ?>";
        	</script>
            <script type="text/javascript" src="include/chat/auto_chat.js"></script>
            <script>
            var name
            $( document ).ready(function() {
            
            $( ".submitmessage" ).submit(function( event ) {
                var nachricht = $('.messagefield').val();
                event.preventDefault();
            
                $.post( "include/chat/sendmessage.php", {message: nachricht })
                                .done(function( data ) {
                                $('.messagefield').val('');
                                });
               
            });
   
            });
            
            </script>
        </div>
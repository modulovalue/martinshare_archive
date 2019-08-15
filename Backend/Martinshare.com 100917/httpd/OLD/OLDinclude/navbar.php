<?php
            $user = new User();
            $userklasse= $user->data()->username;
            $acount = 0;
            $scount = 0;
            $hcount = 0;
            $hdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE typ="h" ');  
            $hcount = $hdb->count();
            $adb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE typ="a" ');  
            $acount = $hdb->count();
            $sdb = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` WHERE typ="s" ');  
            $scount = $hdb->count();
            
            $count1 = 0;
            $count2 = 0;
            $count3 = 0;
           
            
            $db = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` ORDER BY datum');  
                    if($db->count()) {
                        foreach($db->results() as $new) {
                            if((strtotime($new->datum) < strtotime('+1 day')) && (strtotime($new->datum) > strtotime('today'))) {
                                    $count1++;
                            }
                        }
                    }
            $db = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` ORDER BY datum');  
                    if($db->count()) {
                        foreach($db->results() as $new) {
                            if((strtotime($new->datum) < strtotime('+2 day')) && (strtotime($new->datum) > strtotime('+1 day'))) {
                                    $count2++;
                            }
                        }
                    }
            $db = DB::getInstance()->query('SELECT * FROM `'.$userklasse.'` ORDER BY datum');  
                    if($db->count()) {
                        foreach($db->results() as $new) {
                            if((strtotime($new->datum) < strtotime('+3 day')) && (strtotime($new->datum) > strtotime('+2 day'))) {
                                    $count3++;
                            }
                        }
                    }
        
            ?>

            <nav class="navbar navbar-inverse navbar-fixed-top navbarblur" role="navigation">
                <div class="container">
                    <div class="navbar-header ">
                        
                    </div>
                </div>
            </nav>
 <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header ">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand Martinshare" style="padding: 0px; position: relative;" title="Martinshare" href="/main.php">
                    <img style="padding-top: 9px; width: 125px;" src="./images/logomartinshare.png">
                    
                    </a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <!-- <li>
                        <a class="Einreichen" href="/einreichen.php">Einreichen</a>
                    </li> -->
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle Eintraegedrop Eintraege Hausaufgaben Sonstiges Archiv Arbeitstermine" data-toggle="dropdown">Einträge <span class="caret"></span></a>
                      <ul class="dropdown-menu inverse-dropdown" role="menu">
                        <li>
                            <a class="Eintraege " href="/einträge.php">Einträge :  &nbsp; 
                            <span class="badge progress-bar-danger"> Morgen: <?php echo $count1; ?></span>  &nbsp; 
                            <span class="badge badge-morgen">Übermogen <?php echo $count2; ?></span> 
                            <!--<span class="badge badge-uebermorgen"><?php echo $count3; ?></span>-->
                            </a>
                        </li>
                        
                        <li>
                            <a class="Archiv" href="/archiv.php">Archiv</a>
                        </li>
                        
                      </ul>
                    </li>
                    <li>
                        <a class="Vertretungsplan" href="/vertretungsplan.php">Vertretungsplan</a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle Stundenplan Stundenplanupload" data-toggle="dropdown">Stundenplan<span class="caret"></span></a>
                      <ul class="dropdown-menu inverse-dropdown" role="menu">
                        <li><a class="Stundenplan" href="/stundenplan.php">Stundenplan</a></li>
                        <li><a class="Stundenplanupload" href="/stundenplanupload.php">Stundenplan Upload (Beta)</a></li>
                      </ul>
                    </li>
                   <!-- <li>
                        <a class="Upload" href="/upload.php">Upload</a>
                    </li> -->
                    <li>
                       <a class="Profil" href="profile.php">Profil</a>
                    
                    </li>
                  <!--  <li>
                        <a class="Einstellungen" href="/einstellungen.php">Einstellungen</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
    
    
    
    <script>
    
    $('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(120);
  });

  // ADD SLIDEUP ANIMATION TO DROPDOWN //
  $('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(120);
  });
    
    </script>




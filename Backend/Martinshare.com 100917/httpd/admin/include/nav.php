<?php
    $user = new User();
    $data = $user->data();
?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Navigation umschalten</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand " href="https://www.martinshare.com/admin/index.php">
            <img style="margin-top: -10px; max-width:170px;" src="../images/logomartinshare.png">
        </a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul id="active" class="nav navbar-nav side-nav">
            <li <?php if($markasactive == 1) echo'class="selected"' ?>><a href="index.php"><i class="fa fa-fw fa-bullseye"></i> Übersicht</a></li>
            <li <?php if($markasactive == 4) echo'class="selected"' ?>><a href="signupschool.php"><i class="fa fa-fw fa-list-ol"></i> Schule Registrieren</a></li>
            <li <?php if($markasactive == 2) echo'class="selected"' ?>><a href="signupstudent.php"><i class="fa fa-fw fa-list-ol"></i> Schüler Registrieren</a></li>
            <li <?php if($markasactive == 5) echo'class="selected"' ?>><a href="benutzeruebersicht.php"><i class="fa fa-fw fa-list-ol"></i> Benutzer Übersicht</a></li>
            <li <?php if($markasactive == 3) echo'class="selected"' ?>><a href="mobilefeedback.php"><i class="fa fa-fw fa-globe"></i> Feedback</a></li>
            <li <?php if($markasactive == 6) echo'class="selected"' ?>><a href="overview.php"><i class="fa fa-fw fa-globe"></i>Martinshare VP's Schulen</a></li>
            <li <?php if($markasactive == 7) echo'class="selected"' ?>><a href="editplans.php"><i class="fa fa-fw fa-globe"></i>Martinshare VP's Pläne</a></li>
            <li <?php if($markasactive == 8) echo'class="selected"' ?>><a href="schooladdress.php"><i class="fa fa-fw fa-globe"></i>Martinshare VP's Schuladressen</a></li>
            <?php /*
            <li <?php if($markasactive == 5) echo'class="selected"' ?>><a href="register.php"><i class="fa fa-fw fa-font"></i> Register</a></li>
            <li <?php if($markasactive == 6) echo'class="selected"' ?>><a href="timeline.php"><i class="fa fa-fw fa-font"></i> Timeline</a></li>
            <li <?php if($markasactive == 7) echo'class="selected"' ?>><a href="forms.php"><i class="fa fa-fw fa-list-ol"></i> Forms</a></li>
            <li <?php if($markasactive == 8) echo'class="selected"' ?>><a href="typography.php"><i class="fa fa-fw fa-font"></i> Typography</a></li>
            <li <?php if($markasactive == 9) echo'class="selected"' ?>><a href="bootstrap-elements.php"><i class="fa fa-fw fa-list-ul"></i> Bootstrap Elements</a></li>
            <li <?php if($markasactive == 10) echo'class="selected"' ?>><a href="bootstrap-grid.php"><i class="fa fa-fw fa-table"></i> Bootstrap Grid</a></li>
            */
            ?>
        </ul>
        <ul class="nav navbar-nav navbar-right navbar-user">
            
            
            <?php
            $sql = DB::getInstance()->query('SELECT id FROM `mobilefeedbackmessages` WHERE `isread` = 0 AND `deleted` NOT IN (1)');
            
            $sql2 = DB::getInstance()->query('
            SELECT m.id, s.username, m.device, m.message, m.isread, m.deleted, m.created
            FROM mobilefeedbackmessages m
            JOIN users s
            ON m.userid = s.id
            WHERE `isread` = 0 AND `deleted` NOT IN (1)
            ORDER BY created ASC
            LIMIT 0, 5
            ');
    
            ?>
            
            <li class="dropdown messages-dropdown feedbackmessages">
                
                <a href="../admin/mobilefeedback.php" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-envelope"></i> Feedback <span class="badge"><?php echo $sql->count(); ?></span> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                <li class="dropdown-header"><?php echo $sql->count(); ?> ungelesene Feedbacks</li>
                
                <?php
                foreach( $sql2->results() as $data) {
                    if($data->device == "ios") {
                            $device = ' <i class="fa fa-apple"></i>';
                    } else if($data->device == "android") {
                            $device = ' <i class="fa fa-android"></i>';
                    } else {
                            $device = "none";
                    }
                       
                       
                    echo ' 
                     <li class="message-preview">
                        <a href="../admin/mobilefeedback.php">
                            <span>'.$device.' '.$data->username.'</i></span>
                            <span class="message">'.$data->message.'</span>
                        </a>
                    </li>
                    <li class="divider"></li>
                    ';
                     
                }
                ?>
                <li><a href="../admin/mobilefeedback.php">Feedback <span class="badge"><?php echo $sql->count(); ?></span></a></li>
                </ul>
            </li>  
            
               <?php
                    if($user->hasPermission("admin")) { 
                        
                        ?>
                            <li id="backtomartinshare">
                                <a class="backtomartinshareadmin" href="https://www.martinshare.com/admin/">
                                    <i class="fa fa-arrow-circle-right"></i>
                                    <strong> Admin</strong> 
                                    
                                </a> 
                            </li> 
                        <?php
                    }
                    
                    
                    if($user->hasPermission("manager")) { 
                        
                        ?>
                            <li id="backtomartinshare">
                                <a class="backtomartinsharemanager" href="https://www.martinshare.com/manager/">
                                    <i class="fa fa-arrow-circle-right"></i>
                                    <strong> Manager</strong> 
                                </a> 
                            </li> 
                        <?php
                    }
                    
                    if($user->hasPermission("user")) { 
                        
                        ?>
                            <li id="backtomartinshare">
                                <a class="backtomartinshare" href="https://www.martinshare.com/start/">
                                    <i class="fa fa-arrow-circle-right"></i>
                                    <strong> Martinshare </strong> 
                                </a> 
                            </li> 
                        <?php
                    }
                ?>
                                    
            <li class="dropdown user-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-user"></i> 
                <?php $user = new User(); $data = $user->data(); echo $data->username; ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>  <a href="../php/abmelden.php"><i class="fa fa-fw fa-power-off"></i> Abmelden</a></li>

                </ul>
            </li>
            <?php  #<li class="divider-vertical"></li>
            #<li>
            #    <form class="navbar-search">
            #        <input type="text" placeholder="Search" class="form-control">
            #    </form>
            #</li>
            ?>
        </ul>
    </div>
</nav>
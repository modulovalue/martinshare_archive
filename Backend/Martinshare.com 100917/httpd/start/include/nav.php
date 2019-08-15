<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Navigation umschalten</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button> 
                
                <a class="navbar-brand " href="https://www.martinshare.com/start/index.php">
                    <img style="margin-top: -10px; max-width:170px;" src="../images/logomartinshare.png">
                </a>
            </div>
            
            <!-- Top Menu Items -->
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse"  style="padding-right: 15px;">
                <ul class="nav navbar-nav side-nav">
				
					<?php
					if(empty($markasactive)) {
						$markasactive = -1;
					} 
					?>

                    <li <?php if($markasactive == 1) echo'class="active"' ?>>
                        <a href="https://www.martinshare.com/start/index.php"><i class="fa fa-fw fa-dashboard"></i> Übersicht</a>
                    </li>
                    
                    <li <?php if($markasactive == 3) echo'class="active"' ?>>
                        <a href="https://www.martinshare.com/start/vertretungsplan.php"><i class="fa fa-fw fa-paper-plane"></i> Vertretungsplan</a>
                    </li>
                    <li <?php if($markasactive == 4) echo'class="active"' ?>>
                        <a href="https://www.martinshare.com/start/stundenplan.php"><i class="fa fa-fw fa-table"></i> Stundenplan</a>
                    </li>
                    <!--<li <?php if($markasactive == 41) echo'class="active"' ?>>
                        <a href="https://www.martinshare.com/start/stundenplanupload.php"><i class="fa fa-fw fa-upload"></i> Stundenplan Upload</a>
                    </li>-->
                    <li <?php if($markasactive == 5) echo'class="active"' ?>>
                        <a href="https://www.martinshare.com/start/impressum.php"><i class="fa fa-fw fa-info"></i> Impressum</a>
                    </li>
                    
                   
                </ul>
                
                <ul class="nav navbar-nav navbar-right navbar-user ">
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu message-dropdown">
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-footer">
                            <a href="#">Read All New Messages</a>
                        </li>
                    </ul>
                </li>-->
                <!--<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">View All</a>
                        </li>
                    </ul>
                </li> -->
            
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
                    
                <li class="dropdown">
                    <?php 
                    $user = new User();
                    $data = $user->data();
                    
                    ?>

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i style="padding-right:3px;" class="fa fa-fw fa-user"></i> <?php $user = new User(); $data = $user->data(); echo $data->username; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <!--<li>
                            <a href="profil.php">
                                <i class="fa fa-fw fa-user"></i> 
                                Profil
                            </a>
                        </li>-->
                        <!--<li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>-->
                        <!--<li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>-->
                        <li>
                            <a href="../php/abmelden.php"><i class="fa fa-fw fa-power-off"></i> Abmelden</a>
                        </li>
                    </ul>
                </li>
            </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
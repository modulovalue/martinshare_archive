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
        <a class="navbar-brand " href="https://www.martinshare.com/manager/index.php">
            <img style="margin-top: -10px; max-width:170px;" src="../images/logomartinshare.png">
        </a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul id="active" class="nav navbar-nav side-nav">
            <li <?php if($markasactive == 1) echo'class="selected"' ?>><a href="index.php"><i class="fa fa-fw fa-bullseye"></i> Übersicht</a></li>
            <li <?php if($markasactive == 2) echo'class="selected"' ?>><a href="manage.php"><i class="fa fa-fw fa-list-ol"></i> Kontenverwaltung</a></li>
            <li <?php if($markasactive == 3) echo'class="selected"' ?>><a href="manageinfo.php"><i class="fa fa-fw fa-list-ol"></i> Kontenübersicht</a></li>
            <li <?php if($markasactive == 5) echo'class="selected"' ?>><a href="vertretungsplan.php"><i class="fa fa-fw fa-globe"></i> Vertretungsplanverwaltung</a></li>
            <li <?php if($markasactive == 4) echo'class="selected"' ?>><a href="settings.php"><i class="fa fa-fw fa-list-ol"></i> Einstellungen</a></li>
            <li <?php if($markasactive == 6) echo'class="selected"' ?>><a href="abonnement.php"><i class="fa fa-fw fa-list-ol"></i> Abonnement</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right navbar-user">
            
    
            
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
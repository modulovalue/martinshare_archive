<?php
//if not logged in, and false, redirects to index
$noLogin = true;
//if true, redirects to main.php;
$dontvisitifloggedin = false;

require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';

$user = new User(); 

if(Input::exists()) {
    
    if(Token::check(Input::get('token'))) {
        
 
        $validate = new Validate();
        $validation = $validate-> check($_POST, array( 
                'Benutzername' => array(
                    'required' => true),
                'Passwort' => array(
                    'required' => true)
            ));
        
        if($validation->passed()) {
            
            $user = new User();
            $data = $user->data();
            
            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('Benutzername'), Input::get('Passwort'), $remember);
            $user = new User();
            $data = $user->data();
            if($login) {
                
                    setcookie("klasse", $data->username, time() + 94670778, '/','.martinshare.com');
                    Session::flash('eingeloggt', 'Willkommen zurück <strong>'.Input::get('Benutzername').'</strong>! <span class="glyphicon glyphicon-thumbs-up"></span>');
                    
                    $user = new User(); 
                
                    if($user->hasPermission("admin")) {
                        Redirect::to('admin');
                    } elseif($user->hasPermission("user")) {
                        Redirect::to('start');
                    } elseif($user->hasPermission("manager")) {
                        Redirect::to('manager');
                    }
                    
            }
            
            else {
                Session::flash('errorlogin', 'Falsche Benutzername/Passwort Kombination <br>');
            }
        }
        
        else {
            foreach($validation->errors() as $error) {
                
            printt("fail logging in".$error);
                Session::flash('errorlogin', $error.'<br>');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="de">

<head>
	
    <link rel="shortcut icon" href="images/favicon.ico">
    <meta charset="utf-8">
    <!--<meta name="apple-itunes-app" content="app-id=1001211699"> -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.84">
    <meta name="description" content="Willkommen auf Martinshare. Melde dich an und finde die wichtigsten Informationen zum Schulalltag unter einer Adresse.">
    <meta name="author" content="Martinshare">
    <meta name="keywords" content="Martinshare, Martin, Share, Schule, Hausaufgaben, Vertretungsplan, Kalender" />
    <meta name="robots" content="index,follow">
    
    <title>Martinshare - Startseite</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="css/agency.css" rel="stylesheet">
    
    <!-- Custom Fonts -->
    <link href="fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
			<a class="navbar-brand navbar-left page-scroll " href="https://www.martinshare.com/index.php">
                <img style="margin-top:-10px; margin-left:-20px; max-width:170px;" src="images/logomartinshare.png">
            </a>
            
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden"><a href="#page-top"></a></li>
                    <li><a class="page-scroll" href="#services">Martinshare</a></li>
                    
                    <!--<li><a class="page-scroll" href="#about">Über uns</a></li>
                    <li><a class="page-scroll" href="#preis">Preise</a></li> -->
                    
                    <li><a class="page-scroll" href="#contact">Kontakt</a></li> 
                    
                                    
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
                    
                    
                    
                    
                                    <li class="dropdown " id="logintomartinshare">
                                        <a class="dropdown-toggle" data-toggle="dropdown" id="dontclosenavbar">Anmelden <b class="caret"></b></a>
                                        <ul class="dropdown-menu form" style="min-width: 250px;">
                                            <li>
                                               <div class="row" >
                                                  <div class="col-md-12 ">
                                                      
                                                     <form class="formbg" role="form" method="post" action="" id="loginForm" >     
                                                         
                                                            <div class="form-group">
                                                                <label class="sr-only" for="Benutzername">Benutzername</label>
                                                                <input type="text" name="Benutzername"  placeholder="Benutzername" id="Benutzername" autocomplete="on" class="form-control" required>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="sr-only" for="passwort"> Passwort</label>
                                                                <input type="password" name="Passwort" class="form-control" id="Passwort" placeholder="Passwort" required>
                                                            </div>
                                                            
                                                            <div class="checkbox">
                                                               <label>
                                                                   <input type="checkbox" name="remember" checked> Eingeloggt bleiben </label>
                                                            </div>
                                                            
                                                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                         
                                                            
                                                            <div class="form-group">
                                                               <button name="login" type="submit" class="btn btn-success btn-block">Anmelden</button>
                                                            </div>
                    
                                                     </form>
                                                  </div>
                                               </div>
                                            </li>
                                            
                                                <?php
                                                    if(Session::exists('errorlogin')) {
                                                       echo '<li class="divider"></li> <p> <font color="red">' . Session::flash('errorlogin') . ' </font></p>';
                                                    }
                                                ?>
                                                
                                            <li class="divider"></li>
                                            <li>
                                                <p>Konten nur auf <a class="page-scroll" href="#contact" style="color: #6EBCFF;" >Nachfrage</a></p>
                                            
                                            </li>
                                        </ul>
                                    </li>
                    
                
                </ul>
            
            <?php
                    
                    $user = new User();
                    
                   
                    
                    if($user->isLoggedIn()) {
                    ?>
                        <script>
                        document.getElementById("logintomartinshare").style.display = "none";
                        document.getElementById("backtomartinshare").style.display = "show";
                        </script>
                    <?php 
                    } else { 
                    ?>
                        <script>
                        document.getElementById("logintomartinshare").style.display = "show";
                        document.getElementById("backtomartinshare").style.display = "none";
                        </script>
                    <?php 
                    
                    } ?>
            <!-- /.navbar-collapse -->
            </div>
        <!-- /.container-fluid -->
        </div>
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"> Wir bringen die Digitalisierung der Schulen <font style="font-style: italic; text-decoration: underline;">sinnvoll</font> voran.<br>
</div><br><br>
               <!-- <div class="intro-heading">Martinshare</div>-->
                <a href="#services" class="page-scroll btn btn-xl"> Willkommen </a>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="services" class="bg-light-gray">
	
	 <div class="container">
	 <!--
			<div class="row  text-center">
                <div class="col-lg-12 text-center">
                    <h1 class="section-heading">Unsere Startseite befindet sich in einer restrukturierung</h1>
                    <h3 class="section-subheading text-muted">Sie können trotzdem auf alle Services zugreifen. <br>Sollten Sie Fragen haben, nutzen Sie bitte das untere <a class="page-scroll" href="#contact">Formular</a>.</h3>
                </div>
            </div>
            -->
            
        	<div class="row  text-center">
                <div class="col-lg-12 text-center">
                    <img style="max-width:470px;" src="img/martinshare-vertretungsplan.png" class="img-responsive img-centered" alt="Martinshare Vertretungsplan">
                    <h3 class="section-subheading text-muted">Weitere Informationen folgen bald.<br>Sollten Sie Fragen haben, nutzen Sie bitte das untere <a class="page-scroll" href="#contact">Formular</a>.</h3>
                </div>
            </div>
            
    
    <!--
        <div class="row text-center">
				
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading"></h2>
                </div>
				<div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-smile-o fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Vernunft</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-user fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Vertrauen</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-calendar fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Verantwortungsbewusstsein</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
            </div>

        </div>
        
	-->
    </section>
    <!-- Portfolio Grid Section 
    <section id="portfolio" class="bg-light-gray">
	
        <div class="container">
            <div class="row">
                
                 <div class="col-md-6 portfolio-item">
                    <a href="#portfolioModal4" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/msmobilelogo.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Martinshare</h4>
                        <p class="text-muted">Martinshare für iOS- und Android erlaubt Schülern den perfekten Zugang zu den wichtigsten Informationen rund um den Schulalltag.</p>
                    </div>
                </div>
                <div class=" col-md-6 portfolio-item">
                    <a href="#portfolioModal5" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/mspslogo.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Martinshare PS</h4>
                        <p class="text-muted">Mit Martinshare PS können Schulen ihren Schülern den Vertretungsplan digital zur Verfügung stellen.<br><br></p>
                    </div>
                </div>
                
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/roundicons.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Martinshar für iOS</h4>
                        <p class="text-muted"><a target="_blank" href="http://ios.martinshare.com">Martinshare im App Store</a></p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/startup-framework.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Martinshare für Android</h4>
                        <p class="text-muted"><a target="_blank" href="http://android.martinshare.com">Martinshare im Play Store</a></p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/treehouse.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Martinshare.com</h4>
                        <p class="text-muted">Website Design</p>
                    </div>
                </div>
               
            </div>
        </div>
    </section>
-->
<?php /*
    <!-- About Section 
    <section id="about" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Über uns</h2>
                    <h3 class="section-subheading text-muted">Unsere Entwicklung</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="timeline">
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="img/about1.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2013</h4>
                                    <h4 class="subheading">Erste Testversion</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">Ein zu lösendes Problem wurde erkannt. 
									Die erste Version von Martinshare ging online. </p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="img/msiconwbg.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2014</h4>
                                    <h4 class="subheading">Mobile Apps</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">Die ersten Erfolge haben gezeigt, dass der Bedarf für einen 
									crowdgesourcten Klassenkalender besteht. Feedback und Meinungen vieler Schüler sorgten für die kontinuierliche 
									Weiternetwicklung von Martinshare. </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="img/mspswbg.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2015</h4>
                                    <h4 class="subheading">Vertretungsplan</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">"Martinshare PS" bietet Schulen, die synchronisation von Vertretungsplänen mit Martinshare.</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="img/aboutosda.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>August - Oktober 2015</h4>
                                    <h4 class="subheading">Orange Social Design Award 2015</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">Teilnahme am "Orange Social Design Award 2015" von Spiegel Online und die Aufnahme in die Shortlist (Top 10)</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <h4><br>...</h4>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
-->

*/ ?>
        
       <!-- Pricing Section
<section class="container-fluid" id="preis">
  <div class="col-sm-10 col-sm-offset-1">
    <div class="container">
    <div class="row">
      <div class="col-sm-4 col-xs-12">
            <div class="list-group">
              <a href="#" class="list-group-item active">
                <h2 class="list-group-item-heading">Gratis</h2>
                <h4>Probiere Martinshare</h4>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ 2 Kalendereinträge und 2 Updates am Tag</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Unbegrenzt viele Nutzer</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ iOS-App</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Android-App</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Zugang zur Webseite</p>
              </a>
                <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Push-Benachrichtigungen</p>
              </a>
              <a href="#" class="list-group-item">
                <button class="btn btn-primary btn-lg btn-block">Kontaktiere uns</button>
              </a>
            </div>
      </div>
      
      <div class="col-sm-4 col-xs-12">
            <div class="list-group text-center">
              <a href="#" class="list-group-item active">
                <h2 class="list-group-item-heading">Basic</h2>
                <h4>Für einzelne Klassen</h4>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Unbegrenzt viele Einträge</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Stundenplanupload</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Support bei Problemen</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">-</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">-</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">-</p>
              </a>
              <a href="#" class="list-group-item">
                <button class="btn btn-default btn-lg btn-block">59€ p. a.</button>
              </a>
            </div>
      </div>
      
      <div class="col-sm-4 col-xs-12">
            <div class="list-group text-right">
              <a href="#" class="list-group-item active">
                <h2 class="list-group-item-heading">Pro</h2>
                <h4>Für Schulen</h4>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ 10 Jahreslizensen</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Zugang zu Martinshare PS</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">+ Erweiterter Support</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">-</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">-</p>
              </a>
              <a href="#" class="list-group-item">
                <p class="list-group-item-text">Unlimited</p>
              </a>
              <a href="#" class="list-group-item">
                <button class="btn btn-default btn-lg btn-block">490€ p. a.</button>
              </a>
            </div>
      </div>
      
    </div>
    </div>
  </div>
</section>
   -->
        
    <!-- Team Section -->
    <!-- <section id="team" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Our Amazing Team</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/1.jpg" class="img-responsive img-circle" alt="">
                        <h4>Kay Garland</h4>
                        <p class="text-muted">Lead Designer</p>
                        <ul class="list-inline social-buttons">
                            <li><a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/2.jpg" class="img-responsive img-circle" alt="">
                        <h4>Larry Parker</h4>
                        <p class="text-muted">Lead Marketer</p>
                        <ul class="list-inline social-buttons">
                            <li><a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/3.jpg" class="img-responsive img-circle" alt="">
                        <h4>Diana Pertersen</h4>
                        <p class="text-muted">Lead Developer</p>
                        <ul class="list-inline social-buttons">
                            <li><a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <p class="large text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
                </div>
            </div>
        </div>
    </section>

    -->

    <!-- Clients Aside -->
<section class="bg-light-gray">
    <aside class="clients">
        <div class="container">
            <div class="row">
                
                
                
                <!--<div class="col-md-4 col-sm-4">
                    <a target="_blank" href="http://www.martinshare.com/images/presse/bt.jpg">
                        <img style="max-width:130px;"  src="images/presse/btlogo.png" class="img-responsive img-centered" alt="">
                    </a>
                        <h5 style="margin-top: -7px; text-align: center;" class="section-heading">"Ausgezeichnete App"<br> 
                    <a target="_blank" href="http://www.martinshare.com/images/presse/bt.jpg">
                        Badisches Tagblatt <br>
                    </a> </h5>
                </div> -->
                
                
                <div class="col-md-6 col-sm-6">
                    <a href="#">
                        <img  style="max-width:180px; margin-top:80px;" src="img/logos/spiegel_online.png" class="img-responsive img-centered" alt="">
                    </a>
                    <h5 style="margin-top: 79px; text-align: center;" class="section-heading"><br><br>Martinshare im Spiegel Online <br> <a target="_blank" href="http://www.spiegel.de/kultur/gesellschaft/orange-social-design-award-die-kandidaten-im-ueberblick-a-1055664.html">Hier</a> und <a target="_blank" href="http://www.spiegel.de/kultur/gesellschaft/orange-social-design-award-die-zehn-shortlist-kandidaten-im-portraet-a-1058629.html">Hier</a></h5>
                </div>
                
                
                <div class="col-md-6 col-sm-6">
                    <a href="#">
                        <img style="max-width:130px;"  src="img/logos/osda_2015.jpg" class="img-responsive img-centered" alt="">
                    </a>
                    <h5 style="margin-top: -7px; text-align: center;" class="section-heading"><br><br>Martinshare im Finale des <br> Orange Social Design Award<br> <a target="_blank" href="http://www.spiegel.de/kultur/gesellschaft/orange-social-design-award-das-sind-die-gewinner-a-1060831.html">Hier</a> </h5>
                </div>
                
                
            </div>
            <br>
            <br>
        </div>
    </aside>
</section>
    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Fragen?</h2>
                    <h3 class="section-subheading text-muted">Hinterlasse uns eine Nachricht.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" action="" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Dein Name *" id="name" required data-validation-required-message="Bitte gib deinen Namen an.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Deine E-Mail *" id="email" required data-validation-required-message="Bitte gib deine E-Mail-Addresse an.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <!--<div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Your Phone *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>-->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Deine Nachricht *" id="message" required data-validation-required-message="Wir benötigen eine Nachricht."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" name="contact" class="btn btn-xl">Nachricht Senden</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-light-gray">
        
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <span class="copyright">&copy; Martinshare 2017</span>
                </div>
                <!--
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li>
                            <a href="http://fb.martinshare.com"><i class="fa fa-facebook"></i></a>
                        </li>
                    </ul>
                </div>
                -->
                
   <?php /*
                
                <!-- <div class="col-md-3">
            		<div class="thumbnail center well well-sm text-center">
                        <h2>Newsletter   
                        
                        <button class="btn btn-default newslettershowbutton"><span class="newslettercaret glyphicon glyphicon-menu-down"></span></button></h2>
                        
                        <div class="newsletterform">
                        <p>Wir benachrichtigen Sie kostenlos über Neuigkeiten.</p>
                            <form action="../mail/newsletter.php" method="post" role="form">
                                <div class="input-group">
                                  <span class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                  </span>
                                  <input class="form-control" type="email" id="" name="email" placeholder="deine@email.de" required data-validation-required-message="Bitte gib deine E-Mail-Addresse an.">
                                </div>
                                <br>
                                <button type="submit" name="contact" class="newslettersubmitbutton btn btn-xl">Absenden</button>
                          </form>
                       </div>
                    </div>    
                </div> -->
                
                */ ?>
                
                <div class="col-md-6">
                    <ul class="list-inline quicklinks">
                        <!--<li><a href="#">Datenschutz</a>
                        </li> |-->
                        <li> <a href="#impressummodal" data-toggle="modal">Impressum</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>


   <?php /*

    <!-- Portfolio Modals -->
    <!-- Use the modals below to showcase details about your portfolio projects! -->


    <!-- Portfolio Modal 1
    
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                           
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/roundicons-free.png" alt="">
                            <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                            <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                            <p>
                                <strong>Want these icons in this portfolio item sample?</strong>You can download 60 of them for free, courtesy of <a href="https://getdpd.com/cart/hoplink/18076?referrer=bvbo4kax5k8ogc">RoundIcons.com</a>, or you can purchase the 1500 icon set <a href="https://getdpd.com/cart/hoplink/18076?referrer=bvbo4kax5k8ogc">here</a>.</p>
                            <ul class="list-inline">
                                <li>Date: July 2014</li>
                                <li>Client: Round Icons</li>
                                <li>Category: Graphic Design</li>
                            </ul>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
    
    
    <!-- Portfolio Modal 2
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Heading</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/startup-framework-preview.png" alt="">
                            <p><a href="http://designmodo.com/startup/?u=787">Startup Framework</a> is a website builder for professionals. Startup Framework contains components and complex blocks (PSD+HTML Bootstrap themes and templates) which can easily be integrated into almost any design. All of these components are made in the same style, and can easily be integrated into projects, allowing you to create hundreds of solutions for your future projects.</p>
                            <p>You can preview Startup Framework <a href="http://designmodo.com/startup/?u=787">here</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->

    <!-- Portfolio Modal 3 
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/treehouse-preview.png" alt="">
                            <p>Treehouse is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. This is bright and spacious design perfect for people or startup companies looking to showcase their apps or other projects.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/treehouse-free-psd-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
-->

    <!-- Portfolio Modal 4
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            
                            
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/golden-preview.png" alt="">
                            <p>Start Bootstrap's Agency theme is based on Golden, a free PSD website template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Golden is a modern and clean one page web template that was made exclusively for Best PSD Freebies. This template has a great portfolio, timeline, and meet your team sections that can be easily modified to fit your needs.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/golden-free-one-page-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
-->

    <!-- Portfolio Modal 5
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/escape-preview.png" alt="">
                            <p>Escape is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Escape is a one page web template that was designed with agencies in mind. This template is ideal for those looking for a simple one page solution to describe your business and offer your services.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/escape-one-page-psd-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
-->

    <!-- Portfolio Modal 6
    <div class="portfolio-modal modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div  class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div  class="modal-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
    
    */ ?>
    
    <!-- Impressum Modal -->
    <div class="portfolio-modal modal fade" id="impressummodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div  class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div  class="modal-body">
                            <?php
                            include'include/impressumtext.html';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>

   <?php /*
    <!--<script>
        $( document ).ready(function() {
        
            $(".newsletterform").hide();
            
            $(document).on("click",'.newslettershowbutton',function () {
                $(".newsletterform").slideToggle();
            });
        
        });
        
    </script>-->
   
   */ ?>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    
      ga('create', 'UA-50057105-3', 'auto');
      ga('send', 'pageview');
    
    </script>
    <!-- Custom Theme JavaScript -->
    <script src="js/agency.js"></script>

</body>

</html>

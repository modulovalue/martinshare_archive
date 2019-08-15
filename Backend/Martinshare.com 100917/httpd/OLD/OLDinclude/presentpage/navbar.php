

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
                <a class="navbar-brand Martinshare" href="/main.php"><b>Martinshare</b></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="" href="/schueler.php">Für Schüler</a>
                    </li>
                    <li>
                        <a class="" href="/schulen.php">Für Schulen</a>
                    </li>
                    <li>
                        <a class="" href="/vertretungsplan.php">Kontakt</a>
                    </li>
                     <li>
                        <a class="" href="/login.php">Login</a>
                    </li>
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




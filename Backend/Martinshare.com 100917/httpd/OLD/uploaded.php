<?php
$pageTitle = 'Hochgeladene Dateien';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include 'include/header.php';
echo '</head>';
echo '<body>';
include 'include/navbar.php';
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Hochgeladene Dateien</h1>
		            <br>
		            <center>
		            <hr noshade width="300" size="3" align="center">
		            <?php
    		            $user = new User();
                        $userklasse= $user->data()->username;
    		            
    		                $alledateien = scandir('upload/'.$userklasse.'/');
    		                    foreach ($alledateien as $datei) {
    		                        if ($datei != "." && $datei != ".." && $datei != ".htaccess") {
    		                            echo "<a href=\"upload/".$userklasse."/$datei\" target='_blank'>$datei</a>\n";
    		                            $dateigröße = filesize('upload/'.$userklasse.'/'.$datei)/1000/1000;
    		                            echo '<small><small><span style="color:orange">';
    		                            echo round($dateigröße,3).' MB';
    		                            echo '</span><br></small></small>';
    		                        }
    		                    };
		            ?>
		            <hr noshade width="300" size="3" align="center">
	            	</center>
            </div>
        </div>
    </div>
     <?php include Config::get('includes/footer'); ?>
</body>
</html>
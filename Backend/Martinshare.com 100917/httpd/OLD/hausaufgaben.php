<?php
$pageTitle = 'Hausaufgaben';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body id="Hausaufgaben">';
include Config::get('includes/navbar');
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">

                <h1>Hausaufgaben</h1>
                    <?php
                    $_typ = 'h';
                    include 'include/tablesoop.php';
                    ?>
            </div>
        </div>
    </div>
    <?php include Config::get('includes/footer'); ?>
</body>
</html>
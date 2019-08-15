<?php
$pageTitle = 'Sonstiges';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body id="Sonstiges">';
include Config::get('includes/navbar');
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Sonstiges</h1>
                    <?php
                    $_typ = 's';
                    include 'include/tablesoop.php';
                    ?>
            </div>
        </div>
    </div>
    <?php include Config::get('includes/footer'); ?>
</body>
</html>
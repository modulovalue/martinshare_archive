<?php
$pageTitle = 'Arbeitstermine';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body id="Arbeitstermine">';
include Config::get('includes/navbar');
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Arbeitstermine</h1>
                    <?php
                    $_typ = 'a';
                    include 'include/tablesoop.php';
                    ?>
            </div>
        </div>
    </div>
    <?php include Config::get('includes/footer'); ?>
</body>
</html>
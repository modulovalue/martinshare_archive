<?php
$pageTitle = 'Arbeitstermine';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
?>
</head>
<?php
echo '<body id="Arbeitstermine">';
include Config::get('includes/navbar');
$user = new User();
$userklasse= $user->data()->username;
?>

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                </div>
                            </div>
                        </div>
                    
      <?php include Config::get('includes/footer'); ?>
</body>
</html>
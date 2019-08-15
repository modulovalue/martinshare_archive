<?php

    require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
    echo date('Y-m-d',Input::get('timestampp'));
?>
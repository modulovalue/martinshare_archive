<?php require_once '../../include/connect.inc.php'; ?>

<?php

$data = array(
            array('ID'=>'parvez', 'Name'=>11, 'Salary'=>101),
            array('ID'=>'alam', 'Name'=>1, 'Salary'=>102),
            array('ID'=>'phpflow', 'Name'=>21, 'Salary'=>103)          
        );
             

        
    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData"=>$data);

echo json_encode('{"sEcho":1,"iTotalRecords":3,"iTotalDisplayRecords":3,"aaData":[{"id":"1","aid":null,"email":"info@verwaltung.cbs-gaggenau.de","homepage":"http:\/\/www.carl-benz-schule-gaggenau.de\/","name":"Carl-Benz-Schule Gaggenau"},{"id":"2","aid":null,"email":"schulleiter@obs-uplengen.de","homepage":"http:\/\/www.obs-uplengen.de\/","name":"OBS Uplengen"},{"id":"3","aid":null,"email":"","homepage":"https:\/\/www.martinshare.com","name":"Demo"}]}');

?>
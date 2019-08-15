<?php require_once '../include/core.inc.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>

<script>
    $( document ).ready(function() {
        $('#example').dataTable({
                "bProcessing": true,
                "sAjaxSource": "http://martinshare.com/api/apinew.php/datatableschools/",
                "columns": [
                    { mData: 'id' } ,
                    { mData: 'name' },
                    { mData: 'homepage' },
                    { mData: 'email' }
            ]
        });   
    });
</script>
    <div class="">
        <table id="example" class="display" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>homepage</th>
                    <th>email</th>
                   
                </tr>
            </thead>
        </table>
    </div>
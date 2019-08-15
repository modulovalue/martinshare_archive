<?php
                $mysql_host = 'martinshare.com.mysql';
                $mysql_user = 'martinshare_com';
                $mysql_pass = 'xcBZz6w3';
                $mysql_db = 'martinshare_com';
                $error = 'Bitte wenden sie sich an den Admin info@martinshare.com';
                
    		    $link = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die (mysql_error ());
    		    $linkdb = mysql_select_db($mysql_db) or die(mysql_error());
                    
                if(!$link || !$linkdb)
                {die(mysql_error($error));}
?>
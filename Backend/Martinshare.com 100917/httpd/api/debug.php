<html>
    
    <meta name="viewport" content="width=500, initial-scale=1.4">
    <META HTTP-EQUIV="refresh" CONTENT="1">
    <body style="background-color: black; color: white; font-family: monospace">
   
            
        <p>
        <?php 
            if(isset($_GET["start"])) {
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/debug/print.txt', "");
            }
        ?>
        
       
        <center>
            <button style="width: 200px; height: 80px; background-color: white;"> 
                <h1>
                    <a style="color:green;" href="?start">Clear</a>
                </h1>
            </button>
        </center>
       
        
        <?php
            $fh = fopen($_SERVER['DOCUMENT_ROOT'] . '/debug/print.txt','r');
            while ($line = fgets($fh)) {
              // <... Do your work with the line ...>
               echo((nl2br($line)));
            }
            fclose($fh);
        ?>
        </p>
    </body>
</html>
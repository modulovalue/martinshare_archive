<?php
$pageTitle = 'Sharen';
require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php';
include Config::get('includes/header');
echo '</head>';
echo '<body id="Upload">';
include Config::get('includes/navbar');
?>
    <script type="text/javascript">
     function showMyImage(fileInput) {
            var files = fileInput.files;
            for (var i = 0; i < files.length; i++) {           
                var file = files[i];
                var imageType = /image.*/;     
                if (!file.type.match(imageType)) {
                    continue;
                }           
                var img=document.getElementById("thumbnil");            
                img.file = file;    
                var reader = new FileReader();
                reader.onload = (function(aImg) { 
                    return function(e) { 
                        aImg.src = e.target.result; 
                    }; 
                })(img);
                reader.readAsDataURL(file);
            }    
        }
    </script>
<?php
$user = new User();
$data = $user->data();
$target_path = 'upload/'.$data->username.'/';
if (! file_exists($target_path)) {
    mkdir($target_path, 0755, true);
    copy('upload/.htaccess', $target_path.'/.htaccess');
}
?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>Upload</h1>
		            <br>
		            <center>
		            <?php
			            if (!empty($_FILES['datei'])) {
			                
			                if (!empty($_POST['rename'])) {
			                    $filename = $_FILES['datei']['name'];
                                $fileext = explode('.', $filename);
                                $image_file_pfad = $target_path . $_POST['rename'] . '.' . array_pop($fileext);
                            
                                if (move_uploaded_file($_FILES['datei']['tmp_name'], $image_file_pfad))
			                    {echo "<font color='00B200'>Die Datei \"". $_POST['rename'] . '.' . array_pop(explode('.', $_FILES['datei']['name'])) . "\" wurde erfolgreich hochgeladen</font>";}

			                    else
			                    {echo "<font color='FF0022'>Fehler während dem upload, bitte erneut versuchen!</font>";}
			                }
			                
			                else {
			                    $target_path = $target_path . basename( $_FILES['datei']['name']);

			                    if(move_uploaded_file($_FILES['datei']['tmp_name'], $target_path))
			                    {echo "<font color='00B200'>Die Datei \"". basename($_FILES['datei']['name']). "\" wurde erfolgreich hochgeladen</font>";}

			                    else
			                    {echo "<font color='FF0022'>Fehler während dem upload, bitte erneut versuchen!</font>";}
			                }
			            }
		            ?>
		            <p></p>
		            <form action="upload.php" method="post" enctype="multipart/form-data">
		            <input type="hidden" name="MAX_FILE_SIZE" value="8388608">
		            <input style="font-size:120%; color:white" type="file" name="datei" onchange="showMyImage(this)">
		            
		            <img id="thumbnil" style="width:30%; margin-top:10px;"  src="" alt="image"/>
		            <br><br>
		            <input style="font-size:120%" type="text-center" name="rename" placeholder="Datei Umbenennen">
		            <br><br><br>
		            <input style="height: 50px; width: 125px; font-size:120%" type="submit" value="Hochladen">
		            </form>

		            <p>(Maximal 8MB)</p>
		            <h3><a href="/uploaded.php">Hochgeladene Dateien</a></h3>
	            	</center>
            </div>
        </div>
    </div>
     <?php include Config::get('includes/footer'); ?>
</body>
</html>
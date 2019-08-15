<?php require_once $_SERVER['DOCUMENT_ROOT']. '/include/core.inc.php'; 
require_once $_SERVER['DOCUMENT_ROOT']. '/frameworks/resizeimage/function.resize.php';
?>
<!DOCTYPE html>
<html lang="en">

<?php $markasactive = 41; ?>

<?php

// ----------------------- RESIZE FUNCTION -----------------------
// Function for resizing any jpg, gif, or png image files
function ak_img_resize($target, $newcopy, $ext) {
    
    
    $w = 1200;
    $h = 1200;
    
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
    $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
    $img = imagecreatefrompng($target);
    } else { 
    $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    if ($ext == "gif"){ 
        imagegif($tci, $newcopy);
    } else if($ext =="png"){ 
        imagepng($tci, $newcopy);
    } else { 
        imagejpeg($tci, $newcopy, 84);
    }
}
// ---------------- THUMBNAIL (CROP) FUNCTION ------------------
// Function for creating a true thumbnail cropping from any jpg, gif, or png image files
function ak_img_thumb($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $src_x = ($w_orig / 2) - ($w / 2);
    $src_y = ($h_orig / 2) - ($h / 2);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif"){ 
    $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
    $img = imagecreatefrompng($target);
    } else { 
    $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
    if ($ext == "gif"){ 
        imagegif($tci, $newcopy);
    } else if($ext =="png"){ 
        imagepng($tci, $newcopy);
    } else { 
        imagejpeg($tci, $newcopy, 84);
    }
}
// ------------------ IMAGE CONVERT FUNCTION -------------------


// Function for converting GIFs and PNGs to JPG upon upload


function ak_img_convert_to_jpg($target, $newcopy, $ext) {
    
    $quality = 20;
    
    list($w_orig, $h_orig) = getimagesize($target);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif"){ 
        $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
        $img = imagecreatefrompng($target);
    }
    $tci = imagecreatetruecolor($w_orig, $h_orig);
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, $quality);
}
?>

<head>
    <?php include'include/headinclude.php'; ?>
	
    <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1.5, user-scalable=yes">

    <title>Martinshare - Stundenplan Upload</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<script type="text/javascript">
     function showMyImage(fileInput) {
            var files = fileInput.files;
            for (var i = 0; i < files.length; i++) {           
                var file = files[i];
                var imageType = /image.*/;     
                if (!file.type.match(imageType)) {
                    continue;
                }           
                var img=document.getElementById("thumbnail");            
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
	<style>
	    img[src=""] {
           display: none;
        }
	</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include'include/nav.php'; ?>
		
        <div id="page-wrapper">

            <div class="container-fluid" >

                <!-- Page Heading -->
                <div class="row text-center">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Stundenplan Upload (Beta)
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Übersicht</a>
                            </li>
                            <li>
                                <i class="fa fa-table"></i>  <a href="stundenplan.php">Stundenplan</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-upload"></i> Stundenplan Upload (Beta)
                            </li>
                        </ol>
                    </div>
               
                <!-- /.row -->
                    <div class="col-md-12">
                       
                        <?php
                        $user = new User();
                        
                        $target_dir = $_SERVER['DOCUMENT_ROOT'] .'/images/stundenplaene/'.$user->data()->username.'/';;
            
                        if (! file_exists($target_dir)) {
                            mkdir($target_dir, 0755, true);
                        }
                        
                            $target_file = $target_dir . "stundenplan.jpg";
                           
                           
                            if(isset($_POST["submit"])) {
                                
                                
                                $sourceImg = @imagecreatefromstring(@file_get_contents($_FILES["uploaded_file"]["tmp_name"]));
                                
                                if ($sourceImg === false) {
                                   throw new Exception("{$source}: Invalid image.");
                                } else {
                                    echo "<p>IS IMAGE</p>";
                                }

                                           
                                // Access the $_FILES global variable for this specific file being uploaded
                                // and create local PHP variables from the $_FILES array of information
                               
                            
                            //if( $_FILES['uploaded_file']['mime'] == 'image/jpeg') {
                            //  
                            //  echo "png uploaded ------------------------------------------------";
                            //    $exif = exif_read_data($_FILES['uploaded_file']['tmp_name'], 'IFDO', true);
                            //    
                            //    $orientation = $exif['IFD0']['Orientation'];;
                            //    if($orientation != 0) {
                            //          $image = imagecreatefromstring(file_get_contents
                            //($_FILES['uploaded_file']['tmp_name']));
                            //          switch($orientation) {
                            //               case 8:
                            //                  $image = imagerotate($image,90,0);
                            //                  break;
                            //              case 3:
                            //                 $image = imagerotate($image,180,0);
                            //                 break;
                            //              case 6:
                            //                 $image = imagerotate($image,-90,0);
                            //                 break;
                            //           }
                            //           imagejpeg($image, $_FILES['uploaded_file']['tmp_name']);
                            //    }
                            //  
                            //}
                            
                            //$imagee = imagecreatefromstring(file_get_contents($_FILES['uploaded_file']['tmp_name']));
                            //
                            //imagefilter($imagee, IMG_FILTER_BRIGHTNESS, 5);
                            //imagefilter($imagee, IMG_FILTER_CONTRAST, -5);
                            //
                            //// define the sharpen matrix
                            //$sharpen = array(
                            //	array(0.0, -1.0, 0.0),
                            //	array(-1.0, 7.0, -1.0),
                            //	array(0.0, -1.0, 0.0)
                            //);
                            //
                            //// calculate the sharpen divisor
                            //$divisor = array_sum(array_map('array_sum', $sharpen));
                            //
                            //// apply the matrix
                            //imageconvolution($imagee, $sharpen, $divisor, 0);
                            //
                           // imagejpeg($imagee, $_FILES['uploaded_file']['tmp_name']);
                        
                        
            //                $fileName = $_FILES["uploaded_file"]["name"]; // The file name
            //                $fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"]; // File in the PHP tmp folder
            //                $fileType = $_FILES["uploaded_file"]["type"]; // The type of file it is
            //                $fileSize = $_FILES["uploaded_file"]["size"]; // File size in bytes
            //                $fileErrorMsg = $_FILES["uploaded_file"]["error"]; // 0 for false... and 1 for true
            //                $fileName = preg_replace('#[^a-z.0-9]#i', '', $fileName); // filter the $filename
            //                $kaboom = explode(".", $fileName); // Split file name into an array using the dot
            //                $fileExt = end($kaboom); // Now target the last array element to get the file extension
            //                
            //                // START PHP Image Upload Error Handling --------------------------------
            //                if (!$fileTmpLoc) { // if file not chosen
            //                    echo "ERROR: Please browse for a file before clicking the upload button.";
            //                    exit();
            //                } else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
            //                    echo "ERROR: Your file was larger than 5 Megabytes in size.";
            //                    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
            //                    exit();
            //                } else if (!preg_match("/.(gif|jpg|png)$/i", $fileName) ) {
            //                     // This condition is only if you wish to allow uploading of specific file types    
            //                     echo "ERROR: Your image was not .gif, .jpg, or .png.";
            //                     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
            //                     exit();
            //                } else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
            //                    echo "ERROR: An error occured while processing the file. Try again.";
            //                    exit();
            //                }
            //                // END PHP Image Upload Error Handling ----------------------------------
            //                if(file_exists($target_file)) {
            //                    chmod($target_file,0755); //Change the file permissions if allowed
            //                    unlink($target_file); //remove the file
            //                }
            //
            //                $moveResult = move_uploaded_file($fileTmpLoc, $target_file);
            //                // Check to make sure the move result is true before continuing
            //                if ($moveResult != true) {
            //                    echo "ERROR: File not uploaded. Try again.";
            //                    exit();
            //                }
            //                
            //                // ---------- Start Universal Image Resizing Function --------
            //                #$resized_file = $target_dir."resized_$fileName";
            //            //    $resized_file = $target_file;
            //            //    
            //            //    ak_img_resize($target_file, $resized_file, $fileExt);
            //                // ----------- End Universal Image Resizing Function ----------
            //                
            //                // ---------- Start Convert to JPG Function --------
            //            //    if (strtolower($fileExt) != "jpg") {
            //            //        #$new_jpg =  $target_dir."resized_".$kaboom[0].".jpg";
            //            //        ak_img_convert_to_jpg($target_file, $target_file, $fileExt);
            //            //    }
            //                
            //                //image_fix_orientation($target_file);
            //                 
            //                // ----------- End Convert to JPG Function -----------
            //                // Display things to the page so you can see what is happening for testing purposes
            //                echo "Das Bild <strong>$fileName</strong> wurde erfolgreich hochgeladen.<br /><br />";
            //                // echo "It is <strong>$fileSize</strong> bytes in size.<br /><br />";
            //                // echo "It is an <strong>$fileType</strong> type of file.<br /><br />";
            //                // echo "The file extension is <strong>$fileExt</strong><br /><br />";
            //                // echo "The Error Message output for this upload is: $fileErrorMsg";
            //                           
                        }    
                        
                        ?> 
                    
                        <center><p>
                            <form enctype="multipart/form-data" method="post" action="stundenplanupload.php">
                            <p>Wähle dein Bild</p><br>
                            <input style="color: #000;" name="uploaded_file" type="file" accept="image/x-png, image/gif, image/jpeg" onchange="showMyImage(this)"/>
                            <img id="thumbnail" style=" padding-top: 20px; padding-bottom: 20px; width:60%; margin-top:10px;" src=""/>
                            <br>
                            <input type="submit" value="Hochladen" name="submit"/>
                            </form>
                            </p>
                        </center>
                    
                       
                    </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    
    <script src="js/bootstrap.min.js"></script>


	
</body>

</html>

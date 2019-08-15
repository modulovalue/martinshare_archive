<?php
require_once "../include/coreohnecheck.php";
require_once "../include/sanitize.php";
// Check for empty fields
if(empty($_POST['email']) || !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
{
	echo "Fehler";
	return false;
}


DB::getInstance()->query("INSERT INTO `newslettermail` ( email ) VALUES ( '".escape($_POST['email'])."' ) ");

$email_address = $_POST['email'];
	
// Create the email and send the message
$to = 'newsletter@martinshare.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Newsletter: $email_address";
$email_body = "E-Mail: $email_address";
$headers = "From: noreply@martinshare.com\r\n";
// This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.

$headers .= "Reply-To: $email_address\r\n";	

#$header = "From:" .$_POST ["nachname"]. " <" .$_POST ["email"].">\n";
#$headers .= "Reply-To: ".$_POST ["email"]."\n";
$headers .= 'Content-Type: text/plain; charset=utf-8';
mail($to,$email_subject,$email_body,$headers);
header('Location: ../index.php');
return true;

?>
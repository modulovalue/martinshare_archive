<?php
// Check for empty fields
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$message = $_POST['message'];
	
// Create the email and send the message
$to = 'info@martinshare.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Kontaktformular: $name";
$email_body = "Eine neue Nachricht von einem Besucher Ihrer Seite\r\n"."Hier sind die Details:\r\n\r\nName: $name\r\n\r\nE-Mail: $email_address\r\n\r\nMessage:\r\n$message";
$headers = "From: noreply@martinshare.com\r\n";
// This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.

$headers .= "Reply-To: $email_address\r\n";	

#$header = "From:" .$_POST ["nachname"]. " <" .$_POST ["email"].">\n";
#$headers .= "Reply-To: ".$_POST ["email"]."\n";
$headers .= 'Content-Type: text/plain; charset=utf-8';
mail($to,$email_subject,$email_body,$headers);
return true;			
?>
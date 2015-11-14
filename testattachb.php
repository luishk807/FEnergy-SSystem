<?php
session_start();
require("include/phpMailer/class.phpmailer.php");
include "include/config.php";
include "include/function.php";
$mail = new PHPMailer();
$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "smtpout.secureserver.net";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "hr@familyenergysales.com";  // SMTP username
$mail->Password = "hr1514"; // SMTP password
$mail->Port = 80;
//$mail->SMTPSecure = "ssl";
$mail->SMTPSecure = "http";
$mail->SMTPDebug = 1; // 1 tells it to display SMTP errors and messages, 0 turns off all errors and messages, 2 prints 

$mail->From = "hr@familyenergysales.com";
$mail->FromName = "Family Energy Sales Report System";
$mail->AddAddress("luishk807@hotmail.com", "Luis");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("tmp/imagefile.png", "imagefile.png");    // optional name
$mail->AddEmbeddedImage("tmp/imagefile.png",'my-image','imagefile.png');
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Here is the subject";
$mail->Body    = "Something is here becuase it's not there<br/><br/><img src='cid:my-image' alt='something'/>";
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

echo "Message has been sent";
include "include/unconfig.php";
?>
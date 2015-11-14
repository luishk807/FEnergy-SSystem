<?php
$to='luishk807@hotmail.com';
$subject='PHP Mail Attachment Test';
$bound_text="jimmyP123";
$bound="--".$bound_text."\r\n";
$bound_last="--".$bound_text."--\r\n";
$headers="From: admin@server.com\r\n";
$headers .="MIME-Version: 1.0\r\n"
."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
$message .= "If you can see this MIME than your client doesn't accept MIME types!\r\n".$bound;
$file=file_get_contents("tmp/imagefile.png");
//$message .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 64bit\r\n\r\n"."hey my <b>good</b> friend here is a picture of regal beagle\r\n".$bound;
$message .="Content-Type: image/png; name=\"imagefile.png\"\r\n"."Content-Transfer-Encoding: base64\r\n"."Content-disposition: attachment; file=\"imagefile.png\"\r\n"."\r\n".chunk_split(base64_encode($file)).$bound_last;
$message .="<img src=''/><br/>Testing This";
if(mail($to, $subject, $message, $headers))
{
	echo 'MAIL SENT';
}else
{
	echo 'MAIL FAILED';
}
?>
<?php
echo "test";

$to      = 'tbass134@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if(mail($to, $subject, $message, $headers))
{
	echo "mail sent";
}
else
{
	echo "crap";
}
?>
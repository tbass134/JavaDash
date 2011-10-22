<?php
require('inc/functions.php');

$email = $_POST['email'];
if($email == null)
{
	echo "no email address";
	exit;	
}

$user = findUserByEmail($email);
if($user != null)
{
	$sql = "UPDATE users SET enable_email_use=0 WHERE id={$user->id}";
	dbUpdate($sql);
	echo "You have been unsubscribed from JavaDash!";
}
else
{
	echo "User was not found in Database";
}
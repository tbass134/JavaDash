<?php
require('inc/functions.php');

$email = $_GET['email'];
if($email == null)
{
	echo "no email address";
	exit;	
}

$user = findUserByEmail($email);
if($user != null)
{
	$sql = "UPDATE users SET enable_email_use=0 WHERE id={$user->id}";
	//debug($sql);
	dbUpdate($sql);
	echo "ok"
}
else
	echo "fail";

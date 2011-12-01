<?php
require('inc/functions.php');
if (isset($_POST['deviceid'])) {
	// core passed params we care about
	$deviceid = $_POST['deviceid'];
	$name = isset($_POST['name'])?$_POST['name']:"";
	$email = isset($_POST['email'])?$_POST['email']:"";
	$enable_email_use = isset($_POST['enable_email_use'])?$_POST['enable_email_use']:0;
	$platform = isset($_POST['platform'])?$_POST['platform']:"";
	$fbid = isset($_POST['fbid'])?$_POST['fbid']:0;
	
/*	
	debug($deviceid);
	debug($name);
	debug($email);
	debug($enable_email_use);
	debug($platform);
	debug($fbid);
*/	
	
} else {
	// no device id
	echo "no device id";
	exit;
}

$user = findUserByDeviceID($deviceid,$name,$email,$enable_email_use,$platform,$fbid);
if($user != null)
{
	$sql = "UPDATE users SET name=\"{$name}\", email=\"{$email}\", enable_email_use=\"{$enable_email_use}\", fb_id=\"{$fbid}\"  WHERE id={$user->id}";
	if(dbUpdate($sql))
	return "ok";
	else
	return "fail";
}
	
?>
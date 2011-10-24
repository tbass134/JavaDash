<?php
require('inc/functions.php');
if ( isset($_GET['deviceid']) || $deviceid != "(null)") {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$name = $_GET['name'];
	$email = $_GET['email'];
	$enable_email_use = $_GET['enable_email'];
	$fbid = $_GET['fbid'];
	
} else {
	// no device id
	exit;
}

$user = findUserByDeviceID($deviceid);
$sql = "UPDATE users SET name=\"{$name}\", email=\"{$email}\", enable_email_use=\"{$enable_email_use}\", fb_id=\"($fbid}\"  WHERE id={$user->id}";
if(dbUpdate($sql))
	return "ok";
else
	return "fail";
	
?>
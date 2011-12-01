<?php

require('inc/functions.php');


if ( isset($_GET['deviceid']) || $deviceid != "(null)") {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$name = $_GET['name'];
	$email = $_GET['email'];
	$enable_email_use = $_GET['enable_email'];
	$platform = $_GET['platform'];
	$fb_id = $_GET['fb'];
} else {
	// no device id
	exit;
}

if($deviceid == "(null)")
	exit;
$user = findUserByDeviceID($deviceid,$name,$email,$platform);

$sql = "UPDATE users SET fb_id=$fb_id WHERE id={$user->id}";
dbUpdate($sql);
debug($sql);
echo "ok"

?>
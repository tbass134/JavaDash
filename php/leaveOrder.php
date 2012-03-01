<?php
require('inc/functions.php');
require_once 'inc/urbanairship/urbanairship.php';
if (isset($_GET['deviceid'])) {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$run_id = $_GET['run_id'];

	$user = findUserByDeviceID($deviceid);
	debug($user);
} else {
	 echo "no device id";
	exit;
}
//Get the idof the user
$user = findUserByDeviceID($deviceid);

?>
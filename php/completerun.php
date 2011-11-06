<?php
require('inc/functions.php');

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

$sql = "UPDATE runs SET completed=1 WHERE user_id={$user->id} AND id={$run_id}";
debug($sql);
dbUpdate($sql);
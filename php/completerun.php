<?php
require('inc/functions.php');

if (isset($_GET['deviceid'])) {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$run_id = $_GET['run_id'];

	$user = findUserByDeviceID($deviceid);
} else {
	// no device id
	exit;
}

$sql = "UPDATE run SET completed=1 WHERE user_id={$user->id} AND run_id={$run_id}";
dbUpdate($sql);
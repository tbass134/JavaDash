<?php
require('inc/functions.php');

if (isset($_POST['device_id'])) {
	// core passed params we care about
	$deviceid	= $_POST['device_id'];
	$run_id 	= $_POST['run_id'];
	$drink		= $_POST['order'];
	// set up the user
	$user = findUserByDeviceID($deviceid);
} else {
	// no device id
	echo "no device id";
	exit;
}

// see if they have an empty order
$sql = "SELECT id FROM orders WHERE user_id={$user->id} AND run_id={$run_id} AND drink=''";
$result = dbQuery($sql);
if (mysql_num_rows($result)) {
	$order = mysql_fetch_object($result);
	$sql = "UPDATE orders SET drink=\"{$drink}\" WHERE id={$order->id}";
	dbUpdate($sql);
} else {
	$sql = "INSERT INTO orders (user_id, drink, run_id) VALUES ({$user->id}, \"{$drink}\", {$run_id})";
	dbQuery($sql);
}
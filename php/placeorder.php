<?php
require('inc/functions.php');

if (isset($_GET['deviceid'])) {
	// core passed params we care about
	$deviceid	= $_GET['device_id'];
	$run_id 	= $_POST['run_id'];
	$drink		= $_POST['order'];

	// don't care about this
	$first_name = $_POST['first_name'];
	$last_name  = $_POST['last_name'];

	// set up the user
	$user = findUserByDeviceID($deviceid);
} else {
	// no device id
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
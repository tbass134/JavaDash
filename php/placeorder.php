<?php
require('inc/functions.php');
require_once 'inc/urbanairship/urbanairship.php';
if (isset($_POST['device_id'])) {
	// core passed params we care about
	$deviceid	= $_POST['device_id'];
	$run_id 	= $_POST['run_id'];
	$drink		= $_POST['order'];
	$updateOrder= $_POST['updateOrder'];
	// set up the user
	$user = findUserByDeviceID($deviceid);
	debug($user);
} else {
	// no device id
	echo "no device id";
	exit;
}


//Send a push to the runner saying there is an order
//Get the runner device id
$sql = "SELECT runs.id AS runs_id, users.* FROM runs LEFT JOIN users ON runs.user_id = users.id WHERE runs.id=$run_id ORDER BY runs.timestamp ASC LIMIT 0,1";

$result = dbQuery($sql);
while ($row = mysql_fetch_assoc($result)) {

	debug("deviceid = " . $row['deviceid']);
	$APP_MASTER_SECRET = 'D9RVBb5fRYaib0hJGz9L-g';
	$APP_KEY = 'V1IdApIgQ_WuhReygjVqBg';
	$TEST_DEVICE_TOKEN = '9b0e1a82e31b0c7e029c8fb46d2fa40673cfb73ccb76c112dfd0500ad449f639';
	$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
	
	
	if($updateOrder !="1")
	{
		$message = array('aps'=>array('alert'=>$user->name . " has placed an order"),'order'=>array('push_type'=>'notify runner','attendee'=>$user->name));
		$airship->push($message, $row['deviceid'], array('testTag'));	
	}
}

//if this is coming from the update Order page
debug("Update Order = " . $updateOrder);
if($updateOrder =="1")
{
	//Need to edit this
	//You should be able to add orders to the run, this just replaces it
	// see if they have an empty order
	
	$sql = "SELECT id FROM orders WHERE user_id={$user->id} AND run_id={$run_id} AND drink 	!=''";
	debug($sql);
	$result = dbQuery($sql);
	if (mysql_num_rows($result)) {
		$order = mysql_fetch_object($result);
		$sql = "UPDATE orders SET drink=\"{$drink}\" WHERE id={$order->id}";
		dbUpdate($sql);
	} else {
	
		$sql = "INSERT INTO orders (user_id, drink, run_id) VALUES ({$user->id}, \"{$drink}\", {$run_id})";
		dbQuery($sql);
	}
}
else
{
	//Just add iterator_apply
	$sql = "INSERT INTO orders (user_id, drink, run_id) VALUES ({$user->id}, \"{$drink}\", {$run_id})";
	dbQuery($sql);
}
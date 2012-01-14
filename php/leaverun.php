<?php

$_debug = 1;
require('inc/functions.php');
require_once 'inc/urbanairship/urbanairship.php';
if (isset($_GET['deviceid'])) {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$run_id = $_GET['run_id'];

	$user = findUserByDeviceID($deviceid);
	if($_debug)
		debug($user);
} else {
	 echo "no device id";
	exit;
}
//Get the idof the user
$user = findUserByDeviceID($deviceid);


$sql = "SELECT orders.user_id, orders.run_id FROM `orders` LEFT JOIN `runs` ON orders.run_id = runs.id WHERE orders.user_id = {$user->id} AND orders.run_id  = '$run_id' AND runs.completed =0";

if($_debug)
	debug($sql);
$result = dbQuery($sql);

if (mysql_num_rows($result) >0)
{ 
	while ($row = mysql_fetch_assoc($result)) {
	
		$user_id = $row['user_id'];
		$run_id = $row['run_id'];
		$sql = "DELETE FROM orders WHERE `user_id` =$user_id  AND `run_id`=$run_id"; 
		debug($sql);
		//dbUpdate($sql);
	}
	
	include 'inc/login.php';
	$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
	$runner = findUserByID($row['user_id']);
	
	$message = array('aps'=>array('alert'=>$user->name . " has left the run"),'order'=>array('push_type'=>'notify runner','attendee'=>$user->name));
	$airship->push($message, $runner->deviceid); //, array('testTag')	
}


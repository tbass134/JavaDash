<?php

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

//was SELECT orders.user_id, orders.run_id TH 011412
$sql = "SELECT orders.id AS order_id,orders.user_id AS order_user_id,orders.run_id AS orders_run_id, runs.id AS runs_id,runs.user_id AS runs_user_id FROM `orders` LEFT JOIN `runs` ON orders.run_id = runs.id WHERE orders.user_id = {$user->id} AND orders.run_id  = '$run_id' AND runs.completed =0";

if($_debug)
	debug($sql);
$result = dbQuery($sql);

if (mysql_num_rows($result) >0)
{ 
	while ($row = mysql_fetch_assoc($result)) {
	
		$order_user_id = $row['order_user_id'];
		$runs_id = $row['runs_id'];
		$runs_user_id = $row['runs_user_id'];
		
		$sql = "DELETE FROM orders WHERE `user_id` =$order_user_id  AND `run_id`=$runs_id";
		debug($sql);
		dbUpdate($sql);
	}
	
	//now get the user from runs_user_id	
	include 'inc/login.php';
	$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
	$runner = findUserByID($runs_user_id);
	if($_debug)
		debug($runner);
	
	try
	{
		$message = array('aps'=>array('alert'=>$user->name . " has left the run"),'order'=>array('push_type'=>'notify runner','attendee'=>$user->name));
		$airship->push($message, $runner->deviceid);
	}
	catch (Exception $e) {
	    debug('Caught exception: '.   $e->getMessage());
	}
}
$success = 1;
$jsonresult = array(
	"success" => $success,
);
echo json_encode($jsonresult);


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

$sql = "UPDATE runs SET completed=1 WHERE user_id={$user->id} AND id={$run_id}";
debug($sql);
dbUpdate($sql);

//Send push notifications to all users saying that run has been abandoned
//$sql = "SELECT * FROM `orders` WHERE `run_id` ={$run_id} AND `drink` != ''";

//Get the name of the Runner
$runner = findUserByDeviceID($deviceid);


$sql = "SELECT DISTINCT orders.user_id FROM `orders`
LEFT JOIN `runs` ON  orders.run_id = runs.id WHERE `run_id` ={$run_id} AND `drink` != '' AND runs.completed =1";
$result = dbQuery($sql);
include 'inc/login.php';
$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
while ($row = mysql_fetch_assoc($result)) {

	$user = findUserByDeviceID($row['user_id']);
	
	
	$message = array('aps'=>array('alert'=>$runner->name . " has canceled the order"),'order'=>array('push_type'=>'notify runner','attendee'=>$user->name));
	$airship->push($message, $user->deviceid); //, array('testTag')	
}



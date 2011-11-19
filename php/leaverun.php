<?php
require('inc/functions.php');
require_once 'inc/urbanairship/urbanairship.php';
if (isset($_GET['deviceid'])) {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$run_id = $_GET['run_id'];
	$remove_order = $_GET['remove_order'];

	$user = findUserByDeviceID($deviceid);
	debug($user);
} else {
	 echo "no device id";
	exit;
}
//Get the idof the user
$user = findUserByDeviceID($deviceid);


$sql = "SELECT orders.id AS orders_id, runs.user_id AS runner_user FROM `orders` LEFT JOIN `runs` ON orders.run_id = runs.id WHERE orders.user_id = {$user->id} AND orders.run_id  = $run_id AND orders.drink != '' AND runs.completed =0";

$result = dbQuery($sql);
 
while ($row = mysql_fetch_assoc($result)) {

	$runner_id = $row['runner_user_id'];
	debug("update Order");
	$sql = "UPDATE orders SET drink="" WHERE id={$row['orders_id']"; 
	debug($sql);
	dbUpdate($sql);
}

include 'inc/login.php';
$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
$runner = findUserByID($row['user_id']);

$message = array('aps'=>array('alert'=>$user->name . " has canceled the order"),'order'=>array('push_type'=>'notify runner','attendee'=>$user->name));
$airship->push($message, $runner->deviceid); //, array('testTag')	



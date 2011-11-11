<?php
require('inc/functions.php');
require('Postmark/sendEmail.php');
require_once 'inc/urbanairship/urbanairship.php';
if (isset($_POST['device_id'])) {
	// core passed params we care about
	$deviceid	= $_POST['device_id'];
	$run_id 	= $_POST['run_id'];
	$drink		= $_POST['order'];
	
if (isset($_POST['updateOrder']))
{
	$updateOrder= $_POST['updateOrder'];
	$order_id = $_POST['order_id'];
}
else
	$updateOrder = "0";
	
	// set up the user
	$user = findUserByDeviceID($deviceid);
	//debug($user);
} else {
	// no device id
	echo "no device id";
	exit;
}

debug($updateOrder);

//Send a push to the runner saying there is an order
//Get the runner device id
$sql = "SELECT runs.id AS runs_id, users.* FROM runs LEFT JOIN users ON runs.user_id = users.id WHERE runs.id=$run_id ORDER BY runs.timestamp ASC LIMIT 0,1";
debug($sql);
$result = dbQuery($sql);
debug($result);

include 'inc/login.php';
$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
while ($row = mysql_fetch_assoc($result)) {

try
{
	if($updateOrder !="1")
	{
		$message = array('aps'=>array('alert'=>$user->name . " has placed an order"),'order'=>array('push_type'=>'notify runner','attendee'=>$user->name));
		$airship->push($message, $row['deviceid']); //, array('testTag')	
	}
}
catch (Exception $e) {
    error_log('Caught exception: ',  $e->getMessage(), "\n");
}
	//Send the runner the email
	$runner = findUserByDeviceID($row['deviceid']);
	debug($runner);
	//If Email is enabled, email the order to the user
	
	if($runner->enable_email_use && isset($drink))
	{
		$subject = $user->name . " has placed an order using Java Dash";
		$subnav = "";
		$body = $drink;
		$userName = $user->name;
		$userEmail = $runner->email;
		if($subject != null && $subnav != null && $body != null && $userName != null && $userEmail != null)
		{
			if($updateOrder !="1")
			{
				echo "Sending Email";
				sendPostmarkEmail($subject,$subnav,$body,$userEmail,$userName);
			}
		}
	}
	

}
//if this is coming from the update Order page

//escape the json string
$drink =mysql_real_escape_string($drink);
//debug("Update Order = " . $updateOrder);
if($updateOrder =="1")
{
	//Need to edit this
	//You should be able to add orders to the run, this just replaces it
	// see if they have an empty order
	
	//echo "Drink = " . $drink . "\n";
	/*
	$sql = "SELECT id FROM orders WHERE user_id={$user->id} AND run_id={$run_id} AND drink 	!=''";
	debug($sql);
	$result = dbQuery($sql);
	if (mysql_num_rows($result)) {
		$order = mysql_fetch_object($result);
		*/
		debug("update Order");
		$sql = "UPDATE orders SET drink=\"{$drink}\" WHERE id={$order_id}";
		debug($sql);
		dbUpdate($sql);
		/*
	} else {
		$sql = "INSERT INTO orders (user_id, drink, run_id) VALUES ({$user->deviceid}, \"{$drink}\", {$run_id})";
		debug($sql);
		dbQuery($sql);
	}
	*/
}
else
{
	//debug($user);
	$sql = "SELECT id FROM orders WHERE user_id={$user->id} AND run_id={$run_id} AND drink 	=''";
	debug($sql);
	$result = dbQuery($sql);
	if (mysql_num_rows($result)) {
		$order = mysql_fetch_object($result);
		//$sql = "UPDATE orders SET drink=\"{$drink}\" WHERE id={$run_id}";
		$sql = "UPDATE orders SET drink=\"{$drink}\" WHERE id={$order->id}";
		//debug($sql);
		dbUpdate($sql);
	} 
	else
	{
		//Just add iterator_apply
		$sql = "INSERT INTO orders (user_id, drink, run_id) VALUES ({$user->id}, \"{$drink}\", {$run_id})";
		//debug($sql);
		dbQuery($sql);
	}
}
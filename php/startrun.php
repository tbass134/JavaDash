<?php
require('inc/functions.php');
require_once 'inc/urbanairship/urbanairship.php';

$device_token 		= $_POST['device_tokens'];
$push_type	  		= $_POST['push_type'];

$selected_date		= $_POST['selected_date'];
$selected_name 		= $_POST['selected_name'];
$selected_address 	= $_POST['selected_address'];
$selected_url 		= $_POST['selected_url'];
$selected_yelp_id	= $_POST['selected_yelp_id'];
//$isLocal			= $_POST['isLocal'];

$device_tokens_array = explode(",",$device_token);


//echo 'selected_date = ' .$selected_date;
//Runner info
$runner_first_name = $_POST['first_name'];
$runner_last_name = $_POST['last_name'];
$runner_device_id = $_POST['deviceid'];
//echo 'runner_device_id = ' .$runner_device_id;
debug($runner_device_id);

if (isset($_POST['deviceid'])) {
	// core passed params we care about
	$deviceid = $runner_device_id;
	$attendees = $device_tokens_array;
	$location_yelp_id = $selected_yelp_id;
	$timestamp = $selected_date;

	// other stuff we might not care about
	$location_name = $selected_name;
	$location_address = $selected_address;
	$location_url 	=$selected_url;

	// find the user
	$user = findUserByDeviceID($deviceid);
} else {
	// no device id
	$result = array(
		"response" => 'No device id',
	);
	echo json_encode($result);
	exit;
}


echo $device_tokens_array;
// Your testing data
include 'inc/login.php';
$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);


$runner_name = $runner_first_name." ".$runner_last_name;
$message = array('aps'=>array('alert'=>$runner_name .  ' wants to know if you want some coffee!'),'order'=>array('push_type'=>$push_type,'runner'=>$runner_name));

try
{
	$airship->push($message, $device_tokens_array);
}catch (Exception $e) {
    error_log('Caught exception: ',  $e->getMessage(), "\n");
    
}

// set up a run

// 2 set up the run
// 2a check location (based off of yelpid)
$sql = "SELECT id FROM locations WHERE yelp_id='$location_yelp_id'";
$result = dbQuery($sql);
if (mysql_num_rows($result)) {
	// get location
	$location_id = mysql_fetch_object($result);
	$location_id = $location_id->id;
} else {
	// insert location
	$sql = "INSERT INTO locations (name, address, image,  yelp_id) VALUES (\"{$location_name}\", \"{$location_address}\", \"{$location_url}\",  \"{$location_yelp_id}\")";
	dbQuery($sql);
	$location_id = mysql_insert_id();
	debug("inserted location");
}
// 2b set up run
$sql = "INSERT INTO runs (location_id, timestamp, user_id) VALUES ('$location_id', '$timestamp', '$user->id')";
dbQuery($sql);
$run_id = mysql_insert_id();
//debug("1 record added|Last inserted record has id of {$run_id}");

// 3 insert attendees (as users and then orders)
foreach($attendees as $attendee_device_id) {
	// see if the attendee is a user
	$attendee = findUserByDeviceID($attendee_device_id);
	$sql = "INSERT INTO orders (user_id, run_id) VALUES ('{$attendee->id}', {$run_id})";
	dbQuery($sql);
	
	debug($attendee);
	
	if($attendee->enable_email_use)
	{
		echo "send Email";
		sendEmail($user->email,$runner_name .  ' wants to know if you want some coffee!');
	}
	
	
}

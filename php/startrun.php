<?php
require('inc/functions.php');
require_once 'inc/urbanairship/urbanairship.php';

$device_token 		= $_POST['device_tokens'];
$push_type	  		= $_POST['push_type'];

$selected_date		= $_POST['selected_date'];
$selected_name 		= $_POST['selected_name'];
$selected_address 	= $_POST['selected_address'];
$selected_yelp_id	= $_POST['selected_yelp_id'];
$isLocal			= $_POST['isLocal'];

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
/*
// Your testing data
$APP_MASTER_SECRET = 'D9RVBb5fRYaib0hJGz9L-g';
$APP_KEY = 'V1IdApIgQ_WuhReygjVqBg';
$TEST_DEVICE_TOKEN = '8b5149b911a2bc2c3bf55e2796da3e1e71ff4d89237ea3f9c0940f9ee6b0762f';
//$TEST_DEVICE_TOKEN = 'B76CC8AE0270C31E99112E8EC823711A41BEC4E508A9D74B76EDCC290A5B7F45';

if(!$isLocal) {
	$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
	//$airship->register($device_tokens_array[0], 'testTag');
	$message = array('aps'=>array('alert'=>'hello'),'order'=>array('push_type'=>$push_type,'runner'=>$runner_first_name." ".$runner_last_name));
	$airship->push($message, $device_tokens_array, array('testTag'));
}
*/
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
	$sql = "INSERT INTO locations (name, address, yelp_id) VALUES (\"{$location_name}\", \"{$location_address}\", \"{$location_yelp_id}\")";
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
}

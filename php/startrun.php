<?php

require('inc/functions.php');
require ('inc/login.php');
require('Postmark/sendEmail.php');
require_once 'inc/urbanairship/urbanairship.php';
//require_once 'inc/urbanairship2/urbanairship.php';



$device_token 		= $_POST['device_tokens'];
$push_type	  		= $_POST['push_type'];

$selected_date		= $_POST['selected_date'];
$selected_name 		= $_POST['selected_name'];
$selected_address 	= $_POST['selected_address'];
$selected_url 		= $_POST['selected_url'];
$selected_yelp_id	= $_POST['selected_yelp_id'];
$date_added			= $_POST['date_added'];
//$isLocal			= $_POST['isLocal'];

$device_tokens_array = explode(",",$device_token);

$success = 0;



//echo 'selected_date = ' .$selected_date;
//Runner info
$runner_first_name = $_POST['first_name'];
$runner_last_name = $_POST['last_name'];
$runner_device_id = $_POST['deviceid'];

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
	if($_debug)
		debug($user);
} else {
	// no device id
	$success = 0;
	$result = array(
		"response" => 'No device id',
	);
	echo json_encode($result);
	exit;
}


while (($pos = array_search($runner_device_id, $device_tokens_array)) !== false) {
    unset($device_tokens_array[$pos]);
}

// now check if that user has any open runs TH added 012112
$sql = "SELECT * FROM runs WHERE user_id={$user->id} AND completed=0 ORDER BY date_added DESC";
if($_debug)
	debug($sql);
	
$result = dbQuery($sql);
while ($row = mysql_fetch_assoc($result)) {
	$sql = "UPDATE runs SET completed=1 WHERE id=".$row['id'];
	if($_debug)
		debug($sql);
	dbUpdate($sql);	
}
if($_debug)
	debug("device_tokens_array count = ".count($device_tokens_array));


$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);


$runner_name = $runner_first_name." ".$runner_last_name;
$message = array('aps'=>array('alert'=>$runner_name .  ' wants to know if you want some coffee!'),'order'=>array('push_type'=>$push_type,'runner'=>$runner_name));


try
{
	if($_debug)
		echo "try push";
		
	$airship->push($message, $device_tokens_array);
}
catch (Exception $e) {

if($_debug)
	echo "Failed push";
     error_log('Caught exception: '.  $e->getMessage());
    }

// set up a run

// 2 set up the run
// 2a check location (based off of yelpid)
$sql = "SELECT id FROM locations WHERE yelp_id='$location_yelp_id'";
if($_debug)
	debug($sql);
$result = dbQuery($sql);
if (mysql_num_rows($result)) {
	// get location
	$location_id = mysql_fetch_object($result);
	$location_id = $location_id->id;
} else {
	// insert location
	$sql = "INSERT INTO locations (name, address, image,  yelp_id) VALUES (\"{$location_name}\", \"{$location_address}\", \"{$location_url}\",  \"{$location_yelp_id}\")";
	dbQuery($sql);
	if($_debug)
		debug($sql);
	$location_id = mysql_insert_id();
	if($_debug)
		debug("inserted location");
}
// 2b set up run
$sql = "INSERT INTO runs (location_id, timestamp, date_added, user_id) VALUES ('$location_id', '$timestamp','$date_added', '$user->id')";
dbQuery($sql);
if($_debug)
	debug($sql);
$run_id = mysql_insert_id();

// 3 insert attendees (as users and then orders)
foreach($attendees as $attendee_device_id) {
	// see if the attendee is a user
	if($_debug)
		echo("attendee_device_id " . $attendee_device_id);
	$attendee = findUserByDeviceID($attendee_device_id);
	
	
	if($_debug)
		debug($attendee);
	
	$sql = "INSERT INTO orders (user_id, run_id) VALUES ('{$attendee->id}', {$run_id})";
	if($_debug)
		debug($sql);
		
	dbQuery($sql);
	
	if($_debug)
		debug($attendee);
		
	if($attendee->enable_email_use)
	{
		if($_debug)
			debug("Sending Email");
		
			$subject = $runner_name .  ' wants to know if you want some coffee!';
			$subnav = "JavaDash is a great new way to order coffee for you and your friends!";
			$body = "";
			sendPostmarkEmail($subject,$subnav,$body,$attendee->email,$attendee->name);
	}	
}

$success = 1;
$result = array(
	"success" => $success,
);
echo json_encode($result);

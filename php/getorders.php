<?php
require('inc/functions.php');

if (isset($_GET['deviceid'])) {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$name = $_GET['name'];
} else {
	// no device id
	exit;
}

// check if we're a runner or an attendee
// runners will have their user_id in an open run
// all attendees are users

// first get their user_id
$user = findUserByDeviceID($deviceid,$name);
$is_runner = 0;

// now check if that user has any open runs
$sql = "SELECT * FROM runs WHERE user_id={$user->id} ORDER BY timestamp DESC LIMIT 0,1";
$result = dbQuery($sql);
$data = array();

if (mysql_num_rows($result)) {
	$is_runner = 1;
}
$data['run']['is_runner'] = $is_runner;

if ($is_runner) { // they are the runner, let's show them the run/location and the orders they need to pick up
	// get the next available run
	$sql = "SELECT runs.*, locations.* FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id WHERE runs.user_id={$user->id} AND completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
	$result = dbQuery($sql);
	$row = mysql_fetch_assoc($result);

	$run_id = $row['id'];
	$data["run"]['id'] = $row['id'];
	$data["run"]['location_id'] = $row['location_id'];
	$data["run"]['timestamp'] = $row['timestamp'];
	$data["run"]['user_id'] = $row['user_id'];
	$data["run"]['completed'] = $row['completed'];

	$data["run"]["location"]['id'] = $row['id'];
	$data["run"]["location"]['name'] = $row['name'];
	$data["run"]["location"]['address'] = $row['address'];
	$data["run"]["location"]['yelp_id'] = $row['yelp_id'];
	
/*	*/
	// get the orders for the run
	//$sql = "SELECT * FROM orders WHERE run_id={$run_id} AND drink != '';";
	$sql ="SELECT * FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE run_id='" .$row['id'] . "' AND drink != '';";
	$result = dbQuery($sql);
	while ($row = mysql_fetch_assoc($result)) {
	
		$row['drink'] = json_decode($row['drink']);
		$rows[] = $row;
	}

	if($rows != null)
		$data["run"]['orders'] = $rows;
	else
		$data["run"]['orders'] = 0;
/*	*/

} else { // they are an attendee, let's show them where the runner is going
	$sql = "SELECT runs.*, locations.* FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id WHERE orders.user_id={$user->id} AND runs.completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
	/*
	list($result) = dbFetch($sql);
	// return run info to phone
	if($result != null)
		echo json_encode($result);
	*/
	
	$result = dbQuery($sql);
	if($result == null)
		return;
	
	$row = mysql_fetch_assoc($result);

	$data["run"]['id'] = $row['id'];
	$data["run"]['location_id'] = $row['location_id'];
	$data["run"]['timestamp'] = $row['timestamp'];
	$data["run"]['user_id'] = $row['user_id'];
	$data["run"]['completed'] = $row['completed'];

	$data["run"]["location"]['id'] = $row['id'];
	$data["run"]["location"]['name'] = $row['name'];
	$data["run"]["location"]['address'] = $row['address'];
	$data["run"]["location"]['yelp_id'] = $row['yelp_id'];

	$sql ="SELECT * FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE run_id='" .$row['id'] . "' AND drink != '';";
	$result = dbQuery($sql);
	while ($row = mysql_fetch_assoc($result)) {
		$row['drink'] = json_decode($row['drink']);
		
		$rows[] = $row;
	}
	if($rows != null)
		$data["run"]['orders'] = $rows;
		

}

echo json_encode($data);
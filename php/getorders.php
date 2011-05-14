<?php
require('inc/functions.php');

if ( isset($_GET['deviceid']) || $deviceid != "(null)") {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	$name = $_GET['name'];
	$platform = $_GET['platform'];
} else {
	// no device id
	exit;
}

if($deviceid == "(null)")
	exit;
// check if we're a runner or an attendee
// runners will have their user_id in an open run
// all attendees are users

// first get their user_id
$user = findUserByDeviceID($deviceid,$name,$platform);
$is_runner = 0;

// now check if that user has any open runs
$sql = "SELECT * FROM runs WHERE user_id={$user->id} ORDER BY timestamp DESC LIMIT 0,1";
//debug($sql);
$result = dbQuery($sql);
$data = array();

if (mysql_num_rows($result)) {
	$is_runner = 1;
}
$data['run']['is_runner'] = $is_runner;

if ($is_runner) { // they are the runner, let's show them the run/location and the orders they need to pick up
	// get the next available run
	
/*	
	$sql = "SELECT runs.*, locations.* FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id WHERE runs.user_id={$user->id} AND completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
*/	
	
	//This also returns the runners info. need to test to be sure this is working properly
	$sql = "SELECT runs.*, locations.*, users.name AS user_name FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id LEFT JOIN users ON runs.user_id=users.id WHERE runs.user_id={$user->id} AND completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
	
	debug($sql);
	$result = dbQuery($sql);
	$row = mysql_fetch_assoc($result);
	if( $row['id'] != null)
	{
		$run_id = $row['id'];
		$data["run"]['id'] = $row['id'];
		$data["run"]['location_id'] = $row['location_id'];
		$data["run"]['timestamp'] = $row['timestamp'];
		$data["run"]['user_id'] = $row['user_id'];
		$data["run"]['user_name'] = $row['user_name'];
		$data["run"]['completed'] = $row['completed'];
	
		$data["run"]["location"]['id'] = $row['id'];
		$data["run"]["location"]['name'] = $row['name'];
		$data["run"]["location"]['address'] = $row['address'];
		$data["run"]["location"]['yelp_id'] = $row['yelp_id'];
		
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
		
	}

} else { // they are an attendee, let's show them where the runner is going

	/*
	$sql = "SELECT runs.*, locations.*, users.name FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id LEFT JOIN users ON orders.user_id=users.id  WHERE orders.user_id={$user->id} AND runs.completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
	*/	

	//This also returns the runners info. need to test to be sure this is working properly
	$sql = "SELECT runs.*, locations.*, users.name AS user_name FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id LEFT JOIN users ON runs.user_id=users.id  WHERE orders.user_id={$user->id} AND runs.completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
	
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
	
	if($row != null)
	{
		$data["run"]['id'] = $row['id'];
		$data["run"]['location_id'] = $row['location_id'];
		$data["run"]['timestamp'] = $row['timestamp'];
		$data["run"]['user_id'] = $row['user_id'];
		$data["run"]['user_name'] = $row['user_name'];
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

}

echo json_encode($data);
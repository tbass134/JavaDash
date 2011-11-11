<?php
require('inc/functions.php');

if ( isset($_GET['deviceid']) || $deviceid != "(null)") {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
	//$name = $_GET['name'];
	//$email = $_GET['email'];
	//$enable_email_use = $_GET['enable_email'];
	
	//$platform = $_GET['platform'];
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
//$user = findUserByDeviceID($deviceid,$name,$email,$enable_email_use,$platform);
$user = findUserByDeviceID($deviceid);
//debug($user);
$is_runner = 0;

// now check if that user has any open runs
$sql = "SELECT * FROM runs WHERE user_id={$user->id} AND completed=0 ORDER BY timestamp DESC LIMIT 0,1";
$result = dbQuery($sql);
$data = array();
//debug($sql);
if (mysql_num_rows($result)) {
	$is_runner = 1;
}
$data['run']['is_runner'] = $is_runner;

if ($is_runner) { 
/*	
	$sql = "SELECT runs.*, locations.*, users.name AS user_name FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id LEFT JOIN users ON runs.user_id=users.id WHERE runs.user_id={$user->id} AND completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
*/	
	$sql = "SELECT runs.id AS runs_id,runs.timestamp,runs.user_id,runs.completed,locations.id AS locations_id,locations.name AS location_name,locations.address,locations.image,locations.yelp_id,orders.drink,users.name AS user_name,users.deviceid,users.platform,users.purchased FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id LEFT JOIN users ON runs.user_id=users.id WHERE runs.user_id={$user->id} AND completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1";
	
	//debug($sql);
	$result = dbQuery($sql);
	$row = mysql_fetch_assoc($result);
	//debug($row);
	if( $row['runs_id'] != null)
	{
		$run_id = $row['runs_id'];
		$data["run"]['id'] = $row['runs_id'];
		$data["run"]['timestamp'] = $row['timestamp'];
		$data["run"]['user_id'] = $row['user_id'];
		$data["run"]['purchased'] = $row['purchased'];
		$data["run"]['user_name'] = $row['user_name'];
		$data["run"]['completed'] = $row['completed'];
	
		$data["run"]["location"]['id'] = $row['locations_id'];
		$data["run"]["location"]['name'] = $row['location_name'];
		$data["run"]["location"]['address'] = $row['address'];
		$data["run"]["location"]['image'] = $row['image'];
		$data["run"]["location"]['yelp_id'] = $row['yelp_id'];
		
		// get the orders for the run
		//$sql = "SELECT * FROM orders WHERE run_id={$run_id} AND drink != '';";
		//$sql ="SELECT * FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE run_id='" .$row['runs_id'] . "' AND drink != '';";
		
		$sql = "SELECT orders.id AS order_id,user_id,drink,run_id, deviceid,name,email,enable_email_use,platform FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE run_id='".$row['runs_id']."' AND drink != ''";
		
		
		//debug($sql);
		$result = dbQuery($sql);
		while ($row = mysql_fetch_assoc($result)) {
		
		//print_r($row);
			//Since you can add mutiple orders, need to split the string
			// by "json=", then decode that string and make it seperate arrays
			
			//If mutiple orders insde of dict...
			if(strstr($row['drink'], 'json='))
			{
				$tmp_array = explode("json=",$row['drink']);
				if(count($tmp_array)>0)
				{
					for($i=0;$i<count($tmp_array);$i++)
					{
						if($tmp_array[$i] != null)
						{
							$order[] = json_decode($tmp_array[$i]); 
						}
					}
					
				}
				$row['drink'] = $order;
				$rows[] = $row;
			}
			else
			{
				//was TH 060211
				//Only 1 order
				$row['drink'] = json_decode($row['drink']);
				$rows[] = $row;
			}
					}
	
		if($rows != null)
		{
			print_r($row);
			$data["run"]['orders'] = $rows;
		}
		
	}

} else { 
	
	$sql = "SELECT runs.id AS runs_id, runs.timestamp, runs.user_id, runs.completed, locations.id AS locations_id, locations.name AS location_name, locations.address,locations.image, locations.yelp_id, orders.drink, users.name AS user_name, users.deviceid, users.platform,users.purchased FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id LEFT JOIN users ON runs.user_id=users.id  WHERE orders.user_id={$user->id} AND runs.completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1;";
	
	
/*	
	$sql = "SELECT runs.id AS runs_id,runs.timestamp,runs.user_id,runs.completed,locations.id AS locations_id,locations.name AS location_name,locations.address,locations.yelp_id,orders.drink,users.name AS user_name,users.deviceid,users.platform FROM orders LEFT JOIN runs ON orders.run_id=runs.id LEFT JOIN locations ON runs.location_id=locations.id LEFT JOIN users ON runs.user_id=users.id WHERE runs.user_id={$user->id} AND completed=0 ORDER BY runs.timestamp ASC LIMIT 0,1";
*/	
	
	//debug($sql);
	$result = dbQuery($sql);
	
	if($result == null)
		return;
	
	$row = mysql_fetch_assoc($result);
	//debug($row);
	
	if($row != null)
	{
	
		$data["run"]['id'] = $row['runs_id'];
		$data["run"]['timestamp'] = $row['timestamp'];
		$data["run"]['user_id'] = $row['user_id'];
		$data["run"]['purchased'] = $row['purchased'];
		$data["run"]['user_name'] = $row['user_name'];
		$data["run"]['completed'] = $row['completed'];
	
		$data["run"]["location"]['id'] = $row['locations_id'];
		$data["run"]["location"]['name'] = $row['location_name'];
		$data["run"]["location"]['address'] = $row['address'];
		$data["run"]["location"]['image'] = $row['image'];
		
		$data["run"]["location"]['yelp_id'] = $row['yelp_id'];
	
		//$sql ="SELECT * FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE run_id='" .$row['runs_id'] . "' AND drink != '';";
		$sql = "SELECT orders.id AS order_id,user_id,drink,run_id, deviceid,name,email,enable_email_use,platform FROM orders LEFT JOIN users ON orders.user_id = users.id WHERE run_id='".$row['runs_id']."' AND drink != ''";
		$result = dbQuery($sql);
		while ($row = mysql_fetch_assoc($result)) {
		
			//Since you can add mutiple orders, need to split the string
			// by "json=", then decode that string and make it seperate arrays
			//If mutiple orders insde of dict...
			if(strstr($row['drink'], 'json='))
			{
				$tmp_array = explode("json=",$row['drink']);
				if(count($tmp_array)>0)
				{
					for($i=0;$i<count($tmp_array);$i++)
					{
						if($tmp_array[$i] != null)
						{
							$order[] = json_decode($tmp_array[$i]); 
						}
					}
					
				}
				$row['drink'] = $order;
				$rows[] = $row;
			}
			else
			{
				//was TH 060211
				//Only 1 order
				$row['drink'] = json_decode($row['drink']);
				
				
				//Since we are showing info for the attendee, only show their own orders
				//debug($user->deviceid);
				//debug($row['deviceid']);
				//echo "\n";
				if($user->deviceid == $row['deviceid'])
					$rows[] = $row;
			}
			
			
		}
			if($rows != null)
			{
				$data["run"]['orders'] = $rows;	
				
				//debug("user id" . $user->deviceid);
				//debug($data["run"]['orders'][0]['deviceid']);		
			}
		}

}

echo json_encode($data);
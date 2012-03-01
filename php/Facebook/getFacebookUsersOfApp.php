<?php
	require('../inc/functions.php');
	/*
	if (isset($_POST['device_tokens']))
	{
		$device_token 		= $_POST['device_tokens'];
		$device_tokens_array = explode(",",$device_token);
		
		$result = array_unique($device_tokens_array);
		foreach($result as $attendee_device_id) {
		// see if the attendee is a user
			$attendee = findUserByDeviceID($attendee_device_id);
			
			if($attendee->fb_id >0)
			{
				$rows[] = $attendee;
			}
		}
		echo json_encode($rows);
	}
	*/
	$data = array();
	$sql = "SELECT * FROM users WHERE fb_id != 0";
	$result = dbQuery($sql);
	if (mysql_num_rows($result)) {
	
		while ($row = mysql_fetch_assoc($result)) {
			//debug($row);
			$rows[] = $row;
		}
		echo json_encode($rows);
	}
	else
	{
		$success = 0;
		$arr = array('success' => $success);
		echo json_encode($arr);
	}

?>
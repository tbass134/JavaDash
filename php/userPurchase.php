<?php

require('inc/functions.php');

if ( isset($_GET['deviceid']) || $deviceid != "(null)") {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
} else {
	// no device id
	$success = 0;
	$arr = array('success' => $success);
	echo json_encode($arr);
	exit;
}

$user = findUserByDeviceID($deviceid);

$sql = "SELECT * FROM users WHERE id={$user->id}";
$result = dbQuery($sql);
if (mysql_num_rows($result)) {
	
	$sql = "UPDATE users SET purchased=1 WHERE id={$user->id}";
	if(dbUpdate($sql))
		$success = 1;
		$arr = array('success' => $success);
		echo json_encode($arr);
}
else
	$success = 0;
	$arr = array('success' => $success);
	echo json_encode($arr);
?>
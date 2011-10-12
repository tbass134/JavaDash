<?php

require('inc/functions.php');

if ( isset($_GET['deviceid']) || $deviceid != "(null)") {
	// core passed params we care about
	$deviceid = $_GET['deviceid'];
} else {
	// no device id
	echo "0";
	exit;
}

$user = findUserByDeviceID($deviceid);

$sql = "SELECT * FROM users WHERE id={$user->id}";
$result = dbQuery($sql);
if (mysql_num_rows($result)) {
	
	$sql = "UPDATE users SET purchased=1 WHERE id={$user->id}";
	if(dbUpdate($sql))
		echo "1";
}
else
	echo "0";
?>
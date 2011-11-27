<?php
echo "Server" . $_SERVER['SERVER_NAME'];
require('inc/functions.php');
echo "running Clean up";
$sql = "SELECT * FROM `runs` WHERE `timestamp` <= NOW() AND completed=0";
debug($sql);
$result = dbQuery($sql);
while ($row = mysql_fetch_assoc($result)) {
	$sql = "UPDATE runs SET completed=1 WHERE user_id={$row['user_id']}";	debug($sql);
	dbUpdate($sql);
}
echo 'End clean up';

?>
<?php
	//this wil go through all the users of JavaDash and check if the fb_id value is filled.
	//If so, the user has signed on with Facebook
	
	require('../inc/functions.php');
	$sql = "SELECT * FROM `users` WHERE fb_id >0";
	$result = dbQuery($sql);
	while ($row = mysql_fetch_assoc($result)) {
	
		$rows[] = $row;
	}
	
	echo json_encode($rows);

?>
<?php

	require('inc/functions.php');
	require_once 'inc/urbanairship/urbanairship.php';
	
	
	$APP_MASTER_SECRET = 'D9RVBb5fRYaib0hJGz9L-g';
	$APP_KEY = 'V1IdApIgQ_WuhReygjVqBg';
	$TEST_DEVICE_TOKEN = '9b0e1a82e31b0c7e029c8fb46d2fa40673cfb73ccb76c112dfd0500ad449f639';
	$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
	
	//$airship->register($TEST_DEVICE_TOKEN, 'My Alias');

	
	// Test get device token info
	print_r($airship->get_device_token_info($TEST_DEVICE_TOKEN));
	
	// Test push
	$message = array('aps'=>array('alert'=>'hello'),'order'=>array('push_type'=>$push_type,'runner'=>$runner_first_name." ".$runner_last_name));
	$airship->push($message, $TEST_DEVICE_TOKEN, null);
?>
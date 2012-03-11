<?php

require_once 'urbanairship.php';

// Your testing data
$APP_MASTER_SECRET = 'rRd13cYSTGSIQr3gmR23Zg';
$APP_KEY = '4fh8xUNQT1apEm9hSPoo7A';
$TEST_DEVICE_TOKEN = '9AF99354B2B480CFFFB96721382540FB7811D2BE6134396F6C8292169F7F3828';

// Create Airship object
$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);

// Test feedback
/*
$time = new DateTime('now', new DateTimeZone('UTC'));
$time->modify('-1 day');
echo $time->format('c') . '\n';
print_r($airship->feedback($time));
echo "\n";
*/
// Test register

$airship->register($TEST_DEVICE_TOKEN, 'testTag');
/*
// Test get device token info
print_r($airship->get_device_token_info($TEST_DEVICE_TOKEN));
echo "\n";
// Test get device tokens
*/
/*
$tokens = $airship->get_device_tokens();
echo 'Device tokens count is:' . count($tokens);
foreach ($tokens as $item) {
    var_dump($item);
}
*/

// Test deregister

//$airship->deregister($TEST_DEVICE_TOKEN);


// Test push

$message = array('aps'=>array('alert'=>'hello'));
$airship->push($message, array($TEST_DEVICE_TOKEN));

// Test broadcast

//$broadcast_message = array('aps'=>array('alert'=>'hello to all'));
//$airship->broadcast($broadcast_message, array($TEST_DEVICE_TOKEN));

?>

<?php
require('inc/functions.php');
if (isset($_GET['order_id'])) {
	$order_id = $_GET['order_id'];
	$sql = "DELETE from orders WHERE id=".$order_id;
	dbUpdate($sql);
	$success = 1;
	$arr = array('success' => $success);
	echo json_encode($arr);
}
?>
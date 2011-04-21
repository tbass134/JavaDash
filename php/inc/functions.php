<?php
/**
 * @author Jason Lawton
 */

//if($_SERVER['SERVER_NAME'] == "javadash.com") {
//	defined('DB_SERVER') ? null : define('DB_SERVER', '');
//	defined('DB_USER')   ? null : define('DB_USER',   '');
//	defined('DB_PASS')   ? null : define('DB_PASS',   '');
//	defined('DB_NAME')   ? null : define('DB_NAME',   '');
//} else {
//	defined('DB_SERVER') ? null : define('DB_SERVER', 'localhost');
//	defined('DB_USER')   ? null : define('DB_USER',   'root');
//	defined('DB_PASS')   ? null : define('DB_PASS',   'root');
//	defined('DB_NAME')   ? null : define('DB_NAME',   'javadash');
//}

// connect to the db
dbConnect();

/**
 * Display debug text
 * @param string $str string of content to display
 * @param string $location [comment|screen|javascript] where to display the string
 */
function debug($str, $location='screen', $exit=0) {
	if ($location == 'comment') {
		if (strpos($str, '|')) {
			$str = explode('|', $str);
			echo "\n\n<!--\n";
			foreach ($str as $this_str) {
				echo "$this_str\n";
			}
			echo "-->\n\n";
		} else {
			echo "\n\n<!-- $str -->\n\n";
		}
	}
	if ($location == 'screen') {
		if (is_object($str) || is_array($str)) {
			echo "\n\n<hr/><pre>";
			print_r($str);
			echo "</pre><hr/>\n\n";
		} else if (is_string($str)) {
			if (strpos($str, '|')) {
				$str = explode('|', $str);
				echo "<br /><br />\n";
				foreach ($str as $this_str) {
					echo "$this_str<br />\n";
				}
				echo "<br /><br />\n";
			} else {
	//			echo "\n\n<br/><br/>\n$str\n<br/><br/>\n\n";
				echo "\n\n<hr/><pre>$str</pre><hr/>\n\n";
			}
		}
	}
	if ($location == 'javascript') {
		if (strpos($str, '|')) {
			$msg = '';
			foreach($str as $this_str) {
				$msg .= $this_str."\n";
			}
			echo "alert(\"$msg\");";
		} else {
			echo "alert(\"$str\");";
		}
	}
	if ($exit) exit;
}

/**
 * Initiate database connection
 */
function dbConnect() {
	if ($_SERVER['SERVER_NAME'] == 'www.javadash.com') {
		$dbHostname = "";
		$dbUsername = "";
		$dbPassword = "";
		$dbName = "";
	} else {
		$dbHostname = "localhost";
		$dbUsername = "root";
		$dbPassword = "root";
		$dbName = "javadash";
	}
	$dbcnx = mysql_connect($dbHostname, $dbUsername, $dbPassword);
	if (!$dbcnx) {
		echo( "<p>Unable to connect to the database server $dbHostname at this time.</p>" );
		exit();
	}

	if (! @mysql_select_db($dbName) ) {
		echo( "<p>Unable to locate the $dbName database at this time.</p>" );
		exit();
	}
}

/**
 * Run a mySQL query
 * @param string $sql mySQL statement
 * @return object mySQL resource or error message
 */
function dbQuery ($sql) {
	$result = @mysql_query($sql);
	if (!$result) {
		echo("<p>Error performing query: " . mysql_error() . "</p>");
		echo "<p>$sql</p>";
		exit();
	} else {
		return $result;
	}
}

/**
 * Run mySQL query - useful for fetching a single value
 * @param string $sql mySQL statement
 * @return array fetched row fields
 */
function dbFetch ($sql) {
	$result = @mysql_query($sql);
	if (!$result) {
		echo("<p>Error performing fetch: " . mysql_error() . "</p>");
		exit();
	} else {
		return mysql_fetch_array($result);
	}
}

/**
 * Run mySQL update/delete statement
 * @param string $sql
 * @return int success = 1, failure = error message
 */
function dbUpdate ($sql) {
	if (! @mysql_query($sql) ) {
		echo("<p>Error performing update: " . mysql_error() . " - $sql</p>");
	} else {
		return 1;
	}
}

/**
 * Find user by Device ID
 * @param string $deviceid
 * @return stdClass user object
 */
function findUserByDeviceID($deviceid) {
	$sql = "SELECT * FROM users WHERE deviceid='{$deviceid}'";
	$result = dbQuery($sql);
	if (mysql_num_rows($result)) {
		return mysql_fetch_object($result);
	} else {
		$sql = "INSERT INTO users (deviceid) VALUES ('{$deviceid}')";
		dbQuery($sql);
		$user = new stdClass();
		$user->id = mysql_insert_id();
		$user->deviceid = $deviceid;
		$user->name = "";
		return $user;
	}
}



function unixToMySQL($timestamp)
{
    return date('Y-m-d H:i:s', $timestamp);
}

function showNoOrders()
{
	//No Orders Available
	$rows['OrdersAvailable'] = "false";
	print json_encode($rows);
}

function getTSFromDrinkOrder($order,$order_time)
{
	$drink_array = json_decode($order);
	//echo "order_time = " . $order_time;
	//print_r($drink_array);
	$timestamp = $drink_array->timestamp;
	return compareTS($timestamp,$order_time);
}

function compareTS($timestamp,$selected_date)
{
	date_default_timezone_set("GMT+0");
	$DrinkAddedDate = strtotime(date("Y-m-d H:i:s e",$timestamp));
	$run_date = strtotime($selected_date);

	//echo "DrinkAddedDate " . $DrinkAddedDate . " ";
	//echo "selected_date " . $selected_date . " ";
	//echo "run_date " . $run_date;
	if ($run_date > $DrinkAddedDate)
	{
		$vaild =  true;
	}
	else
	{
		$vaild  = false;
	}
	//echo "valid = " . $vaild;
	return $vaild;

}

function mysql2timestamp($datetime){

	$val = explode(" ",$datetime);
	$date = explode("-",$val[0]);
	$time = explode(":",$val[1]);
	return mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);


}

<?php

session_start(); 

require_once('INCLUDE_db_connect.php');

// (1) set default value
$username = -1;
$task_id = -1;
$session_start_time = -1;

// retrieve values from SESSION superglobal
if (isset($_SESSION["username"]))
{
	$username = $_SESSION["username"];
} 

if (isset($_SESSION["task_id"]))
{
	$task_id = $_SESSION["task_id"];
} 

if (isset($_SESSION["session_start_time"]))
{
	$session_start_time = $_SESSION["session_start_time"];
} 


//////
function log_page_visit( $username, $task_id, $requested_url, $request_parameters, $seconds_since_session_start)
{
	global $connection;
	
	// create SQL query string
	$sql_update_string = "";
	$sql_update_string .= "INSERT INTO user_task_action (username, task_id, requested_url, request_parameters, seconds_since_session_start) ";
	$sql_update_string .= "VALUES ('$username', $task_id, '$requested_url', '$request_parameters', $seconds_since_session_start );";
	// run query and store the "result set"
	$update_was_successful = mysql_query($sql_update_string, $connection);
	
}


//******************************************************
//************** INCLUDE_userTracking.php **************
//******************************************************

/*
	a log will be recorded for current user/task ID
	for any page that includes this file
*/

//--********************* start PHP *************************
	// ***********************
	// *** VARIABLES
	// ***********************

	// get URL for this page (actually URI but let's not split hairs here ...)
//	$requested_url = $_SERVER['REQUEST_URI'];

	$thisPage = $_SERVER['PHP_SELF'];
	$filename = ltrim(strrchr($thisPage, '/'), '/');
	

	// get any parameters passed in request for this page
	$request_parameters = $_SERVER['QUERY_STRING'];

	$seconds_since_session_start = time() -  $session_start_time;

	// ***********************
	// *** LOGIC - get Bean to store data into database
	// ***********************
	log_page_visit( $username, $task_id, $filename, $request_parameters, $seconds_since_session_start);

?>
<div style="float:right;">
<a href="../index.php">(website-test-complete - return to LOGIN SCREEN</a>
</div>
<div style="clear: both;">
</div>
<hr/>
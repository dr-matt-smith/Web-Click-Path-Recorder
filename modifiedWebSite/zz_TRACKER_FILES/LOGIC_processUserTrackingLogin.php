<?php
session_start(); 

require_once('INCLUDE_db_connect.php');

$error_message_html = "";

/**
 * return TRUE if both username and task_id are found in the database
 */
function is_valid_new_user_task( $username, $task_id )
{
	$valid_ids = false;
	
	$both_values_in_db = in_username_table( $username ) && in_task_table( $task_id );
	
	if( $both_values_in_db )
	{
		if( !exists_logs( $username, $task_id ) )
		{
			// only accept this username / task_id if no logs already exist for them
			$valid_ids = true;
		}
	}
	
	return $valid_ids;
}

/**
 * return TRUE if username is found in the database
 */
function in_username_table( $username)
{
	global $connection;
	global $error_message_html;
	
	$query = "SELECT * from user WHERE username = '$username' ";
	
	// run query and store the "result set"
	$rs = mysql_query($query, $connection);
	
	// error message if no result set from query ...
	if( !$rs ) die( "ERROR: query did not return a result set: $query");
	
	// if a record found, then username WAS in the table!
	if( mysql_fetch_assoc($rs) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * return TRUE if task_id is found in the database
 */
function in_task_table( $task_id )
{
	global $connection;
	global $error_message_html;
	
	$query = "SELECT * from task WHERE id = '$task_id' ";
	
	// run query and store the "result set"
	$rs = mysql_query($query, $connection);
	
	// error message if no result set from query ...
	if( !$rs ) die( "ERROR: query did not return a result set: $query");
	
	// if a record found, then username WAS in the table!
	if( mysql_fetch_assoc($rs) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * return TRUE if task_id is found in the database
 */
function exists_logs( $username, $task_id )
{
	global $connection;
	global $error_message_html;
	
	$query = "SELECT * from user_task_action WHERE username = '$username' AND task_id = $task_id ";
	
$error_message_html = $query;
	
	// run query and store the "result set"
	$rs = mysql_query($query, $connection);
	
	// error message if no result set from query ...
	if( !$rs ) die( "ERROR: query did not return a result set: $query");
	
	// if a record found, then username WAS in the table!
	if( mysql_fetch_assoc($rs) )
	{
		return true;
	}
	else
	{
		return false;
	}
}


//
// (1) get values from HTTP request
//
$username = filter_input(INPUT_GET, "username");
$task_id = filter_input(INPUT_GET, "task_id");

//
// (2) get the time the request was made
//
$now =  time();
$session_start_time = $now;

//
// (3) default redirection page is ERROR page
//
$redirect_page = "Location: DISPLAY_ERROR_process.php";
$_SESSION["error_message"] = "error - (an unexpected error occurred)";
$_SESSION["error_page"] = $_SERVER['REQUEST_URI'];

//
// (4) forward to appropriate page ...
// 
if( is_valid_new_user_task( $username, $task_id) )
{
	// (a) store values in SESSION	//
	$_SESSION["username"] = $username;
	$_SESSION["task_id"] = $task_id;
	$_SESSION["session_start_time"] = $session_start_time;
	
	// (b) redirect to test website HOME PAGE
	$redirect_page = "../index.php";
} // if
else
{
	// user/task must already exist
	// forward to error page and send user back to login
	$redirect_page = "DISPLAY_invalidUserLoginAgain.php";
	$_SESSION["error_message"] = $error_message_html;

}

// close the DB connection
mysql_close($connection);

// redirect to appropriate page
header("Location: $redirect_page");

?>
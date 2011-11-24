<?php
require_once('zz_TRACKER_FILES/INCLUDE_db_connect.php');

//
// attempt the DB delete
//

// create SQL query string
$query = "DELETE FROM user_task_action";

$delete_success = mysql_query($query, $connection);
mysql_close($connection);

//
// decision based on whether DELETE was successful
//

// default redirection page is ERROR page
$redirect_page = "Location: DISPLAY_ERROR_process.php";
$_SESSION["error_message"] = "error - (an unexpected error occurred)";
$_SESSION["error_page"] = $_SERVER['REQUEST_URI'];

if ($delete_success)
{
	// redirect browser back to log list oage
	$redirect_page = "DISPLAY_user_log_list.php";
}
else
{
	$_SESSION["error_message"] = "error - no records deleted ... (DB error? no records to clear?)";
}

// redirect to appropriate page
header("Location: $redirect_page");

?>
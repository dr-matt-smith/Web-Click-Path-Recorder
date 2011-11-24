<?php

require_once('zz_TRACKER_FILES/INCLUDE_db_connect.php');

// create SQL query string
$query = "SELECT  distinct username, task_id FROM `user_task_action` ORDER BY username, task_id";

// run query and store the "result set"
$rs = mysql_query($query, $connection);

// error message if no result set from query ...
if( !$rs ) die( "ERROR: query did not return a result set: $query");

// this string will hold all the HTML for the table rows for all the user logs
$html_string = "";

while( $row = mysql_fetch_assoc($rs) )
{
	$username = $row["username"];
	$task_id = $row["task_id"];
	
	$row_string = <<<HERE
	<tr>
	<td>
		$username
	</td>
	<td>
		$task_id
	</td>
	<td>
		<a href="DISPLAY_halfviz_codegen.php?username=$username&task_id=$task_id">
		view textfile for Halfviz code to visualise this user session
		</a>
	</td>

	</tr>
HERE;

	$html_string .= $row_string;

} // while

//-- ******************** end PHP ************************* 
//-- ******************** end PHP ************************* 
?>


<html>
<head>
<style>
td, th
	{	font: 10pt;
	}
</style>
</head>

<body>

<!-- ************** navigation ************* -->
<p>
<a href="../index.php">user tracking LOGIN</a>
</p>
<hr/>
<!-- ************** navigation ************* -->

<h2>
	User Tracking Tool (UTT_2.1) &copy; Matt Smith 2011
</h2>


<!-- ************** navigation ************* -->

<hr/>
<p>
<a href="DISPLAY_user_log_list.php">back to list of USER LOGS</a>
</p>

<table border="1" width="100%">
	<caption>
	List of User Logs in database ...
	</caption>

<?php
	print $html_string;
?>

</table>



</body>
</html>
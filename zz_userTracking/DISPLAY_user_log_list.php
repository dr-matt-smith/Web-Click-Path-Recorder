<?php

require_once('zz_TRACKER_FILES/INCLUDE_db_connect.php');



// create SQL query string
$query = "SELECT * from user_task_action ";
$query .= " ORDER BY username, task_id, seconds_since_session_start";


// run query and store the "result set"
$rs = mysql_query($query, $connection);

// error message if no result set from query ...
if( !$rs ) die( "ERROR: query did not return a result set: $query");

// define a string for a blank table row (to separate each user's task in the log
$blank_row_html = <<<HERE
	<tr>
	<td colspan="6" bgcolor="black" height="5">
	&nbsp;
	</td>
	</tr>
HERE;

// initialise some variables
$is_new_user_task = true;
$old_id_string = "";

// this string will hold all the HTML for the table rows for all the user logs
$html_string = "";

while( $row = mysql_fetch_assoc($rs) )
{
	$username = $row["username"];
	$task_id = $row["task_id"];
	$seconds_since_session_start = $row["seconds_since_session_start"];
	$requested_url = $row["requested_url"];
	$request_parameters = $row["request_parameters"];
	$comments = $row["comments"];
	
	// replace & with html line breaks
	
	//$request_parameters = str_replace($request_parameters, "qq", "<br/>");

	if( !$is_new_user_task )
	{
		$current_ids_string = $username.$task_id;
		if( $current_ids_string != $old_id_string )
		{
			$is_new_user_task = true;
		}
	} // if

	// add separator if new task
	if( $is_new_user_task )
	{
		$old_id_string = $username.$task_id;
		$is_new_user_task = false;
		
		// add blank row to HTML string
		$html_string .= $blank_row_html;
		
	}


	$row_string = <<<HERE
	<tr>
	<td>
		$username
	</td>
	<td>
		$task_id
	</td>
	<td>
		$seconds_since_session_start
	</td>
	<td>
		$requested_url
	</td>
	<td>
		$request_parameters
	</td>
	<td>
		$comments
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

<table border="1" width="100%">
<tr>
<th colspan="6">
<h3>List of User Logs in database ...</h3>
</th>
</tr>

<tr>
<th width = "10%">
	username
</th>
<th width = "5%">
	task_id
</th>
<th width = "5%">
	seconds
	Since
	<br/>
	Session
	Start
</th>
<th>
	requestedURL
</th>
<th width = "15%">
	requestParameters
</th>
<th>
	comments
</th>
</tr>

<?php

print $html_string;

?>

<tr>
<td colspan="6" bgcolor="black" height="5">
&nbsp;
</td>
</tr>

</table>


<!-- ************** navigation ************* -->

<hr/>
<p>
<a href="LOGIC_clear_user_logs.php">clear user logs ... WARNING - this DELETES ALL LOG RECORDS</a>
</p>
<!-- ************** navigation ************* -->

<hr/>
<p>
<a href="DISPLAY_halfviz_link_list.php">get graph code to visualise these logs</a>
</p>
</body>
</html>
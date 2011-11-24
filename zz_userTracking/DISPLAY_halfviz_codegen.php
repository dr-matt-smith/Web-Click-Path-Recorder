<?php

require_once('zz_TRACKER_FILES/INCLUDE_db_connect.php');

function hex_color($seconds)
{
	global $total_seconds;
	// allow 20% of color to show (so doesn't fade down to white)
	$max_seconds = $total_seconds * 1.2;
	
	$propertion = $seconds/$max_seconds;

/*
print "total_seconds = $total_seconds";
print "<br/>";
print "max_seconds = $max_seconds";
print "<br/>";
print "seconds = $seconds";
print "<br/>";
print "propertion = $propertion";
print "<br/>";
*/
	
	$r = dechex($propertion*255);
	$g = dechex($propertion*255);
	$b = dechex($propertion*255);
	
	if( strlen($r) < 2){	$g = "0$r";	}
	if( strlen($g) < 2){	$g = "0$g";	}
	if( strlen($b) < 2){	$g = "0$b";	}
	
	$hex_string = $r.$g.$b;
		
	return "#$hex_string";
}

function calc_line_width($seconds)
{
	global $total_seconds;
	$MAX_WIDTH = 20;
	
	// allow 10% of color to show (so doesn't fade down to white)
	$max_seconds = $total_seconds * 1.2;
	
	$propertion = $seconds/$max_seconds;
	
	return $propertion * $MAX_WIDTH;

}


//
// (1) get values from HTTP request
//
$username = filter_input(INPUT_GET, "username");
$task_id = filter_input(INPUT_GET, "task_id");

//
// get max numb seconds (for color coding calculation)
//

$query = "SELECT MAX(seconds_since_session_start) AS total_seconds FROM `user_task_action` WHERE username = '$username' AND task_id = $task_id";
$rs = mysql_query($query, $connection);
$row = mysql_fetch_assoc($rs);
$total_seconds = $row["total_seconds"];

// create SQL query string
$query = "SELECT  * FROM `user_task_action` WHERE username = '$username' AND task_id = $task_id";

// run query and store the "result set"
$rs = mysql_query($query, $connection);

// error message if no result set from query ...
if( !$rs ) die( "ERROR: query did not return a result set: $query");

// this string will hold all the HTML for the table rows for all the user logs
$html_string = "$username task:$task_id\n";

// grab first item (and do nothing)
$row = mysql_fetch_assoc($rs);
$prev_requested_url = "index";

// loop for remaimning visits
while( $row = mysql_fetch_assoc($rs) )
{
	$id = $row["id"];
	$seconds_since_session_start = $row["seconds_since_session_start"];
	$requested_url = $row["requested_url"];

	// remove ".php";
	$requested_url = str_replace(".php", "", $requested_url);

	$color = hex_color($seconds_since_session_start);
	$line_width = calc_line_width($seconds_since_session_start);

	$row_string = <<<HERE
$prev_requested_url -> $id {color:$color, weight:$line_width}
$id -> $requested_url {color:$color, weight:$line_width}

$id { label: $seconds_since_session_start secs }

HERE;

	if( $requested_url != "index" )
	{
		$row_string .= <<<HERE

; endings
$requested_url {color:#db8e3c}
$requested_url { label: $requested_url, shape: dot, color: grey }


HERE;

	}
	
	$html_string .= $row_string;

	// record this ID for next arrow
	$prev_requested_url = $requested_url;
	
} // while

//-- ******************** end PHP ************************* 
//-- ******************** end PHP ************************* 
?>


<html>
<head>
	<title></title>
</head>

<body>
<p>
;<a href="DISPLAY_user_log_list.php">back to list of USER LOGS</a>
</p>
<hr/>
<p>
; Visit <a href="http://arborjs.org/halfviz/#/cube" target="_blank">halfviz</a>
and replace the code on that page with the code below, to see a graphic visualisation of the Users task session

<hr/>
<pre>

; don't color in the decision pages
{color:none}

;index page
index {color:#db8e3c}		
index {color:red, shape: dot, label:index}	


<?php
	print $html_string;
?>

</pre>
</body>
</html>
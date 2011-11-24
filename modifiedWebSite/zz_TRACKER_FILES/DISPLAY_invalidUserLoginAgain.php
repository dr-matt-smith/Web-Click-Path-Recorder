<?php
session_start(); 


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

// error message
$error_message = "(no error message found in SESSION)";
if (isset($_SESSION["error_message"]))
{
	$error_message = $_SESSION["error_message"];
} 

//
// create debug string
//
$debug_html = "";
$debug_html .= "username = $username";
$debug_html .= "<br/>";
$debug_html .= "task_id = $task_id";
$debug_html .= "<br/>";
$debug_html .= "session_start_time = $session_start_time";
$debug_html .= "<br/>";
$debug_html .= "error_message = $error_message";
$debug_html .= "<br/>";

?>

<html>
<head>
</head>

<body>

<h2>
DISPLAY_invalidUserLoginAgain.php
</h2>

<p>
Sorry, that user already exists for that task number
</p>

<hr/>

<p>
please 
<a href="../../index.php">login again</a>
with a unique user name / task number combination
</p>

<hr/>
<?php
	print $debug_html;
?>

</body>
</html>
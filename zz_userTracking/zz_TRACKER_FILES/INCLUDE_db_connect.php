<?php

// define username and password
$db = "USER_TRACKING";
$username = "fred";
$password = "smith";

$host = "localhost";

// try to get connection to this mysql for given host / username / password
$connection = mysql_connect($host, $username, $password) or die( "ERROR: could not connect to mysql on host '$host'");

// select DB
mysql_select_db($db, $connection) or die( "ERROR: could not select database '$db'");
?>
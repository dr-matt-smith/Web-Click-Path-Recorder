<?php
session_start(); 


// another new comment ABOVE
// here is a new comment - for GIT to identify
// another new comment BELOW


// clear session variables
unset( $_SESSION["username"] );
unset( $_SESSION["task_id"] );
unset( $_SESSION["session_start_time"] );


?>	
<html>
<body>

<h2>
	User Tracking Tool (UTT_2.0) &copy; Matt Smith 2011
</h2>

<form 
	name="login" 
	action="modifiedWebSite/zz_TRACKER_FILES/LOGIC_processUserTrackingLogin.php"
	method="GET"
/>

<p>
	Enter ID for this user (e.g. matt):
	<select name="username"/>
		<option value="user_1">user 1</option>
		<option value="user_2">user 2</option>
		<option value="user_3">user 3</option>
		<option value="user_4">user 4</option>
	</select>
</p>

<p>
	Enter ID for this evalation task (e.g. 1):
	<select name="task_id"/>
		<option value="1">task 1</option>
		<option value="2">task 2</option>
		<option value="3">task 3</option>
		<option value="4">task 4</option>
	</select>
</p>

<p>
	<input type="submit" value="Login"/>
</p>

</form>

<hr/>
after login you will be automatically directed to the "index" page of the website to evaluation ...


<hr>
<a href="zz_userTracking/DISPLAY_user_log_list.php">
View list of user tracking records stored in database ...
</a>
</body>
</html>


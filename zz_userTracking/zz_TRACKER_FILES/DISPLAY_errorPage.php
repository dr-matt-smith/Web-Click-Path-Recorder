<?php


// (1) start session in PHP

/**

<%-- we will want to use some "sql" methods/classes ... --%> 
<%@ page language = "java" import="java.sql.*" %>

<%-- define our runtime error page --%> 
<%@ page isErrorPage = "true" %>

<%-- we want to use sessions --%> 
<%@ page session="true"%>

*/

//-- ********************  start PHP  ************************* --
//-- ********************  start PHP  ************************* --

	// ***********************
	// *** VARIABLES
	// ***********************

	$errorMessage = $_SESSION['error_essage'];
	
//-- ********************  end PHP  ************************* --
//-- ********************  end PHP  ************************* --
?>


<!-- start HTML page and include navigation -->
<!-- start HTML page and include navigation -->
<!-- start HTML page and include navigation -->
<!-- start HTML page and include navigation -->
<!-- start HTML page and include navigation -->
<!-- start HTML page and include navigation -->
<!-- start HTML page and include navigation -->
<!-- start HTML page and include navigation -->

<!-- ERROR PAGE -->
<html>
<body>

<h1> DISPLAY_errorPage.php </h1>
<h2> User Tracking Tool (UTT_2.0) &copy; Matt Smith 2011 ERROR detected </h2>

<p>
The following error has been detected
- please report this to the person running this website evaluation session
<hr/>

<!-- use PRE so can see line breaks in string ... -->
<pre>

<?php
	print errorMessage;
?>

</pre>

</p>

</body>
</html>
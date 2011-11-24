<?php

$error_message = $_SESSION["error_message"];
$error_page = $_SESSION["error_page"];

?>


<!-- ERROR PAGE -->
<html>
<body>

<h1> DISPLAY_errorPage.php </h1>
<h2> User Tracking Tool (UTT_2.0) &copy; Matt Smith 2011 ERROR detected </h2>

<p>
The following error has been detected
- please report this to the person running this website evaluation session
<hr/>

Error occured from page:
<strong>
<?php
	print $error_page;
?>
</strong>


<!-- use PRE so can see line breaks in string ... -->
<pre>

<?php
	print $error_message;
?>

</pre>

</p>

</body>
</html>

</body>
</html>
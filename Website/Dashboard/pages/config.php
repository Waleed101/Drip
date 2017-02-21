<?php
	$dbhost = 'localhost';
	$dbuser = 'waleed_user';
	$dbpass = 'waleedisdrip2017';
	$dbname = "waleed_drip";
	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
?>
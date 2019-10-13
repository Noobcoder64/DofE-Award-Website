<?php

 $dbhost = "";
	// Host where server is running (URL)
 $dbuser = "";
	// Username of phpmyadmin
 $dbpass = "";
	// Password of phpmyadmin
 $db = "";
	// Name of the database

// ESTABLISH CONNECTION
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

// FUNCTION TO REMOVE CHARACTERS THAT WOULD ALLOW SQL INJECTION
 function check_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
 }

?>

<!doctype html>
<html lang ="en">

<head>
	<meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	
 	<link href="css/style.css" rel="stylesheet" type="text/css">
	
	<title>Simple Database Apps</title>
</head>

<body>
<div class="header">
  <h1 id="header_text">Simple Database App</h1>
</div>

<?php

if (empty($_SESSION['username'])) {
	?>
<div class="navbar">
  <a href="index.php">CRUD</a>
  <a href="reviews.php">Reviews</a>
  <a href="test.php" style="float:right">Test connection</a>
  <a href="registration.php" style="float:right">Register</a>
</div>

<?php } else { ?>

<div class="navbar">
	
  <a href="index.php">CRUD</a>
  <a href="reviews.php">Reviews</a>
  <a href="test.php" style="float:right">Test connection</a>
  
  	<a href="index2.php" class="dropdown">My account</a>
    
    <div class="dropdown_content">
  		<p>home</p>
  	</div>
	
</div>


<?php } ?>
</div>
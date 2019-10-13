<!doctype html>
<html lang ="en">

<head>

	<meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie-edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
	<!-- link to ccs file -->
 	<link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<body>

<div class="header-container">
	<a href="index.php">
		<div class="box">
		<img src="img/dofe_logo.png" alt="DofE Logo" class="logo">
		</div>
	</a>

	<div class="header-color"></div>

	<ul class="navbar">
		<!-- Currently not available -->
		<li id="about">ABOUT</li>
			<ul class="dropdown" id="about">
				<a href="#">What is DofE</a>
				<a href="#">How to enrol</a>
				<a href="#">Duration</a>
				<a href="#">Costs</a>
			</ul>

		<li id="apply">APPLY</li>
		<ul class="dropdown" id="apply">
			<a href="enrolment.php">Enrol</a>
			<a href="assessor_registration.php">Assessor registration</a>
		</ul>

		<li id="contact">CONTACT US</li>
			<ul class="dropdown" id="contact">
				<!-- REDIRECT TO CONTACT US PAGE -->
				<a href="contact_us.php">Contact Us</a>
			</ul>

<?php session_start();
if(isset($_SESSION['participant_ID'])) { ?>
		<!-- FEATURES OF PARTICIPANT -->
		<li id="login">LOGGED IN</li>
			<ul class="dropdown" id="login">
				<a href="participant_profile.php">Your profile</a>
				<a href="undertake_activity.php">Undertake an activity</a>
				<a href="view_activities.php">View your activities</a>
				<a href="participant_view_details.php">View your details</a>
				<a href="logout.php">Logout</a>	<!-- ALLOW TO LOGOUT -->

<?php } elseif(isset($_SESSION['inactive_ID'])) { ?>
		<!-- FEATURES OF INACTIVE PARTICIPANT -->
		<li id="login">LOGGED IN</li>
			<ul class="dropdown" id="login">
				<a href="inactive_profile.php">Your profile</a>
				<a href="logout.php">Logout</a>	<!-- ALLOW TO LOGOUT -->

<?php } elseif(isset($_SESSION['assessor_ID']) OR isset($_SESSION['manager_ID'])) { ?>
		<li id="login">LOGGED IN</li>
		<!-- FEATURES OF ASSESSORS -->
			<ul class="dropdown" id="login">
<?php
		// LINK TO PROFILE
if(isset($_SESSION['assessor_ID'])) { // ASSESSOR PROFILE
	echo "<a href='assessor_profile.php'>Your profile</a>";
} elseif(isset($_SESSION['manager_ID'])) { // MANAGER PROFILE
	echo "<a href='manager_profile.php'>Your profile</a>";
} ?>
				<a href="report.php">Submit a report</a>
				<a href="assessor_view_activities.php">View your assessments</a>
				<a href="assessor_view_details.php">View your details</a>
				<a href="logout.php">Logout</a> <!-- ALLOW TO LOGOUT -->
<?php } else { ?>
		<li id="login">LOGIN</li>
			<ul class="dropdown" id="login">
				<!-- REDIRECT TO LOGIN PAGE -->
				<a href="login.php?user=Participant">Participant</a>
				<a href="login.php?user=Assessor">Assessor or Manager</a>
<?php } ?>
			</ul>
	</ul>

</div>

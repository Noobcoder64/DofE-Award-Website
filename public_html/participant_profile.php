<?php include "dbconnection.php"; ?>		<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "participant_auth.php"; ?>	<!--	AUTHORIZE PARTICIPANTS	-->

<div class="profile">
<?php
$participant_ID = $_SESSION['participant_ID'];

// RETRIEVE PARTICIPANT'S FIRST NAME
$sql = "SELECT first_name FROM Participant WHERE participant_ID= '$participant_ID'";
$result = mysqli_query($conn, $sql);
$fetch = mysqli_fetch_assoc($result);

echo "<h1>Welcome, {$fetch['first_name']}</h1>";
?>

<!--	REDIRECTION TO OTHER PAGES	-->
<div>
	<a href="undertake_activity.php">Undertake an Activity</a>
	<a href="view_activities.php">View your activities</a>
  <a href="participant_view_details.php">View your details</a>
</div>

<!--	ALLOW TO LOG OUT	-->
<span id="back_logout"><a href="logout.php">Logout</a></span>
</div>

<?php include "templates/footer.php"; ?>

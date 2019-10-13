<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "manager_auth.php"; ?>	<!--	AUTHORIZE MANAGERS	-->

<div class="profile">
<?php
// GET ID OF LOGGED MANAGER
$assessor_ID = $_SESSION['manager_ID'];

// RETRIEVE MANAGER'S FIRST NAME
$sql = "SELECT first_name FROM Assessor WHERE assessor_ID= '$assessor_ID'";
$result = mysqli_query($conn, $sql);
$fetch = mysqli_fetch_assoc($result);

echo "<h1>Welcome {$fetch['first_name']}</h1>";
?>
<!--	REDIRECTION TO OTHER PAGES	-->
<div>
	<a href="submit_report.php">Submit a report</a>
	<a href="view_assessments.php">View your assessments</a>
	<a href="assessor_view_details.php">View your details</a>
</div>

<div>
	<p>Management</p>
</div>

<div>
	<a href="view_participants.php">View participants</a>
	<a href="view_assessors.php">View assessors</a>
	<a href="activate_account.php">Activate an account</a>
	<a href="recruit_manager.php">Recruit a manager</a>
	<a href="insert_activity.php">Insert Activity</a>
</div>

<!--	ALLOW TO LOG OUT	-->
<span id="back_logout"><a href="logout.php">Logout</a></span>
</div>

<?php include "templates/footer.php"; ?>

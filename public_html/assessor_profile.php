<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "assessor_auth.php"; ?>	<!--	AUTHORIZE ASSESSORS	-->

<div class="profile">
<?php
$assessor_ID = $_SESSION['assessor_ID'];

// RETRIEVE ASSESSOR'S FIRST NAME
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

<!--	ALLOW TO LOG OUT	-->
<span id="back_logout"><a href="logout.php">Logout</a></span>
</div>

<?php include "templates/footer.php"; ?>

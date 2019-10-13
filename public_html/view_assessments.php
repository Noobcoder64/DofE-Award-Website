<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "assessor_auth.php"; ?>	<!--	AUTHORIZE ASSESSORS	-->

<title>View Assessments</title>

<?php
// GET ASSESSOR ID
if(isset($_SESSION['manager_ID'])){
		$assessor_ID = $_SESSION['manager_ID'];
		} else {
			$assessor_ID = $_SESSION['assessor_ID'];
		}

// DETERMINE WHICH FIELD SHOULD BE IN ORDER
if(isset($_GET['order'])) {
	$order = $_GET['order'];
} else {
	$order = 'date_completed';
}

// DETERMINE IF ORDER SHOULD BE ASCENDING OR DESCEDING
if(isset($_GET['sort'])) {
	$sort = $_GET['sort'];
} else {
	$sort = 'DESC';
}

//	SWAP BETWEEN ASCENDING OR DESCENDING WHEN SAME FIELD NAME IS CLICKED AGAIN
$sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';

// GET PARTICIPANT NAME TO BE SEARCHED
if (!empty($_GET['search_participant'])) {
	$search_participant = $_GET['search_participant'];
} else {
	$search_participant = "";
}

// GET ACTIVITY NAME TO BE SEARCHED
if (!empty($_GET['search_activity'])) {
	$search_activity = $_GET['search_activity'];
} else {
	$search_activity = "";
}

// SQL STATEMENT TO RETRIEVE ASSESSMENT DETAILS
$sql = "SELECT assessment_ID, first_name, last_name, activity_name, participant_level, section, start_date, date_completed FROM Assessment, Participant, Activity WHERE assessor_ID = '$assessor_ID' AND Assessment.activity_ID = Activity.activity_ID AND Assessment.participant_ID = Participant.participant_ID AND first_name LIKE '%$search_participant%' AND activity_name LIKE '%$search_activity%' ORDER BY $order $sort";
$result = mysqli_query($conn, $sql);
?>

<div class="view_activities">
 	<h1>View assessments</h1>

 <div>
 	<form method="get">
 		<h2>Search</h2>
		<!--	INPUT FIELDS THAT WILL ALLOW SEARCHING	-->
 		<input type="text" name="search_participant" placeholder="Search by participant">
 		<input type="text" name="search_activity" placeholder="Search by activity">
 		<input type="submit" value="Search">
 		<input type="submit" value="Clear">
 	</form>

	<table border = "1">
	<tr>
		<!--	CLICKABLE FIELD NAMES THAT WILL ALLOW SORTING	-->
		<th><a href="?order=first_name&&sort=<?php echo $sort?>">First Name</a></th>
		<th><a href="?order=last_name&&sort=<?php echo $sort?>">Last Name</a></th>
    <th><a href="?order=activity_name&&sort=<?php echo $sort?>">Activity Name</a></th>
		<th><a href="?order=participant_level&&sort=<?php echo $sort?>">Level</a></th>
		<th><a href="?order=section&&sort=<?php echo $sort?>">Section</a></th>
		<th><a href="?order=start_date&&sort=<?php echo $sort?>">Start Date</a></th>
		<th><a href="?order=date_completed&&sort=<?php echo $sort?>">Date Completed</a></th>
		<th>More details</th>
	</tr>

	<?php
if (mysqli_num_rows($result) > 0) {
    while($fetch = mysqli_fetch_assoc($result)) {
		// DISPLAY ASSESSMENT DETAILS
		echo
		"<tr>
			<td>{$fetch['first_name']}</td>
			<td>{$fetch['last_name']}</td>
			<td>{$fetch['activity_name']}</td>
			<td>{$fetch['participant_level']}</td> ";

			switch($fetch['section']) {
				case 'V':
					echo "<td>Volunteering</td>";
				break;
				case 'P':
					echo "<td>Physical</td>";
				break;
				case 'S':
					echo "<td>Skills</td>";
				break;
				case 'E':
					echo "<td>Expedition</td>";
				break;
			}

			echo "
			<td>{$fetch['start_date']}</td>
			";
			// CHECK WHETHER ACTIVITY IS COMPLETED OR NOT
			switch($fetch['date_completed']) {
				default:
					echo "<td>{$fetch['date_completed']}</td>";
				break;
				case 0:
					echo "<td>In progress...</td>";
				break;
			}

		// BUTTONS TO ALLOW REDIRECTION TO VIEW ASSESSMENT DETAILS PAGE ALONG THE ASSESSMENT_ID
echo "<form method= 'post' action= 'view_assessment_details.php'>
				<input type= 'hidden' name= 'assessment_ID' value={$fetch['assessment_ID']}>
				<td><input type= 'submit' name= 'view' value=View></td>
			</form>
		</tr>";
	}
}
	?>
		</table>
	</div>
</div>

<?php
// GO BACK TO ASSESSOR OR MANAGER PROFILE DEPENDING ON USER
if(isset($_SESSION['manager_ID'])){
	echo "<span id='back_logout'><a href='manager_profile.php'>Back</a></span>";
} else {
	echo "<span id='back_logout'><a href='assessor_profile.php'>Back</a></span>";
}
?>

<?php include "templates/footer.php"; ?>

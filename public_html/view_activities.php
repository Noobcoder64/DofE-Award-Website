<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?><!--	DISPLAY HEADER	-->
<?php include "participant_auth.php"; ?><!--	AUTHORIZE PARTICIPANTS	-->
<title>View Activities</title>

<?php
$participant_ID = $_SESSION['participant_ID'];

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

// GET ACTIVITY NAME TO BE SEARCHED
if (isset($_GET['search_activity'])) {
	$search_activity = $_GET['search_activity'];
} else {
	$search_activity = "";
}

// GET ASSESSOR NAME TO BE SEARCHED
if (isset($_GET['search_assessor'])) {
	$search_assessor = $_GET['search_assessor'];
} else {
	$search_assessor = "";
}

// GET SECTION NAME TO BE FILTERED
if (isset($_GET['filter_section'])) {
	$filter_section = $_GET['filter_section'];
} else {
	$filter_section = "";
}

// SQL STATEMENT TO RETRIEVE ASSESSMENT DETAILS
$sql = "SELECT assessment_ID, activity_name, section, first_name, start_date, date_completed FROM Assessor, Activity, Assessment WHERE participant_ID = '$participant_ID' AND Assessment.activity_ID = Activity.activity_ID AND Assessment.assessor_ID = Assessor.assessor_ID AND activity_name LIKE '%$search_activity%' AND first_name LIKE '%$search_assessor%' AND section LIKE '%$filter_section%' ORDER BY $order $sort";
$result = mysqli_query($conn, $sql);
 ?>

<div class="view_activities">
	<h1>View your activities</h1>

<div>
	<form method="get">
		<h2>Search</h2>
		<!--	INPUT FIELDS THAT WILL ALLOW SEARCHING	-->
		<input type="text" name="search_activity" placeholder="Search by activity">
		<input type="text" name="search_assessor" placeholder="Search by assessor">

		<h3>Filter by section</h3>
		<select name="filter_section">
			 <option value="">Select section</option>
			 <option value="V">Volunteering</option>
			 <option value="P">Physical</option>
			 <option value="S">Skills</option>
			 <option value="E">Expedition</option>
		</select>

		<input type="submit" value="Submit">
		<input type="submit" value="Clear">
	</form>

	<table border = "1">
	<tr>
		<!--	CLICKABLE FIELD NAMES THAT WILL ALLOW SORTING	-->
		<th><a href="?order=activity_name&&sort=<?php echo $sort?>">Activity Name</a></th>
		<th><a href="?order=section&&sort=<?php echo $sort?>">Section</a></th>
    <th><a href="?order=first_name&&sort=<?php echo $sort?>">Assessor Name</a></th>
    <th><a href="?order=start_date&&sort=<?php echo $sort?>">Start Date</a></th>
    <th><a href="?order=date_completed&&sort=<?php echo $sort?>">Date Completed</a></th>
		<th>Report</th>
		<th></th>
	</tr>

<?php
    while($fetch = mysqli_fetch_assoc($result)) {
		echo
		"<tr>
			<td>{$fetch['activity_name']}</td> ";

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
			<td>{$fetch['first_name']}</td>
			<td>{$fetch['start_date']}</td>";

		// CHECK WHETHER ACTIVITY IS COMPLETED OR NOT
		 switch($fetch['date_completed']) {
				default:
					echo "<td>{$fetch['date_completed']}</td>
								<td>Available</td>";
					break;
				case 0:
					echo "<td>In progress...</td>
								<td>Not available</td>
					";
					break;
					}

		// BUTTONS TO ALLOW REDIRECTION TO VIEW ACTIVITY DETAILS PAGE ALONG THE ASSESSMENT_ID
		echo "
		<form method= 'post' action= 'view_activity_details.php'>
			<input type= 'hidden' name= 'assessment_ID' value={$fetch['assessment_ID']}>
			<td><input type= 'submit' name= 'view' value=View></td>
		</form>
		</tr>";
	}
?>
	</table>
	</div>
</div>

<!--	ALLOW TO GO BACK TO PROFILE	-->
<span id="back_logout"><a href="participant_profile.php">Back</a></span>

<?php include "templates/footer.php"; ?>

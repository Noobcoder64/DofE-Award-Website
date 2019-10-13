<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "manager_auth.php"; ?>	<!--	AUTHORIZE MANAGERS	-->
<title>View Participants</title>

<?php
// DETERMINE WHICH FIELD SHOULD BE IN ORDER
if(isset($_GET['order'])) {
	$order = $_GET['order'];
} else {
	$order = 'first_name';
}

// DETERMINE IF ORDER SHOULD BE ASCENDING OR DESCEDING
if(isset($_GET['sort'])) {
	$sort = $_GET['sort'];
} else {
	$sort = 'DESC';
}

//	SWAP BETWEEN ASCENDING OR DESCENDING WHEN SAME FIELD NAME IS CLICKED AGAIN
$sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';

// GET PARTICIPANT FIRST NAME TO BE SEARCHED
if (isset($_GET['search_firstname'])) {
	$search_first_name = $_GET['search_firstname'];
} else {
	$search_first_name = "";
}

// GET PARTICIPANT LAST NAME TO BE SEARCHED
if (isset($_GET['search_lastname'])) {
	$search_last_name = $_GET['search_lastname'];
} else {
	$search_last_name = "";
}

// GET LEVEL TO BE FILTERED
if (isset($_GET['filter_level'])) {
	$filter_level = $_GET['filter_level'];
} else {
	$filter_level = "";
}

// SQL STATEMENT TO RETRIEVE PARTICIPANTS DETAILS
$sql = "SELECT * FROM Participant WHERE first_name LIKE '%$search_first_name%' AND last_name LIKE '%$search_last_name%' AND participant_level LIKE '%$filter_level%' ORDER BY $order $sort";
$result = mysqli_query($conn, $sql);
?>

<div class="view_activities">
 <h1>View participants</h1>

 <div>
  <form method="get">
 	 <h2>Search</h2>
	 <!--	INPUT FIELDS THAT WILL ALLOW SEARCHING	-->
 	 <input type="text" name="search_firstname" placeholder="Search by first name">
	 <input type="text" name="search_lastname" placeholder="Search by last name">

	 <h3>Filter by level</h3>
	 <!--	INPUT FIELD THAT WILL ALLOW FILTERING	-->
	 <select name="filter_level">
		  <option value="">Select level</option>
		 	<option value="Bronze">Bronze</option>
	 		<option value="Silver">Silver</option>
			<option value="Gold">Gold</option>
	 </select>

 	 <input type="submit" value="Submit">
 	 <input type="submit" value="Clear">
  </form>

	<table border = "1">
	<tr>
		<!--	CLICKABLE FIELD NAMES THAT WILL ALLOW SORTING	-->
		<th><a href="?order=first_name&&sort=<?php echo $sort?>">First Name</a></th>
		<th><a href="?order=last_name&&sort=<?php echo $sort?>">Last Name</a></th>
		<th>Date of Birth</th>
		<th>Gender</th>
		<th><a href="?order=participant_level&&sort=<?php echo $sort?>">Level</a></th>
		<th><a href="?order=paid_date&&sort=<?php echo $sort?>">Date of Enrolment</a></th>
		<th></th>
		<th></th>
	</tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while($fetch = mysqli_fetch_assoc($result)) {

		$date_of_birth =	DATE('d/m/Y',strtotime($fetch['date_of_birth']));

		echo "<tr>
			 <td>{$fetch['first_name']}</td>
			 <td>{$fetch['last_name']}</td>
			 <td>$date_of_birth</td>";
			 switch($fetch['gender']) {
				case 'M':
					echo "<td>Male</td>";
					break;
				case 'F':
					echo "<td>Female</td>";
					break;
					}

		echo "<td>{$fetch['participant_level']}</td>";

		switch($fetch['paid_date']) {
			default:
				echo "<td>{$fetch['paid_date']}</td>";
				break;
			case 0:
				// DISPLAY THIS IF PARTICIPANT DID NOT PAY THE FEE
				echo "<td>Requires activation</td>";
				break;
		}

		// BUTTONS TO UPDATE OR DELETE A PARTICIPANT
		echo "
			<form method= 'post' action= 'change_participant.php'>
				<input type= 'hidden' name= 'participant_ID' value={$fetch['participant_ID']}>
				<input type= 'hidden' name= 'first_name' value={$fetch['first_name']}>

				<td><input type= 'submit' name= 'update' value=Update></td>
				<input type= 'hidden' name= 'reset' value=Reset>

				<td><input type= 'submit' name= 'delete' value=Delete></td>
			</form>
			</tr>";
    }
}
?>
	</table>

</div>

<?php
	// RETRIEVE PARTICIPANTS THAT ARE IN BRONZE LEVEL
	$result = mysqli_query($conn,"SELECT 1 FROM Participant WHERE participant_level = 'Bronze'");
	// COUNT THE NUMBER OF RETRIEVED PARTICIPANTS
	$count_bronze = mysqli_num_rows($result);
	// RETRIEVE PARTICIPANTS THAT ARE IN SILVER LEVEL
	$result = mysqli_query($conn,"SELECT 1 FROM Participant WHERE participant_level = 'Silver'");
	// COUNT THE NUMBER OF RETRIEVED PARTICIPANTS
	$count_silver = mysqli_num_rows($result);
	// RETRIEVE PARTICIPANTS THAT ARE IN GOLD LEVEL
	$result = mysqli_query($conn,"SELECT 1 FROM Participant WHERE participant_level = 'Gold'");
	// COUNT THE NUMBER OF RETRIEVED PARTICIPANTS
	$count_gold = mysqli_num_rows($result);
	// RETRIEVE ALL OF THE PARTICIPANTS
	$result = mysqli_query($conn,"SELECT 1 FROM Participant");
	// COUNT THE NUMBER OF RETRIEVED PARTICIPANTS
	$count_total = mysqli_num_rows($result);
 ?>

<div id="div_participant_count">
	<h3>Participants</h3>
	<div>
		<div>Bronze:<span><?php echo $count_bronze; ?></span></div>
		<div>Silver:<span><?php echo $count_silver; ?></span></div>
		<div>Gold:<span><?php echo $count_gold; ?></span></div>
		<div>Total:<span><?php echo $count_total; ?></span></div>
	</div>
</div>

</div>

<!-- GO BACK TO MANAGER PROFILE -->
<span id='back_logout'><a href='manager_profile.php'>Back</a></span>

<?php include "templates/footer.php"; ?>

<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "manager_auth.php";?>	<!--	AUTHORIZE MANAGERS	-->
<title>View Assessors</title>

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

// GET ASSESSOR FIRST NAME TO BE SEARCHED
if (!empty($_GET['search_firstname'])) {
	$search_first_name = $_GET['search_firstname'];
} else {
	$search_first_name = "";
}

// GET ASSESSOR LAST NAME TO BE SEARCHED
if (!empty($_GET['search_lastname'])) {
	$search_last_name = $_GET['search_lastname'];
} else {
	$search_last_name = "";
}

// SQL STATEMENT TO RETRIEVE ASSESSORS DETAILS
$sql = "SELECT * FROM Assessor WHERE assessor_ID <> {$_SESSION['manager_ID']} AND first_name LIKE '%$search_first_name%' AND last_name LIKE '%$search_last_name%' ORDER BY $order $sort ";
$result = mysqli_query($conn, $sql);
?>

<div class="view_activities">
 <h1>View assessors</h1>

 <div>
  <form method="get">
 		<h2>Search</h2>
		<!--	INPUT FIELDS THAT WILL ALLOW SEARCHING	-->
 		<input type="text" name="search_firstname" placeholder="Search by first name">
 		<input type="text" name="search_lastname" placeholder="Search by last name">

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
		<th><a href="?order=is_manager&&sort=<?php echo $sort?>">Type</a></th>
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

			// DISPLAY THE TYPE OF USER
			switch($fetch['is_manager']) {
				case 0:
					echo "<td>Assessor</td>";
					break;
				case 1:
					echo "<td>Manager</td>";
					break;
					}

		// BUTTONS TO UPDATE OR DELETE AN ASSESSOR
		echo "
			<form method= 'post' action= 'change_assessor.php'>
				<input type= 'hidden' name= 'assessor_ID' value={$fetch['assessor_ID']}>
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
		// RETRIEVE ASSESSORS
		$result = mysqli_query($conn,"SELECT 1 FROM Assessor WHERE is_manager IS NULL");
		// COUNT THE NUMBER OF ASSESSORS
		$count_assessors = mysqli_num_rows($result);
		// RETRIEVE MANAGERS
		$result = mysqli_query($conn,"SELECT 1 FROM Assessor WHERE is_manager = 1");
		// COUNT THE NUMBER OF MANAGERS
		$count_managers = mysqli_num_rows($result);
		// RETRIEVE ALL OF THE ASSESSORS INCLUDING MANAGERS
		$result = mysqli_query($conn,"SELECT 1 FROM Assessor");
		// COUNT THE NUMBER OF RETRIEVED ASSESSORS AND MANAGERS
		$count_total = mysqli_num_rows($result);
	 ?>

	<div id="div_participant_count">
		<h3>Number of</h3>
		<div>
			<div>Assessors:<span><?php echo $count_assessors; ?></span></div>
			<div>Managers:<span><?php echo $count_managers; ?></span></div>
			<div>Total:<span><?php echo $count_total; ?></span></div>
		</div>
	</div>

</div>

<!-- GO BACK TO MANAGER PROFILE -->
<span id='back_logout'><a href='manager_profile.php'>Back</a></span>

<?php include "templates/footer.php"; ?>

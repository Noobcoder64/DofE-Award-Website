<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?><!--	DISPLAY HEADER	-->
<?php include "participant_auth.php"; ?><!--	AUTHORIZE PARTICIPANTS	-->
<?php include "validation.php"; ?>		<!--	IMPORT VALIDATION FUNCTIONS	-->
<title>Create Activity</title>

<?php
// GET ID OF LOGGED PARTICIPANT
$participant_ID = $_SESSION['participant_ID'];

$field = array(array());	// DECLARE MULTI-DIMENSIONAL ASSOCIATIVE ARRAY

// STORE FIELD NAMES REQUIRED TO BE INPUTTED
$field_names = array('activity_ID','assessor_ID','start_date');

// GIVE EACH FIELD AN INPUT AND ERROR KEY
foreach ($field_names as $name) {
	$field[$name]['input'] = '';	// ASSIGN NULL VALUE
	$field[$name]['error'] = '';	// ASSIGN NULL VALUE
}

if (isset($_POST['undertake'])) {	// EXECUTE ONCE UNDERTAKE BUTTON HAS BEEN CLICKED

// CHECK IF AN ACTIVITY HAS BEEN SELECTED
$field['activity_ID'] = presence_check('activity_ID','Please select an activity');

if(isset($_POST['activity_ID'])) {	// EXECUTE ONCE AN ACTIVITY HAS BEEN SELECTED
	// CHECK IF ACTIVITY HAS ALREADY BEEN UNDERTAKEN
	$sql = "SELECT 1 FROM Assessment WHERE participant_ID = $participant_ID AND activity_ID = {$field['activity_ID']['input']}";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$field['activity_ID']['error'] = "You already undertook this activity";
	}
}

// CHECK WHETHER PARTICIPANT IS CURRENTLY UNDERTAKING 3 ACTIVITIES
$sql = "SELECT 1 FROM Assessment WHERE participant_ID = $participant_ID AND date_completed IS NULL";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 2) {
	$field['activity_ID']['error'] = "You cannot undertake more than 3 activities at a time";
}

// CHECK IF AN ASSESSOR HAS BEEN SELECTED
$field['assessor_ID'] = presence_check('assessor_ID','Please select an assessor');

if(isset($_POST['assessor_ID'])) {	// EXECUTE ONCE AN ASSESSOR HAS BEEN SELECTED
	// CHECK WETHER ASSESSOR IS ASSESSING MORE THAN 3 ACTIVITIES
	$sql = "SELECT 1 FROM Assessment WHERE assessor_ID = {$field['assessor_ID']['input']} AND date_completed IS NULL";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 2) {
	$field['assessor_ID']['error'] = "Assessor too busy, contact for more info";
	}
}

// VALIDATE STARTING DATE
$field['start_date'] = presence_check('start_date','Please enter a starting date');
if(!empty($_POST['start_date'])) {	// EXECUTE ONCE A STARTING DATE HAS BEEN ENTERED
	$current_date = date('Y-m-d');
	if($field['start_date']['input'] < $current_date) {
		$field['start_date']['error'] = "Please enter a valid starting date";
	}
}

// CHECK IF EVERY INPUT IS VALID
	foreach ($field as $field_name) {
		$flag = 1;	// INDICATES IF DATA IS VALID
		if(!empty($field_name['error'])) {
			$flag = 0;	// INDICATOR ASSIGNED 0 IF ERROR MESSAGE HAS BEEN DISPLAYED
		break;
		}
	}

if($flag) {	// EXECUTE IF ALL INPUTS ARE VALID

	// SQL STATEMENT TO UPDATE ASSESSMENT DETAILS INTO DATABASE
	$sql = "INSERT INTO Assessment(participant_ID, activity_ID, assessor_ID, start_date) VALUES ('$participant_ID', '{$field['activity_ID']['input']}', '{$field['assessor_ID']['input']}', '{$field['start_date']['input']}')";

	// EXECUTE SQL QUERY
	if(mysqli_query($conn,$sql)) { header ("Location:participant_profile.php"); }
}

}

// KEEP ENTERED VALUES EVEN IF VIEW IS CLICKED
if(isset($_POST['view'])) {
	(isset($_POST['activity_ID']) ?  $field['activity_ID']['input'] = check_input($_POST['activity_ID']) : "");
	(isset($_POST['assessor_ID']) ?  $field['assessor_ID']['input'] = check_input($_POST['assessor_ID']) : "");
	(isset($_POST['start_date']) ?  $field['start_date']['input'] = check_input($_POST['start_date']) : "");
}

?>

<form method="post" class="create_activity">

<div>
	<h1>Choose an Activity</h1>

	<span class="error"><?php echo $field['activity_ID']['error'];?></span>
	<table border = "1">
	<tr>
		<th>Choose</th>
		<th>Activity Name</th>
    <th>Section</th>
    <th>Activity Description</th>
	</tr>

<?php
	// RETREIVE ACTIVITY DETAILS
	$sql = "SELECT activity_ID, section, activity_name, activity_description FROM Activity";
	$result = mysqli_query($conn, $sql);
  while($fetch = mysqli_fetch_assoc($result)) {
		echo
		// DISPLAY ACTIVITIES
		"<tr>
			<td><input type= 'radio' name= 'activity_ID' value= {$fetch['activity_ID']} ". ($field['activity_ID']['input'] == $fetch['activity_ID'] ? "checked": "") ." ></td>

			<td>{$fetch['activity_name']}</td>";

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

		echo
			"<td>{$fetch['activity_description']}</td>
			</tr>";
		}
?>

	</table>
</div>

<div>
	<h1>Choose an Assessor</h1>
	<span class="error"><?php echo $field['assessor_ID']['error'];?></span>

		<table border="1">
			<tr>
				<th>Choose</th>
				<th>Name</th>
				<th>Surname</th>
				<th>Contacts</th>
			</tr>

<?php
// RETREIVE ASSESSOR DETAILS
$sql = "SELECT assessor_ID, first_name, last_name FROM Assessor";
$result = mysqli_query($conn, $sql);

    while($fetch = mysqli_fetch_assoc($result)) {
		// DISPLAY ASSESSORS
		echo
		"<tr>
			<td><input type= 'radio' name='assessor_ID' value= {$fetch['assessor_ID']} ". ($field['assessor_ID']['input'] == $fetch['assessor_ID'] ? "checked": "") ."></td>

			<td>{$fetch['first_name']}</td>

			<td>{$fetch['last_name']}</td>

			<td>
					<button type='submit' name='view' value={$fetch['assessor_ID']}>View</button>
			</td>
			";
	}
?>
	</table>
</div>

<div>
	<?php
			if(isset($_POST['view'])) {
				$assessor_ID = check_input($_POST['view']);
				// RETREIVE ASSESSOR'S CONTACT DETAILS
				$sql = "SELECT email_address, telephone FROM Assessor WHERE assessor_ID = '$assessor_ID'";
				$result = mysqli_query($conn, $sql);
				$fetch = mysqli_fetch_assoc($result);
				// DISPLAY ASSESSOR'S CONTACT DETAILS
				echo "
				<div>
					<h4>Email</h4>
					<p>{$fetch['email_address']}</p>

					<h4>Telephone</h4>
					<p>{$fetch['telephone']}</p>
				</div>
				";
			}
?>

		<!--	INPUT STARTING DATE	-->
    <label for="start_date">Start Date</label>
    <input type="date" name="start_date" id="start_date" value="<?php echo $field['start_date']['input'];?>">
		<span class="error"><?php echo $field['start_date']['error'];?></span>

		<!--	UNDERTAKE BUTTON	-->
		<input id= "undertake" type="submit" name="undertake" value="UNDERTAKE">

</div>

</form>

<!--	ALLOW TO GO BACK TO PROFILE	-->
<span id="back_logout"><a href="participant_profile.php">Back</a></span>

<?php include "templates/footer.php"; ?>

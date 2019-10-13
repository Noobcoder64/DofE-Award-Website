<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "assessor_auth.php"; ?>	<!--	AUTHORIZE ASSESSORS	-->
<?php include "validation.php"; ?>	<!--	IMPORT VALIDATION FUNCTIONS	-->
<title>Submit a Report</title>

<?php
// GET ID OF LOGGED ASSESSOR
if(isset($_SESSION['manager_ID'])){
	$assessor_ID = $_SESSION['manager_ID'];
} else {
	$assessor_ID = $_SESSION['assessor_ID'];
}

$field = array(array());	// DECLARE MULTI-DIMENSIONAL ASSOCIATIVE ARRAY

// STORE FIELD NAMES REQUIRED TO BE INPUTTED
$field_names = array('assessment_ID','comment','date_completed');

// GIVE EACH FIELD AN INPUT AND ERROR KEY
foreach ($field_names as $name) {
	$field[$name]['input'] = '';	// ASSIGN NULL VALUE
	$field[$name]['error'] = '';	// ASSIGN NULL VALUE
}

if (isset($_POST['submit'])) {

	// CHECK IF AN ASSESSMENT HAS BEEN SELECTED
	$field['assessment_ID'] = presence_check('assessment_ID','Please select an assessment');

	// CHECK IF COMMENTS HAS BEEN WRITTEN
	$field['comment'] = presence_check('comment','Please enter you comments');

	// VALIDATE DATE OF COMPLETION
	$field['date_completed'] = presence_check('date_completed','Please enter the date completed');

	if(isset($_POST['date_completed'])) {	// EXECUTE IF DATE COMPLETED IS SET
		$current_date = date('Y-m-d');

		// VALIDATE THAT DATE COMPLETED IS NOT GREATER THAT CURRENT DATE
		if($field['date_completed']['input'] > $current_date) {
				$field['date_completed']['error'] = "Please enter a valid date of completion";

		} elseif(isset($_POST['assessment_ID'])) {	// EXECUTE IF ASSESSMENT IS SELECTED

			// RETREIVE SELECTED ASSESSMENT'S START DATE
			$sql = "SELECT start_date FROM Assessment WHERE assessment_ID = {$field['assessment_ID']['input']}";
			$result = mysqli_query($conn, $sql);
			$fetch = mysqli_fetch_assoc($result);

			// VALIDATE THAT DATE COMPLETED IS NOT LESS THAN STARTING DATE
			if($field['date_completed']['input'] < $fetch['start_date']) {
				$field['date_completed']['error'] = "Please enter a valid date of completion";
			}
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
		$sql = "UPDATE Assessment SET comment = '{$field['comment']['input']}', date_completed = '{$field['date_completed']['input']}' WHERE assessment_ID = '{$field['assessment_ID']['input']}'";

		// EXECUTE SQL QUERY
		if(mysqli_query($conn,$sql)) { header("Location: submit_report.php"); }
	}
}
?>

<form method="post" class="create_activity">
<div>
	<h1>Choose an Assessment</h1>

	<span class="error"><?php echo $field['assessment_ID']['error'];?></span>
	<table border = "1">
		<tr>
			<th>Choose</th>
			<th>First Name</th>
			<th>Last Name</th>
    	<th>Activity Name</th>
			<th>Level</th>
			<th>Start Date</th>
		</tr>

<?php
	// RETREIVE ASSESSMENT DETAILS
	$sql = "SELECT assessment_ID, first_name, last_name, activity_name, participant_level, start_date, date_completed FROM Assessment, Participant, Activity WHERE assessor_ID = '$assessor_ID' AND date_completed IS NULL AND Assessment.activity_ID = Activity.activity_ID AND Assessment.participant_ID = Participant.participant_ID";
	$result = mysqli_query($conn, $sql);

    while($fetch = mysqli_fetch_assoc($result)) {
			//	DISPLAY ASSESSMENT DETAILS
			echo
				"<tr>
					<td><input type= 'radio' name='assessment_ID' value= {$fetch['assessment_ID']} ". ($field['assessment_ID']['input'] == $fetch['assessment_ID'] ? "checked": "") ."></td>
					<td>{$fetch['first_name']}</td>
					<td>{$fetch['last_name']}</td>
					<td>{$fetch['activity_name']}</td>
					<td>{$fetch['participant_level']}</td>
					<td>{$fetch['start_date']}</td>
				</tr>";
			}
?>
	</table>
</div>

<div>
	<!--	INPUT FOR COMMENTS	-->
	<h1>Comments</h1>
	<span class="error"><?php echo $field['comment']['error'];?></span>
  <textarea name="comment" rows="10" cols="30" placeholder="Enter your comments here"><?php echo $field['comment']['input'];?></textarea>
</div>

<div>
	<!--	INPUT FOR DATE COMPLETED	-->
	<label for="date_completed">Date Completed</label>
  <input type="date" name="date_completed" id="date_completed" value="<?php echo $field['date_completed']['input'];?>">
	<span class="error"><?php echo $field['date_completed']['error'];?></span><br>

	<!--	BUTTON TO SUBMIT REPORT	-->
	<input id= "undertake" type="submit" name="submit" value="SUBMIT">
</div>

</form>

<?php
// GO BACK TO ASSESSOR OR MANAGER PROFILE DEPENDING ON USER
if(isset($_SESSION['manager_ID'])){
		echo "<span id='back_logout'><a href='manager_profile.php'>Back</a></span>";
} else {
		echo "<span id='back_logout'><a href='assessor_profile.php'>Back</a></span>";
}
?>

<?php include "templates/footer.php"; ?>

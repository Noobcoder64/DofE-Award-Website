<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "manager_auth.php"; ?>	<!--	AUTHORIZE MANAGERS	-->
<title>Activate Account</title>

<?php
$error_participant = "";

if (isset($_POST['submit'])) {	// EXECUTE IF ACTIVATE BUTTON IS CLICKED
	$flag = 1;

	// CHECK IF PARTICIPANT HAS BEEN SELECTED
	if(isset($_POST['participant_ID'])) {
		$participant_ID = check_input($_POST['participant_ID']);
	} else {
		$error_participant = "Please select a participant";
		$flag = 0;
	}

	if($flag) {	// EXECUTE ONCE DATA IS VALIDATED
	$current_date = date('Y-m-d');
	//	SQL STATEMENT TO UPDATE PAID DATE WITH CURRENT DATE
	$sql = "UPDATE Participant SET paid_date = '$current_date' WHERE '$participant_ID' = participant_ID";
	$result = mysqli_query($conn, $sql);
	}
}
?>

<form method="post" class="create_activity">
	<div>
		<h1>Choose a participant</h1>
		<span class="error"><?php echo $error_participant;?></span>

		<table border = "1">
			<tr>
				<th>Choose</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Date of Birth</th>
			</tr>

<?php
// RETRIEVE PARTICIPANTS DETAILS
$sql = "SELECT participant_ID, first_name, last_name, date_of_birth, paid_date FROM Participant WHERE paid_date IS NULL";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($fetch = mysqli_fetch_assoc($result)) {

		// DISPLAY PARTICIPANTS DETAILS
		echo "<tr>
				 	<td><input type= 'radio' name='participant_ID' value= ".$fetch['participant_ID']."></td>
				 	<td>{$fetch['first_name']}</td>
				 	<td>{$fetch['last_name']}</td>
				 	<td>". DATE('d/m/Y',strtotime($fetch['date_of_birth'])) ."</td>
				</tr>";
  }
}
?>
	</table>
		<!--	ACTIVATE BUTTON	-->
		<input type="submit" id="btn_activate" name="submit" value="ACTIVATE">
	</div>
</form>

<!--	ALLOW TO GO BACK TO PROFILE	-->
<span id='back_logout'><a href='manager_profile.php'>Back</a></span>

<?php include "templates/footer.php"; ?>

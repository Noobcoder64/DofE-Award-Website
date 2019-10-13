<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "manager_auth.php"; ?>	<!--	AUTHORIZE MANAGERS	-->
<title>Recruit Manager</title>

<?php
$error_assessor = "";

if (isset($_POST['submit'])) {	// EXECUTE IF RECRUIT BUTTON IS CLICKED
	$flag = 1;

	// CHECK IF ASSESSOR HAS BEEN SELECTED
	if(isset($_POST['assessor_ID'])) {
		$assessor_ID = check_input($_POST['assessor_ID']);
	} else {
		$error_assessor = "Please select an assessor";
		$flag = 0;
	}

	if($flag) {	// EXECUTE ONCE DATA IS VALIDATED
	//	SQL STATEMENT TO UPDATE IS_MANAGER TO 1 (TRUE)
	$sql = "UPDATE Assessor SET is_manager = 1 WHERE '$assessor_ID' = assessor_ID";
	$result = mysqli_query($conn, $sql);
	}
}
?>

<form method="post" class="create_activity">
	<div>
		<h1>Choose an assessor</h1>
		<span class="error"><?php echo $error_assessor;?></span>

		<table border = "1">
			<tr>
				<th>Choose</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Date of Birth</th>
			</tr>

<?php
// RETRIEVE ASSESSORS DETAILS
$sql = "SELECT assessor_ID, first_name, last_name, date_of_birth, is_manager FROM Assessor WHERE is_manager = 0";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($fetch = mysqli_fetch_assoc($result)) {

		// DISPLAY ASSESSOR DETAILS
		echo "<tr>
				 		<td><input type= 'radio' name='assessor_ID' value= ".$fetch['assessor_ID']."></td>
				 		<td>{$fetch['first_name']}</td>
				 		<td>{$fetch['last_name']}</td>
				 		<td>". DATE('d/m/Y',strtotime($fetch['date_of_birth'])) ."</td>
				</tr>";
  	}
}
?>
		</table>
		<!--	RECRUIT  BUTTON	-->
		<input type="submit" id="btn_activate"name="submit" value="RECRUIT">
	</div>
</form>

<!--	ALLOW TO GO BACK TO PROFILE	-->
<span id='back_logout'><a href='manager_profile.php'>Back</a></span>

<?php include "templates/footer.php"; ?>

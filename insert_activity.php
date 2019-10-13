<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "manager_auth.php"; ?>	<!--	AUTHORIZE MANAGERS	-->
<title>Insert Activity</title>

<?php
	$error_activity_name = $error_activity_description = "";

	if (isset($_POST['submit'])) {	// EXECUTE ONCE INSERT BUTTON HAS BEEN CLICKED
		$flag = 1;

		// INPUT SECTION
		$section = check_input($_POST['section']);

		// CHECK IF AN ACTIVITY NAME HAS BEEN ENTERED
		if(!empty($_POST['activity_name'])) {
			$activity_name = check_input($_POST['activity_name']);
		} else {
			$error_activity_name = "Please enter the name";
			$flag = 0;
		}

		// CHECK IF AN ACTIVITY DESCRIPTION HAS BEEN ENTERED
		if(!empty($_POST['activity_description'])) {
			$activity_description = check_input($_POST['activity_description']);
		} else {
			$error_activity_description = "Please enter the description";
			$flag = 0;
		}

		if($flag) {	// EXECUTE ONE ALL FIELDS HAVE BEEN ENTERED
			$sql = "INSERT INTO Activity(section, activity_name, activity_description) VALUES ('$section', '$activity_name', '$activity_description')";
			if(mysqli_query($conn,$sql)) { header("Location: manager_profile.php"); }
		}
	}
?>

<div class="insert_activity">
<h1>Insert an activity</h1>

<div>
	<form method = "post">
		<label>Section</label><br>
		<select name="section">
    		<option value="V">Volunteering</option>
    		<option value="P">Physical</option>
    		<option value="S">Skills</option>
    		<option value="E">Expedition</option>
  		</select><br>

		<label for="activity_name">Activity Name</label><br>
		<span class="error"><?php echo $error_activity_name;?></span>
    <input type="text" name="activity_name" id="activity_name">

		<label for="activity_description">Activity Description</label>
		<span class="error"><?php echo $error_activity_description;?></span>
    <textarea name="activity_description" rows="3" cols="30"></textarea>
		<!--	INSERT BUTTON	-->
		<input type="submit" name="submit" value="INSERT">

	</form>
</div>

</div>

<!--	ALLOW TO GO BACK TO PROFILE	-->
<span id='back_logout'><a href='manager_profile.php'>Back</a></span>

<?php include "templates/footer.php"; ?>

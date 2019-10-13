<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->

<?php
$error_username = $error_password = "";
$username = "";

if(isset($_GET['user'])) {	// Determines user type
	$user = $_GET['user'];
} else {
	$user = 'Participant';
}

if (isset($_POST["login"])) {	// EXECUTE ONCE LOGIN BUTTON IS CLICKED
	// INPUT USERNAME
	$username = stripslashes($_POST['username']);
	$username = mysqli_real_escape_string($conn,$username);
	// INPUT PASSWORD
	$password = stripslashes($_POST['password']);
	$password = mysqli_real_escape_string($conn,$password);

	if(empty($username)) {			// PRESENCE CHECK USERNAME
		$error_username = "Please enter your username";
	} elseif(empty($password)) {	// PRESENCE CHECK PASSWORD
		$error_password = "Please enter your password";
	} else {	// EXECUTE ONCE USERNAME AND PASSWORD ARE ENTERED

		if($user == 'Participant') {	// EXECUTE IF USER IS A PARTICIPANT
			$sql = "SELECT 1 FROM Participant WHERE username = '$username'";
			$result = mysqli_query($conn,$sql) or die(mysql_error());
			$rows = mysqli_num_rows($result);

			if($rows==1) {	// EXECUTE IF USERNAME EXITS IN DATABASE
				$sql = "SELECT participant_ID, paid_date FROM Participant WHERE username= '$username' AND password = '".md5($password)."'";
				$result = mysqli_query($conn,$sql) or die(mysql_error());
				$rows = mysqli_num_rows($result);
				$fetch = mysqli_fetch_assoc($result);

				$participant_ID = $fetch['participant_ID'];
				$active = $fetch['paid_date'];

				if($rows==1) {	// EXECUTE IF PASSWORD IS CORRECT

					if(empty($active)) {
						$_SESSION['inactive_ID']= $participant_ID;
						// REDIRECT TO INACTIVE PROFILE IF PARTICIPANT PAYMENT DATE IS NULL
						header("Location: inactive_profile.php");
					} else {
						$_SESSION['participant_ID']= $participant_ID;
						header("Location: participant_profile.php");
					}

				} else { $error_password = "Incorrect password"; }

			} else { $error_username = "Username does not exist"; }

		} elseif ($user == 'Assessor') {	// EXECUTE IF USER IS AN ASSESSOR
			$sql = "SELECT 1 FROM Assessor WHERE username = '$username'";
	 		$result = mysqli_query($conn,$sql) or die(mysql_error());
	 		$rows = mysqli_num_rows($result);

			if($rows==1) {	// EXECUTE IF USERNAME EXITS IN DATABASE
		 		$sql = "SELECT assessor_ID, is_manager FROM Assessor WHERE username= '$username' AND password= '".md5($password)."'";
				$result = mysqli_query($conn,$sql) or die(mysql_error());
				$rows = mysqli_num_rows($result);
				$fetch = mysqli_fetch_assoc($result);

				$assessor_ID = $fetch['assessor_ID'];
				$is_manager = $fetch['is_manager'];

				if($rows==1) {	// EXECUTE IF PASSWORD IS CORRECT

					if($is_manager) {
						$_SESSION['manager_ID'] = $assessor_ID;
						// REDIRECT TO MANAGER PROFILE IF THE ASSESSOR IS A MANAGER
						header("Location: manager_profile.php");
					} else {
						$_SESSION['assessor_ID']= $assessor_ID;
						header("Location: assessor_profile.php");
					}

				} else { $error_password = "Incorrect password"; }

			} else { $error_username = "Username does not exist"; }

		}
	}
}
?>

<div class="login_container">

	<div>
		<form method="get" class="user_div">
			<input type="submit" name="user" value="Participant" <?php echo($user == 'Participant' ? 'id= user' : ''); ?>>
			<input type="submit" name="user" value="Assessor" <?php echo($user == 'Assessor' ? 'id= user' : ''); ?>>
		</form>
	</div>

	<form method="post" name="login">

		<input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
		<span class= "error"><?php echo $error_username; ?></span>

		<input type="password" name="password" placeholder="Password">
		<span class= "error"><?php echo $error_password; ?></span>

		<input type="submit" name="login" value="Login">
		
	</form>
	
</div>

<?php include "templates/footer.php"; ?>

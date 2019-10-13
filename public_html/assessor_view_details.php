<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "assessor_auth.php"; ?>	<!--	AUTHORIZE ASSESSOR	-->
<?php include "validation.php"; ?>	<!--	IMPORT VALIDATION FUNCTIONS	-->

<?php
// GET ID OF LOGGED ASSESSOR
if(isset($_SESSION['manager_ID'])){
	$assessor_ID = $_SESSION['manager_ID'];
} else {
	$assessor_ID = $_SESSION['assessor_ID'];
}

// SQL STATEMENT TO RETREIVE ASSESSOR'S DETAILS
$sql = "SELECT first_name, last_name, date_of_birth, email_address, telephone, username FROM Assessor WHERE assessor_ID = '$assessor_ID'";
$result = mysqli_query($conn, $sql);
$fetch = mysqli_fetch_assoc($result);

$field = array(array());	// DECLARE MULTI-DIMENSIONAL ASSOCIATIVE ARRAY

// STORE FIELD NAMES REQUIRED TO BE INPUTTED
$field_names = array('email_address','telephone','username','current_password','new_password','confirm_password');

// GIVE EACH FIELD AN INPUT AND ERROR KEY
foreach ($field_names as $name) {
	if(!empty($fetch[$name])) {
		$field[$name]['input'] = $fetch[$name];	// ASSIGN DATABASE VALUE
	} else {
		$field[$name]['input'] = '';
	}
		$field[$name]['error'] = '';	// ASSIGN NULL VALUE
}

if (isset($_POST["update"])) {	// EXECUTE IF UPDATE BUTTON IS CLICKED

	// VALIDATE EMAIL ADDRESS
	$field['email_address'] = presence_check('email_address','Please enter your new email address');
	if(isset($_POST['email_address'])) {
		$field['email_address'] = validate_email($field['email_address']['input'], 'Please enter a valid email address');
	}

	// VALIDATE MOBILE NUMBER
	$field['telephone'] = presence_check('telephone','Please enter your new telephone number');
	if(isset($_POST['telephone'])) {
		$field['telephone'] = validate_phone_number($field['telephone']['input'], 'Please enter a valid telephone number');
	}

	// VALIDATE USERNAME
	$field['username'] = presence_check('username','Please enter a username');
	if(!empty($_POST['username'])) {
		if($field['username']['input'] <> $fetch['username']) {
			$field['username'] = validate_username($field['username']['input'], $conn, 'Assessor');
		}
	}

	// VALIDATE CURRENT PASSWORD WITH ACTUAL PASSWORD IN DATABASE
	$field['current_password']['input'] = check_input($_POST['current_password']);
	if(!empty($_POST['current_password'])) {
		$sql = "SELECT 1 FROM Assessor WHERE assessor_ID = $assessor_ID AND password = '".md5($field['current_password']['input'])."'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0) {
			$field['current_password']['error'] = "Incorrect password";
		} else {

			// VALIDATE NEW PASSWORD
			$field['new_password'] = presence_check('new_password','Please enter a new password');
			if(!empty($_POST['new_password'])) {
				$field['new_password'] = validate_password($field['new_password']['input']);
			}

			// CONFIRM THE NEW PASSWORD
			$field['confirm_password'] = presence_check('confirm_password','Please re-enter your new password');
			if(!empty($_POST['confirm_password'])) {
				$field['confirm_password'] = verify_password($field['confirm_password']['input'], $field['new_password']['input']);
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

	if(!empty($_POST['new_password'])) {
		// SQL CODE TO UPDATE PASSWORD IF A NEW PASSWORD IS ENTERED
		$update_password = ", password = '".md5($field['new_password']['input'])."'";
	}

	// SQL STATEMENT TO UPDATE ASSESSOR'S DETAILS INTO DATABASE
	$sql = "UPDATE Assessor SET email_address = '{$field['email_address']['input']}', telephone = '{$field['telephone']['input']}', username = '{$field['username']['input']}' $update_password WHERE assessor_ID = '$assessor_ID'";

	// EXECUTE SQL QUERY
	if(mysqli_query($conn,$sql)) { header("Location: assessor_view_details.php"); }

}

}

//	RESET EVERY FIELD BY REFRESHING THE PAGE
if(isset($_POST['reset'])) { header("Refresh:0"); }

// DISPLAY PERSONAL DETAILS
 echo "
 <div class = 'display_details'>
	<div>

			<h2>Personal Details</h2>

			<h4>First Name</h4>
    	<p>{$fetch['first_name']}</p>

    	<h4>Last Name</h4>
    	<p>{$fetch['last_name']}</p>

    	<h4>Date of Birth</h4>
			<p>". DATE('d/m/Y',strtotime($fetch['date_of_birth'])) ."</p>

	</div>

	<div>
		<form method='post'>

			<!--	DISPLAY CONTACT DETAILS	-->

      <h2>Contact Details</h2>

			<label for='email_address'>Email Address</label>
    	<input type='text' name='email_address' id='email_address' value= '{$field['email_address']['input']}'>
			<span class='error'>{$field['email_address']['error']}</span>

			<label for='telephone'>Telephone</label>
  		<input type='text' name='telephone' id='telephone' value = '{$field['telephone']['input']}'>
			<span class= 'error'>{$field['telephone']['error']}</span>

			<input type='submit' name='reset' value='RESET'>
			<input type='submit' name='update' value='UPDATE'>

	</div>

	<div>
			<!--	DISPLAY LOGIN DETAILS	-->

			<h2>Login Details</h2>

			<label for='username'>Username</label>
			<input type='text' name='username' id='username' value = '{$field['username']['input']}'>
			<span class='error'>{$field['username']['error']}</span>

			<!--	INPUT FOR PASSWORDS	-->

			<h3>Change your password</h3>
			<label for='current_password'>Enter current password</label>
			<input type='password' name='current_password' id='current_password' value= '{$field['current_password']['input']}'>
			<span class='error'>{$field['current_password']['error']}</span>

			<label for='new_password'>Enter new password</label>
			<input type='password' name='new_password' id='new_password' value= '{$field['new_password']['input']}'>
			<span class='error'>{$field['new_password']['error']}</span>

			<label for='confirm_password'>Confirm new password</label>
			<input type='password' name='confirm_password' id='confirm_password' value= '{$field['confirm_password']['input']}'>
			<span class='error'>{$field['confirm_password']['error']}</span>

			<!--	UPDATE BUTTON	-->
			<input type='submit' name='update' value='UPDATE'>

		</form>
	</div>
</div>";

// GO BACK TO ASSESSOR OR MANAGER PROFILE DEPENDING ON USER
if(isset($_SESSION['manager_ID'])){
	echo "<span id='back_logout'><a href='manager_profile.php'>Back</a></span>";
} else {
	echo "<span id='back_logout'><a href='assessor_profile.php'>Back</a></span>";
}
?>

<?php include "templates/footer.php"; ?>

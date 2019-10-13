<?php include "dbconnection.php"; ?>			<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "participant_auth.php"; ?>	<!--	AUTHORIZE PARTICIPANTS	-->
<?php include "validation.php"; ?>				<!--	IMPORT VALIDATION FUNCTIONS	-->
<title>View your details</title>

<?php
$participant_ID = $_SESSION['participant_ID'];

// SQL STATEMENT TO RETREIVE PARTICIPANT'S DETAILS
$sql = "SELECT participant_level, first_name, last_name, date_of_birth, email_address, mobile_number, address, town_city, county, post_code, username FROM Participant WHERE participant_ID = '$participant_ID'";
$result = mysqli_query($conn, $sql);
$fetch = mysqli_fetch_assoc($result);

$field = array(array());	// DECLARE MULTI-DIMENSIONAL ASSOCIATIVE ARRAY

// STORE FIELD NAMES REQUIRED TO BE INPUTTED
$field_names = array('email_address','mobile_number','address','town_city','county','post_code','username','current_password','new_password','confirm_password');

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
	if(!empty($_POST['email_address'])) {
		$field['email_address'] = validate_email($field['email_address']['input'], 'Please enter a valid email address');
	}

	// VALIDATE MOBILE NUMBER
	$field['mobile_number'] = presence_check('mobile_number','Please enter your new mobile number');
	if(!empty($_POST['mobile_number'])) {
		$field['mobile_number'] = validate_phone_number($field['mobile_number']['input'], 'Please enter a valid mobile number');
	}

	// PRESENCE CHECK ADDRESS
	$field['address'] = presence_check('address','Please enter your new address');

	// PRESENCE CHECK TOWN/CITY
	$field['town_city'] = presence_check('town_city','Please enter the new town/city you live in');

	// INPUT COUNTY
	$field['county']['input'] = check_input($_POST['county']);

	// VALIDATE POST CODE
	$field['post_code'] = presence_check('post_code','Please enter your new post code');
	if(!empty($_POST['post_code'])) {
		$field['post_code'] = validate_post_code($field['post_code']['input']);
	}

	// VALIDATE USERNAME
	$field['username'] = presence_check('username','Please enter a username');
	if(!empty($_POST['username'])) {
		if($field['username']['input'] <> $fetch['username']) {
			$field['username'] = validate_username($field['username']['input'], $conn, 'Participant');
		}
	}

// VALIDATE CURRENT PASSWORD WITH ACTUAL PASSWORD IN DATABASE
$field['current_password']['input'] = check_input($_POST['current_password']);
if(!empty($_POST['current_password'])) {
$sql = "SELECT 1 FROM Participant WHERE participant_ID = $participant_ID AND password = '".md5($field['current_password']['input'])."'";
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

	// SQL CODE TO UPDATE PASSWORD IF A NEW PASSWORD IS ENTERED
	if(!empty($_POST['new_password'])) {
		$update_password = ", password = '".md5($field['new_password']['input'])."'";
	}

	// SQL STATEMENT TO UPDATE PARCIPANT'S DETAILS INTO DATABASE
	$sql = "UPDATE Participant SET email_address = '{$field['email_address']['input']}', mobile_number = '{$field['mobile_number']['input']}', address = '{$field['address']['input']}', town_city = '{$field['town_city']['input']}', county = '{$field['county']['input']}', post_code = '{$field['post_code']['input']}', username = '{$field['username']['input']}' $update_password WHERE participant_ID = '$participant_ID'";

	// EXECUTE SQL QUERY
	if(mysqli_query($conn,$sql)) { header("Location: participant_view_details.php"); }
}

}


if(isset($_POST['reset'])) { header("Refresh:0"); }

 echo "
 <div class = 'display_details'>
	<!--	DISPLAY PERSONAL DETAILS	-->
	<div>

		<h2>Personal Details</h2>

		<h4>Level</h4>
		<p>{$fetch['participant_level']}</p>

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
    	<input type='text' name='email_address' id='email_address' value = '{$field['email_address']['input']}'>
			<span class='error'>{$field['email_address']['error']}</span>

			<label for='mobile_number'>Mobile Number</label>
    	<input type='text' name='mobile_number' id='mobile_number' value = '{$field['mobile_number']['input']}'>
			<span class='error'>{$field['mobile_number']['error']}</span>

			<label for='address'>Address</label>
    	<input type='text' name='address' id='address' value = '{$field['address']['input']}'>
			<span class='error'>{$field['address']['error']}</span>

			<label for='town_city'>Town/City</label>
    	<input type='text' name='town_city' id='town_city' value = '{$field['town_city']['input']}'>
			<span class='error'>{$field['town_city']['error']}</span>

			<label for='county'>County</label>
    	<input type='text' name='county' id='county' value = '{$field['county']['input']}' placeholder= 'No entry'>

			<label for='post_code'>Post Code</label>
    	<input type='text' name='post_code' id='post_code' value = '{$field['post_code']['input']}'>
			<span class='error'>{$field['post_code']['error']}</span>

			<input type='submit' name='reset' value='RESET'>
			<input type='submit' name='update' value='UPDATE'>

		</div>
		<div>

			<!--	DISPLAY LOGIN DETAILS	-->
			<h2>Login Details</h2>

			<label for='username'>Username</label>
			<input type='text' name='username' id='username' value = '{$field['username']['input']}'>
			<span class='error'>{$field['username']['error']}</span>

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

			<input type='submit' name='update' value='UPDATE'>

		</form>
</div>

</div>";

?>

<!--	ALLOW TO GO BACK TO PROFILE	-->
<span id="back_logout"><a href="participant_profile.php">Back</a></span>

<?php include "templates/footer.php"; ?>

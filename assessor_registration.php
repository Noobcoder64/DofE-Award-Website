<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->
<?php include "validation.php"; ?>	<!--	IMPORT VALIDATION FUNCTIONS	-->
<title>Assessor Registration</title>

<?php

$field = array(array());	// DECLARE MULTI-DIMENSIONAL ASSOCIATIVE ARRAY

// STORE FIELD NAMES REQUIRED TO BE INPUTTED
$field_names = array('first_name','last_name','gender','date_of_birth','email_address','telephone','username','password','confirm_password');

// GIVE EACH FIELD AN INPUT AND ERROR KEY
foreach ($field_names as $name) {
	$field[$name]['input'] = '';	// ASSIGN NULL VALUE
	$field[$name]['error'] = '';	// ASSIGN NULL VALUE
}

if (isset($_POST['submit'])) {

	// VALIDATE FIRST NAME
	$field['first_name'] = presence_check('first_name','Please enter your first name');
	if(!empty($_POST['first_name'])) {
		$field['first_name'] = validate_name($field['first_name']['input'], 'Can only contain letters');
	}

	// VALIDATE LAST NAME
	$field['last_name'] = presence_check('last_name','Please enter your last name');
	if(!empty($_POST['last_name'])) {
		$field['last_name'] = validate_name($field['last_name']['input'], 'Can only contain letters');
	}

	// VALIDATE GENDER
	$field['gender'] = presence_check('gender','Please select');

	// VALIDATE DATE OF BIRTH
	$field['date_of_birth'] = presence_check('date_of_birth','Please enter your date of birth');
	if(!empty($_POST['date_of_birth'])) {
		$current_date = date('Y-m-d');
		if($field['date_of_birth']['input'] > $current_date) {
			$field['date_of_birth']['error'] = "Please enter a valid date of birth";
		} else {
			// CALCULATE AGE
			$current_time_stamp = time();
			$age = floor(($current_time_stamp - strtotime($field['date_of_birth']['input']))/ 31556926);
			if ($age < 25) {
				$field['date_of_birth']['error'] = "You are too young to be an assessor";
				$flag = 0;
			}
		}
	}

	// VALIDATE EMAIL ADDRESS
	$field['email_address'] = presence_check('email_address','Please enter your email address');
	if(!empty($_POST['email_address'])) {
			$field['email_address'] = validate_email($field['email_address']['input'], 'Please enter a valid email address');
	}

	// VALIDATE MOBILE NUMBER
	$field['telephone'] = presence_check('telephone','Please enter your mobile number');
	if(!empty($_POST['telephone'])) {
			$field['telephone'] = validate_phone_number($field['telephone']['input'], 'Please enter a valid telephone number');
	}

	// VALIDATE USERNAME
	$field['username'] = presence_check('username','Please enter a username');
	if(!empty($_POST['username'])) {
			$field['username'] = validate_username($field['username']['input'], $conn, 'Assessor');
	}

	// VALIDATE PASSWORD
	$field['password'] = presence_check('password','Please enter a password');
	if(!empty($_POST['password'])) {
			$field['password'] = validate_password($field['password']['input']);
	}

	// VERIFY CONFIRM PASSWORD
	$field['confirm_password'] = presence_check('confirm_password','Please re-enter your passoword');
	if(!empty($_POST['confirm_password'])) {
			$field['confirm_password'] = verify_password($field['confirm_password']['input'], $field['password']['input']);
	}

	// CHECK IF EVERY INPUT IS VALID
	foreach ($field as $field_name) {
		$flag = 1;  // INDICATES IF DATA IS VALID
		if(!empty($field_name['error'])) {
				$flag = 0;	// INDICATOR ASSIGNED 0 IF ERROR MESSAGE HAS BEEN DISPLAYED
				break;
		}
	}

		if($flag) {	// EXECUTE IF ALL INPUTS ARE VALID

			// CREATE ARRAY WITH NAMES OF FIELDS THAT NEED TO BE INSERTED
			$fields = array_slice($field_names, 0, 7);
			// PUTS A COMMA BETWEEN IN EACH FIELD AND CONVERTS INTO STRING
			$fields_string = implode(',', $fields);

			// STORE VALUES THAT NEED TO BE INSERTED INTO AN ARRAY
			$values = array();
			foreach ($fields as $field_name) {
				$values[] = $field[$field_name]['input'];	// APPEND INPUT DATA INTO THE ARRAY
			}
			// PUT A COMMA BETWEEN IN EACH FIELD AND CONVERT INTO STRING
			$values_string = "'" . implode("','", $values) . "'";

			// SQL STATEMENT TO INSERT ASSESSOR'S DETAILS INTO DATABASE
			$sql = "INSERT INTO Assessor ($fields_string, password) VALUES ($values_string,'".md5($field['password']['input'])."')";

			// EXECUTE SQL QUERY
			if(mysqli_query($conn,$sql)) { header("Location: login.php?user=Assessor"); }
		}
}
?>

<form method="post" class= "enrolment_form">
<h1>Assessor Registration</h1>

	<h3>Personal Details</h3>

	<div>
		<label for="first_name">First Name</label>
		<input type="text" name="first_name" id="first_name" value="<?php echo $field['first_name']['input'];?>" placeholder="<?php echo $field['first_name']['error'];?>">

		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" id="last_name" value="<?php echo $field['last_name']['input'];?>" placeholder="<?php echo $field['last_name']['error'];?>">

		<label for="gender">Gender</label>
		<div id="radio">
			<input type="radio" name="gender" id="gender" value="M" <?php echo($field['gender']['input'] == 'M' ? 'checked' : '')?> >Male
			<input type="radio" name="gender" id="gender" value="F" <?php echo($field['gender']['input'] == 'F' ? 'checked' : '')?> >Female
			<span class="error"><?php echo $field['gender']['error'];?></span>
		</div>

		<label for="date_of_birth">Date of Birth</label>
		<div id="date">
			<input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo $field['date_of_birth']['input'];?>">
			<span class="error"><?php echo $field['date_of_birth']['error'];?></span>
		</div>
	</div>

<h3>Contact Details</h3>

	<div>
		<label for="email_address">Email Address</label>
		<input type="text" name="email_address" id="email_address" value="<?php echo $field['email_address']['input'];?>" placeholder="<?php echo $field['email_address']['error'];?>">

		<label for="telephone">Telephone</label>
		<input type="text" name="telephone" id="telephone" value="<?php echo $field['telephone']['input'];?>" placeholder="<?php echo $field['telephone']['error'];?>">
	</div>

<h3>Login Details</h3>

	<div>
		<div>
			<label for="username">Enter a username</label>
		 <input type="text" name="username" id="username" value="<?php echo $field['username']['input'];?>" placeholder="<?php echo $field['username']['error'];?>">
		</div>

		<div>
			<label for="password">Enter a password</label>
		 	<input type="password" name="password" id="password" value="<?php echo $field['password']['input'];?>" placeholder="<?php echo $field['password']['error'];?>">
		</div>

		<div>
			<label for="confirm_password">Confirm password</label>
		<input type="password" name="confirm_password" id="confirm_password" value="<?php echo $field['confirm_password']['input'];?>" placeholder="<?php echo $field['confirm_password']['error'];?>">
		</div>
	</div>

		<!-- REGISTER BUTTON -->
		<input type="submit" name="submit" id="btn_enrol" value="REGISTER">
	</form>
</div>

<?php include "templates/footer.php"; ?>

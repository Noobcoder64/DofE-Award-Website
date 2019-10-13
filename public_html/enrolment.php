<?php include "dbconnection.php"; ?>			<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>		<!--	DISPLAY HEADER	-->
<?php include "validation.php"; ?>				<!--	IMPORT VALIDATION FUNCTIONS	-->
<title>Enrolment Form</title>

<?php
$field = array(array());	// DECLARE MULTI-DIMENSIONAL ASSOCIATIVE ARRAY

// STORE FIELD NAMES REQUIRED TO BE INPUTTED
$field_names = array('student_number','participant_level','previously_registered','centre_name','eDofe_ID','pathway_enrichment','on_bursary','first_name','last_name','gender','date_of_birth','primary_language','shoe_size','email_address','mobile_number','telephone','address','town_city','county','post_code','emergency_name_1','emergency_mobile_1','emergency_home_1','emergency_work_1','relationship_1','emergency_name_2','emergency_mobile_2','emergency_home_2','emergency_work_2','relationship_2','doctor_name','doctor_telephone','medical_condition','medicine','injection_date','food_dislikes','other_info','username','password','confirm_password');

// GIVE EACH FIELD AN INPUT AND ERROR KEY
foreach ($field_names as $name) {
	$field[$name]['input'] = '';	// ASSIGN NULL VALUE
	$field[$name]['error'] = '';	// ASSIGN NULL VALUE
}

if (isset($_POST['submit'])) { // EXECUTE ONCE FORM HAS BEEN SUBMITTED

	// VALIDATE STUDENT NUMBER
	if(!empty($_POST['student_number'])) {
		$field['student_number']['input'] = check_input($_POST['student_number']);
		if((strlen($field['student_number']['input']) <> 8)) {
      		$field['student_number']['error'] = "Must be exactly 8 characters long";
			$field['student_number']['input'] = "";
  		}
	}

	// INPUT PARTICIPANT LEVEL
	$field['participant_level']['input'] = check_input($_POST['participant_level']);

	// PRESENCE CHECK IF PREVIOSLY REGISTERED ON DOFE
	$field['previously_registered'] = presence_check('previously_registered','Please select');

	// INPUT CENTRE NAME
	$field['centre_name']['input'] = check_input($_POST['centre_name']);

	// INPUT eDofE ID
	$field['eDofe_ID']['input'] = check_input($_POST['eDofE_ID']);

	// PRESENCE CHECK PATHWAY OR ENRICHMENT
	$field['pathway_enrichment'] = presence_check('pathway_enrichment','Please select');

	// PRESENCE CHECK IF ON BURSARY
	$field['on_bursary'] = presence_check('on_bursary','Please select');

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
			$current_date = date('Y-m-d');
			// VALIDATE AGE
			if($age > 24) {
				$field['date_of_birth']['error'] = "You are too old to enrol";
			} elseif($age < 14) {
				$field['date_of_birth']['error'] = "You are too young to enrol";
			}
		}
	}

	// VALIDATE PRIMARY LANGUAGE
	$field['primary_language'] = presence_check('primary_language','Please select');

	// INPUT SHOE SIZE
	$field['shoe_size']['input'] = check_input($_POST['shoe_size']);

	// VALIDATE EMAIL ADDRESS
	$field['email_address'] = presence_check('email_address','Please enter your email address');
	if(!empty($_POST['email_address'])) {
			$field['email_address'] = validate_email($field['email_address']['input'], 'Please enter a valid email address');
	}

	// VALIDATE MOBILE NUMBER
	$field['mobile_number'] = presence_check('mobile_number','Please enter your mobile number');
	if(!empty($_POST['mobile_number'])) {
			$field['mobile_number'] = validate_phone_number($field['mobile_number']['input'], 'Please enter a valid mobile number');
	}

	// VALIDATE TELEPHONE NUMBER
	if (!empty($_POST['telephone'])) {
		$field['telephone']['input'] = check_input($_POST['telephone']);
		$field['telephone'] = validate_phone_number($field['telephone']['input'], 'Please enter a valid telephone number');
	}

	// PRESENCE CHECK ADDRESS
	$field['address'] = presence_check('address','Please enter your address');

	// PRESENCE CHECK TOWN/CITY
	$field['town_city'] = presence_check('town_city','Please enter the town/city you live in');

	// INPUT COUNTY
	$field['county']['input'] = check_input($_POST['county']);

	// VALIDATE POST CODE
	$field['post_code'] = presence_check('post_code','Please enter your post code');
	if(!empty($_POST['post_code'])) {
			$field['post_code'] = validate_post_code($field['post_code']['input']);
	}

	// VALIDATE EMERGENCY NAME 1
	$field['emergency_name_1'] = presence_check('emergency_name_1','Please enter an emergency contact name');
	if(!empty($_POST['emergency_name_1'])) {
			$field['emergency_name_1'] = validate_name($field['emergency_name_1']['input'], 'Can only contain letters');
	}

	// VALIDATE EMERGENCY MOBILE NUMBER 1
	$field['emergency_mobile_1'] = presence_check('emergency_mobile_1','Please enter a mobile number');
	if(!empty($_POST['emergency_mobile_1'])) {
			$field['emergency_mobile_1'] = validate_phone_number($field['emergency_mobile_1']['input'], 'Please enter a valid mobile number');
	}

	// VALIDATE EMERGENCY HOME TELEPHONE NUMBER 1
	if (!empty($_POST['emergency_home_1'])) {
			$field['emergency_home_1']['input'] = check_input($_POST['emergency_home_1']);
			$field['emergency_home_1'] = validate_phone_number($field['emergency_home_1']['input'], 'Please enter a valid telephone number');
	}

	// VALIDATE EMERGENCY WORK TELEPHONE NUMBER 1
	if (!empty($_POST['emergency_work_1'])) {
			$field['emergency_work_1']['input'] = check_input($_POST['emergency_work_1']);
			$field['emergency_work_1'] = validate_phone_number($field['emergency_work_1']['input'], 'Please enter a valid telephone number');
	}

	// VALIDATE RELATIONSHIP 1
	$field['relationship_1'] = presence_check('relationship_1','Please enter your relationship');
	if(!empty($_POST['relationship_1'])) {
			$field['relationship_1'] = validate_name($field['relationship_1']['input'], 'Can only contain letters');
	}

	// VALIDATE EMERGENCY NAME 2
	$field['emergency_name_2'] = presence_check('emergency_name_2','Please enter an emergency contact name');
	if(!empty($_POST['emergency_name_2'])) {
			$field['emergency_name_2'] = validate_name($field['emergency_name_2']['input'], 'Can only contain letters');
	}

	// VALIDATE EMERGENCY MOBILE NUMBER 2
	$field['emergency_mobile_2'] = presence_check('emergency_mobile_2','Please enter a mobile number');
	if(!empty($_POST['emergency_mobile_2'])) {
			$field['emergency_mobile_2'] = validate_phone_number($field['emergency_mobile_2']['input'], 'Please enter a valid mobile number');
	}

	// VALIDATE EMERGENCY HOME TELEPHONE NUMBER 2
	if (!empty($_POST['emergency_home_2'])) {
			$field['emergency_home_2']['input'] = check_input($_POST['emergency_home_2']);
			$field['emergency_home_2'] = validate_phone_number($field['emergency_home_2']['input'], 'Please enter a valid telephone number');
	}

	// VALIDATE EMERGENCY WORK TELEPHONE NUMBER 2
	if (!empty($_POST['emergency_work_2'])) {
			$field['emergency_work_2']['input'] = check_input($_POST['emergency_work_2']);
			$field['emergency_work_2'] = validate_phone_number($field['emergency_work_2']['input'], 'Please enter a valid telephone number');
	}

	// VALIDATE RELATIONSHIP 2
	$field['relationship_2'] = presence_check('relationship_2','Please enter your relationship');
	if(!empty($_POST['relationship_2'])) {
			$field['relationship_2'] = validate_name($field['relationship_2']['input'], 'Can only contain letters');
	}

	// VALIDATE DOCTOR NAME
	$field['doctor_name'] = presence_check('doctor_name','Please enter the name of your doctor');
	if(!empty($_POST['doctor_name'])) {
			$field['doctor_name'] = validate_name($field['doctor_name']['input'], 'Can only contain letters');
	}

	// VALIDATE DOCTOR TELEPHONE NUMBER
	$field['doctor_telephone'] = presence_check('doctor_telephone','Please enter the telephone number of your doctor');
	if(!empty($_POST['doctor_telephone'])) {
		$field['doctor_telephone'] = validate_phone_number($field['doctor_telephone']['input'], 'Please enter a valid telephone number');
	}

	// INPUT MEDICAL CONDITION
	$field['medical_condition']['input'] = check_input($_POST['medical_condition']);

	// INPUT MEDICINE
	$field['medicine']['input'] = check_input($_POST['medicine']);

	// VALIDATE INJECTION DATE
	if(!empty($_POST['injection_date'])) {
			$field['injection_date']['input'] = check_input($_POST['injection_date']);
			if(($field['injection_date']['input'] > $current_date) OR ($field['injection_date']['input'] < $field['date_of_birth']['input'])) {
					$field['injection_date']['error'] = "Please enter a valid date of injection";
			}
	}

	// INPUT FOOD DISLIKES
	$field['food_dislikes']['input'] = check_input($_POST['food_dislikes']);

	// INPUT OTHER INFO
	$field['other_info']['input'] = check_input($_POST['other_info']);

	// VALIDATE USERNAME
	$field['username'] = presence_check('username','Please enter a username');
	if(!empty($_POST['username'])) {
			$field['username'] = validate_username($field['username']['input'], $conn, 'Participant');
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
		$fields = array_slice($field_names, 0, 38);

		// PUTS A COMMA BETWEEN IN EACH FIELD AND CONVERTS INTO STRING
		$fields_string = implode(',', $fields);

		// STORE VALUES THAT NEED TO BE INSERTED INTO AN ARRAY
		$values = array();
		foreach ($fields as $field_name) {
				$values[] = $field[$field_name]['input'];	// APPEND INPUT DATA INTO THE ARRAY
		}
		// PUT A COMMA BETWEEN IN EACH FIELD AND CONVERT INTO STRING
		$values_string = "'" . implode("','", $values) . "'";

		// SQL STATEMENT TO INSERT PARCIPANT'S DETAILS INTO DATABASE
		$sql = "INSERT INTO Participant ($fields_string, password) VALUES ($values_string,'".md5($field['password']['input'])."')";

		// EXECUTE SQL QUERY
		if(mysqli_query($conn,$sql)) { header("Location: login.php?user=Participant"); }

	}
}
?>

<form method="post" class="enrolment_form">
<h1>DofE Enrolment</h1>

<div>
	<div>
		<label for="student_number">Student Number (If known)</label>
    <input type="text" name="student_number" id="student_number" value="<?php echo $field['student_number']['input'];?>" placeholder="<?php echo $field['student_number']['error'];?>">
	</div>
</div>

<h3>DofE Level</h3>

<div>
	<label>Level<span id="required">*</span></label>
	<select name="participant_level" value="<?php echo $field['participant_level']['input'];?>">
  	<option value="Bronze" <?php echo($field['participant_level']['input'] == 'Bronze' ? 'selected' : '')?> >Bronze</option>
    <option value="Silver" <?php echo($field['participant_level']['input'] == 'Silver' ? 'selected' : '')?> >Silver</option>
    <option value="Gold" <?php echo($field['participant_level']['input'] == 'Gold' ? 'selected' : '')?> >Gold</option>
  </select>

	<label for="previously_registered">Have you registered previously on DofE?<span id="required">*</span></label>
	<div id="radio">
		<input type="radio" name="previously_registered" id="previously_registered" value= 'Y' <?php echo($field['previously_registered']['input'] == 'Y' ? 'checked' : '')?> >Yes
		<input type="radio" name="previously_registered" id="previously_registered" value= 'N' <?php echo($field['previously_registered']['input'] == 'N' ? 'checked' : '')?> >No
		<span class="error"><?php echo $field['previously_registered']['error'];?></span>
	</div>

	<label for="centre_name">Name of previous DofE Centre</label>
  <input type="text" name="centre_name" id="centre_name" value="<?php echo $field['centre_name']['input'];?>" placeholder= "If previously registered">

	<label for="eDofE_ID">eDofE ID number</label>
  <input type="text" name="eDofE_ID" id="eDofE_ID" value="<?php echo $field['eDofe_ID']['input'];?>" placeholder= "If known">

	<label for="pathway_enrichment">Please tick as appropriate<span id="required">*</span>: This is my</label>
	<div id="radio">
		<input type="radio" name="pathway_enrichment" id="pathway_enrichment" value="P" <?php echo($field['pathway_enrichment']['input'] == 'P' ? 'checked' : '')?> >Pathway
		<input type="radio" name="pathway_enrichment" id="pathway_enrichment" value="E" <?php echo($field['pathway_enrichment']['input'] == 'E' ? 'checked' : '')?> >Enrichment
		<span class="error"><?php echo $field['pathway_enrichment']['error'];?></span>
	</div>

	<label for="on_bursary">Are you on bursary?<span id="required">*</span></label>
	<div id="radio">
		<input type="radio" name="on_bursary" id="on_bursary" value= 'Y' <?php echo($field['on_bursary']['input'] == 'Y' ? 'checked' : '')?> >Yes
    <input type="radio" name="on_bursary" id="on_bursary" value= 'N' <?php echo($field['on_bursary']['input'] == 'N' ? 'checked' : '')?> >No
		<span class="error"><?php echo $field['on_bursary']['error'];?></span>
	</div>

</div>

<h3>Personal Details</h3>

<div>

	<label for="first_name">First Name<span id="required">*</span></label>
  <input type="text" name="first_name" id="first_name" value="<?php echo $field['first_name']['input'];?>" placeholder="<?php echo $field['first_name']['error'];?>">

  <label for="last_name">Last Name<span id="required">*</span></label>
  <input type="text" name="last_name" id="last_name" value="<?php echo $field['last_name']['input'];?>" placeholder="<?php echo $field['last_name']['error'];?>">

  <label for="gender">Gender<span id="required">*</span></label>
	<div id="radio">
		<input type="radio" name="gender" id="gender" value="M" <?php echo($field['gender']['input'] == 'M' ? 'checked' : '')?> >Male
    <input type="radio" name="gender" id="gender" value="F" <?php echo($field['gender']['input'] == 'F' ? 'checked' : '')?> >Female
		<span class="error"><?php echo $field['gender']['error'];?></span>
	</div>

	<label for="date_of_birth">Date of Birth<span id="required">*</span></label>
	<div id="date">
    <input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo $field['date_of_birth']['input'];?>">
		<span class="error"><?php echo $field['date_of_birth']['error'];?></span>
	</div>

  <label for="primary_language">Primary Language<span id="required">*</span></label>
	<div id="radio">
		<input type="radio" name="primary_language" id="primary_language" value="E" <?php echo($field['primary_language']['input'] == 'E' ? 'checked' : '')?> >English
		<input type="radio" name="primary_language" id="primary_language" value="O" <?php echo($field['primary_language']['input'] == 'O' ? 'checked' : '')?> >Other
		<span class="error"><?php echo $field['primary_language']['error'];?></span>
	</div>

	<label>Shoe Size<span id="required">*</span></label>
	<select name="shoe_size">
		<?php for($i = 2.5; $i <= 12; $i += 0.5) {
    	echo "<option value= $i ". ($field['shoe_size']['input'] == $i ? "selected" : "") ." >$i</option>";
		} ?>
	</select>

</div>

<h3>Contact Details</h3>

<div>

	<div>
		<label for="email_address">Email Address<span id="required">*</span></label>
    <input type="text" name="email_address" id="email_address" value="<?php echo $field['email_address']['input'];?>" placeholder="<?php echo $field['email_address']['error'];?>">
	</div>

	<label for="mobile_number">Mobile Number<span id="required">*</span></label>
  <input type="text" name="mobile_number" id="mobile_number" value="<?php echo $field['mobile_number']['input'];?>" placeholder="<?php echo $field['mobile_number']['error'];?>">

	<label for="telephone">Telephone</label>
  <input type="text" name="telephone" id="telephone" value="<?php echo $field['telephone']['input'];?>" placeholder="<?php echo $field['telephone']['error'];?>">

	<div>
		<label for="address">Address<span id="required">*</span></label>
    <input type="text" name="address" id="address" value="<?php echo $field['address']['input'];?>" placeholder="<?php echo $field['address']['error'];?>">
	</div>

	<div>
		<label for="town_city">Town/City<span id="required">*</span></label>
    <input type="text" name="town_city" id="town_city" value="<?php echo $field['town_city']['input'];?>" placeholder="<?php echo $field['town_city']['error'];?>">
	</div>

	<label for="county">County</label>
  <input type="text" name="county" id="county" value="<?php echo $field['county']['input'];?>">

	<label for="post_code">Post Code<span id="required">*</span></label>
  <input type="text" name="post_code" id="post_code" value="<?php echo $field['post_code']['input'];?>" placeholder="<?php echo $field['post_code']['error'];?>">

</div>

<h3>Emergency Contact Details</h3>

<div id="emergency_contacts">

	<div>

		<h4>(1) Emergency Contact Details</h4>

		<label for="emergency_name_1" id='item1'>Name<span id="required">*</span></label>
    <input type="text" name="emergency_name_1" id="emergency_name_1" value="<?php echo $field['emergency_name_1']['input'];?>" placeholder="<?php echo $field['emergency_name_1']['error'];?>">

    <label for="emergency_mobile_1">Mobile Telephone<span id="required">*</span></label>
    <input type="text" name="emergency_mobile_1" id="emergency_mobile_1" value="<?php echo $field['emergency_mobile_1']['input'];?>" placeholder="<?php echo $field['emergency_mobile_1']['error'];?>">

    <label for="emergency_home_1">Home Telephone</label>
  	<input type="text" name="emergency_home_1" id="emergency_home_1" value="<?php echo $field['emergency_home_1']['input'];?>" placeholder="<?php echo $field['emergency_home_1']['error'];?>">

    <label for="emergency_work_1">Work Telephone</label>
  	<input type="text" name="emergency_work_1" id="emergency_work_1" value="<?php echo $field['emergency_work_1']['input'];?>" placeholder="<?php echo $field['emergency_work_1']['error'];?>">

    <label for="relationship_1">Relationship to you<span id="required">*</span></label>
  	<input type="text" name="relationship_1" id="relationship_1" value="<?php echo $field['relationship_1']['input'];?>" placeholder="<?php echo $field['relationship_1']['error'];?>">

	</div>

	<div>

		<h4>(2) Emergency Contact Details</h4>

    <label for="emergency_name_2">Name<span id="required">*</span></label>
  	<input type="text" name="emergency_name_2" id="emergency_name_2" value="<?php echo $field['emergency_name_2']['input'];?>" placeholder="<?php echo $field['emergency_name_2']['error'];?>">

    <label for="emergency_mobile_2">Mobile Telephone<span id="required">*</span></label>
  	<input type="text" name="emergency_mobile_2" id="emergency_mobile_2" value="<?php echo $field['emergency_mobile_2']['input'];?>" placeholder="<?php echo $field['emergency_mobile_2']['error'];?>">

    <label for="emergency_home_2">Home Telephone</label>
  	<input type="text" name="emergency_home_2" id="emergency_home_2" value="<?php echo $field['emergency_home_2']['input'];?>" placeholder="<?php echo $field['emergency_home_2']['error'];?>">

    <label for="emergency_work_2">Work Telephone</label>
  	<input type="text" name="emergency_work_2" id="emergency_work_2" value="<?php echo $field['emergency_work_2']['input'];?>" placeholder="<?php echo $field['emergency_work_2']['error'];?>">

		<label for="relationship_2">Relationship to you<span id="required">*</span></label>
  	<input type="text" name="relationship_2" id="relationship_2" value="<?php echo $field['relationship_2']['input'];?>" placeholder="<?php echo $field['relationship_2']['error'];?>">

	</div>

</div>

<h3>Doctor Contact Details</h3>

<div>

	<label for="doctor_name">Name<span id="required">*</span></label>
  <input type="text" name="doctor_name" id="doctor_name" value="<?php echo $field['doctor_name']['input'];?>" placeholder="<?php echo $field['doctor_name']['error'];?>">

  <label for="doctor_telephone">Telephone<span id="required">*</span></label>
  <input type="text" name="doctor_telephone" id="doctor_telephone" value="<?php echo $field['doctor_telephone']['input'];?>" placeholder="<?php echo $field['doctor_telephone']['error'];?>">

</div>

<h3>Medical Details</h3>

<div>

	<div>
  	<label for="medical_condition">Please give details of any medical conditions</label>
    <input type="text" name="medical_condition" id="medical_condition" value="<?php echo $field['medical_condition']['input'];?>" placeholder="e.g. diabetes, epilepsy etc. or any allergies e.g. medication, anaesthetics or food">
	</div>

	<div>
    <label for="medicine">Please list any medicines or tablets which you are taking at present</label>
    <input type="text" name="medicine" id="medicine" value="<?php echo $field['medicine']['input'];?>">
	</div>

	<div>
		<label for="injection_date">Date of last tetanus injection, if known</label>
		<div id="date">
			<input type="date" name="injection_date" id="injection_date" value="<?php echo $field['injection_date']['input'];?>">
			<span class="error"><?php echo $field['injection_date']['error'];?></span>
		</div>
	</div>

	<div>
    <label for="food_dislikes">Food dislikes</label>
    <input type="text" name="food_dislikes" id="food_dislikes" value="<?php echo $field['food_dislikes']['input'];?>">
	</div>

	<div>
    <label for="other_info">Any other relevant information</label>
    <input type="text" name="other_info" id="other_info" value="<?php echo $field['other_info']['input'];?>">
	</div>

</div>

<h3>Login Details</h3>

<div>

	<div>
		<label for="username">Enter a username<span id="required">*</span></label>
    <input type="text" name="username" id="username" value="<?php echo $field['username']['input'];?>" placeholder="<?php echo $field['username']['error'];?>">
	</div>

	<div>
		<label for="password">Enter a password<span id="required">*</span></label>
    <input type="password" name="password" id="password" value="<?php echo $field['password']['input'];?>" placeholder="<?php echo $field['password']['error'];?>">
	</div>

	<div>
		<label for="confirm_password">Confirm password<span id="required">*</span></label>
    <input type="password" name="confirm_password" id="confirm_password" value="<?php echo $field['confirm_password']['input'];?>" placeholder="<?php echo $field['confirm_password']['error'];?>">
	</div>

</div>
	<!-- ENROL BUTTON -->
	<input type="submit" name="submit" id="btn_enrol" value="ENROL NOW! >">

</form>

<?php include "templates/footer.php"; ?>

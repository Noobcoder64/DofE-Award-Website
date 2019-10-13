<?php include "dbconnection.php"; ?>  <!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>  <!--	DISPLAY HEADER	-->
<?php include "manager_auth.php"; ?>  <!--	AUTHORIZE MANAGERS	-->
<?php include "validation.php"; ?>				<!--	IMPORT VALIDATION FUNCTIONS	-->
<title>Change Assessor</title>

<?php
if (isset($_POST['delete'])) {  // EXECUTE IF DELETE WAS POSTED
?>
  <!-- CONFIRM BOX -->
  <div class= "div_confirm">
    <h2>Are you sure you want to delete assessor: <?php echo check_input($_POST['first_name']) ?>?</h2>
    <form method="post">  <!-- REPOST ASSESSOR_ID -->
      <input type= "hidden" name= "assessor_ID" value= <?php echo check_input($_POST['assessor_ID']); ?>>
      <input type="hidden" name="delete" value="Delete">

      <input type= "submit" name= "confirm_yes" value=YES>  <!-- YES BUTTON -->
      <input type= "submit" name= "confirm_no" value=NO>  <!-- NO BUTTON -->
    </form>
  </div>
<?php

if (isset($_POST["confirm_yes"])) { // EXECUTE IF YES IS CLICKED
  $assessor_ID = $_POST['assessor_ID']; // GET REPOSTED ASSESSOR_ID
  // SQL QUERY TO DELETE THE POSTED ASSESSOR
  $sql = "DELETE FROM Assessor WHERE assessor_ID = $assessor_ID";
  // EXECUTE QUERY
  if(mysqli_query($conn,$sql)) { header("Location: view_assessors.php"); }

} elseif (isset($_POST["confirm_no"])) {  // EXECUTE IF NO IS CLICKED
  header("Location: view_assessors.php");
}

} elseif (isset($_POST["update"])) {  // EXECUTE IF DELETE WAS POSTED
  $assessor_ID = $_POST["assessor_ID"];

  $field = array(array());	// DECLARE MULTI-DIMENSIONAL ASSOCIATIVE ARRAY

  // STORE FIELD NAMES REQUIRED TO BE INPUTTED
  $field_names = array('first_name','last_name','gender','date_of_birth','email_address','telephone','username');

  $sql = "SELECT * FROM Assessor WHERE assessor_ID = '$assessor_ID'";
  $result = mysqli_query($conn, $sql);
  $fetch = mysqli_fetch_assoc($result);

  // GIVE EACH FIELD AN INPUT AND ERROR KEY
  foreach ($field_names as $name) {
  	if(!empty($fetch[$name])) {
  		$field[$name]['input'] = $fetch[$name];	// ASSIGN DATABASE VALUE
  	} else {
  		$field[$name]['input'] = '';
  	}
  		$field[$name]['error'] = '';	// ASSIGN NULL VALUE
  }

  if(isset($_POST["confirm_update"])) {

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
      if($field['username']['input'] <> $fetch['username']) {
        $field['username'] = validate_username($field['username']['input'], $conn, 'Assessor');
      }
    }

    // CHECK IF EVERY INPUT IS VALID
  	foreach ($field as $field_name) {
  		$flag = 1;  // INDICATES IF DATA IS VALID
  		if(!empty($field_name['error'])) {
  				$flag = 0;	// INDICATOR ASSIGNED 0 IF ERROR MESSAGE HAS BEEN DISPLAYED
  				break;
  		}
  	}

    if($flag) {

      $update_fields = array();

      //  BEGINNING OF SQL QUERY TO UPDATE
      $sql =  "UPDATE Assessor SET ";

      // FOR LOOP TO PUT FIELDS IN THE FORMAT field name = 'value'
      foreach($field_names as $name){
        $update_fields[] = "$name = '{$field[$name]['input']}'";
      }
      $sql .= implode(', ', $update_fields);
      // PUTS A COMMA BETWEEN IN EACH FIELD ASSIGNMENT AND CONVERTS INTO STRING

      //  ENDING OF SQL QUERY TO UPDATE
      $sql .= " WHERE assessor_ID = $assessor_ID";

      // EXECUTE SQL QUERY
      if(mysqli_query($conn,$sql)) { header("view_assessors.php"); }
    }

  } elseif(isset($_POST['reset'])) {

    // RESTORES THE INPUT AND ERROR VALUES
    foreach ($field_names as $name) {
    	if(!empty($fetch[$name])) {
    		$field[$name]['input'] = $fetch[$name];	// RESOTRED TO DATABASE VALUE
    	} else {
    		$field[$name]['input'] = '';
    	}
    		$field[$name]['error'] = '';	// RESTORED TO NULL VALUE
    }
  }
?>

<form method="post" class= "enrolment_form">

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
</div>

    <input type="hidden" name="update" value="update">
    <input type="hidden" name="assessor_ID" value=<?php echo $assessor_ID; ?>>
    <input type="submit" name="reset" id="btn_reset" value="RESET">
    <input type="submit" name="confirm_update" id="btn_update" value="UPDATE">
	</form>

</div>

<span id="back_logout"><a href="view_assessors.php">Back</a></span>

<?php } ?>

<?php include "templates/footer.php"; ?>

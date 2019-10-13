<?php include "dbconnection.php"; ?>
<?php include "templates/header.php"; ?>
<?php include "manager_auth.php"; ?>

<?php
if (isset($_POST['delete'])) {
?>

  <div class="div_confirm">
    <h2>Are you sure you want to delete participant: <?php echo check_input($_POST['first_name']) ?>?</h2>
    <form method="post">
      <input type= "hidden" name= "participant_ID" value=<?php echo $_POST['participant_ID'];?>>
      <input type="hidden" name="delete" value="Delete">
      <input type= "submit" name= "confirm_yes" value=YES>
      <input type= "submit" name= "confirm_no" value=NO>
    </form>
  </div>

<?php
if (isset($_POST["confirm_yes"])) {
  $participant_ID = $_POST['participant_ID'];
  $sql = "DELETE FROM Participant WHERE participant_ID = $participant_ID";

  if(mysqli_query($conn,$sql)) { header("Location: view_participants.php"); }

} elseif (isset($_POST["confirm_no"])) {
	header("Location: view_participants.php");
}

} elseif (isset($_POST["update"])) {
$participant_ID = $_POST["participant_ID"];

$error_student_number = $error_p_r = $error_p_e = $error_o_b = $error_first_name = $error_lastname = $error_gender = $error_dob = $error_p_l = $error_email_address = $error_mobile_number = $error_telephone = $error_address = $error_town_city = $error_post_code = $error_e_n_1 = $error_e_m_1 = $error_e_h_1 = $error_e_w_1 = $error_r_1 = $error_e_n_2 = $error_e_m_2 = $error_e_h_2 = $error_e_w_2 = $error_r_2 = $error_d_n = $error_d_t = $error_injection_date = $error_username = "";
$student_number = $participant_level = $previously_registered = $centre_name = $eDofe_ID = $pathway_enrichment = $on_bursary = $first_name = $last_name = $gender = $primary_language = $email_address = $mobile_number = $telephone = $address = $town_city = $county = $post_code = $emergency_name_1 = $emergency_mobile_1 = $emergency_home_1 = $emergency_work_1 = $relationship_1 = $emergency_name_2 = $emergency_mobile_2 = $emergency_home_2 = $emergency_work_2 = $relationship_2 = $doctor_name = $doctor_telephone = $participant_username = "";

$sql = "SELECT * FROM Participant WHERE participant_ID = '$participant_ID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST["confirm_update"])) {
  $flag = 1;

// Validate Student Number
	$student_number = check($_POST['student_number']);
	if(!empty($_POST['student_number'])) {
		if((strlen($student_number) <> 8)) {
      	$error_student_number = "Must be exactly 8 characters long";
				$student_number = "";
				$flag = 0;
  	}
	}

	$participant_level = check($_POST['participant_level']);

	if(isset($_POST['previously_registered'])) {
		$previously_registered = check($_POST['previously_registered']);
	} else {
		$error_p_r = "Please select";
		$flag = 0;
	}

	$centre_name = check($_POST['centre_name']);

	$eDofe_ID = check($_POST['eDofE_ID']);

	if(isset($_POST['pathway_enrichment'])) {
		$pathway_enrichment = check($_POST['pathway_enrichment']);
	} else {
		$error_p_e = "Please select";
		$flag = 0;
	}

	if(isset($_POST['on_bursary'])) {
		$on_bursary = check($_POST['on_bursary']);
	} else {
		$error_o_b = "Please select";
		$flag = 0;
	}

// Validate First Name
	$first_name = check($_POST['first_name']);
	if(empty($first_name)) {
		$error_first_name = "Please enter a first name";
		$flag = 0;
	} elseif(!preg_match("/^[a-zA-Z ]*$/",$first_name)) {
      $error_first_name = "Cannot contain numbers or special characters";
			$first_name = "";
			$flag = 0;
  }

	$last_name = check($_POST['last_name']);
	if(empty($last_name)) {
		$error_lastname = "Please enter a last name";
		$flag = 0;
	} elseif (!preg_match("/^[a-zA-Z ]*$/",$last_name)) {
			$error_lastname = "Cannot contain numbers or special characters";
			$last_name = "";
			$flag = 0;
	}

	if(isset($_POST['gender'])) {
		$gender = check($_POST['gender']);
	} else {
		$error_gender = "Please select";
		$flag = 0;
	}

	$date_of_birth = check($_POST['date_of_birth']);
	$current_date = date('Y-m-d');
	$current_time_stamp = time();
	$age = floor(($current_time_stamp - strtotime($date_of_birth))/ 31556926);
	if(empty($date_of_birth)) {
		$error_dob = "Please enter your date of birth";
		$flag = 0;
	} elseif ($date_of_birth > $current_date) {
		$error_dob = "Please enter a valid date of birth";
		$flag = 0;
	} elseif($age > 24) {
		$error_dob = "You are too old to enrol";
		$flag = 0;
	} elseif ($age < 14) {
		$error_dob = "You are too young to enrol";
		$flag = 0;
	}

	if(isset($_POST['primary_language'])) {
		$primary_language = check($_POST['primary_language']);
	} else {
		$error_p_l = "Please select";
		$flag = 0;
	}

	$shoe_size = check($_POST['shoe_size']);

	$email_address = check($_POST['email_address']);
	if(empty($email_address)) {
		$error_email_address = "Please enter your email address";
		$flag = 0;
	} elseif(!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
		$error_email_address = "Please enter a valid email address";
		$email_address = "";
		$flag = 0;
	}

	$mobile_number = check($_POST['mobile_number']);
	if(empty($mobile_number)) {
		$error_mobile_number = "Please enter your mobile number";
		$flag = 0;
	} elseif(!preg_match('/^[0-9]{11}+$/', $mobile_number)) {
		$error_mobile_number = "Please enter a valid mobile number";
		$mobile_number = "";
		$flag = 0;
	}

	$telephone = check($_POST['telephone']);
	if (!empty($telephone)) {
		if(!preg_match('/^[0-9]{11}+$/', $telephone)) {
			$error_telephone = "Please enter a valid mobile number";
			$telephone = "";
			$flag = 0;
		}
	}

	$address = check($_POST['address']);
	if(empty($address)) {
		$error_address = "Please enter your address";
		$flag = 0;
	}

	$town_city = check($_POST['town_city']);
	if(empty($town_city)) {
		$error_town_city = "Please enter the town/city you live in";
		$flag = 0;
	}

	$county = check($_POST['county']);

	$post_code = check($_POST['post_code']);
	if(empty($post_code)) {
		$error_post_code = "Please enter your post code";
		$flag = 0;
	} elseif((strlen($post_code) < 6) or (strlen($post_code) > 8)) {
		$error_post_code = "Must be 6 to 8 characters long";
		$post_code = "";
		$flag = 0;
	}

	$emergency_name_1 = check($_POST['emergency_name_1']);
	if(empty($emergency_name_1)) {
		$error_e_n_1 = "Please enter an emergency contact name";
		$flag = 0;
	} elseif (!preg_match("/^[a-zA-Z ]*$/",$emergency_name_1)) {
      $error_e_n_1 = "Cannot contain numbers or special characters";
			$emergency_name_1 = "";
			$flag = 0;
  }

	$emergency_mobile_1 = check($_POST['emergency_mobile_1']);
	if(empty($emergency_mobile_1)) {
		$error_e_m_1 = "Please enter an emergency contact mobile number";
		$flag = 0;
	} elseif(!preg_match('/^[0-9]{11}+$/', $emergency_mobile_1)) {
		$error_e_m_1 = "Please enter a valid mobile number";
		$emergency_mobile_1 = "";
		$flag = 0;
	}

	$emergency_home_1 = check($_POST['emergency_home_1']);
	if (!empty($emergency_home_1)) {
		if(!preg_match('/^[0-9]{11}+$/', $emergency_home_1)) {
			$error_e_h_1 = "Please enter a valid mobile number";
			$emergency_home_1 = "";
			$flag = 0;
		}
	}

	$emergency_work_1 = check($_POST['emergency_work_1']);
	if (!empty($emergency_work_1)) {
		if(!preg_match('/^[0-9]{11}+$/', $emergency_work_1)) {
			$error_e_w_1 = "Please enter a valid mobile number";
			$emergency_work_1 = "";
			$flag = 0;
		}
	}

	$relationship_1 = check($_POST['relationship_1']);
	if(empty($relationship_1)) {
		$error_r_1 = "Please enter your relationship";
		$flag = 0;
	} elseif (!preg_match("/^[a-zA-Z ]*$/",$relationship_1)) {
			$error_r_1 = "Cannot contain numbers or special characters";
			$relationship_1 = "";
			$flag = 0;
	}

	$emergency_name_2 = check($_POST['emergency_name_2']);
	if(empty($emergency_name_2)) {
		$error_e_n_2 = "Please enter an emergency contact name";
		$flag = 0;
	} elseif (!preg_match("/^[a-zA-Z ]*$/",$emergency_name_2)) {
      $error_e_n_2 = "Cannot contain numbers or special characters";
			$emergency_name_2 = "";
			$flag = 0;
  }

	$emergency_mobile_2 = check($_POST['emergency_mobile_2']);
	if(empty($emergency_mobile_2)) {
		$error_e_m_2 = "Please enter an emergency contact mobile number";
		$flag = 0;
	} elseif(!preg_match('/^[0-9]{11}+$/', $emergency_mobile_2)) {
		$error_e_m_2 = "Please enter a valid mobile number";
		$emergency_mobile_2 = "";
		$flag = 0;
	}

	$emergency_home_2 = check($_POST['emergency_home_2']);
	if (!empty($emergency_home_2)) {
		if(!preg_match('/^[0-9]{11}+$/', $emergency_home_2)) {
			$error_e_h_2 = "Please enter a valid mobile number";
			$emergency_home_2 = "";
			$flag = 0;
		}
	}

	$emergency_work_2 = check($_POST['emergency_work_2']);
	if (!empty($emergency_work_2)) {
		if(!preg_match('/^[0-9]{11}+$/', $emergency_work_2)) {
			$error_e_w_2 = "Please enter a valid mobile number";
			$emergency_work_2 = "";
			$flag = 0;
		}
	}

	$relationship_2 = check($_POST['relationship_2']);
	if(empty($relationship_2)) {
		$error_r_2 = "Please enter your relationship";
		$flag = 0;
	} elseif (!preg_match("/^[a-zA-Z ]*$/",$relationship_2)) {
			$error_r_2 = "Cannot contain numbers or special characters";
			$relationship_2 = "";
			$flag = 0;
	}

	$doctor_name = check($_POST['doctor_name']);
	if(empty($doctor_name)) {
		$error_d_n = "Please enter the name of your doctor";
		$flag = 0;
	} elseif (!preg_match("/^[a-zA-Z ]*$/",$doctor_name)) {
			$error_d_n = "Cannot contain numbers or special characters";
			$doctor_name = "";
			$flag = 0;
	}

	$doctor_telephone = check($_POST['doctor_telephone']);
	if(empty($doctor_telephone)) {
		$error_d_t = "Please enter the telephone number of your doctor";
		$flag = 0;
	} elseif(!preg_match('/^[0-9]{11}+$/', $doctor_telephone)) {
		$error_d_t = "Please enter a valid telephone number";
		$doctor_telephone = "";
		$flag = 0;
	}

	$medical_condition = check($_POST['medical_condition']);

	$medicine = check($_POST['medicine']);

	$injection_date = check($_POST['injection_date']);
	if(!empty($injection_date)) {
		if(($injection_date > $current_date) OR ($injection_date < $date_of_birth)) {
				$error_injection_date = "Please enter a valid date of injection";
				$injection_date = "";
				$flag = 0;
		}
	}

	$food_dislikes = check($_POST['food_dislikes']);

	$other_info = check($_POST['other_info']);

	$participant_username = check($_POST['participant_username']);
	if(empty($participant_username)) {
		$error_username = "Please enter a username";
		$flag = 0;
	} elseif($participant_username <> $row['participant_username']) {
	     $sql = "SELECT participant_username FROM Participant WHERE participant_username = '$participant_username'";
	     $result = mysqli_query($conn, $sql);
	     if (mysqli_num_rows($result) > 0) {
		       $error_username = "Username already exists";
		       $participant_username = "";
		       $flag = 0;
      }
		}

  if($flag) {
    $sql = "UPDATE Participant SET
              student_number = '$student_number',

              participant_level = '$participant_level',
              previously_registered = '$previously_registered',
              centre_name = '$centre_name',
              eDofE_ID = '$eDofe_ID',
              pathway_enrichment = '$pathway_enrichment',
              on_bursary = '$on_bursary',

              first_name = '$first_name',
              last_name = '$last_name',
              gender = '$gender',
              date_of_birth = '$date_of_birth',
              primary_language = '$primary_language',
              shoe_size = '$shoe_size',

              email_address = '$email_address',
              mobile_number = '$mobile_number',
              telephone = '$telephone',
              address = '$address',
              town_city = '$town_city',
              county = '$county',
              post_code = '$post_code',

              emergency_name_1 = '$emergency_name_1',
              emergency_mobile_1 = '$emergency_mobile_1',
              emergency_home_1 = '$emergency_home_1',
              emergency_work_1 = '$emergency_work_1',
              relationship_1 = '$relationship_1',

              emergency_name_2 = '$emergency_name_2',
              emergency_mobile_2 = '$emergency_mobile_2',
              emergency_home_2 = '$emergency_home_2',
              emergency_work_2 = '$emergency_work_2',
              relationship_2 = '$relationship_2',

              doctor_name = '$doctor_name',
              doctor_telephone = '$doctor_telephone',

              medical_condition = '$medical_condition',
              medicine = '$medicine',
              injection_date = '$injection_date',
              food_dislikes = '$food_dislikes',
              other_info = '$other_info',

              participant_username = '$participant_username'

            WHERE participant_ID = '$participant_ID'";

      if(mysqli_query($conn,$sql)) { header("view_participants.php"); }
  }

} elseif(isset($_POST['reset'])) {

$student_number = $row['student_number'];

$participant_level = $row['participant_level'];
$previously_registered = $row['previously_registered'];
$centre_name = $row['centre_name'];
$eDofe_ID = $row['eDofE_ID'];
$pathway_enrichment = $row['pathway_enrichment'];
$on_bursary = $row['on_bursary'];

$first_name = $row['first_name'];
$last_name = $row['last_name'];
$gender = $row['gender'];
$date_of_birth = $row['date_of_birth'];
$primary_language = $row['primary_language'];
$shoe_size = $row['shoe_size'];

$email_address = $row['email_address'];
$mobile_number = $row['mobile_number'];
$telephone = $row['telephone'];
$address = $row['address'];
$town_city = $row['town_city'];
$county = $row['county'];
$post_code = $row['post_code'];

$emergency_name_1 = $row['emergency_name_1'];
$emergency_mobile_1 = $row['emergency_mobile_1'];
$emergency_home_1 = $row['emergency_home_1'];
$emergency_work_1 = $row['emergency_work_1'];
$relationship_1 = $row['relationship_1'];

$emergency_name_2 = $row['emergency_name_2'];
$emergency_mobile_2 = $row['emergency_mobile_2'];
$emergency_home_2 = $row['emergency_home_2'];
$emergency_work_2 = $row['emergency_work_2'];
$relationship_2 = $row['relationship_2'];

$doctor_name = $row['doctor_name'];
$doctor_telephone = $row['doctor_telephone'];

$medical_condition = $row['medical_condition'];
$medicine = $row['medicine'];
$injection_date = $row['injection_date'];
$food_dislikes = $row['food_dislikes'];
$other_info = $row['other_info'];

$participant_username = $row['participant_username'];

}

?>

<form method="post" class="enrolment_form">

<div>
	<div>
			<label for="student_number">Student Number (If known)</label>
    	<input type="text" name="student_number" id="student_number" value="<?php echo $student_number;?>" placeholder="<?php echo $error_student_number;?>">
	</div>
</div>

<h3>DofE Level</h3>

<div>

		<label>Level</label>
		<select name="participant_level" value="<?php echo $participant_level;?>">
    		<option value="Bronze" <?php echo($participant_level == 'Bronze' ? 'selected' : '')?> >Bronze</option>
    		<option value="Silver" <?php echo($participant_level == 'Silver' ? 'selected' : '')?> >Silver</option>
    		<option value="Gold" <?php echo($participant_level == 'Gold' ? 'selected' : '')?> >Gold</option>
  		</select>

		<label for="previously_registered">Have you registered previously on DofE?</label>
<div id="radio">
		<input type="radio" name="previously_registered" id="previously_registered" value= 1 <?php echo($previously_registered == '1' ? 'checked' : '')?> >Yes
		<input type="radio" name="previously_registered" id="previously_registered" value= 0 <?php echo($previously_registered == '0' ? 'checked' : '')?> >No
		<span class="error"><?php echo $error_p_r;?></span>
</div>

		<label for="centre_name">Name of previous DofE Centre</label>
    <input type="text" name="centre_name" id="centre_name" value="<?php echo $centre_name;?>" placeholder= "If previously registered">

		<label for="eDofE_ID">eDofE ID number</label>
    <input type="text" name="eDofE_ID" id="eDofE_ID" value="<?php echo $eDofe_ID;?>" placeholder= "If known">

		<label for="pathway_enrichment">Please tick as appropriate: This is my</label>
	<div id="radio">
		<input type="radio" name="pathway_enrichment" id="pathway_enrichment" value="P" <?php echo($pathway_enrichment == 'P' ? 'checked' : '')?> >Pathway
		<input type="radio" name="pathway_enrichment" id="pathway_enrichment" value="E" <?php echo($pathway_enrichment == 'E' ? 'checked' : '')?> >Enrichment
		<span class="error"><?php echo $error_p_e;?></span>
	</div>

		<label for="on_bursary">Are you on bursary?</label>
	<div id="radio">
		<input type="radio" name="on_bursary" id="on_bursary" value= 1 <?php echo($on_bursary == '1' ? 'checked' : '')?> >Yes
    <input type="radio" name="on_bursary" id="on_bursary" value= 0 <?php echo($on_bursary == '0' ? 'checked' : '')?> >No
		<span class="error"><?php echo $error_o_b;?></span>
	</div>

</div>

<h3>Personal Details</h3>

<div>

			<label for="first_name">First Name</label>
    	<input type="text" name="first_name" id="first_name" value="<?php echo $first_name;?>" placeholder="<?php echo $error_first_name;?>">

    	<label for="last_name">Last Name</label>
    	<input type="text" name="last_name" id="last_name" value="<?php echo $last_name;?>" placeholder="<?php echo $error_lastname;?>">

    	<label for="gender">Gender</label>
<div id="radio">
			<input type="radio" name="gender" id="gender" value="M" <?php echo($gender == 'M' ? 'checked' : '')?> >Male
      <input type="radio" name="gender" id="gender" value="F" <?php echo($gender == 'F' ? 'checked' : '')?> >Female
			<span class="error"><?php echo $error_gender;?></span>
</div>

	  	<label for="date_of_birth">Date of Birth</label>
	<div id="date">
    	<input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo $date_of_birth;?>">
			<span class="error"><?php echo $error_dob;?></span>
	</div>

    	<label for="primary_language">Primary Language</label>
<div id="radio">
			<input type="radio" name="primary_language" id="primary_language" value="E" <?php echo($primary_language == 'E' ? 'checked' : '')?> >English
			<input type="radio" name="primary_language" id="primary_language" value="O" <?php echo($primary_language == 'O' ? 'checked' : '')?> >Other
			<span class="error"><?php echo $error_p_l;?></span>
</div>

			<label>Shoe Size</label>
			<select name="shoe_size">
			<?php for($i = 2.5; $i <= 12; $i += 0.5) {
    	echo "<option value= $i ". ($shoe_size == $i ? "selected" : "") ." >$i</option>";
			} ?>
			</select>

</div>

<h3>Contact Details</h3>

<div>

	<div>
		<label for="email_address">Email Address</label>
    <input type="text" name="email_address" id="email_address" value="<?php echo $email_address;?>" placeholder="<?php echo $error_email_address;?>">
	</div>

		<label for="mobile_number">Mobile Number</label>
    	<input type="text" name="mobile_number" id="mobile_number" value="<?php echo $mobile_number;?>" placeholder="<?php echo $error_mobile_number;?>">

	<label for="telephone">Telephone</label>
    	<input type="text" name="telephone" id="telephone" value="<?php echo $telephone;?>" placeholder="<?php echo $error_telephone;?>">

	<div>
		<label for="address">Address</label>
    	<input type="text" name="address" id="address" value="<?php echo $address;?>" placeholder="<?php echo $error_address;?>">
	</div>

	<div>
		<label for="town_city">Town/City</label>
    	<input type="text" name="town_city" id="town_city" value="<?php echo $town_city;?>" placeholder="<?php echo $error_town_city;?>">
	</div>

		<label for="county">County</label>
    	<input type="text" name="county" id="county" value="<?php echo $county;?>">

		<label for="post_code">Post Code</label>
    	<input type="text" name="post_code" id="post_code" value="<?php echo $post_code;?>" placeholder="<?php echo $error_post_code;?>">



</div>

 <h3>Emergency Contact Details</h3>

<div id="emergency_contacts">

<div>
<h4>(1) Emergency Contact Details</h4>

			<label for="emergency_name_1" id='item1'>Name</label>
    	<input type="text" name="emergency_name_1" id="emergency_name_1" value="<?php echo $emergency_name_1;?>" placeholder="<?php echo $error_e_n_1;?>">

      <label for="emergency_mobile_1">Mobile Telephone</label>
    	<input type="text" name="emergency_mobile_1" id="emergency_mobile_1" value="<?php echo $emergency_mobile_1;?>" placeholder="<?php echo $error_e_m_1;?>">

      <label for="emergency_home_1">Home Telephone</label>
    	<input type="text" name="emergency_home_1" id="emergency_home_1" value="<?php echo $emergency_home_1;?>" placeholder="<?php echo $error_e_h_1;?>">

      <label for="emergency_work_1">Work Telephone</label>
    	<input type="text" name="emergency_work_1" id="emergency_work_1" value="<?php echo $emergency_work_1;?>" placeholder="<?php echo $error_e_w_1;?>">

      <label for="relationship_1">Relationship to you</label>
    	<input type="text" name="relationship_1" id="relationship_1" value="<?php echo $relationship_1;?>" placeholder="<?php echo $error_r_1;?>">

</div>

<div>
<h4>(2) Emergency Contact Details</h4>

      <label for="emergency_name_2">Name</label>
    	<input type="text" name="emergency_name_2" id="emergency_name_2" value="<?php echo $emergency_name_2;?>" placeholder="<?php echo $error_e_n_2;?>">

      <label for="emergency_mobile_2">Mobile Telephone</label>
    	<input type="text" name="emergency_mobile_2" id="emergency_mobile_2" value="<?php echo $emergency_mobile_2;?>" placeholder="<?php echo $error_e_m_2;?>">

      <label for="emergency_home_2">Home Telephone</label>
    	<input type="text" name="emergency_home_2" id="emergency_home_2" value="<?php echo $emergency_home_2;?>" placeholder="<?php echo $error_e_h_2;?>">

      <label for="emergency_work_2">Work Telephone</label>
    	<input type="text" name="emergency_work_2" id="emergency_work_2" value="<?php echo $emergency_work_2;?>" placeholder="<?php echo $error_e_w_2;?>">

			<label for="relationship_2">Relationship to you</label>
    	<input type="text" name="relationship_2" id="relationship_2" value="<?php echo $relationship_2;?>" placeholder="<?php echo $error_r_2;?>">

</div>
</div>

<h3>Doctor Contact Details</h3>

<div>

        <label for="doctor_name">Name</label>
    	<input type="text" name="doctor_name" id="doctor_name" value="<?php echo $doctor_name;?>" placeholder="<?php echo $error_d_n;?>">

        <label for="doctor_telephone">Telephone</label>
    	<input type="text" name="doctor_telephone" id="doctor_telephone" value="<?php echo $doctor_telephone;?>" placeholder="<?php echo $error_d_t;?>">

</div>

<h3>Medical Details</h3>

<div>

			<div>
        <label for="medical_condition">Please give details of any medical conditions</label>
        <input type="text" name="medical_condition" id="medical_condition" value="<?php echo $medical_condition;?>" placeholder="e.g. diabetes, epilepsy etc. or any allergies e.g. medication, anaesthetics or food">
			</div>

			<div>
        <label for="medicine">Please list any medicines or tablets which you are taking at present</label>
        <input type="text" name="medicine" id="medicine" value="<?php echo $medicine;?>">
			</div>

			<div>
				<label for="injection_date">Date of last tetanus injection, if known</label>
				<div id="date">
				<input type="date" name="injection_date" id="injection_date" value="<?php echo $injection_date;?>" placeholder="<?php echo $error_injection_date;?>">
				<span class="error"><?php echo $error_injection_date;?></span>
				</div>
			</div>

			<div>
        <label for="food_dislikes">Food dislikes</label>
        <input type="text" name="food_dislikes" id="food_dislikes" value="<?php echo $food_dislikes;?>">
			</div>

			<div>
        <label for="other_info">Any other relevant information</label>
        <input type="text" name="other_info" id="other_info" value="<?php echo $other_info;?>">
			</div>

</div>

<h3>Login Details</h3>

<div>
  <div>
		<label for="participant_username">Enter a username</label>
    <input type="text" name="participant_username" id="participant_username" value="<?php echo $participant_username;?>" placeholder="<?php echo $error_username;?>">
  </div>
</div>

    <input type="hidden" name="update" value="update">
    <input type="hidden" name="participant_ID" value=<?php echo $participant_ID; ?>>
    <input type="submit" name="reset" id="btn_reset" value="RESET">
    <input type="submit" name="confirm_update" id="btn_update" value="UPDATE">
</form>

<span id="back_logout"><a href="view_participants.php">Back</a></span>

<?php } ?>

<?php include "templates/footer.php"; ?>

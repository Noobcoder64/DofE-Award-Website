<?php include "dbconnection.php"; ?>

<?php
	if (isset($_POST['submit'])) {

	$student_number = $_POST['student_number'];

	$participant_level = $_POST['participant_level'];
	$previously_registered = $_POST['previously_registered'];
	$centre_name = $_POST['centre_name'];
	$eDofe_ID = $_POST['eDofE_ID'];
	$pathway_enrichment = $_POST['pathway_enrichment'];
	$on_bursary = $_POST['on_bursary'];

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$gender = $_POST['gender'];
	$date_of_birth = $_POST['date_of_birth'];
	$primary_language = $_POST['primary_language'];
	$shoe_size = $_POST['shoe_size'];

	$email_address = $_POST['email_address'];
	$address = $_POST['address'];
	$town_city = $_POST['town_city'];
	$county = $_POST['county'];
	$post_code = $_POST['post_code'];
	$telephone = $_POST['telephone'];
	$mobile_number = $_POST['mobile_number'];

	$emergency_name_1 = $_POST['emergency_name_1'];
	$emergency_mobile_1 = $_POST['emergency_mobile_1'];
	$emergency_home_1 = $_POST['emergency_home_1'];
	$emergency_work_1 = $_POST['emergency_work_1'];
	$relationship_1 = $_POST['relationship_1'];

	$emergency_name_2 = $_POST['emergency_name_2'];
	$emergency_mobile_2 = $_POST['emergency_mobile_2'];
	$emergency_home_2 = $_POST['emergency_home_2'];
	$emergency_work_2 = $_POST['emergency_work_2'];
	$relationship_2 = $_POST['relationship_2'];

	$doctor_name = $_POST['doctor_name'];
	$doctor_telephone = $_POST['doctor_telephone'];

	$medical_condition = $_POST['medical_condition'];
	$medicine = $_POST['medicine'];
	$injection_date = $_POST['injection_date'];
	$food_dislikes = $_POST['food_dislikes'];
	$other_info = $_POST['other_info'];

	$participant_username = $_POST['participant_username'];
	$participant_password = $_POST['participant_password'];

	$sql = "INSERT INTO Participant (student_number, participant_level, previously_registered, centre_name, eDofE_ID, pathway_enrichment, on_bursary, first_name, last_name, gender, date_of_birth, primary_language, shoe_size, email_address, address, town_city, county, post_code, telephone, mobile_number, emergency_name_1, emergency_home_1, emergency_mobile_1, emergency_work_1, relationship_1, emergency_name_2, emergency_home_2, emergency_mobile_2, emergency_work_2, relationship_2, doctor_name, doctor_telephone, medical_condition, medicine, injection_date, food_dislikes, other_info, participant_username, participant_password)
		VALUES ('$student_number', '$participant_level', $previously_registered, '$centre_name', '$eDofe_ID', '$pathway_enrichment', $on_bursary, '$first_name', '$last_name', '$gender', '$date_of_birth', '$primary_language', '$shoe_size', '$email_address', '$address', '$town_city', '$county', '$post_code', '$telephone', '$mobile_number', '$emergency_name_1', '$emergency_home_1', '$emergency_mobile_1', '$emergency_work_1', '$relationship_1', '$emergency_name_2', '$emergency_home_2', '$emergency_mobile_2', '$emergency_work_2', '$relationship_2', '$doctor_name', '$doctor_telephone', '$medical_condition', '$medicine', '$injection_date', '$food_dislikes', '$other_info', '$participant_username', '".md5($participant_password)."')";

		if(mysqli_query($conn,$sql))
	{
		header("Location: user_login.php");
	}
	}

?>

<?php
function presence_check($data, $error_message) {
  if(!empty($_POST[$data])) {
    $array = array('input' => check_input($_POST[$data]), 'error' => '');
  } else {
    $array = array('input' => '', 'error' => $error_message);
  }
  return $array;
}

function validate_name($data, $error_message) {
  if(preg_match("/^[a-zA-Z ]*$/", $data)) {
    $array = array('input' => $data, 'error' => '');
  } else {
    $array = array('input' => '', 'error' => $error_message);
  }
  return $array;
}

function validate_email($data, $error_message) {
  if(filter_var($data, FILTER_VALIDATE_EMAIL)) {
    $array = array('input' => $data, 'error' => '');
  } else {
    $array = array('input' => '', 'error' => $error_message);
  }
  return $array;
}

function validate_phone_number($data, $error_message) {
  if(preg_match('/^[0-9]{11}+$/', $data)) {
    $array = array('input' => $data, 'error' => '');
  } else {
    $array = array('input' => '', 'error' => $error_message);
  }
    return $array;
}

function validate_post_code($data) {
  if((strlen($data) >= 6) AND (strlen($data) <= 8)) {
    $array = array('input' => $data, 'error' => '');
  } else {
    $array = array('input' => '', 'error' => 'Must be 6 to 8 characters long');
  }
  return $array;
}

function validate_username($data, $conn, $table_name) {
	$sql = "SELECT 1 FROM $table_name WHERE username = '$data'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 0) {
		$array = array('input' => $data, 'error' => '');
	} else {
		$array = array('input' => '', 'error' => 'Username already exists');
	}
	return $array;
}

function validate_password($data) {
  if((strlen($data) >= 6)) {
		$array = array('input' => $data, 'error' => '');
  } else {
    $array = array('input' => '', 'error' => 'Password too short (at least 6 characters)');
  }
  return $array;
}

function verify_password($confirm_password, $password) {
  if($confirm_password == $password) {
    $array = array('input' => $confirm_password, 'error' => '');
  } else {
    $array = array('input' => '', 'error' => 'Passwords do not match');
  }
  return $array;
}

?>

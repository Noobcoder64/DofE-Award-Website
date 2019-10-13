<?php
session_start();

if(session_destroy()) {
	if(isset($_SESSION['participant_ID']) OR isset($_SESSION['participant_ID'])) {
		header ("Location:login.php?user=Participant");
	} else {
		header ("Location:login.php?user=Assessor");
	}
}
?>

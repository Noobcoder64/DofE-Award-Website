<?php
if(!isset($_SESSION["participant_ID"])) {
	header("Location: login.php?user=Participant");
	exit();
}
?>

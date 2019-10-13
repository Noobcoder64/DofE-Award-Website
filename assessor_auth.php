<?php
if(!isset($_SESSION["assessor_ID"]) and !isset($_SESSION["manager_ID"])) {
	header("Location: login.php?user=Assessor");
	exit();
}
?>

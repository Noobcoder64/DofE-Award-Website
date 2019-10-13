<?php
if(!isset($_SESSION["manager_ID"])) {
	header("Location: index.php");
	exit();
}
?>

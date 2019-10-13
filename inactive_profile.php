<?php include "dbconnection.php"; ?>	<!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>	<!--	DISPLAY HEADER	-->

<?php
// AUTHORIZE ONLY PARTICIPANTS WHO DID NOT PAY THE FEE TO ACCESS THIS PAGE
if(!isset($_SESSION["inactive_ID"])) {
	header("Location: login.php");
	exit();
}

$participant_ID = $_SESSION['inactive_ID'];
$sql = "SELECT first_name, fee FROM Participant, LevelDetail WHERE participant_ID = '$participant_ID' AND Participant.participant_level = LevelDetail.level_name";
$result = mysqli_query($conn, $sql);
$fetch = mysqli_fetch_assoc($result);

$first_name = $fetch['first_name'];
$fee = $fetch['fee'];
?>
<!--	DISPLAY MESSAGE FOR NEXT STEPS	-->
<div class="inactive_profile">
  <p>Hello <?php echo $first_name; ?>, your account is currently inactive. In order to activate it please head to the department to pay the fee required to enrol for the Duke of Edinburgh Award. Fee: £<?php echo $fee; ?></p>
</div>

<!--	ALLOW TO LOGOUT	-->
<span id="back_logout"><a href="logout.php">Logout</a></span>

<?php include "templates/footer.php"; ?>

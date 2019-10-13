<?php include "dbconnection.php"; ?>  <!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>  <!--	DISPLAY HEADER	-->
<?php include "assessor_auth.php"; ?> <!--	AUTHORIZE ASSESSORS	-->

<?php
//  GET ID OF CHOSEN ASSESSMENT FROM View assessments page
$assessment_ID = $_POST['assessment_ID'];

// SQL STATEMENT TO RETREIVE PARTICIPANT AND ACTIVITY DETAILS
$sql = "SELECT first_name, last_name, email_address, mobile_number, activity_name, activity_description, start_date, date_completed, comment FROM Assessment, Activity, Participant WHERE assessment_ID = '$assessment_ID' AND Assessment.activity_ID = Activity.activity_ID AND Assessment.participant_ID = Participant.participant_ID";
$result = mysqli_query($conn, $sql);
$fetch = mysqli_fetch_assoc($result);

// DISPLAY PARTICIPANT DETAILS
  echo "
  <div class= 'display_details'>

		<div>
			<h2>Participant Details</h2>

			<h4>First Name</h4>
    	<p>{$fetch['first_name']}</p>

    	<h4>Last Name</h4>
    	<p>{$fetch['last_name']}</p>

			<h4>Email Address</h4>
			<p>{$fetch['email_address']}</p>

			<h4>Mobile Number</h4>
			<p>{$fetch['mobile_number']}</p>

    </div>";

  // DISPLAY ACTIVITY DETAILS
  echo "
		<div>
			<h2>Activity Details</h2>

 			<h4>Activity Name</h4>
 			<p>{$fetch['activity_name']}</p>

			<h4>Activity Description</h4>
 			<p>{$fetch['activity_description']}</p>

			<h4>Start Date</h4>
 			<p>{$fetch['start_date']}</p> ";

  // DISPLAY REPORT IF AVAILABLE
  if($fetch['date_completed']) {
  echo "
			<h4>End Date</h4>
 			<p>{$fetch['date_completed']}</p>
		</div>

    <div>
      <h2>Comment</h2>
      <p>{$fetch['comment']}</p>
    </div>
    ";
  } else {
    echo "</div>";
  }
echo "</div>";
?>

<!--	ALLOW TO GO BACK TO VIEW ALL ASSESSMENTS	-->
<span id='back_logout'><a href='view_assessments.php'>Back</a></span>

<?php include "templates/footer.php"; ?>

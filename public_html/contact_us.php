<?php include "dbconnection.php"; ?>  <!--	CONNECTION WITH DATABASE	-->
<?php include "templates/header.php"; ?>  <!--	DISPLAY HEADER	-->
<title>Contact Us</title>

<div class="contact_us">
  <h1>Contact Us</h1>

  <?php
  // RETREIVE DETAILS OF MANAGERS
  $sql = "SELECT first_name, last_name, email_address, telephone FROM Assessor WHERE is_manager = 1";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // WILL LOOP BY THE NUMBER OF MANAGERS IN DATABASE
    while($fetch = mysqli_fetch_assoc($result)) {
      // DISPLAY DETAILS OF CURRENTLY FETCHED MANAGER
      echo "
        <div>
          <h3>{$fetch['first_name']} {$fetch['last_name']}</h3>
          <p>Email address: {$fetch['email_address']}</p>
          <p>Telephone: {$fetch['telephone']}</p>
        </div>
        ";
    }
  }
  ?>

</div>

<?php include "templates/footer.php"; ?>

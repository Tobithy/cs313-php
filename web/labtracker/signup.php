<?php
// use php sessions for tracking users
session_start();

// include labtracker_common_php.php, for now it's just local
require_once 'labtracker_common_php.php';

if (isset($_POST['signup_submit'])) {
  // First we need to clean all the data for PHP, to make sure that later on when/if we display
  //  there aren't any exploits. Also we need to make sure the user put in a value at least for the email address
  $fName = $lName = $emailAddress = $password = $password2 = "";

  // check we have an email before anything else
  if (isset($_POST['email']) && isfilled($_POST['email'])) {
    $emptyEmail = false;

    // Now check passwords match
    if (isset($_POST['password']))
      $password = $_POST['password'];
    if (isset($_POST['password2']))
      $password2 = $_POST['password2'];

    if ($password === $password2) {
      $passwordsMatch = true;

      // get other data
      $emailAddress = $_POST['email']; // don't clean this, but make sure to clean it if you display it. should probably use filter_validate, but not for now.
      if (isset($_POST['fname']))
        $fName = clean_input($_POST['fname']);
      if (isset($_POST['lname']))
        $fName = clean_input($_POST['lname']);

      // hash the password
      $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

      // connect to the database
      require_once  $_SERVER["DOCUMENT_ROOT"] . '/labtracker/dbconnect.php';

      // prepare DB statement
      $statement = $db->prepare(
        "INSERT INTO user_account
          (email_address, hashed_password, first_name, last_name, data_display_pref_id)
        VALUES
          (:emailAddress, :hashedPassword, :fName, :lName, 
            (SELECT data_display_pref_id
              FROM data_display_pref AS d
              WHERE d.display_preference = 'TABULAR')
          )
        ;"
      );
      $insertSuccess = $statement->execute(array(
        ':emailAddress' => $emailAddress,
        ':hashedPassword' => $hashedPassword, ':fName' => $fName, ':lName' => $lName
      ));

      // if it was successful, redirect back to main page. Don't if it wasn't successful, instead inform the user later
      if ($insertSuccess) {
        $newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/";
        header($newpage, true, 303);
        die();
      } else {
        // do nothing, inform the user later
      }
    } else {
      $passwordsMatch = false;
    }
  } else {
    $emptyEmail = true;
  }
} else {
  // if we get here, the user is just opening the page, and hasn't signed up yet
}

// when a user opens this page, wipe out any existing session data they may have
session_unset();
session_destroy();
?>



<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTracker Account Creation | CS313 Project 1</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- App specific css -->
  <link rel="stylesheet" href="css/labtracker.css">
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php $currentPage = 'Signup'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php /*require $_SERVER["DOCUMENT_ROOT"] . '/labtracker/labtracker_navbar.php';*/ // no navbar on signup page 
  ?>


  <div class="container pt-3">
    <h2 class="display-5">LabTracker Account Creation</h2>
  </div>
  <div class="container pt-3">
    <?php
    // if this page resulted from a submission, we want to notify the user whether the 
    //  insertion was successful
    if (isset($_POST['signup_submit'])) {
      print '<div class="alert alert-dismissible fade show';  
      if ($emptyEmail) {
        print ' alert-danger" role="alert">Email address <strong>CANNOT</strong> be blank. Please try again.';
      } else if (!$emptyEmail && !$passwordsMatch) {
        print ' alert-danger" role="alert">Passwords <strong>MUST</strong> match. Please try again.';
      } else if (!$emptyEmail && $passwordsMatch && !$insertSuccess) {
        print ' alert-danger" role="alert">Account <strong>NOT</strong> created. Perhaps that email address already has an account?';
      } else {
        print 'ERROR! You should never see this message.';
      }
      print '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>';
    }
    ?>
  </div>

  <!--Not sure that the "form-signin" class does-->
  <div class="container form-signin">
    <form action="" method="POST">
      <div class="row">
        <div class="form-group col-md-6">
          <label for="fname">First name</label>
          <input type="text" class="form-control" name="fname" id="fname" placeholder="First name" required>
        </div>
        <div class="form-group col-md-6">
          <label for="lname">Last name</label>
          <input type="text" class="form-control" name="lname" id="lname" placeholder="Last name" required>
        </div>
      </div>
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="password">Password</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        </div>
        <div class="form-group col-md-6">
          <label for="password2">Password again</label>
          <input type="password" class="form-control" name="password2" id="password2" placeholder="Password" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" name="signup_submit">Create Account</button>
      <a href="./" class="btn btn-warning" role="button">Cancel</a>
    </form>
  </div>

</body>


</html>
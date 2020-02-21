<?php
session_start();

// include labtracker_common_php.php
require_once 'labtracker_common_php.php';

// Make sure user is logged in
checkLogin();

// connect to DB and get user data
require_once  $_SERVER["DOCUMENT_ROOT"] . '/labtracker/dbconnect.php';
$emailAddress = $firstName = $lastName = "";
$statement = $db->prepare(
  "SELECT email_address, first_name, last_name
      FROM user_account
      WHERE email_address = :email
    ;"
);
$executeSuccess = $statement->execute(array(':email' => $_SESSION['email_address']));

// convert to array
$userAccountResults = $statement->fetchAll(PDO::FETCH_ASSOC);

// Now we should verify that we have one row and exactly one row
if (count($userAccountResults) === 1)
  $retrieveSuccess = true;
else
  $retrieveSuccess = false;

// If all is well, assign all the data from the DB to local variables
if ($executeSuccess && $retrieveSuccess) {
  $emailAddress = clean_input($userAccountResults[0]['email_address']); // clean before displaying since we didn't do this before
  $firstName = clean_input($userAccountResults[0]['first_name']);
  $lastName = clean_input($userAccountResults[0]['last_name']);
}
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTrack - My Account | CS313 Project 1</title>
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
  <?php $currentPage = 'My Account'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/labtracker/labtracker_navbar.php'; ?>


  <div class="container pt-3">
    <h2 class="display-5">Account Information</h2>
  </div>

  <!-- warning message if the DB query failed -->
  <div class="container">
    <?php
    if ($executeSuccess && $retrieveSuccess); // do nothing if all is well
    else {
      print '<div class="alert alert-dismissible fade show';
      print ' alert-danger" role="alert">User data <strong>NOT</strong> found.<br>';
      print '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span></button></div>';
    }
    ?>
  </div>

  <div class="container pt-3">
    <div class="row">
      <div class="col-md-3">
        Name:
      </div>
      <div class="col-md-3">
        <?php print $firstName . ' ' . $lastName; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        Email address: 
      </div>
      <div class="col-md-3">
        <?php print $emailAddress; ?>
      </div>
    </div>

</body>


</html>
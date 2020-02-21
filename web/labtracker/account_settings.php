<?php
session_start();

// include labtracker_common_php.php
require_once 'labtracker_common_php.php';

// Make sure user is logged in
checkLogin();
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
    <p>Placeholder for account information</p>
  </div>

</body>


</html>
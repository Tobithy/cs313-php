<?php
// use php sessions for tracking users
session_start();

// include labtracker_common_php.php, for now it's just local
require 'labtracker_common_php.php';

// If the user is logging in, time to check if they were successful. For now we'll just redirect to the next page
//  Later we'll maybe implement a check to redirect the user if they are already logged in
if (loggedIn() === false) {
  // header("Location: ./"); // Redirect the user back to login
}

// session_unset();
// session_destroy();
?>



<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTrack - My Data | CS313 Project 1</title>
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
  <?php $currentPage = 'My Data'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/labtracker/labtracker_navbar.php'; ?>


  <div class="container pt-3">
    <h2 class="display-5">My Data</h2>
  </div>

  <div class="container pt-3">
  <?php
  // For now, let's just get the data displayed in tabular form. First we need to find all the tables the 
  //  current user has data for. Eventually we should factor this out to other files, functions, and classes,
  //  but for now we're just going for MVP
  
  // default Heroku Postgres configuration URL
  $dbUrl = getenv('DATABASE_URL');

  if (empty($dbUrl)) {
    // This gets us the heroku credentials without revealing credentials in our code
    $dbUrl = exec("heroku config:get DATABASE_URL");
  }

  $dbopts = parse_url($dbUrl);

  $dbHost = $dbopts["host"];
  $dbPort = $dbopts["port"];
  $dbUser = $dbopts["user"];
  $dbPassword = $dbopts["pass"];
  $dbName = ltrim($dbopts["path"], '/');

  // print "<p>pgsql:host=$dbHost;port=$dbPort;dbname=$dbName</p>\n\n";

  try {
    $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

    // this line makes PDO give us an exception when there are problems,
    // and can be very helpful in debugging! (But you would likely want
    // to disable it for production environments.)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $ex) {
    print "<p>error: " . $ex->getMessage() . "</p>\n\n";
    die();
  }

  // Get all the available tests for the current user
  $statement = $db->prepare("
  SELECT DISTINCT ct.clinical_test_label, ct.clinical_test_name, ct.clinical_test_format
    FROM ((clinical_test AS ct
    JOIN clinical_data AS cd ON cd.clinical_test_id = ct.clinical_test_id)
    JOIN user_account AS ua ON ua.user_account_id = cd.user_account_id)
    WHERE ua.email_address = 'markhammond@gmail.com'
    ORDER BY ct.clinical_test_name
    ;");
  $statement->execute();  
  $testTypeResults = $statement->fetchAll(PDO::FETCH_ASSOC);

  // now we have an array of arrays. 
  // $numTestTypes = count($testTypeResults);
  foreach ($testTypeResults as $testType) {
    // For each test we have data for, print out a section with a table of the data
    ?><h3 class="display-5"> <?php print $testType['clinical_test_label'] ?></h3><?php

    // now create the table. We first need to query the db for each one
    $statement = $db->prepare("
    SELECT cd.data_date, cd.data_" . strtolower($testType['clinical_test_format']) . ", cd.data_comment
      FROM ((clinical_data AS cd 
      JOIN user_account AS ua ON cd.user_account_id = ua.user_account_id)
      JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
      WHERE ua.email_address = 'markhammond@gmail.com'
      AND ct.clinical_test_label = '" . $testType['clinical_test_label'] . "'
      ORDER BY cd.data_date
      ;");
    $statement->execute();
    $dataResults = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Make the table 
    ?>
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Date</th>
          <th>Result</th>
          <th>Comments</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // loop throuch each piece of clinical data
        foreach ($dataResults as $data) {
        ?>
        <tr>
          <?php
          // loop through each entry of the data point
          foreach ($data as $entryIndex => $entry) {
            print '<td>' . $entry . '</td>';
          } // end column loop
          ?>
        </tr>
        <?php 
        } //end row loop 
        ?> 
      </tbody>
    </table>
    <?php
  }   // end testType loop

  ?>
  </div>

</body>


</html>
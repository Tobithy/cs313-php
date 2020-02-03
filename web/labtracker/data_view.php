<?php
// use php sessions for tracking users
session_start();

$_SESSION['email_address'] = 'markhammond@gmail.com'; // for testing only

// include labtracker_common_php.php
require_once 'labtracker_common_php.php';

// Make sure user is logged in
if (loggedIn() === false) {
  // header("Location: ./"); // Redirect the user back to login
}

// Now that we know we are logged in, we can connect to the database
require_once  $_SERVER["DOCUMENT_ROOT"] . '/labtracker/dbconnect.php';

// Now check if the user has chosen to delete or edit data
if (isset($_POST['delete_data']) && isfilled($_POST['delete_data'])) {
  // prepare the statement. Make sure the user only deletes stuff they own
  $statement = $db->prepare(
    "DELETE FROM clinical_data 
      WHERE clinical_data_id = :clinicalDataId
      AND user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = :email
        )
    "
  );
  // Note that this won't tell us if the row was actually deleted. If the SQL is correct, but 
  //  there are no matches, then this will still return true. 
  $executeSuccess = $statement->execute(array(
    ':clinicalDataId' => $_POST['delete_data'], ':email' => $_SESSION['email_address']));
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
    <a href="data_add.php" class="btn btn-info float-right" role="button">Add Data</a>
  </div>

  <!-- Make a form placeholder that we can assign all the buttons to -->
    <form action="" id="modify_buttons" method="POST"></form>

  <div class="container pt-3">
  <?php
  // For now, let's just get the data displayed in tabular form. First we need to find all the tables the 
  //  current user has data for. Eventually we should factor this out to other files, functions, and classes,
  //  but for now we're just going for MVP

  
  // Get all the available tests for the current user
  $statement = $db->prepare("
  SELECT DISTINCT ct.clinical_test_label, ct.clinical_test_name, ct.clinical_test_format
    FROM ((clinical_test AS ct
    JOIN clinical_data AS cd ON cd.clinical_test_id = ct.clinical_test_id)
    JOIN user_account AS ua ON ua.user_account_id = cd.user_account_id)
    WHERE ua.email_address = :email
    ORDER BY ct.clinical_test_name
    ;");
  $statement->execute(array(':email' => $_SESSION['email_address'],));
  $testTypeResults = $statement->fetchAll(PDO::FETCH_ASSOC);

  // now we have an array of arrays. 
  // $numTestTypes = count($testTypeResults);
  foreach ($testTypeResults as $testType) {
    // For each test we have data for, print out a section with a table of the data
    ?><h3 class="display-5"> <?php print $testType['clinical_test_label'] ?></h3><?php

    // now create the table. We first need to query the db for all the data of the current test type
    $statement = $db->prepare(
    "SELECT cd.clinical_data_id, cd.data_date, cd.data_" . strtolower($testType['clinical_test_format']) . ", cd.data_comment
      FROM ((clinical_data AS cd 
      JOIN user_account AS ua ON cd.user_account_id = ua.user_account_id)
      JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
      WHERE ua.email_address = :email
      AND ct.clinical_test_label = '" . $testType['clinical_test_label'] . "'
      ORDER BY cd.data_date
      ;");
    $statement->execute(array(':email' => $_SESSION['email_address'],));
    $dataResults = $statement->fetchAll(PDO::FETCH_ASSOC);

    
    // Make the table 
    ?>
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Date</th>
          <th>Result</th>
          <th>Comments</th>
          <!-- Next two columns are for Edit and Delete buttons -->
          <th></th>
          <th></th>
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
            if ($entryIndex === 'clinical_data_id') // skip this iteration if the data is our ID
              continue;
            print '<td>' . $entry . '</td>';
          } // end column loop
          ?>
          <!-- This next section sets up the submit buttons that can delete or edit the line of data in question -->
          <td>
            <button type="submit" form="modify_buttons" class="btn btn-warning btn-sm" 
            name="edit_data" value="<?php print $data['clinical_data_id']?>">Edit</button>
          </td>
          <td>
            <button type="submit" form="modify_buttons" class="btn btn-danger btn-sm" 
            name="delete_data" value="<?php print $data['clinical_data_id']?>">Delete</button>
          </td>
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
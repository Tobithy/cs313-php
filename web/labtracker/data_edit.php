<?php
// use php sessions for tracking users
session_start();

$_SESSION['email_address'] = 'markhammond@gmail.com'; // for testing only
// $_SESSION['clinical_data_id_to_edit'] = 4;  // TESTING ONLY

// include labtracker_common_php.php, for now it's just local
require_once 'labtracker_common_php.php';

// Make sure user is logged in
if (loggedIn() === false) {
  // header("Location: ./"); // Redirect the user back to login
}

// If the clinical_data_id_to_edit session variable isn't set, redirect back to data_view
if (!isset($_SESSION['clinical_data_id_to_edit'])) {
  $newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/data_view.php";
  header($newpage, true, 303);
}

// Now that we know we are logged in, we can connect to the database
require_once  $_SERVER["DOCUMENT_ROOT"] . '/labtracker/dbconnect.php';

$modifyIsOK = false;  // start as false for safety
// Check if this page has been submitted. If so, modify the DB with the received data
if (isset($_POST['modify_data'])) {
  // First we need to clean all the data for PHP, to make sure that later on when/if we display
  //  there aren't any exploits
  $dataDate = $clinicalTestLabel = $dataText = $dataComment = "";
  $dataFloat = '0';  // float needs to start as 'something' in case of TEXT, because "real" can't be blank. I tried NULL but couldn't get that to work.
  $dataDate = clean_input($_POST['data_date']);
  $clinicalTestLabel = clean_input($_POST['clinical_test_label']);
  if (isset($_POST['data_float'])) {
    $dataFloat = clean_input($_POST['data_float']);
  } else {
    $dataText = clean_input($_POST['data_text']);
  }
  if (isset($_POST['data_comment']))
    $dataComment = clean_input($_POST['data_comment']);

  // Next check that we have permission to modify the data
  $modifyIsOK = clinicalDataOKToModify($db, $_SESSION['clinical_data_id_to_edit']);

  if ($modifyIsOK) {
    // Now it's time to modify the clinical_data table. If the UPDATE fails, we will
    //  notify the user 

    // Prepare the db statement 
    $statement = $db->prepare(
      "UPDATE clinical_data
        SET data_date = :dataDate, 
        clinical_test_id = (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = :clinicalTestLabel),
        data_float = :dataFloat, 
        data_text = :dataText, 
        data_comment = :dataComment
      WHERE clinical_data_id = :clinicalDataId 
      AND user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = :email
        )
      ;"
    );
    $executeSuccess = $statement->execute(array(
      ':email' => $_SESSION['email_address'],
      ':dataDate' => $dataDate, ':clinicalTestLabel' => $clinicalTestLabel,
      ':dataFloat' => $dataFloat, ':dataText' => $dataText, ':dataComment' => $dataComment,
      ':clinicalDataId' => $_SESSION['clinical_data_id_to_edit'], ':email' => $_SESSION['email_address']
    ));

    // Now we decide whether to redirect the user back to My Data. Only redirect back if the edit was successful
    if ($executeSuccess) {
      // unset the id to edit, we're leaving
      unset($_SESSION['clinical_data_id_to_edit']);
      $newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/data_view.php";
      header($newpage, true, 303);
    } else {
      // in this case, don't redirect anywhere, just render the page. The user will be notified if there was a failure
    }
  } 
} else {
  // If we reach here, the user has not edited the data yet. We need to pull the current data from the 
  //  database in order to display it.
  $dataDate = $clinicalTestLabel = $dataText = $dataComment = $dataFloat = "";

  // Pull from db
  $statement = $db->prepare(
    "SELECT cd.data_date, ct.clinical_test_label, cd.data_float, cd.data_text, cd.data_comment 
      FROM (clinical_data AS cd
      JOIN clinical_test AS ct ON cd.clinical_test_id = ct.clinical_test_id)
      WHERE cd.clinical_data_id = :clinicalDataId
      AND cd.user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = :email
        )
    ;"
  );

  // execute the statement
  $executeSuccess = $statement->execute(array(
    ':clinicalDataId' => $_SESSION['clinical_data_id_to_edit'], ':email' => $_SESSION['email_address']
  ));

  // convert to array
  $clinicalDataResults = $statement->fetchAll(PDO::FETCH_ASSOC);

  // Now we should verify that we have one row and exactly one row
  if (count($clinicalDataResults) === 1)
    $retrieveSuccess = true;
  else
    $retrieveSuccess = false;

  // If all is well, assign all the data from the DB to local variables
  if ($executeSuccess && $retrieveSuccess) {
    $dataDate = $clinicalDataResults[0]['data_date'];
    $clinicalTestLabel = $clinicalDataResults[0]['clinical_test_label'];
    $dataFloat = $clinicalDataResults[0]['data_float'];
    $dataText = $clinicalDataResults[0]['data_text'];
    $dataComment = $clinicalDataResults[0]['data_comment'];
  }
}
// session_unset();
// session_destroy();
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTrack - Edit Data | CS313 Project 1</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- App specific css -->
  <link rel="stylesheet" href="css/labtracker.css">

  <!-- Javascript -->
  <script src="assets/data_add.js"></script>
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php /*$currentPage = 'Add Data';*/ // Edit Data doesn't have a navbar entry 
  ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/labtracker/labtracker_navbar.php'; ?>


  <div class="container pt-3">
    <h2 class="display-5">Edit Data</h2>
    <?php
    // if this page resulted from a submission, we want to notify the user whether the 
    //  insertion was successful
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      print '<div class="alert alert-dismissible fade show';
      if ($modifyIsOK && isset($_POST['modify_data']) && $executeSuccess)
        print ' alert-success" role="alert">Lab data was <strong>successfully</strong> modified.';  // actually we won't really get here
      else
        print ' alert-danger" role="alert">Lab data was <strong>NOT successfully</strong> modified. Please try again.';
      print '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>';
    } else /* we didn't post */ {
      // In this case, we'll do a similar check and tell the user if data wasn't successfully retrieved from the db

      if ($executeSuccess && $retrieveSuccess);   // do nothing if all is well
      else {
        print '<div class="alert alert-dismissible fade show';
        print ' alert-danger" role="alert">Lab data was <strong>NOT successfully</strong> retrieved from the database.<br> 
          Modification will likely fail. You may wish to return to My Data.';
        print '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span></button></div>';
      }
    }
    ?>
  </div>

  <?php
  // we need to get a list of all of the test types, along with whether they are TEXT or FLOAT
  $statement = $db->query('SELECT clinical_test_label, clinical_test_format FROM clinical_test;');
  $clinicalTests = $statement->fetchAll(PDO::FETCH_ASSOC);

  // Now create a 2D array in Javascript that can be used as a lookup table for data format.
  $js2dString = php2dTojs2d($clinicalTests);
  ?>
  <script>
    var clinicalTests = <?php print $js2dString ?>;
  </script>

  <div class="container form-signin">
    <form action="" method="POST">
      <div class="form-group row">
        <label for="data_date" class="col-lg-2 col-form-label">Date of lab test</label>
        <div class="col-lg-4">
          <input type="date" class="form-control" name="data_date" value="<?php print $dataDate ?>" id="data_date" required>
        </div>
        <label for="clinical_test_label" class="col-lg-2 col-form-label">Type of test</label>
        <div class="col-lg-4">
          <select class="form-control" name="clinical_test_label" id="clinical_test_label" required>
            <option value=""></option>
            <?php
            // loop to fill in every option in the select form element
            foreach ($clinicalTests as $row) {
              print '<option value="' . $row['clinical_test_label'] . '" ';
              if ($row['clinical_test_label'] == $clinicalTestLabel)  // make current option selected if match
                print 'selected';
              print '>' . $row['clinical_test_label'] . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group row" id="lab_result_div">
        <?php
        // Here we need to check whether the selected test is FLOAT or TEXT, and display the appropriate form element
        if (testIsFloat($clinicalTests, $clinicalTestLabel)) {
          ?>
          <label for="data_float" class="col-lg-2 col-form-label">Lab result</label>
          <div class="col-lg-10">
            <input type="number" class="form-control" name="data_float" step="any" value="<?php print $dataFloat ?>" id="data_float" required>
          </div>
        <?php } else { ?>
          <label for="data_text" class="col-lg-2 col-form-label">Lab result</label>
          <div class="col-lg-10">
            <input type="text" class="form-control" name="data_text" value="<?php print $dataText ?>" id="data_text" required>
          </div>
        <?php } ?>
      </div>
      <div class="form-group row">
        <label for="data_comment" class="col-lg-2 col-form-label">Comment</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" name="data_comment" value="<?php print $dataComment ?>" id="data_comment">
        </div>
      </div>
      <button type="submit" class="btn btn-primary" name="modify_data">Modify</button>
      <a href="data_view.php" class="btn btn-warning" role="button">Cancel</a>
    </form>
  </div>

</body>


</html>
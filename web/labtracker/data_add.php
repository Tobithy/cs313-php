<?php
// use php sessions for tracking users
session_start();

$_SESSION['email_address'] = 'markhammond@gmail.com'; // for testing only

// include labtracker_common_php.php, for now it's just local
require_once 'labtracker_common_php.php';

// Make sure user is logged in
if (loggedIn() === false) {
  // header("Location: ./"); // Redirect the user back to login
}

// Now that we know we are logged in, we can connect to the database
require_once  $_SERVER["DOCUMENT_ROOT"] . '/labtracker/dbconnect.php';

// Check if this page has been submitted. If so, add the received data to the DB
if (isset($_POST['add_and_new']) || isset($_POST['add_data'])) {
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

  // Now it's time to insert the data into the clinical_data table. If the insertion fails, we will
  //  notify the user

  // Prepare the db statement
  $statement = $db->prepare(
    "INSERT INTO clinical_data
      (user_account_id, data_date, clinical_test_id, data_float, data_text, data_comment)
    VALUES
      (
        (SELECT user_account_id FROM user_account AS u WHERE u.email_address = :email),
        :dataDate,
        (SELECT clinical_test_id FROM clinical_test AS c WHERE c.clinical_test_label = :clinicalTestLabel), 
        :dataFloat, :dataText, :dataComment
      )"
  );
  $insertSuccess = $statement->execute(array(
    ':email' => $_SESSION['email_address'],
    ':dataDate' => $dataDate, ':clinicalTestLabel' => $clinicalTestLabel,
    ':dataFloat' => $dataFloat, ':dataText' => $dataText, ':dataComment' => $dataComment
  ));

  // Now we decide whether to redirect the user back to My Data. Only redirect back if they picked
  //  "Submit and return" AND the insert was successful
  if (isset($_POST['add_data']) && $insertSuccess) {
    $newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/data_view.php";
    header($newpage, true, 303);
  } else {
    // in this case, don't redirect anywhere, just render the page. The user will be notified if there was a failure
  }
}
// session_unset();
// session_destroy();
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTrack - Add Data | CS313 Project 1</title>
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
  <?php $currentPage = 'Add Data'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/labtracker/labtracker_navbar.php'; ?>


  <div class="container pt-3">
    <h2 class="display-5">Add Data</h2>
    <?php
    // if this page resulted from a submission, we want to notify the user whether the 
    //  insertion was successful
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      print '<div class="alert alert-dismissible fade show';
      if ($insertSuccess)
        print ' alert-success" role="alert">Lab data was <strong>successfully</strong> added.';
      else
        print ' alert-danger" role="alert">Lab data was <strong>NOT successfully</strong> added. Please try again.';
      print '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>';
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
          <input type="date" class="form-control" name="data_date" id="data_date" required>
        </div>
        <label for="clinical_test_label" class="col-lg-2 col-form-label">Type of test</label>
        <div class="col-lg-4">
          <select class="form-control" name="clinical_test_label" id="clinical_test_label" required>
            <option value=""></option>
            <?php 
            // loop to fill in every option in the select form element
            foreach ($clinicalTests as $row) {
              ?><option value="<?php print $row['clinical_test_label'] ?>"><?php print $row['clinical_test_label'] ?></option>
            <?php 
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group row" id="lab_result_div">
        <label for="data_float" class="col-lg-2 col-form-label">Lab result</label>
        <div class="col-lg-10">
          <input type="number" class="form-control" name="data_float" step="any" id="data_float" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="data_comment" class="col-lg-2 col-form-label">Comment</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" name="data_comment" id="data_comment">
        </div>
      </div>
      <button type="submit" class="btn btn-primary" name="add_and_new">Submit and add another</button>
      <button type="submit" class="btn btn-primary" name="add_data">Submit and return</button>
      <button type="reset" class="btn btn-warning" name="reset">Reset</button>
    </form>
  </div>

</body>


</html>
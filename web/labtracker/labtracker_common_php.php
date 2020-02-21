<?php

// Function checkLogin
//  This function checks if the user has a email_address session variable. If not, then redirects 
//  the user back to the labtracker main page. Returns nothing
function checkLogin()
{
  if (isset($_SESSION['email_address'])) {
    // do nothing, assume if this variable is set then the user is logged in
  } else {
    $newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/";
    header($newpage, true, 303);
    die();
  }
}

// Function clean_input
//  Cleans user entered data so it can be safely used elsewhere. From w3schools
//  Input
//    $data - potentially unclean data
//  Returns cleaned data (whitespace before and after removed, backslashes removed, and
//  special characters replaced with html codes.
function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Function isfilled
//  Short function to check if a variable has data (it's not empty).
//    Use the trim function so that data that only consists of spaces is considered empty.
//  Input
//    $data - variable to be tested
//  Returns TRUE if there is data, FALSE if not.
function isfilled($data) {
  if (empty(trim($data)))
    return false;
  
  return true;
}

// Function php2dTojs2d
//  Parses a 2D array to a string that can be output into a <script> tag and creates a 2D array 
//  in JS when the page loads. 
//  Input
//    $php2d - PHP 2D array
//  Output
//    $js2dString - string that produces a 2D array in Javascript
function php2dTojs2d($php2d){
  $js2dString = "[";
  $firstRow = true;
  foreach ($php2d as $row) {
    if ($firstRow) {
      $firstRow = false;
    } else {
      $js2dString = $js2dString . ",";  // make sure to add a comma after each row (so this is for the previous row)
    }
    $js2dString = $js2dString . "['" . $row['clinical_test_label'] . "','" . $row['clinical_test_format'] . "']";
  }
  $js2dString = $js2dString . "]";

  return $js2dString;
}

// Function clinicalDataOKToModify
//  Checks whether a select statement to clinical data returns 1 and only 1 row. This is for verifying
//  that a delete or edit action will be successful
//  Input
//    $db - the PDO database object
//    $clinicalDataId - id of row to be deleted/edited
//  Output
//    $modifyOK - boolean 
function clinicalDataOKToModify($db, $clinicalDataId){
  $modifyOK = false;
  $statement = $db->prepare(
    "SELECT clinical_data_id FROM clinical_data 
      WHERE clinical_data_id = :clinicalDataId
      AND user_account_id = 
        (SELECT user_account_id FROM user_account
            WHERE email_address = :email
        )
    ;"
  );

  // execute the statement
  $executeSuccess = $statement->execute(array(
    ':clinicalDataId' => $clinicalDataId, ':email' => $_SESSION['email_address']
  ));

  // now check that this returned exactly 1 row
  $clinicalDataResults = $statement->fetchAll(PDO::FETCH_ASSOC);
  if (count($clinicalDataResults) === 1)
    $modifyOK = true;

  return $modifyOK;
}

// Function testIsFloat
//  Determines if a test is FLOAT, returns true if yes
//  Input
//    $clinicalTests - 2d array of clinical test labels and clinical test formats
//    $clinicalTestLabel - the label in question
//  Output
//    true or false
function testIsFloat($clinicalTests, $clinicalTestLabel){
  // loop through $clinicalTests
  foreach ($clinicalTests as $row){
    if ($row['clinical_test_label'] == $clinicalTestLabel){
      if ($row['clinical_test_format'] == 'FLOAT')
        return true;
      else 
        return false;
    }
  }
  // we should never get here unless the user is pully shenanigans, but we'll return false if we do
  return false;
}
?>
<?php

// Function loggedIn 
//  Returns True if user is logged in, false if not
function loggedIn() {
  if (isset($_SESSION['logged_in'])){
    if ($_SESSION['logged_in'] === true)
    {
      return true;
    }
  }

  return false;
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
function php2dTojs2d($php2d)
{
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
?>
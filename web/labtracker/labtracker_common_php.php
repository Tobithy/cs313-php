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



// create the items_in_cart aray if it doesn't exist yet. 
if (!isset($_SESSION['items_in_cart'])) {
  $_SESSION['items_in_cart'] = array();
}

// Define array of items to buy. This could be changed to an array of item objects
//  if we create an item class at some point. Right now it's just a 2d array that has a short key and 
//  a description
$available_items = array(
  array("snes-excellent", "Super Nintendo Entertainment System, excellent condition"),
  array("mario-kart-good", "SNES Mario Kart, good condition, fully tested and working"),
  array("super-mario-world-excellent", "SNES Super Mario World, excellent condition, fully tested and working"),
  array("tetris-and-dr-mario-excellent", "SNES Tetris & Dr. Mario, excellent condition, fully tested and working"),
  array("star-fox-good", "SNES Star Fox, good condition, fully tested and working"),
  array("star-fox-excellent", "SNES Star Fox, excellent condition, fully tested and working")
);

// Function test_input
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


// Function issetandfilled
//  Short function to check if a variable is set (not NULL) and it has data (it's not empty).
//    Use the trim function so that data that only consists of spaces is considered empty.
//  Input
//    $data - variable to be tested
//  Returns TRUE if it passes both tests, FALSE if not.
function issetandfilled($data) {
  if (!isset($data))
    return false;

  if (empty(trim($data)))
    return false;
  
  return true;
}

?>
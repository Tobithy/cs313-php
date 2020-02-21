<?php
session_start();
// Unset all session variables
session_unset();
session_destroy();
// Redirect user back to main page
$newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/";
header($newpage, true, 303);
die();
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTracker - Logout | CS313 Project 1</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

</head>

<body>
 
</body>


</html>
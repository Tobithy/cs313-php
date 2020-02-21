<?php
// A less cool way of making the the user can press "back" without getting a "page expired" message
// ini_set('session.cache_limiter', 'public');
// session_cache_limiter(false);

// use php sessions for tracking users
session_start();

// if the user is already logged in, skip this page and go straight to data_view
if (isset($_SESSION['email_address'])) {
  $newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/data_view.php";
  header($newpage, true, 303);
  die();
}

// If the user is logging in, time to check if they were successful.
if (isset($_POST['login'])) {
  // Load up the db
  require_once  $_SERVER["DOCUMENT_ROOT"] . '/labtracker/dbconnect.php';
  $emailAddress = $_POST['email'];

  // get hashed password from DB
  $statement = $db->prepare(
    "SELECT user_account_id, hashed_password, email_address FROM user_account
      WHERE email_address = :emailAddress
    ;"
  );
  $statement->execute(array(':emailAddress' => $emailAddress));
  $userResults = $statement->fetchAll(PDO::FETCH_ASSOC);

  // we should have returned one and only one row. If we returned less or more (more should be impossible because
  //  of the UNIQUE email constraint), then create a made-up hash and proceed to check it against the user's typed
  //  in password, in order to avoid any timing attacks.
  if (count($userResults) === 1) {
    $hashedPassword = $userResults[0]['hashed_password'];
  } else {
    $hashedPassword = 'THISISNOTAGOODHASHEDPASSWORD';
  }

  $signinSuccess = password_verify($_POST['password'], $hashedPassword);

  if ($signinSuccess) {
    $_SESSION['email_address'] = $userResults[0]['email_address'];

    // redirect the user using 303 redirect to improve back/reload experience. 
    //  Also, use HTTP_HOST to make an absolute path in order to function well if the user
    //  types in the address without trailing forward slash e.g. <root>/labtracker
    //  Also check for HTTPS so it functions OK on my local server.
    $newpage = "Location: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/labtracker/data_view.php";
    header($newpage, true, 303);
    die();
  } else {
    // do nothing, notify the user later
  }
}
// session_unset();
// session_destroy();
?>



<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTracker | CS313 Project 1</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- include this to avoid broken relative links if someone types in the URL without the trailing slash -->
  <!-- This is ONLY needed for index.php type pages, where you can access the page as domain/folder or  
        domain/folder/ -->
  <base href="/labtracker/" target="_self">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- App specific css -->
  <link rel="stylesheet" href="css/labtracker.css">
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php $currentPage = 'Login'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php /*require $_SERVER["DOCUMENT_ROOT"] . '/labtracker/labtracker_navbar.php';*/ // no navbar on login page 
  ?>


  <div class="container pt-3">
    <h2 class="display-5">LabTracker Login</h2>
  </div>

  <!-- notify the user if they didn't successfully login -->
  <div class="container pt-3">
    <?php
    // if this page resulted from a submission, we want to notify the user whether the 
    //  insertion was successful
    if (isset($_POST['login']) && !$signinSuccess) {
      print '<div class="alert alert-dismissible fade show';
      print ' alert-danger" role="alert">Invalid email address and/or password. Please try again.';
      print '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>';
    }
    ?>
  </div>

  <div class="container form-signin">
    <!--Not sure that the "form-signin" class does-->
    <form action="" method="POST">
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
      </div>
      <!-- <div class="form-check">
        <input type="checkbox" class="form-check-input" name="remember" id=remember>
        <label class="form-check-label" for="remember">Remember me (not recommended for public computers)</label>
      </div> -->
      <button type="submit" class="btn btn-primary" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href=signup.php>Click here</a> to create one for free!</p>
  </div>

</body>


</html>
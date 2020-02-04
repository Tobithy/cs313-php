<?php
// A less cool way of making the the user can press "back" without getting a "page expired" message
// ini_set('session.cache_limiter', 'public');
// session_cache_limiter(false);

// use php sessions for tracking users
session_start();

// If the user is logging in, time to check if they were successful. For now we'll just redirect to the next page
//  Next step will be to validate the username and password.
//  Later we'll maybe implement a check to redirect the user if they are already logged in
if (isset($_POST['login'])) {
  // redirect the user using 303 redirect to improve back/reload experience. 
  //  Also, use HTTP_HOST to make an absolute path in order to function well if the user
  //  types in the address without trailing forward slash e.g. <root>/labtracker
  //  Also check for HTTPS so it functions OK on my local server.
  $newpage = "Location: ". (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") ."://" . $_SERVER['HTTP_HOST'] . "/labtracker/data_view.php";
  header($newpage, true, 303);
  // header("Location: data_view.php", true, 303); // Redirect the user properly 
}

// session_unset();
// session_destroy();
?>



<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>LabTrack | CS313 Project 1</title>
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

  <div class="container form-signin"> <!--Not sure that the "form-signin" class does-->
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
  </div>

</body>


</html>
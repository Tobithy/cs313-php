<?php
// use php sessions for tracking users
session_start();

// include shopping_cart_common_php, for now it's just local
require 'shopping_cart_common_php.php';

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prove 03 | CS313 Shopping Cart - Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- App specific css -->
  <link rel="stylesheet" href="css/shoppingcart.css">
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php $currentPage = 'Checkout'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/prove_03_shopping_cart/shopping_navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-4">Checkout</h1>
  </div>

  <!-- Make a form for submitting -->
  <div class="container">
    <form action="confirmation.php" method="POST">
      <div class="row">
        <div class="col-md-6">
          <label for="email">Email address</label>
          <input type="email" class="form-control" placeholder="somebody@domain.com" name="email" id="email" required>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="fname">First name </label>
          <input type="text" class="form-control" placeholder="First name" name="fname" id="fname" required>
        </div>
        <div class="col-md-6">
          <label for="lname">Last name </label>
          <input type="text" class="form-control" placeholder="Last name" name="lname" id="lname" required>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="address1">Address line 1 </label>
          <input type="text" class="form-control" name="address1" id="address1" required>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="address2">Address line 2 </label>
          <input type="text" class="form-control" name="address2" id="address2">
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="address3">Address line 3 </label>
          <input type="text" class="form-control" name="address3" id="address3">
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="city">City</label>
          <input type="text" class="form-control" placeholder="City" name="city" id="city" required>
        </div>
        <div class="col-md-6">
          <label for="state">State</label>
          <input type="text" class="form-control" placeholder="State" name="state" id="state" required>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="zipcode">Zip code </label>
          <input type="text" class="form-control" placeholder="12345" name="zipcode" id="zipcode" required>
        </div>
      </div>
      <input type="submit" class="btn btn-primary mt-3" value="Submit Order">
    </form>
  </div>

</body>


</html>
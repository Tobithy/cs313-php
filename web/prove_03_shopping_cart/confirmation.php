<?php
// use php sessions for tracking users
session_start();

// include shopping_cart_common_php, for now it's just local
require 'shopping_cart_common_php.php';

// Store the user's shipping info in session variables (assuming they exist)
//  first create a variable to determine if the address is present and valid. We'll
//  start it at TRUE but if any part of the required address is missing it will 
//  be set to false. Also sanitizes data.

// email
$addressValid = true;
if ($addressValid && issetandfilled($_POST['email'])) {
  // we could filter this here but for this assignment we're not going that in depth
  $_SESSION['email'] = clean_input($_POST['email']);
} else {
  $addressValid = false;
}

// first and last name
if ($addressValid && issetandfilled($_POST['fname'])) {
  $_SESSION['fname'] = clean_input($_POST['fname']);
} else {
  $addressValid = false;
}
if ($addressValid && issetandfilled($_POST['lname'])) {
  $_SESSION['lname'] = clean_input($_POST['lname']);
} else {
  $addressValid = false;
}

// address values. Only address 1 is required
if ($addressValid && issetandfilled($_POST['address1'])) {
  $_SESSION['address1'] = clean_input($_POST['address1']);
} else {
  $addressValid = false;
}
if ($addressValid && issetandfilled($_POST['address2'])) {
  $_SESSION['address2'] = clean_input($_POST['address2']);
} else {
  // address 2 not required, just make sure it's unset
  unset($_SESSION['address2']);
}
if ($addressValid && issetandfilled($_POST['address3'])) {
  $_SESSION['address3'] = clean_input($_POST['address3']);
} else {
  // address 3 not required, just make sure it's unset
  unset($_SESSION['address3']);
}

// city, state, and zip
if ($addressValid && issetandfilled($_POST['city'])) {
  $_SESSION['city'] = clean_input($_POST['city']);
} else {
  $addressValid = false;
}
if ($addressValid && issetandfilled($_POST['state'])) {
  $_SESSION['state'] = clean_input($_POST['state']);
} else {
  $addressValid = false;
}
if ($addressValid && issetandfilled($_POST['zipcode'])) {
  $_SESSION['zipcode'] = clean_input($_POST['zipcode']);
} else {
  $addressValid = false;
}

?>



<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prove 03 | CS313 Shopping Cart - Confirmation</title>
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
  <?php /* $currentPage = 'View Cart'; */ // Confirmation is not navigable?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/prove_03_shopping_cart/shopping_navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-4">Order Complete</h1>
  </div>

  <!-- Use a table to present items -->
  <div class="container">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Items Purchased</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($_SESSION['items_in_cart'] as $item_index => $item) {
          $item_row = array_search($item, array_column($available_items, 0));
        ?>
          <tr>
            <td><?php echo $available_items[$item_row][1] ?></td>
          </tr>
        <?php } //end loop 
        ?>
      </tbody>
    </table>
    <?php
    // Show address if items were purchased
    if (count($_SESSION['items_in_cart']) > 0) {
      echo '<h3 class="display-6">Shipping Address</h3>';
      echo '<p>' . $_SESSION['fname'] . ' ' . $_SESSION['lname'] . '<br>';
      echo $_SESSION['address1'] . '<br>';
      if (isset($_SESSION['address2'])) echo $_SESSION['address2'] . '<br>';
      if (isset($_SESSION['address3'])) echo $_SESSION['address3'] . '<br>';
      echo $_SESSION['city'] . ', ' . $_SESSION['state'] . ' ' . $_SESSION['zipcode'] . '</p>';
      echo '<p>Thank you for your purchase!</p>';

      // clear out 'items_in_cart' since they have been bought
      unset($_SESSION['items_in_cart']);
    } else {
      echo '<p>No items were purchased</p>';
    }
    ?>
  </div>

</body>


</html>
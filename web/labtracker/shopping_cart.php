<?php
// use php sessions for tracking users
session_start();

// include shopping_cart_common_php, for now it's just local
require 'shopping_cart_common_php.php';

// Now we need to see if the user removed anything from their cart. 
// If so, remove it from their session array
if (isset($_GET['remove'])) {
  $index = clean_input($_GET['remove']);

  // proceed with remove ONLY IF the input is numeric
  if (is_numeric($index)){
    $index = intval($index);  // convert to integer
    if ($index >= 0 && $index < count($_SESSION['items_in_cart'])){
      array_splice($_SESSION['items_in_cart'],$index,1);  // array_splice to remove one element
    }
  }
}
?>



<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prove 03 | CS313 Shopping Cart - Cart</title>
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
  <?php $currentPage = 'View Cart'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/prove_03_shopping_cart/shopping_navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-4">Your Cart</h1>
  </div>

  <!-- Use a table to present items -->
  <div class="container">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Items in Cart</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($_SESSION['items_in_cart'] as $item_index => $item) {
          $item_row = array_search($item, array_column($available_items, 0));
        ?>
        <tr>
          <td><?php echo $available_items[$item_row][1]?></td>
          <td><a href="?remove=<?php echo $item_index?>">Remove</a></td>
        </tr>
        <?php } //end loop ?> 
      </tbody>
    </table>
    <?php 
    if (count($_SESSION['items_in_cart']) == 0){
      echo '<p>Your cart is empty</p>';
    }
    ?>
  </div>

</body>


</html>
<?php
// use php sessions for tracking users
session_start();

// include shopping_cart_common_php, for now it's just local
require 'shopping_cart_common_php.php';
// Now we need to see if the user bought anything. If so, add it to their session array (assuming it's valid)
if (isset($_GET['add'])) {
  $item_to_add = clean_input($_GET['add']);

  // if the item is a valid item, add it to the array
  if (in_array($item_to_add, array_column($available_items, 0), TRUE)) {
    array_push($_SESSION['items_in_cart'], $item_to_add);
  }
}
// session_unset();
// session_destroy();
?>



<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prove 03 | CS313 Shopping Cart - Browse</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- include this to avoid broken relative links if someone types in the URL without the trailing slash -->
  <!-- This is ONLY needed for index.php type pages, where you can access the page as domain/folder or  
        domain/folder/ -->
  <base href="/prove_03_shopping_cart/" target="_self">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- App specific css -->
  <link rel="stylesheet" href="css/shoppingcart.css">
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php $currentPage = 'Browse Items'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/prove_03_shopping_cart/shopping_navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-4">Browse SNES Items</h1>
  </div>

  <!-- Lay out items for sale in a grid. Tiles are cards. -->
  <div class="container">
    <div class="card-deck">
      <div class="card">
        <div class="card-header">
          <img class="card-img-top" src="img/snes.jpg" alt="SNES system">
        </div>
        <div class="card-body">
          <h4 class="card-title">SNES</h4>
          <p class="card-text">Super Nintendo Entertainment System, excellent condition</p>
          <p class="card-text">$150.00</p>
        </div>
        <div class="card-footer">
          <a href="?add=snes-excellent" class="btn btn-primary">Add to cart</a>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <img class="card-img-top" src="img/mario-kart.jpg" alt="SNES Mario Kart">
        </div>
        <div class="card-body">
          <h4 class="card-title">Mario Kart - SNES</h4>
          <p class="card-text">SNES Mario Kart, good condition, fully tested and working</p>
          <p class="card-text">$25.00</p>
        </div>
        <div class="card-footer">
          <a href="?add=mario-kart-good" class="btn btn-primary">Add to cart</a>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <img class="card-img-top" src="img/super-mario-world.jpg" alt="Super Mario World">
        </div>
        <div class="card-body">
          <h4 class="card-title">Super Mario World - SNES</h4>
          <p class="card-text">SNES Super Mario World, excellent condition, fully tested and working</p>
          <p class="card-text">$35.00</p>
        </div>
        <div class="card-footer">
          <a href="?add=super-mario-world-excellent" class="btn btn-primary">Add to cart</a>
        </div>
      </div>
    </div>

    <div class="card-deck mt-3">
      <div class="card">
        <div class="card-header">
          <img class="card-img-top" src="img/tetris-dr-mario.jpg" alt="SNES Tetris & Dr Mario">
        </div>
        <div class="card-body">
          <h4 class="card-title">Tetris & Dr. Mario - SNES</h4>
          <p class="card-text">SNES Tetris & Dr. Mario, excellent condition, fully tested and working</p>
          <p class="card-text">$50.00</p>
        </div>
        <div class="card-footer">
          <a href="?add=tetris-and-dr-mario-excellent" class="btn btn-primary">Add to cart</a>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <img class="card-img-top" src="img/star-fox.jpg" alt="SNES Star Fox">
        </div>
        <div class="card-body">
          <h4 class="card-title">Star Fox - SNES</h4>
          <p class="card-text">SNES Star Fox, good condition, fully tested and working</p>
          <p class="card-text">$10.00</p>
        </div>
        <div class="card-footer">
          <a href="?add=star-fox-good" class="btn btn-primary">Add to cart</a>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <img class="card-img-top" src="img/star-fox.jpg" alt="SNES Star Fox">
        </div>
        <div class="card-body">
          <h4 class="card-title">Star Fox - SNES</h4>
          <p class="card-text">SNES Star Fox, excellent condition, fully tested and working</p>
          <p class="card-text">$20.00</p>
        </div>
        <div class="card-footer">
          <a href="?add=star-fox-excellent" class="btn btn-primary">Add to cart</a>
        </div>
      </div>
    </div>
  </div>

</body>


</html>
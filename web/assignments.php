<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prove 02 | CS313 Assignments</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- Page specific css (use absolute path from web root) -->
  <link rel="stylesheet" href="/css/assignments.css">
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php $currentPage = 'Assignments'; ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-3">Assignments</h1>
  </div>

  <!-- Lay out assignments in a grid. Tiles are cards. -->
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="card bg-warning h-100">
          <img class="card-img-top" src="img_assignments/03_prove_shopping_cart.svg" alt="Prove 3: Shopping Cart">
          <div class="card-body">
            <h4 class="card-title">Prove 03</h4>
            <p class="card-text">Shopping Cart</p>
            <a href="prove_03_shopping_cart/" class="stretched-link"></a>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>


</html>
<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prove 03 | CS313 Shopping Cart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- Page specific css -->
  <link rel="stylesheet" href="/css/assignments.css">
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php /*$currentPage = 'Assignments';//No active for now*/ ?>
  <?php include $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-3">Shopping Cart</h1>
  </div>

</body>


</html>
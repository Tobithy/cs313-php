<!-- Collapsible navbar. Should stay at the top of the screen -->
<nav class="navbar navbar-expand-sm bg-primary navbar-dark sticky-top">
  <!-- Button to collapse navbar -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#shoppingCartNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar -->
  <div class="collapse navbar-collapse" id="shoppingCartNavbar">
    <ul class="navbar-nav">
      <!-- php in order to format NavBar for currentPage -->
      <?php
        // urls and names array
        $urls = array(
          'Browse Items' => './',
          'View Cart' => 'shopping_cart.php',
          'Checkout' => 'checkout.php'
        );

        foreach ($urls as $name => $url) {
          echo '<li class="nav-item';
          if (isset($currentPage) && ($currentPage === $name)) {
            echo ' active';
          }
          echo '"><a class="nav-link" href="'.$url.'">'.$name.'</a></li>';
        }
      ?>
    </ul>
  </div>
</nav>
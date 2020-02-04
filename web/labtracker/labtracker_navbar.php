<!-- Collapsible navbar. Should stay at the top of the screen -->
<nav class="navbar navbar-expand-sm bg-primary navbar-dark sticky-top">
  <!-- Button to collapse navbar -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#labtrackerNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar -->
  <div class="collapse navbar-collapse" id="labtrackerNavbar">
    <ul class="navbar-nav">
      <!-- php in order to format NavBar for currentPage -->
      <?php
      // urls and names array
      $urls = array(
        'My Data' => 'data_view.php',
        'Add Data' => 'data_add.php',
        'My Account' => 'account_settings.php',
        'Logout' => 'logout.php'
      );

      foreach ($urls as $name => $url) {
        echo '<li class="nav-item';
        if (isset($currentPage) && ($currentPage === $name)) {
          echo ' active';
        }
        echo '"><a class="nav-link" href="' . $url . '">' . $name . '</a></li>';
      }
      ?>
    </ul>
  </div>
</nav>
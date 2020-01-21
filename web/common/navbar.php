<!-- Collapsible navbar. Should stay at the top of the screen -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
  <!-- Button to collapse navbar -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar -->
  <div class="collapse navbar-collapse" id="mainNavbar">
    <ul class="navbar-nav">
      <!-- php in order to format NavBar for currentPage -->
      <?php
        // urls and names array
        $urls = array(
          'Landing' => '/landing.php',
          'Assignments' => '/assignments.php'
        );

        foreach ($urls as $name => $url) {
          echo '<li class="nav-item';
          if ($currentPage === $name) {
            echo ' active';
          }
          echo '"><a class="nav-link" href="'.$url.'">'.$name.'</a></li>';
        }
      ?>
    </ul>
  </div>
</nav>
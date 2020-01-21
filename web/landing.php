<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prove 02 | CS313 Landing Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- Bootstrap necessaries -->
  <?php require 'common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires -->
  <?php require 'common/sitewide_includes.php'; ?>
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php $currentPage = 'Landing'; ?>
  <?php require 'common/navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-3">About me</h1>
    <p>
      Hello and welcome to my homepage for CS 313. My name is Gordon Mancuso. I am a medical physicist, and am currently pursuing a software engineering degree at Brigham Young University - Idaho.
    </p>
    <p>
      I am married with two kids. Below is a picture of my awesome family.
    </p>
    <figure class="figure text-center d-block">
      <img src="img/family_halloween_small.jpg" class="figure-img rounded img-fluid" alt="Family dressed as Tangled">
      <figcaption class="figure-caption">Some days Eugene wears a full beard</figcaption>
    </figure>

    <h2>Firefly: The Game</h2>
    <p>
      One of my favorite things to do in my spare time (of which there is precious little these days) is to play board games. I have played many different games, and always am interested in learning new games. One of my favorite games of all time is <a href="https://firefly.gf9games.com/">Firefly: The Game</a>. The game is really a labor of love, and I wholeheartedly recommend it to anyone who is a fan of the TV series.
    </p>
    <figure class="figure text-center d-block">
      <img src="img/firefly_in_progress_small.jpg" class="figure-img rounded img-fluid" alt="Firefly: The Game in progress. Shiny!">
      <figcaption class="figure-caption">Yes, this game has a lot of parts. Look at the pretties!</figcaption>
    </figure>
  </div>

</body>


</html>
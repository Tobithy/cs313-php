<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Teach 03 | CS313 PHP forms - Return input</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">


</head>

<body>
  Name: <?php echo $_POST["name"]; ?><br>
  Email: <?php echo $_POST["email"]; ?><br>
  Major: <?php echo $_POST["major"]; ?><br>
  Comments:<br>
  <?php echo $_POST["comments"]; ?><br>
  Continents visited:<br>
  <?php
    $continent=$_POST["continent"];
    $N = count($continent);

    $continent_map = array("na"=>"North America", "sa"=>"South America", "eu"=>"Europe", "as"=>"Asia",
      "au"=>"Australia", "af"=>"Africa", "an"=>"Antarctica");
    
    for($i=0; $i < $N; $i++)
    {
      echo($continent_map[$continent[$i]] . "<br>");
    }
  ?>
  <br>

</body>


</html>
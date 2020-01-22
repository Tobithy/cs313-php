<?php
$majors = array("Computer Science", "Web Design and Development", "Computer Information Technology", "Computer Engineering");
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Teach 03 | CS313 PHP forms</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">


</head>

<body>
  <form action="teach03_return.php" method="POST">
    Name: <input type="text" name="name" id="name"><br>
    Email: <input type="text" name="email" id="email"><br>
    Major:<br>

    <?php
    foreach ($majors as $major) {
    ?>
      <input type="radio" name="major" value="<?php echo $major; ?>"> <?php echo $major; ?><br>
    <?php
    }
    ?>
    Comments:<br>
    <textarea name="comments" rows="5" cols="50"></textarea>
    <br>
    What continents have you visited?<br>
    <input type="checkbox" name="continent[]" value="na"> North America<br>
    <input type="checkbox" name="continent[]" value="sa"> South America<br>
    <input type="checkbox" name="continent[]" value="eu"> Europe<br>
    <input type="checkbox" name="continent[]" value="as"> Asia<br>
    <input type="checkbox" name="continent[]" value="au"> Australia<br>
    <input type="checkbox" name="continent[]" value="af"> Africa<br>
    <input type="checkbox" name="continent[]" value="an"> Antarctica<br>

    <input type="submit">
  </form>

</body>


</html>
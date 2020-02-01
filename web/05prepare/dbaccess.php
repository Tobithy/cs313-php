<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Prepare 05 | Database Access in PHP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

  <!-- Bootstrap necessaries -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/bootstrap_includes.php'; ?>

  <!-- Sitewide requires (use absolute paths for sitewide php) -->
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/sitewide_includes.php'; ?>

  <!-- Page specific css (use absolute path from web root) -->
  <!-- <link rel="stylesheet" href="/css/assignments.css"> -->
</head>

<body>
  <!-- Navbar. currentPage variable ensures correct "active" status in the navbar -->
  <?php /*$currentPage = 'Assignments'; */ ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/common/navbar.php'; ?>


  <div class="container pt-3">
    <h1 class="display-3">DB Access</h1>
  </div>

  <div class="container pt-3">
  <?php

  // default Heroku Postgres configuration URL
  $dbUrl = getenv('DATABASE_URL');

  if (empty($dbUrl)) {
    // This gets us the heroku credentials without revealing credentials in our code
    $dbUrl = exec("heroku config:get DATABASE_URL");
  }

  $dbopts = parse_url($dbUrl);

  $dbHost = $dbopts["host"];
  $dbPort = $dbopts["port"];
  $dbUser = $dbopts["user"];
  $dbPassword = $dbopts["pass"];
  $dbName = ltrim($dbopts["path"], '/');

  // print "<p>pgsql:host=$dbHost;port=$dbPort;dbname=$dbName</p>\n\n";

  try {
    $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

    // this line makes PDO give us an exception when there are problems,
    // and can be very helpful in debugging! (But you would likely want
    // to disable it for production environments.)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $ex) {
    print "<p>error: " . $ex->getMessage() . "</p>\n\n";
    die();
  }

  print '<h3>Foreach</h3>';
  $start_time = microtime(true); 
  foreach ($db->query('SELECT * FROM user_account') as $row) {
    print 'User Name: ' . $row['first_name'] . ' ' . $row['last_name'] . '<br>';
    print 'Email Address: ' . $row['email_address'];
    print '<br><br>';
  }
  $end_time = microtime(true); 
  $execution_time = ($end_time - $start_time); 
  echo " Execution time of Foreach = ".$execution_time." sec";
  print "<br>";

  print '<h3>While statement</h3>';
  print '<p>This should be better than Foreach because it only has to query the DB once</p>';
  $start_time = microtime(true);
  $statement = $db->query('SELECT * FROM user_account');
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
    print 'User Name: ' . $row['first_name'] . ' ' . $row['last_name'] . '<br>';
    print 'Email Address: ' . $row['email_address'];
    print '<br><br>';
  }
  $end_time = microtime(true); 
  $execution_time = ($end_time - $start_time); 
  echo " Execution time of While = ".$execution_time." sec";
  print "<br>";

  print '<h3>fetchAll</h3>';  
  // This is a good way to just grab all the results and then do what you want with them
  $statement = $db->query('SELECT * FROM user_account');
  $results = $statement->fetchAll(PDO::FETCH_ASSOC);
  print "<br>";

  // prepared statements are necessary any time you are sending user-supplied data to the 
  //  DB to avoid SQL injection attacks
  print '<h3>Repeat While using prepared statement</h3>';
  $start_time = microtime(true);
  $stmt = $db->prepare('SELECT * FROM user_account WHERE user_account_id=:id OR first_name=:firstName');
  $stmt->execute(array(':id' => '1', ':firstName'=>'Bob'));
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    print 'User Name: ' . $row['first_name'] . ' ' . $row['last_name'] . '<br>';
    print 'Email Address: ' . $row['email_address'];
    print '<br><br>';
  }
  $end_time = microtime(true); 
  $execution_time = ($end_time - $start_time); 
  echo " Execution time of prepared While = ".$execution_time." sec";
  print "<br>";
  ?>

  </div>

</body>


</html>
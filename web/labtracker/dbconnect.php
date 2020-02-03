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
?>
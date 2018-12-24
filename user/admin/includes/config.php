<?php
// DB credentials.
//define('DB_HOST','localhost');
//define('DB_USER','ises9801_isese');
//define('DB_PASS','N_3m6!18');
//define('DB_NAME','ises9801_kayit');

// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','iseser_uyelik');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
$error = NULL;
$msg = NULL;
?>
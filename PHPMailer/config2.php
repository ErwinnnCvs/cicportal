<?php
//Database information

define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "chat");

//Database table information 
define("USER_TABLE", "tbUsers");
define("LOGGED_IN_TABLE", "tbLoggedInUsers");

//Fully Qualified Domain Name
define("SITE_HTTP", "");

//Return email address
define("FROM_EMAIL", "");

$dbh = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME)
#$dbh  = new PDO('mysql:dbname='.$db.';host='.$host.';port='.$port,$user,$pass);

?>
<?php
//Database information

// define("DB_SERVER", "10.250.111.80");
// define("DB_USER", "remoteportal");
// define("DB_PASS", "R0g3rR@bb1t");
// define("DB_NAME", "cicportal");

define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "cicportal");

$servername1 = "10.250.111.80";
$username1 = "disputeuser";
$password1 = "P3t3rR@bb1t!";

// define("DB_SERVER", "localhost");
// define("DB_USER", "root");
// define("DB_PASS", "");
// define("DB_NAME", "ceportal");

//Database table information
define("USER_TABLE", "tbusers");
define("LOGGED_IN_TABLE", "tbLoggedInUsers");

//Fully Qualified Domain Name
define("SITE_HTTP", "");

//Return email address
define("FROM_EMAIL", "");

$dbh = new mysqli("localhost", "root", "", "cicportal");
$dbh2 = new mysqli("localhost", "root", "", "dispute");
$dbh3 = new mysqli("localhost", "root", "", "appointment");
$dbh4 = mysqli_connect("localhost", "root", "", "cicseis");
$dbh5 = new mysqli("localhost", "root", "", "employeeportal");
define("EMAIL_USER", "cicportal@creditinfo.gov.ph");
define("EMAIL_PASS", "tefg hodr yguc xhyi");
//$dbh2 = new mysqli("10.250.111.80", "seisuser", "h0neyBadg3r", "cicseis");
//$dbh3 = new mysqli(DB_SERVER, DB_USER, DB_PASS, "appointment");
// $dbh4 = new mysqli("10.250.111.80", "newportal80", "cR3d1tInf0rm4t!on80", "dispute");
//$dbh5 = new mysqli("10.250.111.80", "empuser82", "LELxn9P", "employeeportal");

//$dbh4 = mysqli_connect($servername1, $username1, $password1, "dispute");

// $dbh6 = new mysqli("10.250.106.33", "newportal", "cR3d1tInf0rm4t!on", "cicportal");


// $sql = $dbh2->query("SELECT AES_DECRYPT(fld_username, MD5('8CZw[XMy?;Egs?<')) AS username, AES_DECRYPT(fld_password, MD5('8CZw[XMy?;Egs?<')) AS password FROM mailer.user WHERE AES_DECRYPT(fld_username, MD5('8CZw[XMy?;Egs?<*')) = 'cicportal@creditinfo.gov.ph';");
// $r = $sql->fetch_array();
// define("EMAIL_USER", $r['username']);
// define("EMAIL_PASS", $r['password']);

?>
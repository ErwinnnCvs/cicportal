<?php
//Database information

#define("DB_SERVER", "localhost");
#define("DB_USER", "root");
#define("DB_PASS", "");
#define("DB_NAME", "cicportal");

define("DB_SERVER", "10.250.106.33");
define("DB_USER", "myuser1");
define("DB_PASS", "mypassword");
define("DB_NAME", "cicportal");

//Database table information 
define("USER_TABLE", "tbusers");
define("LOGGED_IN_TABLE", "tbLoggedInUsers");

//Fully Qualified Domain Name
define("SITE_HTTP", "");

//Return email address
define("FROM_EMAIL", "");

$dbh = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
# CONNECTION TO DASHBOARD SERVER
#$dbh1 = new mysqli("10.250.106.15", "complianceuser", "dgzr2EMXsqss5cL6", "cicdms");

#$dbh2 = new mysqli("10.250.111.80", "portal98", "Bl@ckR4bb1t", "dispute");

$dbh3 = new mysqli("10.250.111.80", "email98", "d4n13lL0p3z", "cicportal");

$dbh4 = new mysqli("10.250.111.80", "potalseis", "cR3d1tInf0rm4t!on", "cicseis");

$dbh5 = new mysqli("10.250.111.80", "cimsazureconn", "\$HSz1#Zd@d(xwc9", "employeeportal");

$sql = $dbh->query("SELECT AES_DECRYPT(fld_username, MD5('8CZw[XMy?;Egs?<*')) AS username, AES_DECRYPT(fld_password, MD5('8CZw[XMy?;Egs?<*')) AS password FROM mailer.user WHERE AES_DECRYPT(fld_username, MD5('8CZw[XMy?;Egs?<*')) = 'cicportal@creditinfo.gov.ph';");
$r = $sql->fetch_array();
define("EMAIL_USER", $r['username']);
define("EMAIL_PASS", $r['password']);



?>

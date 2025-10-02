<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once'../PHPMailer/PHPMailerAutoload.php';
require_once'../config.php';

$get_entity_for_account_creation = $dbh4->query("SELECT fld_ctrlno FROM tbentities WHERE fld_uat_ceportal_sent = 1 LIMIT 1");
$gefac=$get_entity_for_account_creation->fetch_array();

echo $gefac['fld_ctrlno'];

?>
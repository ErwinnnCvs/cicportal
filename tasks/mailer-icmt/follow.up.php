<?php
include("../config.php");

// require '..\includes\PHPMailer.php';
// require '..\includes\SMTP.php';
// require '..\includes\Exception.php';

require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';

// require('..\includes\PHPMailer.php');
// require('..\includes\SMTP.php');
// require('..\includes\Exception.php');



ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

//$datetoday = date("Y-m-d H:i:s");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = "true";
$mail->SMTPSecure = "tls";
$mail->Port = "587";
$mail->Username = EMAIL_USER;
$mail->Password = EMAIL_PASS;
$mail->Subject = "Follow Up Notice for: " . $ctrl_no;
$mail->setFrom("ta.cicportal.0526@gmail.com", "CIC Mailer");
$mail->isHTML(true);

$message = 
    "<h4>Hello, " . $cm_nm['company_name'] . "! </h4><br> " . 
    "Follow Up Regarding Your Submission. <br>"  .
    "<b> Remarks:  </b>". $_POST['remarksTxt'] . "<br>";


$mail->msgHTML($message);

$mail->addAddress("princess.bullos@creditinfo.gov.ph","Princess");
//$mail->addAddress($cs_tb['fld_email_ar'], $cs_tb['fld_fname_ar']);
//$mail->addCC("jitsukki@gmail.com", "Jitsukki Group of Companies");

if ($mail->send()) {
    $msg = "Successfully Updated and Email Sent!";
    $msgclr = "success";
}
else {
    $msg = "email not sent";
    $msgclr = "danger";
}

$mail->smtpClose();


?>
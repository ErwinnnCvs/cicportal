<?php
require_once '../PHPMailerAutoload.php';
 
$results_messages = array();
 
$mail = new PHPMailer(true);
$mail->CharSet = 'utf-8';
ini_set('default_charset', 'UTF-8');
 
class phpmailerAppException extends phpmailerException {}
 
try {
$to = 'micoreyesiphone@gmail.com';
if(!PHPMailer::validateAddress($to)) {
  throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
}
$mail->isSMTP();
$mail->SMTPDebug  = 2;
$mail->Host       = "smtp.gmail.com";
$mail->Port       = "587";
$mail->SMTPSecure = "tls";
$mail->SMTPAuth   = true;
$mail->Username   = "no_reply@creditinfo.gov.ph";
$mail->Password   = "portal*C1c2017";
$mail->addReplyTo("no_reply@creditinfo.gov.ph", "CIS Mailer");
$mail->setFrom("no_reply@creditinfo.gov.ph", "CIS Mailer");
$mail->addAddress("micoreyesiphone@gmail.com", "Mico Reyes");
$mail->Subject  = "Testing (PHPMailer test using SMTP)";
$body = <<<'EOT'
this is just a test
EOT;
$mail->WordWrap = 78;
$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
$mail->addAttachment('images/phpmailer_mini.png','phpmailer_mini.png');  // optional name
$mail->addAttachment('images/phpmailer.png', 'phpmailer.png');  // optional name
 
try {
  $mail->send();
  $results_messages[] = "Message has been sent using SMTP";
}
catch (phpmailerException $e) {
  throw new phpmailerAppException('Unable to send to: ' . $to. ': '.$e->getMessage());
}
}
catch (phpmailerAppException $e) {
  $results_messages[] = $e->errorMessage();
}
 
if (count($results_messages) > 0) {
  echo "<h2>Run results</h2>\n";
  echo "<ul>\n";
foreach ($results_messages as $result) {
  echo "<li>$result</li>\n";
}
echo "</ul>\n";
}
?>
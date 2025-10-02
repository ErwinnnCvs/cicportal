<?php
	require_once 'PHPMailerAutoload.php';
	
	## EMAIL SCRIPT FOR MONITORING

	$mail = new PHPMailer(true);
	$mail->CharSet = 'utf-8';
	ini_set('default_charset', 'UTF-8');
	
	$mail->isSMTP();
	$mail->SMTPDebug  = 0;
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = "587";
	$mail->SMTPSecure = "tls";
	$mail->SMTPAuth   = true;
	$mail->Username   = "no_reply@creditinfo.gov.ph";
	$mail->Password   = "portal*C1c2017";
	$mail->addReplyTo("no_reply@creditinfo.gov.ph", "Credit Information System Mailer");
	$mail->setFrom("no_reply@creditinfo.gov.ph", "CIS Mailer");
	$mail->addAddress("gilescalante@gmail.com", "Gil Escalante");
	$mail->Subject  = "Test cron using email";
	$mail->WordWrap = 78;
	$mail->msgHTML("Hello there", dirname(__FILE__), true); //Create message bodies and embed images
	try {
		$mail->send();
		$results_messages[] = "Message has been sent using SMTP";
	}catch (phpmailerException $e) {
		throw new phpmailerAppException('Unable to send to: ' . $to. ': '.$e->getMessage());
	}
}
catch (phpmailerAppException $e) {
  $results_errormessages[] = $e->errorMessage();
}	
?>

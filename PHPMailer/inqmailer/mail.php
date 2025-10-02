<?php
	require_once 'PHPMailerAutoload.php';
	
	include("inqmailer.php");
	
	## EMAIL SCRIPT FOR MONITORING
	$mail = new PHPMailer(true);
	$mail->CharSet = 'utf-8';
	$mail->isSMTP();
	$mail->SMTPDebug  = 0;
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = "587";
	$mail->SMTPSecure = "tls";
	$mail->SMTPAuth   = true;
	$mail->Username   = "gilescalante";
	$mail->Password   = "TeaTurmeric127!";
	$mail->addReplyTo("no_reply@creditinfo.gov.ph", "Credit Information System Mailer");
	$mail->setFrom("no_reply@creditinfo.gov.ph", "CIS Mailer");
	$mail->addAddress("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
#	$mail->addAddress("bon.bautista@creditinfo.gov.ph", "Bon Bautista");
#	$mail->addCC("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
#	$mail->addCC("karlmichael.reyes@creditinfo.gov.ph", "Karl Michael Reyes");
	$mail->Subject  = "CIS Inquiries Daily Report [Testing lang]";
/*	$body = "Hi Gil,<br/><br/>This is your test email. It works!!!<br/><br/>From,<br/><br/>Gil din";*/
	$mail->WordWrap = 78;
	$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
	if($mail->send()){
		$mailsent1 = 1;
		echo "sent";
	}


?>

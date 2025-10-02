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
	$mail->Username   = "no_reply@creditinfo.gov.ph";
	$mail->Password   = "portal*C1c2017";
	$mail->addReplyTo("no_reply@creditinfo.gov.ph", "Credit Information System Mailer");
	$mail->setFrom("no_reply@creditinfo.gov.ph", "CIS Mailer");
	$mail->addAddress("doris.kalalo@creditinfo.gov.ph", "Doris Kalalo");
	$mail->addAddress("sien.cabasis@creditinfo.gov.ph", "Maria Siena Cabasis");
	$mail->addAddress("alyssa.marie.vicente@creditinfo.gov.ph", "Alyssa Marie Vicente");
#	$mail->addCC("jaime.garchitorena@creditinfo.gov.ph", "Jaime Garchitorena");
	$mail->addCC("bon.bautista@creditinfo.gov.ph", "Bon Bautista");
	$mail->addCC("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
	$mail->Subject  = "CIS Inquiries Daily Report";
	$mail->WordWrap = 78;
	$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
	if($mail->send()){
		$mailsent1 = 1;
		echo "sent";
	}
?>

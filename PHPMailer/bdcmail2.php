<?php
	function encrypt($string, $key){
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
	}
	
	require_once 'PHPMailerAutoload.php';
	
	include("config.php");

	$sql=$dbh->query("SELECT * FROM tbemailaddressee WHERE fld_senddate < '".strtotime(date("Y-m-d H:i:s"))."' AND fld_status = '0' ORDER BY fld_senddate ASC, fld_eid ASC LIMIT 1");

	$e1=$sql->fetch_array();
	
	$info = encrypt($e1['fld_eid']."|".$e1['fld_email'], "C1CP0rt@l");
#	$info = $e1['fld_eid']."|".$e1['fld_email'];
	
	$sql1=$dbh->query("SELECT * FROM tbsendemail WHERE fld_eid = '".$e1['fld_sendmail_id']."'");
	$f1=$sql1->fetch_array();

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
	$mail->addAddress($e1['fld_email'], $e1['fld_name']);
	$mail->Subject  = $f1['fld_emailsubject'];
	$mail->WordWrap = 78;
	$mail->msgHTML("<img src='http://www.creditinfo.gov.ph/cicportal/stats.php?id=".urlencode($info)."' style='display:none'>".$f1['fld_emailfile'], dirname(__FILE__), true); //Create message bodies and embed images
	if($mail->send()){
		$sql2=$dbh->query("UPDATE tbemailaddressee SET fld_status = '1' WHERE fld_eid = '".$e1['fld_eid']."'");
	}else{
		$sql2=$dbh->query("UPDATE tbemailaddressee SET fld_status = '2' WHERE fld_eid = '".$e1['fld_eid']."'");
	}
?>

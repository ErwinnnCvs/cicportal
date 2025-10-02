<?php
require_once 'config.php';
require_once 'PHPMailerAutoload.php';


$inq = "<table class='table table-bordered' bgcolor='#494949' cellspacing='1' cellpadding='5'><tr><th width = '5%' bgcolor='#bbbbbb'></th><th width = '15%' bgcolor='#bbbbbb'><center>Provider Code</center></th>"
  ."<th width = '40%' bgcolor='#bbbbbb'><center>Company Name</center></th><th width = '10%' bgcolor='#bbbbbb'><center>Inquiries</center></th><th width = '10%' bgcolor='#bbbbbb'><center>Beginning Balance</center></th></tr>";

$timestamp = date("Y-m-d H:i:s");
$sql = $dbh->query("SELECT * FROM tbbilling WHERE fld_advance_payment_current > 0");
while($e1=$sql->fetch_array()){
  $sql1=$dbh->query("SELECT fld_code, fld_name FROM tbfininst WHERE fld_code = '".$e1['fld_provcode']."'");
  $p1=$sql1->fetch_array();

  if ($p1['fld_code']) {
    $cnt1++;
    $inq .= "<tr><td bgcolor='#ffffff'><center>".$cnt1."</center></td><td bgcolor='#ffffff'><center>".$e1['fld_provcode']."</center></td><td bgcolor='#ffffff'>".$p1['fld_name']."</td><td bgcolor='#ffffff'><center>"
      .number_format($e1['fld_access_limit_current'], 0, ".", ",")."</center></td><td bgcolor='#ffffff'><center>".$e1['fld_advance_payment_current']."</center></td></tr>";
  }

}

$inq .= "</table>";

$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
<head>
	<!--[if gte mso 9]>
	<xml>
		<o:OfficeDocumentSettings>
		<o:AllowPNG/>
		<o:PixelsPerInch>96</o:PixelsPerInch>
		</o:OfficeDocumentSettings>
	</xml><![endif]-->
	<!-- fix outlook zooming on 120 DPI windows devices -->
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'> <!-- So that mobile will display zoomed in -->
	<meta http-equiv='X-UA-Compatible' content='IE=edge'> <!-- enable media queries for windows phone 8 -->
	<meta name='format-detection' content='date=no'> <!-- disable auto date linking in iOS 7-9 -->
	<meta name='format-detection' content='telephone=no'> <!-- disable auto telephone linking in iOS 7-9 -->
	<title>Billing Inquiries Limit</title>
	<link rel='stylesheet' type='text/css' href='styles.css'>
	<link rel='stylesheet' type='text/css' href='responsive.css'>
</head>
<body style='margin:0; padding:0;' bgcolor='#F0F0F0' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
	<!-- 100% background wrapper (grey background) -->
	<table border='0' width='50%' height='100%' cellpadding='0' cellspacing='0' bgcolor='#F0F0F0' align='center'>
  <tr style='background-color:#f5f5f5'>
     <td align='center' style='border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);padding-bottom: 15px' class='m_-3480498238929770810gmail-m_3586475887212474448null-pad-logo'><a href='http://www.creditinfo.gov.ph/' style='display:block;margin-bottom:10px' target='_blank'><img class='m_-3480498238929770810gmail-m_3586475887212474448head_1_logo-833 CToWUd' src='www.creditinfo.gov.ph/cicportal/images/emailer/regularNewsLetter.jpg' width='100%' style='background-color: #f5f5f5' alt=' style='max-width:645px;display:block'></a></td>
  </tr>
		<tr>
			<td align='center' valign='top' bgcolor='#F0F0F0' style='background-color: #F0F0F0;'>
				<!-- 600px container (white background) -->
				<table border='0' width='600' cellpadding='0' cellspacing='0' class='container'>
					<tr>
						<td class='container-padding content' align='left'>
							<br>
							<div class='title'>
								<h4>Inquiry Monitoring as of ".date("j M Y")."</h4>
							</div>
							<div class='body-text'>
								<font color='#880000'><h3>The following are the beginning balance of each Accessing Entity:</h3></font>
							</div>
							<!--/ end example -->
							<div class='hr' style='clear: both;'>&nbsp;</div>
							".$inq."
							<br>
						</td>
					</tr>
					<tr>
						<td class='container-padding footer-text' align='left'>
							<br/><br/><strong>CIC Portal</strong><br/><br/>
						</td>
					</tr>
				</table><!--/600px container 1d3294-->
			</td>
		</tr>
	</table><!--/100% background wrapper-->
</body>
</html>";
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
$mail->Password   = "d0ubl3M!nt@092019";
$mail->addReplyTo("no_reply@creditinfo.gov.ph", "Credit Information System Mailer");
$mail->setFrom("no_reply@creditinfo.gov.ph", "CIC Billing System");
// $mail->addAddress("doris.kalalo@creditinfo.gov.ph", "Doris Kalalo");
// $mail->addAddress("sien.cabasis@creditinfo.gov.ph", "Maria Siena Cabasis");
// $mail->addAddress("alyssa.marie.vicente@creditinfo.gov.ph", "Alyssa Marie Vicente");
#	$mail->addCC("jaime.garchitorena@creditinfo.gov.ph", "Jaime Garchitorena");
// $mail->addCC("bon.bautista@creditinfo.gov.ph", "Bon Bautista");
// $mail->addCC("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
$mail->addAddress("karl.guevarra@creditinfo.gov.ph", "Karl Jorden Guevarra");
$mail->Subject  = "CIS Monthly Reset Report";
$mail->WordWrap = 78;
$mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
if($mail->send()){
  $mailsent1 = 1;
  echo "sent";
}
?>

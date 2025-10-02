<?php
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);
require_once'../PHPMailer/PHPMailerAutoload.php';
require_once'../config.php';

$ctrlno = $controlno_se;

$sql = $dbh4->query("SELECT  fld_ctrlno,
                    AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS name,
                    AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS provcode,
                    AES_DECRYPT(fld_ip, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS ip, fld_mnemonics
                  FROM tbentities
                  WHERE fld_ctrlno = '$ctrlno'");
$res = $sql->fetch_array();

$company_name = $res['name'];
$provider_code = $res['provcode'];
$connectivity = $res['ip'];
$mnemonic = $res['fld_mnemonics'];

if ($connectivity == "will avail 2fa") {
	$connectivity_type = "";
} else {
	$connectivity_type = $connectivity;
}

$mail          = new PHPMailer(true);
$mail->CharSet = 'utf-8';
$mail->isSMTP();
$mail->SMTPDebug  = 0;
$mail->Host       = "smtp.gmail.com";
$mail->Port       = "587";
$mail->SMTPSecure = "tls";
$mail->SMTPAuth   = true;
$mail->Username   = "cicportal@creditinfo.gov.ph";
$mail->Password   = "DEVSCIC.701@RA3019nXh";
$mail->setFrom('cicportal@creditinfo.gov.ph',"Credit Information Corporation");
$mail->addAddress("compliance.monitoring@creditinfo.gov.ph", "Compliance");
$mail->addAddress("SEP-updates-internal@creditinfo.gov.ph", "SEP-in-Charge");
$mail->addAddress("NOC-internal@creditinfo.gov.ph", "NOC Internal (including InfoSec)");
$mail->addAddress("legal@creditinfo.gov.ph", "Legal");
$mail->addAddress("dispute-internal@creditinfo.gov.ph", "Dispute");
$mail->addAddress("business.dev@creditinfo.gov.ph", "BDC");
// $mail->addCc("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
$mail->addCc("karl.guevarra@creditinfo.gov.ph", "Karl Jorden Guevarra");
$mail->Subject  = "Circular Letter No. ".$circular_no." (".$circular_subject.")";
$message = "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
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
    <p>Hi <b>Team</b>,<br/></p>
                                    
    <p align='justify'>
    Please find the attached BSP Circular Letter regarding the above subject matter.
    </p>
    <p>This was taken from BSP's website</p>
   <br/>
   <p><b>Thank you</b></p>
   <p>CIC Portal</p>
</body>
</html>";
// echo $message;

$mail->msgHTML($message);
$mail->addAttachment($uploadfile, $company_name."_Circular Letter");


if ($mail->send()) {
}

?>
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
                    AES_DECRYPT(fld_ip, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS ip
                  FROM tbentities
                  WHERE fld_ctrlno = '$ctrlno'");
$res = $sql->fetch_array();

$company_name = $res['name'];
$provider_code = $res['provcode'];
$connectivity = $res['ip'];

if ($connectivity == "will avail 2fa") {
	$connectivity_type = "<b>Connectivity Type</b>: Two-Factor Authentication";
} else {
	$connectivity_type = "<b>Connectivity Type</b>: IP Address";
}

$get_batch_operators = $dbh4->query("SELECT fld_oid, AES_DECRYPT(fld_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname, AES_DECRYPT(fld_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname, AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname, AES_DECRYPT(fld_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email FROM tboperators WHERE fld_ctrlno = '".$res['fld_ctrlno']."' and fld_batch = 1 and fld_delete = 0");

if (mysqli_num_rows($get_batch_operators) == 0) {

}else {

	$batch_tbody .= "<p><b>Batch Operators</b></p>";
	$batch_tbody .= "<table class='table table-bordered' bgcolor='#494949' cellspacing='1' cellpadding='5'>
		        		<thead>
		        			<tr>
		        				<th width='5%' bgcolor='#bbbbbb'>#</th>
		        				<th width='30%' bgcolor='#bbbbbb' style='text-align: left;'>Name</th>
		        				<th width='30%' bgcolor='#bbbbbb' style='text-align: left;'>Email</th>
		        				<th width='15%' bgcolor='#bbbbbb'><center>Username</center></th>
		        			</tr>
		        		</thead>
		        		<tbody>";

	while ($gbo=$get_batch_operators->fetch_array()) {
		$b1++;
		$get_user_details = $dbh->query("SELECT fld_username FROM tbnocusers WHERE fld_email = '".$gbo['email']."' and fld_status = 1 and fld_ctrlno = '".$res['fld_ctrlno']."'");
		$gud=$get_user_details->fetch_array();
		$batch_tbody .= "<tr><td bgcolor='#ffffff'><center>".$b1."</center></td><td bgcolor='#ffffff'>".$gbo['fname']." ".$gbo['mname']." ".$gbo['lname']."</td><td bgcolor='#ffffff'>".$gbo['email']."</td><td bgcolor='#ffffff'><center>".$gud['fld_username']."</center></td></tr>";
	}

	$batch_tbody .= "</tbody></table>";

}

$get_web_operators = $dbh4->query("SELECT fld_oid, AES_DECRYPT(fld_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname, AES_DECRYPT(fld_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname, AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname, AES_DECRYPT(fld_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email FROM tboperators WHERE fld_ctrlno = '".$res['fld_ctrlno']."' and fld_web = 1 and fld_delete = 0");

if (mysqli_num_rows($get_web_operators) == 0) {

}else {
	$web_tbody .= "<p><b>Web Operators</b></p>";
	$web_tbody .= "<table class='table table-bordered' bgcolor='#494949' cellspacing='1' cellpadding='5'>
	        		<thead>
	        			<tr>
	        				<th width='5%' bgcolor='#bbbbbb'>#</th>
	        				<th width='30%' bgcolor='#bbbbbb' style='text-align: left;'>Name</th>
	        				<th width='30%' bgcolor='#bbbbbb' style='text-align: left;'>Email</th>
	        				<th width='15%' bgcolor='#bbbbbb'><center>Username</center></th>
	        			</tr>
	        		</thead>
	        		<tbody>";

	while ($gwo=$get_web_operators->fetch_array()) {
		$get_user_details = $dbh->query("SELECT fld_username FROM tbnocusers WHERE fld_email = '".$gwo['email']."' and fld_status = 1 and fld_ctrlno = '".$res['fld_ctrlno']."'");
		$gud=$get_user_details->fetch_array();
		$w1++;
		$web_tbody .= "<tr><td bgcolor='#ffffff'><center>".$w1."</center></td><td bgcolor='#ffffff'>".$gwo['fname']." ".$gwo['mname']." ".$gwo['lname']."</td><td bgcolor='#ffffff'>".$gwo['email']."</td><td bgcolor='#ffffff'><center>".$gud['fld_username']."</center></td></tr>";
	}

	$web_tbody .= "</tbody></table>";
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
$mail->Password   = "DEVSCIC.421@RA3019$";
$mail->setFrom('cicportal@creditinfo.gov.ph',"Credit Information Corporation");
$mail->addAddress("Anthropos.delosSantos@creditinfo.gov.ph", "Anthropos delos Santos");
$mail->addCc("karl.guevarra@creditinfo.gov.ph", "Karl Jorden Guevarra");
$mail->addCc("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
$mail->Subject  = "[CIC] ".$res['name']." Closing of Company";
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
	<!-- 100% background wrapper (grey background) -->
	<table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' bgcolor='#F0F0F0'>
		<tr>
			<td align='center' valign='top' bgcolor='#F0F0F0' style='background-color: #F0F0F0;'>
				<!-- 800px container (white background) -->
				<table border='0' width='800' cellpadding='0' cellspacing='0' class='container'>
					<tr>
						<td class='container-padding content' align='left'>
							<p>Dear <b>NOC Team</b>,<br/></p>
                                    
                                    <p align='justify'>
                                    In reference to <b>BSP Circular Letter No. ".$circular_no."</b>, the Data Submission Team tagged <b>".$company_name." - ".$provider_code."</b> as closed. Details registered to CIC are as follows:
                                    </p>
                                    <p>
                                    	<label>".$connectivity_type."</label>
                                    	<br/>
                                    	".$batch_tbody."	
                                    	<br/>
                                    	".$web_tbody."
                                    </p>

                                   <br/>
                                   <p><b>Thank you</b></p>
                                   <p>CIC Portal</p>
						</td>
					</tr>
				</table><!--/600px container 1d3294-->
			</td>
		</tr>
	</table><!--/100% background wrapper-->
</body>
</html>";
// echo $message;

$mail->msgHTML($message);



if ($mail->send()) {
}

?>
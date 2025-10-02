<?php
	include("config.php");
	
	$sae = array("CRF" => "CRIF", "STU" => "TransUnion", "COM" => "CompuScan", "CIB" => "CIBI");
	
	$det = explode("-", date("Y-m"));
	
	$inq = "<table class='table table-bordered'><tr><th width = '5%'></th><th width = '15%'><center>Provider Code</center></th>"
		."<th width = '40%'><center>Company Name</center></th><th width = '10%'><center>Inquiries</center></th><th width = '10%'><center>Limit</center></th></tr>";

	$sql=$dbh->query("SELECT substr(fld_usercode, 6, 3) AS cd, fld_provcode, sum(fld_inqcount) AS inq, fld_errorcode FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' AND MONTH(fld_inqdate) = '".$det[1]."' AND (fld_inqresult <= '1' OR fld_errorcode LIKE '%1-100%') GROUP BY fld_provcode, fld_usercode, fld_errorcode");
	while($e1=$sql->fetch_array()){
		$sql1=$dbh->query("SELECT fld_code, fld_name, fld_limit FROM tbfininst WHERE fld_code = '".$e1['fld_provcode']."'");
		$p1=$sql1->fetch_array();
		if(!$pvc1){
			$pvc1 = $e1['fld_provcode'];
			$pname1 = $p1['fld_name'];
			$totalinq1 = $totalinq + $e1['inq'];
			$plimit1 = $p1['fld_limit'];
		}elseif(($pvc1 == $e1['fld_provcode']) && (!array_key_exists($e1['cd'], $sae))){
			$totalinq1 = $totalinq1 + $e1['inq'];
		}elseif($pvc1 <> $e1['fld_provcode'] || (array_key_exists($e1['cd'], $sae))){
			if($totalinq1 > $plimit1){
				$cnt1++;
				$inq .= "<tr><td><center>".$cnt1."</center></td><td><center>".$pvc1."</center></td><td>".$sae_name1.$pname1."</td><td><center>".number_format($totalinq1, 0, ".", ",")."</center></td><td><center>".$plimit1."</center></td></tr>";
			}
			$totalinq1 = 0;
			if(array_key_exists($e1['cd'], $sae)){
				$sae_name1 = $sae[$e1['cd']]." - ";
				$pvc1 = $sae[$e1['cd']];
			}else{
				$sae_name1 = "";
				$pvc1 = $e1['fld_provcode'];
			}
			$pname1 = $p1['fld_name'];
			$plimit1 = $p1['fld_limit'];
			$totalinq1 = $totalinq1 + $e1['inq'];
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
	<table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' bgcolor='#F0F0F0'>
		<tr>
			<td align='center' valign='top' bgcolor='#F0F0F0' style='background-color: #F0F0F0;'>
				<br>
				<!-- 600px container (white background) -->
				<table border='0' width='600' cellpadding='0' cellspacing='0' class='container'>
					<tr>
						<td class='container-padding header' align='left'>Email Notification : Billing</td>
					</tr>
					<tr>
						<td class='container-padding content' align='left'>
							<br>
							<div class='title'>Inquiries exceeding limits</div>
							<br>
							<div class='body-text'>
								The following are Accessing Entities exceeding their limits on their inquiries.
								<br><br>
							</div>
							<!--/ end example -->
							<div class='hr' style='clear: both;'>&nbsp;</div>
							<br>
							".$inq."
							<br>
						</td>
					</tr>
					<tr>
						<td class='container-padding footer-text' align='left'>
							<br><br>
							Sample Footer text: &copy; 2015 Acme, Inc.
							<br><br>
							You are receiving this email because you opted in on our website. Update your <a href='#'>email preferences</a> or <a href='#'>unsubscribe</a>.
							<br><br>
							<strong>Acme, Inc.</strong><br>
							<span class='ios-footer'>
								123 Main St.<br>
								Springfield, MA 12345<br>
							</span>
							<a href='http://www.acme-inc.com'>www.acme-inc.com</a><br>
							<br><br>
						</td>
					</tr>
				</table><!--/600px container -->
			</td>
		</tr>
	</table><!--/100% background wrapper-->
</body>
</html>";
?>
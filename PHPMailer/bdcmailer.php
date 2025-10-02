<?php
#error_reporting(E_ALL);
#ini_set('display_errors', 1);

	
	
	
	$sae = array("CRF" => "CRIF", "STU" => "TransUnion", "COM" => "CompuScan", "CIB" => "CIBI");
	
	$det = explode("-", date("Y-m"));
	$cnt1 = 0;
	$inq = "<table class='table table-bordered' bgcolor='#494949' cellspacing='1' cellpadding='5'><tr><th width = '5%' bgcolor='#bbbbbb'></th><th width = '15%' bgcolor='#bbbbbb'><center>Provider Code</center></th>"
		."<th width = '40%' bgcolor='#bbbbbb'><center>Company Name</center></th><th width = '10%' bgcolor='#bbbbbb'><center>Inquiries</center></th><th width = '10%' bgcolor='#bbbbbb'><center>Limit</center></th></tr>";

		$sql1=$dbh->query("SELECT fld_code, fld_name, fld_limit FROM tbfininst WHERE fld_code = '".$e1['fld_provcode']."'");
		$p1=$sql1->fetch_array();
		$rows = count($sql);
		if($rows > 1){
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
					$inq .= "<tr><td bgcolor='#ffffff'><center>".$cnt1."</center></td><td bgcolor='#ffffff'><center>".$pvc1."</center></td><td bgcolor='#ffffff'>".$sae_name1.$pname1."</td><td bgcolor='#ffffff'><center>"
						.number_format($totalinq1, 0, ".", ",")."</center></td><td bgcolor='#ffffff'><center>".$plimit1."</center></td></tr>";
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
		}else{
			$pvc1 = $e1['fld_provcode'];
			$pname1 = $p1['fld_name'];
			$totalinq1 = $e1['inq'];
			$plimit1 = $p1['fld_limit'];
			$cnt1++;
			$inq .= "<tr><td bgcolor='#ffffff'><center>".$cnt1."</center></td><td bgcolor='#ffffff'><center>".$pvc1."</center></td><td bgcolor='#ffffff'>".$pname1."</td><td bgcolor='#ffffff'><center>"
				.number_format($totalinq1, 0, ".", ",")."</center></td><td bgcolor='#ffffff'><center>".$plimit1."</center></td></tr>";
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
				<!-- 600px container (white background) -->
				<table border='0' width='600' cellpadding='0' cellspacing='0' class='container'>
					<tr>
						<td class='container-padding content' align='left'>
							<br>
							<div class='title'>
								<h4>Inquiry Monitoring as of ".date("j M Y")."</h4>
							</div>
							<div class='body-text'>
								<font color='#880000'><h3>The following are the Accessing Entities who exceeded their credit limits on their inquiries:</h3></font>
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
?>
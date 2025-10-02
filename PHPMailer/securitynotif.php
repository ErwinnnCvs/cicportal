<?php

	##SEIS
	
	date_default_timezone_set("Asia/Manila");
	require_once 'config.php';
	require_once 'PHPMailerAutoload.php';
	// require_once 'classes/Auth.class.php';

	//ERROR REPORTING
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);
	$credentials = $dbh->query("SELECT * FROM tbemailcredentials WHERE fld_type = 'cicportal'");
	$c=$credentials->fetch_array();
	
	// $auth = new Auth();
	
	$key = "RA3019";
	// $controlNo = "2019040003";

	$sql=$dbh4->query("SELECT COUNT(fld_ctrlno) as no_pending FROM tbentities WHERE fld_status = 0 and fld_ip <> AES_ENCRYPT('will avail 2fa', md5(CONCAT(fld_ctrlno, 'RA3019'))) and fld_process_status = 2 LIMIT 1");
	
	if ($r=$sql->fetch_array()) {
		$mail = new PHPMailer(true);
		$mail->CharSet = 'utf-8';
		ini_set('default_charset', 'UTF-8');
		$mail->SingleTo = true;

			
		$mail->SMTPDebug  = 2;
		$mail->isSMTP();
		$mail->Host       = "smtp.gmail.com";
		$mail->SMTPAuth   = true;
		$mail->Username   = $c['fld_email'];
		$mail->Password   = $c['fld_password'];
		$mail->SMTPSecure = "tls";
		$mail->Port       = "587";

		$mail->isHTML(true);
		$mail->setFrom("cicportal@creditinfo.gov.ph", "CIC Portal");
		$mail->addAddress('herby.taino@creditinfo.gov.ph', 'Herby Jose Taino');
		$mail->Subject  = "IP Assesment - Number of Pending";
		$mail->WordWrap = 78;
		$message = '
		<div id="all">
		   <div style="display:block;width:100%;max-width:650px;margin:0 auto" >
		      <table cellpadding="0" cellspacing="0" border="0" width="100%">
		         <tr style="background-color: #f5f5f5;">
		            <td style="border-top: 1px solid rgb(202,201,200);border-left: 1px solid rgb(202,201,200);border-right: 1px solid rgb(202,201,200); border-radius: 15px 50px 10px 10px;"><br></td>
		         </tr>
		         <tr style="background-color:#f5f5f5">
		            <td align="center" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);padding-bottom: 15px" class="m_-3480498238929770810gmail-m_3586475887212474448null-pad-logo"><a href="http://www.creditinfo.gov.ph/" style="display:block;margin-bottom:10px" target="_blank"><img class="m_-3480498238929770810gmail-m_3586475887212474448head_1_logo-833 CToWUd" src="www.creditinfo.gov.ph/img/CICLogo.png" width="100%" style="background-color: #f5f5f5" alt="" style="max-width:645px;display:block"></a></td>
		         </tr>
		         <tr>
		            <td>
		               <div>
		                  <table bgcolor="#ffffff" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);background-color: #f5f5f5" >
		                     <tbody>
		                        <tr height="20px" rowspan="1" colspan="3">
		                           <td style="max-width: 645px; word-wrap: break-word;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:black;line-height:1.25;min-width:300px;padding:10px 30px 15px;" id="editTR">
		                                      
		                                      Dear Security User,<br/><br/>
							              	  <p align="justify">You have <b>'.$r['no_pending'].'</b> pending in IP Asessment of CIC Portal.</p>
							              	  <p align="justify">Please check if number is greater than <b>0</b>. Login to - <a href="http://10.250.100.165/cicportal">http://10.250.100.165/cicportal</a></p>
							              	  <br/><br/><br/>
							              	  <p>Thank you and Have a nice day!</p>
		                           </td>
		                        </tr>
		                     </tbody>
		                  </table>
		               </div>
		            </td>
		         </tr>
		         <tr>
		            <table width="100%" bgcolor="#cac9c8" style="background-color:#cac9c8" border="0" cellpadding="0" cellspacing="0" align="center">
		                 <tbody>
		                     <tr>
		                         <td width="50%" valign="top" dir="ltr" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448full m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-pad" style="padding:0px 30px">
		                             <table align="left" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
		                                 <tbody>
		                                     <tr>
		                                         <td valign="top" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-padding" style="padding-right:10px;padding-left:0px">
		                                             <p style="margin:25px 0px 15px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:16px;color:rgb(20,72,133);text-align:left;line-height:16px;font-weight:bold">Follow Us:</p>
		                                             <p style="margin:3px 0px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:rgb(130,128,128);text-align:left;font-weight:normal"><a href="https://facebook.com/creditinfo.gov.ph"><img src="www.creditinfo.gov.ph/img/fb.png" width="24" style="display:inline-block" class="m_-8036216849319893849m_-6971811544154621716CToWUd m_-8036216849319893849CToWUd CToWUd"><span style="vertical-align:top;display:inline-block;line-height:24px;margin-left:5px;color:rgb(187,115,36)"><span>/creditinfo.gov.ph</span></span></a></p>
		                                         </td>
		                                     </tr>
		                                 </tbody>
		                             </table>
		                         </td>
		                         <td width="50%" valign="top" dir="ltr" style="padding:0px 30px">
		                             <table align="left" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
		                                 <tbody>
		                                     <tr>
		                                         <td valign="top" class="m_-8036216849319893849m_-6971811544154621716m_3167257287174861847gmail-m_3586475887212474448mobile-padding" style="padding-right:10px;padding-left:0px">
		                                             <p style="margin:25px 0px 15px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:16px;color:rgb(20,72,133);text-align:left;line-height:16px;font-weight:bold">Visit us:</p>
		                                             <p style="margin:3px 0px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;color:rgb(130,128,128);text-align:left;font-weight:normal"><a href="http://www.creditinfo.gov.ph/"><img src="www.creditinfo.gov.ph/img/web.png" width="24" style="display:inline-block" class="m_-8036216849319893849m_-6971811544154621716CToWUd m_-8036216849319893849CToWUd CToWUd"><span style="vertical-align:top;display:inline-block;line-height:24px;margin-left:5px;color:rgb(187,115,36)"><span>http://www.creditinfo.gov.ph</span></span></a></p>
		                                         </td>
		                                     </tr>
		                                 </tbody>
		                             </table>
		                         </td>
		                     </tr>
		                 </tbody>
		             </table>
		             <table width="100%" cellpadding="0" style="border-radius:  0px 0px 50px 50px ; background-color:#cac9c8" cellspacing="0" >
		                 <tbody>
		                     <tr>
		                         &nbsp;
		                     </tr>
		                 </tbody>
		             </table>
		         </tr>
		         <tr style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:10px;color:#666666;line-height:18px;padding-bottom:10px">
		            <footer><small>© '.date('Y').' Credit Information Corporation. <a href="https://www.google.com/maps/place/Credit+Information+Corporation/@14.5560001,121.0148883,17z/data=!3m1!4b1!4m5!3m4!1s0x3397c90e63386907:0x2132dc0efdf6cf6e!8m2!3d14.5560001!4d121.017077"  target="_blank">  6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City. </a></small></footer>
		         </tr>
		      </table>
		      <tr height="16px"></tr>
		   </div>
		</div>';
		$mail->msgHTML($message);
		$msg = '';
		$mail->send();

		// $dbh2->query("UPDATE tbentities SET fld_process_status = 3 WHERE fld_ctrlno = '".$r['fld_ctrlno']."'");
	} else

	{

	}
	
?>

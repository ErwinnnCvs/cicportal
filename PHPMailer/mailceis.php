<?php

	##SEIS

	date_default_timezone_set("Asia/Manila");
	// require_once('config.php');
	
define("DB_SERVER", "10.250.106.33");
define("DB_USER", "myuser1");
define("DB_PASS", "mypassword");
define("DB_NAME", "cicportal");

//Database table information 
define("USER_TABLE", "tbusers");
define("LOGGED_IN_TABLE", "tbLoggedInUsers");

//Fully Qualified Domain Name
define("SITE_HTTP", "");

//Return email address
define("FROM_EMAIL", "");

$dbh = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
# CONNECTION TO DASHBOARD SERVER
#$dbh1 = new mysqli("10.250.106.15", "complianceuser", "dgzr2EMXsqss5cL6", "cicdms");

#$dbh2 = new mysqli("10.250.111.80", "portal98", "Bl@ckR4bb1t", "dispute");

$dbh3 = new mysqli("10.250.111.80", "email98", "d4n13lL0p3z", "cicportal");

$dbh4 = new mysqli("10.250.111.80", "potalseis", "cR3d1tInf0rm4t!on", "cicseis");
	require_once 'PHPMailerAutoload.php';
	// require_once 'classes/Auth.class.php';

	//ERROR REPORTING
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	// $auth = new Auth();

	$key = "RA3019";
	// $controlNo = "2019040003";
	$credentials = $dbh->query("SELECT * FROM tbemailcredentials WHERE fld_type = 'cicportal'");
	$c=$credentials->fetch_array();

	$sql=$dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_tinno, md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_tinno, AES_DECRYPT(fld_compregno,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_compregno, fld_compregtype, AES_DECRYPT(fld_name,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, fld_type, AES_DECRYPT(fld_landline,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_landline, fld_zip,
	AES_DECRYPT(fld_name_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name_ar,
	AES_DECRYPT(fld_lname_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_lname_ar,
	AES_DECRYPT(fld_fname_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_fname_ar,
	AES_DECRYPT(fld_mname_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_mname_ar,
	AES_DECRYPT(fld_ip,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_ip,
	AES_DECRYPT(fld_extname_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_extname_ar,
	AES_DECRYPT(fld_position_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_position_ar,
	AES_DECRYPT(fld_contactno_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_contactno_ar,
	AES_DECRYPT(fld_landline_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_landline_ar,
	AES_DECRYPT(fld_email_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_email_ar,
	AES_DECRYPT(fld_name_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name_c1,
	AES_DECRYPT(fld_position_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_position_c1,
	AES_DECRYPT(fld_contactno_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_contactno_c1,
	AES_DECRYPT(fld_landline_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_landline_c1,
	AES_DECRYPT(fld_email_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_email_c1,
	AES_DECRYPT(fld_name_c2,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name_c2,
	AES_DECRYPT(fld_position_c2,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_position_c2,
	AES_DECRYPT(fld_contactno_c2,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_contactno_c2,
	AES_DECRYPT(fld_landline_c2,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_landline_c2,
	AES_DECRYPT(fld_email_c2,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_email_c2,
	AES_DECRYPT(fld_head_name,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_head_name,
	AES_DECRYPT(fld_head_position,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_head_position,
	AES_DECRYPT(fld_head_email,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_head_email,
	fld_disp_status, fld_status, fld_process_status
	FROM tbentities WHERE fld_disp_status = 2 and fld_status = 1 and fld_process_status = 2 LIMIT 1");

	if ($r=$sql->fetch_array()) {
		$mail = new PHPMailer(true);
		$mail->CharSet = 'utf-8';
		ini_set('default_charset', 'UTF-8');
		$mail->SingleTo = true;


		$mail->SMTPDebug  = 2;
		$mail->isSMTP();
		$mail->Host       = "smtp.gmail.com";
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";
		$mail->Port       = "587";
		$mail->Username   = $c['fld_email'];
		$mail->Password   = $c['fld_password'];

		$mail->isHTML(true);
		$mail->setFrom("cicportal@creditinfo.gov.ph", "CIC Data Submission");
		$mail->addAddress($r['fld_email_ar'], $r['fld_fname_ar']);
		$mail->addBCC("compliance.monitoring-internal@creditinfo.gov.ph", "Compliance Monitoring Internal");
		$mail->addBcc("Jovis.Batongbakal@creditinfo.gov.ph", "Jell Batongbakal");
		$mail->addBcc("cis-support-internal@creditinfo.gov.ph");
        $mail->addBcc("trixia.basa@creditinfo.gov.ph", "Trixia Gueverra");
        $mail->addBcc("mj.tinagan@creditinfo.gov.ph", "MJ Tinagan");
        $mail->addBcc("rex.berdandino@creditinfo.gov.ph", "Rex Berdandino");
        $mail->addBcc("jho.mercado@creditinfo.gov.ph", "Jho Mercado");
        $mail->addBcc("rowena.castro@creditinfo.gov.ph", "Rowena Castro");
        $mail->addBcc("datasubmission-internal@creditinfo.gov.ph");
        $mail->addBcc("jacquiline.cardino@creditinfo.gov.ph", "Jacquiline Cardino");

        
		$mail->Subject  = "Submitting Entity Information Sheet..";
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
		                                      <b style="text-transform: uppercase">'.$r['fld_fname_ar'].' '.$r['fld_lname_ar'].'</b><br/>'.
								              $r['fld_position_ar'].'<br/>'.
								              $r['fld_name'].'<br/><br/>'.
							              	  'Dear '.$r['fld_position_ar'].' '.$r['fld_fname_ar'].': <br/><br/>
							              	  You are almost done with your CEIS registration process.<br/>
							              	  <br/>
							              	  Attached herewith are the encrypted documents from the CEIS that you filled out, namely:
							              	  <ol>
							              	   <li>SEIS Form</li>
							              	   <li>Name of Operators</li>
							              	   <li>Certificate of Total Number of Loan Accounts</li>
							              	   <li>Secretary Certificate</li>
							              	  </ol><br/>
							              	  In order for us to conclude your registration, please download the files and open them using your
							              	  entity\'s <b><u>provider code</u></b> as password.<br/><br/>

							              	  Once signed, please courier the original copies to the following address:<br/><br/>

																Also, please include the Certified True Copies of the following documents:<br/>
																<ol>
																	<li>Articles of Incorporation / Cooperation</li>
																	<li>Certificate of Incorporation / Registration</li>
																</ol>
																<br/>

							              	  <center style="text-transform: uppercase">Credit Information Corporation<br/></center>
							              	  <center>6F Exchange Corner Building<br/></center>
							              	  <center>cor Bolanos and Esteban Sts.<br/></center>
							              	  <center>Legaspi Village, Makati City<br/><br/></center>
							              	  <center>Re: <u>CEIS Registration Documents</u></center><br/><br/><br/>
							              	  For clarifications, please send them to <u>cichelpdesk@creditinfo.gov.ph</u><br/><br/>
							              	  Thank you!
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
		// $mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
		$mail->addAttachment('../download/SEIS'.$r['fld_ctrlno'].'.pdf','SEIS Form.pdf');  // optional name
		$mail->addAttachment('../download/SEISOPERATORS'.$r['fld_ctrlno'].'.pdf','Name of Operators.pdf');  // optional name
		$mail->addAttachment('../download/SEISLOANACCOUNTS'.$r['fld_ctrlno'].'.pdf','Certificate of Total Number of Loan Accounts.pdf');
		if ($r['fld_compregtype'] == "SEC") {
			$mail->addAttachment('../attachment/seccert_corprevised.pdf','Secretary Certificate.pdf');
		} elseif ($r['fld_compregtype'] == "CDA") {
			$mail->addAttachment('../attachment/seccert_cooprevised.pdf','Secretary Certificate.pdf');
		}

		if ($mail->send()) {
			$timestamp = date("Y-m-d H:i:s");
			$dbh4->query("UPDATE tbentities SET fld_process_status = 3, fld_sent_attachments_ts = '".$timestamp."' WHERE fld_ctrlno = '".$r['fld_ctrlno']."'");
		}
	}

?>

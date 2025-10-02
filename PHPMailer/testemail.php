<?php
	require_once 'PHPMailerAutoload.php';
  $key = 'RA3019';
	require_once 'config.php';
	$credentials = $dbh->query("SELECT * FROM tbemailcredentials WHERE fld_type = 'cicportal'");
	$c=$credentials->fetch_array();

  $ctrlno = '2019070041';

  $sql=$dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_tinno, md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_tinno, AES_DECRYPT(fld_compregno,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_compregno, fld_compregtype, AES_DECRYPT(fld_name,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, fld_type, AES_DECRYPT(fld_landline,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_landline, fld_zip,
  	AES_DECRYPT(fld_lname_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_lname_ar,
  	AES_DECRYPT(fld_fname_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_fname_ar,
  	AES_DECRYPT(fld_mname_ar,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_mname_ar,
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
  	AES_DECRYPT(fld_head_position,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_head_position,
  	AES_DECRYPT(fld_head_email,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_head_email,
  	fld_disp_status, fld_status, fld_process_status
  	FROM tbentities WHERE fld_ctrlno = '".$ctrlno."'");

  $r=$sql->fetch_array();

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
                             <td style="text-align: justify;max-width: 645px; word-wrap: break-word;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:black;line-height:1.25;min-width:300px;padding:10px 30px 15px;" id="editTR">
                                        <b style="text-transform: uppercase">'.$r['fld_fname_ar'].' '.$r['fld_lname_ar'].'</b><br/>'.
                            $r['fld_position_ar'].'<br/>'.
                            $r['fld_name'].'<br/><br/>'.
                              'Dear '.$r['fld_position_ar'].' '.$r['fld_fname_ar'].': <br/><br/>

                              You are almost done with your Covered Entity Information Sheet (CEIS) registration process.<br/>
                              <br/>
                              Your entity\'s Billing and Collection Point Person (BCPP) has already confirmed his/her designation. In order to complete your registration, please download and sign the following encrypted documents using your entity\'s <b>Provider Code</b> as password:
                              <br/>
                              <ol>
                               <li>Accessing Entity Information Sheet (AEIS) form</li>
                               <li>Name of Operators</li>
                               <li>Signed and notarized Memorandum of Agreement (MOA) with Board Resolution or Secretary\'s Certificate. The MOA must include the following annexes:</li>
                                    <ol type="a">
                                      <li>Annex "A" - General Terms and Conditions for Access</li>
                                      <li>Annex "B" - Billing and Collection</li>
                                      <li>Annex "C" - Security Requirements</li>
                                      <li>Annex "D" - Secretary\'s Certificate</li>
                                    </ol>
                              </ol>
                              <br/>
                              Once accomplished and signed, please courier the original copies of the above documents to:<br/><br/>

                              <center style="text-transform: uppercase">Credit Information Corporation<br/></center>
                              <center>6F Exchange Corner Building<br/></center>
                              <center>cor Bolanos and Esteban Sts.<br/></center>
                              <center>Legaspi Village, Makati City<br/><br/></center>
                              <center>Re: <u>CEIS Registration Documents</u></center><br/><br/>
                              For clarifications, please send them to <u>cichelpdesk@creditinfo.gov.ph</u><br/><br/>
                              Thank you!<br/><br/>
                              <b>Credit Information Corporation</b>
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

	## EMAIL SCRIPT FOR MONITORING
	$mail = new PHPMailer(true);
	$mail->CharSet = 'utf-8';
	$mail->isSMTP();
	$mail->SMTPDebug  = 0;
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = "587";
	$mail->SMTPSecure = "tls";
	$mail->SMTPAuth   = true;
	$mail->Username   = $c['fld_email'];
	$mail->Password   = $c['fld_password'];
	$mail->addReplyTo("no_reply@creditinfo.gov.ph", "Credit Information System Mailer");
	$mail->setFrom("no_reply@creditinfo.gov.ph", "CIS Mailer");
$mail->addAddress("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
$mail->addAddress("karl.guevarra@creditinfo.gov.ph", "Karl Guevarra");
$mail->addAddress("jordan.vinluan@creditinfo.gov.ph", "Jordan Vinluan");
#	$mail->addAddress("sien.cabasis@creditinfo.gov.ph", "Maria Siena Cabasis");
#	$mail->addAddress("alyssa.marie.vicente@creditinfo.gov.ph", "Alyssa Marie Vicente");
#	$mail->addCC("jaime.garchitorena@creditinfo.gov.ph", "Jaime Garchitorena");
#	$mail->addCC("bon.bautista@creditinfo.gov.ph", "Bon Bautista");
	// $mail->addCC("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
	// $mail->addCC("karl.guevarra@creditinfo.gov.ph", "Karl Guevarra");
	// $mail->addCC("jordan.vinluan@creditinfo.gov.ph", "Jordan Vinluan");
	$mail->Subject  = "CIS Inquiries Daily Report";
	$mail->WordWrap = 78;
	$mail->msgHTML($message, dirname(__FILE__), true); //Create message bodies and embed images
	if($mail->send()){
		$mailsent1 = 1;
		echo "sent";
	}
?>

<?php
include("../config.php");

require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

//$datetoday = date("Y-m-d H:i:s");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = "true";
$mail->SMTPSecure = "tls";
$mail->Port = "587";
$mail->Username = EMAIL_USER;
$mail->Password = EMAIL_PASS;
$mail->Subject = "Required Attendance in Technical Training Sessions (" . $ctrl_no . ")";
$mail->setFrom("ta.cicportal.0526@gmail.com", "CIC Mailer");
$mail->isHTML(true);

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
                                <center><b style="text-transform: uppercase"><u> MANDATED TECHNICAL TRAINING SESSIONS </u></b></center><br/>'.
                                    '<br/>
                                    <i>Pursuant to CIC Circular No. 2023-04 entitled Implementing Guidelines for the Compliance of all Submitting Entities under the Credit Information Systems Act </i> (“Guidelines”),
                                    all Submitting Entities (“SE”) are mandated to register and submit all of their borrowers\' basic credit data in their possession to the CIC, regardless of the volume of the said data, the size, or capacity of the SE. <br/><br/>
                                        
                                    Furthermore, under Rule II, Section 1 of the Guidelines, an SE is non-compliant when it fails to complete the onboarding process and adhere to the mandatory timelines prescribed, 
                                    whether intentional or not, as follows:<br/>
                                    <ol type="a">
                                        <li>Stage 1 - Fifteen (15) working days</li>
                                        <li>Stage 2 – Thirty (30) working days</li>
                                        <li>Stage 3 - Fifteen (15) working days</li>
                                    </ol><br/>

                                    On 30 April 2024, CIC Circular No. 2024-01 entitled <i>Further Extension of the period to Comply with the Initial Compliance Stages in Accordance with CIC Circular No. 2023-04 or the "Implementing Guidelines for the Compliance 
                                    of All Submitting Entities under the Credit Information Act"</i> was signed and promulgated giving all SE a refresh period to comply with their initial compliance. <br/><br/>

                                    Notwithstanding the given refresh period, you continuously disregard your compliance obligations or failed to complete the same within the prescribed period. <br/><br/>

                                    <b>IN VIEW OF YOUR NON-COMPLIANCE, YOU ARE HEREBY ORDERED TO ATTEND TECHNICAL TRAINING SESSIONS IN THE SCHEDULE PROVIDED BY THE INITIAL COMPLIANCE TEAM. </b><br/><br/>
                                        
                                    Kindly visit the Covered Entity (CE) Portal and go to the Microsession Module to view the available dates and times for technical training sessions (for Stages 2 and 3) and to place your preferred schedule. 
                                    In scheduling, please note that a minimum of two (2) persons shall attend from your organization. <br/><br/>

                                    <b>FAILURE TO ATTEND THE SCHEDULED TECHNICAL TRAINING SESSIONS SHALL BE DEALT MORE SEVERELY AND SHALL BE PRESUMED TO BE WILLFUL DEFIANCE ON YOUR PART. </b><br/><br/>
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

$mail->addAddress("princess.bullos@creditinfo.gov.ph","Princess");
//$mail->addAddress($cs_tb['fld_email_ar'], $cs_tb['fld_fname_ar']);
//$mail->addCC("annapatricia.teodoro@creditinfo.gov.ph", "Pat");
//$mail->addAddress("jitsukki@gmail.com","Patrice");

if ($mail->send()) {
    $msg = "Successfully Updated and Email Sent!";
    $msgclr = "success";
}
else {
    $msg = "email not sent";
    $msgclr = "danger";
}

$mail->smtpClose();


?>
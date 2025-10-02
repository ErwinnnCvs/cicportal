<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
require_once("config.php");

//Include required PHPMailer file
 require 'includes/PHPMailer.php';
 require 'includes/SMTP.php';
 require 'includes/Exception.php';


//Define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Create instance of PHPMailer
$mail = new PHPMailer();

//Set mailer to use SMTP
$mail->isSMTP();

//Define SMTP Host
$mail->Host = 'smtp.gmail.com';

//Enable SMTP Authentication
$mail->SMTPAuth = "true";

//Set type of encryption (ssl/tls)
$mail->SMTPSecure = "tls";

//Set port to connect SMTP
$mail->Port = "587";

//Set Gmail username
$mail->Username = "cicportal@creditinfo.gov.ph";

//Set Gmail password
$mail->Password =  "DEVSCIC.20240609@RA3019!";

//Set Gmail subject
$mail->Subject = "[CIC] Letter of Compliance";

//Set Sender email
$mail->setFrom("cicportal@creditinfo.gov.ph", "CIC Mailer");

//Enable HTML
$mail->isHTML(true);

$get_se = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
$gs=$get_se->fetch_array();

$se_name = "DEMON SLAYER CORPS";

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
                           <td style="text-align: justify;max-width: 645px; word-wrap: break-word;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:black;line-height:1.25;min-width:300px;padding:10px 30px 15px;" id="editTR">'.

                            'Hi <b>'.$se_name.'</b>, <br><br>
                            
                            Good day, <br/><br/>

                            YOU ARE NOT COMPLIANT, PLEASE SUBMIT THANK YOU!

                                    
                            <br/><br/>

                            Regards,<br>
                            <b>KIBUTSUJI MUZAN</b>
                            
                          
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


$mail->addAddress("karl.guevarra@creditinfo.gov.ph");

if(!$mail->send()){
    $eloc_resp = "Email was not Sent!";
}else{
	$dbh4->query("INSERT INTO tbloclogs (fld_provcode) VALUES ('".$provcode."')");
    $eloc_resp = "Email Sent!";
}

//Closing SMTP connection
$mail->smtpClose();
?>
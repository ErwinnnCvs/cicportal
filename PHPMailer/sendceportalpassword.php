<?php

$dbh = new mysqli("10.250.106.33", "myuser1", "mypassword", "cicportal");
# CONNECTION TO DASHBOARD SERVER
$dbh1 = new mysqli("10.250.111.80", "complianceuser", "s3v3nElev3n!", "cicdms1");

#$dbh2 = new mysqli("10.250.111.80", "portal98", "Bl@ckR4bb1t", "dispute");

$dbh3 = new mysqli("10.250.111.80", "email98", "d4n13lL0p3z", "cicportal");

$dbh4 = new mysqli("10.250.111.80", "potalseis", "cR3d1tInf0rm4t!on", "cicseis");


require 'PHPMailerAutoload.php';
// $controlno = '2019110040';
// $password2 = 'Lk1s$opf';
$key = 'RA3019';
$sql2=$dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_name, AES_DECRYPT(fld_lname_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_lname_c1, AES_DECRYPT(fld_fname_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_fname_c1, AES_DECRYPT(fld_mname_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_mname_c1, AES_DECRYPT(fld_extname_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_extname_c1, AES_DECRYPT(fld_provcode,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_provcode, AES_DECRYPT(fld_email_c1,md5(CONCAT(fld_ctrlno, '".$key."'))) AS fld_email_c1 FROM tbentities WHERE fld_ctrlno = '".$controlno."'");

$s=$sql2->fetch_array();
$email = $gf80['fld_email_c1'];
$name = $s['fld_fname_c1']. " " .substr($s['fld_mname_c1'], 0, 1). ". " .$s['fld_lname_c1']. " " .$s['fld_extname_c1'];
$company = $s['fld_name'];

        // //if code = null, then retrieve code from db
        // if ($code == null) {
        //     $db = new AuthDB();
        //     $code = $db->retrieveCode($email);
        //     if (!$code) {
        //         return false;
        //     }
        // }

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "cicportal@creditinfo.gov.ph";
        $mail->Password = "DEVSCIC.619@RA3019^";
        $mail->setFrom("cicportal@creditinfo.gov.ph", "Credit Information Corporation");
        $mail->addAddress($email, $name);
        // $mail->addAddress("bon.bautista@creditinfo.gov.ph", "Bon Bautista");
        // $mail->addCc("gil.escalante@creditinfo.gov.ph", "Gil Escalante");
        // $mail->addCc("karl.guevarra@creditinfo.gov.ph", "Karl Jorden Guevarra");
        // $mail->addBcc("ala.bautista@creditinfo.gov.ph", "Aileen Amor-Bautista");
        // $mail->addBcc("romeo.ofrin@creditinfo.gov.ph", "Romeo Ofrin");
        // $mail->addBcc("dennyson.hilbero@creditinfo.gov.ph", "Dennyson Hilbero");
        $mail->addBCC("compliance.monitoring-internal@creditinfo.gov.ph", "Compliance Monitoring Internal");
        $mail->addBcc("Jovis.Batongbakal@creditinfo.gov.ph", "Jell Batongbakal");
        $mail->addBcc("cis-support-internal@creditinfo.gov.ph");
        $mail->addBcc("trixia.basa@creditinfo.gov.ph", "Trixia Gueverra");
        $mail->addBcc("mj.tinagan@creditinfo.gov.ph", "MJ Tinagan");
        $mail->addBcc("rex.berdandino@creditinfo.gov.ph", "Rex Berdandino");
        $mail->addBcc("jho.mercado@creditinfo.gov.ph", "Jho Mercado");
        $mail->addBcc("rowena.castro@creditinfo.gov.ph", "Rowena Castro");
        $mail->addBcc("jacquiline.cardino@creditinfo.gov.ph", "Jacquiline Cardino");
        $mail->addBcc("datasubmission-internal@creditinfo.gov.ph");

        $mail->Subject = "Access to CIC Covered Entity Portal";

        $message = '
<div id="all">
   <div style="display:block;width:100%;max-width:700px;margin:0 auto" >
      <table cellpadding="0" cellspacing="0" border="0" width="100%">
         <tr style="background-color: #f5f5f5;">
            <td style="border-top: 1px solid rgb(202,201,200);border-left: 1px solid rgb(202,201,200);border-right: 1px solid rgb(202,201,200); border-radius: 15px 50px 10px 10px;"><br></td>
         </tr>
         <tr style="background-color:#f5f5f5">
            <td align="center" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);padding-bottom: 15px" class="m_-3480498238929770810gmail-m_3586475887212474448null-pad-logo"><a href="http://www.creditinfo.gov.ph/" style="display:block;margin-bottom:10px" target="_blank"><img class="m_-3480498238929770810gmail-m_3586475887212474448head_1_logo-833 CToWUd" src="https://www.creditinfo.gov.ph/img/CICLogo.png" width="80%" style="background-color: #f5f5f5" alt="" style="max-width:645px;display:block"></a></td>
         </tr>
         <tr>
            <td>
               <div>
                  <table bgcolor="#ffffff" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-left:1px solid rgb(202,201,200);border-right:1px solid rgb(202,201,200);background-color: #f5f5f5" >
                     <tbody>
                        <tr height="20px" rowspan="1" colspan="3">
                           <td style="max-width: 645px; word-wrap: break-word;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:black;line-height:1.25;min-width:300px;padding:10px 30px 15px;" id="editTR">
                           <p>Dear <b>'.$name.'</b>,</p>
                                    
                            <p align="justify">This is to inform you that <b>'.$company.'</b> already completed the online Submitting Entity Information Sheet (SEIS) and that you are receiving this email because you are registered as the Primary Contact Person.</p>

                            <p align="justify">
                            As such, you may now register as an Accessing Entity of the Credit Information Corporation (CIC).<br>
                            To proceed with the registration, please login to this site >> <a href="https://www.creditinfo.gov.ph/cicportal">https://www.creditinfo.gov.ph/cicportal</a>
                            </p>

                            <p align="justify">
                                Your credentials are as follows:
                                <br>
                                <table style="font-size: 14px">
                                    <tr>
                                        <td><b>Username:</b></td>
                                        <td><i>Registered email of Primary Contact Person</i></td>
                                    </tr>
                                    <tr>
                                        <td><b>Password:</b></td>
                                        <td><i>'.$password2.'</i></td>
                                    </tr>
                                </table>
                            </p>

                            <p align="justify">
                            The login credentials are intended solely for the use and safekeeping of the individual to whom it was sent. All liability that will emanate from the disclosure, misuse, and/or mishandling of this information, directly and/or indirectly by the individual, will fall on the individual and the Accessing Entity (AE) that authorized or allowed such disclosure, misuse and or mishandling of information, directly or indirectly.
                            </p>
                            <p align="justify"><b>Please note that the release of the access credentials are subject to the following conditions:</b></p>

                            <ol>
                                <li type="1" align="justify">At least six (6) months’ continuous submission of current data.</li>
                                <li type="1" align="justify">Filling-out of the online Accessing Entity Information Sheet (AEIS). The link will be sent to the registered email address of the Primary Contact Person, the Authorized Representative will be copied in the email.</li>
                                <li type="1" align="justify">Complying with the Terms and Conditions which will be displayed on CIC portal by clicking the “I Agree” button.</li>
                                <li type="1" align="justify">Static IP address (unless access is through the Special Accessing Entity of the CIC).</li>
                                <li type="1" align="justify">Consumable minimum advance payment equivalent to 1,000 CIC Credit Reports (unless access is through the Special Accessing Entity of the CIC, in which case this will be governed by an applicable separate Agreement between you and the SAE).
                                </li>
                            </ol>
                            <p align="justify">
                            <b>Below are the payment channels of the CIC with Account No. 1802-1033-91:</b>
                                <ol>
                                    <li type="1" align="justify">Landbank of the Philippines (LBP) or WeAccess Facility, if applicable;</li>
                                    <li type="1" align="justify">Any Bancnet member banks accredited by the Landbank Electronic Payment Portal (EPP); and</li>
                                    <li type="1" align="justify">Instapay facilities of all Landbank partner-banks.</li>
                                </ol>
                            </p>

                            <p align="justify"><b>As an AE, there are two (2) options for access:</b></p>

                            <ol>
                                <li type="1" align="justify">Direct access from the CIC for:
                                    <ol>
                                        <li type="a">Web Portal Access in PDF format</li>
                                        <li type="a">Batch Access in PDF format</li>
                                    </ol>
                                    <p align="justify">For CIC direct access, there must be a consumable minimum advance payment equivalent to 1,000 CIC Credit Reports. </p>
                                </li>
                                <li type="1" align="justify">Through Special Accessing Entity (SAE):
                                    <ol>
                                        <li type="a">Web Portal Access</li>
                                        <li type="a">Batch Access</li>
                                        <li type="a">Application to Application</li>
                                    </ol>
                                </li>
                            </ol>
                            <br>
                            <br>
                            <p>Thank you.</p>
                            <p><img class="m_-3480498238929770810gmail-m_3586475887212474448head_1_logo-833 CToWUd" src="https://www.creditinfo.gov.ph/img/CICLogo.png" width="40%" style="background-color: #f5f5f5" alt="" style="max-width:645px;display:block"></p>
                            
                            <p align="justify"><i style="color:red;">This is an automated notification which is unable to receive replies.</i><br>Should you have any questions or clarifications, you may send an e-mail to <a href="mailto:compliance.access@creditinfo.gov.ph">compliance.access@creditinfo.gov.ph</a></p>
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
</div>
<div>------------------------------<wbr>---------------------------</div>
                            <p style="color:rgb(80,0,80);"><i><span style="background-image:initial;background-position:initial;background-repeat:initial">This&nbsp;e-mail&nbsp;is intended solely for the use of the addressee/s and authorized recipients. It may contain matters which are confidential or legally privileged under the law.&nbsp;</span>Any unauthorized use, copying or distribution, disclosure or dissemination of its contents including the accompanying documents is strictly prohibited. If you are not the intended recipient, please delete all information and kindly notify the sender of the&nbsp;email. Unless expressly stated, any personal views and opinions are the sole responsibility of the sender and not that of the Credit Information Corporation.</i>
                            </p>
                            <div>------------------------------<wbr>---------------------------<b style="color:rgb(80,0,80);font-size:12.8px;text-align:justify"><i><br></i></b></div>';


echo $mail->msgHTML($message);

        //send the message, check for errors
if (!$mail->send()) {
    $errmsg = "Mailer Error: " . $mail->ErrorInfo;
    $sent = False;
} else {
    $msg = "Message sent!";
    $sent = True;
    echo getcwd();
}

?>
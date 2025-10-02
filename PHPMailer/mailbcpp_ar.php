<?php
//ERROR REPORTING
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Manila");

require_once 'config.php';
require_once 'PHPMailerAutoload.php';
require_once '../tcpdf/tcpdf.php';

// $confirm_select = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code FROM tbentities WHERE fld_bill_status = 2 ORDER BY RAND() LIMIT 1;");
$confirm_select = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE fld_bill_status = 2 LIMIT 1");

$credentials = $dbh->query("SELECT * FROM tbemailcredentials WHERE fld_type = 'cicportal'");
$c=$credentials->fetch_array();

if ($rowconfirm = $confirm_select->fetch_array()) {
    function fullname($f, $m, $l, $s){
    $fullname = $f." ";
    if($m){
      $fullname .= substr($m,0,1).". ";
    }
    $fullname .= $l;
    if($s){
      $fullname .= " ".$s;
    }

    return $fullname;
  }



  $SEA = array("10"=>"UB","15"=>"CO","20"=>"CC","25"=>"IH","30"=>"RB","35"=>"UT","40"=>"GF","50"=>"TB","55"=>"TE", "60"=>"PN", "65"=>"PF","70"=>"MF","75"=>"IS","80"=>"LS","85"=>"SAE","90"=>"OT");

  $sql=$dbh->query("SELECT fld_accountno, fld_name FROM tbfininst WHERE fld_code = '".$rowconfirm['provider_code']."'");
  
  if ($res = $sql->fetch_array()) {
    
    if ($res['fld_accountno']) {
      $accountNo = $res['fld_accountno'];
      $dbh->query("UPDATE tbbilling SET fld_accountno = '".$res['fld_accountno']."' WHERE fld_provcode = '".$rowconfirm['provider_code']."'");
    }else{
      $sql10=$dbh->query("SELECT SUBSTRING(`fld_accountno`, 7, 6) AS extract, `fld_accountno` FROM `tbfininst` ORDER BY extract DESC LIMIT 1");
      $e1=$sql10->fetch_array();
      $series = str_pad(((int)$e1['extract'] + 1), 6, "0", STR_PAD_LEFT);
      $genacctno = array_search(substr($rowconfirm['provider_code'], 0, 2), $SEA).date("ym").$series;
      #$accountNo = $genacctno;
      if($dbh->query("UPDATE tbfininst SET fld_accountno = '".$genacctno."' WHERE fld_code = '".$rowconfirm['provider_code']."'")){
        $sql=$dbh->query("SELECT fld_accountno FROM tbfininst WHERE fld_code = '".$rowconfirm['provider_code']."'");
        $res2 = $sql->fetch_array();
        $accountNo = $res2['fld_accountno'];

        $dbh->query("UPDATE tbbilling SET fld_accountno = '".$genacctno."' WHERE fld_provcode = '".$rowconfirm['provider_code']."'");
      }
    }
  }else{
    $sql10=$dbh->query("SELECT SUBSTRING(`fld_accountno`, 7, 6) AS extract, `fld_accountno` FROM `tbfininst` ORDER BY extract DESC LIMIT 1");
    $e1=$sql10->fetch_array();
    $series = str_pad(((int)$e1['extract'] + 1), 6, "0", STR_PAD_LEFT);
    $genacctno = array_search(substr($rowconfirm['provider_code'], 0, 2), $SEA).date("ym").$series;

    $dbh->query("INSERT INTO tbfininst SET fld_accountno = '".$genacctno."', fld_code = '".$rowconfirm['provider_code']."', fld_name = '".$rowconfirm['name']."';");
    $sql=$dbh->query("SELECT fld_accountno FROM tbfininst WHERE fld_code = '".$rowconfirm['provider_code']."'");
    if ($res2 = $sql->fetch_array()) {
      $accountNo = $res2['fld_accountno'];
    }
        
  }

  $ctrlno = $rowconfirm['fld_ctrlno'];
  if (!$accountNo) {
    exit;
  }

  $sql = $dbh4->query("SELECT  fld_ctrlno,
                      AES_DECRYPT(fld_name,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS name,
                      AES_DECRYPT(fld_bill_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_email,
                      AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS contact_fname,
                      AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS contact_mname,
                      AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS contact_lname,
                      AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS contact_sname,
                      AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS contact_email,
                      AES_DECRYPT(fld_provcode,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS provcode,
                      AES_DECRYPT(fld_addr_subdv ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv, 
                      AES_DECRYPT(fld_addr_street ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street, 
                      AES_DECRYPT(fld_addr_number ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number, 
                      AES_DECRYPT(fld_address ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS address, 
                      fld_zip, 
                      AES_DECRYPT(fld_landline_ar ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS landline_ar, 
                      fld_landlinecode_ar, fld_landlinelocal_ar, 
                      AES_DECRYPT(fld_contactno_ar ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS contactno_ar,
                      AES_DECRYPT(fld_lname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS lname_ar,
                      AES_DECRYPT(fld_fname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fname_ar,
                      AES_DECRYPT(fld_mname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS mname_ar,
                      AES_DECRYPT(fld_extname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS extname_ar,
                      AES_DECRYPT(fld_position_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS position_ar,
                      AES_DECRYPT(fld_email_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS email_ar,
                      AES_DECRYPT(fld_sae,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS sae
                    FROM tbentities
                    WHERE fld_ctrlno = '$ctrlno'");
  $res = $sql->fetch_array();

  $name = fullname($res['contact_fname'], $res['contact_mname'], $res['contact_lname'], $res['contact_sname']);
  $id = $ctrlno;
  $email = $res['contact_email'];
  $mail          = new PHPMailer(true);
  $mail->CharSet = 'utf-8';
  $mail->isSMTP();
  $mail->SMTPDebug  = 0;
  $mail->Host       = "smtp.gmail.com";
  $mail->Port       = "587";
  $mail->SMTPSecure = "tls";
  $mail->SMTPAuth   = true;
  $mail->Username   = $c['fld_email'];
  $mail->Password   = $c['fld_password'];
  $mail->setFrom('cicportal@creditinfo.gov.ph',"Credit Information Corporation");
  $mail->addAddress($email, $name);
  $mail->addCC("billing.inquiry@creditinfo.gov.ph", "CIC Billing Inquiry");
  

  $mail->Subject  = "[CIC] ".$res['name']." Accessing Entity Billing Process";
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
                             <p>Dear <b>'.$name.'</b>,<br/></p>
                                      
                                      <p align="justify">
                                      Congratulations on your successful registration and confirmation of your appointment as the Billing and Collection Point Person (BCPP). You may now make your advance payment for
                                      your credit data access limit.  For your payment transaction, kindly adhere to the
                                      following details:
                                      </p>

                                      <ul align="justify">
                                        <li>Agency Name: Credit Information Corporation</li>
                                        <li>Agency Clearing Account No.: 1802-2221-37</li>
                                        <li>Reference Number 1: '.$res['name'].'</li>
                                        <li>Reference Number 2 (Account Number): '.$accountNo.'</li>
                                        <li>Amount: Consumable Advance Payment Required - Minimum amount is Php10,000
  (ten thousand pesos) or equivalent to 1,000 credit reports with the introductory price of Php10 per
  inquiry. The introductory price shall run until December 31, 2020. The CIC shall
  thereafter release Circulars for any price changes.</li>
                                      </ul>

                                      <p align="justify">
                                      The foregoing consumable prepaid Access Limit shall be subject to the following conditions:
                                      </p>

                                      <ol align="justify">
                                        <li>Trigger for replenishment when consumption reaches between 40-50% of the prepaid
  amount. An email/text notification will be sent to you for this purpose.</li>
                                        <li>Immediate Replenishment if consumption reaches more than 50% of the advance payment to
  avoid deactivation of access. An email/text notification will be sent to you for this
  purpose.</li>
                                        <li>Temporary cutting of access when consumption reaches 80% of the prepaid amount. An
  email/text notification will be sent to you for this purpose.</li>
                                        <li>Trigger to request for an increase in your monthly Access Limit (AL) when consumption
  reaches 50% of your monthly Access Limit. The same is subject to CIC’s review and
  approval. An email/text notification will be sent to you for this purpose.</li>
                                        <li>Temporary deactivation when consumption reaches 80% of monthly Access Limit. An
  email/text notification will be sent to you for this purpose.</li>
                                        <li>In cases of disconnection due to non-replenishment of advance payment, access to the
  CIS will be reactivated within the five-day processing period from the CIC’s
  confirmation of the advance payment made for the disconnected accounts.</li>
                                        <li>Requests for reactivation during weekends and holidays shall be done on the next
  working day.</li>
                                      </ol>
                                      <p align="justify">
                                      An additional fee of PhP50.00 (fifty pesos) is charged per transaction by the Land Bank of the Philippines (LBP) for the use of the online collection (On-Coll) payment facility.
                                      </p>
                                      <p align="justify">
                                      You may also make use of the following payment channels:
                                      </p>

                                      <ol align="justify">
                                        <li>BSP - Real Time Gross System (RTGS), if applicable;</li>
                                        <li>BSP - Philpass, if applicable;</li>
                                        <li>Land Bank of the Philippines (LBP) - WeAccess Facility, if applicable;</li>
                                        <li>Any Bancnet member-banks accredited by the Landbank Electronic Payment Portal (EPP);
  and</li>
                                        <li>Instapay facilities of all Landbank partner-banks.</li>
                                      </ol>
                                      <p align="justify">
                                      Please note that the CIC shall issue an official receipt within three (3) working days from the date of
  payment.
                                      </p>
                                      <p align="justify">
                                      If you are accessing through a Special Accessing Entity (SAE), please disregard this email.
                                      </p>
                                      <p align="justify">
                                      Should you have any questions related to this matter, you may send an e-mail to: <a href="mailto:billing.inquiry@creditinfo.gov.ph" target="_blank">billing.inquiry@creditinfo.gov.ph</a>. 
                                      </p>
                                      <p align="justify">
                                      Kindly advise your institution’s Authorized Representative that a separate email will be sent to him/her containing the files that should be signed and uploaded to the CE Portal in order to proceed with your registration as an Accessing Entity.
                                      </p>
                                      <br/>
                             
                              <p>Thank you and best regards,</p>
                              <p><b>Credit Information Corporation</b></p>
                             <br/><br />
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
  $mailSent = 0;
  if ($mail->send()) {
    $mailSent = 1;
    $update2 = $dbh4->query("UPDATE tbentities SET fld_bill_status = 3, fld_bill_instruction_sent = '".date("Y-m-d H:i:s")."' WHERE fld_ctrlno = '".$ctrlno."'");
  }



  $code = $ctrlno.'RA3019';
  $controlNo = $ctrlno;
  $pass = $rowconfirm['provider_code'];
  include('aeispdf.php');
  include('aeisoperatorspdf.php');
  include('moapdf2.php');
  include('seccertpdf.php');



  if ($res['address']) {
    $sql = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".substr($res['address'], 0, 2)."0000000"."'");
    $reg = $sql->fetch_array();

    $sql = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".substr($res['address'], 0, 4)."00000'");
    $prov = $sql->fetch_array();

    $sql = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$res['address']."'");
    $city = $sql->fetch_array();
  }
  $office = ($res['addr_number']? $res['addr_number'].', ':'').($res['addr_street']? $res['addr_street'].', ':'').($res['addr_subdv']? $res['addr_subdv'].', ':'').($city['fld_geotitle']? $city['fld_geotitle'].', ':'').($prov['fld_geotitle']? $prov['fld_geotitle'].', ':'').($reg['fld_geotitle']?:'');
  $landline = preg_replace("/[^0-9]/", "", $res['landline_ar']);
  $phone = $landline? (($res['fld_landlinecode_ar']? '('.$res['fld_landlinecode_ar'].') ':'').substr($landline, 0, 4).' '.substr($landline, 4).($res['fld_landlinelocal_ar']? ' local '.$res['fld_landlinelocal_ar']: '')):'';

  if (!$phone) $phone = $res['contactno_ar'];
  include('aeismailer.php');
}




















$sql = $dbh4->query("SELECT  fld_ctrlno, fld_access_type,
                      AES_DECRYPT(fld_name,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS name,
                      AES_DECRYPT(fld_provcode,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS provcode,
                      AES_DECRYPT(fld_addr_subdv ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv, 
                      AES_DECRYPT(fld_addr_street ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street, 
                      AES_DECRYPT(fld_addr_number ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number, 
                      AES_DECRYPT(fld_address ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS address, 
                      fld_zip, 
                      AES_DECRYPT(fld_landline_ar ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS landline_ar, 
                      fld_landlinecode_ar, fld_landlinelocal_ar, 
                      AES_DECRYPT(fld_contactno_ar ,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS contactno_ar,
                      AES_DECRYPT(fld_lname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS lname_ar,
                      AES_DECRYPT(fld_fname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS fname_ar,
                      AES_DECRYPT(fld_mname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS mname_ar,
                      AES_DECRYPT(fld_extname_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS extname_ar,
                      AES_DECRYPT(fld_position_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS position_ar,
                      AES_DECRYPT(fld_email_ar,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS email_ar,
                      AES_DECRYPT(fld_sae,md5(CONCAT(fld_ctrlno, 'RA3019'))) AS sae
                    FROM tbentities
                    WHERE fld_access_type = 2 AND fld_aeisattach_emailsent = 1 LIMIT 1");
if ($res = $sql->fetch_array()) {
  
  if ($res['address']) {
    $sql = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".substr($res['address'], 0, 2)."0000000"."'");
    $reg = $sql->fetch_array();

    $sql = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".substr($res['address'], 0, 4)."00000'");
    $prov = $sql->fetch_array();

    $sql = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$res['address']."'");
    $city = $sql->fetch_array();
  }
  $office = ($res['addr_number']? $res['addr_number'].', ':'').($res['addr_street']? $res['addr_street'].', ':'').($res['addr_subdv']? $res['addr_subdv'].', ':'').($city['fld_geotitle']? $city['fld_geotitle'].', ':'').($prov['fld_geotitle']? $prov['fld_geotitle'].', ':'').($reg['fld_geotitle']?:'');
  $landline = preg_replace("/[^0-9]/", "", $res['landline_ar']);
  $phone = $landline? (($res['fld_landlinecode_ar']? '('.$res['fld_landlinecode_ar'].') ':'').substr($landline, 0, 4).' '.substr($landline, 4).($res['fld_landlinelocal_ar']? ' local '.$res['fld_landlinelocal_ar']: '')):'';

  if (!$phone) $phone = $res['contactno_ar'];

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
  $mail->setFrom("cicportal@creditinfo.gov.ph", "CIC Data Submission");
  $mail->addAddress($res['email_ar'], $res['fname_ar']);
  $mail->Subject  = "Accessing Entity Information Sheet";
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
                                   <td style="text-align: justify;max-width: 645px; word-wrap: break-word;font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:black;line-height:1.25;min-width:300px;padding:10px 30px 15px;" id="editTR">
                                              <b style="text-transform: uppercase">'.($res['fname_ar'].($res['mname_ar']? " ".substr($res['mname_ar'], 0, 1).'.':'')." ".$res['lname_ar'].($res['extname_ar']? " ".$res['extname_ar']:'')).'</b><br/>'.
                                  $res['position_ar'].'<br/>'.
                                  $res['name'].'<br/><br/>'.
                                    'Dear '.$res['position_ar'].' '.$res['fname_ar'].': <br/><br/><br/>
                                    In order to complete your registration, please download and sign the attached encrypted documents using your entity\'s <b>Provider Code</b> as password.
                                    <br/><br/><br/>
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
        $mail->msgHTML($message);
        $code = $res['fld_ctrlno'].'RA3019';
        $controlNo = $res['fld_ctrlno'];
        $pass = $res['provcode'];
        include('moapdf2.php');
        $mail->addAttachment('../pdf/MOA'.$res['fld_ctrlno'].'.pdf','Memorandum of Agreement with Accessing Entity.pdf');

        $ctrlno = $res['fld_ctrlno'];
        include('aeispdf.php');
        $mail->addAttachment('../pdf/AEIS'.$r['fld_ctrlno'].'.pdf','AEIS Form.pdf');


        if ($res['sae']) {

          $saes = explode(' | ', $res['sae']);
          for ($i=0; $i < sizeof($saes); $i++) {
            $sql = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) AS provcode FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$saes[$i]."'");
            $sae = $sql->fetch_array();
            include('se_agreementpdf.php');
            include('saepdf.php');
            $mail->addAttachment('../pdf/AGR_'.$res['provcode'].'_'.$sae['provcode'].'_TOSE.pdf', $sae['name'].' Agreement 1.pdf');
            $mail->addAttachment('../pdf/AGR_'.$res['provcode'].'_'.$sae['provcode'].'_TOSAE.pdf', $sae['name'].' Agreement 2.pdf');
          }


        }



    
    if ($mail->send()) {
      $dbh4->query("UPDATE tbentities SET fld_aeisattach_emailsent = 2 WHERE fld_ctrlno = '".$res['fld_ctrlno']."'");
    }
    
}else{
  echo 'exit2';
}


?>
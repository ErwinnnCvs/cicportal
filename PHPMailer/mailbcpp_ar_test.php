<?php
exit;
//ERROR REPORTING
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Manila");

require_once 'config.php';
require_once 'PHPMailerAutoload.php';
require_once '../tcpdf/tcpdf.php';

$confirm_select = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code FROM tbentities WHERE fld_ctrlno = 2020020074");

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
  $res = $sql->fetch_array();
  if ($res['fld_accountno']) {
    $accountNo = $res['fld_accountno'];
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

  // print_r($res);exit;



  $code = $ctrlno.'RA3019';
  $controlNo = $ctrlno;
  $pass = $rowconfirm['provider_code'];
  // include('aeispdf.php');
  // include('aeisoperatorspdf.php');
  include('moapdf2_test.php');
  // include('seccertpdf.php');



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
  // include('aeismailer_test2.php');
}























?>
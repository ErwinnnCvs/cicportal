<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
require_once'../config.php' ;

echo "<table border='1' width='100%'>";
$sql1 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE fld_stmt_date = '2020-04-01' AND fld_emailsent IS NULL");#".date('Y-m-d', strtotime('first day of last month'))."
while($r1 = $sql1->fetch_array()){
/*
	$sqlb = $dbh->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code1, fld_access_type, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address, AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number, AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street, AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv, fld_zip AS zip FROM `tbentities` HAVING provider_code1 = '".$r1['fld_provcode']."'");# 1 = direct; 2 = SAE; 3 = both
	$rb = $sqlb->fetch_array();
	if(substr($rb['fld_address'], 6, 6) <> "000"){
		$bgy = $dbh->query("SELECT * FROM tblocation WHERE fld_geocode = '".$rb['fld_address']."'");
		$b1 = $bgy->fetch_array();
	}
	$cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($rb['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
	$c1 = $cty->fetch_array();
	$prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($rb['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
	$p1 = $prv->fetch_array();

	if (!empty($rb['addr_number'])) $addr1a .= trim($rb['addr_number']).', ';
	if (!empty($rb['addr_street'])) $addr1a .= trim($rb['addr_street']).', ';
	if (!empty($rb['addr_subdv'])) $addr1a .= trim($rb['addr_subdv']).', ';
	if (!empty($b1['fld_geotitle'])) $addr1a .= $b1['fld_geotitle'].', ';

	if (!empty($c1['fld_geotitle'])) $addr2a .= $c1['fld_geotitle'].', ';
	if (!empty($p1['fld_geotitle'])) $addr2a .= $p1['fld_geotitle'].' ';
	if (!empty($rb['zip'])) $addr2a .= $rb['zip'];
*/
	$sqla = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code1, AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name,
    AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_bill_contact_fname,
    AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_bill_contact_mname,
    AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_bill_contact_lname,
    AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_bill_contact_sname,
    AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_bill_contact_email
 	FROM `tbentities` HAVING provider_code1 = '".$r1['fld_provcode']."'");


#	$sqla = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code1, fld_access_type, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address, AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number, AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street, AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv, fld_zip AS zip FROM `tbentities` HAVING provider_code1 = '".$r1['fld_provcode']."'");# 1 = direct; 2 = SAE; 3 = both
$ra = $sqla->fetch_array();

	if (!empty($ra['fld_name'])) $addr1 .= trim($ra['fld_name']).', ';
	if (!empty($ra['fld_bill_contact_fname'])) $addr1 .= trim($ra['fld_bill_contact_fname']).', ';
	if (!empty($ra['fld_bill_contact_mname'])) $addr1 .= trim($ra['fld_bill_contact_mname']).', ';
	if (!empty($ra['fld_bill_contact_lname'])) $addr1 .= trim($ra['fld_bill_contact_lname']).', ';
	if (!empty($ra['fld_bill_contact_sname'])) $addr1 .= trim($ra['fld_bill_contact_sname']).', ';
	if (!empty($ra['fld_bill_contact_email'])) $addr1 .= trim($ra['fld_bill_contact_email']).', ';

	$j++;
	echo "<tr><td>".$j."</td><td>".$ra['fld_ctrlno']."</td><td>".$r1['fld_provcode']."</td><td>".$ra['fld_name']."</td><td>".$ra['fld_bill_contact_fname']."</td><td>".$ra['fld_bill_contact_mname']."</td><td>".$ra['fld_bill_contact_lname']."</td><td>".$ra['fld_bill_contact_sname']."</td><td>".$ra['fld_bill_contact_email']."</td></tr>";
#	echo "<tr><td>".$j."</td><td>".$r1['fld_provcode']."</td><td>".$rb['name']."</td><td>".$rb['fld_access_type']."</td><td>".$rb['fld_address']."</td><td>".$rb['addr_number']."</td><td>".$rb['addr_street']."</td><td>".$rb['addr_subdv']."</td><td>".$b['fld_geotitle']."</td><td>".$c1['fld_geotitle']."</td><td>".$p1['fld_geotitle']."</td><td>".$rb['zip']."</td></tr>";
}
echo "</table>";
echo "gil";
?>
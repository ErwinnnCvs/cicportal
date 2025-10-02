<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require_once'../config.php' ;

echo "<table border='1'>";
$sql = $dbh->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM `tbentities` WHERE fld_aeis_process = '4' ORDER BY provider_code ASC");
while($r = $sql->fetch_array()){
	# Get Account No.
	$sql1 = $dbh->query("SELECT * FROM `tbbilling` WHERE fld_provcode = '".$r['provider_code']."'");
	$r1= $sql1->fetch_array();
	# Get Beginning Balance
	$begbal = $paydate = "";
	$sql2 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE fld_provcode = '".$r['provider_code']."' AND fld_date LIKE '".date('Y-m-d', strtotime('first day of last month'))."%'");
	$r2= $sql2->fetch_array();
	$begbal = $r2['fld_balance'];
	if(!$begbal){
		$begbal = "0.00";
	}else{
		$paydate = " `fld_payment_date` >= '".date('Y-m-d', strtotime('first day of last month'))." 00:00:00' AND `fld_payment_date` <= '".date('Y-m-d', strtotime('last day of last month'))." 00:00:00' AND";	
	}
	# GET TOTAL DEDUCTIONS
	$sql3 = $dbh->query("SELECT SUM(`fld_inq_price`) AS totalprice FROM `tbinquiriesdaytemp` WHERE `fld_inqdate` >= '".date('Y-m-d', strtotime('first day of last month'))."' AND `fld_inqdate` <= '".date('Y-m-d', strtotime('last day of last month'))."' AND `fld_provcode` = '".$r['provider_code']."' AND fld_branchcode = 'USERS' AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_CE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_CE' AND fld_errorcode LIKE '%1-100%'))");
	$r3= $sql3->fetch_array();
	$deductions = $r3['totalprice'];
	if(!$deductions){
		$deductions = "0.00";
	}
	echo $begbal." = SELECT `fld_acct_no`, SUM(`fld_amount`) AS totalamount FROM `tbbillingpayment` WHERE".$paydate." `fld_acct_no` = '".$r1['fld_accountno']."'<br/>";
	# GET TOTAL ADDITIONS
	$sql4 = $dbh->query("SELECT `fld_acct_no`, SUM(`fld_amount`) AS totalamount FROM `tbbillingpayment` WHERE".$paydate." `fld_acct_no` = '".$r1['fld_accountno']."'");
	$r4= $sql4->fetch_array();
	$additions = $r4['totalamount'];
	if(!$additions){
		$additions = "0.00";
	}
	$endbal = $begbal + $additions - $deductions;
	if($begbal == "0.00" && $additions > 0){
		$bgc = "#ff0000";
	}else{
		$bgc = "#ffffff";
	}
	echo "<tr><td>".$r['provider_code']."</td><td>".$r1['fld_accountno']."</td><td>".$r['name']."</td><td align='right'>".number_format($begbal, 2)."</td><td align='right' bgcolor='".$bgc."'>".number_format($additions, 2)."</td><td align='right'>".number_format($deductions, 2)."</td><td align='right'>".number_format($endbal, 2)."</td></tr>";
}

	#CRIF
	$sql2 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE fld_provcode = 'SAE09440' AND fld_date LIKE '".date('Y-m-d', strtotime('first day of last month'))."%'");
	$r2= $sql2->fetch_array();
	$begbal = $r2['fld_balance'];
	if(!$begbal){
		$begbal = "0.00";
	}
	echo "<tr><td>SAE09440</td><td>851703000022</td><td>CRIF Corporation</td><td align='right'>".number_format($begbal, 2)."</td><td align='right'>".number_format($additions, 2)."</td><td align='right'>".number_format($deductions, 2)."</td><td align='right'>".number_format($endbal, 2)."</td></tr>";

	#CIBI
	$sql2 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE fld_provcode = 'SAE09670' AND fld_date LIKE '".date('Y-m-d', strtotime('first day of last month'))."%'");
	$r2= $sql2->fetch_array();
	$begbal = $r2['fld_balance'];
	if(!$begbal){
		$begbal = "0.00";
	}
	echo "<tr><td>SAE09670</td><td>851707000020</td><td>CIBI Information Inc</td><td align='right'>".number_format($begbal, 2)."</td><td align='right'>".number_format($additions, 2)."</td><td align='right'>".number_format($deductions, 2)."</td><td align='right'>".number_format($endbal, 2)."</td></tr>";

echo "</table>";

?>

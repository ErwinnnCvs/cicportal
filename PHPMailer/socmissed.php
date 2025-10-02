<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
require_once '../tcpdf/tcpdf.php';
require_once'../config.php' ;

$dateLastMonth =  date('Y-m-d', strtotime("first day of previous month"));###
$month = date('m', strtotime($dateLastMonth));
$year = date('Y', strtotime($dateLastMonth));




$ae_arr = [ 'TB000370', //Bank One Savings Corporation
			'RB001020', //Camalig Bank, Inc. (A Rural Bank)
			'CC000491', //Equicom Savings Bank
			'PF015890', //Filcredit Finance and Capital Development Corporation
			'RB002900', //Golden Rural Bank of the Philippines, Inc.
			'CO009590', //Holy Cross Savings and Credit Cooperative
			'TB002650', //Sun Savings Bank, Inc. (certified: 1April2017)
			'PF004650'  //Welcome Finance Inc.
		];

foreach ($ae_arr as $key => $provcode) {
	$sql_exist = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."' AND fld_provcode = '".$provcode."'");
	if ($exist = $sql_exist->fetch_array()) {
		$ids[] = $exist['fld_id'];
		
	}else{
		$sql_acctno = $dbh->query("SELECT * FROM `tbbilling` WHERE fld_provcode = '".$provcode."'");
		if ($acctno = $sql_acctno->fetch_array()) {

			$sql = $dbh->query("SELECT * FROM tbbillingpayment WHERE fld_acct_no = '".$acctno['fld_accountno']."'");
			$payment = 0;$payment_date = '';
			
			while ($r = $sql->fetch_array()) {
				$payment += $r['fld_amount'];
				$payment_date = $r['fld_datetime'];
			}
			if ($payment) {
				if (date('m', strtotime($payment_date)) == $month) $payment = 0;

				$dbh->query("INSERT INTO tbbillingbalance SET fld_provcode = '".$provcode."', fld_balance = '".$payment."', fld_stmt_id = 0, fld_date = '".$dateLastMonth."'");
				$ids[] = $dbh->insert_id;
			}
		}
	}
	
}


class MYPDF extends TCPDF {
    public function Header() {
        // Logo
        $this->Image('../images/CIClogo3.png', 25, 10, 35, 25, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
        // Title
        $this->Ln(15);
        $this->SetFont('helvetica', 'B', 11);
        $this->MultiCell(0, 5, "CREDIT INFORMATION CORPORATION", 0, 'C', 0, 1, '', '', true);
        $this->SetFont('helvetica', '', 11);
        $this->MultiCell(0, 5, $this->CustomHeaderText, 0, 'C', 0, 1, '', '', true);
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $this->SetAlpha(0.2);
        // Watermark
        $img_file = '../images/CIClogo3.png';
        $this->Image($img_file, 2, 40, 200, 150, '', '', '', false, 300, '', false, false, 0);
        $this->SetAlpha(1);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }
    public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-25);
        $this->SetFont('helvetica', 'I', 7);
        $this->Cell(0, 10, '© 2019 Credit Information Corporation. 6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City.', 'T', true, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-30);
        $this->SetFont('helvetica', 'I', 9);
        $this->MultiCell(0, 5, $this->CustomFooterText, 0, 'C', 0, 1, '', '', true);
    }
}
foreach ($ids as $key => $id) {

	$sql1 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."' AND fld_stmt_id <> 0 ORDER BY fld_stmt_id DESC LIMIT 1");

	if ($r1 = $sql1->fetch_array()) $statementNo = $r1['fld_stmt_id'] + 1;
	else $statementNo = substr($year, -2).$month.'000001';

	$sql2 = $dbh->query("UPDATE `tbbillingbalance` SET fld_stmt_id = ".$statementNo." WHERE fld_id = ".$id." AND fld_stmt_id = 0");
	$sql3 = $dbh->query("SELECT * FROM tbbillingbalance WHERE fld_id = ".$id."");
	$r3 = $sql3->fetch_array();
	$statementNo = $r3['fld_stmt_id'];
	$provcode = $r3['fld_provcode'];
	$begBal = $r3['fld_balance']?:'00';

	// echo $statementNo."<br/>";
	// continue;



		
	
	// create new PDF document
	$pdf = new MYPDF('P','mm',array(210, 297));
	$pdf->SetMargins(15, 20, 15);
	$pdf->SetAutoPageBreak(TRUE, 0);
		


	$sql_bcpp = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS entity_name, AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_fname, AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_mname, AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_lname, AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_sname, AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_email, AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address, AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number, AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street, AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv, fld_zip AS zip FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'"); 
	$bcpp_row = $sql_bcpp->fetch_array();
	$sename = $bcpp_row['entity_name'];


	$b = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$bcpp_row['fld_address']."'")->fetch_array();
	$c = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($bcpp_row['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'")->fetch_array();
	$p = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($bcpp_row['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'")->fetch_array();
	$addr1 = (trim($bcpp_row['addr_number'])? trim($bcpp_row['addr_number']).', ': '').(trim($bcpp_row['addr_street'])? trim($bcpp_row['addr_street']).', ': '').(trim($bcpp_row['addr_subdv'])? trim($bcpp_row['addr_subdv']).', ': '').(trim($b['fld_geotitle'])? trim($b['fld_geotitle']).', ': '');
	$addr2 = (trim($c['fld_geotitle'])? trim($c['fld_geotitle']).', ': '').(trim($p['fld_geotitle'])? trim($p['fld_geotitle']).' ': '').(trim($bcpp_row['zip'])? trim($bcpp_row['zip']): '');


	$r5=$dbh->query("SELECT fld_accountno FROM tbfininst WHERE fld_code = '".$provcode."'")->fetch_array();
    $accountno = $r5['fld_accountno'];
    $ae_inqcost = 10;$sae_inqcost = 55;

	
	
	$pdf->CustomHeaderText = "Statement of Consumption (SOC)";
	$pdf->CustomFooterText = "This is a system generated Statement of Consumption. Signature is not required.";

	
	$sql1=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS inq_price, f.fld_accountno, f.fld_access_limit, f.fld_access_limit_current FROM tbinquiriesdaytemp i JOIN tbbilling f ON i.fld_provcode = f.fld_provcode WHERE i.fld_provcode = '$provcode' AND YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND i.fld_branchcode = 'USERS' AND ((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL')) GROUP BY DAY(i.fld_inqdate)");

	$sql3=$dbh->query("SELECT i.fld_provcode, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS inq_price, f.fld_accountno, f.fld_access_limit, f.fld_access_limit_current, i.fld_sourcecode FROM tbinquiriesdaytemp i JOIN tbbilling f ON i.fld_provcode = f.fld_provcode WHERE i.fld_provcode = '$provcode' AND YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND i.fld_branchcode = 'USERS' AND (i.fld_sourcecode = 'CB_NAE') AND i.fld_inqresult = '0'");
	$nae = $sql3->fetch_array();


	for($d=1; $d<=date("t", strtotime($dateLastMonth)); $d++)
		$list[$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT)]=0;

	$total_numinq = $total_inqprice = 0;$daily_inquiries = [];
    while ($r = $sql1->fetch_array()) {
    	$total_numinq += $r['inq'];
    	$total_inqprice += $r['inq_price'];
    	$list[$r['day']] = ["inquiries" => $r['inq'], "price" => $r['inq_price']];
	}




	$pdf->AddPage();
	$pdf->Ln(17);
	$pdf->SetFont('helvetica', '', 9);
	$pdf->MultiCell(30, 5, "Company Name:", 0, 'L', 0, 0, '', '', true);
	$pdf->MultiCell(70, 5, $sename, 0, 'L', 0, 1, '', '', true);
	$pdf->MultiCell(30, 5, "Company Address:", 0, 'L', 0, 0, '', '', true);
	$pdf->MultiCell(70, 5, $addr1, 0, 'L', 0, 1, '', '', true);
	$pdf->MultiCell(30, 5, "", 0, 'L', 0, 0, '', '', true);
	$pdf->MultiCell(70, 5, $addr2, 0, 'L', 0, 1, '', '', true);

	$pdf->MultiCell(32, 5, "Account Number:", 0, 'L', 0, 0, 120, 37, true);
	$pdf->MultiCell(43, 5, $accountno, 0, 'R', 0, 1, '', '', true);
	$pdf->MultiCell(32, 5, "Statement  Number:", 0, 'L', 0, 0, 120, '', true);
	$pdf->MultiCell(43, 5, $statementNo, 0, 'R', 0, 1, '', '', true);
	$pdf->MultiCell(32, 5, "Statement  Period:", 0, 'L', 0, 0, 120, '', true);
	$pdf->MultiCell(43, 5, date("F j", strtotime($dateLastMonth))." - ".date("t, Y", strtotime($dateLastMonth)), 0, 'R', 0, 1, '', '', true);
	
	//BALANCE
	$pdf->MultiCell(0, 7, "", 'B', 'L', 0, 1, '', '', true);
	$y1 = $pdf->GetY();
	$pdf->Ln(3);
	$pdf->MultiCell(70, 5, "Beginning Balance", 0, 'L', 0, 0, 17, '', true);
	$pdf->MultiCell(68, 5, '', 0, 'R', 0, 0, '', '', true);
	$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
	$pdf->MultiCell(28, 5, toDisp($begBal), '', 'R', 0, 1, '', '', true);

	$sql9=$dbh->query("SELECT fld_payment_date, fld_amount FROM tbbillingpayment WHERE YEAR(fld_payment_date) = '".$year."' AND MONTH(fld_payment_date) = '".$month."' AND fld_acct_no = '".$accountno."'");

	$text = "Advance Payment";$php = 'PHP';
	$ctr = $totAdvPay = 0;
	$num_rows = $sql9->num_rows;
	while ($r9 = $sql9->fetch_array()) {
		$ctr++;
		$totAdvPay += $r9['fld_amount'];
		if ($num_rows == $ctr) {
			$totAdvPayDisp = toDisp($totAdvPay);
	        $marginB = 'B';
		}

	    $pdf->MultiCell(65, 5, $text, 0, 'L', 0, 0, 17, '', true);
	    $pdf->MultiCell(35, 5, date('m/d/Y', strtotime($r9['fld_payment_date'])), '', 'L', 0, 0, '', '', true);
	    $pdf->MultiCell(30, 5, $php." ".toDisp($r9['fld_amount']), $marginB, 'R', 0, 0, '', '', true);
	    $pdf->MultiCell(8, 5, '', '', 'L', 0, 0, '', '', true);
	    $pdf->MultiCell(10, 5, "", 0, 'L', 0, 0, '', '', true);
	    $pdf->MultiCell(28, 5, $totAdvPayDisp, 0, 'R', 0, 0, '', '', true);
	    $pdf->MultiCell(2, 5, '', 0, 'R', 0, 1, '', '', true);
	    $text = $php = '';


	    $replenishment[date("Y-m-d", strtotime($r9['fld_payment_date']))][] = $r9['fld_amount'];
	        $replenishmentTotal += $r9['fld_amount'];

	}



	
	$pdf->SetFont('helvetica', '', 9);
	$pdf->MultiCell(100, 5, "Number of Inquiries", 0, 'L', 0, 0, 17, '', true);
	$pdf->MultiCell(30, 5, number_format(preg_replace("/[^0-9.]/", "", $total_numinq))." @ 10.00", 'B', 'R', 0, 0, '', '', true);
	$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
	$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
	$pdf->MultiCell(28, 5, ($total_inqprice) ? "(".toDisp($total_inqprice).")": "(0.00)", 'B', 'R', 0, 0, '', '', true);
	$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);

	$totalAvailBal = toDisp(toNumber($totAdvPay + $begBal) - toNumber($total_inqprice));
	
	$pdf->SetFont('helvetica', 'B', 9);
	$pdf->MultiCell(70, 5, "Total Available Balance", '', 'L', 0, 0, 17, '', true);
	$pdf->MultiCell(68, 5, '', '', 'R', 0, 0, '', '', true);
	$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
	$pdf->MultiCell(28, 5, $totalAvailBal, '', 'R', 0, 0, '', '', true);
	$pdf->MultiCell(2, 5, "", '', 'L', 0, 1, '', '', true);
	



	//NAE
	$pdf->MultiCell(0, 5, "", 'B', 'L', 0, 1, '', '', true);
	$pdf->Ln(2);
	$pdf->SetFont('helvetica', 'B', 9);
	$pdf->MultiCell(100, 5, 'New Application Inquiries w/ No Hit', 0, 'L', 0, 0, 17, '', true);
	$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
	$pdf->MultiCell(46, 5, ($nae['inq']) ? number_format($nae['inq']): '0', 0, 'R', 0, 0, '', '', true);
	$pdf->MultiCell(2, 5, '', 0, 'R', 0, 1, '', '', true);

	$pdf->Ln(1);
	$naedesc = ["Chargeback Inquiries", "Number of Inquiries", "Balance"];
	foreach ($naedesc as $key => $value) {
		$pdf->SetFont('helvetica', ($value == "Balance")? 'B': '', 9);
		$pdf->MultiCell(138, 4, $value, 0, 'L', 0, 0, 17, '', true);
		$pdf->MultiCell(38, 4, '0', ($value == "Balance")? 'T': '', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 4, "", ($value == "Balance")? 'T': '', 'L', 0, 1, '', '', true);
	}

	$pdf->MultiCell(0, 5, "", 'B', 'L', 0, 1, '', '', true);
	$pdf->Line(15, $y1, 15, $pdf->GetY());
	$pdf->Line(195, $y1, 195, $pdf->GetY());


	$style = array( 'fgcolor' => array(0,0,0), 'bgcolor' => false, 'module_width' => 1, 'module_height' => 1 );
	$pdf->write2DBarcode(md5($provcode."|".date("Ym")."|".$grandtotal_numinq), 'QRCODE,Q', 173, 14, 20, 20, $style, 'N');





		if ($total_numinq) {
			$pdf->AddPage();
		    $pdf->Ln(20);
		    $pdf->SetFont('helvetica', '', 12);
		    $pdf->MultiCell(0, 5, "Details of Accessing Entity Inquiries", 0, 'C', 0, 0, '', '', true);

		    $pdf->Ln(10);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 0, '', '', true);

		    $pdf->Ln(2);
		    $pdf->SetFont('helvetica', '', 9);
		    $pdf->MultiCell(30, 4, "Beginning Balance", 0, 'R', 0, 0, 40, '', true);
		    $pdf->MultiCell(30, 4, "(+) Replenishment", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "(-) Consumption", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Adjustment", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, "Ending Balance", 0, 'R', 0, 1, '', '', true);

		    $pdf->MultiCell(30, 4, toDisp($begBal), 0, 'R', 0, 0, 40, '', true);
		    $pdf->MultiCell(30, 4, toDisp($replenishmentTotal), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, toDisp($total_inqprice), 0, 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, " -   ", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, $totalAvailBal, 0, 'R', 0, 1, '', '', true);

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);

		    $pdf->MultiCell(25, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Replenishment / (Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Consumption/( Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, "Balance", 0, 'R', 0, 1, '', '', true);

		    $cap = $begBal;
		    $pdf->MultiCell(25, 4, 'Beg. Balance', 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, toDisp($begBal), 0, 'R', 0, 1, '', '', true);
		    $pdf->SetFont('helvetica', '', 9);

			$totCons = $totInqs = 0;
		    foreach ($list as $key => $value) {
		        if (isset($replenishment[$key])) {
		        	foreach ($replenishment[$key] as $repkey => $repvalue) {
		        		$cap = toNumber($cap) + toNumber($repvalue);
			            $pdf->MultiCell(25, 4, $key, 0, 'C', 0, 0, '', '', true);
			            $pdf->MultiCell(30, 4, 'Replenishment', 0, 'C', 0, 0, '', '', true);
			            $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
			            $pdf->MultiCell(35, 4, toDisp($repvalue), 0, 'R', 0, 0, '', '', true);
			            $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
			            $pdf->MultiCell(26, 4, toDisp($cap), 0, 'R', 0, 1, '', '', true);
		        	}
		            
		        }
		        if ($value['inquiries']) {
		            $cap = toNumber($cap) - toNumber($value['price']);
		            $totCons += toNumber($value['price']);
		            $totInqs += toNumber($value['inquiries']);
		            $pdf->MultiCell(25, 4, $key, 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(30, 4, (isset($value['inquiries'])) ? 'Inquiries': "", 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(30, 4, (number_format($value['inquiries'])) ? number_format($value['inquiries']) : "", 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		            $pdf->MultiCell(35, 4, toDisp($value['price']), 0, 'R', 0, 0, '', '', true);
		            $pdf->MultiCell(26, 4, toDisp($cap), 0, 'R', 0, 1, '', '', true);
		        }
		        
		    }
		    
		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(25, 4, "Total", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, number_format(preg_replace("/[^0-9.]/", "", $totInqs)), 'TB', 'C', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp(0), 'TB', 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "PHP ".toDisp($totCons), 'TB', 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(26, 4, "PHP ".toDisp($cap), 'TB', 'R', 0, 1, '', '', true);

		    $pdf->Ln(10);
		    $pdf->SetFont('helvetica', '', 9);
		    $pdf->MultiCell(20, 4, "Inquiry Cost: ", 0, 'L', 0, 0, '', '', true);
		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(20, 4, "PHP ".toDisp($ae_inqcost), 0, 'L', 0, 1, '', '', true);
		}

		//POSITION OF INSTRUCTIONS TABLE
		$pdf->setPage(1);$pdf->SetY(123);$y_paydetails = 215;$font = 8;

$tbl = <<<EOD
	<table border="1" cellpadding="2" nobr="true"> <tr> <th width="80" align="center"><b>Consumption \nLevel</b></th> <th width="80" align="center"><b>Subject</b></th> <th width="350" align="center"><b>Email Message</b></th> </tr> <tr> <td>1. 40-50% of Advance Payment</td> <td>Account Deactivation Warning</td> <td>"This is to inform you that you have already consumed more than 40% of your advance payment. You may wish to replenish soon to avoid deactivation of access."</td> </tr> <tr> <td>2. More than 50% of Advance Payment</td> <td>Notice of Account Deactivation at 80%</td> <td>"This is to inform you that you have already consumed more than 50% of your advance payment. Also, be advised that your access will be temporarily cut when you reach 80% consumption of your advance payment.<br/><br/>Kindly replenish immediately to avoid deactivation of access."</td> </tr> <tr> <td>3. 80% and above of Advance Payment</td> <td>Notice of Account Deactivation</td> <td>"We regret to inform you that your credit data access has been temporarily deactivated."</td> </tr> <tr> <td>4. 50% of Access Limit</td> <td>Account Deactivation Warning</td> <td>"This is to remind you that you have already reached 50% of your monthly access limit. You may wish to increase your monthly access limit, subject for review and approval of CIC."</td> </tr> <tr> <td>5. 80% and above of Access Limit</td> <td>Notice of Account Deactivation</td> <td>"We regret to inform you that your access has been temporarily deactivated and will be refreshed next month. You may wish to increase your monthly Access Limit, subject for review and approval of CIC."</td> </tr> <tr> <td colspan="3">6. In cases of disconnection due to non-replenishment of advance payment, access to the CIS will be reactivated within the five-day processing period from the CIC’s confirmation of the advance payment made for the disconnected accounts.<br/><br/> 7. Requests for reactivation during weekends and holidays shall be done on the next working day.<br/><br/> * Amount: “Consumable Advance Payment Required”-minimum amount is equivalent to 1,000 credit reports. The introductory price of Php 10 per access shall run until December 31, 2020. The CIC shall thereafter release Circulars for any price changes. </td> </tr> </table>
EOD;

	$pdf->SetFont('helvetica', '', $font);
	$pdf->MultiCell(0, 5, "Please note that you will be receiving system generated notification from the CIC at various consumption levels, to wit:", 0, 'L', 0, 1, '', '', true);

	$pdf->writeHTML($tbl, true, false, false, false, '');

	$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
	$pdf->SetY($y_paydetails);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetFont('helvetica', 'B', 8);
	$pdf->MultiCell(60, 10, 'Payment Channel    ', 'LR', 'L', 1, 0, '', '', true, 0, false, true, 10, 'M');
	$pdf->MultiCell(60, 10, 'On-Coll Payment Facility', 'LR', 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
	$pdf->MultiCell(60, 10, 'Other Payment Facility (RTGS, InstaPay, PesoNet, BSP Philpass)', 'LR', 'C', 1, 1, '', '', true, 0, false, true, 10, 'M');

	$pdf->SetFont('helvetica', '', $font);
	$pdf->MultiCell(60, 5, 'Agency Name/Biller/Payee', 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'Credit Information Corporation', 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'Credit Information Corporation', 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

	$pdf->MultiCell(60, 5, 'Agency Clearing Acct/Account Number', 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, '1802-2221-37', 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, '1802-1033-91', 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

	$pdf->MultiCell(60, 5, 'Reference Number 1', 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, $sename, 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'n/a', 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

	$pdf->MultiCell(60, 5, 'Reference Number 2 (Account Number)', 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, $accountno, 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'n/a', 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

	$pdf->MultiCell(60, 5, 'Transaction fee', 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'PhP50.00 per transaction', 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'Depends on the payment facility being used.', 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

	$pdf->MultiCell(60, 5, 'Proof of Payment Requirement', 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'No proof of payment required', 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
	$pdf->MultiCell(60, 5, 'Required submission of proof of payment to CIC via email.', 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

	if (substr($provcode, 0, 3) != 'SAE') {
		$pdf->SetFont('helvetica', '', 9);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Ln(2);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->MultiCell(0, 5, "***END OF STATEMENT***", 0, 'C', 0, 1, '', '', true);
	}



	$pdf->lastPage();
	$pass = substr($accountno, -4).strtoupper(str_replace(' ', '', str_replace('-', '', $bcpp_row['bill_contact_lname'])));
	$pdf->SetProtection(array('modify'), $pass);###
	$pdf->Output('../pdf/'.$provcode.date("Ym", strtotime($dateLastMonth)).'.pdf', 'F');
	echo $provcode." - ".$sename."<br/> Password - ".$pass."<br/><br/>";
	
	###include '../PHPMailer/socmailer.php';
	$sql_next = $dbh->query("INSERT INTO `cicportal`.`tbbillingbalance` (`fld_id`, `fld_provcode`, `fld_balance`, `fld_stmt_id`, `fld_emailsent`, `fld_date`) VALUES (NULL, '".$provcode."', '".str_replace( ',', '', $totalAvailBal)."', 0, NULL, '".date("Y-m-d", strtotime($dateLastMonth.' next month'))."');");
	
}


function toNumber($val){
    if (empty($val)) { $val = 0;}
    return (float)preg_replace("/[^0-9.]/", "", $val);
}
function toDisp($val){
    if (empty($val)) { return '';}
    return number_format(preg_replace("/[^0-9.]/", "", $val), 2, '.', ',');
}

?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../tcpdf/tcpdf.php';
require_once'../config.php' ;

$chk = $dbh->query("SELECT * FROM `tbsendsoc` WHERE fld_enddate IS NULL AND fld_processingmonth = '".date('Y-m-d', strtotime('last day of last month'))."'");
if($ck1 = $chk->fetch_array()){


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function Header() {
        // Logo
        $this->Image('../images/CIClogo3.png', 25, 10, 35, 25, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 20);
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
        // Define the path to the image that you want to use as watermark.
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

    function SetDash($black=null, $white=null){
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
}

function toNumber($val){
    if (empty($val)) { $val = 0;}
    return (float)preg_replace("/[^0-9.]/", "", $val);
}
function toDisp($val){
    if (empty($val)) { return '';}
    return number_format(preg_replace("/[^0-9.]/", "", $val), 2, '.', ',');
}

	$tbl = <<<EOD
	<table border="1" cellpadding="2" nobr="true">
	 <tr>
	  <th width="80" align="center"><b>Consumption \nLevel</b></th>
	  <th width="80" align="center"><b>Subject</b></th>
	  <th width="350" align="center"><b>Email Message</b></th>
	 </tr>
	 <tr>
	  <td>1. 40-50% of Advance Payment</td>
	  <td>Account Deactivation Warning</td>
	  <td>"This is to inform you that you have already consumed more than 40% of your advance payment. You may wish to replenish soon to avoid deactivation of access."</td>
	 </tr>
	 <tr>
	  <td>2. More than 50% of Advance Payment</td>
	  <td>Notice of Account Deactivation at 80%</td>
	  <td>"This is to inform you that you have already consumed more than 50% of your advance payment. Also, be advised that your access will be temporarily cut when you reach 80% consumption of your advance payment.<br/><br/>Kindly replenish immediately to avoid deactivation of access."</td>
	 </tr>
	 <tr>
	  <td>3. 80% and above of Advance Payment</td>
	  <td>Notice of Account Deactivation</td>
	  <td>"We regret to inform you that your credit data access has been temporarily deactivated."</td>
	 </tr>
	 <tr>
	  <td>4. 50% of Access Limit</td>
	  <td>Account Deactivation Warning</td>
	  <td>"This is to remind you that you have already reached 50% of your monthly access limit. You may wish to increase your monthly access limit, subject for review and approval of CIC."</td>
	 </tr>
	 <tr>
	  <td>5. 80% and above of Access Limit</td>
	  <td>Notice of Account Deactivation</td>
	  <td>"We regret to inform you that your access has been temporarily deactivated and will be refreshed next month. You may wish to increase your monthly Access Limit, subject for review and approval of CIC."</td>
	 </tr>
	 <tr>
	  <td colspan="3">6. In cases of disconnection due to non-replenishment of advance payment, access to the CIS will be reactivated within the five-day processing period from the CIC’s confirmation of the advance payment made for the disconnected accounts.<br/><br/>
	  7. Requests for reactivation during weekends and holidays shall be done on the next working day.<br/><br/>
	  * Amount: “Consumable Advance Payment Required”-minimum amount is equivalent to 1,000 credit reports. The introductory  price of Php 10 per access shall run until December 31, 2020. The CIC shall thereafter release Circulars for any price changes.
	  </td>
	 </tr>
	</table>
EOD;

$sql3 = $dbh->query("SELECT * FROM `tbinquirycost` WHERE fld_effectivity_date > '".date('Y-m-d', strtotime('first day of last month'))."' AND fld_effectivity_date > '".date('Y-m-d', strtotime('last day of last month'))."' AND fld_costtype = 1");
$r3 = $sql3->fetch_array();
$inqcost = $r3['fld_cost'];

#$sql1 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE fld_stmt_date = '2020-03-01' AND fld_emailsent IS NULL LIMIT 1");
$sql1 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE fld_stmt_date = '".date('Y-m-d', strtotime('first day of last month'))."' AND fld_emailsent IS NULL LIMIT 1");
if($r1 = $sql1->fetch_array()){
	$rec_id = $r1['fld_id'];
	$aetype = $beginbalance = 0;
	# REMOVE SAE ONLY ACCESS (fld_access_type = 2)
	$sqla = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provider_code1, fld_access_type, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address, AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number, AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street, AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv, fld_zip AS zip, AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_fname,		AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_mname, AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_lname, AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_sname, AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_email FROM `tbentities` HAVING provider_code1 = '".$r1['fld_provcode']."'");# 1 = direct; 2 = SAE; 3 = both
	$ra = $sqla->fetch_array();
			$addr1 = $addr2 = '';
	if($ra['fld_access_type'] <> 2){
		$cnt++;
		$provcode = $r1['fld_provcode'];
		# GENERATE STATEMENT ID
		$sql2 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE fld_stmt_date = '".date('Y-m-d', strtotime('first day of last month'))."' AND fld_emailsent IS NOT NULL ORDER BY fld_stmt_id DESC LIMIT 1");
		if ($r2 = $sql2->fetch_array()) {
		    $statementNo = $r2['fld_stmt_id'] + 1;
		}else{
			$statementNo = date('ym', strtotime('first day of last month'))."000001";
		}
		$sqlc = $dbh->query("SELECT * FROM `tbbilling` WHERE fld_provcode = '".$ra['provider_code1']."'");
		$rc = $sqlc->fetch_array();
		$sename = $ra['name'];
		$accountno = $rc['fld_accountno'];
		if($r1['fld_beginbalance'] > 0){
			$beginbalance =  toDisp($r1['fld_beginbalance']);
		}else{
			$beginbalance =  "0.00";
		}
#		echo "gil".$beginbalance;
		if(substr($ra['fld_address'], 6, 6) <> "000"){
			$bgy = $dbh->query("SELECT * FROM tblocation WHERE fld_geocode = '".$ra['fld_address']."'");
			$b = $bgy->fetch_array();
		}
		$cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($ra['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
		$c = $cty->fetch_array();
		$prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($ra['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
		$p = $prv->fetch_array();

		if (!empty($ra['addr_number'])) $addr1 .= trim($ra['addr_number']).', ';
		if (!empty($ra['addr_street'])) $addr1 .= trim($ra['addr_street']).', ';
		if (!empty($ra['addr_subdv'])) $addr1 .= trim($ra['addr_subdv']).', ';
		if (!empty($b['fld_geotitle'])) $addr1 .= $b['fld_geotitle'].', ';

		if (!empty($c['fld_geotitle'])) $addr2 .= $c['fld_geotitle'].', ';
		if (!empty($p['fld_geotitle'])) $addr2 .= $p['fld_geotitle'].' ';
		if (!empty($ra['zip'])) $addr2 .= $ra['zip'];

		if(substr($ra['provider_code1'], 0, 3) <> "SAE"){  # CHECK IF SAE THEN GENERATE CORRESPONDING REPORT
			$aetype = 1; #Accessing Entity
			$soctitle = "Statement of Consumption (SOC)";
		}else{
			$aetype = 2;
			$soctitle = "Statement of Aggregated Consumption (SOAC)";
		}
		// create new PDF document
		$pdf = new MYPDF('P','mm',array(210, 297));
		$pdf->SetMargins(15, 20, 15);
		$pdf->CustomHeaderText = $soctitle;
		$pdf->CustomFooterText = "This is a system generated Statement of Aggregated Consumption. Signature is not required.";
		$pdf->SetAutoPageBreak(TRUE, 0);

		$pdf->AddPage();
		$pdf->Ln(17);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(30, 5, "Company Name:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(70, 5, $sename, 0, 'L', 0, 1, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(30, 5, "Company Address:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(70, 5, $addr1.$addr2, 0, 'L', 0, 1, '', '', true);
		$pdf->MultiCell(30, 5, "", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 5, "", 0, 'L', 0, 1, '', '', true);#$addr2

		$pdf->SetXY(120, 37);
		$pdf->MultiCell(32, 5, "Account Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $accountno, 0, 'R', 0, 1, '', '', true);
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $statementNo, 0, 'R', 0, 1, '', '', true);
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Period:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(43, 5, date('F j', strtotime('first day of last month'))." - ".date('j, Y', strtotime('last day of last month')), 0, 'R', 0, 1, '', '', true);
		$pdf->SetFont('helvetica', '', 9);

		$pdf->SetXY(17, 60);
		$pdf->MultiCell(70, 5, "Beginning Balance", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(68, 5, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, $beginbalance, '', 'R', 0, 1, '', '', true);

		$text = "Advance Payment";
		$php = 'PHP';
		$totAdvPay = 0;
		$arrAdvPay = [];
		$ctr = 0;
 
		$sqlb = $dbh->query("SELECT `fld_aeis_noc_ts` FROM `tbentities` WHERE `fld_ctrlno` = '".$ra['fld_ctrlno']."'");
		$rb =$sqlb->fetch_array();
		if(strtotime($rb['fld_aeis_noc_ts']) >= strtotime("first day of last month") && strtotime($rb['fld_aeis_noc_ts']) <= strtotime("last day of last month")){
			$sql9=$dbh->query("SELECT fld_payment_date, fld_amount FROM tbbillingpayment WHERE fld_payment_date <= '".date('Y-m-d', strtotime('last day of last month'))."' AND fld_acct_no = '".$accountno."'");
		}else{
			$sql9=$dbh->query("SELECT fld_payment_date, fld_amount FROM tbbillingpayment WHERE fld_payment_date >= '".date('Y-m-d', strtotime('first day of last month'))."' AND fld_payment_date <= '".date('Y-m-d', strtotime('last day of last month'))."' AND fld_acct_no = '".$accountno."'");
		}

		if($aetype == 1){ # AE
			$replenishment = [];
			while ($r9 = $sql9->fetch_array()) {
			    $totAdvPay += $r9['fld_amount'];
			    $arrAdvPay[$ctr] = ["amount" => $r9['fld_amount'], "date" => $r9['fld_payment_date']];
			    $list[substr($r9['fld_payment_date'], 0, 10)] = ["replenishment" => $r9['fld_amount'], "price" => $r9['fld_amount']];
			    $ctr++;
#			    if (!$r10['fld_balance']) {
#			        $r10['fld_balance'] = $r9['fld_amount'];
#			    }else{
			        $replenishment[date("Y-m-d", strtotime($r9['fld_payment_date']))][$ctr] = $r9['fld_amount'];
			        $replenishmentTotal += $r9['fld_amount'];
#			    }
			}
		}elseif($aetype == 2){ # SAE
			while ($r9 = $sql9->fetch_array()) {
			    $totAdvPay += $r9['fld_amount'];
			    $arrAdvPay[$ctr] = ["amount" => $r9['fld_amount'], "date" => $r9['fld_payment_date']];
			    $ctr++;
			}
		}


		$ctrAdv = 1;
		$totAdvPayDisp = "";
		$marginB = 0;
		$countarrAdvPay = count($arrAdvPay);
		foreach ($arrAdvPay as $key => $value) {
		    if ($countarrAdvPay == $ctrAdv) {
		        $totAdvPayDisp = $totAdvPay;
		        $marginB = 'B';
		    }
		    $pdf->SetX(17);
		    $pdf->MultiCell(65, 5, $text, 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 5, date('m/d/Y', strtotime($value['date'])), '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 5, $php." ".toDisp($value['amount']), $marginB, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(8, 5, '', '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(10, 5, "", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(28, 5, toDisp($totAdvPayDisp), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(2, 5, '', 0, 'R', 0, 1, '', '', true);
		    $text = $php = '';
		    $ctrAdv++;
		}
		if($aetype == 1){ # AE
			$sql1=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS inq_price, f.fld_accountno, f.fld_access_limit, f.fld_access_limit_current FROM tbinquiriesdaytemp i JOIN tbbilling f ON i.fld_provcode = f.fld_provcode WHERE i.fld_provcode = '".$provcode."' AND i.fld_inqdate LIKE '".date('Y-m', strtotime('first day of last month'))."%' AND i.fld_branchcode = 'USERS' AND ((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL')) GROUP BY DAY(i.fld_inqdate)");

			$inq_price = 0;
			$totInq1 = 0;
			while ($r = $sql1->fetch_array()) {
			    $totInq1 += $r['inq'];
			    $inq_price = $inq_price + $r['inq_price'];
			    $list[$r['day']] = ["inquiries" => $r['inq'], "price" => $r['inq_price']];
			}

			$pdf->SetX(17);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->MultiCell(100, 5, "Number of Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5, number_format(preg_replace("/[^0-9.]/", "", $totInq1))." @ 10.00", 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(28, 5, ($inq_price) ? "(".toDisp($inq_price).")": "(0.00)", 'B', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);


			$pdf->Line(15, 58, 15, 90);
			$pdf->Line(195, 58, 195, 90);
			$pdf->Line(15, 58, 195, 58);
			$pdf->Line(15, 100, 195, 100);
			$pdf->Line(15, 90, 15, 121);
			$pdf->Line(195, 90, 195, 121);
			$pdf->Line(15, 121, 195, 121);

			$totalAvailBal = $totAdvPay + $r1['fld_beginbalance'] - $inq_price;
			$pdf->SetX(17);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(100, 5, "Total Available Balance", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5, '', 'T', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 5, "PHP", '0', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(28, 5, toDisp(toNumber($totalAvailBal)), '', 'R', 0, 0, '', '', true);//$r10['fld_balance']
			$pdf->MultiCell(2, 5, "", '0', 'L', 0, 1, '', '', true);

			for($ge = 1; $ge <= 3; $ge++){
				$pdf->SetX(15);
				$pdf->SetFont('helvetica', 'B', 9);
				$pdf->MultiCell(180, 5, "", 0, 'L', 0, 1, '', '', true);
			}

			$style = array(
			    'fgcolor' => array(0,0,0),
			    'bgcolor' => false, //array(255,255,255)
			    'module_width' => 1, // width of a single module in points
			    'module_height' => 1 // height of a single module in points
			);
			$pdf->write2DBarcode(md5($provcode."|".date("Ym")), 'QRCODE,Q', 173, 14, 20, 20, $style, 'N');

			$pdf->SetXY(17, 102);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(70, 4, "New Application Inquiries w/ No Hit", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(28, 4, ($nae['inq']) ? $nae['inq']: '0', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 4, '', 0, 'L', 0, 1, '', '', true);

			$pdf->Ln(1);
			$pdf->SetX(17);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->MultiCell(70, 4, "Chargeback Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(28, 4, '0', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 4, '', 0, 'L', 0, 1, '', '', true);

			$pdf->SetX(17);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->MultiCell(70, 4, "Number of Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(38, 4, '0', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 4, "", 0, 'L', 0, 1, '', '', true);

			$pdf->SetX(17);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(70, 4, "Balance", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 4, '', 'T', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(28, 4, '0', 'T', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 4, "", 'T', 'L', 0, 1, '', '', true);

			$pdf->SetFont('helvetica', '', 9);


			// $pdf->Ln(15);
			$pdf->SetY(123);

			$pdf->SetFont('helvetica', '', 8);
			$pdf->MultiCell(0, 5, "Please note that you will be receiving system generated notification from the CIC at various consumption levels, to wit:", 0, 'L', 0, 1, '', '', true);


			$pdf->writeHTML($tbl, true, false, false, false, '');

			$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
			$pdf->SetY(215);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('helvetica', 'B', 8);
			$pdf->MultiCell(60, 10, 'Payment Channel    ', 'LR', 'L', 1, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf->MultiCell(60, 10, 'On-Coll Payment Facility', 'LR', 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf->MultiCell(60, 10, 'Other Payment Facility (RTGS, InstaPay, PesoNet, BSP Philpass)', 'LR', 'C', 1, 1, '', '', true, 0, false, true, 10, 'M');


			$pdf->SetFont('helvetica', '', 8);
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

			$pdf->AddPage();
		    $pdf->Ln(20);
		    $pdf->SetFont('helvetica', '', 12);
		    $pdf->MultiCell(0, 5, "Details of Accessing Entity Inquiries", 0, 'C', 0, 0, '', '', true);

		    $pdf->Ln(10);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 0, '', '', true);


		    $pdf->Ln(2);
		    $pdf->SetX(40);
		    $pdf->SetFont('helvetica', '', 9);
		    $pdf->MultiCell(30, 4, "Beginning Balance", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "(+) Replenishment", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "(-) Consumption", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Adjustment", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, "Ending Balance", 0, 'R', 0, 1, '', '', true);

		    $pdf->SetX(40);
		    $pdf->MultiCell(30, 4, toDisp($beginbalance), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, toDisp($replenishmentTotal), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, toDisp($inq_price), 0, 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, " -   ", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, toDisp(toNumber($totAdvPay + $beginbalance) - toNumber($inq_price)), 0, 'R', 0, 1, '', '', true);

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);

		    $pdf->MultiCell(25, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Replenishment / (Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Consumption/( Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, "Balance", 0, 'R', 0, 1, '', '', true);

		    $cap = $beginbalance;
		    $pdf->MultiCell(25, 4, 'Beg. Balance', 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, toDisp($beginbalance), 0, 'R', 0, 1, '', '', true);
		    $pdf->SetFont('helvetica', '', 9);

		    $totCons = "0.00";
		    $totInqs = 0;
		    foreach ($list as $key => $value) {
		    	if(count($replenishment[key($replenishment)]) > 0){
#		        if (isset($replenishment[$key])) {
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
		    if($totCons < 0){
		    	$nega_l = "(";
		    	$nega_r = ")";
		    }else{
		    	$nega_l = "";
		    	$nega_r = "";
		    }
		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(25, 4, "Total", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, number_format(preg_replace("/[^0-9.]/", "", $totInqs)), 'TB', 'C', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp(0), 'TB', 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "PHP ".$nega_l.toDisp($totCons).$nega_r, 'TB', 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(26, 4, "PHP ".toDisp($cap), 'TB', 'R', 0, 1, '', '', true);

		    $pdf->Ln(10);
		    $pdf->SetFont('helvetica', '', 9);
		    $pdf->MultiCell(20, 4, "Inquiry Cost: ", 0, 'L', 0, 0, '', '', true);
		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(20, 4, "PHP ".toDisp(10), 0, 'L', 0, 1, '', '', true);
		}elseif($aetype == 2){ # SAE
			$SAE_details["SAE09670"] = ["branchcode" => "SCIBI", "user" =>  "CIB", "compname" =>  "CIBI Inc."];
			$SAE_details["SAE09440"] = ["branchcode" => "SCRIF", "user" =>  "CRF", "compname" =>  "CRIF Inc."];
			$comp[$provcode]['name'] = $SAE_details[$provcode]['compname'];
			$comp[$provcode]['inquiries'] = "0";
			$comp[$provcode]['price'] = "0.00";
			$sql1=$dbh->query("SELECT i.fld_provcode, SUM(i.fld_inqcount) AS inq, SUM(i.fld_inq_price) AS price, f.fld_name AS name, fld_inqdate FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code WHERE i.fld_inqdate LIKE '".date('Y-m', strtotime('first day of last month'))."%' AND i.fld_usercode <> 'TESTTEST' AND i.fld_servicecode <> 'CBPMS' AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = '".$SAE_details[$provcode]["branchcode"]."' OR (i.fld_branchcode LIKE 'SAE%' AND i.fld_usercode LIKE '%".$SAE_details[$provcode]["user"]."')) AND ((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%')) GROUP BY i.fld_provcode, i.fld_inqdate");
			while ($r8 = $sql1->fetch_array()) {
				$totInqPrice5 = $totInqPrice5 + $r8['price'];				
				$comp[$r8['fld_provcode']]['name'] = $r8['name'];
				$comp[$r8['fld_provcode']]['inquiries'] = $comp[$r8['fld_provcode']]['inquiries'] + $r8['inq'];
				$comp[$r8['fld_provcode']]['price'] = $comp[$r8['fld_provcode']]['price'] + $r8['price'];
				$list2[$r8['fld_provcode']][$r8['fld_inqdate']]['inquiries'] = $list2[$r8['fld_provcode']][$r8['fld_inqdate']]['inquiries'] + $r8['inq'];
				$list2[$r8['fld_provcode']][$r8['fld_inqdate']]['inqprice'] = $list2[$r8['fld_provcode']][$r8['fld_inqdate']]['inqprice'] + $r8['price'];
			}

			$pdf->SetX(17);
			$pdf->MultiCell(138, 5, "Total Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(28, 5, ($totInqPrice5) ? "(".toDisp($totInqPrice5).")": "(0.00)", 'B', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);

			$totalAvailBal = $totAdvPay + $r1['fld_beginbalance'] - $totInqPrice5;
			if(($totAdvPay + $r1['fld_beginbalance']) < $totInqPrice5){
				$nega_l = "(";
				$nega_r = ")";
			}else{
				$nega_l = "";
				$nega_r = "";

			}

			$pdf->SetX(17);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(70, 5, "Total Available Balance", '', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(68, 5, '', '', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(28, 5, $nega_l.toDisp(toNumber($totalAvailBal)).$nega_r, '', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 5, "", '', 'L', 0, 1, '', '', true);

			$pdf->SetX(15);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->MultiCell(5, 5, "", 'B', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(70, 5, "", 'B', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(65, 5, '', 'B', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(28, 5, "", 'B', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);


			$pdf->Line(15, 58, 15, 124);
			$pdf->Line(195, 58, 195, 124);

			$pdf->Line(15, 58, 195, 58);

			$pdf->Line(15, 106, 15, 155);
			$pdf->Line(195, 106, 195, 155);

			$pdf->Line(15, 130, 195, 130);
			$pdf->Line(15, 155, 195, 155);

			$pdf->Ln(3);
			$pdf->SetX(17);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(70, 5, "Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5, 'Quantity', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(38, 5, 'Amount', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 5, "", 0, 'L', 0, 1, '', '', true);

			$totalPrice = 0;
			$tctr = "PHP";
			foreach ($comp as $key => $value) {
			    $totalPrice += $value['price'];
			    $pdf->SetX(22);
			    $pdf->SetFont('helvetica', '', 9);
			    $pdf->MultiCell(5, 5, "", 0, 'L', 0, 0, '', '', true);
			    $pdf->MultiCell(90, 5, ($key == $provcode) ? $value['name']." (Consumer Inquiry)": $value['name'], 0, 'L', 0, 0, '', '', true);
			    $pdf->MultiCell(30, 5, number_format($value['inquiries']), 0, 'R', 0, 0, '', '', true);
			    $pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
			    $pdf->MultiCell(10, 5, $tctr, '', 'L', 0, 0, '', '', true);
			    $pdf->MultiCell(28, 5, toDisp(($value['price']) ? $value['price']: '00'), 0, 'R', 0, 0, '', '', true);
			    $pdf->MultiCell(2, 5, '', '', 'R', 0, 1, '', '', true);
			    $tctr = '';
			}
			if($totalPrice > 0){
				$tPrice = toDisp($totalPrice);
			}else{
				$tPrice = "0.00";
			}
			$pdf->SetX(17);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(100, 5, "Total Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5, number_format(preg_replace("/[^0-9.]/", "", $totInq5)), 'T', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 5, "PHP", 'T', 'L', 0, 0, '', '', true);
			$pdf->MultiCell(28, 5, $tPrice, 'T', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 5, "", 'T', 'L', 0, 1, '', '', true);

			$pdf->SetXY(17, 132);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(100, 5, 'New Application Inquiries w/ No Hit', 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(46, 5, ($nae['inq']) ? number_format($nae['inq']): '0', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 5, '', 0, 'R', 0, 1, '', '', true);

			$pdf->Ln(1);
			$pdf->SetX(17);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->MultiCell(70, 4, "Chargeback Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(28, 4, '0', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 4, '', 0, 'L', 0, 1, '', '', true);

			$pdf->SetX(17);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->MultiCell(70, 4, "Number of Inquiries", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(38, 4, '0', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 4, "", 0, 'L', 0, 1, '', '', true);

			$pdf->SetX(17);
			$pdf->SetFont('helvetica', 'B', 9);
			$pdf->MultiCell(70, 4, "Balance", 0, 'L', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(30, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(8, 4, '', 0, 'R', 0, 0, '', '', true);
			$pdf->MultiCell(10, 4, '', 'T', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(28, 4, '0', 'T', 'R', 0, 0, '', '', true);
			$pdf->MultiCell(2, 4, "", 'T', 'L', 0, 1, '', '', true);

#			$pdf->SetFont('helvetica', '', 9);

			$pdf->SetFont('helvetica', '', 9);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Ln(20);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->MultiCell(0, 5, "***END OF STATEMENT***", 0, 'C', 0, 1, '', '', true);

			$style = array(
			    'fgcolor' => array(0,0,0),
			    'bgcolor' => false, //array(255,255,255)
			    'module_width' => 1, // width of a single module in points
			    'module_height' => 1 // height of a single module in points
			);
			$pdf->write2DBarcode(md5($provcode."|".date("Ym")."|".$totInq5), 'QRCODE,Q', 173, 14, 20, 20, $style, 'N');
			$pdf->SetY(123);
			foreach($comp as $key1 => $value1){
				if($key1 <> $provcode){
			#		echo $key1." = ".$value1."<br/>";
				    $pdf->AddPage();

				    $pdf->Ln(20);
				    $pdf->SetFont('helvetica', '', 12);
				    $pdf->MultiCell(0, 5, "Inquiries for ".$value1['name'], 0, 'C', 0, 0, '', '', true);


				    $pdf->Ln(10);
				    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 0, '', '', true);

				    $pdf->Ln(2);
				    $pdf->SetFont('helvetica', '', 9);

		/*
				    $pdf->MultiCell(60, 4, "Date", 0, 'C', 0, 0, '', '', true);
				    $pdf->MultiCell(60, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
				    $pdf->MultiCell(60, 4, "Quantity", 0, 'C', 0, 1, '', '', true);
		*/
				    $pdf->MultiCell(40, 4, "Date", 0, 'C', 0, 0, '', '', true);
				    $pdf->MultiCell(40, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
				    $pdf->MultiCell(40, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
				    $pdf->MultiCell(45, 4, "Amount", 0, 'R', 0, 0, '', '', true);
				    $pdf->MultiCell(15, 4, "", 0, 'C', 0, 1, '', '', true);


				    $pdf->Ln(2);
				    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);
		#echo key($list2[$key1]);
				    $totAmt = $totCnt = 0;
				    foreach ($list2[$key1] as $key => $value) {
			            $totAmt += toNumber($value['inqprice']);
			            $totCnt += $value['inquiries'];
			            $pdf->MultiCell(40, 4, $key, 0, 'C', 0, 0, '', '', true);
				        $pdf->MultiCell(40, 4, (isset($value['inquiries'])) ? 'Inquiries':'', 0, 'C', 0, 0, '', '', true);
				        $pdf->MultiCell(40, 4, (isset($value['inquiries'])) ? number_format($value['inquiries']):'', 0, 'C', 0, 0, '', '', true);
				        $pdf->MultiCell(45, 4, (isset($value['inqprice'])) ? toDisp($value['inqprice']):'', 0, 'R', 0, 0, '', '', true);
				        $pdf->MultiCell(15, 4, '', 0, 'C', 0, 1, '', '', true);
				    }

				    $pdf->SetFont('helvetica', 'B', 9);
				    $pdf->MultiCell(40, 4, "", 0, 'L', 0, 0, '', '', true);
				    $pdf->MultiCell(40, 4, "Total", 0, 'C', 0, 0, '', '', true);
				    $pdf->MultiCell(40, 4, number_format($totCnt), 'TB', 'C', 0, 0, '', '', true);
				    $pdf->MultiCell(45, 4, "PHP ".toDisp(($totAmt) ? $totAmt: '00'), 'TB', 'R', 0, 0, '', '', true);
				    $pdf->MultiCell(15, 4, '', 'TB', 'C', 0, 1, '', '', true);

				    $pdf->Ln(10);
				    $pdf->SetFont('helvetica', '', 9);
				    $pdf->MultiCell(20, 4, "Inquiry Cost: ", 0, 'L', 0, 0, '', '', true);
				    $pdf->SetFont('helvetica', 'B', 9);
				    $pdf->MultiCell(20, 4, "PHP ".toDisp($inqcost), 0, 'L', 0, 1, '', '', true);
			    }
			}

			$pdf->AddPage();
			$pdf->SetY(40);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->MultiCell(0, 5, "Please note that you will be receiving system generated notification from the CIC at various consumption levels, to wit:", 0, 'L', 0, 1, '', '', true);


			$pdf->writeHTML($tbl, true, false, false, false, '');
			$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
			$pdf->SetY(158);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('helvetica', 'B', 8);
			$pdf->MultiCell(60, 10, 'Payment Channel    ', 'LR', 'L', 1, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf->MultiCell(60, 10, 'On-Coll Payment Facility', 'LR', 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
			$pdf->MultiCell(60, 10, 'Other Payment Facility (RTGS, InstaPay, PesoNet, BSP Philpass)', 'LR', 'C', 1, 1, '', '', true, 0, false, true, 10, 'M');


			$pdf->SetFont('helvetica', '', 9);
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
		}

		$pass = substr($accountno, -4).strtoupper(str_replace(' ', '', str_replace('-', '', $ra['bill_contact_lname'])));
		$pdf->SetProtection(array('modify'), $pass);
		#$pdf->Output('../pdf/test/SOC'.$provcode.'202004.pdf', 'F');
		$pdf->Output('../pdf/test/SOC'.$provcode.date("Ym", strtotime('first day of last month')).'.pdf', 'F');
#		$pdf->Output('../pdf/test/SOC'.$provcode.date("Ym", strtotime('first day of last month')).'.pdf', 'I');
		include '../PHPMailer/socmailer.php';
	}
}else{
	$dbh->query("UPDATE tbsendsoc SET fld_enddate = '".date('Y-m-d H:i:s')."' WHERE fld_id = '".$ck1['fld_id']."'");
}


}



?>
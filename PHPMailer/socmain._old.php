<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../tcpdf/tcpdf.php';
require_once'../config.php' ;

$dateLastMonth =  date('Y-m-d', strtotime("first day of previous month"));
$month = date('m', strtotime($dateLastMonth));
$year = date('Y', strtotime($dateLastMonth));
$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);


$sql = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."' AND fld_stmt_id = '0'");
$r = $sql->fetch_array();
if ($r) {
	$sql1 = $dbh->query("SELECT * FROM `tbbillingbalance` WHERE YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."' AND fld_stmt_id <> 0 ORDER BY fld_stmt_id DESC LIMIT 1");


	if ($r1 = $sql1->fetch_array()) {
	    $statementNo = $r1['fld_stmt_id'] + 1;
	}else{
	    $statementNo = substr($year, -2).$month.'000001';
	}

	
	$sql2 = $dbh->query("UPDATE `tbbillingbalance` SET fld_stmt_id = ".$statementNo." WHERE YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."' AND fld_stmt_id = 0 ORDER BY fld_id LIMIT 1");

	$sql3 = $dbh->query("SELECT * FROM tbbillingbalance WHERE fld_stmt_id = ".$statementNo."");
	$r3 = $sql3->fetch_array();
	$provcode = $r3['fld_provcode'];




	
	if ($provcode == 'SAE09670') {

	    

		$sql_bcpp = $dbh4->query("SELECT 
		AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS entity_name,
		AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_fname,
		AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_mname,
		AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_lname,
		AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_sname,
		AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_email
		FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
		$bcpp_row = $sql_bcpp->fetch_array();





		$sql2=$dbh->query("SELECT DISTINCT(i.fld_provcode) FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCIBI' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CIB')) AND "
		  ."((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR "
		  ."(i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%')) ORDER BY f.fld_name");

		$sql_nae=$dbh->query("SELECT SUM(fld_inqcount) AS inq FROM tbinquiriesdaytemp AS i 
		    WHERE YEAR(i.fld_inqdate) = '".$year."' 
		    AND MONTH(i.fld_inqdate) = '".$month."' 
		    AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCIBI' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CIB'))
		    AND (i.fld_sourcecode = 'CB_NAE') AND i.fld_inqresult = '0'");


		$nae = $sql_nae->fetch_array();

		function moveElement(&$array, $a, $b) {
		    $out = array_splice($array, $a, 1);
		    array_splice($array, $b, 0, $out);
		} 

		$provcodes = [];
		for ($i=0; $i < $sql2->num_rows; $i++) { 
		    $r2 = $sql2->fetch_array();
		    $provcodes[$i] = $r2['fld_provcode'];
		}


		if (!in_array($provcode, $provcodes)) {
		    array_unshift($provcodes, $provcode);
		}else{
		    moveElement($provcodes, array_search($provcode, $provcodes), 0);
		}






		$totInq5 = 0;
		$totInqPrice5 = 0;

		foreach ($provcodes as $key => $fld_provcode) {


		    $sql4=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, f.fld_name, sum(i.fld_inqcount) AS inq, f.fld_accountno, f.fld_datasubjectid, f.fld_datasubjectbd, f.fld_limit FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE i.fld_provcode = '".$provcode."'");
		    $r4 = $sql4->fetch_array();



		    $sql5=$dbh->query("SELECT b.fld_advance_payment, b.fld_advance_payment_current, b.fld_access_limit, b.fld_access_limit_current, b.fld_accountno, f.fld_name FROM tbbilling b JOIN tbfininst f ON b.fld_provcode = f.fld_code WHERE b.fld_provcode = '".$provcode."'");
		    $r5 = $sql5->fetch_array();

		    $sql7=$dbh->query("SELECT sum(fld_amount) AS deposit FROM tbbillingpayment WHERE fld_acct_no = '".$r4['fld_accountno']."'");
		    $r7 = $sql7->fetch_array();
		    
		    

		    $sename = $r4['fld_name'];
		    $accountno = $r4['fld_accountno'];
		    $addr1 = "30th floor, BDO Equitable Tower, 8751, \nPaseo de Roxas, Makati City, Philippines";
		    $addr2 = '';
		    $advance_payment = $r5['fld_advance_payment'];
		    $advance_payment_current = $r5['fld_advance_payment_current'];
		    


		    $sql3=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, f.fld_name, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS inq_price, f.fld_accountno, f.fld_datasubjectid, f.fld_datasubjectbd, f.fld_limit FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE i.fld_provcode = '".$fld_provcode."' AND YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCIBI' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CIB')) AND "
		  ."((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR "
		  ."(i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%'))"
		  ."GROUP BY DAY(i.fld_inqdate)");



		    for($d=1; $d<=$days; $d++)
		    {    
		      $list3[$fld_provcode][$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT)]=0;
		    }
		    

		    $totInq6 = 0;
		    $totInqPrice = 0;
		    if ($sql3->num_rows) {
		        while ($r3 = $sql3->fetch_array()) {
		            $name = $r3['fld_name'];
		            $totInq5 += $r3['inq'];
		            $totInq6 += $r3['inq'];
		            $totInqPrice5 += $r3['inq_price'];
		            $totInqPrice += $r3['inq_price'];
		            $list3[$fld_provcode][$r3['day']] = ["inquiries" => $r3['inq'], "cost" =>  $r3['inq_price']];
		        }
		        $comp[$fld_provcode] = ["name" => $name, "inquiries" => $totInq6, "price" => $totInqPrice];
		    }else{
		        $comp[$fld_provcode] = ["name" => $bcpp_row['entity_name'], "inquiries" => $totInq6, "price" => $totInqPrice];
		    }
		    
		    
		    
		    
		    
		}

		$inqs = [];
		for($d=1; $d<=$days; $d++)
		    {    
		      $inqs[strtotime($year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT)." 23:59:59")]=[];
		    }
		$sql8=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, f.fld_name, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS amount, f.fld_accountno, f.fld_datasubjectid, f.fld_datasubjectbd, f.fld_limit FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCIBI' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CIB')) AND "
		  ."((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR "
		  ."(i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%'))"
		  ."GROUP BY DAY(i.fld_inqdate)");

		$inqpt = 0 ;

		while ($r8 = $sql8->fetch_array()) {
		    $inqpt += $r8['amount'];
		    $r8['particular'] = 'inquiries';
		    $inqs[strtotime($r8['day']." 23:59:59")] = $r8;
		}


		$sql9=$dbh->query("SELECT fld_payment_date, fld_amount FROM tbbillingpayment WHERE YEAR(fld_payment_date) = '".$year."' AND MONTH(fld_payment_date) = '".$month."' AND fld_acct_no = '".$r4['fld_accountno']."'");


		$sql10=$dbh->query("SELECT * FROM tbbillingbalance WHERE fld_provcode = '".$provcode."' AND YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."'");
		$r10 = $sql10->fetch_array();





		function __reorder(&$a, &$b){
		    $c = array();
		    foreach($b as $index){
		        array_push($c, $a[$index]);
		    }
		    return $c;
		}
		ksort($inqs);

		$detBal = $advance_payment;
		$totRepAdj = 0;
		$totConAdj = 0;
		foreach ($inqs as $key => $value) {

		    $repAdj = '';
		    $conAdj = '';
		    $inq = '';
		    
		    if (isset($value['particular'])) {
		        if ($value['particular'] == 'replenishment') {
		            $repAdj = toDisp($value['amount']);
		            $totRepAdj += toNumber($repAdj);
		            $detBal += $value['amount'];
		        }elseif ($value['particular'] == 'inquiries') {
		            $detBal -= $value['amount'];
		            $inq = number_format($value['inq']);
		            $conAdj = toDisp($value['amount']);
		            $totConAdj += toNumber($conAdj);
		        }
		    }
		    $inqs2[$key] = [
		        "date" => date('Y-m-d', $key),
		        "particular" => (isset($value['particular'])) ? $value['particular']: '',
		        "inq" => $inq,
		        "repAdj" => $repAdj,
		        "conAdj" => $conAdj,
		        "detBal" => toDisp($detBal)
		    ];
		}


		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {

		    //Page header
		    public function Header() {
		        // Logo
		        // $image_file = K_PATH_IMAGES.'logo_example.jpg';
		        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
		        $this->Image('../images/CIClogo3.png', 25, 10, 35, 25, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
		        // Set font
		        $this->SetFont('helvetica', 'B', 20);
		        // Title
		        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		        $this->Ln(15);
		        $this->SetFont('helvetica', 'B', 11);
		        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		        $this->MultiCell(0, 5, "CREDIT INFORMATION CORPORATION", 0, 'C', 0, 1, '', '', true);
		        $this->SetFont('helvetica', '', 11);
		        $this->MultiCell(0, 5, "Statement of Aggregated Consumption (SOAC)", 0, 'C', 0, 1, '', '', true);




		        // Get the current page break margin
		        $bMargin = $this->getBreakMargin();

		        // Get current auto-page-break mode
		        $auto_page_break = $this->AutoPageBreak;

		        // Disable auto-page-break
		        $this->SetAutoPageBreak(false, 0);
		        // set alpha to semi-transparency
		        $this->SetAlpha(0.2);
		        // Define the path to the image that you want to use as watermark.
		        $img_file = '../images/CIClogo3.png';
		        // set alpha to semi-transparency
		        

		        // Render the image
		        $this->Image($img_file, 2, 40, 200, 150, '', '', '', false, 300, '', false, false, 0);
		        $this->SetAlpha(1);
		        // Restore the auto-page-break status
		        $this->SetAutoPageBreak($auto_page_break, $bMargin);

		        // Set the starting point for the page content
		        $this->setPageMark();
		    }

		    // Page footer
		    public function Footer() {
		        // Position at 15 mm from bottom
		        $this->SetY(-25);
		        // Set font
		        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
		        $this->SetFont('helvetica', 'I', 7);
		        $this->Cell(0, 10, '© 2019 Credit Information Corporation. 6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City.', 'T', true, 'C', 0, '', 0, false, 'T', 'M');

		        $this->SetFont('helvetica', 'I', 8);
		        // Page number
		        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');

		        $this->SetY(-30);
		        $this->SetFont('helvetica', 'I', 9);
		        $this->MultiCell(0, 5, "This is a system generated Statement of Aggregated Consumption. Signature is not required.", 0, 'C', 0, 1, '', '', true);
		    }

		    function SetDash($black=null, $white=null)
		    {
		        if($black!==null)
		            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
		        else
		            $s='[] 0 d';
		        $this->_out($s);
		    }


		}


		// create new PDF document
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new MYPDF('P','mm',array(210, 297));//355.6



		$pdf->SetMargins(15, 20, 15);
		// remove default header/footer
		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
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
		$pdf->MultiCell(70, 5, $addr1, 0, 'L', 0, 1, '', '', true);
		$pdf->MultiCell(30, 5, "", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 5, $addr2, 0, 'L', 0, 1, '', '', true);

		$pdf->SetXY(120, 37);//135
		$pdf->MultiCell(32, 5, "Account Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $accountno, 0, 'R', 0, 1, '', '', true);
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $statementNo, 0, 'R', 0, 1, '', '', true);
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Period:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(43, 5, date("F j", strtotime($dateLastMonth))." - ".date("t, Y", strtotime($dateLastMonth)), 0, 'R', 0, 1, '', '', true);
		$pdf->SetFont('helvetica', '', 9);




		$pdf->SetXY(17, 60);
		$pdf->MultiCell(70, 5, "Beginning Balance", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(68, 5, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, toDisp($r10['fld_balance']), '', 'R', 0, 1, '', '', true);


		$text = "Advance Payment";
		$php = 'PHP';
		$totAdvPay = 0;
		$arrAdvPay = [];
		$ctr = 0;
		while ($r9 = $sql9->fetch_array()) {
		    $totAdvPay += $r9['fld_amount'];
		    $arrAdvPay[$ctr] = ["amount" => $r9['fld_amount'], "date" => $r9['fld_payment_date']];
		    $ctr++;
		}


		$ctrAdv = 1;
		$totAdvPayDisp = "";
		$marginB = 0;
		$countarrAdvPay = count($arrAdvPay);
		foreach ($arrAdvPay as $key => $value) {
		    if ($countarrAdvPay == $ctrAdv) {
		        $totAdvPayDisp = toDisp($totAdvPay);
		        $marginB = 'B';
		    }
		    $pdf->SetX(17);
		    $pdf->MultiCell(65, 5, $text, 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 5, date('m/d/Y', strtotime($value['date'])), '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 5, $php." ".toDisp($value['amount']), $marginB, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(8, 5, '', '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(10, 5, "", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(28, 5, $totAdvPayDisp, 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(2, 5, '', 0, 'R', 0, 1, '', '', true);
		    $text = $php = '';
		    $ctrAdv++;
		}


		$pdf->SetX(17);
		$pdf->MultiCell(138, 5, "Total Inquiries", 0, 'L', 0, 0, '', '', true);
		// $pdf->MultiCell(35, 5, "PHP ".toDisp(5000), '', 'R', 0, 1, '', '', true);//number_format(preg_replace("/[^0-9.]/", "", $amtforrep), 2, '.', ',')
		$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, ($totInqPrice5) ? "(".toDisp($totInqPrice5).")": "(0.00)", 'B', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);


		$totalAvailBal = toDisp(toNumber($totAdvPay + $r10['fld_balance']) - toNumber($totInqPrice5));
		$pdf->SetX(17);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->MultiCell(70, 5, "Total Available Balance", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(68, 5, '', '', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, $totalAvailBal, '', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 5, "", '', 'L', 0, 1, '', '', true);


		$pdf->SetX(15);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(5, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, '', 'B', 'R', 0, 0, '', '', true);
		// $pdf->MultiCell(35, 5, "PHP ".toDisp(500), 'B', 'R', 0, 1, '', '', true);
		$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, "", 'B', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);


		$pdf->Line(15, 58, 15, 124);
		$pdf->Line(195, 58, 195, 124);

		$pdf->Line(15, 58, 195, 58);
		// $pdf->Line(15, 124, 195, 124);

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

		$pdf->SetX(17);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->MultiCell(100, 5, "Total Inquiries", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(30, 5, number_format(preg_replace("/[^0-9.]/", "", $totInq5)), 'T', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", 'T', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, toDisp($totalPrice), 'T', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 5, "", 'T', 'L', 0, 1, '', '', true);

		$pdf->SetXY(17, 132);
		$pdf->SetFont('helvetica', 'B', 9);
		// $pdf->MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
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

		$pdf->SetFont('helvetica', '', 9);

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



		if (false) {//$sql1->num_rows
		    $pdf->AddPage();
		    $pdf->Ln(20);
		    $pdf->SetFont('helvetica', '', 12);
		    $pdf->MultiCell(0, 5, "Details of Inquiries", 0, 'C', 0, 0, '', '', true);

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
		    $pdf->MultiCell(30, 4, toDisp($advance_payment), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, toDisp($totRepAdj), 0, 'R', 0, 0, '', '', true);
		    // $pdf->SetTextColor(255, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp($totConAdj), 0, 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, ' -', 0, 'R', 0, 0, '', '', true);//number_format(toNumber($totRepAdj) - toNumber($totConAdj), 2, '.', ',')
		    $pdf->MultiCell(26, 4, toDisp($detBal), 0, 'R', 0, 1, '', '', true);

		    

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);

		    $pdf->MultiCell(25, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Replenishment / (Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Consumption/( Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, "Balance", 0, 'R', 0, 1, '', '', true);

		    $cap = $r7['deposit'];
		    $pdf->MultiCell(25, 4, 'Beg. Balance', 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, toDisp($advance_payment), 0, 'R', 0, 1, '', '', true);
		    $pdf->SetFont('helvetica', '', 9);

		    
		    foreach ($inqs2 as $key => $value) {
		        $pdf->MultiCell(25, 4, $value['date'], 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(30, 4, $value['particular'], 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(30, 4, $value['inq'], 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(35, 4, $value['repAdj'], 0, 'R', 0, 0, '', '', true);
		        $pdf->MultiCell(35, 4, $value['conAdj'], 0, 'R', 0, 0, '', '', true);
		        $pdf->MultiCell(26, 4, $value['detBal'], 0, 'R', 0, 1, '', '', true);
		    }

		    
		    $pdf->MultiCell(25, 4, "Total", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    // $pdf->SetTextColor(255, 0, 0);
		    $pdf->MultiCell(30, 4, number_format(preg_replace("/[^0-9.]/", "", $totInq5)), 'TB', 'C', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp($totRepAdj), 'TB', 'R', 0, 0, '', '', true);
		    // $pdf->SetTextColor(255, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp('312,970.00'), 'TB', 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(26, 4, toDisp(toNumber($r7['deposit']) - toNumber($totInqPrice5)), 'TB', 'R', 0, 1, '', '', true);
		}


		$ctr = 0;
		// $inqcost = [0, 55, 10, 10];

		foreach ($list3 as $key => $value) {
		    if ($key == $provcode) {
		    	continue;
		    }
		    $ctr++;
		    $inqcost = ($key == $provcode) ? 55: 10;
		    $pdf->AddPage();

		    $pdf->Ln(20);
		    $pdf->SetFont('helvetica', '', 12);
		    $pdf->MultiCell(0, 5, ($key == $provcode) ? 'Consumer Inquiries for '.$comp[$key]['name']: 'Inquiries for '.$comp[$key]['name'], 0, 'C', 0, 0, '', '', true);


		    $pdf->Ln(10);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 0, '', '', true);

		    $pdf->Ln(2);
		    $pdf->SetFont('helvetica', '', 9);

		    $pdf->MultiCell(40, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(45, 4, "Amount", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(15, 4, "", 0, 'C', 0, 1, '', '', true);

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);
		     
		    
		    $totAmt = 0;
		    foreach ($value as $key2 => $value2) {
		        if ($value2['inquiries']) {
		            $totAmt += toNumber($value2['cost']);
		            $pdf->MultiCell(40, 4, $key2, 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(40, 4, (isset($value2['inquiries'])) ? 'Inquiries':'', 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(40, 4, (isset($value2['inquiries'])) ? number_format($value2['inquiries']):'', 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(45, 4, (isset($value2['cost'])) ? toDisp($value2['cost']):'', 0, 'R', 0, 0, '', '', true);
		            $pdf->MultiCell(15, 4, '', 0, 'C', 0, 1, '', '', true);
		        }
		        
		    }

		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(40, 4, "", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, "Total", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, number_format($comp[$key]['inquiries']), 'TB', 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(45, 4, "PHP ".toDisp(($totAmt) ? $totAmt: '00'), 'TB', 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(15, 4, '', 'TB', 'C', 0, 1, '', '', true);

		    $pdf->Ln(10);
		    $pdf->SetFont('helvetica', '', 9);
		    $pdf->MultiCell(20, 4, "Inquiry Cost: ", 0, 'L', 0, 0, '', '', true);
		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(20, 4, "PHP ".toDisp($inqcost), 0, 'L', 0, 1, '', '', true);
		}

		$pdf->AddPage();
		$pdf->SetY(40);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(0, 5, "Please note that you will be receiving system generated notification from the CIC at various consumption levels, to wit:", 0, 'L', 0, 1, '', '', true);



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
  * Amount: “Consumable Advance Payment Required”-minimum amount is equivalent to 1,000 credit reports. The introductory  price of Php 10 per access shall run until March 31, 2020. The CIC shall thereafter release Circulars for any price changes.
  </td>
 </tr>
</table>
EOD;


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

		// move pointer to last page
		$pdf->lastPage();

		// ---------------------------------------------------------


		$pass = substr($r4['fld_accountno'], -4).$bcpp_row['bill_contact_lname'];
		$pdf->SetProtection(array('modify'),str_replace('-','',$pass) );
		//Close and output PDF document
		$pdf->Output('../pdf/SOC'.$provcode.date("Ym", strtotime($dateLastMonth)).'.pdf', 'F');
	    echo 'SAE '.$provcode." Pass- ".$pass;





	}elseif($provcode == 'SAE09440'){

		$sql_bcpp = $dbh4->query("SELECT 
		AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS entity_name,
		AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_fname,
		AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_mname,
		AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_lname,
		AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_sname,
		AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_email
		FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
		$bcpp_row = $sql_bcpp->fetch_array();






		$sql2=$dbh->query("SELECT DISTINCT(i.fld_provcode) FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCRIF' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CRF')) AND "
		  ."((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR "
		  ."(i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%')) ORDER BY f.fld_name");


		$sql_nae=$dbh->query("SELECT SUM(fld_inqcount) AS inq FROM tbinquiriesdaytemp AS i 
		    WHERE YEAR(i.fld_inqdate) = '".$year."' 
		    AND MONTH(i.fld_inqdate) = '".$month."' 
		    AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCRIF' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CRF'))
		    AND (i.fld_sourcecode = 'CB_NAE') AND i.fld_inqresult = '0'");


		$nae = $sql_nae->fetch_array();

		function moveElement(&$array, $a, $b) {
		    $out = array_splice($array, $a, 1);
		    array_splice($array, $b, 0, $out);
		} 

		$provcodes = [];
		for ($i=0; $i < $sql2->num_rows; $i++) { 
		    $r2 = $sql2->fetch_array();
		    $provcodes[$i] = $r2['fld_provcode'];
		}


		if (!in_array($provcode, $provcodes)) {
		    array_unshift($provcodes, $provcode);
		}else{
		    moveElement($provcodes, array_search($provcode, $provcodes), 0);
		}






		$totInq5 = 0;
		$totInqPrice5 = 0;

		foreach ($provcodes as $key => $fld_provcode) {


		    $sql4=$dbh->query("SELECT * FROM `tbfininst` WHERE fld_accountno = '851703000022'");
		    $r4 = $sql4->fetch_array();


		    $sql5=$dbh->query("SELECT b.fld_advance_payment, b.fld_advance_payment_current, b.fld_access_limit, b.fld_access_limit_current, b.fld_accountno, f.fld_name FROM tbbilling b JOIN tbfininst f ON b.fld_provcode = f.fld_code WHERE b.fld_provcode = '".$provcode."'");
		    $r5 = $sql5->fetch_array();

		    $sql7=$dbh->query("SELECT sum(fld_amount) AS deposit FROM tbbillingpayment WHERE fld_acct_no = '".$r4['fld_accountno']."'");
		    $r7 = $sql7->fetch_array();
		    
		    

		    $sename = $r4['fld_name'];
		    $accountno = $r4['fld_accountno'];
		    $addr1 = "Level 10-1 Fort Legend Tower, \n31st Street & 3rd Avenue \nBonifacio Global City, Taguig City";//8747 Paseo de Roxas, Bel-Air, Makati, 1209, Philippines
		    $addr2 = '';
		    $advance_payment = $r5['fld_advance_payment'];
		    $advance_payment_current = $r5['fld_advance_payment_current'];
		    


		    $sql3=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, f.fld_name, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS inq_price, f.fld_accountno, f.fld_datasubjectid, f.fld_datasubjectbd, f.fld_limit FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE i.fld_provcode = '".$fld_provcode."' AND YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCRIF' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CRF')) AND "
		  ."((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR "
		  ."(i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%'))"
		  ."GROUP BY DAY(i.fld_inqdate)");
		    
		    // echo "<pre/>";



		    for($d=1; $d<=$days; $d++)
		    {    
		      $list3[$fld_provcode][$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT)]=0;
		    }
		    

		    $totInq6 = 0;
		    $totInqPrice = 0;
		    if ($sql3->num_rows) {
		        while ($r3 = $sql3->fetch_array()) {
		            $name = $r3['fld_name'];
		            $totInq5 += $r3['inq'];
		            $totInq6 += $r3['inq'];
		            $totInqPrice5 += $r3['inq_price'];
		            $totInqPrice += $r3['inq_price'];
		            $list3[$fld_provcode][$r3['day']] = ["inquiries" => $r3['inq'], "cost" =>  $r3['inq_price']];
		        }
		        $comp[$fld_provcode] = ["name" => $name, "inquiries" => $totInq6, "price" => $totInqPrice];
		    }else{
		        $comp[$fld_provcode] = ["name" => $bcpp_row['entity_name'], "inquiries" => $totInq6, "price" => $totInqPrice];
		    }
		    // print_r($comp);
		    // exit;
		    
		    
		    
		    
		    
		}

		$inqs = [];
		for($d=1; $d<=$days; $d++)
		    {    
		      $inqs[strtotime($year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT)." 23:59:59")]=[];//["inquiries" => $r['inq']]
		    }
		$sql8=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, f.fld_name, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS amount, f.fld_accountno, f.fld_datasubjectid, f.fld_datasubjectbd, f.fld_limit FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND (i.fld_provcode = '".$provcode."' OR i.fld_branchcode = 'SCRIF' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CRF')) AND "
		  ."((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR "
		  ."(i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%'))"
		  ."GROUP BY DAY(i.fld_inqdate)");

		$inqpt = 0 ;

		while ($r8 = $sql8->fetch_array()) {
		    $inqpt += $r8['amount'];
		    $r8['particular'] = 'inquiries';
		    $inqs[strtotime($r8['day']." 23:59:59")] = $r8;
		}


		$sql9=$dbh->query("SELECT fld_payment_date, fld_amount FROM tbbillingpayment WHERE YEAR(fld_payment_date) = '".$year."' AND MONTH(fld_payment_date) = '".$month."' AND fld_acct_no = '".$r4['fld_accountno']."'");


		$sql10=$dbh->query("SELECT * FROM tbbillingbalance WHERE fld_provcode = '".$provcode."' AND YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."'");
		$r10 = $sql10->fetch_array();





		function __reorder(&$a, &$b){
		    $c = array();
		    foreach($b as $index){
		        array_push($c, $a[$index]);
		    }
		    return $c;
		}
		ksort($inqs);

		$detBal = $advance_payment;
		$totRepAdj = 0;
		$totConAdj = 0;
		foreach ($inqs as $key => $value) {

		    $repAdj = '';
		    $conAdj = '';
		    $inq = '';
		    
		    if (isset($value['particular'])) {
		        if ($value['particular'] == 'replenishment') {
		            $repAdj = toDisp($value['amount']);
		            $totRepAdj += toNumber($repAdj);
		            $detBal += $value['amount'];
		        }elseif ($value['particular'] == 'inquiries') {
		            $detBal -= $value['amount'];
		            $inq = number_format($value['inq']);
		            $conAdj = toDisp($value['amount']);
		            $totConAdj += toNumber($conAdj);
		        }
		    }
		    $inqs2[$key] = [
		        "date" => date('Y-m-d', $key),
		        "particular" => (isset($value['particular'])) ? $value['particular']: '',
		        "inq" => $inq,
		        "repAdj" => $repAdj,
		        "conAdj" => $conAdj,
		        "detBal" => toDisp($detBal)
		    ];
		}


		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {

		    //Page header
		    public function Header() {
		        // Logo
		        // $image_file = K_PATH_IMAGES.'logo_example.jpg';
		        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
		        $this->Image('../images/CIClogo3.png', 25, 10, 35, 25, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
		        // Set font
		        $this->SetFont('helvetica', 'B', 20);
		        // Title
		        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		        $this->Ln(15);
		        $this->SetFont('helvetica', 'B', 11);
		        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		        $this->MultiCell(0, 5, "CREDIT INFORMATION CORPORATION", 0, 'C', 0, 1, '', '', true);
		        $this->SetFont('helvetica', '', 11);
		        $this->MultiCell(0, 5, "Statement of Aggregated Consumption (SOAC)", 0, 'C', 0, 1, '', '', true);




		        // Get the current page break margin
		        $bMargin = $this->getBreakMargin();

		        // Get current auto-page-break mode
		        $auto_page_break = $this->AutoPageBreak;

		        // Disable auto-page-break
		        $this->SetAutoPageBreak(false, 0);
		        // set alpha to semi-transparency
		        $this->SetAlpha(0.2);
		        // Define the path to the image that you want to use as watermark.
		        $img_file = '../images/CIClogo3.png';
		        // set alpha to semi-transparency
		        

		        // Render the image
		        $this->Image($img_file, 2, 40, 200, 150, '', '', '', false, 300, '', false, false, 0);
		        $this->SetAlpha(1);
		        // Restore the auto-page-break status
		        $this->SetAutoPageBreak($auto_page_break, $bMargin);

		        // Set the starting point for the page content
		        $this->setPageMark();
		    }

		    // Page footer
		    public function Footer() {
		        // Position at 15 mm from bottom
		        $this->SetY(-25);
		        // Set font
		        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
		        $this->SetFont('helvetica', 'I', 7);
		        $this->Cell(0, 10, '© 2019 Credit Information Corporation. 6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City.', 'T', true, 'C', 0, '', 0, false, 'T', 'M');

		        $this->SetFont('helvetica', 'I', 8);
		        // Page number
		        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');

		        $this->SetY(-30);
		        $this->SetFont('helvetica', 'I', 9);
		        $this->MultiCell(0, 5, "This is a system generated Statement of Aggregated Consumption. Signature is not required.", 0, 'C', 0, 1, '', '', true);
		    }

		    function SetDash($black=null, $white=null)
		    {
		        if($black!==null)
		            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
		        else
		            $s='[] 0 d';
		        $this->_out($s);
		    }


		}


		// create new PDF document
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new MYPDF('P','mm',array(210, 297));//355.6




		$pdf->SetMargins(15, 20, 15);
		// remove default header/footer
		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
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
		$pdf->MultiCell(70, 5, $addr1, 0, 'L', 0, 1, '', '', true);
		$pdf->MultiCell(30, 5, "", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 5, $addr2, 0, 'L', 0, 1, '', '', true);

		$pdf->SetXY(120, 37);//135
		$pdf->MultiCell(32, 5, "Account Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $accountno, 0, 'R', 0, 1, '', '', true);
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $statementNo, 0, 'R', 0, 1, '', '', true);
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Period:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(43, 5, date("F j", strtotime($dateLastMonth))." - ".date("t, Y", strtotime($dateLastMonth)), 0, 'R', 0, 1, '', '', true);
		$pdf->SetFont('helvetica', '', 9);

		$pdf->SetXY(17, 60);
		$pdf->MultiCell(70, 5, "Beginning Balance", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(68, 5, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, '00', '', 'R', 0, 1, '', '', true);//toDisp($r10['fld_balance'])


		$text = "Advance Payment";
		$php = 'PHP';
		$totAdvPay = 0;
		$arrAdvPay = [];
		$ctr = 0;
		while ($r9 = $sql9->fetch_array()) {
		    $totAdvPay += $r9['fld_amount'];
		    $arrAdvPay[$ctr] = ["amount" => $r9['fld_amount'], "date" => $r9['fld_payment_date']];
		    $ctr++;
		}


		$ctrAdv = 1;
		$totAdvPayDisp = "";
		$marginB = 0;
		$countarrAdvPay = count($arrAdvPay);
		foreach ($arrAdvPay as $key => $value) {
		    if ($countarrAdvPay == $ctrAdv) {
		        $totAdvPayDisp = toDisp($totAdvPay);
		        $marginB = 'B';
		    }
		    $pdf->SetX(17);
		    $pdf->MultiCell(65, 5, $text, 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 5, date('m/d/Y', strtotime($value['date'])), '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 5, $php." ".toDisp($value['amount']), $marginB, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(8, 5, '', '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(10, 5, "", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(28, 5, $totAdvPayDisp, 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(2, 5, '', 0, 'R', 0, 1, '', '', true);
		    $text = $php = '';
		    $ctrAdv++;
		}


		$pdf->SetX(17);
		$pdf->MultiCell(138, 5, "Total Inquiries", 0, 'L', 0, 0, '', '', true);
		// $pdf->MultiCell(35, 5, "PHP ".toDisp(5000), '', 'R', 0, 1, '', '', true);//number_format(preg_replace("/[^0-9.]/", "", $amtforrep), 2, '.', ',')
		$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, ($totInqPrice5) ? "(".toDisp($totInqPrice5).")": "(0.00)", 'B', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);


		$totalAvailBal = toDisp(toNumber($totAdvPay + $r10['fld_balance']) - toNumber($totInqPrice5));
		$pdf->SetX(17);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->MultiCell(70, 5, "Total Available Balance", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(68, 5, '', '', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, $totalAvailBal, '', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 5, "", '', 'L', 0, 1, '', '', true);


		$pdf->SetX(15);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(5, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(65, 5, '', 'B', 'R', 0, 0, '', '', true);
		// $pdf->MultiCell(35, 5, "PHP ".toDisp(500), 'B', 'R', 0, 1, '', '', true);
		$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);//PHP
		$pdf->MultiCell(28, 5, "", 'B', 'R', 0, 0, '', '', true);//toDisp(500)
		$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);


		$pdf->Line(15, 58, 15, 124);
		$pdf->Line(195, 58, 195, 124);

		$pdf->Line(15, 58, 195, 58);
		// $pdf->Line(15, 124, 195, 124);

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
		    $pdf->MultiCell(90, 5, ($key == $provcode) ? $value['name']." (Consumer Inquiry)": 'CreditAccess Philippines Financing Company, Inc.', 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 5, number_format($value['inquiries']), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(10, 5, $tctr, '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(28, 5, toDisp(($value['price']) ? $value['price']: '00'), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(2, 5, '', '', 'R', 0, 1, '', '', true);
		    $tctr = '';
		}

		$pdf->SetX(17);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->MultiCell(100, 5, "Total Inquiries", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(30, 5, number_format(preg_replace("/[^0-9.]/", "", $totInq5)), 'T', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", 'T', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, toDisp($totalPrice), 'T', 'R', 0, 0, '', '', true);//number_format(preg_replace("/[^0-9.]/", "", $q))
		$pdf->MultiCell(2, 5, "", 'T', 'L', 0, 1, '', '', true);

		$pdf->SetXY(17, 132);
		$pdf->SetFont('helvetica', 'B', 9);
		// $pdf->MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
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

		$pdf->SetFont('helvetica', '', 9);

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



		if (false) {//$sql1->num_rows
		    $pdf->AddPage();
		    $pdf->Ln(20);
		    $pdf->SetFont('helvetica', '', 12);
		    $pdf->MultiCell(0, 5, "Details of Inquiries", 0, 'C', 0, 0, '', '', true);

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
		    $pdf->MultiCell(30, 4, toDisp($advance_payment), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, toDisp($totRepAdj), 0, 'R', 0, 0, '', '', true);
		    // $pdf->SetTextColor(255, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp($totConAdj), 0, 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, ' -', 0, 'R', 0, 0, '', '', true);//number_format(toNumber($totRepAdj) - toNumber($totConAdj), 2, '.', ',')
		    $pdf->MultiCell(26, 4, toDisp($detBal), 0, 'R', 0, 1, '', '', true);

		    

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);

		    $pdf->MultiCell(25, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Replenishment / (Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Consumption/( Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, "Balance", 0, 'R', 0, 1, '', '', true);

		    $cap = $r7['deposit'];
		    $pdf->MultiCell(25, 4, 'Beg. Balance', 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, toDisp($advance_payment), 0, 'R', 0, 1, '', '', true);
		    $pdf->SetFont('helvetica', '', 9);

		    
		    foreach ($inqs2 as $key => $value) {
		        $pdf->MultiCell(25, 4, $value['date'], 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(30, 4, $value['particular'], 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(30, 4, $value['inq'], 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(35, 4, $value['repAdj'], 0, 'R', 0, 0, '', '', true);
		        $pdf->MultiCell(35, 4, $value['conAdj'], 0, 'R', 0, 0, '', '', true);
		        $pdf->MultiCell(26, 4, $value['detBal'], 0, 'R', 0, 1, '', '', true);
		    }

		    
		    $pdf->MultiCell(25, 4, "Total", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    // $pdf->SetTextColor(255, 0, 0);
		    $pdf->MultiCell(30, 4, number_format(preg_replace("/[^0-9.]/", "", $totInq5)), 'TB', 'C', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp($totRepAdj), 'TB', 'R', 0, 0, '', '', true);
		    // $pdf->SetTextColor(255, 0, 0);
		    $pdf->MultiCell(35, 4, toDisp('312,970.00'), 'TB', 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(26, 4, toDisp(toNumber($r7['deposit']) - toNumber($totInqPrice5)), 'TB', 'R', 0, 1, '', '', true);
		}


		$ctr = 0;
		// $inqcost = [0, 55, 10, 10];

		foreach ($list3 as $key => $value) {
		    if ($key == $provcode) {
		        continue;
		    }
		    $ctr++;
		    $inqcost = ($key == $provcode) ? 55: 10;
		    $pdf->AddPage();

		    $pdf->Ln(20);
		    $pdf->SetFont('helvetica', '', 12);
		    $pdf->MultiCell(0, 5, ($key == $provcode) ? 'Consumer Inquiries for '.$comp[$key]['name']: 'Inquiries for '.$comp[$key]['name'], 0, 'C', 0, 0, '', '', true);


		    $pdf->Ln(10);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 0, '', '', true);

		    $pdf->Ln(2);
		    $pdf->SetFont('helvetica', '', 9);

		    $pdf->MultiCell(40, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(45, 4, "Amount", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(15, 4, "", 0, 'C', 0, 1, '', '', true);

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);
		     
		    
		    $totAmt = 0;
		    foreach ($value as $key2 => $value2) {
		        if ($value2['inquiries']) {
		            $totAmt += toNumber($value2['cost']);
		            $pdf->MultiCell(40, 4, $key2, 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(40, 4, (isset($value2['inquiries'])) ? 'Inquiries':'', 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(40, 4, (isset($value2['inquiries'])) ? number_format($value2['inquiries']):'', 0, 'C', 0, 0, '', '', true);
		            $pdf->MultiCell(45, 4, (isset($value2['cost'])) ? toDisp($value2['cost']):'', 0, 'R', 0, 0, '', '', true);
		            $pdf->MultiCell(15, 4, '', 0, 'C', 0, 1, '', '', true);
		        }
		        
		    }

		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(40, 4, "", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, "Total", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(40, 4, number_format($comp[$key]['inquiries']), 'TB', 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(45, 4, "PHP ".toDisp(($totAmt) ? $totAmt: '00'), 'TB', 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(15, 4, '', 'TB', 'C', 0, 1, '', '', true);

		    $pdf->Ln(10);
		    $pdf->SetFont('helvetica', '', 9);
		    $pdf->MultiCell(20, 4, "Inquiry Cost: ", 0, 'L', 0, 0, '', '', true);
		    $pdf->SetFont('helvetica', 'B', 9);
		    $pdf->MultiCell(20, 4, "PHP ".toDisp($inqcost), 0, 'L', 0, 1, '', '', true);
		}

		$pdf->AddPage();
		$pdf->SetY(40);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(0, 5, "Please note that you will be receiving system generated notification from the CIC at various consumption levels, to wit:", 0, 'L', 0, 1, '', '', true);


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
  * Amount: “Consumable Advance Payment Required”-minimum amount is equivalent to 1,000 credit reports. The introductory  price of Php 10 per access shall run until March 31, 2020. The CIC shall thereafter release Circulars for any price changes.
  </td>
 </tr>
</table>
EOD;


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

		// move pointer to last page
		$pdf->lastPage();

		// ---------------------------------------------------------
		$pass = substr($r4['fld_accountno'], -4).$bcpp_row['bill_contact_lname'];
		$pdf->SetProtection(array('modify'),str_replace('-','',$pass) );

		//Close and output PDF document
		$pdf->Output('../pdf/SOC'.$provcode.date("Ym", strtotime($dateLastMonth)).'.pdf', 'F');

	    echo 'SAE '.$provcode." Pass- ".$pass;





	}elseif(substr($provcode, 0, 3) != 'SAE'){

		$sql_bcpp = $dbh4->query("SELECT 
		AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_fname,
		AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_mname,
		AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_lname,
		AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_sname,
		AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS bill_contact_email
		FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
		$bcpp_row = $sql_bcpp->fetch_array();


		$list=array();
		$list2=array();

		for($d=1; $d<=$days; $d++)
		{    
		  $list[$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT)]=0;
		  $list2[$year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-'.str_pad($d, 2, '0', STR_PAD_LEFT)]=0;
		}


		$sql=$dbh->query("SELECT b.fld_advance_payment, b.fld_advance_payment_current, b.fld_access_limit, b.fld_access_limit_current, b.fld_accountno, f.fld_name FROM tbbilling b JOIN tbfininst f ON b.fld_provcode = f.fld_code WHERE b.fld_provcode = '".$provcode."'");
		$r = $sql->fetch_array();

		$sql7=$dbh->query("SELECT sum(fld_amount) AS deposit FROM tbbillingpayment WHERE fld_acct_no = '".$r['fld_accountno']."' AND fld_charge_back = 0");
		$r7 = $sql7->fetch_array();

		$sql9=$dbh->query("SELECT fld_payment_date, fld_amount FROM tbbillingpayment WHERE YEAR(fld_payment_date) = '".$year."' AND MONTH(fld_payment_date) = '".$month."' AND fld_acct_no = '".$r['fld_accountno']."'");

		$bcpp=$dbh4->query("SELECT AES_DECRYPT(fld_bill_contact_fname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fname,
		                    AES_DECRYPT(fld_bill_contact_mname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS mname,
		                    AES_DECRYPT(fld_bill_contact_lname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS lname,
		                    AES_DECRYPT(fld_bill_contact_sname, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS sname,
		                    AES_DECRYPT(fld_bill_contact_email, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS email
		                    FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$provcode."'");
		$rbcpp = $bcpp->fetch_array();

		// echo "<pre/>";
		// print_r($bcpp->fetch_array());
		// exit;


		$advance_payment = $r7['deposit'];
		$advance_payment_current = $r['fld_advance_payment_current'];
		$access_limit = $r['fld_access_limit'];
		$access_limit_current = $r['fld_access_limit_current'];
		$accountno = $r['fld_accountno'];
		$sename = $r['fld_name'];

		$sql=$dbh4->query("SELECT 
		     AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address, 
		    AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number,
		    AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street, 
		    AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv,
		    fld_zip AS zip
		    FROM tbentities WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '$provcode'");
		$r = $sql->fetch_array();




		$bgy = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$r['fld_address']."'");
		$b = $bgy->fetch_array();
		$cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($r['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
		$c = $cty->fetch_array();
		$prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($r['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
		$p = $prv->fetch_array();

		$addr1 = '';
		if (!empty($r['addr_number'])) $addr1 .= trim($r['addr_number']).', ';
		if (!empty($r['addr_street'])) $addr1 .= trim($r['addr_street']).', ';
		if (!empty($r['addr_subdv'])) $addr1 .= trim($r['addr_subdv']).', ';
		if (!empty($b['fld_geotitle'])) $addr1 .= $b['fld_geotitle'].', ';

		$addr2 = '';
		if (!empty($c['fld_geotitle'])) $addr2 .= $c['fld_geotitle'].', ';
		if (!empty($p['fld_geotitle'])) $addr2 .= $p['fld_geotitle'].' ';
		if (!empty($r['zip'])) $addr2 .= $r['zip'];



		$sql1=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS inq_price, f.fld_accountno, f.fld_access_limit, f.fld_access_limit_current FROM tbinquiriesdaytemp i JOIN tbbilling f ON i.fld_provcode = f.fld_provcode WHERE i.fld_provcode = '$provcode' AND YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND i.fld_branchcode = 'USERS' AND ((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL')) GROUP BY DAY(i.fld_inqdate)");


		$sql3=$dbh->query("SELECT i.fld_provcode, sum(i.fld_inqcount) AS inq, sum(i.fld_inq_price) AS inq_price, f.fld_accountno, f.fld_access_limit, f.fld_access_limit_current, i.fld_sourcecode 
		FROM tbinquiriesdaytemp i JOIN tbbilling f ON i.fld_provcode = f.fld_provcode 
		    WHERE i.fld_provcode = '$provcode' 
		    AND YEAR(i.fld_inqdate) = '".$year."' 
		    AND MONTH(i.fld_inqdate) = '".$month."' 
		    AND i.fld_branchcode = 'USERS' 
		    AND (i.fld_sourcecode = 'CB_NAE') AND i.fld_inqresult = '0'");
		$nae = $sql3->fetch_array();



		// echo "<pre/>";
		// print_r($sql1->fetch_array());
		// exit;
		$sql2=$dbh->query("SELECT i.fld_provcode, i.fld_inqdate AS day, f.fld_name, sum(i.fld_inqcount) AS inq, f.fld_accountno, f.fld_datasubjectid, f.fld_datasubjectbd, f.fld_limit FROM tbinquiriesdaytemp i JOIN tbfininst f ON i.fld_provcode = f.fld_code "
		  ."WHERE i.fld_provcode = '$provcode' AND YEAR(i.fld_inqdate) = '".$year."' AND MONTH(i.fld_inqdate) = '".$month."' AND (i.fld_provcode = 'SAE09670' OR i.fld_branchcode = 'SCIBI' OR (i.fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CIB')) AND "
		  ."((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR "
		  ."(i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%'))"
		  ."GROUP BY DAY(i.fld_inqdate)");



		$inq_price = 0;
		$totInq1 = 0;
		while ($r = $sql1->fetch_array()) {
		    $totInq1 += $r['inq'];
		    $inq_price = $inq_price + $r['inq_price'];
		    $list[$r['day']] = ["inquiries" => $r['inq'], "price" => $r['inq_price']];
		}


		$cap = toNumber($advance_payment_current) + toNumber($inq_price);

		$access_limit_current = $totInq1;

		if ($sql2->num_rows) {
		    $totInq = 0;
		    while ($r2 = $sql2->fetch_array()) {
		        $totInq += $r2['inq'];
		        $list2[$r2['day']] = ["inquiries" => $r2['inq']];
		    }
		    $access_limit_current += $totInq;
		    
		}

		$sql10=$dbh->query("SELECT fld_balance FROM tbbillingbalance WHERE fld_provcode = '".$provcode."' AND YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."'");
		// echo "SELECT fld_balance FROM tbbillingbalance WHERE fld_provcode = '".$provcode."' AND YEAR(fld_date) = '".$year."' AND MONTH(fld_date) = '".$month."'";exit;
		$r10 = $sql10->fetch_array();
		$begBal = $r10['fld_balance'];
		// $r10['fld_balance'] = '9880';
		// echo "<pre/>";
		// print_r($r10);
		// exit;

		// $sql7=$dbh->query("SELECT sum(fld_amount) AS deposit FROM tbbillingpayment WHERE fld_acct_no = '".$r4['fld_accountno']."'");
		// $r7 = $sql7->fetch_array();

		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {

		    //Page header
		    public function Header() {
		        // Logo
		        // $image_file = K_PATH_IMAGES.'logo_example.jpg';
		        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
		        $this->Image('../images/CIClogo3.png', 25, 10, 35, 25, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
		        // Set font
		        $this->SetFont('helvetica', 'B', 20);
		        // Title
		        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		        $this->Ln(15);
		        $this->SetFont('helvetica', 'B', 11);
		        // $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		        $this->MultiCell(0, 5, "CREDIT INFORMATION CORPORATION", 0, 'C', 0, 1, '', '', true);
		        $this->SetFont('helvetica', '', 11);
		        $this->MultiCell(0, 5, "Statement of Consumption (SOC)", 0, 'C', 0, 1, '', '', true);




		        // Get the current page break margin
		        $bMargin = $this->getBreakMargin();

		        // Get current auto-page-break mode
		        $auto_page_break = $this->AutoPageBreak;

		        // Disable auto-page-break
		        $this->SetAutoPageBreak(false, 0);
		        // set alpha to semi-transparency
		        $this->SetAlpha(0.2);
		        // Define the path to the image that you want to use as watermark.
		        $img_file = '../images/CIClogo3.png';
		        // set alpha to semi-transparency
		        

		        // Render the image
		        $this->Image($img_file, 2, 40, 200, 150, '', '', '', false, 300, '', false, false, 0);
		        $this->SetAlpha(1);
		        // Restore the auto-page-break status
		        $this->SetAutoPageBreak($auto_page_break, $bMargin);

		        // Set the starting point for the page content
		        $this->setPageMark();
		    }

		    // Page footer
		    public function Footer() {
		        // Position at 15 mm from bottom
		        $this->SetY(-25);
		        // Set font
		        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
		        $this->SetFont('helvetica', 'I', 7);
		        $this->Cell(0, 10, '© 2019 Credit Information Corporation. 6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City.', 'T', true, 'C', 0, '', 0, false, 'T', 'M');

		        $this->SetFont('helvetica', 'I', 8);
		        // Page number
		        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');

		        $this->SetY(-30);
		        $this->SetFont('helvetica', 'I', 9);
		        $this->MultiCell(0, 5, "This is a system generated Statement of Consumption. Signature is not required.", 0, 'C', 0, 1, '', '', true);
		    }

		    function SetDash($black=null, $white=null)
		    {
		        if($black!==null)
		            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
		        else
		            $s='[] 0 d';
		        $this->_out($s);
		    }


		}


		// create new PDF document
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new MYPDF('P','mm',array(210, 297));//355.6

		/*$words = explode(" ", $rbcpp['fname']." ".$rbcpp['mname'].$rbcpp['sname']);
		$acronym = "";

		foreach ($words as $w) {
		  $acronym .= $w[0];
		}*/





		$pdf->SetMargins(15, 20, 15);
		// remove default header/footer
		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(TRUE, 0);
		// add a page
		$pdf->AddPage();

		$pdf->Ln(17);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(30, 5, "Company Name:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(70, 5, $sename, 0, 'L', 0, 1, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(30, 5, "Company Address:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(70, 5, $addr1, 0, 'L', 0, 1, '', '', true);
		$pdf->MultiCell(30, 5, "", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(70, 5, $addr2, 0, 'L', 0, 1, '', '', true);

		$pdf->SetXY(120, 37);
		$pdf->MultiCell(32, 5, "Account Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $accountno, 0, 'R', 0, 1, '', '', true);
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Number:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(43, 5, $statementNo, 0, 'R', 0, 1, '', '', true);//strtotime(date('Y-m-d H:m:s'))
		$pdf->SetX(120);
		$pdf->MultiCell(32, 5, "Statement  Period:", 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(43, 5, date("F j", strtotime($dateLastMonth))." - ".date("t, Y", strtotime($dateLastMonth)), 0, 'R', 0, 1, '', '', true);
		$pdf->SetFont('helvetica', '', 9);

		


		/*$pdf->SetXY(17, 60);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(70, 4, "Statement Date:", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(68, 4, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(38, 4, date("F j, Y", strtotime("first day of this month")), 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 4, "", '', 'L', 0, 1, '', '', true);*/

		$pdf->SetXY(17, 70);
		$pdf->MultiCell(70, 4, "Beginning Balance", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(68, 4, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 4, "PHP", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 4, toDisp(($r10['fld_balance']) ? $r10['fld_balance']: '00'), '', 'R', 0, 1, '', '', true);


		$replenishment = [];
		$text = "Advance Payment";
		$php = 'PHP';
		$totAdvPay = 0;
		$arrAdvPay = [];
		$ctr = 0;
		while ($r9 = $sql9->fetch_array()) {
		    $totAdvPay += $r9['fld_amount'];
		    $arrAdvPay[$ctr] = ["amount" => $r9['fld_amount'], "date" => $r9['fld_payment_date']];
		    $ctr++;
		    if (!$r10['fld_balance']) {
		        $r10['fld_balance'] = $r9['fld_amount'];
		    }else{
		        $replenishment[date("Y-m-d", strtotime($r9['fld_payment_date']))][] = $r9['fld_amount'];
		        $replenishmentTotal += $r9['fld_amount'];
		    }
		}




		$ctrAdv = 1;
		$totAdvPayDisp = "";
		$marginB = 0;
		$countarrAdvPay = count($arrAdvPay);
		foreach ($arrAdvPay as $key => $value) {
		    if ($countarrAdvPay == $ctrAdv) {
		        $totAdvPayDisp = toDisp($totAdvPay);
		        $marginB = 'B';
		    }
		    $pdf->SetX(17);
		    $pdf->MultiCell(65, 4, $text, 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, date('m/d/Y', strtotime($value['date'])), '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, $php." ".toDisp($value['amount']), $marginB, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(8, 4, '', '', 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(10, 4, "", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(28, 4, $totAdvPayDisp, 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(2, 4, "", 0, 'L', 0, 1, '', '', true);
		    $text = $php = '';
		    $ctrAdv++;
		}


		$pdf->SetX(17);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->MultiCell(100, 5, "Number of Inquiries", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(30, 5, number_format(preg_replace("/[^0-9.]/", "", $totInq1))." @ 10.00", 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "", 'B', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, ($inq_price) ? "(".toDisp($inq_price).")": "(0.00)", 'B', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(2, 5, "", 'B', 'L', 0, 1, '', '', true);


		$pdf->Line(15, 68, 15, 90);
		$pdf->Line(195, 68, 195, 90);
		$pdf->Line(15, 68, 195, 68);
		$pdf->Line(15, 90, 195, 90);


		$pdf->Line(15, 90, 15, 114);
		$pdf->Line(195, 90, 195, 114);
		// $pdf->Line(15, 106, 195, 106);
		$pdf->Line(15, 114, 195, 114);


		$totalAvailBal = toDisp(toNumber($totAdvPay + $begBal) - toNumber($inq_price));
		$pdf->SetX(17);
		$pdf->SetFont('helvetica', 'B', 9);
		$pdf->MultiCell(100, 5, "Total Available Balance", 0, 'L', 0, 0, '', '', true);
		$pdf->MultiCell(30, 5, '', 'T', 'R', 0, 0, '', '', true);
		$pdf->MultiCell(8, 5, '', 0, 'R', 0, 0, '', '', true);
		$pdf->MultiCell(10, 5, "PHP", '', 'L', 0, 0, '', '', true);
		$pdf->MultiCell(28, 5, $totalAvailBal, '', 'R', 0, 0, '', '', true);//$r10['fld_balance']
		$pdf->MultiCell(2, 5, "", '', 'L', 0, 1, '', '', true);

		$style = array(
		    'fgcolor' => array(0,0,0),
		    'bgcolor' => false, //array(255,255,255)
		    'module_width' => 1, // width of a single module in points
		    'module_height' => 1 // height of a single module in points
		);
		$pdf->write2DBarcode(md5($provcode."|".date("Ym")), 'QRCODE,Q', 173, 14, 20, 20, $style, 'N');



		$pdf->SetXY(17, 93);
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
		  * Amount: “Consumable Advance Payment Required”-minimum amount is equivalent to 1,000 credit reports. The introductory  price of Php 10 per access shall run until March 31, 2020. The CIC shall thereafter release Circulars for any price changes.
		  </td>
		 </tr>
		</table>
EOD;

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


		$pdf->SetFont('helvetica', '', 9);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Ln(2);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->MultiCell(0, 5, "***END OF STATEMENT***", 0, 'C', 0, 1, '', '', true);




		if ($sql1->num_rows) {
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
		    $pdf->MultiCell(30, 4, toDisp($r10['fld_balance']), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, toDisp($replenishmentTotal), 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, toDisp($inq_price), 0, 'R', 0, 0, '', '', true);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(35, 4, " -   ", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, toDisp(toNumber($totAdvPay + $begBal) - toNumber($inq_price)), 0, 'R', 0, 1, '', '', true);

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 1, '', '', true);

		    $pdf->MultiCell(25, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "Quantity", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Replenishment / (Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "Consumption/( Adj)", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, "Balance", 0, 'R', 0, 1, '', '', true);

		    $cap = $r10['fld_balance'];
		    $pdf->MultiCell(25, 4, 'Beg. Balance', 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(30, 4, "", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(35, 4, "", 0, 'R', 0, 0, '', '', true);
		    $pdf->MultiCell(26, 4, toDisp($r10['fld_balance']), 0, 'R', 0, 1, '', '', true);
		    $pdf->SetFont('helvetica', '', 9);

		    $totCons = 0;
		    $totInqs = 0;
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
		    $pdf->MultiCell(20, 4, "PHP ".toDisp(10), 0, 'L', 0, 1, '', '', true);
		}





		if ($sql2->num_rows) {

		    $pdf->AddPage();

		    $pdf->Ln(20);
		    $pdf->SetFont('helvetica', '', 12);
		    $pdf->MultiCell(0, 5, "Details of Special Accessing Entity Inquiries", 0, 'C', 0, 0, '', '', true);


		    $pdf->Ln(10);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 0, '', '', true);

		    $pdf->Ln(2);
		    $pdf->SetFont('helvetica', '', 9);

		    $pdf->MultiCell(60, 4, "Date", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(60, 4, "Particulars", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(60, 4, "Quantity", 0, 'C', 0, 1, '', '', true);

		    $pdf->Ln(2);
		    $pdf->MultiCell(0, 5, "", 'T', 'L', 0, 0, '', '', true);
		     
		    foreach ($list2 as $key => $value) {
		        $pdf->MultiCell(60, 4, $key, 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(60, 4, (isset($value['inquiries'])) ? 'Inquiries':'', 0, 'C', 0, 0, '', '', true);
		        $pdf->MultiCell(60, 4, ($value['inquiries']) ? number_format($value['inquiries']):'', 0, 'C', 0, 1, '', '', true);
		    }

		    $pdf->MultiCell(60, 4, "", 0, 'L', 0, 0, '', '', true);
		    $pdf->MultiCell(60, 4, "Total", 0, 'C', 0, 0, '', '', true);
		    $pdf->MultiCell(60, 4, number_format($totInq), 'TB', 'C', 0, 0, '', '', true);

		    $pdf->Ln(20);
		    $pdf->SetTextColor(0, 0, 0);
		    $pdf->MultiCell(0, 5, "***END OF STATEMENT***", 0, 'C', 0, 1, '', '', true);
		}


		// move pointer to last page
		$pdf->lastPage();
		// ---------------------------------------------------------

		$pass = substr($accountno, -4).strtoupper(str_replace(' ', '', str_replace('-', '', $bcpp_row['bill_contact_lname'])));
		$pdf->SetProtection(array('modify'), $pass);

		//Close and output PDF document
		$pdf->Output('../pdf/SOC'.$provcode.date("Ym", strtotime($dateLastMonth)).'.pdf', 'F');
		// $pdf->Output('../pdf/SOC'.date("MY", strtotime($dateLastMonth)).'-'.$sename.'-'.$provcode.'.pdf', 'F');
	    echo 'AE '.$provcode." Pass- ".$pass;
	}

	include '../PHPMailer/socmailer.php';
	$sql_next = $dbh->query("INSERT INTO `cicportal`.`tbbillingbalance` (`fld_id`, `fld_provcode`, `fld_balance`, `fld_stmt_id`, `fld_emailsent`, `fld_date`) VALUES (NULL, '".$provcode."', '".str_replace( ',', '', $totalAvailBal)."', 0, NULL, '".date("Y-m-d", strtotime($dateLastMonth.' next month'))."');");
	
}else{
	echo 'exited';
}



function toNumber($val){
    if (empty($val)) { $val = 0;}
    return (float)preg_replace("/[^0-9.]/", "", $val);
}
function toDisp($val){
    if (empty($val)) { return '';}//- 
    return number_format(preg_replace("/[^0-9.]/", "", $val), 2, '.', ',');
}

?>
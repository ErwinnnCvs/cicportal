<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../../TCPDF-main/tcpdf.php');
require_once('../../config.php');
ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$logo = dirname(__FILE__).'/CICLogo.png';

class MYPDF extends TCPDF {

    public function Header() {
        // Logo
        $this->Image(dirname(__FILE__).'/../../images/letterhead_new.png', 20, 23, 170, 20, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Ln(15);
        $this->SetFont('helvetica', '', 11);
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $this->SetAlpha(0.2);
        // Define the path to the image that you want to use as watermark.
        $img_file = '../assets/images/CIClogo3.png';
        $this->Image($img_file, 2, 40, 200, 150, '', '', '', false, 300, '', false, false, 0);
        $this->SetAlpha(1);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }

    public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-25);
        $this->SetFont('helvetica', 'I', 7);
        $this->Cell(0, 10, '© 2020 Credit Information Corporation. 6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City.', 'T', true, 'C', 0, '', 0, false, 'T', 'M');
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


$dispute_id = $_GET['dispute'];
$getDispute = $dbh2->query("SELECT * from contract where fld_id > 9434 and fld_id = '".$dispute_id."'  LIMIT 1");
$gd = $getDispute->fetch_array();


$trn = $gd['fld_TRN'];
$status = $gd['fld_status'];
$id = $gd['fld_id'];
$provCode = $gd['fld_prov'];
$drcpUpdateTs = $gd['fld_dispute_verification_ts'];
$subjectNo = $gd['fld_provsubj_number'];
$disputeRemarks = $gd['fld_dispute_remarks'];
$recordCount = $gd['fld_record_count'];

if($gd['fld_dispute_remarks'] == NULL || empty($gd['fld_dispute_remarks']) || $gd['fld_dispute_remarks'] == " "){
    $disputeRemarks = 'N/A';
}else{
    $disputeRemarks = $gd['fld_dispute_remarks'];
}

$drtRemarks = $gd['fld_subjcode_remarks'];

$drtRemarks = str_replace("<hr><small>", "",$drtRemarks);
$drtRemarks = str_replace("</small><br><b>", "|",$drtRemarks);
$drtRemarks = str_replace("</b><br>", "|",$drtRemarks);
$drtRemarks = str_replace("<br>", "|",$drtRemarks);

$drtRemarks = str_replace("<b>", "" ,$drtRemarks);
$drtRemarks = str_replace("</b>", "" ,$drtRemarks);
$drtRemarks = str_replace("CIC - Dispute Resolution Representative", "CIC - Dispute Resolution Representative" ,$drtRemarks);

$remarksArray = explode("|", $drtRemarks);

$countRemarks = count($remarksArray);



$isMultiplePCN = 0;
$isMultipleFile = 0;

//Check if Multiple Provider Contract No
if($gd['is_multiple_pcn'] == 1){
    $contractNo = explode("|", $gd['fld_provcontr_number']);
    $isMultiplePCN = 1;
}else{

    $contractNo = explode(" ", $gd['fld_provcontr_number']);

    // //Check if PCN have whitespace
    // if(strpos($gd['fld_provcontr_number'], " ")){
    //     $contractNo = explode(" ", $gd['fld_provcontr_number']);
    //     $isMultiplePCN = 1;
    // }else{
    //     $contractNo = $gd['fld_provcontr_number'];
    // }   
}


//Check if Multiple Provider Contract No
if($gd['is_multiple_filename'] == 1){
    $fileName = explode("|", $gd['fld_filename']);
    $isMultipleFile = 1;
}else{

    //Check if PCN have whitespace
    if(strpos($gd['fld_filename'], " ")){
        $fileName = explode(" ", $gd['fld_filename']);
        $isMultipleFile = 1;
    }else{
        $fileName = $gd['fld_filename'];
    }   
}

$ct = 1;



$fieCount = $gd['fld_record_count'];
$fileName = $gd['fld_filename'];
$drcpRemarks = $gd['fld_dispute_remarks'];


$submissionDate = date("F d, Y H:i a",strtotime($gd['fld_se_dispdetails_ts']));


$subject = $dbh2->query("SELECT fld_TRN, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject WHERE fld_TRN = '".$gd['fld_TRN']."' ORDER BY subjcode ASC");
$s=$subject->fetch_array();

$disputerName = $s['firstname']. " " .$s['middlename']. " " .$s['lastname'];

$disputeDateFiled = date("F d, Y H:i a",strtotime($s['fld_DateFilled']));
$birthDate = date("F d, Y", strtotime( $s['fld_Birthday']));

$appointment = $dbh5->query("SELECT AES_decrypt(fld_email,CONCAT(fld_refID, 'RA9510')) as email FROM tblappointment where fld_refID = '".$gd['fld_TRN']."' ");
$a = $appointment->fetch_array();

if ($gd['fld_status'] == 4) {
  $sename = $gd['fld_name'];
} else {
  $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gd['fld_prov']."'");
  $gcn=$get_company_name->fetch_array();
  $sename = $gcn['name'];
}

if(!$submissionDate){
    $submissionDateC = "N/A";
    $submissionTimeC = "N/A";
} else {
    $submissionDateC = date("Y-m-d",strtotime($gd['fld_se_dispdetails_ts']));
    $submissionTimeC = date("H:i:s",strtotime($gd['fld_se_dispdetails_ts']));
}

$pdf = new MYPDF('P','mm',array(210, 297));
$pdf->SetMargins(15, 20, 15);
$pdf->CustomHeaderText = "Statement of Aggregated Consumption (SOAC)";
$pdf->CustomFooterText = "This is a system generated report. For any inquiries, please send an email to dispute@creditinfo.gov.ph";
$pdf->SetAutoPageBreak(TRUE, 15);


$pdf->AddPage();
$pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
$pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
$pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));

$pdf->Ln(30);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->MultiCell(0, 5, "DISPUTE RESOLUTION  ", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(5);

// Multicell test

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(120, 7, 'Disputer Name', 1, 'C', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(60, 7, 'Filing Date', 1, 'C', 1, 0, '', '', false, 0, false, true, 0);

$pdf->Ln();
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(120, 7, $disputerName, 1, 'C', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(60, 7, $disputeDateFiled, 1, 'C', 1, 0, '', '', false, 0, false, true, 0);

$pdf->Ln();
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(120, 7, 'Institution Name', 1, 'C', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(60, 7, 'Transaction Reference No.', 1, 'C', 1, 0, '', '', false, 0, false, true, 0);
$pdf->Ln();
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(120, 7, $sename, 1, 'C', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(60, 7, $trn, 1, 'C', 1, 0, '', '', false, 0, false, true, 0);


//MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->Ln(16);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell($pdf->getPageWidth() - 30, 7, 'DISPUTE LOGS', 0, 'C', 1, 0, '', '', false, 0, false, true, 0);
$pdf->Ln(4);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell($pdf->getPageWidth() - 30, 7, 'This report was generated on '.date("d F Y")." at ".date("H:i:s"), 0, 'C', 1, 0, '', '', false, 0, false, true, 0);


$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, 'SE Submission Date:', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(50, 7, $submissionDateC, 0, 'L', 1, 0, '', '', false, 0, false, true, 0);

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, 'SE Submission Time:', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(50, 7, $submissionTimeC, 0, 'L', 1, 0, '', '', false, 0, false, true, 0);


$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, 'Dispute Submission Details:', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(50, 7, 'Provider Contract Number:', 0, 'L', 1, 0, '', '', false, 0, false, true, 0);

$pdf->Ln(4);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, '', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);

foreach($contractNo as $contr){
    if ($pdf->GetY() > 260) $pdf->AddPage();
    $pdf->MultiCell(50, 7, trim($contr), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
}

$pdf->Ln(4);
$pdf->MultiCell(80, 7, '', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(50, 7, 'Provider Subject Number:', 0, 'L', 1, 0, '', '', false, 0, false, true, 0);

$pdf->Ln(4);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, '', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(50, 7, $subjectNo, 0, 'L', 1, 0, '', '', false, 0, false, true, 0);

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, 'DRCP Remarks:', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);

$count_disp_remarks = strlen($gd['fld_dispute_remarks']);
$divcount = $count_disp_remarks/53;


$pdf->SetFont('helvetica', '', 10);
if($count_disp_remarks >= 53 && round($divcount) <= 2){
    $firstfiftythree = substr($gd['fld_dispute_remarks'], 0, 53);
    $laststrings = substr($gd['fld_dispute_remarks'], 53, $count_disp_remarks);
    $pdf->MultiCell(100, 7, trim($firstfiftythree), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
    $pdf->Ln(4);
    $pdf->MultiCell(80, 7, '', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
    $pdf->MultiCell(100, 7, trim($laststrings), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
} elseif($count_disp_remarks >= 53 && round($divcount) >= 2) {
    for($i=1; $i<= round($divcount); $i++){
        $pdf->Ln(4);
        $pdf->MultiCell(80, 7, '', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
        $pdf->MultiCell(100, 7, substr($gd['fld_dispute_remarks'], 0, ($i*53)), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
    }
} else {
    $pdf->MultiCell(100, 7, $gd['fld_dispute_remarks'], 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
}



$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, 'DRT Verification Date:', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(50, 7, date("Y-m-d", strtotime($drcpUpdateTs)), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, 'DRT Verification Time:', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
$pdf->MultiCell(50, 7, date("H:i:s", strtotime($drcpUpdateTs)), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);


$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(80, 7, 'DRT Remarks:', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);

$count_disp_remarks = strlen($drtRemarks);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
if($count_disp_remarks >= 53){
    $firstfiftythree = substr($drtRemarks, 0, 53);
    $laststrings = substr($drtRemarks, 53, $count_disp_remarks);
    $pdf->MultiCell(100, 7, trim($firstfiftythree), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
    $pdf->Ln(4);
    $pdf->MultiCell(80, 7, '', 0, 'R', 1, 0, '', '', false, 0, false, true, 0);
    $pdf->MultiCell(100, 7, trim($laststrings), 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
} else {
    $pdf->MultiCell(100, 7, $drtRemarks, 0, 'L', 1, 0, '', '', false, 0, false, true, 0);
}



// $ctr = 1;


// $pdf = new MYPDF('P','mm',array(210, 297));

// $pdf->SetAutoPageBreak(TRUE, 0);
// $pdf->setPrintFooter(true);
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


// $pdf->AddPage();

// $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
// $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
// $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));


// $pdf->Ln(35);
// $pdf->SetFont('helvetica', 'B', 11);
// $pdf->MultiCell(0, 5, "DISPUTE RESOLUTION  ", 0, 'C', 0, 1, '', '', true);

// $pdf->Ln(3);


// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->SetX(15);
// $pdf->MultiCell(120, 7, 'Disputer Name', 'TBR', 0, 'C', 0, '', 0, false, 'T', 'M');

// // $pdf->SetX(80);
// // $pdf->SetFont('helvetica', 'B', 9);
// // $pdf->MultiCell(60, 7, 'Birthdate', 'TBR', 0, 'C', 0, '', 0, false, 'T', 'M');

// $pdf->SetX(135);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(60, 7, 'Filing Date', 'TB', 1, 'C', 0, '', 0, false, 'T', 'M');

// $pdf->SetX(15);
// $pdf->SetFont('helvetica', '', 9);
// $pdf->MultiCell(120, 7, $disputerName, 'R', 0, 'C', 0, '', 0, false, 'T', 'M');

// // $pdf->SetX(80);
// // $pdf->SetFont('helvetica', '', 9);
// // $pdf->MultiCell(60, 7, $birthDate, 'R', 0, 'C', 0, '', 0, false, 'T', 'M');


// $pdf->SetX(135);
// $pdf->SetFont('helvetica', '', 9);
// $pdf->MultiCell(60, 7, $disputeDateFiled, 0, 1, 'C', 0, '', 0, false, 'T', 'M');


// //START DEPT
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->SetX(15);
// $pdf->MultiCell(65, 7, 'Transaction Reference No.', 'TBR', 0, 'C', 0, '', 0, false, 'T', 'M');

// $pdf->SetX(80);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(115, 7, 'Financial Institution', 'TB', 1, 'C', 0, '', 0, false, 'T', 'M');


// $pdf->SetX(15);
// $pdf->SetFont('helvetica', '', 9);
// $pdf->MultiCell(65, 7, $trn, 'BR', 0, 'C', 0, '', 0, false, 'T', 'M');

// $pdf->SetX(80);
// $pdf->SetFont('helvetica', '', 9);
// $pdf->MultiCell(115, 7, $sename, 'B', 1, 'C', 0, '', 0, false, 'T', 'M');


// $pdf->SetXY(60, 90);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(8, 5, '', 0, 0, 'C', 0, '', 0, false, 'T', 'M');
// $pdf->MultiCell(70, 5, 'DISPUTE LOGS:', 0, 0, 'C', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY(68, 95);
// $pdf->SetFont('helvetica', 'I', 9);
// $pdf->MultiCell(70, 5, "This report was generated on ".date("d F Y")." at ".date("H:i:s").".", 0, 0, 'C', 0, '', 0, false, 'T', 'M');

// // $pdf->resetColumns();
// // $pdf->setEqualColumns(2, 84);  // KEY PART -  number of cols and width
// // $pdf->selectColumn();
// // dispute details start

// if(!$submissionDate){
//     $submissionDateC = "N/A";
//     $submissionTimeC = "N/A";
// } else {
//     $submissionDateC = date("Y-m-d",strtotime($gd['fld_se_dispdetails_ts']));
//     $submissionTimeC = date("H:i:s",strtotime($gd['fld_se_dispdetails_ts']));
// }

// $pdf->SetXY(15, 107);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, 'SE Submission Date:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY(90, 107);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, $submissionDateC, 0, 0, 'L', 0, '', 0, false, 'T', 'M');


// $pdf->SetXY(15, 117);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, 'SE Submission Time:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY(90, 117);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, $submissionTimeC, 0, 0, 'L', 0, '', 0, false, 'T', 'M');


// $pdf->SetXY(15, 127);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, 'Dispute Submission Details:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY(90, 127);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, "Provider Contract Number:", 0, 0, 'L', 0, '', 0, false, 'T', 'M');


// $ctrCont = 1;
// $yAxis = 126;
// $xAxis = 80;



// if($isMultiplePCN == 1){
//     foreach($contractNo as $cont){

//         if($xAxis == 80 && $yAxis < 275){
//             $pdf->SetXY($xAxis, $yAxis+=5);
//             $pdf->SetFont('helvetica', 'B', 9);
//             $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//             $pdf->MultiCell(70, 5, $cont, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//         }elseif($xAxis == 80 && $yAxis == 275){
//             $yAxis = 85;
//             $xAxis = 100;

//             $pdf->SetXY($xAxis, $yAxis+=5);
//             $pdf->SetFont('helvetica', 'B', 9);
//             $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//             $pdf->MultiCell(70, 5, $cont, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        
 
//         }elseif($xAxis == 100 && $yAxis < 275){
//             $pdf->SetXY($xAxis, $yAxis+=5);
//             $pdf->SetFont('helvetica', 'B', 9);
//             $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//             $pdf->MultiCell(70, 5, $cont, 0, 0, 'L', 0, '', 0, false, 'T', 'M');

//             if($xAxis == 100 && $yAxis == 275){
//                 $xAxis = 15;
//                 $yAxis = 45;
//                 $pdf->AddPage();
//                 $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
//                 $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
//                 $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
//             }
//         }
//     }
// }else{
    
//     $get_width_contract = $pdf->GetStringWidth($contractNo,'','');
//     $widthd = $get_width_contract/2;

//     $contractNoArray = explode(" ", $contractNo);

//     /*foreach($contractNoArray as $contArr){

//         //Validate lenght of str if greater than 140
//         if(strlen($contArr) >= 60){
    
//             $chunkSize = 27;
//             $string_length = strlen($contArr);
    
//             //
//             for ($i = 0; $i < $string_length; $i += $chunkSize) {
//                 $chunk = substr($contArr, $i, $chunkSize);
//                 $pdf->SetXY($xAxis, $yAxis+=4 );
//                 $pdf->SetFont('helvetica', '', 9);
//                 $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//                 $pdf->MultiCell(70, 5,  $chunk , 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//             }
    
//         }else{
//             $pdf->SetXY($xAxis, $yAxis+=4 );
//             $pdf->SetFont('helvetica', '', 9);
//             $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//             $pdf->MultiCell(70, 5, $contArr, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//         }
//     }*/

//     $pdf->SetXY($xAxis, $yAxis+=5);
//     $pdf->SetFont('helvetica', '', 9);
//     $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//     $pdf->MultiCell(70, 5, $contractNo, 0, 0, 'L', 0, '', 0, false, 'T', 'M');

//     if($yAxis == 275){
//         $yAxis = 45;
//         $pdf->AddPage();
//         $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
//         $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
//         $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
//     }
// }


// if($yAxis >= 265){    
//     $yAxis = 45;
//     $pdf->AddPage();
//     $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
//     $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
//     $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));

// }


// $pdf->SetXY($xAxis+2, $yAxis += 8);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(8, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->MultiCell(70, 5, 'Provider Subject Number:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');


// $pdf->SetXY($xAxis, $yAxis+= 5 );
// $pdf->SetFont('helvetica', '', 9);
// $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->MultiCell(70, 5, $subjectNo, 0, 0, 'L', 0, '', 0, false, 'T', 'M');


// $pdf->SetXY($xAxis+2, $yAxis += 8);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(8, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->MultiCell(70, 5, 'File Name and Record Count', 0, 0, 'L', 0, '', 0, false, 'T', 'M');

// $ctrFile = 1;

// if($isMultipleName== 1){
//     foreach($fileName as $file){
//         $pdf->SetXY($xAxis, $yAxis+=5);
//         $pdf->SetFont('helvetica', '', 9);
//         $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//         $pdf->MultiCell(70, 5, $yAxis++.". ".$file ." Count - ".$recordCount, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    
//         if($yAxis == 275){
//             $yAxis = 45;
//             $pdf->AddPage();
//             $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
//             $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
//             $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
//         }
//     }
// }else{
//     $pdf->SetXY($xAxis, $yAxis+=5);
//         $pdf->SetFont('helvetica', '', 9);
//         $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//         $pdf->MultiCell(70, 5, $fileName." Count - ".$recordCount, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    
//         if($yAxis == 275){
//             $yAxis = 45;
//             $pdf->AddPage();
//             $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
//             $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
//             $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
//         }
// }

// $pdf->SetXY(51, $yAxis += 12);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(8, 5, '', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
// $pdf->MultiCell(70, 5, 'DRCP Remarks:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');

// if(strlen($disputeRemarks) >= 95){
   

//     $chunkSize = 95;
//     $string_length = strlen($disputeRemarks);

//     //
//     for ($i = 0; $i < $string_length; $i += $chunkSize) {
//         $chunk = substr($disputeRemarks, $i, $chunkSize);
//         $pdf->SetXY($xAxis, $yAxis );
//         $pdf->SetFont('helvetica', '', 9);
//         $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//         $pdf->MultiCell(70, 5, $chunk, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//     }

// }else{
//     $pdf->SetXY($xAxis-1, $yAxis );
//     $pdf->SetFont('helvetica', 'B', 9);
//     $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//     $pdf->MultiCell(70, 5, $disputeRemarks, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// }


// $pdf->SetXY(15, $yAxis+10);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, 'DRT Verification Date:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY(90, $yAxis+10);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, date("Y-m-d", strtotime($drcpUpdateTs)), 0, 0, 'L', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY(15, $yAxis+20);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, 'DRT Verification Time:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY(90, $yAxis+20);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(70, 5, date("H:i:s", strtotime($drcpUpdateTs)), 0, 0, 'L', 0, '', 0, false, 'T', 'M');

// $pdf->SetXY($xAxis-27, $yAxis += 30);
// $pdf->SetFont('helvetica', 'B', 9);
// $pdf->MultiCell(8, 5, '', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
// $pdf->MultiCell(70, 5, 'DRT Remarks:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');


// foreach($remarksArray as $rem){

//     //Validate lenght of str if greater than 140
//     if(strlen($rem) >= 60){

//         $chunkSize = 60;
//         $string_length = strlen($rem);

//         //
//         for ($i = 0; $i < $string_length; $i += $chunkSize) {
//             $chunk = substr($rem, $i, $chunkSize);
//             $pdf->SetXY($xAxis, $yAxis+=4 );
//             $pdf->SetFont('helvetica', '', 9);
//             $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//             $pdf->MultiCell(70, 5,  $chunk , 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//         }

//     }else{
//         $pdf->SetXY($xAxis, $yAxis+=4 );
//         $pdf->SetFont('helvetica', '', 9);
//         $pdf->MultiCell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//         $pdf->MultiCell(70, 5, $rem, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
//     }
// }
ob_end_clean();
$pdf->Output("SummaryReport.pdf", 'I');
// $pdf->Output(dirname(__FILE__)."/generated/DISPUTE_".$trn."_".$provCode.".pdf", 'f');
?>
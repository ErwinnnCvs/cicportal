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

    
    // public function Header() {

    //     $this->Image(dirname(__FILE__).'/../../images/CICLogo.png', 22, 20, 100, 25, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
        
    //     $this->SetFont('helvetica', '', 9);
    //     $this->setCellHeightRatio(0.95);

    //     $this->MultiCell(70, 5, "6th Floor, Exchange Corner Building 107 V.A.\nRufino Street corner Esteban Street\nLegaspi Village, Makati City 1229\n(632) 8236-5900\nhttps://www.creditinfo.gov.ph/", 0, 'L', 0, 0, 125, 25, true);
        
    //     $bMargin = $this->getBreakMargin();

        
    //     $auto_page_break = $this->AutoPageBreak;

        
    //     $this->SetAutoPageBreak(false, 0);
        
    //     $this->SetAlpha(0.2);
    //     // Define the path to the image that you want to use as watermark.
    //     $img_file = dirname(__FILE__).'/../../images/CIClogo3.png';

    //     // Render the image
    //     $this->Image($img_file, 0, 60, 200, 145, '', '', '', false, 300, '', false, false, 0);
    //     $this->SetAlpha(1);

    //     // Restore the auto-page-break status
    //     $this->SetAutoPageBreak($auto_page_break, $bMargin);

    //     // Set the starting point for the page content
    //     $this->setPageMark();
    // }


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

    	// Page footer
	public function Footer() {
	   $this->SetY(-25);
        $this->SetFont('helvetica', 'I', 7);
        $this->Cell(5, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(180, 10, '© 2020 Credit Information Corporation. 6th Floor, Exchange Corner Building 107 V.A. Rufino Street corner Esteban Street Legaspi Village,1229, Makati City.', 'T', true, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-30);
        $this->SetFont('helvetica', 'I', 9);
        $this->MultiCell(0, 5, $this->CustomFooterText, 0, 'C', 0, 1, '', '', true);
	}


}

$dispute_id = $_GET['dispute'];
$getDispute = $dbh2->query("SELECT * from contract where fld_id > 9434 and fld_id = '".$dispute_id."'  LIMIT 1");
$gd = $getDispute->fetch_array();


$trn = $gd['fld_TRN'];
$status = $gd['fld_status'];
$id = $gd['fld_id'];
$provCode = $gd['fld_prov'];
$drtUpdateTs = $gd['fld_dispute_verification_ts'];
$drcpUpdateTs = $gd['fld_se_dispdetails_ts'];
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

    $contractNo = $gd['fld_provcontr_number'];

    // //Check if PCN have whitespace
    if(strpos($gd['fld_provcontr_number'], " ")){
        $contractNo = explode(" ", $gd['fld_provcontr_number']);
        $isMultiplePCN = 1;
    }else{
        $contractNo = $gd['fld_provcontr_number'];
    }   
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


$submissionDate = date("F d, Y H:i",strtotime($gd['fld_se_dispdetails_ts']));

if(!$submissionDate){
    $submissionDateC = "N/A";
    $submissionTimeC = "N/A";
} else {
    $submissionDateC = date("Y-m-d",strtotime($gd['fld_se_dispdetails_ts']));
    $submissionTimeC = date("H:i:s",strtotime($gd['fld_se_dispdetails_ts']));
}


$subject = $dbh2->query("SELECT fld_TRN, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject WHERE fld_TRN = '".$gd['fld_TRN']."' ORDER BY subjcode ASC");
$s=$subject->fetch_array();

$disputerName = $s['firstname']. " " .$s['middlename']. " " .$s['lastname'];

$disputeDateFiled = date("F d, Y H:i:s",strtotime($s['fld_DateFilled']));
$birthDate = date("F d, Y", strtotime( $s['fld_Birthday']));

$appointment = $dbh6->query("SELECT AES_decrypt(fld_email,CONCAT(fld_refID, 'RA9510')) as email FROM tblappointment where fld_refID = '".$gd['fld_TRN']."' ");
$a = $appointment->fetch_array();

if ($gd['fld_status'] == 4) {
  $sename = $gd['fld_name'];

  if ($gd['fld_prov'] == 'RB002210') {
    $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gd['fld_prov']."'");
    $gcn=$get_company_name->fetch_array();
    $sename = $gcn['name'];
  }
} else {
  $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gd['fld_prov']."'");
  $gcn=$get_company_name->fetch_array();
  $sename = $gcn['name'];
}


$ctr = 1;


$pdf = new MYPDF('P','mm',array(210, 297));

$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->setPrintFooter(true);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->CustomFooterText = "This is a system generated report. For any inquiries, please send an email to dispute@creditinfo.gov.ph";



$pdf->AddPage();

$pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
$pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
$pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));


$pdf->Ln(35);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->MultiCell(0, 5, "DISPUTE RESOLUTION  ", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(3);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(15);
$pdf->Cell(125, 7, 'Disputer Name', 'TBR', 0, 'C', 0, '', 0, false, 'T', 'M');

$pdf->SetX(140);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(55, 7, 'Filing Date', 'TB', 1, 'C', 0, '', 0, false, 'T', 'M');

$pdf->SetX(15);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(125, 7, $disputerName, 'R', 0, 'C', 0, '', 0, false, 'T', 'M');


$pdf->SetX(135);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(60, 7, $disputeDateFiled, 0, 1, 'C', 0, '', 0, false, 'T', 'M');


//START DEPT

$pdf->SetX(15);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(125, 7, 'Financial Institution', 'TB', 0, 'C', 0, '', 0, false, 'T', 'M');

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetX(140);
$pdf->Cell(55, 7, 'Transaction Reference No.', 'LTBR', 1, 'C', 0, '', 0, false, 'T', 'M');


$pdf->SetX(15);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(125, 7, $sename, 'B', 0, 'C', 0, '', 0, false, 'T', 'M');

$pdf->SetX(140);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(55, 7, $trn, 'LBR', 1, 'C', 0, '', 0, false, 'T', 'M');

// dispute details start
$pdf->Ln(4);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell($pdf->getPageWidth() - 30, 7, 'DISPUTE LOGS', 0, 'C', 1, 0, '', '', false, 0, false, true, 0);
$pdf->Ln(4);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell($pdf->getPageWidth() - 30, 7, 'This report was generated on '.date("d F Y")." at ".date("H:i:s"), 0, 'C', 1, 0, '', '', false, 0, false, true, 0);

$ctrCont = 1;
$yAxis = 90;



$pdf->SetXY(15, $yAxis+=10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'SE Submission Date:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Cell(19, 5, date("Y-m-d", strtotime($drcpUpdateTs)), 0, 0, 'R', 0, '', 0, false, 'T', 'M');


$pdf->SetXY(15, $yAxis+=10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'SE Submission Time:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Cell(15, 5, date("H:i:s", strtotime($drcpUpdateTs)), 0, 0, 'R', 0, '', 0, false, 'T', 'M');


$pdf->SetXY(15, $yAxis+=10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'Provider Contract Number:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');


if($isMultiplePCN == 1){
    foreach($contractNo as $cont){
        $pdf->SetFont('helvetica', '', 9); 
        if($cont != ""){

            if($yAxis < 260){
                $pdf->SetXY(95, $yAxis+=5);
                $pdf->Cell(15, 5, $cont, 0, 0, 'L', 0, '', 0, false, 'T', 'M'); 
            }elseif($yAxis >= 260){

                $xAxis = 15;
                $yAxis = 40;
                $pdf->AddPage();
                $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
                $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
                $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
            }
        }
    }
}else{
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(15, 5, $contractNo, 0, 0, 'L', 0, '', 0, false, 'T', 'M');

}

if($yAxis >= 250){
    $xAxis = 15;
    $yAxis = 10;
    $pdf->AddPage();
    $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
    $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
    $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
}

$pdf->SetXY(15, $yAxis+=10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'Provider Subject Number:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(70, 5, $subjectNo, 0, 0, 'L', 0, '', 0, false, 'T', 'M');

if($yAxis >= 250){
    $xAxis = 15;
    $yAxis = 40;
    $pdf->AddPage();
    $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
    $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
    $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
}

$pdf->SetXY(15, $yAxis+=10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'DRCP Remarks:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');

//start
    if(strlen($disputeRemarks) >= 55){

        $chunkSize = 55;
        $string_length = strlen($disputeRemarks);


        for ($i = 0; $i < $string_length; $i += $chunkSize) {

            if($yAxis >= 250){
                $xAxis = 15;
                $yAxis = 40;
                $pdf->AddPage();
                $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
                $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
                $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
            }

            $chunk = substr($disputeRemarks, $i, $chunkSize);
            $pdf->SetXY(85, $yAxis+= 5 );
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(70, 5,  trim($chunk) , 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        }


    }else{
        $pdf->SetXY(85, $yAxis+= 5 );
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(70, 5, $disputeRemarks, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    }

//end

if($yAxis >= 250){
    $xAxis = 15;
    $yAxis = 10;
    $pdf->AddPage();
    $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
    $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
    $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
}

$pdf->SetXY(15, $yAxis += 10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'DRT Verification Date:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Cell(70, 5, date("Y-m-d", strtotime($drtUpdateTs)), 0, 0, 'L', 0, '', 0, false, 'T', 'M');

if($yAxis >= 250){
    $xAxis = 15;
    $yAxis = 40;
    $pdf->AddPage();
    $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
    $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
    $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
}

$pdf->SetXY(15, $yAxis += 10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'DRT Verification Time:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Cell(70, 5, date("H:i:s", strtotime($drtUpdateTs)), 0, 0, 'L', 0, '', 0, false, 'T', 'M');



if($yAxis >= 250){
    $xAxis = 15;
    $yAxis = 40;
    $pdf->AddPage();
    $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
    $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
    $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
}

$pdf->SetXY(15, $yAxis += 10);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(80, 5, 'DRT Remarks:', 0, 0, 'R', 0, '', 0, false, 'T', 'M');


$pdf->SetFont('helvetica', '', 9);

foreach($remarksArray as $rem){

    //Validate lenght of str if greater than 140
    if(strlen($rem) >= 55){

        $chunkSize = 55;
        $string_length = strlen($rem);


        for ($i = 0; $i < $string_length; $i += $chunkSize) {

            if($yAxis >= 250){
                $xAxis = 15;
                $yAxis = 40;
                $pdf->AddPage();
                $pdf->SetLineStyle( array( 'width' => .5 , 'color' => array(0,0,0)));
                $pdf->Rect(15, 15, $pdf->getPageWidth() - 30, $pdf->getPageHeight() - 30);
                $pdf->SetLineStyle( array( 'width' => .1 , 'color' => array(0,0,0)));
            }

            $chunk = substr($rem, $i, $chunkSize);
            $pdf->SetXY(85, $yAxis+= 5 );
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(70, 5,  trim($chunk) , 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        }


    }else{
        $pdf->SetXY(85, $yAxis+= 5 );
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(10, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(70, 5, $rem, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    }
}



ob_end_clean();
$pdf->Output("SummaryReport.pdf", 'I');
// $pdf->Output(dirname(__FILE__)."/generated/DISPUTE_".$trn."_".$provCode.".pdf", 'i');
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../tcpdf/tcpdf.php';
require_once'config.php' ;

$sql=$dbh4->query("SELECT fld_ctrlno, 
    AES_DECRYPT(fld_name,MD5('".$code."')) AS name, 
    AES_DECRYPT(fld_lname_ar,MD5('".$code."')) AS lname_ar,
    AES_DECRYPT(fld_fname_ar,MD5('".$code."')) AS fname_ar,
    AES_DECRYPT(fld_mname_ar,MD5('".$code."')) AS mname_ar,
    AES_DECRYPT(fld_extname_ar,MD5('".$code."')) AS extname_ar,
    AES_DECRYPT(fld_provcode,MD5('".$code."')) AS provcode
    FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");

$r=$sql->fetch_array();
$ar_name = $r['fname_ar']." ";
if($r['mname_ar']){
  $ar_name .= substr($r['mname_ar'],0,1).". ";
}
$ar_name .= $r['lname_ar'];
if($r['extname_ar']){
  $ar_name .= " ".$r['extname_ar'];
}

$sae = 'SAE09440 | SAE09450';
$saes = explode(' | ', $sae);


class MYPDF2 extends TCPDF {

    //Page header
    public function Header() {

        $this->Image('../images/letterhead3.png', 25, 7, 145, 25, 'PNG', 'http://www.creditinfo.gov.ph', '', true, 150, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('times', 'B', 20);
        // Get the current page break margin
        $bMargin = $this->getBreakMargin();

        // Get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;

        // Disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set alpha to semi-transparency
        $this->SetAlpha(0.1);
        // Define the path to the image that you want to use as watermark.
        $img_file = '../images/CIClogo3.png';
        // set alpha to semi-transparency
        

        // Render the image
        $this->Image($img_file, 0, 60, 200, 145, '', '', '', false, 300, '', false, false, 0);
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

        $this->SetFont('times', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
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


foreach ($saes as $key => $value) {
  $sql=$dbh->query("SELECT fld_name FROM tbfininst WHERE fld_code = '".$value."'");
  $rsae = $sql->fetch_array();

$pdf = new MYPDF2('P','mm',array(210, 297));
$pdf->SetMargins(25, 10, 25);
$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->setPrintFooter(false);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->AddPage();
$pdf->SetProtection(array('modify'),str_replace('-','',$pass) );
$pdf->Ln(20);
$pdf->SetFont('times', 'B', 11);
$pdf->MultiCell(15, 5, "To", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(15, 5, ":", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "Submitting Entities", 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(15, 5, "Re", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(15, 5, ":", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "Certification Requirement for Allowing the Special Accessing \nEntity to Access Data on Behalf of the Submitting Entity", 0, 'L', 0, 1, '', '', true);
$pdf->Ln(0);
$pdf->MultiCell(0, 5, "", 'B', 'L', 0, 1, '', '', true);
$pdf->Ln(0);
$pdf->SetFont('times', '', 11);
$pdf->SetX(35);
$pdf->MultiCell(0, 5, "The Special Accessing Entities (SAE) of the Credit Information Corporation (CIC) namely:", 0, 'J', 0, 1, '', '', true);
$html1 = <<<EOD
<p align="justify"><b><i>CRIF Philippines, COMPUSCAN Philippines, TransUnion Information Solutions and CIBI Information Inc.</i></b>, as part of the latter's services offered, will access the CIC's database via the Credit Information System, on behalf of their respective client Submitting Entities<sup>1</sup> (SE).</p>
EOD;

$pdf->writeHTML($html1, true, false, false, false, '');
$pdf->Ln(2);
$pdf->SetX(35);
$pdf->MultiCell(0, 5, "In order for the CIC to validate, properly monitor, and audit the transactions between the SAE", 0, 'J', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, "and SE, the CIC may conduct a separate inquiry with the SE regarding the due execution of the Certification below. This is to ensure that the necessary provisions are embodied in the required Certification, which must be retained as given and should not be altered in any way.\n", 0, 'J', 0, 1, '', '', true);
$pdf->Ln(2);
$pdf->SetX(35);
$pdf->MultiCell(0, 5, "For your compliance and information.\n", 0, 'J', 0, 1, '', '', true);


$pdf->Ln(8);
$pdf->SetFont('times', 'BU', 11);
$pdf->MultiCell(0, 5, "CERTIFICATION", 0, 'C', 0, 1, '', '', true);
$pdf->Ln(2);
$pdf->SetFont('times', '', 10);
$pdf->MultiCell(0, 5, "This certifies that an Agreement dated ____________________________ was executed between ___________________________________________________________________________________ (SAE) and ________________________________________________________________________________ (SE), collectively referred to as the \"PARTIES\", represented by their authorized representatives, hereby attest to the following:\n", 0, 'J', 0, 1, '', '', true);




$pdf->Ln(1);
$pdf->SetX(35);
$pdf->SetFont('times', '', 9);
$pdf->MultiCell(7, 5, "1.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "This Certification is a requirement for the SAE to access the database of the CIC on behalf of the SE;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "2.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The account of the SE with the CIC is not suspended and hence, it is in good standing;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "3.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The SAE is duly authorized by the SE to access the basic credit data of the loan applicant or existing borrower of the SE with the CIC;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "4.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The SAE shall only access the basic credit data of a loan applicant/s or existing borrower/s whose written consent has already been previously obtained by the SE;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "5.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The authority herein granted is only valid from ___________ to __________ and may only be renewed by the SE in writing to the SAE and CIC. If the contract is open-ended, the authority ceases upon the transmittal and receipt by the CIC of the written termination or annulment of the contract;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "6.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The SE authorizes the SAE to transmit the SE’s Certification on its behalf, to the CIC;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "7.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "Non-submission of the SE and SAE Certifications will not initiate the processing of the SAE’s request for access;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "8.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "If the contract between the SAE and SE is time-bound, the validity of the authority shall terminate upon the expiration of the term in the contract. Both the SAE and the SE must inform the CIC that their contract has been terminated;\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "9.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The CIC assumes no liability for the SAE on its unauthorized access in the event that the CIC has not been informed of the termination of the contract. Unauthorized access of the data from the CIC is punishable by law; and\n", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(35);
$pdf->MultiCell(7, 5, "10.\n", 0, 'J', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The Authorized Representative indicated herein is the same individual stated in the SE’s SEIS.\n", 0, 'J', 0, 1, '', '', true);


$pdf->Ln(2);
$pdf->SetFont('times', '', 10);
$pdf->Write(0, 'The ', '', 0, 'L', 0, 0, false, false, 0);
$pdf->SetFont('times', 'B', 10);
$pdf->Write(0, 'PARTIES ', '', 0, 'L', 0, 0, false, false, 0);
$pdf->SetFont('times', '', 10);
$pdf->Write(0, 'herein have hereunto signed this ', '', 0, 'L', 0, 0, false, false, 0);
$pdf->SetFont('times', 'B', 10);
$pdf->Write(0, 'CERTIFICATION ', '', 0, 'L', 0, 0, false, false, 0);
$pdf->SetFont('times', '', 10);
$pdf->Write(0, 'this ____ day of __________in Makati, Philippines.', '', 0, 'J', 0, 0, false, false, 0);
// $pdf->Ln(5);
// $html2 = <<<enchant_broker_describe(broker)
// <p align="justify">    The <b>PARTIES</b> herein have hereunto signed the <b>CERTIFICATION</b> this _____ day of __________ in Makati,  \nPhilippines.</p>
// EOD;
// $pdf->writeHTML($html2, true, false, false, false, '');


$pdf->Ln(7);
$pdf->MultiCell(80, 1, "          ______________________________          ", 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(80, 1, "          ______________________________          ", 0, 'C', 0, 1, '', '', true);
$pdf->MultiCell(80, 1, "Name of Submitting Entity\n", 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(80, 1, "Name of Special Accessing Entity\n", 0, 'C', 0, 1, '', '', true);
// $pdf->Ln(1);
// $pdf->MultiCell(12, 5, "", 0, 'L', 0, 0, '', '', true);
// $pdf->MultiCell(68, 5, "By\n", 0, 'L', 0, 0, '', '', true);
// $pdf->MultiCell(12, 5, "", 0, 'L', 0, 0, '', '', true);
// $pdf->MultiCell(68, 5, "By\n", 0, 'L', 0, 1, '', '', true);
$pdf->Ln(10);
$pdf->Write(0, 'By:     ', '', 0, 'L', 0, 0, false, false, 0);
$pdf->MultiCell(60, 1, "", 'B', 'C', 0, 0, '', '', true);
$pdf->Write(0, '            By:     ', '', 0, 'L', 0, 0, false, false, 0);
$pdf->MultiCell(60, 1, "", 'B', 'C', 0, 1, '', '', true);
$pdf->SetFont('times', 'B', 9);
$pdf->MultiCell(80, 1, "SE REPRESENTATIVE\n", 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(80, 1, "SAE REPRESENTATIVE\n", 0, 'C', 0, 1, '', '', true);
$pdf->SetFont('times', '', 9);
$pdf->MultiCell(80, 1, "Authorized Representative\n", 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(80, 1, "Authorized Representative\n", 0, 'C', 0, 1, '', '', true);
$pdf->SetFont('times', 'I', 9);
$pdf->MultiCell(80, 1, "Signature over Printed Name", 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(80, 1, "Signature over Printed Name\n", 0, 'C', 0, 1, '', '', true);

$pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
$pdf->Line(20, 104, 20, 265);
$pdf->Line(190, 104, 190, 265);

$pdf->Line(20, 104, 190, 104);
$pdf->Line(20, 265, 190, 265);  


$pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
$pdf->SetY(117);
$pdf->SetFont('times', 'B', 10);
$pdf->MultiCell(0, 5, $rsae['fld_name'], 0, 'C', 0, 0, '', '', true);

$pdf->SetY(122);
$pdf->MultiCell(0, 5, $r['name'], 0, 'C', 0, 0, '', '', true);

$pdf->SetY(229);
$pdf->MultiCell(80, 5, $r['name'], 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(80, 5, $rsae['fld_name'], 0, 'C', 0, 0, '', '', true);

$pdf->SetY(248);
$pdf->MultiCell(80, 5, $ar_name, 0, 'C', 0, 0, '', '', true);
$pdf->MultiCell(80, 5, "", 0, 'C', 0, 0, '', '', true);

$pdf->SetY(-30);
$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(50, 5, "", 'B', 'C', 0, 1, '', '', true);
$pdf->MultiCell(0, 10, "1 Section 3 (q) of R.A. 9510 - \"Submitting Entity\" refers to any entity that provides credit facilities such as, but not limited to, banks, quasi-banks, trust entities, investment houses, financing companies, cooperatives, nongovernmental, micro-financing organizations, credit card companies, insurance companies and government lending institutions.\n", 0, 'J', 0, 0, '', '', true);

$pdf->AddPage();
$pdf->Ln(30);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "VERIFICATION FOR ACCESS BY THE SPECIAL ACCESSING ENTITY (SAE) ON BEHALF OF THE SUBMITTING ENTITY (SE)", 0, 'j', 0, 0, '', '', true);

$pdf->Ln(15);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(15, 5, "1)\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "Upon sending the completed and signed Certification - request via e-mail to", 0, 'J', 0, 1, '', '', true);// datasubmission@creditinfo.gov.ph, it shall be reviewed by the Data Submission Team for not more than three (3) working days.\n\nThe e-mail header requesting access on behalf of the SE shall be in the following format:
$pdf->SetFont('times', 'U', 11);
$pdf->MultiCell(15, 5, "\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(57, 5, "datasubmission@creditinfo.gov.ph", 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, ", it shall be reviewed by the Data Submission Team", 0, 'J', 0, 1, '', '', true);
$pdf->MultiCell(15, 5, "\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "for not more than three (3) working days.\n\nThe e-mail header requesting access on behalf of the SE shall be in the following format:\n", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(20);
$pdf->SetFont('times', 'B', 11);
$pdf->MultiCell(0, 5, "SAE ACCESS REQUEST FOR ".strtoupper($rsae['fld_name'])." ON\n BEHALF OF ".strtoupper($r['name']), 0, 'C', 0, 1, '', '', true);
$pdf->SetFont('times', '', 11);
$pdf->Ln(2);
$pdf->MultiCell(15, 5, "", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "Original signed copies of the SAE-SE Certifications are subject to CIC's audit and must be kept by the SAE.\n", 0, 'J', 0, 1, '', '', true);
$pdf->Ln(5);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(15, 5, "2)\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "The request for access shall be forwarded by the Data Submission Team to the CIS Support\n", 0, 'J', 0, 1, '', '', true);// cis-support@creditinfo.gov.ph which shall process the request within one (1) working day.
$pdf->SetFont('times', 'U', 11);
$pdf->MultiCell(15, 5, "\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(50, 5, "cis-support@creditinfo.gov.ph", 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, " which shall process the request within one (1) working", 0, 'J', 0, 1, '', '', true);
$pdf->MultiCell(15, 5, "\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "day.\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->MultiCell(15, 5, "3)\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(0, 5, "CIS Support shall then forward the request to the Network Operations Center", 0, 'J', 0, 1, '', '', true);// noc@creditinfo.gov.ph for the release of access to the SAE.
$pdf->SetFont('times', 'U', 11);
$pdf->MultiCell(15, 5, "\n", 0, 'R', 0, 0, '', '', true);
$pdf->MultiCell(40, 5, "noc@creditinfo.gov.ph", 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "for the release of access to the SAE.\n", 0, 'J', 0, 1, '', '', true);
$pdf->MultiCell(15, 5, "\n", 0, 'R', 0, 0, '', '', true);


$pdf->Ln(15);
$pdf->SetFont('times', 'BU', 11);
$pdf->MultiCell(0, 5, "Contact Details of the Authorized Representative from the SE:\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(15);
$pdf->SetFont('times', '', 11);
$pdf->setCellMargins( '', '', '', 5);
$pdf->MultiCell(50, 5, "NAME\n", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(90, 5, "", 'B', 'L', 0, 1, '', '', true);
$pdf->MultiCell(50, 5, "DESIGNATION\n", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(90, 5, "", 'B', 'L', 0, 1, '', '', true);
$pdf->MultiCell(50, 5, "OFFICE ADDRESS\n", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(90, 5, "", 'B', 'L', 0, 1, '', '', true);
$pdf->MultiCell(50, 5, "PHONE NUMBER\n", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(90, 5, "", 'B', 'L', 0, 1, '', '', true);
$pdf->MultiCell(50, 5, "E-MAIL ADDRESS\n", 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(90, 5, "", 'B', 'L', 0, 1, '', '', true);

$pdf->lastPage();
$pdf->Output("../pdf/AGRSECERT_".$value.$controlNo.'.pdf','F');
}

?>
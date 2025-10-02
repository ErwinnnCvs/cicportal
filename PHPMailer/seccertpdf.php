<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../tcpdf/tcpdf.php';
require_once'config.php' ;


$sql=$dbh4->query("SELECT 
    AES_DECRYPT(fld_name,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS name,
    AES_DECRYPT(fld_fname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fname_ar,
    AES_DECRYPT(fld_mname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS mname_ar,
    AES_DECRYPT(fld_lname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS lname_ar,
    AES_DECRYPT(fld_extname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS extname_ar,
    AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as addr_number,
    AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as addr_street,
    AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as addr_subdv,
    AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as address,
    fld_zip
 FROM tbentities WHERE fld_ctrlno = '".$ctrlno."'");


$r=$sql->fetch_array();

$bgy = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$r['address']."'");
$b = $bgy->fetch_array();
$cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($r['address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
$c = $cty->fetch_array();
$prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($r['address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
$p = $prv->fetch_array();



class MYPDF2 extends TCPDF {

    //Page header
    public function Header() {
        // Set font
        $this->SetFont('times', 'B', 20);
        // Get the current page break margin
        $bMargin = $this->getBreakMargin();

        // Get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;

        // Disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
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

$pdf = new MYPDF2('P','mm',array(210, 297));
$pdf->SetMargins(25, 0, 25);
$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->setPrintFooter(false);

$pdf->AddPage();

$pdf->SetProtection(array('modify'),str_replace('-','',$pass) );
$pdf->Ln(20);
$pdf->SetFont('times', 'B', 11);
$pdf->MultiCell(0, 5, "REPUBLIC OF THE PHILIPPINES}", 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, "________________________________} S.S.", 0, 'L', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetFont('times', 'BU', 11);
$pdf->MultiCell(0, 5, "SECRETARY’S CERTIFICATE", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetX(30);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "I, ____________________________________________, Filipino, of legal age, with address at", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(25);
$pdf->MultiCell(0, 5, "________________________________________________________________________________,\nunder oath, state:\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(2);
$pdf->SetX(45);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "1. I am the duly elected Corporate Secretary of", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(25);
$pdf->MultiCell(0, 5, "_________________________________________________________________________________ (the “Corporation”), a corporation duly organized and existing under and by virtue of the laws of the Republic of the Philippines with office address at\n", 0, 'J', 0, 1, '', '', true);
$pdf->Ln(4);
$pdf->MultiCell(0, 5, "", 'B', 'J', 0, 1, '', '', true);


$pdf->Ln(2);
$pdf->SetX(45);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "2.   As Corporate Secretary, I have possession of the records of the Corporation.\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(1);
$pdf->SetX(45);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "3. During the Regular/ Special Meeting of the Board of Directors held on", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(25);
$pdf->MultiCell(0, 5, "______________________ at ____________________________________________, wherein a quorum was present and acted throughout, the following resolutions were unanimously approved and adopted:\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(1);
$pdf->SetX(55);

$pdf->Write(0, '“', '', 0, 'L', false, 0, false, false, 0);
$pdf->SetFont('times', 'B', 11);
$pdf->Write(0, 'RESOLVED, AS IT IS HEREBY RESOLVED,', '', 0, 'L', false, 0, false, false, 0);

$pdf->SetMargins(45, 10, 45);
$pdf->SetFont('times', '', 11);
$pdf->Write(0, 'that ', '', 0, 'R', true, 0, false, false, 0);
$pdf->MultiCell(0, 5, "______________________________________________________ is the authorized representative of the Corporation to sign and execute the Memorandum of Agreement with Accessing Entities or other documents required by the Credit Information Corporation (CIC) relative to accessing basic credit data from the CIC;\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(1);
$pdf->SetX(55);
$pdf->SetFont('times', 'B', 11);
$pdf->Write(0, 'RESOLVED FURTHER,', '', 0, 'L', false, 0, false, false, 0);

$pdf->SetMargins(45, 10, 45);
$pdf->SetFont('times', '', 11);
$pdf->Write(0, ' that all prior resolutions of the', '', 0, 'J', true, 0, false, false, 0);
$pdf->MultiCell(0, 5, "Board on the matter are hereby amended accordingly;\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(1);
$pdf->SetX(55);
$pdf->SetFont('times', 'B', 11);
$pdf->Write(0, 'RESOLVED FINALLY,', '', 0, 'L', false, 0, false, false, 0);

$pdf->SetMargins(45, 10, 45);
$pdf->SetFont('times', '', 11);
$pdf->Write(0, ' that the following resolutions', '', 0, 'J', true, 0, false, false, 0);
$pdf->MultiCell(0, 5, "shall remain valid and binding unless the Corporation, through its Corporate Secretary, issues and transmits to CIC a subsequent Board Resolution or Secretary’s Certificate expressly repealing or amending any or all of these resolutions.”\n", 0, 'J', 0, 1, '', '', true);

$pdf->SetMargins(25, 10, 25);
$pdf->Ln(3);
$pdf->SetX(45);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "4. The foregoing resolution has not been revoked, modified or suspended,", 0, 'J', 0, 1, '', '', true);

$pdf->SetMargins(0, 10, 25);
$pdf->MultiCell(0, 5, "and shall remain in full force and effect, and may be relied upon unless written notice to the contrary is issued by the Corporation.\n", 0, 'J', 0, 1, '', '', true);

$pdf->SetMargins(25, 10, 25);
$pdf->Ln(1);
$pdf->SetX(45);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "IN WITNESS WHEREOF, I hereby affix my signature this _________________ at", 0, 'J', 0, 1, '', '', true);

$pdf->SetMargins(0, 10, 25);
$pdf->MultiCell(0, 5, "________________________________________________________________________________ .\n", 0, 'J', 0, 1, '', '', true);

$pdf->SetMargins(25, 10, 25);
$pdf->Ln(10);
$pdf->SetX(120);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(50, 5, "", 'B', 'J', 0, 1, '', '', true);
$pdf->SetX(120);
$pdf->SetFont('times', 'I', 11);
$pdf->MultiCell(50, 5, "Corporate Secretary ", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetX(30);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "SUBSCRIBED AND SWORN to before me this __ day of ____________ __________ ", 0, 'J', 0, 1, '', '', true);
$pdf->SetX(25);
$pdf->MultiCell(0, 5, "at ______________________________________________________________________________, affiant exhibiting to me his/her government issued identification card valid until __________ in ____________. \n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(0, 5, "Doc. No. ____; ", 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, "Page No. ____; ", 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, "Book No.____; ", 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, "Series of 2019 ", 0, 'L', 0, 1, '', '', true);

$pdf->SetY(67);
$pdf->SetFont('times', 'B', 11);
$pdf->MultiCell(0, 5, $r['name'], 0, 'C', 0, 0, '', '', true);
$pdf->SetY(81);

$addr1 = '';
if (!empty($r['addr_number'])) $addr1 .= $r['addr_number'].', ';
if (!empty($r['addr_street'])) $addr1 .= $r['addr_street'].', ';
if (!empty($r['addr_subdv'])) $addr1 .= $r['addr_subdv'];

$addr2 = '';
if (!empty($b['fld_geotitle'])) $addr2 .= $b['fld_geotitle'].', ';
if (!empty($c['fld_geotitle'])) $addr2 .= $c['fld_geotitle'].', ';
if (!empty($p['fld_geotitle'])) $addr2 .= $p['fld_geotitle'];
if (!empty($r['fld_zip'])) $addr2 .= " ".$r['fld_zip'];


$pdf->MultiCell(0, 5, $addr1, 0, 'C', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, $addr2, 0, 'C', 0, 0, '', '', true);

$fullname_ar = $r['fname_ar']." ";
if($r['mname_ar']) $fullname_ar .= substr($r['mname_ar'],0,1).". ";
$fullname_ar .= $r['lname_ar'];
if($r['extname_ar']) $fullname_ar .= " ".$r['extname_ar'];


$pdf->SetY(124);
$pdf->MultiCell(0, 5, $fullname_ar, 0, 'C', 0, 0, '', '', true);


$pdf->lastPage();
$pdf->Output('../pdf/SECCERT'.$ctrlno.'.pdf', 'F');


?>
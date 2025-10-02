<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../tcpdf/tcpdf.php';
require_once'config.php' ;


$sql = $dbh4->query("SELECT 
    AES_DECRYPT(fld_name,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS name,
    AES_DECRYPT(fld_provcode,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS provcode,
    AES_DECRYPT(fld_landline,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS landline,
    AES_DECRYPT(fld_lname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS lname_ar,
    AES_DECRYPT(fld_fname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fname_ar,
    AES_DECRYPT(fld_mname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS mname_ar,
    AES_DECRYPT(fld_extname_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS extname_ar,
    AES_DECRYPT(fld_position_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS position_ar,
    AES_DECRYPT(fld_email_ar,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS email_ar,
    AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_number,
    AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_street,
    AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_subdv,
    AES_DECRYPT(fld_addr_brgy, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_brgy,
    AES_DECRYPT(fld_addr_city, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_city,
    AES_DECRYPT(fld_addr_province, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_province,
    AES_DECRYPT(fld_addr_region, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS addr_region,
    AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_address,
    fld_zip
    FROM tbentities WHERE fld_ctrlno = '".$controlNo."'");
$r = $sql->fetch_array();
$password = $r['provcode'];//$r['provcode']

// Extend the TCPDF class to create custom Header and Footer
/*class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
}*/

if(!function_exists('appendAddress2')){
    function appendAddress2($num, $st, $sbd, $brg, $ct, $pr, $zip){
        $addr = '';
        if (!empty($num)) $addr .= $num.', ';
        if (!empty($st)) $addr .= $st.', ';
        if (!empty($sbd)) $addr .= $sbd.', ';
        if (!empty($brg)) $addr .= $brg.", ";
        if (!empty($ct)) $addr .= $ct.', ';
        if (!empty($pr)) $addr .= $pr.' ';
        if (!empty($zip)) $addr .= $zip;
        return $addr;
    }
}

if(!function_exists('fullname')){
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
}


// create new PDF document
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P','mm',array(215.9, 330.2));//355.6

$pdf->SetMargins(25, 20, 25);
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 0);
// add a page
$pdf->AddPage();
// $pdf->SetProtection(array('modify'),str_replace('-','',$pass) );

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->SetFont('helvetica', 'B', 11);
$pdf->MultiCell(0, 5, "MEMORANDUM OF AGREEMENT", 0, 'C', 0, 1, '', '', true);

$pdf->Ln();
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 5, "This Memorandum of Agreement with Accessing Entity (“Agreement”) is made and executed in the Philippines by and between:\n", 0, 'J', 0, 1, '', '', true);

// $pdf->Ln(5);
// $pdf->setCellPaddings(9, 1, 9, 1);
// $pdf->MultiCell(0, 5, "The CREDIT INFORMATION CORPORATION, a government-owned and controlled corporation organized and existing under and by virtue of Republic Act No. 9510, with principal office address at 6 th Floor, Exchange Corner Building, 107 V.A. Rufino St., cor. Esteban St. Legaspi Village, Makati City, acting through its President and CEO, duly authorized signatory, Mr. Jaime Casto Jose P. Garchitorena, (hereinafter referred to as the “CIC”);\n", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(2);
// create some HTML content
$html = '<table>
    <tr>
        <td></td>
        <td align="justify" colspan="14">The <b>CREDIT INFORMATION CORPORATION</b>, a government-owned and-
controlled corporation organized and existing under and by virtue of Republic Act

No. 9510, with principal office address at 6th Floor, Exchange Corner Building, 107
V.A. Rufino St., cor. Esteban St. Legaspi Village, Makati City, acting through its
President and CEO, duly authorized signatory, <b>Mr. Jaime Casto Jose P.
Garchitorena</b>, (hereinafter referred to as the “CIC”);</td>
        <td></td>
    </tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table>
    <tr>
        <td></td>
        <td align="center" colspan="14">-and-</td>
        <td></td>
    </tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');



// $r['position_ar'] = "President and Powerful Manager also Vice-President";

$pdf->SetMargins(35, 1, 35);
$pdf->Ln(0);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->Ln(1);
$pdf->MultiCell(0, 5, "___________________________________________________________________,", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(7);
$pdf->MultiCell(0, 5, "a corporation or entity organized and existing under the laws of", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(4);
$pdf->MultiCell(0, 5, "the Republic of the Philippines, with principal office address at", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(10);
$pdf->MultiCell(0, 5, "___________________________________________________________________,", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(9);
$pdf->MultiCell(0, 5, "represented by ____________________________________________________,", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(7);
$pdf->MultiCell(0, 5, "as authorized representative by virtue of Board Resolution dated _________________, as evidenced by Board Resolution/ Secretary’s Certificate dated ______________, which is hereto attached. Hereinafter referred to as the Accessing Entity (\"AE”).\n", 0, 'J', 0, 0, '', '', true);
$pdf->Ln(20);
$pdf->MultiCell(0, 5, "The CIC and the AE may, whenever the context so permits, be referred to as the “Parties” and individually as a “Party”.\n", 0, 'J', 0, 0, '', '', true);



$pdf->Ln(5);
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellMargins(15, 9, 15, 1);
$pdf->MultiCell(0, 5, "WITNESSETH: That -", 0, 'C', 0, 1, '', '', true);


/*$pdf->SetMargins(25, 10, 25);
$pdf->Ln(2);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->MultiCell(0, 5, "          WHEREAS, the CIC was created by virtue of Republic Act No. 9510 otherwise known as the Credit Information System Act, and its Implementing Rules and Regulations (IRR) to receive and consolidate basic credit data, act as a central registry or central repository of credit information, and provide access to reliable standardized information on the credit history and financial condition of borrowers to authorized entities;\n", 0, 'J', 0, 1, '', '', true);
$pdf->Ln(5);
$pdf->MultiCell(0, 5, "    WHEREAS, the CIC was created by virtue of Republic Act No. 9510 otherwise known as the Credit Information System Act, and its Implementing Rules and Regulations (IRR) to receive and consolidate basic credit data, act as a central registry or central repository of credit information, and provide access to reliable standardized information on the credit history and financial condition of borrowers to authorized entities;\n", 0, 'J', 0, 1, '', '', true);*/

$pdf->SetMargins(25, 20, 25);

$pdf->Ln(1);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WHEREAS, the CIC was created by virtue of Republic Act No. 9510 otherwise known as
the <i>Credit Information System Act</i>, and its Implementing Rules and Regulations (IRR) to receive
and consolidate basic credit data, act as a central registry or central repository of credit
information, and provide access to reliable standardized information on the credit history and
financial condition of borrowers to authorized entities;
        </td>
    </tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// $pdf->Ln(1);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;WHEREAS, on the basis of reciprocity, the AE is submitting basic credit data of all its
borrowers to the CIC, with at least six (6) months’ continuous submission reckoned from the
start date of this Agreement, and thus, desires to access Credit Reports from the CIC;
        </td>
    </tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WHEREAS, the CIC authorizes AE to access basic credit data, subject to payment of
Usage Fees in accordance with this Agreement, including attachments and Annexes “A” on
General Provisions, “B” on Billing and Collection, and “C” on Security Requirements, which are
made an integral part of this Agreement; and
        </td>
    </tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;&nbsp;&nbsp;Premises considered, the Parties agree to be bound by the terms and conditions of
Annexes “A” on General Provisions, “B” on Billing and Collection, and “C” on Security
Requirements.
        </td>
    </tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;&nbsp;&nbsp;Under this Agreement, AE has two (2) options to access the CIC’s central registry of
credit information:
        </td>
    </tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$html = '<table>
    <tr>
        <td align="right">1) </td>
        <td align="justify" colspan="14">
        Through Special Accessing Entity (SAE):

        </td>
        <td></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="justify" colspan="14">
        a. Web Portal Access
        </td>
        <td></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="justify" colspan="14">
        b. Batch Access
        </td>
        <td></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="justify" colspan="14">
        c. Application to Application
        </td>
        <td></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="justify" colspan="14">
        </td>
        <td></td>
    </tr>
    <tr>
        <td align="right">2) </td>
        <td align="justify" colspan="14">
        Direct access from the CIC for:
        </td>
        <td></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="justify" colspan="14">
        a. Web Portal Access in PDF format
        </td>
        <td></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td align="justify" colspan="14">
        b. Batch Access in PDF format
        </td>
        <td></td>
    </tr>
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetXY(168, 308);
$pdf->SetFont('helvetica', '', 11);
$pdf->Write(1, 'Page ');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Write(1, '1 ');
$pdf->SetFont('helvetica', '', 11);
$pdf->Write(1, 'of ');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Write(1, '18');

$pdf->SetXY(35, 86);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->MultiCell(145, 5, $r['name'], 0, 'C', 0, 1, '', '', true);

$bgy = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$r['fld_address']."'");
$b = $bgy->fetch_array();
$cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($r['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
$c = $cty->fetch_array();
$prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($r['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
$p = $prv->fetch_array();

$address = appendAddress2($r['addr_number'], $r['addr_street'], $r['addr_subdv'], $b['fld_geotitle'], $c['fld_geotitle'], $p['fld_geotitle'], $r['fld_zip']);

// $address = appendAddress2($r['addr_number'], $r['addr_street'], $r['addr_subdv'], $r['addr_brgy'], $r['addr_city'], $r['addr_province'], $r['fld_zip']);
$pdf->SetXY(35, 104);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell(145, 5, $address, 0, 'C', 0, 1, '', '', true);

/*$pdf->SetXY(67, 116);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->MultiCell(112, 5, fullname($r['fname_ar'], $r['mname_ar'], $r['lname_ar'], $r['extname_ar'])."\n", 0, 'C', 0, 1, '', '', true);*/



$pdf->AddPage();

// $pdf->setPrintHeader(true);
$pdf->SetFont('helvetica', 'BI', 10);
$pdf->MultiCell(0, 5, "MEMORANDUM OF AGREEMENT BETWEEN THE CREDIT INFORMATION CORPORATION
AND ACCESSING ENTITY", 0, 'L', 0, 1, '', '', true);

$pdf->SetMargins(25, 20, 25);

$pdf->Ln();
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Access from CIC and/ or through SAE is subject to the maintenance of existing data
quality standards as well as constantly improving said measures on the areas of periodicity,
accuracy, and completeness with the overarching aim of improving overall data quality.
        </td>
    </tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Effectivity of this Agreement shall start on ____ day of the month of ______________,
2019 (start date) and shall remain in force and effect until ______________________ unless
such date is mutually extended.
        </td>
    </tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table>
    <tr>
        <td align="justify" colspan="14">
        &nbsp;&nbsp;IN WITNESS WHEREOF, the parties have caused their respective authorized representatives to sign this Agreement.
        </td>
    </tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');


$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->MultiCell(0, 5, "CREDIT INFORMATION CORPORATION", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 5, "By:", 0, 'C', 0, 1, '', '', true);

// $pdf->SetMargins(35, 25, 35);
$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellMargins(1, 1, 1, 1);
$pdf->Cell(37, 8, "", '', 0, 'R');
$pdf->Cell(87, 8, "", 'B', 0, 'C');
$pdf->Cell(2, 8, "", '', 1, 'R');

// $pdf->Ln();
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->MultiCell(0, 5, "MR. JAIME CASTO JOSE P. GARCHITORENA", 0, 'C', 0, 0, '', '', true);
$pdf->Ln();
$pdf->MultiCell(0, 5, "President and CEO", 0, 'C', 0, 0, '', '', true);

$pdf->Ln(15);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->MultiCell(0, 5, strtoupper($r['name']), 0, 'C', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 5, "By:", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellMargins(1, 1, 1, 1);
$pdf->Cell(40, 8, "", '', 0, 'R');
$pdf->Cell(80, 8, "", 'B', 0, 'C');
$pdf->Cell(40, 8, "", '', 1, 'R');

$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 5, "WITNESSES", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(0);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellMargins(1, 1, 1, 1);
// $pdf->Cell(37, 8, "", '', 0, 'R');
$pdf->Cell(10, 8, "", '', 0, 'C');
$pdf->Cell(60, 8, "", 'B', 0, 'C');
$pdf->Cell(20, 8, "", '', 0, 'C');
$pdf->Cell(60, 8, "", 'B', 0, 'C');
$pdf->Cell(10, 8, "", '', 0, 'C');
// $pdf->Cell(2, 8, "", '', 1, 'R');

$pdf->Ln(15);
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 5, "A  C  K  N  O  W  L  E  D  G  E  M  E  N  T", 0, 'C', 0, 1, '', '', true);


$pdf->SetLineWidth(0.1);
$pdf->SetDash(1.5,1.5); //5mm on, 5mm off
$pdf->Line(69, 226, 215-69, 226);
$pdf->SetDash(); //restores no dash
// $pdf->Line(20,170,190,170);

$pdf->Ln(2);
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 5, "REPUBLIC OF THE PHILIPPINES}\nMakati City                                      }S.S.", 0, 'L', 0, 1, '', '', true);

$pdf->Ln(2);
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->setCellMargins(1, 1, 1, 1);
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 5, "    BEFORE ME, this __________, day of __________ 20___ at __________________________, personally came and appeared:\n", '', 'J', 0, 1, '', '', true);


$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->Cell(20, 8, "", 0, 0, 'C');
$pdf->Cell(50, 8, "   Name", 1, 0, 'L');
$pdf->Cell(50, 8, "   Government Issued Id", 1, 0, 'L');
$pdf->Cell(40, 8, "   Date/Place Issued", 1, 0, 'L');
$pdf->Cell(20, 8, "", 0, 0, 'C');

$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->Cell(20, 8, "", 0, 0, 'C');
$pdf->MultiCell(50, 8, "   Jaime Casto Jose P. \n   Garchitorena \n   (Credit Information\n   Corporation)", 'LR', 'L', 0, 0, '', '', true);
$pdf->Cell(50, 20, "", 1, 0, 'C');
$pdf->Cell(40, 20, "", 1, 0, 'C');
$pdf->Cell(20, 20, "", 0, 1, 'C');

$pdf->Ln(0);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->Cell(20, 8, "", 0, 0, 'C');
$pdf->Cell(50, 8, "", 1, 0, 'C');
$pdf->Cell(50, 8, "", 1, 0, 'C');
$pdf->Cell(40, 8, "", 1, 0, 'C');
$pdf->Cell(20, 8, "", 0, 1, 'C');

$pdf->SetXY(168, 308);
$pdf->SetFont('helvetica', '', 11);
$pdf->Write(1, 'Page ');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Write(1, '2 ');
$pdf->SetFont('helvetica', '', 11);
$pdf->Write(1, 'of ');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Write(1, '18');

$pdf->AddPage();
$pdf->SetFont('helvetica', 'BI', 10);
$pdf->MultiCell(0, 5, "MEMORANDUM OF AGREEMENT BETWEEN THE CREDIT INFORMATION CORPORATION
AND ACCESSING ENTITY", 0, 'L', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->MultiCell(0, 5, "all known to me, to be the same persons who executed the foregoing instrument and they acknowledged to me that the same is their free and voluntary act and deed as well as that of the CORPORATIONS they respectively represent.\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->MultiCell(0, 5, "This instrument which consists of eighteen (18) pages including this page whereon the acknowledgement is written, has been signed by the parties hereto and their instrumental witnesses at the lower portion of this page and the left hand margin of all other pages and sealed with my notarial seal.\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->MultiCell(0, 5, "IN WITNESS WHEREOF, I have hereunto set my hand and affixed my notarial seal this ________ day of _________________ , _______ in __________, Philippines.\n", 0, 'J', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->MultiCell(0, 5, "NOTARY PUBLIC
Doc. No. ______;
Page No. ______;
Book No. ______;
Series of 2019\n", 0, 'J', 0, 1, '', '', true);

$pdf->SetXY(168, 308);
$pdf->SetFont('helvetica', '', 11);
$pdf->Write(1, 'Page ');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Write(1, '3 ');
$pdf->SetFont('helvetica', '', 11);
$pdf->Write(1, 'of ');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Write(1, '18');
// move pointer to last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('moa/MOA'.$controlNo.'part1.pdf', 'F');
// $pdf->Output(__DIR__. "/pdf/MOA".$controlNo.'.pdf','F');


// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
/*$pdf = new MYPDF('P','mm',array(215.9, 330.2));//355.6

$pdf->SetMargins(25, 15, 25);
// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 0);
// add a page
$pdf->AddPage();
$pdf->SetFont('helvetica', 'BI', 10);
$pdf->MultiCell(0, 5, "MEMORANDUM OF AGREEMENT BETWEEN THE CREDIT INFORMATION CORPORATION \nAND ACCESSING ENTITY", 0, 'L', 0, 1, '', '', true);

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Ln(5);
$pdf->MultiCell(0, 5, "ANNEX “B-1”", 0, 'R', 0, 1, '', '', true);
$pdf->Ln(5);
$pdf->MultiCell(0, 5, "CREDIT INFORMATION CORPORATION \n BILLING AND COLLECTION", 0, 'C', 0, 1, '', '', true);
$pdf->Ln(5);
$pdf->MultiCell(0, 5, "ACTION REQUEST FORM", 0, 'C', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 9);
$pdf->MultiCell(0, 5, "DATE:", 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, "NAME OF ACCESSING ENTITY:", 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 5, "ACCOUNT NUMBER:", 0, 'L', 0, 1, '', '', true);

$pdf->Ln(5);
$pdf->MultiCell(20, 5, "TYPE OF REQUEST:", 0, 'L', 0, 1, '', '', true);



$pdf->Output('page1.pdf', 'I');exit;*/

require_once('../fpdf/fpdf.php');
require_once('../FPDI2/src/autoload.php');
require_once('../FPDI2/src/autoload2.php');

$origFile = "moa/part3.pdf";
$destFile = "moa/MOA".$controlNo."part3.pdf";
$pdf = new setasign\FpdiProtection\FpdiProtection('P','mm',array(215.9, 330.2));
            
//calculate the number of pages from the original document
$pagecount = $pdf->setSourceFile($origFile);

// copy all pages from the old unprotected pdf in the new one
for ($loop = 1; $loop <= $pagecount; $loop++) {
    
    $tpl = $pdf->importPage($loop);
    
    // add a page
    $pdf->AddPage();
    $pdf->useTemplate($tpl);
}

$pdf->SetXY(65, 63);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell(120, 5, $r['name'], 0, 'L', 0, 1, '', '', true);


$pdf->SetXY(65, 68);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->MultiCell(0, 5, $accountNo, 0, 'L', 0, 1, '', '', true);

$pdf->Output($destFile, 'F');



function pdfEncrypt($origFile, $origFile2, $origFile3, $origFile4, $password, $destFile) {
            
            
            $pdf = new setasign\FpdiProtection\FpdiProtection('P','mm',array(215.9, 330.2));
            
            //calculate the number of pages from the original document
            $pagecount = $pdf->setSourceFile($origFile);
            
            // copy all pages from the old unprotected pdf in the new one
            for ($loop = 1; $loop <= $pagecount; $loop++) {
                
                $tpl = $pdf->importPage($loop);
                
                // add a page
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            $pagecount2 = $pdf->setSourceFile($origFile2);
            for ($loop = 1; $loop <= $pagecount2; $loop++) {
                
                $tpl = $pdf->importPage($loop);
                
                // add a page
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            $pagecount3 = $pdf->setSourceFile($origFile3);
            for ($loop = 1; $loop <= $pagecount3; $loop++) {
                
                $tpl = $pdf->importPage($loop);
                
                // add a page
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }

            $pagecount4 = $pdf->setSourceFile($origFile4);
            for ($loop = 1; $loop <= $pagecount4; $loop++) {
                
                $tpl = $pdf->importPage($loop);
                
                // add a page
                $pdf->AddPage();
                $pdf->useTemplate($tpl);
            }


            // protect the new pdf file, and allow no printing, copy etc and leave only reading allowed
            $pdf->SetProtection(array(
                'print'
            ), $password);

            $pdf->Output($destFile, 'F');//F
            $pdf->Close();
            
            return true;
            
        }
$uploadsPath = "moa/MOA".$controlNo."part1.pdf";
$uploadsPath2 = "moa/part2.pdf";
$uploadsPath3 = "moa/MOA".$controlNo."part3.pdf";
$uploadsPath4 = "moa/part4.pdf";
$destFile = "../pdf/MOA".$controlNo.".pdf";
pdfEncrypt($uploadsPath, $uploadsPath2, $uploadsPath3, $uploadsPath4, $password, $destFile);

//============================================================+
// END OF FILE
//============================================================+
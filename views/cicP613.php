<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($_GET['nid'] == 6 and $_GET['sid'] == 1 and $_GET['rid'] == 3) {
  $access_label = "SAE Access";
  $sae = "SAE09450";
  $sae_label = "TransUnion Information Solutions Inc";
}

if (!$_POST['yearmonth']) {
  $det[0] = date("Y");
  $det[1] = date("m");
  $yrsel[date("Y-m")] = " selected";
  $labelyearmonth = $abbrmo[(int)$det[1]]." ".$det[0];
}

if($_POST['yearmonth']){
  $active1[2] = " class='active'";
  $active2[2] = " active";
  $yrsel[$_POST['yearmonth']] = " selected";
  $det = explode("-", $_POST['yearmonth']);
  
  $labelyearmonth = $abbrmo[(int)$det[1]]." ".$det[0];
}
// print_r($det);
// echo date("Y-m-d", strtotime($det[0].'-'.$det[1].'-'.'01 -1 day'));
$get_access_limit = $dbh1->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$sae."'");
$gal=$get_access_limit->fetch_array();

// $get_beginning_balance = $dbh1->query("SELECT fld_beginbalance FROM tbbillingbalance WHERE fld_provcode = '".$sae."' and YEAR(fld_stmt_date) = '".$det[0]."' and MONTH(fld_stmt_date) = '".$det[1]."' ");
$get_beginning_balance = $dbh5->query("SELECT fld_beginbalance FROM tbcrbillingbalance WHERE fld_provcode = '".$sae."' and fld_stmt_date = '".date("Y-m-d", strtotime($det[0].'-'.$det[1].'-'.'01 -1 day'))."'");
$gbb=$get_beginning_balance->fetch_array();
// print_r($gbb);
$get_deposit_month = $dbh5->query("SELECT SUM(fld_amount) as amount FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_payment_date LIKE '".date("Y-m")."%' GROUP BY fld_acct_no");
$gdm=$get_deposit_month->fetch_array();


$SAE_details["SAE09670"] = ["branchcode" => "SCIBI", "user" =>  "CIB", "compname" =>  "CIBI Inc."];
$SAE_details["SAE09440"] = ["branchcode" => "SCRIF", "user" =>  "CRF", "compname" =>  "CRIF Inc."];
$SAE_details["SAE09450"] = ["branchcode" => "STRAN", "user" =>  "TRA", "compname" =>  "Transunion Information Solutions Inc."];
$sql1=$dbh5->query("SELECT i.fld_provcode, SUM(i.fld_inqcount) AS inq, SUM(i.fld_inq_price) AS price, fld_inqdate FROM tbinquiries i WHERE i.fld_inqdate LIKE '".$det[0].'-'.$det[1]."%' AND i.fld_usercode <> 'TESTTEST' AND i.fld_servicecode <> 'CBPMS' AND (i.fld_provcode = '".$sae."' OR i.fld_branchcode = '".$SAE_details[$sae]["branchcode"]."' OR (i.fld_branchcode LIKE 'SAE%' AND i.fld_usercode LIKE '%".$SAE_details[$sae]["user"]."')) AND ((i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_NAE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_ME' AND i.fld_errorcode LIKE '%1-100%') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode = 'NULL') OR (i.fld_sourcecode = 'CB_CE' AND i.fld_errorcode LIKE '%1-100%')) AND fld_inqresult = 1 GROUP BY i.fld_provcode, i.fld_inqdate");
$r = $sql1->fetch_array();


$current_balance = ($gbb['fld_beginbalance'] + $gdm['amount']) - $r['price'];

$current_balance_percentage = ((300000 - $current_balance) / 300000) * 100;
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <?php echo $access_label; ?>
        </button>
        <ul class="dropdown-menu">
          <a href="main.php?nid=6&sid=0&rid=0"><li class="dropdown-item">SE Access</li></a>
          <a href="main.php?nid=6&sid=1&rid=1"><li class="dropdown-item">SAE Access</li></a>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <center>
      <form method="post">
      <select name="yearmonth" class="form-control" style="width: 8%;" onchange="submit()">
      <?php
        $cnt1=0;
        $sql=$dbh5->query("SELECT SUBSTR(fld_inqdate, 1, 7) AS ym FROM tbinquiries WHERE SUBSTR(fld_inqdate, 1, 7) <= '".date("Y-m")."' GROUP BY SUBSTR(fld_inqdate, 1, 7)");
        while($h=$sql->fetch_array()){
          if(!$_POST['yearmonth']){
            $_POST['yearmonth'] = $h['ym'];
          }
          $dt = explode("-", $h['ym']);
          echo "<option value='".$h['ym']."'".$yrsel[$h['ym']].">".$abbrmo[(int)$dt[1]]." ".$dt[0]."</option>";
        }
      ?>
      </select>
      </form>
      <br>
      <h3 class="page-header"><?php echo $labelyearmonth; ?></h3>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <?php echo $sae_label; ?>
        </button>
        <ul class="dropdown-menu">
          <a href="main.php?nid=6&sid=1&rid=1"><li class="dropdown-item">CIBI Information Inc</li></a>
          <a href="main.php?nid=6&sid=1&rid=2"><li class="dropdown-item">CRIF Corporation</li></a>
          <a href="main.php?nid=6&sid=1&rid=3"><li class="dropdown-item">TransUnion Information Solutions Inc</li></a>
        </ul>
      </center>
      <div class="float-right">
        <table class="table table-bordered">
          <tr>
            <td><b>Balance: </b></td>
            <td style="text-align: right;"><?php echo number_format($current_balance); ?></td>
          </tr>
          <!-- <tr>
            <td><b>Consumption: </b></td>
            <td><center><?php echo round($current_balance_percentage, 2)."%"; ?></center></td>
          </tr> -->
        </table>
      </div>
      <br>
      <table class="table table-bordered table-hover table-sm">
        <thead>
          <tr>
            <th width="15%">Account Number</th>
            <th width="40%">Entity</th>
            <th style="text-align: right;" width="9%">Inquiries</th>
            <th style="text-align: right;" width="9%">Access Limit</th>
            <th width="9%"><center>%</center></th>
          </tr>
        </thead>
        <tbody>
         <?php
         #CIBI
          $get_cibi_inquiry_month = $dbh5->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND (fld_provcode = 'SAE09450' OR fld_branchcode = 'STRAN' OR (fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%TRA')) AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_CE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_CE' AND fld_errorcode LIKE '%1-100%')) AND ((SUBSTR(fld_usercode, -3) <> 'DQ1' or SUBSTR(fld_usercode, -3) <> 'DQ2' or SUBSTR(fld_usercode, -3) <> 'DQ3' ) AND fld_sourcecode <> 'CB_ME') GROUP BY fld_provcode");
          if (mysqli_num_rows($get_cibi_inquiry_month)==0) {
          ?>
          <tr>
            <td colspan="5"><center><b class="text-danger">No data</b></center></td>
          </tr>
          <?php
          } else {
          while($gcim=$get_cibi_inquiry_month->fetch_array()){
            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gcim['fld_provcode']."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();

            $access_limit_percentage = ($gcim['inqcnt'] / $gen['fld_access_limit']) * 100;

            if ($access_limit_percentage >= 50 and $access_limit_percentage < 80) {
              $alcolor = "#f5f536";
              $txtColor = "#000000";
            } elseif ($access_limit_percentage >= 80) {
              $alcolor = "#b8120f";
              $txtColor = "#ffffff";
            } else {
              $alcolor = "#ffffff";
              $txtColor = "#000000";
            }

         ?>
         <tr>
           <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $txtColor; ?>"><?php echo $gal['fld_accountno']; ?></td>
           <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $txtColor; ?>"><?php echo $sae_label."[".$gen['name']."]"; ?></td>
           <td style="text-align: right; background-color: <?php echo $alcolor; ?>; color: <?php echo $txtColor; ?>"><?php echo number_format($gcim['inqcnt']); ?></td>
           <td style="text-align: right; background-color: <?php echo $alcolor; ?>; color: <?php echo $txtColor; ?>"><?php echo number_format($gen['fld_access_limit']); ?></td>
           <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $txtColor; ?>"><center><?php echo round($access_limit_percentage, 2)."%"; ?></center></td>
         </tr>
         <?php
            }
          }
         ?>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
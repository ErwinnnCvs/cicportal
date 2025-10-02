<?php

if ($_GET['nid'] == 7 and $_GET['sid'] == 1 and $_GET['rid'] == 2) {
  $access_label = "SAE Access";
  $sae = "SAE09440";
  $sae_label = "CRIF Corporation";
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


$get_access_limit = $dbh->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$sae."'");
$gal=$get_access_limit->fetch_array();

$get_beginning_balance = $dbh->query("SELECT fld_beginbalance FROM tbbillingbalance WHERE fld_provcode = '".$sae."' and YEAR(fld_stmt_date) = '".$det[0]."' and MONTH(fld_stmt_date) = '".$det[1]."' ");
$gbb=$get_beginning_balance->fetch_array();

$get_deposit_month = $dbh->query("SELECT SUM(fld_amount) as amount FROM tbbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_payment_date LIKE '".date("Y-m")."%' GROUP BY fld_acct_no");
$gdm=$get_deposit_month->fetch_array();

$current_balance = ($gbb['fld_beginbalance'] + $gdm['amount']) - $gcim['inqprice'];

$current_balance_percentage = ((10000 - $current_balance) / 10000) * 100;
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
          <a href="main.php?nid=7&sid=0&rid=0"><li class="dropdown-item">SE Access</li></a>
          <a href="main.php?nid=7&sid=1&rid=1"><li class="dropdown-item">SAE Access</li></a>
        </ul>
      </div>
    </div>

    <div class="card-body">
      <center>
      <form method="post">
      <select name="yearmonth" class="form-control" style="width: 8%;" onchange="submit()">
      <?php
        $cnt1=0;
        $sql=$dbh->query("SELECT SUBSTR(fld_inqdate, 1, 7) AS ym FROM tbinquiries WHERE SUBSTR(fld_inqdate, 1, 7) <= '".date("Y-m")."' GROUP BY SUBSTR(fld_inqdate, 1, 7)");
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
          <a href="main.php?nid=7&sid=1&rid=1"><li class="dropdown-item">CIBI Information Inc</li></a>
          <a href="main.php?nid=7&sid=1&rid=2"><li class="dropdown-item">CRIF Corporation</li></a>
        </ul>
      </center>
      <br>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="15%">Account Number</th>
            <th width="40%">Entity</th>
            <th style="text-align: right;" width="9%">Inquiries</th>
            <th style="text-align: right;" width="9%">Sales</th>
          </tr>
        </thead>
        <tbody>
         <?php
         #CIBI
          $get_crif_inquiry_month = $dbh->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND ((fld_branchcode = 'SCRIF' OR (fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CRF')) OR (fld_branchcode = 'SCRIF' OR (fld_branchcode = 'CONSU' AND fld_usercode = 'CRIF3SNC'))) AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_CE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_CE' AND fld_errorcode LIKE '%1-100%')) GROUP BY fld_provcode");


          if (mysqli_num_rows($get_crif_inquiry_month)==0) {
          ?>
          <tr>
            <td colspan="5"><center><b class="text-danger">No data</b></center></td>
          </tr>
          <?php
          } else {
          while($gcim=$get_crif_inquiry_month->fetch_array()){
            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gcim['fld_provcode']."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();
         ?>
         <tr>
           <td><?php echo $gal['fld_accountno']; ?></td>
           <td><?php echo $sae_label."[".$gen['name']."]"; ?></td>
           <td style="text-align: right;"><?php echo number_format($gcim['inqcnt']); ?></td>
           <td style="text-align: right;"><?php echo number_format($gcim['inqprice']); ?></td>
         </tr>
         <?php
              $totalinq += $gcim['inqcnt'];
              $totalsales += $gcim['inqprice'];
            }
          }
         ?>
        </tbody>
      </table>
      <table class="table table-bordered">
        <tbody>
          <tr>
            <td  width="15%"></td>
            <td style="text-align: right;" width="40%"><b>TOTAL:</b></td>
            <td style="text-align: right;" width="9%"><b><?php echo number_format($totalinq); ?></b></td>
            <td style="text-align: right;" width="9%"><b><?php echo number_format($totalsales); ?></b></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
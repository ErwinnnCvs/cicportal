<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
if ($_GET['nid'] == 5 and $_GET['sid'] == 1 and $_GET['rid'] == 2) {
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

?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="input-group-prepend">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <?php echo $access_label; ?>
          </button>
          <ul class="dropdown-menu">
            <a href="main.php?nid=5&sid=0&rid=0"><li class="dropdown-item">SE Access</li></a>
            <a href="main.php?nid=5&sid=1&rid=1"><li class="dropdown-item">SAE Access</li></a>
          </ul>
        </div>
      </div>
        <a href="main.php?nid=6&sid=2&rid=1" class="float-right">Access Limit</a>
    </div>
    <div class="card-body">
      <center>
      <form method="post">
        <select name="yearmonth" class="form-control" style="width: 10%;" onchange="submit()">
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
      <h3 class="page-header"><a href="main.php?nid=5&sid=1&rid=1&date=<?php echo $det[0]."-".$det[1]; ?>"><?php echo $labelyearmonth; ?></a></h3>
       <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <?php echo $sae_label; ?>
        </button>
        <ul class="dropdown-menu">
          <a href="main.php?nid=5&sid=1&rid=1"><li class="dropdown-item">CIBI Information Inc</li></a>
          <a href="main.php?nid=5&sid=1&rid=2"><li class="dropdown-item">CRIF Corporation</li></a>
          <a href="main.php?nid=5&sid=1&rid=3"><li class="dropdown-item">TransUnion Information Solutions Inc</li></a>
        </ul>
      </center>

      <table class="table table-bordered table-hover table-sm" id="se_inquiries">
        <thead>
          <tr>
            <th width="10%">Account Number</th>
            <th width="40%">Entity</th>
            <th style="text-align: right;" width="9%">Inquiries</th>
            <!-- <th width="9%">Charge Back</th> -->
            <!-- <th width="9%">Access Limit</th>
            <th width="9%"><center>%</center></th> -->
            <!-- <th width="9%">Credits</th> -->
<!--             <th width="9%">Available Credits</th>
            <th width="9%">Reorder Point</th> -->
            <!-- <th width="9%"><center>%</center></th> -->
            <!-- <th style="text-align: right;" width="9%">Available</th> -->
            <!-- <?php
              if ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 10) {
            ?>
            <th style="text-align: right;" width="9%">Balance</th>
            <th width="9%"><center>%</center></th>
            <?php
              }
            ?> -->
          </tr>
        </thead>
        <tbody>
         <?php
          $get_inquiry_cost = $dbh->query("SELECT * FROM tbinquirycost WHERE fld_effectivity_date > '".$det[0]."-".$det[1]."-01 8:00:00' and fld_costtype = 1");
          $gic=$get_inquiry_cost->fetch_array();
          

          $get_inquiry_month = $dbh->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND ((fld_branchcode = 'SCRIF' OR (fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CRF')) OR (fld_branchcode = 'SCRIF' OR (fld_branchcode = 'CONSU' AND fld_usercode = 'CRIF3SNC'))) AND fld_inqresult = 1 AND ((SUBSTR(fld_usercode, -3) <> 'DQ1' or SUBSTR(fld_usercode, -3) <> 'DQ2' or SUBSTR(fld_usercode, -3) <> 'DQ3' ) AND fld_sourcecode <> 'CB_ME') GROUP BY fld_provcode");
          while($gim=$get_inquiry_month->fetch_array()){

            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_alcons_status, fld_alwarning_ts, fld_alcut_ts, fld_alnoccut_ts, fld_apcons_status, fld_apwarning_ts, fld_apcut_ts, fld_apnoccut_ts, fld_apcredits_reorderpt, fld_accountno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gim['fld_provcode']."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();

            $access_limit_percentage = ($gim['inqcnt'] / $gen['fld_access_limit']) * 100;

            // $get_access_limit = $dbh->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$gim['fld_provcode']."'");
            // $gal=$get_access_limit->fetch_array();

            // $get_beginning_balance = $dbh->query("SELECT fld_converted_credits FROM tbcrbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and YEAR(fld_stmt_date) = '".$det[0]."' and MONTH(fld_stmt_date) = '".$det[1]."' ");
            // $gbb=$get_beginning_balance->fetch_array();



            $get_deposit_month = $dbh->query("SELECT SUM(fld_converted_credits) as amount FROM tbcrbillingpayment WHERE fld_acct_no = '".$gen['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_transaction_type <> 'MIGRATED' GROUP BY fld_acct_no");
            $gdm=$get_deposit_month->fetch_array();

            $check_previous_cb_from_payment = $dbh->query("SELECT SUM(fld_converted_credits) as amount, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$gen['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_charge_back = 1 and fld_transaction_type <> 'MIGRATED' GROUP BY fld_acct_no");
            $cpcbp=$check_previous_cb_from_payment->fetch_array();

            $picked_date = strtotime($det[0]."-".$det[1]."-01");
            $date1 = date('Y-m-d', strtotime("+1 month", $picked_date));

            $get_next_beginning_balance_cb = $dbh->query("SELECT fld_chargeback FROM tbcrbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and fld_stmt_date = '".$date1." '");
            $gnbbcb=$get_next_beginning_balance_cb->fetch_array();

            $beggining_cb_balance = $dbh->query("SELECT fld_chargeback FROM tbcrbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and fld_stmt_date = '".$det[0]."-".$det[1]."-01"."'");
            $bcbb=$beggining_cb_balance->fetch_array();

            $beginningbalancecb=$bcbb['fld_chargeback'];
            $additionalcb=$cpcbp['amount'];
            $endingcb=$gnbbcb['fld_chargeback'];

            $inqcount = $gim['inqcnt'];


            $chargeback = ($beginningbalancecb + $additionalcb) - $endingcb;



            if ($chargeback > 0) {
              $inquiryresult = $gim['inqcnt'] - $chargeback;
            } else {
              $inquiryresult = $gim['inqcnt'];
            }
         ?>
         <tr>
           <td width="10%"><?php echo $gen['fld_accountno']; ?></td>
           <td><?php echo $gen['name']; ?></td>
           <td style="text-align: right;" width="9%"><?php echo number_format($inquiryresult); ?></td>
           <!-- <td style="text-align: right;"><?php echo number_format($available); ?></td>
           <td style="background-color: <?php echo $alcolor; ?>;">
            <center>
              <?php echo round($access_limit_percentage, 2)."%"; ?>
            </center>
           </td> -->
           <!-- <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $altxtColor; ?>;"><?php echo number_format($total_credits); ?></td> -->
           
           <!-- <td style="background-color: <?php echo $apcolor; ?>;">
            <center>
              <?php echo round($current_balance_percentage, 2)."%"; ?>
            </center>
           </td> -->
           
         </tr>
         <?php

              $total_inquiries += $gim['inqcnt'];
              $total_cb += $$chargeback;
            }
         ?>
<!--          <tr>
            <td width="10%"></td>
            <td width="40%"><b>TOTAL:</b></td>
            <td style="text-align: right;" width="9%"><b><?php echo number_format($total_inquiries); ?></b></td>
          </tr> -->
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
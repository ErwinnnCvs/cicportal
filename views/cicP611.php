<?php

if ($_GET['nid'] == 6 and $_GET['sid'] == 1 and $_GET['rid'] == 1) {
  $access_label = "SAE Access";
  $sae = "SAE09670";
  $sae_label = "CIBI Information Inc";
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

$get_access_limit = $dbh1->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$sae."'");
$gal=$get_access_limit->fetch_array();

// $last_day_prev_month = date('Y-m-d', strtotime('last day of previous month'));
$last_day_prev_month = date("Y-m-d", strtotime(date('Y-m-d', strtotime($det[0].'-'.$det[1].'-01')).'-1 day'));

$get_beginning_balance = $dbh5->query("SELECT fld_beginbalance FROM tbcrbillingbalance WHERE fld_provcode = '".$sae."' and fld_stmt_date = '".$last_day_prev_month."' ");
$gbb=$get_beginning_balance->fetch_array();

$get_deposit_month = $dbh5->query("SELECT SUM(fld_converted_credits) as amount FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_payment_date LIKE '".date("Y-m")."%' and fld_transaction_type <> 'MIGRATED' GROUP BY fld_acct_no");
$gdm=$get_deposit_month->fetch_array();

$current_balance = ($gbb['fld_beginbalance'] + $gdm['amount']) - $gcim['inqprice'];

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
        $sql=$dbh5->query("SELECT SUBSTR(fld_inqdate, 1, 7) AS ym FROM tbinquiries WHERE SUBSTR(fld_inqdate, 1, 7) >= '2021-08' GROUP BY SUBSTR(fld_inqdate, 1, 7)");
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
      <!-- <div class="float-right">
        <table class="table table-bordered">
          <tr>
            <td><b>Available Credits: </b></td>
            <td style="text-align: right;"><?php echo number_format($current_balance); ?></td>
          </tr>
          <tr>
            <td><b>Consumption: </b></td>
            <td><center><?php echo round($current_balance_percentage, 2)."%"; ?></center></td>
          </tr>
        </table>
      </div> -->
      <br>
      <table class="table table-bordered table-hover table-sm">
        <thead>
          <tr>
            <th width="15%">Account Number</th>
            <th width="40%">Entity</th>
            <th style="text-align: right;" width="9%">Available Credits</th>
            <th style="text-align: right;" width="9%">Reorder Point</th>
            <!-- <th width="9%"><center>%</center></th> -->
          </tr>
        </thead>
        <tbody>
         <?php
         #CIBI
          $get_cibi_inquiry_month = $dbh5->query("SELECT fld_branchcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND (fld_provcode = 'SAE09670' OR fld_branchcode = 'SCIBI' OR (fld_branchcode LIKE 'SAE%' AND fld_usercode LIKE '%CIB')) AND fld_inqresult = 1 AND ((SUBSTR(fld_usercode, -3) <> 'DQ1' or SUBSTR(fld_usercode, -3) <> 'DQ2' or SUBSTR(fld_usercode, -3) <> 'DQ3' ) AND fld_sourcecode <> 'CB_ME')");

          if (mysqli_num_rows($get_cibi_inquiry_month)==0) {
          ?>
          <tr>
            <td colspan="5"><center><b class="text-danger">No data</b></center></td>
          </tr>
          <?php
          } else {
          while($gcim=$get_cibi_inquiry_month->fetch_array()){

            if ($gcim['fld_branchcode'] == "SCIBI") {
              $sae_provider_code = "SAE09670";
            }
            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_alcons_status, fld_alwarning_ts, fld_alcut_ts, fld_alnoccut_ts, fld_apcons_status, fld_apwarning_ts, fld_apcut_ts, fld_apnoccut_ts, fld_apcredits_reorderpt, fld_accountno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$sae_provider_code."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();

            $access_limit_percentage = ($gim['inqcnt'] / $gen['fld_access_limit']) * 100;

            $get_access_limit = $dbh1->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$sae_provider_code."'");
            $gal=$get_access_limit->fetch_array();


            $get_deposit_month = $dbh5->query("SELECT SUM(fld_converted_credits) as credit_amount FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_transaction_type <> 'MIGRATED' GROUP BY fld_acct_no");
            $gdm=$get_deposit_month->fetch_array();

            $check_previous_cb_from_payment = $dbh5->query("SELECT SUM(fld_converted_credits) as amount, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_charge_back = 1 and fld_transaction_type <> 'MIGRATED' GROUP BY fld_acct_no");
            $cpcbp=$check_previous_cb_from_payment->fetch_array();

            $picked_date = strtotime($det[0]."-".$det[1]."-01");
            $date1 = date('Y-m-d', strtotime("+1 month", $picked_date));

            $get_next_beginning_balance_cb = $dbh1->query("SELECT fld_chargeback FROM tbbillingbalance WHERE fld_provcode = '".$sae_provider_code."' and fld_stmt_date = '".$date1." '");
            $gnbbcb=$get_next_beginning_balance_cb->fetch_array();

            $beggining_cb_balance = $dbh1->query("SELECT fld_chargeback FROM tbbillingbalance WHERE fld_provcode = '".$sae_provider_code."' and fld_stmt_date = '".$det[0]."-".$det[1]."-01"."'");
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
            
            $get_noc_ts = $dbh1->query("SELECT fld_aeis_noc_ts FROM tbentities WHERE fld_ctrlno = '".$gen['fld_ctrlno']."'");
            $gnts=$get_noc_ts->fetch_array();

            if ($det[0]."-".$det[1] == date("Y-m", strtotime($gnts['fld_aeis_noc_ts']))) {
              // echo "<br>string".$gal['fld_accountno'];
              $get_initial_payment = $dbh5->query("SELECT SUM(fld_converted_credits) as fld_converted_credits FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_transaction_type <> 'MIGRATED'");
              $gip=$get_initial_payment->fetch_array();

              $totalamount = $gdm['amount'] + $gip['fld_converted_credits'];
            } else {
              $totalamount = $gdm['amount'];
            }
            
            if ($chargeback > 0) {
              $inqcostresult = $inquiryresult;# * $gic['fld_cost']
              $total_credits = $gbb['fld_beginbalance'] + $totalamount;
              $current_balance = ($gbb['fld_beginbalance'] + $totalamount) - $inqcostresult;
            } else {
              $total_credits = $gbb['fld_beginbalance'] + $totalamount;
              $current_balance = ($gbb['fld_beginbalance'] + $totalamount) - $gim['inqcnt'];
            }

            $current_balance_percentage = ((10000 - $current_balance) / 10000) * 100;

            // if ($access_limit_percentage >= 50 and $access_limit_percentage < 80) {
            //   $alcolor = "#ffffff";
            //   $altxtColor = "#000000";
            // } elseif ($access_limit_percentage >= 80) {
            //   $alcolor = "#ffffff";
            //   $altxtColor = "#ffffff";
            // } else {
            //   $alcolor = "#ffffff";
            //   $altxtColor = "#000000";
            // }

                // if ($current_balance_percentage <= 50 and $current_balance_percentage < 80) {
                //   $apcolor = "#f5f536";
                //   $aptxtColor = "#000000";
                // } elseif ($current_balance_percentage <= 80) {
                //   $apcolor = "#b8120f";
                //   $aptxtColor = "#ffffff";
                // } else {
                //   $apcolor = "#ffffff";
                //   $aptxtColor = "#000000";
                // }

            $available = $gen['fld_access_limit'] - $inquiryresult;

            if(!empty($gen['fld_apcredits_reorderpt'])){ 
              // echo "<b>".$gen['fld_apcredits_reorderpt']."</b>";
              $reorder_point = $gen['fld_apcredits_reorderpt'];
            } else { 
              // echo floor($gbb['fld_beginbalance'] / 2);
              $reorder_point = $gbb['fld_beginbalance'] / 2;
            }
            // <= ((reorder point * 1.5) and > (reorder point))
            // <= (redorder point)
            // <= 0

            if ($current_balance <= 0) {
              $scenario = "Scenario 3: Available Credits less than or equal to 0";
              // $dbh4->query("UPDATE tbentities SET fld_apcredits_status =  WHERE fld_ctrlno = '".$gen['fld_ctrlno']."'");
            } elseif ($current_balance <= ($reorder_point * 1.5) and $current_balance > $reorder_point) {
              $scenario = "Scenario 1: 150%";#Available Credits <= ((Reorder point * 1.5) and > (Reorder point))
              // $dbh4->query("UPDATE tbentities SET fld_apcredits_status = 1 WHERE fld_ctrlno = '".$gen['fld_ctrlno']."'");
            } elseif ($current_balance <= $reorder_point) {
              $scenario = "Scenario 2: Available Credits is less than or equal to Reorder Point";
              // $dbh4->query("UPDATE tbentities SET fld_apcredits_status = 2 WHERE fld_ctrlno = '".$gen['fld_ctrlno']."'");
            }


         ?>
         <tr>
           <td><?php echo $gen['fld_accountno']; ?></td>
           <td><?php echo $gen['name']; ?></td>
           <td style="text-align: right;"><?php echo number_format($current_balance); ?></td>
           <td style="text-align: right;"><?php echo number_format($reorder_point); ?></td>
           <!-- <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $txtColor; ?>"><center><?php echo round($access_limit_percentage, 2)."%"; ?></center></td> -->
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
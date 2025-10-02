<?php
require_once("../config.php");
$det[0] = date("Y");
$det[1] = date("m");
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Billing Monitoring</title>
  </head>
  <body>
    <table class="table table-bordered">
      <thead>
        <tr>
          <!-- <th>Account No</th> -->
          <th>Company Name</th>
          <!-- <th>Inquiries</th> -->
          <!-- <th>Charge Back</th> -->
          <th>Available Credits</th>
          <th>Reorder Point</th>
          <th> * 1.5</th>
          <th>Scenario</th>
        </tr>
      </thead>
      <tbody>
        <?php

          $get_inquiry_month = $dbh->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND fld_branchcode = 'USERS' AND fld_inqresult = 1 GROUP BY fld_provcode");
          while($gim=$get_inquiry_month->fetch_array()){

            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_alcons_status, fld_alwarning_ts, fld_alcut_ts, fld_alnoccut_ts, fld_apcons_status, fld_apwarning_ts, fld_apcut_ts, fld_apnoccut_ts, fld_apcredits_reorderpt FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gim['fld_provcode']."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();

            $access_limit_percentage = ($gim['inqcnt'] / $gen['fld_access_limit']) * 100;

            $get_access_limit = $dbh->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$gim['fld_provcode']."'");
            $gal=$get_access_limit->fetch_array();

            $get_beginning_balance = $dbh->query("SELECT fld_converted_credits FROM tbbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and YEAR(fld_stmt_date) = '".$det[0]."' and MONTH(fld_stmt_date) = '".$det[1]."' ");
            $gbb=$get_beginning_balance->fetch_array();



            $get_deposit_month = $dbh->query("SELECT SUM(fld_converted_credits) as credit_amount FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' GROUP BY fld_acct_no");
            $gdm=$get_deposit_month->fetch_array();

            $check_previous_cb_from_payment = $dbh->query("SELECT SUM(fld_converted_credits) as amount, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_charge_back = 1 GROUP BY fld_acct_no");
            $cpcbp=$check_previous_cb_from_payment->fetch_array();

            $picked_date = strtotime($det[0]."-".$det[1]."-01");
            $date1 = date('Y-m-d', strtotime("+1 month", $picked_date));

            $get_next_beginning_balance_cb = $dbh->query("SELECT fld_chargeback FROM tbbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and fld_stmt_date = '".$date1." '");
            $gnbbcb=$get_next_beginning_balance_cb->fetch_array();

            $beggining_cb_balance = $dbh->query("SELECT fld_chargeback FROM tbbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and fld_stmt_date = '".$det[0]."-".$det[1]."-01"."'");
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

            $get_noc_ts = $dbh->query("SELECT fld_aeis_noc_ts FROM tbentities WHERE fld_ctrlno = '".$gen['fld_ctrlno']."'");
            $gnts=$get_noc_ts->fetch_array();

            if ($det[0]."-".$det[1] == date("Y-m", strtotime($gnts['fld_aeis_noc_ts']))) {
              // echo "<br>string".$gal['fld_accountno'];
              $get_initial_payment = $dbh->query("SELECT SUM(fld_converted_credits) as fld_converted_credits FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."'");
              $gip=$get_initial_payment->fetch_array();

              $totalamount = $gdm['credit_amount'] + $gip['fld_converted_credits'];
            } else {
              $totalamount = $gdm['credit_amount'];
            }
            
            if ($chargeback > 0) {
              $inqcostresult = $inquiryresult;# * $gic['fld_cost']
              $total_credits = $gbb['fld_converted_credits'] + $totalamount;
              $current_balance = ($gbb['fld_converted_credits'] + $totalamount) - $inqcostresult;
            } else {
              $total_credits = $gbb['fld_converted_credits'] + $totalamount;
              $current_balance = ($gbb['fld_converted_credits'] + $totalamount) - $gim['inqcnt'];
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

            // if(!empty($gen['fld_apcredits_reorderpt'])){ 
              // echo "<b>".$gen['fld_apcredits_reorderpt']."</b>";
            $reorder_point = $gen['fld_apcredits_reorderpt'];
            // } else { 
              // echo floor($gbb['fld_converted_credits'] / 2);
              // $reorder_point = $gbb['fld_converted_credits'] / 2;
            // }
            // <= ((reorder point * 1.5) and > (reorder point))
            // <= (redorder point)
            // <= 0
            if($gal['fld_accountno'] == "101705000005") {
              $current_balance = 0;
            }

            if ($current_balance <= 0) {
              $scenario = "Scenario 3: Available Credits less than or equal to 0";
            } elseif ($current_balance <= (($reorder_point * 1.5) and $current_balance > ($reorder_point))) {
              $scenario = "Scenario 1: 150%";#Available Credits <= ((Reorder point * 1.5) and > (Reorder point))
            } elseif ($current_balance <= $reorder_point) {
              $scenario = "Scenario 2: Available Credits is less than or equal to Reorder Point";
            }

            



        ?>
        <tr>
          <td><?php echo $gen['name']; ?></td>
          <td><u><?php echo number_format($current_balance); ?></u></td>
          <td><?php echo $reorder_point; ?></td>
          <td><?php echo $reorder_point * 1.5; ?></td>
          <td><?php echo $scenario; ?></td>
        </tr>
        <?php
          }
        ?>
      </tbody>
    </table>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
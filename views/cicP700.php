<?php

if ($_GET['nid'] == 7 and $_GET['sid'] == 0 and $_GET['rid'] == 0) {
  $access_label = "SE Access";
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
            <a href="main.php?nid=7&sid=0&rid=0"><li class="dropdown-item">SE Access</li></a>
            <a href="main.php?nid=7&sid=1&rid=1"><li class="dropdown-item">SAE Access</li></a>
          </ul>
        </div>
      </div>
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
      <h3 class="page-header"><?php echo $labelyearmonth; ?></h3>
       
      </center>

      <table class="table table-bordered table-hover table-sm">
        <thead>
          <tr>
            <th width="15%">Account Number</th>
            <th width="40%">Entity</th>
            <th style="text-align: right;" width="9%">Inquiries</th>
            <th style="text-align: right;" width="9%">Charge Back</th>
            <th style="text-align: right;" width="9%">Sales</th>
          </tr>
        </thead>
        <tbody>
         <?php

          $get_inquiry_month = $dbh->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND fld_branchcode = 'USERS' AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL')) and fld_provcode <> 'OT888888' GROUP BY fld_provcode");
          while($gim=$get_inquiry_month->fetch_array()){

            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_alcons_status, fld_alwarning_ts, fld_alcut_ts, fld_alnoccut_ts, fld_apcons_status, fld_apwarning_ts, fld_apcut_ts, fld_apnoccut_ts FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gim['fld_provcode']."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();

            $get_access_limit = $dbh->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$gim['fld_provcode']."'");
            $gal=$get_access_limit->fetch_array();

            $check_previous_cb_from_payment = $dbh->query("SELECT SUM(fld_amount) as amount, fld_datetime FROM tbbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_charge_back = 1 GROUP BY fld_acct_no");
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

         ?>
         <tr>
           <td><?php echo $gal['fld_accountno']; ?></td>
           <td><?php echo $gen['name']; ?></td>
           <td style="text-align: right;"><?php echo number_format($inquiryresult); ?></td>
           <td style="text-align: right;"><?php echo number_format($chargeback); ?></td>
           <td style="text-align: right;"><?php echo number_format($gim['inqprice']); ?></td>
         </tr>
         <?php
              $totalinq += $inquiryresult;
              $totalcb += $chargeback;
              $totalsales += $gim['inqprice'];
            }
         ?>
        </tbody>
      </table>
      <table class="table table-bordered table-sm">
        <tbody>
          <tr>
            <td  width="15%"></td>
            <td style="text-align: right;" width="40%"><b>TOTAL (Inquiries/Charge Back/Sales):</b></td>
            <td style="text-align: right;" width="9%"><b><?php echo number_format($totalinq); ?></b></td>
            <td style="text-align: right;" width="9%"><b><?php echo number_format($totalcb); ?></b></td>
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
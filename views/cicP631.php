<?php
if ($_GET['date']) {
  $date = explode("-", $_GET['date']);
}else{
  $date = explode("-", date("Y-m"));
}


$det[0] = $date[0];
$det[1] = $date[1];

$labelyearmonth = $abbrmo[(int)$det[1]]." ".$det[0];
$no_of_days = cal_days_in_month(CAL_GREGORIAN, $date[1], $date[0]);

$fromsel[$_POST['from_day']] = " selected";
$tosel[$_POST['to_day']] = " selected";
$namsel[$_POST['entity_name']] = " selected";

if ($_POST['sbtApply']) {
  $from = $_POST['from_day'];
  $to = $_POST['to_day'];

  if ($to > $from) {
    # code...
  } else {
    
  }

  $exp = explode("-", $_POST['entity_name']);

  $get_deposit_month = $dbh->query("SELECT SUM(fld_amount) as amount, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$exp[1]."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' GROUP BY fld_acct_no");
  $gdm=$get_deposit_month->fetch_array();

  $get_beginning_balance = $dbh->query("SELECT fld_beginbalance FROM tbcrbillingbalance WHERE fld_provcode = '".$exp[0]."' and fld_stmt_date = '".date("Y-m-t", strtotime($det[0].'-'.$det[1].'-01 -1 month'))."' ");
  $gbb=$get_beginning_balance->fetch_array();

  $beginning_balance = $gbb['fld_beginbalance'];
}
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-body">
      <center>
      <h3 class="page-header"><a href="main.php?nid=6&sid=0&rid=1&date=<?php echo $det[0]."-".$det[1]; ?>"><?php echo $labelyearmonth; ?></a></h3>
      <form method="post">
        <label>Company Name</label>
        <select class="form-control" name="entity_name" style="width: 30%;">
          <option selected disabled>----SELECT----</option>
          <?php
            $get_se_based_on_month = $dbh->query("SELECT fld_provcode FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND fld_branchcode = 'USERS' AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL')) GROUP BY fld_provcode");
            while ($gsbom=$get_se_based_on_month->fetch_array()) {
              $get_entity_name_gsbom = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_accountno FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gsbom['fld_provcode']."' GROUP BY name ASC");
              $gen_gsbom=$get_entity_name_gsbom->fetch_array();
              echo "<option value='".$gsbom['fld_provcode']."-".$gen_gsbom['fld_accountno']."'".$namsel[$gsbom['fld_provcode']."-".$gen_gsbom['fld_accountno']].">".$gen_gsbom['name']."</option>";
            }
          ?>
        </select>
        <br>
        <table>
          <tbody>
            <tr>
              <td>
                <label>From:</label>
                <select name="from_day" class="form-control">
                  <?php
                    foreach (range(1, $no_of_days) as $no) {
                      echo "<option value='".$no."'".$fromsel[$no].">".$no."</option>";
                    }
                  ?>
                </select>
              </td>
              <td>
                <label>To:</label>
                <select name="to_day" class="form-control">
                  <?php
                    foreach (range(1  , $no_of_days) as $no) {
                      echo "<option value='".$no."'".$tosel[$no].">".$no."</option>";
                    }
                  ?>
                </select>
              </td>
              <td>
                <br>
                <button type="submit" name="sbtApply" value="1" class="btn btn-primary">Apply</button>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
      <br>
       
      </center>

      <label class="float-right">Beginning Balance: <?php echo $gbb['fld_beginbalance']; ?></label>
      <table class="table table-bordered table-hover table-sm">
        <thead>
          <tr>
            <th width="10%">Account Number</th>
            <th width="2%"><center>Day</center></th>
            <th style="text-align: right;" width="9%">Inquiries</th>
            <th style="text-align: right;" width="9%">Price</th>
            <th style="text-align: right;" width="9%">Replenish</th>
            <th style="text-align: right;" width="9%">Balance</th>
          </tr>
        </thead>
        <tbody>
         <?php
          $count = 0;
          $length = count(range($from, $to));
          foreach (range($from, $to) as $no_day) {
            $get_inquiry_day = $dbh->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE fld_provcode = '".$exp[0]."' and YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND DAY(fld_inqdate) = '".$no_day."' AND fld_branchcode = 'USERS' AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL')) GROUP BY fld_provcode");
            $gid=$get_inquiry_day->fetch_array();

            $get_deposit_daily = $dbh->query("SELECT fld_amount, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$exp[1]."' and fld_datetime LIKE '".$det[0]."-".$det[1]."-".sprintf("%02d", $no_day)."%' GROUP BY fld_acct_no");
            $gdd=$get_deposit_daily->fetch_array();
            $count++;

            // $get_noc_ts = $dbh->query("SELECT fld_aeis_noc_ts FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$exp[0]."'");
            // $gnts=$get_noc_ts->fetch_array();


            // if (date("Y-m") == date("Y-m", strtotime($gnts['fld_aeis_noc_ts']))) {
            //   $get_initial_payment = $dbh->query("SELECT fld_amount FROM tbcrbillingpayment WHERE fld_acct_no = '".$gen_gsbom['fld_accountno']."'");
            //   $gip=$get_initial_payment->fetch_array();

            //   $totalamount = $gdm['amount'] + $gip['fld_amount'];
            // } else {
            //   $totalamount = $gdm['amount'];
            // }
            if ($count == 1) {
              $balance = ($beginning_balance) - $gid['inqprice'];
            } else {
              if ($count == date("d", strtotime($gdd['fld_datetime']))) {
                $balance = ($balance + $gdd['fld_amount']) - $gid['inqprice'];
              } else {
                $balance = ($balance) - $gid['inqprice'];
              }
              
            }

         ?>
         <tr>
           <td><?php echo $exp[1]; ?></td>
           <td><center><?php echo $no_day; ?></center></td>
           <td style="text-align: right; color: <?php if($gid['inqcnt'] <= 0) { echo "red;"; }?>"><?php if($gid['inqcnt'] > 0) { ?> <a href="main.php?nid=6&sid=3&rid=2&dte=<?php echo $_GET['date'].'-'.sprintf("%02d", $no_day); ?>&provcode=<?php echo $exp[0]; ?>"> <?php echo $gid['inqcnt']; ?> </a> <?php } else { echo "0"; } ?></td>
           <td style="text-align: right; color: <?php if($gid['inqprice'] <= 0) { echo "red;"; }?>"><?php if($gid['inqprice'] > 0) { echo $gid['inqprice']; } else { echo "0"; } ?></td>
           <td style="text-align: right;"><?php echo $gdd['fld_amount']; ?></td>
           <td style="text-align: right;"><?php echo $balance; ?></td>
         </tr>
         <?php
            $total_inquiries += $gid['inqcnt'];
            $total_price += $gid['inqprice'];
            $total_balance += $balance;
            if ($count === $length) {
              $final_balance = $balance;
            }
          }
         ?>
         <tr>
           <td></td>
           <td><center><b>TOTAL:</b></center></td>
           <td style="text-align: right;"><b><?php echo $total_inquiries; ?></b></td>
           <td style="text-align: right;"><b><?php echo $total_price; ?></b></td>
           <td style="text-align: right;"><b></b></td>
           <td style="text-align: right;"><b><?php echo $final_balance; ?></b></td>
         </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
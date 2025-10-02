<?php

if ($_GET['nid'] == 6 and $_GET['sid'] == 0 and $_GET['rid'] == 0) {
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
            <a href="main.php?nid=6&sid=0&rid=0"><li class="dropdown-item">SE Access</li></a>
            <a href="main.php?nid=6&sid=1&rid=1"><li class="dropdown-item">SAE Access</li></a>
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

      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="15%">Account Number</th>
            <th width="40%">Entity</th>
            <th style="text-align: right;" width="9%">Inquiries</th>
            <th style="text-align: right;" width="9%">Access Limit</th>
            <th width="9%"><center>%</center></th>
            <th style="text-align: right;" width="9%">Current Balance</th>
            <th width="9%"><center>%</center></th>
          </tr>
        </thead>
        <tbody>
         <?php
          $get_inquiry_month = $dbh->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND fld_branchcode = 'USERS' AND ((fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode LIKE '%1-100%') OR (fld_sourcecode = 'CB_ME' AND fld_errorcode = 'NULL') OR (fld_sourcecode = 'CB_NAE' AND fld_errorcode = 'NULL')) GROUP BY fld_provcode");
          while($gim=$get_inquiry_month->fetch_array()){

            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_alcons_status, fld_alwarning_ts, fld_alcut_ts, fld_alnoccut_ts, fld_apcons_status, fld_apwarning_ts, fld_apcut_ts, fld_apnoccut_ts FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gim['fld_provcode']."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();

            $access_limit_percentage = ($gim['inqcnt'] / $gen['fld_access_limit']) * 100;

            $get_access_limit = $dbh->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$gim['fld_provcode']."'");
            $gal=$get_access_limit->fetch_array();

            $get_beginning_balance = $dbh->query("SELECT fld_beginbalance FROM tbbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and YEAR(fld_stmt_date) = '".date("Y")."' and MONTH(fld_stmt_date) = '".date('m')."' ");
            $gbb=$get_beginning_balance->fetch_array();

            $get_deposit_month = $dbh->query("SELECT SUM(fld_amount) as amount FROM tbbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_payment_date LIKE '".date("Y-m")."%' GROUP BY fld_acct_no");
            $gdm=$get_deposit_month->fetch_array();

            $current_balance = ($gbb['fld_beginbalance'] + $gdm['amount']) - $gim['inqprice'];

            $current_balance_percentage = ((10000 - $current_balance) / 10000) * 100;

            if ($access_limit_percentage >= 50 and $access_limit_percentage < 80) {
              $alcolor = "#f5f536";
              $altxtColor = "#000000";
            } elseif ($access_limit_percentage >= 80) {
              $alcolor = "#b8120f";
              $altxtColor = "#ffffff";
            } else {
              $alcolor = "#ffffff";
              $altxtColor = "#000000";
            }

            if ($current_balance_percentage >= 50 and $current_balance_percentage < 80) {
              $apcolor = "#f5f536";
              $aptxtColor = "#000000";
            } elseif ($current_balance_percentage >= 80) {
              $apcolor = "#b8120f";
              $aptxtColor = "#ffffff";
            } else {
              $apcolor = "#ffffff";
              $aptxtColor = "#000000";
            }

         ?>
         <tr>
           <td><?php echo $gal['fld_accountno']; ?></td>
           <td><?php echo $gen['name']; ?></td>
           <td style="text-align: right; background-color: <?php echo $alcolor; ?>; color: <?php echo $altxtColor; ?>;"><?php echo number_format($gim['inqcnt']); ?></td>
           <td style="text-align: right; background-color: <?php echo $alcolor; ?>; color: <?php echo $altxtColor; ?>;"><?php echo number_format($gen['fld_access_limit']); ?></td>
           <td style="background-color: <?php echo $alcolor; ?>;">
            <center>
              <?php echo round($access_limit_percentage, 2)."%"; ?>
              <?php
                if ($gen['fld_alcons_status'] == 1 && $gen['fld_alwarning_ts'] == NULL) {
                  echo "<br><small class='text-danger'>(For Sending)</small>";
                } elseif ($gen['fld_alcons_status'] == 1 && $gen['fld_alwarning_ts'] != NULL) {
                  echo "<br><small class='text-danger'>(Email warning sent))</small>";
                } elseif ($gen['fld_alcons_status'] == 2 && $gen['fld_alcut_ts'] == NULL) {
                  echo "<br><small class='text-danger'>(For sending deactivation)</small>";
                } elseif ($gen['fld_alcons_status'] == 2 && $gen['fld_alcut_ts'] != NULL) {
                  echo "<br><small class='text-danger'>(Email deactivation sent)</small>";
                } elseif ($gen['fld_alcons_status'] == 3 && $gen['fld_alnoccut_ts'] == NULL) {
                  echo "<br><small class='text-danger'>(Will email to NOC)</small>";
                } elseif ($gen['fld_alcons_status'] == 3 && $gen['fld_alnoccut_ts'] == NULL) {
                  echo "<br><small class='text-danger'>(Email deactivation sent to NOC)</small>";
                }
              ?>
            </center>
           </td>
           <td style="text-align: right; background-color: <?php echo $apcolor; ?>; color: <?php echo $aptxtColor; ?>;"><?php echo number_format($current_balance); ?></td>
          <td style="background-color: <?php echo $apcolor; ?>; color: <?php echo $aptxtColor; ?>;">
            <center>
              <?php echo round($current_balance_percentage, 2)."%"; ?>
              <?php
                if ($gen['fld_apcons_status'] == 1 && $gen['fld_apwarning_ts'] == NULL) {
                  echo "<br><small class='text-danger'>(For Sending)</small>";
                } elseif ($gen['fld_apcons_status'] == 1 && $gen['fld_apwarning_ts'] != NULL) {
                  echo "<br><small class='text-danger'>(Email warning sent)</small>";
                } elseif ($gen['fld_apcons_status'] == 2 && $gen['fld_apcut_ts'] == NULL) {
                  echo "<br><small class='text-white'>(For sending deactivation)</small>";
                } elseif ($gen['fld_apcons_status'] == 2 && $gen['fld_apcut_ts'] != NULL) {
                  echo "<br><small class='text-white'>(Email deactivation sent)</small>";
                } elseif ($gen['fld_apcons_status'] == 3 && $gen['fld_apnoccut_ts'] == NULL) {
                  echo "<br><small class='text-white'>(Will email to NOC)</small>";
                } elseif ($gen['fld_apcons_status'] == 4 && $gen['fld_apnoccut_ts'] != NULL) {
                  echo "<br><small class='text-white'>(Email deactivation sent to NOC)</small>";
                }
              ?>    
            </center>
          </td>
         </tr>
         <?php
              $total_inquiries += $gim['inqcnt'];
            }
         ?>
        </tbody>
      </table>
      <table class="table table-bordered">
        <tr>
          <td width="15%"></td>
          <td style="text-align: right;" width="40%"><b>Total:</b></td>
          <td style="text-align: right;" width="9%"><b><?php echo $total_inquiries; ?></b></td>
          <td width="9%"></td>
          <td width="9%"></td>
          <td width="9%"></td>
          <td width="9%"></td>
        </tr>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
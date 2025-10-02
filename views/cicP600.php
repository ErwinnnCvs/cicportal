<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
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
        <a href="main.php?nid=6&sid=4&rid=1" class="float-right">Balance Monitoring (Old)</a>
    </div>
    <div class="card-body">
      <center>
      <form method="post">
        <select name="yearmonth" class="form-control" style="width: 10%;" onchange="submit()">
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
      <h3 class="page-header"><a href="main.php?nid=6&sid=3&rid=1&date=<?php echo $det[0]."-".$det[1]; ?>"><?php echo $labelyearmonth; ?></a></h3>
       
      </center>

      <table class="table table-bordered table-hover table-sm" id="se_inquiries">
        <thead>
          <tr>
            <th width="15%">Account Number</th>
            <th width="40%">Entity</th>
            <th width="9%">Inquiries</th>
            <th width="9%">Charge Back</th>
            <!-- <th width="9%">Access Limit</th>
            <th width="9%"><center>%</center></th> -->
            <!-- <th width="9%">Credits</th> -->
            <th width="9%">Available Credits</th>
            <th width="9%">Reorder Point</th>
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
          $get_inquiry_cost = $dbh5->query("SELECT * FROM tbinquirycost WHERE fld_effectivity_date > '".$det[0]."-".$det[1]."-01 8:00:00' and fld_costtype = 1");
          $gic=$get_inquiry_cost->fetch_array();

          $get_inquiry_month = $dbh5->query("SELECT fld_provcode, SUM(fld_inqcount) as inqcnt, SUM(fld_inq_price) as inqprice FROM tbinquiries WHERE YEAR(fld_inqdate) = '".$det[0]."' and MONTH(fld_inqdate) = '".$det[1]."' AND (fld_branchcode = 'USERS' or fld_branchcode = 'BATCH') AND fld_inqresult = 1 GROUP BY fld_provcode");
          while($gim=$get_inquiry_month->fetch_array()){

            $get_entity_name = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_access_limit, fld_alcons_status, fld_alwarning_ts, fld_alcut_ts, fld_alnoccut_ts, fld_apcons_status, fld_apwarning_ts, fld_apcut_ts, fld_apnoccut_ts, fld_apcredits_reorderpt FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gim['fld_provcode']."' GROUP BY name ASC");
            $gen=$get_entity_name->fetch_array();

            $access_limit_percentage = ($gim['inqcnt'] / $gen['fld_access_limit']) * 100;

            $get_access_limit = $dbh1->query("SELECT fld_accountno FROM tbbilling WHERE fld_provcode = '".$gim['fld_provcode']."'");
            $gal=$get_access_limit->fetch_array();

            // $last_day_prev_month = date('Y-m-d', strtotime('last day of previous month'));
            // $last_day_prev_month = date($det[0].'-'.$det[1].'-'.'d', strtotime('last day of previous month'));
            $last_day_prev_month = date("Y-m-d", strtotime(date('Y-m-d', strtotime($det[0].'-'.$det[1].'-01')).'-1 day'));
            // $last_day_prev_month = date ('last day of previous month');
            // echo $last_day_prev_month.'<br/>';
            $get_beginning_balance = $dbh5->query("SELECT fld_beginbalance FROM tbcrbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and fld_stmt_date = '".$last_day_prev_month."'");
            $gbb=$get_beginning_balance->fetch_array();



            ###$get_deposit_month = $dbh1->query("SELECT SUM(fld_converted_credits) as amount FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_transaction_type <> 'MIGRATED' GROUP BY fld_acct_no");
            ###$gdm=$get_deposit_month->fetch_array();

            $check_previous_cb_from_payment = $dbh5->query("SELECT SUM(fld_converted_credits) as amount, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_charge_back = 1 and fld_transaction_type <> 'MIGRATED' GROUP BY fld_acct_no");
            $cpcbp=$check_previous_cb_from_payment->fetch_array();

            $picked_date = strtotime($det[0]."-".$det[1]."-01");
            $date1 = date('Y-m-d', strtotime("+1 month", $picked_date));

            $get_next_beginning_balance_cb = $dbh5->query("SELECT fld_chargeback FROM tbcrbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and fld_stmt_date = '".$date1." '");
            $gnbbcb=$get_next_beginning_balance_cb->fetch_array();

            
            $beggining_cb_balance = $dbh5->query("SELECT fld_chargeback FROM tbcrbillingbalance WHERE fld_provcode = '".$gim['fld_provcode']."' and fld_stmt_date = '".$det[0]."-".$det[1]."-01"."'");
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

            /*
            if ($det[0]."-".$det[1] == date("Y-m", strtotime($gnts['fld_aeis_noc_ts']))) {
              // echo "<br>string".$gal['fld_accountno'];
              $get_initial_payment = $dbh1->query("SELECT SUM(fld_converted_credits) as fld_converted_credits FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_transaction_type <> 'MIGRATED'");
              $gip=$get_initial_payment->fetch_array();

              $totalamount = $gdm['amount'] + $gip['fld_converted_credits'];
            } else {
              $totalamount = $gdm['amount'];
            }*/
            if ($det[0]."-".$det[1] == date("Y-m", strtotime($gnts['fld_aeis_noc_ts']))) {
              $all_payments = $dbh5->query("SELECT fld_converted_credits, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_transaction_type <> 'MIGRATED'");
            } else {
              $all_payments = $dbh5->query("SELECT fld_converted_credits, fld_datetime FROM tbcrbillingpayment WHERE fld_acct_no = '".$gal['fld_accountno']."' and fld_datetime LIKE '".$det[0]."-".$det[1]."%' and fld_transaction_type <> 'MIGRATED'");
            }
            $totalamount = 0;
            $payments = [];
            while ($rap = $all_payments->fetch_array()) {
              $payments[strtotime($rap['fld_datetime'])] = $rap['fld_converted_credits'];
              $totalamount = $totalamount + $rap['fld_converted_credits'];
            }

            if ($chargeback > 0) {
              $inqcostresult = $inquiryresult;# * $gic['fld_cost']
              $total_credits = $gbb['fld_beginbalance'] + $totalamount;
              $current_balance = ($gbb['fld_beginbalance'] + $totalamount) - $inqcostresult."sel1";
            } else {
              $total_credits = $gbb['fld_beginbalance'] + $totalamount;
              $current_balance = (($gbb['fld_beginbalance'] + $totalamount) - $gim['inqcnt'])."sel2";
            }
#            echo $current_balance."gil";

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
         ?>
         <tr>
           <td><?php echo $gal['fld_accountno']; ?></td>
           <td><?php echo $gen['name']; ?></td>
           <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $altxtColor; ?>;"><?php echo number_format($inquiryresult); ?></td>
           <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $altxtColor; ?>;"><?php echo number_format($chargeback); ?></td>
           <!-- <td style="text-align: right;"><?php echo number_format($available); ?></td>
           <td style="background-color: <?php echo $alcolor; ?>;">
            <center>
              <?php echo round($access_limit_percentage, 2)."%"; ?>
            </center>
           </td> -->
           <!-- <td style="background-color: <?php echo $alcolor; ?>; color: <?php echo $altxtColor; ?>;"><?php echo number_format($total_credits); ?></td> -->
           <td style="background-color: <?php echo $apcolor; ?>; color: <?php echo $aptxtColor; ?>; cursor: pointer;" data-toggle="modal" data-target="#modal-payments<?php echo $gal['fld_accountno']; ?>"><u><?php echo number_format($current_balance); ?></u></td>
            <div class="modal fade" id="modal-payments<?php echo $gal['fld_accountno']; ?>">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title"><?php echo $gen['name']; ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                        <div class="col">
                          <b>BEGINNING CREDIT BALANCE</b>
                        </div>
                        <div class="col" style="text-align: right;">
                          <?php echo number_format($gbb['fld_beginbalance']); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                          <b>ADD: DEPOSITS</b>
                        </div>
                        <div class="col" style="text-align: right;">
                          <div class="row">
                            <div class="col">
                            </div>
                          </div>
                        </div>
                        <hr>
                    </div>
                    <?php
                    if ($payments) {
                      foreach ($payments as $key => $value) {
                    ?>
                      <div class="row">
                          <div class="col">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo date("M j, Y g:i A", $key);?></b>
                          </div>
                          <div class="col" style="text-align: right;">
                            <div class="row">
                              <div class="col">
                              <?php echo number_format($value); ?>
                                
                              </div>
                            </div>
                          </div>
                          <hr>
                      </div>
                    <?php
                      }
                    }else{
                    ?>
                      <div class="row">
                          <div class="col"></div>
                          <div class="col" style="text-align: right;">
                            <div class="row">
                              <div class="col">
                              <?php echo number_format(0); ?>
                                
                              </div>
                            </div>
                          </div>
                          <hr>
                      </div>
                    <?php
                    }
                    ?>
                    
                    <hr>
                    <div class="row">
                        <div class="col">
                          <b>TOTAL</b>
                        </div>
                        <div class="col" style="text-align: right;">
                          <?php echo number_format($total_credits); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                          <b>LESS: INQUIRIES</b>
                        </div>
                        <div class="col" style="text-align: right;">
                          <?php echo number_format($inqcount); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                          <b>CURRENT BALANCE</b>
                        </div>
                        <div class="col" style="text-align: right;">
                          <?php echo number_format($current_balance); ?>
                        </div>
                    </div>
                  </div>
                </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
           <td><?php if(!empty($gen['fld_apcredits_reorderpt'])){ echo "<b>".$gen['fld_apcredits_reorderpt']."</b>"; } else { echo floor($gbb['fld_beginbalance'] / 2); }  ?></td>
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
            <td width="15%"></td>
            <td width="40%"><b>TOTAL:</b></td>
            <td style="text-align: right;" width="9%"><b><?php echo number_format($total_inquiries); ?></b></td>
            <td style="text-align: right;" width="9%"><b><?php echo number_format($total_cb); ?></b></td>
            <td style="text-align: right;" width="9%"></td>
          </tr> -->
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
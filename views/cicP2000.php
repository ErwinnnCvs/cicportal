<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
$btnsel[$_POST['fi_select']] = " selected";
if ($_POST['uplFile']) {
  if ($_FILES["paymentFile"]["type"] == 'text/plain') {
    $handle = fopen($_FILES["paymentFile"]["tmp_name"], "r");
    if (($handle = fopen($_FILES["paymentFile"]["tmp_name"], "r")) !== FALSE) {
      $line = 0;
      $counter_keys  = 0;
      // echo "<pre/>";
      while ($data = fgetcsv($handle, 1000, "\t")) {
        # Get Process Date
        if ($line == 1) {
          $procdate = date('Y-m-d',strtotime(substr($data[0], -8)));
        }
        # Get Total Amount
        if (strpos($data[0], '     TOTAL COLLECTIONS:') !== false) {
            // print_r($data[0]);
          // echo $line." | ".substr($data[0], 49)."<br/>";
          $computed_total = str_replace(',', '', substr($data[0], 49));
        }

        $getaccountno = trim(substr($data[0], 42, 13));
        if (is_numeric($getaccountno) && strlen($getaccountno) == 12) {
          $gettransaction_type = substr($data[0], 16, 6);
          $getbilling_number = substr($data[0], 59, 15);
          $getamount = str_replace( ',', '', substr($data[0], 74, 25));
          $getcheck_no = substr($data[0], 99, 11);
          $getvalidate = substr($data[0], 110, 6);


          $sql_provcode = $dbh->query("SELECT fld_provcode FROM `tbbilling` WHERE `fld_accountno` = '".$getaccountno."'");
          if ($r_provcode = $sql_provcode->fetch_array()) {
            $sql_ent = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS name FROM `tbentities` WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$r_provcode['fld_provcode']."'");
            $r_ent = $sql_ent->fetch_array();
            if (trim($keys_accountno[$getaccountno]) != '') {
              $_POST['onColl_amount'][$keys_accountno[$getaccountno]] += $getamount;
              $_POST['onColl_check_number'][$keys_accountno[$getaccountno]] .= "|".$getcheck_no;
              $_POST['onColl_transaction_type'][$keys_accountno[$getaccountno]] .= "|".$gettransaction_type;
            }else{
              $_POST['onColl_accountno'][] = $getaccountno;
               $_POST['onColl_amount'][] = $getamount;
               $_POST['onColl_check_number'][] = $getcheck_no;
               $_POST['onColl_transaction_type'][] = $gettransaction_type;
               $_POST['onColl_name'][] = $r_ent['name'];
               $_POST['onColl_process_date'][] = $procdate;
               $keys_accountno[$getaccountno] = $counter_keys;
               $counter_keys++;
            }
            $total_oncollamount += $getamount;
          }


          
          
      
        }

        $line++;
      }
      
      if ($computed_total != str_replace(',', '', $total_oncollamount)) {
        $message = 'Please check Oncoll Total Collections.';
        $message_type = ' callout-danger';
        unset($_POST);
      }

      if ($aes) {
        ###require('mail_noc_reactivate.php');
      }
      
    }
  }elseif ($_POST['uplFile'] == 2) {
    if (strtoupper(pathinfo($_FILES["paymentFile"]["name"], PATHINFO_EXTENSION)) == 'CSV') {
      // echo strtoupper(pathinfo($_FILES["paymentFile"]["name"], PATHINFO_EXTENSION));exit;
      $csv = array_map('str_getcsv', file($_FILES["paymentFile"]["tmp_name"]));
      foreach ($csv as $key => $row) {
        if ($key > 0) {
          $sql_provcode = $dbh->query("SELECT fld_provcode FROM `tbbilling` WHERE `fld_accountno` = '".$row[0]."'");
          if ($r_provcode = $sql_provcode->fetch_array()) {
            $sql_ent = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS name FROM `tbentities` WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$r_provcode['fld_provcode']."'");
            $r_ent = $sql_ent->fetch_array();
            $_POST['rtgs_accountno'][] = $row[0];
            $_POST['rtgs_name'][] = $r_ent['name'];
            $_POST['rtgs_payment_date'][] = date('Y-m-d',strtotime($row[1]));
            $_POST['rtgs_amount'][] = $row[2];
            $_POST['rtgs_check_number'][] = $row[3];
            $_POST['rtgs_particulars'][] = $row[4];
            $_POST['rtgs_transaction_type'][] = $row[5];
          }

          
        }
      }
      // echo "<pre/>";
      // print_r($_POST);

    }else{

    }
  }
}



if ($_POST['btnSaveComputation']) {
  $save_row = key($_POST['btnSaveComputation']);
  if ($_POST['onColl_accountno']) {
    if ($dbh->query("INSERT INTO tbcrbillingpayment (fld_transaction_type, fld_acct_no, fld_amount, fld_check_number, fld_payment_date, fld_published, fld_converted_credits, fld_converted_change, fld_credits_expiry, fld_pricingtype, fld_topup, fld_payment_type, fld_payment_channel) VALUES ('".$_POST['onColl_transaction_type'][$save_row]."', '".$_POST['onColl_accountno'][$save_row]."', '".$_POST['computed_amount'][$save_row]."', '".$_POST['onColl_check_number'][$save_row]."', '".$_POST['onColl_process_date'][$save_row]."', '1', '".$_POST['computed_credits'][$save_row]."', '".$_POST['computed_change'][$save_row]."', '".date('Y-m-d', strtotime("+18 months"))."', '".$_POST['computed_pricingtype'][$save_row]."', '".$_POST['computed_topup'][$save_row]."', '".$_POST['onColl_payment_type'][$save_row]."', '1')")) {
      $_POST['onColl_row_saved'][$save_row] = 1;
    }
  }elseif($_POST['rtgs_accountno']){
    if ($dbh->query("INSERT INTO tbcrbillingpayment (fld_transaction_type, fld_acct_no, fld_amount, fld_check_number, fld_payment_date, fld_published, fld_converted_credits, fld_converted_change, fld_credits_expiry, fld_pricingtype, fld_topup, fld_payment_type, fld_payment_channel) VALUES ('".$_POST['rtgs_transaction_type'][$save_row]."', '".$_POST['rtgs_accountno'][$save_row]."', '".$_POST['computed_amount'][$save_row]."', '".$_POST['rtgs_check_number'][$save_row]."', '".$_POST['rtgs_payment_date'][$save_row]."', '1', '".$_POST['computed_credits'][$save_row]."', '".$_POST['computed_change'][$save_row]."', '".date('Y-m-d', strtotime("+18 months"))."', '".$_POST['computed_pricingtype'][$save_row]."', '".$_POST['computed_topup'][$save_row]."', '".$_POST['rtgs_payment_type'][$save_row]."', '2')")) {
      $_POST['rtgs_row_saved'][$save_row] = 1;
    }
  }
  
}
 


?>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Upload Payment File</h3> -->

          <div class="box-tools pull-right">
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
              <i class="fa fa-times"></i></button> -->
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <?php
              // echo $_POST['fi_select'];
                if ($message) {
                  if (!$message_type) {
                    $message_type = ' callout-success';
                  }
              ?>
              <div class="callout<?php echo $message_type;?>">
                <!-- <h4>Information!</h4> -->

                <p><?php echo $message;?></p>
              </div>
              <?php
                }
              ?>
              
              <form method="POST">
              <?php
              if ($_POST['btnCompute']) {
                $row = key($_POST['btnCompute']);
                if ($_POST['btnCompute'][key($_POST['btnCompute'])] == 1) {
                  $row_file_taxtype = $_POST['onColl_tax_type'][$row];
                  $row_file_paymenttype = $_POST['onColl_payment_type'][$row];
                  $row_file_amount = $_POST['onColl_amount'][$row];
                  $row_file_accountno = $_POST['onColl_accountno'][$row];
                  $row_file_entityname = $_POST['onColl_name'][$row];
                }elseif ($_POST['btnCompute'][key($_POST['btnCompute'])] == 2) {
                  $row_file_taxtype = $_POST['rtgs_tax_type'][$row];
                  $row_file_paymenttype = $_POST['rtgs_payment_type'][$row];
                  $row_file_amount = $_POST['rtgs_amount'][$row];
                  $row_file_accountno = $_POST['rtgs_accountno'][$row];
                  $row_file_entityname = $_POST['rtgs_name'][$row];
                }
                
                $error = 0;
                if ($row_file_taxtype == '-1') {
                  $sel_style_ott[$row] = ' style="border-color: red;"';
                  $error = 1;
                }
                if ($row_file_paymenttype == '-1') {
                  $sel_style_rpt[$row] = ' style="border-color: red;"';
                  $error = 1;
                }
                


                if(!$error) {
                  // $btnCompute_disabled[$row] = ' disabled';

                  # COMPUTE
                  if ($row_file_taxtype == '0') {
                    $amount = $row_file_amount;
                    $amount_computation_text = number_format($row_file_amount, 2);
                  }elseif ($row_file_taxtype == '1') {
                    $amount = $row_file_amount / 0.982142857;
                    $amount_computation_text =number_format($row_file_amount, 2).' / 0.98';
                  }elseif ($row_file_taxtype == '2') {
                    $amount = round($row_file_amount * 1.12);
                    $amount_computation_text = number_format($row_file_amount, 2).' * 1.12';
                  }

                  $topup = 0;
                  $sql_checkw = $dbh->query("SELECT * FROM `tbcrbillingpayment` WHERE fld_pricingtype = 1 and DATE_ADD(DATE(fld_datetime), INTERVAL 1 YEAR) >= '".date("Y-m-d")."' AND fld_acct_no = '".$row_file_accountno."'");
                  if ($r_checkw = $sql_checkw->fetch_array()) {
                    $pricingtype = '1';
                    $topup = 1;
                  }else{
                    $sql1 = $dbh->query("SELECT * FROM tbinquirycost WHERE fld_costtype = '1' AND fld_pricingtype = '1' and fld_effectivity_date > '".date("Y-m-d H:i:s", (strtotime(date("Y-m-d H:i:s")) - 3600))."' ORDER BY fld_effectivity_date DESC LIMIT 1");
                    $s=$sql1->fetch_array();

                    $wholesale_min = $s['fld_cost'] * 1000000;
                    $pricingtype = '';
                    if ($amount >= $wholesale_min) {
                      $pricingtype = '1';
                    }else{
                      $pricingtype = '2';
                    }
                  }
                  
                  $sql_cost = $dbh->query("SELECT * FROM tbinquirycost WHERE fld_costtype = '1' AND fld_pricingtype = '".$pricingtype."' and fld_effectivity_date > '".date("Y-m-d H:i:s", (strtotime(date("Y-m-d H:i:s")) - 3600))."' ORDER BY fld_effectivity_date DESC LIMIT 1");
                  $r_cost=$sql_cost->fetch_array();
                  $remainder=round(fmod($amount, $r_cost['fld_cost']), 1);
                  $credits = intval($amount / $r_cost['fld_cost']);

                  $_POST['computed_amount'][$row] = $amount;
                  $_POST['computed_credits'][$row] = $credits;
                  $_POST['computed_change'][$row] = $remainder;
                  $_POST['computed_pricingtype'][$row] = $pricingtype;
                  $_POST['computed_topup'][$row] = $topup;
              ?>
              
                  <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="page-header">Payment of <?php echo $row_file_entityname;?></h4>
                              <input type="hidden" name="computed_amount[<?php echo $row;?>]" value="<?php echo $_POST['computed_amount'][$row];?>">
                              <input type="hidden" name="computed_credits[<?php echo $row;?>]" value="<?php echo $_POST['computed_credits'][$row];?>">
                              <input type="hidden" name="computed_change[<?php echo $row;?>]" value="<?php echo $_POST['computed_change'][$row];?>">
                              <input type="hidden" name="computed_pricingtype[<?php echo $row;?>]" value="<?php echo $_POST['computed_pricingtype'][$row];?>">
                              <input type="hidden" name="computed_topup[<?php echo $row;?>]" value="<?php echo $_POST['computed_topup'][$row];?>">
                      <table class="table">
                        <tbody>
                          <tr>
                            <td width="20%">Amount</td>
                            <td width="50%" align="right"><?php echo '( PHP '.$amount_computation_text.' )';?></td>
                            <td width="30%" align="right"><?php echo  'PHP '.number_format($amount, 2);?></td>
                          </tr>
                          <tr>
                            <td>Price</td>
                            <td align="right"></td>
                            <td align="right"><?php echo $r_cost['fld_cost'];?></td>
                          </tr>
                          <tr>
                            <td>Credits</td>
                            <td align="right"></td>
                            <td align="right"><?php echo number_format($credits);?></td>
                          </tr>
                          <tr>
                            <td>Change</td>
                            <td align="right"></td>
                            <td align="right"><?php echo 'PHP '.number_format($remainder, 2);?></td>
                          </tr>
                          <tr style="border: none;">
                            <td></td>
                            <td></td>
                            <td><button type="submit" name="btnSaveComputation[<?php echo $row;?>]" class="btn btn-primary" style="float: right; width: 200px;">Save</button></td>
                          </tr>
                        </tbody>
                      </table>
                      </div>
              </div>
                    </div>
                <div class="col-md-3">

                
                </div>
              </div>
              
              <br/><br/><br/>
              <?php
                }
              }
              $saved_onColl = 0;
              for ($i=0; $i < count($_POST['onColl_row_saved']); $i++) { 
                if ($_POST['onColl_row_saved'][$i] == '1') {
                  $saved_onColl++;
                }
              }

              $saved_rtgs = 0;
              for ($i=0; $i < count($_POST['rtgs_row_saved']); $i++) { 
                if ($_POST['rtgs_row_saved'][$i] == '1') {
                  $saved_rtgs++;
                }
              }
              
              if ($_POST['onColl_accountno'] && ($saved_onColl != count($_POST['onColl_row_saved']) || count($_POST['onColl_row_saved']) == 0)) {
              ?>

              <div class="row">
                <div class="col-md-1">

                </div>
                <div class="col-md-10">
                  <div class="card">
                    <div class="card-body">
                  <h4 class="page-header">Details of OnColl file</h4>
                  
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th><center>#</center></th>
                        <th><center>Account Number</center></th>
                        <th>Company Name</th>
                        <th>Amount</th>
                        <th>Payment Type</th>
                        <!-- <th>Check Number</th> -->
                        <th>Tax Type</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                        $counter = 0;
                        for ($i=0; $i < count($_POST['onColl_accountno']); $i++) { 
                          $counter++;
                          $sel_selected_ott[$i][$_POST['onColl_tax_type'][$i]] = ' selected';
                          $sel_selected_rpt[$i][$_POST['onColl_payment_type'][$i]] = ' selected';
                      ?>
                      <tr>
                        
                          <input type="hidden" name="onColl_accountno[<?php echo $i;?>]" value="<?php echo $_POST['onColl_accountno'][$i];?>">
                          <input type="hidden" name="onColl_amount[<?php echo $i;?>]" value="<?php echo $_POST['onColl_amount'][$i];?>">
                          <input type="hidden" name="onColl_check_number[<?php echo $i;?>]" value="<?php echo $_POST['onColl_check_number'][$i];?>">
                          <input type="hidden" name="onColl_transaction_type[<?php echo $i;?>]" value="<?php echo $_POST['onColl_transaction_type'][$i];?>">
                          <input type="hidden" name="onColl_name[<?php echo $i;?>]" value="<?php echo $_POST['onColl_name'][$i];?>">
                          <input type="hidden" name="onColl_process_date[<?php echo $i;?>]" value="<?php echo $_POST['onColl_process_date'][$i];?>">
                          <input type="hidden" name="onColl_payment_type[<?php echo $i;?>]" value="<?php echo $_POST['onColl_payment_type'][$i];?>">
                          <input type="hidden" name="onColl_row_saved[<?php echo $i;?>]" value="<?php echo $_POST['onColl_row_saved'][$i];?>">
                        <td><center><?php echo $counter;?></center></td>
                        <td><center><?php echo $_POST['onColl_accountno'][$i]; ?></center></td>
                        <td><?php echo $_POST['onColl_name'][$i]; ?></td>
                        <td>PHP <?php echo number_format($_POST['onColl_amount'][$i], 2); ?></td>
                        <!-- <td align="right"><?php echo $value['check_number']; ?></td> -->
                        <td>
                          <select name="onColl_payment_type[<?php echo $i;?>]" class="custom-select mr-sm-2"<?php echo $sel_style_rpt[$i];?>>
                            <option value="-1" selected>Choose...</option>
                            <option value="0"<?php echo $sel_selected_rpt[$i][0];?>>Initial Payment</option>
                            <option value="1"<?php echo $sel_selected_rpt[$i][1];?>>Replenishment</option>
                          </select>
                        </td>
                        <td>
                            <select name="onColl_tax_type[<?php echo $i;?>]" class="custom-select mr-sm-2" id="inlineFormCustomSelect"<?php echo $sel_style_ott[$i];?>>
                              <option value="-1" selected>Choose...</option>
                              <option value="0"<?php echo $sel_selected_ott[$i][0];?>>None</option>
                              <option value="1"<?php echo $sel_selected_ott[$i][1];?>>2%</option>
                              <option value="2"<?php echo $sel_selected_ott[$i][2];?>>12%</option>
                            </select>
                          </td>
                        <td>
                          <?php
                          if ($_POST['onColl_row_saved'][$i] == 1) {
                          ?>
                          <button type="button" name="btnSaved[<?php echo $i;?>]" class="btn btn-default">Saved</button>
                          <?php
                          }else{
                          ?>
                          <button type="submit" value="1" name="btnCompute[<?php echo $i;?>]" class="btn btn-success"<?php echo $btnCompute_disabled[$i];?>>Compute</button>
                          <?php
                          }
                          ?>
                          
                        </td>
                        
                      </tr>
                      <?php
                          
                        }
                      ?>
                    </tbody>
                  </table>
                  </div>
                </div>
                </div>
                <div class="col-md-1">

                </div>
              </div>
              
              <br/><br/><br/>

              <?php
              }elseif ($_POST['rtgs_accountno'] && ($saved_rtgs != count($_POST['rtgs_row_saved']) || count($_POST['rtgs_row_saved']) == 0)) {
              ?>
              <div class="row">
                <div class="col-md-1">

                </div>
                <div class="col-md-10">
                  <div class="card">
                    <div class="card-body">
                  <h4 class="page-header">Details of RTGS file</h4>
                  
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th><center>#</center></th>
                        <th><center>Account Number</center></th>
                        <th>Company Name</th>
                        <th>Amount</th>
                        <th>Payment Type</th>
                        <!-- <th>Check Number</th> -->
                        <th>Tax Type</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                        $counter = 0;
                        for ($i=0; $i < count($_POST['rtgs_accountno']); $i++) { 
                          $counter++;
                          $sel_selected_ott[$i][$_POST['rtgs_tax_type'][$i]] = ' selected';
                          $sel_selected_rpt[$i][$_POST['rtgs_payment_type'][$i]] = ' selected';
                      ?>
                      <tr>
                        
                          <input type="hidden" name="rtgs_accountno[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_accountno'][$i];?>">
                          <input type="hidden" name="rtgs_amount[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_amount'][$i];?>">
                          <input type="hidden" name="rtgs_check_number[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_check_number'][$i];?>">
                          <input type="hidden" name="rtgs_transaction_type[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_transaction_type'][$i];?>">
                          <input type="hidden" name="rtgs_name[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_name'][$i];?>">
                          <input type="hidden" name="rtgs_payment_date[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_payment_date'][$i];?>">
                          <input type="hidden" name="rtgs_payment_type[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_payment_type'][$i];?>">
                          <input type="hidden" name="rtgs_row_saved[<?php echo $i;?>]" value="<?php echo $_POST['rtgs_row_saved'][$i];?>">
                        <td><center><?php echo $counter;?></center></td>
                        <td><center><?php echo $_POST['rtgs_accountno'][$i]; ?></center></td>
                        <td><?php echo $_POST['rtgs_name'][$i]; ?></td>
                        <td>PHP <?php echo number_format($_POST['rtgs_amount'][$i], 2); ?></td>
                        <!-- <td align="right"><?php echo $value['check_number']; ?></td> -->
                        <td>
                          <select name="rtgs_payment_type[<?php echo $i;?>]" class="custom-select mr-sm-2"<?php echo $sel_style_rpt[$i];?>>
                            <option value="-1" selected>Choose...</option>
                            <option value="0"<?php echo $sel_selected_rpt[$i][0];?>>Initial Payment</option>
                            <option value="1"<?php echo $sel_selected_rpt[$i][1];?>>Replenishment</option>
                          </select>
                        </td>
                        <td>
                          <select name="rtgs_tax_type[<?php echo $i;?>]" class="custom-select mr-sm-2" id="inlineFormCustomSelect"<?php echo $sel_style_ott[$i];?>>
                            <option value="-1" selected>Choose...</option>
                            <option value="0"<?php echo $sel_selected_ott[$i][0];?>>None</option>
                            <option value="1"<?php echo $sel_selected_ott[$i][1];?>>2%</option>
                            <option value="2"<?php echo $sel_selected_ott[$i][2];?>>12%</option>
                          </select>
                        </td>
                        <td>
                          <?php
                          if ($_POST['rtgs_row_saved'][$i] == 1) {
                          ?>
                          <button type="button" name="btnSaved[<?php echo $i;?>]" class="btn btn-default">Saved</button>
                          <?php
                          }else{
                          ?>
                          <button type="submit" value="2" name="btnCompute[<?php echo $i;?>]" class="btn btn-success"<?php echo $btnCompute_disabled[$i];?>>Compute</button>
                          <?php
                          }
                          ?>
                          
                        </td>
                        
                      </tr>
                      <?php
                          
                        }
                      ?>
                    </tbody>
                  </table>
                  </div>
                </div>
                </div>
                <div class="col-md-1">

                </div>
              </div>
              
              <br/><br/><br/>
              <?php
              }else{
                $_POST['onColl_accountno'] = [];
                $_POST['rtgs_accountno'] = [];
              }
              ?>
              </form>
              
            </div>
            
          </div>
        </div>
        <?php
        if (!$_POST['btnSingleUpload'] && !$_POST['btnCompute'] && !$_POST['onColl_accountno'] && !$_POST['rtgs_accountno']) {
        ?>
        
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h3 class="page-header">Upload Payment</h3>
                  <form method="POST" enctype="multipart/form-data">
                <div class="form-group">

                  <?php
                    if ($_POST['btnFileUpload']) {
                  ?>
                  <label for="paymentFile">Upload Oncoll File Here</label><br/>
                  <input type="file" id="paymentFile" name="paymentFile" required><br/><br/>
                  <button type="submit" name="uplFile" class="btn btn-primary" value="1">Upload File</button>
                  <p class="help-block">.txt file only</p>
                  <?php
                    }elseif ($_POST['btnRTGSUpload']) {
                  ?>
                  <label for="paymentFile">Upload RTGS Here</label><br/>
                  <input type="file" name="paymentFile" required><br/><br/>
                  <button type="submit" name="uplFile" class="btn btn-primary" value="2">Upload File</button>
                  <p class="help-block">.csv file only</p>
                  <?php
                    }else{
                  ?>
                  <button class="btn btn-default btn-block" name="btnRTGSUpload" value="1">RTGS Upload</button><br>
                  <button class="btn btn-primary btn-block" name="btnFileUpload" value="1">Oncoll Upload</button>
                  <?php
                    }
                  ?>

                </div>
              </form>
                </div>
              </div>
              
            </div>
            <div class="col-md-4"></div>
          </div>

        <?php
        }
        ?>
          

        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
      </div>
      <!-- /.box -->
  

</section>
<!-- /.content -->
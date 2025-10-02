<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

 


?>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
   <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Latest</h3>
    </div>
    <div class="card-body">
      
          <div class="row">
            <div class="col-md-12">
              <?php
              // echo $_POST['fi_select'];
                if ($message) {
              ?>
              <div class="callout callout-success">
                <h4>Information!</h4>

                <p>Payment has been uploaded! Thank you!</p>
              </div>
              <?php
              }
              if ($_POST['btnSearch']) {
                $search = trim($_POST['inputSearch']);
                if ($search) {
                  if ($_POST['inputFilter'] == 1) {
                    $accountnos = $search;
                  }else{
                    
                    $names = [];
                    $sql_search1 = $dbh4->query("SELECT AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) As provcode FROM `tbentities` WHERE AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) LIKE '%".$search."%'");
                    while ($r_search1 = $sql_search1->fetch_array()) {
                      // echo $r_search1['provcode'];
                      $provcodes .= "'".$r_search1['provcode']."', ";
                    }
                    $provcodes = substr($provcodes, 0, -2);
                    
                    if ($provcodes) {
                      $sql_search2 = $dbh->query("SELECT * FROM `tbbilling` WHERE `fld_provcode` IN (".$provcodes.")");
                      while ($r_search = $sql_search2->fetch_array()) {
                        if ($r_search['fld_accountno']) {
                          $accountnos .= $r_search['fld_accountno'].", ";
                        }
                      }
                      $accountnos = substr($accountnos, 0, -2);
                    }
                    

                  }
                  

                  
                }
              }
              

              if ($accountnos) {
                $sql_sum = "SELECT SUM(fld_amount) as total_amount FROM tbcrbillingpayment WHERE fld_published = 1 AND fld_acct_no IN ('".$accountnos."')";
                $sql_payments = "SELECT * FROM tbcrbillingpayment WHERE fld_published = 1 AND fld_acct_no IN ('".$accountnos."') ORDER BY fld_datetime DESC";
              }else{
                $sql_sum = "SELECT SUM(fld_amount) as total_amount FROM tbcrbillingpayment WHERE fld_published = 1";
                $sql_payments = "SELECT * FROM tbcrbillingpayment WHERE fld_published = 1 ORDER BY fld_datetime DESC";
              }
              $paymentsum = $dbh->query($sql_sum);
                $ps=$paymentsum->fetch_array();


                $sel_filter[$_POST['inputFilter']] = ' selected';
              ?>
              
              <!-- <center><h3>OVERALL: PHP <?php echo number_format($ps['total_amount']); ?></h3></center> -->
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <form class="form-inline" method="POST">
                    <div class="form-group mx-sm-3 mb-2">
                      <label for="exampleFormControlSelect1">Filter by: &nbsp;&nbsp;</label>
                      <select name="inputFilter" class="form-control" id="exampleFormControlSelect1">
                        <option value="1"<?php echo $sel_filter[1]?>>Account Number</option>
                        <option value="2"<?php echo $sel_filter[2]?>>Accessing Entity Name</option>
                      </select>&nbsp;&nbsp;&nbsp;
                      <input type="text" class="form-control" name="inputSearch" placeholder="" value="<?php echo $_POST['inputSearch'];?>">
                    </div>
                    <!-- <button class="btn btn-primary mb-2" name="btnSearch" value="1">Search</button> -->
                    <!-- <button type="submit" class="btn btn-primary mb-2" value="1" name="btnSearch">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> -->
                    <button type="submit" class="btn btn-primary mb-2" value="1" name="btnSearch">Filter</button>
                  </form>
                </div>
                <div class="col-md-3"></div>
              </div>
              <br/>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th><center>#</center></th>
                    <th><center>Account Number</center></th>
                    <th>Company Name</th>
                    <th nowrap="">Deposit Amount</th>
                    <th>Change</th>
                    <th>Credits</th>
                    <th>Transaction Type</th>
                    <th>Date of Payment</th>
                    <th>Date of Upload</th>
                    <th>Date of Migration</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                    $sql = $dbh->query($sql_payments);
                    while ($s=$sql->fetch_array()) {
                      $date_ts = strtotime($s['fld_payment_date']);
                      $sql_search3 = $dbh->query("SELECT fld_provcode FROM `tbbilling` WHERE fld_accountno = '".$s['fld_acct_no']."'");
                      $r_search3 = $sql_search3->fetch_array();
                      $sql_search4 = $dbh4->query("SELECT AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) As name FROM `tbentities` WHERE AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$r_search3['fld_provcode']."'");
                      $r_search4 = $sql_search4->fetch_array();
                  ?>
                  <tr>
                    <td><center><?php echo $counter;?></center></td>
                    <td><center><?php echo $s['fld_acct_no']; ?></center></td>
                    <td><?php echo $r_search4['name']; ?></td>
                    <td nowrap="">PHP <?php echo number_format($s['fld_amount'], 2); ?></td>
                    <td nowrap="">PHP <?php echo number_format($s['fld_converted_change'], 2); ?></td>
                    <td align="right"><?php echo number_format($s['fld_converted_credits']); ?></td>
                    <td><?php echo $s['fld_transaction_type']; ?></td>
                    <td>
                      <?php 
                      if ($date_ts) {
                        echo date("F d, Y", $date_ts); 
                      }
                      ?>
                    </td>
                    <td>
                      <?php 
                      if ($date_ts) {
                        echo date("F d, Y", strtotime($s['fld_datetime'])); 
                      }
                      ?>
                    </td>
                    <td>
                      <?php
                      if ($s['fld_transaction_type'] == 'MIGRATED') {
                        echo date("F d, Y", strtotime($s['fld_datetime'])); 
                      }
                      ?>
                    </td>
                  </tr>
                  <?php
                      $counter++;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
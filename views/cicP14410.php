<?php
date_default_timezone_set('Asia/Manila'); 

$searchsel[$_POST['searchEntity']] = " selected";


$prev_month = date('m', strtotime('first day of last month'));
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          List By Type
        </button>
        <ul class="dropdown-menu">
          <a href="main.php?nid=144&sid=0&rid=0"><li class="dropdown-item">Single</li></a>
          <a href="main.php?nid=144&sid=1&rid=0"><li class="dropdown-item">List By Type</li></a>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group" data-select2-id="29">
              <label>Search Entity</label>
              <select class="form-control select2 select2-hidden-accessible" name="searchEntity" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true" onchange="submit()">
                <option selected disabled>----- SELECT -----</option>
                <option value="all">ALL</option>
                <?php

                  $get_all_entities_type = $dbh4->query("SELECT fld_type, fld_name FROM tbenttypes");
                  while ($gaet=$get_all_entities_type->fetch_array()) {
                ?>
                <option value="<?php echo $gaet['fld_type']; ?>"<?php echo $searchsel[$gaet['fld_type']]; ?>><?php echo $gaet['fld_type']. " - " .$gaet['fld_name']; ?></option>
                <?php
                  }
                ?>
              </select>
            </div>
          </div>
        </div>
      </form>
      
      <?php

        if ($_POST['searchEntity']) {
          if ($_POST['searchEntity'] != 'all') {
            $query = "WHERE fld_type = '".$_POST['searchEntity']."' and CONVERT( AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) USING 'utf8' ) <> '';";
          } else {
            $query = "";
          }
          $get_company = $dbh4->query("SELECT fld_name, fld_type FROM tbenttypes WHERE fld_type = '".$_POST['searchEntity']."'");
          $gc=$get_company->fetch_array();
      ?>
      <h3 class="card-header"><?php echo $gc['fld_type']. " - " .$gc['fld_name']; ?></h3>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Control No</th>
            <th>Provider Code</th>
            <th>Company</th>
            <th>Type</th>
            <?php
              for($i=1; $i<=$prev_month; $i++){
                $month = date('F', mktime(0, 0, 0, $i, 10)); 
            ?>
            <th style="text-align: left;"><?php echo $month; ?></th>
            <?php
              }
            ?>
            <th style="text-align: right;">Total</th>
            <th style="text-align: right;">Missed Months</th>
            <th style="text-align: right;">Compliance Rating</th>
          </tr>
        </thead>
        <tbody>
          <?php
            
            $get_all_entities = $dbh4->query("SELECT fld_ctrlno, fld_company_name, fld_type, fld_providercode FROM tbentities ".$query);
            while($gae=$get_all_entities->fetch_array()){
            
            $total_rs = 0;
            $total_rsl = 0;
            $total_rse = 0;
            $total_ccf = 0;
            $total_chd = 0;
            $total_df = 0;
            $total_horizontal_total = 0;
            $total_horizontal = 0;
            $total_rs_true = 0;

            
          ?>
          <tr>
            <td><?php echo $gae['fld_ctrlno']; ?></td>
            <td style="text-align: left;"><?php echo $gae['fld_providercode']; ?></td>
            <td><b><?php echo $gae['fld_company_name']; ?></b></td>
            <td><b><?php echo $gae['fld_type']; ?></b></td>
            <?php
              for($i=1; $i<=$prev_month; $i++){
                $total[$i] = 0;
                $month = date('F', mktime(0, 0, 0, $i, 10)); 

                $date = date('Y-m', strtotime(date("Y")."-".$i."-01"));
                $month2 = date('F Y', mktime(0, 0, 0, $i, 10));

                $month3 = date('F 31, Y', mktime(0, 0, 0, $i, 10));
                $month4 = date('F 30, Y', mktime(0, 0, 0, $i, 10));

                $check_regular_submission = $dbh4->query("SELECT COUNT(fld_filename) as cnt_rs FROM tbtransmittal WHERE fld_provcode = '".$gae['fld_providercode']."' and (fld_date_covered LIKE '".$date."%' AND fld_trans_type = 1 or fld_date_covered LIKE '%".$month2."%' AND fld_trans_type = 1 or fld_date_covered LIKE '%".$month3."%' AND fld_trans_type = 1 or fld_date_covered LIKE '%".$month4."%' AND fld_trans_type = 1)");
                $crs=$check_regular_submission->fetch_array();

                $check_regular_submission_late = $dbh4->query("SELECT COUNT(fld_filename) as cnt_rsl FROM tbtransmittal WHERE fld_provcode = '".$gae['fld_providercode']."' and (fld_date_covered LIKE '".$date."%' AND fld_trans_type = 6 or fld_date_covered LIKE '%".$month2."%' AND fld_trans_type = 6 or fld_date_covered LIKE '%".$month3."%' AND fld_trans_type = 6 or fld_date_covered LIKE '%".$month4."%' AND fld_trans_type = 6)");
                $crsl=$check_regular_submission_late->fetch_array();

                $check_regular_submission_extended = $dbh4->query("SELECT COUNT(fld_filename) as cnt_rse FROM tbtransmittal WHERE fld_provcode = '".$gae['fld_providercode']."' and (fld_date_covered LIKE '".$date."%' AND fld_trans_type = 5 or fld_date_covered LIKE '%".$month2."%' AND fld_trans_type = 5 or fld_date_covered LIKE '%".$month3."%' AND fld_trans_type = 5 or fld_date_covered LIKE '%".$month4."%' AND fld_trans_type = 5)");
                $crse=$check_regular_submission_extended->fetch_array();

                $total_regular_submitted = $crs['cnt_rs'] + $crsl['cnt_rsl'] + $crse['cnt_rse'];
                
                $rs_true = ($total_regular_submitted > 0 ? 1 : 0);

                $total[$i] += $total_regular_submitted;

            ?>
            <td style="text-align: right;"><?php echo ($total_regular_submitted ? $total_regular_submitted : "<p style='color: red;'>0</p>"); ?></td>
            <?php

                $total_horizontal += $total_regular_submitted;
                $total_rs_true += $rs_true;
              }

              $missed_months = $prev_month - $total_rs_true;

              if($missed_months == 0){
                $compliance_rating = "Fully Compliant";
                $color = "text-success";
                $dataOrder = 'data-order = "1" ';
                $reduction_fee = 100;
              } elseif ($missed_months > 0 and $missed_months <= 3) {
                $compliance_rating = "Mostly Compliant";
                $color = "text-info";
                $dataOrder = 'data-order = "2" ';
                $reduction_fee = 75;
              } elseif($missed_months >= 4 and $missed_months <= 6){
                $compliance_rating = "Partially Compliant";
                $color = "text-primary";
                $dataOrder = 'data-order = "3" ';
                $reduction_fee = 50;
              } elseif ($missed_months >= 7 and $missed_months <= 9) {
                $compliance_rating = "Minimally Compliant";
                $color = "text-warning"; 
                $dataOrder = 'data-order = "4" ';
                $reduction_fee = 25;     
              } elseif ($missed_months > 9) {
                $compliance_rating = "Inactive";
                $color = "text-danger";
                $dataOrder = 'data-order = "5" ';
                $reduction_fee = 0;
              } else {
                // echo $rowcount."<br>";
                $compliance_rating = "N/A";
                // $color = "text-success";
                $dataOrder = 'data-order = "1" ';
                // $reduction_fee = 100;
              }
            ?>
            <td style="text-align: right;"><?php echo $total_horizontal; ?></td>
            <td style="text-align: right;"><?php echo $missed_months; ?></td>
            <td style="text-align: right;"><?php echo $compliance_rating; ?></td>
          </tr>
          <?php
            
          }
          ?>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php
              for($i=1; $i<=$prev_month; $i++){

                $month = date('F', mktime(0, 0, 0, $i, 10)); 

                $date = date('Y-m', strtotime(date("Y")."-".$i."-01"));
                $month2 = date('F Y', mktime(0, 0, 0, $i, 10));

                $month3 = date('F 31, Y', mktime(0, 0, 0, $i, 10));
                $month4 = date('F 30, Y', mktime(0, 0, 0, $i, 10));
            ?>
            <td style="text-align: right;"><?php echo $total[1]; ?></td>
            <?php
              }
            ?>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <?php
        }

      ?>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if (!$_POST['yearmonth']) {
  $det[0] = date("Y");
  $det[1] = date("m");
  $yrsel[date("Y")] = " selected";
  $labelyearmonth = $abbrmo[(int)$det[1]]." ".$det[0];

  $year = date("Y");
}

if($_POST['yearmonth']){
  $active1[2] = " class='active'";
  $active2[2] = " active";
  $yrsel[$_POST['yearmonth']] = " selected";
  $det = explode("-", $_POST['yearmonth']);
  
  $labelyearmonth = $abbrmo[(int)$det[1]]." ".$det[0];

  $year = $_POST['yearmonth'];
}

if ($_POST['month']) {
  $mnthsel[$_POST['month']] = " selected";
}



// echo $year;
?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Hit Rate</h3>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label>Select Year:</label>
              <select name="yearmonth" class="form-control" onchange="submit()">
              <?php
                $cnt1=0;
                $sql=$dbh->query("SELECT SUBSTR(fld_inqdate, 1, 4) AS ym FROM tbinquiries WHERE SUBSTR(fld_inqdate, 1, 4) <= '".date("Y-m")."' AND SUBSTR(fld_inqdate, 1, 4) <> '0000' GROUP BY SUBSTR(fld_inqdate, 1, 4)");
                while($h=$sql->fetch_array()){
                  if(!$_POST['yearmonth']){
                    $_POST['yearmonth'] = $h['ym'];
                  }
                  $dt = explode("-", $h['ym']);
                  echo "<option value='".$h['ym']."'".$yrsel[$h['ym']].">".$abbrmo[(int)$dt[1]]." ".$dt[0]."</option>";
                }
              ?>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>Select Month:</label>
              <select name="month" class="form-control" onchange="submit()">
              <option selected disabled>----SELECT----</option>
              <?php
              for ($m=1; $m<=date("m"); $m++) {
               $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
               echo "<option value='".$m."'".$mnthsel[$m].">".$month."</option>";
               }
              ?>
              </select>
            </div>
          </div>
        </div>
      </form>
      <br>	
      <a href="main.php?nid=44&sid=0&rid=0" class="btn btn-primary">Clear Filter</a>
      <br>
      <br>
      <?php
        if ($_POST['yearmonth'] and !$_POST['month']) {
      ?>
      <table class="table table-bordered table-hover table-sm" id="hitratetable_monthly">
        <thead>
          <tr>
            <th>MONTH</th>
            <th style="text-align: right;">HIT</th>
            <th style="text-align: right;">NO HIT</th>
            <th style="text-align: right;">TOTAL</th>
            <th style="text-align: right;" colspan="2">HIT RATE</th>
          </tr>
        </thead>
      <tbody>
        <?php
          $total_all_hit = 0;
          $total_all_nohit = 0;
          $total_all_vcol = 0;

          for ($m=1; $m<=12; $m++) {
           $month = date('F', mktime(0,0,0,$m, 1, $_POST['yearmonth']));

           $get_inquiries_with_hit = $dbh->query("SELECT fld_inqresult, DATE_FORMAT(fld_inqdate, '%Y-%m'), SUM(fld_inqcount) as inqcnt FROM tbinquiries WHERE fld_inqresult = 1 AND YEAR(fld_inqdate) = ".$year." AND MONTH(fld_inqdate) = ".$m." GROUP BY DATE_FORMAT(fld_inqdate, '%Y-%m')");
           $giwh=$get_inquiries_with_hit->fetch_array();


           $get_inquiries_no_hit = $dbh->query("SELECT fld_inqresult, DATE_FORMAT(fld_inqdate, '%Y-%m'), SUM(fld_inqcount) as inqcnt FROM tbinquiries WHERE fld_inqresult = 0 AND YEAR(fld_inqdate) = ".$year." AND MONTH(fld_inqdate) = ".$m." GROUP BY DATE_FORMAT(fld_inqdate, '%Y-%m')");
           $ginh=$get_inquiries_no_hit->fetch_array();

           $total_col = $giwh['inqcnt'] + $ginh['inqcnt'];

           $hit_rate_decimal = 0;
          $percent = 0;
          $total_hit_rate_decimal = 0;
          $total_percent = 0;

           if ($giwh['inqcnt'] > 0) {
             $hit_rate_decimal = $giwh['inqcnt']/($giwh['inqcnt']+$ginh['inqcnt']);
             $percent = (float)$hit_rate_decimal * 100;
           }

        ?>
        <tr>
          <td><?php echo $month; ?></td>
          <td style="text-align: right;"><?php echo number_format($giwh['inqcnt']); ?></td>
          <td style="text-align: right;"><?php echo number_format($ginh['inqcnt']); ?></td>
          <td style="text-align: right;"><?php echo number_format($total_col); ?></td>
          <td style="text-align: right;"><?php echo number_format($hit_rate_decimal, 2); ?></td>
          <td style="text-align: right;"><b><?php echo substr($percent, 0, 5); ?>%</b></td>
        </tr>
        <?php
            $total_all_hit += $giwh['inqcnt'];
            $total_all_nohit += $ginh['inqcnt'];
            $total_all_vcol += $total_col;


            if ($total_all_hit > 0) {
              $total_hit_rate_decimal = $total_all_hit/($total_all_hit+$total_all_nohit);

              $total_percent = (float)$total_hit_rate_decimal * 100;
            }
          }
        ?>
        <tr>
          <td><b>TOTAL</b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_all_hit); ?></b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_all_nohit); ?></b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_all_vcol); ?></b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_hit_rate_decimal, 2); ?></b></td>
          <td style="text-align: right;"><b><?php echo substr($total_percent, 0, 5); ?>%</b></td>
        </tr>
      </tbody>
      </table>
      <?php
        } elseif ($_POST['yearmonth'] and $_POST['month']) {
          // $month = $_POST['month'];
      ?>
      <table class="table table-bordered table-hover table-sm" id="hitratetable_daily">
        <thead>
          <tr>
            <th>DATE</th>
            <th style="text-align: right;">HIT</th>
            <th style="text-align: right;">NO HIT</th>
            <th style="text-align: right;">TOTAL</th>
            <th style="text-align: right;" colspan="2">HIT RATE</th>
          </tr>
        </thead>
      <tbody>
      	<?php
          $total_all_hit = 0;
          $total_all_nohit = 0;
          $total_all_vcol = 0;

          $days=cal_days_in_month(CAL_GREGORIAN,$_POST['month'],$_POST['yearmonth']);

          for ($d=1; $d<=$days; $d++) {
           $date = date('Y-m-d', mktime(0,0,0,$_POST['month'], $d, $_POST['yearmonth']));

           $get_inquiries_with_hit = $dbh->query("SELECT fld_inqresult, DATE_FORMAT(fld_inqdate, '%Y-%m-%d'), SUM(fld_inqcount) as inqcnt FROM tbinquiries WHERE fld_inqresult = 1 AND fld_inqdate = '".$date."' GROUP BY DATE_FORMAT(fld_inqdate, '%Y-%m-%d')");
           $giwh=$get_inquiries_with_hit->fetch_array();


           $get_inquiries_no_hit = $dbh->query("SELECT fld_inqresult, DATE_FORMAT(fld_inqdate, '%Y-%m'), SUM(fld_inqcount) as inqcnt FROM tbinquiries WHERE fld_inqresult = 0 AND fld_inqdate = '".$date."' GROUP BY DATE_FORMAT(fld_inqdate, '%Y-%m-%d')");
           $ginh=$get_inquiries_no_hit->fetch_array();

           $total_col = $giwh['inqcnt'] + $ginh['inqcnt'];

           $hit_rate_decimal = 0;
           $percent = 0;
           $total_hit_rate_decimal = 0;
           $total_percent = 0;

           if ($giwh['inqcnt'] > 0) {
             $hit_rate_decimal = $giwh['inqcnt']/($giwh['inqcnt']+$ginh['inqcnt']);
             $percent = (float)$hit_rate_decimal * 100;
           }

        ?>
        <tr>
          <td><?php echo $date; ?></td>
          <td style="text-align: right;"><?php echo number_format($giwh['inqcnt']); ?></td>
          <td style="text-align: right;"><?php echo number_format($ginh['inqcnt']); ?></td>
          <td style="text-align: right;"><?php echo number_format($total_col); ?></td>
          <td style="text-align: right;"><?php echo number_format($hit_rate_decimal, 2); ?></td>
          <td style="text-align: right;"><b><?php echo substr($percent, 0, 5); ?>%</b></td>
        </tr>
        <?php
            $total_all_hit += $giwh['inqcnt'];
            $total_all_nohit += $ginh['inqcnt'];
            $total_all_vcol += $total_col;


            if ($total_all_hit > 0) {
              $total_hit_rate_decimal = $total_all_hit/($total_all_hit+$total_all_nohit);

              $total_percent = (float)$total_hit_rate_decimal * 100;
            }
          }
        ?>
        <tr>
          <td><b>TOTAL</b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_all_hit); ?></b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_all_nohit); ?></b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_all_vcol); ?></b></td>
          <td style="text-align: right;"><b><?php echo number_format($total_hit_rate_decimal, 2); ?></b></td>
          <td style="text-align: right;"><b><?php echo substr($total_percent, 0, 5); ?>%</b></td>
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
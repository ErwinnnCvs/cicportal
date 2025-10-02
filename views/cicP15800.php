<?php

$yrsel[$_POST['yearmonthday-pto']] = " selected";

if (!$_POST['yearmonthday-pto']) {
  $_POST['yearmonthday-pto'] = "2025-05-19";
}

?>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
          
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Chart View
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=158&sid=0&rid=0">Chart View</a></li>
          <li class="dropdown-item"><a href="main.php?nid=158&sid=1&rid=1">Summary View</a></li>
          <li class="dropdown-item"><a href="main.php?nid=158&sid=1&rid=2">Detailed View</a></li>
        </ul>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  <form method="post">
    <label>Select Date</label>
    <select name="yearmonthday-pto" id="yearmonthday-pto" class="form-control" style="width: 10%;" onchange="submit()">
    <?php
      $cnt1=0;
      $sql=$dbh4->query("SELECT DATE_FORMAT(fld_startdate, '%Y-%m-%d') AS ymd FROM tbcisauditloginquiries WHERE fld_startdate <= '".date("Y-m-d")."' GROUP BY DATE_FORMAT(fld_startdate, '%Y-%m-%d')");
      while($h=$sql->fetch_array()){
        if(!$_POST['yearmonthday-pto']){
          $_POST['yearmonthday-pto'] = $h['ymd'];
        }
        $dt = explode("-", $h['ymd']);
        echo "<option value='".$h['ymd']."'".$yrsel[$h['ymd']].">".date("F d, Y", strtotime($h['ymd']))."</option>";
      }
    ?>
    </select>
  </form>

  <br>
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">Count</h3>
            <!-- <a href="javascript:void(0);">View Report</a> -->
          </div>
        </div>
        <div class="card-body">
          

          <div class="position-relative mb-4">
            <canvas id="pto-doughnut" style="min-height: 100; height: 100; max-height: 100; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
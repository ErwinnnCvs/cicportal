<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$registered_se = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_operational = 0 and fld_status <> 0 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%'");

$rse=$registered_se->fetch_array();

$not_registered_se = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_se FROM tbentities WHERE fld_operational = 0 and fld_status = 0 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%'");

$nrse=$not_registered_se->fetch_array();

$registered_ae = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_ae FROM tbentities WHERE fld_operational = 0 and fld_aeis = 1 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' and fld_registration_type <> 1");
$rae=$registered_ae->fetch_array();

$not_registered_ae = $dbh4->query("SELECT COUNT(fld_ctrlno) as no_ae FROM tbentities WHERE fld_operational = 0 and fld_aeis = 1 and fld_access_type = 0 and AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) NOT LIKE 'SAE%' and fld_registration_type <> 1");
$nrae=$not_registered_ae->fetch_array();


$get_as_of_date_loading = $dbh4->query("SELECT fld_date_completed FROM tbloading ORDER BY fld_date_completed DESC LIMIT 1;");
$gaodl=$get_as_of_date_loading->fetch_array();

$get_as_of_date_daily_loading = $dbh4->query("SELECT fld_date FROM tbdailyloading ORDER BY fld_date DESC LIMIT 1;");
$gaoddl=$get_as_of_date_daily_loading->fetch_array();

$get_as_of_date_inquiries = $dbh->query("SELECT fld_inqdate FROM tbinquiries ORDER BY fld_inqdate DESC LIMIT 1;");
$gaodi=$get_as_of_date_inquiries->fetch_array();



?>

<!-- Main content -->
<section class="content">

  <div class="row">
  <div class="col-12 col-sm-6 col-md-3">
  <div class="info-box">
  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
  <div class="info-box-content">
  <span class="info-box-text">Submitting Entities in Production</span>
  <span class="info-box-number">
  <?php echo number_format($rse['no_se']); ?>
  </span>
  </div>

  </div>

  </div>

  <div class="col-12 col-sm-6 col-md-3">
  <div class="info-box mb-3">
  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
  <div class="info-box-content">
  <span class="info-box-text">Accessing Entities - Onboarded</span>
  <span class="info-box-number">
  <?php echo number_format($rae['no_ae']); ?>
  </span>
  </div>

  </div>

  </div>


  <div class="clearfix hidden-md-up"></div>
  <div class="col-12 col-sm-6 col-md-3">
  <div class="info-box mb-3">
  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-key"></i></span>
  <div class="info-box-content">
  <span class="info-box-text">Vulnerability Assessment & Penetration Testing</span>
  <span class="info-box-number">
    <!-- <?php echo number_format($nrse['no_se']); ?> -->
    Ongoing
  </span>
  </div>

  </div>

  </div>

  <div class="col-12 col-sm-6 col-md-3">
  <div class="info-box mb-3">
  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-cog"></i></span>
  <div class="info-box-content">
  <span class="info-box-text">System Availability</span>
  <span class="info-box-number">
    <!-- <?php echo number_format($nrae['no_ae']); ?> -->
    99%
  </span>
  </div>

  </div>

  </div>

  </div>

  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-3">
          <!-- Date range -->
          <form method="POST">
            <div class="form-group">
              <label>Date range for Daily Loading:</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="text" class="form-control float-right" name="date_filter_daily_loading" id="reservation" value="<?php echo $_POST['date_filter_daily_loading']; ?>">
                <button type="submit" value="1" name="sbtDailyLoadingFilter" class="btn btn-primary">Filter</button>
              </div>
              <!-- /.input group -->
            </div>
            <!-- /.form group -->
          </form>
        </div>
      </div>
      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">Daily Loading (Days) as of <?php echo date("d M Y", strtotime($gaoddl['fld_date']));  ?></h3>
            <!-- <a href="javascript:void(0);">View Report</a> -->
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div>
            <canvas id="myChart"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->


  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">Processing Time (Days) as of <?php echo date("d M Y", strtotime($gaodl['fld_date_completed']));  ?></h3>
            <!-- <a href="javascript:void(0);">View Report</a> -->
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex">
            <p class="d-flex flex-column">
              <!-- <?php
                $get_loading_ytd = $dbh4->query("SELECT SUM(fld_loaded_subject + fld_loaded_contract) as loaded FROM tbloading;");
                $glytd=$get_loading_ytd->fetch_array();
              ?>
              <span class="text-bold text-lg"><?php echo number_format($glytd['loaded']); ?></span>
              <span>Loaded YTD</span> -->
            </p>
            <p class="ml-auto d-flex flex-column text-right">
              <!-- <span class="text-danger">
                <i class="fas fa-arrow-down"></i> 33.1%
              </span>
              <span class="text-muted">Since last month</span> -->
            </p>
          </div>
          <!-- /.d-flex -->

          <div class="position-relative mb-4">
            <canvas id="processing-rate" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">Submission and Loading Rate as of <?php echo date("d M Y", strtotime($gaodl['fld_date_completed']));  ?></h3>
            <!-- <a href="javascript:void(0);">View Report</a> -->
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex">
            <p class="d-flex flex-column">
              <?php
                $get_loading_ytd = $dbh4->query("SELECT SUM(fld_loaded_subject + fld_loaded_contract) as loaded FROM tbloading;");
                $glytd=$get_loading_ytd->fetch_array();
              ?>
              <span class="text-bold text-lg"><?php echo number_format($glytd['loaded']); ?></span>
              <span>Loaded YTD</span>
            </p>
            <p class="ml-auto d-flex flex-column text-right">
              <!-- <span class="text-danger">
                <i class="fas fa-arrow-down"></i> 33.1%
              </span>
              <span class="text-muted">Since last month</span> -->
            </p>
          </div>
          <!-- /.d-flex -->

          <div class="position-relative mb-4">
            <canvas id="loading-rate" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">Access of Dec 31 2024 <!-- <?php echo date("d M Y", strtotime($gaodi['fld_inqdate']));  ?> --></h3>
            <!-- <a href="javascript:void(0);">View Report</a> -->
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex">
            <p class="d-flex flex-column">
              <?php
                $get_access_ytd = $dbh->query("SELECT SUM(fld_inqcount) as inquiries FROM tbinquiries WHERE YEAR(fld_inqdate) = 2024;");
                $gaytd=$get_access_ytd->fetch_array();
              ?>
              <span class="text-bold text-lg"><?php echo number_format($gaytd['inquiries']); ?></span>
              <span>Total Access YTD</span>
            </p>
            <p class="ml-auto d-flex flex-column text-right">
              <!-- <span class="text-danger">
                <i class="fas fa-arrow-down"></i> 33.1%
              </span>
              <span class="text-muted">Since last month</span> -->
            </p>
          </div>
          <!-- /.d-flex -->

          <div class="position-relative mb-4">
            <canvas id="access-inquiries" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="row"> 
      <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-header">
          <h3 class="card-title">Hit Rate 2025</h3>
          
          </div>
          <div class="card-body">
          <div class="chart">

          <div class="row">
                  
              <!-- ./col -->
              <div class="col-6 col-md-3 text-center">
                <input type="text" class="knob" id="hitrateq1" value="60" data-skin="tron" data-thickness="0.2" data-width="120"
                       data-height="120" data-fgColor="#3c8dbc" disabled>

                <div class="knob-label">Q1</div>
              </div>
              <div class="col-6 col-md-3 text-center">
                <input type="text" class="knob" id="hitrateq2" value="60" data-skin="tron" data-thickness="0.2" data-width="120"
                       data-height="120" data-fgColor="#3c8dbc" disabled>

                <div class="knob-label">Q2</div>
              </div>

              <div class="col-6 col-md-3 text-center">
                <input type="text" class="knob" id="hitrateq3" value="60" data-skin="tron" data-thickness="0.2" data-width="120"
                       data-height="120" data-fgColor="#3c8dbc" disabled>

                <div class="knob-label">Q3</div>
              </div>

              <div class="col-6 col-md-3 text-center">
                <input type="text" class="knob" id="hitrateq4" value="0" data-skin="tron" data-thickness="0.2" data-width="120"
                       data-height="120" data-fgColor="#3c8dbc" disabled>

                <div class="knob-label">Q4</div>
              </div>
              <!-- ./col -->
            
            </div>
          </div>
          <br>  
          <div style="text-align: center;">
            <p id="totalYTDHitRate"></p>
            <p>As of <?php echo date("F d, Y"); ?></p>
          </div>
          </div>
        </div>

        <div class="card card-danger">
          <div class="card-header">
          <h3 class="card-title">Error Code (1-098)</h3>
          
          </div>
          <div class="card-body">
          <div class="chart">
            <div class="chartjs-size-monitor">
              <div class="chartjs-size-monitor-expand">
                <div class="">
                  
                </div>
              </div>
              <div class="chartjs-size-monitor-shrink">
                <div class=""></div>
              </div>
            </div>
          <canvas id="barChartErrorCode" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 764px;" width="764" height="250" class="chartjs-render-monitor">
            
          </canvas>
          </div>
          <div style="text-align: center;">
            <p id="totalYTDErrorCode"></p>
          </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Contract Data</h3>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                  <!-- Bar chart -->
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="far fa-chart-bar"></i>
                        Total Contracts
                      </h3>
                    </div>
                    <div class="card-body">
                      <div id="total-contracts-bar-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.card-body-->
                  </div>
                  <!-- /.card -->

                  <!-- Donut chart -->
                  
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
              <div class="col-md-12">
                  <!-- Bar chart -->
                  <div class="card card-primary card-outline">
                    
                    <div class="card-body">
                        <table class="table"> 
                          <thead>
                            <tr>
                              <th></th>
                              <?php
                                $get_three_months = $dbh->query("SELECT fld_date FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                                while ($gtm=$get_three_months->fetch_array()) {
                              ?>
                              <th><?php echo date("d-M-Y", strtotime($gtm['fld_date'])); ?></th>
                              <?php
                                }
                              ?>
                            </tr>
                          </thead>
                          <tbody> 
                              <tr>  
                                  <th>Credit Cards</th>
                                  <?php
                                    $get_three_months_cc = $dbh->query("SELECT fld_date, fld_contract_credit_card as cc FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                                    while ($gtmcc=$get_three_months_cc->fetch_array()) {
                                  ?>
                                    <td><?php echo number_format($gtmcc['cc']); ?></td>
                                  <?php
                                    }
                                  ?>
                              </tr>
                              <tr>
                                  <th>Installment</th>
                                  <?php
                                    $get_three_months_ci = $dbh->query("SELECT fld_date, fld_contract_installment as ci FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                                    while ($gtmci=$get_three_months_ci->fetch_array()) {
                                  ?>
                                    <td><?php echo number_format($gtmci['ci']); ?></td>
                                  <?php
                                    }
                                  ?>
                              </tr>
                              <tr>
                                  <th>Non-Installment</th>
                                  <?php
                                    $get_three_months_cn = $dbh->query("SELECT fld_date, fld_contract_non_installment as cn FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                                    while ($gtmcn=$get_three_months_cn->fetch_array()) {
                                  ?>
                                    <td><?php echo number_format($gtmcn['cn']); ?></td>
                                  <?php
                                    }
                                  ?>
                              </tr>
                              <tr>
                                <th>Total</th>
                                <?php
                                $get_total = $dbh->query("SELECT * FROM tbcins WHERE fld_date >= now()-interval 1 month ORDER BY fld_date ASC;");
                                while ($gt=$get_total->fetch_array()) {
                                ?>
                                <td><b><?php echo number_format($gt['fld_contract_credit_card'] + $gt['fld_contract_installment'] + $gt['fld_contract_non_installment']); ?></b></td>
                                <?php
                                  }
                                ?>
                              </tr>
                          </tbody>
                        </table>  
                    </div>
                    <!-- /.card-body-->
                  </div>
                  <!-- /.card -->

                  <!-- Donut chart -->
                  
                </div>
                <!-- /.col -->
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
  </div>
  
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Unique Data Subjects</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
            <!-- Bar chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Individual
                </h3>
              </div>
              <div class="card-body">
                <div id="individual-bar-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Donut chart -->
            
          </div>
          <!-- /.col -->

          <div class="col-md-6">
            <!-- Bar chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Company
                </h3>
              </div>
              <div class="card-body">
                <div id="company-bar-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Donut chart -->
            
          </div>
          <!-- /.col -->
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  

</section>
<!-- /.content -->
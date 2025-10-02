<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Manila');
ini_set('MAX_EXECUTION_TIME', '-1');

include("classes/Auth.class.php");

ini_set('session.cache_limiter','public');
session_cache_limiter(false);


$auth = new Auth();

require_once 'config.php';

session_start();



$ip_arr = array("180.232.77.52", "203.160.183.107", "175.176.38.136");
if (!in_array($_SERVER['HTTP_X_FORWARDED_FOR'], $ip_arr)) {
  //Not logged in, send to login page.

  // echo "CURRENT IP: ".$_SERVER['HTTP_X_FORWARDED_FOR'];
  echo "PERMISSION DENIED";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unauthorized</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  </head>
  <body>
    <div class="card">
      <div class="card-body">
        YOU ARE UNAUTHORIZED TO ACCESS THIS PAGE.
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
  </body>
</html>
<?php
} else {

// echo $_SERVER['HTTP_X_FORWARDED_FOR']. " " .$_SERVER['REMOTE_ADDR'];
$get_as_of_date_loading = $dbh4->query("SELECT fld_date_completed FROM tbloading ORDER BY fld_date_completed DESC LIMIT 1;");
$gaodl=$get_as_of_date_loading->fetch_array();

$get_as_of_date_daily_loading = $dbh4->query("SELECT fld_date FROM tbdailyloading ORDER BY fld_date DESC LIMIT 1;");
$gaoddl=$get_as_of_date_daily_loading->fetch_array();

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CIC Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  </head>
  <body>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="row">
              <div class="col-3">
                <!-- Date range -->
                
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
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <?php
if ($_SERVER['REQUEST_URI'] == "/mycic/dailyloading.php") {


  // if ($_SESSION['usertype'] == 0) {
  $indv_arr = array();
  $indv_date_arr = array();
  $data_indv = '';
  $get_cins_individual_data = $dbh->query("SELECT fld_date, fld_subject_individual FROM tbcins WHERE fld_date >= now()-interval 1 month GROUP BY fld_date ORDER BY fld_date ASC LIMIT 3");
  while($gcid=$get_cins_individual_data->fetch_array()){
    $ctrli++;
    $indv_data = $gcid['fld_subject_individual'];
    $indv_date = number_format($indv_data)."<br>".date("M-d-Y", strtotime($gcid['fld_date']));
        
    $data_indv = "[".$ctrli. "," .$indv_data."]"; 
    $date_indv = "[".$ctrli. ", '".$indv_date."']";
    array_push($indv_arr, $data_indv);
    array_push($indv_date_arr, $date_indv);
  }

  end($indv_arr);
  end($indv_date_arr);

  // -----------
  $comp_arr = array();
  $comp_date_arr = array();
  $data_comp = '';
  $get_cins_company_data = $dbh->query("SELECT fld_date, fld_subject_company FROM tbcins WHERE fld_date >= now()-interval 1 month GROUP BY fld_date ORDER BY fld_date ASC LIMIT 3");
  while($gccd=$get_cins_company_data->fetch_array()){
    $ctrlc++;
    $comp_data = $gccd['fld_subject_company'];
    $comp_date = number_format($comp_data)."<br>".date("M-d-Y", strtotime($gccd['fld_date']));
        
    $data_comp = "[".$ctrlc. "," .$comp_data."]";
    $date_comp = "[".$ctrlc. ", '".$comp_date."']";
    array_push($comp_arr, $data_comp);
    array_push($comp_date_arr, $date_comp);
  }

  end($comp_arr);

  // -----------
  $cont_arr = array();
  $cont_date_arr = array();
  $data_cont = '';
  $get_cins_cont_data = $dbh->query("SELECT fld_date, fld_contract_credit_card as cc, fld_contract_installment as ci, fld_contract_non_installment as cn FROM tbcins WHERE fld_date >= now()-interval 3 month GROUP BY MONTH(fld_date), YEAR(fld_date) ORDER BY fld_date ASC LIMIT 3");
  while($gccod=$get_cins_cont_data->fetch_array()){
    $ctrlcont++;
    $cont_data = $gccod['cc'] + $gccod['ci'] + $gccod['cn'];
    $cont_date = number_format($cont_data)."<br>".date("M-d-Y", strtotime($gccod['fld_date']));
    // echo $gccod['fld_date']."- CC: ".$gccod['cc']." - CI: ".$gccod['ci']." - CN: ".$gccod['cn']."<br>";
    $data_cont = "[".$ctrlcont. "," .$cont_data."]";
    $date_cont = "[".$ctrlcont. ", '".$cont_date."']";
    array_push($cont_arr, $data_cont);
    array_push($cont_date_arr, $date_cont);
  }

  end($cont_arr);
  // }
?>
<script type="text/javascript">
  //Date range picker
    $('#reservation').daterangepicker();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.js" integrity="sha512-7DgGWBKHddtgZ9Cgu8aGfJXvgcVv4SWSESomRtghob4k4orCBUTSRQ4s5SaC2Rz+OptMqNk0aHHsaUBk6fzIXw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js" integrity="sha512-ZwR1/gSZM3ai6vCdI+LVF1zSq/5HznD3ZSTk7kajkaj4D292NLuduDCO1c/NT8Id+jE58KYLKT7hXnbtryGmMg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/helpers.js" integrity="sha512-08S2icXl5dFWPl8stSVyzg3W14tTISlNtJekjsQplv326QtsmbEVqL4TFBrRXTdEj8QI5izJFoVaf5KgNDDOMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/helpers.min.js" integrity="sha512-JG3S/EICkp8Lx9YhtIpzAVJ55WGnxT3T6bfiXYbjPRUoN9yu+ZM+wVLDsI/L2BWRiKjw/67d+/APw/CDn+Lm0Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  $(function () {

     /*
     * BAR CHART - INDIVIDUAL
     * ---------
     */

    var bar_data = {
      data : [
              <?php 
                  foreach ($indv_arr as $key) {
                    echo $key.","; 
                    }
              ?>
            ],
      bars: { show: true }
    }
    $.plot('#individual-bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
         bars: {
          show: true, barWidth: 0.5, align: 'center',
        },
      },
      colors: ['#3c8dbc'],
      yaxis: {
        minTickSize: [10, 50]
      },
      xaxis : {
        ticks: [
              <?php
                foreach ($indv_date_arr as $key) {
                  echo $key.",";
                }
              ?>
        ]
      }
    })
    /* END BAR CHART */

  })


</script>
<script>
  $(function() {
          /*
     * BAR CHART - COMPANY
     * ---------
     */

    var comp_bar_data = {
      data : [
              <?php 
                  foreach ($comp_arr as $key) {
                    echo $key.","; 
                    }
              ?>
            ],
      bars: { show: true }
    }
    $.plot('#company-bar-chart', [comp_bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
         bars: {
          show: true, barWidth: 0.5, align: 'center',
        },
      },
      colors: ['#3c8dbc'],
      xaxis : {
        ticks: [
              <?php
                foreach ($comp_date_arr as $key) {
                  echo $key.",";
                }
              ?>
        ]
      }
    })
    /* END BAR CHART */
  })
</script>

<script>
  $(function() {
          /*
     * BAR CHART - CONTRACT
     * ---------
     */

    var cont_bar_data = {
      data : [
              <?php 
                  foreach ($cont_arr as $key) {
                    echo $key.","; 
                    }
              ?>
            ],
      bars: { show: true }
    }
    $.plot('#total-contracts-bar-chart', [cont_bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
         bars: {
          show: true, barWidth: 0.5, align: 'center',
        },
      },
      colors: ['#3c8dbc'],
      xaxis : {
        ticks: [
              <?php
                foreach ($cont_date_arr as $key) {
                  echo $key.",";
                }
              ?>
        ]
      }
    })
    /* END BAR CHART */
  })
</script>

<?php

$yearhitrate = '2025';

$get_hit_rate_q1 = $dbh->query("SELECT fld_inqresult, SUM(fld_inqcount) as inq FROM tbinquiries WHERE (fld_inqresult = 0 OR fld_inqresult = 1) AND fld_inqdate >= '".$yearhitrate."-01-01' AND fld_inqdate <= '".$yearhitrate."-03-31' GROUP BY fld_inqresult;");
while ($ghrq1=$get_hit_rate_q1->fetch_array()) {
  $hr_result = $ghrq1['fld_inqresult'];

  if ($hr_result == 0) {
    $no_hit_q1 = $ghrq1['inq'];
  } elseif ($hr_result == 1) {
    $with_hit_q1 = $ghrq1['inq'];
  }

  $hit_rate_q1_decimal = $with_hit_q1 / ($with_hit_q1 + $no_hit_q1);

  $hit_rate_q1_float = (float)$hit_rate_q1_decimal * 100;
  $hit_rate_q1_percent = substr(round($hit_rate_q1_float, 2), 0, 5);
}

$get_hit_rate_q2 = $dbh->query("SELECT fld_inqresult, SUM(fld_inqcount) as inq FROM tbinquiries WHERE (fld_inqresult = 0 OR fld_inqresult = 1) AND fld_inqdate >= '".$yearhitrate."-04-01' AND fld_inqdate <= '".$yearhitrate."-06-30' GROUP BY fld_inqresult;");
while ($ghrq2=$get_hit_rate_q2->fetch_array()) {
  $hr_result = $ghrq2['fld_inqresult'];

  if ($hr_result == 0) {
    $no_hit_q2 = $ghrq2['inq'];
  } elseif ($hr_result == 1) {
    $with_hit_q2 = $ghrq2['inq'];
  }

  $hit_rate_q2_decimal = $with_hit_q2 / ($with_hit_q2 + $no_hit_q2);

  $hit_rate_q2_float = (float)$hit_rate_q2_decimal * 100;
  $hit_rate_q2_percent = substr(round($hit_rate_q2_float, 2), 0, 5);
}

$get_hit_rate_q3 = $dbh->query("SELECT fld_inqresult, SUM(fld_inqcount) as inq FROM tbinquiries WHERE (fld_inqresult = 0 OR fld_inqresult = 1) AND fld_inqdate >= '".$yearhitrate."-07-01' AND fld_inqdate <= '".$yearhitrate."-09-30' GROUP BY fld_inqresult;");
while ($ghrq3=$get_hit_rate_q3->fetch_array()) {
  $hr_result = $ghrq3['fld_inqresult'];

  if ($hr_result == 0) {
    $no_hit_q3 = $ghrq3['inq'];
  } elseif ($hr_result == 1) {
    $with_hit_q3 = $ghrq3['inq'];
  }

  $hit_rate_q3_decimal = $with_hit_q3 / ($with_hit_q3 + $no_hit_q3);

  $hit_rate_q3_float = (float)$hit_rate_q3_decimal * 100;
  $hit_rate_q3_percent = substr(round($hit_rate_q3_float, 2), 0, 5);
}

$get_hit_rate_q4 = $dbh->query("SELECT fld_inqresult, SUM(fld_inqcount) as inq FROM tbinquiries WHERE (fld_inqresult = 0 OR fld_inqresult = 1) AND fld_inqdate >= '".$yearhitrate."-10-01' AND fld_inqdate <= '".$yearhitrate."-12-31' GROUP BY fld_inqresult;");
while ($ghrq4=$get_hit_rate_q4->fetch_array()) {
  $hr_result = $ghrq4['fld_inqresult'];

  if ($hr_result == 0) {
    $no_hit_q4 = $ghrq4['inq'];
  } elseif ($hr_result == 1) {
    $with_hit_q4 = $ghrq4['inq'];
  }

  $hit_rate_q4_decimal = $with_hit_q4 / ($with_hit_q4 + $no_hit_q4);

  $hit_rate_q4_float = (float)$hit_rate_q4_decimal * 100;
  $hit_rate_q4_percent = substr(round($hit_rate_q4_float, 2), 0, 5);
}

$total_hit_rate = $with_hit_q1 + $with_hit_q2 + $with_hit_q3 + $with_hit_q4;
$total_no_hit_rate = $no_hit_q1 + $no_hit_q2 + $no_hit_q3 + $no_hit_q4;


$total_hit_rate_decimal = $total_hit_rate / ($total_hit_rate + $total_no_hit_rate);
$total_hit_rate_float = (float)$total_hit_rate_decimal * 100;
$total_hit_rate_percent =  substr(round($total_hit_rate_float, 2), 0, 5);



$get_errorcode_q1 = $dbh->query("SELECT COUNT(*) as err_code FROM tbinquiries WHERE fld_errorcode = '1-098' AND fld_inqdate >= '".$yearhitrate."-01-01' AND fld_inqdate <= '".$yearhitrate."-03-31';");
while ($gecq1=$get_errorcode_q1->fetch_array()) {
  $errorcode_q1 = $gecq1['err_code'];
}


$get_errorcode_q2 = $dbh->query("SELECT COUNT(*) as err_code FROM tbinquiries WHERE fld_errorcode = '1-098' AND fld_inqdate >= '".$yearhitrate."-04-01' AND fld_inqdate <= '".$yearhitrate."-06-30';");
while ($gecq2=$get_errorcode_q2->fetch_array()) {
  $errorcode_q2 = $gecq2['err_code'];
}

$get_errorcode_q3 = $dbh->query("SELECT COUNT(*) as err_code FROM tbinquiries WHERE fld_errorcode = '1-098' AND fld_inqdate >= '".$yearhitrate."-07-01' AND fld_inqdate <= '".$yearhitrate."-09-30';");
while ($gecq3=$get_errorcode_q3->fetch_array()) {
  $errorcode_q3 = $gecq3['err_code'];
}

$get_errorcode_q4 = $dbh->query("SELECT COUNT(*) as err_code FROM tbinquiries WHERE fld_errorcode = '1-098' AND fld_inqdate >= '".$yearhitrate."-10-01' AND fld_inqdate <= '".$yearhitrate."-12-31';");
while ($gecq4=$get_errorcode_q4->fetch_array()) {
  $errorcode_q4 = $gecq4['err_code'];
}

$total_error_code = $errorcode_q1 + $errorcode_q2 + $errorcode_q3 + $errorcode_q4;

if ($hit_rate_q1_percent <= 0) {
  $hit_rate_q1_percent = 0;
}

if ($hit_rate_q2_percent <= 0) {
  $hit_rate_q2_percent = 0;
}

if ($hit_rate_q3_percent <= 0) {
  $hit_rate_q3_percent = 0;
}

if ($hit_rate_q4_percent <= 0) {
  $hit_rate_q4_percent = 0;
}
?>
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    document.getElementById("hitrateq1").value = <?php echo $hit_rate_q1_percent; ?>;
    document.getElementById("hitrateq2").value = <?php echo $hit_rate_q2_percent; ?>;
    document.getElementById("hitrateq3").value = <?php echo $hit_rate_q3_percent; ?>;
    document.getElementById("hitrateq4").value = <?php echo $hit_rate_q4_percent; ?>;

    document.getElementById('totalYTDHitRate').innerText = "TOTAL YTD: " + <?php echo $total_hit_rate_percent; ?> + "%";
    document.getElementById('totalYTDHitRate').style.fontWeight = 'bold';

  })
</script>

<?php

if (!$_POST['date_filter_daily_loading']) {
  $_POST['date_filter_daily_loading'] = '01/01/2025 - '.date("m/d/Y");
}

$date_expl = explode(" - ", $_POST['date_filter_daily_loading']);


$date_daily_loading = array();
$subjects_daily_loading = array();
$contracts_daily_loading = array();

$start_date = date("Y-m-d", strtotime($date_expl[0]));
$end_date = date("Y-m-d", strtotime($date_expl[1]));

$get_daily_loading = $dbh4->query("SELECT fld_date, fld_subjects, fld_contracts FROM tbdailyloading WHERE fld_date >= '".$start_date."' and fld_date <= '".$end_date."' GROUP BY fld_date;");
while ($gdl=$get_daily_loading->fetch_array()) {
  array_push($date_daily_loading, strtotime($gdl['fld_date']));
  array_push($subjects_daily_loading, $gdl['fld_subjects']);
  array_push($contracts_daily_loading, $gdl['fld_contracts']);
}


$gproc_total = array();

$get_proc_total = $dbh4->query("SELECT fld_date_completed, SUM(fld_processing_total) as proc_total FROM tbloading GROUP BY fld_date_completed;");
while ($gpt=$get_proc_total->fetch_array()) {

  $rproctotal = round($gpt['proc_total'], 2);


  $total_proc_total = round(($rproctotal / 1440), 2);

  array_push($gproc_total, $total_proc_total);
}


?>

<script>
  const labdate = [
    <?php
        foreach ($date_daily_loading as $key) {
          echo $key.",";
        }
    ?>
  ];

  const new_date = [];

  labdate.forEach(element => {
    var d = new Date(element * 1000);
    
    new_date.push(d.toDateString());
  });


  const data = {
    labels: new_date,
    datasets: [
      {
        label: 'Subjects',
        data: [
          <?php
            foreach ($subjects_daily_loading as $key) {
              echo $key.",";
            }
          ?>
        ],
        borderWidth: 1,
        yAxisID: 'y'
      },
      {
        label: 'Contracts',
        data: [
          <?php
            foreach ($contracts_daily_loading as $key) {
              echo $key.",";
            }
          ?>
        ],
        borderWidth: 1,
        yAxisID: 'y'
      },
      {
        label: 'Processing Time (Day)',
        data: [
          <?php
            foreach ($gproc_total as $key) {
              echo $key.",";
            }
          ?>
        ],
        type: 'line',
        order: 0,
        yAxisID: 'y1'
      }
    ]
  }


  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
      responsive: true,
      scales: {
        x: {
          stacked: true,
        },
        y: {
          stacked: true
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
        }
      },
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Daily Loading'
        }
      }
    },
  });

  
</script>

<script>
  $(function () {
    /* jQueryKnob */

    $('.knob').knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a   = this.angle(this.cv)  // Angle
            ,
              sa  = this.startAngle          // Previous start angle
            ,
              sat = this.startAngle         // Start angle
            ,
              ea                            // Previous end angle
            ,
              eat = sat + a                 // End angle
            ,
              r   = true

          this.g.lineWidth = this.lineWidth

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3)

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value)
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3)
            this.g.beginPath()
            this.g.strokeStyle = this.previousColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
            this.g.stroke()
          }

          this.g.beginPath()
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
          this.g.stroke()

          this.g.lineWidth = 2
          this.g.beginPath()
          this.g.strokeStyle = this.o.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
          this.g.stroke()

          return false
        }
      }
    })
    /* END JQUERY KNOB */

  })
</script>

<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.

    document.getElementById('totalYTDErrorCode').innerText = "TOTAL YTD: " + <?php echo $total_error_code; ?>;
    document.getElementById('totalYTDErrorCode').style.fontWeight = 'bold';

    var areaChartDataErrorCode = {
      labels  : ['Q1', 'Q2', 'Q3', 'Q4'],
      datasets: [
        {
          label               : 'Error Code',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [<?php echo $errorcode_q1; ?>, <?php echo $errorcode_q2; ?>, <?php echo $errorcode_q3; ?>, <?php echo $errorcode_q4; ?>]
        }
      ]
    }

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvasErrorCode = $('#barChartErrorCode').get(0).getContext('2d')
    var barChartDataErrorCode = $.extend(true, {}, areaChartDataErrorCode)
    var temp0ErrorCode = areaChartDataErrorCode.datasets
    barChartDataErrorCode.datasets = temp0ErrorCode

    var barChartOptionsErrorCode = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvasErrorCode, {
      type: 'bar',
      data: barChartDataErrorCode,
      options: barChartOptionsErrorCode
    })
  })
</script>


<?php


$loading_rate_arr = array();
$submission_rate_arr = array();
$date_arr = array();

$loading_submission_rate = $dbh4->query("SELECT DATE_FORMAT(fld_date_completed, '%b') as date_sl, SUM(fld_submitted_subject + fld_submitted_contract) as submitted, SUM(fld_loaded_subject + fld_loaded_contract) as loaded FROM tbloading GROUP BY DATE_FORMAT(fld_date_completed, '%Y-%m');");
while ($lsr=$loading_submission_rate->fetch_array()) {
  array_push($loading_rate_arr, $lsr['loaded']);
  array_push($submission_rate_arr, $lsr['submitted']);
  array_push($date_arr, $lsr['date_sl']);
}


// print_r($loading_rate_arr);
?>
<script>
  /* global Chart:false */
$(function () {

  var areaChartData = {
    labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label               : 'Loaded',
        backgroundColor     : '#1d428a',
        borderColor         : '#1d428a',
        pointRadius          : true,
        pointColor          : '#1d428a',
        pointStrokeColor    : '#1d428a',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: '#1d428a',
        data: [
          <?php
              foreach ($loading_rate_arr as $key) {
                echo $key.",";
              }
          ?>
        ]
      },
      {
        label               : 'Submitted',
        backgroundColor     : '#ffc72c',
        borderColor         : '#ffc72c',
        pointRadius         : false,
        pointColor          : '#ffc72c',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data: [
          <?php
              foreach ($submission_rate_arr as $key) {
                echo $key.",";
              }
          ?>
        ]
      },
    ]
  }

var barChartData = $.extend(true, {}, areaChartData)
//---------------------
//- STACKED BAR CHART -
//---------------------
var stackedBarChartCanvas = $('#loading-rate').get(0).getContext('2d')
var stackedBarChartData = $.extend(true, {}, barChartData)

var stackedBarChartOptions = {
  responsive              : true,
  maintainAspectRatio     : false,
  scales: {
    xAxes: [{
      stacked: true,
    }],
    yAxes: [{
      stacked: true
    }]
  }
}

new Chart(stackedBarChartCanvas, {
  type: 'bar',
  data: stackedBarChartData,
  options: stackedBarChartOptions
})

})

// lgtm [js/unused-local-variable]

</script>


<?php

$proc_pre_arr = array();
$proc_cps_arr = array();
$proc_cpc_arr = array();
$proc_lps_arr = array();
$proc_lpc_arr = array();
$date_proc_arr = array();

$processing_rate = $dbh4->query("SELECT DATE_FORMAT(fld_date_completed, '%b') as date_sl, SUM(fld_processing_pre) as proc_pre, SUM(fld_processing_cps) as proc_cps, SUM(fld_processing_cpc) as proc_cpc, SUM(fld_processing_lps) as proc_lps, SUM(fld_processing_lpc) as proc_lpc FROM tbloading GROUP BY DATE_FORMAT(fld_date_completed, '%Y-%m');");
while ($procr=$processing_rate->fetch_array()) {
  $rprocpre = round($procr['proc_pre'], 2);
  $rproccps = round($procr['proc_cps'], 2);
  $rproccpc = round($procr['proc_cpc'], 2);
  $rproclps = round($procr['proc_lps'], 2);
  $rproclpc = round($procr['proc_lpc'], 2);


  $total_proc_pre = round(($rprocpre / 1440), 2);
  $total_proc_cps = round(($rproccps / 1440), 2);
  $total_proc_cpc = round(($rproccpc / 1440), 2);
  $total_proc_lps = round(($rproclps / 1440), 2);
  $total_proc_lpc = round(($rproclpc / 1440), 2);

  array_push($proc_pre_arr, $total_proc_pre);
  array_push($proc_cps_arr, $total_proc_cps);
  array_push($proc_cpc_arr, $total_proc_cpc);
  array_push($proc_lps_arr, $total_proc_lps);
  array_push($proc_lpc_arr, $total_proc_lpc);
  array_push($date_proc_arr, $procr['date_sl']);
}


$access_with_hit = array();
$access_no_hit = array();
$access_with_error = array();
$date_access_arr = array();

$access_details = $dbh->query("SELECT DATE_FORMAT(fld_inqdate, '%Y-%m') as access_date, fld_inqresult as access_type, SUM(fld_inqcount) as access FROM tbinquiries WHERE YEAR(fld_inqdate) = 2024 GROUP BY DATE_FORMAT(fld_inqdate, '%Y-%m'), fld_inqresult;");
while ($ad=$access_details->fetch_array()) {

  if ($ad['access_type'] == 1) {
    array_push($access_with_hit, $ad['access']);
  }

  if ($ad['access_type'] == 0) {
    array_push($access_no_hit, $ad['access']);
  }

  if ($ad['access_type'] == 2) {
    array_push($access_with_error, $ad['access']);
  }

  // array_push($proc_cps_arr, $total_proc_cps);
  // array_push($proc_cpc_arr, $total_proc_cpc);
  // array_push($proc_lps_arr, $total_proc_lps);
  // array_push($proc_lpc_arr, $total_proc_lpc);
  array_push($date_access_arr, $ad['access_date']);
}


// print_r($loading_rate_arr);
?>


<script>
  /* global Chart:false */
$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $salesChart = $('#processing-rate')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sept', 'Oct'],
      datasets: [
        {
          label: 'PRE',
          backgroundColor: '#0c2340',
          borderColor: '#0c2340',
          data: [
            <?php
                foreach ($proc_pre_arr as $key) {
                  echo $key.",";
                }
            ?>
          ]
        },
        {
          label: 'CPS',
          backgroundColor: '#c8102e',
          borderColor: '#c8102e',
          data: [
            <?php
                foreach ($proc_cps_arr as $key) {
                  echo $key.",";
                }
            ?>
          ]
        },
        {
          label: 'CPC',
          backgroundColor: '#85714d',
          borderColor: '#85714d',
          data: [
            <?php
                foreach ($proc_cpc_arr as $key) {
                  echo $key.",";
                }
            ?>
          ]
        },
        {
          label: 'LPS',
          backgroundColor: '#e6f511',
          borderColor: '#e6f511',
          data: [
            <?php
                foreach ($proc_lps_arr as $key) {
                  echo $key.",";
                }
            ?>
          ]
        },
        {
          label: 'LPC',
          backgroundColor: '#f58b11',
          borderColor: '#f58b11',
          data: [
            <?php
                foreach ($proc_lpc_arr as $key) {
                  echo $key.",";
                }
            ?>
          ]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
})

// lgtm [js/unused-local-variable]

</script>

<script>
  /* global Chart:false */
$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $salesChart = $('#access-inquiries')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
      datasets: [
        {
          label: 'With Hit',
          backgroundColor: '#007ac1',
          borderColor: '#007ac1',
          data: [
            <?php
                foreach ($access_with_hit as $key) {
                  echo $key.",";
                }
            ?>
          ]
        },
        {
          label: 'No Hit',
          backgroundColor: '#002d62',
          borderColor: '#002d62',
          data: [
            <?php
                foreach ($access_no_hit as $key) {
                  echo $key.",";
                }
            ?>
          ]
        },
        {
          label: 'With Error',
          backgroundColor: '#ef3b24',
          borderColor: '#ef3b24',
          data: [
            <?php
                foreach ($access_with_error as $key) {
                  echo $key.",";
                }
            ?>
          ]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
})

// lgtm [js/unused-local-variable]

</script>

<?php
}
?>
  </body>
</html>
<?php

}

?>
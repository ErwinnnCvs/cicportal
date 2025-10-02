<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
ini_set('MAX_EXECUTION_TIME', '-1');
include("classes/Auth.class.php");
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
$auth = new Auth();
require_once 'config.php';

if(!$_GET['nid']){
  $_GET['nid'] = 1;
  $_GET['sid'] = 1;
}

session_start();


if (!isset($_SESSION['user_id'])) {
  //Not logged in, send to login page.
  header("Location: login.php");
} else {
  $act1[$_GET['nid']] = "active ";
  $act2[$_GET['sid']] = " class='active'";
  $oprtrs = array("CIC140007" => "Hannah", "CIC130001" => "Genie", "CIC160025" => "Josan", "CIC160046" => "Trixie", "CIC170049" => "Claude", "CIC200074" => "Vivian", "CIC220112" => "Rayson", "CIC220111" => "Victoria", "CIC220104" => "Jacquiline", "CIC220106" => "Gilbert", "CIC190026" => "Hanselle", "CIC190055" => "Mj", "CIC220110" => "Karen", "CIC220116" => "Amy" );
  $datetime = array(1 => "fld_datetime_registration", "fld_test_datetime_training", "fld_test_datetime_isfaq", "fld_test_datetime_isemail", "fld_test_datetime_iscall1", "fld_test_datetime_iscall2", "fld_test_datetime_iscall3", "fld_datetime_validation", "fld_production_comment");
  $comment = array(1 => "fld_registration_comment", "fld_test_istraining_comment", "fld_test_isfaq_comment", "fld_test_isemail_comment", "fld_test_iscall1_comment", "fld_test_iscall2_comment", "fld_test_iscall3_comment", "fld_validation_comment", "fld_production_comment");
  $SEA = array("10"=>"UB","15"=>"CO","20"=>"CC","25"=>"IH","30"=>"RB","35"=>"UT","40"=>"GF","50"=>"TB","55"=>"TE", "60"=>"PN", "65"=>"PF","70"=>"MF","75"=>"IS","80"=>"LS","85"=>"SAE","90"=>"OT");

  $user_position = array("System Administrator", "Submitting / Accessing Entity", "Special Accessing Entity", "IT", "BDC", "Legal", "Marketing", "Board/CEO/SVP", "Head Operator", "Operator", "Billing", "Security (Head)", "Security (User)", "NOC (Head)", "NOC (User)", "Head Data Submission", "Compliance", "Application", "Press Release", "Test User" , "Dispute" , "BDC Events" , "BDC-SAE" , "Executive Assistant" , "Data Submission");
  $user_access = array("", "", "", "", "", "", "", "", "");
  // print_r($_SESSION);
  
  $sae_label = array("SAE09670" => "CIBI Information, Inc.", "SAE09440" => "CRIF Corporation", "SAE99999" => "CIC SAE ( Test Only )", "SAE09450" => "TransUnion Information Solutions Inc.");

  $SE = array("UB"=>"Commercial/Universal Bank","CO"=>"Cooperative","CC"=>"Credit Card","IH"=>"Investment House","RB"=>"Rural Bank","UT"=>"Utilities","GF"=>"Government Lending Institution","TB"=>"Thrift Bank","TE"=>"Trust Entity", "PN"=>"Pawnshop", "PF"=>"Private Lending Institution","MF"=>"Micro-Finance","IS"=>"Insurance","LS"=>"Leasing","PF"=>"Private Financing / Lending Company","SAE"=>"Special Acessing Entity","OT"=>"Others","GL"=>"Government Lending");
  
  $ent2["UT"] = " ";
  $ent2["UB"] = "Universal/Commercial Bank";
  $ent2["TE"] = "Trust Entity";
  $ent2["TB"] = "Thrift/Savings Bank";
  $ent2["RB"] = "Rural Bank";
  $ent2["MF"] = "Non-Bank Micro-Financing Institution";
  $ent2["IS"] = "Insurance";
  $ent2["IH"] = "Investment House";
  $ent2["GF"] = "Non-Bank Government Lending Institution";
  $ent2["PF"] = "Financing Company";
  $ent2["CO"] = "Credit Cooperative";
  $ent2["CC"] = "Credit Card Institution";
  $ent2["CB"] = "Credit Bureau";
  $ent2["PS"] = "Pawn Shop";
  $ent2["LS"] = "Leasing Company";
  $ent2["OT"] = "Other";
  $ent2["CB"] = "Cooperative Bank";
  $ent2["SL"] = "Savings and Loan Association";
  $ent2["MB"] = "Mutual Benefit Association";
  $ent2["LD"] = "Lending Company";
  $ent2["PLI"] = "Private Lending Institution";
  $ent2["DB"] = "Digital Bank";
  $ent2[""] = "N/A";

  $key = "RA3019";

  // $url_docs = "http://localhost/dqua/employeeportal/uplfiles/ceis/";
  $url_docs = "http://10.250.106.28/uplfiles/ceis/";

  $audit_source = "CIC Portal";

  $abbrmo = array(1 => "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
  $audciccode = array("REVINC"=>"RE Validation - Incomplete", "REVDISC" => "RE Validation - Discrepancy in the contents of the documents submitted", "REVCOM" => "RE Validation - Completed", "REAREJ"=> "RE Approval - REJECTED", "REAAPPR" => "RE Approval - APPROVED");


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CIC Portal</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="plugins/pace-progress/themes/black/pace-theme-flat-top.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini pace-primary">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-cog"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <center><img src="dist/img/employees-2x2/<?php echo $_SESSION['image']; ?>" class="img-circle elevation-2" alt="User Image"></center>
          <span class="dropdown-item dropdown-header"><?php echo $_SESSION['name']; ?></span>
         
          <div class="dropdown-divider"></div>
          <a href="main.php?nid=99&sid=9&rid=9" class="dropdown-item dropdown-footer">Profile</a>
          <a href="logout.php" class="dropdown-item dropdown-footer">Logout</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/cic-logo.png"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">CIC Portal</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/employees-2x2/<?php echo $_SESSION['image']; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="main.php?nid=99&sid=9&rid=9" class="d-block"><?php echo $_SESSION['name']; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      
      <nav class="mt-2">
        
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MAIN NAVIGATION</li>
       
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php

            $get_all_menus = $dbh->query("SELECT * FROM tbmenu WHERE fld_published = 1 and fld_nid > 0 and fld_sid < 1");
            while ($gam=$get_all_menus->fetch_array()) {

              $get_link_access = explode("|", $gam['fld_users']);
              if (in_array($_SESSION['user_id'], $get_link_access)) {
          ?>
          <li class="nav-item">
            <a href="main.php?nid=<?php echo $gam['fld_nid']; ?>&sid=<?php echo $gam['fld_sid']; ?>&rid=<?php echo $gam['fld_rid']; ?>" class="nav-link">
              <i class="nav-icon fas <?php echo $gam['fld_icon'] ?>"></i>
              <p>
                <?php echo $gam['fld_title']; ?>
              </p>
            </a>
          </li>
          <?php
              }
            }
          ?>
      
       
          
        
        </ul>
      
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


  <?php

    $get_page_menu = $dbh5->query("SELECT * FROM tbmenu WHERE fld_published = 1 and fld_nid = '".$_GET['nid']."' and fld_sid = '".$_GET['sid']."' and fld_rid = '".$_GET['rid']."' ");
    $gpm=$get_page_menu->fetch_array();

    $get_user_access = explode("|", $gpm['fld_users']);

    if (in_array($_SESSION['user_id'], $get_user_access)) {
      if ($gpm['fld_maintenance'] == 1 and $_SESSION['user_id'] != 76) {
  ?>
          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1>403</h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">403</a></li>
                      <li class="breadcrumb-item active"></li>
                    </ol>
                  </div>
                </div>
              </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

              <!-- Default box -->
              <div class="card card-danger">
                <div class="card-header">
                  <h3 class="card-title">Module Maintenance</h3>
                </div>
                <div class="card-body">
                  Apologies for the inconvenience, but this page you're accessing is under maintenance. Please contact your developer or go back to <a href="main.php?nid=1&sid=0&rid=0">home</a>.
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->

            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->
  <?php
      } else {
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo $gpm['fld_title']; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="main.php?nid=1&sid=0&rid=0">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $gpm['fld_title']; ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <?php
      $page_location = "views/cicP".$_GET['nid'].$_GET['sid'].$_GET['rid'].".php";

      if (file_exists($page_location)) {
        include("views/cicP".$_GET['nid'].$_GET['sid'].$_GET['rid'].".php");
      } else {
    ?>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">403</h3>
        </div>
        <div class="card-body">

          Ooops! Page not found. Please contact your developer or go back to <a href="main.php?nid=1&sid=0&rid=0">home</a>.
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
    <?php
      }
    ?>
    
  </div>
  <!-- /.content-wrapper -->
  <?php
      }
    } else {
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>403</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">403</a></li>
              <li class="breadcrumb-item active"></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-danger">
        <div class="card-header">
          <h3 class="card-title">Unauthorized Access</h3>
        </div>
        <div class="card-body">
        <?php print_r($_SESSION);?>
          Sorry, you don't have access to this page. Please contact your developer or go back to <a href="main.php?nid=1&sid=0&rid=0">home</a>.
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php
    }
  ?>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.2
    </div>
    <strong>Credit Information Corporation
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- pace-progress -->
<script src="plugins/pace-progress/pace.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- FLOT CHARTS -->
<script src="plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="plugins/flot/plugins/jquery.flot.resize.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="plugins/flot/plugins/jquery.flot.pie.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script type="text/javascript" src="js/script.js"></script>

<?php
if ($_GET['nid'] == 42) {
?>
<script>
  $(function(){
    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
  })
</script>
<?php
}
?>

<?php
if ($_GET['nid'] == 44 || $_GET['nid'] == 45) {
  // if ($_SESSION['usertype'] == 0) {
  $indv_arr = array();
  $indv_date_arr = array();
  $data_indv = '';
  $get_cins_individual_data = $dbh->query("SELECT fld_date, fld_subject_individual FROM tbcins WHERE fld_date >= now()-interval 3 month GROUP BY fld_date ORDER BY fld_date ASC LIMIT 4");
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
  $get_cins_company_data = $dbh->query("SELECT fld_date, fld_subject_company FROM tbcins WHERE fld_date >= now()-interval 3 month GROUP BY fld_date ORDER BY fld_date ASC LIMIT 4");
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
  $get_cins_cont_data = $dbh->query("SELECT fld_date, fld_contract_credit_card as cc, fld_contract_installment as ci, fld_contract_non_installment as cn FROM tbcins WHERE fld_date >= now()-interval 3 month GROUP BY MONTH(fld_date), YEAR(fld_date) ORDER BY fld_date ASC LIMIT 4");
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
}
?>

<script>
$(function () {
  bsCustomFileInput.init();
});
</script>

<?php

if ($_GET['nid'] == 21) {
?>
<script>
  $(function () {
    $("#tbasaes").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#tbasaes_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 40) {
?>
<script>
  $(function () {
    $("#uat_creds_pending").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#uat_creds_pending_wrapper .col-md-6:eq(0)');
    $("#uat_creds_generated").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#uat_creds_generated_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 23) {
?>
<script>
  $(function () {
    $("#uat_creds_pending").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#uat_creds_pending_wrapper .col-md-6:eq(0)');
    $("#uat_creds_generated").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#uat_creds_generated_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 11) {
?>
<script>
  $(function () {
    $("#dqua_creds_pending").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#dqua_creds_pending_wrapper .col-md-6:eq(0)');
    $("#dqua_creds_generated").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#dqua_creds_generated_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 34) {
?>
<script>
  $(function () {
    $("#list_of_ctnla").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#list_of_ctnla_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 28) {
?>
<script>
  $(function () {
    $("#validate_dispute_pending").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#validate_dispute_pending_wrapper .col-md-6:eq(0)');
    $("#validate_dispute_completed").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#validate_dispute_completed_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}


?>

<?php

if ($_GET['nid'] == 32) {
?>
<script>
  $(function () {
    $("#seis_prod_validation").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#seis_prod_validation_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 31) {
?>
<script>
  $(function () {
    $("#prod_utilities").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#prod_utilities_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 16) {
?>
<script>
  $(function () {
    $("#merging_pending").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#merging_pending_wrapper .col-md-6:eq(0)');
    $("#merging_completed").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#merging_completed_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 29) {
?>
<script>
  $(function () {
    $("#compliance_monitoring_tool").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      // "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#compliance_monitoring_tool_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 27) {
?>
<script>
  $(function () {
    $("#disputeVerificationPending").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#disputeVerificationPending_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 2) {
?>
<script>
  $(function () {
    $("#re_se").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#re_se_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 3) {
?>
<script>
  $(function () {
    $("#re_pending_se").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#re_pending_se_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>
<?php

if ($_GET['nid'] == 15) {
?>
<script>
  $(function () {
    $("#delist_se").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["print"]
    }).buttons().container().appendTo('#delist_se_wrapper .col-md-6:eq(0)');
    $("#delisted_se").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["print"]
    }).buttons().container().appendTo('#delisted_se_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>


<?php

if ($_GET['nid'] == 5) {
?>
<script>
  $(function () {
    $("#se_inquiries").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#se_inquiries_wrapper .col-md-6:eq(0)');
    $("#cibi_inquiries").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#cibi_inquiries_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php
  if ($_GET['nid'] == 6) {
?>
<script>
  $(function () {
    $("#se_inquiries").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#se_inquiries_wrapper .col-md-6:eq(0)');
    $("#cibi_inquiries").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#cibi_inquiries_wrapper .col-md-6:eq(0)');
    $("#old_balance_monitoring").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#old_balance_monitoring_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
  }

  if ($_GET['nid'] == 17) {
    ?>
    <script>
      //Date picker
    $('#reservationdate').datetimepicker({
       
    });
    </script>
    <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <?php  

    }

    if ($_GET['nid'] == 100){ ?>
      <script src="js/pages.js"></script>
      <?php
    }

    
  if ($_GET['nid'] == 113){ ?>
    <script>
    $(function () {
      $('#example1').DataTable( {
      fixedHeader: true
    });
      $('#example2').DataTable( {
      fixedHeader: true
    });
  });
  </script>
    <?php
  }
  

  if ($_GET['nid'] == 108) {
    ?>
    <script>
      $(function () {
        $('#example1').DataTable( {
        fixedHeader: true
      });
        $('#example2').DataTable( {
        fixedHeader: true
      });
         
      });
    </script>
    <script type="text/javascript" src="js/ds2.js"></script>

    <?php  

  
    }

    

    if ($_GET['nid'] == 112) {
      ?>
      <script>
        $(function () {
          $('#example1').DataTable( {
          fixedHeader: true
        });
          $('#example2').DataTable( {
          fixedHeader: true
        });
          $('#example3').DataTable( {
          fixedHeader: true
        });
           
        });
      </script>
      <script type="text/javascript" src="js/aeisds.js"></script>

      <?php
       if ($_GET['sid'] == 1){ ?>
          
         
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
      <?php } 
      }

      if ($_GET['nid'] == 114) {
        ?>
        <script>
          $(function () {
            $('#example1').DataTable( {
            fixedHeader: true
          });
            $('#example2').DataTable( {
            fixedHeader: true
          });
          $('#example3').DataTable( {
            fixedHeader: true
          });
             
          });
        </script>
        <script type="text/javascript" src="js/aeisds.js"></script>
        <script type="text/javascript" src="js/dsstats.js"></script>
        <script type="text/javascript" src="js/security.js"></script>
        <script type="text/javascript" src="js/ds2.js"></script>
        <?php  
      
        }
        
  if ($_GET['nid'] == 115) {
    ?>
    <script>
      $(function () {
        $('#example1').DataTable( {
        fixedHeader: true
      });
        $('#example2').DataTable( {
        fixedHeader: true
      });
        $('#example3').DataTable( {
        fixedHeader: true
      });
         
      });
    </script>

    <?php  

    }

    if ($_GET['nid'] == 110) {
      ?>
     <script type="text/javascript">
        $('#seis').DataTable({
          //   responsive: true,
          //   "order": [[ 2, "desc" ], [ 1, "asc" ]],
          //   "ordering": true,
          //   "columnDefs": [{
          //               "targets": [ 2 ],
          //               "visible": false
          //           },
          //           {
          //   orderable: false,
          //   targets: "no-sort"
          // }
          //       ]
        });
        $('#seis2').DataTable({
            // responsive: true,
            // ordering: false
        });
        $('#seis3').DataTable({
            // responsive: true,
            // ordering: false
        });
    </script>
  
      <?php  
  
      }
    
?>



<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  });

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false;

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template");
  previewNode.id = "";
  var previewTemplate = previewNode.parentNode.innerHTML;
  previewNode.parentNode.removeChild(previewNode);

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  });

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
  });

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
  });

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1";
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
  });

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0";
  });

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
  };
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true);
  };
  // DropzoneJS Demo Code End
</script>
<?php
if ($_GET['nid'] == '33' || $_GET['nid'] == '39') {
?>
<script type="text/javascript">


  $(".btnAttend").click(function(e){
    // console.log($(this).parent().children('input[name="id"]').val());
    $form = $(this).parent();
    $('#attended_name').html($form.children('input[name="name"]').val());
    $('#attended_btn').val($form.children('input[name="id"]').val());
    $modal = $('#modal-attended');
    $modal.modal('show');
  });
</script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="js/arrival.js"></script>
	<script type="text/javascript" src="js/dsstats.js"></script>
  <script type="text/javascript" src="js/ds2.js"></script>
  <script type="text/javascript" src="js/aeisds.js"></script>
  <script type="text/javascript" src="js/security.js"></script>
  <script type="text/javascript" src="js/csvtrans.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
    var t = $('#tbevaluation').DataTable( {
            dom: 'lBfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
            order: [[ 1, 'asc' ]]
        });
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#tbdisputelist').DataTable({'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-1, -2] /* 1st one, start by the right */
      }]
    });
} );
    
      </script>

      
      
      ?>
<?php
}

                switch($_GET['nid']){
                case 102:
                switch($_GET['sid']){
                  case 0:
                    switch($_GET['rid']){
                      case 0:
                        ?>
                        <script src="./plugins/ckeditor/ckeditor.js"></script>
                        <script type="text/javascript" src="js/emailer1.js"></script>
                        <?php 
                        break;
                    }
                }
                case 106:
                  switch($_GET['sid']){
                    case 0:
                      switch($_GET['rid']){
                        case 0:
            
                          ?>
                          <script type="text/javascript" src="js/arrival.js"></script>
                          <?php 
                          break;
                      }
                  }

                  case 107:
                    switch($_GET['sid']){
                      case 0:
                        switch($_GET['rid']){
                          case 0:
              
                            ?>
                            <script type="text/javascript" src="js/arrival.js"></script>
                            <?php 
                            break;
                        }
                    }
                  
                  case 113:
                    ?>
                    <script type="text/javascript" src="js/aeisds.js"></script>
                    <script type="text/javascript" src="js/dsstats.js"></script>
                    <script type="text/javascript" src="js/security.js"></script>
                    <script type="text/javascript" src="js/ds2.js"></script>
                    
                    <?php 
                    break;

                    

              
             }
             

?>
</body>
</html>

<?php
}

?>
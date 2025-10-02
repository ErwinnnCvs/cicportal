<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
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
    // echo "SESSION".$_SESSION['success_otp'];
  if ($_SESSION['success_otp'] < 2) {
      header('Location: otp.php');
  } else {
  $act1[$_GET['nid']] = "active ";
  $act2[$_GET['sid']] = " class='active'";
  $oprtrs = array("CIC140007" => "Hannah", "CIC130001" => "Genie", "CIC160025" => "Josan", "CIC160046" => "Trixie", "CIC170048" => "Nina", "CIC170049" => "Claude", "CIC170026" => "Hanselle", "CIC170027" => "Reyce", "CIC170021" => "Krystal", "CIC200074" => "Vivian", "CIC240001" => "Horace", "CIC250001" => "Horace");
  $datetime = array(1 => "fld_datetime_registration", "fld_test_datetime_training", "fld_test_datetime_isfaq", "fld_test_datetime_isemail", "fld_test_datetime_iscall1", "fld_test_datetime_iscall2", "fld_test_datetime_iscall3", "fld_datetime_validation", "fld_production_comment");
  $comment = array(1 => "fld_registration_comment", "fld_test_istraining_comment", "fld_test_isfaq_comment", "fld_test_isemail_comment", "fld_test_iscall1_comment", "fld_test_iscall2_comment", "fld_test_iscall3_comment", "fld_validation_comment", "fld_production_comment");
  $SEA = array("10"=>"UB","15"=>"CO","20"=>"CC","25"=>"IH","30"=>"RB","35"=>"UT","40"=>"GF","50"=>"TB","55"=>"TE", "60"=>"PN", "65"=>"PF","70"=>"MF","75"=>"IS","80"=>"LS","85"=>"SAE","90"=>"OT");

  $user_position = array("System Administrator", "Submitting / Accessing Entity", "Special Accessing Entity", "IT", "BDC", "Legal", "Marketing", "Board/CEO/SVP", "Head Operator", "Operator", "Billing", "Security (Head)", "Security (User)", "NOC (Head)", "NOC (User)", "Head Data Submission", "Compliance", "Application", "Press Release", "Test User" , "Dispute" , "BDC Events" , "BDC-SAE" , "Executive Assistant" , "Data Submission");
  $user_access = array("", "", "", "", "", "", "", "", "");
  // print_r($_SESSION);

  $cmpriority = array(1=>"Low", 2=>"Medium", 3=>"High", 4=>"Urgent");

  $cmstatus = array(2=>"Open", 3=>"Pending", 4=>"Resolved", 5=>"Closed", 9=>"In Progress");

  $tech_team = array(12, 155, 179, 96, 130, 9);

  $legal_team = array(143, 132, 173, 150);
  
  $sae_label = array("SAE09670" => "CIBI Information, Inc.", "SAE09440" => "CRIF Corporation", "SAE99999" => "CIC SAE ( Test Only )");

  $SE = array("UB"=>"Commercial/Universal Bank","CO"=>"Cooperative","CC"=>"Credit Card","IH"=>"Investment House","RB"=>"Rural Bank","UT"=>"Utilities","GF"=>"Government Lending Institution","TB"=>"Thrift Bank","TE"=>"Trust Entity", "PN"=>"Pawnshop", "PF"=>"Private Lending Institution","MF"=>"Micro-Finance","IS"=>"Insurance","LS"=>"Leasing","PF"=>"Private Financing / Lending Company","SAE"=>"Special Acessing Entity","OT"=>"Others","GL"=>"Government Lending");
  
  $ent2["UT"] = "Utility";
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
  $url_docs = "http://10.250.106.28/employeeportal/uplfiles/ceis/";

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

  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center" style="background-color: #ffffff;">
    <img class="animation__shake" src="dist/img/cic-logo.png" alt="CICLogo" height="60" width="60">
  </div> -->
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
    <a href="index.php" class="brand-link">
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
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php

            $get_all_menus = $dbh->query("SELECT * FROM tbmenu WHERE fld_published = 1 and fld_nid > 0 and fld_sid < 1");
            while ($gam=$get_all_menus->fetch_array()) {

              $get_link_access = explode("|", $gam['fld_users']);
              if (in_array($_SESSION['user_id'], $get_link_access) or $_SESSION['usertype'] == 0) {
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

    $get_page_menu = $dbh->query("SELECT * FROM tbmenu WHERE fld_published = 1 and fld_nid = '".$_GET['nid']."' and fld_sid = '".$_GET['sid']."' and fld_rid = '".$_GET['rid']."' ");
    $gpm=$get_page_menu->fetch_array();

    $get_user_access = explode("|", $gpm['fld_users']);

    if (in_array($_SESSION['user_id'], $get_user_access) or $_SESSION['usertype'] == 0) {
      if ($gpm['fld_maintenance'] == 1 and $_SESSION['usertype'] != 0 || $gpm['fld_maintenance'] == 1 and $_SESSION['usertype'] and $_SESSION['user_id'] != 12) {
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

<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script type="text/javascript" src="js/script.js"></script>

<!-- <script src="scripts/scripts.js"></script> -->
<?php
if ($_GET['nid'] == 141 || $_GET['nid'] == 45 || $_GET['nid'] == 1) {


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
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="plugins/sparklines/sparkline.js"></script>
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
  $_POST['date_filter_daily_loading'] = '01/01/2024 - '.date("m/d/Y");
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
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>

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
  if ($_GET['nid'] == 158) {
?>

<?php
    $error_codes_pto = array();
    $get_all_error_codes_pto = $dbh4->query("SELECT fld_error_code, fld_error_message, COUNT(*) as pto_error_count FROM tbcisauditloginquiries WHERE fld_startdate LIKE '".$_POST['yearmonthday-pto']."%' GROUP BY fld_error_code, fld_error_message;");
    while ($gaec=$get_all_error_codes_pto->fetch_array()) {
      if (empty($gaec['fld_error_code'])) {
        // if (!empty($gaec['fld_error_message'])) {
        //   $error_code_label = $gaec['fld_error_message'];
        // } else {
        //   $error_code_label = "N/A";
        // }
        $error_code_label = "N/A";
      } else {
        $error_code_label = $gaec['fld_error_code']; 
      }

      $error_codes_pto[$error_code_label] = $gaec['pto_error_count'];
    }



  ?>

<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="plugins/sparklines/sparkline.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.js" integrity="sha512-7DgGWBKHddtgZ9Cgu8aGfJXvgcVv4SWSESomRtghob4k4orCBUTSRQ4s5SaC2Rz+OptMqNk0aHHsaUBk6fzIXw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js" integrity="sha512-ZwR1/gSZM3ai6vCdI+LVF1zSq/5HznD3ZSTk7kajkaj4D292NLuduDCO1c/NT8Id+jE58KYLKT7hXnbtryGmMg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/helpers.js" integrity="sha512-08S2icXl5dFWPl8stSVyzg3W14tTISlNtJekjsQplv326QtsmbEVqL4TFBrRXTdEj8QI5izJFoVaf5KgNDDOMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/helpers.min.js" integrity="sha512-JG3S/EICkp8Lx9YhtIpzAVJ55WGnxT3T6bfiXYbjPRUoN9yu+ZM+wVLDsI/L2BWRiKjw/67d+/APw/CDn+Lm0Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

  var epto = document.getElementById("yearmonthday-pto");
  var valuepto = epto.value;
  var textpto = epto.options[epto.selectedIndex].text;


  const ctx = document.getElementById('pto-doughnut');

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: [
          <?php 
            foreach($error_codes_pto as $errorlbl=>$errorcnt){
              if (!empty($errorlbl)) {
                echo "'".$errorlbl."',";
              }
            }
          ?>
        ],
      datasets: [{
        label: 'Count',
        data: [<?php 
            foreach($error_codes_pto as $errorlbl=>$errorcnt){
              if (!empty($errorcnt)) {
                echo $errorcnt.",";
              }
            }
          ?>],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
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
    $("#dispute_completed").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#dispute_completed_wrapper .col-md-6:eq(0)');
    $("#dispute_resolve").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#dispute_resolve_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
}

?>

<?php

if ($_GET['nid'] == 26) {
?>
<script>
  $(function () {
    $("#merging_pending").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#merging_pending_wrapper .col-md-6:eq(0)');
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

if ($_GET['nid'] == 30) {
?>
<script>
  $(function () {
    $("#newregistered_entities").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#newregistered_entities_wrapper .col-md-6:eq(0)');
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
  if ($_GET['nid'] == 116) {
?>
<script>
  $(function () {
    $("#submissionstablearrival").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#submissionstablearrival_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
  }
     if ($_GET['nid'] == 123) {
            if($_GET['sid'] == 0){
            ?>
             <script>
                $(function() {
                  $("#example1").DataTable({
                    "responsive": true, "lengthChange": true, "autoWidth": false,
                    "buttons": ["csv", "excel", "pdf", "print"]
                  })
                  .buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
              
                  
                });
              </script>
              <script type="text/javascript" src="js/ds2.js"></script>
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
              <script type="text/javascript" src="js/trasnmittal.js"></script>
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
           
            <?php
              }
            }

  switch ($GET['nid']) {
      case 123:
                      switch($_GET['sid']){
                        case 0:
                          switch($_GET['rid']){
                            case 0:
                
                              ?>                     
                              <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
                              <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                              <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
                              <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
                              <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
                              <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
                              <script type="text/javascript" src="js/countrecords.js"></script>
                              <?php 
                              break;
                          }
                      }
  }
?>
<?php
  if ($_GET['nid'] == 123) {
?>
<script>
  $(function () {
    $("#submissionsmonitoringtable").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#submissionsmonitoringtable_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
  }
?>

<?php
if ($_GET['nid'] == 44) {
?>
<script>
  
  $(function () {
    $("#hitratetable_monthly").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false, "paging": false, "searching": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#hitratetable_monthly_wrapper .col-md-6:eq(0)');
  });

</script>
<?php
}
?>

<?php
if ($_GET['nid'] == 135) {
?>
<script>
  
  $(function () {
    $("#example1").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false, "pageLength": 1000, "paging": false, "searching": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });

</script>
<?php
}
?>

<?php
if ($_GET['nid'] == 140) {
?>
<script>
  
  $(function () {
    $("#unassignedse").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false, "pageLength": 50,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#unassignedse_wrapper .col-md-6:eq(0)');
  });

</script>
<?php
}
?>


<?php
if ($_GET['nid'] == 151) {
?>
<script>
  
  $(function () {
    $("#disputeIngestionQ1").DataTable({
    "responsive": false, "lengthChange": true, "autoWidth": false,
                          "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#disputeIngestionQ1_wrapper .col-md-6:eq(0)');
  });

</script>
<?php
}
?>


<?php
if ($_GET['nid'] == 152) {
?>
<script>
  
  $(function () {
    $("#disputeIngestionQ2").DataTable({
    "responsive": false, "lengthChange": true, "autoWidth": false,
                          "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#disputeIngestionQ2_wrapper .col-md-6:eq(0)');
  });

</script>
<?php
}
?>


<?php
if ($_GET['nid'] == 153) {
?>
<script>
  
  $(function () {
    $("#disputeIngestionQ3").DataTable({
    "responsive": false, "lengthChange": true, "autoWidth": false,
                          "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#disputeIngestionQ3_wrapper .col-md-6:eq(0)');
  });

</script>
<?php
}
?>




<?php

if ($_GET['nid'] == 139) {
?>

<script>
  
  $(function () {
    $("#tickets_table").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false, "pageLength": 50, "ordering": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"],
    }).buttons().container().appendTo('#tickets_table_wrapper .col-md-6:eq(0)');
  });

</script>

<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    const btnReply = document.getElementById("replyBtn");

    btnReply.addEventListener("click", composeFunction);

    function composeFunction() {
      const replycard = document.getElementById("reply-card");
      const composecard = document.getElementById("compose-card");
      const composesendcard = document.getElementById("compose-send-card");

      replycard.setAttribute("hidden", true);
      composecard.removeAttribute("hidden");
      composesendcard.removeAttribute("hidden");
      $('#compose-textarea').summernote()
    }


    $('#sel_status').change(function() {
        var sel_status_input = document.getElementById('sel_status').value;
        if (sel_status_input != "") {
            document.getElementById('btnUpdate').removeAttribute("disabled");
        } else {
            document.getElementById('btnUpdate').setAttribute("disabled", null);
        }
    });

    $('#sel_priority').change(function() {
        var sel_priority_input = document.getElementById('sel_priority').value;
        if (sel_priority_input != "") {
            document.getElementById('btnUpdate').removeAttribute("disabled");
        } else {
            document.getElementById('btnUpdate').setAttribute("disabled", null);
        }
    });
  });
</script>
<?php
}
?>

<?php
  if ($_GET['nid'] == 124 || $_GET['nid'] == 129 || $_GET['nid'] == 130) {
?>
<script>
  $(function () {
    $("#submissionstablearrival").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#submissionstablearrival_wrapper .col-md-6:eq(0)');
  });
</script>

<script>
  $(function () {
    $("#caftable").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false, "pageLength": 100,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#caftable_wrapper .col-md-6:eq(0)');
  });
</script>


<script>
  $(function () {
    $("#filedTransmittals").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false, "pageLength": 10,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#filedTransmittals_wrapper .col-md-6:eq(0)');
  });
</script>

<script>
  $(function () {
    $("#pendingTransmittal").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false, "pageLength": 10,
      "buttons": ["csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#pendingTransmittal_wrapper .col-md-6:eq(0)');
  });
</script>
<?php
  }
?>




<?php
if ($_GET['nid'] == 137 and $_GET['sid'] == 0) {
?>
<script>
  $(function () {
    $("#casemanagement").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false, "paging": true, "pageLength": 10, "ordering": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#casemanagement_wrapper .col-md-6:eq(0)');
  });
</script>

<?php
}
?>

<?php
if ($_GET['nid'] == 139 and $_GET['sid'] == 1) {
?>
<script>
  $(document).ready(function(){
    var getProv = document.getElementById('getProvCode').value;
    $.ajax({
       url: 'ajax/ticketsUpdate.php',
       type: 'POST',
       data: {provcode : getProv},
       // dataType: "json",
       success: function(response){
          console.log(response)
       }
    });
  })

</script>

<?php
}
?>

<?php
if ($_GET['nid'] == 137 and $_GET['sid'] == 1) {
?>
<script>
  $(document).ready(function(){
    var getProv = document.getElementById('getProvCode').value;
    $.ajax({
       url: 'ajax/ticketsUpdate.php',
       type: 'POST',
       data: {provcode : getProv},
       // dataType: "json",
       success: function(response){
          console.log(response)
       }
    });
  })

</script>
<?php
}


           if ($_GET['nid'] == 142) {
                if($_GET['sid'] == 2){
                ?>
                 <script>
                    $(function() {
                      $("#disputeVerificationPending").DataTable({
                        "responsive": true, "lengthChange": true, "autoWidth": false,
                        "buttons": ["csv", "excel", "pdf", "print"]
                      })
                      .buttons().container().appendTo('#disputeVerificationPending_wrapper .col-md-6:eq(0)');
                  
                      
                    });
                  </script>
          
               
                <?php
                  }
                }

              
              if ($_GET['nid'] == 142) {
                if($_GET['sid'] == 0){
                ?>
                 <script>
                    $(function() {
                      $("#disputeVerificationInprog").DataTable({
                        "responsive": true, "lengthChange": true, "autoWidth": false,
                        "buttons": ["csv", "excel", "pdf", "print"]
                      })
                      .buttons().container().appendTo('#disputeVerificationInprog_wrapper .col-md-6:eq(0)');
                  
                      
                    });
                  </script>
          
               
                <?php
                  }
                }

              if ($_GET['nid'] == 142) {
                if($_GET['sid'] == 1){
                ?>
                 <script>
                    $(function() {
                      $("#disputeVerificationCompleted").DataTable({
                        "responsive": true, "lengthChange": true, "autoWidth": false,
                        "buttons": ["csv", "excel", "pdf", "print"]
                      })
                      .buttons().container().appendTo('#disputeVerificationCompleted_wrapper .col-md-6:eq(0)');
                  
                      
                    });
                  </script>
          
               
                <?php
                  }
                }
?>


<?php
if ($_GET['nid'] == 138) {
?>
<script>
  
  $(function () {
    $("#assignedse").DataTable({
      "responsive": false, "lengthChange": false, "autoWidth": false, "paging": true, "pageLength": 100,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#assignedse_wrapper .col-md-6:eq(0)');
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
  if ($_GET['nid'] == 143) {
      if($_GET['sid'] == 0){
      ?>
       <script>
          $(function() {
            $("#disputeMonitoring").DataTable({
              "responsive": false, "lengthChange": true, "autoWidth": false,
              "buttons": ["csv", "excel", "pdf", "print"]
            })
            .buttons().container().appendTo('#disputeMonitoring_wrapper .col-md-6:eq(0)');
        
            
          });
        </script>

     
      <?php
        }
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
  });
</script>
<?php
  }
?>






<?php
  if ($_GET['nid'] == 139 and $_GET['sid'] == 1) {
?>
<script type="text/javascript">
  $(document).ready(function(){
    var sel = $("#sel_group");
    $.ajax({
       url: 'tickets/groups.php',
       type: 'GET',
       dataType: "json",
       success: function(response){
          console.log("RESPONSE: " + response)
          var options = JSON.parse(response);
          console.log(options)
         //  for(var i = 0; i < options.length; i++) {
         //    var opt = options[i];

         //    console.log(opt);
         //    // sel.append("<option value=\"" + opt + "\">" + opt + "</option>");
         // }
       }
    });
    // alert("REX CEASAR APE");
  })
</script>
<?php
  }
?>


<?php 
if ($_GET['nid'] == 149) {
?>

  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <script src="plugins/summernote/summernote-bs4.min.js"></script>

<script>
    $('#start_date').datetimepicker({
        format: 'YYYY/MM/DD',
        maxDate: new Date()
    });

    $('#end_date').datetimepicker({
        format: 'YYYY/MM/DD',
        maxDate: new Date()
    });

     $(function () {
     //Add text editor
     $('#compose-textarea').summernote()
   });


     $(function () {
    $("#initialcompliance").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv","excel", "pdf", "print"]
    }).buttons().container().appendTo('#initialcompliance_wrapper .col-md-6:eq(0)');
  });
</script>

<?php
}

?>


<?php

if ($_GET['nid'] == 149) {
?>
<script>
  
</script>
<?php
}

?>

<?php


if ($_GET['nid'] == 154) {
  if($_GET['sid'] == 0){
  ?>
   <script>
      $(function() {
        $("#disputeSummary").DataTable({
          "responsive": false, "lengthChange": true, "autoWidth": false,
          "buttons": ["csv", "excel", "pdf", "print"]
        })
        .buttons().container().appendTo('#disputeSummary_wrapper .col-md-6:eq(0)');
    
        
      });
    </script>

 
  <?php
    }
  }

if ($_GET['nid'] == 157) {
  if($_GET['sid'] == 0){
  ?>
   <script>
      $(function() {
        $("#disputeSummary2").DataTable({
          "responsive": false, "lengthChange": true, "autoWidth": false,
          "buttons": ["csv", "excel", "pdf", "print"]
        })
        .buttons().container().appendTo('#disputeSummary2_wrapper .col-md-6:eq(0)');
    
        
      });
    </script>

 
  <?php
    }
  }
                  

if ($_GET['nid'] == 150) {
?>
<script>
  $(function () {
    $("#tbAssignSE").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["csv","excel", "pdf", "print"]
    }).buttons().container().appendTo('#tbAssignSE_wrapper .col-md-6:eq(0)');
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


  $(".btnNoAttend").click(function(e){
    
    $form = $(this).parent();
    $('#noattended_name').html($form.children('input[name="name"]').val());
    $('#noshow_btn').val($form.children('input[name="id"]').val());
    $modal = $('#modal-noshow');
    $modal.modal('show');
  });
</script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" src="js/script.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    

  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

    <script type="text/javascript">
      $(document).ready(function() {
      $('#tbevaluation').DataTable( {
          dom: 'lBfrtip',
          buttons: [
              'csv', 'excel', 'pdf', 'print'
          ]
      });
    
} );

      $('#tbdisputelist').DataTable({'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-1, -2] /* 1st one, start by the right */
      }]
    });
    
      </script>
<?php
}
?>
</body>
</html>

<?php
}
}

?>
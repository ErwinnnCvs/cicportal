<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("config.php");
session_start();

if ($_SESSION['otp_post'] == 1) {

if($_POST["submit_otp"]) {
  $query = "SELECT * FROM cic_otp_expiry WHERE user_id = '".$_SESSION['user_id']."' and otp='" . $_POST["otp"] . "' AND is_expired!=1 AND DATE_ADD(create_at, INTERVAL 12 HOUR) >= NOW()";
  $result = mysqli_query($dbh, $query);
  $count  = mysqli_num_rows($result);
  
  // Check what's actually in the database
  $check_query = "SELECT * FROM cic_otp_expiry WHERE user_id = '".$_SESSION['user_id']."' ORDER BY create_at DESC LIMIT 1";
  $check_result = mysqli_query($dbh, $check_query);
  $latest_otp = mysqli_fetch_assoc($check_result);
  
  if(!empty($count)) {
    $result = mysqli_query($dbh,"UPDATE cic_otp_expiry SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
    $_SESSION['success_otp'] = 2;
    header('Location: index.php');
  } else {
    $_SESSION['success_otp'] =1;
    $debug = "Posted=" . $_POST["otp"] . ", Session=" . $_SESSION['current_otp'] . ", UserID=" . $_SESSION['user_id'] . ", Count=" . $count;
    $debug .= "<br>Query: " . $query;
    if($latest_otp) {
      $debug .= "<br>Latest DB OTP: " . $latest_otp['otp'] . ", Expired: " . $latest_otp['is_expired'] . ", Created: " . $latest_otp['create_at'];
      
      // Check time condition
      $time_query = "SELECT NOW() as now_time, DATE_ADD('" . $latest_otp['create_at'] . "', INTERVAL 1 HOUR) as expiry_time";
      $time_result = mysqli_query($dbh, $time_query);
      $time_data = mysqli_fetch_assoc($time_result);
      $debug .= "<br>Current: " . $time_data['now_time'] . ", Expiry: " . $time_data['expiry_time'];
    } else {
      $debug .= "<br>No OTP found in database for this user";
    }
    $error = "Invalid OTP! Debug: " . $debug;
  } 
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CIC Portal | One Time Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="dist/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="dist/css/fonts.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="dist/img/logo-horizontal.png">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <?php

        if ($error) {
      ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        <?php echo $error; ?>
      </div>
      <?php
        }
      ?>
      <p class="login-box-msg">Enter One-Time Password that was sent to your email address</p>
      <?php if(isset($_SESSION['current_otp'])): ?>
      <div class="alert alert-info">
        <strong>OTP for testing:</strong> <?php echo $_SESSION['current_otp']; ?>
      </div>
      <?php endif; ?>

      <form method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Enter OTP" name="otp">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <!-- <a href="forgot-password.php">I forgot my password</a> -->
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" value="1" name="submit_otp">Enter</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
<?php
  }

?>
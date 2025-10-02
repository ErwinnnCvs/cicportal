<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'classes/Auth.class.php';

require_once 'PHPMailer/PHPMailerAutoload.php';
ini_set('default_charset', 'UTF-8');

session_start();

$auth = new Auth();
include_once __DIR__ . '/securimage/securimage.php';

$securimage = new Securimage();
if($_POST['sbtSignIn']){
  if (isset($_POST['email']) && isset($_POST['password'])) {

    $status = $auth->login($_POST['email'], $_POST['password']);

    if ($status == 0) {

      if ($securimage->check($_POST['captcha_code']) == false) {
        $error = "Invalid captcha";
      } else {

        $otp = rand(100000,999999);



         // Send OTP
        require_once("PHPMailer/mail_otp_function.php");
        $mail_status = sendOTP($_SESSION['email'],$otp);

        // echo $otp;
        // echo $_SESSION['email'];
        // echo $mail_status;
        // die();

        
        if($mail_status == 1) {
            $result = mysqli_query($dbh,"INSERT INTO cic_otp_expiry(otp,is_expired,create_at, user_id) VALUES ('" . $otp . "', 0, '" . date("Y-m-d H:i:s"). "', '".$_SESSION['user_id']."')");
            $current_id = mysqli_insert_id($dbh);
            if(!empty($current_id)) {
                $success=1;
                $_SESSION['otp_post'] = 1;
                $_SESSION['current_otp'] = $otp;
                header('Location: otp.php');
            }
        }
      }
		} else {
			switch ($status) {
				case 1:
					$error = 'User not verified, please check your email for verification';
					break;
				case 2:
					$error = 'User is not active, please check your email for activation information';
					break;
				case 3:
					$error = 'Username and password correct, but issue logging in, try again.';
					break;
				case 4:
					$error = 'Please check username and password and try again';
					break;
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CIC Portal | Log in</title>
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
      <p class="login-box-msg">Sign in to CIC Portal</p>

      <form method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="form-gp">
            <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" border="2" style="border-style: solid; border-color: #e3e1dc;"/>
            <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Refresh ]</a>
        </div>
        <br>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Enter Captcha" id="captcha_code" name="captcha_code" autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <a href="forgot-password.php">I forgot my password</a>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" value="1" name="sbtSignIn">Sign In</button>
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

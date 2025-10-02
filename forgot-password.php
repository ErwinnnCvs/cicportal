<?php
require_once 'classes/Auth.class.php';

require_once 'PHPMailer/PHPMailerAutoload.php';
ini_set('default_charset', 'UTF-8');

session_start();

$auth = new Auth();


if(isset($_POST['sbtForgot'])){
  if(isset($_POST['email'])){
        $sql=$dbh->query("SELECT count(*) AS rcnt FROM tbcicusers WHERE email = '".$_POST['email']."'");
        $s=$sql->fetch_array();
        if($s['rcnt'] > 0){
          if($auth->forgotPassword($_POST['email']) == true){
            $msg = "An email instruction was sent to your email.";
            $msgclr = "success";
          }
        }else{
          $msg = "Email not registered.";
          $msgclr = "danger";
        }
  }else{
    $msg = "Please enter your email address in the Email textbox.";
    $msgclr = "danger";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CIC Portal | Forgot Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="dist/img/logo-horizontal.png">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Please enter your email in CIC Portal.</p>
      <?php 
        if ($msg) {
      ?>
      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>
      <form method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" name="sbtForgot" class="btn btn-primary btn-block" value="1">Request new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="login.php">Login</a>
      </p>
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

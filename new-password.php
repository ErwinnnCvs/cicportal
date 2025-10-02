<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("classes/Auth.class.php");

$auth = new Auth();
if (empty($_GET['email']) && empty($_GET['code'])) {
  echo "Unauthorized Access";
} else {



$get_user_id = explode(".", $_GET['code']);

$user_id = $get_user_id[1];
$email = $_GET['email'];

if($_POST['sbtChangePassword']){
    if($_POST['newpassword'] && $_POST['confirmpassword']){
    
        if($_POST['newpassword'] == $_POST['confirmpassword']){
          if (strlen($_POST['newpassword']) >= 8 and preg_match('/[\'^£$%&*()}!{@#~?><>,|=_+¬-]/', $_POST['newpassword']) and preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $_POST['newpassword'])) {
            if($auth->changePassword($user_id, $email, $_POST['newpassword']) == true){
                $msg = "Password has been successfully changed.";
                $msgclr = "success";
            }else{
                $msg = "Old Password incorrect.";
                $msgclr = "danger";
            }
          } else {
            $msg = "Password length must be equal or greater than 8 characters and must have atleast 1 special character and atleast 1 number";
            $msgclr = "danger";
          }
        }else{
            $msg = "New Password and Confirm Password does not match.";
            $msgclr = "danger";
        }
    
    }else{
        $msg = "Please complete all entries.";
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
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Reset your password.</p>
              <?php
                if ($msg) {
              ?>
              <div class="alert alert-<?php echo $msgclr; ?> icons-alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="icofont icofont-close-line-circled"></i>
                </button>
                <p><strong>Information!</strong> <?php echo $msg; ?>
              </div>
              <?php
                }
              ?>
              <form method="POST" class="form-horizontal">

                <div class="input-group mb-3">
                  <input type="password" name="newpassword" class="form-control" id="inputName" placeholder="New Password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-key"></span>
                    </div>
                  </div>
                </div>

                <div class="input-group mb-3">
                  <input type="password" name="confirmpassword" class="form-control" id="inputName" placeholder="Confirm Password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-key"></span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <button type="submit" value="1" name="sbtChangePassword" class="btn btn-danger btn-block">Submit</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>
        </div><!-- /.card-body -->
      </div>
      <!-- /.card -->

</div>
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
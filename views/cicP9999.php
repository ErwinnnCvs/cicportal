<?php
if($_POST['sbtChangePassword']){
    if($_POST['oldpassword'] && $_POST['newpassword'] && $_POST['confirmpassword']){
      if($_POST['oldpassword'] == $_POST['newpassword']){
        $msg = "New password must not be the same with old password. Please try another password.";
        $msgclr = "warning";
      } else {
        if($_POST['newpassword'] == $_POST['confirmpassword']){
          if (strlen($_POST['newpassword']) >= 8 and preg_match('/[\'^£$%&*()}!{@#~?><>,|=_+¬-]/', $_POST['newpassword']) and preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $_POST['newpassword'])) {
            if($auth->changePassword($_SESSION['user_id'], $_SESSION['email'], $_POST['oldpassword'], $_POST['newpassword']) == true){
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
      }
    }else{
        $msg = "Please complete all entries.";
        $msgclr = "danger";
    }
}
?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle" src="dist/img/employees-2x2/<?php echo $_SESSION['image']; ?>" alt="User profile picture">
            </div>

            <h3 class="profile-username text-center"><?php echo $_SESSION['name']; ?></h3>

            <p class="text-muted text-center"><?php echo $user_position[$_SESSION['usertype']]; ?></p>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">

              <div class="active tab-pane" id="settings">
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
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Old Password</label>
                    <div class="col-sm-10">
                      <input type="password" name="oldpassword" class="form-control" id="inputName" placeholder="Old Password">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">New Password</label>
                    <div class="col-sm-10">
                      <input type="password" name="newpassword" class="form-control" id="inputName" placeholder="New Password">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Confirm Password</label>
                    <div class="col-sm-10">
                      <input type="password" name="confirmpassword" class="form-control" id="inputName" placeholder="Confirm Password">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                      <button type="submit" value="1" name="sbtChangePassword" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
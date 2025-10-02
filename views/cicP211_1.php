<?php
$controlno = $_GET['ctrlno'];
$timestamp = date("Y-m-d H:i:s");
if ($_POST['sbtYes']) {
  if ($dbh4->query("UPDATE tbentities SET fld_re_resend_link_bu = '".$_SESSION['name']."', fld_re_resend_link_ts = '".$timestamp."', fld_process_status = 0 WHERE fld_ctrlno = '".$controlno."'")) {
    $msg = "Successfully updated";
    $msgclr = "success";
  } else {
    $msg = "Error updating";
    $msgclr = "danger";
  }
}

if ($_POST['sbtSaveChanges']) {
  # AUthorized Representative
  $fname_ar = addslashes($_POST['fname_ar']);
  $mname_ar = addslashes($_POST['mname_ar']);
  $lname_ar = addslashes($_POST['lname_ar']);
  $extname_ar = addslashes($_POST['extname_ar']);
  $position_ar = addslashes($_POST['position_ar']);
  $email_ar = addslashes($_POST['email_ar']);
  $landline_ar = addslashes($_POST['landline_ar']);
  $contactno_ar = addslashes($_POST['contactno_ar']);

  # Primary Contact Person
  $fname_c1 = addslashes($_POST['fname_c1']);
  $mname_c1 = addslashes($_POST['mname_c1']);
  $lname_c1 = addslashes($_POST['lname_c1']);
  $extname_c1 = addslashes($_POST['extname_c1']);
  $position_c1 = addslashes($_POST['position_c1']);
  $email_c1 = addslashes($_POST['email_c1']);
  $landline_c1 = addslashes($_POST['landline_c1']);
  $contactno_c1 = addslashes($_POST['contactno_c1']);

  if ($dbh4->query("UPDATE tbentities SET fld_fname_ar = AES_ENCRYPT('".$fname_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_mname_ar = AES_ENCRYPT('".$mname_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_lname_ar = AES_ENCRYPT('".$lname_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_extname_ar = AES_ENCRYPT('".$extname_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_position_ar = AES_ENCRYPT('".$position_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_email_ar = AES_ENCRYPT('".$email_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_landline_ar = AES_ENCRYPT('".$landline_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_contactno_ar = AES_ENCRYPT('".$contactno_ar."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_fname_c1 = AES_ENCRYPT('".$fname_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_mname_c1 = AES_ENCRYPT('".$mname_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_lname_c1 = AES_ENCRYPT('".$lname_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_extname_c1 = AES_ENCRYPT('".$extname_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_position_c1 = AES_ENCRYPT('".$position_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_email_c1 = AES_ENCRYPT('".$email_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_landline_c1 = AES_ENCRYPT('".$landline_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_contactno_c1 = AES_ENCRYPT('".$contactno_c1."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_re_changes_by = '".$_SESSION['name']."', fld_re_changes_ts = '".$timestamp."' WHERE fld_ctrlno = '".$controlno."'")) {
    $msg = "Successfully updated";
    $msgclr = "success";
  } else {
    $msg = "Error updating";
    $msgclr = "danger";
  }
}


$get_entity_details = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_fname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname_ar, AES_DECRYPT(fld_mname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname_ar, AES_DECRYPT(fld_lname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname_ar, AES_DECRYPT(fld_extname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as extname_ar, AES_DECRYPT(fld_position_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as position_ar, AES_DECRYPT(fld_email_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email_ar, AES_DECRYPT(fld_landline_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as landline_ar, AES_DECRYPT(fld_contactno_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as contactno_ar, AES_DECRYPT(fld_fname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname_c1, AES_DECRYPT(fld_mname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname_c1, AES_DECRYPT(fld_lname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname_c1, AES_DECRYPT(fld_extname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as extname_c1, AES_DECRYPT(fld_email_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email_c1, AES_DECRYPT(fld_position_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as position_c1, AES_DECRYPT(fld_landline_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as landline_c1, AES_DECRYPT(fld_contactno_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as contactno_c1 FROM tbentities WHERE fld_ctrlno = '".$controlno."'");
$ged=$get_entity_details->fetch_array();


if ($ged['fld_ctrlno']) {
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header d-flex p-0">
      <h3 class="card-title p-3"><b><?php if($ged['provcode']){ echo $ged['provcode']. " - "; } echo $ged['name']; ?></b></h3>
      <ul class="nav nav-pills ml-auto p-2">
        <!-- <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Tab 1</a></li> -->
        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="modal" data-target="#modal-default">Resend Link</a></li>
        <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Confirmation..</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are you sure you want to resend link for <b><?php echo $ged['name']; ?></b>?
            </div>
            <form method="post">
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" value="1" name="sbtYes" class="btn btn-primary">Yes</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
        <!-- <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Tab 3</a></li> -->
        <!-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
            Action <span class="caret"></span>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" tabindex="-1" href="#">Action</a>
            <a class="dropdown-item" tabindex="-1" href="#">Another action</a>
            <a class="dropdown-item" tabindex="-1" href="#">Something else here</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" tabindex="-1" href="#">Separated link</a>
          </div>
        </li> -->
      </ul>
    </div>
    <div class="card-body">
      <?php
        if ($msg) {
      ?>
      <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Info!</h5>
        <?php echo $msg; ?>
      </div>
      <?php
        }
      ?>
      <!-- <?php echo $ged['email_ar']; ?> -->
      <form method="post">
      <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#modalSaveChanges">Save Changes</button>
      <div class="modal fade" id="modalSaveChanges">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Confirmation..</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are you sure you want to save changes?
            </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" value="1" name="sbtSaveChanges" class="btn btn-primary">Yes</button>
              </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <a href="main.php?nid=2&sid=0&rid=0" class="btn btn-secondary">Cancel</a>
      <br><br>
        <div class="row">
          <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Authorized Representative</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                  <div class="card-body">
                    <div class="form-group">
                      <label for="fname_ar">First Name</label>
                      <input type="text" class="form-control" id="fname_ar" name="fname_ar" value="<?php echo $ged['fname_ar']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="mname_ar">Middle Name</label>
                      <input type="text" class="form-control" id="mname_ar" name="mname_ar" value="<?php echo $ged['mname_ar']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="lname_ar">Last Name</label>
                      <input type="text" class="form-control" id="lname_ar" name="lname_ar" value="<?php echo $ged['lname_ar']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="position_ar">Position</label>
                      <input type="text" class="form-control" id="position_ar" name="position_ar" value="<?php echo $ged['position_ar']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="email_ar">Email address</label>
                      <input type="email" class="form-control" id="email_ar" name="email_ar" value="<?php echo $ged['email_ar']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="landline_ar">Landline</label>
                      <input type="text" class="form-control" id="landline_ar" name="landline_ar" value="<?php echo $ged['landline_ar']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="contactno_ar">ContactNo</label>
                      <input type="text" class="form-control" id="contactno_ar" name="contactno_ar" value="<?php echo $ged['contactno_ar']; ?>">
                    </div>
                  </div>
                  <!-- /.card-body -->
              </div>
          </div>
          <div class="col-md-6">
            <div class="card card-secondary">
                <div class="card-header">
                  <h3 class="card-title">Primary Contact Person</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                  <div class="card-body">
                    <div class="form-group">
                      <label for="fname_c1">First Name</label>
                      <input type="text" class="form-control" id="fname_c1" name="fname_c1" value="<?php echo $ged['fname_c1']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="mname_c1">Middle Name</label>
                      <input type="text" class="form-control" id="mname_c1" name="mname_c1" value="<?php echo $ged['mname_c1']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="lname_c1">Last Name</label>
                      <input type="text" class="form-control" id="lname_c1" name="lname_c1" value="<?php echo $ged['lname_c1']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="position_c1">Position</label>
                      <input type="text" class="form-control" id="position_c1" name="position_c1" value="<?php echo $ged['position_c1']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="email_c1">Email address</label>
                      <input type="email" class="form-control" id="email_c1" name="email_c1" value="<?php echo $ged['email_c1']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="landline_c1">Landline</label>
                      <input type="text" class="form-control" id="landline_c1" name="landline_c1" value="<?php echo $ged['landline_c1']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="contactno_c1">ContactNo</label>
                      <input type="text" class="form-control" id="contactno_c1" name="contactno_c1" value="<?php echo $ged['contactno_c1']; ?>">
                    </div>
                  </div>
                  <!-- /.card-body -->
              </div>
          </div>
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php
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
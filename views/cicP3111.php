<?php
$control_No = $_POST['controlno'];
$timestamp = date("Y-m-d H:i:s");
if ($_POST['sbtUpdateAR']) {
  if (!$_POST['admin_password']) {
    $msg = "Please enter admin password to update.";
    $msgclr = "danger";
  } else {
    $status = $auth->checkPassword($_SESSION['usertype'], $_SESSION['email'], $_POST['admin_password']);
    if ($status == 1 ) {
      if ($dbh4->query("UPDATE tbentities SET fld_fname_ar = AES_ENCRYPT('".$_POST['ar_firstname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_mname_ar = AES_ENCRYPT('".$_POST['ar_middlename']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_lname_ar = AES_ENCRYPT('".$_POST['ar_lastname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_extname_ar = AES_ENCRYPT('".$_POST['ar_extname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_email_ar = AES_ENCRYPT('".$_POST['email_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_position_ar = AES_ENCRYPT('".$_POST['position_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_landline_ar = AES_ENCRYPT('".$_POST['landline_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_contactno_ar = AES_ENCRYPT('".$_POST['mobile_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_name = AES_ENCRYPT('".addslashes($_POST['company_name'])."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_updated_details_by = '".$_SESSION['name']."', fld_updated_details_ts = '".$timestamp."', fld_trade_name = AES_ENCRYPT('".$_POST['trade_name']."', md5(CONCAT(fld_ctrlno, 'RA3019'))) WHERE fld_ctrlno = '".$control_No."' ")) {
        if ($dbh->query("UPDATE tbentities SET fld_fname_ar = AES_ENCRYPT('".$_POST['ar_firstname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_mname_ar = AES_ENCRYPT('".$_POST['ar_middlename']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_lname_ar = AES_ENCRYPT('".$_POST['ar_lastname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_extname_ar = AES_ENCRYPT('".$_POST['ar_extname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_email_ar = AES_ENCRYPT('".$_POST['email_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_position_ar = AES_ENCRYPT('".$_POST['position_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_landline_ar = AES_ENCRYPT('".$_POST['landline_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_contactno_ar = AES_ENCRYPT('".$_POST['mobile_ar']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_name = AES_ENCRYPT('".addslashes($_POST['company_name'])."', md5(CONCAT(fld_ctrlno, 'RA3019'))) WHERE fld_ctrlno = '".$control_No."' ")) {
          $msg = "Successfully updated!";
          $msgclr = "success";
        } else {
          $msg = "ERROR updating the details.";
          $msgclr = "danger";
        }
      } else {
          $msg = "ERROR updating the details";
          $msgclr = "danger";
      }
    } elseif ($status == 0) {
      $msg = "Incorrect password";
      $msgclr = "danger";
    } elseif ($status == 0) {
      $msg = "Permission Denied";
      $msgclr = "danger";
    }
  }
  
}

if ($_POST['sbtUpdatePCP']) {
  if (!$_POST['p-password']) {
    $msg = "Please enter admin password to update.";
    $msgclr = "danger";
  } else {
    $status = $auth->checkPassword($_SESSION['usertype'], $_SESSION['email'], $_POST['p-password']);
    if ($status == 1 ) {
      if ($dbh4->query("UPDATE tbentities SET fld_fname_c1 = AES_ENCRYPT('".$_POST['p-firstname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_mname_c1 = AES_ENCRYPT('".$_POST['p-middlename']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_lname_c1 = AES_ENCRYPT('".$_POST['p-lastname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_email_c1 = AES_ENCRYPT('".$_POST['p-email']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_position_c1 = AES_ENCRYPT('".$_POST['p-position']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_landline_c1 = AES_ENCRYPT('".$_POST['p-landline']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_contactno_c1 = AES_ENCRYPT('".$_POST['p-contact']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_updated_details_by = '".$_SESSION['name']."', fld_updated_details_ts = '".$timestamp."' WHERE fld_ctrlno = '".$control_No."' ")) {
        if ($dbh->query("UPDATE tbentities SET fld_fname_c1 = AES_ENCRYPT('".$_POST['p-firstname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_mname_c1 = AES_ENCRYPT('".$_POST['p-middlename']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_lname_c1 = AES_ENCRYPT('".$_POST['p-lastname']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_email_c1 = AES_ENCRYPT('".$_POST['p-email']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_position_c1 = AES_ENCRYPT('".$_POST['p-position']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_landline_c1 = AES_ENCRYPT('".$_POST['p-landline']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_contactno_c1 = AES_ENCRYPT('".$_POST['p-contact']."', md5(CONCAT(fld_ctrlno, 'RA3019'))) WHERE fld_ctrlno = '".$control_No."' ")) {
          $msg = "Successfully updated!";
          $msgclr = "success";
        } else {
          $msg = "ERROR updating the details.";
          $msgclr = "danger";
        }
      } else {
          $msg = "ERROR updating the details";
          $msgclr = "danger";
      }
    } elseif ($status == 0) {
      $msg = "Incorrect password";
      $msgclr = "danger";
    } elseif ($status == 0) {
      $msg = "Permission Denied";
      $msgclr = "danger";
    }
  }
  
}
if ($_POST['resendAEIS']) {
  if ($dbh->query("UPDATE tbentities SET fld_ceportal_sent = 0 WHERE fld_ctrlno = '".$control_No."'")) {
    if ($dbh3->query("UPDATE tbusers SET is_active = 0 WHERE fld_ctrlno = '".$control_No."'")) {
      $msg = "Successfully sent CEIS Link!";
      $msgclr = "success";
    } else  {
      $msg = "ERROR resending CEIS Link.2";
    $msgclr = "danger";  
    }
  } else {
    $msg = "ERROR resending CEIS Link.1";
    $msgclr = "danger";
  }
}

if ($_POST['sbtUpdateDRCP']) {
  if (!$_POST['p-password']) {
    $msg = "Please enter admin password to update.";
    $msgclr = "danger";
  } else {
    $status = $auth->checkPassword($_SESSION['usertype'], $_SESSION['email'], $_POST['p-password']);
    if ($status == 1 ) {
      if ($dbh4->query("UPDATE tbentities SET fld_disp_contact_fname = AES_ENCRYPT('".$_POST['fname_disp_contact']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_disp_contact_mname = AES_ENCRYPT('".$_POST['mname_disp_contact']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_disp_contact_lname = AES_ENCRYPT('".$_POST['lname_disp_contact']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_disp_email = AES_ENCRYPT('".$_POST['email_disp_contact']."', md5(CONCAT(fld_ctrlno, 'RA3019'))), fld_disp_contact_email = AES_ENCRYPT('".$_POST['contact_email_disp_contact']."', md5(CONCAT(fld_ctrlno, 'RA3019'))) fld_updated_details_by = '".$_SESSION['name']."', fld_updated_details_ts = '".$timestamp."' WHERE fld_ctrlno = '".$control_No."' ")) {
        
      } else {
          $msg = "ERROR updating the details";
          $msgclr = "danger";
      }
    } elseif ($status == 0) {
      $msg = "Incorrect password";
      $msgclr = "danger";
    } elseif ($status == 0) {
      $msg = "Permission Denied";
      $msgclr = "danger";
    }
  }
}

if ($_POST['resendSEISLink']) {
  if ($dbh4->query("UPDATE tbentities SET fld_process_status = 0, fld_resend_seis_by = '".$_SESSION['name']."', fld_resend_seis_ts = '".$timestamp."' WHERE fld_ctrlno = '".$control_No."'")) {
    $msg = "Successfully sent CEIS Link!";
    $msgclr = "success";
  } else {
    $msg = "ERROR resending CEIS Link";
    $msgclr = "danger";
  }
}

if ($_POST['sbtResendDocs']) {
  $selected_docs = $_POST['seisdocs'];
  $controlNo = $control_No;
  $code = $controlNo."RA3019";
  $pass = $_POST['provcode'];
  
  include("seispdf_manual.php");
  include("operatorspdf_manual.php");
  include("ctnlapdf_manual.php");

  if ($dbh4->query("UPDATE tbentities SET fld_sent_attachment_update = 1 WHERE fld_ctrlno = '".$controlNo."'")) {
    $msg = "Successfully sent CEIS Docs!";
    $msgclr = "success";
  } else {
    $msg = "ERROR resending CEIS Docs!";
    $msgclr = "danger";
  }
}
$details = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode,AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as company, AES_DECRYPT(fld_fname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as firstname, AES_DECRYPT(fld_mname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as middlename, AES_DECRYPT(fld_lname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lastname, AES_DECRYPT(fld_extname_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as extname, AES_DECRYPT(fld_email_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email_ar, AES_DECRYPT(fld_landline_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as landline_ar, fld_type, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mobile_ar, AES_DECRYPT(fld_position_ar, md5(CONCAT(fld_ctrlno, 'RA3019'))) as position_ar, AES_DECRYPT(fld_head_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname_head, AES_DECRYPT(fld_head_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname_head, AES_DECRYPT(fld_head_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname_head, AES_DECRYPT(fld_head_extname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as extname_head, AES_DECRYPT(fld_head_position, md5(CONCAT(fld_ctrlno, 'RA3019'))) as position_head, AES_DECRYPT(fld_head_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email_head, AES_DECRYPT(fld_fname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname_c1, AES_DECRYPT(fld_mname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname_c1, AES_DECRYPT(fld_lname_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname_c1, AES_DECRYPT(fld_position_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as position_c1, AES_DECRYPT(fld_contactno_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as contactno_c1, AES_DECRYPT(fld_landline_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as landline_c1, AES_DECRYPT(fld_email_c1, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email_c1, AES_DECRYPT(fld_fname_c2, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname_c2, AES_DECRYPT(fld_mname_c2, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname_c2, AES_DECRYPT(fld_lname_c2, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname_c2, AES_DECRYPT(fld_position_c2, md5(CONCAT(fld_ctrlno, 'RA3019'))) as position_c2, AES_DECRYPT(fld_contactno_c2, md5(CONCAT(fld_ctrlno, 'RA3019'))) as contactno_c2, AES_DECRYPT(fld_landline_c2, md5(CONCAT(fld_ctrlno, 'RA3019'))) as landline_c2, AES_DECRYPT(fld_email_c2, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email_c2, fld_numacct_indv, fld_numacct_comp, fld_numacct_inst, fld_numacct_noninst, fld_numacct_cc, fld_numacct_util, AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_number, AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_street, AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_subdv, AES_DECRYPT(fld_address,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address, AES_DECRYPT(fld_disp_contact_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname_disp_contact, AES_DECRYPT(fld_disp_contact_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname_disp_contact, AES_DECRYPT(fld_disp_contact_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname_disp_contact, AES_DECRYPT(fld_disp_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email_disp_contact, AES_DECRYPT(fld_disp_contact_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as contact_email_disp_contact, AES_DECRYPT(fld_bill_contact_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as bill_contact_fname, AES_DECRYPT(fld_bill_contact_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as bill_contact_mname, AES_DECRYPT(fld_bill_contact_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as bill_contact_lname, AES_DECRYPT(fld_bill_landline, md5(CONCAT(fld_ctrlno, 'RA3019'))) as bill_contact_landline, AES_DECRYPT(fld_bill_mobile, md5(CONCAT(fld_ctrlno, 'RA3019'))) as bill_contact_mobile, AES_DECRYPT(fld_bill_contact_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as bill_contact_email, fld_ceapprove_status, AES_DECRYPT(fld_landline, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_landline, aes_decrypt(fld_trade_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as trade_name FROM tbentities WHERE fld_ctrlno = '".$control_No."'");
  $dt=$details->fetch_array();

  // $cicportaldetails = $dbh->query("SELECT fld_ceapprove_status, fld_noc_pass_status FROM tbentities WHERE fld_ctrlno = '".$control_No."'");
  // $cd=$cicportaldetails->fetch_array();

  $bgy = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$dt['fld_address']."'");
  $b = $bgy->fetch_array();
  $cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($dt['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
  $c = $cty->fetch_array();
  $prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($dt['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
  $p = $prv->fetch_array();

?>
<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-3">

      <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle"
                   src="dist/img/cic-logo.png"
                   alt="User profile picture">
            </div>

            <h3 class="profile-username text-center"><?php echo $dt['company']; ?></h3>

            <p class="text-muted text-center"><b style="color: black;"><?php echo $dt['provcode']; ?></b><br/><?php echo $ent2[$dt['fld_type']]; ?></p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Individual</b> <a class="float-right"><?php echo $dt['fld_numacct_indv']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Corporate</b> <a class="float-right"><?php echo $dt['fld_numacct_comp']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Installment</b> <a class="float-right"><?php echo $dt['fld_numacct_inst']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Non-Installment</b> <a class="float-right"><?php echo $dt['fld_numacct_noninst']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Credit Card</b> <a class="float-right"><?php echo $dt['fld_numacct_cc']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Utilities</b> <a class="float-right"><?php echo $dt['fld_numacct_util']; ?></a>
              </li>
            </ul>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Details</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

            <p class="text-muted"><?php echo $dt['fld_addr_number']." ".$dt['fld_addr_street']." ".$dt['fld_addr_subdv']." ".$b['fld_geotitle']." ".$c['fld_geotitle']." ".$p['fld_geotitle']; ?></p>

            <hr>

            <strong><i class="fa fa-phone mr-1"></i> Landline</strong>

            <p class="text-muted"><?php echo $dt['fld_landline']; ?></p>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header p-2">
          <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Details</a></li>
            <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Operators</a></li>
            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="#documents" data-toggle="tab">SEIS Documents</a></li> -->
            <!-- <li class="nav-item"><a class="nav-link" href="#documents2" data-toggle="tab">AEIS Documents</a></li> -->
          </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
          <div class="tab-content">
            <div class="active tab-pane" id="activity">
              <div class="post">
                <form method="post">
                  <input type="hidden" name="controlno" value="<?php echo $control_No; ?>">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="company_name" value="<?php echo $dt['company']; ?>" >
                            </span>
                        <span class="description">Company Name</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="trade_name" value="<?php echo $dt['trade_name']; ?>">
                            </span>
                        <span class="description">Trade Name</span>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="ar_firstname" value="<?php echo $dt['firstname']; ?>">
                              <a href="#">Firstname</a>
                            </span>
                        <span class="description">Authorized Representative</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="ar_middlename" value="<?php echo $dt['middlename']; ?>">
                              <a href="#">Middlename</a>
                            </span>
                        <span class="description">Authorized Representative</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="ar_lastname" value="<?php echo $dt['lastname']; ?>">
                              <a href="#">Lastname</a>
                            </span>
                        <span class="description">Authorized Representative</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="ar_extname" value="<?php echo $dt['extname']; ?>">
                              <a href="#">Extension Name</a>
                            </span>
                        <span class="description">Authorized Representative</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="position_ar" value="<?php echo $dt['position_ar']; ?>">
                            </span>
                        <span class="description">Position</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="email_ar" value="<?php echo $dt['email_ar']; ?>">
                            </span>
                        <span class="description">Email Address</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="landline_ar" value="<?php echo $dt['landline_ar']; ?>">
                            </span>
                        <span class="description">Landline Number</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="mobile_ar" value="<?php echo $dt['mobile_ar']; ?>">
                            </span>
                        <span class="description">Mobile Number</span>
                      </div>
                      <input type="password" class="form-control" name="admin_password" placeholder="Enter password..">
                      <button type="submit" name="sbtUpdateAR" value="1" class="btn btn-primary">Update AR Details</button>
                    </div>
                  </div>
                  <!-- /.user-block -->
                  </form>
              </div>
              <div class="post clearfix">
                <div class="row">
                  <div class="col-md-4">
                    <div class="user-block">
                          <span class="username">
                            <a><?php echo $dt['fname_head']. " " .$dt['mname_head']. " " .$dt['lname_head']. " " .$dt['extname_head']; ?></a>
                          </span>
                      <span class="description">Head of Office</span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="user-block">
                          <span class="username">
                            <a href="#"><?php echo $dt['position_head']; ?></a>
                          </span>
                      <span class="description">Position</span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="user-block">
                          <span class="username">
                            <a href="#"><?php echo $dt['email_head']; ?></a>
                          </span>
                      <span class="description">Email Address</span>
                    </div>
                  </div>
                </div>
                <!-- /.user-block -->
              </div>
              <!-- /.post -->
              <div class="post">
               <form method="post">
                <input type="hidden" name="controlno" value="<?php echo $control_No; ?>">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="p-firstname" value="<?php echo $dt['fname_c1']; ?>">
                              <a>First Name</a>
                            </span>
                        <span class="description">Primary Contact Person</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="p-middlename" value="<?php echo $dt['mname_c1']; ?>">
                              <a>Middle Name</a>
                            </span>
                        <span class="description">Primary Contact Person</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="p-lastname" value="<?php echo $dt['lname_c1']; ?>">
                              <a>Last Name</a>
                            </span>
                        <span class="description">Primary Contact Person</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="p-position" value="<?php echo $dt['position_c1']; ?>">
                              <a href="#">Position</a>
                            </span>
                        <span class="description">Primary Contact Person</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="p-email" value="<?php echo $dt['email_c1']; ?>">
                              <a href="#">Email</a>
                            </span>
                        <span class="description">Primary Contact Person</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="p-landline" value="<?php echo $dt['landline_c1']; ?>">
                              <a href="#">Landline</a>
                            </span>
                        <span class="description">Primary Contact Person</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <input type="text" name="p-contact" value="<?php echo $dt['contactno_c1']; ?>">
                              <a href="#">Mobile Number</a>
                            </span>
                        <span class="description">Primary Contact Person</span>
                      </div>
                    <input type="password" name="p-password" class="form-control" placeholder="Enter password">
                    <button type="submit" name="sbtUpdatePCP" class="btn btn-warning pull-right" value="1">Update PCP</button>
                    </div>
                  </div>
                  <!-- /.user-block -->
                  </form>
                </div>

                <div class="post">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a><?php echo $dt['fname_c2']. " " .$dt['mname_c2']. " " .$dt['lname_c2']; ?></a>
                            </span>
                        <span class="description">Secondary Contact Person</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a href="#"><?php echo $dt['position_c2']; ?></a>
                            </span>
                        <span class="description">Position</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a href="#"><?php echo $dt['email_c2']; ?></a>
                            </span>
                        <span class="description">Email Address</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a href="#"><?php echo $dt['landline_c2']; ?></a>
                            </span>
                        <span class="description">Landline Number</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a href="#"><?php echo $dt['contactno_c2']; ?></a>
                            </span>
                        <span class="description">Mobile Number</span>
                      </div>
                    </div>
                  </div>
                  <!-- /.user-block -->

                </div>

                <div class="post">
                  <form method="post">
                    <input type="hidden" name="controlno" value="<?php echo $control_No; ?>">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="user-block">
                                <span class="username">
                                  <input type="text" name="fname_disp_contact" value="<?php echo $dt['fname_disp_contact']; ?>">
                                  <a>First Name</a>
                                </span>
                            <span class="description">Dispute Contact Person</span>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="user-block">
                                <span class="username">
                                  <input type="text" name="mname_disp_contact" value="<?php echo $dt['mname_disp_contact']; ?>">
                                  <a>Middle Name</a>
                                </span>
                            <span class="description">Dispute Contact Person</span>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="user-block">
                                <span class="username">
                                  <input type="text" name="lname_disp_contact" value="<?php echo $dt['lname_disp_contact']; ?>">
                                  <a>Last Name</a>
                                </span>
                            <span class="description">Dispute Contact Person</span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <div class="user-block">
                                <span class="username">
                                  <input type="text" name="contact_email_disp_contact" value="<?php echo $dt['contact_email_disp_contact']; ?>">
                                  <a href="#">Contact Email</a>
                                </span>
                            <span class="description">Dispute Contact Person</span>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="user-block">
                                <span class="username">
                                  <input type="text" name="email_disp_contact" value="<?php echo $dt['email_disp_contact']; ?>">
                                  <a href="#">Dispute Email</a>
                                </span>
                            <span class="description">Dispute Email</span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          
                        </div>
                        <div class="col-md-3">
                          
                        </div>
                        <div class="col-md-3">
                          
                        </div>
                        
                        <div class="col-md-3">
                        <input type="password" name="p-password" class="form-control" placeholder="Enter password">
                        <button type="submit" name="sbtUpdateDRCP" class="btn btn-info pull-right" value="1">Update DRCP</button>
                        </div>
                      </div>
                      <!-- /.user-block -->
                  </form>

                </div>

                <div class="post">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a><?php echo $dt['bill_contact_fname']. " " .$dt['bill_contact_mname']. " " .$dt['bill_contact_lname']; ?></a>
                            </span>
                        <span class="description">Billing & Collection Point Person</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a href="#"><?php echo $dt['bill_contact_email']; ?></a>
                            </span>
                        <span class="description">Email</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a href="#"><?php echo $dt['bill_contact_landline']; ?></a>
                            </span>
                        <span class="description">Landline Number</span>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="user-block">
                            <span class="username">
                              <a href="#"><?php echo $dt['bill_contact_mobile']; ?></a>
                            </span>
                        <span class="description">Mobile Number</span>
                      </div>
                    </div>
                  </div>
                  <!-- /.user-block -->

                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="timeline">
              <h3 class="page-header">SEIS BATCH OPERATORS</h3>
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><center>#</center></th>
                      <th><center>Firstname</center></th>
                      <th><center>Middlename</center></th>
                      <th><center>Lastname</center></th>
                      <th><center>Email</center></th>
                      <th><center>Contact No</center></th>
                      <th><center>Designation</center></th>
                      <th><center>Department</center></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sqlopbat = $dbh4->query("SELECT AES_DECRYPT(fld_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname, AES_DECRYPT(fld_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname, AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname, AES_DECRYPT(fld_department, md5(CONCAT(fld_ctrlno, 'RA3019'))) as department, AES_DECRYPT(fld_designation, md5(CONCAT(fld_ctrlno, 'RA3019'))) as designation, AES_DECRYPT(fld_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email, AES_DECRYPT(fld_contactno, md5(CONCAT(fld_ctrlno, 'RA3019'))) as contactno FROM tboperators WHERE fld_ctrlno = '".$control_No."' and fld_batch = 1 and fld_delete = 0");

                      while ($rowbat=$sqlopbat->fetch_array()) {
                        $counterbat++;
                    ?>
                    <tr>
                      <td><center><?php echo $counterbat; ?></center></td>
                      <td><center><?php echo $rowbat['fname']; ?></center></td>
                      <td><center><?php echo $rowbat['mname']; ?></center></td>
                      <td><center><?php echo $rowbat['lname']; ?></center></td>
                      <td><center><?php echo $rowbat['email']; ?></center></td>
                      <td><center><?php echo $rowbat['contactno']; ?></center></td>
                      <td><center><?php echo $rowbat['designation']; ?></center></td>
                      <td><center><?php echo $rowbat['department']; ?></center></td>
                    </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
                <h3 class="page-header">AEIS WEB OPERATORS</h3>
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><center>#</center></th>
                      <th><center>Firstname</center></th>
                      <th><center>Middlename</center></th>
                      <th><center>Lastname</center></th>
                      <th><center>Email</center></th>
                      <th><center>Contact No</center></th>
                      <th><center>Designation</center></th>
                      <th><center>Department</center></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sqlopbat = $dbh4->query("SELECT AES_DECRYPT(fld_fname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as fname, AES_DECRYPT(fld_mname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as mname, AES_DECRYPT(fld_lname, md5(CONCAT(fld_ctrlno, 'RA3019'))) as lname, AES_DECRYPT(fld_department, md5(CONCAT(fld_ctrlno, 'RA3019'))) as department, AES_DECRYPT(fld_designation, md5(CONCAT(fld_ctrlno, 'RA3019'))) as designation, AES_DECRYPT(fld_email, md5(CONCAT(fld_ctrlno, 'RA3019'))) as email, AES_DECRYPT(fld_contactno, md5(CONCAT(fld_ctrlno, 'RA3019'))) as contactno FROM tboperators WHERE fld_ctrlno = '".$control_No."' and fld_web = 1 and fld_delete = 0");

                      while ($rowbat=$sqlopbat->fetch_array()) {
                        $counterbat++;
                    ?>
                    <tr>
                      <td><center><?php echo $counterbat; ?></center></td>
                      <td><center><?php echo $rowbat['fname']; ?></center></td>
                      <td><center><?php echo $rowbat['mname']; ?></center></td>
                      <td><center><?php echo $rowbat['lname']; ?></center></td>
                      <td><center><?php echo $rowbat['email']; ?></center></td>
                      <td><center><?php echo $rowbat['contactno']; ?></center></td>
                      <td><center><?php echo $rowbat['designation']; ?></center></td>
                      <td><center><?php echo $rowbat['department']; ?></center></td>
                    </tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
            </div>
            <!-- /.tab-pane -->

            <div class="tab-pane" id="settings">
              <form class="form-horizontal" method="post">
                <input type="hidden" name="controlno" value="<?php echo $control_No; ?>">
                <input type="submit" class="btn btn-primary btn-sm" name="resendSEISLink" value="Re-send SEIS Registration Link">
                <input type="submit" class="btn btn-primary btn-sm" name="resendAEIS" value="Re-send AEIS CE Portal Link">
                
              </form>
            </div>
            <!-- /.tab-pane -->

            <div class="tab-pane" id="documents">
              <form method="post">
                <input type="hidden" name="controlno" value="<?php echo $control_No; ?>">
                <h4 class="page-header">SEIS Documents</h4>
                <!-- <div class="form-group">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seisdocs[]" value="seispdf">
                      SEIS Form
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seisdocs[]" value="operatorspdf">
                      Batch Operators Form
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="seisdocs[]" value="ctnlapdf">
                      CTNLA
                    </label>
                  </div>
                </div> -->
                <button type="submit" class="btn btn-primary" value="1" name="sbtResendDocs">Resend Documents</button>
              </form>
            </div>
            
              <div class="tab-pane" id="documents2">
              <form method="post" action="main.php?nid=120&sid=1&rid=2">
                <input type="hidden" name="controlnoaeis" id="controlnoaeis" value="<?php echo $control_No; ?>">
                <h4 class="page-header">AEIS Documents</h4>
 
                <button type="submit" class="btn btn-primary" value="1" name="sbtAEISDocs">Resend AEIS Documents</button>
              </form>
            </div>
          </div>
          <!-- /.tab-content -->
        </div><!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>

</section>
<!-- /.content -->
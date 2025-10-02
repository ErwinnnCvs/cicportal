<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
$companyselected[$_POST['company_select']] = " selected";
if ($_POST['viewTimeLine']) {
  $_POST['sbtSearchStatus'] = True;
}
?>

<!-- Main content -->
<section class="content">
  
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Check Status</h3>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Select Company</label>
              <select class="form-control select2" name="company_select" style="width: 100%;">

                <option selected="selected">---SELECT--</option>
                <?php
                  $get_all_registered_entities = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode FROM tbentities WHERE fld_registration_type = 1 ");
                  while ($gare=$get_all_registered_entities->fetch_array()) {
                    echo "<option value='".$gare['fld_ctrlno']."'".$companyselected[$gare['fld_ctrlno']].">".$gare['name']."</option>";
                  }
                ?>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" value="1" name="sbtSearchStatus" class="btn btn-primary btn-sm">Check Status</button>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  <!-- Search Result -->
  <?php
    if ($_POST['sbtSearchStatus']) {
  ?>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Result</h3>
        </div>
        <div class="card-body">
          <?php
            $get_the_details_of_company_selected = $dbh4->query("SELECT AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) as provcode, AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name, fld_re_ts, fld_re_validation_status, fld_re_validation_ts, fld_re_validation_incom_ts, fld_re_validation_by, fld_re_approval_status, fld_re_approval_ts, fld_re_approval_rej_ts, fld_re_approval_by, fld_uat_ceportal_sent, fld_uat_ceportal_sent_ts, fld_registration_upload, fld_registration_uploaded_by, AES_DECRYPT(fld_fname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) fname_c1, AES_DECRYPT(fld_mname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) mname_c1, AES_DECRYPT(fld_lname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) lname_c1, AES_DECRYPT(fld_extname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) extname_c1, AES_DECRYPT(fld_email_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) email_c1, AES_DECRYPT(fld_fname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) fname_ar, AES_DECRYPT(fld_mname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) mname_ar, AES_DECRYPT(fld_lname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) lname_ar, AES_DECRYPT(fld_extname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) extname_ar, AES_DECRYPT(fld_email_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) email_ar, fld_batch_uat_creds_status, fld_batch_uat_creds_ts, fld_batch_uat_creds_by, fld_batch_ops_reg, AES_DECRYPT(fld_ip, MD5(CONCAT(fld_ctrlno, 'RA3019'))) ip FROM tbentities WHERE fld_ctrlno = '".$_POST['company_select']."'");
            $gtdocs=$get_the_details_of_company_selected->fetch_array();

            $check_uat_operators = $dbh4->query("SELECT fld_oid FROM tboperators WHERE fld_ctrlno = '".$_POST['company_select']."' and fld_uat = 1 ORDER BY fld_oid DESC LIMIT 1");
            $cuo=$check_uat_operators->fetch_array();
          ?>
          
              <?php echo $_POST['company_select']. "-" .$gtdocs['provcode']; ?>
              <h4 class="page-header"><?php echo $gtdocs['name']; ?></h4>
              <p>Date Registered: <?php if($gtdocs['fld_re_ts']) { echo date("F d, Y h:ia", strtotime($gtdocs['fld_re_ts'])); } ?></p>
              <div class="info-box bg-info">
              <span class="info-box-icon"><i class="fa fa-search"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Current Status</span>
                <!-- <span class="info-box-number">41,410</span> -->

                <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  <?php
                    $status = "N/A";
                    if (!$gtdocs['fld_re_ts']) {
                      $status = "Pending Online Registration";
                    } elseif ($gtdocs['fld_re_ts']) {
                      if ($gtdocs['fld_re_validation_status'] == 0) {
                        $status = "Pending RE Validation";
                      } elseif ($gtdocs['fld_re_validation_status'] == 2) {
                        $status = "RE Validated - Incomplete / Discrepancy in documents";
                      } elseif ($gtdocs['fld_re_validation_status'] == 1) {
                        if ($gtdocs['fld_re_approval_status'] == 0) {
                          $status = "Pending RE Validation - Approval";
                        } elseif ($gtdocs['fld_re_approval_status'] == 2) {
                          $status = "RE Validation - Rejected";
                        } elseif ($gtdocs['fld_re_approval_status'] == 1) {
                          if ($gtdocs['fld_uat_ceportal_sent'] == 0) {
                            $status = "Pending Sending of CE Portal Credentials";
                          } elseif ($gtdocs['fld_uat_ceportal_sent'] > 0 ) {
                            if (!$cuo['fld_oid']) {
                              $status = "Pending Batch Operators (UAT)";
                            } elseif ($cuo['fld_oid']) {
                              if ($gtdocs['fld_batch_uat_creds_status'] == 0) {
                                $status = "Pending Generation of Credentials";
                              } elseif ($gtdocs['fld_batch_uat_creds_status'] > 0) {
                                $status = "Generated Batch Operators Credentials (UAT)";
                              }
                            }
                          }
                        }
                      }
                    }
                    echo $status;
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <form method="post">
              <input type="hidden" name="company_select" value="<?php echo $_POST['company_select']; ?>">
              <button type="submit" name="viewTimeLine" class="btn btn-success btn-block" value="1">View Timeline</button>
            </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>

    <?php 
      if ($_POST['viewTimeLine']) {
    ?>
    <div class="col-md-6">
      <div class="card" style="height: 600px; overflow: scroll;">
        <div class="card-body">
          <div class="timeline">
            <!-- timeline time label -->
            <div class="time-label">
              <span class="bg-green"><?php echo date("d M. Y", strtotime($gtdocs['fld_registration_upload'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fas fa-upload bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_registration_upload'])); ?></span>
                <h3 class="timeline-header"><a href="#">Data Submission Team</a> - <?php echo $gtdocs['fld_registration_uploaded_by']; ?> </h3>

                <div class="timeline-body">
                  <b>Action: b><?php echo $gtdocs['name']; ?></b> to CIC Portal.
                </div></b>Uploaded <
                <div class="timeline-footer">
                  <!-- <p><?php echo $gtdocs['fld_registration_uploaded_by']; ?></p> -->
                  <!-- <a class="btn btn-primary btn-sm">Read more</a> -->
                  <!-- <a class="btn btn-danger btn-sm">Delete</a> -->
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <!-- timeline time label -->
            <?php
              if (!$gtdocs['fld_re_ts']) {
            ?>
            <div class="time-label">
              <span class="bg-gray">Waiting</span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-clock bg-gray"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i></span>
                <h3 class="timeline-header"><a href="#">Online Registration</a></h3>
                <div class="timeline-body">
                  <p>Pending Online Registration</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($gtdocs['fld_re_ts']) {
            ?>
            <div class="time-label">
              <span class="bg-green"><?php echo date("d M. Y", strtotime($gtdocs['fld_re_ts'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-registered bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_re_ts'])); ?></span>
                <h3 class="timeline-header"><a href="#">Online Registration</a></h3>
                <div class="timeline-body">
                  <p><b>Action: </b>Successfully registered.</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              }
            ?>

            <!-- RE VALIDATION -->
            <?php
              if ($gtdocs['fld_re_validation_status'] == 0) {
            ?>
            <div class="time-label">
              <span class="bg-gray">Waiting</span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-clock bg-gray"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i></span>
                <h3 class="timeline-header"><a href="#">RE Validation</a></h3>
                <div class="timeline-body">
                  <p>Pending RE Validation</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($gtdocs['fld_re_validation_status'] == 2) {
            ?>
            <div class="time-label">
              <span class="bg-red"><?php echo date("d M. Y", strtotime($gtdocs['fld_re_validation_incom_ts'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-check bg-red"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_re_validation_incom_ts'])); ?></span>
                <h3 class="timeline-header"><a href="#">RE Validation</a> - <?php echo $gtdocs['fld_re_validation_by']; ?></h3>
                <div class="timeline-body">
                  <p><b>Action: </b>Tagged as Incomplete or Decrepancy in documents submitted by the Entity.</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($gtdocs['fld_re_validation_status'] == 1) {
            ?>
            <div class="time-label">
              <span class="bg-green"><?php echo date("d M. Y", strtotime($gtdocs['fld_re_validation_ts'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-check bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_re_validation_ts'])); ?></span>
                <h3 class="timeline-header"><a href="#">RE Validation</a> - <?php echo $gtdocs['fld_re_validation_by']; ?></h3>
                <div class="timeline-body">
                  <p><b>Action: </b> - Validated</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              }
            ?>

            <!-- RE Validation Approval -->
            <?php
              if ($gtdocs['fld_re_approval_status'] == 0) {
            ?>
            <div class="time-label">
              <span class="bg-gray">Waiting</span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-clock bg-gray"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i></span>
                <h3 class="timeline-header"><a href="#">RE Validation Approval</a></h3>
                <div class="timeline-body">
                  <p>Pending RE Validation Approval</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($gtdocs['fld_re_approval_status'] == 2) {
            ?>
            <div class="time-label">
              <span class="bg-red"><?php echo date("d M. Y", strtotime($gtdocs['fld_re_approval_rej_ts'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-check bg-red"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_re_approval_rej_ts'])); ?></span>
                <h3 class="timeline-header"><a href="#">RE Validation Approval</a> - <?php echo $gtdocs['fld_re_approval_by']; ?></h3>
                <div class="timeline-body">
                  <p><b>Action: </b> Rejected</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($gtdocs['fld_re_approval_status'] == 1) {
            ?>
            <div class="time-label">
              <span class="bg-green"><?php echo date("d M. Y", strtotime($gtdocs['fld_re_approval_ts'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-check bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_re_approval_ts'])); ?></span>
                <h3 class="timeline-header"><a href="#">RE Validation Approval</a> - <?php echo $gtdocs['fld_re_approval_by']; ?></h3>
                <div class="timeline-body">
                  <p><b>Action: </b> Approved</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              }
            ?>

            <!-- CE Portal Sending of Credentials -->
            <?php
              if ($gtdocs['fld_uat_ceportal_sent'] == 0) {
            ?>
            <div class="time-label">
              <span class="bg-gray">Waiting</span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-clock bg-gray"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i></span>
                <h3 class="timeline-header"><a href="#">CE Portal</a></h3>
                <div class="timeline-body">
                  <p>Pending RE Validation Approval</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($gtdocs['fld_uat_ceportal_sent'] == 2) {
            ?>
            <div class="time-label">
              <span class="bg-green"><?php echo date("d M. Y", strtotime($gtdocs['fld_uat_ceportal_sent_ts'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-check bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_uat_ceportal_sent_ts'])); ?></span>
                <h3 class="timeline-header"><a href="#">CE Portal</a> - System (CIC Portal)</h3>
                <div class="timeline-body">
                  <p><b>Action: </b> Access to CE Portal</p>
                  <p><b>User: </b><?php if(!empty($gtdocs['email_c1'])){ echo $gtdocs['fname_c1']. " " .$gtdocs['mname_c1']. " " .$gtdocs['lname_c1']. " " .$gtdocs['extname_c1']; } ?></p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              }
            ?>

            <!-- Adding of Batch Operators (UAT) -->
            <!-- CE Portal Sending of Credentials -->
            <?php
              
              if (!$cuo['fld_oid']) {
            ?>
            <div class="time-label">
              <span class="bg-gray">Waiting</span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-clock bg-gray"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i></span>
                <h3 class="timeline-header"><a href="#">CE Portal</a></h3>
                <div class="timeline-body">
                  <p>Pending Registration of Batch Operators (UAT)</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($cuo['fld_oid']) {
            ?>
            <div class="time-label">
              <span class="bg-green"><?php echo date("d M. Y", strtotime($gtdocs['fld_batch_ops_reg'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-user-plus bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_batch_ops_reg'])); ?></span>
                <h3 class="timeline-header"><a href="#">CE Portal</a> - <?php if(!empty($gtdocs['email_c1'])){ echo $gtdocs['fname_c1']. " " .$gtdocs['mname_c1']. " " .$gtdocs['lname_c1']. " " .$gtdocs['extname_c1']; } ?></h3>
                <div class="timeline-body">
                  <p><b>Action: </b> Added Batch Operators (UAT)</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              }
            ?>

            <!-- Batch Operators Credentials Generations (UAT) -->
            <!-- CE Portal Sending of Credentials -->
            <?php
              if ($gtdocs['fld_batch_uat_creds_status'] == 0) {
            ?>
            <div class="time-label">
              <span class="bg-gray">Waiting</span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-clock bg-gray"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i></span>
                <h3 class="timeline-header"><a href="#">Credentials Generation (UAT)</a></h3>
                <div class="timeline-body">
                  <p>Pending Batch Operators (UAT) Credentials Generation</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              } elseif ($gtdocs['fld_batch_uat_creds_status'] == 1) {
            ?>
            <div class="time-label">
              <span class="bg-green"><?php echo date("d M. Y", strtotime($gtdocs['fld_batch_uat_creds_ts'])); ?></span>
            </div>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div>
              <i class="fa fa-key bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?php echo date("h:ia", strtotime($gtdocs['fld_batch_uat_creds_ts'])); ?></span>
                <h3 class="timeline-header"><a href="#">Credentials Generation (UAT)</a> - <?php  echo $gtdocs['fld_batch_uat_creds_by']; ?></h3>
                <div class="timeline-body">
                  <p><b>Action: </b> Generated Credentials</p>
                </div>
              </div>
            </div>
            <!-- END timeline item -->
            <?php
              }
            ?>
           
            <!-- <div>
              <i class="fas fa-flag bg-green"></i>
            </div> -->
          </div>
        </div>
      </div>
    </div>
    <?php
      }
    ?>
  </div>
  <?php
    }
  ?>

</section>
<!-- /.content -->
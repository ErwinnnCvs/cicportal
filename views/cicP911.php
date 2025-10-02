<?php
$controlno = $_GET['ctrlno'];
$get_entity_details = $dbh4->query("SELECT fld_ctrlno, AES_DECRYPT(fld_tinno, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_tinno,
        AES_DECRYPT(fld_compregno, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_compregno,
        AES_DECRYPT(fld_compregtype, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_compregtype,
        AES_DECRYPT(fld_name, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_name,
        fld_type,
        AES_DECRYPT(fld_addr_number, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_number,
        AES_DECRYPT(fld_addr_street, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_street,
        AES_DECRYPT(fld_addr_subdv, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_subdv,
        AES_DECRYPT(fld_addr_region, MD5(CONCAT(fld_ctrlno, 'RA3019'))) as fld_addr_region,
        AES_DECRYPT(fld_landline, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline,
        AES_DECRYPT(fld_zip, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_zip,
        AES_DECRYPT(fld_provcode, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_provcode,
        AES_DECRYPT(fld_lname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_ar,
        AES_DECRYPT(fld_fname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_ar,
        AES_DECRYPT(fld_mname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_ar,
        AES_DECRYPT(fld_extname_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_extname_ar,
        AES_DECRYPT(fld_position_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_ar,
        AES_DECRYPT(fld_contactno_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_ar,
        AES_DECRYPT(fld_landline_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline_ar,
        AES_DECRYPT(fld_landlinecode_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landlinecode_ar,
        AES_DECRYPT(fld_landlinelocal_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landlinelocal_ar,
        AES_DECRYPT(fld_email_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_ar,
        AES_DECRYPT(fld_upload_ts_ar, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_upload_ts_ar,
        AES_DECRYPT(fld_fname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_c1,
        AES_DECRYPT(fld_mname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_c1,
        AES_DECRYPT(fld_lname_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_c1,
        AES_DECRYPT(fld_position_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_c1,
        AES_DECRYPT(fld_contactno_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_c1,
        AES_DECRYPT(fld_landline_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline_c1,
        AES_DECRYPT(fld_fname_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_fname_c2,
        AES_DECRYPT(fld_mname_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_mname_c2,
        AES_DECRYPT(fld_lname_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_lname_c2,
        AES_DECRYPT(fld_position_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_position_c2,
        AES_DECRYPT(fld_contactno_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_contactno_c2,
        AES_DECRYPT(fld_landline_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_landline_c2,
        AES_DECRYPT(fld_head_fname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_fname,
        AES_DECRYPT(fld_head_mname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_mname,
        AES_DECRYPT(fld_head_lname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_lname,
        AES_DECRYPT(fld_head_extname,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_extname,
        AES_DECRYPT(fld_head_position,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_position,
        AES_DECRYPT(fld_head_email,MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_head_email,
        AES_DECRYPT(fld_address, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_address,
        AES_DECRYPT(fld_email_c1, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_c1,AES_DECRYPT(fld_email_c2, MD5(CONCAT(fld_ctrlno, 'RA3019'))) AS fld_email_c2,
        fld_re_validation_status, fld_dqua_sae_selected FROM tbentities WHERE fld_ctrlno = '".$controlno."' and fld_registration_type = 4");
$ged=$get_entity_details->fetch_array();

$bgy = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".$ged['fld_address']."'");
$b = $bgy->fetch_array();
$cty = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($row['fld_address'], 0, 6), 9, "0", STR_PAD_RIGHT)."'");
$c = $cty->fetch_array();
$prv = $dbh4->query("SELECT * FROM tblocation WHERE fld_geocode = '".str_pad(substr($row['fld_address'], 0, 4), 9, "0", STR_PAD_RIGHT)."'");
$p = $prv->fetch_array();

$selvaloptions = array("1" => "Incomplete documentary content", "2" => "Discrepancy in the content's of the documents submitted", "3" => "Complete and Correct");

if ($ged['fld_ctrlno']) {
  $timestamp = date("Y-m-d H:i:s");
  
  if ($_POST['sbtReject']) {
    // $txt = addslashes($_POST['approval_remarks']);

    // if ($dbh->query("INSERT INTO tbrevalidation_remarks (fld_ctrlno, fld_validation, fld_status, fld_comments, fld_ts, fld_comment_by) VALUES ('".$controlno."', '4', '1', '".$txt."', '".$timestamp."', '".$_SESSION['name']."')")) {
      if ($dbh4->query("UPDATE tbentities SET fld_dqua_cert_status = 2, fld_dqua_cert_rej_ts = '".$timestamp."', fld_dqua_cert_appr_by = '".$_SESSION['name']."' WHERE fld_ctrlno = '".$controlno."'" )) {
        $msg = "Successfuly upated";
        $msgclr = "success";
      }

    // }
  } elseif ($_POST['sbtApprove']) {

    // if ($dbh->query("INSERT INTO tbrevalidation_remarks (fld_ctrlno, fld_validation, fld_status, fld_comments, fld_ts, fld_comment_by) VALUES ('".$controlno."', '5', '1', '".$txt."', '".$timestamp."', '".$_SESSION['name']."')")) {
      if ($dbh4->query("UPDATE tbentities SET fld_dqua_cert_status = 1, fld_dqua_cert_appr_ts = '".$timestamp."', fld_dqua_cert_appr_by = '".$_SESSION['name']."' WHERE fld_ctrlno = '".$controlno."'")) {
        $msg = "Successfuly upated";
        $msgclr = "success";
      }
    // }
  }
?>
<!-- Main content -->
<section class="content">

  <?php

    if ($msg) {
  ?>
  <div class="alert alert-<?php echo $msgclr; ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <?php echo $msg; ?>
  </div>
  <?php
    }

  ?>
  <!-- Default box -->
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Registered Entity Details</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="cic-controlno">CIC Control No.</label>
                
                <p id="cic-controlno"><?php echo $ged['fld_ctrlno']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="provider-code">Provider Code</label>
                <?php
                  if (!empty($ged['fld_provcode'])) {
                ?>
                <p id="provider-code"><?php echo $ged['fld_provcode']; ?></p>
                <?php
                  } else {
                ?>
                <p id="provider-code">NA</p>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="entity-name">Entity Name</label>
                <p id="entity-name"><?php echo $ged['fld_name']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="entity-type">Entity Type</label>
                <p id="entity-type"><?php echo $ent2[$ged['fld_type']]; ?></p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tin">Tax Identification Number</label>
                <p id="tin"><?php echo $ged['fld_tinno']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="reg-no">Company Registration Number</label>
                <p id="reg-no"><?php echo $ged['fld_compregno']; ?></p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="address">Address</label>
                <p id="address"><?php echo $ged['fld_addr_number']." ".$ged['fld_addr_street']." ".$ged['fld_addr_subdv']." ".$b['fld_geotitle']." ".$c['fld_geotitle']." ".$p['fld_geotitle']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="contact-no">Contact No.</label>
                <p id="contact-no"><?php echo $ged['fld_landline']; ?></p>
              </div>
            </div>
          </div>
          <br>
          <h3 class="card-title">Head of Office</h3>
          <br>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-name">Name</label>
                <p id="hof-name"><?php echo $ged['fld_head_fname']. " " .$ged['fld_head_mname']. " " .$ged['fld_head_lname']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-position">Position</label>
                <p id="hof-position"><?php echo $ged['fld_head_position']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Email</label>
                <p id="hof-email"><?php echo $ged['fld_head_email']; ?></p>
              </div>
            </div>
          </div>

          <br>
          <h3 class="card-title">Primary Contact Person</h3>
          <br>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-name">Name</label>
                <p id="hof-name"><?php echo $ged['fld_fname_c1']. " " .$ged['fld_mname_c1']. " " .$ged['fld_lname_c1']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-position">Position</label>
                <p id="hof-position"><?php echo $ged['fld_position_c1']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Email</label>
                <p id="hof-email"><?php echo $ged['fld_email_c1']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Landline</label>
                <p id="hof-email"><?php echo $ged['fld_landline_c1']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Mobile No. </label>
                <p id="hof-email"><?php echo $ged['fld_contactno_c1']; ?></p>
              </div>
            </div>
          </div>

          <br>
          <h3 class="card-title">Secondary Contact Person</h3>
          <br>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-name">Name</label>
                <p id="hof-name"><?php echo $ged['fld_fname_c2']. " " .$ged['fld_mname_c2']. " " .$ged['fld_lname_c2']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-position">Position</label>
                <p id="hof-position"><?php echo $ged['fld_position_c2']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Email</label>
                <p id="hof-email"><?php echo $ged['fld_email_c2']; ?></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Landline</label>
                <p id="hof-email"><?php echo $ged['fld_landline_c2']; ?></p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hof-email">Mobile No. </label>
                <p id="hof-email"><?php echo $ged['fld_contactno_c2']; ?></p>
              </div>
            </div>
          </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Attachments</h3>
        </div>
        <div class="card-body">
          <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
            <li>
              <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

              <div class="mailbox-attachment-info">
                <a href="#" id="seis-document-link" class="mailbox-attachment-name" data-toggle="modal" data-target="#seis-document"><i class="fas fa-paperclip"></i> Cert. of Purging.pdf</a>
                    <span class="mailbox-attachment-size clearfix mt-1">
                      
                      
                    </span>
              </div>
            </li>
          </ul>
          <!-- <p><a href="#" data-toggle="modal" data-target="#seis-document"><i class="fa fa-file-pdf"></i> SEIS Document</a></p> -->

          <div class="modal fade" id="seis-document">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">DQUA Certificate of Purging Document</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <?php
                    $file = $url_docs."DQUA_SAE_CERT_".$controlno."_".$ged['fld_dqua_sae_selected'].".pdf";
                    $handle = @fopen($file, 'r');
                    if (!$handle) {
                  ?>
                  <h2>File not found/File doesn't exist. Please upload the file.</h2>
                  <?php
                    } else {
                  ?>
                  <embed src="<?php echo $url_docs."DQUA_SAE_CERT_".$controlno."_".$ged['fld_dqua_sae_selected'].".pdf"; ?>#toolbar=0" width="750" height="1000"></embed>
                  <?php
                    }
                  ?>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->


        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      

      <div class="card">
        <div class="card-header d-flex p-0">
          <h3 class="card-title p-3">Approval</h3>
        </div><!-- /.card-header -->
        <div class="card-body">
          <form method="post">
            <label>Remarks:</label>
            <textarea class="form-control" name="approval_remarks"></textarea>
            <br>
            <button type="submit" value="1" name="sbtApprove" class="btn btn-success float-right">Approve</button>
            <button type="submit" value="1" name="sbtReject" class="btn btn-danger">Reject</button>
          </form>
        </div><!-- /.card-body -->
      </div>
      <!-- ./card -->

      <div class="card" style="height: 550px; overflow-y: scroll;">
        <div class="card-header">
          <h3 class="card-title">History</h3>
        </div>
        <div class="card-body">
         <!--  <?php
            $get_all_validation_remarks = $dbh->query("SELECT * FROM tbrevalidation_remarks WHERE fld_ctrlno = '".$controlno."' ORDER BY fld_ts DESC");
            while ($gavr=$get_all_validation_remarks->fetch_array()) {
              if ($gavr['fld_validation'] >= 4) {
          ?>
          <div class="callout callout-danger">
            <h5>RE <?php if($gavr['fld_validation'] == 4) { echo "Rejected"; } elseif($gavr['fld_validation'] == 5) { echo "Approved"; } ?></h5>
            <p><b>Remarks</b>: <?php echo $gavr['fld_comments']; ?></p>
            <span>CIC: <?php echo $gavr['fld_comment_by']; ?> | <?php echo date("F d Y - h:ia", strtotime($gavr['fld_ts'])); ?></span>
          </div>
          <?php
              } else {
          ?>
          <div class="callout callout-<?php if($gavr['fld_validation'] == 1) { echo "warning"; } elseif($gavr['fld_validation'] == 2) { echo "danger"; } elseif($gavr['fld_validation'] == 3) { echo "success"; }?>">
            <h5><?php echo $selvaloptions[$gavr['fld_validation']]; ?></h5>
            <p><b>Remarks</b>: <?php echo $gavr['fld_comments']; ?></p>
            <span>CIC: <?php echo $gavr['fld_comment_by']; ?> | <?php echo date("F d Y - h:ia", strtotime($gavr['fld_ts'])); ?></span>
          </div>
          <?php
              }
            }
          ?> -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
  </div>

</section>
<!-- /.content -->
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
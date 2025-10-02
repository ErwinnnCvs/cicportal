<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
if($_POST['exportDispute']){
  include('pdf/dispute/exportDispute.php');
}


  if ($_POST['sbtSubjectCode']) {
    $subjcode = trim($_POST['subject_code']);
    // $comment = $_POST['input_subj_comment'];

    if (strlen($subjcode) > 9) {
      $msg = "Subject Code must not be greater than 9 digit.";
      $msgclr = "danger";
    } else {
      if (ctype_alnum($subjcode)) {
        if ($dbh2->query("UPDATE contract SET fld_subjcode = AES_ENCRYPT('".$subjcode."', CONCAT(fld_id,'G3n13')), fld_subjcode_ts = '".date("Y-m-d H:i:s")."', fld_subjcode_remarks = '".addslashes($_POST['subject_remarks'])."', fld_subjcode_by = '".$_SESSION['name']."' WHERE fld_id = '".$_POST['subj_ID']."'")) {
          if ($dbh2->query("UPDATE subject SET fld_subjcode = AES_ENCRYPT('".$subjcode."', CONCAT(fld_Birthday,'G3n13')), fld_subjcode_ts = '".date("Y-m-d H:i:s")."' WHERE fld_TRN = '".$_POST['subj_TRN']."' and AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) IS NULL")) {
            $msg = "Subject Code successfully added.";
            $msgclr = "success";
          } else {
            $msg = "Error adding Subject Code.";
            $msgclr = "danger";
          }
        } else {
            $msg = "Error adding Subject Code.";
            $msgclr = "danger";
        }
      } else {
        $msg = "Alphabetic Characters are not allowed. Please use numbers only.";
        $msgclr = "danger";
      }
    }
  }
?>
<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          Completed
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=142&sid=2&rid=2">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=142&sid=1&rid=1">Completed</a></li>
          <li class="dropdown-item"><a href="main.php?nid=142&sid=0&rid=0">For Verification</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
     <form method="post">
      <table class="table table-bordered" id="disputeVerificationCompleted">
        <thead>
          <tr>
            <th width="15"><center>#</center></th>
            <th width="100">Date Filed</th>
            <th width="200">TRN</th>
            <th width="300">Name</th>
            <th width="100">Birth Date</th>
            <th width="400">Financial Institution</th>
            <th>DRCP Last Submission Timestamp</th>
            <th>Remarks</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $c = 0;
            $get_filed_disputes = $dbh2->query("SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_status, fld_contractType, fld_complaint, fld_description, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode,fld_provsubj_number,fld_provcontr_number,fld_dispute_details,fld_filename, fld_subjcode_remarks, fld_dispute_verification_ts, fld_se_dispdetails_ts FROM contract WHERE fld_id > 9434 and fld_dispute_verification_status = 1 and fld_disp_status >= 1 ORDER BY fld_id DESC ");
            while ($gfd = $get_filed_disputes->fetch_array()) {
              $c++;

              $id = $gfd['fld_id'];
              $subject = $dbh2->query("SELECT fld_TRN, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject WHERE fld_TRN = '".$gfd['fld_TRN']."' ORDER BY subjcode ASC");
              $s=$subject->fetch_array();

              // if ($gfd['fld_status'] == 4) {
              //   $name = $gfd['fld_name'];
              // } else {
              //   $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
              //   $gcn=$get_company_name->fetch_array();
              //   $name = $gcn['name'];
              // }

              if ($gfd['fld_status'] == 4) {
                $name = $gfd['fld_name'];

                if ($gfd['fld_prov'] == 'RB002210') {
                  $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
                  $gcn=$get_company_name->fetch_array();
                  $name = $gcn['name'];
                } 
              } 
              else {
                $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
                $gcn=$get_company_name->fetch_array();
                $name = $gcn['name'];
              }

          ?>
          <tr>           
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $s['fld_DateFilled']; ?></td>
            <td><?php echo $gfd['fld_TRN']; ?></td>
            <!-- <td><?php echo $s['firstname']. " " .$s['middlename']. " " .$s['lastname']; ?> </td> -->
            <td><a href="#" data-toggle="modal" data-target="#modal_dispute_details<?php echo $gfd['fld_id']; ?>"><?php echo $s['firstname']. " " .$s['middlename']. " " .$s['lastname']; ?></a>
              <div class="modal fade" id="modal_dispute_details<?php echo $gfd['fld_id']; ?>" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title"><?php echo $gfd['fld_TRN']. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                      <label>Loan Name:</label><br>
                      <p><?php echo $gfd['fld_contractType']; ?></p>
                      <label>Dispute:</label><br>
                      <p><?php echo $gfd['fld_complaint']; ?></p>
                      <label>Description:</label><br>
                      <p><?php echo $gfd['fld_description']; ?></p>
                      <label>Provider Contract Number:</label><br>
                      <p><?php echo $gfd['fld_provsubj_number']; ?></p>
                      <label>Provider Subject Number:</label><br>
                      <p><?php echo $gfd['fld_provcontr_number']; ?></p>
                      <label>Filename:</label><br>
                      <p><?php echo $gfd['fld_filename']; ?></p>
                      <label>Record Count:</label><br>
                      <p><?php echo $gfd['fld_record_count']; ?></p>
                      <label>DRCP Remarks:</label><br>
                      <p><?php echo $gfd['fld_dispute_remarks']; ?></p>
                      <label>Changes</label><br>
                      <p>
                        <?php 
                          if($s['changes']){ 
                            $subval = explode("|", $s['changes']);
                            foreach ($subval as $key) {
                              echo $subject_arr[$key]."<br>";
                            }
                          } else { 
                            echo "N/A";
                          } 
                        ?>
                      </p>
                      <?php
                        if ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 8) {
                      ?>
                      <label>ID NUMBER(S)</label>
                      <ul>
                        <?php
                            if (trim($s['SSS'])) {
                        ?>
                        <li><b>SSS:</b> <?php echo $s['SSS']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['GSIS'])) {
                        ?>
                        <li><b>GSIS:</b> <?php echo $s['GSIS']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['TIN'])) {
                        ?>
                        <li><b>TIN:</b> <?php echo $s['TIN']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['UMID'])) {
                        ?>
                        <li><b>UMID:</b> <?php echo $s['UMID']; ?></li>
                        <?php
                            }
                        ?>

                        <?php
                            if (trim($s['DL'])) {
                        ?>
                        <li><b>DL:</b> <?php echo $s['DL']; ?></li>
                        <?php
                            }
                        ?>
                      </ul>

                      <?php
                        }
                      ?>
                      <hr>
                      <label>Address</label>
                      <br>
                      <?php
                      $get_address_from_appt = $dbh6->query("SELECT AES_DECRYPT(fld_street1, CONCAT(fld_refID, 'RA9510')) as fld_street1, AES_DECRYPT(fld_subd1, CONCAT(fld_refID, 'RA9510')) as fld_subd1, AES_DECRYPT(fld_brngy1, CONCAT(fld_refID, 'RA9510')) as fld_brngy1, fld_postal1, fld_city1, AES_DECRYPT(fld_street2, CONCAT(fld_refID, 'RA9510')) as fld_street2, AES_DECRYPT(fld_subd2, CONCAT(fld_refID, 'RA9510')) as fld_subd2, AES_DECRYPT(fld_brngy2, CONCAT(fld_refID, 'RA9510')) as fld_brngy2, fld_postal2, fld_city2  FROM tblappointment WHERE fld_refID = '".$gfd['fld_TRN']."'");
                      $gafappt=$get_address_from_appt->fetch_array();
                      ?>
                      <p><?php echo $gafappt['fld_street1']. " " .$gafappt['fld_subd1']. " " .$gafappt['fld_brngy1']. " " .$gafappt['fld_city1']. " " .$gafappt['fld_postal1']; ?></p>
                      <p><?php echo $gafappt['fld_street2']. " " .$gafappt['fld_subd2']. " " .$gafappt['fld_brngy2']. " " .$gafappt['fld_city2']. " " .$gafappt['fld_postal2']; ?></p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

            </td>
            <td><?php echo $s['fld_Birthday']; ?></td>
            <!-- <td><?php echo $s['contact']; ?></td> -->
            <td><?php echo $name; ?></td>
            <td><?php echo $gfd['fld_se_dispdetails_ts']; ?></td>
            <td><?php echo $gfd['fld_subjcode_remarks']; ?></td>
            <td>
              
          <!--     <a target="_blank" href="pdf/dispute/exportDispute_i.php?dispute=<?php echo $id; ?>" class="btn btn-primary">
                View PDF
              </a> -->
                  <a target="_blank" href="pdf/dispute/exportDispute_new.php?dispute=<?php echo $id; ?>" class="btn btn-primary">
                 View PDF
              </a>

              <?php 
              if($_SESSION['user_id'] == 139){
              ?>

                <a target="_blank" href="pdf/dispute/exportDispute_new.php?dispute=<?php echo $id; ?>" class="btn btn-primary">
                 View PDF
              </a>

              <?php
              }

              ?>

            </td>

          </tr>
          </form>
          <?php
            }
          ?>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
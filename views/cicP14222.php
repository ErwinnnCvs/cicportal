<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

  if ($_POST['sbtSupplyDetails']) {
    if ($_POST['se_submission_confirmation'][$_POST['fld_id']] == "isConfirmed") {
      // echo $_POST['fld_id'];
      if ($dbh2->query("UPDATE contract SET fld_provsubj_number = '".$_POST['provider_subject_number']."', fld_provcontr_number = '".$_POST['provider_contract_number']."', fld_dispute_details = '".$_POST['dispute_details']."', fld_filename = '".$_POST['filename']."', fld_submission_confirmation = '1', fld_dispute_verification_status = 1, fld_dispute_verification_ts = '".date("Y-m-d H:i:s")."', fld_dispute_verification_by = '".$_SESSION['name']."' WHERE fld_id = '".$_POST['fld_id']."'")) {
        $msg = "Successfully saved!";
        $msgclr = "success";
      }
    } else {
      echo "not confirmed";
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
          Pending
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-item"><a href="main.php?nid=142&sid=2&rid=2">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=142&sid=0&rid=0">For Verification</a></li>
          <li class="dropdown-item"><a href="main.php?nid=142&sid=1&rid=1">Completed</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered" id="disputeVerificationPending">
        <thead>
          <tr>
            <th width="15"><center>#</center></th>
            <th width="100">Date Filed</th>
            <th width="200">TRN</th>
            <th width="300">Name</th>
            <th width="100">Birth Date</th>
            <th width="400">Financial Institution</th>
            <th width="150"><center>Remarks</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $c = 0;

     

            $get_filed_disputes = $dbh2->query("SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_status, fld_contractType, fld_complaint, fld_description, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode, fld_subjcode_remarks FROM contract WHERE fld_id > 9434 and fld_dispute_verification_status = 0 and fld_disp_status = 0 ORDER BY fld_id desc  ");


            while ($gfd = $get_filed_disputes->fetch_array()) {






              $c++;
              $subject = $dbh2->query("SELECT fld_TRN, AES_DECRYPT(fld_Fname, CONCAT(fld_Birthday,'G3n13')) AS firstname, fld_Birthday, AES_DECRYPT(fld_Mname, CONCAT(fld_Birthday,'G3n13')) AS middlename, AES_DECRYPT(fld_Lname, CONCAT(fld_Birthday,'G3n13')) AS lastname, AES_DECRYPT(fld_Contact, CONCAT(fld_Birthday,'G3n13')) AS contact, fld_DateFilled, changes, AES_DECRYPT(fld_SSS, CONCAT(fld_Birthday,'G3n13')) AS SSS, AES_DECRYPT(fld_GSIS, CONCAT(fld_Birthday,'G3n13')) AS GSIS, AES_DECRYPT(fld_TIN, CONCAT(fld_Birthday,'G3n13')) AS TIN, AES_DECRYPT(fld_UMID, CONCAT(fld_Birthday,'G3n13')) AS UMID, AES_DECRYPT(fld_DL, CONCAT(fld_Birthday,'G3n13')) AS DL, AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) AS subjcode FROM subject WHERE fld_TRN = '".$gfd['fld_TRN']."' ORDER BY subjcode ASC");
              $s=$subject->fetch_array();

              if ($gfd['fld_status'] == 4) {
                $name = $gfd['fld_name'];
              } else {
                $get_company_name = $dbh4->query("SELECT AES_DECRYPT(fld_name, md5(CONCAT(fld_ctrlno, 'RA3019'))) as name FROM tbentities WHERE AES_DECRYPT(fld_provcode, md5(CONCAT(fld_ctrlno, 'RA3019'))) = '".$gfd['fld_prov']."'");
                $gcn=$get_company_name->fetch_array();
                $name = $gcn['name'];
              }

          ?>
          <tr>           
            <td><center><?php echo $c; ?></center></td>
            <td><?php echo $s['fld_DateFilled']; ?></td>
            <td><?php echo $gfd['fld_TRN']; ?></td>
            <td><?php echo $s['firstname']. " " .$s['middlename']. " " .$s['lastname']; ?></td>
            <td><?php echo $s['fld_Birthday']; ?></td>
            <!-- <td><?php echo $s['contact']; ?></td> -->
            <td><?php echo $name; ?></td>
            <!-- <td>
              <center>
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal_enter_details<?php echo $gfd['fld_id']; ?>">Update</button>

              </center>
              <div class="modal fade" id="modal_enter_details<?php echo $gfd['fld_id']; ?>" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Supply Details for <?php echo $gfd['fld_TRN']. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <form method="post">
                    <div class="modal-body">
                      <input type="hidden" name="fld_id" value="<?php echo $gfd['fld_id']; ?>">
                      <div class="form-group">
                        <label for="provider_subject_number">Provider Subject Number</label>
                        <input type="text" class="form-control" id="provider_subject_number" name="provider_subject_number" placeholder="Enter Provider Subject Number" required>
                      </div>

                      <div class="form-group">
                        <label for="provider_contract_number">Provider Contract Number</label>
                        <input type="text" class="form-control" id="provider_contract_number" name="provider_contract_number" placeholder="Enter Provider Contract Number" required>
                      </div>

                      <div class="form-group">
                        <label>Dispute Details</label>
                        <textarea class="form-control" rows="5" name="dispute_details" placeholder="Enter details"></textarea>
                      </div>

                      <div class="form-group">
                        <label for="filename">Update File</label>
                        <input type="text" class="form-control" id="filename" name="filename" placeholder="Enter Filename" required>
                      </div>

                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="se_submission_confirmation[<?php echo $gfd['fld_id']; ?>]" name="se_submission_confirmation[<?php echo $gfd['fld_id']; ?>]" value="isConfirmed" required>
                        <label for="se_submission_confirmation[<?php echo $gfd['fld_id']; ?>]" class="custom-control-label">SE Submission Confirmation</label>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="submit" value="1" name="sbtSupplyDetails" class="btn btn-primary pull-left">Save</button>
                    </div>
                    </form>
                  </div>
                  <!-- /.modal-content
                </div>
                <!-- /.modal-dialog -->
              </div>
            </td> 
            <td><?php  if($gfd['fld_subjcode_remarks'] != NULL){ echo $gfd['fld_subjcode_remarks'];}else{ echo "N/A";}; ?></td>

          </tr>
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


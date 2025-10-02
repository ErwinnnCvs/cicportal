<?php
  if ($_POST['sbtSubjectCode']) {
    $subjcode = trim($_POST['subject_code']);
    // $comment = $_POST['input_subj_comment'];

    if (strlen($subjcode) > 9) {
      $msg = "Subject Code must not be greater than 9 digit.";
      $msgclr = "danger";
    } else {
      if (ctype_alnum($subjcode)) {
        $remarks = "<hr><small>".date('Y-m-d H:i:s')."</small><br><b>".$_SESSION['name']."</b><br>".$_POST['subject_remarks']."<br>";
        $get_previous_remarks = $dbh2->query("SELECT fld_subjcode_remarks FROM contract WHERE fld_id = '".$_POST['fld_id']."'");
        $gpr=$get_previous_remarks->fetch_array();

        $new_remarks = addslashes($remarks.$gpr['fld_subjcode_remarks']);

        if ($dbh2->query("UPDATE contract SET fld_subjcode = AES_ENCRYPT('".$subjcode."', CONCAT(fld_id,'G3n13')), fld_subjcode_ts = '".date("Y-m-d H:i:s")."', fld_subjcode_remarks = '".$new_remarks."', fld_subjcode_by = '".$_SESSION['name']."', fld_disp_status = 1 WHERE fld_id = '".$_POST['fld_id']."'")) {
          if ($dbh2->query("UPDATE subject SET fld_subjcode = AES_ENCRYPT('".$subjcode."', CONCAT(fld_Birthday,'G3n13')), fld_subjcode_ts = '".date("Y-m-d H:i:s")."' WHERE fld_TRN = '".$_POST['subj_TRN']."' and AES_DECRYPT(fld_subjcode, CONCAT(fld_Birthday,'G3n13')) IS NULL")) {
            $msg = "Subject Code successfully added.";
            $msgclr = "success";
          } else {
            $msg = "Error adding Subject Code.2";
            $msgclr = "danger";
          }
        } else {
            $msg = "Error updating.";
            $msgclr = "danger";
        }
      } else {
        $msg = "Alphabetic Characters are not allowed. Please use numbers only.";
        $msgclr = "danger";
      }
    }
  }

  $subject_arr = array("01"=>"Main Address","02"=>"Civil Status","03"=>"Secondary Address","04"=>"TIN Number","05"=>"Cars Owned","06"=>"SSS Number","07"=>"Number of Dependents","08"=>"GSIS Number","09"=>"Gross Income","10"=>"Additional Phone", "11"=>"Company Trade Name", "12"=>"Main Phone","13"=>"Company Main Address","14"=>"Name");
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
          <li class="dropdown-item"><a href="main.php?nid=28&sid=0&rid=0">Pending</a></li>
          <li class="dropdown-item"><a href="main.php?nid=28&sid=1&rid=1">Completed</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
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
      <table class="table table-bordered" id="validate_dispute_pending">
        <thead>
          <tr>
            <th width="15"><center>#</center></th>
            <th width="100">Date Filed</th>
            <th width="200">TRN</th>
            <th width="300">Name</th>
            <th width="100">Birth Date</th>
            <th width="400">Financial Institution</th>
            <th width="150"><center>Action</center></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $get_filed_disputes = $dbh2->query("SELECT fld_id, fld_TRN, fld_prov, fld_name, fld_status, fld_contractType, fld_complaint, fld_description, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) AS subjcode,fld_provsubj_number,fld_provcontr_number,fld_dispute_details,fld_filename, fld_subjcode_remarks, AES_DECRYPT(fld_subjcode, CONCAT(fld_id,'G3n13')) as fld_subjcode FROM contract WHERE (fld_id > 51 and fld_id <= 9434) and fld_dispute_verification_status = 1 and fld_disp_status = 0 ORDER BY fld_id DESC");
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
                      <?php
                      if ($s['changes']) {
                      ?>
                      <h5><b>Subject</b></h5>
                      <label>Selected information:</label><br>
                      <p>
                        <?php 
                        
                          $subval = explode("|", $s['changes']);
                            foreach ($subval as $key) {
                              echo $subject_arr[$key]."<br>";
                            }
                        ?>
                      </p>
                      <hr>
                      <?php
                      }
                      ?>
                      

                      <?php
                      if ($gfd['fld_contractType']) {
                      ?>
                      <h5><b>Contract</b></h5>
                      <label>Loan Name:</label><br>
                      <p><?php echo $gfd['fld_contractType']; ?></p>
                      <label>Dispute:</label><br>
                      <p><?php echo $gfd['fld_complaint']; ?></p>
                      <label>Description:</label><br>
                      <p><?php echo $gfd['fld_description']; ?></p>
                      
                      <br>
                      <hr>
                      <?php
                      }
                      ?>
                      

                      <h5><b>Personal Information</b></h5>
                      <?php
                        if ($_SESSION['usertype'] == 0 || $_SESSION['usertype'] == 8 || $_SESSION['user_id'] == 115 || $_SESSION['user_id'] == 174|| $_SESSION['user_id'] == 189) {
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
            <td>
              <center>
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal_enter_subjcode<?php echo $gfd['fld_id']; ?>">Enter Subject Code</button>
              </center>
              <div class="modal fade" id="modal_enter_subjcode<?php echo $gfd['fld_id']; ?>" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Enter Subject Code for <?php echo $gfd['fld_TRN']. " - ".$name ; ?></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    </div>
                    <form method="post">
                    <div class="modal-body">
                      <input type="hidden" name="fld_id" value="<?php echo $gfd['fld_id']; ?>">
                      <input type="hidden" name="subj_TRN" value="<?php echo $gfd['fld_TRN']; ?>">
                      <p>Provider Subject Number: <b><?php echo $gfd['fld_provsubj_number']; ?></b></p>
                      <p>Provider Contract Number: <b><?php echo $gfd['fld_provcontr_number']; ?></b></p>
                      <p>Dispute Details: <b><?php echo $gfd['fld_dispute_details']; ?></b></p>
                      <p>Filename(s): <b><?php echo $gfd['fld_filename']; ?></b></p>

                      <?php
                        if (empty($gfd['fld_subjcode'])) {
                      ?>
                      <div class="form-group">
                        <label for="subject_code">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Enter Subject Code" required>
                      </div>
                      <?php
                        } else {
                      ?>
                      <input type="text" class="form-control" value="<?php echo $gfd['fld_subjcode']; ?>" disabled>
                      <input type="hidden" class="form-control" id="subject_code" name="subject_code" placeholder="Enter Subject Code" value="<?php echo $gfd['fld_subjcode']; ?>">
                      <?php
                        }
                      ?>

                      <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" rows="5" name="subject_remarks" placeholder="Enter details"></textarea>
                      </div>
                      <p>
                        <?php echo $gfd['fld_subjcode_remarks']; ?>
                      </p>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="submit" value="1" name="sbtSubjectCode" class="btn btn-primary pull-left">Save</button>
                    </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
            </td>
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